<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'property_id',
        'user_id',
        'visitor_name',
        'visit_date',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
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
     * Relación con el usuario (agendador/cliente)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: próximas citas
     */
    public function scopeUpcoming($query)
    {
        return $query->where('visit_date', '>=', now())->orderBy('visit_date');
    }

    /**
     * Scope: filtrar por estado
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: por propiedad
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope: por usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
