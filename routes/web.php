<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/work', fn () => view('work'))->name('work');
Route::get('/contact', fn () => view('contact'))->name('contact');
