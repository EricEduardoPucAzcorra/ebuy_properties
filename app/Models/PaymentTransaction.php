<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{

    protected $table = 'payment_transactions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'subscription_id',
        'bbva_charge_id',
        'bbva_order_id',
        'bbva_authorization_id',
        'amount',
        'status',
        'currency',
        'customer_data',
        'bbva_response',
        'redirect_url',
        'error_message',
        'processed_at'
    ];

    protected $casts = [
        'customer_data' => 'array',
        'bbva_response' => 'array',
        'amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'transaction_id', 'bbva_charge_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Métodos de utilidad
    public function markAsCompleted($authorizationId, $bbvaResponse)
    {
        $this->update([
            'status' => 'completed',
            'bbva_authorization_id' => $authorizationId,
            'bbva_response' => $bbvaResponse,
            'processed_at' => now()
        ]);
    }

    public function markAsFailed($errorMessage, $bbvaResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'bbva_response' => $bbvaResponse,
            'processed_at' => now()
        ]);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
