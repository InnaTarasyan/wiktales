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
        
        foreach ($lines as $l) {
            $t = preg_replace('/\s+/u', ' ', $l['text']);
            if ($t === '') { 
                if ($inToc && count($buffer) > 0) break; 
                continue; 
            }
            
            // Check if this line starts with a number followed by a dot (like "1.", "2.", etc.)
            if (preg_match('/^\d+\.\s*(.+)$/u', $t, $matches)) {
                $inToc = true;
                // Clean the title by removing trailing dots and page numbers
                // Remove any trailing dots, spaces, and page numbers
                $cleanTitle = preg_replace('/[\.\s]*\d+[\.\s]*$/u', '', $matches[1]);
                // Remove any remaining trailing dots, spaces, and other punctuation
                $cleanTitle = preg_replace('/[\.\s\p{P}]+$/u', '', $cleanTitle);
                $cleanTitle = trim($cleanTitle);
                $buffer[] = $cleanTitle;
                continue;
            } else {
                if ($inToc) break;
            }
        }
        
        // Process the TOC entries
        foreach ($buffer as $i => $title) {
            $toc[] = [ 
                'title' => trim($title), 
                'order' => $i + 1 
            ];
        }

        // Sections: split by Heading 1
        $sections = [];
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


