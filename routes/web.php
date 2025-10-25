<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaleController;
use App\Http\Controllers\ContactController;

// Main home page - Tales collection
Route::get('/', [TaleController::class, 'index'])->name('home');

// Welcome page route
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Individual tale route
Route::get('/tales/{tale:slug}', [TaleController::class, 'show'])->name('tales.show');

// Tale download route
Route::get('/tales/{tale:slug}/download', [TaleController::class, 'download'])->name('tales.download');

// Contact form route
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
