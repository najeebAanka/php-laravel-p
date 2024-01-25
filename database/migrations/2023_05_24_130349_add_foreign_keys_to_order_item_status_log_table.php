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
        Schema::table('order_item_status_log', function (Blueprint $table) {
            $table->foreign(['order_item_id'], 'fk_order_item_status_log_order_items1')->references(['id'])->on('order_items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_item_status_log', function (Blueprint $table) {
            $table->dropForeign('fk_order_item_status_log_order_items1');
        });
    }
};
