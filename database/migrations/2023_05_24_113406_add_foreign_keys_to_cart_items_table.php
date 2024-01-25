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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign(['cart_id'], 'cart_items_ibfk_2')->references(['id'])->on('carts')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['product_id'], 'cart_items_ibfk_5')->references(['id'])->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['store_id'], 'cart_items_ibfk_4')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign('cart_items_ibfk_2');
            $table->dropForeign('cart_items_ibfk_5');
            $table->dropForeign('cart_items_ibfk_4');
        });
    }
};
