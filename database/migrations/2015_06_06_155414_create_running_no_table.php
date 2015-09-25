<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunningNoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('running_no', function(Blueprint $table)
		{
            $table->string('prefix',10)->primary();
            $table->unique('prefix');
            $table->integer('no');
            $table->integer('subno');

            $table->engine = 'InnoDB';
		});

        DB::unprepared("CREATE PROCEDURE `running_number`(IN `pf` VARCHAR(10), IN `hassubno` INT)
            BEGIN
            START TRANSACTION;
            insert into running_no (prefix, no, subno) values(pf,1,hassubno) on duplicate key update no = if(hassubno = 1, no+if(subno = 0,1,0), no+1),subno = if(hassubno = 1, subno+1, 0);
            select if(subno > 0, concat(no,'/',subno), concat(no,'')) as no from running_no where prefix = pf COLLATE utf8_unicode_ci;
            COMMIT;
            END");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('running_no');
	}

}
