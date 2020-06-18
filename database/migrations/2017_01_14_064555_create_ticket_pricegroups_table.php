<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketPricegroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_pricegroups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('detailid');
            $table->string('title');
            $table->text('description');
            $table->double('servicecharge');
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
        Schema::drop('ticket_pricegroups');
    }
}
