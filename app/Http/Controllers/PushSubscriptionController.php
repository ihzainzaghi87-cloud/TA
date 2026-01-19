<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        Log::info('📥 Push subscription request received', [
            'endpoint' => $request->input('endpoint'),
            'has_keys' => $request->has('keys'),
            'keys' => $request->input('keys'),
            'user_id' => Auth::id(),
            'all_data' => $request->all(),
        ]);

        try {
            // Validate with correct nested format
            $validated = $request->validate([
                'endpoint' => 'required|string',
                'keys' => 'required|array',
                'keys.p256dh' => 'required|string',
                'keys.auth' => 'required|string',
            ]);

            Log::info('✅ Validation passed', $validated);

            $user = Auth::user();

            if (!$user) {
                Log::error('❌ User not authenticated');
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $subscription = PushSubscription::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'endpoint' => $request->endpoint,
                ],
                [
                    'public_key' => $request->input('keys.p256dh'),
                    'auth_token' => $request->input('keys.auth'),
                    'content_encoding' => $request->input('contentEncoding', 'aesgcm'),
                ]
            );

            Log::info('✅ Push subscription saved', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'endpoint' => substr($subscription->endpoint, 0, 50) . '...',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription saved successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('❌ Failed to save subscription', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $deleted = PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->delete();

        Log::info('🗑️ Subscription deleted', [
            'user_id' => Auth::id(),
            'deleted_count' => $deleted,
        ]);

        return response()->json(['success' => true]);
    }

    public function getVapidPublicKey()
    {
        $publicKey = env('VAPID_PUBLIC_KEY');
        
        Log::info('📤 VAPID public key requested', [
            'has_key' => !empty($publicKey),
            'key_preview' => $publicKey ? substr($publicKey, 0, 20) . '...' : 'NULL',
        ]);

        if (!$publicKey) {
            Log::error('❌ VAPID_PUBLIC_KEY not set in .env');
            return response()->json(['error' => 'VAPID key not configured'], 500);
        }

        return response()->json(['publicKey' => $publicKey]);
    }
}
