<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Article;
use App\Models\PointTransaction;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // Data berdasarkan role
        if ($user->hasRole('superadmin')) {
            $data = $this->getSuperadminData();
        } elseif ($user->hasRole('owner')) {
            $data = $this->getOwnerData();
        } else {
            $data = $this->getStaffData();
        }

        $data['userRole'] = $user->roles->first()?->name ?? 'user';
        return view('admin.dashboard', $data);
    }

    /**
     * Superadmin: Full access - semua statistik sistem
     */
    private function getSuperadminData(): array
    {
        // Chart: Orders by Status (Pie Chart)
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Chart: Revenue Last 7 Days (Line Chart)
        $revenueLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenueLast7Days[$date->format('d M')] = Order::where('payment_status', 'Paid')
                ->whereDate('created_at', $date)
                ->sum('total');
        }

        // Chart: Users by Role (Bar Chart)
        $usersByRole = Role::withCount('users')
            ->get()
            ->pluck('users_count', 'name')
            ->toArray();

        return [
            // User & Access Stats
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count(),
            'newUsersThisMonth' => User::whereMonth('created_at', Carbon::now()->month)->count(),

            // Product Stats
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'totalCategories' => Category::count(),

            // Order Stats
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'processingOrders' => Order::where('status', 'processing')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),

            // Revenue Stats
            'totalRevenue' => Order::where('payment_status', 'Paid')->sum('total'),
            'revenueThisMonth' => Order::where('payment_status', 'Paid')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total'),

            // Recent Data
            'recentOrders' => Order::with('user')->latest()->take(5)->get(),
            'recentUsers' => User::latest()->take(5)->get(),

            // Chart Data
            'ordersByStatus' => $ordersByStatus,
            'revenueLast7Days' => $revenueLast7Days,
            'usersByRole' => $usersByRole,
        ];
    }

    /**
     * Owner: Business overview - fokus pada sales dan users
     */
    private function getOwnerData(): array
    {
        // Chart: Revenue Last 30 Days (Area Chart)
        $revenueLast30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenueLast30Days[$date->format('d M')] = Order::where('payment_status', 'Paid')
                ->whereDate('created_at', $date)
                ->sum('total');
        }

        // Chart: Orders This Month by Status (Doughnut Chart)
        $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Chart: Top 5 Products by Sales
        $topProducts = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'Paid')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->pluck('total_sold', 'products.name')
            ->toArray();

        return [
            // User Stats
            'totalUsers' => User::count(),
            'newUsersThisMonth' => User::whereMonth('created_at', Carbon::now()->month)->count(),
            'totalRoles' => Role::count(),

            // Order & Revenue Stats
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'totalRevenue' => Order::where('payment_status', 'Paid')->sum('total'),
            'revenueThisMonth' => Order::where('payment_status', 'Paid')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total'),
            'revenueToday' => Order::where('payment_status', 'Paid')
                ->whereDate('created_at', Carbon::today())
                ->sum('total'),

            // Product Overview
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),

            // Recent Orders
            'recentOrders' => Order::with('user')->latest()->take(5)->get(),

            // Chart Data
            'revenueLast30Days' => $revenueLast30Days,
            'ordersThisMonth' => $ordersThisMonth,
            'topProducts' => $topProducts,
        ];
    }

    /**
     * Staff: Content management - fokus pada products, orders, categories
     */
    private function getStaffData(): array
    {
        // Chart: Orders to Process (Bar Chart)
        $ordersToProcess = Order::whereIn('status', ['pending', 'processing', 'shipped'])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Chart: Products by Category (Pie Chart)
        $productsByCategory = Category::withCount('products')
            ->get()
            ->pluck('products_count', 'name')
            ->toArray();

        // Chart: Articles Published Last 6 Months
        $articlesLast6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $articlesLast6Months[$date->format('M Y')] = Article::where('is_published', true)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            // Product Stats
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'inactiveProducts' => Product::where('is_active', false)->count(),
            'totalCategories' => Category::count(),

            // Order Stats (yang perlu diproses)
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'processingOrders' => Order::where('status', 'processing')->count(),
            'shippedOrders' => Order::where('status', 'shipped')->count(),

            // Article Stats (jika ada)
            'totalArticles' => Article::count(),
            'publishedArticles' => Article::where('is_published', true)->count(),

            // Recent Orders to Process
            'recentOrders' => Order::with('user')
                ->whereIn('status', ['pending', 'processing'])
                ->latest()
                ->take(5)
                ->get(),

            // Recent Products
            'recentProducts' => Product::with('category')->latest()->take(5)->get(),

            // Chart Data
            'ordersToProcess' => $ordersToProcess,
            'productsByCategory' => $productsByCategory,
            'articlesLast6Months' => $articlesLast6Months,
        ];
    }
}
