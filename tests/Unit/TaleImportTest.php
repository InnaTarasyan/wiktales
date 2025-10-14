<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Tale;

class TaleImportTest extends TestCase
{
    public function test_import_command_processes_docx_fixture(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite extension not available in this environment.');
        }

        // Ensure we have a sqlite database file
        @mkdir(database_path(), 0777, true);
        if (!file_exists(database_path('database.sqlite'))) {
            touch(database_path('database.sqlite'));
        }

        // Run migrations
        Artisan::call('migrate', ['--force' => true]);

        Storage::fake('public');

        $tmp = tempnam(sys_get_temp_dir(), 'docx');
        unlink($tmp);
        $zipPath = $tmp . '.docx';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);
        $docXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
            .'<w:body>'
            .'<w:p><w:r><w:t>My Test Tale</w:t></w:r></w:p>'
            .'<w:p><w:r><w:t>Section 1</w:t></w:r></w:p>'
            .'<w:p><w:r><w:t>First paragraph.</w:t></w:r></w:p>'
            .'</w:body>'
            .'</w:document>';
        $zip->addFromString('word/document.xml', $docXml);
        $zip->addFromString('word/media/image1.jpeg', 'fakeimg');
        $zip->close();

        @mkdir(base_path('storage/tales'), 0777, true);
        copy($zipPath, base_path('storage/tales/test-tale.docx'));

        Artisan::call('tales:import');

        $this->assertDatabaseHas('tales', [
            'source_filename' => 'test-tale.docx',
        ]);

        $tale = Tale::where('source_filename', 'test-tale.docx')->first();
        $this->assertNotNull($tale);
        $this->assertNotNull($tale->cover_url);
        $this->assertTrue(str_starts_with($tale->cover_url, '/storage/tales/covers/'));
    }
}
