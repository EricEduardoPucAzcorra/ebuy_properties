<!-- Versión Móvil -->
<div class="ebuy-mobile-header" style="position: fixed; top: 0; left: 0; height: 70px; background: #fff; display: block !important; visibility: visible !important; width: 100%; z-index: 99998;">
    <!-- Logo a la izquierda -->
    <a href="{{ url('/') }}" class="ebuy-brand-main" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); z-index: 10; display: block !important;">
        <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties" style="height: 35px; width: auto;">
    </a>

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