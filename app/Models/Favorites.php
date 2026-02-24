<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = 'favorites';

    protected $fillable = [
        'user_id',
        'property_id',
        'session_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la propiedad
     */
    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }

    /**
     * Scope: filtrar favoritos por usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: filtrar favoritos por propiedad
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope: favoritos recientes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Comprueba si un usuario ya marcó una propiedad como favorita
     */
    public static function isFavoritedBy($userId, $propertyId)
    {
        return self::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->exists();
    }

    /**
     * Comprueba si una sesión ya marcó una propiedad como favorita
     */
    public static function isFavoritedBySession($sessionId, $propertyId)
    {
        return self::where('session_id', $sessionId)
            ->where('property_id', $propertyId)
            ->exists();
    }
}
