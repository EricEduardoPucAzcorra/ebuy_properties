<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\OpenpayService;
use Openpay\Data\Openpay;
use App\Models\Plan;

class OpenpaySubscriptionController extends Controller
{
    private function getOpenpayInstance(Request $request = null)
    {
        $isProduction = config('app.env') === 'production';

        $clientIp = $request->ip();

        if (!$isProduction && in_array($clientIp, ['127.0.0.1', '::1', ''])) {
            $clientIp = '187.189.155.42'; 
        }

        return Openpay::getInstance(
            env('OPENPAY_MERCHANT_ID'),
            env('OPENPAY_PRIVATE_KEY'),
            'MX',
            $clientIp  
        );
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'token_id' => 'required|string',
            'device_session_id' => 'required|string',
            'plan_id' => 'required|string',
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);
            $user = auth()->user();
            $plan = Plan::where('openpay_plan_id', $request->plan_id)->first();
            
            $openpayService = new OpenpayService($openpay, $user, $plan);
            
            $result = $openpayService->processSubscription(
                $request->token_id,
                $request->device_session_id,
                $request->plan_id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Suscripción creada correctamente',
                'data' => [
                    'customer_id' => $result['customer']->id,
                    'card_id' => $result['card']->id,
                    'subscription_id' => $result['subscription']->id,
                    'plan_id' => $result['subscription']->plan_id,
                    'status' => $result['subscription']->status ?? 'active',
                    'charge_date' => $result['subscription']->charge_date ?? null,
                    'creation_date' => $result['subscription']->creation_date ?? null
                ]
            ]);

        } catch (\OpenpayApiTransactionError $e) {
            return response()->json([
                'success' => false,
                'type' => 'transaction_error',
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
                'error_code' => $e->getErrorCode()
            ], 400);

        } catch (\OpenpayApiRequestError $e) {
            return response()->json([
                'success' => false,
                'type' => 'request_error',
                'message' => 'Error en la solicitud: ' . $e->getMessage(),
                'error_code' => $e->getErrorCode()
            ], 400);

        } catch (\OpenpayApiConnectionError $e) {
            return response()->json([
                'success' => false,
                'type' => 'connection_error',
                'message' => 'Error de conexión con Openpay'
            ], 500);

        } catch (\OpenpayApiAuthError $e) {
            return response()->json([
                'success' => false,
                'type' => 'auth_error',
                'message' => 'Error de configuración con Openpay'
            ], 401);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'type' => 'general_error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar suscripción
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);

            // Obtener la suscripción
            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );

            // Cancelar la suscripción
            $subscription->cancel();

            Log::info('Suscripción cancelada: ' . $request->subscription_id);

            return response()->json([
                'success' => true,
                'message' => 'Suscripción cancelada correctamente',
                'data' => [
                    'subscription_id' => $request->subscription_id,
                    'status' => 'cancelled'
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error al cancelar suscripción: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la suscripción: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obtener detalles de una suscripción
     */
    public function getSubscription(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'subscription_id' => 'required|string'
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);

            $subscription = $openpay->customers->subscriptions->get(
                $request->customer_id,
                $request->subscription_id
            );

            return response()->json([
                'success' => true,
                'subscription' => [
                    'id' => $subscription->id ?? null,
                    'plan_id' => $subscription->plan_id ?? null,
                    'plan_name' => $subscription->plan_name ?? null,
                    'status' => $subscription->status ?? null,
                    'amount' => $subscription->amount ?? null,
                    'currency' => $subscription->currency ?? null,
                    'charge_date' => $subscription->charge_date ?? null,
                    'creation_date' => $subscription->creation_date ?? null,
                    'next_charge_date' => $subscription->next_charge_date ?? null,
                    'trial_end_date' => $subscription->trial_end_date ?? null,
                    'repeat_every' => $subscription->repeat_every ?? null,
                    'repeat_unit' => $subscription->repeat_unit ?? null
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error al obtener suscripción: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la suscripción: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Listar todas las suscripciones de un cliente
     */
    public function listSubscriptions(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string'
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);

            $subscriptions = $openpay->customers->subscriptions->getList($request->customer_id);

            $data = [];

            foreach (($subscriptions ?? []) as $sub) {
                $data[] = [
                    'id' => $sub->id ?? null,
                    'plan_id' => $sub->plan_id ?? null,
                    'plan_name' => $sub->plan_name ?? null,
                    'status' => $sub->status ?? null,
                    'amount' => $sub->amount ?? null,
                    'currency' => $sub->currency ?? null,
                    'charge_date' => $sub->charge_date ?? null,
                    'creation_date' => $sub->creation_date ?? null,
                    'next_charge_date' => $sub->next_charge_date ?? null
                ];
            }

            return response()->json([
                'success' => true,
                'total' => count($data),
                'subscriptions' => $data
            ]);

        } catch (Exception $e) {
            Log::error('Error al listar suscripciones: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al listar las suscripciones: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obtener las tarjetas de un cliente
     */
    public function getCards(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string'
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);
            
            $cards = $openpay->customers->cards->getList($request->customer_id);
            
            $data = [];
            
            foreach (($cards ?? []) as $card) {
                $data[] = [
                    'id' => $card->id,
                    'brand' => $card->brand,
                    'type' => $card->type,
                    'bank_name' => $card->bank_name,
                    'card_number' => $card->card_number,
                    'holder_name' => $card->holder_name,
                    'expiration_month' => $card->expiration_month,
                    'expiration_year' => $card->expiration_year,
                    'device_session_id' => $card->device_session_id ?? null
                ];
            }
            
            return response()->json([
                'success' => true,
                'cards' => $data
            ]);
            
        } catch (Exception $e) {
            Log::error('Error al obtener tarjetas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las tarjetas: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Eliminar una tarjeta
     */
    public function deleteCard(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'card_id' => 'required|string'
        ]);

        try {
            $openpay = $this->getOpenpayInstance($request);
            
            $openpay->customers->cards->delete(
                $request->customer_id,
                $request->card_id
            );
            
            Log::info('Tarjeta eliminada: ' . $request->card_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Tarjeta eliminada correctamente'
            ]);
            
        } catch (Exception $e) {
            Log::error('Error al eliminar tarjeta: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tarjeta: ' . $e->getMessage()
            ], 400);
        }
    }
}