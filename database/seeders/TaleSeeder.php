<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tale;
use App\Models\TaleSection;

class TaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tales = [
            [
                'title' => 'The Enchanted Forest',
                'slug' => 'enchanted-forest',
                'source_filename' => 'enchanted-forest.docx',
                'cover_url' => '/storage/tales/covers/enchanted-forest-cover.jpg',
                'toc' => [
                    ['title' => 'Chapter 1: The Beginning', 'order' => 1],
                    ['title' => 'Chapter 2: The Journey', 'order' => 2],
                    ['title' => 'Chapter 3: The Discovery', 'order' => 3],
                ],
            ],
            [
                'title' => 'Dragons of the North',
                'slug' => 'dragons-north',
                'source_filename' => 'dragons-north.docx',
                'cover_url' => '/storage/tales/covers/dragons-north-cover.jpg',
                'toc' => [
                    ['title' => 'The Ancient Prophecy', 'order' => 1],
                    ['title' => 'The Dragon\'s Lair', 'order' => 2],
                ],
            ],
            [
                'title' => 'Mystical Adventures',
                'slug' => 'mystical-adventures',
                'source_filename' => 'mystical-adventures.docx',
                'cover_url' => '/storage/tales/covers/mystical-adventures-cover.jpg',
                'toc' => [
                    ['title' => 'The Magic Portal', 'order' => 1],
                    ['title' => 'Lost in Time', 'order' => 2],
                    ['title' => 'The Final Battle', 'order' => 3],
                ],
            ],
            [
                'title' => 'Princess and the Pea',
                'slug' => 'princess-pea',
                'source_filename' => 'princess-pea.docx',
                'cover_url' => '/storage/tales/covers/princess-pea-cover.jpg',
                'toc' => [
                    ['title' => 'The Royal Test', 'order' => 1],
                    ['title' => 'True Love Found', 'order' => 2],
                ],
            ],
            [
                'title' => 'The Golden Goose',
                'slug' => 'golden-goose',
                'source_filename' => 'golden-goose.docx',
                'cover_url' => '/storage/tales/covers/golden-goose-cover.jpg',
                'toc' => [
                    ['title' => 'The Magic Goose', 'order' => 1],
                    ['title' => 'Fortune and Misfortune', 'order' => 2],
                ],
            ],
            [
                'title' => 'Alice in Wonderland',
                'slug' => 'alice-wonderland',
                'source_filename' => 'alice-wonderland.docx',
                'cover_url' => '/storage/tales/covers/alice-wonderland-cover.jpg',
                'toc' => [
                    ['title' => 'Down the Rabbit Hole', 'order' => 1],
                    ['title' => 'The Mad Tea Party', 'order' => 2],
                    ['title' => 'The Queen\'s Court', 'order' => 3],
                ],
            ],
            [
                'title' => 'The Little Mermaid',
                'slug' => 'little-mermaid',
                'source_filename' => 'little-mermaid.docx',
                'cover_url' => '/storage/tales/covers/little-mermaid-cover.jpg',
                'toc' => [
                    ['title' => 'Under the Sea', 'order' => 1],
                    ['title' => 'The Human World', 'order' => 2],
                    ['title' => 'Love and Sacrifice', 'order' => 3],
                ],
            ],
            [
                'title' => 'Snow White and the Seven Dwarfs',
                'slug' => 'snow-white-seven-dwarfs',
                'source_filename' => 'snow-white-seven-dwarfs.docx',
                'cover_url' => '/storage/tales/covers/snow-white-seven-dwarfs-cover.jpg',
                'toc' => [
                    ['title' => 'The Evil Queen', 'order' => 1],
                    ['title' => 'The Seven Dwarfs', 'order' => 2],
                    ['title' => 'The Prince\'s Kiss', 'order' => 3],
                ],
            ],
            [
                'title' => 'Cinderella',
                'slug' => 'cinderella',
                'source_filename' => 'cinderella.docx',
                'cover_url' => '/storage/tales/covers/cinderella-cover.jpg',
                'toc' => [
                    ['title' => 'The Wicked Stepmother', 'order' => 1],
                    ['title' => 'The Fairy Godmother', 'order' => 2],
                    ['title' => 'The Glass Slipper', 'order' => 3],
                ],
            ],
            [
                'title' => 'Beauty and the Beast',
                'slug' => 'beauty-beast',
                'source_filename' => 'beauty-beast.docx',
                'cover_url' => '/storage/tales/covers/beauty-beast-cover.jpg',
                'toc' => [
                    ['title' => 'The Enchanted Castle', 'order' => 1],
                    ['title' => 'True Beauty Within', 'order' => 2],
                ],
            ],
            [
                'title' => 'The Three Little Pigs',
                'slug' => 'three-little-pigs',
                'source_filename' => 'three-little-pigs.docx',
                'cover_url' => '/storage/tales/covers/three-little-pigs-cover.jpg',
                'toc' => [
                    ['title' => 'Building Houses', 'order' => 1],
                    ['title' => 'The Big Bad Wolf', 'order' => 2],
                ],
            ],
            [
                'title' => 'Little Red Riding Hood',
                'slug' => 'little-red-riding-hood',
                'source_filename' => 'little-red-riding-hood.docx',
                'cover_url' => '/storage/tales/covers/little-red-riding-hood-cover.jpg',
                'toc' => [
                    ['title' => 'Through the Woods', 'order' => 1],
                    ['title' => 'The Wolf\'s Deception', 'order' => 2],
                    ['title' => 'Grandma\'s Rescue', 'order' => 3],
                ],
            ],
            [
                'title' => 'Jack and the Beanstalk',
                'slug' => 'jack-beanstalk',
                'source_filename' => 'jack-beanstalk.docx',
                'cover_url' => '/storage/tales/covers/jack-beanstalk-cover.jpg',
                'toc' => [
                    ['title' => 'Magic Beans', 'order' => 1],
                    ['title' => 'The Giant\'s Castle', 'order' => 2],
                    ['title' => 'The Golden Goose', 'order' => 3],
                ],
            ],
            [
                'title' => 'The Ugly Duckling',
                'slug' => 'ugly-duckling',
                'source_filename' => 'ugly-duckling.docx',
                'cover_url' => '/storage/tales/covers/ugly-duckling-cover.jpg',
                'toc' => [
                    ['title' => 'Born Different', 'order' => 1],
                    ['title' => 'The Journey of Self-Discovery', 'order' => 2],
                    ['title' => 'A Beautiful Swan', 'order' => 3],
                ],
            ],
            [
                'title' => 'Hansel and Gretel',
                'slug' => 'hansel-gretel',
                'source_filename' => 'hansel-gretel.docx',
                'cover_url' => '/storage/tales/covers/hansel-gretel-cover.jpg',
                'toc' => [
                    ['title' => 'Lost in the Forest', 'order' => 1],
                    ['title' => 'The Gingerbread House', 'order' => 2],
                    ['title' => 'Outsmarting the Witch', 'order' => 3],
                ],
            ],
        ];

        foreach ($tales as $taleData) {
            $tale = Tale::create($taleData);
            
            // Create sample sections for each tale
            foreach ($taleData['toc'] as $index => $sectionData) {
                $tale->sections()->create([
                    'order' => $sectionData['order'],
                    'title' => $sectionData['title'],
                    'anchor' => \Illuminate\Support\Str::slug($sectionData['title']),
                    'body_html' => '<p>This is the content for ' . $sectionData['title'] . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                    'body_text' => 'This is the content for ' . $sectionData['title'] . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                ]);
            }
        }
    }
}
