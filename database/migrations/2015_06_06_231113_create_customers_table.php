<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->string('title',10);
            $table->string('firstname',50);
            $table->string('lastname',50)->nullable();
            $table->string('phone1',20);
            $table->string('phone2',20)->nullable();
            $table->integer('occupationid')->unsigned()->nullable();
            $table->foreign('occupationid')->references('id')->on('occupations');
            $table->text('address')->nullable();
            $table->integer('districtid')->unsigned()->nullable();
            $table->foreign('districtid')->references('id')->on('districts');
            $table->integer('amphurid')->unsigned()->nullable();
            $table->foreign('amphurid')->references('id')->on('amphurs');
            $table->integer('addprovinceid')->unsigned()->nullable();
            $table->foreign('addprovinceid')->references('id')->on('provinces');
            $table->string('zipcode',5)->nullable();
            $table->string('email',100)->nullable();



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
		Schema::drop('customers');
	}

}
