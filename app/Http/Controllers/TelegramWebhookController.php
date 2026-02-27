<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationPreference;
use App\Models\UserTelegramAccount;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        $chatId = $data['message']['chat']['id'] ?? null;
        $username = $data['message']['from']['username'] ?? null;
        $text = $data['message']['text'] ?? null;

        if (!$chatId || !$text) {
            return response()->json(['ok' => true]);
        }

        if (str_starts_with($text, '/start')) {

            $parts = explode(' ', $text);
            $userId = $parts[1] ?? null;

            if ($userId) {

                UserTelegramAccount::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'telegram_chat_id' => $chatId,
                        'telegram_username' => $username,
                        'is_active' => true
                    ]
                );

                UserNotificationPreference::firstOrCreate(
                    ['user_id' => $userId]
                );
            }
        }

        return response()->json(['ok' => true]);
    }
}
