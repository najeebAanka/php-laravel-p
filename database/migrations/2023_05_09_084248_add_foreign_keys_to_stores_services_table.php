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
        Schema::table('stores_services', function (Blueprint $table) {
            $table->foreign(['product_id'], 'stores_services_ibfk_1')->references(['id'])->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['store_id'], 'stores_services_ibfk_3')->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['service_id'], 'stores_services_ibfk_2')->references(['id'])->on('services')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores_services', function (Blueprint $table) {
            $table->dropForeign('stores_services_ibfk_1');
            $table->dropForeign('stores_services_ibfk_3');
            $table->dropForeign('stores_services_ibfk_2');
        });
    }
};
