<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyUser extends Model
{
    protected $table = 'agency_user';

    protected $fillable = [
        'agency_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Agency
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Relación con User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: por agencia
     */
    public function scopeForAgency($query, $agencyId)
    {
        return $query->where('agency_id', $agencyId);
    }

    /**
     * Scope: por usuario
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Comprueba si existe la relación agencia-usuario
     */
    public static function existsFor($agencyId, $userId)
    {
        return self::where('agency_id', $agencyId)
            ->where('user_id', $userId)
            ->exists();
    }
}
