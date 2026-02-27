<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('deposit_transaction_id')->unique();
            $table->string('crypto');
            $table->decimal('crypto_price', 40, 8);
            $table->decimal('deposit_amount', 40, 8);
            $table->decimal('deposit_amount_in_usd');
            $table->decimal('token_price', 40, 4);
            $table->decimal('token_qty', 40, 8);
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
        Schema::dropIfExists('purchases');
    }
}
