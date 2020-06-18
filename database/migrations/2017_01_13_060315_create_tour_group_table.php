<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('portrait_image');
            $table->string('landscape_image');
            $table->string('title');
            $table->string('language');
            $table->string('select_sentence');
            $table->text('description');
            $table->string('tour_list');
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
        Schema::drop('tour_groups');
    }
}
