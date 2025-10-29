<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Wiktales

Import .docx fairytales, extract cover/title/TOC/sections, and render pages.

### Requirements
- PHP 8.2+
- Composer
- Node 18+
- SQLite (for tests) or MySQL

### Setup
1) Install dependencies
```bash
composer install
```
2) Create storage symlink
```bash
php artisan storage:link
```
3) Configure database in `.env`, then run migrations
```bash
php artisan migrate --force
```

### Import tales
Put `.docx` files in `storage/tales` (project root) or `storage/app/tales`.
```bash
php artisan tales:import
# or
composer run import:tales
```

### Pages
- `/tales` — grid of tales (cover + title)
- `/tales/{slug}` — cover, TOC with anchors, sections

### Testing
```bash
php artisan test
```
Tests will skip if `pdo_sqlite` isn’t available.

### Tale corpus overview

This repository ships with a working corpus of author tales and poetry located under `storage/tales`. They are primarily in Russian (with occasional German headings and terms), spanning several genres:

- Epical city/war journeys and dreamlike prose: `3-Blick werfen der Mond,Herr Oberst!.docx`, `2-Blauenstadt.docx`, `3.Berchtesgaden .docx`, `5-Vandimenen Eiland.docx`
- Mythic and lunar cycles, visionary prose-poems: `3.Пороги Луны .docx`, `4.Плесень в Храме .docx`
- Lyrical cycles and miniatures (haiku/танка-like): `6.Малые формы .docx`
- Libretti/adaptations of classical Chinese poetry: `7.Либретто .docx` (Ли Бо, Чжан Цзи, Мэн Хао-жань и др.)
- Portraits and gallery-inspired narratives: `1-Weiße Stirn. Grenze.docx`
- Satirical/postmodern verse: `8.Романтический хулиган .docx`

Brief highlights (non-spoiler synopses):

- 1-Weiße Stirn. Grenze: A gallery and city-portrait cycle centered on “Александер”, mixing ironic urbanity with esoteric motifs; chapters traverse nightly patrols, ceremonies, and ferry crossings.
- 2-Blauenstadt: A three-part Verona-inspired urban carnival with commissar, clans, and “магический театр”; themes of youth, power, and staged violence.
- 3-Blick werfen der Mond, Herr Oberst!: A fog-of-war march—prologues, maneuvers, checkpoints, and a dinner with the general; perception shifts, identities swap around a guarded hearth.
- 3.Berchtesgaden: Café nocturnes and postal summons; slow-cinema prose of streets, posters, and crowds, with dream sequences and a fateful notice.
- 3.Пороги Луны: Hymns to the Moon; processional visions, carnivals of shadows, and mythic figures (Марсий, Анитра), between terror and illumination.
- 4.Плесень в Храме: Arena and freedom, northern seas, storms, ruins and “плесень” color cycles; elegies of age, dogs, and sailors at the world’s edge.
- 5-Vandimenen Eiland: A large-arc novel with juries, priests, fakirs, and biblical/mythic interludes; a carnival of intertexts from Ulysses to Herod and Circe.
- 6.Малые формы: Minimalist aphorisms and images (ворона, туча, круг, звон колокола), often existential or zen-like.
- 7.Либретто: Opera-like libretti reimagining classical Chinese poems (Ли Бо, Чжан Цзи, Мэн Хао-жань), with scene outlines and refrains.
- 8.Романтический хулиган: Playful, satirical verse collaging Russian classics and pop imagery, from Пушкин и Бродский to “летняя пастораль”.

Languages and style:

- Mostly Russian texts; occasional German titles/sections. Tone ranges from lyrical to experimental, with dense metaphor, intertext, and cinematic montage.
- Themes recur: город/туман, луна/вода, карнавал/тени, миф/память, море/буря, арена/свобода.

Covers and media:

- Image covers are stored in `storage/tales/covers` and are associated during import. Non-image assets are ignored by the importer.

### Content tooling

To quickly preview tale contents without importing, a small helper script is provided:

```bash
php scripts/extract_tales.php > tales.ndjson
# Each line: {"file": "…", "characters": N, "preview": "…"}
```

Notes:

- The script scans `storage/tales`, skips `covers/`, and extracts `.docx` text using PhpOffice PhpWord.
- Output is truncated per file to keep previews readable. It does not change the database.
- `.doc` files are not parsed by default; convert to `.docx` for consistent results.

### Editorial and rights

- The texts and covers under `storage/tales` are provided for development/demo. If you intend to deploy publicly, ensure you have the rights to publish the content and images.
- For multilingual rendering and typography, consider enabling locale-aware fonts and hyphenation on the frontend.
