<?php

use App\Services\TranslationService;

if (!function_exists('auto_trans')) {
    function auto_trans($text, $replace = [], $locale = null)
    {
        // Si es null o vacío, retornar igual
        if (is_null($text) || $text === '') {
            return $text;
        }

        // Si es un array, traducir cada elemento
        if (is_array($text)) {
            $result = [];
            foreach ($text as $key => $value) {
                $result[$key] = auto_trans($value, $replace, $locale);
            }
            return $result;
        }

        // Para textos muy largos, usar método especial
        if (is_string($text) && strlen($text) > 2000) {
            return app(TranslationService::class)->translateLongText($text, $locale);
        }

        // Primero intentar con Laravel
        $translation = trans($text, $replace, $locale);

        // Si no existe o es el mismo texto, usar traducción automática
        if ($translation === $text) {
            $translation = app(TranslationService::class)->translate($text, $locale);

            // Aplicar reemplazos de manera más eficiente
            if (!empty($replace)) {
                $search = [];
                $replaceValues = [];

                foreach ($replace as $key => $value) {
                    $search[] = ':' . $key;
                    $search[] = ':' . strtoupper($key);
                    $search[] = ':' . ucfirst($key);
                    $search[] = '{' . $key . '}'; // Formato adicional

                    // Repetir el valor para cada búsqueda
                    $replaceValues = array_merge($replaceValues, [$value, $value, $value, $value]);
                }

                $translation = str_replace($search, $replaceValues, $translation);
            }
        }

        return $translation;
    }
}

// Helper adicional para detectar idioma
if (!function_exists('detect_lang')) {
    function detect_lang($text)
    {
        return app(TranslationService::class)->detectLanguage($text);
    }
}
