<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Openpay\Data\Openpay;
use Exception;

class OpenpaySubscriptionController extends Controller
{
    private function getOpenpayInstance(Request $request = null)
    {
        $isProduction = config('app.env') === 'production';
        $clientIp = '187.189.155.42';
        
        if ($request) {
            $clientIp = $request->ip();
            if (!$isProduction && in_array($clientIp, ['127.0.0.1', '::1', ''])) {
                $clientIp = '187.189.155.42';
            }
        }
        
        return Openpay::getInstance(
            env('OPENPAY_MERCHANT_ID'),
            env('OPENPAY_PRIVATE_KEY'),
            'MX',
            $clientIp,
            $isProduction
        );
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'token_id' => 'required|string',
            'device_session_id' => 'required|string',
            'plan_id' => 'required|string',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $customerData = [
                'name' => $request->customer_name,
                'last_name' => $request->customer_last_name ?? '',
                'phone_number' => $request->customer_phone,
                'email' => $request->customer_email
            ];
            
            $customer = $openpay->customers->add($customerData);
            $customerId = $customer->id;

            $cardData = [
                'token_id' => $request->token_id,
                'device_session_id' => $request->device_session_id
            ];
            
            $card = $openpay->customers->cards->add($customerId, $cardData);
            $cardId = $card->id;

            $subscriptionData = [
                'plan_id' => $request->plan_id,
                'source_id' => $cardId
            ];

            $subscription = $openpay->customers->subscriptions->add($customerId, $subscriptionData);

            return response()->json([
                'success' => true,
                'customer_id' => $customerId,
                'card_id' => $cardId,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );
            
            $subscription->cancel();

            return response()->json([
                'success' => true,
                'message' => 'Suscripción cancelada exitosamente'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getSubscription(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );

            return response()->json([
                'success' => true,
                'subscription' => $subscription
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function listSubscriptions(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $subscriptions = $openpay->customers->subscriptions->getList($request->customer_id);

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function pause(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );
            
            $subscription->pause();

            return response()->json([
                'success' => true,
                'message' => 'Suscripción pausada exitosamente'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function resume(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        $openpay = $this->getOpenpayInstance($request);

        try {
            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );
            
            $subscription->resume();

            return response()->json([
                'success' => true,
                'message' => 'Suscripción reanudada exitosamente'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}