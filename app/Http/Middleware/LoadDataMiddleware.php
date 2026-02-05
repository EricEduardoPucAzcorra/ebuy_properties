<?php
namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class LoadDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Solo si hay conexión a DB
        try {
            DB::connection()->getPdo();

            // 1. Cargar Permissions (Gates)
            if (Schema::hasTable('permissions')) {
                Permission::pluck('name')->each(function ($name) {
                    Gate::define($name, function ($user) use ($name) {
                        return $user->hasPermission($name);
                    });
                });
            }

            // 2. Cargar Menús para vistas
            if (Auth::check() && Schema::hasTable('menus')) {
                $menus = Menu::with('module')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->with('items');

                if(Auth::user()->hasRoleName('Owner')){
                    $menus->whereNotIn('clasification', ['admin', 'site']);
                }else{
                    $menus->whereNotIn('clasification', ['owner', 'site']);
                }

                $menus = $menus->get();

                // dd($menus);

                $tenants = Tenant::where('is_active', true)->get();
                $activeTenantId = session('tenant_id') ?? $tenants->first()?->id;

                // Compartir con todas las vistas
                View::share([
                    'menus' => $menus,
                    'tenants' => $tenants,
                    'activeTenantId' => $activeTenantId
                ]);
            }

        } catch (\Exception $e) {
            // Si no hay DB, no hacemos nada
        }

        return $next($request);
    }
}
