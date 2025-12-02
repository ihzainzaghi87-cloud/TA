<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllerMiddleware;

class UserPointController extends Controller
{
    /**
     * Laravel 12: definisikan middleware di sini per aksi.
     */
    public static function middleware(): array
    {
        return [
            // selalu butuh login
            new ControllerMiddleware('auth'),

            // LIST roles
            (new ControllerMiddleware('permission:user-points.index|user-points.view'))->only(['index', 'show', 'leaderboard']),

            // Sinkronisasi permissions ke role
            (new ControllerMiddleware('permission:user-points.sync-permissions|user-points.update'))->only(['syncPermissions']),
        ];
    }

    /**
     * Display a listing of user points.
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        
        $userPoints = UserPoint::with('user')
            ->when($q, function($query) use ($q) {
                $query->whereHas('user', function($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('total_points', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.user-points.index', compact('userPoints', 'q'));
    }

    /**
     * Display the specified user point.
     */
    public function show($userId)
    {
        $userPoint = UserPoint::where('user_id', $userId)->with('user')->firstOrFail();

        $transactions = PointTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.user-points.show', compact('userPoint', 'transactions'));
    }

    /**
     * Show the form for editing user points.
     */
    // public function edit($userId)
    // {
    //     $userPoint = UserPoint::where('user_id', $userId)
    //         ->with('user')
    //         ->firstOrFail();

    //     return view('admin.user-points.edit', compact('userPoint'));
    // }

    /**
     * Update the specified user point.
     */
    // public function update(Request $request, $userId)
    // {
    //     $request->validate([
    //         'total_points' => 'required|integer|min:0',
    //     ]);

    //     $userPoint = UserPoint::where('user_id', $userId)->firstOrFail();
    //     $userPoint->update([
    //         'total_points' => $request->total_points
    //     ]);

    //     return redirect()
    //         ->route('admin.user-points.show', $userId)
    //         ->with('success', 'Poin user berhasil diupdate.');
    // }

    /**
     * Reset user points to zero.
     */
    public function reset($userId)
    {
        $userPoint = UserPoint::where('user_id', $userId)->firstOrFail();
        $userPoint->update(['total_points' => 0]);

        return redirect()
            ->route('admin.user-points.show', $userId)
            ->with('success', 'Poin user berhasil direset.');
    }

    /**
     * Get user points leaderboard.
     */
    public function leaderboard(Request $request)
    {
        $limit = $request->query('limit', 20);
        
        $leaderboard = UserPoint::with('user:id,name,email')
            ->where('total_points', '>', 0)
            ->orderBy('total_points', 'desc')
            ->limit($limit)
            ->get();

        return view('admin.user-points.leaderboard', compact('leaderboard', 'limit'));
    }
}
