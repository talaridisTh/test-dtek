<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_quantities', function (Blueprint $table) {
            $table->bigIncrements('product_quantity_id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->bigInteger('shelf_id')->unsigned();
            $table->unsignedSmallInteger('batch');
            $table->unsignedInteger('stock');
            $table->timestamps();

        });

        Schema::table('product_quantities', function (Blueprint $table) {
            $table->unique(['product_id', 'warehouse_id', 'shelf_id', 'batch']);
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('shelf_id')->references('shelf_id')->on('shelves');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_quantities');
    }
}
