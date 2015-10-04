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
            $table->integer('colorid1')->unsigned()->nullable();
            $table->foreign('colorid1')->references('id')->on('colors');
            $table->integer('colorid2')->unsigned()->nullable();
            $table->foreign('colorid2')->references('id')->on('colors');
            $table->integer('colorid3')->unsigned()->nullable();
            $table->foreign('colorid3')->references('id')->on('colors');
            $table->integer('buyingtrends')->comment('0:A-HOT(7 วัน), 1:B-HOT(15 วัน), 2:C-HOT(30 วัน), 3:เกิน 1 เดือน');
            $table->string('newcarthingsrequired',200)->nullable();
            $table->string('otherconsideration',200)->nullable();
            $table->string('oldcarspecifications',200)->nullable();
            $table->decimal('budgetpermonth',10,2)->nullable();
            $table->text('conditionproposed')->nullable();
            $table->decimal('conditionfinancedown',10,2)->nullable();
            $table->decimal('conditionfinanceinterest',10,2)->nullable();
            $table->integer('conditionfinanceperiod')->nullable();
            $table->dateTime('nextappointmentdate')->nullable();
            $table->string('remarks',200)->nullable();
            $table->boolean('active')->default(true);

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
