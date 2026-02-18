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
                'countryname as name',
                'latitude as lat',
                'longitude as lng'
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
                'statename as name',
                'latitude as lat',
                'longitude as lng'
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
                'cityname as name',
                'latitude as lat',
                'longitude as lng'
            ]);
    }

    public function reverse_geocode(Request $request)
    {
        $lat = floatval($request->get('lat'));
        $lng = floatval($request->get('lng'));

        \Log::info("Reverse geocoding - Coords: {$lat}, {$lng}");

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Coordinates are required'], 400);
        }

        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return response()->json(['error' => 'Invalid coordinates'], 400);
        }

        $city = Citie::selectRaw('*, (
            6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))
        ) as distance', [$lat, $lng, $lat])
            ->having('distance', '<=', 30) 
            ->orderBy('distance')
            ->first();

        if ($city) {
            // Obtener el estado usando el stateid de la ciudad
            $state = State::where('stateid', $city->stateid)->first();
            
            // Obtener el país usando el countryid de la ciudad
            $country = Countrie::where('countryid', $city->countryid)->first();
            
            // Verificar consistencia: el estado debe pertenecer al mismo país
            if ($state && $country && $state->countryid == $country->countryid) {
                return response()->json([
                    'country' => [
                        'id' => $country->countryid,
                        'name' => $country->countryname,
                        'lat' => floatval($country->latitude),
                        'lng' => floatval($country->longitude)
                    ],
                    'state' => [
                        'id' => $state->stateid,
                        'name' => $state->statename,
                        'lat' => floatval($state->latitude),
                        'lng' => floatval($state->longitude)
                    ],
                    'city' => [
                        'id' => $city->cityid,
                        'name' => $city->cityname,
                        'lat' => floatval($city->latitude),
                        'lng' => floatval($city->longitude)
                    ]
                ]);
            }
        }

        // Si no hay ciudad, buscar estado por coordenadas
        $state = State::selectRaw('*, (
            6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))
        ) as distance', [$lat, $lng, $lat])
            ->having('distance', '<=', 100) // Máximo 100km para estados
            ->orderBy('distance')
            ->first();

        if ($state) {
            // Obtener el país usando el countryid del estado
            $country = Countrie::where('countryid', $state->countryid)->first();
            
            if ($country) {
                return response()->json([
                    'country' => [
                        'id' => $country->countryid,
                        'name' => $country->countryname,
                        'lat' => floatval($country->latitude),
                        'lng' => floatval($country->longitude)
                    ],
                    'state' => [
                        'id' => $state->stateid,
                        'name' => $state->statename,
                        'lat' => floatval($state->latitude),
                        'lng' => floatval($state->longitude)
                    ],
                    'city' => null
                ]);
            }
        }

        // Si no hay estado, buscar país por coordenadas
        $country = Countrie::selectRaw('*, (
            6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))
        ) as distance', [$lat, $lng, $lat])
            ->having('distance', '<=', 500) // Máximo 500km para países
            ->orderBy('distance')
            ->first();

        if (!$country) {
            return response()->json(['error' => 'No country found within reasonable distance'], 404);
        }

        return response()->json([
            'country' => [
                'id' => $country->countryid,
                'name' => $country->countryname,
                'lat' => floatval($country->latitude),
                'lng' => floatval($country->longitude)
            ],
            'state' => null,
            'city' => null
        ]);
    }
}
