<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('txn_id')->nullable()->default(null);
            $table->foreignId('withdraw_coin_id')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->decimal('tokens', 40, 8)->nullable()->default(null);
            $table->decimal('token_price', 20, 4)->nullable()->default(null);
            $table->decimal('withdrawal_crypto_price', 40, 8)->nullable()->default(null);
            $table->decimal('fees', 40, 8);
            $table->decimal('amount', 40, 8);
            $table->decimal('receivable_amount', 40, 8);
            $table->enum('status', ['pending', 'processing', 'failed', 'success']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal_temps');
    }
}
