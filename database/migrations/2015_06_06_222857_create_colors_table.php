<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('colors', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('code',50);
            $table->string('name',50);
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
		Schema::drop('colors');
	}

}
