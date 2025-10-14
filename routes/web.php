<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TaleController;

Route::get('/tales', [TaleController::class, 'index'])->name('tales.index');
Route::get('/tales/{tale:slug}', [TaleController::class, 'show'])->name('tales.show');
