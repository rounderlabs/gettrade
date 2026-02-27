<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendTelegramNotificationJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $tries = 5;
    public $backoff = [5, 10, 30, 60];

    public function __construct(
        public int $userId,
        public string $chatId,
        public string $eventType,
        public string $message
    ) {}

    /**
     * @throws Throwable
     */
    public function handle()
    {
        try {

            $token = siteSetting('telegram_bot_token');

            $response = Http::timeout(10)->post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $this->chatId,
                    'text' => $this->message,
                    'parse_mode' => 'HTML'
                ]
            );


        } catch (Throwable $e) {

        }
    }
}
