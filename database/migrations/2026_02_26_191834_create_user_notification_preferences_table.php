<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            // Security / System
            $table->boolean('otp')->default(true);

            // Account Events
            $table->boolean('subscription')->default(true);
            $table->boolean('kyc')->default(true);
            $table->boolean('deposit')->default(true);
            $table->boolean('withdrawal_success')->default(true);

            // Team Events
            $table->boolean('active_downline')->default(true);
            $table->boolean('transfer')->default(true);
            $table->boolean('direct_income')->default(true);
            $table->boolean('roi_income')->default(true);

            // Admin related
            $table->boolean('admin_notification')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
