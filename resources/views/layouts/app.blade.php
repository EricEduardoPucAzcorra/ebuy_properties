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

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div data-page="dashboard" class="admin-layout">
        <div id="loading-screen" class="loading-screen">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <div class="admin-wrapper" id="admin-wrapper">
            <header class="admin-header">
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                       <a class="navbar-brand d-flex align-items-center gap-2 gap-md-3" href="{{ url('/home') }}">
                            <img src="{{ asset('icons/lalux.png') }}"
                                alt="Logo Lalux"
                                height="40"
                                class="d-inline-block d-md-none">

                            <img src="{{ asset('icons/lalux.png') }}"
                                alt="Logo Lalux"
                                height="48"
                                class="d-none d-md-inline-block">

                            <span class="fw-bold fs-5 fs-md-4 text-primary lh-1">
                                Lalux
                                <small class="fw-semibold text-muted ms-1">
                                    Developer
                                </small>
                            </span>
                        </a>

                        @guest
                            <div class="navbar-nav flex-row">
                                <div x-data="themeSwitch">
                                    <button class="btn btn-outline-secondary me-2" type="button" @click="toggle()">
                                        <i class="bi bi-sun-fill" x-show="currentTheme === 'light'"></i>
                                        <i class="bi bi-moon-fill" x-show="currentTheme === 'dark'"></i>
                                    </button>
                                </div>
                                <div x-data="languageSwitch">
                                    <button class="btn btn-outline-secondary me-2" type="button" @click="changeLang()">
                                        <span x-show="currentLang === 'es'" class="fw-bold">🇲🇽 ES</span>
                                        <span x-show="currentLang === 'en'" class="fw-bold">🇺🇸 EN</span>
                                    </button>
                                </div>
                                @if (Route::has('login'))
                                    <a class="btn btn-outline-secondary me-2" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                                @endif
                                @if (Route::has('register'))
                                    <a class="btn btn-outline-secondary me-2" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                                @endif
                            </div>
                        @else
                            <div class="nav-company-selector me-3">
                                <div class="dropdown">

                                    @if($tenants->count() >= 1)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 {{ $tenants->count() > 1 ? 'dropdown-toggle' : '' }}"
                                            @if($tenants->count() > 1)
                                                data-bs-toggle="dropdown"
                                            @endif
                                        >
                                            <i class="bi bi-building me-1"></i>
                                            {{ $tenants->firstWhere('id', session('tenant_id'))?->name ?? 'Mi Empresa' }}
                                        </button>
                                    @endif

                                    @if($tenants->count() > 1)
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            @foreach ($tenants as $t)
                                                <li>
                                                    <a href="{{ route('tenant.set', $t) }}"
                                                    class="dropdown-item {{ session('tenant_id') === $t->id ? 'active' : '' }}">
                                                        @if (session('tenant_id') === $t->id)
                                                            <i class="bi bi-check2 me-2"></i>
                                                        @endif
                                                        {{ $t->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </div>
                            </div>

                            <div class="search-container flex-grow-1 mx-4" x-data="searchComponent">
                                <div class="position-relative">

                                    <!-- INPUT -->
                                    <input type="search"
                                        class="form-control"
                                        placeholder="{{ __('general.search') }} (Ctrl+K)"
                                        x-model="query"
                                        @input.debounce.300ms="search()">

                                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>

                                    <!-- RESULTADOS (AQUÍ VA) -->
                                    <div class="dropdown-menu show w-100 mt-1"
                                        x-show="showResults"
                                        x-transition
                                        @click.outside="showResults = false">

                                        <template x-for="result in results" :key="result.id">
                                            <a href="#"
                                            class="dropdown-item d-flex align-items-center gap-2"
                                            @click.prevent="selectResult(result)">
                                                <i :class="result.icon"></i>
                                                <span x-text="result.title"></span>
                                            </a>
                                        </template>

                                        <div x-show="results.length === 0"
                                            class="dropdown-item text-muted">
                                            Sin resultados
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="navbar-nav flex-row">
                                <div x-data="themeSwitch">
                                    <button class="btn btn-outline-secondary me-2" type="button" @click="toggle()">
                                        <i class="bi bi-sun-fill" x-show="currentTheme === 'light'"></i>
                                        <i class="bi bi-moon-fill" x-show="currentTheme === 'dark'"></i>
                                    </button>
                                </div>

                                <div x-data="languageSwitch">
                                    <button class="btn btn-outline-secondary me-2" type="button" @click="changeLang()">
                                        <span x-show="currentLang === 'es'" class="fw-bold">🇲🇽 ES</span>
                                        <span x-show="currentLang === 'en'" class="fw-bold">🇺🇸 EN</span>
                                    </button>
                                </div>

                                <button class="btn btn-outline-secondary me-2" type="button" data-fullscreen-toggle>
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>

                                {{-- <div class="dropdown me-2">
                                    <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-bell"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Notifications</h6></li>
                                        <li><a class="dropdown-item" href="#">New user registered</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-center" href="#">View all</a></li>
                                    </ul>
                                </div> --}}

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                        @if (Auth::user()->profile)
                                            <img src="{{ asset('storage/' . Auth::user()->profile) }}" width="24" height="24" class="rounded-circle me-2">
                                        @else
                                            <img src="{{ asset('images/avatar-placeholder.svg') }}" width="24" height="24" class="rounded-circle me-2">
                                        @endif
                                        <span class="d-none d-md-inline"> {{ Auth::user()->name }} </span>
                                        <i class="bi bi-chevron-down ms-1"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{route('profile')}}"><i class="bi bi-person me-2"></i> {{ __('general.profile')}}</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('general.logout') }}
                                            </a>
                                        </li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </ul>
                                </div>
                            </div>
                        @endguest
                    </div>
                </nav>
            </header>

            @auth
                <aside class="admin-sidebar" id="admin-sidebar">
                    <div class="sidebar-content">
                        <div class="company-card border-bottom">
                            <div class="company-banner">
                                @php
                                    $tenant = $tenants->firstWhere('id', $activeTenantId);
                                    $logo = $tenant && $tenant->logo
                                        ? asset('storage/' . $tenant->logo)
                                        : asset('images/lalux3.png');
                                @endphp

                                <img src="{{ $logo }}" alt="Logo"
                                    class="company-image img-fluid">

                                <div class="company-overlay">
                                    <div class="dropdown company-selector">

                                        @if($tenants->count() >= 1)
                                            <button
                                                type="button"
                                                class="company-selector-btn {{ $tenants->count() > 1 ? 'dropdown-toggle' : '' }}"
                                                @if($tenants->count() > 1)
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                @endif
                                            >
                                                {{ $tenants->firstWhere('id', $activeTenantId)?->name ?? 'Seleccionar clínica' }}
                                            </button>
                                        @endif

                                        @if($tenants->count() > 1)
                                            <ul class="dropdown-menu company-dropdown">
                                                @foreach ($tenants as $t)
                                                    <li>
                                                        <form method="POST" action="{{ route('tenant.set', $t) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="dropdown-item {{ $activeTenantId === $t->id ? 'active' : '' }}">
                                                                @if ($activeTenantId === $t->id)
                                                                    <i class="bi bi-check2 me-2"></i>
                                                                @endif
                                                                {{ $t->name }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>

                        <nav class="sidebar-nav">
                            <ul class="nav flex-column">
                                @foreach($menus as $menu)
                                    @if($menu->items->isEmpty())
                                        @canany([$menu->module->name . '.view', $menu->module->name . '.' . $menu->title])
                                            <li class="nav-item">
                                                <a class="nav-link {{ request()->routeIs($menu->route) ? 'active' : '' }}" href="{{ route($menu->route) }}">
                                                    <i class="{{ $menu->icon }}"></i>
                                                    <span>{{ __('menu.' . $menu->title) }}</span>
                                                </a>
                                            </li>
                                        @endcanany
                                    @else
                                        @php
                                            $visibleItems = $menu->items->filter(function ($item) {
                                                return Auth::user()->canAny([$item->module->name . '.view', $item->module->name . '.' . $item->title]);
                                            });
                                            $isOpen = $visibleItems->contains(fn($item) => request()->routeIs($item->route . '*'));
                                        @endphp
                                        @if($visibleItems->isNotEmpty())
                                            <li class="nav-item">
                                                <a class="nav-link d-flex justify-content-between align-items-center" href="#menu-{{ $menu->id }}" data-bs-toggle="collapse" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                                                    <span><i class="{{ $menu->icon }}"></i> {{ __('menu.' . $menu->title) }}</span>
                                                    <i class="bi bi-chevron-down ms-2"></i>
                                                </a>
                                                <div class="collapse {{ $isOpen ? 'show' : '' }}" id="menu-{{ $menu->id }}">
                                                    <ul class="nav flex-column ms-3">
                                                        @foreach($visibleItems as $item)
                                                            <li class="nav-item">
                                                                <a class="nav-link {{ request()->routeIs($item->route) ? 'active' : '' }}" href="{{ route($item->route) }}">
                                                                    <i class="{{ $item->icon }}"></i>
                                                                    <span>{{ __('menu.' . $item->title) }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                </aside>

                <button class="hamburger-menu" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
            @endauth

            <main class="admin-main">
                <div class="container-fluid p-4 p-lg-5">@yield('content')</div>
            </main>

            <footer class="admin-footer">
                <div class="container-fluid text-center text-md-start">
                    <div class="row">
                        <div class="col-md-6"><p class="mb-0 text-muted">© 2026 Desarrollando Ideas</p></div>
                        <div class="col-md-6 text-md-end"><p class="mb-0 text-muted">Pos Finance</p></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.body.classList.add('ready');
            const loader = document.getElementById('loading-screen');
            if (loader) loader.classList.add('d-none');
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('[data-sidebar-toggle]');
            const wrapper = document.getElementById('admin-wrapper');

            if (toggleButton && wrapper) {
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                if (isCollapsed) {
                    wrapper.classList.add('sidebar-collapsed');
                    toggleButton.classList.add('is-active');
                }

                toggleButton.addEventListener('click', () => {
                    const isNowCollapsed = wrapper.classList.toggle('sidebar-collapsed');
                    toggleButton.classList.toggle('is-active', isNowCollapsed);
                    localStorage.setItem('sidebar-collapsed', isNowCollapsed);
                });
            }
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('languageSwitch', () => ({
                currentLang: '{{ app()->getLocale() }}',
                changeLang() {
                    this.currentLang = this.currentLang === 'es' ? 'en' : 'es';
                    window.location.href = `/lang/${this.currentLang}`;
                }
            }))
        });

        window.DEFAULT_COMPANY_LOGO = "{{ asset('images/lalux3.png') }}";

       window.userPermissions = @json(auth()->check() ? auth()->user()->getPermissionNames() : []);

        window.can = function(permission) {
            return window.userPermissions.includes(permission);
        };

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    @include('layouts.traductions')
</body>
</html>
