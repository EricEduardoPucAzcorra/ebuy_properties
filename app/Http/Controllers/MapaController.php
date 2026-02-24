<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Propertie;
use App\Models\AddressPropertie;
use App\Models\State;
use App\Models\Citie;
use App\Models\TypePropetie;
use App\Models\TypeOperation;
use App\Models\Countrie;

class MapaController extends Controller
{
    public function getLocationCoordinates(Request $request)
    {
        try {
            $type = $request->get('type');
            $id = $request->get('id');
             
            $coordinates = null;
            
            if ($type === 'state') {
                $state = State::find($id);
                if ($state) {
                    $avgCoordinates = AddressPropertie::where('state_id', $id)
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->selectRaw('AVG(latitude) as lat, AVG(longitude) as lng')
                        ->first();
                    
                    if ($avgCoordinates && $avgCoordinates->lat && $avgCoordinates->lng) {
                        $coordinates = [
                            'lat' => (float) $avgCoordinates->lat,
                            'lng' => (float) $avgCoordinates->lng
                        ];
                    } else {
                        if ($state->latitude && $state->longitude) {
                            $coordinates = [
                                'lat' => (float) $state->latitude,
                                'lng' => (float) $state->longitude
                            ];
                        } elseif ($state->country && $state->country->latitude && $state->country->longitude) {
                            $coordinates = [
                                'lat' => (float) $state->country->latitude,
                                'lng' => (float) $state->country->longitude
                            ];
                        }
                    }
                }
            } else {
                $city = Citie::find($id);
                if ($city) {
                   
                    $avgCoordinates = \App\Models\AddressPropertie::where('city_id', $id)
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->selectRaw('AVG(latitude) as lat, AVG(longitude) as lng')
                        ->first();
                    
                    if ($avgCoordinates && $avgCoordinates->lat && $avgCoordinates->lng) {
                        $coordinates = [
                            'lat' => (float) $avgCoordinates->lat,
                            'lng' => (float) $avgCoordinates->lng
                        ];
                       
                    } else {
                       
                        if ($city->latitude && $city->longitude) {
                            $coordinates = [
                                'lat' => (float) $city->latitude,
                                'lng' => (float) $city->longitude
                            ];
                           
                        } elseif ($city->state && $city->state->latitude && $city->state->longitude) {
                            $coordinates = [
                                'lat' => (float) $city->state->latitude,
                                'lng' => (float) $city->state->longitude
                            ];
                            
                        } elseif ($city->state->country && $city->state->country->latitude && $city->state->country->longitude) {
                            $coordinates = [
                                'lat' => (float) $city->state->country->latitude,
                                'lng' => (float) $city->state->country->longitude
                            ];
                           
                        }
                    }
                }
            }
            
            return response()->json($coordinates ?: ['lat' => null, 'lng' => null]);
            
        } catch (\Exception $e) {
            \Log::error('Error en getLocationCoordinates: ' . $e->getMessage());
            return response()->json(['lat' => null, 'lng' => null]);
        }
    }

    public function mapa()
    {
        $type_properties = TypePropetie::all();
        $type_operations = TypeOperation::all();
        $countries = Countrie::orderBy('countryname')->get();
        $states = State::with('country')->orderBy('statename')->get();
        $cities = Citie::with('state.country')->orderBy('cityname')->get();

        $recommendedIds = [];

        try {
            $aiService = app(\App\Services\PropertyRecommendationService::class);
            $recommendedIds = $aiService->getRecommendedIds(6);
        } catch (\Exception $e) {
            $recommendedIds = [];
        }

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
            ->where('is_active', true)
            ->whereHas('address', function ($query) {
                $query->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            });

        if (!empty($recommendedIds)) {
            $idsOrdered = implode(',', $recommendedIds);
            $recommendedProperties = $query->whereIn('id', $recommendedIds)
                ->orderByRaw("FIELD(id, $idsOrdered)")
                ->get();
        } else {
            $recommendedProperties = $query->orderBy('created_at', 'desc')->take(6)->get();
        }

        $mostViewed = Propertie::with([
                'images' => function ($q) {
                    $q->orderByDesc('is_main')->orderBy('order');
                },
                'address.city',
                'type',
                'operation'
            ])
            ->where('is_active', true)
            ->whereHas('address', function ($query) {
                $query->whereNotNull('latitude')
                      ->whereNotNull('longitude');
            })
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('site.mapa', compact(
            'recommendedProperties', 
            'mostViewed',
            'type_properties',
            'type_operations',
            'countries',
            'states',
            'cities'
        ));
    }

    public function properties_map(Request $request)
    {
        try {
            $query = Propertie::with([
                'address.city.state.country',
                'images' => function ($q) {
                    $q->orderByDesc('is_main')->orderBy('order');
                },
                'type',
                'operation'
            ])
            ->where('is_active', true)
            ->whereHas('address', function ($query) {
                $query->whereNotNull('latitude')
                    ->whereNotNull('longitude');
            });

            if ($request->type) {
                $query->where('type_property_id', $request->type);
            }

            if ($request->operation) {
                $query->where('type_operation_id', $request->operation);
            }

            if ($request->location_type && $request->location_id) {
                if ($request->location_type === 'state') {
                    $state = State::where('stateid', $request->location_id)->first();
                    if ($state) {
                        $query->whereHas('address', function ($q) use ($state) {
                            $q->where('state_id', $state->id);
                        });
                    }
                } else {
                    $city = Citie::where('cityid', $request->location_id)->first();
                    if ($city) {
                        $query->whereHas('address', function ($q) use ($city) {
                            $q->where('city_id', $city->id);
                        });
                    }
                }
            }
            else if ($request->q) {
                $searchTerm = $request->q;
                
                $query->where(function ($q) use ($searchTerm) {
                    $q->orWhereHas('address.city', function ($q) use ($searchTerm) {
                        $q->where('cityname', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('address.state', function ($q) use ($searchTerm) {
                        $q->where('statename', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('address.country', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhere('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            $properties = $query->get();
            
            $locations = $properties->map(function ($property) {
                $mainImage = $property->images->firstWhere('is_main', true);
                
                return [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => number_format($property->price, 2),
                    'currency' => $property->currency ?? '$',
                    'lat' => $property->address->latitude,
                    'lng' => $property->address->longitude,
                    'image' => $mainImage ? asset('storage/' . $mainImage->path) : null,
                    'type_operation' => $property->operation->name ?? '',
                    'type_property' => $property->type->name ?? '',
                    'url' => route('property', $property->id)
                ];
            });

            return response()->json($locations);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => auto_trans('Error al cargar las propiedades: ' . $e->getMessage())
            ], 500);
        }
    }
}
