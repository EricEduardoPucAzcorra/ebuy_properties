<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/mypropiertes', [PropertiesController::class, 'ownerPropertiesView'])->name('mypropiertes');
    Route::get('/help', [HelpController::class, 'help'])->name('help');
});
