<?php

use App\Services\TranslationService;

if (!function_exists('auto_trans')) {
    function auto_trans($text, $replace = [], $locale = null)
    {
        // Primero intentar con Laravel
        $translation = trans($text, $replace, $locale);

        // Si no existe, usar traducción automática
        if ($translation === $text) {
            $translation = app(TranslationService::class)->translate($text, $locale);

            // Aplicar reemplazos
            foreach ($replace as $key => $value) {
                $translation = str_replace(
                    [':' . $key, ':' . strtoupper($key), ':' . ucfirst($key)],
                    $value,
                    $translation
                );
            }
        }

        return $translation;
    }
}
