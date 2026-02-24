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
        Schema::table('withdrawal_histories', function (Blueprint $table) {
            $table->foreignId('withdraw_coin_id')->nullable()->default(null)->after('txn_id');
            $table->string('address')->nullable()->default(null)->after('withdraw_coin_id');
            $table->decimal('tokens', 40, 8)->nullable()->default(null)->after('address');
            $table->decimal('token_price', 20, 4)->nullable()->default(null)->after('tokens');
            $table->decimal('withdrawal_crypto_price', 40, 8)->nullable()->default(null)->after('token_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_histories', function (Blueprint $table) {
            $table->foreignId('withdraw_coin_id')->nullable()->default(null)->after('txn_id');
            $table->string('address')->nullable()->default(null)->after('withdraw_coin_id');
            $table->decimal('tokens', 40, 8)->nullable()->default(null)->after('address');
            $table->decimal('token_price', 20, 4)->nullable()->default(null)->after('tokens');
            $table->decimal('withdrawal_crypto_price', 40, 8)->nullable()->default(null)->after('token_price');
        });
    }
};
