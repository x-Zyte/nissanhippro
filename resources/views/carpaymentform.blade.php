@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มการชำระเงินใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขการชำระเงิน ของรายการจอง '.$carpayment->bookno.'/'.$carpayment->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลการชำระเงิน ของรายการจอง '.$carpayment->bookno.'/'.$carpayment->no)
@endif

@section('menu-selling-class','active hsub open')
@section('menu-carpayment-class','active')
@section('pathPrefix',$pathPrefix)

@section('content')
    <script type="text/javascript">
        function CarpreemptionChange(sel)
        {
            var carpreemptionid = sel.value;

            $('#carid').children('option:not(:first)').remove();

            $.get('{{$pathPrefix}}carpreemption/getbyid/'+carpreemptionid, function(data){

                $('#customer').val(data.customer);
                $('#customer2').val(data.customer);
                $("input[name=carobjectivetype][value=" + data.carobjectivetype + "]").prop('checked', true);
                $('#carmodel').val(data.carmodel);
                $('#carcolor').val(data.carcolor);
                $('#carprice').val(data.carprice.toFixed(2));

                $.each(data.cars, function(i, option) {
                    $('#carid').append($('<option/>').attr("value", option.id).text(option.chassisno+'/'+option.engineno));
                });
                $('#carid').val(null).trigger('chosen:updated');

                $("input[name=purchasetype][value=" + data.purchasetype + "]").prop('checked', true);

                $('#purchasetype').val(data.purchasetype);
                if(data.purchasetype == 0){
                    $('#down').val((parseFloat(data.carprice) - parseFloat(data.discount)).toFixed(2));
                    $(".purchasetype1ib").css("display","none");
                    $(".purchasetype1b").css("display","none");
                    $("#openbill").removeClass("input-readonly");
                    $("#openbill").removeAttr("readonly");
                }
                else if(data.purchasetype == 1){
                    $('#down').val(data.down);
                    $(".purchasetype1ib").css("display","inline-block");
                    $(".purchasetype1b").css("display","block");
                    $("#openbill").addClass("input-readonly");
                    $("#openbill").attr("readonly", "readonly");
                }

                $('#installments').val(data.installments);
                $('#interest').val(data.interest);
                $('#finacecompany').val(data.finacecompany);

                $('#yodjud').val(parseFloat(data.yodjud).toFixed(2));

                var insurancepremium = $('#insurancepremium').val();
                if(insurancepremium == null || insurancepremium == '')
                    insurancepremium = 0;
                $('#yodjudwithinsurancepremium').val((parseFloat(data.yodjud) + parseFloat(insurancepremium)).toFixed(2));

                if(data.down == null || data.down == '')
                    data.down = 0;

                if(data.purchasetype == 0){
                    $('#openbill').val((parseFloat(data.carprice) - parseFloat(data.discount)).toFixed(2));
                }
                else if(data.purchasetype == 1){
                    $('#openbill').val((parseFloat(data.yodjud) + parseFloat(data.down)).toFixed(2));
                }

                $('#realprice').val(parseFloat(data.realprice).toFixed(2));

                $('#accessoriesfee').val(data.accessoriesfee);
                $('#insurancefee').val(data.insurancefee);
                $('#compulsorymotorinsurancefee').val(data.compulsorymotorinsurancefee);
                $('#financingfee').val(data.financingfee);
                $('#transferfee').val(data.transferfee);
                $('#transferoperationfee').val(data.transferoperationfee);
                $('#registerprovince').val(data.registerprovince);
                $("input[name=registrationtype][value=" + data.registrationtype + "]").prop('checked', true);
                $('#registrationfee').val(data.registrationfee);
                $('#redlabel').val(data.redlabel);
                $('#cashpledgeredlabel').val(data.cashpledgeredlabel);

                $('#subdown').val(data.subdown);
                $('#cashpledge').val(data.cashpledge);
                $('#oldcarprice').val(data.oldcarprice);
                if(data.oldcarprice == null || data.oldcarprice == '')
                    $(".hasoldcar").css("display","none");
                else
                    $(".hasoldcar").css("display","block");

                $('#salesmanemployee').val(data.salesmanemployee);
                $('#approversemployee').val(data.approversemployee);

                if(data.carobjectivetype == 0){
                    $(".financingfee").css("display","none");
                    $(".transferfee").css("display","none");
                    $(".transferoperationfee").css("display","none");
                    $(".cashpledgeredlabel").css("display","block");
                    $(".registration").css("display","block");
                }
                else if(data.carobjectivetype == 1){
                    if(data.purchasetype == 1)
                        $(".financingfee").css("display","block");

                    $(".transferfee").css("display","block");
                    $(".transferoperationfee").css("display","block");
                    $(".cashpledgeredlabel").css("display","none");
                    $(".registration").css("display","none");
                }

                CalTotalpayments();
            });
        }

        function PaymentmodeChange(){
            var paymentmode = $("input[name=paymentmode]:checked").val();
            var $installmentsinadvance = $("#installmentsinadvance");
            if(paymentmode == 0){
                $installmentsinadvance.val(1);
            }

            AmountperinstallmentChange();
        }

        function AmountperinstallmentChange(){
            var amountperinstallment = $('#amountperinstallment').val();
            if(amountperinstallment == null || amountperinstallment == '')
                amountperinstallment = 0;

            var installmentsinadvance = $('#installmentsinadvance').val();
            if(installmentsinadvance == null || installmentsinadvance == '')
                $("#payinadvanceamount").val(null);
            else
                $("#payinadvanceamount").val((parseFloat(amountperinstallment)*parseFloat(installmentsinadvance)).toFixed(2));

            CalTotalpayments();
        }

        function InstallmentsinadvanceChange(){
            AmountperinstallmentChange();
        }

        function FirstinstallmentpayChange(){
            if($('#firstinstallmentpay').is(':checked')){
                var amountperinstallment = $('#amountperinstallment').val();
                if(amountperinstallment == null || amountperinstallment == '')
                    amountperinstallment = 0;

                $("#firstinstallmentpayamount").val(parseFloat(amountperinstallment).toFixed(2));
            }
            else
                $("#firstinstallmentpayamount").val(null);

            CalTotalpayments();
        }

        function AccessoriesfeeactuallypaidChange(){
            var accessoriesfee = $('#accessoriesfee').val();
            if(accessoriesfee == null || accessoriesfee == '')
                accessoriesfee = 0;

            var oldaccessoriesfeeincludeinyodjud = $('#accessoriesfeeincludeinyodjud').val();
            if(oldaccessoriesfeeincludeinyodjud == null || oldaccessoriesfeeincludeinyodjud == '')
                oldaccessoriesfeeincludeinyodjud = 0;

            var newaccessoriesfeeincludeinyodjud = 0;

            var accessoriesfeeactuallypaid = $('#accessoriesfeeactuallypaid').val();
            if(accessoriesfeeactuallypaid == null || accessoriesfeeactuallypaid == ''){
                $('#accessoriesfeeincludeinyodjud').val(null);
            }
            else{
                if(parseFloat(accessoriesfeeactuallypaid) > parseFloat(accessoriesfee)){
                    accessoriesfeeactuallypaid = accessoriesfee;
                    $('#accessoriesfeeactuallypaid').val(parseFloat(accessoriesfeeactuallypaid).toFixed(2));
                }

                newaccessoriesfeeincludeinyodjud = parseFloat(accessoriesfee) - parseFloat(accessoriesfeeactuallypaid);
                $('#accessoriesfeeincludeinyodjud').val(parseFloat(newaccessoriesfeeincludeinyodjud).toFixed(2));
            }

            var yodjud = $('#yodjud').val();
            if(yodjud == null || yodjud == '')
                yodjud = 0;
            yodjud = parseFloat(yodjud) - parseFloat(oldaccessoriesfeeincludeinyodjud) + parseFloat(newaccessoriesfeeincludeinyodjud);
            $('#yodjud').val(parseFloat(yodjud).toFixed(2));

            var insurancepremium = $('#insurancepremium').val();
            if(insurancepremium == null || insurancepremium == '')
                insurancepremium = 0;

            $('#yodjudwithinsurancepremium').val((parseFloat(yodjud) + parseFloat(insurancepremium)).toFixed(2));

            var openbill = $('#openbill').val();
            if(openbill == null || openbill == '')
                openbill = 0;
            openbill = parseFloat(openbill) - parseFloat(oldaccessoriesfeeincludeinyodjud) + parseFloat(newaccessoriesfeeincludeinyodjud);
            $('#openbill').val(parseFloat(openbill).toFixed(2));

            CalTotalpayments();
        }

        function InsurancepremiumChange(){
            var insurancepremium = $('#insurancepremium').val();
            if(insurancepremium == null || insurancepremium == '')
                insurancepremium = 0;

            var yodjud = $('#yodjud').val();
            if(yodjud == null || yodjud == '')
                yodjud = 0;
            $('#yodjudwithinsurancepremium').val((parseFloat(yodjud) + parseFloat(insurancepremium)).toFixed(2));
        }

        function CalTotalpayments(){
            var down = $('#down').val();
            if(down == null || down == '') down = 0;

            var firstinstallmentpayamount = $('#firstinstallmentpayamount').val();
            if(firstinstallmentpayamount == null || firstinstallmentpayamount == '') firstinstallmentpayamount = 0;

            var payinadvanceamount = $('#payinadvanceamount').val();
            if(payinadvanceamount == null || payinadvanceamount == '') payinadvanceamount = 0;

            var accessoriesfeeactuallypaid = $('#accessoriesfeeactuallypaid').val();
            if(accessoriesfeeactuallypaid == null || accessoriesfeeactuallypaid == '') accessoriesfeeactuallypaid = 0;

            var insurancefee = $('#insurancefee').val();
            if(insurancefee == null || insurancefee == '') insurancefee = 0;

            var compulsorymotorinsurancefee = $('#compulsorymotorinsurancefee').val();
            if(compulsorymotorinsurancefee == null || compulsorymotorinsurancefee == '') compulsorymotorinsurancefee = 0;

            var financingfee = $('#financingfee').val();
            if(financingfee == null || financingfee == '') financingfee = 0;

            var transferfee = $('#transferfee').val();
            if(transferfee == null || transferfee == '') transferfee = 0;

            var transferoperationfee = $('#transferoperationfee').val();
            if(transferoperationfee == null || transferoperationfee == '') transferoperationfee = 0;

            var registrationfee = $('#registrationfee').val();
            if(registrationfee == null || registrationfee == '') registrationfee = 0;

            var cashpledgeredlabel = $('#cashpledgeredlabel').val();
            if(cashpledgeredlabel == null || cashpledgeredlabel == '') cashpledgeredlabel = 0;

            var total = (parseFloat(down) + parseFloat(firstinstallmentpayamount)
                    + parseFloat(payinadvanceamount) + parseFloat(accessoriesfeeactuallypaid) + parseFloat(insurancefee)
                    + parseFloat(compulsorymotorinsurancefee) + parseFloat(financingfee) + parseFloat(transferfee)
                    + parseFloat(transferoperationfee) + parseFloat(registrationfee) + parseFloat(cashpledgeredlabel)).toFixed(2);

            $('#total').val(total);

            var subdown = $('#subdown').val();
            if(subdown == null || subdown == '') subdown = 0;

            var cashpledge = $('#cashpledge').val();
            if(cashpledge == null || cashpledge == '') cashpledge = 0;

            var oldcarprice = $('#oldcarprice').val();
            if(oldcarprice == null || oldcarprice == '') oldcarprice = 0;

            var totalpayments = (parseFloat(total) - parseFloat(subdown) - parseFloat(cashpledge) - parseFloat(oldcarprice)).toFixed(2);
            $('#totalpayments').val(totalpayments);

            //$.get('{{$pathPrefix}}carpayment/getbahttext/'+ totalpayments, function(data){
                //alert(data);
            //});

            CalOverdue();
        }

        function CalOverdue(){
            var buyerpay = $('#buyerpay').val();
            if(buyerpay == null || buyerpay == ''){
                $('#overdue').val(null);
                $('#totaloverdue').val(null);
                return;
            }

            var totalpayments = $('#totalpayments').val();
            if(totalpayments == null || totalpayments == '') totalpayments = 0;

            var overdue = (parseFloat(totalpayments) - parseFloat(buyerpay)).toFixed(2);
            $('#overdue').val(overdue);

            var overdueinterest = $('#overdueinterest').val();
            if(overdueinterest == null || overdueinterest == '') overdueinterest = 0;

            var totaloverdue = (parseFloat(overdue) + parseFloat(overdueinterest)).toFixed(2);
            $('#totaloverdue').val(totaloverdue);
        }

        function PaybytypeChange(){
            var paybytype = $("input[name=paybytype]:checked").val();
            if(paybytype == 1 || paybytype == 2){
                var overdueinstallments = $('#overdueinstallments').val();
                if(overdueinstallments == null || overdueinstallments == '') overdueinstallments = 0;

                if(overdueinstallments > 6){
                    overdueinstallments = 6;
                    $('#overdueinstallments').val(overdueinstallments);
                }

                $(".paybytype12").css("display","inline-block");

                for (i = 1; i <= 6; i++) {
                    if(i <= overdueinstallments)
                        $(".installment"+i).css("display","block");
                    else
                        $(".installment"+i).css("display","none");
                }
            }
            else{
                $(".paybytype12").css("display","none");
                $(".installment1").css("display","none");
                $(".installment2").css("display","none");
                $(".installment3").css("display","none");
                $(".installment4").css("display","none");
                $(".installment5").css("display","none");
                $(".installment6").css("display","none");
            }
        }

        function OldcarpayamountChange(){
            var oldcarpayamount = $('#oldcarpayamount').val();
            if(oldcarpayamount == null || oldcarpayamount == '') oldcarpayamount = 0;

            var oldcarprice = $('#oldcarprice').val();
            if(oldcarprice == null || oldcarprice == '') oldcarprice = 0;

            if(oldcarpayamount > oldcarprice){
                alert("ชำระค่ารถเก่า ต้องน้อยกว่าหรือเท่ากับ ค่ารถเก่า");
                $('#oldcarpayamount').val(null);
                return;
            }
        }

    </script>

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
        {!! Form::model($carpayment, array('url' => 'carpayment/save', 'id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
    @elseif($oper == 'edit')
        {!! Form::model($carpayment, array('url' => 'carpayment/save', 'id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form', 'files'=>true)) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($carpayment, array('id'=>'form-carpayment', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

        <div class="form-group" style="margin-top:10px;" >
            {!! Form::label('carpreemptionid', 'ชำระเงินการจอง เล่มที่/เลขที่', array('class' => 'col-sm-3 control-label no-padding-right')) !!}
            <div class="col-sm-3">
                @if($oper == 'new')
                    {!! Form::select('carpreemptionid', $carpreemptionselectlist, null, array('id'=>'carpreemptionid', 'class' => 'chosen-select', 'onchange'=>'CarpreemptionChange(this)')); !!}
                @else
                    {!! Form::select('carpreemptionid', $carpreemptionselectlist, null, array('id'=>'carpreemptionid', 'class' => 'chosen-select', 'onchange'=>'CarpreemptionChange(this)', 'disabled'=>'disabled')); !!}
                    {!! Form::hidden('carpreemptionid') !!}
                @endif
            </div>

            <label>
                {!! Form::checkbox('isdraft', 1, false, array('class' => 'ace')) !!}
                <span class="lbl" style="width:130px;" >  ส่งรถก่อนชำระเงิน</span>
            </label>
        </div>



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
                                    {!! Form::label('customer', 'ชื่อลูกค้า', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-4">
                                        {!! Form::text('customer', null, array('style'=>'width:235px;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>
                                    {!! Form::label('date', 'วันที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('date', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-5">
                                        <label>
                                            {!! Form::radio('carobjectivetype', 0, $carobjectivetype0, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  รถใหม่</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            {!! Form::radio('carobjectivetype', 1, $carobjectivetype1, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  รถบริษัท (รถใช้งาน รถทดสอบ)</span>&nbsp;&nbsp;
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('carmodel', 'แบบ/รุ่น', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-4">
                                        {!! Form::text('carmodel', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>
                                    {!! Form::label('carcolor', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::text('carcolor', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>

                                </div>
                                <div class="form-group">
                                    {!! Form::label('carprice', 'ราคาขาย', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('carprice', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>

                                    {!! Form::label('carid', 'รถ เลขตัวถัง/เลขเครื่อง', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('carid', $carselectlist, null, array('id'=>'carid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-1"></div>
                                    <div class="col-sm-11">
                                        <label>
                                            {!! Form::radio('purchasetype', 0, $purchasetype0, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  สด</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            {!! Form::radio('purchasetype', 1, $purchasetype1, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  ผ่อน</span>&nbsp;&nbsp;
                                        </label>
                                        {!! Form::hidden('purchasetype',null, array('id'=>'purchasetype')) !!}
                                        <label class="purchasetype1ib">
                                            {!! Form::number('installments', null, array('style'=>'width:70px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'installments')) !!}
                                            <span class="lbl"> งวด ๆ ละ</span>&nbsp;
                                            {!! Form::number('amountperinstallment', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'amountperinstallment', 'onchange'=>'AmountperinstallmentChange();')) !!}
                                        </label>
                                        <label class="purchasetype1ib">
                                            <span class="lbl"> บาท ดอกเบี้ย</span>&nbsp;
                                            {!! Form::number('interest', null, array('style'=>'width:70px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'interest')) !!}
                                            <span class="lbl"> %</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group purchasetype1b">
                                    {!! Form::label('finacecompany', 'ไฟแนนซ์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('finacecompany', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
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
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('down', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'down')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label> ( </label>

                                        <div class="purchasetype1ib" style="display: inline-block;">
                                        <label>ยอดจัด</label>
                                        {!! Form::number('yodjud', null, array('style'=>'width:100px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'yodjud')) !!}
                                        <label> เบี้ยประกันชีวิต</label>
                                        {!! Form::number('insurancepremium', null, array('style'=>'width:100px;','step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'insurancepremium', 'onchange'=>'InsurancepremiumChange();')) !!}
                                        <label> รวม</label>
                                        {!! Form::number('yodjudwithinsurancepremium', null, array('style'=>'width:100px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'yodjudwithinsurancepremium')) !!}
                                        <label> บาท</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>

                                        <label>เปิดบิล</label>
                                        {!! Form::number('openbill', null, array('style'=>'width:100px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'openbill')) !!}
                                        <label> บาท</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label>ราคาขายจริง</label>
                                        {!! Form::number('realprice', null, array('style'=>'width:100px;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'realprice')) !!}
                                        <label> บาท )</label>
                                    </div>
                                </div>
                                <div class="form-group purchasetype1b" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label>
                                        {!! Form::checkbox('firstinstallmentpay', 1, false, array('class' => 'ace', 'onchange'=>'FirstinstallmentpayChange();', 'id' => 'firstinstallmentpay')) !!}
                                        <span class="lbl" style="width:100px;" >  ชำระงวดแรก</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('firstinstallmentpayamount', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'firstinstallmentpayamount')) !!}
                                    </div>
                                </div>
                                <div class="form-group purchasetype1b" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label> จำนวนงวดชำระล่วงหน้า</label>
                                        {!! Form::number('installmentsinadvance', null, array('style'=>'width:60px;','step' => '1', 'min' => '0', 'id'=>'installmentsinadvance', 'onchange'=>'InstallmentsinadvanceChange();')) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('payinadvanceamount', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'payinadvanceamount')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> อุปกรณ์รวม</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('accessoriesfee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'accessoriesfee')) !!}
                                    </div>
                                </div>
                                <div class="form-group purchasetype1b" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:105px;"></label>
                                        <label style="width:120px;"> จ่ายจริง</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('accessoriesfeeactuallypaid', null, array('style'=>'width:100%;','step' => '1', 'min' => '0','id'=>'accessoriesfeeactuallypaid', 'onchange'=>'AccessoriesfeeactuallypaidChange();')) !!}
                                    </div>
                                </div>
                                <div class="form-group purchasetype1b" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:105px;"></label>
                                        <label style="width:120px;"> เหลือรวมเข้ายอดจัด</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('accessoriesfeeincludeinyodjud', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'accessoriesfeeincludeinyodjud')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> เบี้ยประกันชั้น 1,3</label>
                                        <label style="width:45px;"> บริษัท</label>
                                        {!! Form::select('insurancecompanyid', $insurancecompanyselectlist, null, array('id'=>'insurancecompanyid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('insurancefee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'insurancefee')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"></label>
                                        <label style="width:45px;"> ทุน</label>
                                        {!! Form::number('capitalinsurance', null, array('style'=>'width:100px;','step' => '1', 'min' => '0')) !!}
                                        <label> บาท</label>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> เบี้ย พ.ร.บ.</label>
                                        <label style="width:45px;"> บริษัท</label>
                                        {!! Form::select('compulsorymotorinsurancecompanyid', $insurancecompanyselectlist, null, array('id'=>'compulsorymotorinsurancecompanyid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('compulsorymotorinsurancefee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'compulsorymotorinsurancefee')) !!}
                                    </div>
                                </div>
                                <div class="form-group financingfee" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ค่าจัดไฟแนนซ์</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('financingfee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'financingfee')) !!}
                                    </div>
                                </div>
                                <div class="form-group transferfee" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ค่าโอน</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('transferfee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'transferfee')) !!}
                                    </div>
                                </div>
                                <div class="form-group transferoperationfee" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ค่าดำเนินการโอน</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('transferoperationfee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'transferoperationfee')) !!}
                                    </div>
                                </div>
                                <div class="form-group registration" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> ทะเบียน</label>
                                        {!! Form::text('registerprovince', null, array('style'=>'width:150px;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'registerprovince')) !!}
                                        &nbsp;&nbsp;
                                        <label>
                                            {!! Form::radio('registrationtype', 0, $registrationtype0, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  บุคคล</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            {!! Form::radio('registrationtype', 1, $registrationtype1, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  นิติบุคคล</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            {!! Form::radio('registrationtype', 2, $registrationtype2, array('class' => 'ace', 'disabled'=>'disabled')) !!}
                                            <span class="lbl">  ราชการ</span>
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('registrationfee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'registrationfee')) !!}
                                    </div>
                                </div>
                                <div class="form-group cashpledgeredlabel" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> มัดจำป้ายแดง</label>
                                        {!! Form::text('redlabel', null, array('style'=>'width:150px;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'redlabel')) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('cashpledgeredlabel', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'cashpledgeredlabel')) !!}
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:170px; text-align:right; padding-right:10px; font-weight:bold;"> รวมเงิน</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('total', null, array('style'=>'width:100%; border-color:black; color:black;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'total')) !!}
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"> <span style=" text-decoration:underline;">หัก</span> Sub ดาวน์ ทั้งหมด (Sub Down + บวกอุปกรณ์หลอก)</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('subdown', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'subdown')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"><span style=" text-decoration:underline;">หัก</span> มัดจำรถ</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('cashpledge', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'cashpledge')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:180px;"><span style=" text-decoration:underline;">หัก</span> ค่ารถเก่า</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('oldcarprice', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'oldcarprice')) !!}
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-7">
                                        <label style="width:170px; text-align:right; padding-right:10px; font-weight:bold;"> ชำระเงินรวม</label>
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::number('totalpayments', null, array('style'=>'width:100%; border-color:black; color:black;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'totalpayments')) !!}
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
                        <h4 class="widget-title">แบ่งชำระค่ารถเป็นงวด</h4>
                        <div class="widget-toolbar">
                            <a href="form-elements.html#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-body-inner" style="display: block;">
                            <div class="widget-main">
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        <label style="width:180px;">
                                            ผู้ซื้อได้ชำระเงินเป็นจำนวน&nbsp;
                                        </label>
                                        {!! Form::number('buyerpay', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'buyerpay', 'onchange'=>'CalOverdue();')) !!}
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label style="width:165px;">
                                            สำหรับส่วนที่ค้างชำระอีก&nbsp;
                                        </label>
                                        {!! Form::number('overdue', null, array('class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'overdue')) !!}
                                    </div>
                                </div>
                                <div class="form-group" style="padding-left:20px;">
                                    <div class="col-sm-12">
                                        <label style="width:180px;">
                                            พร้อมดอกเบี้ยจำนวน&nbsp;
                                        </label>
                                        {!! Form::number('overdueinterest', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinterest', 'onchange'=>'CalOverdue();')) !!}
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label style="width:165px;">
                                            รวมเป็นเงินค้างชำระทั้งสิ้น&nbsp;
                                        </label>
                                        {!! Form::number('totaloverdue', null, array('class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'totaloverdue')) !!}
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
                                                {!! Form::radio('paybytype', 0, false, array('class' => 'ace', 'onchange'=>'PaybytypeChange();')) !!}
                                                <span class="lbl">  รถ</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('paybytype', 1, false, array('class' => 'ace', 'onchange'=>'PaybytypeChange();')) !!}
                                                <span class="lbl">  เงินสด</span>&nbsp;&nbsp;
                                            </label>
                                            <label>
                                                {!! Form::radio('paybytype', 2, false, array('class' => 'ace', 'onchange'=>'PaybytypeChange();')) !!}
                                                <span class="lbl">  อื่นๆ</span>&nbsp;&nbsp;
                                            </label>
                                            &nbsp;
                                            <div class="form-group paybytype12" style="display: none">
                                            {!! Form::text('paybyotherdetails') !!}
                                            <label> จำนวน </label>
                                            {!! Form::number('overdueinstallments', null, array('style'=>'width:60px;','step' => '1', 'min' => '1', 'max' => '6', 'id'=>'overdueinstallments', 'onchange'=>'PaybytypeChange();')) !!}
                                            <label> งวด  ดังนี้</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment1" style="display: none; padding-left:60px;  padding-top:10px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate1', 'งวดที่ 1 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate1', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate1')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount1', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount1', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount1')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment2" style="display: none; padding-left:60px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate2', 'งวดที่ 2 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate2', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate2')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount2', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount2', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount2')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment3" style="display: none; padding-left:60px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate3', 'งวดที่ 3 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate3', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate3')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount3', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount3', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount3')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment4" style="display: none; padding-left:60px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate4', 'งวดที่ 4 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate4', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate4')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount4', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount4', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount4')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment5" style="display: none; padding-left:60px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate5', 'งวดที่ 5 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate5', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate5')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount5', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount5', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount5')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group installment6" style="display: none; padding-left:60px;">
                                    <div class="col-sm-12">
                                        {!! Form::label('overdueinstallmentdate6', 'งวดที่ 6 วันที่', array('class' => 'control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('overdueinstallmentdate6', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'overdueinstallmentdate6')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                            </div>
                                        </div>
                                        {!! Form::label('overdueinstallmentamount6', 'จำนวน', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'float:left;')) !!}
                                        <div class="col-sm-2">
                                            {!! Form::number('overdueinstallmentamount6', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'overdueinstallmentamount6')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="padding-top:15px;">
                                    <label class="col-sm-1 control-label no-padding-right" for="customerbuy">ผู้ซื้อ</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('customer2', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'customer2')) !!}
                                    </div>
                                    <label class="col-sm-2 control-label no-padding-right" for="salesperson">พนักงานขาย</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('salesmanemployee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'salesmanemployee')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="customerbuyoldcar">ผู้ซื้อรถเก่า</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('oldcarbuyername', null, array('style'=>'width:100%;')) !!}
                                    </div>
                                    <label class="col-sm-2 control-label no-padding-right" for="approver">ผู้อนุมัติ</label>
                                    <div class="col-sm-3">
                                        {!! Form::text('approversemployee', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'approversemployee')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>


        <!-- For Money Officer -->
        <div class="row hasoldcar">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">กรณีมีรถเก่า</h4>
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
                                            {!! Form::number('oldcarpayamount', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'oldcarpayamount', 'onchange'=>'OldcarpayamountChange();')) !!}
                                        </label>
                                        <label>&nbsp;
                                            <span>&nbsp;&nbsp;&nbsp; โดย&nbsp;&nbsp;&nbsp;</span>
                                            {!! Form::radio('oldcarpaytype', 0, false, array('class' => 'ace')) !!}
                                            <span class="lbl" style="margin-right:15px;	">  เงินสด</span>
                                        </label>
                                        <label>
                                            {!! Form::radio('oldcarpaytype', 1, false, array('class' => 'ace')) !!}
                                            <span class="lbl" style="margin-right:15px;"> เช็ค</span>&nbsp;
                                        </label>
                                        <label>
                                            {!! Form::radio('oldcarpaytype', 2, false, array('class' => 'ace')) !!}
                                            <span class="lbl"> โอน</span>&nbsp;
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right" for="receivedpaymentdate">วันที่รับเงิน</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('oldcarpaydate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'oldcarpaydate', 'id'=>'oldcarpaydate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <label class="col-sm-1 control-label no-padding-right" for="receivedpaymentby">ผู้รับเงิน</label>
                                    <div class="col-sm-3">
                                        {!! Form::select('payeeemployeeid', $payeeemployeeselectlist, null, array('id'=>'payeeemployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><br class="hasoldcar">

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title">ใบส่งรถ</h4>
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
                                <div class="col-sm-8">
                                    <div class="col-sm-6">
                                        <label style="margin-left: 20px;">
                                            <span> เล่มที่</span>&nbsp;&nbsp;
                                        </label>
                                        {!! Form::number('deliverycarbookno',null,array('min' => '0','max' => '999')) !!}
                                        &nbsp;&nbsp;
                                        <label>
                                            <span> เลขที่</span>&nbsp;&nbsp;
                                        </label>
                                        {!! Form::number('deliverycarno',null,array('min' => '0','max' => '9999')) !!}
                                        &nbsp;&nbsp;
                                        <label>
                                            <span> วันที่</span>&nbsp;&nbsp;
                                        </label>
                                    </div>
                                    <div class="col-sm-1" style="margin-left: -30px;">
                                        <div class="input-group">
                                            {!! Form::text('deliverycardate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'deliverycardate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-1">
                                    <label style="margin-left: 30px;">
                                        <span> รูป</span>&nbsp;&nbsp;
                                    </label>
                                </div>

                                @if($oper != 'new' && $carpayment->deliverycarfilepath != null)
                                    <div class="col-xs-2" style="margin-left: -30px;">
                                        <a href = "{{ $carpayment->deliverycarfilepath }}" data-lightbox="' + cellvalue + '">View photo</a>
                                    </div>

                                    <div class="col-xs-1" style="margin-left: -90px;">
                                        {!! Form::file('deliverycarfile','',array('id'=>'deliverycarfile')) !!}
                                    </div>
                                @else
                                    <div class="col-xs-1" style="margin-left: -30px;">
                                        {!! Form::file('deliverycarfile','',array('id'=>'deliverycarfile')) !!}
                                    </div>
                                @endif
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

    <script type="text/javascript">
        $(document).ready(function() {
            var carobjectivetype = jQuery( 'input[name=carobjectivetype]:checked' ).val();
            var purchasetype = jQuery( 'input[name=purchasetype]:checked' ).val();

            if(purchasetype == 0){
                $(".purchasetype1ib").css("display","none");
                $(".purchasetype1b").css("display","none");
                $("#openbill").removeClass("input-readonly");
                $("#openbill").removeAttr("readonly");
            }
            else if(purchasetype == 1){
                $(".purchasetype1ib").css("display","inline-block");
                $(".purchasetype1b").css("display","block");
                $("#openbill").addClass("input-readonly");
                $("#openbill").attr("readonly", "readonly");
            }

            if(carobjectivetype == 0){
                $(".financingfee").css("display","none");
                $(".transferfee").css("display","none");
                $(".transferoperationfee").css("display","none");
                $(".cashpledgeredlabel").css("display","block");
                $(".registration").css("display","block");
            }
            else if(carobjectivetype == 1){
                if(purchasetype == 1)
                    $(".financingfee").css("display","block");

                $(".transferfee").css("display","block");
                $(".transferoperationfee").css("display","block");
                $(".cashpledgeredlabel").css("display","none");
                $(".registration").css("display","none");
            }

            var oldcarprice = $('#oldcarprice').val();
            if(oldcarprice == null || oldcarprice == '')
                $(".hasoldcar").css("display","none");
            else
                $(".hasoldcar").css("display","block");

            PaybytypeChange();

            //datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            }).next().on(ace.click_event, function(){ //show datepicker when clicking on the icon
                $(this).prev().focus();
            });

            $('.chosen-select').chosen({allow_single_deselect: true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function () {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width': 189});

                $('#carid').width(300);
                $('#carid_chosen').width(300);

                $('#payeeemployeeid').width(250);
                $('#payeeemployeeid_chosen').width(250);
            }).trigger('resize.chosen');

            $('.date-picker').parent().width(140);
            $('.date-picker').width(90);

            @if($oper == 'view')
                $("#form-carpayment :input").prop("disabled", true);
                $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif
        });

        $('#form-carpayment').submit(function(){ //listen for submit event

            var paybytype = $("input[name=paybytype]:checked").val();
            var overdueinstallments = $("#overdueinstallments").val();
            var totaloverdue = $("#totaloverdue").val();
            var totalamount = 0;
            if((paybytype == 1 || paybytype == 2) && overdueinstallments != null && overdueinstallments != '' && overdueinstallments != 0){
                for (i = 1; i <= overdueinstallments; i++) {
                    var amount = $("#overdueinstallmentamount"+i).val();
                    if(amount == null || amount == '') amount = 0;
                    totalamount = parseFloat(totalamount) + parseFloat(amount);
                }

                if(parseFloat(totaloverdue) != parseFloat(totalamount)){
                    alert("จำนวนเงินรวมของทุกงวด ต้องเท่ากับ จำนวนเงินรวมค้างชำระทั้งสิ้น");
                    return false;
                }
            }

            return true;
        });
    </script>
@endsection