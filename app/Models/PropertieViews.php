<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertieViews extends Model
{
    protected $table = 'property_views';

    protected $fillable = [
        'property_id',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con propiedad
     */
    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }

    /**
     * Scope: recientes
     */
    public function scopeRecent($query, $limit = 20)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Scope: por propiedad
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Comprueba si la IP ya registró una vista para una propiedad
     */
    public static function existsFromIp($propertyId, $ip)
    {
        return self::where('property_id', $propertyId)
            ->where('ip_address', $ip)
            ->exists();
    }
}
