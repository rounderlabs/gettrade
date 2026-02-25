<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ref_code')->unique();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('security_answer');
            $table->string('mobile');
            $table->string('sponsor_id');
            $table->string('profile_picture')->nullable();
            $table->enum('position', ['LEFT', 'RIGHT'])->nullable()->default(null);
            $table->unsignedInteger('country_id');
            $table->string('country')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('state')->nullable();
            $table->string('state_code', 10)->nullable();
            $table->string('city')->nullable();
            $table->timestamp('active_at')->nullable()->default(null);
            $table->tinyInteger('is_blocked')->default(0);
            $table->tinyInteger('kyc_verified')->default(0);
            $table->timestamp('welcome_seen_at')->nullable();
            $table->enum('welcome_mode', ['every_login', 'weekly', 'monthly', 'once', 'never'])->default('once');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
