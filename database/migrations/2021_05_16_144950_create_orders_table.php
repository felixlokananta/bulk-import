<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->date('order_date');
            $table->date('shipped_date');
            $table->enum('order_status', [config('order.ORDER_STATUS.pending'), config('order.ORDER_STATUS.awaitingPayment'), config('order.ORDER_STATUS.awaitingFulfillment'), config('order.ORDER_STATUS.awaitingShipment'), config('order.ORDER_STATUS.awaitingPickup'), config('order.ORDER_STATUS.partiallyShipped'), config('order.ORDER_STATUS.completed'), config('order.ORDER_STATUS.shipped'), config('order.ORDER_STATUS.cancelled'), config('order.ORDER_STATUS.declined'), config('order.ORDER_STATUS.refunded'), config('order.ORDER_STATUS.disputed')]);
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
        Schema::dropIfExists('orders');
    }
}
