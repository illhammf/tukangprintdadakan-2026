<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'is_production' => filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),
    'is_sanitized' => filter_var(env('MIDTRANS_IS_SANITIZED', true), FILTER_VALIDATE_BOOLEAN),
    'is_3ds' => filter_var(env('MIDTRANS_IS_3DS', true), FILTER_VALIDATE_BOOLEAN),

    /*
    |--------------------------------------------------------------------------
    | Local Sandbox Fallback
    |--------------------------------------------------------------------------
    | Hanya untuk demo/local sandbox. Saat production, logic ini tidak akan aktif
    | karena tetap dicek dengan is_production=false.
    */
    'local_sandbox_fallback' => filter_var(env('MIDTRANS_LOCAL_SANDBOX_FALLBACK', true), FILTER_VALIDATE_BOOLEAN),
];