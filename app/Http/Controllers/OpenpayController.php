<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Openpay\Data\Openpay;

class OpenpayController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'token_id' => 'required|string',
            'device_session_id' => 'required|string',
            'plan_id' => 'nullable|integer'
        ]);

        $isProduction = config('app.env') === 'production';

        $clientIp = $request->ip();

        if (!$isProduction && in_array($clientIp, ['127.0.0.1', '::1', ''])) {
            $clientIp = '187.189.155.42'; 
        }

        $openpay = Openpay::getInstance(
            env('OPENPAY_MERCHANT_ID'),
            env('OPENPAY_PRIVATE_KEY'),
            'MX',
            $clientIp  
        );

        $chargeData = [
            'method' => 'card',
            'source_id' => $request->token_id,
            'amount' => 299,
            'currency' => 'MXN',
            'description' => 'Pago suscripción',
            'device_session_id' => $request->device_session_id,
            'customer' => [
                'name' => 'Eric',
                'last_name' => 'Azcorra',
                'phone_number' => '9999999999',
                'email' => 'correo@test.com'
            ]
        ];

        try {
            $charge = $openpay->charges->create($chargeData);

            return response()->json([
                'success' => true,
                'charge_id' => $charge->id,
                'status' => $charge->status,
                'amount' => $charge->amount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}