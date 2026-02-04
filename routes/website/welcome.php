<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.site');
Route::get('/about', [WelcomeController::class, 'about'])->name('about.site');
Route::get('/location-search', [WelcomeController::class, 'searchLocation'])->name('location.search');



