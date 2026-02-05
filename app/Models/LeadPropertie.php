<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadPropertie extends Model
{
    protected $table = 'property_leads';

    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'message',
    ];

    protected $casts = [
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
     * Scope para obtener leads recientes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope para filtrar por propiedad
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }
}
