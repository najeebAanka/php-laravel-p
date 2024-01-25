<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->string('name_ar');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('user_type')->default('customer');
            $table->string('otp')->nullable();
            $table->tinyInteger('isEmailVerified')->default(0);
            $table->tinyInteger('isPhoneVerified')->default(0);
            $table->bigInteger('phone')->nullable();
            $table->string('forgot_password_token')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->text('provider_access_token')->nullable();
            $table->integer('current_order')->default(-1);
            $table->string('status', 45)->default('active');
            $table->float('latitude', 10, 0)->default(25.2048);
            $table->float('longitude', 10, 0)->default(55.2708);
            $table->string('image', 120)->nullable();
            $table->integer('receive_notification')->default(0);
            $table->integer('delivery_fee')->default(8);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
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
};
