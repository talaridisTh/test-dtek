<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnvironmentalTaxToOrderReturnedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_returned_products', function (Blueprint $table) {
            $table->decimal('environmental_tax')->default(0)->after('tax_class_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_returned_products', function (Blueprint $table) {
            $table->dropColumn('environmental_tax');
        });
    }
}
