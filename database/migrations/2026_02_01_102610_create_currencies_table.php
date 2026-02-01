<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // INR, USD
            $table->string('name');              // Indian Rupee
            $table->string('symbol', 15);          // ₹, $
            $table->enum('currency_type', ['fiat', 'crypto'])->default('fiat');
            $table->boolean('is_base')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
