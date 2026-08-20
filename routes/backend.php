<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('blogs', BlogController::class)->names('blogs');
        Route::post('blogs/data', [BlogController::class, 'data'])->name('blogs.data');
        Route::post('blogs/update-status', [BlogController::class, 'updateStatus'])->name('blogs.update-status');
        Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload-image');
    });
});
