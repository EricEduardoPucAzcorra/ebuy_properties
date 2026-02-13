<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ebuy Properties</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/ebuy_1.png') }}">

    <script src="{{ asset('min/vue.min.js') }}"></script>

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
                        <a class="navbar-brand d-flex align-items-center gap-2 gap-md-3" href="{{ url('/') }}">
                            <img src="{{ asset('images/ebuy_2.jpg') }}"
                                alt="Ebuy Properties"
                                style="height: 40px; width: auto;"
                                class="d-inline-block align-top">
                        </a>
                        @guest
                            <div class="navbar-nav flex-row">
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
                            <div class="navbar-nav flex-row align-items-center">
                                <div x-data="languageSwitch">
                                    <button class="btn btn-outline-secondary me-2" type="button" @click="changeLang()">
                                        <span x-show="currentLang === 'es'" class="fw-bold">🇲🇽 ES</span>
                                        <span x-show="currentLang === 'en'" class="fw-bold">🇺🇸 EN</span>
                                    </button>
                                </div>

                                <div class="dropdown me-2">
                                    <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <i class="bi bi-bell"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Notifications</h6></li>
                                        <li><a class="dropdown-item" href="#">New user registered</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-center" href="#">View all</a></li>
                                    </ul>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary d-flex align-items-center" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        @if (Auth::user()->profile)
                                            <img src="{{ asset('storage/' . Auth::user()->profile) }}" width="24" height="24" class="rounded-circle me-2">
                                        @elseif (Auth::user()->profile_url)
                                            <img src="{{ Auth::user()->profile_url }}" width="24" height="24" class="rounded-circle me-2">
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
                <div class="sidebar-overlay" data-sidebar-overlay></div>
                
                <aside class="admin-sidebar" id="admin-sidebar">
                    <div class="sidebar-content">
                        @if(auth()->user()->hasRoleName('Admin'))
                            @include('menu_user.admin')
                        @elseif (auth()->user()->hasRoleName('Owner'))
                            @include('menu_user.owner')
                        @else

                        @endif
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
                        <div class="col-md-6 text-md-end"><p class="mb-0 text-muted">Ebuy properties</p></div>
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
            const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

            if (toggleButton && wrapper) {
                // Function to check if mobile/tablet
                function isMobile() {
                    return window.innerWidth <= 1200; // Updated breakpoint
                }

                // Function to update sidebar behavior
                function updateSidebarBehavior() {
                    const mobile = isMobile();
                    
                    // Remove overlay functionality
                    wrapper.classList.remove('sidebar-open');
                    
                    if (!mobile) {
                        // Desktop: use collapsed state from localStorage
                        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                        if (isCollapsed) {
                            wrapper.classList.add('sidebar-collapsed');
                            toggleButton.classList.add('is-active');
                        } else {
                            wrapper.classList.remove('sidebar-collapsed');
                            toggleButton.classList.remove('is-active');
                        }
                        
                        // Reset mobile menu state
                        wrapper.classList.remove('mobile-menu-hidden');
                        localStorage.removeItem('mobile-menu-hidden');
                        
                        // Update icon to desktop style
                        const icon = toggleButton.querySelector('i');
                        if (icon) {
                            icon.className = 'bi bi-list';
                        }
                        
                        // Update button styles for desktop
                        toggleButton.style.background = 'none';
                        toggleButton.style.border = 'none';
                        toggleButton.style.color = '#6c757d';
                        toggleButton.style.boxShadow = 'none';
                        
                    } else {
                        // Mobile/tablet: check mobile menu state from localStorage
                        const mobileMenuHidden = localStorage.getItem('mobile-menu-hidden') === 'true';
                        if (mobileMenuHidden) {
                            wrapper.classList.add('mobile-menu-hidden');
                            toggleButton.classList.add('is-active');
                            // Change icon to arrow-right
                            const icon = toggleButton.querySelector('i');
                            if (icon) {
                                icon.className = 'bi bi-arrow-right';
                            }
                        } else {
                            wrapper.classList.remove('mobile-menu-hidden');
                            toggleButton.classList.remove('is-active');
                            // Change icon to list
                            const icon = toggleButton.querySelector('i');
                            if (icon) {
                                icon.className = 'bi bi-list';
                            }
                        }
                        // Always start collapsed for better UX
                        wrapper.classList.add('sidebar-collapsed');
                        
                        // Update button styles for mobile
                        toggleButton.style.background = 'white';
                        toggleButton.style.border = '1px solid #e0e0e0';
                        toggleButton.style.color = '#6c757d';
                        toggleButton.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                    }
                }

                // Initialize
                updateSidebarBehavior();

                // Single toggle button click handler for both desktop and mobile
                toggleButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (isMobile()) {
                        // Mobile/tablet: toggle menu hidden/shown
                        const isNowHidden = wrapper.classList.toggle('mobile-menu-hidden');
                        toggleButton.classList.toggle('is-active', isNowHidden);
                        
                        // Update icon based on state
                        const icon = toggleButton.querySelector('i');
                        if (icon) {
                            if (isNowHidden) {
                                icon.className = 'bi bi-arrow-right';
                            } else {
                                icon.className = 'bi bi-list';
                            }
                        }
                        
                        // Save mobile menu state
                        localStorage.setItem('mobile-menu-hidden', isNowHidden);
                    } else {
                        // Desktop: toggle collapsed state
                        const isNowCollapsed = wrapper.classList.toggle('sidebar-collapsed');
                        toggleButton.classList.toggle('is-active', isNowCollapsed);
                        
                        // Save state for desktop
                        localStorage.setItem('sidebar-collapsed', isNowCollapsed);
                    }
                });

                // Handle window resize with immediate detection
                let resizeTimer;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        const previousMobile = window.innerWidth <= 1200;
                        updateSidebarBehavior(); // This will detect the new size
                    }, 50); // Faster detection - 50ms delay
                });
                
                // Also handle orientation change for mobile devices
                window.addEventListener('orientationchange', () => {
                    setTimeout(updateSidebarBehavior, 100);
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

        window.DEFAULT_COMPANY_LOGO = "{{ asset('images/ebuy_1.png') }}";

       window.userPermissions = @json(auth()->check() ? auth()->user()->getPermissionNames() : []);

        window.can = function(permission) {
            return window.userPermissions.includes(permission);
        };

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}"></script>

    @stack('scripts')

    @include('layouts.traductions')
</body>
</html>
