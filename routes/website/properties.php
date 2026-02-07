<?php

use App\Http\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::get('/properties', [PropertiesController::class, 'properties_global'])->name('properties');

