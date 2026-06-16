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
        Schema::create('user_booster_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('subscription_id');
            $table->integer('active_direct_count')->default(0);
            $table->tinyInteger('is_booster_active')->default(0);
            $table->timestamp('booster_activated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'subscription_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_booster_stats');
    }
};
