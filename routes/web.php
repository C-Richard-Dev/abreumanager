<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HaveWorkspace;
use App\Http\Controllers\Workspaces\FormCreateWorkspaceController;
use App\Http\Controllers\Workspaces\FormEditWorkspaceController;
use App\Http\Controllers\Workspaces\ListWorkspaceController;
use App\Http\Controllers\Workspaces\StoreWorkspaceController;
use App\Http\Controllers\Workspaces\ShowWorkspaceController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')
        ->name('dashboard')
        ->middleware(HaveWorkspace::class);

    Route::prefix('workspace')->group(function () {
        Route::get('/', ListWorkspaceController::class)
            ->name('workspaces.index')
            ->middleware(HaveWorkspace::class);
        Route::get('/create', FormCreateWorkspaceController::class)
            ->name('workspaces.create');
        Route::get('/{workspaceUuid}', ShowWorkspaceController::class)
            ->name('workspaces.show');
        Route::post('/store', StoreWorkspaceController::class)
            ->name('workspaces.store');
        Route::get('/{workspaceUuid}/edit', FormEditWorkspaceController::class)
            ->name('workspaces.edit');
    });
});

require __DIR__.'/settings.php';
