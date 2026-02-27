<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected $translator;
    protected $sourceLang = 'es'; // Idioma base
    protected $cacheTime = 2592000; // 30 días en segundos

    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        $targetLang = $targetLang ?? app()->getLocale();
        $sourceLang = $sourceLang ?? $this->sourceLang;

        if ($targetLang === $sourceLang) {
            return $text;
        }

        $cacheKey = 'translation.' . md5($text . $sourceLang . $targetLang);

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($text, $targetLang, $sourceLang) {
            try {
                $translator = new GoogleTranslate($targetLang);
                $translator->setSource($sourceLang);

                // Configurar timeout y reintentos
                $translator->setOptions([
                    'timeout' => 10,
                    'retries' => 2,
                ]);

                $translated = $translator->translate($text);

                // Validar que no devuelva el mismo texto (error silencioso)
                if ($translated === $text || empty($translated)) {
                    Log::warning('Traducción fallida o vacía', [
                        'text' => $text,
                        'source' => $sourceLang,
                        'target' => $targetLang
                    ]);
                    return $text;
                }

                return $translated;

            } catch (\Exception $e) {
                Log::error('Error en traducción automática: ' . $e->getMessage(), [
                    'text' => $text,
                    'target_lang' => $targetLang
                ]);

                return $text; // Fallback al texto original
            }
        });
    }

    public function setSourceLang($lang)
    {
        $this->sourceLang = $lang;
        return $this;
    }
}
