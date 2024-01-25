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
        Schema::create('stores_services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('store_id')->nullable()->index('store_id');
            $table->integer('product_id')->nullable()->index('product_id');
            $table->integer('service_id')->nullable()->index('service_id');
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
        Schema::dropIfExists('stores_services');
    }
};
