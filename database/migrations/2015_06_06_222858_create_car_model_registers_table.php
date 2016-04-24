<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarModelRegistersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_model_registers', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carmodelid')->unsigned();
            $table->foreign('carmodelid')->references('id')->on('car_models');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->decimal('individualregistercost', 10, 2);
            $table->decimal('implementingindividualregistercost', 10, 2);
            $table->decimal('companyregistercost', 10, 2);
            $table->decimal('implementingcompanyregistercost', 10, 2);
            $table->decimal('governmentregistercost', 10, 2);
            $table->decimal('implementinggovernmentregistercost', 10, 2);

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
		Schema::drop('car_model_registers');
	}

}
