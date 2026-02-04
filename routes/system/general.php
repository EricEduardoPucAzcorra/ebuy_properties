<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\FeaturePlanController;
use App\Http\Controllers\PlansController;
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

    //Configuración de planes
    Route::middleware('can:plans.view')->group(function () {
        Route::get('/plans', [PlansController::class, 'plans_view'])->name('plans');
        Route::get('/plans-index', [PlansController::class, 'index']);
    });
    Route::middleware('can:plans.create')->group(function () {
        Route::post('/plans-index', [PlansController::class, 'store']);
    });
    Route::middleware('can:plans.update')->group(function () {
        Route::put('/plans-index/{plan}', [PlansController::class, 'update']);
        Route::delete('/plans-index/{plan}', [PlansController::class, 'destroy']);
    });

    //Features planes
    Route::get('/feature-plans', [FeaturePlanController::class, 'index']);
    Route::get('/plans_features', [FeaturePlanController::class, 'view'])->name('plans_feature');
    Route::get('/plans_features_index', [FeaturePlanController::class, 'index_data']);
    Route::post('/save-plan-feature', [FeaturePlanController::class, 'store']);
    Route::post('/update-plan-feature', [FeaturePlanController::class, 'update']);
    Route::post('/update-plan-feature-state', [FeaturePlanController::class, 'update_state']);

});
