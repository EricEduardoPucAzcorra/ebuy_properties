<?php

namespace App\Http\Controllers;

use App\Models\AddressPropertie;
use App\Models\Citie;
use App\Models\Countrie;
use App\Models\Propertie;
use App\Models\PropertyContact;
use App\Models\State;
use App\Models\StatePropertie;
use App\Models\TypeOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertiesController extends Controller
{
    public function properties_global(Request $request)
    {
        \Log::info('PropertiesController - Parámetros recibidos:', $request->all());
        
        if (!$request->operation) {
            if (request()->routeIs('properties.sale')) {
                $op = TypeOperation::where('name', 'Venta')->first();
                if ($op) $request->merge(['operation' => $op->id]);
            } elseif (request()->routeIs('properties.rent')) {
                $op = TypeOperation::where('name', 'Renta')->first();
                if ($op) $request->merge(['operation' => $op->id]);
            }
        }

        $query = Propertie::filtered($request->all());

        if (request()->routeIs('properties.new')) {
            $query->whereDate('created_at', today());
        }

        $properties = $query->paginate(10)->withQueryString();

        return view('site.properties', compact('properties'));
    }

    public function states_properties()
    {
        return response()->json(
            StatePropertie::where('is_active', true)->get()
        );
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
                'attributes',
                'features',
                'contacts'
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

        $properties = $query->orderBy('created_at', 'desc')->paginate(10);

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
                'currency'=>$property->currency ?? null,
                'price_negotiable'=>$property->price_negotiable ?? null,
                'status_property_id'=> $property->status->id,
                'status'=> $property->status->name,
                'address' => [
                    'id' => $property->address->id ?? null,
                    'property_id'=> $property->address->property_id ?? null,
                    'street' => $property->address->street ?? null,
                    'number' => $property->address->number ?? null,
                    'neighborhood'=> $property->address->neighborhood ?? null,
                    'city_name' => $property->address->city->cityname ?? null,
                    'city' =>[
                        'id'=> $property->address->city->cityid ?? null,
                        'name'=> $property->address->city->cityname ?? null,
                        'lat'=> $property->address->city->latitude ?? null,
                        'lng'=>$property->address->city->longitude ?? null
                    ],
                    'state_name' => $property->address->city->state->statename ?? null,
                    'state' =>[
                        'id'=> $property->address->city->state->stateid ?? null,
                        'name'=> $property->address->city->state->statename ?? null,
                        'lat'=> $property->address->city->state->latitude ?? null,
                        'lng'=>$property->address->city->state->longitude ?? null
                    ],
                    'country_name' => $property->address->city->state->country->name ?? null,
                    'country' =>[
                        'id'=> $property->address->city->state->country->countryid ?? null,
                        'name'=> $property->address->city->state->country->name?? null,
                        'lat'=> $property->address->city->state->country->latitude ?? null,
                        'lng'=>$property->address->city->state->country->longitude ?? null
                    ],
                    'location'=>[
                        'latitude'=> $property->address->latitude ?? null,
                        'longitude'=> $property->address->longitude?? null,
                    ],
                    'postal_code' => $property->address->postal_code ?? null,
                    'references'=>$property->address->references ?? null
                ],
                'images' => [
                    'main' => $mainImage ? asset('storage/' . $mainImage->path) : null,
                    'others' => $otherImages->map(fn($img) => asset('storage/' . $img->path)),
                ],
                'videos' => $property->videos->map(fn($video) => asset('storage/' . $video->url)),
                'attributes' => $property->attributes->map(fn($attr) => [
                    'key'   => $attr->key,
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
                    'photo' => $contact->photo
                        ? asset('storage/' . $contact->photo)
                        : null,
                ])->values()
            ];
        });

        $properties->setCollection($formatted);

        return response()->json($properties);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cadastral_code'=>'required|string|max:255|unique:properties,cadastral_code',
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
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric',
            'features' => 'nullable|array',
            'attributes' => 'nullable|array',
            'media.files' => 'nullable|array',
            'media.files.*.file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.phone' => 'required|string|max:50',
            'contacts.*.whatsapp' => 'nullable|string|max:50',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.date_atention' => 'nullable|date',
            'contacts.*.photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $price_negotiable = 0;
            if($request->price_negotiable === 'SI'){
                $price_negotiable  = 1;
            }
            $property = Propertie::create([
                'cadastral_code'=>$request->cadastral_code,
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'type_property_id' => $request->type_property_id,
                'type_operation_id' => $request->type_operation_id,
                'price' => $request->price,
                'currency' => $request->currency ?? 'MNX',
                'price_negotiable'=>$price_negotiable,
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

            if ($request->filled('contacts')) {
                foreach ($request->contacts as $index => $contact) {

                    $photoPath = null;

                    if ($request->hasFile("contacts.$index.photo")) {
                        $photo = $request->file("contacts.$index.photo");

                        $filename = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                        $photoPath = $photo->storeAs('property_contacts', $filename, 'public');
                    }

                    PropertyContact::create([
                        'property_id'    => $property->id,
                        'name'           => $contact['name'],
                        'phone'          => $contact['phone'],
                        'whatsapp'       => $contact['whatsapp'] ?? null,
                        'email'          => $contact['email'] ?? null,
                        'date_atention'  =>  null,
                        'photo'          => null,
                    ]);
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => auto_trans('Propiedad guardada correctamente'),
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

      public function update(Request $request, $id)
    {
        $property = Propertie::findOrFail($id);

        $request->validate([
            'cadastral_code' => 'required|string|max:255|unique:properties,cadastral_code,' . $property->id,
            'title' => 'required|string|max:255',
            'type_property_id' => 'required|integer',
            'type_operation_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'address.street' => 'required|string',
            'address.country_id' => 'required|integer',
            'address.state_id' => 'required|integer',
            'address.city_id' => 'required|integer',
            'features' => 'nullable|array',
            'attributes' => 'nullable|array',
            'keep_media' => 'nullable|array',
            'media' => 'nullable|array',
            'contacts' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $price_negotiable = $request->price_negotiable === 'SI' ? 1 : 0;
            $property->update([
                'cadastral_code' => $request->cadastral_code,
                'title' => $request->title,
                'description' => $request->description,
                'type_property_id' => $request->type_property_id,
                'type_operation_id' => $request->type_operation_id,
                'price' => $request->price,
                'currency' => $request->currency ?? 'MNX',
                'price_negotiable' => $price_negotiable,
                'status_property_id' => $request->status_property_id ?? $property->status_property_id,
            ]);

            $country = Countrie::where('countryid', $request->input('address.country_id'))->first();
            $state   = State::where('stateid', $request->input('address.state_id'))->first();
            $city    = Citie::where('cityid', $request->input('address.city_id'))->first();

            $property->address()->update([
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

            $property->features()->sync($request->features ?? []);
            $property->attributes()->delete();
            foreach ($request->input('attributes', []) as $attr) {
                if (!empty($attr['key']) && isset($attr['value'])) {
                    $property->attributes()->create($attr);
                }
            }

            $keepMediaRaw = $request->input('keep_media', []);

            $keepPaths = collect($keepMediaRaw)->where('type', 'image')->pluck('path')->toArray();

            $imagesToDelete = $property->images()->whereNotIn('path', $keepPaths)->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }

            foreach ($keepMediaRaw as $item) {
                if ($item['type'] === 'image') {
                    $property->images()
                        ->where('path', $item['path'])
                        ->update(['is_main' => ($item['is_primary'] == "1" || $item['is_primary'] == "true")]);
                }
            }

            $keepVideoPaths = collect($keepMediaRaw)->where('type', 'video')->pluck('path')->toArray();
            $videosToDelete = $property->videos()->whereNotIn('url', $keepVideoPaths)->get();
            foreach ($videosToDelete as $vid) {
                Storage::disk('public')->delete($vid->url);
                $vid->delete();
            }

            if ($request->has('media')) {
                foreach ($request->file('media') as $index => $mediaData) {
                    if (!isset($mediaData['file'])) continue;

                    $file = $mediaData['file'];
                    $type = $request->input("media.$index.type", 'image');
                    $isMain = ($request->input("media.$index.is_primary") == "1" || $request->input("media.$index.is_primary") == "true");

                    $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('properties', $filename, 'public');

                    if ($type === 'image') {
                        if ($isMain) $property->images()->update(['is_main' => false]);

                        $property->images()->create([
                            'path' => $path,
                            'is_main' => $isMain,
                            'order' => 0
                        ]);
                    } else {
                        $property->videos()->create(['url' => $path]);
                    }
                }
            }

            $incomingContacts = collect($request->input('contacts', []));

            $incomingIds = $incomingContacts
                ->pluck('id')
                ->filter()
                ->values()
                ->toArray();

            $property->contacts()
                ->whereNotIn('id', $incomingIds)
                ->get()
                ->each(function ($contact) {
                    if ($contact->photo) {
                        Storage::disk('public')->delete($contact->photo);
                    }
                    $contact->delete();
                });

            foreach ($incomingContacts as $index => $contactData) {

                $contact = $property->contacts()
                    ->where('id', $contactData['id'] ?? null)
                    ->first();

                if ($request->hasFile("contacts.$index.photo")) {

                    if ($contact && $contact->photo) {
                        Storage::disk('public')->delete($contact->photo);
                    }

                    $photoPath = $request
                        ->file("contacts.$index.photo")
                        ->store('property_contacts', 'public');

                } else {
                    $photoPath = $contact->photo ?? null;
                }

                $property->contacts()->updateOrCreate(
                    ['id' => $contactData['id'] ?? null],
                    [
                        'name' => $contactData['name'],
                        'phone' => $contactData['phone'],
                        'whatsapp' => $contactData['whatsapp'] ?? null,
                        'email' => $contactData['email'] ?? null,
                        'photo' => null,
                    ]
                );
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => auto_trans('Propiedad actualizada con éxito')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:status_properties,id'
        ]);

        $property = Propertie::findOrFail($id);
        $property->status_property_id = $request->status_id;
        $property->save();

        return response()->json(['success' => true]);
    }

    public function property($id)
    {
        $property = Propertie::with([
                'address.city.state.country',
                'images',
                'videos',
                'attributes',
                'features',
                'contacts',
                'type',
                'operation',
                'status'
            ])
            ->find($id);

        $properties = Propertie::filtered([
            'location_type' => $property->address->city->type,
            'location_id'   => $property->address->city_id,
            'operation'     => $property->type_operation_id,
            'type'          => $property->type_property_id
        ])
        ->where('id', '!=', $property->id)
        ->paginate(6, ['*'], 'related_page')
        ->withQueryString();

        if (!$property) {
            abort(404, "La propiedad no existe en nuestra base de datos.");
        }

        $mainImage = $property->images->firstWhere('is_main', true);
        $otherImages = $property->images->where('is_main', false)->values();

        $formattedProperty = [
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
            'address' => [
                'id' => $property->address->id ?? null,
                'property_id' => $property->address->property_id ?? null,
                'street' => $property->address->street ?? null,
                'number' => $property->address->number ?? null,
                'neighborhood' => $property->address->neighborhood ?? null,
                'city' => [
                    'id' => $property->address->city->cityid ?? null,
                    'name' => $property->address->city->cityname ?? null,
                    'lat' => $property->address->city->latitude ?? null,
                    'lng' => $property->address->city->longitude ?? null
                ],
                'state' => [
                    'id' => $property->address->city->state->stateid ?? null,
                    'name' => $property->address->city->state->statename ?? null,
                ],
                'country' => [
                    'id' => $property->address->city->state->country->countryid ?? null,
                    'name' => $property->address->city->state->country->name ?? null,
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
            'videos' => $property->videos->map(fn($video) => asset('storage/' . $video->url)),
            'attributes' => $property->attributes->map(fn($attr) => [
                'key' => $attr->key,
                'value' => $attr->value,
            ]),
            // 'features' => $property->features->pluck('id')->values()->toArray(),
            'features' => $property->features->groupBy(function($feature) {
                return $feature->category->name ?? auto_trans('Otros');
            })->map(function($group) {
                return $group->map(fn($f) => [
                    'name' => $f->name,
                    'icon' => $f->icon
                ]);
            }),
            'contacts' => $property->contacts->map(fn($contact) => [
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->phone,
                'whatsapp' => $contact->whatsapp,
                'email' => $contact->email,
                'photo' => $contact->photo ? asset('storage/' . $contact->photo) : null,
            ])
        ];

        return view('site.property', ['propertie' => (object)$formattedProperty, 'properties'=>$properties]);
    }
}
