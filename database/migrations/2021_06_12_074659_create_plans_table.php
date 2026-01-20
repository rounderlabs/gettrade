<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 40, 2)->default('0.00');
            $table->decimal('monthly_roi_amount', 40, 4)->default('0');
            $table->decimal('tenure', 40, 2)->default(0);
            $table->decimal('principle_amount', 40, 2)->default('0.00');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('allow_to_user')->default(0);
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
        Schema::dropIfExists('plans');
    }
}
