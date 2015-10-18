<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarPreemptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('car_preemptions', function(Blueprint $table)
		{
            $table->increments('id');

            $table->integer('bookno');
            $table->integer('no');
            $table->dateTime('date');
            $table->integer('bookingcustomerid')->unsigned();
            $table->foreign('bookingcustomerid')->references('id')->on('customers');
            $table->integer('carmodelid')->unsigned();
            $table->foreign('carmodelid')->references('id')->on('car_models');
            $table->integer('carsubmodelid')->unsigned();
            $table->foreign('carsubmodelid')->references('id')->on('car_submodels');
            $table->integer('colorid')->unsigned();
            $table->foreign('colorid')->references('id')->on('colors');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('subdown', 10, 2);
            $table->decimal('accessories', 10, 2);

            $table->integer('oldcarbrandid')->unsigned()->nullable();
            $table->foreign('oldcarbrandid')->references('id')->on('car_brands');
            $table->integer('oldcarmodelid')->unsigned()->nullable();
            $table->foreign('oldcarmodelid')->references('id')->on('car_models');
            $table->integer('oldcargear')->nullable()->comment(':เลือกเกียร์, 0:ธรรมดา/MT, 1:ออโต้/AT');
            $table->string('oldcarcolor',50)->nullable();
            $table->integer('oldcarenginesize')->nullable();
            $table->string('oldcarlicenseplate',50)->nullable();
            $table->integer('oldcaryear')->nullable();
            $table->decimal('oldcarprice', 10, 2)->nullable();
            $table->string('oldcarbuyername',100)->nullable();
            $table->text('oldcarother')->nullable();

            $table->decimal('cashpledge', 10, 2);
            $table->integer('purchasetype')->comment('0:เงินสด, 1:เช่าซื้อกับบริษัท');
            $table->string('leasingcompanyname',100)->nullable();
            $table->decimal('interest', 10, 2)->nullable();
            $table->decimal('down', 10, 2)->nullable();
            $table->integer('installments')->nullable();
            $table->decimal('cashpledgeredlabel', 10, 2);
            $table->integer('registrationtype')->comment('0:บุคคล, 1:นิติบุคคล');
            $table->decimal('registrationfee', 10, 2);
            $table->decimal('insurancefee', 10, 2);
            $table->decimal('compulsorymotorinsurancefee', 10, 2);
            $table->decimal('accessoriesfee', 10, 2);
            $table->decimal('otherfee', 10, 2);
            $table->dateTime('datewantgetcar');

            $table->integer('buyercustomerid')->unsigned();
            $table->foreign('buyercustomerid')->references('id')->on('customers');
            $table->integer('salesmanemployeeid')->unsigned();
            $table->foreign('salesmanemployeeid')->references('id')->on('employees');
            $table->integer('salesmanteamid')->unsigned()->nullable()->comment('ทีมที่อยู่ ณ ช่วงเวลานั้น');
            $table->foreign('salesmanteamid')->references('id')->on('teams');
            $table->integer('salesmanageremployeeid')->unsigned();
            $table->foreign('salesmanageremployeeid')->references('id')->on('employees');
            $table->integer('approversemployeeid')->unsigned();
            $table->foreign('approversemployeeid')->references('id')->on('employees');
            $table->dateTime('approvaldate');

            $table->boolean('place')->default(false);
            $table->boolean('showroom')->default(false);
            $table->boolean('booth')->default(false);
            $table->boolean('leaflet')->default(false);
            $table->boolean('businesscard')->default(false);
            $table->boolean('invitationcard')->default(false);
            $table->boolean('phone')->default(false);
            $table->boolean('signshowroom')->default(false);
            $table->boolean('spotradiowalkin')->default(false);
            $table->boolean('recommendedby')->default(false);
            $table->string('recommendedbyname',100)->nullable();
            $table->integer('recommendedbytype')->nullable()->comment('0:เพื่อน, 1:ญาติ, 2:ลูกค้าเก่า, 3:พนักงาน');
            $table->integer('customertype')->comment('0:ซื้อใหม่, 1:ซื้อทดแทน');
            $table->text('remark');

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
		Schema::drop('car_preemptions');
	}

}
