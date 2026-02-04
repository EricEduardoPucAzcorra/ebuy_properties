<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\Countrie;
use App\Models\State;
use App\Models\Citie;
use App\Models\Menu;
use App\Models\Propertie;
use App\Models\TypeOperation;
use App\Models\TypePropetie;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('site.welcome');
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
