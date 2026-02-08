<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureCategory extends Model
{
    public $table  = 'feature_categories';

    public $primaryKey = 'id';

    public $timestamps = true;

    public $fillable = [
      'name',
      'description',
      'icon'
    ];

    public function features()
    {
        return $this->hasMany(PropertyFeature::class, 'feature_category_id');
    }

}
