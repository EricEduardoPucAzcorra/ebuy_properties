<?php

use App\Http\Controllers\AutocompleteController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/countries/search', [AutocompleteController::class, 'search_countries']);
    Route::get('/states/search', [AutocompleteController::class, 'search_states']);
    Route::get('/cities/search', [AutocompleteController::class, 'search_cities']);
    Route::get('/location/reverse-geocode', [AutocompleteController::class, 'reverse_geocode']);
});
