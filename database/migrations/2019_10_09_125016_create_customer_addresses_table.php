<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('company')->nullable();
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('postcode');
            $table->unsignedInteger('country_id');
            $table->timestamps();
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('country_id')->references('country_id')->on('countries');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
