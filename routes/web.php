<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthDevMiddleware;
use App\Http\Controllers\Admin\{SystemConfigurationController, TemplateController, UserController};

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dev', fn () => view('dev'))->middleware(AuthDevMiddleware::class);

    Route::get('/', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::name('admin.')->prefix('admin')->group(function (): void {
        Route::apiResource('/template', TemplateController::class);
        Route::apiResource('/user', UserController::class);
        Route::apiResource('/system-configuration', SystemConfigurationController::class);
    });
});
