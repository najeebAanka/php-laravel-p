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
            // $table->foreign(['service_id'], 'cart_items_ibfk_1')->references(['id'])->on('stores_services');
            $table->foreign(['cart_id'], 'cart_items_ibfk_2')->references(['id'])->on('carts')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign('cart_items_ibfk_1');
            $table->dropForeign('cart_items_ibfk_2');
        });
    }
};
