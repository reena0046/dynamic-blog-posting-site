<?php

use App\Http\Controllers\Frontend\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');

Route::get('/blogs/{slug}/details', [BlogController::class, 'show'])->name('blogs.show');

Route::middleware('auth')->group(function () {
    Route::post('/blogs/{slug}/details/like', [BlogController::class, 'like'])->name('blogs.like');
    Route::post('/blogs/{slug}/details/comments', [BlogController::class, 'storeComment'])->name('blogs.comments.store');
});
