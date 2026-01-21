<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->tinyInteger('is_blocked')->nullable()->default(0);
            $table->tinyInteger('direct')->nullable()->default(0);
            $table->tinyInteger('roi')->nullable()->default(0);
            $table->tinyInteger('roi_on_roi')->nullable()->default(0);
            $table->tinyInteger('rank')->nullable()->default(0);
            $table->tinyInteger('bonanza')->nullable()->default(0);
            $table->tinyInteger('reward')->nullable()->default(0);
            $table->tinyInteger('withdrawal')->nullable()->default(0);
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
        Schema::dropIfExists('user_stops');
    }
}
