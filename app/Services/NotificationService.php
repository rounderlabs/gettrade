<?php

namespace App\Services;

use App\Jobs\SendTelegramNotificationJob;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Models\UserTelegramAccount;

class NotificationService
{
    /**
     * Main Entry Point
     */
    public static function notify(User $user, string $eventType, array $data = []): void
    {
        // 1️⃣ Check Telegram Connected
        $telegram = UserTelegramAccount::where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$telegram) {
            return;
        }

        // 2️⃣ Check Preferences
        $pref = UserNotificationPreference::firstWhere('user_id', $user->id);

        if (!$pref || !self::isAllowed($pref, $eventType)) {
            return;
        }

        // 3️⃣ Build Message
        $message = self::buildMessage($eventType, $data);

        if (!$message) {
            return;
        }

        // 4️⃣ Dispatch Job (Rate-limit safe)
        SendTelegramNotificationJob::dispatch(
            $telegram->telegram_chat_id,
            $message
        )->onQueue('telegram');
    }

    /**
     * Check If Notification Enabled
     */
    protected static function isAllowed($pref, string $eventType): bool
    {
        return property_exists($pref, $eventType)
            ? (bool)$pref->{$eventType}
            : false;
    }

    /**
     * Build Message Templates
     */
    protected static function buildMessage(string $eventType, array $data): ?string
    {
        return match ($eventType) {

            'deposit' =>
                "💰 <b>Deposit Successful</b>\n\n" .
                "Amount: {$data['amount']} {$data['currency']}\n" .
                "Date: " . now()->format('d M Y H:i'),

            'withdrawal_success' =>
                "✅ <b>Withdrawal Successful</b>\n\n" .
                "Amount: {$data['amount']} {$data['currency']}\n" .
                "Wallet: {$data['wallet']}",

            'direct_income' =>
                "💸 <b>Direct Income Credited</b>\n\n" .
                "Amount: {$data['amount']}\n" .
                "From: {$data['from']}",

            'roi_income' =>
                "📈 <b>ROI Credited</b>\n\n" .
                "Amount: {$data['amount']}\n" .
                "Plan: {$data['plan']}",

            'transfer' =>
                "🔁 <b>Fund Transfer Completed</b>\n\n" .
                "Amount: {$data['amount']}",

            'active_downline' =>
                "👥 <b>Downline Activated</b>\n\n" .
                "User: {$data['username']}",

            'kyc' =>
                "🛂 <b>KYC Status Update</b>\n\n" .
                "Status: {$data['status']}",

            'subscription' =>
                "📦 <b>Subscription Activated</b>\n\n" .
                "Plan: {$data['plan']}",

            'otp' =>
                "🔐 <b>Your OTP Code</b>\n\n" .
                "Code: {$data['code']}",

            default => null
        };
    }
}
