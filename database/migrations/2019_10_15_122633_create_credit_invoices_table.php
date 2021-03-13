<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('credit_invoices', function (Blueprint $table) {
            $table->bigIncrements('credit_invoice_id');
            $table->unsignedBigInteger('order_id');
            $table->timestamp('invoice_date');
            $table->tinyInteger('invoice_status');
            $table->timestamps();
        });

        Schema::table('credit_invoices', function (Blueprint $table) {
            $table->foreign('order_id')->references('order_id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_invoices');
    }
}
