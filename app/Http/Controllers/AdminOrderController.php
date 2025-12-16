<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\RajaOngkirService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class AdminOrderController extends Controller
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            // selalu butuh login
            new ControllerMiddleware('auth'),

            // LIST orders
            (new ControllerMiddleware('permission:orders.index|orders.view'))->only(['index']),

            // FORM edit + update order
            (new ControllerMiddleware('permission:orders.update'))->only(['edit','update']),

            // SHOW DETAIL order
            (new ControllerMiddleware('permission:orders.view'))->only(['show']),
        ];
    }

    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Display list of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'orderItems.variation.product'])
                     ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display order detail
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'shippingAddress.city.province',
            'orderItems.variation.product.images'
        ]);

        // AMBIL TRACKING DATA untuk status Shipped atau Delivered
        $trackingData = null;
        if (in_array($order->status, ['Shipped', 'Delivered']) && $order->hasTracking()) {
            $trackingData = $this->getTrackingData($order);
            
            // DEBUG: Log tracking data untuk troubleshooting
            Log::info('Admin Order Tracking', [
                'order_id' => $order->id,
                'status' => $order->status,
                'has_tracking' => $order->hasTracking(),
                'tracking_number' => $order->tracking_number,
                'courier' => $order->courier,
                'has_trackingData' => !is_null($trackingData),
                'manifest_count' => isset($trackingData['manifest']) ? count($trackingData['manifest']) : 0
            ]);
        }

        return view('admin.orders.show', compact('order', 'trackingData'));
    }

    /**
     * Show form to update shipping info
     */
    public function editShipping(Order $order)
    {
        // Only allow if order is paid
        if ($order->payment_status !== 'Paid') {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah dibayar yang bisa diupdate');
        }

        return view('admin.orders.edit-shipping', compact('order'));
    }

    /**
     * Update shipping information and status
     */
    public function updateShipping(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Processing,Shipped,Delivered',
                'tracking_number' => 'required_if:status,Shipped,Delivered|string|max:100',
            ], [
                'status.required' => 'Status harus dipilih',
                'status.in' => 'Status tidak valid',
                'tracking_number.required_if' => 'Nomor resi wajib diisi untuk status Shipped/Delivered',
                'tracking_number.max' => 'Nomor resi maksimal 100 karakter',
            ]);

            DB::beginTransaction();

            // Update status
            $order->status = $validated['status'];
            
            // Update tracking number if provided
            if (!empty($validated['tracking_number'])) {
                $order->tracking_number = $validated['tracking_number'];
            }

            // Set shipped_at timestamp when status changes to Shipped
            if ($validated['status'] === Order::STATUS_SHIPPED && !$order->shipped_at) {
                $order->shipped_at = now();
            }

            // Set delivered_at timestamp when status changes to Delivered
            if ($validated['status'] === Order::STATUS_DELIVERED && !$order->delivered_at) {
                $order->delivered_at = now();
                
                // Also set shipped_at if not set yet
                if (!$order->shipped_at) {
                    $order->shipped_at = now();
                }
            }

            $order->save();

            DB::commit();

            Log::info("Order {$order->order_number} updated by admin", [
                'order_id' => $order->id,
                'new_status' => $validated['status'],
                'tracking_number' => $validated['tracking_number'] ?? null,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->route('admin.orders.show', $order)
                           ->with('success', 'Informasi pengiriman berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order shipping: ' . $e->getMessage());
            
            return redirect()->back()
                           ->with('error', 'Gagal mengupdate informasi pengiriman')
                           ->withInput();
        }
    }

    /**
     * Update only order status (quick action)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatusOptions())),
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate');
    }

    private function getTrackingData($order)
    {
        if (!$order->hasTracking()) {
            return null;
        }

        try {
            // EXTRACT COURIER CODE dengan fallback
            $courierCode = $this->extractCourierCode($order->courier);
            
            Log::info('Fetching tracking data', [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'courier_raw' => $order->courier,
                'courier_code' => $courierCode
            ]);

            $result = $this->rajaOngkir->trackWaybill(
                $order->tracking_number,
                $courierCode
            );

            if (isset($result['error'])) {
                Log::warning('Tracking API Error', [
                    'order_id' => $order->id,
                    'error' => $result['error']
                ]);
                return null;
            }

            if (isset($result['meta']) && $result['meta']['code'] == 200 && isset($result['data'])) {
                Log::info('Tracking data retrieved successfully', [
                    'order_id' => $order->id,
                    'has_manifest' => isset($result['data']['manifest'])
                ]);
                return $result['data'];
            }

            Log::warning('Tracking API returned unexpected response', [
                'order_id' => $order->id,
                'response_keys' => array_keys($result)
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Error fetching tracking data', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function extractCourierCode($courier)
    {
        if (!$courier) {
            return null;
        }

        // Jika sudah dalam format yang benar (jne, tiki, pos, dll)
        $courier = strtolower(trim($courier));
        
        // Mapping untuk berbagai format courier
        $courierMap = [
            'jne' => 'jne',
            'pos indonesia' => 'pos',
            'pos' => 'pos',
            'tiki' => 'tiki',
            'jnt' => 'jnt',
            'j&t' => 'jnt',
            'sicepat' => 'sicepat',
            'anteraja' => 'anteraja',
            'ninja' => 'ninja',
            'lion' => 'lion',
            'pcp' => 'pcp',
            'sap' => 'sap',
            'jet' => 'jet',
            'dse' => 'dse',
            'first' => 'first',
        ];

        // Cek apakah ada match di mapping
        foreach ($courierMap as $key => $code) {
            if (strpos($courier, $key) !== false) {
                return $code;
            }
        }

        // Default return courier as is
        return $courier;
    }
}
