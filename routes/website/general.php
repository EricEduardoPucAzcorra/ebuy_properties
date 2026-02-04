<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlansController;
use Illuminate\Support\Facades\Route;

Route::get('/api/plans-index', [PlansController::class, 'index']);
Route::post('/planes/seleccionar', [HomeController::class, 'select'])->name('plans.select');
