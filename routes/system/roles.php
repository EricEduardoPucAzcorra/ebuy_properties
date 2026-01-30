<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::middleware(['auth'])->group(function () {
    Route::get('/roles/selected', [RoleController::class, 'roles_select']);
    Route::post('/get-roles', [RoleController::class, 'index']);

    Route::middleware('can:roles.view')->group(function () {
        Route::get('/roles', [RoleController::class, 'view'])->name('roles');
        Route::get('/roles/{role}/permissions', [RoleController::class, 'getRolePermissions']);
    });
    Route::middleware('can:roles.create')->group(function () {
        Route::post('/save-role', [RoleController::class, 'store']);
    });
    Route::middleware('can:roles.update')->group(function () {
        Route::post('/update-role', [RoleController::class, 'update']);
    });
    Route::middleware('can:roles.permissions')->group(function () {
        Route::post('/roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
    });
});
