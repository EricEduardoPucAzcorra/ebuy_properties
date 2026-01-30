<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
       if (Schema::hasTable('permissions')) {
            Permission::pluck('name')->each(function ($name) {
                Gate::define($name, function ($user) use ($name) {
                    return $user->hasPermission($name);
                });
            });
        }

        View::composer('*', function ($view) {

            if (!Auth::check() || !Schema::hasTable('menus')) {
                return;
            }

            $menus = Menu::with('module')
                ->where('is_active', true)
                ->orderBy('order')
                ->with('items')
                ->get();

            $tenants = Tenant::where('is_active', true)->get();

            $activeTenantId = session('tenant_id') ?? $tenants->first()?->id;

            $view->with([
                'menus'   => $menus,
                'tenants' => $tenants,
                'activeTenantId'=>$activeTenantId
            ]);
        });
    }
}
