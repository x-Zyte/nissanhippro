<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->integer('branchid')->unsigned();
            $table->foreign('branchid')->references('id')->on('branchs');
            $table->integer('toemployeeid')->unsigned();
            $table->foreign('toemployeeid')->references('id')->on('employees');
            $table->integer('cancelreasontype')->comment('0:สัญญาไม่ผ่าน, 1:ไม่มีรถ, 2:อื่นๆ');
            $table->string('cancelreasondetails',100)->nullable();
            $table->text('remark');
            $table->decimal('refundamount', 10, 2);
            $table->dateTime('refunddate')->nullable();
            $table->string('refunddocno', 20)->nullable();
            $table->decimal('confiscateamount', 10, 2);
            $table->dateTime('confiscatedate')->nullable();
            $table->string('confiscatedocno', 20)->nullable();
            
            
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
