<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableColumnsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable(true)->change();
            $table->string('mobile')->nullable(true)->change();
            $table->string('fax')->nullable(true)->change();
            $table->string('tax_id')->nullable(true)->change();
            $table->string('tax_office')->nullable(true)->change();
            $table->string('company_name')->nullable(true)->change();
            $table->string('company_kind')->nullable(true)->change();
            $table->string('email')->nullable(true)->change();
            $table->string('comments')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->string('mobile')->nullable(false)->change();
            $table->string('fax')->nullable(false)->change();
            $table->string('tax_id')->nullable(false)->change();
            $table->string('tax_office')->nullable(false)->change();
            $table->string('company_name')->nullable(false)->change();
            $table->string('company_kind')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('comments')->nullable(false)->change();
        });
    }
}
