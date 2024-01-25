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
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign(['order_id'], 'order_items_ibfk_2')->references(['id'])->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['cart_item_id'], 'fk_order_items_cart_items1')->references(['id'])->on('cart_items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['store_id'], 'fk_order_items_users1')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['product_id'], 'fk_order_items_products1')->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_ibfk_2');
            $table->dropForeign('fk_order_items_cart_items1');
            $table->dropForeign('fk_order_items_users1');
            $table->dropForeign('fk_order_items_products1');
        });
    }
};
