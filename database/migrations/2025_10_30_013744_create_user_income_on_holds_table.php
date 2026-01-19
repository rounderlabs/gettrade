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
        Schema::create('user_income_on_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->decimal('total', 40, 4)->default('0');
            $table->decimal('level', 40, 4)->default('0');
            $table->decimal('roi', 40, 4)->default('0');
            $table->decimal('roi_on_roi', 40, 4)->default('0');
            $table->decimal('maturity_level', 40, 4)->default('0');
            $table->decimal('bonanza', 40, 4)->default('0');
            $table->decimal('reward', 40, 4)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_income_on_holds');
    }
};
