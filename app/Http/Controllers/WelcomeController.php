<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\Countrie;
use App\Models\State;
use App\Models\Citie;
use App\Models\Propertie;
use App\Models\TypeOperation;
use App\Models\TypePropetie;

class WelcomeController extends Controller
{
    public function index()
    {
        $type_properties = TypePropetie::all();
        $type_operations = TypeOperation::all();

        return view('site.welcome', compact(
            'type_properties',
            'type_operations'
        ));
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

        // 🔹 CIUDADES
        $citiesQuery = Citie::with(['state.country'])
            ->where('cityname', 'LIKE', "%{$q}%");

        if ($state) {
            $citiesQuery->where('stateid', $state->stateid);
        }

        foreach ($citiesQuery->limit(5)->get() as $city) {
            $results[] = [
                'label' => "{$city->cityname}, {$city->state->statename}, {$city->state->country->countryname}",
                'type'  => 'city',
                'id'    => $city->cityid
            ];
        }

        // 🔹 ESTADOS
        $statesQuery = State::with('country')
            ->where('statename', 'LIKE', "%{$q}%");

        if ($country) {
            $statesQuery->where('countryid', $country->countryid);
        }

        foreach ($statesQuery->limit(5)->get() as $stateItem) {
            $results[] = [
                'label' => "{$stateItem->statename}, {$stateItem->country->countryname}",
                'type'  => 'state',
                'id'    => $stateItem->stateid
            ];
        }

        return response()->json($results);
    }

    public function search(Request $request)
    {
        $query = Propertie::query()
            ->with(['address.city.state.country'])
            ->where('is_active', true);

        if ($request->operation) {
            $query->where('type_operation_id', $request->operation);
        }

        if ($request->type) {
            $query->where('type_property_id', $request->type);
        }

        if ($request->location_type && $request->location_id) {

            $query->whereHas('address', function ($q) use ($request) {

                if ($request->location_type === 'city') {
                    $q->where('city_id', $request->location_id);
                }

                if ($request->location_type === 'state') {
                    $q->where('state_id', $request->location_id);
                }
            });
        }

        $properties = $query
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('site.list_properties', compact('properties'));
    }

}
