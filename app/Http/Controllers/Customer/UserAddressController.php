<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        // $this->middleware('auth');
        $this->rajaOngkir = $rajaOngkir;
    }
    
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('customer.addresses.index', compact('addresses'));
    }
    
    public function create()
    {
        // Fetch provinces from API
        $provinces = $this->rajaOngkir->getProvinces();
        
        // ✅ DEBUG: Log structure
        \Log::info('Provinces structure:', [
            'count' => count($provinces),
            'first_item' => $provinces[0] ?? 'empty',
            'all_keys' => isset($provinces[0]) ? array_keys($provinces[0]) : []
        ]);
        
        return view('customer.addresses.create', compact('provinces'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string',
            'note' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean'
        ]);
        
        // Cek apakah user sudah memiliki alamat
        $hasExistingAddress = UserAddress::where('user_id', Auth::id())->exists();
        
        // Jika belum punya alamat, otomatis jadikan primary
        $isPrimary = !$hasExistingAddress ? true : ($request->has('is_primary') && $request->is_primary);
        
        // If this address is set as primary, unset other primary addresses
        if ($isPrimary) {
            UserAddress::where('user_id', Auth::id())
                ->update(['is_primary' => false]);
        }
        
        // Create new address
        UserAddress::create([
            'user_id' => Auth::id(),
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'province_id' => $validated['province_id'],
            'province_name' => $validated['province_name'],
            'city_id' => $validated['city_id'],
            'city_name' => $validated['city_name'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'note' => $validated['note'] ?? null,
            'is_primary' => $isPrimary
        ]);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }
    
    public function show($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('customer.addresses.show', compact('address'));
    }
    
    public function edit($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Fetch provinces from API
        $provincesData = $this->rajaOngkir->getProvinces();
        $provinces = is_array($provincesData) ? $provincesData : [];
        
        // Fetch cities for the current province
        $citiesData = $this->rajaOngkir->getCitiesByProvince($address->province_id);
        $cities = is_array($citiesData) ? $citiesData : [];
        
        // Debug log
        \Log::info('Edit Address - Provinces:', ['count' => count($provinces)]);
        \Log::info('Edit Address - Cities:', ['count' => count($cities)]);
        
        return view('customer.addresses.edit', compact('address', 'provinces', 'cities'));
    }
    
    public function update(Request $request, $id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province_id' => 'required|string',
            'province_name' => 'required|string',
            'city_id' => 'required|string',
            'city_name' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'required|string',
            'note' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean'
        ]);
        
        // Cek apakah ini satu-satunya alamat user
        $totalAddresses = UserAddress::where('user_id', Auth::id())->count();
        
        // Jika hanya punya 1 alamat, paksa jadi primary
        $isPrimary = $totalAddresses === 1 ? true : ($request->has('is_primary') && $request->is_primary);
        
        // If this address is set as primary, unset other primary addresses
        if ($isPrimary) {
            UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->update(['is_primary' => false]);
        }
        
        $address->update([
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'province_id' => $validated['province_id'],
            'province_name' => $validated['province_name'],
            'city_id' => $validated['city_id'],
            'city_name' => $validated['city_name'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'note' => $validated['note'] ?? null,
            'is_primary' => $isPrimary
        ]);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil diperbarui');
    }
    
    public function setPrimary($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Unset all primary addresses
        UserAddress::where('user_id', Auth::id())
            ->update(['is_primary' => false]);
            
        // Set this address as primary
        $address->update(['is_primary' => true]);
        
        return redirect()->route('addresses.index')
            ->with('success', 'Alamat utama berhasil diubah');
    }
    
    public function destroy($id)
    {
        $address = UserAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $wasPrimary = $address->is_primary;
        $address->delete();
        
        // Jika alamat yang dihapus adalah primary, set alamat pertama yang tersisa sebagai primary
        if ($wasPrimary) {
            $firstAddress = UserAddress::where('user_id', Auth::id())
                ->orderBy('created_at', 'asc')
                ->first();
                
            if ($firstAddress) {
                $firstAddress->update(['is_primary' => true]);
            }
        }
        
        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil dihapus');
    }
    
    /**
     * API endpoint to get cities by province
     */
    public function getCities($provinceId)
    {
        \Log::info("Getting cities for province: {$provinceId}");
        
        $cities = $this->rajaOngkir->getCitiesByProvince($provinceId);
        
        \Log::info('Cities result:', [
            'count' => count($cities),
            'first_item' => $cities[0] ?? 'empty',
            'all_keys' => isset($cities[0]) ? array_keys($cities[0]) : []
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }
}
