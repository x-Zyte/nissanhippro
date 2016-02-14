@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มการชำระเงินใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขการชำระเงิน '.$carpreemption->bookno.'/'.$carpreemption->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลการชำระเงิน '.$carpreemption->bookno.'/'.$carpreemption->no)
@endif

@section('menu-selling-class','active hsub open')
@section('menu-carpayment-class','active')
@section('pathPrefix','')

@section('content')

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> เพิ่มการชำระเงินใหม่</h3>
    @elseif($oper == 'edit')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> แก้ไขการชำระเงิน</h3>
    @elseif($oper == 'view')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> ดูข้อมูลการชำระเงิน</h3>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>ขออภัย!</strong> มีปัญหาบางอย่างกับการป้อนข้อมูลของคุณ<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($oper == 'new')
        {!! Form::open(array('url' => 'carpayment/save', 'id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @elseif($oper == 'edit')
        {!! Form::model($carpayment, array('url' => 'carpayment/save', 'id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form')) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($carpayment, array('id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

        <!-- Detail 1 -->
        <div class="row">
            <div class="col-xs-1 col-sm-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">ข้อมูลส่วนที่ 1</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>

                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <div class="form-group" style="padding-top:5px;">
                                    <label class="col-sm-1 control-label no-padding-right" for="customer">ชื่อลูกค้า</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="customer" data-placeholder="โปรดเลือกชื่อลูกค้า ...">
                                                <option value="">  </option>
                                                <option value="customer1">นายเสก โลโซ</option>
                                                <option value="customer2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="customer3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="date">วันที่</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input class="form-control date-picker" id="date" type="text" data-date-format="dd-mm-yyyy" />
																<span class="input-group-addon">
																	<i class="fa fa-calendar bigger-110"></i>
																</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="models">รถนิสสันรุ่น</label>
                                    <div class="col-sm-3">
                                        <select id="models" data-placeholder="โปรดเลือกรุ่น ...">
                                            <option value="">  </option>
                                            <option value="saleman1">รุ่น A </option>
                                            <option value="saleman2">รุ่น B</option>
                                            <option value="saleman3">รุ่น C</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="color">สี</label>
                                    <div class="col-sm-3">
                                        <select id="color" data-placeholder="โปรดเลือกสี ...">
                                            <option value="">  </option>
                                            <option value="saleman1">K21</option>
                                            <option value="saleman2">B32</option>
                                            <option value="saleman3">C49</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="price">ราคาขายจริง</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="price" placeholder="บาท" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="body">ตัวถัง</label>
                                    <div class="col-sm-3">
                                        <select id="body" data-placeholder="โปรดเลือกหมายเลขตัวถัง ...">
                                            <option value="">  </option>
                                            <option value="saleman1">ตัวถัง A </option>
                                            <option value="saleman2">ตัวถัง B</option>
                                            <option value="saleman3">ตัวถัง C</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="enginenumber">เลขเครื่อง</label>
                                    <div class="col-sm-3">
                                        <select id="enginenumber" data-placeholder="โปรดเลือกหมายเลขเครื่อง ...">
                                            <option value="">  </option>
                                            <option value="saleman1">เลขเครื่อง A</option>
                                            <option value="saleman2">เลขเครื่อง B</option>
                                            <option value="saleman3">เลขเครื่อง C</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-11">

                                        <label>
                                            <input name="paymenttype" type="radio" class="ace" />
                                            <span class="lbl">  สด</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input name="paymenttype" type="radio" class="ace" />
                                            <span class="lbl"> ผ่อน</span>&nbsp;&nbsp;
                                            <input type="number" id="numberinstallments" min="0" style="width:80px;" placeholder="งวด" />
                                        </label>
                                        <label>
                                            <span class="lbl"> งวด ๆ ละ</span>&nbsp;
                                            <input type="number" id="interestpertime" min="0" style="width:120px;" placeholder="บาท" />
                                        </label>
                                        <label>
                                            <span class="lbl"> บาท ดอกเบี้ย</span>&nbsp;
                                            <input type="number" id="interest" min="0" max="100" style="width:60px;" placeholder="%" /> %
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="financeofficer">ไฟแนนซ์ </label>
                                    <div class="col-sm-3">
                                        <select id="financeofficer" data-placeholder="โปรดเลือกไฟแนนซ์ (จนท.) ...">
                                            <option value="">  </option>
                                            <option value="finan1">ไฟแนนซ์ A</option>
                                            <option value="finan2">ไฟแนนซ์ B</option>
                                            <option value="finan3">ไฟแนนซ์ C</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>


        <!-- Detail 2 -->
        <div class="row">
            <div class="col-sm-12 ">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">ข้อมูลส่วนที่ 2</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <div class="form-group" style="padding-top:10px; padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ชำระเงินค่าดาวน์ / ค่ารถ</label>
                                        <label> ( ยอดจัด</label>
                                        <input type="number" min="0" id="financingamount" placeholder="บาท" style="width:120px;" />
                                        <label> บาท )</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="downcarpayment" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ชำระงวดแรก</label>
                                        <label> ( จำนวนงวด</label>
                                        <input type="number" min="0" id="time" placeholder="งวด" style="width:50px;" />
                                        <label> รวมเบี้ยประกัน</label>
                                        <input type="number" min="0" id="totalplusinsurance" placeholder="บาท" style="width:100px;" />
                                        <label> บาท )</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="firstinstallmentpayment " placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> อุปกรณ์รวม</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="totalequipment" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> เบี้ยประกันชั้น 1,3</label>
                                        <label style="width:45px;"> บริษัท</label>
                                        <select id="insurancecompany" data-placeholder="เลือกบริษัท">
                                            <option value=""></option>
                                            <option value="company1">ไฟแนนซ์ A</option>
                                            <option value="company2">ไฟแนนซ์ B</option>
                                            <option value="company3">ไฟแนนซ์ C</option>
                                        </select>

                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="insurancepremium" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"></label>
                                        <label style="width:45px;"> ทุน</label>
                                        <input type="number" min="0" id="insurancecapital" placeholder="บาท" style="width:100px;" />
                                        <label> บาท</label>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> เบี้ย พ.ร.บ.</label>
                                        <label style="width:45px;"> บริษัท</label>
                                        <select id="compulsoryinsurancecompany" data-placeholder="เลือกบริษัท">
                                            <option value=""></option>
                                            <option value="company1">ไฟแนนซ์ A</option>
                                            <option value="company2">ไฟแนนซ์ B</option>
                                            <option value="company3">ไฟแนนซ์ C</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="compulsoryinsurancepremium" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ทะเบียน</label>
                                        <label>
                                            <input name="registrationtype" type="radio" class="ace" />
                                            <span class="lbl">  บุคคล</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input name="registrationtype" type="radio" class="ace" />
                                            <span class="lbl"> นิติบุคคล</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="registrationfees" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> มัดจำป้ายแดง</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="depositredplate" placeholder="บาท"  />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:170px; text-align:right; padding-right:10px; font-weight:bold;"> รวมเงิน</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" readonly min="0" id="total" placeholder="" value=""  />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> <span style=" text-decoration:underline;">หัก</span> Sub ดาวน์</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="deductsubdown" placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"><span style=" text-decoration:underline;">หัก</span> มัดจำรถ</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="deductearnestcar " placeholder="บาท"  />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"><span style=" text-decoration:underline;">หัก</span> ค่ารถเก่า</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" min="0" id="deductoldcar" placeholder="บาท"  />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:170px; text-align:right; padding-right:10px; font-weight:bold;"> ชำระเงินรวม</label>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="number" readonly min="0" id="totalpayment" placeholder="" value=""  />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>


        <!-- Details 3 -->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">ข้อมูลส่วนที่ 3 </h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <div class="form-group" style="padding-left:20px; padding-top:10px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentsdate">วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentsdate" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        <label style="width:180px;">
                                            ผู้ซื้อได้ชำระเงินเป็นจำนวน&nbsp;
                                        </label>
                                        <input type="number" id="downinstallmentspaid" min="0" style="margin-right:70px;" placeholder="บาท" />
                                        <label style="width:165px;">
                                            สำหรับส่วนที่ค้างชำระอีก&nbsp;
                                        </label>
                                        <input type="number" id="downinstallmentsunpaid" min="0" placeholder="บาท" />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        <label style="width:180px;">
                                            พร้อมดอกเบี้ยจำนวน&nbsp;
                                        </label>
                                        <input type="number" id="downinstallmentsunpaidinterest" min="0" style="margin-right:70px;" placeholder="บาท" />
                                        <label style="width:165px;">
                                            รวมเป็นเงินทั้งสิ้น&nbsp;
                                        </label>
                                        <input type="number" id="totaldowninstallmentsunpaid" min="0" placeholder="บาท" />
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        <div style="float:left;padding-top:6px; ">
                                            <label style="width:180px;">
                                                โดยขอเสนอชำระเป็น
                                            </label>
                                        </div>
                                        <div class="checkbox" style="padding-top:0px;">
                                            <label>
                                                <input name="downinstallmentspaymenttype" type="checkbox" class="ace" />
                                                <span class="lbl" style="width:60px;" > รถเก่า</span>
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                <input name="downinstallmentspaymenttype" type="checkbox" class="ace" />
                                                <span class="lbl" style="width:60px;" > เงินสด</span>
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                <input name="downinstallmentspaymenttype" type="checkbox" class="ace" />
                                                <span class="lbl" style="width:60px;" >  อื่น ๆ </span>
                                            </label>
                                            <input type="text" id="otherpaymenttype" placeholder="" />
                                            <label> จำนวน </label><input type="number" id="numberdowninstallments" min="0" style="width:70px;" placeholder="งวด" /><label> งวด  ดังนี้</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;  padding-top:10px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate1">งวดที่ 1 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate1" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount1">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount1" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate2">งวดที่ 2 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate2" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount2">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount2" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate3">งวดที่ 3 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate3" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount3">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount3" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate4">งวดที่ 4 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate4" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount4">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount4" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate5">งวดที่ 5 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate5" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount5">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount5" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:60px;">
                                    <div class="col-sm-12">
                                        <label class="control-label no-padding-right" style="float:left;" for="downinstallmentdate6">งวดที่ 6 วันที่</label>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="downinstallmentdate6" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                            </div>
                                        </div>
                                        <label class="col-sm-1 control-label no-padding-right" style="float:left;" for="downinstallmentamount6">จำนวน</label>
                                        <div class="col-sm-2">
                                            <input type="number" id="downinstallmentamount6" min="0" placeholder="บาท" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-top:15px;">
                                    <label class="col-sm-1 control-label no-padding-right" for="customerbuy">ผู้ซื้อ</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="customerbuy" data-placeholder="โปรดเลือกชื่อลูกค้า ...">
                                                <option value="">  </option>
                                                <option value="customer1">นายเสก โลโซ</option>
                                                <option value="customer2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="customer3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="salesperson">พนักงานขาย</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="salesperson" data-placeholder="โปรดเลือกชื่อพนักงานขาย ...">
                                                <option value="">  </option>
                                                <option value="saleman1">นายเสก โลโซ</option>
                                                <option value="saleman2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="saleman3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="customerbuyoldcar">ผู้ซื้อรถเก่า</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="customerbuyoldcar" data-placeholder="โปรดเลือกชื่อลูกค้า ...">
                                                <option value="">  </option>
                                                <option value="customer1">นายเสก โลโซ</option>
                                                <option value="customer2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="customer3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="approver">ผู้อนุมัติ</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="approver" data-placeholder="โปรดเลือกชื่อผู้อนุมัติ ...">
                                                <option value="">  </option>
                                                <option value="approver1">นายเสก โลโซ</option>
                                                <option value="approver2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="approver3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>


        <!-- For Money Officer -->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">สำหรับเจ้าหน้าที่การเงิน</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <div class="form-group" style="padding-left:20px; padding-top:10px;">
                                    <div class="col-sm-12">
                                        <label>
                                            <span> ชำระค่ารถเก่าจำนวน</span>&nbsp;&nbsp;
                                            <input type="number" id="oldcarpaymentamount" min="0" style="width:120px;" placeholder="บาท" />
                                        </label>
                                        <label>&nbsp;
                                            <span>&nbsp;&nbsp;&nbsp; โดย&nbsp;&nbsp;&nbsp;</span>
                                            <input name="oldcarpaymenttype" type="radio" class="ace" />
                                            <span class="lbl" style="margin-right:15px;	">  เงินสด</span>
                                        </label>
                                        <label>
                                            <input name="oldcarpaymenttype" type="radio" class="ace" />
                                            <span class="lbl" style="margin-right:15px;"> เช็ค</span>&nbsp;
                                        </label>
                                        <label>
                                            <input name="oldcarpaymenttype" type="radio" class="ace" />
                                            <span class="lbl"> โอน</span>&nbsp;
                                        </label>
                                    </div>


                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="receivedpaymentdate">วันที่รับเงิน</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input class="form-control date-picker" id="receivedpaymentdate" type="text" data-date-format="dd-mm-yyyy" />
																<span class="input-group-addon">
																	<i class="fa fa-calendar bigger-110"></i>
																</span>
                                        </div>
                                    </div>

                                    <label class="col-sm-1 control-label no-padding-right" for="receivedpaymentby">ผู้รับเงิน</label>
                                    <div class="col-sm-3">
                                        <div>
                                            <select id="receivedpaymentby" data-placeholder="โปรดเลือกชื่อผู้รับเงิน ...">
                                                <option value="">  </option>
                                                <option value="receiver1">นายเสก โลโซ</option>
                                                <option value="receiver2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                <option value="receiver3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($oper != 'view')
            <div class="clearfix form-actions">
                <div class="col-md-offset-5 col-md-5">
                    <button id="btnSubmit" class="btn btn-info" type="submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button id="btnReset" class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
        @endif

    {!! Form::close() !!}

@endsection