<?php

use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
//Obtener propiedades
Route::match(['get', 'post'],'/properties', [PropertiesController::class, 'properties_global'])->name('properties');
Route::get('/properties/sale', [PropertiesController::class, 'properties_global'])->name('properties.sale');
Route::get('/properties/rent', [PropertiesController::class, 'properties_global'])->name('properties.rent');
Route::get('/properties/new', [PropertiesController::class, 'properties_global'])->name('properties.new');

Route::get('/properties', [PropertiesController::class, 'properties_global'])->name('properties');

Route::get('/property/{id}',[PropertiesController::class, 'property'])->name('property');

//Favoritos de propiedeades
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/favorites/check/{propertyId}', [FavoriteController::class, 'check'])->name('favorites.check');
Route::delete('/favorites/remove', [FavoriteController::class, 'remove'])->name('favorites.remove');

