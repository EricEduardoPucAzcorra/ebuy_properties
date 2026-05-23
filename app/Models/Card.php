<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'openpay_card_id',
        'card_number',
        'card_holder_name',
        'card_expiry_month',
        'card_expiry_year',
        'openpay_customer_id',
        'type',
        'brand',
        'bank_name',
        'bank_code'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
