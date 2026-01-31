<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_jobs', function (Blueprint $table) {
            $table->id();

            // Human readable name for admin
            $table->string('name');

            // Artisan command name (whitelisted)
            $table->string('command');

            // Optional command parameters (JSON)
            $table->json('parameters')->nullable();

            /**
             * Schedule types:
             * once    → run only once at specific date & time
             * daily   → every day at given time
             * weekly  → specific weekdays at time
             * monthly → specific day of month at time
             */
            $table->enum('schedule_type', [
                'once',
                'daily',
                'weekly',
                'monthly',
            ]);

            // One-time execution date
            $table->date('run_date')->nullable();

            // Execution time (common for all types)
            $table->time('run_time');

            // For weekly jobs: [1,2,3] (Mon=1, Sun=7)
            $table->json('days_of_week')->nullable();

            // For monthly jobs: 1–31
            $table->unsignedTinyInteger('day_of_month')->nullable();

            // Tracking
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('last_failed_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamp('next_run_at')->nullable();

            // Status & audit
            $table->boolean('is_active')->default(true);

            $table->boolean('skip_holidays')->default(false);

            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index(['is_active', 'next_run_at']);
            $table->index('command');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_jobs');
    }
};
