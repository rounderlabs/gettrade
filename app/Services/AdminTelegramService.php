<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminTelegramService
{
    public static function send($message)
    {
        $token = siteSetting('telegram_bot_token');
        $chatId = siteSetting('telegram_chat_id');

        if (!$token || !$chatId) {
            return;
        }
        try {
            Http::timeout(10)
                ->retry(3, 1000)
                ->post(
                    "https://api.telegram.org/bot{$token}/sendMessage",
                    [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'HTML'
                    ]
                );
        } catch (\Throwable $e) {

            Log::warning('Telegram error', [
                'message' => $e->getMessage()
            ]);

        }
    }
}
