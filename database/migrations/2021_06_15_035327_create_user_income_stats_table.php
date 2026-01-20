<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIncomeStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_income_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->decimal('total', 40, 4)->default('0');
            $table->decimal('direct', 40, 4)->default('0');
            $table->decimal('level', 40, 4)->default('0');
            $table->decimal('roi', 40, 4)->default('0');
            $table->decimal('roi_on_roi', 40, 4)->default('0');
            $table->decimal('rank', 40, 4)->default('0');
            $table->decimal('bonanza', 40, 4)->default('0');
            $table->decimal('reward', 40, 4)->default('0');
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
        Schema::dropIfExists('user_income_stats');
    }
}
