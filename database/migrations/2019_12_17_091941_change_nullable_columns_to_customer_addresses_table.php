<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableColumnsToCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('firstname')->nullable(true)->change();
            $table->string('lastname')->nullable(true)->change();
            $table->string('company')->nullable(true)->change();
            $table->string('address_1')->nullable(true)->change();
            $table->string('postcode')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('firstname')->nullable(false)->change();
            $table->string('lastname')->nullable(false)->change();
            $table->string('company')->nullable(false)->change();
            $table->string('address_1')->nullable(false)->change();
            $table->string('postcode')->nullable(false)->change();
        });
    }
}
