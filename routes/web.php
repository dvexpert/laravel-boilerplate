<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthDevMiddleware;

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dev', fn () => view('dev'))->middleware(AuthDevMiddleware::class);

    Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');
});
