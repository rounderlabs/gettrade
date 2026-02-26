<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedBigInteger('deposit_transactionable_id');
            $table->string('deposit_transactionable_type');
            $table->string('address');
            $table->string('currency_symbol');
            $table->decimal('currency_price', 40, 8);
            $table->decimal('amount', 40, 8);
            $table->string('quote_currency');
            $table->decimal('quote_amount', 40, 8);
            $table->dateTime('txn_time');
            $table->timestamps();

            $table->unique(['deposit_transactionable_id', 'deposit_transactionable_type'], 'deposit_morph_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposit_transactions');
    }
}
