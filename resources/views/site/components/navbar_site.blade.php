@php
    use App\Models\Menu;
    $menus = Menu::where('is_active', true)
            ->whereHas('module')
            ->with(['module', 'items'])
            ->orderBy('order')
            ->get();
@endphp
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
                @foreach($menus as $menu)
                    @if($menu->module->name !== 'site')
                        @continue
                    @endif
                    @if($menu->items->isEmpty())
                        <a href="{{ route($menu->route) }}"
                        class="nav-item nav-link {{ request()->routeIs($menu->route) ? 'active' : '' }}">
                            {{ __('menu-site.' . $menu->title) }}
                        </a>
                    @else
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                {{ __('menu-site.' . $menu->title) }}
                            </a>

                            <div class="dropdown-menu rounded-0 m-0">
                                @foreach($menu->items as $item)
                                    <a href="{{ route($item->route) }}" class="dropdown-item">
                                        {{ __('menu-site.' . $item->title) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
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

                <a href="{{ route('login') }}" class="btn btn-outline-primary px-3 me-2">
                        {{ __('Publicar') }}
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary px-3">
                        {{ __('auth.login') }}
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

