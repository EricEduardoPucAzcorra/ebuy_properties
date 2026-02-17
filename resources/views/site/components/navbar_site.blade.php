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

    @include('site.components.nav.nav-desktop')

    @include('site.components.nav.nav-movil')

</header>

<script src="{{ asset('site/js/desktop-menu.js') }}"></script>
<script src="{{ asset('site/js/mobile-menu.js') }}"></script>
