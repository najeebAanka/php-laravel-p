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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('user_id')->nullable();
            $table->string('user_ip', 120)->nullable();
            $table->bigInteger('store_id')->nullable();
            $table->integer('cart_id');
            $table->string('name', 120)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 120)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('street', 120)->nullable();
            $table->string('building', 120)->nullable();
            $table->string('floor', 120)->nullable();
            $table->string('flat', 120)->nullable();
            $table->integer('address_id')->nullable();
            $table->double('vat')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->double('coupon_discount')->nullable();
            $table->double('discount')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('total')->nullable();
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('orders');
    }
};
