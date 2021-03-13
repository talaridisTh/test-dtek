<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShelvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelves', function (Blueprint $table) {
            $table->bigIncrements('shelf_id');
            $table->string('name');
            $table->bigInteger('warehouse_id')->unsigned();
            $table->boolean('is_product_shelf');
            $table->timestamps();
        });

        Schema::table('shelves', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shelves');
    }
}
