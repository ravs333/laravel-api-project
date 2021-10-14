<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary('id');
            $table->string('payment_method');
            $table->string('shipping_method');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->unsignedBigInteger('billing_address_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->double('total', 8, 2);
            $table->timestamps();
        });

        Schema::table('order', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customer');
            $table->foreign('billing_address_id')->references('id')->on('address');
            $table->foreign('shipping_address_id')->references('id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
