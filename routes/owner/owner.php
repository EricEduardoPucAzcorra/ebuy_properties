<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\PropertyAttributeController;
use App\Http\Controllers\PropertyFeatureController;
use App\Http\Controllers\TypePropertieController;
use App\Http\Controllers\OpenpaySubscriptionController;
use App\Http\Controllers\OpenpayController;
use App\Http\Controllers\MyFavoritesController;
use App\Http\Controllers\SusbcriptionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/mypropiertes', [PropertiesController::class, 'ownerPropertiesView'])->name('mypropiertes');
    Route::get('/owner/my-properties', [PropertiesController::class, 'ownerMyProperties'])->middleware('auth');
    Route::post('/save/mypropertie',[PropertiesController::class, 'store']);
    Route::post('/update/mypropertie/{id}', [PropertiesController::class, 'update']);

    Route::get('/help', [HelpController::class, 'help'])->name('help');

    Route::get('/types_operations', [OperationController::class, 'operation_types']);
    Route::get('/types_properties', [TypePropertieController::class, 'types_properties']);
    Route::get('/property-features', [PropertyFeatureController::class, 'index']);
    Route::get('/property-attributes/defaults', [PropertyAttributeController::class, 'defaults']);
    Route::get('/states-properties', [PropertiesController::class, 'states_properties']);
    Route::post('/properties/{id}/status', [PropertiesController::class, 'updateStatus']);

    //pago individual
    // Route::post('/payments/process', [OpenpayController::class, 'process']);

    //Favoritos
    Route::get('/my-favorites', [MyFavoritesController::class, 'view'])->name('my-favorites');
    Route::get('/owner/favorite-properties', [MyFavoritesController::class, 'ownerFavoriteProperties'])->name('favorite-properties');
    
    //suscripciones
    Route::get('/my-plans', [SusbcriptionController::class, 'view'])->name('my-plans');
    Route::post('/owner/susbcription/process', [SusbcriptionController::class, 'subscribe']);
    Route::get('/owner/current-subscriptions', [SusbcriptionController::class, 'getCurrentSubscriptions']);
    Route::post('/owner/subscriptions/cancel', [SusbcriptionController::class, 'cancel']);

});
