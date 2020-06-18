<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('language');
            $table->string('image_upload');
            $table->string('image_preview');
            $table->string('image_position');
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
        Schema::drop('home_posts');
    }
}
