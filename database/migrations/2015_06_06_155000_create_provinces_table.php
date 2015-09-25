<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('provinces', function(Blueprint $table)
		{
            $table->Increments('id');
            $table->string('code',2);
            $table->string('name',100);
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
		Schema::drop('provinces');
	}

}
