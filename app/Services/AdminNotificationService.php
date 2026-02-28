<?php
namespace App\Services;

use App\Jobs\SendAdminTelegramNotificationJob;
use App\Models\AdminNotification;

class AdminNotificationService
{
    public static function notify($type, $message, $meta = [])
    {
        AdminNotification::create([
            'type' => $type,
            'message' => $message,
            'meta' => $meta,
        ]);

        SendAdminTelegramNotificationJob::dispatch($message);
    }
}
