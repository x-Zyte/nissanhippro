<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZipcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zipcodes', function(Blueprint $table)
		{
            $table->Increments('id');
            $table->string('districtcode',6);
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->integer('amphurid')->unsigned();
            $table->foreign('amphurid')->references('id')->on('amphurs');
            $table->integer('districtid')->unsigned();
            $table->foreign('districtid')->references('id')->on('districts');
            $table->string('code',5);

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
		Schema::drop('zipcodes');
	}

}
