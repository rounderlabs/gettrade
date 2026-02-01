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
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20);       // BTC, ETH, USDT
            $table->string('pair', 20)->default('USD'); // BTC/USD
            $table->decimal('price', 24, 8);
            $table->timestamp('fetched_at');
            $table->timestamps();

            $table->unique(['symbol', 'pair']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_prices');
    }
};
