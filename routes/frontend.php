<?php

use App\Http\Controllers\Frontend\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blog-detail/{id}', [BlogController::class, 'show'])->name('blog-detail');
