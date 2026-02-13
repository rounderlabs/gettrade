<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptApiTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypt_api_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('invoice_id');
            $table->string('crypto');
            $table->string('address_in');
            $table->string('address_out');
            $table->string('txn_in');
            $table->string('txn_out');
            $table->decimal('amount_in', 40, 8);
            $table->decimal('amount_out', 40, 8);
            $table->decimal('crypto_price', 40, 8);
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
        Schema::dropIfExists('crypt_api_transactions');
    }
}
