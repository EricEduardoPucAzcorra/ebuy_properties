@php
    use App\Models\Menu;
    use App\Models\Countrie;
    use App\Models\State;
    use App\Models\Citie;
    use Stevebauman\Location\Facades\Location;

    $ip = request()->ip() == '127.0.0.1' ? '45.167.93.33' : request()->ip();
    $position = Location::get($ip);

    $currentCountry = null;
    $currentState = null;
    $featuredCities = collect();
    $featuredStates = collect();

    if ($position) {
        $currentCountry = Countrie::where('code', $position->countryCode)->first();

        if ($currentCountry) {
            $currentState = State::where('countryid', $currentCountry->countryid)
                ->where('statename', 'LIKE', "%{$position->regionName}%")
                ->first();

            if ($currentState) {
                $featuredCities = Citie::where('stateid', $currentState->stateid)
                    ->orderBy('population', 'desc')
                    ->limit(5)
                    ->get();
            }

            $queryStates = State::where('countryid', $currentCountry->countryid);
            if ($currentState) {
                $queryStates->orderByRaw("CASE WHEN stateid = ? THEN 0 ELSE 1 END", [$currentState->stateid]);
            }
            $featuredStates = $queryStates->orderBy('population', 'desc')
                ->limit(5)
                ->get();
        }
    }

    $menus = Menu::where('is_active', true)
            ->whereHas('module')
            ->with(['module', 'items.children'])
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

            @include('site.components.buy_item_menu')

            @include('site.components.rent_item_menu')

            @foreach($menus as $menu)
                @if($menu->module->name !== 'site') @continue @endif

                <div class="ebuy-menu-item">
                    <a href="{{ $menu->items->isEmpty() ? route($menu->route) : 'javascript:void(0)' }}"
                    class="ebuy-link-top {{ request()->routeIs($menu->route) ? 'is-active' : '' }}">
                        {{ auto_trans($menu->title) }}
                        @if(!$menu->items->isEmpty()) <i class="fa fa-chevron-down ms-1"></i> @endif
                    </a>

                    @if(!$menu->items->isEmpty())
                        <div class="ebuy-dropdown-float ebuy-grid-menu">
                            <div class="grid-menu-inner">
                                @foreach($menu->items as $item)
                                    <a href="{{ route($item->route) }}" class="ebuy-grid-link">
                                        <div class="icon-box">
                                            <i class="{{ $item->icon ?? 'fa fa-chevron-right' }}"></i>
                                        </div>
                                        <div class="text-box">
                                            <span class="title">{{ auto_trans( $item->title) }}</span>
                                            <small class="desc">Explorar opciones</small> </div>
                                    </a>
                                @endforeach
                            </div>
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
                <a href="{{ route('login') }}" class="btn-ebuy-solid">{{ __('Iniciar') }}</a>
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

<script>
    document.querySelectorAll('.sidebar-item').forEach(el => {
        el.addEventListener('mouseenter', function() {
            const targetId = this.getAttribute('data-target');
            const megaCard = this.closest('.mega-card');

            megaCard.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
            megaCard.querySelectorAll('.mega-pane').forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
</script>
