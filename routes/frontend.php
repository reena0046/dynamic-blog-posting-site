
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Frontend.Pages.blog');
})->name('home');

Route::get('/blog-detail/{id}', function () {
    return view('Frontend.Pages.blog-detail');
})->name('blog-detail');
