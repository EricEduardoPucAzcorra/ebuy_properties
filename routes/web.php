<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/location-search', [WelcomeController::class, 'searchLocation'])
    ->name('location.search');

Route::get('/propiedades', [WelcomeController::class, 'search'])
    ->name('properties.search');


Auth::routes();

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

require __DIR__ . '/system/lang.php';
require __DIR__ . '/system/home.php';
require __DIR__ . '/system/general.php';
require __DIR__ . '/system/users.php';
require __DIR__ . '/system/roles.php';
require __DIR__ . '/system/autocompletes.php';

