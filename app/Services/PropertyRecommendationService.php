<?php

namespace App\Services;

use App\Models\Propertie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PropertyRecommendationService
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function getRecommendedIds(int $limit = 4): array
    {
        return Cache::remember('ai_recommended_ids', 3600, function () use ($limit) {
            try {
                $candidates = Propertie::where('is_active', true)
                    ->orderBy('price', 'asc')
                    ->where('price', '=<', '10,000')
                    ->limit(20)
                    ->get(['id', 'title', 'price']);

                if ($candidates->isEmpty()) return [];

                $prompt = "Analiza estas propiedades y selecciona los {$limit} IDs con mejor precio: " . $candidates->toJson() .
                         ". Responde solo el array JSON, ejemplo: [1,2,3]";

                         $response = $this->gemini->generateContent($prompt);

                if (str_contains($response, '429') || empty($response)) {
                    Log::warning("Gemini Rate Limit alcanzado o respuesta vacía.");
                    return $this->getLocalFallback($candidates, $limit);
                }

                if (preg_match('/\[.*\]/s', $response, $matches)) {
                    $ids = json_decode($matches[0], true);
                    if (is_array($ids)) return array_map('intval', $ids);
                }

                return $this->getLocalFallback($candidates, $limit);

            } catch (\Exception $e) {
                Log::error("Error en PropertyRecommendationService: " . $e->getMessage());
                return [];
            }
        });
    }

    private function getLocalFallback($candidates, $limit)
    {
        return $candidates->pluck('id')->take($limit)->toArray();
    }
}
