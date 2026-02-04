<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $table = 'payments';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'status',
        'transaction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
