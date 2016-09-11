<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRedlabelhistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('redlabelhistories', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('redlabelid')->unsigned();
            $table->foreign('redlabelid')->references('id')->on('redlabels');
            $table->dateTime('issuedate');
            $table->integer('carpreemptionid')->unsigned();
            $table->foreign('carpreemptionid')->references('id')->on('car_preemptions');
            $table->dateTime('returndate')->nullable();
			$table->dateTime('returncashpledgedate')->nullable();
            $table->text('remarks')->nullable();

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
		Schema::drop('redlabelhistories');
	}

}
