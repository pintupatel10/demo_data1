<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_volumes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('detailid');
            $table->integer('pricegroupid');
            $table->string('title');
            $table->string('title1');
            $table->string('type');
            $table->string('volume');
            $table->string('discount');
            $table->string('discount1');
            $table->text('date');
            $table->text('to');
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
        Schema::drop('ticket_volumes');
    }
}
