<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'property_id',
        'reason',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la propiedad reportada
     */
    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }

    /**
     * Scope: por propiedad
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope: por razón (exacta o parcial)
     */
    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', 'like', "%{$reason}%");
    }

    /**
     * Scope: recientes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Accesor: fragmento de la descripción
     */
    public function getShortDescriptionAttribute()
    {
        return mb_strimwidth($this->description ?? '', 0, 200, '...');
    }
}
