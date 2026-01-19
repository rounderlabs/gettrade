<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUsdWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_usd_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->decimal('balance', 40)->default(0);
            $table->decimal('balance_on_hold', 40)->default(0);
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
        Schema::dropIfExists('user_usd_wallets');
    }
}
