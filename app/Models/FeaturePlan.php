<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturePlan extends Model
{
    public $table = 'feature_plans';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'descripcion',
        'is_active'
    ];

    public function plans()
    {
        return $this->belongsToMany(
            Plan::class,
            'plans_features'
        );
    }
}
