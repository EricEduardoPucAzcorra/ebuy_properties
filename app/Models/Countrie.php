<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countrie extends Model
{
    protected $table = 'countries';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'name',
        'code'
    ];
}
