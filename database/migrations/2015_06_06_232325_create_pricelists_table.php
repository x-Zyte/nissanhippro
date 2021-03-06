<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricelistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pricelists', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('carmodelid')->unsigned();
            $table->foreign('carmodelid')->references('id')->on('car_models');
            $table->integer('carsubmodelid')->unsigned();
            $table->foreign('carsubmodelid')->references('id')->on('car_submodels');
            $table->dateTime('effectivefrom');
            $table->dateTime('effectiveto')->nullable();
            $table->decimal('sellingprice', 10, 2)->default(0)->comment('ราคาขายผู้จำหน่าย');
            $table->decimal('accessoriesprice', 10, 2)->default(0);
            $table->decimal('sellingpricewithaccessories', 10, 2)->comment('sellingprice + margin');
            $table->decimal('margin', 10, 2)->default(0);
            $table->decimal('ws50', 10, 2)->default(0);
            $table->decimal('dms', 10, 2)->default(0);
            $table->decimal('wholesale', 10, 2)->default(0);
            $table->decimal('execusiveinternal', 10, 2)->default(0);
            $table->decimal('execusivecampaing', 10, 2)->default(0);
            $table->decimal('execusivetotalmargincampaing', 10, 2)->comment('margin + execusiveinternal + execusivecampaing');
            $table->decimal('internal', 10, 2)->default(0);
            $table->decimal('campaing', 10, 2)->default(0);
            $table->decimal('totalmargincampaing', 10, 2)->comment('margin + totalcampaing');
            $table->string('promotion',100);
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
		Schema::drop('pricelists');
	}

}
