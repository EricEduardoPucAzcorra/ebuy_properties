<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;
use App\Models\Countrie;
use App\Models\State;
use App\Models\Citie;
use App\Models\AddressPropertie;
use App\Models\Propertie;
use App\Services\PropertyRecommendationService;

class WelcomeController extends Controller
{
    public function index(Request $request, PropertyRecommendationService $aiService)
    {
        $recommendedIds = $aiService->getRecommendedIds(2);

        $query = Propertie::query()
            ->with([
                'images' => function ($q) {
                    $q->orderByDesc('is_main')->orderBy('order');
                },
                'address.city',
                'address.state',
                'address.country',
                'type',
                'operation',
                'attributes'
            ])
            ->where('is_active', true);

        if (!empty($recommendedIds)) {
            $idsOrdered = implode(',', $recommendedIds);
            $properties = $query->whereIn('id', $recommendedIds)
                ->orderByRaw("FIELD(id, $idsOrdered)")
                ->get();
        } else {
            $properties = $query->orderBy('created_at', 'desc')->paginate(10);
        }

        $stats = [
            'properties' => Propertie::where('is_active', true)->count(),
            'states' => State::count(),
            'cities' => Citie::count(),
        ];

        $topStateCounts = DB::table((new AddressPropertie())->getTable() . ' as ap')
            ->join((new Propertie())->getTable() . ' as p', 'p.id', '=', 'ap.property_id')
            ->select('ap.state_id', DB::raw('COUNT(*) as properties_count'))
            ->where('p.is_active', true)
            ->whereNotNull('ap.state_id')
            ->groupBy('ap.state_id')
            ->orderByDesc('properties_count')
            ->limit(6)
            ->get();

        $topStatesById = State::whereIn('id', $topStateCounts->pluck('state_id'))
            ->get()
            ->keyBy('id');

        $topStates = $topStateCounts
            ->map(function ($row) use ($topStatesById) {
                $st = $topStatesById->get($row->state_id);
                if (!$st) return null;
                $st->properties_count = (int) $row->properties_count;
                return $st;
            })
            ->filter()
            ->values();

        $topCityCounts = DB::table((new AddressPropertie())->getTable() . ' as ap')
            ->join((new Propertie())->getTable() . ' as p', 'p.id', '=', 'ap.property_id')
            ->select('ap.city_id', DB::raw('COUNT(*) as properties_count'))
            ->where('p.is_active', true)
            ->whereNotNull('ap.city_id')
            ->groupBy('ap.city_id')
            ->orderByDesc('properties_count')
            ->limit(6)
            ->get();

        $topCitiesById = Citie::whereIn('id', $topCityCounts->pluck('city_id'))
            ->with('state')
            ->get()
            ->keyBy('id');

        $topCities = $topCityCounts
            ->map(function ($row) use ($topCitiesById) {
                $ct = $topCitiesById->get($row->city_id);
                if (!$ct) return null;
                $ct->properties_count = (int) $row->properties_count;
                return $ct;
            })
            ->filter()
            ->values();

        return view('site.welcome.welcome', compact('properties', 'recommendedIds', 'stats', 'topStates', 'topCities'));
    }

    public function searchLocation(Request $request)
    {
        $q = trim($request->q);

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $position = Location::get('45.167.93.33'); // puedes cambiarlo a request()->ip()

        $country = null;
        $state   = null;

        if ($position) {
            $country = Countrie::where('code', $position->countryCode)->first();

            if ($country) {
                $state = State::where('countryid', $country->countryid)
                    ->where('statename', 'LIKE', '%' . $position->regionName . '%')
                    ->first();
            }
        }

        $results = [];

        $citiesQuery = Citie::with(['state.country'])
            ->where('cityname', 'LIKE', "%{$q}%");

        if ($state) {
            $citiesQuery->where('stateid', $state->stateid);
        }

        foreach ($citiesQuery->limit(5)->get() as $city) {
            $results[] = [
                'label' => "{$city->cityname}, {$city->state->statename}, {$city->state->country->countryname}",
                'type'  => $city->type,
                'id'    => $city->id
            ];
        }

        $statesQuery = State::with('country')
            ->where('statename', 'LIKE', "%{$q}%");

        if ($country) {
            $statesQuery->where('countryid', $country->countryid);
        }

        foreach ($statesQuery->limit(5)->get() as $stateItem) {
            $results[] = [
                'label' => "{$stateItem->statename}, {$stateItem->country->countryname}",
                'type'  => 'state',
                'id'    => $stateItem->id
            ];
        }

        return response()->json($results);
    }

    public function about()
    {
        return view('site.about');
    }

}
