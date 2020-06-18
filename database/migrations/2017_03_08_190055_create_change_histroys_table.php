<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeHistroysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_histroys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customerid');
            $table->integer('orderid');
            $table->integer('productid');
            $table->string('change_from');
            $table->string('change_to');
            $table->string('change_by');
            $table->text('remark1');
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
        Schema::drop('change_histroys');
    }
}
