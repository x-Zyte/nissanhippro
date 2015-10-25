@extends('appformpathlevel2')
@section('title','เพิ่มใบจองใหม่')
@section('menu-carpreemption-class','active')

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

            $.get('../customer/getbyid/'+customerid, function(data){
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

            $.get('../customer/getbyid/'+customerid, function(data){
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
            $.get('../amphur/read/'+provinceid, function(data){
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
            $.get('../amphur/read/'+provinceid, function(data){
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
            $.get('../district/read/'+amphurid, function(data){
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
            $.get('../district/read/'+amphurid, function(data){
                $.each(data, function(i, option) {
                    $('#buyercustomerdistrictid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#buyercustomerdistrictid').val(null).trigger('chosen:updated');
            });
        }

        function BookingCustomerDistrictChange(sel) {
            var districtid = sel.value;
            $('#bookingcustomerzipcode').val(null);
            $.get('../zipcode/read/'+districtid, function(data){
                $('#bookingcustomerzipcode').val(data.code);
            });
        }

        function BuyerCustomerDistrictChange(sel) {
            var districtid = sel.value;
            $('#buyercustomerzipcode').val(null);
            $.get('../zipcode/read/'+districtid, function(data){
                $('#buyercustomerzipcode').val(data.code);
            });
        }

        function CarModelChange(sel) {
            var carmodelid = sel.value;
            $('#carsubmodelid').children('option:not(:first)').remove();
            $('#colorid').children('option:not(:first)').remove();
            $.get('../carmodel/getsubmodelandcolorbyid/'+carmodelid, function(data){
                $.each(data.carsubmodels, function(i, option) {
                    $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#carsubmodelid').val(null).trigger('chosen:updated');

                $.each(data.colors, function(i, option) {
                    $('#colorid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#colorid').val(null).trigger('chosen:updated');
            });
        }

        function OldCarBrandChange(sel) {
            var carbrandid = sel.value;
            $('#oldcarmodelid').children('option:not(:first)').remove();
            $.get('../carbrand/getmodelbyid/'+carbrandid, function(data){
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

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> เพิ่มใบจองใหม่</h3>

    {!! Form::open(array('url' => 'foo/bar', 'id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
        <div class="form-group" style="margin-top:10px;" >
            {!! Form::label('bookno', 'เล่มที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                {!! Form::number('bookno') !!}
            </div>
            {!! Form::label('no', 'เลขที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                {!! Form::number('no') !!}
            </div>
            {!! Form::label('date', 'วันที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-2">
                <div class="input-group">
                    {!! Form::text('date', date("d-m-Y"), array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
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
                                    <label class="col-sm-1 control-label no-padding-right " >ชื่อผู้สั่งจอง</label>
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
                                    <div class="col-sm-2">
                                        {!! Form::select('bookingcustomerprovinceid', $provinceselectlist, null, array('id'=>'bookingcustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerProvinceChange(this)')); !!}
                                    </div>
                                    {!! Form::label('bookingcustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('bookingcustomeramphurid', array(null => 'เลือกเขต/อำเภอ'), null, array('id'=>'bookingcustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerAmphurChange(this)')); !!}
                                    </div>
                                    {!! Form::label('bookingcustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('bookingcustomerdistrictid', array(null => 'เลือกแขวง/ตำบล'), null, array('id'=>'bookingcustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerDistrictChange(this)')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomerzipcode', 'รหัสไปรษณีย์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('bookingcustomerzipcode') !!}
                                    </div>
                                    {!! Form::label('bookingcustomerphone1', 'เบอร์โทร 1', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::text('bookingcustomerphone1') !!}
                                        </div>
                                    </div>
                                    {!! Form::label('bookingcustomerphone2', 'เบอร์โทร 2', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::text('bookingcustomerphone2') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomeroccupationid', 'อาชีพ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomeroccupationid', $occupationselectlist, null, array('id'=>'bookingcustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomerbirthdate', 'วันเกิด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('bookingcustomerbirthdate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy')) !!}
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
                                    {!! Form::label('carmodelid', 'รถนิสสัน แบบ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('carmodelid', $carmodelselectlist, null, array('id'=>'carmodelid', 'class' => 'chosen-select', 'onchange'=>'CarModelChange(this)', 'style'=>'width:150px;')); !!}
                                    </div>
                                    {!! Form::label('carsubmodelid', 'รุ่น', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('carsubmodelid', array(null => 'เลือกรุ่น'), null, array('id'=>'carsubmodelid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('colorid', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('colorid', array(null => 'เลือกสี'), null, array('id'=>'colorid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('price', 'ราคา', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('price', null, array('placeholder' => 'บาท', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>
                                    {!! Form::label('discount', 'ส่วนลด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('discount', null, array('placeholder' => 'บาท')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('subdown', 'Sub Down', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('subdown', null, array('placeholder' => 'บาท')) !!}
                                    </div>
                                    {!! Form::label('accessories', 'บวกอุปกรณ์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('accessories', null, array('placeholder' => 'บาท')) !!}
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
                                        {!! Form::select('oldcarmodelid', array(null => 'เลือกแบบ'), null, array('id'=>'oldcarmodelid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcargear', 'เกียร์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('oldcargear', array(null => 'เลือกเกียร์', 0 => 'ธรรมดา/MT', 1 => 'ออโต้/AT'), null) !!}
                                    </div>
                                    {!! Form::label('oldcarcolor', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('oldcarcolors') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarenginesize', 'ขนาดเครื่องยนต์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('oldcarenginesize', null, array('placeholder' => 'ซีซี')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarlicenseplate', 'ทะเบียน', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::text('oldcarlicenseplate') !!}
                                    </div>
                                    {!! Form::label('oldcaryear', 'ปี', array('class' => 'col-sm-1 col-lg-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::number('oldcaryear') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('oldcarprice', 'ราคารถเก่า', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-1">
                                        {!! Form::number('oldcarprice', null, array('placeholder' => 'บาท')) !!}
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
                                            {!! Form::number('cashpledge', null, array('placeholder' => 'บาท')) !!}
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
                                                {!! Form::text('leasingcompanyname', null, array('placeholder' => 'ชื่อบริษัท')) !!}
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('interest', 'ดอกเบี้ย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::number('interest', null, array('placeholder' => '%', 'min'=>'0', 'max'=>'100', 'style'=>'width:70px;')) !!}&nbsp;&nbsp;&nbsp;
                                            {!! Form::label('down', 'ดาวน์') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('down', null, array('placeholder' => 'บาท')) !!}
                                            &nbsp;&nbsp;&nbsp;
                                            {!! Form::label('installments', 'จำนวนงวด') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('installments', null, array('style'=>'width:70px;')) !!}
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('cashpledgeredlabel', '3. ค่ามัดจำป้ายแดง', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3 ">
                                            {!! Form::number('cashpledgeredlabel', null, array('placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('registrationtype', '4. ค่าจดทะเบียน', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <label>
                                                {!! Form::radio('registrationtype', 0, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  บุคคล</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('registrationtype', 1, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  นิติบุคคล</span>&nbsp;&nbsp;
                                                {!! Form::number('registrationfee', null, array('placeholder' => 'บาท')) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('insurancefee', '5. ค่าประกันภัย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('insurancefee', null, array('placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('compulsorymotorinsurancefee', '6. ค่า พ.ร.บ.', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('compulsorymotorinsurancefee', null, array('placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('accessoriesfee', '7. ค่าอุปกรณ์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('accessoriesfee', null, array('placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('otherfee', '8. ค่าอื่นๆ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('otherfee', null, array('placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
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
                                    <div class="col-sm-2">
                                        {!! Form::select('buyercustomerprovinceid', $provinceselectlist, null, array('id'=>'buyercustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerProvinceChange(this)')); !!}
                                    </div>
                                    {!! Form::label('buyercustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('buyercustomeramphurid', array(null => 'เลือกเขต/อำเภอ'), null, array('id'=>'buyercustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerAmphurChange(this)')); !!}
                                    </div>
                                    {!! Form::label('buyercustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('buyercustomerdistrictid', array(null => 'เลือกแขวง/ตำบล'), null, array('id'=>'buyercustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerDistrictChange(this)')); !!}
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    {!! Form::label('buyercustomerzipcode', 'รหัสไปรษณีย์', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::text('buyercustomerzipcode') !!}
                                    </div>
                                    {!! Form::label('buyercustomerphone1', 'เบอร์โทร 1', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::text('buyercustomerphone1') !!}
                                        </div>
                                    </div>
                                    {!! Form::label('buyercustomerphone2', 'เบอร์โทร 2', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone"></i>
                                            </span>
                                            {!! Form::text('buyercustomerphone2') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
                                    {!! Form::label('buyercustomeroccupationid', 'อาชีพ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomeroccupationid', $occupationselectlist, null, array('id'=>'buyercustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')); !!}
                                    </div>
                                </div>
                                <div class="form-group same-customer" style="display:none;">
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

                                <div class="form-group">
                                    {!! Form::label('salesmanemployeeid', 'พนักงานขาย', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('salesmanemployeeid', $saleemployeeselectlist, null, array('id'=>'salesmanemployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                    {!! Form::label('salesmanageremployeeid', 'ผู้จัดการฝ่ายขาย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('salesmanageremployeeid', $saleemployeeselectlist, null, array('id'=>'salesmanageremployeeid', 'class' => 'chosen-select')); !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('approversemployeeid', 'ผู้อนุมัติ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('approversemployeeid', $saleemployeeselectlist, null, array('id'=>'approversemployeeid', 'class' => 'chosen-select')); !!}
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
    {!! Form::close() !!}

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        $(document).ready(function() {

        })
    </script>
@endsection