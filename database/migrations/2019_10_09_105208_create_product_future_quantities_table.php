<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFutureQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_future_quantities', function (Blueprint $table) {
            $table->bigIncrements('product_future_quantity_id');
            $table->timestamps();
            $table->bigInteger('product_id')->unsigned();
            $table->unsignedInteger('stock');
            $table->date('arrival_date');
        });

        Schema::table('product_future_quantities', function (Blueprint $table) {
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
        Schema::dropIfExists('product_future_quantities');
    }
}
