<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    public $table = 'plans_features';

    protected $fillable = [
        'plan_id',
        'feature_plan_id',
        'mount',
        'description',
        'other_description',
    ];

    protected $casts = [
        'mount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function featurePlan()
    {
        return $this->belongsTo(FeaturePlan::class);
    }
}
