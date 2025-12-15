<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;
    
    public function __construct()
    {
        $this->apiKey = config('rajaongkir.api_key');
        $this->baseUrl = config('rajaongkir.base_url');
        
        if (empty($this->apiKey)) {
            throw new \Exception('RajaOngkir API Key tidak ditemukan');
        }
    }
    
    /**
     * Get all provinces from RajaOngkir API
     */
    public function getProvinces()
    {
        try {
            // Cache untuk 7 hari karena data jarang berubah
            return Cache::remember('rajaongkir_provinces', 604800, function () {
                $url = "{$this->baseUrl}/destination/province";
                
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(30)->get($url);
                
                if ($response->failed()) {
                    Log::error('Failed to fetch provinces', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }
                
                $result = $response->json();
                
                if (isset($result['data']) && is_array($result['data'])) {
                    return $result['data'];
                }
                
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Get Provinces Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get cities by province ID from RajaOngkir API
     */
    public function getCitiesByProvince($provinceId)
    {
        try {
            // Cache per province untuk 7 hari
            return Cache::remember("rajaongkir_cities_{$provinceId}", 604800, function () use ($provinceId) {
                $url = "{$this->baseUrl}/destination/city/{$provinceId}";
                
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->timeout(30)->get($url);
                
                if ($response->failed()) {
                    Log::error('Failed to fetch cities', [
                        'province_id' => $provinceId,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }
                
                $result = $response->json();
                
                if (isset($result['data']) && is_array($result['data'])) {
                    return $result['data'];
                }
                
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Get Cities Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost($origin, $destination, $weight, $courier)
    {
        try {
            $url = "{$this->baseUrl}/calculate/domestic-cost";
            
            $payload = [
                'origin' => (string) $origin,
                'destination' => (string) $destination,
                'weight' => (string) $weight,
                'courier' => strtolower($courier)
            ];
            
            Log::info("RajaOngkir Request", [
                'url' => $url,
                'payload' => $payload
            ]);
            
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->asForm()->timeout(30)->post($url, $payload);
            
            if ($response->failed()) {
                return [
                    'error' => 'HTTP Error: ' . $response->status(),
                    'message' => $response->body()
                ];
            }
            
            $result = $response->json();
            
            if (isset($result['meta']['status']) && $result['meta']['status'] === 'error') {
                return [
                    'error' => $result['meta']['message'],
                    'message' => $result['meta']['message']
                ];
            }
            
            if (isset($result['data']) && is_array($result['data'])) {
                return $this->convertKomerceToRajaOngkir($result, $origin, $destination);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Calculate Shipping Error: ' . $e->getMessage());
            return [
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Convert Komerce API response to RajaOngkir format
     */
    private function convertKomerceToRajaOngkir($komerceResponse, $origin, $destination)
    {
        if (!isset($komerceResponse['data']) || empty($komerceResponse['data'])) {
            return [
                'rajaongkir' => [
                    'status' => [
                        'code' => 400,
                        'description' => 'No shipping services available'
                    ],
                    'results' => []
                ]
            ];
        }
        
        $groupedCosts = [];
        
        foreach ($komerceResponse['data'] as $service) {
            $courierCode = strtolower($service['code']);
            
            if (!isset($groupedCosts[$courierCode])) {
                $groupedCosts[$courierCode] = [
                    'code' => $courierCode,
                    'name' => $service['name'],
                    'costs' => []
                ];
            }
            
            $groupedCosts[$courierCode]['costs'][] = [
                'service' => $service['service'],
                'description' => $service['description'],
                'cost' => (int) $service['cost'],
                'etd' => $service['etd'],
                'note' => ''
            ];
        }
        
        $courierData = reset($groupedCosts);
        
        if (!$courierData) {
            return [
                'rajaongkir' => [
                    'status' => [
                        'code' => 400,
                        'description' => 'No courier data found'
                    ],
                    'results' => []
                ]
            ];
        }
        
        return [
            'rajaongkir' => [
                'status' => [
                    'code' => 200,
                    'description' => 'OK'
                ],
                'origin_details' => [
                    'city_id' => $origin,
                    'city_name' => ''
                ],
                'destination_details' => [
                    'city_id' => $destination,
                    'city_name' => ''
                ],
                'results' => [$courierData]
            ]
        ];
    }

    public function trackWaybill(string $awb, ?string $courier = null): array
    {
        try {
            Log::info("=== RAJAONGKIR TRACK REQUEST ===", [
                'awb' => $awb,
                'courier' => $courier,
                'url' => "{$this->baseUrl}/track/waybill"
            ]);
            
            // ✅ CRITICAL FIX: Build URL with query parameters
            $url = "{$this->baseUrl}/track/waybill?awb={$awb}";
            
            if ($courier) {
                $url .= "&courier=" . strtolower($courier);
            }
            
            Log::info('Request URL', ['url' => $url]);
            
            // ✅ POST request WITHOUT body, parameters in URL
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->timeout(30)->post($url);

            Log::info('=== HTTP RESPONSE ===', [
                'status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->failed()) {
                $errorBody = $response->body();
                Log::error('API HTTP Failed', [
                    'status' => $response->status(),
                    'body' => $errorBody
                ]);
                
                return [
                    'error' => "HTTP {$response->status()}: Gagal melacak pengiriman"
                ];
            }

            $result = $response->json();
            
            Log::info('=== PARSED JSON ===', [
                'has_meta' => isset($result['meta']),
                'has_data' => isset($result['data']),
                'meta_code' => $result['meta']['code'] ?? 'N/A',
                'meta_status' => $result['meta']['status'] ?? 'N/A',
            ]);

            // ✅ Return full result
            return $result;
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection Error', [
                'message' => $e->getMessage()
            ]);
            return ['error' => 'Koneksi ke server tracking gagal. Coba lagi nanti.'];
            
        } catch (\Exception $e) {
            Log::error('=== UNEXPECTED EXCEPTION ===', [
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            
            return ['error' => 'Error: ' . $e->getMessage()];
        }
    }
}
