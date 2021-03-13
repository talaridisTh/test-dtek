<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUniqueConstraintInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);

            $table->dropUnique(['manufacturer_id', 'model', 'speed_flag', 'weight_flag']);

            $table->foreign('manufacturer_id')->references('manufacturer_id')->on('manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);

            $table->unique(['manufacturer_id', 'model', 'speed_flag', 'weight_flag']);

            $table->foreign('manufacturer_id')->references('manufacturer_id')->on('manufacturers');
        });
    }
}
