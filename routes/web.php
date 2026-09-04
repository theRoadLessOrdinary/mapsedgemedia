<?php

use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');

Route::get('/work', [WorkController::class, 'index'])->name('work');
Route::get('/work/{slug}', [WorkController::class, 'show'])->name('work.show');
