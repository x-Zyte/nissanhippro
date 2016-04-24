<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionFinacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commission_finaces', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('finacecompanyid')->unsigned();
            $table->foreign('finacecompanyid')->references('id')->on('finace_companies');
            $table->integer('interestratetypeid')->unsigned();
            $table->foreign('interestratetypeid')->references('id')->on('interestrate_types');
            $table->string('name',100);
            //$table->integer('useforcustomertype')->comment('0:ปกติ, 1:พิเศษ');
            $table->dateTime('effectivefrom');
            $table->dateTime('effectiveto');
            $table->decimal('finaceminimumprofit', 10, 2)->default(0);
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
		Schema::drop('commission_finaces');
	}

}
