<?php

namespace App\Jobs;

use App\Services\AdminTelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAdminTelegramNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $message;

    public $tries = 5;
    public $timeout = 30;

    public function __construct(string $message)
    {
        $this->onQueue('notifications');
        $this->message = $message;
    }

    public function handle()
    {
        AdminTelegramService::send($this->message);
    }
}
