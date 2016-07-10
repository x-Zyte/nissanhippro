@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มรายละเอียดเพื่อการบันทึกบัญชีใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขรายละเอียดเพื่อการบันทึกบัญชี ของรายการจอง '.$accountingdetail->bookno.'/'.$accountingdetail->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลรายละเอียดเพื่อการบันทึกบัญชี ของรายการจอง '.$accountingdetail->bookno.'/'.$accountingdetail->no)
@endif

@section('menu-accountingdetail-class','active')
@section('pathPrefix',$pathPrefix)

@section('content')
    <style>
        .underline_db {
            border-bottom: 3px double #000;
            text-align: right;
        }

        .underline {
            border-bottom: 1px solid #000;
            text-align: right;
        }

        .underline_g {
            border-bottom: 1px solid #8C8C8C;
            text-align: right;
        }

        .dotted {
            border-bottom: 1px dotted #8C8C8C;
        }

        .dashed {
            border-bottom: 1px dashed #000;
        }

        .clear {
            clear: both
        }

        div.me {
            display: inline-block;
            padding: 3px;
            border: 1px solid black;
            margin-left: -1px;
            margin-top: -1px;
            white-space: 0;
        }

        .form-group {
            margin-bottom: 0;
        }

        .widget-main {
            padding: 5px;
        }
    </style>

    <script type="text/javascript">
        function CarpaymentChange(sel) {
            var carpaymentid = sel.value;

            $.get('{{$pathPrefix}}carpayment/getforaccountingdetailbyid/' + carpaymentid, function (data) {
                $("#branchname").text(data.branchname);
                $("#customername").text(data.customername);
                $("#date").text(data.date);

                if (data.openbill == 0) $("#openbill").text("-");
                else $("#openbill").text(parseFloat(data.openbill).toFixed(2));

                var additionalopenbill = $('#additionalopenbill').val();
                if (additionalopenbill == null || additionalopenbill == '')
                    additionalopenbill = 0;
                var finalopenbill = parseFloat(data.openbill) + parseFloat(additionalopenbill);
                if (finalopenbill == 0) $("#finalopenbill").text("-");
                else $("#finalopenbill").text(parseFloat(finalopenbill).toFixed(2));

                if (data.carpriceinpricelist == 0) $("#carpriceinpricelist").text("-");
                else $("#carpriceinpricelist").text(parseFloat(data.carpriceinpricelist).toFixed(2));

                if (data.colorprice == 0) $("#colorprice").text("-");
                else $("#colorprice").text(parseFloat(data.colorprice).toFixed(2));

                if (data.carwithcolorprice == 0) $("#carwithcolorprice").text("-");
                else $("#carwithcolorprice").text(parseFloat(data.carwithcolorprice).toFixed(2));

                if (data.accessoriesfeeincludeinyodjud == 0) $("#accessoriesfeeincludeinyodjud").text("-");
                else $("#accessoriesfeeincludeinyodjud").text(parseFloat(data.accessoriesfeeincludeinyodjud).toFixed(2));

                if (data.fakeaccessories == 0) {
                    $("#plusfakeaccessories").text("-");
                    $("#minusfakeaccessories").text("-");
                }
                else {
                    $("#plusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                    $("#minusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                }

                if (data.discount == 0) $("#discount").text("-");
                else $("#discount").text(parseFloat(data.discount).toFixed(2));

                if (data.subdown == 0) $("#subdown").text("-");
                else $("#subdown").text(parseFloat(data.subdown).toFixed(2));

                if (data.realsalesprice == 0) {
                    $("#realsalesprice").text("-");
                    $("#realsalesprice2").text("-");
                }
                else {
                    $("#realsalesprice").text(parseFloat(data.realsalesprice).toFixed(2));
                    $("#realsalesprice2").text(parseFloat(data.realsalesprice).toFixed(2));
                }

                var vat;
                if (finalopenbill == 0) vat = 0;
                else vat = parseFloat(finalopenbill) * parseFloat(0.07);

                if (vat == 0) $("#vatoffinalopenbill").text("-");
                else $("#vatoffinalopenbill").text(parseFloat(vat).toFixed(2));

                var finalopenbillwithoutvat = parseFloat(finalopenbill) - parseFloat(vat);
                if (finalopenbillwithoutvat == 0) $("#finalopenbillwithoutvat").text("-");
                else $("#finalopenbillwithoutvat").text(parseFloat(finalopenbillwithoutvat).toFixed(2));

                var realsalespricewithoutvat = parseFloat(data.realsalesprice) - parseFloat(vat);
                if (realsalespricewithoutvat == 0) $("#realsalespricewithoutvat").text("-");
                else $("#realsalespricewithoutvat").text(parseFloat(realsalespricewithoutvat).toFixed(2));

                if (data.accessoriesfeeactuallypaid == 0) $("#accessoriesfeeactuallypaid").text("-");
                else $("#accessoriesfeeactuallypaid").text(parseFloat(data.accessoriesfeeactuallypaid).toFixed(2));

                if (data.registrationfee == 0) $("#registrationfee").text("-");
                else $("#registrationfee").text(parseFloat(data.registrationfee).toFixed(2));

                if (data.compulsorymotorinsurancefeecash == 0) $("#compulsorymotorinsurancefeecash").text("-");
                else $("#compulsorymotorinsurancefeecash").text(parseFloat(data.compulsorymotorinsurancefeecash).toFixed(2));

                if (data.insurancefeecash == 0) $("#insurancefeecash").text("-");
                else $("#insurancefeecash").text(parseFloat(data.insurancefeecash).toFixed(2));

                if (data.implementfee == 0) $("#implementfee").text("-");
                else $("#implementfee").text(parseFloat(data.implementfee).toFixed(2));

                if (data.otherfee == 0) $("#otherfee").text("-");
                else $("#otherfee").text(parseFloat(data.otherfee).toFixed(2));

                if (data.totalotherfees == 0) $("#totalotherfees").text("-");
                else $("#totalotherfees").text(parseFloat(data.totalotherfees).toFixed(2));

                $("#submodel").text(data.submodel);
                $("#carno").text(data.carno);
                $("#engineno").text(data.engineno);
                $("#chassisno").text(data.chassisno);
                $("#color").text(data.color);
                $("#purchasetype").text(data.purchasetype);

                if (data.down == 0) $("#down").text("-");
                else $("#down").text(parseFloat(data.down).toFixed(2));
            });
        }

        function TotalotherfeedetailToggle() {
            if ($(".totalotherfeedetail").css('display') == 'block') {
                $(".totalotherfeedetail").css("display", "none");
            }
            else {
                $(".totalotherfeedetail").css("display", "block");
            }
        }
    </script>

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            เพิ่มรายละเอียดเพื่อการบันทึกบัญชีใหม่</h3>
    @elseif($oper == 'edit')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            แก้ไขรายละเอียดเพื่อการบันทึกบัญชี
        </h3>
    @elseif($oper == 'view')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-info-circle"></i>
            ดูข้อมูลรายละเอียดเพื่อการบันทึกบัญชี
        </h3>
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
        {!! Form::model($accountingdetail, array('url' => 'accountingdetail/save', 'id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
    @elseif($oper == 'edit')
        {!! Form::model($accountingdetail, array('url' => 'accountingdetail/save', 'id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($accountingdetail, array('id'=>'form-accountingdetail', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

    <div class="form-group" style="margin-top:10px;">
        {!! Form::label('carpaymentid', 'รายละเอียดเพื่อการบันทึกบัญชีของการจอง เล่มที่/เลขที่', array('class' => 'col-sm-4 control-label no-padding-right')) !!}
        <div class="col-sm-3">
            @if($oper == 'new')
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)')) !!}
            @else
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)', 'disabled'=>'disabled')) !!}
                {!! Form::hidden('carpaymentid') !!}
            @endif
        </div>
    </div>

    <h4 style="display: inline-block;">บริษัท ฮิปโปร พาวเวอร์ จำกัด : สาขา</h4> <h4
            style="display: inline-block;" id="branchname">{{$accountingdetail->branchname}}</h4><br>

    <div>
        <table>
            <tbody>
            <tr>
                <td><label style="width:60px; font-weight:bold">ชื่อลูกค้า</label></td>
                <td class="underline_g"><label style="width:220px; text-align:center;"
                                               id="customername">{{$accountingdetail->customername}}</label>
                </td>
                <td><label style="width:90px; font-weight:bold; margin-left:20px;">เลขที่ใบกำกับ</label></td>
                <td>{!! Form::text('invoiceno', null, array('id'=>'invoiceno')) !!}</td>
                <td><label style="width:35px; font-weight:bold; margin-left:20px;">วันที่</label></td>
                <td><label>
                        <div class="input-group">
                            {!! Form::text('date', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date')) !!}
                            <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                        </div>
                    </label></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-xs-12 ">

            <div class="row">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">รายละเอียด</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">1. รายได้ค่ารถ</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:550px; float:left;">
                                            <label style="width:140px; font-weight:bold;">ราคาเปิดบิล</label>
                                            <label style="width: 100px; font-weight:bold; text-align:right;"
                                                   id="openbill">{{$accountingdetail->openbill}}</label>
                                            <label style="width:30px; text-align:center;">+/-</label>
                                            {!! Form::number('additionalopenbill', null, array('style'=>'width: 100px; height: 22px; padding:0; color:black; font-weight:bold; text-align:right;','id'=>'additionalopenbill')) !!}
                                            <label style="width:30px; text-align:center;">=</label>
                                            <label style="width: 100px; font-weight:bold; text-align:right; color:red;"
                                                   id="finalopenbill">{{$accountingdetail->finalopenbill}}</label>
                                            <label style="font-weight:bold; padding-left:5px;">(1)*</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคารถขายจริง (รวม
                                                VAT)</label>
                                            <label class="underline_db" style="width: 100px; font-weight:bold;"
                                                   id="realsalesprice">{{$accountingdetail->realsalesprice}}</label>
                                            <label style="width:45px; text-align:right;">(1)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">ราคาตั้งขาย + สี</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="carpriceinpricelist">{{$accountingdetail->carpriceinpricelist}}</label>
                                            <label style="width:30px; text-align:center;">+</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="colorprice">{{$accountingdetail->colorprice}}</label>
                                            <label style="width:30px; text-align:center;">=</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="carwithcolorprice">{{$accountingdetail->carwithcolorprice}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">หัก VAT 7% (ตามบัญชี
                                                1)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="vatoffinalopenbill">{{$accountingdetail->vatoffinalopenbill}}</label>
                                            <label style="width:45px; text-align:right;">(2)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">ขายอุปกรณ์จริง</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="accessoriesfeeincludeinyodjud">{{$accountingdetail->accessoriesfeeincludeinyodjud}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคาค่ารถ (ตามบัญชี 1) ไม่รวม
                                                VAT (1)* - (2)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="finalopenbillwithoutvat">{{$accountingdetail->finalopenbillwithoutvat}}</label>
                                            <label style="width:45px; text-align:right;">(3)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; padding-left:20px;">บวกอุปกรณ์ (หลอก)</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="plusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label>
                                            <label style="width:30px; text-align:center;"></label>
                                            <label style="width:110px;">หักอุปกรณ์ (หลอก)</label>
                                            <label style="width:20px; text-align:center;"></label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="minusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">ราคาค่ารถ (ตามบัญชี 2) ไม่รวม
                                                VAT (1) - (2)</label>
                                            <label class="underline" style="width:100px;"
                                                   id="realsalespricewithoutvat">{{$accountingdetail->realsalespricewithoutvat}}</label>
                                            <label style="width:45px; text-align:right;">(4)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px;"><span
                                                        style="font-weight:bold;text-decoration:underline; padding-left:20px;">หัก</span>
                                                ส่วนลด</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="discount">{{$accountingdetail->discount}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">2. รายได้อื่น</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px;"><span
                                                        style="font-weight:bold;text-decoration:underline; padding-left:20px;">หัก</span>
                                                Sub down</label>
                                            <label class="dotted" style="width: 100px; text-align:right;"
                                                   id="subdown">{{$accountingdetail->subdown}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.1 รายได้ค่าอุปกรณ์</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="accessoriesfeeactuallypaid">{{$accountingdetail->accessoriesfeeactuallypaid}}</label>
                                            <label style="width:45px; text-align:right;">(5)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:140px; font-weight:bold; text-decoration:underline; color:red; padding-left:20px;">คงเหลือขายจริง</label>
                                            <label style="width: 100px; text-align:right; font-weight:bold; color:red;"
                                                   id="realsalesprice2">{{$accountingdetail->realsalesprice}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.2
                                                รายได้ค่าจดทะเบียน</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="registrationfee">{{$accountingdetail->registrationfee}}</label>
                                            <label style="width:45px; text-align:right;">(6)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">รุ่น</label>
                                            <label style="width:330px; font-weight:bold;"
                                                   id="submodel">{{$accountingdetail->submodel}}</label>
                                            <label style="width:50px; font-weight:bold;">คันที่</label>
                                            <label style="width:50px; font-weight:bold;"
                                                   id="carno">{{$accountingdetail->carno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.3 รายได้ค่า
                                                พ.ร.บ.(กรณีสด)</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="compulsorymotorinsurancefeecash">{{$accountingdetail->compulsorymotorinsurancefeecash}}</label>
                                            <label style="width:45px; text-align:right;">(7)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">เลขเครื่อง</label>
                                            <label style="width:170px; font-weight:bold;"
                                                   id="engineno">{{$accountingdetail->engineno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.4
                                                รายได้ค่าเบี้ยประกันภัย(กรณีสด)</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="insurancefeecash">{{$accountingdetail->insurancefeecash}}</label>
                                            <label style="width:45px; text-align:right;">(8)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">เลขถัง</label>
                                            <label style="width:170px; font-weight:bold;"
                                                   id="chassisno">{{$accountingdetail->chassisno}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.5
                                                รายได้ค่าดำเนินการ</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="implementfee">{{$accountingdetail->implementfee}}</label>
                                            <label style="width:45px; text-align:right;">(9)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">สี</label>
                                            <label class="dashed"
                                                   style="width:70px; font-weight:bold; text-align:center;"
                                                   id="color">{{$accountingdetail->color}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">2.6 รายได้อื่น <a
                                                        href="javascript:TotalotherfeedetailToggle();"> - รายละอียด</a></label>
                                            <label class="underline" style="width:100px;"
                                                   id="totalotherfee">{{$accountingdetail->totalotherfee}}</label>
                                            <label style="width:45px; text-align:right;">(10)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">ประเภทขาย</label>
                                            <label class="dashed"
                                                   style="width:70px; font-weight:bold; text-align:center; color:red;"
                                                   id="purchasetype">{{$accountingdetail->purchasetype}}</label>
                                            <label style="width:100px;"></label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px;">เงินดาวน์</label>
                                            <label class="dashed" style="width:130px; text-align:right;"
                                                   id="down">{{$accountingdetail->down}}</label>
                                        </div>
                                    </div>
                                    <div class="totalotherfeedetail" style="display: none">
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:388px; padding-left:45px;">- subsidise</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="subsidise">{{$accountingdetail->subsidise}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:388px; padding-left:45px;">- หัก ณ ที่จ่าย
                                                    (กรณีลูกค้าได้รับของแถม เช่น ทอง)</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="giveawaywithholdingtax">{{$accountingdetail->giveawaywithholdingtax}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (1) : </label>
                                                <label class="underline_g" style="width: 279px;"
                                                       id="otherfeedetail">{{$accountingdetail->otherfeedetail}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee">{{$accountingdetail->otherfee}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (2) : </label>
                                                <label class="underline_g" style="width: 279px;"
                                                       id="otherfeedetail2">{{$accountingdetail->otherfeedetail2}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee2">{{$accountingdetail->otherfee2}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-left:10px;">
                                            <div style="width:540px; float:left; margin-right:20px;">
                                                <label style="width:105px; padding-left:45px;">- อื่นๆ (3) : </label>
                                                <label class="underline_g" style="width: 279px;"
                                                       id="otherfeedetail3">{{$accountingdetail->otherfeedetail3}}</label>
                                                <label class="underline_g" style="width:100px;"
                                                       id="otherfee3">{{$accountingdetail->otherfee3}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายได้อื่น
                                                (5)+(6)+(7)+(8)+(9)+(10)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalotherfees">{{$accountingdetail->totalotherfees}}</label>
                                            <label style="width:45px; text-align:right;">(11)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">3. ค่าเบี้ยประกันภัยรับล่วงหน้า (ล/ค จ่ายกรณีจัด
                                                FIN)</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:center;">เงื่อนไข*</label>
                                            <label style="width:100px; text-align:center;">ยอดรวม</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">บริษัทชำระ</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">ลูกค้าชำระ</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.1 ค่าเบี้ย (ป.1)
                                                รับล่วงหน้า</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="insurancefeefn">{{$accountingdetail->insurancefeefn}}</label>
                                            <label style="width:45px; text-align:right;">(12)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px;">ค่าเบี้ย ป.1</label>
                                            {!! Form::select('insurancefeereceiptcondition', array(null => 'เลือก', 1 => 'ชื่อบริษัท', 0 => 'ชื่อลูกค้า'), null, array('style' => 'padding:0; height:23px; width:75px;')) !!}
                                            <label class="dashed" style="width:100px; text-align:right; color:#0090FF;"
                                                   id="conditioninsurancefee">{{$accountingdetail->insurancefeeincludevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioninsurancefeecompanypaid">{{$accountingdetail->insurancefeecompanypaid}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioninsurancefeecustomerpaid">{{$accountingdetail->insurancefeecustomerpaid}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.2 พ.ร.บ.
                                                รับล่วงหน้า</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="compulsorymotorinsurancefeefn">{{$accountingdetail->compulsorymotorinsurancefeefn}}</label>
                                            <label style="width:45px; text-align:right;">(13)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:65px;">ค่า พ.ร.บ.</label>
                                            {!! Form::select('compulsorymotorinsurancefeereceiptcondition', array(null => 'เลือก', 1 => 'ชื่อบริษัท', 0 => 'ชื่อลูกค้า'), null, array('style' => 'padding:0; height:23px; width:75px;')) !!}
                                            <label class="dashed" style="width:100px; text-align:right; color:#0090FF;"
                                                   id="conditioncompulsorymotorinsurancefee">{{$accountingdetail->compulsorymotorinsurancefeeincludevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioncompulsorymotorinsurancefeecompanypaid">{{$accountingdetail->compulsorymotorinsurancefeecompanypaid}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="conditioncompulsorymotorinsurancefeecustomerpaid">{{$accountingdetail->compulsorymotorinsurancefeecustomerpaid}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.3 ค่างวดแรก</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="firstinstallmentpayamount">{{$accountingdetail->firstinstallmentpayamount}}</label>
                                            <label style="width:45px; text-align:right;">(14)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">บจ. ประกัน</label>
                                            <label class="dashed" style="width:174px; text-align:center;"
                                                   id="insurancecompany">{{$accountingdetail->insurancecompany}}</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px;">ทุนประกัน</label>
                                            <label class="dashed" style="width:130px; text-align:right;"
                                                   id="capitalinsurance">{{$accountingdetail->capitalinsurance}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:191px; padding-left:25px;">3.4 ค่างวดรับล่วงหน้า
                                                จำนวน</label>
                                            <label class="dashed" style="width:30px; text-align:center;"
                                                   id="installmentsinadvance">{{$accountingdetail->installmentsinadvance}}</label>
                                            <label style="width:40px;">งวด @</label>
                                            <label class="dashed" style="width:70px; text-align:center;"
                                                   id="amountperinstallment">{{$accountingdetail->amountperinstallment}}</label>
                                            <label style="width:43px;">/M</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="payinadvanceamount">{{$accountingdetail->payinadvanceamount}}</label>
                                            <label style="width:45px; text-align:right;">(15)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;">บจ. พ.ร.บ.</label>
                                            <label class="dashed" style="width:174px; text-align:center;"
                                                   id="compulsorymotorinsurancecompany">{{$accountingdetail->compulsorymotorinsurancecompany}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">3.5 ค่าเบี้ยประกันชีวิต
                                                รับล่วงหน้า</label>
                                            <label class="underline" style="width:100px;"
                                                   id="insurancepremium">{{$accountingdetail->insurancepremium}}</label>
                                            <label style="width:45px; text-align:right;">(16)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายการรับล่วงหน้า
                                                (12)+(13)+(14)+(15)+(16)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalinadvancefees">{{$accountingdetail->totalinadvancefees}}</label>
                                            <label style="width:45px; text-align:right;">(17)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; font-weight:bold; text-align:left; color: limegreen">Note
                                                1</label>
                                            <label style="width:100px; text-align:center;">ยอดก่อน VAT</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">VAT</label>
                                            <label style="width:30px;"></label>
                                            <label style="width:100px; text-align:center;">ยอดรวม VAT</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:490px;">4. หักเงินมัดจำ</label>
                                            <label style="width:45px;"></label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">ค่าเบี้ย ป.1</label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1insurancefee">{{$accountingdetail->insurancefee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1insurancefeevat">{{$accountingdetail->insurancefeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1insurancefeeincludevat">{{$accountingdetail->insurancefeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">4.1 เงินมัดจำป้ายแดง</label>
                                            <label class="underline_g" style="width:100px;"
                                                   id="cashpledgeredlabel">{{$accountingdetail->cashpledgeredlabel}}</label>
                                            <label style="width:45px; text-align:right;">(18)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">ค่า พ.ร.บ.</label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefee">{{$accountingdetail->compulsorymotorinsurancefee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefeevat">{{$accountingdetail->compulsorymotorinsurancefeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1compulsorymotorinsurancefeeincludevat">{{$accountingdetail->compulsorymotorinsurancefeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; padding-left:25px;">4.2 เงินมัดจำรถ</label>
                                            <label class="underline" style="width:100px;"
                                                   id="cashpledge">{{$accountingdetail->cashpledge}}</label>
                                            <label style="width:45px; text-align:right;">(19)</label>
                                        </div>
                                        <div style="width:540px; float:left;">
                                            <label style="width:70px;"></label>
                                            <label style="width:70px; text-align:left;">รวม</label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1totalfee">{{$accountingdetail->insurancefeewithcompulsorymotorinsurancefee}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1totalfeevat">{{$accountingdetail->insurancefeevatwithcompulsorymotorinsurancefeevat}}</label>
                                            <label style="width:30px;"></label>
                                            <label class="dashed" style="width:100px; text-align:right;"
                                                   id="note1totalfeeincludevat">{{$accountingdetail->insurancefeeincludevatwithcompulsorymotorinsurancefeeincludevat}}</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:auto; padding-left:25px;">4.2 เลขที่ใบรับเงิน</label>
                                            {!! Form::number('cashpledgereceiptbookno', null, array('style'=>'width: 50px; height: 22px; padding:0; text-align:center;','id'=>'cashpledgereceiptbookno')) !!}
                                            <label style="width:auto;">/</label>
                                            {!! Form::number('cashpledgereceiptno', null, array('style'=>'width: 50px; height: 22px; padding:0; text-align:center;','id'=>'cashpledgereceiptno')) !!}
                                            <label style="width:45px;">วันที่รับ</label>
                                            <div class="input-group" style="position: absolute; display: inline-table;">
                                                {!! Form::text('cashpledgereceiptdate', null, array('style'=>'height: 22px; padding:0;','class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'cashpledgereceiptdate')) !!}
                                                <span class="input-group-addon" style="padding:1px;">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold; padding-left:55px;">รวมรายการหักเงินมัดจำ
                                                (18)+(19)</label>
                                            <label class="underline_db" style="width:100px;"
                                                   id="totalcashpledge">{{$accountingdetail->totalcashpledge}}</label>
                                            <label style="width:45px; text-align:right;">(20)</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">
                                            <label style="width:388px; font-weight:bold;">รวมเงินสดที่ได้รับ ณ.วันรับรถ
                                                (1)+(11)+(17)-(20)</label>
                                            <label class="underline_db" style="width:100px; color: red;"
                                                   id="totalcashpledge">{{$accountingdetail->totalcashpledge}}</label>
                                            <label style="width:45px; text-align:right;">(21)</label>
                                        </div>
                                    </div>


                                    <div class="form-group" style="margin-left:10px;">
                                        <div style="width:540px; float:left; margin-right:20px;">

                                        </div>
                                        <div style="width:540px; float:left;">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">กรณีจัดไฟแนนซ์</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-1 col-sm-1"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">กรณีแลกเปลี่ยนรถเก่า หรือ กรณีรับชำระค่ารถ (เงินดาวน์)</h4>
                            <div class="widget-toolbar">
                                <a href="form-elements.html#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-body-inner" style="display: block;">
                                <div class="widget-main">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.col -->
    </div><!-- /.row -->

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

    <script type="text/javascript">
        $(document).ready(function () {
            //datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            }).next().on(ace.click_event, function () { //show datepicker when clicking on the icon
                $(this).prev().focus();
            });

            $('.chosen-select').chosen({allow_single_deselect: true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function () {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width': 189});
            }).trigger('resize.chosen');

            $('.date-picker').parent().width(140);
            $('#cashpledgereceiptdate').parent().width(98);
            $('.date-picker').width(90);
            $('#cashpledgereceiptdate').width(80);

            @if($oper == 'view')
                $("#form-accountingdetail :input").prop("disabled", true);
            $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif
        });

        $('#form-accountingdetail').submit(function () { //listen for submit event
            return true;
        });
    </script>
@endsection