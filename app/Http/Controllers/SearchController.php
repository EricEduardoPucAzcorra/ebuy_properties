<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = strtolower(trim($request->get('q', '')));
        $locale = app()->getLocale();

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = collect();

        // 1. Buscar en menús principales
        $menuResults = $this->searchInMenus($query, $locale);
        $results = $results->merge($menuResults);

        // 2. Buscar en items de menú
        $menuItemResults = $this->searchInMenuItems($query, $locale);
        $results = $results->merge($menuItemResults);

        // 3. Filtrar y ordenar
        $results = $results
            ->filter(function ($item) {
                return $item['relevance'] > 0;
            })
            ->sortByDesc('relevance')
            ->take(10)
            ->values()
            ->all();

        return response()->json([
            'results' => $results,
            'query' => $query,
            'locale' => $locale
        ]);
    }

    private function searchInMenus($query, $locale)
    {
        return Menu::with('items')
            ->where('is_active', true)
            ->where('route', '!=', '')
            ->get()
            ->map(function ($menu) use ($query, $locale) {
                $menuKey = $menu->title; // Ej: 'dashboard'
                $translatedTitle = __('menu.' . $menuKey); // Ej: 'Panel de Control' o 'Dashboard'

                // Calcular relevancia en ambos idiomas
                $relevance = $this->calculateRelevanceForBilingual(
                    $menuKey,      // Texto original (inglés)
                    $translatedTitle, // Texto traducido
                    $query
                );

                // Si este menú tiene items que coinciden, aumentar relevancia
                $childRelevance = $this->getChildItemsRelevance($menu, $query, $locale);
                $relevance = max($relevance, $childRelevance);

                return [
                    'id' => 'menu_' . $menu->id,
                    'title' => $translatedTitle,
                    'original_key' => $menuKey,
                    'url' => $menu->route ? url($menu->route) : '#',
                    'type' => 'menu',
                    'category' => __('general.main_menu'),
                    'icon' => $menu->icon ?? 'bi-folder',
                    'description' => __('menu.' . $menuKey . '_desc') ?? $this->getDefaultDescription($menuKey),
                    'relevance' => $relevance,
                    'has_items' => $menu->items->count() > 0
                ];
            });
    }

    private function searchInMenuItems($query, $locale)
    {
        return MenuItem::with('menu')
            ->where('is_active', true)
            ->get()
            ->map(function ($item) use ($query, $locale) {
                $itemKey = $item->title; // Ej: 'user_list'
                $translatedTitle = __('menu.' . $itemKey); // Ej: 'Lista de Usuarios'
                $menuTitle = __('menu.' . $item->menu->title);

                // Calcular relevancia
                $relevance = $this->calculateRelevanceForBilingual(
                    $itemKey,
                    $translatedTitle,
                    $query
                );

                return [
                    'id' => 'menu_item_' . $item->id,
                    'title' => $translatedTitle,
                    'original_key' => $itemKey,
                    'url' => url($item->route),
                    'type' => 'page',
                    'category' => $menuTitle,
                    'icon' => $item->icon ?? 'bi-file',
                    'description' => $this->getDefaultDescription($itemKey),
                    'relevance' => $relevance,
                    'parent_menu' => $item->menu->title
                ];
            });
    }

    /**
     * Calcular relevancia considerando ambos idiomas
     */
    private function calculateRelevanceForBilingual($englishText, $translatedText, $query)
    {
        $relevanceEnglish = $this->calculateSingleRelevance($englishText, $query);
        $relevanceTranslated = $this->calculateSingleRelevance($translatedText, $query);

        // Si coincide en español, darle prioridad (asumiendo que el usuario busca en español)
        if ($relevanceTranslated > 0) {
            return $relevanceTranslated + 5; // Bonus por coincidencia en español
        }

        return $relevanceEnglish;
    }

    /**
     * Calcular relevancia para un solo texto
     */
    private function calculateSingleRelevance($text, $query)
    {
        if (empty($text)) return 0;

        $text = strtolower(trim($text));

        // 1. Coincidencia exacta
        if ($text === $query) {
            return 100;
        }

        // 2. Coincide al inicio
        if (strpos($text, $query) === 0) {
            return 95;
        }

        // 3. Contiene la cadena completa
        if (strpos($text, $query) !== false) {
            return 90;
        }

        // 4. Buscar palabras individuales
        $queryWords = preg_split('/\s+/', $query);
        $textWords = preg_split('/\s+/', $text);

        $matches = 0;
        foreach ($queryWords as $qWord) {
            if (strlen($qWord) < 2) continue;

            foreach ($textWords as $tWord) {
                if ($tWord === $qWord) {
                    $matches += 2; // Coincidencia exacta de palabra
                    break;
                } elseif (strpos($tWord, $qWord) !== false) {
                    $matches += 1; // Coincidencia parcial
                    break;
                }
            }
        }

        if ($matches > 0) {
            return 50 + ($matches * 10); // Base 50 + 10 por cada coincidencia
        }

        // 5. Búsqueda fonética o similar (solo para español)
        if (app()->getLocale() === 'es') {
            $similar = $this->checkSimilarity($text, $query);
            if ($similar) {
                return 40;
            }
        }

        return 0;
    }

    /**
     * Verificar similitud fonética para español
     */
    private function checkSimilarity($text, $query)
    {
        $similarities = [
            'panel' => ['dashboard', 'control', 'principal'],
            'usuario' => ['user', 'users'],
            'producto' => ['product', 'products'],
            'venta' => ['sale', 'sales'],
            'reporte' => ['report', 'reports'],
            'configuración' => ['setting', 'settings'],
            'sistema' => ['system'],
        ];

        foreach ($similarities as $spanish => $englishWords) {
            if (strpos($text, $spanish) !== false && in_array($query, $englishWords)) {
                return true;
            }
            if (strpos($query, $spanish) !== false && in_array($text, $englishWords)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtener relevancia de items hijos
     */
    private function getChildItemsRelevance($menu, $query, $locale)
    {
        if ($menu->items->isEmpty()) {
            return 0;
        }

        $maxChildRelevance = 0;
        foreach ($menu->items as $item) {
            $itemKey = $item->title;
            $translatedTitle = __('menu.' . $itemKey);

            $relevance = $this->calculateRelevanceForBilingual($itemKey, $translatedTitle, $query);
            $maxChildRelevance = max($maxChildRelevance, $relevance);
        }

        // Si hay items hijos que coinciden, dar relevancia al menú padre
        if ($maxChildRelevance > 0) {
            return $maxChildRelevance - 10; // Menor que los items directos
        }

        return 0;
    }

    /**
     * Descripciones por defecto
     */
    private function getDefaultDescription($key)
    {
        $descriptions = [
            'dashboard' => 'Panel de control principal',
            'users' => 'Gestión de usuarios',
            'products' => 'Administración de productos',
            'sales' => 'Ventas y transacciones',
            'reports' => 'Reportes y estadísticas',
            'settings' => 'Configuración del sistema',
            'user_list' => 'Lista completa de usuarios',
            'add_user' => 'Agregar nuevo usuario',
            'product_list' => 'Ver todos los productos',
            'add_product' => 'Crear nuevo producto',
            'sales_list' => 'Historial de ventas',
            'new_sale' => 'Registrar nueva venta',
            'reports_sales' => 'Reportes de ventas detallados',
            'reports_users' => 'Estadísticas de usuarios',
        ];

        return $descriptions[$key] ?? '';
    }
}
