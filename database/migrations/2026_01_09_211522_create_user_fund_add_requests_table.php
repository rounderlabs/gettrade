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
        Schema::create('user_fund_add_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('payment_type',['Cash', 'Cheque', 'NEFT', 'RTGS', 'UPI'])->default('UPI');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('payment_proof')->nullable();
            $table->string('txn_id')->nullable();
            $table->string('comment')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_fund_add_requests');
    }
};
