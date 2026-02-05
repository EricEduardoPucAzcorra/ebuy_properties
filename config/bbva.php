<?php

// return [
//     'bbva' => [
//         'api_key' => env('BBVA_API_KEY', 'sk_54e30bbceb3c4d23a66b636692876315'),
//         'merchant_id' => env('BBVA_MERCHANT_ID', 'mnvxtggr7s034amreewd'),
//         'affiliation_bbva' => env('BBVA_AFFILIATION', '280691'),
//         'customer_id' => env('BBVA_CUSTOMER_ID', '4342802'),
//         'redirect_url' => env('BBVA_REDIRECT_URL', 'http://localhost:8000/payments/confirm/{transaction_id}'),
//         'use_3d_secure' => env('BBVA_USE_3D_SECURE', 'true'),
//         'production' => env('BBVA_PRODUCTION', false),
//         // URLs CORRECTAS basadas en el código .NET
//         'sandbox_url' => 'https://sand-api.ecommercebbva.com/v1/',
//         'production_url' => 'https://api.ecommercebbva.com/v1/'
//     ]
// ];

return [
    'bbva' => [
        'api_key' => env('BBVA_API_KEY'),
        'merchant_id' => env('BBVA_MERCHANT_ID'),
        'affiliation_bbva' => env('BBVA_AFFILIATION'),
        'customer_id' => env('BBVA_CUSTOMER_ID'),
        'redirect_url' => env('BBVA_REDIRECT_URL'),
        'use_3d_secure' => env('BBVA_USE_3D_SECURE'),
        'production' => env('BBVA_PRODUCTION'),

        'sandbox_url' => 'https://sand-api.ecommercebbva.com/v1/',
        'production_url' => 'https://api.ecommercebbva.com/v1/'
    ]
];

//  "BbvaConfiguration": {
//    "api_key": "sk_54e30bbceb3c4d23a66b636692876335",
//    "merchant_id": "mnvxtggr7s034amreywd",
//    "production": false,
//    "affiliation_bbva": "280691",
//    "customer_id": "4342802",
//    "redirect_url": "https://localhost:44376/PerfilCuenta/ConfirmacionCargo",
//    "use_3d_secure": "true"
//  },
