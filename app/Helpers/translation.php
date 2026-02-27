<?php

use App\Services\TranslationService;
use Illuminate\Support\Facades\Log;

if (!function_exists('auto_trans')) {
    function auto_trans($text, $replace = [], $locale = null)
    {
        if (is_null($text) || $text === '') {
            return $text;
        }

        if (is_array($text)) {
            return array_map(function($item) use ($replace, $locale) {
                return auto_trans($item, $replace, $locale);
            }, $text);
        }

        $text = trim((string)$text);
        if (empty($text)) return $text;

        // Validar locale (opcional pero recomendado)
        $locale = $locale ?? app()->getLocale();
        $locale = in_array($locale, ['es', 'en', 'fr', 'it', 'de', 'pt']) ? $locale : 'es';

        // Intentar con Laravel primero
        $translation = trans($text, $replace, $locale);
        if ($translation !== $text) {
            return $translation;
        }

        // Usar V3
        try {
            $service = app(TranslationService::class);
            $translated = $service->translate($text, $locale);

            if (!empty($replace) && $translated !== $text) {
                $translated = apply_auto_trans_replacements($translated, $replace);
            }

            return $translated;

        } catch (Exception $e) {
            Log::error('Error en traducción: ' . $e->getMessage(), [
                'text' => substr($text, 0, 100),
                'locale' => $locale
            ]);

            if (!empty($replace)) {
                return apply_auto_trans_replacements($text, $replace);
            }
            return $text;
        }
    }
}

if (!function_exists('apply_auto_trans_replacements')) {
    function apply_auto_trans_replacements($text, $replace)
    {
        if (empty($replace)) return $text;

        $map = [];
        foreach ($replace as $key => $value) {
            $map[':' . $key] = $value;
            $map['{' . $key . '}'] = $value;
        }
        return strtr($text, $map);
    }
}
