<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountingDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('carpaymentid')->unsigned();
            $table->foreign('carpaymentid')->references('id')->on('car_payments');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->integer('branchid')->unsigned();
            $table->foreign('branchid')->references('id')->on('branchs');
            $table->string('invoiceno', 50);
            $table->dateTime('date');
            $table->decimal('additionalopenbill', 10, 2);
            $table->integer('insurancefeereceiptcondition')->nullable()->comment('0:ชื่อลูกค้า, 1:ชื่อบริษัท');
            $table->integer('compulsorymotorinsurancefeereceiptcondition')->nullable()->comment('0:ชื่อลูกค้า, 1:ชื่อบริษัท');
            $table->integer('cashpledgereceiptbookno');
            $table->integer('cashpledgereceiptno');
            $table->dateTime('cashpledgereceiptdate');
            $table->dateTime('receivedcashfromfinacedate')->nullable();
            $table->integer('receivedcashfromfinacebankid')->nullable()->unsigned();
            $table->foreign('receivedcashfromfinacebankid')->references('id')->on('banks');

            $table->integer('createdby')->unsigned();
            $table->foreign('createdby')->references('id')->on('employees');
            $table->dateTime('createddate');
            $table->integer('modifiedby')->unsigned();
            $table->foreign('modifiedby')->references('id')->on('employees');
            $table->dateTime('modifieddate');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounting_details');
    }

}
