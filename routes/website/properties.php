<?php

use App\Http\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'],'/properties', [PropertiesController::class, 'properties_global'])->name('properties');
Route::get('/properties/sale', [PropertiesController::class, 'properties_global'])->name('properties.sale');
Route::get('/properties/rent', [PropertiesController::class, 'properties_global'])->name('properties.rent');
Route::get('/properties/new', [PropertiesController::class, 'properties_global'])->name('properties.new');

Route::get('/properties', [PropertiesController::class, 'properties_global'])->name('properties');

Route::get('/property',[PropertiesController::class, 'property'])->name('property');
