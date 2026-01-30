<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lalux</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ asset('icons/lalux.png') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <header class="auth-header">
        <div class="auth-header-inner">
            <img src="{{ asset('icons/lalux.png') }}" height="40" alt="Lalux">
            <div class="lang-switch">
                <a href="/lang/es" class="{{ app()->getLocale() === 'es' ? 'active' : '' }}">🇲🇽 ES</a>
                <a href="/lang/en" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">🇺🇸 EN</a>
            </div>
        </div>
    </header>

    <main class="auth-main">
        @yield('content')
    </main>

    @include('layouts.traductions')
</body>
</html>
