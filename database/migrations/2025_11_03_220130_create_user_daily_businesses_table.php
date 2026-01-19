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
        Schema::create('user_daily_businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('business_date');
            $table->decimal('team_business', 15, 2)->default(0);
            $table->decimal('direct_business', 15, 2)->default(0);
            $table->decimal('self_business', 15, 2)->default(0);
            $table->tinyInteger('is_achieved')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'business_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_businesses');
    }
};
