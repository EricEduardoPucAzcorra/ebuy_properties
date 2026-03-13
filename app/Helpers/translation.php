<?php

use App\Services\TranslationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

if (!function_exists('auto_trans')) {
    function auto_trans($text, $replace = [], $locale = null)
    {
        // 1. Lista de ignorados para no saturar la API
        $ignore = ['Not Found', 'Dashboard', 'Logout', 'Login', 'Register'];
        if (in_array($text, $ignore)) return $text;

        $locale = $locale ?? app()->getLocale();
        $service = app(TranslationService::class);

        return $service->translate($text, $locale);
    }
}
