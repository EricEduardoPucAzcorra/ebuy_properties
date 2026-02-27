<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected $apiKey;
    protected $projectId;
    protected $sourceLang = 'es';
    protected $cacheTime = 2592000; // 30 días

    public function __construct()
    {
        $this->apiKey = config('services.google.translate_api_key');
        $this->projectId = config('services.google.project_id');

        if (!$this->apiKey || !$this->projectId) {
            Log::error('Google Translate: Credenciales no configuradas', [
                'apiKey' => $this->apiKey ? '✓' : '✗',
                'projectId' => $this->projectId ? '✓' : '✗'
            ]);
        }
    }

    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        $targetLang = $targetLang ?? app()->getLocale();
        $sourceLang = $sourceLang ?? $this->sourceLang;

        // Validaciones rápidas
        if ($targetLang === $sourceLang || empty(trim($text))) {
            return $text;
        }

        // Para textos muy largos, cache más corto
        $cacheTime = strlen($text) > 1000 ? 3600 : $this->cacheTime;
        $cacheKey = 'translation.v3.' . md5($text . $sourceLang . $targetLang);

        return Cache::remember($cacheKey, $cacheTime, function () use ($text, $targetLang, $sourceLang) {
            try {
                // Construir URL correcta para v3
                $url = sprintf(
                    'https://translation.googleapis.com/v3/projects/%s/locations/global:translateText',
                    $this->projectId
                );

                // Hacer la petición POST
                $response = Http::retry(2, 100)
                    ->timeout(15) // Timeout más largo para textos largos
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $this->apiKey
                    ])
                    ->post($url, [
                        'contents' => [$text],
                        'targetLanguageCode' => $targetLang,
                        'sourceLanguageCode' => $sourceLang,
                        'mimeType' => 'text/plain'
                    ]);

                // Verificar si la respuesta fue exitosa
                if (!$response->successful()) {
                    Log::warning('Error en Google Translate v3', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'text_length' => strlen($text)
                    ]);

                    // Intentar con método alternativo si falla
                    return $this->fallbackTranslate($text, $targetLang, $sourceLang);
                }

                $data = $response->json();

                // Extraer traducción
                $translation = $data['translations'][0]['translatedText'] ?? $text;

                // Decodificar caracteres HTML
                $translation = html_entity_decode($translation, ENT_QUOTES, 'UTF-8');

                return $translation;

            } catch (\Exception $e) {
                Log::error('Excepción en traducción v3: ' . $e->getMessage(), [
                    'text_length' => strlen($text),
                    'target' => $targetLang
                ]);

                return $this->fallbackTranslate($text, $targetLang, $sourceLang);
            }
        });
    }

    /**
     * Método de fallback usando v2 (más permisivo)
     */
    protected function fallbackTranslate($text, $targetLang, $sourceLang)
    {
        // Solo intentar fallback para textos no muy largos
        if (strlen($text) > 5000) {
            return $text;
        }

        try {
            $response = Http::retry(1, 100)
                ->timeout(10)
                ->asForm()
                ->post('https://translation.googleapis.com/language/translate/v2', [
                    'q' => $text,
                    'target' => $targetLang,
                    'source' => $sourceLang,
                    'key' => $this->apiKey,
                    'format' => 'text'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['data']['translations'][0]['translatedText'] ?? $text;
            }
        } catch (\Exception $e) {
            Log::warning('Fallback también falló', ['error' => $e->getMessage()]);
        }

        return $text;
    }

    /**
     * Método específico para textos muy largos
     */
    public function translateLongText($text, $targetLang = null, $sourceLang = null)
    {
        $targetLang = $targetLang ?? app()->getLocale();
        $sourceLang = $sourceLang ?? $this->sourceLang;

        // Si no es muy largo, usar método normal
        if (strlen($text) < 2000) {
            return $this->translate($text, $targetLang, $sourceLang);
        }

        // Dividir en párrafos
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $translated = [];
        $total = count($paragraphs);

        foreach ($paragraphs as $index => $paragraph) {
            if (trim($paragraph)) {
                // Traducir cada párrafo
                $translated[] = $this->translate($paragraph, $targetLang, $sourceLang);

                // Pequeña pausa entre párrafos para no saturar
                if ($index < $total - 1) {
                    usleep(200000); // 0.2 segundos
                }
            } else {
                $translated[] = $paragraph;
            }
        }

        return implode("\n\n", $translated);
    }

    /**
     * Detectar idioma del texto
     */
    public function detectLanguage($text)
    {
        if (empty(trim($text))) {
            return null;
        }

        try {
            $response = Http::retry(1, 100)
                ->timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey
                ])
                ->post("https://translation.googleapis.com/v3/projects/{$this->projectId}/locations/global:detectLanguage", [
                    'content' => $text
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['languages'][0]['languageCode'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Error detectando idioma', ['error' => $e->getMessage()]);
        }

        return null;
    }

    public function setSourceLang($lang)
    {
        $this->sourceLang = $lang;
        return $this;
    }
}
