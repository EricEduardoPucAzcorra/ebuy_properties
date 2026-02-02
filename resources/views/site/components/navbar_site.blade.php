<div class="container-fluid nav-bar bg-transparent">
    <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
        <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/ebuy_1.png') }}"
                alt="Ebuy Properties"
                style="width:200px;height:85px;background:transparent;">
        </a>

        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
                <a href="{{ url('/') }}" class="nav-item nav-link active">
                    {{ __('Home') }}
                </a>

                <a href="#" class="nav-item nav-link">
                    {{ __('About') }}
                </a>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        {{ __('Property') }}
                    </a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="#" class="dropdown-item">{{ __('Property List') }}</a>
                        <a href="#" class="dropdown-item">{{ __('Property Type') }}</a>
                        <a href="#" class="dropdown-item">{{ __('Agents') }}</a>
                    </div>
                </div>

                <a href="#" class="nav-item nav-link">
                    {{ __('Contact') }}
                </a>
            </div>

            <div class="d-flex align-items-center ms-3">

                <div class="nav-item dropdown me-3">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa fa-language text-black"></i> {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{ url('lang/es') }}"
                        class="dropdown-item {{ app()->getLocale() === 'es' ? 'active' : '' }}">
                            🇲🇽 ES
                        </a>
                        <a href="{{ url('lang/en') }}"
                        class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            🇺🇸 EN
                        </a>
                    </div>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 me-2">
                        {{ __('Login') }}
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-primary px-3">
                        {{ __('Register') }}
                    </a>
                @else
                    <div class="nav-item dropdown">
                        <a href="#" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                {{ __('Dashboard') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

            </div>
        </div>

    </nav>
</div>
<!-- Navbar End -->
