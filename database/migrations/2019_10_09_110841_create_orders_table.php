<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('address_id');
            $table->decimal('order_total');
            $table->tinyInteger('payment_type');
            $table->string('payment_type_number')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedTinyInteger('type_of_receipt');
            $table->tinyInteger('shipping_method');
            $table->decimal('shipping_cost')->default(0);
            $table->decimal('payment_cost')->default(0);
            $table->unsignedBigInteger('waitting_shelf_id')->nullable();
            $table->tinyInteger('discount_type')->nullable();
            $table->decimal('discount_amount')->nullable();
            //1=added,2=open for products/discount,3=on_watting_shelf,4=sent,5=completed,6=returned,7=canceled
            $table->unsignedTinyInteger('order_status');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('address_id')->references('address_id')->on('customer_addresses');
            $table->foreign('waitting_shelf_id')->references('shelf_id')->on('shelves');
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
