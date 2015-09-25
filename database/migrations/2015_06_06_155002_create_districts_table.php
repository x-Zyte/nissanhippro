<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('districts', function(Blueprint $table)
		{
            $table->Increments('id');
            $table->string('code',6);
            $table->string('name',100);
            $table->integer('amphurid')->unsigned();
            $table->foreign('amphurid')->references('id')->on('amphurs');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->integer('geoid')->unsigned();
            $table->foreign('geoid')->references('id')->on('geographies');

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
		Schema::drop('districts');
	}

}
