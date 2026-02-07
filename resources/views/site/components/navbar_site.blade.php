@php
    use App\Models\Menu;
    $menus = Menu::where('is_active', true)
            ->whereHas('module')
            ->with(['module', 'items'])
            ->where('clasification','site')
            ->orderBy('order')
            ->get();
@endphp

<header class="ebuy-header-full">
    <div class="ebuy-container-limit">
        <a href="{{ url('/') }}" class="ebuy-brand-main">
            <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties">
        </a>

        <nav class="ebuy-nav-center">
            @foreach($menus as $menu)
                @if($menu->module->name !== 'site') @continue @endif

                <div class="ebuy-menu-item">
                    <a href="{{ $menu->items->isEmpty() ? route($menu->route) : 'javascript:void(0)' }}"
                       class="ebuy-link-top {{ request()->routeIs($menu->route) ? 'is-active' : '' }}">
                        {{ __('menu-site.' . $menu->title) }}
                        @if(!$menu->items->isEmpty()) <i class="fa fa-chevron-down ms-1"></i> @endif
                    </a>

                    @if(!$menu->items->isEmpty())
                        <div class="ebuy-dropdown-float">
                            @foreach($menu->items as $item)
                                <a href="{{ route($item->route) }}" class="ebuy-sub-link">
                                    {{ __('menu-site.' . $item->title) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="ebuy-actions-right">

            <div class="ebuy-lang-picker">
                <div class="lang-pill-row">
                    <i class="fa fa-globe"></i>
                    <span>{{ app()->getLocale() == 'es' ? 'MX ES' : 'US EN' }}</span>
                </div>
                <div class="lang-drop-box">
                    <a href="{{ url('lang/es') }}">MX ES</a>
                    <a href="{{ url('lang/en') }}">US EN</a>
                </div>
            </div>

            <a href="{{ route('login') }}" class="btn-ebuy-outline">
                <i class="fa fa-plus-circle me-1"></i> {{ auto_trans('Publicar') }}
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn-ebuy-solid">
                    {{ __('Iniciar') }}
                </a>
            @else
                <div class="ebuy-user-box">
                    <button class="btn-ebuy-solid dropdown-toggle" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                        <li><a class="dropdown-item" href="{{route('home')}}">{{ __('Dashboard') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"> {{ __('Logout') }} </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</header>

@include('styles.navbar_site')
