 <div class="company-card border-bottom">
    <div class="company-banner">
        @php
            $tenant = $tenants->firstWhere('id', $activeTenantId);
            $logo = $tenant && $tenant->logo
                ? asset('storage/' . $tenant->logo)
                : asset('images/ebuy_2.jpg');
        @endphp

        <img src="{{ $logo }}" alt="Logo" class="company-image img-fluid">
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

