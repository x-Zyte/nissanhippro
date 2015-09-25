<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmphursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('amphurs', function(Blueprint $table)
		{
            $table->Increments('id');
            $table->string('code',4);
            $table->string('name',100);
            $table->integer('geoid')->unsigned();
            $table->foreign('geoid')->references('id')->on('geographies');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');

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
		Schema::drop('amphurs');
	}

}
