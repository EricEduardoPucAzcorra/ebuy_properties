<?php

namespace App\Services;

use Google\Cloud\Translate\V3\TranslateTextRequest;
use Google\ApiCore\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;

class TranslationService
{
    protected $projectId;
    protected $credentialsPath;
    protected $sourceLang = 'es';
    protected $cacheTime = 2592000; // 30 días
    protected $client = null;
    protected $maxChunkSize = 2800; // Google recomienda 3000, usamos 2800 por seguridad

    public function __construct()
    {
        $this->projectId = config('services.google.project_id');
        $this->initializeCredentials();

        Log::info('TranslationService inicializado', [
            'project' => $this->projectId,
            'credenciales' => file_exists($this->credentialsPath) ? '✓' : '✗',
            'ruta' => $this->credentialsPath,
            'ambiente' => app()->environment()
        ]);
    }

    /**
     * Inicializa las credenciales buscando en múltiples ubicaciones
     */
    protected function initializeCredentials()
    {
        $credentialsFile = config('services.google.credentials_path', 'google-credentials.json');

        // Posibles ubicaciones del archivo de credenciales
        $possiblePaths = [
            storage_path('app/' . $credentialsFile),
            base_path($credentialsFile),
            storage_path('app/google-credentials.json'),
            base_path('google-credentials.json'),
            env('GOOGLE_APPLICATION_CREDENTIALS', '')
        ];

        foreach ($possiblePaths as $path) {
            if (!empty($path) && file_exists($path)) {
                $this->credentialsPath = $path;
                break;
            }
        }

        // Si no encuentra, usar la ruta por defecto
        if (!isset($this->credentialsPath)) {
            $this->credentialsPath = storage_path('app/google-credentials.json');
        }
    }

    /**
     * Obtiene el cliente de traducción
     */
    protected function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        try {
            // Intentar con API Key primero (más simple para producción)
            if (config('services.google.api_key')) {
                $this->client = new TranslationServiceClient([
                    'apiKey' => config('services.google.api_key'),
                    'transport' => 'rest'
                ]);

                Log::info('Usando API Key para autenticación');
                return $this->client;
            }

            // Si no hay API Key, usar credenciales JSON
            if (!file_exists($this->credentialsPath)) {
                throw new Exception('Archivo de credenciales no encontrado en: ' . $this->credentialsPath);
            }

            $credentialsContent = file_get_contents($this->credentialsPath);
            $credentials = json_decode($credentialsContent, true);

            if (!$credentials) {
                throw new Exception('Credenciales JSON inválidas');
            }

            // Usar project_id de las credenciales si no está configurado
            if (empty($this->projectId) && isset($credentials['project_id'])) {
                $this->projectId = $credentials['project_id'];
            }

            $this->client = new TranslationServiceClient([
                'credentials' => $credentials,
                'transport' => 'rest',
            ]);

            Log::info('Usando credenciales JSON para autenticación');
            return $this->client;

        } catch (Exception $e) {
            Log::error('Error creando cliente de traducción: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Método principal de traducción
     */
    public function translate($text, $targetLang = null, $sourceLang = null)
    {
        try {
            // Validaciones básicas
            if (empty(trim($text))) return $text;
            if (!is_string($text)) return $text;

            $targetLang = $targetLang ?? app()->getLocale();
            $sourceLang = $sourceLang ?? $this->sourceLang;

            // Normalizar códigos de idioma
            $targetLang = $this->normalizeLanguageCode($targetLang);
            $sourceLang = $this->normalizeLanguageCode($sourceLang);

            // Si es el mismo idioma, no traducir
            if ($targetLang === $sourceLang) return $text;

            // Cache key
            $cacheKey = 'trans_v3_' . md5($text . $targetLang . $sourceLang);

            // Intentar obtener de caché
            $cached = Cache::get($cacheKey);
            if ($cached) return $cached;

            // Determinar método según longitud
            $result = (strlen($text) > $this->maxChunkSize)
                ? $this->translateLongText($text, $targetLang, $sourceLang)
                : $this->translateWithV3($text, $targetLang, $sourceLang);

            // Guardar en caché
            Cache::put($cacheKey, $result, $this->cacheTime);

            return $result;

        } catch (Exception $e) {
            Log::error('Error en translate: ' . $e->getMessage(), [
                'texto' => substr($text, 0, 100)
            ]);
            return $text;
        }
    }

    /**
     * Traducción directa con V3
     */
    protected function translateWithV3($text, $targetLang, $sourceLang)
    {
        try {
            $client = $this->getClient();

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
            Log::warning('Error en translateWithV3: ' . $e->getMessage(), [
                'texto' => substr($text, 0, 50)
            ]);
            return $text;
        }
    }

    /**
     * Traducción de textos largos (corregido)
     */
    public function translateLongText($text, $targetLang, $sourceLang)
    {
        try {
            // 1. Dividir por párrafos
            $paragraphs = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);

            if (count($paragraphs) > 1) {
                return $this->translateParagraphs($paragraphs, $targetLang, $sourceLang);
            }

            // 2. Dividir por oraciones
            $sentences = $this->splitSentences($text);

            if (count($sentences) > 3) {
                return $this->translateSentences($sentences, $targetLang, $sourceLang);
            }

            // 3. Dividir por chunks
            return $this->translateByChunks($text, $targetLang, $sourceLang);

        } catch (Exception $e) {
            Log::error('Error en translateLongText: ' . $e->getMessage());
            return $this->translateByChunks($text, $targetLang, $sourceLang);
        }
    }

    /**
     * Traduce párrafos
     */
    protected function translateParagraphs($paragraphs, $targetLang, $sourceLang)
    {
        $translated = [];
        foreach ($paragraphs as $index => $paragraph) {
            if (empty(trim($paragraph))) continue;

            if (strlen($paragraph) > $this->maxChunkSize) {
                $translated[] = $this->translateLongText($paragraph, $targetLang, $sourceLang);
            } else {
                $translated[] = $this->translateWithV3($paragraph, $targetLang, $sourceLang);
            }

            // Pausa entre párrafos
            if ($index < count($paragraphs) - 1) {
                usleep(150000);
            }
        }

        return implode("\n\n", $translated);
    }

    /**
     * Traduce oraciones
     */
    protected function translateSentences($sentences, $targetLang, $sourceLang)
    {
        $translated = [];
        $batch = [];
        $batchSize = 0;

        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (empty($sentence)) continue;

            // Agrupar oraciones pequeñas
            if ($batchSize + strlen($sentence) < $this->maxChunkSize) {
                $batch[] = $sentence;
                $batchSize += strlen($sentence);
            } else {
                // Traducir batch actual
                if (!empty($batch)) {
                    $translated[] = $this->translateWithV3(implode(' ', $batch), $targetLang, $sourceLang);
                    usleep(100000);
                }

                // Nuevo batch
                $batch = [$sentence];
                $batchSize = strlen($sentence);
            }
        }

        // Traducir último batch
        if (!empty($batch)) {
            $translated[] = $this->translateWithV3(implode(' ', $batch), $targetLang, $sourceLang);
        }

        return implode(' ', $translated);
    }

    /**
     * Traduce por chunks de tamaño fijo
     */
    protected function translateByChunks($text, $targetLang, $sourceLang, $chunkSize = 2500)
    {
        $words = explode(' ', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($words as $word) {
            if (strlen($currentChunk) + strlen($word) + 1 > $chunkSize) {
                if (!empty($currentChunk)) {
                    $chunks[] = $currentChunk;
                }
                $currentChunk = $word;
            } else {
                $currentChunk .= (empty($currentChunk) ? '' : ' ') . $word;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        $translated = [];
        foreach ($chunks as $index => $chunk) {
            if (!empty(trim($chunk))) {
                $translated[] = $this->translateWithV3($chunk, $targetLang, $sourceLang);

                if ($index < count($chunks) - 1) {
                    usleep(150000);
                }
            }
        }

        return implode(' ', $translated);
    }

    /**
     * Divide texto en oraciones
     */
    protected function splitSentences($text)
    {
        // Patrón mejorado para español
        $pattern = '/(?<=[.?!])\s+(?=[A-ZÁÉÍÓÚÑ])/';
        $sentences = preg_split($pattern, $text, -1, PREG_SPLIT_NO_EMPTY);

        if (count($sentences) < 2) {
            // Si no funciona, dividir por puntos seguidos de espacio
            $sentences = explode('. ', $text);
        }

        return array_map('trim', $sentences);
    }

    /**
     * Normaliza códigos de idioma
     */
    protected function normalizeLanguageCode($lang)
    {
        $map = [
            'es' => 'es',
            'en' => 'en',
            'fr' => 'fr',
            'it' => 'it',
            'de' => 'de',
            'pt' => 'pt',
            'pt-br' => 'pt',
            'en-us' => 'en',
            'es-es' => 'es',
        ];

        $lang = strtolower($lang);
        return $map[$lang] ?? $lang;
    }

    /**
     * Traducción segura (siempre devuelve string)
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
