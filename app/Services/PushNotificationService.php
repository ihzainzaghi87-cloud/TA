<?php

namespace App\Services;

use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $this->webPush = new WebPush($auth);
    }

    public function sendToUser(User $user, array $payload)
    {
        Log::info('🔔 Sending notification to user', [
            'user_id' => $user->id,
            'payload' => $payload,
        ]);

        $subscriptions = $user->pushSubscriptions;

        if ($subscriptions->isEmpty()) {
            Log::warning('⚠️ No subscriptions found for user', [
                'user_id' => $user->id,
            ]);
            return;
        }

        Log::info('📲 Processing subscriptions', [
            'count' => $subscriptions->count(),
        ]);

        foreach ($subscriptions as $subscription) {
            Log::info('📡 Queuing notification', [
                'subscription_id' => $subscription->id,
                'endpoint' => substr($subscription->endpoint, 0, 50) . '...',
            ]);

            $pushSubscription = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'publicKey' => $subscription->public_key,
                'authToken' => $subscription->auth_token,
                'contentEncoding' => $subscription->content_encoding,
            ]);

            $this->webPush->queueNotification(
                $pushSubscription,
                json_encode($payload)
            );
        }

        // Flush and check results
        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                Log::info('✅ Notification sent successfully', [
                    'endpoint' => substr($endpoint, 0, 50) . '...',
                ]);
            } else {
                Log::error('❌ Notification failed', [
                    'endpoint' => substr($endpoint, 0, 50) . '...',
                    'reason' => $report->getReason(),
                ]);

                // Delete invalid subscriptions
                if ($report->getResponse() &&
                    in_array($report->getResponse()->getStatusCode(), [404, 410])) {
                    Log::info('🗑️ Deleting invalid subscription', [
                        'endpoint' => substr($endpoint, 0, 50) . '...',
                    ]);

                    $user->pushSubscriptions()
                        ->where('endpoint', $endpoint)
                        ->delete();
                }
            }
        }
    }
}
