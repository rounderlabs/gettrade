<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoiIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roi_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->bigInteger('subscription_id');
            $table->date('closing_date');
            $table->decimal('amount', 40, 4);
            $table->decimal('income', 40, 8);
            $table->timestamps();

            $table->unique(['subscription_id', 'closing_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roi_incomes');
    }
}
