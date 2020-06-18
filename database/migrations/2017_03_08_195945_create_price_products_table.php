<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customerid');
            $table->integer('orderid');
            $table->integer('productid');
            $table->string('title');
            $table->string('type');
            $table->integer('type_id');
            $table->integer('qty');
            $table->string('price');
            $table->string('price_type');
            $table->integer('price_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('price_products');
    }
}
