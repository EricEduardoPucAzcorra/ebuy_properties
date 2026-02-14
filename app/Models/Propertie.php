<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propertie extends Model
{
    public $table = 'properties';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'type_property_id',
        'type_operation_id',
        'status_property_id',
        'cadastral_code',
        'title',
        'description',
        'price',
        'currency',
        'price_negotiable',
        'is_active',
        'is_exclusive',
        'presentation',
        'condition',
        'delivery',
        'delivery_obs'
    ];

    public function address()
    {
        return $this->hasOne(AddressPropertie::class, 'property_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(TypePropetie::class, 'type_property_id', 'id');
    }

    public function operation()
    {
        return $this->belongsTo(TypeOperation::class, 'type_operation_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatePropertie::class, 'status_property_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany(PropertyVideo::class, 'property_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(PropertyAttribute::class, 'property_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(PropertyFeature::class, 'property_feature', 'property_id', 'feature_id')
            ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany(PropertieViews::class, 'property_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorites::class, 'property_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany(LeadPropertie::class, 'property_id', 'id');
    }

    public function featured()
    {
        return $this->hasMany(FeaturedPropertie::class, 'property_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'property_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'property_id', 'id');
    }

    public function costs()
    {
        return $this->hasMany(PropertieCost::class, 'property_id', 'id');
    }

   public function contacts()
    {
        return $this->hasMany(PropertyContact::class, 'property_id');
    }

    public function scopeFiltered($query, array $filters)
    {
        $query->with([
            'images' => function ($q) {
                $q->orderByDesc('is_main')->orderBy('order');
            },
            'address.city', 'address.state', 'address.country', 'type', 'operation', 'attributes'
        ])->where('is_active', true);

        // Debug para ver los filtros aplicados
        \Log::info('Propertie::filtered - Filtros recibidos:', $filters);

        // Filtro por tipo de operación
        if (!empty($filters['operation'])) {
            $query->where('type_operation_id', $filters['operation']);
            \Log::info('Aplicando filtro operation:', ['operation' => $filters['operation']]);
        }

        // Filtro por tipo de propiedad
        if (!empty($filters['type'])) {
            $query->where('type_property_id', $filters['type']);
            \Log::info('Aplicando filtro type:', ['type' => $filters['type']]);
        }

        // Búsqueda por texto (título, descripción, ubicación, atributos)
        if (!empty($filters['q'])) {
            $searchTerm = $filters['q'];
            \Log::info('Aplicando búsqueda por texto:', ['q' => $searchTerm]);
            $query->where(function ($q) use ($searchTerm) {
                // Buscar en título y descripción
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('cadastral_code', 'LIKE', "%{$searchTerm}%")
                  // Buscar en dirección
                  ->orWhereHas('address', function ($subQ) use ($searchTerm) {
                      $subQ->where('street', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('number', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('neighborhood', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('postal_code', 'LIKE', "%{$searchTerm}%")
                            // Buscar en ciudad, estado, país
                            ->orWhereHas('city', function ($cityQ) use ($searchTerm) {
                                $cityQ->where('cityname', 'LIKE', "%{$searchTerm}%");
                            })
                            ->orWhereHas('state', function ($stateQ) use ($searchTerm) {
                                $stateQ->where('statename', 'LIKE', "%{$searchTerm}%");
                            })
                            ->orWhereHas('country', function ($countryQ) use ($searchTerm) {
                                $countryQ->where('name', 'LIKE', "%{$searchTerm}%");
                            });
                  })
                  // Buscar en atributos
                  ->orWhereHas('attributes', function ($attrQ) use ($searchTerm) {
                      $attrQ->where('key', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('value', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Filtro por ubicación específica (autocomplete)
        if (!empty($filters['location_type']) && !empty($filters['location_id'])) {
            \Log::info('Aplicando filtro location:', ['location_type' => $filters['location_type'], 'location_id' => $filters['location_id']]);
            $query->whereHas('address', function ($q) use ($filters) {
                if ($filters['location_type'] === 'state') {
                    $q->where('state_id', $filters['location_id']);
                } else {
                    $q->where('city_id', $filters['location_id']);
                }
            });
        }

        // Filtro por rango de precios
        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
            \Log::info('Aplicando filtro price_min:', ['price_min' => $filters['price_min']]);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
            \Log::info('Aplicando filtro price_max:', ['price_max' => $filters['price_max']]);
        }

        // Filtro por número de habitaciones
        if (!empty($filters['bedrooms'])) {
            \Log::info('Aplicando filtro bedrooms:', ['bedrooms' => $filters['bedrooms']]);
            if ($filters['bedrooms'] === '5+') {
                $query->whereHas('attributes', function ($q) {
                    $q->where('key', 'Camas')->where('value', '>=', 5);
                });
            } else {
                $query->whereHas('attributes', function ($q) use ($filters) {
                    $q->where('key', 'Camas')->where('value', $filters['bedrooms']);
                });
            }
        }

        // Filtro por número de baños
        if (!empty($filters['bathrooms'])) {
            \Log::info('Aplicando filtro bathrooms:', ['bathrooms' => $filters['bathrooms']]);
            if ($filters['bathrooms'] === '4+') {
                $query->whereHas('attributes', function ($q) {
                    $q->where('key', 'Baños')->where('value', '>=', 4);
                });
            } else {
                $query->whereHas('attributes', function ($q) use ($filters) {
                    $q->where('key', 'Baños')->where('value', $filters['bathrooms']);
                });
            }
        }

        // Filtro por superficie (m²)
        if (!empty($filters['area_min'])) {
            \Log::info('Aplicando filtro area_min:', ['area_min' => $filters['area_min']]);
            $query->whereHas('attributes', function ($q) use ($filters) {
                $q->where('key', 'M²')->where('value', '>=', $filters['area_min']);
            });
        }

        if (!empty($filters['area_max'])) {
            \Log::info('Aplicando filtro area_max:', ['area_max' => $filters['area_max']]);
            $query->whereHas('attributes', function ($q) use ($filters) {
                $q->where('key', 'M²')->where('value', '<=', $filters['area_max']);
            });
        }

        // Log de la consulta SQL generada
        \Log::info('SQL Query:', ['sql' => $query->toSql()]);

        return $query->orderBy('created_at', 'desc');
    }
}
