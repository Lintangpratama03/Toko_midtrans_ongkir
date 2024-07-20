<?php

return [
    'merchant_id' => 'G777111405',
    'client_key' => 'SB-Mid-client-SQkB8a1nLEW3Nz6W',
    'server_key' => 'SB-Mid-server-AqEXKgaXMOW2VRTS9VzHziBS',
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
