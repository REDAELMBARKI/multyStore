<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Tenancy\TenancyDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenancy Routes (Shared Platform Infrastructure)
|--------------------------------------------------------------------------
| These routes are part of the shared multi-tenant management platform.
| They are accessible to the entire team and are NOT ignored in Git.
*/

// Auth (Shared)
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Marketplace (Shared)
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// Tenancy Management (Shared)
Route::prefix('tenancy')->group(function(){
    Route::get('/dashboard', [TenancyDashboardController::class, 'index'])->name('tenancy.dashboard');
    // Add additional tenancy management routes here
});

// Roles & User Infrastructure (Shared)
Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
Route::post('/users/{user}/roles', [RoleController::class, 'assignRole'])->name('admin.users.assignRole');
Route::delete('/users/{user}/roles', [RoleController::class, 'removeRole'])->name('admin.users.removeRole');
