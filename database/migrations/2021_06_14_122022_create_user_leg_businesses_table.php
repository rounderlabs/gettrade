<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLegBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_leg_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->bigInteger('direct_user_id');
            $table->decimal('amount', 40, 2)->nullable()->default('0');
            $table->timestamps();

            $table->unique(['user_id', 'direct_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_leg_businesses');
    }
}
