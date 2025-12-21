<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\PointTransaction;
use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;
use App\Exports\PointTransactionsExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class ReportController extends Controller
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            // Wajib login
            new ControllerMiddleware('auth'),

            // Halaman utama laporan
            (new ControllerMiddleware('permission:reports.view'))->only([
                'index'
            ]),

            // Orders report
            (new ControllerMiddleware('permission:reports.orders'))->only([
                'orders',
                'ordersExportPdf',
                'ordersExportExcel',
            ]),

            // Products report
            (new ControllerMiddleware('permission:reports.products'))->only([
                'products',
                'productsExportPdf',
                'productsExportExcel',
            ]),

            // Users report
            (new ControllerMiddleware('permission:reports.users'))->only([
                'users',
                'usersExportPdf',
                'usersExportExcel',
            ]),

            // Point transactions report
            (new ControllerMiddleware('permission:reports.points'))->only([
                'pointTransactions',
                'pointTransactionsExportPdf',
                'pointTransactionsExportExcel',
            ]),

            // Sales summary report
            (new ControllerMiddleware('permission:reports.sales'))->only([
                'salesSummary',
                'salesSummaryExportPdf',
            ]),
        ];
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    // ========== ORDERS REPORT ==========
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.variation.product']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate summary
        $summary = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'total_shipping' => $query->sum('shipping_cost'),
            'total_points_used' => $query->sum('total_points_used'),
            'total_points_earned' => $query->sum('points_earned'),
        ];

        return view('admin.reports.orders', compact('orders', 'summary'));
    }

    public function ordersExportPdf(Request $request)
    {
        $query = Order::with(['user', 'orderItems.variation.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'total_shipping' => $orders->sum('shipping_cost'),
            'total_points_used' => $orders->sum('total_points_used'),
            'total_points_earned' => $orders->sum('points_earned'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.orders', compact('orders', 'summary'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('orders-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function ordersExportExcel(Request $request)
    {
        return Excel::download(
            new OrdersExport($request->all()),
            'orders-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ========== PRODUCTS REPORT ==========
    public function products(Request $request)
    {
        $query = Product::with(['category', 'variations']);

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Filter by type
        if ($request->filled('is_reward')) {
            $query->where('is_reward', $request->is_reward);
        }

        $products = $query->orderBy('name')->paginate(20);

        return view('admin.reports.products', compact('products'));
    }

    public function productsExportPdf(Request $request)
    {
        $query = Product::with(['category', 'variations']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($request->filled('is_reward')) {
            $query->where('is_reward', $request->is_reward);
        }

        $products = $query->orderBy('name')->get();

        $pdf = Pdf::loadView('admin.reports.pdf.products', compact('products'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('products-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function productsExportExcel(Request $request)
    {
        return Excel::download(
            new ProductsExport($request->all()),
            'products-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ========== USERS REPORT ==========
    public function users(Request $request)
    {
        $query = User::with(['roles', 'userPoint', 'orders']);

        // Filter by date joined
        if ($request->filled('start_date')) {
            $query->whereDate('users.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('users.created_at', '<=', $request->end_date);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate summary
        $summary = [
            'total_users' => (clone $query)->count(),
            'total_points' => (clone $query)
                ->join('user_points', 'users.id', '=', 'user_points.user_id')
                ->sum('user_points.total_points'),
        ];

        return view('admin.reports.users', compact('users', 'summary'));
    }

    public function usersExportPdf(Request $request)
    {
        $query = User::with(['userPoint', 'orders']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_users' => $users->count(),
            'total_points' => $users->sum('userPoint.total_points'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.users', compact('users', 'summary'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('users-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function usersExportExcel(Request $request)
    {
        return Excel::download(
            new UsersExport($request->all()),
            'users-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ========== POINT TRANSACTIONS REPORT ==========
    public function pointTransactions(Request $request)
    {
        $query = PointTransaction::with(['user', 'transactionable']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate summary
        $summary = [
            'total_transactions' => $query->count(),
            'total_earned' => $query->where('type', 'earned')->sum('points'),
            'total_redeemed' => $query->where('type', 'redeemed')->sum('points'),
        ];

        return view('admin.reports.point-transactions', compact('transactions', 'summary'));
    }

    public function pointTransactionsExportPdf(Request $request)
    {
        $query = PointTransaction::with(['user', 'transactionable']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_earned' => $transactions->where('type', 'earned')->sum('points'),
            'total_redeemed' => $transactions->where('type', 'redeemed')->sum('points'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.point-transactions', compact('transactions', 'summary'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('point-transactions-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function pointTransactionsExportExcel(Request $request)
    {
        return Excel::download(
            new PointTransactionsExport($request->all()),
            'point-transactions-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ========== SALES SUMMARY REPORT ==========
    public function salesSummary(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'Paid')
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'total_shipping' => $orders->sum('shipping_cost'),
            'average_order_value' => $orders->count() > 0 ? $orders->avg('total') : 0,
            'by_status' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, count(*) as count, sum(total) as total')
                ->groupBy('status')
                ->get(),
            'by_payment' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('payment_status, count(*) as count, sum(total) as total')
                ->groupBy('payment_status')
                ->get(),
        ];

        return view('admin.reports.sales-summary', compact('summary', 'startDate', 'endDate'));
    }

    public function salesSummaryExportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'Paid')
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total'),
            'total_shipping' => $orders->sum('shipping_cost'),
            'average_order_value' => $orders->count() > 0 ? $orders->avg('total') : 0,
            'by_status' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, count(*) as count, sum(total) as total')
                ->groupBy('status')
                ->get(),
            'by_payment' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('payment_status, count(*) as count, sum(total) as total')
                ->groupBy('payment_status')
                ->get(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.sales-summary', compact('summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('sales-summary-' . now()->format('Y-m-d') . '.pdf');
    }
}
