<?php

return [
    'api_key' => env('RAJAONGKIR_API_KEY'),
    
    // Ganti URL ke API Komerce yang baru
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
    
    'origin_city' => env('RAJAONGKIR_ORIGIN_CITY', 128),
];
