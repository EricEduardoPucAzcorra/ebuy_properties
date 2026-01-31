<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'stateid',
        'countryid',
        'statename',
        'statecode',
        'latitude',
        'longitude'
    ];

    public function country()
    {
        return $this->belongsTo(Countrie::class, 'countryid', 'countryid');
    }
}

