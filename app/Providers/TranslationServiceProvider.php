<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Translators\GoogleFallbackTranslator;

class TranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend('translator', function ($translator, $app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            return new GoogleFallbackTranslator($loader, $locale);
        });
    }
}
