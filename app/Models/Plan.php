<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $table = 'plans';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        // 'features',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function features()
    {
        return $this->belongsToMany(
            FeaturePlan::class,
            'plans_features'
        )->withPivot([
            'mount',
            'description',
            'other_description'
        ])->withTimestamps();
    }
}
