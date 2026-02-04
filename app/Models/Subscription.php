<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $table = 'subscriptions';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'status', //pending, active, trialing, past_due, canceled
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
