<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tale;
use Illuminate\Http\Response;

class TaleController extends Controller
{
    public function index()
    {
        $tales = Tale::with('sections')->orderBy('title')->paginate(12);
        return view('tales.index', compact('tales'));
    }

    public function show(Tale $tale)
    {
        $tale->load('sections');
        return view('tales.show', compact('tale'));
    }

    public function download(Tale $tale)
    {
        $tale->load('sections');
        
        // Generate HTML content for the tale
        $html = $this->generateTaleHtml($tale);
        
        // Create filename
        $filename = $tale->slug . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateTaleHtml(Tale $tale)
    {
        $html = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($tale->title) . '</title>
    <style>
        body {
            font-family: Georgia, "Times New Roman", serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h2 {
            color: #34495e;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        p {
            text-align: justify;
            margin-bottom: 15px;
            text-indent: 1.5em;
        }
        .section {
            margin-bottom: 40px;
        }
        .toc {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .toc h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .toc ul {
            list-style: none;
            padding-left: 0;
        }
        .toc li {
            margin-bottom: 5px;
        }
        .toc a {
            color: #3498db;
            text-decoration: none;
        }
        .toc a:hover {
            text-decoration: underline;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
        }
        blockquote {
            border-left: 4px solid #3498db;
            margin: 20px 0;
            padding: 10px 20px;
            background: #f8f9fa;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>' . htmlspecialchars($tale->title) . '</h1>';

        // Add table of contents
        if ($tale->sections->count() > 0) {
            $html .= '<div class="toc">
                <h3>Содержание</h3>
                <ul>';
            
            foreach ($tale->sections as $index => $section) {
                $sectionTitle = $section->title ?: 'Раздел ' . ($index + 1);
                $html .= '<li><a href="#section-' . $index . '">' . ($index + 1) . '. ' . htmlspecialchars($sectionTitle) . '</a></li>';
            }
            
            $html .= '</ul>
            </div>';
        }

        // Add sections
        foreach ($tale->sections as $index => $section) {
            $html .= '<div class="section" id="section-' . $index . '">';
            
            if ($section->title) {
                $html .= '<h2>' . htmlspecialchars($section->title) . '</h2>';
            }
            
            $html .= $section->body_html;
            $html .= '</div>';
        }

        $html .= '</body>
</html>';

        return $html;
    }
}
