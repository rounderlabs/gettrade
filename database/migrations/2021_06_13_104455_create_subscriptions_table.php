<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('plan_id');
            $table->foreignId('user_usd_wallet_transaction_id')->default(0);
            $table->decimal('amount', 40, 2);
            $table->date('tenure_end_date');
            $table->date('maturity_date');
            $table->date('lock_end_date');
            $table->decimal('earned_so_far', 40, 4);
            $table->decimal('working_earned_so_far', 40, 4);
            $table->decimal('passive_earned_so_far', 40, 4);
            $table->decimal('max_income_limit', 40, 4);
            $table->decimal('working_income_limit', 40, 4);
            $table->decimal('passive_income_limit', 40, 4);
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('subscriptions');
    }
}
