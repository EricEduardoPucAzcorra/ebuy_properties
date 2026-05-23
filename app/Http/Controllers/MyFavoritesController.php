<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertie;
use Illuminate\Support\Facades\Auth;

class MyFavoritesController extends Controller
{
    public function view()
    {
        return view('owner.favorites');
    }

    public function ownerFavoriteProperties(Request $request)
    {
        $query = Propertie::query()
            ->where('user_id', Auth::id()) 
            ->whereHas('favorites') 
            ->with([
                'address.city.state.country',
                'images',
                'videos',
                'tours',
                'attributes',
                'features',
                'contacts'
            ])
            ->withCount('favorites'); 

        // if ($request->type_operation_id) {
        //     $query->where('type_operation_id', $request->type_operation_id);
        // }

        // if ($request->operation) {
        //     $query->where('type_operation_id', $request->operation);
        // }

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

        $query->orderBy('favorites_count', 'desc');
        
        $properties = $query->paginate(10);

        $formatted = $properties->getCollection()->map(function ($property) {
            $mainImage = $property->images->firstWhere('is_main', true);
            $otherImages = $property->images->where('is_main', false)->values();

            return [
                'id' => $property->id,
                'title' => $property->title,
                'cadastral_code' => $property->cadastral_code,
                'description' => $property->description,
                'price' => number_format($property->price, 2),
                'type_property_id' => $property->type->id ?? null,
                'type_property' => $property->type->name ?? null,
                'type_operation' => $property->operation->name ?? null,
                'type_operation_id' => $property->operation->id ?? null,
                'created_at' => $property->created_at->format('d/m/Y'),
                'currency' => $property->currency ?? null,
                'price_negotiable' => $property->price_negotiable ?? null,
                'status_property_id' => $property->status->id ?? null,
                'status' => $property->status->name ?? null,
                'favorites_count' => $property->favorites_count,
                'address' => [
                    'id' => $property->address->id ?? null,
                    'property_id' => $property->address->property_id ?? null,
                    'street' => $property->address->street ?? null,
                    'number' => $property->address->number ?? null,
                    'neighborhood' => $property->address->neighborhood ?? null,
                    'city_name' => $property->address->city->cityname ?? null,
                    'city' => [
                        'id' => $property->address->city->cityid ?? null,
                        'name' => $property->address->city->cityname ?? null,
                        'lat' => $property->address->city->latitude ?? null,
                        'lng' => $property->address->city->longitude ?? null
                    ],
                    'state_name' => $property->address->city->state->statename ?? null,
                    'state' => [
                        'id' => $property->address->city->state->stateid ?? null,
                        'name' => $property->address->city->state->statename ?? null,
                        'lat' => $property->address->city->state->latitude ?? null,
                        'lng' => $property->address->city->state->longitude ?? null
                    ],
                    'country_name' => $property->address->city->state->country->name ?? null,
                    'country' => [
                        'id' => $property->address->city->state->country->countryid ?? null,
                        'name' => $property->address->city->state->country->name ?? null,
                        'lat' => $property->address->city->state->country->latitude ?? null,
                        'lng' => $property->address->city->state->country->longitude ?? null
                    ],
                    'location' => [
                        'latitude' => $property->address->latitude ?? null,
                        'longitude' => $property->address->longitude ?? null,
                    ],
                    'postal_code' => $property->address->postal_code ?? null,
                    'references' => $property->address->references ?? null
                ],
                'images' => [
                    'main' => $mainImage ? asset('storage/' . $mainImage->path) : null,
                    'others' => $otherImages->map(fn($img) => asset('storage/' . $img->path)),
                ],
                'videos' => $property->videos->map(fn($video) => $video->url),
                'tours' => $property->tours->map(fn($tour) => $tour->url),
                'attributes' => $property->attributes->map(fn($attr) => [
                    'key' => $attr->key,
                    'value' => $attr->value,
                ]),
                'features' => $property->features->pluck('id')->values()->toArray(),
                'contacts' => $property->contacts->map(fn($contact) => [
                    'id' => $contact->id,
                    'property_id' => $contact->property_id,
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'whatsapp' => $contact->whatsapp,
                    'email' => $contact->email,
                    'date_atention' => $contact->date_atention,
                    'photo' => $contact->photo ? asset('storage/' . $contact->photo) : null,
                ])->values()
            ];
        });

        $properties->setCollection($formatted);

        return response()->json($properties);
    }
}
