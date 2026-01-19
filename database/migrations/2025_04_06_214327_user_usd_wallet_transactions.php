<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserUsdWalletTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_usd_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('transaction_type', ['debit', 'credit']);
            $table->decimal('amount_in_usd', 40, 2);
            $table->decimal('last_amount', 40, 2);
            $table->string('summary')->nullable()->default(null);
            $table->enum('status', ['success', 'pending', 'fail', 'hold']);
            $table->dateTime('txn_time');
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
        Schema::dropIfExists('user_usd_wallet_transactions');
    }
}
