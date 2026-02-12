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
    <!-- Versión Desktop -->
    <div class="ebuy-desktop-header">
        <table style="width: 100%; max-width: 1400px; margin: 0 auto; border-collapse: collapse; height: 90px;">
            <tr>
                <!-- Logo a la izquierda -->
                <td style="width: auto; padding: 0 30px; vertical-align: middle;">
                    <a href="{{ url('/') }}" class="ebuy-brand-main">
                        <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties" style="height: 60px; width: auto;">
                    </a>
                </td>
                
                <!-- Espacio flexible -->
                <td style="width: 100%;">&nbsp;</td>
                
                <!-- Menú y acciones a la derecha -->
                <td style="width: auto; padding: 0 2px; vertical-align: middle; white-space: nowrap;">
                    <div style="display: flex; align-items: center; gap: 25px; margin-left: 120px; justify-content: flex-end;">
                        <!-- Menú de navegación -->
                        <nav class="ebuy-nav-center" style="display: flex; gap: 15px; height: 100%; position: static; margin-right: 25px;">
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

                        <!-- Acciones -->
                        <div class="ebuy-actions-right" style="display: flex; align-items: center; gap: 15px;">
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
                                <div class="ebuy-user-dropdown">
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
                </td>
            </tr>
        </table>
    </div>

    <!-- Versión Móvil -->
    <div class="ebuy-mobile-header" style="position: fixed; top: 0; left: 0; height: 70px; background: #fff; display: block !important; visibility: visible !important; width: 100%; z-index: 99998;">
        <!-- Logo a la izquierda -->
        <a href="{{ url('/') }}" class="ebuy-brand-main" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); z-index: 10; display: block !important;">
            <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties" style="height: 35px; width: auto;">
        </a>

        <!-- Botón hamburguesa NUEVO Y FORZADO -->
        <div id="mobileMenuToggle" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); width: 32px; height: 32px; background: #00B98E; border: 2px solid #00B98E; border-radius: 6px; cursor: pointer; z-index: 99999; display: flex !important; flex-direction: column; justify-content: center; align-items: center; padding: 6px; transition: all 0.3s ease;">
            <div style="width: 16px; height: 2px; background: #ffffff; margin: 1.5px 0; border-radius: 2px;"></div>
            <div style="width: 16px; height: 2px; background: #ffffff; margin: 1.5px 0; border-radius: 2px;"></div>
            <div style="width: 16px; height: 2px; background: #ffffff; margin: 1.5px 0; border-radius: 2px;"></div>
        </div>
    </div>

    <!-- Menú móvil deslizable -->
    <div class="ebuy-mobile-menu" id="mobileMenu">
        <div class="ebuy-mobile-menu-header">
            <a href="{{ url('/') }}" class="ebuy-brand-main">
                <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties" style="height: 40px; max-width: 140px; object-fit: contain;">
            </a>
            <button class="ebuy-mobile-close" id="mobileMenuClose" style="width: 36px; height: 36px; border: none; background: #f8fafc; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #64748b; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); position: relative; z-index: 10001; flex-shrink: 0; margin-right: 10px;">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="ebuy-mobile-menu-body">
            <!-- Menú de compra versión móvil -->
            <div class="ebuy-mobile-menu-section">
                <button class="ebuy-mobile-menu-toggle" data-target="mobile-buy-menu">
                    {{ auto_trans('Comprar') }}
                    <i class="fa fa-chevron-down"></i>
                </button>
                <div class="ebuy-mobile-submenu" id="mobile-buy-menu">
                    @php
                        use App\Models\TypeOperation;
                        use App\Models\TypePropetie;
                        
                        $type_operationbuy = TypeOperation::where('name','Venta')->first();
                        $property_typebuy = TypePropetie::where('name','Casa')->first();
                        $type_operationdep = TypeOperation::where('name','Venta')->first();
                        $property_typedep = TypePropetie::where('name','Departamento')->first();
                    @endphp
                    
                    <!-- Casas -->
                    <button class="ebuy-mobile-submenu-toggle" data-target="mobile-buy-casas">
                        <i class="bi bi-houses"></i>
                        {{ auto_trans('Casas') }}
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="ebuy-mobile-submenu-nested" id="mobile-buy-casas">
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Estados destacados') }}</div>
                        @foreach($featuredStates as $st)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationbuy->id, 'type'=> $property_typebuy->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ $st->statename }}
                            </a>
                        @endforeach
                        
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Ciudades destacadas') }}</div>
                        @forelse($featuredCities as $city)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationbuy->id, 'type'=> $property_typebuy->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-building"></i>
                                {{ $city->cityname }}
                            </a>
                        @empty
                            <a href="{{ route('properties', ['operation' => $type_operationbuy->id, 'type'=> $property_typebuy->id]) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-search"></i>
                                {{ auto_trans('Explorar todas las casas') }}
                            </a>
                        @endforelse
                    </div>
                    
                    <!-- Departamentos -->
                    <button class="ebuy-mobile-submenu-toggle" data-target="mobile-buy-departamentos">
                        <i class="bi bi-building"></i>
                        {{ auto_trans('Departamentos') }}
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="ebuy-mobile-submenu-nested" id="mobile-buy-departamentos">
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Estados destacados') }}</div>
                        @foreach($featuredStates as $st)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationdep->id, 'type'=> $property_typedep->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ $st->statename }}
                            </a>
                        @endforeach
                        
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Ciudades destacadas') }}</div>
                        @forelse($featuredCities as $city)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationdep->id, 'type'=> $property_typedep->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-building"></i>
                                {{ $city->cityname }}
                            </a>
                        @empty
                            <a href="{{ route('properties', ['operation' => $type_operationdep->id, 'type'=> $property_typedep->id]) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-search"></i>
                                {{ auto_trans('Explorar todos los departamentos') }}
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Menú de renta versión móvil -->
            <div class="ebuy-mobile-menu-section">
                <button class="ebuy-mobile-menu-toggle" data-target="mobile-rent-menu">
                    {{ auto_trans('Rentar') }}
                    <i class="fa fa-chevron-down"></i>
                </button>
                <div class="ebuy-mobile-submenu" id="mobile-rent-menu">
                    @php
                        $type_operationrent = TypeOperation::where('name','Renta')->first();
                        $property_typehouse = TypePropetie::where('name','Casa')->first();
                        $property_typedep = TypePropetie::where('name','Departamento')->first();
                    @endphp
                    
                    <!-- Casas -->
                    <button class="ebuy-mobile-submenu-toggle" data-target="mobile-rent-casas">
                        <i class="bi bi-houses"></i>
                        {{ auto_trans('Casas') }}
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="ebuy-mobile-submenu-nested" id="mobile-rent-casas">
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Estados destacados') }}</div>
                        @foreach($featuredStates as $st)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typehouse->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ $st->statename }}
                            </a>
                        @endforeach
                        
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Ciudades destacadas') }}</div>
                        @forelse($featuredCities as $city)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typehouse->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-building"></i>
                                {{ $city->cityname }}
                            </a>
                        @empty
                            <a href="{{ route('properties', ['operation' => $type_operationrent->id, 'type'=> $property_typehouse->id]) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-search"></i>
                                {{ auto_trans('Explorar todas las casas') }}
                            </a>
                        @endforelse
                    </div>
                    
                    <!-- Departamentos -->
                    <button class="ebuy-mobile-submenu-toggle" data-target="mobile-rent-departamentos">
                        <i class="bi bi-building"></i>
                        {{ auto_trans('Departamentos') }}
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="ebuy-mobile-submenu-nested" id="mobile-rent-departamentos">
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Estados destacados') }}</div>
                        @foreach($featuredStates as $st)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typedep->id, 'location_type'=>'state', 'location_id'=>$st->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-geo-alt"></i>
                                {{ $st->statename }}
                            </a>
                        @endforeach
                        
                        <div class="ebuy-mobile-submenu-divider">{{ auto_trans('Ciudades destacadas') }}</div>
                        @forelse($featuredCities as $city)
                            <a href="{{ route('properties', array_merge(['operation' => $type_operationrent->id, 'type'=> $property_typedep->id, 'location_type'=>$city->type, 'location_id'=>$city->id])) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-building"></i>
                                {{ $city->cityname }}
                            </a>
                        @empty
                            <a href="{{ route('properties', ['operation' => $type_operationrent->id, 'type'=> $property_typedep->id]) }}" class="ebuy-mobile-submenu-item">
                                <i class="bi bi-search"></i>
                                {{ auto_trans('Explorar todos los departamentos') }}
                            </a>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Menús dinámicos -->
            @foreach($menus as $menu)
                @if($menu->module->name !== 'site') @continue @endif

                <div class="ebuy-mobile-menu-section">
                    @if(!$menu->items->isEmpty())
                        <button class="ebuy-mobile-menu-toggle" data-target="mobile-{{ $menu->id }}">
                            {{ auto_trans($menu->title) }}
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <div class="ebuy-mobile-submenu" id="mobile-{{ $menu->id }}">
                            @foreach($menu->items as $item)
                                <a href="{{ route($item->route) }}" class="ebuy-mobile-submenu-item">
                                    <i class="{{ $item->icon ?? 'fa fa-chevron-right' }}"></i>
                                    {{ auto_trans($item->title) }}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <a href="{{ route($menu->route) }}" class="ebuy-mobile-menu-link {{ request()->routeIs($menu->route) ? 'is-active' : '' }}">
                            {{ auto_trans($menu->title) }}
                        </a>
                    @endif
                </div>
            @endforeach

            <!-- Acciones móviles -->
            <div class="ebuy-mobile-actions">
                <div class="ebuy-mobile-lang">
                    <div class="ebuy-mobile-lang-current">
                        <i class="fa fa-globe"></i>
                        {{ app()->getLocale() == 'es' ? 'MX ES' : 'US EN' }}
                        <i class="fa fa-chevron-down"></i>
                    </div>
                    <div class="ebuy-mobile-lang-options">
                        <a href="{{ url('lang/es') }}">MX ES</a>
                        <a href="{{ url('lang/en') }}">US EN</a>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="btn-ebuy-outline btn-mobile">
                    <i class="fa fa-plus-circle me-1"></i> {{ auto_trans('Publicar') }}
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn-ebuy-solid btn-mobile">{{ __('Iniciar') }}</a>
                @else
                    <div class="ebuy-mobile-user">
                        <button class="btn-ebuy-solid btn-mobile dropdown-toggle" data-bs-toggle="dropdown">
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
    </div>
</header>

<script src="{{ asset('site/js/mobile-menu.js') }}"></script>
