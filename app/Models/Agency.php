<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Agency extends Model
{
    protected $table = 'agencies';

    protected $fillable = [
        'name',
        'logo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Usuarios relacionados (agentes/perfiles)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'agency_user');
    }

    /**
     * Scope: agencias con logo
     */
    public function scopeWithLogo($query)
    {
        return $query->whereNotNull('logo');
    }

    /**
     * Scope: buscar por nombre (partial)
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope: recientes
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Accessor: URL pública del logo (si existe)
     */
    public function getLogoUrlAttribute()
    {
        if (! $this->logo) {
            return null;
        }

        // Intentar devolver URL de almacenamiento, si existe
        if (Storage::exists($this->logo)) {
            return Storage::url($this->logo);
        }

        // Fallback: tratar como ruta pública
        return asset($this->logo);
    }

    /**
     * Adjunta un usuario a la agencia
     */
    public function attachUser($user)
    {
        return $this->users()->attach($user);
    }

    /**
     * Desadjunta un usuario de la agencia
     */
    public function detachUser($user)
    {
        return $this->users()->detach($user);
    }

    /**
     * Comprueba si la agencia tiene al usuario
     */
    public function hasUser($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }
}
