<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class OpenpayService
{
    private $openpay;
    private $user;
    private $plan;
    
    public function __construct($openpay, $user, $plan = null)
    {
        $this->openpay = $openpay;
        $this->user = $user;
        $this->plan = $plan;
    }
    
    /**
     * Obtiene o crea un cliente en Openpay
     */
    public function getOrCreateCustomer()
    {
        $customer = null;
        
        // Verificar si el usuario ya tiene un cliente registrado
        if ($this->user->openpay_customer_id) {
            try {
                $customer = $this->openpay->customers->get($this->user->openpay_customer_id);
            } catch (Exception $e) {
                // $this->user->openpay_customer_id = null;
                // $this->user->save();
                throw new Exception('Error al consultar Openpay: ' . $e->getMessage());
            }
        }
        
        // Crear nuevo cliente si no existe
        if (!$customer) {
            $customerData = [
                // 'external_id' => 'ebuy_users_' . $this->user->id,
                'name' => $this->user->name ?? 'User',
                'last_name' => $this->user->last_name ?? 'NA',
                'email' => $this->user->email,
                'phone_number' => $this->user->phone ?? '9999999999',
                'requires_account' => false,
            ];
            
            $customer = $this->openpay->customers->add($customerData);
            
            if (!$customer) {
                throw new Exception('No se pudo crear el cliente en Openpay');
            }
            
            $this->user->openpay_customer_id = $customer->id;
            $this->user->save();
          
        }
        
        return $customer;
    }
    
    /**
     * Crea una tarjeta asociada al cliente
     */
    // public function createCard($customer, $tokenId, $deviceSessionId)
    // {
    //     $cardData = [
    //         'token_id' => $tokenId,
    //         'device_session_id' => $deviceSessionId
    //     ];
        
    //     $card = $customer->cards->add($cardData);
        
    //     if (!$card) {
    //         throw new Exception('La tarjeta no se pudo crear o fue rechazada');
    //     }
        
    //     $cardbd = $this->user->cards()->create([
    //         'user_id' => $this->user->id,
    //         'openpay_card_id' => $card->id,
    //         'card_number' => $card->card_number,
    //         'card_holder_name' => $card->holder_name,
    //         'card_expiry_month' => $card->expiration_month,
    //         'card_expiry_year' => $card->expiration_year,
    //         'openpay_customer_id' => $customer->id,
    //         'type' => $card->type,
    //         'brand' => $card->brand,
    //         'bank_name' => $card->bank_name,
    //         'bank_code' => $card->bank_code
    //     ]);

    //     return $card;
    // }

    public function createCard($customer, $tokenId, $deviceSessionId)
    {
        $cardData = [
            'token_id' => $tokenId,
            'device_session_id' => $deviceSessionId
        ];

        $card = $customer->cards->add($cardData);

        if (!$card) {
            throw new Exception('La tarjeta no se pudo crear o fue rechazada');
        }

        $existing = $this->user->cards()
            ->where('card_number', $card->card_number)
            ->first();

        if ($existing) {
            $existing->update([
                'card_holder_name' => $card->holder_name,
                'card_expiry_month' => $card->expiration_month,
                'card_expiry_year' => $card->expiration_year,
                'type' => $card->type,
                'brand' => $card->brand,
                'bank_name' => $card->bank_name,
                'bank_code' => $card->bank_code
            ]);
        } else {
            $this->user->cards()->create([
                'user_id' => $this->user->id,
                'openpay_card_id' => $card->id,
                'card_number' => $card->card_number,
                'card_holder_name' => $card->holder_name,
                'card_expiry_month' => $card->expiration_month,
                'card_expiry_year' => $card->expiration_year,
                'openpay_customer_id' => $customer->id,
                'type' => $card->type,
                'brand' => $card->brand,
                'bank_name' => $card->bank_name,
                'bank_code' => $card->bank_code
            ]);
        }

        return $card;
    }
    
    /**
     * Cancela todas las suscripciones activas del cliente
     */
    public function cancelAllActiveSubscriptions($customer, $exceptPlanId = null)
    {
        try {
            $findData = array(
                'offset' => 0,
                'limit' => 100
            );

            $subscriptionsList = $customer->subscriptions->getList($findData);
            $cancelledSubscriptions = [];
            
            foreach ($subscriptionsList as $subscription) {
                // Cancelar si está activa o en periodo de prueba
                if (in_array($subscription->status, ['active', 'trial'])) {
                    // Si se especifica un plan a exceptuar, no cancelar ese
                    if ($exceptPlanId && $subscription->plan_id === $exceptPlanId) {
                        continue;
                    }
                    
                    $subscription->delete();
                    
                    $this->user->subscriptions()
                        ->where('openpay_subscription_id', $subscription->id)
                        ->update([
                            'status' => 'Cancelada',
                            'ends_at' => now()
                        ]);
                    
                    $cancelledSubscriptions[] = $subscription->id;
                }
            }
            
            return $cancelledSubscriptions;
            
        } catch (Exception $e) {
            Log::error('Error al cancelar suscripciones activas: ' . $e->getMessage());
            throw new Exception('Error al cancelar suscripciones existentes: ' . $e->getMessage());
        }
    }
    
    /**
     * Verifica si el usuario ya tiene una suscripción activa
     */
    public function hasActiveSubscription($customer, $planId)
    {
        try {
            $findData = array(
                'offset' => 0,
                'limit' => 100
            );

            $subscriptionsList = $customer->subscriptions->getList($findData);
            
            foreach ($subscriptionsList as $subscription) {
                if ($subscription->plan_id === $planId && in_array($subscription->status, ['active', 'trial'])) {
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Crea una suscripción para el cliente
     */
    public function createSubscription($customer, $cardId, $planId)
    {
        $subscriptionData = [
            'plan_id' => $planId,
            'source_id' => $cardId
        ];
        
        $subscription = $customer->subscriptions->add($subscriptionData);
        
        if (!$subscription) {
            throw new Exception('No se pudo crear la suscripción en Openpay');
        }

        $subscriptionbd = $this->user->subscriptions()->create([
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'status' => 'Activa',
            'openpay_subscription_id' => $subscription->id
        ]);
        
        return $subscription;
    }

    /**
     * Crea una suscripción usando una tarjeta existente
     */
    public function createSubscriptionWithExistingCard($customer, $openpayCardId, $planId)
    {
        $subscriptionData = [
            'plan_id' => $planId,
            'source_id' => $openpayCardId
        ];
        
        $subscription = $customer->subscriptions->add($subscriptionData);
        
        if (!$subscription) {
            throw new Exception('No se pudo crear la suscripción en Openpay');
        }

        $subscriptionbd = $this->user->subscriptions()->create([
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'status' => 'Activa',
            'openpay_subscription_id' => $subscription->id
        ]);
        
        return $subscription;
    }

    /**
     * Método principal para procesar toda la suscripción
     */
    public function processSubscription($tokenId, $deviceSessionId, $planId)
    {
        // 1. Obtener o crear cliente
        $customer = $this->getOrCreateCustomer();

        // 2. Cancelar todas las suscripciones activas existentes
        $this->cancelAllActiveSubscriptions($customer, $planId);

        // 3. Crear tarjeta
        $card = $this->createCard($customer, $tokenId, $deviceSessionId);

        // 4. Crear nueva suscripción
        $subscription = $this->createSubscription($customer, $card->id, $planId);

        return [
            'customer' => $customer,
            'card' => $card,
            'subscription' => $subscription,
            'cancelled_subscriptions' => $this->cancelAllActiveSubscriptions($customer, $planId) // Retorna las canceladas
        ];
    }

    /**
     * Método para procesar suscripción con tarjeta existente
     */
    public function processSubscriptionWithExistingCard($openpayCardId, $planId)
    {
        // 1. Obtener o crear cliente
        $customer = $this->getOrCreateCustomer();

        // 2. Cancelar todas las suscripciones activas existentes
        $this->cancelAllActiveSubscriptions($customer, $planId);

        // 3. Crear suscripción con tarjeta existente
        $subscription = $this->createSubscriptionWithExistingCard($customer, $openpayCardId, $planId);

        return [
            'customer' => $customer,
            'subscription' => $subscription,
            'cancelled_subscriptions' => $this->cancelAllActiveSubscriptions($customer, $planId)
        ];
    }

    public function cancelSubscription($openpay_customer_id, $openpay_subscription_id)
    {
        try {
            $customer = $this->openpay->customers->get($openpay_customer_id);
            $subscription = $customer->subscriptions->get($openpay_subscription_id);
            $subscription->delete();
            
            $this->user->subscriptions()
                ->where('openpay_subscription_id', $openpay_subscription_id)
                ->update([
                    'status' => 'Cancelada',
                    'ends_at' => now()
                ]);

            return true;
            
        } catch (Exception $e) {
            throw new Exception('Error al cancelar la suscripción: ' . $e->getMessage());
        }
    }
    
}