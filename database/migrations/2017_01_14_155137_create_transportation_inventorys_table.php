<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportationInventorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportation_inventorys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('detailid');
            $table->integer('pricegroupid');
            $table->string('title');
            $table->string('dquota');
            $table->string('status')->default('active');
            $table->integer('displayorder');
            $table->softDeletes();
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
        Schema::drop('transportation_inventorys');
    }
}
