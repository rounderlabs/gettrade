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
        Schema::create('user_rank_roi_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('user_roi_income_id')->index();
            $table->unsignedTinyInteger('rank'); // current achieved rank
            $table->decimal('income_percent', 5, 2); // e.g. 6.00
            $table->decimal('income', 18, 2);
            $table->timestamps();

            $table->unique(
                ['user_id', 'user_roi_income_id'],
                'uniq_rank_roi_per_day'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rank_roi_incomes');
    }
};
