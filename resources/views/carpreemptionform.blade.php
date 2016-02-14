@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มใบจองใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขใบจอง '.$carpreemption->bookno.'/'.$carpreemption->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลใบจอง '.$carpreemption->bookno.'/'.$carpreemption->no)
@endif

@section('menu-carpreemption-class','active')
@section('pathPrefix',$pathPrefix)

@section('content')
    <script type="text/javascript">
        function NewCustomer(customernew)
        {
            if(customernew == 'y')
            {
                $(".new-customer").css("display","");
                $(".old-customer").css("display","none");

                $('#bookingcustomerid').val(null).trigger('chosen:updated');
                $('#bookingcustomerfirstname').val(null);
                $('#bookingcustomerlastname').val(null);
                $('#bookingcustomeraddress').val(null);
                $('#bookingcustomerprovinceid').val(null).trigger('chosen:updated');
                $('#bookingcustomeramphurid').children('option:not(:first)').remove();
                $('#bookingcustomeramphurid').val(null).trigger('chosen:updated');
                $('#bookingcustomerdistrictid').children('option:not(:first)').remove();
                $('#bookingcustomerdistrictid').val(null).trigger('chosen:updated');
                $('#bookingcustomerzipcode').val(null);
                $('#bookingcustomerphone1').val(null);
                $('#bookingcustomerphone2').val(null);
                $('#bookingcustomeroccupationid').val(null).trigger('chosen:updated');
                $('#bookingcustomerbirthdate').val(null);
            }
            else
            {
                $(".new-customer").css("display","none");
                $(".old-customer").css("display","inline-block");

                $('#bookingcustomerid').val(null).trigger('chosen:updated');
                $('#bookingcustomerfirstname').val(null);
                $('#bookingcustomerlastname').val(null);
                $('#bookingcustomeraddress').val(null);
                $('#bookingcustomerprovinceid').val(null).trigger('chosen:updated');
                $('#bookingcustomeramphurid').children('option:not(:first)').remove();
                $('#bookingcustomeramphurid').val(null).trigger('chosen:updated');
                $('#bookingcustomerdistrictid').children('option:not(:first)').remove();
                $('#bookingcustomerdistrictid').val(null).trigger('chosen:updated');
                $('#bookingcustomerzipcode').val(null);
                $('#bookingcustomerphone1').val(null);
                $('#bookingcustomerphone2').val(null);
                $('#bookingcustomeroccupationid').val(null).trigger('chosen:updated');
                $('#bookingcustomerbirthdate').val(null);
            }
        }

        function BookingCustomerChange(sel)
        {
            var customerid = sel.value;
            $('#bookingcustomeramphurid').children('option:not(:first)').remove();
            $('#bookingcustomerdistrictid').children('option:not(:first)').remove();

            $.get('{{$pathPrefix}}customer/getbyid/'+customerid, function(data){
                $('#bookingcustomeraddress').val(data.address);
                $('#bookingcustomerprovinceid').val(data.addprovinceid).trigger('chosen:updated');

                $.each(data.amphurs, function(i, option) {
                    $('#bookingcustomeramphurid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#bookingcustomeramphurid').val(data.amphurid).trigger('chosen:updated');

                $.each(data.districts, function(i, option) {
                    $('#bookingcustomerdistrictid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#bookingcustomerdistrictid').val(data.districtid).trigger('chosen:updated');

                $('#bookingcustomerzipcode').val(data.zipcode);
                $('#bookingcustomerphone1').val(data.phone1);
                $('#bookingcustomerphone2').val(data.phone2);
                $('#bookingcustomeroccupationid').val(data.occupationid).trigger('chosen:updated');
                $('#bookingcustomerbirthdate').datepicker('setDate', data.birthdate);
            });
        }

        function BuyerCustomerChange(sel)
        {
            var customerid = sel.value;
            $('#buyercustomeramphurid').children('option:not(:first)').remove();
            $('#buyercustomerdistrictid').children('option:not(:first)').remove();

            $.get('{{$pathPrefix}}customer/getbyid/'+customerid, function(data){
                $('#buyercustomeraddress').val(data.address);
                $('#buyercustomerprovinceid').val(data.addprovinceid).trigger('chosen:updated');

                $.each(data.amphurs, function(i, option) {
                    $('#buyercustomeramphurid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#buyercustomeramphurid').val(data.amphurid).trigger('chosen:updated');

                $.each(data.districts, function(i, option) {
                    $('#buyercustomerdistrictid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#buyercustomerdistrictid').val(data.districtid).trigger('chosen:updated');

                $('#buyercustomerzipcode').val(data.zipcode);
                $('#buyercustomerphone1').val(data.phone1);
                $('#buyercustomerphone2').val(data.phone2);
                $('#buyercustomeroccupationid').val(data.occupationid).trigger('chosen:updated');
                $('#buyercustomerbirthdate').datepicker('setDate', data.birthdate);
            });
        }

        function BookingCustomerProvinceChange(sel) {
            var provinceid = sel.value;
            $('#bookingcustomeramphurid').children('option:not(:first)').remove();
            $('#bookingcustomerdistrictid').children('option:not(:first)').remove();
            $('#bookingcustomerzipcode').val(null);
            $.get('{{$pathPrefix}}amphur/read/'+provinceid, function(data){
                $.each(data, function(i, option) {
                    $('#bookingcustomeramphurid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#bookingcustomeramphurid').val(null).trigger('chosen:updated');
                $('#bookingcustomerdistrictid').val(null).trigger('chosen:updated');
            });
        }

        function BuyerCustomerProvinceChange(sel) {
            var provinceid = sel.value;
            $('#buyercustomeramphurid').children('option:not(:first)').remove();
            $('#buyercustomerdistrictid').children('option:not(:first)').remove();
            $('#buyercustomerzipcode').val(null);
            $.get('{{$pathPrefix}}amphur/read/'+provinceid, function(data){
                $.each(data, function(i, option) {
                    $('#buyercustomeramphurid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#buyercustomeramphurid').val(null).trigger('chosen:updated');
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
            });
        }

        function BookingCustomerAmphurChange(sel) {
            var amphurid = sel.value;
            $('#bookingcustomerdistrictid').children('option:not(:first)').remove();
            $('#bookingcustomerzipcode').val(null);
            $.get('{{$pathPrefix}}district/read/'+amphurid, function(data){
                $.each(data, function(i, option) {
                    $('#bookingcustomerdistrictid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#bookingcustomerdistrictid').val(null).trigger('chosen:updated');
            });
        }

        function BuyerCustomerAmphurChange(sel) {
            var amphurid = sel.value;
            $('#buyercustomerdistrictid').children('option:not(:first)').remove();
            $('#buyercustomerzipcode').val(null);
            $.get('{{$pathPrefix}}district/read/'+amphurid, function(data){
                $.each(data, function(i, option) {
                    $('#buyercustomerdistrictid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
            });
        }

        function BookingCustomerDistrictChange(sel) {
            var districtid = sel.value;
            $('#bookingcustomerzipcode').val(null);
            $.get('{{$pathPrefix}}zipcode/read/'+districtid, function(data){
                $('#bookingcustomerzipcode').val(data.code);
            });
        }

        function BuyerCustomerDistrictChange(sel) {
            var districtid = sel.value;
            $('#buyercustomerzipcode').val(null);
            $.get('{{$pathPrefix}}zipcode/read/'+districtid, function(data){
                $('#buyercustomerzipcode').val(data.code);
            });
        }

        function CarModelChange(sel) {
            var carmodelid = sel.value;
            if(carmodelid == null || carmodelid == '') return;
            $('#carsubmodelid').children('option:not(:first)').remove();
            $('#colorid').children('option:not(:first)').remove();

            var registrationtype = $("input[name=registrationtype]:checked").val();

            $.get('{{$pathPrefix}}carmodel/getsubmodelandcolorbyid/'+carmodelid+'/'+registrationtype, function(data){
                $.each(data.carsubmodels, function(i, option) {
                    $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#carsubmodelid').val(null).trigger('chosen:updated');

                $.each(data.colors, function(i, option) {
                    $('#colorid').append($('<option/>').attr("value", option.id).text(option.code + ' - ' + option.name));
                });
                $('#colorid').val(null).trigger('chosen:updated');

                $('#registrationfee').val(data.registercost);
                $('#compulsorymotorinsurancefee').val(data.actcharged);
            });
        }

        function GetPrice() {
            var carsubmodelid = $("#carsubmodelid").chosen().val()
            var date = $('#date').val();
            if(carsubmodelid == null || carsubmodelid == '' || date == null || date == '') return;

            $.get('{{$pathPrefix}}pricelist/getprice/'+carsubmodelid+'/'+date, function(data){
                $('#pricelistid').children('option').remove();
                if(data.count == 0){
                    $('#pricelistid').append($('<option/>').attr("value", null).text('เลือกราคา'));
                    alert("ไม่พบข้อูลราคา กรุณาเพิ่มข้อมูล ราคา ของรถรุ่นนี้ แล้วทำการเลือกรุ่น ใหม่อีกครั้ง");
                }

                $.each(data.pricelists, function(i, option) {
                    $('#pricelistid').append($('<option/>').attr("value", option.id).text(option.sellingpricewithaccessories));
                });
                $('#pricelistid').trigger('chosen:updated');
            });
        }

        function RegistrationTypeChange(){
            var carmodelid = $("#carmodelid").chosen().val()
            if(carmodelid == null || carmodelid == '') return;
            var registrationtype = $("input[name=registrationtype]:checked").val();
            $.get('{{$pathPrefix}}carmodel/getregistrationcost/'+carmodelid+'/'+registrationtype, function(data){
                $('#registrationfee').val(data.registercost);
            });
        }

        function OldCarBrandChange(sel) {
            var carbrandid = sel.value;
            $('#oldcarmodelid').children('option:not(:first)').remove();
            $.get('{{$pathPrefix}}carbrand/getmodelbyid/'+carbrandid, function(data){
                $.each(data, function(i, option) {
                    $('#oldcarmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#oldcarmodelid').val(null).trigger('chosen:updated');
            });
        }

        function BuyCustomerType(buyertype)
        {
            if(buyertype == 'same')
            {
                $(".same-customer").css("display","none");
                $(".insystem-customer").css("display","none");
                $(".newbuy-customer").css("display","none");

                $('#buyercustomerid').val(null).trigger('chosen:updated');
                $('#buyercustomerfirstname').val(null);
                $('#buyercustomerlastname').val(null);
                $('#buyercustomeraddress').val(null);
                $('#buyercustomerprovinceid').val(null).trigger('chosen:updated');
                $('#buyercustomeramphurid').children('option:not(:first)').remove();
                $('#buyercustomeramphurid').val(null).trigger('chosen:updated');
                $('#buyercustomerdistrictid').children('option:not(:first)').remove();
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
                $('#buyercustomerzipcode').val(null);
                $('#buyercustomerphone1').val(null);
                $('#buyercustomerphone2').val(null);
                $('#buyercustomeroccupationid').val(null).trigger('chosen:updated');
                $('#buyercustomerbirthdate').val(null);
            }
            else if(buyertype == 'insystem')
            {
                $(".insystem-customer").css("display","inline-block");
                $(".same-customer").css("display","");
                $(".newbuy-customer").css("display","none");

                $('#buyercustomerid').val(null).trigger('chosen:updated');
                $('#buyercustomerfirstname').val(null);
                $('#buyercustomerlastname').val(null);
                $('#buyercustomeraddress').val(null);
                $('#buyercustomerprovinceid').val(null).trigger('chosen:updated');
                $('#buyercustomeramphurid').children('option:not(:first)').remove();
                $('#buyercustomeramphurid').val(null).trigger('chosen:updated');
                $('#buyercustomerdistrictid').children('option:not(:first)').remove();
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
                $('#buyercustomerzipcode').val(null);
                $('#buyercustomerphone1').val(null);
                $('#buyercustomerphone2').val(null);
                $('#buyercustomeroccupationid').val(null).trigger('chosen:updated');
                $('#buyercustomerbirthdate').val(null);
            }
            else if(buyertype == 'newbuyer')
            {
                $(".newbuy-customer").css("display","");
                $(".same-customer").css("display","");
                $(".insystem-customer").css("display","none");

                $('#buyercustomerid').val(null).trigger('chosen:updated');
                $('#buyercustomerfirstname').val(null);
                $('#buyercustomerlastname').val(null);
                $('#buyercustomeraddress').val(null);
                $('#buyercustomerprovinceid').val(null).trigger('chosen:updated');
                $('#buyercustomeramphurid').children('option:not(:first)').remove();
                $('#buyercustomeramphurid').val(null).trigger('chosen:updated');
                $('#buyercustomerdistrictid').children('option:not(:first)').remove();
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
                $('#buyercustomerzipcode').val(null);
                $('#buyercustomerphone1').val(null);
                $('#buyercustomerphone2').val(null);
                $('#buyercustomeroccupationid').val(null).trigger('chosen:updated');
                $('#buyercustomerbirthdate').val(null);

            }
        }

    </script>

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> เพิ่มใบจองใหม่</h3>
    @elseif($oper == 'edit')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> แก้ไขใบจอง</h3>
    @elseif($oper == 'view')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ดูข้อมูลใบจอง</h3>
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
        {!! Form::open(array('url' => 'carpreemption/save', 'id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @elseif($oper == 'edit')
        {!! Form::model($carpreemption, array('url' => 'carpreemption/save', 'id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($carpreemption, array('id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

        <div class="form-group" style="margin-top:10px; @if(!Auth::user()->isadmin ) display: none; @endif" >
            {!! Form::label('provincebranchid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-3">
                {!! Form::select('provincebranchid', $provincebranchselectlist, null, array('id'=>'provinceid', 'class' => 'chosen-select')); !!}
            </div>
        </div>

        <div class="form-group" style="margin-top:10px;" >
            {!! Form::label('bookno', 'เล่มที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                {!! Form::number('bookno',null,array('min' => '0','max' => '999')) !!}
            </div>
            {!! Form::label('no', 'เลขที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                {!! Form::number('no',null,array('min' => '0','max' => '9999')) !!}
            </div>
            {!! Form::label('date', 'วันที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                <div class="input-group">
                    {!! Form::text('date', date("d-m-Y"), array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date')) !!}
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
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " >ผู้สั่งจอง</label>
                                    <div class="col-sm-11" >
                                        <label>
                                            {!! Form::radio('customer-type',0, true, array('class' => 'ace', 'onchange'=>'NewCustomer("n")')) !!}
                                            <span class="lbl"> มีชื่อในระบบ</span>&nbsp;&nbsp;
                                            <div class="old-customer" style="display:inline-block">
                                                {!! Form::select('bookingcustomerid', $customerselectlist, null, array('id'=>'bookingcustomerid', 'class' => 'chosen-select', 'style'=>'width:20%', 'onchange'=>'BookingCustomerChange(this)')); !!}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1 control-label no-padding-right " ></label>
                                    <div class="col-sm-11">
                                        <label>
                                            {!! Form::radio('customer-type',1, false, array('class' => 'ace', 'onchange'=>'NewCustomer("y")')) !!}
                                            <span class="lbl"> ลูกค้าใหม่</span>&nbsp;&nbsp;
                                            {!! Form::select('bookingcustomertitle', array('นาย' => 'นาย', 'นาง' => 'นาง', 'นางสาว' => 'นางสาว'), 'นาย', array('class' => 'new-customer', 'style'=>'font-size:14px; padding:5px 4px 6px; height:34px; display:none;')) !!}
                                            {!! Form::text('bookingcustomerfirstname', null, array('id'=>'bookingcustomerfirstname', 'class' => 'new-customer', 'style'=>'display:none;', 'placeholder'=>'ชื่อ')) !!}
                                            {!! Form::text('bookingcustomerlastname', null, array('id'=>'bookingcustomerlastname', 'class' => 'new-customer', 'style'=>'display:none;', 'placeholder'=>'นามสกุล')) !!}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomeraddress', 'ที่อยู่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-11">
                                        {!! Form::text('bookingcustomeraddress', null, array('style'=>'width:45%; min-width:250px;')) !!}
                                </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomerprovinceid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomerprovinceid', $provinceselectlist, null, array('id'=>'bookingcustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerProvinceChange(this)')); !!}
                                    </div>
                                    {!! Form::label('bookingcustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomeramphurid', $bookingcustomeramphurselectlist, null, array('id'=>'bookingcustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerAmphurChange(this)')); !!}
                                    </div>
                                    {!! Form::label('bookingcustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomerdistrictid', $bookingcustomerdistrictselectlist, null, array('id'=>'bookingcustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerDistrictChange(this)')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomerzipcode', 'รหัสไปรษณีย์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::number('bookingcustomerzipcode',null,array('min' => '0','max' => '99999')) !!}
                                    </div>
                                    {!! Form::label('bookingcustomerphone1', 'เบอร์โทร 1', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::number('bookingcustomerphone1',null,array('min' => '0','max' => '9999999999')) !!}
                                        </div>
                                    </div>
                                    {!! Form::label('bookingcustomerphone2', 'เบอร์โทร 2', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::number('bookingcustomerphone2',null,array('min' => '0','max' => '9999999999')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomeroccupationid', 'อาชีพ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomeroccupationid', $occupationselectlist, null, array('id'=>'bookingcustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')); !!}
                                    </div>
                                    {!! Form::label('bookingcustomerbirthdate', 'วันเกิด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('bookingcustomerbirthdate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
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
                                    {!! Form::label('carmodelid', 'รถนิสสัน แบบ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-4">
                                        {!! Form::select('carmodelid', $carmodelselectlist, null, array('id'=>'carmodelid', 'class' => 'chosen-select', 'onchange'=>'CarModelChange(this)', 'style'=>'width:150px;')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('carsubmodelid', 'รุ่น', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('carsubmodelid', $carsubmodelselectlist, null, array('id'=>'carsubmodelid', 'class' => 'chosen-select', 'onchange'=>'GetPrice()')); !!}
                                    </div>
                                    {!! Form::label('colorid', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('colorid', $colorselectlist, null, array('id'=>'colorid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('pricelistid', 'ราคา', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('pricelistid', $priceselectlist, null, array('id'=>'pricelistid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    {!! Form::label('discount', 'ส่วนลด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('discount', null, array('step' => '0.01', 'min' => '0' ,'placeholder' => 'บาท')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('subdown', 'Sub Down', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::number('subdown', null, array('step' => '0.01', 'min' => '0' ,'placeholder' => 'บาท')) !!}
                                    </div>
                                    {!! Form::label('accessories', 'บวกอุปกรณ์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('accessories', null, array('step' => '0.01', 'min' => '0' ,'placeholder' => 'บาท')) !!}
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
                                    {!! Form::label('oldcarbrandid', 'ยี่ห้อ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('oldcarbrandid', $oldcarbrandselectlist, null, array('id'=>'oldcarbrandid', 'class' => 'chosen-select', 'onchange'=>'OldCarBrandChange(this)')); !!}
                                    </div>
                                    {!! Form::label('oldcarmodelid', 'แบบ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('oldcarmodelid', $oldcarmodelselectlist, null, array('id'=>'oldcarmodelid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcargear', 'เกียร์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('oldcargear', array(null => 'เลือกเกียร์', 0 => 'ธรรมดา/MT', 1 => 'ออโต้/AT'), null) !!}
                                    </div>
                                    {!! Form::label('oldcarcolor', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('oldcarcolor') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarenginesize', 'ขนาดเครื่องยนต์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('oldcarenginesize', null, array('min' => '0','placeholder' => 'ซีซี')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarlicenseplate', 'ทะเบียน', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::text('oldcarlicenseplate') !!}
                                    </div>
                                    {!! Form::label('oldcaryear', 'ปี', array('class' => 'col-sm-1 col-lg-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::number('oldcaryear',null,array('min' => '0','max' => '9999')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarprice', 'ราคารถเก่า', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::number('oldcarprice', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท')) !!}
                                    </div>
                                    {!! Form::label('oldcarbuyername', 'ผู้ให้ราคา', array('class' => 'col-sm-1 col-lg-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('oldcarbuyername', null, array('placeholder' => 'ชื่อ-นามสกุล', 'style' => 'width:300px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarother', 'อื่นๆ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-10">
                                        {!! Form::textarea('oldcarother', null, ['size' => '70x2']) !!}
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
                                        {!! Form::label('cashpledge', '1. เงินมัดจำ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('cashpledge', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('purchasetype', '2. ซื้อรถยนต์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <label>
                                                {!! Form::radio('purchasetype', 0, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  เงินสด</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('purchasetype', 1, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  เช่าซื้อกับบริษัท</span>&nbsp;&nbsp;
                                                {!! Form::select('finacecompanyid', $finacecompanyselectlist, null, array('id'=>'finacecompanyid', 'class' => 'chosen-select')); !!}
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('interest', 'ดอกเบี้ย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::number('interest', null, array('step' => '0.01', 'min' => '0', 'max' => '100','placeholder' => '%', 'min'=>'0', 'max'=>'100', 'style'=>'width:70px;')) !!}&nbsp;&nbsp;&nbsp;
                                            {!! Form::label('down', 'ดาวน์') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('down', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท')) !!}
                                            &nbsp;&nbsp;&nbsp;
                                            {!! Form::label('installments', 'จำนวนงวด') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('installments', null, array('min' => '0','style'=>'width:70px;')) !!}
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('cashpledgeredlabel', '3. ค่ามัดจำป้ายแดง', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3 ">
                                            {!! Form::number('cashpledgeredlabel', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('registrationtype', '4. ค่าจดทะเบียน', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <label>
                                                {!! Form::radio('registrationtype', 0, true, array('class' => 'ace', 'onchange'=>'RegistrationTypeChange();')) !!}
                                                <span class="lbl">  บุคคล</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('registrationtype', 1, false, array('class' => 'ace', 'onchange'=>'RegistrationTypeChange();')) !!}
                                                <span class="lbl">  นิติบุคคล</span>&nbsp;&nbsp;
                                                {!! Form::number('registrationfee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'id' => 'registrationfee', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('insurancefee', '5. ค่าประกันภัย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('insurancefee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('compulsorymotorinsurancefee', '6. ค่า พ.ร.บ.', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('compulsorymotorinsurancefee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('accessoriesfee', '7. ค่าอุปกรณ์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('accessoriesfee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('otherfee', '8. ค่าอื่นๆ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('otherfee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('datewantgetcar', 'วันที่ต้องการรับรถ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                {!! Form::text('datewantgetcar', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
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
                                    <table id="grid-table-in-form"></table>
                                    <div id="grid-pager"></div>
                                </div><br>

                                <div class="form-group">
                                    {!! Form::label('giveawayadditionalcharges', 'ลูกค้าจ่ายเพิ่มเติ่มค่าของแถม', array('class' => 'col-sm-3 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('giveawayadditionalcharges', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                    </div>
                                </div>

                                <br>
                                <div>
                                    <table id="grid-table-in-form2"></table>
                                    <div id="grid-pager2"></div>
                                </div><br>
                                <div class="form-group">
                                    <div style="height:35px;">
                                        {!! Form::label('buyertype', 'ผู้ซื้อ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                        <div class="col-sm-11" >
                                            <div class="checkbox" style="padding-left:0px;">
                                                <label>
                                                    {!! Form::radio('buyertype', 0, true, array('class' => 'ace', 'onchange'=>'BuyCustomerType("same");')) !!}
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
                                                {!! Form::radio('buyertype', 1, false, array('class' => 'ace', 'onchange'=>'BuyCustomerType("insystem");')) !!}
                                                <span class="lbl"> มีชื่อในระบบ</span>&nbsp;&nbsp;
                                                <div class="insystem-customer" style="display:none;">
                                                    {!! Form::select('buyercustomerid', $customerselectlist, null, array('id'=>'buyercustomerid', 'class' => 'chosen-select', 'style'=>'width:25%', 'onchange'=>'BuyerCustomerChange(this)')); !!}
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
                                                {!! Form::radio('buyertype', 2, false, array('class' => 'ace', 'onchange'=>'BuyCustomerType("newbuyer");')) !!}
                                                <span class="lbl"> ลูกค้าใหม่</span>&nbsp;&nbsp;
                                                {!! Form::select('buyercustomertitle', array('นาย' => 'นาย', 'นาง' => 'นาง', 'นางสาว' => 'นางสาว'), 'นาย', array('class' => 'newbuy-customer', 'style'=>'font-size:14px; padding:5px 4px 6px; height:34px; display:none;')) !!}
                                                {!! Form::text('buyercustomerfirstname', null, array('id'=>'buyercustomerfirstname', 'class' => 'newbuy-customer', 'style'=>'display:none;', 'placeholder'=>'ชื่อ')) !!}
                                                {!! Form::text('buyercustomerlastname', null, array('id'=>'buyercustomerlastname', 'class' => 'newbuy-customer', 'style'=>'display:none;', 'placeholder'=>'นามสกุล')) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none; padding-top:15px;" >
                                    {!! Form::label('buyercustomeraddress', 'ที่อยู่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-11">
                                        {!! Form::text('buyercustomeraddress', null, array('style'=>'width:45%; min-width:250px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;" >
                                    {!! Form::label('buyercustomerprovinceid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomerprovinceid', $provinceselectlist, null, array('id'=>'buyercustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerProvinceChange(this)')); !!}
                                    </div>
                                    {!! Form::label('buyercustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomeramphurid', $buyercustomeramphurselectlist, null, array('id'=>'buyercustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerAmphurChange(this)')); !!}
                                    </div>
                                    {!! Form::label('buyercustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomerdistrictid', $buyercustomerdistrictselectlist, null, array('id'=>'buyercustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerDistrictChange(this)')); !!}
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    {!! Form::label('buyercustomerzipcode', 'รหัสไปรษณีย์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::number('buyercustomerzipcode',null,array('min' => '0','max' => '99999')) !!}
                                    </div>
                                    {!! Form::label('buyercustomerphone1', 'เบอร์โทร 1', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::number('buyercustomerphone1',null,array('min' => '0','max' => '9999999999')) !!}
                                        </div>
                                    </div>
                                    {!! Form::label('buyercustomerphone2', 'เบอร์โทร 2', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::number('buyercustomerphone2',null,array('min' => '0','max' => '9999999999')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    {!! Form::label('buyercustomeroccupationid', 'อาชีพ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomeroccupationid', $occupationselectlist, null, array('id'=>'buyercustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')); !!}
                                    </div>
                                    {!! Form::label('buyercustomerbirthdate', 'วันเกิด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('buyercustomerbirthdate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    {!! Form::label('salesmanemployeeid', 'พนักงานขาย', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('salesmanemployeeid', $saleemployeeselectlist, null, array('id'=>'salesmanemployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    {!! Form::label('salesmanageremployeeid', 'ผู้จัดการฝ่ายขาย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('salesmanageremployeeid', $salemanageremployeeselectlist, null, array('id'=>'salesmanageremployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('approversemployeeid', 'ผู้อนุมัติ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('approversemployeeid', $approveremployeeselectlist, null, array('id'=>'approversemployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    {!! Form::label('approvaldate', 'วันที่อนุมัติ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('approvaldate', date("d-m-Y"), array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
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
                                        {!! Form::label('aboutcustomer', 'ที่มาของลูกค้า', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('place', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" >  สถานที่</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('showroom', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" > โชว์รูม</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('booth', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" > บูธ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('leaflet', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" > ใบปลิว</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('businesscard', 1, false, array('class' => 'ace')) !!}
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
                                                    {!! Form::checkbox('invitationcard', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" >  การ์ดเชิญ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('phone', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" > โทรศัพท์</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('signshowroom', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:115px;" > ป้ายหน้าโชว์รูม</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::checkbox('spotradiowalkin', 1, false, array('class' => 'ace')) !!}
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
                                                    {!! Form::checkbox('recommendedby', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:100px;" >  แนะนำโดย</span>
                                                </label>
                                                {!! Form::text('recommendedbyname') !!}
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::radio('recommendedbytype', 0, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:auto;" > เพื่อน</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::radio('recommendedbytype', 1, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:auto;" > ญาติ</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::radio('recommendedbytype', 2, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:auto;" > ลูกค้าเก่า</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::radio('recommendedbytype', 3, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:auto;" > พนักงาน</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-9 no-padding-left" style="height:35px; margin-top:10px;">
                                        {!! Form::label('customertype', 'ประเภทลูกค้า', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::radio('customertype', 0, false, array('class' => 'ace')) !!}
                                                    <span class="lbl" style="width:80px;" >  ซื้อใหม่</span>
                                                </label>
                                                &nbsp;&nbsp;
                                                <label>
                                                    {!! Form::radio('customertype', 1, false, array('class' => 'ace')) !!}
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
                {!! Form::textarea('remark', null, ['size' => '0x2','class' => 'autosize-transition limited', 'placeholder' => 'หมายเหตุ/โน๊ต', 'style' => 'width:100%;']) !!}
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

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        var giveawayFreeData = [
            @foreach ($giveawayFreeDatas as $data)
                {"giveawayid":"{{$data->giveawayid}}","price":"{{$data->price}}"},
            @endforeach
        ];

        var giveawayBuyData = [
            @foreach ($giveawayBuyDatas as $data)
                {"giveawayid":"{{$data->giveawayid}}"},
            @endforeach
        ];

        onclickSubmitLocal = function (options, postdata) {
            var $this = $(this), p = $(this).jqGrid("getGridParam"),// p = this.p,
                    idname = p.prmNames.id,
                    id = this.id,
                    idInPostdata = id + "_id",
                    rowid = postdata[idInPostdata],
                    addMode = rowid === "_empty",
                    oldValueOfSortColumn,
                    newId,
                    idOfTreeParentNode;

            // postdata has row id property with another name. we fix it:
            if (addMode) {
                // generate new id
                newId = $.jgrid.randId();
                while ($("#" + newId).length !== 0) {
                    newId = $.jgrid.randId();
                }
                postdata[idname] = String(newId);
            } else if (postdata[idname] === undefined) {
                // set id property only if the property not exist
                postdata[idname] = rowid;
            }
            delete postdata[idInPostdata];

            // prepare postdata for tree grid
            if (p.treeGrid === true) {
                if (addMode) {
                    idOfTreeParentNode = p.treeGridModel === "adjacency" ? p.treeReader.parent_id_field : "parent_id";
                    postdata[idOfTreeParentNode] = p.selrow;
                }

                $.each(p.treeReader, function () {
                    if (postdata.hasOwnProperty(this)) {
                        delete postdata[this];
                    }
                });
            }

            // decode data if there encoded with autoencode
            if (p.autoencode) {
                $.each(postdata, function (n, v) {
                    postdata[n] = $.jgrid.htmlDecode(v); // TODO: some columns could be skipped
                });
            }

            // save old value from the sorted column
            oldValueOfSortColumn = p.sortname === "" ? undefined : $this.jqGrid("getCell", rowid, p.sortname);

            // save the data in the grid
            if (p.treeGrid === true) {
                if (addMode) {
                    $this.jqGrid("addChildNode", newId, p.selrow, postdata);
                } else {
                    $this.jqGrid("setTreeRow", rowid, postdata);
                }
            } else {
                if (addMode) {
                    $this.jqGrid("addRowData", newId, postdata, options.addedrow);
                } else {
                    $this.jqGrid("setRowData", rowid, postdata);
                }
            }

            if ((addMode && options.closeAfterAdd) || (!addMode && options.closeAfterEdit)) {
                // close the edit/add dialog
                $.jgrid.hideModal("#editmod" + $.jgrid.jqID(id), {
                    gb: "#gbox_" + $.jgrid.jqID(id),
                    jqm: options.jqModal,
                    onClose: options.onClose
                });
            }

            if (postdata[p.sortname] !== oldValueOfSortColumn) {
                // if the data are changed in the column by which are currently sorted
                // we need resort the grid
                setTimeout(function () {
                    $this.trigger("reloadGrid", [{current: true}]);
                }, 100);
            }

            // !!! the most important step: skip ajax request to the server
            options.processing = true;
            return {};
        }

        $(document).ready(function() {
            var grid_selector = "#grid-table-in-form";
            var pager_selector = "#grid-pager";
            var grid_selector2 = "#grid-table-in-form2";
            var pager_selector2 = "#grid-pager2";

            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                resizeGridInForm('grid-table-in-form');
                resizeGridInForm('grid-table-in-form2');
            })
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            })

            var parent_column2 = $(grid_selector2).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector2).jqGrid( 'setGridWidth', parent_column2.width() );
                }
            })

            $(grid_selector).jqGrid({
                datatype: "local",
                data: giveawayFreeData,
                colNames: ["อุปกรณ์ของแถม","ราคาที่แถม"],
                colModel:[
                    {name:'giveawayid',index:'giveawayid', width:100, editable: true,edittype:"select",formatter:'select',
                        editoptions:{value: "{{ $giveawayselectlist }}"},editrules:{required:true}
                        ,align:'left',stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "{{ $giveawayselectlist }}" }},
                    {name:'price',index:'price', width:200,editable: true,
                        editrules:{required:true, number:true,custom: true, custom_func: check_giveawaysaleprice},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}}
                ],
                cmTemplate: {editable: true, sortable: false, searchoptions: {clearSearch: false }},
                rowNum: 10,
                rowList: [5, 10, 20],
                pager: pager_selector,
                gridview: true,
                rownumbers: true,
                //autoencode: true,
                //ignoreCase: true,
                viewrecords: true,
                altRows: true,
                multiselect: true,
                multiboxonly: true,
                caption: "อุปกรณ์ของแถม",
                height: "100%",
                editurl: "clientArray",
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },
                ondblClickRow: function (rowid) {
                    /*var $this = $(this), p = this.p;
                    if (p.selrow !== rowid) {
                        // prevent the row from be unselected on double-click
                        // the implementation is for "multiselect:false" which we use,
                        // but one can easy modify the code for "multiselect:true"
                        $this.jqGrid("setSelection", rowid);
                    }
                    $this.jqGrid("editGridRow", rowid, editSettings);*/
                }
            });

            $(grid_selector2).jqGrid({
                datatype: "local",
                data: giveawayBuyData,
                colNames: ["อุปกรณ์ซื้อเพิ่มเติม"],
                colModel:[
                    {name:'giveawayid',index:'giveawayid', width:100, editable: true,edittype:"select",formatter:'select',
                        editoptions:{value: "{{ $giveawayselectlist }}"},editrules:{required:true}
                        ,align:'left',stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "{{ $giveawayselectlist }}" }}
                ],
                cmTemplate: {editable: true, sortable: false, searchoptions: {clearSearch: false }},
                rowNum: 10,
                rowList: [5, 10, 20],
                pager: pager_selector2,
                gridview: true,
                rownumbers: true,
                //autoencode: true,
                //ignoreCase: true,
                viewrecords: true,
                altRows: true,
                multiselect: true,
                multiboxonly: true,
                caption: "อุปกรณ์ซื้อเพิ่มเติม",
                height: "100%",
                editurl: "clientArray",
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },
                ondblClickRow: function (rowid) {
                    /*var $this = $(this), p = this.p;
                     if (p.selrow !== rowid) {
                     // prevent the row from be unselected on double-click
                     // the implementation is for "multiselect:false" which we use,
                     // but one can easy modify the code for "multiselect:true"
                     $this.jqGrid("setSelection", rowid);
                     }
                     $this.jqGrid("editGridRow", rowid, editSettings);*/
                }
            });

            $(window).triggerHandler('resize.jqGrid');

            function check_giveawaysaleprice(value, colname) {
                var giveawayid = $('#giveawayid').val();
                $.ajax({
                    url: '../giveaway/check_saleprice',
                    data: { id:giveawayid,saleprice:value, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    async: false,
                    datatype: 'text',
                    success: function (data) {
                        if (!data) result = [true, ""];
                        else result = [false,"มูลค่าที่แถมห้ามต่ำกว่า "+data];
                    }
                })
                return result;
            }

            var alertt = $(".page-content").height()*0.71;
            var alertl = ($(window).width()-80)/2;

            var notViewMode = true;
            if('{{$oper}}' == 'view'){
                notViewMode = false;
            }

            $(grid_selector).jqGrid("navGrid", pager_selector,
                { 	//navbar options
                    edit: notViewMode,
                    editicon : 'ace-icon fa fa-pencil blue',
                    add: notViewMode,
                    addicon : 'ace-icon fa fa-plus-circle purple',
                    del: notViewMode,
                    delicon : 'ace-icon fa fa-trash-o red',
                    search: false,
                    searchicon : 'ace-icon fa fa-search orange',
                    refresh: false,
                    refreshicon : 'ace-icon fa fa-refresh green',
                    view: false,
                    viewicon : 'ace-icon fa fa-search-plus grey',
                    alerttop : alertt,
                    alertleft : alertl
                },
                {
                    //edit record form
                    closeAfterEdit: true,
                    width: 600,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        dlgDiv[0].style.left = (($(grid_selector).width() - 150)/2) + "px";
                    },
                    reloadAfterSubmit: false,
                    savekey: [true, 13],
                    modal:true,
                    onclickSubmit: onclickSubmitLocal
                },
                {
                    //new record form
                    width: 600,
                    closeAfterAdd: true,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm : function(e) {
                        jQuery(grid_selector).jqGrid('resetSelection');
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        dlgDiv[0].style.left = (($(grid_selector).width() - 150)/2) + "px";
                    },
                    reloadAfterSubmit: false,
                    savekey: [true, 13],
                    modal:true,
                    onclickSubmit: onclickSubmitLocal
                },
                {
                    //delete record form
                    width: 400,
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        if(!form.data('styled')) {
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                            style_delete_form(form);

                            form.data('styled', true);

                            var dlgDiv = $("#delmod" + jQuery(grid_selector)[0].id);
                            dlgDiv[0].style.left = (($(grid_selector).width() - 150)/2) + "px";
                        }

                        var totalRows = $(grid_selector).jqGrid('getGridParam', 'selarrrow');
                        var totalRowsCount = totalRows.length;
                        $("td.delmsg", form).html("คุณต้องการลบข้อมูลที่ถูกเลือก <b>ทั้งหมด " + totalRowsCount + " รายการ</b>" + " ใช่หรือไม่?");
                    },
                    // because I use "local" data I don't want to send the changes to the server
                    // so I use "processing:true" setting and delete the row manually in onclickSubmit
                    onclickSubmit: function (options, rowid) {
                        var $this = $(this), id = $.jgrid.jqID(this.id), p = this.p,
                                newPage = p.page;

                        // reset the value of processing option to true to
                        // skip the ajax request to "clientArray".
                        options.processing = true;

                        var totalRows = $(grid_selector).jqGrid('getGridParam', 'selarrrow');
                        var rowLength = totalRows.length;
                        for (var i = 0; i < rowLength; i++) {
                            $this.jqGrid("delRowData", totalRows[0]);
                        }

                        $.jgrid.hideModal("#delmod" + id, {
                            gb: "#gbox_" + id,
                            jqm: options.jqModal,
                            onClose: options.onClose
                        });

                        if (p.lastpage > 1) {// on the multipage grid reload the grid
                            if (p.reccount === 0 && newPage === p.lastpage) {
                                // if after deliting there are no rows on the current page
                                // which is the last page of the grid
                                newPage--; // go to the previous page
                            }
                            // reload grid to make the row from the next page visable.
                            $this.trigger("reloadGrid", [{page: newPage}]);
                        }

                        return true;
                    },
                    processing: true
                }
            )

            $(grid_selector2).jqGrid("navGrid", pager_selector2,
                    { 	//navbar options
                        edit: notViewMode,
                        editicon : 'ace-icon fa fa-pencil blue',
                        add: notViewMode,
                        addicon : 'ace-icon fa fa-plus-circle purple',
                        del: notViewMode,
                        delicon : 'ace-icon fa fa-trash-o red',
                        search: false,
                        searchicon : 'ace-icon fa fa-search orange',
                        refresh: false,
                        refreshicon : 'ace-icon fa fa-refresh green',
                        view: false,
                        viewicon : 'ace-icon fa fa-search-plus grey',
                        alerttop : alertt,
                        alertleft : alertl
                    },
                    {
                        //edit record form
                        closeAfterEdit: true,
                        width: 600,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm : function(e) {
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                            style_edit_form(form);

                            var dlgDiv = $("#editmod" + jQuery(grid_selector2)[0].id);
                            dlgDiv[0].style.left = (($(grid_selector2).width() - 150)/2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal:true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //new record form
                        width: 600,
                        closeAfterAdd: true,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm : function(e) {
                            jQuery(grid_selector2).jqGrid('resetSelection');
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                            style_edit_form(form);

                            var dlgDiv = $("#editmod" + jQuery(grid_selector2)[0].id);
                            dlgDiv[0].style.left = (($(grid_selector2).width() - 150)/2) + "px";
                        },
                        reloadAfterSubmit: false,
                        savekey: [true, 13],
                        modal:true,
                        onclickSubmit: onclickSubmitLocal
                    },
                    {
                        //delete record form
                        width: 400,
                        recreateForm: true,
                        beforeShowForm : function(e) {
                            var form = $(e[0]);
                            if(!form.data('styled')) {
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                style_delete_form(form);

                                form.data('styled', true);

                                var dlgDiv = $("#delmod" + jQuery(grid_selector2)[0].id);
                                dlgDiv[0].style.left = (($(grid_selector2).width() - 150)/2) + "px";
                            }

                            var totalRows = $(grid_selector2).jqGrid('getGridParam', 'selarrrow');
                            var totalRowsCount = totalRows.length;
                            $("td.delmsg", form).html("คุณต้องการลบข้อมูลที่ถูกเลือก <b>ทั้งหมด " + totalRowsCount + " รายการ</b>" + " ใช่หรือไม่?");
                        },
                        // because I use "local" data I don't want to send the changes to the server
                        // so I use "processing:true" setting and delete the row manually in onclickSubmit
                        onclickSubmit: function (options, rowid) {
                            var $this = $(this), id = $.jgrid.jqID(this.id), p = this.p,
                                    newPage = p.page;

                            // reset the value of processing option to true to
                            // skip the ajax request to "clientArray".
                            options.processing = true;

                            var totalRows = $(grid_selector2).jqGrid('getGridParam', 'selarrrow');
                            var rowLength = totalRows.length;
                            for (var i = 0; i < rowLength; i++) {
                                $this.jqGrid("delRowData", totalRows[0]);
                            }

                            $.jgrid.hideModal("#delmod" + id, {
                                gb: "#gbox_" + id,
                                jqm: options.jqModal,
                                onClose: options.onClose
                            });

                            if (p.lastpage > 1) {// on the multipage grid reload the grid
                                if (p.reccount === 0 && newPage === p.lastpage) {
                                    // if after deliting there are no rows on the current page
                                    // which is the last page of the grid
                                    newPage--; // go to the previous page
                                }
                                // reload grid to make the row from the next page visable.
                                $this.trigger("reloadGrid", [{page: newPage}]);
                            }

                            return true;
                        },
                        processing: true
                    }
            )

            var customertype = jQuery( 'input[name=customer-type]:checked' ).val();
            if(customertype == 0){
                $(".new-customer").css("display","none");
                $(".old-customer").css("display","inline-block");
            }
            else if(customertype == 1){
                $(".new-customer").css("display","");
                $(".old-customer").css("display","none");
            }

            //$('#carmodelid').trigger('change');

            var buyertype = jQuery( 'input[name=buyertype]:checked' ).val();
            if(buyertype == 0){
                $(".same-customer").css("display","none");
                $(".insystem-customer").css("display","none");
                $(".newbuy-customer").css("display","none");
            }
            else if(buyertype == 1){
                $(".insystem-customer").css("display","inline-block");
                $(".same-customer").css("display","");
                $(".newbuy-customer").css("display","none");
            }
            else if(buyertype == 2){
                $(".newbuy-customer").css("display","");
                $(".same-customer").css("display","");
                $(".insystem-customer").css("display","none");
            }

            //textarea
            $('textarea[class*=autosize]').autosize({append: "\n"});
            $('textarea.limited').inputlimiter({
                remText: '%n character%s remaining',
                limitText: 'max allowed : %n.'
            });

            // pattern validate
            $('.input-mask-phone').mask('(999) 999-9999');

            //datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            })
                //show datepicker when clicking on the icon
                    .next().on(ace.click_event, function(){
                        $(this).prev().focus();
                    });

            $('#date').datepicker().on('changeDate', function(){ GetPrice() });

            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function() {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width':189});

                $('#bookingcustomerid').width(250);
                $('#bookingcustomerid_chosen').width(250);

                $('#carmodelid').width(300);
                $('#carmodelid_chosen').width(300);

                $('#carsubmodelid').width(300);
                $('#carsubmodelid_chosen').width(300);

                $('#colorid').width(300);
                $('#colorid_chosen').width(300);

                $('#buyercustomerid').width(250);
                $('#buyercustomerid_chosen').width(250);

                $('#salesmanemployeeid').width(250);
                $('#salesmanemployeeid_chosen').width(250);

                $('#salesmanageremployeeid').width(250);
                $('#salesmanageremployeeid_chosen').width(250);

                $('#approversemployeeid').width(250);
                $('#approversemployeeid_chosen').width(250);
            }).trigger('resize.chosen');

            $('.date-picker').parent().width(140);
            $('.date-picker').width(90);

            @if($oper == 'view')
                $("#form-carpreemption :input").prop("disabled", true);
                $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif

        })



        $('#form-carpreemption').submit(function(){ //listen for submit event
            var giveawayFreeData = $("#grid-table-in-form").jqGrid('getGridParam', 'data');
            var giveawayBuyData = $("#grid-table-in-form2").jqGrid('getGridParam', 'data');
            giveawayFreeData = JSON.stringify(giveawayFreeData);
            giveawayBuyData = JSON.stringify(giveawayBuyData);
            $(this).append($('<input>').attr('type', 'hidden').attr('name', 'giveawayFreeData').val(giveawayFreeData));
            $(this).append($('<input>').attr('type', 'hidden').attr('name', 'giveawayBuyData').val(giveawayBuyData));
            return true;
        });
    </script>
@endsection