<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customerid');
            $table->integer('orderid');
            $table->string('product_name');
            $table->string('subproduct_no');
            $table->string('voucher_no');
            $table->string('e_ticket_no');
            $table->string('live_ticket_no');
            $table->string('reference_no');
            $table->string('post');
            $table->string('date');
            $table->string('time');
            $table->string('class');
            $table->string('hotel_stay');
            $table->string('title');
            $table->string('fname');
            $table->string('lname');
            $table->string('passport');
            $table->string('email');
            $table->string('pnumber');
            $table->string('snumber');
            $table->string('promocode');
            $table->string('remark');
            $table->string('adult');
            $table->string('children');
            $table->string('total');
            $table->string('type');
            $table->string('subtotal');
            $table->string('discount');
            $table->string('status')->default('Pending');
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
        Schema::drop('transaction_products');
    }
}
