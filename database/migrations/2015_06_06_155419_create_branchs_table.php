<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('branchs', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name',100);
            $table->string('taxinvoicename',100);
            $table->string('taxpayerno',50);
            $table->text('address');
            $table->integer('districtid')->unsigned();
            $table->foreign('districtid')->references('id')->on('districts');
            $table->integer('amphurid')->unsigned();
            $table->foreign('amphurid')->references('id')->on('amphurs');
            $table->integer('provinceid')->unsigned();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->string('zipcode',5);
            $table->boolean('isheadquarter')->default(false);
            $table->integer('keyslot')->default(0);
            $table->boolean('active')->default(true);

            $table->integer('createdby')->unsigned();
            $table->dateTime('createddate');
            $table->integer('modifiedby')->unsigned();
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
		Schema::drop('branchs');
	}

}
