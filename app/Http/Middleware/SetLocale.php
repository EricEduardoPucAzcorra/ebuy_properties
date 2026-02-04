<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (session()->has('locale')) {
    //         app()->setLocale(session('locale'));
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // 1. Verificar en sesión
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }
        // 2. Verificar en cookie
        elseif ($request->hasCookie('locale')) {
            App::setLocale($request->cookie('locale'));
        }
        // 3. Detectar del navegador
        else {
            $browserLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($browserLang, ['en', 'es'])) {
                App::setLocale($browserLang);
            }
        }

        return $next($request);
    }
}
