<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('txn_id')->nullable()->default(null);
            $table->string('bank_name')->nullable()->default(null);
            $table->string('bank_ifsc')->nullable()->default(null);
            $table->string('bank_account_no')->nullable()->default(null);
            $table->string('upi_id')->nullable()->default(null);
            $table->string('upi_number')->nullable()->default(null);
            $table->decimal('fees', 40, 2);
            $table->decimal('amount', 40, 2);
            $table->decimal('receivable_amount', 40, 2);
            $table->enum('status', ['pending', 'processing', 'failed', 'success']);
            $table->enum('type', ['level', 'dividend', 'maturity']);
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
        Schema::dropIfExists('withdrawal_histories');
    }
}
