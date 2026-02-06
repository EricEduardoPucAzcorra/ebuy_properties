<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';
    protected string $defaultModel = 'gemini-3-flash-preview';
    protected array $defaultConfig = [
        'temperature' => 0.7,
        'maxOutputTokens' => 2048,
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));

        if (empty($this->apiKey)) {
            throw new \RuntimeException('Gemini API key no configurada');
        }
    }

    public function generateContent(string $prompt, ?string $model = null, ?array $config = null): ?string
    {
        $model = $model ?? $this->defaultModel;
        $generationConfig = array_merge($this->defaultConfig, $config ?? []);

        return $this->makeRequest($model, $prompt, $generationConfig);
    }

    private function makeRequest(string $model, string $prompt, array $generationConfig): ?string
    {
        try {
            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->post("{$this->baseUrl}{$model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => $generationConfig,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($data['candidates'][0]['content']['parts'][0]['text']);
                }

                if (isset($data['promptFeedback']['blockReason'])) {
                    throw new \RuntimeException('Contenido bloqueado: ' . $data['promptFeedback']['blockReason']);
                }

                Log::warning('Formato de respuesta inesperado', ['response' => $data]);
                return "La IA respondió en un formato inesperado.";
            }

            // Manejo de errores HTTP
            return $this->handleHttpError($response);

        } catch (ConnectionException $e) {
            Log::error('Error de conexión con Gemini: ' . $e->getMessage());
            return "Error de conexión con el servicio de IA. Intenta de nuevo.";

        } catch (\Exception $e) {
            Log::error('Error en GeminiService: ' . $e->getMessage());
            return "Ocurrió un error inesperado: " . $e->getMessage();
        }
    }

    private function handleHttpError($response): string
    {
        $statusCode = $response->status();
        $errorBody = $response->json();

        $errorMessage = $errorBody['error']['message'] ?? 'Error desconocido';

        Log::error("Gemini API Error [$statusCode]", [
            'message' => $errorMessage,
            'body' => $errorBody
        ]);

        return match ($statusCode) {
            400 => "Error en la solicitud: $errorMessage",
            401, 403 => "Error de autenticación con la API de Gemini.",
            429 => "Demasiadas solicitudes. Espera un momento antes de intentar de nuevo.",
            503 => "Servicio de IA temporalmente no disponible. Intenta más tarde.",
            default => "Error del servicio de IA ($statusCode): $errorMessage",
        };
    }
}
