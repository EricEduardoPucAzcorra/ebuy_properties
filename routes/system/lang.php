<?php

use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
});
