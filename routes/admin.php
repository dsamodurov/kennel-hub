<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNews;
use App\Http\Controllers\Admin\MediaController as AdminMedia;
use App\Http\Controllers\Admin\PageController as AdminPages;

Route::get('/', DashboardController::class)->name('dashboard');

Route::prefix('news')->as('news.')->group(function () {
    Route::get('/', [AdminNews::class, 'index'])->name('index');
    Route::get('/create', [AdminNews::class, 'create'])->name('create');
    Route::post('/', [AdminNews::class, 'store'])->name('store');
    Route::get('/{news}/edit', [AdminNews::class, 'edit'])->name('edit');
    Route::post('/{news}', [AdminNews::class, 'update'])->name('update');
    Route::delete('/{news}', [AdminNews::class, 'destroy'])->name('destroy');
    Route::post('/{news}/restore', [AdminNews::class, 'restore'])->name('restore');
    Route::delete('/{news}/force', [AdminNews::class, 'forceDestroy'])->name('force-destroy');
});

Route::prefix('media')->as('media.')->group(function () {
    Route::get('/', [AdminMedia::class, 'index'])->name('index');
    Route::get('/library', [AdminMedia::class, 'library'])->name('library');
    Route::post('/', [AdminMedia::class, 'store'])->name('store');
    Route::post('/upload', [AdminMedia::class, 'upload'])->name('upload');
    Route::put('/{media}', [AdminMedia::class, 'update'])->name('update');
    Route::delete('/{media}', [AdminMedia::class, 'destroy'])->name('destroy');
});

Route::prefix('pages')->as('pages.')->group(function () {
    Route::get('/', [AdminPages::class, 'index'])->name('index');
    Route::get('/create', [AdminPages::class, 'create'])->name('create');
    Route::post('/', [AdminPages::class, 'store'])->name('store');
    Route::get('/{page}/edit', [AdminPages::class, 'edit'])->name('edit');
    Route::post('/{page}', [AdminPages::class, 'update'])->name('update');
    Route::delete('/{page}', [AdminPages::class, 'destroy'])->name('destroy');
});
