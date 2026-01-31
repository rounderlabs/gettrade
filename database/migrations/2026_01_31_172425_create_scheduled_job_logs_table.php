<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scheduled_job_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_job_id')->constrained()->cascadeOnDelete();
            $table->string('run_type')->comment('manual|scheduled');
            $table->enum('status', ['success', 'failed']);
            $table->text('output')->nullable();
            $table->text('error')->nullable();
            $table->integer('execution_time_ms')->nullable();
            $table->timestamp('ran_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_job_logs');
    }
};
