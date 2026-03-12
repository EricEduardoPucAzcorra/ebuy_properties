<?php

use App\Services\TranslationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

if (!function_exists('auto_trans')) {
    /**
     * TRADUCE TEXTO AUTOMÁTICAMENTE
     */
    function auto_trans($text, $replace = [], $locale = null)
    {
        // VALIDACIONES
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

        // DETERMINAR IDIOMA
        $locale = $locale ?? app()->getLocale();

        // IDIOMAS SOPORTADOS
        $supportedLocales = ['es', 'en', 'fr', 'it', 'de', 'pt'];
        $locale = in_array($locale, $supportedLocales) ? $locale : 'es';

        // CACHE CORTO (1 hora)
        $cacheKey = 'auto_trans_' . md5($text . $locale . json_encode($replace));

        return Cache::remember($cacheKey, 3600, function() use ($text, $replace, $locale) {
            try {
                // 1. INTENTAR CON LARAVEL PRIMERO
                $translation = trans($text, $replace, $locale);
                if ($translation !== $text) {
                    return $translation;
                }

                // 2. USAR GOOGLE TRANSLATE
                $service = app(TranslationService::class);

                // REGISTRO PARA DEBUG
                Log::info('auto_trans llamando a servicio', [
                    'longitud' => strlen($text),
                    'locale' => $locale
                ]);

                $translated = $service->translate($text, $locale);

                // 3. APLICAR REEMPLAZOS
                if (!empty($replace) && $translated !== $text) {
                    $translated = apply_replacements($translated, $replace);
                }

                return $translated ?: $text;

            } catch (Exception $e) {
                Log::error('❌ Error en auto_trans: ' . $e->getMessage(), [
                    'texto' => substr($text, 0, 100),
                    'locale' => $locale
                ]);

                return apply_replacements($text, $replace);
            }
        });
    }
}

if (!function_exists('apply_replacements')) {
    /**
     * APLICA REEMPLAZOS AL TEXTO
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
