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
        Schema::create('working_withdrawal_history_closings', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date')->unique();
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_withdrawal_history_closings');
    }
};
