<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Services\BbvaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private $paymentService;

    public function __construct(BbvaPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware('auth:sanctum')->except(['confirmCallback', 'webhook']);
    }

    /**
     * Iniciar pago para un plan
     */
    public function iniciarPago(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->paymentService->iniciarPagoPlan($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Callback de confirmación desde BBVA
     */
    public function confirmCallback(Request $request, string $orderId)
    {
        $result = $this->paymentService->confirmarPago($orderId);

        // Si es API call, retornar JSON
        if ($request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 400);
        }

        // Redirigir a página frontend
        $redirectUrl = config('app.frontend_url') . '/payment/result?' . http_build_query([
            'success' => $result['success'] ? 'true' : 'false',
            'message' => $result['message'] ?? $result['error'] ?? '',
            'order_id' => $orderId
        ]);

        return redirect($redirectUrl);
    }

    /**
     * Webhook de BBVA para notificaciones
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook BBVA recibido:', [
            'payload' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // Verificar firma si BBVA la envía
        // $signature = $request->header('Webhook-Signature');
        // $this->verificarFirmaWebhook($signature, $request->getContent());

        $event = $request->input('type');
        $data = $request->input('data.object');

        if ($event === 'charge.succeeded' && isset($data['order_id'])) {
            // Procesar confirmación automática
            $this->paymentService->confirmarPago($data['order_id']);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Verificar estado de un pago
     */
    public function verificarEstado(Request $request, string $orderId)
    {
        $result = $this->paymentService->verificarEstadoPago($orderId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Obtener historial de pagos del usuario
     */
    public function historialPagos(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $historial = PaymentTransaction::where('user_id', $user->id)
            ->with(['plan', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $historial
        ]);
    }

    /**
     * Obtener planes disponibles
     */
    public function obtenerPlanes(Request $request)
    {
        $plans = Plan::where('is_active', true)
            ->with(['features'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    /**
     * Obtener suscripción actual del usuario
     */
    public function suscripcionActual(Request $request)
    {
        $user = Auth::user();

        $subscription = $user->subscription()->with(['plan.features'])->first();

        return response()->json([
            'success' => true,
            'data' => [
                'subscription' => $subscription,
                'has_active_subscription' => $user->hasActiveSubscription(),
                'can_publish_property' => $user->canPublishProperty(),
                'can_create_more_properties' => $user->canCreateMoreProperties()
            ]
        ]);
    }

    /**
     * Cancelar suscripción actual
     */
    public function cancelarSuscripcion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motivo' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $result = $this->paymentService->cancelarSuscripcion($user, $request->motivo);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Obtener estadísticas de pagos
     */
    public function estadisticas(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRoleName('admin');

        $result = $this->paymentService->obtenerEstadisticas(
            $isAdmin ? null : $user
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
