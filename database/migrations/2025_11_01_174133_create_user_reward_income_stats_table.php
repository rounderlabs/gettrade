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
        Schema::create('user_reward_income_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('reward_income_closing_id');
            $table->foreignId('reward_id');
            $table->decimal('income_usd', 40, 4);
            $table->text('reward_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reward_income_stats');
    }
};
