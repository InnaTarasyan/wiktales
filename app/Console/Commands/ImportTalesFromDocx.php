<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Tale;
use App\Models\TaleSection;
use App\Services\DocxParser;

class ImportTalesFromDocx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tales:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт сказок из .docx файлов в storage/tales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $legacyDir = base_path('storage/tales');
        $appDir = storage_path('app/tales');
        $candidates = [];
        if (is_dir($legacyDir)) {
            $candidates = array_merge($candidates, glob($legacyDir . '/*.docx') ?: []);
        }
        if (is_dir($appDir)) {
            $candidates = array_merge($candidates, glob($appDir . '/*.docx') ?: []);
        }
        // de-duplicate
        $files = collect($candidates)->unique()->values();

        if ($files->isEmpty()) {
            $this->info('Файлы .docx не найдены в storage/tales или storage/app/tales');
            return self::SUCCESS;
        }

        $parser = app(DocxParser::class);

        foreach ($files as $fullPath) {
            $this->line("Импорт: {$fullPath}");

            try {
                $data = $parser->parse($fullPath);
            } catch (\Throwable $e) {
                $this->error("Ошибка парсинга {$fullPath}: " . $e->getMessage());
                continue;
            }

            $baseName = pathinfo($fullPath, PATHINFO_FILENAME);
            $proposedTitle = $this->getCorrectTitle($baseName, $data['title'] ?? '');
            $slug = Str::slug($proposedTitle !== '' ? $proposedTitle : $baseName);
            // Avoid generic titles causing collisions; ensure uniqueness across tales
            $ensureUnique = function (string $trySlug) use ($baseName, $fullPath) {
                $slugCandidate = $trySlug !== '' ? $trySlug : Str::slug($baseName);
                $original = $slugCandidate;
                $i = 1;
                while (\App\Models\Tale::where('slug', $slugCandidate)->exists()) {
                    $i++;
                    $slugCandidate = $original . '-' . $i;
                    if ($i > 50) { // fallback hard stop
                        $slugCandidate = Str::slug($baseName) . '-' . substr(md5($baseName), 0, 6);
                        break;
                    }
                }
                return $slugCandidate;
            };
            $slug = $ensureUnique($slug);

            $coverUrl = null;
            if (!empty($data['cover']) && !empty($data['cover']['data'])) {
                $ext = $data['cover']['extension'] ?? 'jpg';
                Storage::disk('public')->put("tales/covers/{$slug}-cover.{$ext}", $data['cover']['data']);
                $coverUrl = "/storage/tales/covers/{$slug}-cover.{$ext}";
            }

            $tale = Tale::updateOrCreate(
                ['source_filename' => basename($fullPath)],
                [
                    'title' => $proposedTitle !== '' ? $proposedTitle : $baseName,
                    'slug' => $slug,
                    'cover_url' => $coverUrl,
                    'toc' => $data['toc'] ?? [],
                    'meta' => $data['meta'] ?? [],
                ]
            );

            if (!empty($data['sections'])) {
                $tale->sections()->delete();
                foreach ($data['sections'] as $index => $section) {
                    $tale->sections()->create([
                        'order' => $index + 1,
                        'title' => $section['title'] ?? null,
                        'anchor' => $section['anchor'] ?? null,
                        'body_html' => $section['body_html'] ?? null,
                        'body_text' => $section['body_text'] ?? null,
                    ]);
                }
            }
        }

        $this->info('Импорт завершен.');
        return self::SUCCESS;
    }

    /**
     * Get the correct title for a file based on filename mapping
     */
    private function getCorrectTitle(string $filename, string $extractedTitle): string
    {
        $titleMapping = [
            '1 Faun' => 'ФАВН',
            '2 Tanz der Empussa' => 'ТАНЕЦ    ЭМПУСЫ',
            '3 Volontären für Kirka' => 'КИРКИ',
            '4 Argonauten' => 'ARGONAUTEN',
            '5-Vandimenen Eiland' => 'VANDIMENEN EILAND',
            '1-Fortinbras ist gekommen' => 'FORTINBRAS IST GEKOMMEN',
            '1-Weiße Stirn. Grenze' => 'БЕЛОЛОБЫЙ. РУБЕЖ',
            '1.Im Gesetzkörper (Säule und Mond) ' => 'ЛУНА И СТОЛБ. В ТЕЛЕ ЗАКОНА',
            '2-Blauenstadt' => 'BLAUENSTADT',
            '2-Velichan' => 'ВЕЛИХАН',
            '2.Melisanda ' => 'МЕЛИСАНДА. ПОПЫТКА ОДНОЙ РЕАБИЛИТАЦИИ',
            '2.Драконья гора ' => 'Драконья Гора',
            '3-Blick werfen der Mond,Herr Oberst!' => 'ВЗГЛЯНЕМ НА ЛУНУ, ПОЛКОВНИК!',
            '3.Berchtesgaden ' => 'BERCHESTCADEN',
            '3.Пороги Луны ' => 'Пороги Луны',
            '4.Krähen Freiheit ' => ' ВОРОНЬЯ   СЛОБОДА',
            '4.Плесень в Храме ' => 'Плесень в Храме',
            '5.Anadyr ' => 'Anadyr',
            '5.Пленэр ' => 'Пленэр',
            '6.Малые формы ' => 'Малые формы',
            '7.Либретто ' => 'Либретто',
            '8.Романтический хулиган ' => 'Романтический хулиган',
        ];

        // Check if we have a mapping for this filename (without extension)
        if (isset($titleMapping[$filename])) {
            return $titleMapping[$filename];
        }

        // Fallback to extracted title or filename
        return trim($extractedTitle) !== '' ? trim($extractedTitle) : $filename;
    }
}
