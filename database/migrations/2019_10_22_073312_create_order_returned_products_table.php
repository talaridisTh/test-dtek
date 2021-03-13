<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReturnedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_returned_products', function (Blueprint $table) {
            $table->bigIncrements('order_returned_product_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_quantity_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('shelf_id')->nullable();
            $table->unsignedTinyInteger('batch')->nullable();
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price');
            $table->decimal('product_tax');
            $table->unsignedBigInteger('tax_class_id')->nullable();
            $table->timestamps();
        });
        Schema::table('order_returned_products', function (Blueprint $table) {
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_quantity_id')->references('product_quantity_id')->on('product_quantities')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('set null');
            $table->foreign('shelf_id')->references('shelf_id')->on('shelves')->onDelete('set null');
            $table->foreign('tax_class_id')->references('tax_class_id')->on('tax_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_returned_products');
    }
}
