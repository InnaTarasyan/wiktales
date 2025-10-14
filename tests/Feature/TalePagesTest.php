<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tale;

class TalePagesTest extends TestCase
{
    public function test_tales_pages_render(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite not available');
        }
        @mkdir(database_path(), 0777, true);
        if (!file_exists(database_path('database.sqlite'))) {
            touch(database_path('database.sqlite'));
        }
        Artisan::call('migrate', ['--force' => true]);

        $tale = Tale::create([
            'title' => 'Sample Tale',
            'slug' => 'sample-tale',
            'source_filename' => 'sample.docx',
            'cover_url' => '/storage/tales/covers/sample-tale-cover.jpg',
            'toc' => [['title' => 'Section 1', 'order' => 1]],
        ]);
        $tale->sections()->create([
            'order' => 1,
            'title' => 'Section 1',
            'anchor' => 'section-1',
            'body_html' => '<p>Hello</p>',
            'body_text' => 'Hello',
        ]);

        $this->get('/tales')->assertOk()->assertSee('Sample Tale');
        $this->get('/tales/sample-tale')->assertOk()->assertSee('Section 1')->assertSee('Hello');
    }
}
