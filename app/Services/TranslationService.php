<?php

namespace App\Services;

use Google\Cloud\Translate\V3\TranslateTextRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;

class TranslationService
{
    protected $projectId;
    protected $apiKey;
    protected $sourceLang = 'es';
    protected $cacheTime = 2592000; // 30 días
    protected $maxChunkSize = 2800;

    public function __construct()
    {
        $this->projectId = config('services.google.project_id');
        $this->apiKey = config('services.google.translate_api_key');

        Log::info('TranslationService INICIADO', [
            'project_id' => $this->projectId,
            'api_key' => !empty($this->apiKey) ? 'CONFIGURADA' : 'NO CONFIGURADA',
            'ambiente' => app()->environment()
        ]);
    }

    /**
     * MÉTODO PRINCIPAL DE TRADUCCIÓN
     */
    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        try {
            // VALIDACIONES BÁSICAS
            if (empty(trim($text))) return $text;
            if (!is_string($text)) return $text;

            $targetLang = $targetLang ?? app()->getLocale();
            $sourceLang = $sourceLang ?? $this->sourceLang;

            // NORMALIZAR IDIOMAS
            $targetLang = $this->normalizeLanguage($targetLang);
            $sourceLang = $this->normalizeLanguage($sourceLang);

            // MISMO IDIOMA = NO TRADUCIR
            if ($targetLang === $sourceLang) return $text;

            // LOG PARA DEBUG
            Log::info('🔄 Traduciendo', [
                'longitud' => strlen($text),
                'target' => $targetLang,
                'primeros_50' => substr($text, 0, 50)
            ]);

            // CACHE
            $cacheKey = 'trans_' . md5($text . $targetLang . $sourceLang);
            $cached = Cache::get($cacheKey);
            if ($cached) return $cached;

            // DECIDIR MÉTODO SEGÚN LONGITUD
            $result = (strlen($text) > $this->maxChunkSize)
                ? $this->translateLargo($text, $targetLang, $sourceLang)
                : $this->translateCorto($text, $targetLang, $sourceLang);

            // GUARDAR EN CACHE
            Cache::put($cacheKey, $result, $this->cacheTime);

            return $result;

        } catch (Exception $e) {
            Log::error('❌ Error en translate: ' . $e->getMessage());
            return $text;
        }
    }

    /**
     * TRADUCCIÓN DE TEXTOS CORTOS (< 2800 caracteres)
     */
    protected function translateCorto($text, $targetLang, $sourceLang)
    {
        try {
            $client = new TranslationServiceClient([
                'apiKey' => $this->apiKey,
                'transport' => 'rest'
            ]);

            $parent = sprintf('projects/%s/locations/global', $this->projectId ?: 'traduccion-app');

            $request = new TranslateTextRequest();
            $request->setContents([$text]);
            $request->setTargetLanguageCode($targetLang);
            $request->setParent($parent);
            $request->setSourceLanguageCode($sourceLang);
            $request->setMimeType('text/plain');

            $response = $client->translateText($request);
            $translations = $response->getTranslations();

            if (count($translations) === 0) {
                return $text;
            }

            return $translations[0]->getTranslatedText();

        } catch (Exception $e) {
            Log::warning('⚠️ Error en translateCorto: ' . $e->getMessage());
            return $text;
        }
    }

    /**
     * TRADUCCIÓN DE TEXTOS LARGOS (> 2800 caracteres) - VERSIÓN PRODUCCIÓN
     */
    protected function translateLargo($text, $targetLang, $sourceLang)
    {
        try {
            Log::info('📏 translateLargo iniciado', ['longitud' => strlen($text)]);

            // ESTRATEGIA 1: DIVIDIR POR PÁRRAFOS (doble salto de línea)
            if (strpos($text, "\n\n") !== false) {
                $partes = explode("\n\n", $text);
                Log::info('Dividido por párrafos: ' . count($partes) . ' partes');
                return $this->procesarPartes($partes, "\n\n", $targetLang, $sourceLang);
            }

            // ESTRATEGIA 2: DIVIDIR POR ORACIONES (punto y espacio)
            if (strpos($text, '. ') !== false) {
                $partes = explode('. ', $text);
                // Reconstruir los puntos
                foreach ($partes as $key => $parte) {
                    if ($key < count($partes) - 1) {
                        $partes[$key] = $parte . '.';
                    }
                }
                Log::info('Dividido por oraciones: ' . count($partes) . ' partes');
                return $this->procesarPartes($partes, ' ', $targetLang, $sourceLang);
            }

            // ESTRATEGIA 3: DIVIDIR POR PUNTOS SEGUIDOS
            if (strpos($text, '.') !== false) {
                $partes = explode('.', $text);
                foreach ($partes as $key => $parte) {
                    if (!empty(trim($parte)) && $key < count($partes) - 1) {
                        $partes[$key] = $parte . '.';
                    }
                }
                Log::info('Dividido por puntos: ' . count($partes) . ' partes');
                return $this->procesarPartes($partes, ' ', $targetLang, $sourceLang);
            }

            // ESTRATEGIA 4: DIVIDIR POR CHUNKS (MÉTODO INFALIBLE)
            Log::info('Usando método de chunks');
            return $this->traducirPorChunks($text, $targetLang, $sourceLang);

        } catch (Exception $e) {
            Log::error('❌ Error en translateLargo: ' . $e->getMessage());
            return $this->traducirPorChunks($text, $targetLang, $sourceLang);
        }
    }

    /**
     * PROCESA PARTES DE TEXTO
     */
    protected function procesarPartes($partes, $separador, $targetLang, $sourceLang)
    {
        $traducidas = [];

        foreach ($partes as $index => $parte) {
            $parte = trim($parte);
            if (empty($parte)) continue;

            // Si la parte aún es larga, procesar recursivamente
            if (strlen($parte) > $this->maxChunkSize) {
                $traducidas[] = $this->translateLargo($parte, $targetLang, $sourceLang);
            } else {
                $traducidas[] = $this->translateCorto($parte, $targetLang, $sourceLang);
            }

            // Pausa entre partes
            if ($index < count($partes) - 1) {
                usleep(150000); // 150ms
            }
        }

        return implode($separador, $traducidas);
    }

    /**
     * MÉTODO INFALIBLE: DIVIDIR POR CHUNKS DE TAMAÑO FIJO
     */
    protected function traducirPorChunks($text, $targetLang, $sourceLang, $chunkSize = 2000)
    {
        $chunks = [];
        $longitud = strlen($text);

        for ($i = 0; $i < $longitud; $i += $chunkSize) {
            $chunks[] = substr($text, $i, $chunkSize);
        }

        Log::info('📦 traducirPorChunks: ' . count($chunks) . ' chunks');

        $traducidos = [];
        foreach ($chunks as $index => $chunk) {
            if (!empty(trim($chunk))) {
                $traducidos[] = $this->translateCorto($chunk, $targetLang, $sourceLang);

                if ($index < count($chunks) - 1) {
                    usleep(150000);
                }
            }
        }

        return implode('', $traducidos);
    }

    /**
     * NORMALIZA CÓDIGOS DE IDIOMA
     */
    protected function normalizeLanguage($lang)
    {
        $lang = strtolower($lang);

        $map = [
            'es' => 'es',
            'es-es' => 'es',
            'en' => 'en',
            'en-us' => 'en',
            'fr' => 'fr',
            'it' => 'it',
            'de' => 'de',
            'pt' => 'pt',
            'pt-br' => 'pt',
        ];

        return $map[$lang] ?? $lang;
    }

    /**
     * TRADUCCIÓN SEGURA
     */
    public function translateSafe($text, $targetLang = null)
    {
        $result = $this->translate($text, $targetLang);
        return !empty($result) ? $result : (string)$text;
    }

    public function setSourceLang($lang)
    {
        $this->sourceLang = $lang;
        return $this;
    }
}
