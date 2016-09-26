<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_payments', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carpreemptionid')->unsigned();
            $table->foreign('carpreemptionid')->references('id')->on('car_preemptions');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->integer('branchid')->unsigned();
            $table->foreign('branchid')->references('id')->on('branchs');
            $table->dateTime('date');
            $table->integer('carid')->unsigned();
            $table->foreign('carid')->references('id')->on('cars');
            $table->decimal('amountperinstallment', 10, 2)->nullable();
            $table->decimal('insurancepremium', 10, 2)->nullable();
            $table->decimal('overrideopenbill', 10, 2)->nullable();
            $table->boolean('firstinstallmentpay')->default(false);
            $table->integer('installmentsinadvance')->default(0);
            $table->decimal('accessoriesfeeactuallypaid', 10, 2)->default(0);
            $table->decimal('accessoriesfeeincludeinyodjud', 10, 2)->default(0);
            $table->integer('insurancecompanyid')->unsigned()->nullable();
            $table->foreign('insurancecompanyid')->references('id')->on('insurance_companies');
            $table->decimal('capitalinsurance', 10, 2)->nullable();
            $table->integer('compulsorymotorinsurancecompanyid')->unsigned();
            $table->foreign('compulsorymotorinsurancecompanyid')->references('id')->on('insurance_companies');

            $table->decimal('buyerpay', 10, 2)->nullable();
            $table->decimal('overdue', 10, 2)->nullable();
            $table->decimal('overdueinterest', 10, 2)->nullable();
            $table->decimal('totaloverdue', 10, 2)->nullable();
            $table->integer('paybytype')->nullable()->comment('0:รถ, 1:เงินสด, 2:อื่นๆ');
            $table->string('paybyotherdetails',100)->nullable();
            $table->integer('overdueinstallments')->nullable();
            $table->dateTime('overdueinstallmentdate1')->nullable();
            $table->decimal('overdueinstallmentamount1', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount1', 10, 2)->nullable();
            $table->dateTime('overdueinstallmentdate2')->nullable();
            $table->decimal('overdueinstallmentamount2', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount2', 10, 2)->nullable();
            $table->dateTime('overdueinstallmentdate3')->nullable();
            $table->decimal('overdueinstallmentamount3', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount3', 10, 2)->nullable();
            $table->dateTime('overdueinstallmentdate4')->nullable();
            $table->decimal('overdueinstallmentamount4', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount4', 10, 2)->nullable();
            $table->dateTime('overdueinstallmentdate5')->nullable();
            $table->decimal('overdueinstallmentamount5', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount5', 10, 2)->nullable();
            $table->dateTime('overdueinstallmentdate6')->nullable();
            $table->decimal('overdueinstallmentamount6', 10, 2)->nullable();
            $table->decimal('overdueinterestinstallmentamount6', 10, 2)->nullable();
            $table->string('oldcarbuyername',100)->nullable();
            $table->decimal('oldcarpayamount', 10, 2)->nullable();
            $table->integer('oldcarpaytype')->nullable()->comment('0:เงินสด, 1:เช็ค, 2:โอน');
            $table->dateTime('oldcarpaydate')->nullable();
            $table->integer('payeeemployeeid')->unsigned()->nullable();
            $table->foreign('payeeemployeeid')->references('id')->on('employees');

            $table->integer('deliverycarbookno')->nullable();
            $table->integer('deliverycarno')->nullable();
            $table->dateTime('deliverycardate')->nullable();
            $table->string('deliverycarfilepath',2083)->nullable();
            $table->boolean('isdraft')->default(false);

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
		Schema::drop('car_payments');
	}

}
