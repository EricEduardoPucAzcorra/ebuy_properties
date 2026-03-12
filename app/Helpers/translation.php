<?php

use App\Services\TranslationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

if (!function_exists('auto_trans')) {
    /**
     * Traduce texto automáticamente
     */
    function auto_trans($text, $replace = [], $locale = null)
    {
        // Validaciones básicas
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

        // Determinar locale
        $locale = $locale ?? app()->getLocale();

        // Idiomas soportados
        $supportedLocales = ['es', 'en', 'fr', 'it', 'de', 'pt'];
        $locale = in_array($locale, $supportedLocales) ? $locale : 'es';

        // Cache para evitar traducciones repetidas
        $cacheKey = 'auto_trans_' . md5($text . $locale . json_encode($replace));

        return Cache::remember($cacheKey, 3600, function() use ($text, $replace, $locale) {
            try {
                // Intentar con Laravel primero
                $translation = trans($text, $replace, $locale);
                if ($translation !== $text) {
                    return $translation;
                }

                // Usar Google Translate
                $service = app(TranslationService::class);

                // Textos largos necesitan tratamiento especial
                if (strlen($text) > 3000) {
                    $translated = translate_long_text($text, $locale, $service);
                } else {
                    $translated = $service->translate($text, $locale);
                }

                // Aplicar reemplazos
                if (!empty($replace) && $translated !== $text) {
                    $translated = apply_replacements($translated, $replace);
                }

                return $translated ?: $text;

            } catch (Exception $e) {
                Log::error('Error en auto_trans: ' . $e->getMessage(), [
                    'texto' => substr($text, 0, 100),
                    'locale' => $locale
                ]);

                return apply_replacements($text, $replace);
            }
        });
    }
}

if (!function_exists('translate_long_text')) {
    /**
     * Traduce textos largos manualmente
     */
    function translate_long_text($text, $locale, $service)
    {
        // Dividir en oraciones
        $sentences = preg_split('/(?<=[.?!])\s+(?=[A-ZÁÉÍÓÚÑ])/', $text, -1, PREG_SPLIT_NO_EMPTY);

        if (count($sentences) < 2) {
            return $service->translate($text, $locale);
        }

        $translated = [];
        foreach ($sentences as $sentence) {
            if (!empty(trim($sentence))) {
                $translated[] = $service->translate($sentence, $locale);

                // Pequeña pausa cada 5 oraciones
                if (count($translated) % 5 == 0) {
                    usleep(100000);
                }
            }
        }

        return implode(' ', $translated);
    }
}

if (!function_exists('apply_replacements')) {
    /**
     * Aplica reemplazos al texto traducido
     */
    function apply_replacements($text, $replace)
    {
        if (empty($replace)) return $text;

        foreach ($replace as $key => $value) {
            $text = str_replace(
                [':' . $key, '{' . $key . '}'],
                (string)$value,
                $text
            );
        }

        return $text;
    }
}
