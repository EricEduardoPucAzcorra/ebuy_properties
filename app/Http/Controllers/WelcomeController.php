<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\Countrie;
use App\Models\State;
use App\Models\Citie;
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

        return view('site.welcome', compact('properties', 'recommendedIds'));
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
                'type'  => 'city',
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
