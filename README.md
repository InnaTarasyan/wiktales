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
