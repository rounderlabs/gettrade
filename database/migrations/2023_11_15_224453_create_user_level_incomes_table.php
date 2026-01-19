<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLevelIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_level_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('income_date');
            $table->decimal('amount', 40, 2)->default('0');
            $table->timestamps();

            $table->unique(['user_id', 'income_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_level_incomes');
    }
}
