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
        'feature_category_id'
    ];

    public function properties()
    {
        return $this->belongsToMany(Propertie::class, 'property_feature', 'feature_id', 'property_id')->withTimestamps();
    }

    public function category(){
        return $this->belongsTo(FeatureCategory::class, 'feature_category_id');
    }
}
