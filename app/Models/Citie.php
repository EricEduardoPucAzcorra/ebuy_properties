<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    public $table = 'cities';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'cityid',
        'stateid',
        'countryid',
        'cityname',
        'type',
        'latitude',
        'longitude',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'stateid', 'stateid');
    }
}
