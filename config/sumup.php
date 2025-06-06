<?php

return [
    'client_id' => env('SUMUP_CLIENT_ID'),
    'client_secret' => env('SUMUP_CLIENT_SECRET'),
    'redirect_uri' => env('SUMUP_REDIRECT_URI'),
    'api_base' => env('SUMUP_API_BASE', 'https://api.sumup.com'),
    'auth_base' => env('SUMUP_AUTH_BASE', 'https://web.sumup.com'),
    'merchant_id' => env('SUMUP_MERCHANT_ID'),
    'api_key' => env('SUMUP_API_KEY'),
];
