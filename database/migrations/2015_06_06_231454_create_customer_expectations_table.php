<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerExpectationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_expectations', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('customerid')->unsigned();
            $table->foreign('customerid')->references('id')->on('customers');
            $table->integer('employeeid')->unsigned();
            $table->foreign('employeeid')->references('id')->on('employees');
            $table->dateTime('date');
            $table->integer('carmodelid1')->unsigned()->nullable();
            $table->foreign('carmodelid1')->references('id')->on('car_models');
            $table->integer('carmodelid2')->unsigned()->nullable();
            $table->foreign('carmodelid2')->references('id')->on('car_models');
            $table->integer('carmodelid3')->unsigned()->nullable();
            $table->foreign('carmodelid3')->references('id')->on('car_models');
            $table->text('details');

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
		Schema::drop('customer_expectations');
	}

}
