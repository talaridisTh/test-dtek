<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTubeTypeAndIsHeavyToNullableProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->smallInteger('tube_type')->tinyInteger('tube_type')->unsigned()->nullable()->change();
            $table->boolean('is_heavy')->nullable()->change();
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
            $table->smallInteger('tube_type')->tinyInteger('tube_type')->unsigned()->nullable(false)->change();
            $table->boolean('is_heavy')->nullable(false)->change();
        });
    }
}
