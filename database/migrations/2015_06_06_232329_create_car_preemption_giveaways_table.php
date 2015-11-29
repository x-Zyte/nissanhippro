<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarPreemptionGiveawaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_preemption_giveaways', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carpreemptionid')->unsigned();
            $table->foreign('carpreemptionid')->references('id')->on('car_preemptions');
            $table->integer('giveawayid')->unsigned();
            $table->foreign('giveawayid')->references('id')->on('giveaways');
            $table->boolean('free');
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
		Schema::drop('car_preemption_giveaways');
	}

}
