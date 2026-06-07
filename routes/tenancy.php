<?php

use App\Http\Controllers\Tenancy\StoreController;
use App\Http\Controllers\Tenancy\TenenacyDashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::domain('localhost')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('home.tenancy');
    
    Route::get('/tenancy/dashboard', [TenenacyDashboardController::class , 'index'])->name('tenancy.dashboard');

    Route::get('/tenancy/stores', function () {
        return Inertia::render('tenancy/stores/Index');
    })->name('tenancy.stores');

    Route::get('/tenancy/roles', function () {
        return Inertia::render('tenancy/roles/Index');
    })->name('tenancy.roles');

    // Tenancy Store Creation
    Route::middleware(['auth'])->group(function () {
        Route::get('/tenancy/stores/create', [StoreController::class, 'create'])->name('tenancy.stores.create');
        Route::post('/tenancy/stores', [StoreController::class, 'store'])->name('tenancy.stores.store');
    });


    
});
