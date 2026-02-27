<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected $apiKey;
    protected $sourceLang = 'es';
    protected $cacheTime = 2592000; // 30 días

    public function __construct()
    {
        $this->apiKey = config('services.google.translate_api_key');

        if (!$this->apiKey) {
            Log::error('Google Translate API Key no configurada');
        }
    }

    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        $targetLang = $targetLang ?? app()->getLocale();
        $sourceLang = $sourceLang ?? $this->sourceLang;

        if ($targetLang === $sourceLang || empty(trim($text))) {
            return $text;
        }

        $cacheKey = 'translation.' . md5($text . $sourceLang . $targetLang);

        return Cache::remember($cacheKey, $this->cacheTime, function () use ($text, $targetLang, $sourceLang) {
            try {
                $response = Http::retry(2, 100)
                    ->timeout(10)
                    ->get('https://translation.googleapis.com/language/translate/v2', [
                        'q' => $text,
                        'target' => $targetLang,
                        'source' => $sourceLang,
                        'key' => $this->apiKey,
                        'format' => 'text'
                    ]);

                if (!$response->successful()) {
                    Log::warning('Error en respuesta de Google Translate', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return $text;
                }

                $data = $response->json();

                // Verificar que la respuesta tenga el formato esperado
                if (!isset($data['data']['translations'][0]['translatedText'])) {
                    Log::warning('Respuesta de Google Translate con formato inesperado', [
                        'data' => $data
                    ]);
                    return $text;
                }

                $translation = $data['data']['translations'][0]['translatedText'];

                // Decodificar caracteres HTML (Google los escapa por seguridad)
                $translation = html_entity_decode($translation, ENT_QUOTES, 'UTF-8');

                if (empty($translation) || $translation === $text) {
                    return $text;
                }

                return $translation;

            } catch (\Exception $e) {
                Log::error('Error en traducción automática: ' . $e->getMessage(), [
                    'text' => substr($text, 0, 100),
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
