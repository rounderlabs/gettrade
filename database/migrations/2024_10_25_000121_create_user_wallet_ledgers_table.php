<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallet_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('wallet_type',['USDT Wallet', 'Income Wallet'])->nullable();
            $table->decimal('last_amount', 40 , 8)->nullable();
            $table->string('currency')->default('INR');
            $table->enum('txn_type', ['Debit', 'Credit'])->default('Debit');
            $table->decimal('amount', 40 , 8)->nullable();
            $table->decimal('new_amount', 40 , 8)->nullable();
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('user_wallet_ledgers');
    }
}
