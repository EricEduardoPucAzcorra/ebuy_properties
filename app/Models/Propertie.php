<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propertie extends Model
{
    public $table = 'properties';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'type_property_id',
        'type_operation_id',
        'status_property_id',
        'cadastral_code',
        'title',
        'description',
        'price',
        'currency',
        'price_negotiable',
        'is_active',
        'is_exclusive',
        'presentation',
        'condition',
        'delivery',
        'delivery_obs'
    ];

    public function address()
    {
        return $this->hasOne(AddressPropertie::class, 'property_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(TypePropetie::class, 'type_property_id', 'id');
    }

    public function operation()
    {
        return $this->belongsTo(TypeOperation::class, 'type_operation_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatePropertie::class, 'status_property_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id');
    }

    public function videos()
    {
        return $this->hasMany(PropertyVideo::class, 'property_id', 'id');
    }

    public function attributes()
    {
        return $this->hasMany(PropertyAttribute::class, 'property_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(PropertyFeature::class, 'property_feature', 'property_id', 'feature_id')
            ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany(PropertieViews::class, 'property_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorites::class, 'property_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany(LeadPropertie::class, 'property_id', 'id');
    }

    public function featured()
    {
        return $this->hasMany(FeaturedPropertie::class, 'property_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'property_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'property_id', 'id');
    }

    public function costs()
    {
        return $this->hasMany(PropertieCost::class, 'property_id', 'id');
    }

   public function contacts()
    {
        return $this->hasMany(PropertyContact::class, 'property_id');
    }
}
