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
            $table->foreign(['service_id'], 'order_items_ibfk_1')->references(['id'])->on('stores_services')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['order_id'], 'order_items_ibfk_2')->references(['id'])->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign('order_items_ibfk_1');
            $table->dropForeign('order_items_ibfk_2');
        });
    }
};
