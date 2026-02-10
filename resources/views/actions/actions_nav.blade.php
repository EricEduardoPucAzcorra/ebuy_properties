@php
    use App\Models\Menu;
    use App\Models\Countrie;
    use App\Models\State;
    use App\Models\Citie;
    use Stevebauman\Location\Facades\Location;


    $position = Location::get('45.167.93.33');
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
