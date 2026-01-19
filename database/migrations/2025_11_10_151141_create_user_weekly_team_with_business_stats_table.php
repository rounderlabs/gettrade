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
        Schema::create('user_weekly_team_with_business_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('direct_active')->default(0);
            $table->integer('team_active')->default(0);
            $table->decimal('direct_business', 48, 2)->default(0);
            $table->decimal('team_business', 48, 2)->default(0);
            $table->tinyInteger('is_pool_active')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_weekly_team_with_business_stats');
    }
};
