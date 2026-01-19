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
