<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

require __DIR__ . '/system/lang.php';
require __DIR__ . '/system/home.php';
require __DIR__ . '/system/general.php';
require __DIR__ . '/system/users.php';
require __DIR__ . '/system/roles.php';
require __DIR__ . '/system/autocompletes.php';

