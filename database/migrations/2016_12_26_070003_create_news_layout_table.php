<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsLayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_layouts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu_name');
            $table->string('title');
            $table->string('image');
            $table->string('language');
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
        Schema::drop('news_layouts');
    }
}
