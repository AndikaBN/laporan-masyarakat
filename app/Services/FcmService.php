<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    /**
     * Send push notification via Firebase Cloud Messaging.
     *
     * @param string $token Device token
     * @param string $title Notification title
     * @param string $body Notification body
     */
    public static function notify(string $token, string $title, string $body): void
    {
        $serverKey = config('services.fcm.server_key');

        // Skip if FCM server key not configured
        if (!$serverKey) {
            Log::warning('FCM server key not configured. Notification not sent.');
            return;
        }

        try {
            Http::withToken($serverKey)
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ]);

            Log::info("FCM notification sent to token: {$token}");
        } catch (\Exception $e) {
            Log::error("FCM notification failed: {$e->getMessage()}");
        }
    }

    /**
     * Send notification to multiple tokens.
     */
    public static function notifyMultiple(array $tokens, string $title, string $body): void
    {
        foreach ($tokens as $token) {
            self::notify($token, $title, $body);
        }
    }
}
