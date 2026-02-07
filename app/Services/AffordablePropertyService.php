<?php

namespace App\Services;

use App\Models\Propertie;
use Illuminate\Support\Facades\Log;

class AffordablePropertyService
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function getAffordableProperties(int $limit = 3)
    {
        // 1. Buscamos propiedades candidatas (filtramos un rango razonable en DB)
        $properties = Propertie::where('is_active', true)
            ->where('price', '<', 50000) // Un techo un poco más alto para que la IA compare
            ->withCount('images')
            ->orderBy('price', 'asc')
            ->limit(20)
            ->get();

        if ($properties->isEmpty()) return collect();

        // 2. Formatear datos mínimos para el prompt
        $dataForAi = $properties->map(fn($p) => [
            'id' => $p->id,
            't'  => $p->title,
            'p'  => (float)$p->price,
            'img'=> $p->images_count
        ])->toArray();

        $prompt = "Actúa como experto inmobiliario. De este JSON, selecciona los ID de las {$limit} mejores propiedades con precio < 10000.
        Prioriza: 1. Precio bajo, 2. Más imágenes.
        Responde estrictamente un array de IDs: [1, 2, 3]. Si no hay ninguna < 10000, responde: []
        DATOS: " . json_encode($dataForAi);

        // 3. Llamada y DEBUG para ver la respuesta de Gemini
        $response = $this->gemini->generateContent($prompt);

        echo "\n--- RESPUESTA CRUDA DE GEMINI ---\n";
        dump($response); // Esto imprimirá el string exacto que viene de la IA
        echo "---------------------------------\n";

        // 4. Procesar IDs (Extracción simple de números entre corchetes)
        preg_match_all('/\d+/', $response, $matches);
        $ids = array_map('intval', $matches[0] ?? []);

        // 5. Retornar modelos hidratados
        return Propertie::whereIn('id', $ids)
            ->with(['type', 'operation', 'address.city', 'images'])
            ->get();
    }
}
