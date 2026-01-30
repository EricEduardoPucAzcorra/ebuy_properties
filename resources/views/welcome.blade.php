<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lalux Development</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('icons/lalux.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>

    {{-- Language Switcher funcional EN / ES --}}
    <div class="auth-header">
        <div class="auth-header-inner">
            <div class="lang-switch">
                @foreach(['es' => '🇲🇽 ES', 'en' => '🇺🇸 EN'] as $code => $label)
                    <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                       class="{{ app()->getLocale() === $code ? 'active' : '' }}">
                       {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Hero Section --}}
    <section class="hero">
        <div class="hero-content">
            <h1>Lalux <span>Development</span></h1>
            <p>{{ __('Soluciones digitales modernas y escalables para llevar tu negocio al siguiente nivel.') }}</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary">{{ __('Register') }}</a>
                <a href="{{ route('login') }}" class="btn-outline">{{ __('Login') }}</a>
            </div>
        </div>

        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1581091012184-1a1c1d6c5f1f?auto=format&fit=crop&w=600&q=80" alt="Hero Image">
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features">
        <div class="feature">
            <h3>{{ __('Seguridad') }}</h3>
            <p>{{ __('Protegemos tus datos con los más altos estándares de seguridad.') }}</p>
        </div>
        <div class="feature">
            <h3>{{ __('Escalabilidad') }}</h3>
            <p>{{ __('Nuestros proyectos crecen junto a tu negocio sin complicaciones.') }}</p>
        </div>
        <div class="feature">
            <h3>{{ __('Soporte') }}</h3>
            <p>{{ __('Atención rápida y personalizada para resolver tus dudas.') }}</p>
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        &copy; {{ date('Y') }} Lalux Development. {{ __('Todos los derechos reservados.') }}
    </footer>

</body>
</html>
