<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Tenant;
use App\Services\TranslationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //services

        $this->app->singleton(TranslationService::class, function ($app) {
            return new TranslationService();
        });

        $this->app->singleton(\App\Services\GeminiService::class, function ($app) {
            return new \App\Services\GeminiService();
        });
    }

    public function boot(): void
    {

    }
}
