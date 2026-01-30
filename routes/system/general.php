<?php

use App\Http\Controllers\ConfigController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    //profile
    Route::get('/profile', [UserController::class, 'profile_view'])->name('profile');
    Route::get('/auth/user', [UserController::class, 'authenticatedUser'])
    ->middleware('auth');


    //Buscardores
    Route::get('/search', [SearchController::class, 'search']);
    //Change tenant
    Route::match(['get', 'post'], '/set-tenant/{tenant}', [TenantController::class, 'set'])
    ->name('tenant.set');
    //tenants
    Route::middleware('can:config.view')->group(function () {
        Route::get('/config-session', [ConfigController::class, 'view'])->name('config');
        Route::post('/tenant-principal', [TenantController::class, 'getTenantPrincipal']);
        Route::post('/tenant/all', [TenantController::class, 'getTenantBusiness']);
    });
    Route::middleware('can:config.set_up_global')->group(function () {
         Route::post('/tenant-principal/save', [TenantController::class, 'save']);
    });
});
