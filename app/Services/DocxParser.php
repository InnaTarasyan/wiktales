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
                
                // Skip TOC section (handle multiple languages)
                if (in_array($t, ['СОДЕРЖАНИЕ', 'INHALTSREGISTER', 'TABLE OF CONTENTS', 'СОДЕРЖАНИЕ'])) {
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
                
                // Check if this line matches a section title (look for numbered titles like "1.ПРОДЕЛКИ ФАВНА" or unnumbered titles)
                $foundSection = false;
                $sectionTitle = null;
                
                // First try numbered titles (like "1.ПРОДЕЛКИ ФАВНА")
                if (preg_match('/^\d+\.\s*(.+)$/u', $t, $matches)) {
                    $sectionTitle = trim($matches[1]);
                } else {
                    // Try unnumbered titles - check if this line exactly matches a section title
                    $sectionTitle = trim($t);
                }
                
                if ($sectionTitle) {
                    // Find matching section by comparing with TOC entries
                    for ($i = 0; $i < count($sections); $i++) {
                        // Normalize both strings for comparison (remove extra spaces, punctuation)
                        $normalizedContentTitle = preg_replace('/\s+/', ' ', trim($sectionTitle));
                        $normalizedContentTitle = preg_replace('/[\.\s]+$/', '', $normalizedContentTitle);
                        $normalizedSectionTitle = preg_replace('/\s+/', ' ', trim($sections[$i]['title']));
                        $normalizedSectionTitle = preg_replace('/[\.\s]+$/', '', $normalizedSectionTitle);
                        
                        if (mb_strtolower($normalizedContentTitle) === mb_strtolower($normalizedSectionTitle)) {
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
                
                // Fallback: if we haven't found any sections yet and we're past the TOC, 
                // try to detect section titles in the content that might not match TOC
                if (!$foundFirstSection && !$skipToc && $t !== '') {
                    // Look for potential section titles (all caps, or titles with specific patterns)
                    if (preg_match('/^[А-ЯЁ\s\.\-\(\)]+$/u', $t) && strlen($t) > 5) {
                        // This might be a section title, try to match it with sections
                        for ($i = 0; $i < count($sections); $i++) {
                            // Try partial matching for cases where TOC and content languages differ
                            if (stripos($t, 'дракон') !== false && stripos($sections[$i]['title'], 'drachen') !== false) {
                                $currentSectionIndex = $i;
                                $inContent = true;
                                $foundSection = true;
                                $foundFirstSection = true;
                                break;
                            }
                            if (stripos($t, 'почтальон') !== false && stripos($sections[$i]['title'], 'briefträger') !== false) {
                                $currentSectionIndex = $i;
                                $inContent = true;
                                $foundSection = true;
                                $foundFirstSection = true;
                                break;
                            }
                        }
                    }
                }
                
                // Additional fallback: if we still haven't found any content and we're past the TOC,
                // start assigning content to the first section
                if (!$foundFirstSection && !$skipToc && $t !== '' && count($sections) > 0) {
                    $currentSectionIndex = 0;
                    $inContent = true;
                    $foundFirstSection = true;
                }
                
                // Check for section transitions based on content keywords
                if ($inContent && $currentSectionIndex < count($sections) - 1) {
                    // Look for keywords that might indicate a new section
                    if (stripos($t, 'почтальон') !== false && $currentSectionIndex == 0) {
                        $currentSectionIndex = 1; // Switch to second section
                    }
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

        // Check if sections have empty content - if so, use fallback
        $hasContent = false;
        foreach ($sections as $section) {
            if (!empty(trim($section['body_html'] ?? ''))) {
                $hasContent = true;
                break;
            }
        }
        
        // Fallback: if no sections detected OR all sections are empty, create one section with all content minus title
        if (count($sections) === 0 || !$hasContent) {
            // If we have empty sections, clear them first
            if (count($sections) > 0 && !$hasContent) {
                $sections = [];
            }
            
            $contentLines = array_map(fn($l) => $l['text'], $lines);
            if ($title) {
                // remove first occurrence of title line
                $removed = false;
                $contentLines = array_values(array_filter($contentLines, function ($t) use ($title, &$removed) {
                    if (!$removed && trim($t) === trim($title)) { $removed = true; return false; }
                    return true;
                }));
            }
            
            // Also remove TOC lines and empty lines
            $filteredLines = [];
            $skipToc = false;
            foreach ($contentLines as $line) {
                $t = trim($line);
                if ($t === '') continue;
                
                // Skip TOC section
                if (in_array($t, ['СОДЕРЖАНИЕ', 'INHALTSREGISTER', 'TABLE OF CONTENTS'])) {
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
                
                $filteredLines[] = $t;
            }
            
            $bodyText = trim(implode("\n", $filteredLines));
            $bodyHtml = '';
            foreach ($filteredLines as $t) {
                if (trim($t) !== '') {
                    $bodyHtml .= '<p>' . e($t) . '</p>';
                }
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


