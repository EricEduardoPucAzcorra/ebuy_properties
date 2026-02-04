<?php

use App\Http\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::get('/properties', [PropertiesController::class, 'search'])->name('properties');

