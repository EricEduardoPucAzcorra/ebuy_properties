<?php

namespace App\Http\Controllers;

use App\Models\Propertie;
use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function ownerPropertiesView(){
        return view('owner.properties');
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

        return view('site.properties', compact('properties'));
    }
}
