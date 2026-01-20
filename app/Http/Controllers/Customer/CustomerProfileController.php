<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use App\Models\Order;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerProfileController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    // ================== HELPER METHOD ==================
    
    /**
     * Get tracking data for an order
     */
    private function getTrackingData($order)
    {
        if (!$order->hasTracking()) {
            return null;
        }

        try {
            $courierCode = $order->courier_code;
            
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
                return $result['data'];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching tracking data', [
                'order_id' => $order->id,
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    // ================== PROFILE SECTION ==================

    // ... (profile methods tetap sama) ...
    
    public function index()
    {
        try {
            $user = Auth::user();
            $userPoint = UserPoint::where('user_id', $user->id)->first();
            $totalOrders = Order::where('user_id', $user->id)->count();
            $completedOrders = Order::where('user_id', $user->id)
                ->where('status', Order::STATUS_DELIVERED)
                ->count();

            return view('customer.profile.index', compact(
                'user',
                'userPoint',
                'totalOrders',
                'completedOrders'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading profile: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat profil');
        }
    }

    public function edit()
    {
        try {
            $user = Auth::user();
            return view('customer.profile.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading edit profile: ' . $e->getMessage());
            return redirect()->route('customer.profile.index')
                ->with('error', 'Gagal memuat halaman edit profil');
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
            ], [
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'date_of_birth.before' => 'Tanggal lahir tidak valid',
            ]);

            $user->update($validated);

            return redirect()->route('customer.index')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            dd($e);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil')
                ->withInput();
        }
    }

    public function showChangePasswordForm()
    {
        return view('customer.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)],
            ], [
                'current_password.required' => 'Password lama wajib diisi',
                'password.required' => 'Password baru wajib diisi',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'password.min' => 'Password minimal 8 karakter',
            ]);

            $user = Auth::user();

            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password lama tidak sesuai'])
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('customer.index')
                ->with('success', 'Password berhasil diubah');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengubah password');
        }
    }

    // ================== POINTS SECTION ==================

    public function points()
    {
        try {
            $user = Auth::user();
            $userPoint = UserPoint::firstOrCreate(
                ['user_id' => $user->id],
                ['total_points' => 0]
            );

            $recentTransactions = PointTransaction::where('user_id', $user->id)
                ->with('transactionable')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $totalEarned = PointTransaction::where('user_id', $user->id)
                ->where('type', 'earned')
                ->sum('points');

            $totalRedeemed = PointTransaction::where('user_id', $user->id)
                ->where('type', 'redeemed')
                ->sum('points');

            return view('customer.profile.points', compact(
                'userPoint',
                'recentTransactions',
                'totalEarned',
                'totalRedeemed'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading points: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat data poin');
        }
    }

    public function pointTransactions(Request $request)
    {
        try {
            $user = Auth::user();
            $type = $request->input('type');

            $query = PointTransaction::where('user_id', $user->id)
                ->with('transactionable')
                ->orderBy('created_at', 'desc');

            if ($type && in_array($type, ['earned', 'redeemed', 'refund'])) {
                $query->where('type', $type);
            }

            $transactions = $query->paginate(20);

            $userPoint = UserPoint::where('user_id', $user->id)->first();
            $currentBalance = $userPoint ? $userPoint->total_points : 0;

            return view('customer.profile.point-transactions', compact(
                'transactions',
                'currentBalance',
                'type'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading point transactions: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat riwayat transaksi poin');
        }
    }

    // ================== ORDER SECTION ==================

    /**
     * Display order history WITH TRACKING DATA
     */
    public function orders(Request $request)
    {
        try {
            $status = $request->input('status');

            $query = Order::with(['orderItems.variation.product.images', 'shippingAddress'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');

            // Accept both lowercase and capitalized
            if ($status) {
                $query->where('status', $status);
            }

            $orders = $query->paginate(10);

            // ADD TRACKING DATA
            foreach ($orders as $order) {
                $order->trackingData = null;
                if ($order->status == 'Shipped' && $order->hasTracking()) {
                    $order->trackingData = $this->getTrackingData($order);
                }
            }

            // Stats dengan format yang benar
            $orderStats = [
                'total' => Order::where('user_id', Auth::id())->count(),
                'pending' => Order::where('user_id', Auth::id())->where('status', 'Pending')->count(),
                'processing' => Order::where('user_id', Auth::id())->where('status', 'Processing')->count(),
                'shipped' => Order::where('user_id', Auth::id())->where('status', 'Shipped')->count(),
                'delivered' => Order::where('user_id', Auth::id())->where('status', 'Delivered')->count(),
            ];

            return view('customer.profile.orders', compact('orders', 'orderStats', 'status'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat daftar pesanan');
        }
    }

    /**
     * Display order detail WITH TRACKING DATA
     */
    public function orderDetail($orderId)
    {
        try {
            $order = Order::with([
                'orderItems.variation.product.images',
                'shippingAddress',
                'pointTransactions'
            ])
                ->where('user_id', Auth::id())
                ->findOrFail($orderId);

            // ADD TRACKING DATA
            $trackingData = null;
            if ($order->status == Order::STATUS_SHIPPED && $order->hasTracking()) {
                $trackingData = $this->getTrackingData($order);
            }

            return view('customer.profile.order-detail', compact('order', 'trackingData'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Order not found: {$orderId} for user: " . Auth::id());
            return redirect()->route('profile.orders')
                ->with('error', 'Pesanan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error loading order detail: ' . $e->getMessage());
            return redirect()->route('profile.orders')
                ->with('error', 'Gagal memuat detail pesanan');
        }
    }

    /**
     * Track order shipment
     */
    public function trackOrder($orderId)
    {
        try {
            $order = Order::with([
                'orderItems.variation.product.images',
                'shippingAddress'
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);

            if (!$order->hasTracking()) {
                return redirect()->route('customer.order-detail', $orderId)
                            ->with('error', 'Nomor resi belum tersedia. Pesanan belum dikirim.');
            }

            $courierCode = $order->courier_code;
            
            Log::info('=== TRACKING ORDER ===', [
                'order_id' => $orderId,
                'tracking_number' => $order->tracking_number,
                'courier_raw' => $order->courier,
                'courier_code' => $courierCode,
                'status' => $order->status
            ]);

            $trackingData = null;
            
            try {
                $result = $this->rajaOngkir->trackWaybill(
                    $order->tracking_number,
                    $courierCode
                );

                Log::info('=== API RAW RESPONSE ===', $result);

                if (isset($result['error'])) {
                    Log::error('API Error', ['error' => $result['error']]);
                    session()->flash('warning', 'Gagal mengambil data tracking: ' . $result['error']);
                }
                elseif (isset($result['meta']) && isset($result['data'])) {
                    if ($result['meta']['code'] == 200) {
                        $trackingData = $result['data'];
                        Log::info('SUCCESS: Tracking data extracted', [
                            'has_manifest' => isset($trackingData['manifest']),
                            'manifest_count' => isset($trackingData['manifest']) ? count($trackingData['manifest']) : 0
                        ]);
                    } else {
                        Log::warning('API returned non-200 code', [
                            'code' => $result['meta']['code'],
                            'message' => $result['meta']['message'] ?? 'Unknown'
                        ]);
                        session()->flash('warning', $result['meta']['message'] ?? 'Data tracking tidak ditemukan');
                    }
                } else {
                    Log::error('Unexpected API response structure', [
                        'keys' => array_keys($result)
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('=== TRACKING EXCEPTION ===', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }

            return view('customer.profile.track-order', compact('order', 'trackingData'));

        } catch (ModelNotFoundException $e) {
            return redirect()->route('customer.orders')
                        ->with('error', 'Pesanan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error loading order tracking', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('customer.order-detail', $orderId)
                        ->with('error', 'Gagal memuat informasi pengiriman');
        }
    }

    /**
     * Confirm order received (mark as delivered)
     */
    public function confirmReceived($orderId)
    {
        try {
            $order = Order::where('user_id', Auth::id())
                ->where('id', $orderId)
                ->whereIn('status', ['Shipped', 'shipped'])
                ->firstOrFail();

            $order->update([
                'status' => 'Delivered',
                'delivered_at' => now()
            ]);

            Log::info("Order #{$order->order_number} marked as delivered by user");

            return redirect()->route('customer.order-detail', $orderId)
                ->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('customer.orders')
                ->with('error', 'Pesanan tidak ditemukan atau tidak dapat dikonfirmasi');
        } catch (\Exception $e) {
            Log::error('Error confirming order received: ' . $e->getMessage());
            dd($e);
            return redirect()->back()
                ->with('error', 'Gagal mengkonfirmasi penerimaan pesanan');
        }
    }

    public function printInvoice(Order $order)
    {
        // Ensure user can only print their own invoice
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'order' => $order->load([
                'user',
                'orderItems.variation.product.images',
                'shippingAddress.province',
                'shippingAddress.city',
                'pointTransactions'
            ]),
            'companyName' => 'The Paranoia',
            'companyAddress' => 'Jl. Fashion Street No. 123, Jakarta',
            'companyPhone' => '+62 21 1234 5678',
            'companyEmail' => 'info@theparanoia.com',
        ];

        $pdf = Pdf::loadView('customer.profile.invoice', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }

    // ================== ADDRESS SECTION ==================

    public function addresses()
    {
        return redirect()->route('addresses.index');
    }
}
