<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_color');
            $table->string('image');
            $table->string('ticket_type');
            $table->string('ticket_code');
            $table->string('display');
            $table->string('post');
            $table->text('link');
            $table->string('language');
            $table->text('description');
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
        Schema::drop('ticket_lists');
    }
}
