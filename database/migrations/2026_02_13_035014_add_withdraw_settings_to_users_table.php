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
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {

                $table->string('withdraw_mode')
                    ->nullable()
                    ->after('email');
                // INR or CRYPTO

                $table->string('withdraw_type')
                    ->nullable()
                    ->after('withdraw_mode');
                // MANUAL or AUTO
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['withdraw_mode', 'withdraw_type']);
        });
    }
};
