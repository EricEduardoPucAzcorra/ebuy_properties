<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\Propertie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Agregar una propiedad a favoritos
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id'
        ]);

        $propertyId = $request->property_id;
        $sessionId = session()->getId();
        
        // Verificar si ya existe en la tabla favorites
        $existingFavorite = Favorites::where('session_id', $sessionId)
            ->where('property_id', $propertyId)
            ->first();

        if ($existingFavorite) {
            // Eliminar de favoritos
            $existingFavorite->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Propiedad eliminada de favoritos',
                'is_favorited' => false
            ]);
        } else {
            // Agregar a favoritos
            Favorites::create([
                'session_id' => $sessionId,
                'property_id' => $propertyId,
                'user_id' => Auth::check() ? Auth::id() : null
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => '¡Propiedad agregada a favoritos!',
                'is_favorited' => true
            ]);
        }
    }

    /**
     * Verificar si una propiedad es favorita del usuario actual
     */
    public function check($propertyId)
    {
        $sessionId = session()->getId();
        
        // Verificar si este usuario ya le ha dado clic antes
        $hasClicked = Favorites::isFavoritedBySession($sessionId, $propertyId);

        return response()->json([
            'is_favorited' => $hasClicked
        ]);
    }

    /**
     * Obtener los favoritos del usuario
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Debes iniciar sesión para ver tus favoritos');
        }

        $favorites = Auth::user()->favoriteProperties()
            ->with([
                'images' => function ($q) {
                    $q->orderByDesc('is_main')->orderBy('order');
                },
                'address.city.state.country',
                'type',
                'operation'
            ])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('site.favorites', compact('favorites'));
    }

    /**
     * Obtener conteo de favoritos para una propiedad
     */
    private function getFavoritesCount($propertyId)
    {
        return Favorites::where('property_id', $propertyId)->count();
    }

    /**
     * Eliminar una propiedad de favoritos
     */
    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión'
            ], 401);
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id'
        ]);

        $deleted = Favorites::where('user_id', Auth::id())
            ->where('property_id', $request->property_id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Propiedad eliminada de favoritos',
                'favorites_count' => $this->getFavoritesCount($request->property_id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se pudo eliminar la propiedad de favoritos'
        ], 400);
    }
}
