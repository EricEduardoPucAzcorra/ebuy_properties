<?php

use App\Http\Controllers\MapaController;

Route::get('/mapa', [MapaController::class, 'mapa'])->name('site.mapa');
Route::get('/location-coordinates', [MapaController::class, 'getLocationCoordinates'])->name('location.coordinates');
Route::get('/properties/map', [MapaController::class, 'properties_map'])->name('properties.map');


