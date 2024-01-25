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
        Schema::create('order_items_services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_item_id')->index('fk_order_items_services_order_items1_idx');
            $table->integer('service_id')->index('fk_order_items_services_services1_idx');
            $table->double('price')->nullable();
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
        Schema::dropIfExists('order_items_services');
    }
};
