<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_indices', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->string('product_index');
        });

        Schema::table('product_indices', function (Blueprint $table) {
            $table->unique(['product_id', 'product_index']);
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
        Schema::dropIfExists('product_indices');
    }
}
