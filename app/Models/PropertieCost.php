<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertieCost extends Model
{
    public $table = 'property_costs';
    public $primaryKey = 'id';
    public $timestamps = true;

    public $fillable = [
        'property_id',
        'cost_category_id',
        'concept',
        'amount',
        'currency',
        'periodicity',
        'included',
        'visible_public'
    ];

    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }
}
