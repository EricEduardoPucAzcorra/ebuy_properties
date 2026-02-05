<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\TypePropertieController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/mypropiertes', [PropertiesController::class, 'ownerPropertiesView'])->name('mypropiertes');
    Route::get('/help', [HelpController::class, 'help'])->name('help');
    Route::get('/owner/my-properties', [PropertiesController::class, 'ownerMyProperties'])->middleware('auth');
    Route::get('/types_operations', [OperationController::class, 'operation_types']);
    Route::get('/types_properties', [TypePropertieController::class, 'types_properties']);
});
