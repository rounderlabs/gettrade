<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class AdminTelegramService
{
    public static function send($message)
    {
        $token = siteSetting('telegram_bot_token');
        $chatId = siteSetting('telegram_chat_id');

        if (!$token || !$chatId) {
            return;
        }

        Http::post(
            "https://api.telegram.org/bot{$token}/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]
        );
    }
}
