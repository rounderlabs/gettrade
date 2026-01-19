<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDailyIncomeStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_daily_income_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('income_date');
            $table->decimal('income_amount', 8, 2)->default(0);
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
        Schema::dropIfExists('user_daily_income_stats');
    }
}
