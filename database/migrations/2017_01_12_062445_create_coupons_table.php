<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tourlist_id');
            $table->string('title');
            $table->string('couponcode');
            $table->string('type');
            $table->date('earlydate');
            $table->date('orderdate_start');
            $table->date('orderdate_end');
            $table->string('discountby');
            $table->double('discount');
            $table->string('quota');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupons');
    }
}
