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
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('kyc_id');

            // Identity
            $table->string('aadhaar_number')->nullable();
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();

            // PAN
            $table->string('pan_number')->nullable();
            $table->string('pan_file')->nullable();

            // Bank
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('cancel_cheque')->nullable();

            //UPI
            $table->string('upi_id')->nullable();
            $table->string('upi_number')->nullable();

            // Status
            $table->enum('status', ['submitted', 'approved', 'rejected'])->default('submitted');
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
