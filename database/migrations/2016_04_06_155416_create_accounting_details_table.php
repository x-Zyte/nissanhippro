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
            $table->dateTime('payinadvanceamountreimbursementdate')->nullable();
            $table->string('payinadvanceamountreimbursementdocno', 20)->nullable();
            $table->integer('note1insurancefeereceiptcondition')->nullable()->comment('0:ชื่อลูกค้า, 1:ชื่อบริษัท');
            $table->integer('note1compulsorymotorinsurancefeereceiptcondition')->nullable()->comment('0:ชื่อลูกค้า, 1:ชื่อบริษัท');
            $table->integer('insurancefeepayment')->nullable()->comment('0:หักจากยอดจัด, 1:จ่ายต่างหาก');
            $table->dateTime('insurancefeepaidseparatelydate')->nullable();
            $table->decimal('insurancepremiumnet', 10, 2)->nullable();
            $table->decimal('insurancepremiumcom', 10, 2)->nullable();
            $table->decimal('insurancefeepaidseparatelytotal', 10, 2)->nullable();
            $table->integer('compulsorymotorinsurancefeepayment')->nullable()->comment('0:หักจากยอดจัด, 1:จ่ายต่างหาก');
            $table->dateTime('compulsorymotorinsurancefeepaidseparatelydate')->nullable();
            $table->decimal('compulsorymotorinsurancepremiumnet', 10, 2)->nullable();
            $table->decimal('compulsorymotorinsurancepremiumcom', 10, 2)->nullable();
            $table->decimal('compulsorymotorinsurancefeepaidseparatelytotal', 10, 2)->nullable();
            $table->integer('cashpledgeredlabelreceiptbookno')->nullable();
            $table->integer('cashpledgeredlabelreceiptno')->nullable();
            $table->dateTime('cashpledgeredlabelreceiptdate')->nullable();
            $table->integer('cashpledgereceiptbookno');
            $table->integer('cashpledgereceiptno');
            $table->dateTime('cashpledgereceiptdate');
            $table->decimal('incasefinacecomfinamount', 10, 2)->nullable();
            $table->decimal('incasefinacecomfinvat', 10, 2)->nullable();
            $table->decimal('incasefinacecomfinamountwithvat', 10, 2)->nullable();
            $table->decimal('incasefinacecomfinwhtax', 10, 2)->nullable();
            $table->decimal('incasefinacecomfintotal', 10, 2)->nullable();
            $table->decimal('systemcalincasefinacecomfinamount', 10, 2)->nullable();
            $table->decimal('systemcalincasefinacecomfinvat', 10, 2)->nullable();
            $table->decimal('systemcalincasefinacecomfinamountwithvat', 10, 2)->nullable();
            $table->decimal('systemcalincasefinacecomfinwhtax', 10, 2)->nullable();
            $table->decimal('systemcalincasefinacecomfintotal', 10, 2)->nullable();
            $table->decimal('receivedcashfromfinacenet', 10, 2)->nullable();
            $table->decimal('receivedcashfromfinacenetshort', 10, 2)->nullable();
            $table->decimal('receivedcashfromfinacenetover', 10, 2)->nullable();
            $table->decimal('oldcarcomamount', 10, 2);
            $table->decimal('adj', 10, 2);
            $table->decimal('totalaccount1', 10, 2);
            $table->decimal('totalaccount1short', 10, 2);
            $table->decimal('totalaccount1over', 10, 2);
            $table->decimal('totalaccount2', 10, 2);
            $table->decimal('totalaccount2short', 10, 2);
            $table->decimal('totalaccount2over', 10, 2);

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
