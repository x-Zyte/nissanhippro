<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountingDetailReceiveAndPaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_detail_receiveandpays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('accountingdetailid')->unsigned();
            $table->foreign('accountingdetailid')->references('id')->on('accounting_details');
            $table->integer('sectiontype')->comment('0:ไฟแนนซ์, 1:ลูกหนี้การค้า');
            $table->dateTime('date');
            $table->integer('type')->comment('0:รับเงิน, 1:จ่ายเงิน');
            $table->decimal('amount', 10, 2);
            $table->integer('accountgroup');
            $table->integer('bankid')->unsigned();
            $table->foreign('bankid')->references('id')->on('banks');
            $table->text('note');

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
        Schema::drop('accounting_detail_receiveandpays');
    }

}
