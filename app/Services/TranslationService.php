<?php

namespace App\Services;

use Google\Cloud\Translate\V3\TranslateTextRequest;
use Google\ApiCore\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient as ClientTranslationServiceClient;

class TranslationService
{
    protected $projectId;
    protected $credentialsPath;
    protected $sourceLang = 'es';
    protected $cacheTime = 2592000; // 30 días

    public function __construct()
    {
        $this->projectId = config('services.google.project_id');
        $credentialsFile = config('services.google.credentials_path', 'google-credentials.json');
        $this->credentialsPath = storage_path('app/' . $credentialsFile);

        Log::info('TranslationService V3 iniciado', [
            'project' => $this->projectId,
            'credenciales' => file_exists($this->credentialsPath) ? 'yes' : 'not',
            'ruta' => $this->credentialsPath
        ]);
    }

    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        try {
            if (empty(trim($text))) return $text;

            $targetLang = $targetLang ?? app()->getLocale();
            $sourceLang = $sourceLang ?? $this->sourceLang;

            if ($targetLang === $sourceLang) return $text;

            $cacheKey = 'trans_v3_' . md5($text . $targetLang . $sourceLang);

            $cached = Cache::get($cacheKey);
            if ($cached) return $cached;

            if (strlen($text) > 5000) {
                return $this->translateLongText($text, $targetLang, $sourceLang);
            }

            $result = $this->translateWithV3($text, $targetLang, $sourceLang);

            Cache::put($cacheKey, $result, $this->cacheTime);

            return $result;

        } catch (Exception $e) {
            Log::error('Error en translate (recuperado): ' . $e->getMessage());
            return $text;
        }
    }

    protected function translateWithV3($text, $targetLang, $sourceLang)
    {
        try {
            if (!file_exists($this->credentialsPath)) {
                throw new Exception('Archivo de credenciales no encontrado');
            }

            $credentials = json_decode(file_get_contents($this->credentialsPath), true);

            if (!$credentials) {
                throw new Exception('Error al decodificar JSON de credenciales');
            }

            // ⚡ CONFIGURACIÓN ULTRA RÁPIDA
            $client = new ClientTranslationServiceClient([
                'credentials' => $credentials,
                'transport' => 'rest',
                'clientConfig' => [
                    'timeoutMillis' => 3000,
                    'retrySettings' => [
                        'maxRetries' => 0
                    ]
                ]
            ]);

            $parent = sprintf('projects/%s/locations/global', $this->projectId);

            $request = new TranslateTextRequest();
            $request->setContents([$text]);
            $request->setTargetLanguageCode($targetLang);
            $request->setParent($parent);
            $request->setSourceLanguageCode($sourceLang);
            $request->setMimeType('text/plain');

            $response = $client->translateText($request);
            $translations = $response->getTranslations();

            $client->close();

            if (count($translations) === 0) {
                return $text; // NUNCA FALLA
            }

            $translatedText = $translations[0]->getTranslatedText();

            return html_entity_decode($translatedText, ENT_QUOTES, 'UTF-8');

        } catch (Exception $e) {
            Log::warning('V3 timeout/error, usando texto original', [
                'texto' => substr($text, 0, 50)
            ]);
            return $text;
        }
    }

    public function translateLongText($text, $targetLang, $sourceLang)
    {
        try {
            if (strlen($text) <= 5000) {
                return $this->translateWithV3($text, $targetLang, $sourceLang);
            }

            $parts = preg_split('/\n\n+/', $text, -1, PREG_SPLIT_NO_EMPTY);

            if (count($parts) < 2) {
                $parts = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $text, -1, PREG_SPLIT_NO_EMPTY);
            }

            if (count($parts) < 2) {
                $parts = str_split($text, 3000);
            }

            $results = [];
            $batchSize = 3;
            $batches = array_chunk($parts, $batchSize);

            foreach ($batches as $batch) {
                $batchResults = [];
                foreach ($batch as $part) {
                    if (!empty(trim($part))) {
                        $batchResults[] = $this->translateWithV3($part, $targetLang, $sourceLang);
                    }
                }
                $results = array_merge($results, $batchResults);

                if (count($batches) > 1) {
                    usleep(100000);
                }
            }

            return implode("\n\n", $results);

        } catch (Exception $e) {
            Log::error('Error en texto largo, devolviendo original');
            return $text;
        }
    }

    public function translateSafe($text, $targetLang = null)
    {
        $result = $this->translate($text, $targetLang);
        return !empty($result) ? $result : $text;
    }

    public function setSourceLang($lang)
    {
        $this->sourceLang = $lang;
        return $this;
    }
}
