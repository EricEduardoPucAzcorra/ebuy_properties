<!-- Versión Desktop -->
<div class="ebuy-desktop-header">
    <table style="width: 100%; max-width: 1400px; margin: 0 auto; border-collapse: collapse; height: 90px;">
        <tr>
           
            <td style="width: auto; padding: 0 30px; vertical-align: middle;">
                <a href="{{ url('/') }}" class="ebuy-brand-main">
                    <img src="{{ asset('images/ebuy_1.png') }}" alt="Ebuy Properties" style="height: 60px; width: auto;">
                </a>
            </td>
            
            <!-- Espacio flexible -->
            <td style="width: 100%;">&nbsp;</td>
            
           
            <td style="width: auto; padding: 0 2px; vertical-align: middle; white-space: nowrap;">
                <div style="display: flex; align-items: center; gap: 25px; margin-left: 120px; justify-content: flex-end;">
                   
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
                                    <!-- <li><a class="dropdown-item" href="{{route('favorites')}}"><i class="fa fa-heart me-2"></i>{{ __('Mis Favoritos') }}</a></li> -->
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