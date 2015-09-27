<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarPreemptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_preemptions', function(Blueprint $table)
		{
            $table->increments('id');

            $table->integer('bookno');
            $table->integer('no');
            $table->dateTime('date');
            $table->integer('customerid')->unsigned();
            $table->foreign('customerid')->references('id')->on('customers');


            /*$table->foreign('branchid')->references('id')->on('branchs');
            $table->integer('carmodelid')->unsigned();
            $table->foreign('carmodelid')->references('id')->on('car_models');
            $table->integer('carsubmodelid')->unsigned();
            $table->foreign('carsubmodelid')->references('id')->on('car_submodels');
            $table->string('no',10);
            $table->dateTime('dodate');
            $table->dateTime('receiveddate');
            $table->string('engineno',50);
            $table->unique('engineno');
            $table->string('chassisno',50);
            $table->unique('chassisno');
            $table->integer('keyno')->unsigned();
            $table->string('colour',10);
            $table->integer('objective')->comment('0:ขาย, 1:ใช้งาน, 2:ทดสอบ');
            $table->integer('receivetype')->comment('0:ปกติ, 1:ประมูล');;
            $table->string('receivecarfilepath',2083)->nullable();
            $table->string('deliverycarfilepath',2083)->nullable();
            $table->boolean('issold')->default(false);
            $table->boolean('isregistered')->default(false);
            $table->boolean('isdelivered')->default(false);*/

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
		Schema::drop('car_preemptions');
	}

}
