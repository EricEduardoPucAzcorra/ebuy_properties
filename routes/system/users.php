<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::middleware('can:users.view')->group(function () {
        Route::get('/users', [UserController::class, 'view'])->name('users');
        Route::get('/get-users', [UserController::class, 'index']);
    });
    Route::middleware('can:users.create')->group(function () {
        Route::post('/save-user', [UserController::class, 'store']);
    });
    Route::middleware('can:users.update')->group(function () {
        Route::post('/update-user', [UserController::class, 'update']);
        Route::post('/update-state', [UserController::class, 'update_state']);
    });
});
