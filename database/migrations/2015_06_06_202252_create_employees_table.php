<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('provinceid')->unsigned()->nullable();
            $table->foreign('provinceid')->references('id')->on('provinces');
            $table->string('title',10);
            $table->string('firstname',50);
            $table->string('lastname',50);
            $table->string('code',50);
            $table->unique('code');
            $table->dateTime('workingstartdate')->nullable();
            $table->dateTime('workingenddate')->nullable();
            $table->string('username',50)->nullable();
            $table->unique('username');
            $table->string('password',100)->nullable();
            $table->string('email',100)->nullable();
            $table->unique('email');
            $table->dateTime('loginstartdate')->nullable();
            $table->dateTime('loginenddate')->nullable();
            $table->string('phone',20)->nullable();
            $table->boolean('isadmin')->default(false);
            $table->integer('branchid')->unsigned()->nullable();
            $table->foreign('branchid')->references('id')->on('branchs');
            $table->integer('departmentid')->unsigned()->nullable();
            $table->foreign('departmentid')->references('id')->on('departments');
            $table->integer('teamid')->unsigned()->nullable();
            $table->foreign('teamid')->references('id')->on('teams');
            $table->boolean('candeletedata')->default(false);
            $table->text('remarks')->nullable();
            $table->boolean('active')->default(true);
            $table->rememberToken();

            $table->integer('createdby')->unsigned()->nullable();
            $table->dateTime('createddate');
            $table->integer('modifiedby')->unsigned()->nullable();
            $table->dateTime('modifieddate');

            $table->engine = 'InnoDB';
		});

        Schema::table('logs', function($table)
        {
            $table->foreign('employeeid')->references('id')->on('employees');
        });
        Schema::table('branchs', function($table)
        {
            $table->foreign('createdby')->references('id')->on('employees');
            $table->foreign('modifiedby')->references('id')->on('employees');
        });
        Schema::table('departments', function($table)
        {
            $table->foreign('createdby')->references('id')->on('employees');
            $table->foreign('modifiedby')->references('id')->on('employees');
        });
        Schema::table('teams', function($table)
        {
            $table->foreign('createdby')->references('id')->on('employees');
            $table->foreign('modifiedby')->references('id')->on('employees');
        });
        Schema::table('employees', function($table)
        {
            $table->foreign('createdby')->references('id')->on('employees');
            $table->foreign('modifiedby')->references('id')->on('employees');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
