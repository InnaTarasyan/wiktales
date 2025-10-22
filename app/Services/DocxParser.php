<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use ZipArchive;
use Illuminate\Support\Str;

class DocxParser
{
    public function parse(string $docxPath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($docxPath) !== true) {
            throw new \RuntimeException('Unable to open DOCX file: ' . $docxPath);
        }

        $documentXml = null;
        $relsXml = null;
        $firstImage = null;
        $firstImageExt = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === 'word/document.xml') {
                $documentXml = $zip->getFromIndex($i);
            } elseif ($name === 'word/_rels/document.xml.rels') {
                $relsXml = $zip->getFromIndex($i);
            } elseif (str_starts_with($name, 'word/media/') && $firstImage === null) {
                $firstImage = $zip->getFromIndex($i);
                $firstImageExt = pathinfo($name, PATHINFO_EXTENSION) ?: 'jpg';
            }
        }

        if ($documentXml === null) {
            $zip->close();
            throw new \RuntimeException('document.xml not found in DOCX');
        }

        $cover = null;
        if ($firstImage !== null) {
            $cover = [
                'filename' => 'cover.' . $firstImageExt,
                'extension' => $firstImageExt,
                'data' => $firstImage,
            ];
        }

        $dom = new DOMDocument();
        $dom->loadXML($documentXml);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $paragraphs = $xpath->query('//w:body/w:p');
        $lines = [];
        foreach ($paragraphs as $p) {
            $textNodes = $xpath->query('.//w:t', $p);
            $line = '';
            foreach ($textNodes as $t) {
                $line .= $t->textContent;
            }
            $style = $xpath->evaluate('string(.//w:pPr/w:pStyle/@w:val)', $p);
            $lines[] = [
                'text' => trim($line),
                'style' => $style ?: null,
            ];
        }

        $title = null;
        foreach ($lines as $l) {
            if ($l['style'] === 'Title' || $l['style'] === 'Heading1' || $l['style'] === 'Heading 1') {
                if ($l['text'] !== '') { $title = $l['text']; break; }
            }
        }
        if ($title === null) {
            foreach ($lines as $l) { if ($l['text'] !== '') { $title = $l['text']; break; } }
        }

        // TOC heuristic: contiguous lines with numbered sections (1., 2., etc.) followed by titles
        $toc = [];
        $inToc = false;
        $buffer = [];
        $foundContent = false;
        
        foreach ($lines as $l) {
            $t = preg_replace('/\s+/u', ' ', $l['text']);
            if ($t === '') { 
                continue; 
            }
            
            // Check if this line starts with a number followed by a dot (like "1.", "2.", etc.)
            if (preg_match('/^\d+\.\s*(.+)$/u', $t, $matches)) {
                $inToc = true;
                // Clean the title by removing trailing dots, spaces, and page numbers
                // Remove any trailing dots, spaces, and page numbers at the end
                $cleanTitle = preg_replace('/[\.\s]*\d+[\.\s]*$/u', '', $matches[1]);
                // Remove any remaining trailing dots, spaces, and other punctuation
                $cleanTitle = preg_replace('/[\.\s\p{P}]+$/u', '', $cleanTitle);
                $cleanTitle = trim($cleanTitle);
                $buffer[] = $cleanTitle;
                continue;
            } else {
                // If we're in TOC and encounter a non-numbered line, check if it's still part of TOC
                if ($inToc) {
                    // If it's not a numbered line and not empty, we're probably done with TOC
                    if (trim($t) !== '' && !preg_match('/^[А-ЯЁ\s]+$/u', $t)) {
                        $foundContent = true;
                        break;
                    }
                }
            }
        }
        
        // Process the TOC entries, avoiding duplicates
        $seenTitles = [];
        $order = 1;
        foreach ($buffer as $title) {
            $normalizedTitle = mb_strtolower(trim($title));
            if (!in_array($normalizedTitle, $seenTitles)) {
                $seenTitles[] = $normalizedTitle;
                $toc[] = [ 
                    'title' => trim($title), 
                    'order' => $order++
                ];
            }
        }

        // Sections: create sections based on TOC entries
        $sections = [];
        
        if (!empty($toc)) {
            // Create sections based on TOC entries, avoiding duplicates
            $seenTitles = [];
            foreach ($toc as $tocEntry) {
                $normalizedTitle = mb_strtolower(trim($tocEntry['title']));
                if (!in_array($normalizedTitle, $seenTitles)) {
                    $seenTitles[] = $normalizedTitle;
                    $anchor = Str::slug($tocEntry['title']);
                    $sections[] = [
                        'title' => $tocEntry['title'],
                        'anchor' => $anchor,
                        'body_html' => '',
                        'body_text' => ''
                    ];
                }
            }
            
            // Now try to find content for each section by looking for section titles in the text
            $currentSectionIndex = 0;
            $inContent = false;
            $skipToc = false;
            $foundFirstSection = false;
            
            foreach ($lines as $l) {
                $t = trim($l['text']);
                if ($t === '') continue;
                
                // Skip TOC section
                if ($t === 'СОДЕРЖАНИЕ') {
                    $skipToc = true;
                    continue;
                }
                
                if ($skipToc && preg_match('/^\d+\.\s*(.+)$/u', $t)) {
                    continue; // Skip TOC entries
                }
                
                if ($skipToc && !preg_match('/^\d+\.\s*(.+)$/u', $t) && $t !== '') {
                    $skipToc = false; // We're past the TOC
                }
                
                if ($skipToc) continue;
                
                // Check if this line matches a section title (look for numbered titles like "1.ПРОДЕЛКИ ФАВНА")
                $foundSection = false;
                if (preg_match('/^\d+\.\s*(.+)$/u', $t, $matches)) {
                    $sectionTitle = trim($matches[1]);
                    // Find matching section by comparing with TOC entries
                    for ($i = 0; $i < count($sections); $i++) {
                        if (mb_strtolower($sectionTitle) === mb_strtolower($sections[$i]['title'])) {
                            $currentSectionIndex = $i;
                            $inContent = true;
                            $foundSection = true;
                            $foundFirstSection = true;
                            break;
                        }
                    }
                }
                
                if (!$foundSection && $inContent && $currentSectionIndex < count($sections)) {
                    // This is content for the current section
                    $sections[$currentSectionIndex]['body_text'] .= 
                        ($sections[$currentSectionIndex]['body_text'] ? "\n" : '') . $t;
                    $sections[$currentSectionIndex]['body_html'] .= 
                        '<p>' . e($t) . '</p>';
                }
            }
        } else {
            // Fallback: try to find sections by looking for numbered titles in the text
            $current = null;
            foreach ($lines as $l) {
                if (in_array($l['style'], ['Heading1', 'Heading 1'])) {
                    if ($current !== null) { $sections[] = $current; }
                    $anchor = Str::slug($l['text']);
                    $current = [ 'title' => $l['text'], 'anchor' => $anchor, 'body_html' => '', 'body_text' => '' ];
                    continue;
                }
                if ($current !== null && $l['text'] !== '') {
                    $current['body_text'] .= ($current['body_text'] ? "\n" : '') . $l['text'];
                    $current['body_html'] .= '<p>' . e($l['text']) . '</p>';
                }
            }
            if ($current !== null) { $sections[] = $current; }
        }

        // Fallback: if no sections detected, create one section with all content minus title
        if (count($sections) === 0) {
            $contentLines = array_map(fn($l) => $l['text'], $lines);
            if ($title) {
                // remove first occurrence of title line
                $removed = false;
                $contentLines = array_values(array_filter($contentLines, function ($t) use ($title, &$removed) {
                    if (!$removed && trim($t) === trim($title)) { $removed = true; return false; }
                    return true;
                }));
            }
            $bodyText = trim(implode("\n", array_filter($contentLines)));
            $bodyHtml = '';
            foreach (array_filter($contentLines) as $t) {
                $bodyHtml .= '<p>' . e($t) . '</p>';
            }
            $sections[] = [
                'title' => $title ?? pathinfo($docxPath, PATHINFO_FILENAME),
                'anchor' => Str::slug($title ?? pathinfo($docxPath, PATHINFO_FILENAME)),
                'body_text' => $bodyText,
                'body_html' => $bodyHtml,
            ];
        }

        $zip->close();

        return [
            'title' => $title ?? pathinfo($docxPath, PATHINFO_FILENAME),
            'cover' => $cover,
            'toc' => $toc,
            'sections' => $sections,
            'meta' => [],
        ];
    }
}


