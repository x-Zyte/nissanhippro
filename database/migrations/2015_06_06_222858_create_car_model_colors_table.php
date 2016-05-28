<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarModelColorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_model_colors', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carmodelid')->unsigned();
            $table->foreign('carmodelid')->references('id')->on('car_models');
            $table->integer('colorid')->unsigned();
            $table->foreign('colorid')->references('id')->on('colors');
            $table->decimal('price', 10, 2)->default(0);
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
		Schema::drop('car_model_colors');
	}

}
