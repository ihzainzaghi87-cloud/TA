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

class CustomerProfileController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        // $this->middleware('auth');
        $this->rajaOngkir = $rajaOngkir;
    }

    // ================== PROFILE SECTION ==================

    /**
     * Display user profile
     */
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
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat profil');
        }
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        try {
            $user = Auth::user();
            return view('customer.profile.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading edit profile: ' . $e->getMessage());
            return redirect()->route('profile.index')
                ->with('error', 'Gagal memuat halaman edit profil');
        }
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
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

            return redirect()->route('profile.index')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil')
                ->withInput();
        }
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('customer.profile.change-password');
    }

    /**
     * Update user password
     */
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

            // Check if current password is correct
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Password lama tidak sesuai'])
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->route('profile.index')
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

    /**
     * Display user points overview
     */
    public function points()
    {
        try {
            $user = Auth::user();
            $userPoint = UserPoint::firstOrCreate(
                ['user_id' => $user->id],
                ['total_points' => 0]
            );

            // Get recent transactions (last 5)
            $recentTransactions = PointTransaction::where('user_id', $user->id)
                ->with('transactionable')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Calculate statistics
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

    /**
     * Display point transactions history
     */
    public function pointTransactions(Request $request)
    {
        try {
            $user = Auth::user();
            $type = $request->input('type'); // 'earned', 'redeemed', or 'all'

            $query = PointTransaction::where('user_id', $user->id)
                ->with('transactionable')
                ->orderBy('created_at', 'desc');

            // Filter by type if specified
            if ($type && in_array($type, ['earned', 'redeemed', 'refund'])) {
                $query->where('type', $type);
            }

            $transactions = $query->paginate(20);

            // Get current balance
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
     * Display order history
     */
    public function orders(Request $request)
    {
        try {
            $status = $request->input('status'); // Filter by status

            $query = Order::with(['orderItems.variation.product.images', 'shippingAddress'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');

            // Filter by status if specified
            if ($status && in_array($status, [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED
            ])) {
                $query->where('status', $status);
            }

            $orders = $query->paginate(10);

            // Get order statistics
            $orderStats = [
                'total' => Order::where('user_id', Auth::id())->count(),
                'pending' => Order::where('user_id', Auth::id())->where('status', Order::STATUS_PENDING)->count(),
                'processing' => Order::where('user_id', Auth::id())->where('status', Order::STATUS_PROCESSING)->count(),
                'shipped' => Order::where('user_id', Auth::id())->where('status', Order::STATUS_SHIPPED)->count(),
                'delivered' => Order::where('user_id', Auth::id())->where('status', Order::STATUS_DELIVERED)->count(),
            ];

            return view('customer.profile.orders', compact('orders', 'orderStats', 'status'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat daftar pesanan');
        }
    }

    /**
     * Display order detail
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

            return view('customer.profile.order-detail', compact('order'));
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

            // Check if order has tracking information
            if (!$order->hasTracking()) {
                return redirect()->route('profile.order-detail', $orderId)
                    ->with('error', 'Nomor resi belum tersedia. Pesanan belum dikirim.');
            }

            // Get tracking data from RajaOngkir
            $trackingData = null;
            try {
                $trackingData = $this->rajaOngkir->trackWaybill(
                    $order->getCourierForTracking(),
                    $order->tracking_number
                );
            } catch (\Exception $e) {
                Log::error('Error tracking shipment: ' . $e->getMessage());
                // Continue without tracking data
            }

            return view('customer.profile.track-order', compact('order', 'trackingData'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Order not found for tracking: {$orderId} for user: " . Auth::id());
            return redirect()->route('profile.orders')
                ->with('error', 'Pesanan tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error loading order tracking: ' . $e->getMessage());
            return redirect()->route('profile.order-detail', $orderId)
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
                ->where('status', Order::STATUS_SHIPPED)
                ->firstOrFail();

            $order->update([
                'status' => Order::STATUS_DELIVERED,
                'delivered_at' => now()
            ]);

            Log::info("Order #{$order->order_number} marked as delivered by user");

            return redirect()->route('profile.order-detail', $orderId)
                ->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('profile.orders')
                ->with('error', 'Pesanan tidak ditemukan atau tidak dapat dikonfirmasi');
        } catch (\Exception $e) {
            Log::error('Error confirming order received: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengkonfirmasi penerimaan pesanan');
        }
    }

    // ================== ADDRESS SECTION ==================
    // Note: User address management is handled by UserAddressController
    // This method just redirects to the address management page

    /**
     * Redirect to address management
     */
    public function addresses()
    {
        return redirect()->route('addresses.index');
    }
}
