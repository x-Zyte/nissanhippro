@extends('appformpathlevel2')

@section('menu-carpreemption-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> เพิ่มใบจองใหม่</h3>

    <form id="form-preemption" class="form-horizontal" role="form">
        <div class="form-group" style="margin-top:10px;" >
            <label class="col-sm-1 control-label no-padding-right" for="customerorderbook">เล่มที่</label>
            <div class="col-sm-2">
                <input type="number" id="customerorderbook" placeholder="หมายเลขเล่ม" />
            </div>
            <label class="col-sm-1 control-label no-padding-right" for="no">เลขที่</label>
            <div class="col-sm-2">
                <input type="number" id="no" placeholder="หมายเลข" />
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

        <!-- Customer Details -->
        <div class="row">
            <div class="col-xs-1 col-sm-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">ข้อมูลลูกค้า</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>

                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <script type="text/javascript">
                                    function NewCustomer(customernew)
                                    {
                                        if(customernew == 'y')
                                        {
                                            $(".new-customer").css("display","");
                                            $(".old-customer").css("display","none");
                                        }
                                        else
                                        {
                                            $(".new-customer").css("display","none");
                                            $(".old-customer").css("display","inline-block");
                                        }
                                    }
                                </script>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " >ชื่อผู้สั่งจอง</label>
                                    <div class="col-sm-11" >
                                        <label>
                                            <input name="customer-type" type="radio" class="ace" onchange="NewCustomer('n');" checked />
                                            <span class="lbl"> มีชื่อในระบบ</span>&nbsp;&nbsp;
                                            <div class="old-customer" style="display:inline-block">
                                                <select class="chosen-select" id="customer" data-placeholder="โปรดเลือกชื่อลูกค้า " style="width:20%;">
                                                    <option value="">  </option>
                                                    <option value="customer1">นายเสก โลโซ</option>
                                                    <option value="customer2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                    <option value="customer3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                                </select>
                                            </div>

                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " ></label>
                                    <div class="col-sm-11">
                                        <label>
                                            <input name="customer-type" type="radio" class="ace" onchange="NewCustomer('y');" />
                                            <span class="lbl"> ลูกค้าใหม่</span>&nbsp;&nbsp;
                                            <select id="title" class="new-customer" style="font-size:14px; padding:5px 4px 6px; height:34px; display:none;">
                                                <option value="mr">นาย</option>
                                                <option value="mrs">นาง</option>
                                                <option value="miss">นางสาว</option>
                                            </select>
                                            <input type="text" class="new-customer" id="firstname"  class="new-customer" style="display:none;"  placeholder="ชื่อ" />
                                            <input type="text" class="new-customer" id="lastname"  class="new-customer" style="display:none;"  placeholder="นามสกุล" />
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " >ที่อยู่</label>
                                    <div class="col-sm-11">
                                        <input type="text" id="address" placeholder="บ้านเลขที่ / หมู่ที่ / ซอย / ถนน" style="width:45%; min-width:250px;" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " >จังหวัด</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="province" data-placeholder="โปรดเลือกจัดหวัด ">
                                            <option value="">  </option>
                                            <option value="1">จังหวัด</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right " >อำเภอ </label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="aumphur" data-placeholder="โปรดเลือกอำเภอ ">
                                            <option value="">  </option>
                                            <option value="1">อำเภอ </option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right " >ตำบล </label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="tumbon" data-placeholder="โปรดเลือกตำบล  ">
                                            <option value="">  </option>
                                            <option value="1">ตำบล </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-1 control-label no-padding-right " >ไปรษณีย์</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="zipcode" placeholder="รหัสไปรษณีย์" />
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="phone">เบอร์โทร 1</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>
                                            <input class="form-control input-mask-phone" type="text" id="phone" />
                                        </div>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="phone">เบอร์โทร 2</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>
                                            <input class="form-control input-mask-phone" type="text" id="phone2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="models">อาชีพ</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" id="occupation" data-placeholder="โปรดเลือกหมวดหมู่อาชีพ " style="width:15%;">
                                            <option value="">  </option>
                                            <option value="0">ไม่ระบุ</option>
                                            <option value="1">พนักงานบริษัท</option>
                                            <option value="2">ธุรกิจส่วนตัว</option>
                                            <option value="3">นักเรียน /นักศึกษา </option>
                                            <option value="4">ข้าราชการ </option>
                                            <option value="5">พนักงานรัฐวิสาหกิจ </option>
                                            <option value="6">รับจ้างทั่วไป</option>
                                            <option value="7">ค้าขาย</option>
                                            <option value="8">ว่างงาน /เกษียณ</option>
                                            <option value="9">แม่บ้าน / พ่อบ้าน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="date">วันเกิด</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input class="form-control date-picker" id="birthday" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                        </div>
                                    </div>
                                </div><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- New Car Details -->
        <div class="row">
            <div class="col-sm-12 ">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">รายละเอียดรถยนตร์ใหม่</h4>
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
                                    <label class="col-sm-1 control-label no-padding-right" for="models">รถนิสสัน</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="models" data-placeholder="โปรดเลือกรุ่นรถ " style="width:150px;">
                                            <option value="">  </option>
                                            <option value="0">models</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="models">แบบ</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="model" data-placeholder="โปรดเลือกแบบรถ ">
                                            <option value="">  </option>
                                            <option value="0">model</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="colors">สี</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="colors" data-placeholder="โปรดเลือกสี ">
                                            <option value="">  </option>
                                            <option value="0">colors</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="price">ราคา</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="price" placeholder="(โปรแกรมดึงมา)" style="background-color:#FAFAFA;" readonly />
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="discount">ส่วนลด</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="discount" placeholder="บาท" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="price">Sub Down</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="subdown" placeholder="บาท " />
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="discount">บวกอุปกรณ์</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="plustool" placeholder="บาท" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Old Car Details -->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">รายละเอียดรถยนตร์เก่า</h4>
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
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarbrand">ยี่ห้อ</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="oldcarbrand" placeholder="ยี่ห้อ"  />
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarmodels">รุ่น</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="oldcarmodels" placeholder="รุ่น" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcargear">เกียร์</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="oldcargear" data-placeholder="โปรดเลือกเกียร์ ">
                                            <option value="">  </option>
                                            <option value="0">กระปุก</option>
                                            <option value="1">ออโต้</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarcolors">สี</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="oldcarcolors" placeholder="สี" />
                                    </div>
                                </div>
                                <div class="form-group">

                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarenginesize">ขนาดเครื่องยนต์ </label>
                                    <div class="col-sm-2">
                                        <input type="number" id="oldcarenginesize" placeholder="ซีซี" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarvehicleregistrationno">ทะเบียน</label>
                                    <div class="col-sm-1">
                                        <input type="text" id="oldcarvehicleregistrationno" placeholder="ทะเบียน" />
                                    </div>
                                    <label class="col-sm-1 col-lg-2 control-label no-padding-right" for="oldcaryear">ปี </label>
                                    <div class="col-sm-1">
                                        <input type="number" id="oldcaryear" placeholder="ปี" style="width:60px;" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarprice">ราคารถเก่า</label>
                                    <div class="col-sm-1">
                                        <input type="number" id="oldcarprice" placeholder="บาท" />
                                    </div>
                                    <label class="col-sm-2 control-label no-padding-right" for="oldcarpriceby">ผู้ให้ราคา</label>
                                    <div class="col-sm-2">
                                        <input type="text" id="oldcarpriceby" placeholder="ชื่อ-นามสกุล" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="oldcarother">อื่น ๆ </label>
                                    <div class="col-sm-10">
                                        <textarea type="text" id="oldcarother" placeholder="ข้อความ" style="width:512px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Details / Payment Terms & Conditions-->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">รายละเอียด / เงื่อนไขการชำระเงิน</h4>
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
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="earnest">1. เงินมัดจำ</label>
                                        <div class="col-sm-3">
                                            <input type="number" id="earnest" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right " >2. ซื้อรถยนต์</label>
                                        <div class="col-sm-10">
                                            <label>
                                                <input name="buytype" type="radio" class="ace" />
                                                <span class="lbl">  เงินสด</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                <input name="buytype" type="radio" class="ace" />
                                                <span class="lbl"> เช่าซื้อกับบริษัท</span>&nbsp;&nbsp;
                                                <input type="text" id="company" placeholder="ชื่อบริษัท" />
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="interest">% ดอกเบี้ย </label>
                                        <div class="col-sm-10">
                                            <input type="number" min="0" max="100"  id="interest" placeholder="%" style="width:70px;" />&nbsp;&nbsp;&nbsp;
                                            ดาวน์  <input type="number" id="downpayment" placeholder="บาท" style="width:150px;" />&nbsp;&nbsp;&nbsp;
                                            จำนวนงวด   <input type="number"  min="0" id="instalment" placeholder="งวด" style="width:70px;" />
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right"  for="earnestredplate">3.  ค่ามัดจำป้ายแดง</label>
                                        <div class="col-sm-3 ">
                                            <input type="number" id="earnestredplate" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right " >4. ค่าจดทะเบียน</label>
                                        <div class="col-sm-10">
                                            <label>
                                                <input name="registrationtype" type="radio" class="ace" />
                                                <span class="lbl">  บุคคล</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                <input name="registrationtype" type="radio" class="ace" />
                                                <span class="lbl"> นิติบุคคล</span>&nbsp;&nbsp;
                                                <input type="number" id="registrationfees" placeholder="บาท" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="insurancefees">5. ค่าประกันภัย</label>
                                        <div class="col-sm-3">
                                            <input type="number" id="insurancefees" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="compulsoryInsurancefees">6. ค่า พรบ.</label>
                                        <div class="col-sm-3">
                                            <input type="number" id="compulsoryInsurancefees" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="equipmentfees">7. ค่าอุปกรณ์</label>
                                        <div class="col-sm-3">
                                            <input type="number" id="equipmentfees" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="otherfees">8. ค่าอื่น ๆ</label>
                                        <div class="col-sm-3">
                                            <input type="number" id="otherfees" placeholder="บาท" style="width:100%;" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-sm-9 no-padding-left">
                                        <label class="col-sm-2 control-label no-padding-right" for="wishreceivedate">วันที่ต้องการรับรถ</label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="wishreceivedate" type="text" data-date-format="dd-mm-yyyy" />
																		<span class="input-group-addon">
																			<i class="fa fa-calendar bigger-110"></i>
																		</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Other Details -->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">รายละเอียดอื่น ๆ </h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">

                                <div>
                                    <table id="premium-table"></table>
                                    <div id="premium-pager"></div>
                                </div><br>
                                <div>
                                    <table id="toolforsale-table"></table>
                                    <div id="toolforsale-pager"></div>
                                </div><br>
                                <script type="text/javascript">
                                    function BuyCustomerType(buyertype)
                                    {
                                        if(buyertype == 'same')
                                        {
                                            $(".same-customer").css("display","none");
                                            $(".insystem-customer").css("display","none");
                                            $(".newbuy-customer").css("display","none");
                                        }
                                        else if(buyertype == 'insystem')
                                        {
                                            $(".insystem-customer").css("display","inline-block");
                                            $(".same-customer").css("display","");
                                            $(".newbuy-customer").css("display","none");
                                        }
                                        else if(buyertype == 'newbuyer')
                                        {
                                            $(".newbuy-customer").css("display","");
                                            $(".same-customer").css("display","");
                                            $(".insystem-customer").css("display","none");

                                        }
                                    }
                                </script>
                                <div class="form-group">
                                    <div style="height:35px;">
                                        <label class="col-sm-1 control-label no-padding-right " >ผู้ซื้อ</label>
                                        <div class="col-sm-11" >
                                            <div class="checkbox" style="padding-left:0px;">
                                                <label>
                                                    <input name="BuyerType" type="radio" class="ace" onchange="BuyCustomerType('same');" checked />
                                                    <span class="lbl"> คนเดียวกับผู้จอง</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <label class="col-sm-1 control-label no-padding-right " ></label>
                                    <div class="col-sm-11" >
                                        <div class="checkbox" style="padding-left:0px;">
                                            <label>
                                                <input name="BuyerType" type="radio" class="ace" onchange="BuyCustomerType('insystem');" />
                                                <span class="lbl"> มีชื่อในระบบ</span>&nbsp;&nbsp;
                                                <div class="insystem-customer" style="display:none;">
                                                    <select class="chosen-select" id="customerbuy" data-placeholder="โปรดเลือกชื่อลูกค้า " style="width:25%;">
                                                        <option value="">  </option>
                                                        <option value="customer1">นายเสก โลโซ</option>
                                                        <option value="customer2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                                        <option value="customer3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                                    </select>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " ></label>
                                    <div class="col-sm-11">
                                        <div class="checkbox" style="padding-left:0px;">
                                            <label>
                                                <input name="BuyerType" type="radio" class="ace" onchange="BuyCustomerType('newbuyer');" />
                                                <span class="lbl"> ลูกค้าใหม่</span>&nbsp;&nbsp;
                                                <select id="title" class="newbuy-customer" style="font-size:14px; padding:5px 4px 6px; height:34px; display:none;">
                                                    <option value="mr">นาย</option>
                                                    <option value="mrs">นาง</option>
                                                    <option value="miss">นางสาว</option>
                                                </select>
                                                <input type="text" class="newbuy-customer" id="firstname"  class="new-customer" style="display:none;"  placeholder="ชื่อ" />
                                                <input type="text" class="newbuy-customer" id="lastname"  class="new-customer" style="display:none;"  placeholder="นามสกุล" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none; padding-top:15px;" >
                                    <label class="col-sm-1 control-label no-padding-right " >ที่อยู่</label>
                                    <div class="col-sm-11">
                                        <input type="text" id="address" placeholder="บ้านเลขที่ / หมู่ที่ / ซอย / ถนน" style="width:45%; min-width:250px;" />
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;" >
                                    <label class="col-sm-1 control-label no-padding-right " >จังหวัด</label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="province" data-placeholder="โปรดเลือกจัดหวัด ">
                                            <option value="">  </option>
                                            <option value="1">จังหวัด</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right " >อำเภอ </label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="aumphur" data-placeholder="โปรดเลือกอำเภอ ">
                                            <option value="">  </option>
                                            <option value="1">อำเภอ </option>
                                        </select>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right " >ตำบล </label>
                                    <div class="col-sm-2">
                                        <select class="chosen-select" id="tumbon" data-placeholder="โปรดเลือกตำบล  ">
                                            <option value="">  </option>
                                            <option value="1">ตำบล </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    <label class="col-sm-1 control-label no-padding-right " >ไปรษณีย์</label>
                                    <div class="col-sm-2">
                                        <input type="number" id="zipcode" placeholder="รหัสไปรษณีย์" />
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="phone">เบอร์โทร 1</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>
                                            <input class="form-control input-mask-phone" type="text" id="phone" />
                                        </div>
                                    </div>
                                    <label class="col-sm-1 control-label no-padding-right" for="phone">เบอร์โทร 2</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
																<span class="input-group-addon">
																	<i class="ace-icon fa fa-phone"></i>
																</span>
                                            <input class="form-control input-mask-phone" type="text" id="phone2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    <label class="col-sm-1 control-label no-padding-right" for="models">อาชีพ</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" id="occupation" data-placeholder="โปรดเลือกหมวดหมู่อาชีพ " style="width:15%;">
                                            <option value="">  </option>
                                            <option value="0">ไม่ระบุ</option>
                                            <option value="1">พนักงานบริษัท</option>
                                            <option value="2">ธุรกิจส่วนตัว</option>
                                            <option value="3">นักเรียน /นักศึกษา </option>
                                            <option value="4">ข้าราชการ </option>
                                            <option value="5">พนักงานรัฐวิสาหกิจ </option>
                                            <option value="6">รับจ้างทั่วไป</option>
                                            <option value="7">ค้าขาย</option>
                                            <option value="8">ว่างงาน /เกษียณ</option>
                                            <option value="9">แม่บ้าน / พ่อบ้าน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    <label class="col-sm-1 control-label no-padding-right" for="date">วันเกิด</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input class="form-control date-picker" id="birthday" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="salesperson">พนักงานขาย</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" id="salesperson" data-placeholder="โปรดเลือกพนักงานขาย ">
                                            <option value="">  </option>
                                            <option value="saleman1">นายเสก โลโซ</option>
                                            <option value="saleman2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                            <option value="saleman3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label no-padding-right" for="salesmanager">ผู้จัดการฝ่ายขาย</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" id="salesmanager" data-placeholder="โปรดเลือกผู้จัดการขาย ">
                                            <option value="">  </option>
                                            <option value="salemanager1">นายเสก โลโซ</option>
                                            <option value="salemanager2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                            <option value="salemanager3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="approver">ผู้อนุมัติ</label>
                                    <div class="col-sm-3">
                                        <select class="chosen-select" id="approver" data-placeholder="โปรดเลือกผู้อนุมัติ ">
                                            <option value="">  </option>
                                            <option value="approver1">นายเสก โลโซ</option>
                                            <option value="approver2">นายเรืองศักดิ์ ลอยชูศักดิ์</option>
                                            <option value="approver3">นางสาวคักกิ่งรักษ์ คิกคักสะระนัง</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 control-label no-padding-right" for="approveddate">วันที่อนุมัติ</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input class="form-control date-picker" id="approveddate" type="text" data-date-format="dd-mm-yyyy" />
																	<span class="input-group-addon">
																		<i class="fa fa-calendar bigger-110"></i>
																	</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Customer Other Details -->
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">เกี่ยวกับลูกค้า</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">

                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left" style="height:35px;">
                                        <label class="col-sm-2 control-label no-padding-right " >ที่มาของลูกค้า </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" >  สถานที่</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" > โชว์รูม</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" > บูธ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" > ใบปลิว</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" > นามบัตร</span>
                                                </label>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 no-padding-left" style="height:35px;">
                                        <label class="col-sm-2 control-label no-padding-right " >&nbsp;</label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">

                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" >  การ์ดเชิญ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:80px;" > โทรศัพท์</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:115px;" > ป้ายหน้าโชว์รูม</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:150px;" > สปอตวิทยุ/walk in</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9 no-padding-left" style="height:35px;">
                                        <label class="col-sm-2 control-label no-padding-right " ></label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="customersource" type="checkbox" class="ace" />
                                                    <span class="lbl" style="width:100px;" >  แนะนำโดย</span>
                                                </label>
                                                <input>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="radio" class="ace" />
                                                    <span class="lbl" style="width:auto;" > เพื่อน</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="radio" class="ace" />
                                                    <span class="lbl" style="width:auto;" > ญาติ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="radio" class="ace" />
                                                    <span class="lbl" style="width:auto;" > ลูกค้าเก่า</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customersource" type="radio" class="ace" />
                                                    <span class="lbl" style="width:auto;" > พนักงาน</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-9 no-padding-left" style="height:35px; margin-top:10px;">
                                        <label class="col-sm-2 control-label no-padding-right " >ประเภทลูกค้า </label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="customerbuytype" type="radio" class="ace" />
                                                    <span class="lbl" style="width:80px;" >  ซื้อใหม่</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    <input name="customerbuytype" type="radio" class="ace" />
                                                    <span class="lbl" style="width:auto;" > ซื้อทดแทน</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Notes -->
        <div class="form-group">
            <div class="col-sm-12">
                <textarea type="text" id="notes" class="autosize-transition limited" placeholder="หมายเหตุ  / โน๊ต " maxlength="500" style="width:100%;"></textarea>
            </div>
        </div>

        <div class="clearfix form-actions">
            <div class="col-md-offset-5 col-md-5">
                <button id="btnSubmit" class="btn btn-info" type="button">
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
    </form>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        $(document).ready(function() {

        })
    </script>
@endsection