<?php

use App\Http\Controllers\AutocompleteController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/countries/search', [AutocompleteController::class, 'search_countries']);
});
