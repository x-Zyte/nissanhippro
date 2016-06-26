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
    </style>

    <script type="text/javascript">
        function CarpaymentChange(sel) {
            var carpaymentid = sel.value;

            $.get('{{$pathPrefix}}carpayment/getforaccountingdetailbyid/' + carpaymentid, function (data) {
                $("#branchname").text(data.branchname);
                $("#customername").text(data.customername);
                $("#date").text(data.date);
                $("#openbill").text(parseFloat(data.openbill).toFixed(2));

                var additionalopenbill = $('#additionalopenbill').val();
                if (additionalopenbill == null || additionalopenbill == '')
                    additionalopenbill = 0;
                $("#finalopenbill").text((parseFloat(data.openbill) + parseFloat(additionalopenbill)).toFixed(2));

                $("#carpriceinpricelist").text(parseFloat(data.carpriceinpricelist).toFixed(2));
                $("#colorprice").text(parseFloat(data.colorprice).toFixed(2));
                $("#carwithcolorprice").text(parseFloat(data.carwithcolorprice).toFixed(2));
                $("#accessoriesfeeincludeinyodjud").text(parseFloat(data.accessoriesfeeincludeinyodjud).toFixed(2));
                $("#plusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                $("#minusfakeaccessories").text(parseFloat(data.fakeaccessories).toFixed(2));
                $("#discount").text(parseFloat(data.discount).toFixed(2));
                $("#subdown").text(parseFloat(data.subdown).toFixed(2));
                $("#realsalesprice").text(parseFloat(data.realsalesprice).toFixed(2));
                $("#realsalesprice2").text(parseFloat(data.realsalesprice).toFixed(2));
                var vat = (parseFloat(data.openbill) + parseFloat(additionalopenbill)) * parseFloat(0.07);
                $("#vatoffinalopenbill").text(parseFloat(vat).toFixed(2));
                $("#finalopenbillwithoutvat").text((parseFloat(data.openbill) + parseFloat(additionalopenbill) - parseFloat(vat)).toFixed(2));
                $("#realsalespricewithoutvat").text((parseFloat(data.realsalesprice) - parseFloat(vat)).toFixed(2));
                $("#accessoriesfeeactuallypaid").text(parseFloat(data.accessoriesfeeactuallypaid).toFixed(2));
            });
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
        {!! Form::label('carpaymentid', 'รายละเอียดเพื่อการบันทึกบัญชีของการจอง เล่มที่/เลขที่', array('class' => 'col-sm-3 control-label no-padding-right')) !!}
        <div class="col-sm-3">
            @if($oper == 'new')
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)')) !!}
            @else
                {!! Form::select('carpaymentid', $carpaymentselectlist, null, array('id'=>'carpaymentid', 'class' => 'chosen-select', 'onchange'=>'CarpaymentChange(this)', 'disabled'=>'disabled')) !!}
                {!! Form::hidden('carpaymentid') !!}
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 ">

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
            <br>

            <table style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                <tbody>
                <tr>
                    <td><label style="width: 25px; margin-top: 10px;"></label></td>
                    <td><label style="width: 350px; margin-top: 10px;">1. รายได้ค่ารถ</label></td>
                    <td><label style="width: 100px; margin-top: 10px;"></label></td>
                    <td><label style="width: 60px; margin-top: 10px;"></label></td>

                    <td><label style="width: 140px; padding-left: 15px; font-weight: bold; margin-top: 10px;">ราคาเปิดบิล</label>
                    </td>
                    <td><label style="width: 100px; font-weight:bold; text-align:right; margin-top: 10px;"
                               id="openbill">{{$accountingdetail->openbill}}</label></td>
                    <td><label style="width: 30px; text-align: center; margin-top: 10px;">+/-</label></td>
                    <td>{!! Form::number('additionalopenbill', null, array('style'=>'width: 110px; margin-top: 10px; margin-bottom: 5px;','id'=>'additionalopenbill')) !!}</td>
                    <td><label style="width: 30px; text-align: center; margin-top: 10px;">=</label></td>
                    <td><label style="width: 100px; font-weight:bold; text-align:right; color:red; margin-top: 10px;"
                               id="finalopenbill">{{$accountingdetail->finalopenbill}}</label></td>
                    <td><label style="width: 15px; margin-top: 10px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;">ราคารถขายจริง (รวม VAT)</label></td>
                    <td class="underline_db"><label style="width: 100px; font-weight:bold;"
                                                    id="realsalesprice">{{$accountingdetail->realsalesprice}}</label>
                    </td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;">(1)</label></td>

                    <td style="border-top: 1px solid black; border-left: 1px solid black;"><label
                                style="width: 140px; padding-left: 15px;">ราคาตั้งขาย + สี</label></td>
                    <td class="dotted" style="border-top: 1px solid black;"><label
                                style="width: 100px; text-align:right;"
                                id="carpriceinpricelist">{{$accountingdetail->carpriceinpricelist}}</label></td>
                    <td style="border-top: 1px solid black;"><label style="width: 30px; text-align: center;">+</label>
                    </td>
                    <td class="dotted" style="border-top: 1px solid black;"><label
                                style="width: 110px; text-align:right;"
                                id="colorprice">{{$accountingdetail->colorprice}}</label></td>
                    <td style="border-top: 1px solid black;"><label style="width: 30px; text-align: center;">=</label>
                    </td>
                    <td class="dotted" style="border-top: 1px solid black; border-right: 1px solid black;"><label
                                style="width: 100px; text-align:right;"
                                id="carwithcolorprice">{{$accountingdetail->carwithcolorprice}}</label></td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;">หัก VAT 7% (ตามบัญชี 1)</label></td>
                    <td class="underline"><label style="width: 100px; font-weight:bold;"
                                                 id="vatoffinalopenbill">{{$accountingdetail->vatoffinalopenbill}}</label>
                    </td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;">(2)</label></td>

                    <td style="border-left: 1px solid black;"><label style="width: 140px; padding-left: 15px;">ขายอุปกรณ์จริง</label>
                    </td>
                    <td class="dotted"><label style="width: 100px; text-align:right;"
                                              id="accessoriesfeeincludeinyodjud">{{$accountingdetail->accessoriesfeeincludeinyodjud}}</label>
                    </td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 110px; text-align: right;"></label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td style="border-right: 1px solid black;"><label style="width: 100px; text-align: right;"></label>
                    </td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;">ราคาค่ารถ (ตามบัญชี 1) ไม่รวม VAT (1)* -
                            (2)</label></td>
                    <td class="underline"><label style="width: 100px; font-weight:bold;"
                                                 id="finalopenbillwithoutvat">{{$accountingdetail->finalopenbillwithoutvat}}</label>
                    </td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;">(3)</label></td>

                    <td style="border-left: 1px solid black;"><label style="width: 140px; padding-left: 15px;">บวกอุปกรณ์
                            (หลอก)</label></td>
                    <td class="dotted"><label style="width: 100px; text-align:right;"
                                              id="plusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label>
                    </td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 110px; text-align: right;">หักอุปกรณ์ (หลอก)</label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td class="dotted" style="border-right: 1px solid black;"><label
                                style="width: 100px; text-align: right;"
                                id="minusfakeaccessories">{{$accountingdetail->fakeaccessories}}</label></td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;">ราคาค่ารถ (ตามบัญชี 2) ไม่รวม VAT (1) -
                            (2)</label></td>
                    <td class="underline"><label style="width: 100px; font-weight:bold;"
                                                 id="realsalespricewithoutvat">{{$accountingdetail->realsalespricewithoutvat}}</label>
                    </td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;">(4)</label></td>

                    <td style="border-left: 1px solid black;"><label style="width: 140px; padding-left: 15px;"><span
                                    style="font-weight:bold;text-decoration:underline;">หัก</span> ส่วนลด</label></td>
                    <td class="dotted"><label style="width: 100px; text-align:right;"
                                              id="discount">{{$accountingdetail->discount}}</label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 110px; text-align: right;"></label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td style="border-right: 1px solid black;"><label style="width: 100px; text-align: right;"></label>
                    </td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px;">2. รายได้อื่น</label></td>
                    <td><label style="width: 100px;"></label></td>
                    <td><label style="width: 60px;"></label></td>

                    <td style="border-left: 1px solid black;"><label style="width: 140px; padding-left: 15px;"><span
                                    style="font-weight:bold;text-decoration:underline;">หัก</span> Sub down</label></td>
                    <td class="dotted"><label style="width: 100px; text-align:right;"
                                              id="subdown">{{$accountingdetail->subdown}}</label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 110px; text-align: right;"></label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td style="border-right: 1px solid black;"><label style="width: 100px; text-align: right;"></label>
                    </td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;">2.1 รายได้ค่าอุปกรณ์</label></td>
                    <td class="underline_g"><label style="width: 100px; font-weight:bold;"
                                                   id="accessoriesfeeactuallypaid">{{$accountingdetail->accessoriesfeeactuallypaid}}</label>
                    </td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;">(5)</label></td>

                    <td style="border-left: 1px solid black; border-bottom: 1px solid black;"><label
                                style="width: 140px; padding-left: 15px; font-weight:bold; text-decoration:underline; color:red;">คงเหลือขายจริง</label>
                    </td>
                    <td class="dotted" style="border-bottom: 1px solid black;"><label
                                style="width: 100px; text-align:right; font-weight:bold; color:red;"
                                id="realsalesprice2">{{$accountingdetail->realsalesprice}}</label></td>
                    <td style="border-bottom: 1px solid black;"><label style="width: 30px; text-align: center;"></label>
                    </td>
                    <td style="border-bottom: 1px solid black;"><label style="width: 110px; text-align: right;"></label>
                    </td>
                    <td style="border-bottom: 1px solid black;"><label style="width: 30px; text-align: center;"></label>
                    </td>
                    <td style="border-right: 1px solid black; border-bottom: 1px solid black;"><label
                                style="width: 100px; text-align: right;"></label></td>
                    <td><label style="width: 15px;"></label></td>
                </tr>

                <tr>
                    <td><label style="width: 25px;"></label></td>
                    <td><label style="width: 350px; padding-left: 25px;"></label></td>
                    <td><label style="width: 100px; font-weight:bold;"></label></td>
                    <td><label style="width: 60px; text-align:right; padding-right: 10px;"></label></td>

                    <td><label style="width: 140px; padding-left: 15px;"></label></td>
                    <td><label style="width: 100px; text-align:right;"></label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 110px; text-align: right;"></label></td>
                    <td><label style="width: 30px; text-align: center;"></label></td>
                    <td><label style="width: 100px; text-align: right;"></label></td>
                    <td><label style="width: 15px;"></label></td>
                </tr>
                </tbody>
            </table>

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
                                    <table>
                                        <tr>
                                            <td></td>
                                        </tr>
                                    </table>
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
            $('.date-picker').width(90);

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