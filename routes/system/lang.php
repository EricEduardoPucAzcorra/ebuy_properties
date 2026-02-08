<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
});

Route::post('/auto_trans', function (Request $request) {
    return response()->json([
        'res' => auto_trans($request->text)
    ]);
});

Route::post('/auto_trans_batch', function (Request $request) {
    $texts = $request->texts ?? [];
    $results = [];
    foreach ($texts as $text) {
        $results[$text] = auto_trans($text);
    }
    return response()->json(['results' => $results], 200, [], JSON_UNESCAPED_UNICODE);
});
