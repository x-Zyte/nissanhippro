<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionFinaceComsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commission_finace_coms', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('commissionfinaceid')->unsigned();
            $table->foreign('commissionfinaceid')->references('id')->on('commission_finaces');
            $table->decimal('interestcalculationbeginning', 10, 2);
            $table->decimal('interestcalculationending', 10, 2);
            $table->decimal('com', 10, 2);

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
		Schema::drop('commission_finace_coms');
	}

}
