<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedPropertie extends Model
{
    protected $table = 'featured_properties';

    protected $fillable = [
        'property_id',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la propiedad
     */
    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }

    /**
     * Scope para propiedades destacadas activas
     */
    public function scopeActive($query)
    {
        return $query->where('starts_at', '<=', now())
                     ->where('ends_at', '>=', now());
    }
}
