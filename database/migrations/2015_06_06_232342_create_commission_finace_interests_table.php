<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionFinaceInterestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commission_finace_interests', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('commissionfinaceid')->unsigned();
            $table->foreign('commissionfinaceid')->references('id')->on('commission_finaces');
            $table->float('downfrom');
            $table->float('downto');
            $table->decimal('installment24', 10, 2);
            $table->decimal('installment36', 10, 2);
            $table->decimal('installment48', 10, 2);
            $table->decimal('installment60', 10, 2);
            $table->decimal('installment72', 10, 2);
            $table->decimal('installment84', 10, 2);

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
		Schema::drop('commission_finace_interests');
	}

}
