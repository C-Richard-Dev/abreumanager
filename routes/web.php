<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HaveWorkspace;
use App\Http\Controllers\Workspaces\FormCreateWorkspaceController;
use App\Http\Controllers\Workspaces\FormEditWorkspaceController;
use App\Http\Controllers\Workspaces\ListWorkspaceController;
use App\Http\Controllers\Workspaces\StoreWorkspaceController;
use App\Http\Controllers\Workspaces\ShowWorkspaceController;
use App\Http\Controllers\Workspaces\UpdateWorkspaceController;
use App\Http\Controllers\Workspaces\DeleteWorkspaceController;

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
        Route::put('/{workspaceUuid}', UpdateWorkspaceController::class)
            ->name('workspaces.update');
        Route::delete('/destroy/{workspaceUuid}', DeleteWorkspaceController::class)
            ->name('workspaces.destroy');
    });

    Route::prefix('categories')->group(function () {
        Route::post('{workspaceUuid}/store', App\Http\Controllers\Categories\StoreCategoryController::class)
            ->name('categories.store');
        Route::put('{categoryUuid}/update', App\Http\Controllers\Categories\UpdateCategoryController::class)
            ->name('categories.update');
        Route::delete('{categoryUuid}/destroy', App\Http\Controllers\Categories\DeleteCategoryController::class)
            ->name('categories.destroy');
    });
});

require __DIR__.'/settings.php';
