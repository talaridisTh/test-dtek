<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->bigIncrements('product_price_id');
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('general_price');
            $table->decimal('wholesale_price')->nullable();
            $table->decimal('buying_price')->nullable();
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->unique('product_id');
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
        Schema::dropIfExists('product_prices');
    }
}
