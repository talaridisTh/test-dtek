<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table)
        {
            $table->bigIncrements('customer_id');
            $table->bigInteger('customer_group_id');
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_kind')->nullable();
            $table->string('email')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreign('customer_group_id')->references('customer_group_id')->on('customer_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
