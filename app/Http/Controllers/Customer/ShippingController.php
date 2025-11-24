<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use App\Models\Province;
use App\Models\City;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $rajaOngkir;
    
    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }
    
    /**
     * Get all provinces from database.
     */
    public function getProvinces()
    {
        try {
            // Cache selama 24 jam
            $provinces = Cache::remember('provinces', 86400, function () {
                return Province::orderBy('name')->get(['id', 'province_id', 'name']);
            });
            
            return response()->json([
                'success' => true,
                'data' => $provinces
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Provinces Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi'
            ], 500);
        }
    }
    
    /**
     * Get cities by province ID from database.
     */
    public function getCitiesByProvince($provinceId)
    {
        try {
            // Cache per province selama 24 jam
            $cacheKey = "cities_province_{$provinceId}";
            
            $cities = Cache::remember($cacheKey, 86400, function () use ($provinceId) {
                return City::where('province_id', $provinceId)
                           ->orderBy('type')
                           ->orderBy('name')
                           ->get(['id', 'city_id', 'name', 'type', 'postal_code']);
            });
            
            return response()->json([
                'success' => true,
                'data' => $cities
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Cities Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota'
            ], 500);
        }
    }
    
    /**
     * Calculate shipping cost.
     */
    public function calculateShippingCost(Request $request)
    {
        try {
            $validated = $request->validate([
                'destination_city_id' => 'required|integer|exists:cities,city_id',
                'weight' => 'required|integer|min:1',
                'courier' => 'required|string|in:jne,pos,tiki,rpx,esl,pcp,pandu,wahana,sicepat,jnt,pahala,sap,jet,indah,dse,slis,first,ncs,star,ninja,lion,idl,rex,ide,sentral,anteraja'
            ], [
                'destination_city_id.required' => 'Kota tujuan harus dipilih',
                'destination_city_id.exists' => 'Kota tujuan tidak valid',
                'weight.required' => 'Berat paket harus diisi',
                'weight.min' => 'Berat minimal 1 gram',
                'courier.required' => 'Kurir harus dipilih',
                'courier.in' => 'Kurir tidak valid'
            ]);
            
            // Get origin city from config
            $origin = config('rajaongkir.origin_city');
            
            // Validate origin city
            if (!$origin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kota asal tidak dikonfigurasi'
                ], 500);
            }
            
            // Call RajaOngkir API
            $result = $this->rajaOngkir->calculateShippingCost(
                $origin,
                $validated['destination_city_id'],
                $validated['weight'],
                $validated['courier']
            );
            
            // Check for API errors
            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghitung ongkir: ' . $result['error']
                ], 500);
            }
            
            // Check RajaOngkir response status
            if (!isset($result['rajaongkir']['status'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Response API tidak valid'
                ], 500);
            }
            
            if ($result['rajaongkir']['status']['code'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $result['rajaongkir']['status']['description']
                ], 400);
            }
            
            // Format response
            $costs = [];
            
            if (isset($result['rajaongkir']['results'][0]['costs'])) {
                foreach ($result['rajaongkir']['results'][0]['costs'] as $cost) {
                    $costs[] = [
                        'service' => $cost['service'],
                        'description' => $cost['description'],
                        'cost' => $cost['cost'][0]['value'],
                        'etd' => $cost['cost'][0]['etd'],
                        'note' => $cost['cost'][0]['note'] ?? ''
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'origin' => $result['rajaongkir']['origin_details'],
                    'destination' => $result['rajaongkir']['destination_details'],
                    'courier' => [
                        'code' => $result['rajaongkir']['results'][0]['code'],
                        'name' => $result['rajaongkir']['results'][0]['name']
                    ],
                    'costs' => $costs
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Calculate Shipping Cost Error: ' . $e->getMessage());
            dd($e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim'
            ], 500);
        }
    }
    
    /**
     * Calculate shipping for cart (authenticated users).
     */
    public function calculateCartShipping(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu'
                ], 401);
            }
            
            $validated = $request->validate([
                'destination_city_id' => 'required|integer|exists:cities,city_id',
                'courier' => 'required|string|in:jne,pos,tiki,rpx,esl,pcp,pandu,wahana,sicepat,jnt,pahala,sap,jet,indah,dse,slis,first,ncs,star,ninja,lion,idl,rex,ide,sentral,anteraja'
            ]);
            
            $selectedVariations = session('selected_variations', []);
            $query = Cart::where('user_id', Auth::id())->with(['variation.product']);
            
            if (!empty($selectedVariations)) {
                $query->whereIn('variation_id', $selectedVariations);
            }
            
            $cartItems = $query->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja kosong atau tidak ada produk yang dipilih'
                ], 400);
            }
            
            $totalWeight = 0;
            foreach ($cartItems as $item) {
                if ($item->variation && $item->variation->product) {
                    $product = $item->variation->product;
                    $totalWeight += ($product->weight * $item->quantity);
                }
            }
            
            $totalWeight = max($totalWeight, 1000);
            $origin = config('rajaongkir.origin_city');
            
            Log::info("Calculating shipping: Origin=$origin, Dest={$validated['destination_city_id']}, Weight=$totalWeight, Courier={$validated['courier']}");
            
            $result = $this->rajaOngkir->calculateShippingCost(
                $origin,
                $validated['destination_city_id'],
                $totalWeight,
                $validated['courier']
            );
            
            Log::info('RajaOngkir Result:', $result);
            
            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
                ], 500);
            }
            
            if (!isset($result['rajaongkir']) || $result['rajaongkir']['status']['code'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $result['rajaongkir']['status']['description'] ?? 'Gagal menghitung ongkir'
                ], 400);
            }
            
            if (empty($result['rajaongkir']['results']) || empty($result['rajaongkir']['results'][0]['costs'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada layanan pengiriman tersedia untuk rute ini. Silakan pilih kurir lain atau hubungi customer service.'
                ], 400);
            }
            
            // ✅ PERBAIKAN: Match dengan struktur dari RajaOngkirService
            $costs = [];
            foreach ($result['rajaongkir']['results'][0]['costs'] as $cost) {
                $costs[] = [
                    'service' => $cost['service'],
                    'description' => $cost['description'],
                    'cost' => $cost['cost'],  // ← Langsung ambil cost (bukan cost[0][value])
                    'etd' => $cost['etd'],
                    'note' => $cost['note'] ?? ''
                ];
            }
            
            return response()->json([
                'success' => true,
                'rajaongkir' => [
                    'status' => $result['rajaongkir']['status'],
                    'origin_details' => $result['rajaongkir']['origin_details'],
                    'destination_details' => $result['rajaongkir']['destination_details'],
                    'results' => [[
                        'code' => $result['rajaongkir']['results'][0]['code'],
                        'name' => $result['rajaongkir']['results'][0]['name'],
                        'costs' => $costs
                    ]]
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Calculate Cart Shipping Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());  // ✅ Tambah detail error
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim'
            ], 500);
        }
    }
    
    /**
     * Get available couriers.
     */
    public function getCouriers()
    {
        $couriers = [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'rpx', 'name' => 'RPX'],
            ['code' => 'esl', 'name' => 'ESL'],
            ['code' => 'pcp', 'name' => 'PCP'],
            ['code' => 'pandu', 'name' => 'Pandu Logistics'],
            ['code' => 'wahana', 'name' => 'Wahana'],
            ['code' => 'sicepat', 'name' => 'SiCepat'],
            ['code' => 'jnt', 'name' => 'J&T'],
            ['code' => 'pahala', 'name' => 'Pahala'],
            ['code' => 'sap', 'name' => 'SAP'],
            ['code' => 'jet', 'name' => 'JET'],
            ['code' => 'indah', 'name' => 'Indah Cargo'],
            ['code' => 'dse', 'name' => 'DSE'],
            ['code' => 'slis', 'name' => 'Solusi Ekspres'],
            ['code' => 'first', 'name' => 'First Logistics'],
            ['code' => 'ncs', 'name' => 'NCS'],
            ['code' => 'star', 'name' => 'Star Cargo'],
            ['code' => 'ninja', 'name' => 'Ninja Xpress'],
            ['code' => 'lion', 'name' => 'Lion Parcel'],
            ['code' => 'idl', 'name' => 'IDL'],
            ['code' => 'rex', 'name' => 'REX'],
            ['code' => 'ide', 'name' => 'IDE'],
            ['code' => 'sentral', 'name' => 'Sentral Cargo'],
            ['code' => 'anteraja', 'name' => 'AnterAja'],
        ];
        
        return response()->json([
            'success' => true,
            'data' => $couriers
        ]);
    }
    
    /**
     * Get origin city info.
     */
    public function getOriginCity()
    {
        try {
            $originCityId = config('rajaongkir.origin_city');
            
            if (!$originCityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kota asal belum dikonfigurasi'
                ], 500);
            }
            
            $city = City::where('city_id', $originCityId)->first();
            
            if (!$city) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kota asal tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'city_id' => $city->city_id,
                    'city_name' => $city->name,
                    'type' => $city->type,
                    'province_name' => $city->province->name ?? '',
                    'postal_code' => $city->postal_code
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get Origin City Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota asal'
            ], 500);
        }
    }
    
    /**
     * Sync provinces from RajaOngkir API to database.
     * (Admin only - bisa tambahkan middleware auth:admin)
     */
    public function syncProvinces()
    {
        try {
            $result = $this->rajaOngkir->getProvinces();
            
            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sync: ' . $result['error']
                ], 500);
            }
            
            if ($result['rajaongkir']['status']['code'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $result['rajaongkir']['status']['description']
                ], 400);
            }
            
            $count = 0;
            foreach ($result['rajaongkir']['results'] as $province) {
                Province::updateOrCreate(
                    ['province_id' => $province['province_id']],
                    ['name' => $province['province']]
                );
                $count++;
            }
            
            // Clear cache
            Cache::forget('provinces');
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil sync {$count} provinsi"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Sync Provinces Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync provinsi'
            ], 500);
        }
    }
    
    /**
     * Sync cities from RajaOngkir API to database.
     * (Admin only - bisa tambahkan middleware auth:admin)
     */
    public function syncCities()
    {
        try {
            $result = $this->rajaOngkir->getCities();
            
            if (isset($result['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sync: ' . $result['error']
                ], 500);
            }
            
            if ($result['rajaongkir']['status']['code'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => $result['rajaongkir']['status']['description']
                ], 400);
            }
            
            $count = 0;
            foreach ($result['rajaongkir']['results'] as $city) {
                City::updateOrCreate(
                    ['city_id' => $city['city_id']],
                    [
                        'province_id' => $city['province_id'],
                        'name' => $city['city_name'],
                        'type' => $city['type'],
                        'postal_code' => $city['postal_code']
                    ]
                );
                $count++;
            }
            
            // Clear all city caches
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil sync {$count} kota"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Sync Cities Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal sync kota'
            ], 500);
        }
    }
}
