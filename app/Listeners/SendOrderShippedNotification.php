<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Services\PushNotificationService;
use Illuminate\Support\Facades\Log;

class SendOrderShippedNotification
{
    protected $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderShipped $event)
    {
        Log::info('🚀 OrderShipped Event Received in Listener', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'user_id' => $event->order->user_id,
        ]);

        try {
            $order = $event->order;
            $user = $order->user;

            if (!$user) {
                Log::error('❌ Order has no user', ['order_id' => $order->id]);
                return;
            }

            Log::info('📦 Preparing notification for user', [
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]);

            // Check if user has subscriptions
            $subscriptionCount = $user->pushSubscriptions()->count();
            Log::info('📱 User push subscriptions', [
                'user_id' => $user->id,
                'subscription_count' => $subscriptionCount,
            ]);

            if ($subscriptionCount === 0) {
                Log::warning('⚠️ User has no push subscriptions', [
                    'user_id' => $user->id,
                ]);
                return;
            }

            // Prepare payload
            $payload = [
                'title' => 'Pesanan Anda Telah Dikirim!',
                'body' => "Order #{$order->order_number} sedang dalam pengiriman via {$order->courier}",
                'icon' => '/images/icons/icon-192x192.png',
                'badge' => '/images/icons/icon-72x72.png',
                'data' => [
                    'url' => '/orders/' . $order->id,
                    'order_id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                ],
            ];

            Log::info('📤 Sending push notification', [
                'payload' => $payload,
            ]);

            $this->pushService->sendToUser($user, $payload);

            Log::info('✅ Push notification sent successfully', [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Failed to send push notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
