<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\PropertyAttributeController;
use App\Http\Controllers\PropertyFeatureController;
use App\Http\Controllers\TypePropertieController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/mypropiertes', [PropertiesController::class, 'ownerPropertiesView'])->name('mypropiertes');
    Route::get('/owner/my-properties', [PropertiesController::class, 'ownerMyProperties'])->middleware('auth');
    Route::post('/save/mypropertie',[PropertiesController::class, 'store'])->middleware('subscription');
    Route::post('/update/mypropertie/{id}', [PropertiesController::class, 'update'])->middleware('subscription');

    Route::get('/help', [HelpController::class, 'help'])->name('help');

    Route::get('/types_operations', [OperationController::class, 'operation_types']);
    Route::get('/types_properties', [TypePropertieController::class, 'types_properties']);
    Route::get('/property-features', [PropertyFeatureController::class, 'index']);
    Route::get('/property-attributes/defaults', [PropertyAttributeController::class, 'defaults']);
    Route::get('/states-properties', [PropertiesController::class, 'states_properties']);
    Route::post('/properties/{id}/status', [PropertiesController::class, 'updateStatus']);
});
