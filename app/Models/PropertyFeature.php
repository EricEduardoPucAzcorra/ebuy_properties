<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    public $table = 'property_features';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function properties()
    {
        return $this->belongsToMany(Propertie::class, 'property_feature', 'feature_id', 'property_id')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
