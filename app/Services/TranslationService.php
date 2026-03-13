<?php

namespace App\Services;

use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    protected $client;
    protected $projectId;

    public function __construct()
    {
        $this->projectId = config('services.google.project_id');
        // El ajuste clave: Forzar REST para evitar el error 14 UNAVAILABLE
        $this->client = new TranslationServiceClient([
            'transport' => 'rest',
            'credentials' => storage_path('app/google-credentials.json')
            ]);
    }

    public function translate($text, $targetLang = 'es')
    {
        $text = trim($text);
        if (empty($text)) return '';

        // Definimos una clave única para este texto.
        // Si el texto ya se tradujo, no llamamos a Google (ahorro de dinero y tiempo).
        $cacheKey = 'trans_' . md5($text . $targetLang);

        return Cache::remember($cacheKey, 86400, function () use ($text, $targetLang) {
            try {
                $parent = sprintf('projects/%s/locations/global', $this->projectId);
                $request = new TranslateTextRequest([
                    'contents' => [$text],
                    'target_language_code' => $targetLang,
                    'parent' => $parent,
                    'mime_type' => 'text/plain',
                ]);

                $response = $this->client->translateText($request);
                return $response->getTranslations()[0]->getTranslatedText();
            } catch (\Exception $e) {
                Log::error('Error API Google: ' . $e->getMessage());
                return $text; // Retorna el original si falla para no romper la web
            }
        });
    }
}
