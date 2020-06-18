<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('email');
            $table->string('telephone');
            $table->string('address');
            $table->string('fax_no');
            $table->string('country');
            $table->string('message');
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
        Schema::drop('hotel_contacts');
    }
}
