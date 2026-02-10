<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countrie extends Model
{
    protected $table = 'countries';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'countryid',
        'countryname',
        'countrycode',
        'continent',
        'latitude',
        'longitude',
        'name',
        'code',
        'population'

    ];

    public function states()
    {
        return $this->hasMany(State::class, 'countryid');
    }
}
