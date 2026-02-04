<?php

namespace App\Translators;
use Illuminate\Translation\Translator as BaseTranslator;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Log;

class GoogleFallbackTranslator extends BaseTranslator
{
    protected $translationService;

    public function __construct($loader, $locale, $translationService = null)
    {
        parent::__construct($loader, $locale);
        $this->translationService = $translationService ?? app(TranslationService::class);
    }

    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        // Obtener traducción normal
        $translation = parent::get($key, $replace, $locale, $fallback);

        // Si no se encontró la clave (devuelve la misma clave)
        if ($translation === $key) {
            // Solo usar auto-traducción si no está en modo desarrollo
            if (config('app.env') !== 'local') {
                try {
                    $autoTranslated = $this->translationService->translate(
                        $this->extractTextFromKey($key),
                        $locale ?: $this->locale
                    );

                    // Registrar en log para luego agregar a lang files
                    Log::channel('translations')->info('Auto-translated', [
                        'key' => $key,
                        'locale' => $locale ?: $this->locale,
                        'translation' => $autoTranslated
                    ]);

                    return $autoTranslated;
                } catch (\Exception $e) {
                    // Silenciosamente fallar al texto original
                }
            }
        }

        return $translation;
    }

    protected function extractTextFromKey($key)
    {
        // Si es formato "file.key", usar la última parte
        if (str_contains($key, '.')) {
            $parts = explode('.', $key);
            $text = end($parts);
            // Convertir a texto legible: "hello_world" -> "Hello world"
            return ucfirst(str_replace('_', ' ', $text));
        }

        return $key;
    }
}
