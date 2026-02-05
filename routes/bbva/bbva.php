<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/payments/bbva/webhook', [PaymentController::class, 'webhook']);
Route::get('/payments/confirm/{orderId}', [PaymentController::class, 'confirmCallback']);

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Pagos
    Route::post('/payments/initiate', [PaymentController::class, 'iniciarPago']);
    Route::get('/payments/verify/{orderId}', [PaymentController::class, 'verificarEstado']);
    Route::get('/payments/history', [PaymentController::class, 'historialPagos']);

    // Planes
    // Route::get('/plans', [PaymentController::class, 'obtenerPlanes']);

    // Suscripciones
    Route::get('/subscription/current', [PaymentController::class, 'suscripcionActual']);
    Route::post('/subscription/cancel', [PaymentController::class, 'cancelarSuscripcion']);

    // Estadísticas (admin puede ver todas)
    Route::get('/payments/stats', [PaymentController::class, 'estadisticas']);
});


// Route::middleware(['auth:sanctum', 'subscription.active:publish_property'])->group(function () {
//     Route::post('/properties', [PropertyController::class, 'store']);
//     Route::post('/properties/{id}/publish', [PropertyController::class, 'publish']);
// });

// Route::middleware(['auth:sanctum', 'subscription.active:create_property'])->group(function () {
//     Route::post('/properties', [PropertyController::class, 'store']);
// });
