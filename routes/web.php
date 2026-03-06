<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    $theme = config('theme.active');
    return view("{$theme}.home");
})->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])
    ->get('/dashboard', DashboardController::class)
    ->name('dashboard');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!admin|news|login|register|settings|password|storage).*$')
    ->name('pages.show');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        require __DIR__.'/admin.php';
    });
