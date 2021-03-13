<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) 
        {
            $table->bigIncrements('id');
            $table->bigInteger('manufacturer_id')->unsigned();

            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('model');
            $table->integer('notify_quantity');
            $table->string('description')->nullable();
            
            $table->string('width');
            $table->unsignedInteger('height_percentage');
            $table->string('radial_structure');
            $table->unsignedInteger('diameter');
            $table->unsignedTinyInteger('fitting_position');
            $table->string('speed_flag');
            $table->string('weight_flag'); 
            $table->unsignedTinyInteger('tube_type');
            $table->boolean('is_heavy');
            $table->text('comments')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique(['manufacturer_id', 'model', 'speed_flag', 'weight_flag']);
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
        Schema::dropIfExists('products');
    }
}
