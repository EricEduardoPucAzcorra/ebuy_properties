<?php

namespace App\Http\Controllers;

use App\Models\AddressPropertie;
use App\Models\Citie;
use App\Models\Countrie;
use App\Models\Propertie;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertiesController extends Controller
{
    public function properties_global(Request $request)
    {
        $query = Propertie::query()
            ->with([
                'images' => function ($q) {
                    $q->orderByDesc('is_main')
                    ->orderBy('order');
                },
                'address.city',
                'address.state',
                'address.country',
                'type',
                'operation',
                'attributes'
            ])
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
            ->paginate(10)
            ->withQueryString();

        return view('site.properties', compact('properties'));
    }

    public function ownerPropertiesView(){
        return view('owner.properties');
    }

    public function ownerMyProperties(Request $request)
    {
        $query = Propertie::query()
            ->where('user_id', Auth::id())
            ->with([
                'address.city.state.country',
                'images',
                'videos',
                'attributes'
            ]);

        if ($request->type_operation_id) {
            $query->where('type_operation_id', $request->type_operation_id);
        }

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

        $properties = $query->orderBy('created_at', 'desc')->paginate(12);

        $formatted = $properties->getCollection()->map(function ($property) {
            $mainImage = $property->images->firstWhere('is_main', true);
            $otherImages = $property->images->where('is_main', false)->values();

            return [
                'id' => $property->id,
                'title' => $property->title,
                'description' => $property->description,
                'price' => number_format($property->price, 2),
                'type_property' => $property->type->name ?? null,
                'type_operation' => $property->operation->name ?? null,
                'created_at' => $property->created_at->format('d/m/Y'),
                'status'=> $property->status->name,
                'address' => [
                    'street' => $property->address->street ?? null,
                    'number' => $property->address->number ?? null,
                    'city' => $property->address->city->cityname ?? null,
                    'state' => $property->address->city->state->statename ?? null,
                    'country' => $property->address->city->state->country->name ?? null,
                    'postal_code' => $property->address->postal_code ?? null,
                ],
                'images' => [
                    'main' => $mainImage ? asset('storage/' . $mainImage->path) : null,
                    'others' => $otherImages->map(fn($img) => asset('storage/' . $img->path)),
                ],
                'videos' => $property->videos->map(fn($video) => asset('storage/' . $video->url)),
                'attributes' => $property->attributes->map(fn($attr) => [
                    'key'   => $attr->key,
                    'value' => $attr->value,
                ])
            ];
        });

        $properties->setCollection($formatted);

        return response()->json($properties);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type_property_id' => 'required|integer',
            'type_operation_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'address.street' => 'required|string',
            'address.number' => 'nullable|string',
            'address.neighborhood' => 'nullable|string',
            'address.postal_code' => 'nullable|string',
            'address.country_id' => 'required|integer',
            'address.state_id' => 'required|integer',
            'address.city_id' => 'required|integer',
            'address.latitude' => 'required|numeric',
            'address.longitude' => 'required|numeric',
            'features' => 'nullable|array',
            'attributes' => 'nullable|array',
            'media.files' => 'nullable|array',
            'media.files.*.file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov',
        ]);

        DB::beginTransaction();

        try {
            $property = Propertie::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'type_property_id' => $request->type_property_id,
                'type_operation_id' => $request->type_operation_id,
                'price' => $request->price,
                'currency' => $request->currency ?? 'MNX',
                'status_property_id' => $request->status_property_id ?? 2,
                'is_active' => true,
            ]);

            $country = Countrie::where('countryid', intval($request->input('address.country_id')))->first();
            $state = State::where('stateid', intval($request->input('address.state_id')))->first();
            $city = Citie::where('cityid', intval($request->input('address.city_id')))->first();

            $address = new AddressPropertie([
                'street' => $request->input('address.street'),
                'number' => $request->input('address.number'),
                'neighborhood' => $request->input('address.neighborhood'),
                'postal_code' => $request->input('address.postal_code'),
                'country_id' => $country->id,
                'state_id' => $state->id,
                'city_id' => $city->id,
                'latitude' => $request->input('address.latitude'),
                'longitude' => $request->input('address.longitude'),
                'references' => $request->input('address.references'),
            ]);

            $property->address()->save($address);

            if ($request->filled('features')) {
                $property->features()->sync($request->features);
            }

            if ($request->filled('attributes')) {
                $attributes = $request->input('attributes');

                foreach ($attributes as $attr) {
                    if (!empty($attr['key']) && isset($attr['value'])) {
                        $property->attributes()->create([
                            'key' => $attr['key'],
                            'value' => $attr['value'],
                        ]);
                    }
                }
            }

            $mediaFiles = $request->file('media', []);
            foreach ($mediaFiles as $index => $media) {
                if (!isset($media['file'])) continue;

                $file = $media['file'];
                $type = $request->input("media.$index.type", 'image');
                $isMain = $request->input("media.$index.is_primary", false);

                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('properties', $filename, 'public');

                if ($type === 'image') {
                    $property->images()->create([
                        'path' => $path,
                        'order' => 0,
                        'is_main' => $isMain,
                    ]);
                } elseif ($type === 'video') {
                    $property->videos()->create([
                        'url' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Propiedad guardada correctamente',
                'property_id' => $property->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la propiedad: ' . $e->getMessage()
            ], 500);
        }
    }
}
