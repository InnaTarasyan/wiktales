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
    protected $description = 'Import tales from .docx files in storage/tales';

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
            $this->info('No .docx files found in storage/tales or storage/app/tales');
            return self::SUCCESS;
        }

        $parser = app(DocxParser::class);

        foreach ($files as $fullPath) {
            $this->line("Importing: {$fullPath}");

            try {
                $data = $parser->parse($fullPath);
            } catch (\Throwable $e) {
                $this->error("Failed to parse {$fullPath}: " . $e->getMessage());
                continue;
            }

            $baseName = pathinfo($fullPath, PATHINFO_FILENAME);
            $proposedTitle = trim((string)($data['title'] ?? ''));
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

        $this->info('Import finished.');
        return self::SUCCESS;
    }
}
