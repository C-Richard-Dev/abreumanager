<?php

use App\Http\Controllers\Categories\DeleteCategoryController;
use App\Http\Controllers\Categories\StoreCategoryController;
use App\Http\Controllers\Categories\UpdateCategoryController;
use App\Http\Controllers\Links\DestroyLinkController;
use App\Http\Controllers\Links\StoreLinkController;
use App\Http\Controllers\Links\UpdateLinkController;
use App\Http\Controllers\ResetPassword\SendPasswordResetEmailController;
use App\Http\Controllers\ResetPassword\ShowResetPasswordFormController;
use App\Http\Controllers\Users\ResetPasswordOfUserController;
use App\Http\Controllers\Workspaces\DeleteWorkspaceController;
use App\Http\Controllers\Workspaces\FormCreateWorkspaceController;
use App\Http\Controllers\Workspaces\FormEditWorkspaceController;
use App\Http\Controllers\Workspaces\ListUsersOfWorkspace;
use App\Http\Controllers\Workspaces\ListWorkspaceController;
use App\Http\Controllers\Workspaces\SendInviteEmailController;
use App\Http\Controllers\Workspaces\ShowWorkspaceController;
use App\Http\Controllers\Workspaces\StoreWorkspaceController;
use App\Http\Controllers\Workspaces\UpdateWorkspaceController;
use App\Http\Middleware\EnsureWorkspaceAccess;
use App\Http\Middleware\HaveWorkspace;
use Illuminate\Support\Facades\Route;

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
        Route::post('/store', StoreWorkspaceController::class)
            ->name('workspaces.store');
        Route::middleware(EnsureWorkspaceAccess::class)->group(function () {
            Route::get('/{workspaceUuid}', ShowWorkspaceController::class)
                ->name('workspaces.show');
            Route::get('/{workspaceUuid}/edit', FormEditWorkspaceController::class)
                ->name('workspaces.edit');
            Route::post('/{workspaceUuid}/invite', SendInviteEmailController::class)
                ->name('workspaces.invite');
            Route::put('/{workspaceUuid}', UpdateWorkspaceController::class)
                ->name('workspaces.update');
            Route::delete('/destroy/{workspaceUuid}', DeleteWorkspaceController::class)
                ->name('workspaces.destroy');
            Route::get('/{workspaceUuid}/users', ListUsersOfWorkspace::class)
                ->name('workspaces.users');
        });
    });

    Route::prefix('categories')->middleware(EnsureWorkspaceAccess::class)->group(function () {
        Route::post('{workspaceUuid}/store', StoreCategoryController::class)
            ->name('categories.store');
        Route::put('{categoryUuid}/update', UpdateCategoryController::class)
            ->name('categories.update');
        Route::delete('{categoryUuid}/destroy', DeleteCategoryController::class)
            ->name('categories.destroy');
    });

    Route::prefix('links')->middleware(EnsureWorkspaceAccess::class)->group(function () {
        Route::post('{workspaceUuid}/store', StoreLinkController::class)
            ->name('links.store');
        Route::put('{linkUuid}/update', UpdateLinkController::class)
            ->name('links.update');
        Route::delete('{linkUuid}/destroy', DestroyLinkController::class)
            ->name('links.destroy');
    });
});

Route::get('{token}/reset-password/form', ShowResetPasswordFormController::class)
    ->name('password.reset.form');
Route::post('forgot-password/send', SendPasswordResetEmailController::class)
    ->name('password.send-reset-link');
Route::post('reset-password', ResetPasswordOfUserController::class)
    ->name('password.update');

require __DIR__.'/settings.php';
