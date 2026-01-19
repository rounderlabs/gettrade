<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_reward_ranks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->integer('rank');
            $table->tinyInteger('is_achieved')->default(0);
            $table->tinyInteger('is_credited')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reward_ranks');
    }
};
