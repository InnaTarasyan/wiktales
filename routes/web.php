<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaleController;

// Main home page - Tales collection
Route::get('/', [TaleController::class, 'index'])->name('home');

// Welcome page route
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Individual tale route
Route::get('/tales/{tale:slug}', [TaleController::class, 'show'])->name('tales.show');
