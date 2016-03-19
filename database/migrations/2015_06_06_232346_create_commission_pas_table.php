<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionPAsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commission_pas', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('finacecompanyid')->unsigned();
            $table->foreign('finacecompanyid')->references('id')->on('finace_companies');
            $table->dateTime('effectivefrom');
            $table->dateTime('effectiveto');
            $table->decimal('finaceminimumprofit', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
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
		Schema::drop('commission_pas');
	}

}
