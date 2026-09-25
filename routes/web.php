<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HaveWorkspace;
use App\Http\Controllers\Workspaces\FormCreateWorkspaceController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')
        ->name('dashboard')
        ->middleware(HaveWorkspace::class);

    Route::prefix('workspace')->group(function () {
        Route::get('/create', FormCreateWorkspaceController::class)
            ->name('workspaces.create');
    });
});

require __DIR__.'/settings.php';
