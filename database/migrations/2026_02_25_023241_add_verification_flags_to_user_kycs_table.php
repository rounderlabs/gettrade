<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_kycs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Verification Flags
            |--------------------------------------------------------------------------
            */

            $table->boolean('aadhaar_verified')
                ->default(false)
                ->after('aadhaar_back');

            $table->boolean('pan_verified')
                ->default(false)
                ->after('pan_file');

            $table->boolean('bank_details_completed')
                ->default(false)
                ->after('cancel_cheque');

            /*
            |--------------------------------------------------------------------------
            | Crypto Wallet Completion Flag (Optional)
            |--------------------------------------------------------------------------
            | Only needed if you want to track separately
            */

            $table->boolean('crypto_wallet_completed')
                ->default(false)
                ->after('bank_details_completed');

            /*
            |--------------------------------------------------------------------------
            | Lifecycle Tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('kyc_submitted_at')
                ->nullable()
                ->after('status');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('kyc_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('user_kycs', function (Blueprint $table) {

            $table->dropColumn([
                'aadhaar_verified',
                'pan_verified',
                'bank_details_completed',
                'crypto_wallet_completed',
                'kyc_submitted_at',
                'verified_at',
            ]);
        });
    }
};
