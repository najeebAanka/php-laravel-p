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
        Schema::table('order_items_services', function (Blueprint $table) {
            $table->foreign(['order_item_id'], 'fk_order_items_services_order_items1')->references(['id'])->on('order_items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['service_id'], 'fk_order_items_services_services1')->references(['id'])->on('services')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items_services', function (Blueprint $table) {
            $table->dropForeign('fk_order_items_services_order_items1');
            $table->dropForeign('fk_order_items_services_services1');
        });
    }
};
