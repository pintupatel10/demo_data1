<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactedRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacted_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('follow_up');
            $table->string('title');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('email');
            $table->string('telephone');
            $table->text('address');
            $table->string('fax_no');
            $table->string('country');
            $table->text('message');
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
        Schema::drop('contacted_records');
    }
}
