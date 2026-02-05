<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveSubscription
{
    public function handle(Request $request, Closure $next, $feature = null)
    {
        $user = Auth::user();

        if (!$user->hasActiveSubscription()) {
            return response()->json([
                'success' => false,
                'error' => 'Se requiere una suscripción activa para acceder a esta funcionalidad'
            ], 403);
        }

        // Verificar feature específico si se especifica
        if ($feature) {
            switch ($feature) {
                case 'publish_property':
                    if (!$user->canPublishProperty()) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Tu plan actual no permite publicar más propiedades'
                        ], 403);
                    }
                    break;

                case 'create_property':
                    if (!$user->canCreateMoreProperties()) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Has alcanzado el límite de propiedades para tu plan actual'
                        ], 403);
                    }
                    break;
            }
        }

        return $next($request);
    }
}
