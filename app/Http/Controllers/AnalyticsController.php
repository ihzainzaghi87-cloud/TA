<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Analytics Overview - Halaman Utama
     */
    public function index(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'overview' => $this->getOverviewStats($startDate),
            'charts' => [
                'daily_revenue' => $this->getDailyRevenue($startDate),
                'order_status' => $this->getOrderStatusDistribution($startDate),
                'category_performance' => $this->getCategorySales($startDate)->take(5),
            ]
        ];
        
        return view('admin.analytics.index', $data);
    }
    
    /**
     * Sales Analytics
     */
    public function sales(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'revenue_stats' => $this->getRevenueStats($startDate),
            'order_stats' => $this->getOrderStats($startDate),
            'charts' => [
                'daily_revenue' => $this->getDailyRevenue($startDate),
                'hourly_sales' => $this->getHourlySales($startDate),
                'revenue_by_status' => $this->getRevenueByStatus($startDate),
            ],
            'payment_methods' => $this->getPaymentMethodStats($startDate),
            'shipping_stats' => $this->getShippingStats($startDate),
        ];
        
        return view('admin.analytics.sales', $data);
    }
    
    /**
     * Product Analytics
     */
    public function products(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'product_stats' => $this->getProductStats($startDate),
            'top_products' => $this->getTopProducts($startDate, 20),
            'category_sales' => $this->getCategorySales($startDate),
            'variation_performance' => $this->getVariationPerformance($startDate),
            'inventory' => [
                'low_stock' => $this->getLowStockProducts(),
                'out_of_stock' => $this->getOutOfStockProducts(),
                'stock_value' => $this->getStockValue(),
            ],
        ];
        
        return view('admin.analytics.products', $data);
    }
    
    /**
     * Customer Analytics
     */
    public function customers(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'customer_stats' => $this->getCustomerStats($startDate),
            'top_customers' => $this->getTopCustomers($startDate, 20),
            'customer_segments' => $this->getCustomerSegments($startDate),
            'retention_rate' => $this->calculateRetentionRate($startDate),
            'geographic_distribution' => $this->getGeographicDistribution($startDate),
            'customer_lifetime_value' => $this->getCustomerLifetimeValue($startDate),
        ];
        
        return view('admin.analytics.customers', $data);
    }
    
    /**
     * Points System Analytics
     */
    public function points(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'points_stats' => $this->getPointsStats($startDate),
            'points_distribution' => $this->getPointsDistribution(),
            'top_point_earners' => $this->getTopPointEarners($startDate, 20),
            'top_point_redeemers' => $this->getTopPointRedeemers($startDate, 20),
            'points_transactions' => $this->getRecentPointTransactions(50),
            'reward_products' => $this->getRewardProductsStats($startDate),
        ];
        
        return view('admin.analytics.points', $data);
    }
    
    /**
     * Content Analytics (Articles & Banners)
     */
    public function content(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'period' => $period,
            'article_stats' => $this->getArticleStats($startDate),
            'banner_stats' => $this->getBannerStats($startDate),
        ];
        
        return view('admin.analytics.content', $data);
    }
    
    // ==================== PRIVATE METHODS ====================
    
    /**
     * Overview Statistics
     */
    private function getOverviewStats($startDate)
    {
        $totalRevenue = Order::where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', $startDate)
            ->sum('total');
            
        $days = $startDate->diffInDays(Carbon::now());
        $previousPeriod = $startDate->copy()->subDays($days);
        
        $previousRevenue = Order::where('payment_status', Order::PAYMENT_PAID)
            ->whereBetween('created_at', [$previousPeriod, $startDate])
            ->sum('total');
            
        $revenueGrowth = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;
        
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $previousOrders = Order::whereBetween('created_at', [$previousPeriod, $startDate])->count();
        $orderGrowth = $previousOrders > 0 ? (($totalOrders - $previousOrders) / $previousOrders) * 100 : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'revenue_growth' => round($revenueGrowth, 2),
            'total_orders' => $totalOrders,
            'order_growth' => round($orderGrowth, 2),
            'total_customers' => User::where('created_at', '>=', $startDate)->count(),
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'conversion_rate' => $this->calculateConversionRate($startDate),
        ];
    }
    
    /**
     * Revenue Statistics
     */
    private function getRevenueStats($startDate)
    {
        $paidOrders = Order::where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', $startDate);
            
        return [
            'total_revenue' => $paidOrders->sum('total'),
            'total_subtotal' => $paidOrders->sum('subtotal'),
            'total_shipping' => $paidOrders->sum('shipping_cost'),
            'points_used_value' => $paidOrders->sum('total_points_used'),
            'average_order_value' => $paidOrders->avg('total'),
            'highest_order' => $paidOrders->max('total'),
            'lowest_order' => $paidOrders->min('total'),
        ];
    }
    
    /**
     * Order Statistics
     */
    private function getOrderStats($startDate)
    {
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        
        return [
            'total_orders' => $totalOrders,
            'pending_orders' => Order::where('created_at', '>=', $startDate)
                ->where('status', Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::where('created_at', '>=', $startDate)
                ->where('status', Order::STATUS_PROCESSING)->count(),
            'shipped_orders' => Order::where('created_at', '>=', $startDate)
                ->where('status', Order::STATUS_SHIPPED)->count(),
            'delivered_orders' => Order::where('created_at', '>=', $startDate)
                ->where('status', Order::STATUS_DELIVERED)->count(),
            'cancelled_orders' => Order::where('created_at', '>=', $startDate)
                ->where('status', Order::STATUS_CANCELLED)->count(),
            'completion_rate' => $this->calculateCompletionRate($startDate),
            'cancellation_rate' => $this->calculateCancellationRate($startDate),
            'average_fulfillment_time' => $this->getAverageFulfillmentTime($startDate),
        ];
    }
    
    /**
     * Product Statistics
     */
    private function getProductStats($startDate)
    {
        $soldProducts = OrderItem::whereHas('order', function($q) use ($startDate) {
            $q->where('created_at', '>=', $startDate)
              ->where('payment_status', Order::PAYMENT_PAID);
        });
        
        return [
            'total_items_sold' => $soldProducts->sum('quantity'),
            'unique_products_sold' => $soldProducts->distinct('variation_id')->count(),
            'total_product_revenue' => $soldProducts->sum('subtotal'),
            'total_point_products' => $soldProducts->sum('point_subtotal'),
            'average_items_per_order' => $soldProducts->count() > 0 
                ? $soldProducts->sum('quantity') / Order::where('payment_status', Order::PAYMENT_PAID)
                    ->where('created_at', '>=', $startDate)->count() 
                : 0,
            'total_active_products' => Product::where('is_active', true)->count(),
            'total_products' => Product::count(),
        ];
    }
    
    /**
     * Customer Statistics
     */
    private function getCustomerStats($startDate)
    {
        $newCustomers = User::where('created_at', '>=', $startDate)->count();
        $activeCustomers = Order::where('created_at', '>=', $startDate)
            ->distinct('user_id')->count();
            
        $returningCustomers = Order::where('created_at', '>=', $startDate)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();
            
        return [
            'new_customers' => $newCustomers,
            'active_customers' => $activeCustomers,
            'returning_customers' => $returningCustomers,
            'first_time_buyers' => $activeCustomers - $returningCustomers,
            'customer_retention_rate' => $this->calculateRetentionRate($startDate),
            'repeat_purchase_rate' => $activeCustomers > 0 
                ? round(($returningCustomers / $activeCustomers) * 100, 2) 
                : 0,
        ];
    }
    
    /**
     * Points Statistics
     */
    private function getPointsStats($startDate)
    {
        $pointsEarned = PointTransaction::earned()
            ->where('created_at', '>=', $startDate)
            ->sum('points');

        $pointsRedeemed = abs(PointTransaction::redeemed()
            ->where('created_at', '>=', $startDate)
            ->sum('points'));

        // Perbaikan: Tambahkan fallback untuk average_points_per_user
        $averagePoints = DB::table('user_points')->avg('total_points');

        return [
            'total_points_earned' => $pointsEarned,
            'total_points_redeemed' => $pointsRedeemed,
            'net_points' => $pointsEarned - $pointsRedeemed,
            'active_point_users' => PointTransaction::where('created_at', '>=', $startDate)
                ->distinct('user_id')->count(),
            'average_points_per_user' => $averagePoints ?? 0, // Fallback ke 0
            'total_points_in_system' => DB::table('user_points')
                ->sum('total_points') ?? 0, // Fallback ke 0
        ];
    }
    
    /**
     * Daily Revenue Chart Data
     */
    private function getDailyRevenue($startDate)
    {
        return Order::where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('AVG(total) as avg_order')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    /**
     * Hourly Sales Pattern
     */
    private function getHourlySales($startDate)
    {
        return Order::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }
    
    /**
     * Order Status Distribution
     */
    private function getOrderStatusDistribution($startDate)
    {
        return Order::where('created_at', '>=', $startDate)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return [
                    'status' => $item->status,
                    'count' => $item->count,
                    'label' => Order::getStatusOptions()[$item->status] ?? $item->status
                ];
            });
    }
    
    /**
     * Revenue by Payment Status
     */
    private function getRevenueByStatus($startDate)
    {
        return Order::where('created_at', '>=', $startDate)
            ->select(
                'payment_status',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->groupBy('payment_status')
            ->get();
    }
    
    /**
     * Top Selling Products
     */
    private function getTopProducts($startDate, $limit = 20)
    {
        return OrderItem::whereHas('order', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)
                  ->where('payment_status', Order::PAYMENT_PAID);
            })
            ->select(
                'product_name',
                'variation_id',
                'variant_details',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->groupBy('product_name', 'variation_id', 'variant_details')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Category Sales Distribution
     */
    private function getCategorySales($startDate)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->where('orders.created_at', '>=', $startDate)
            ->select(
                'categories.name as category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
    }
    
    /**
     * Variation Performance (Color & Size)
     */
    private function getVariationPerformance($startDate)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('variations', 'order_items.variation_id', '=', 'variations.id')
            ->where('orders.payment_status', Order::PAYMENT_PAID)
            ->where('orders.created_at', '>=', $startDate)
            ->select(
                'variations.color',
                'variations.size',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as revenue')
            )
            ->groupBy('variations.color', 'variations.size')
            ->orderByDesc('total_sold')
            ->limit(30)
            ->get();
    }
    
    /**
     * Payment Method Statistics
     */
    private function getPaymentMethodStats($startDate)
    {
        // Assuming you track payment method, adjust if needed
        return Order::where('created_at', '>=', $startDate)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->select(
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->first();
    }
    
    /**
     * Shipping Statistics
     */
    private function getShippingStats($startDate)
    {
        return [
            'by_courier' => Order::where('created_at', '>=', $startDate)
                ->where('payment_status', Order::PAYMENT_PAID)
                ->select(
                    'courier',
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(shipping_cost) as total_shipping_cost')
                )
                ->groupBy('courier')
                ->get(),
            'by_city' => Order::where('created_at', '>=', $startDate)
                ->where('payment_status', Order::PAYMENT_PAID)
                ->select(
                    'destination_city_id',
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('destination_city_id')
                ->orderByDesc('order_count')
                ->limit(10)
                ->get(),
        ];
    }
    
    /**
     * Get Low Stock Products
     */
    private function getLowStockProducts($threshold = 10)
    {
        return DB::table('variations')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->where('variations.stock', '>', 0)
            ->where('variations.stock', '<=', $threshold)
            ->where('products.is_active', true)
            ->select(
                'products.name as product_name',
                'variations.color',
                'variations.size',
                'variations.stock'
            )
            ->orderBy('variations.stock')
            ->get();
    }
    
    /**
     * Get Out of Stock Products
     */
    private function getOutOfStockProducts()
    {
        return DB::table('variations')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->where('variations.stock', 0)
            ->where('products.is_active', true)
            ->select(
                'products.name as product_name',
                'variations.color',
                'variations.size'
            )
            ->get();
    }
    
    /**
     * Calculate Stock Value
     */
    private function getStockValue()
    {
        return DB::table('variations')
            ->join('products', 'variations.product_id', '=', 'products.id')
            ->where('products.is_active', true)
            ->select(
                DB::raw('SUM(variations.stock) as total_stock'),
                DB::raw('SUM(variations.stock * products.price) as stock_value')
            )
            ->first();
    }
    
    /**
     * Get Top Customers by Revenue
     */
    private function getTopCustomers($startDate, $limit = 20)
    {
        return Order::where('payment_status', Order::PAYMENT_PAID)
            ->where('created_at', '>=', $startDate)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_spent'),
                DB::raw('AVG(total) as avg_order_value')
            )
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Customer Segments (RFM Analysis)
     */
    private function getCustomerSegments($startDate)
    {
        $customers = Order::where('payment_status', Order::PAYMENT_PAID)
            ->select(
                'user_id',
                DB::raw('MAX(created_at) as last_order_date'),
                DB::raw('COUNT(*) as order_frequency'),
                DB::raw('SUM(total) as total_monetary')
            )
            ->groupBy('user_id')
            ->get();
            
        $segments = [
            'vip' => 0,      // High frequency, high value
            'loyal' => 0,    // High frequency
            'at_risk' => 0,  // Haven't ordered recently
            'new' => 0,      // First time buyers
        ];
        
        foreach ($customers as $customer) {
            $daysSinceLastOrder = Carbon::parse($customer->last_order_date)->diffInDays(Carbon::now());
            
            if ($customer->order_frequency >= 5 && $customer->total_monetary >= 10000000) {
                $segments['vip']++;
            } elseif ($customer->order_frequency >= 3) {
                $segments['loyal']++;
            } elseif ($daysSinceLastOrder > 90) {
                $segments['at_risk']++;
            } elseif ($customer->order_frequency == 1) {
                $segments['new']++;
            }
        }
        
        return $segments;
    }
    
    /**
     * Geographic Distribution
     */
    private function getGeographicDistribution($startDate)
    {
        // Cara 1: Jika menggunakan relasi province/city dari UserAddress
        return Order::where('orders.payment_status', Order::PAYMENT_PAID)
            ->where('orders.created_at', '>=', $startDate)
            ->join('user_addresses', 'orders.user_address_id', '=', 'user_addresses.id')
            ->select(
                'user_addresses.province_name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(orders.total) as total_revenue')
            )
            ->groupBy('user_addresses.province_name')
            ->orderByDesc('order_count')
            ->get();
    }
    
    /**
     * Customer Lifetime Value
     */
    private function getCustomerLifetimeValue($startDate)
    {
        return DB::table(DB::raw('(
            SELECT user_id, SUM(total) as lifetime_value
            FROM orders
            WHERE payment_status = ?
            GROUP BY user_id
        ) as customer_total'))
        ->selectRaw('
            AVG(customer_total.lifetime_value) as avg_lifetime_value,
            MAX(customer_total.lifetime_value) as max_lifetime_value
        ', [Order::PAYMENT_PAID])
        ->first();
    }
    
    /**
     * Top Point Earners
     */
    private function getTopPointEarners($startDate, $limit = 20)
    {
        return PointTransaction::earned()
            ->where('created_at', '>=', $startDate)
            ->select(
                'user_id',
                DB::raw('SUM(points) as total_earned')
            )
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderByDesc('total_earned')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Top Point Redeemers
     */
    private function getTopPointRedeemers($startDate, $limit = 20)
    {
        return PointTransaction::redeemed()
            ->where('created_at', '>=', $startDate)
            ->select(
                'user_id',
                DB::raw('SUM(ABS(points)) as total_redeemed')
            )
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderByDesc('total_redeemed')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Recent Point Transactions
     */
    private function getRecentPointTransactions($limit = 50)
    {
        return PointTransaction::with('user:id,name,email')
            ->latest()
            ->limit($limit)
            ->get();
    }
    
    /**
     * Points Distribution
     */
    private function getPointsDistribution()
    {
        return DB::table(DB::raw('(
            SELECT
                CASE
                    WHEN total_points = 0 THEN "0"
                    WHEN total_points BETWEEN 1 AND 100 THEN "1-100"
                    WHEN total_points BETWEEN 101 AND 500 THEN "101-500"
                    WHEN total_points BETWEEN 501 AND 1000 THEN "501-1000"
                    WHEN total_points BETWEEN 1001 AND 5000 THEN "1001-5000"
                    ELSE "5000+"
                END AS point_range,
                total_points
            FROM user_points
        ) AS ranges'))
        ->select(
            'point_range',
            DB::raw('COUNT(*) as user_count')
        )
        ->groupBy('point_range')
        ->orderByRaw('MIN(total_points)')
        ->get();
    }
    
    /**
     * Reward Products Statistics
     */
    private function getRewardProductsStats($startDate)
    {
        return OrderItem::whereHas('order', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate)
                  ->where('payment_status', Order::PAYMENT_PAID);
            })
            ->where('point_price', '>', 0)
            ->select(
                'product_name',
                DB::raw('SUM(quantity) as total_redeemed'),
                DB::raw('SUM(point_subtotal) as total_points_used')
            )
            ->groupBy('product_name')
            ->orderByDesc('total_redeemed')
            ->get();
    }
    
    /**
     * Article Statistics
     */
    private function getArticleStats($startDate)
    {
        return [
            'total_articles' => Article::where('created_at', '>=', $startDate)->count(),
            'published_articles' => Article::where('is_published', true)
                ->where('created_at', '>=', $startDate)->count(),
            'draft_articles' => Article::where('is_published', false)
                ->where('created_at', '>=', $startDate)->count(),
            'recent_articles' => Article::latest()->limit(10)->get(),
        ];
    }
    
    /**
     * Banner Statistics
     */
    private function getBannerStats($startDate)
    {
        return [
            'total_banners' => DB::table('banners')->count(),
            'active_banners' => DB::table('banners')->where('is_active', true)->count(),
            'inactive_banners' => DB::table('banners')->where('is_active', false)->count(),
        ];
    }
    
    /**
     * Calculate Completion Rate
     */
    private function calculateCompletionRate($startDate)
    {
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $deliveredOrders = Order::where('created_at', '>=', $startDate)
            ->where('status', Order::STATUS_DELIVERED)
            ->count();
            
        return $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0;
    }
    
    /**
     * Calculate Cancellation Rate
     */
    private function calculateCancellationRate($startDate)
    {
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $cancelledOrders = Order::where('created_at', '>=', $startDate)
            ->where('status', Order::STATUS_CANCELLED)
            ->count();
            
        return $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 2) : 0;
    }
    
    /**
     * Calculate Average Fulfillment Time
     */
    private function getAverageFulfillmentTime($startDate)
    {
        $avg = Order::where('created_at', '>=', $startDate)
            ->whereNotNull('delivered_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, delivered_at)) as avg_hours')
            ->first();
            
        return round($avg->avg_hours ?? 0, 2);
    }
    
    /**
     * Calculate Customer Retention Rate
     */
    private function calculateRetentionRate($startDate)
    {
        $days = $startDate->diffInDays(Carbon::now());
        $previousPeriodStart = $startDate->copy()->subDays($days);
        
        $previousCustomers = Order::whereBetween('created_at', [$previousPeriodStart, $startDate])
            ->distinct('user_id')
            ->pluck('user_id');
            
        if ($previousCustomers->count() == 0) {
            return 0;
        }
            
        $returningCustomers = Order::where('created_at', '>=', $startDate)
            ->whereIn('user_id', $previousCustomers)
            ->distinct('user_id')
            ->count();
            
        return round(($returningCustomers / $previousCustomers->count()) * 100, 2);
    }
    
    /**
     * Calculate Conversion Rate (Users who made purchase)
     */
    private function calculateConversionRate($startDate)
    {
        $totalUsers = User::where('created_at', '<=', Carbon::now())->count();
        $buyingUsers = Order::where('created_at', '>=', $startDate)
            ->where('payment_status', Order::PAYMENT_PAID)
            ->distinct('user_id')
            ->count();
            
        return $totalUsers > 0 ? round(($buyingUsers / $totalUsers) * 100, 2) : 0;
    }
    
    /**
     * Export Analytics Report
     */
    public function export(Request $request)
    {
        $period = $request->input('period', '30');
        $startDate = Carbon::now()->subDays($period);
        
        $data = [
            'report_info' => [
                'period' => $period . ' days',
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d'),
                'generated_at' => Carbon::now()->toDateTimeString(),
            ],
            'overview' => $this->getOverviewStats($startDate),
            'revenue' => $this->getRevenueStats($startDate),
            'orders' => $this->getOrderStats($startDate),
            'products' => $this->getProductStats($startDate),
            'customers' => $this->getCustomerStats($startDate),
            'points' => $this->getPointsStats($startDate),
            'top_products' => $this->getTopProducts($startDate, 50),
            'top_customers' => $this->getTopCustomers($startDate, 50),
        ];
        
        // Return as JSON for download
        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="analytics-report-' . date('Y-m-d') . '.json"');
    }
}
