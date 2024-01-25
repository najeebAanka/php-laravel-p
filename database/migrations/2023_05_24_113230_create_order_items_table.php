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
        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id')->index('order_id');
            $table->integer('cart_id')->index('fk_order_items_carts1_idx');
            $table->bigInteger('store_id')->index('fk_order_items_users1_idx');
            $table->integer('product_id')->index('fk_order_items_products1_idx');
            $table->double('price');
            $table->integer('qty');
            $table->double('total')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 45)->default('pending');
            $table->integer('delivery_boy_id')->default(-1);
            $table->string('name', 120);
            $table->string('email', 120);
            $table->string('phone', 120);
            $table->string('country', 120);
            $table->string('city', 120);
            $table->string('street', 120);
            $table->string('building', 120);
            $table->string('floor', 120);
            $table->string('flat', 120);
            $table->integer('address_id')->nullable();
            $table->float('latitude', 10, 0);
            $table->float('longitude', 10, 0);
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
        Schema::dropIfExists('order_items');
    }
};
