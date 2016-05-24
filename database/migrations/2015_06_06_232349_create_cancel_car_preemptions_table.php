<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelCarPreemptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cancel_car_preemptions', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carpreemptionid')->unsigned();
            $table->foreign('carpreemptionid')->references('id')->on('car_preemptions');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->integer('branchid')->unsigned()->nullable();
            $table->foreign('branchid')->references('id')->on('branchs');
            $table->integer('toemployeeid')->unsigned();
            $table->foreign('toemployeeid')->references('id')->on('employees');
            $table->integer('cancelreasontype')->comment('0:สัญญาไม่ผ่าน, 1:ไม่มีรถ, 2:อื่นๆ');
            $table->string('cancelreasondetails',100)->nullable();
            $table->text('remark');
            $table->integer('approvaltype')->comment('0:คืนเงิน, 1:ไม่คืนเงิน');
            $table->decimal('amountapproved', 10, 2)->nullable();

            $table->dateTime('salesmanemployeedate');

            $table->integer('accountemployeeid')->unsigned();
            $table->foreign('accountemployeeid')->references('id')->on('employees');
            $table->dateTime('accountemployeedate');

            $table->integer('financeemployeeid')->unsigned();
            $table->foreign('financeemployeeid')->references('id')->on('employees');
            $table->dateTime('financeemployeedate');

            $table->integer('approversemployeeid')->unsigned();
            $table->foreign('approversemployeeid')->references('id')->on('employees');
            $table->dateTime('approversemployeedate');

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
		Schema::drop('cancel_car_preemptions');
	}

}
