<?php

namespace App\Http\Controllers;

use App\Models\Citie;
use App\Models\Countrie;
use App\Models\State;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function search_countries(Request $request)
    {
        $q = $request->get('q', '');

        return Countrie::where('countryname', 'LIKE', "%{$q}%")
            ->orderBy('countryname')
            ->limit(10)
            ->get([
                'countryid as id',
                'countryname as name'
            ]);
    }

    public function search_states(Request $request)
    {
        $q = $request->get('q', '');
        $countryId = $request->get('country_id');

        $states = State::where('statename', 'LIKE', "%{$q}%");

        if ($countryId) {
            $states->where('countryid', $countryId);
        }

        return $states
            ->orderBy('statename')
            ->limit(10)
            ->get([
                'stateid as id',
                'statename as name'
            ]);
    }

    public function search_cities(Request $request)
    {
        $q = $request->get('q', '');
        $stateId = $request->get('state_id');

        $cities = Citie::where('cityname', 'LIKE', "%{$q}%");

        if ($stateId) {
            $cities->where('stateid', $stateId);
        }

        return $cities
            ->orderBy('cityname')
            ->limit(10)
            ->get([
                'cityid as id',
                'cityname as name'
            ]);
    }
}
