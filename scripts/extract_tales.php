<?php
declare(strict_types=1);

// Simple extractor for DOCX tales in storage/tales
// Requires phpoffice/phpword (already in vendor)

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;

$baseDir = realpath(__DIR__ . '/../storage/tales');
if ($baseDir === false) {
    fwrite(STDERR, "storage/tales not found\n");
    exit(1);
}

// Recursively iterate files, skipping covers directory
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));

$results = [];
foreach ($rii as $file) {
    if ($file->isDir()) {
        continue;
    }
    $path = $file->getPathname();
    if (stripos($path, DIRECTORY_SEPARATOR . 'covers' . DIRECTORY_SEPARATOR) !== false) {
        continue;
    }
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['docx'], true)) { // focus on docx; .doc support is limited
        continue;
    }

    $relPath = substr($path, strlen($baseDir) + 1);
    $text = '';
    try {
        $reader = IOFactory::createReader('Word2007');
        $phpWord = $reader->load($path);
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= extractElementText($element) . "\n";
            }
        }
    } catch (Throwable $e) {
        $text = '[ERROR] ' . $e->getMessage();
    }

    // Trim and collapse whitespace
    $clean = preg_replace('/[\x00-\x1F]+/u', ' ', (string)$text);
    $clean = preg_replace('/\s+/u', ' ', (string)$clean);
    $clean = trim((string)$clean);

    // Limit per file output to avoid flooding
    $maxLen = 6000; // characters
    if (mb_strlen($clean) > $maxLen) {
        $clean = mb_substr($clean, 0, $maxLen) . '…';
    }

    $results[] = [
        'file' => $relPath,
        'characters' => mb_strlen($clean),
        'preview' => $clean,
    ];
}

// Print as NDJSON for easier consumption
foreach ($results as $row) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
}

// --- helpers ---
function extractElementText($element): string {
    // Handle common element types
    $class = is_object($element) ? get_class($element) : '';

    // Paragraphs and TextRuns
    if (method_exists($element, 'getElements')) {
        $buffer = '';
        foreach ($element->getElements() as $child) {
            $buffer .= extractElementText($child);
        }
        return $buffer;
    }

    // Text elements
    if (method_exists($element, 'getText')) {
        return (string)$element->getText();
    }

    // Table
    if ($class === 'PhpOffice\\PhpWord\\Element\\Table') {
        $buffer = '';
        foreach ($element->getRows() as $row) {
            foreach ($row->getCells() as $cell) {
                foreach ($cell->getElements() as $cellElement) {
                    $buffer .= extractElementText($cellElement) . " ";
                }
            }
            $buffer .= "\n";
        }
        return $buffer;
    }

    // TextBreak, Line breaks
    if ($class === 'PhpOffice\\PhpWord\\Element\\TextBreak') {
        return "\n";
    }

    // Fallback empty
    return '';
}



