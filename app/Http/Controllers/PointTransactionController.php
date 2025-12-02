<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\UserPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class PointTransactionController extends Controller
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            // selalu butuh login
            new ControllerMiddleware('auth'),

            // LIST point transactions
            (new ControllerMiddleware('permission:point-transactions.index|point-transactions.view'))->only(['index']),

            // FORM create + store
            (new ControllerMiddleware('permission:point-transactions.create'))->only(['create','store']),

            // FORM edit + update nama point transaction
            (new ControllerMiddleware('permission:point-transactions.update'))->only(['edit','update']),

            // Hapus point transaction
            (new ControllerMiddleware('permission:point-transactions.delete'))->only(['destroy']),

            // Sinkronisasi permissions ke point transaction
            (new ControllerMiddleware('permission:point-transactions.sync-permissions|point-transactions.update'))->only(['syncPermissions']),
        ];
    }

    /**
     * Display a listing of point transactions.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $type = $request->string('type')->toString();
        $userId = $request->integer('user_id');
        $startDate = $request->string('start_date')->toString();
        $endDate = $request->string('end_date')->toString();

        $transactions = PointTransaction::with('user:id,name,email')
            ->when($q, function($query) use ($q) {
                $query->where(function($w) use ($q) {
                    $w->where('description', 'like', "%{$q}%")
                      ->orWhereHas('user', function($q2) use ($q) {
                          $q2->where('name', 'like', "%{$q}%")
                             ->orWhere('email', 'like', "%{$q}%");
                      });
                });
            })
            ->when($type, fn($s) => $s->where('type', $type))
            ->when($userId, fn($s) => $s->where('user_id', $userId))
            ->when($startDate, fn($s) => $s->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($s) => $s->whereDate('created_at', '<=', $endDate))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.point-transactions.index', compact(
            'transactions',
            'q',
            'type',
            'userId',
            'startDate',
            'endDate',
            'users'
        ));
    }

    /**
     * Display the specified point transaction.
     */
    public function show($id)
    {
        $transaction = PointTransaction::with(['user', 'transactionable'])
            ->findOrFail($id);

        return view('admin.point-transactions.show', compact('transaction'));
    }

    /**
     * Get user's earned transactions.
     */
    public function earned($userId)
    {
        $user = User::findOrFail($userId);
        
        $transactions = PointTransaction::where('user_id', $userId)
            ->earned()
            ->with('transactionable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.point-transactions.earned', compact('user', 'transactions'));
    }

    /**
     * Get user's redeemed transactions.
     */
    public function redeemed($userId)
    {
        $user = User::findOrFail($userId);
        
        $transactions = PointTransaction::where('user_id', $userId)
            ->redeemed()
            ->with('transactionable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.point-transactions.redeemed', compact('user', 'transactions'));
    }

    /**
     * Get transaction statistics for a user.
     */
    public function statistics($userId)
    {
        $user = User::findOrFail($userId);
        
        $stats = [
            'total_earned' => PointTransaction::where('user_id', $userId)
                ->earned()
                ->sum('points'),
            'total_redeemed' => abs(PointTransaction::where('user_id', $userId)
                ->redeemed()
                ->sum('points')),
            'transaction_count' => PointTransaction::where('user_id', $userId)->count(),
            'earned_count' => PointTransaction::where('user_id', $userId)->earned()->count(),
            'redeemed_count' => PointTransaction::where('user_id', $userId)->redeemed()->count(),
            'current_balance' => UserPoint::where('user_id', $userId)->value('total_points') ?? 0,
        ];

        $recentTransactions = PointTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.point-transactions.statistics', compact('user', 'stats', 'recentTransactions'));
    }

    /**
     * Delete a point transaction (admin only).
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaction = PointTransaction::findOrFail($id);
            
            // Reverse the points
            $userPoint = UserPoint::where('user_id', $transaction->user_id)->first();
            if ($userPoint) {
                $newBalance = $userPoint->total_points - $transaction->points;
                $userPoint->update(['total_points' => max(0, $newBalance)]);
            }

            $transaction->delete();
            DB::commit();

            return redirect()
                ->route('admin.point-transactions.index')
                ->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
