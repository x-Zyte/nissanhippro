@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มการจองใหม่')
@elseif($oper == 'edit')
    @if($carpreemption->status == 0)
        @section('title','แก้ไขการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (จอง)')
    @elseif($carpreemption->status == 1)
        @section('title','แก้ไขการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ชำระเงินแล้ว)')
    @elseif($carpreemption->status == 2)
        @section('title','แก้ไขการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ยกเลิก)')
    @elseif($carpreemption->status == 3)
        @section('title','แก้ไขการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ส่งรถก่อนชำระเงิน)')
    @endif
@elseif($oper == 'view')
    @if($carpreemption->status == 0)
        @section('title','ดูข้อมูลการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (จอง)')
    @elseif($carpreemption->status == 1)
        @section('title','ดูข้อมูลการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ชำระเงินแล้ว)')
    @elseif($carpreemption->status == 2)
        @section('title','ดูข้อมูลการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ยกเลิก)')
    @elseif($carpreemption->status == 3)
        @section('title','ดูข้อมูลการจอง '.$carpreemption->bookno.'/'.$carpreemption->no.' (ส่งรถก่อนชำระเงิน)')
    @endif
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

        var colorprices = [];
        @foreach ($colorprices as $data)
            colorprices["{{$data->colorid}}"] = "{{$data->price}}";
        @endforeach

        var provinceregistrationfee = [];
        var registrationfee = [];
        @foreach ($provinceregistrationfee as $data)
            registrationfee = [];
            @foreach ($data->registrationfee as $item)
                registrationfee["{{$item->type}}"] = "{{$item->price}}";
            @endforeach
            provinceregistrationfee["{{$data->provinceid}}"] = registrationfee;
        @endforeach

        function CarModelChange(sel) {
            var carmodelid = sel.value;
            if(carmodelid == null || carmodelid == '') return;
            $('#carsubmodelid').children('option:not(:first)').remove();
            $('#colorid').children('option:not(:first)').remove();

            $('#pricelistid').children('option').remove();
            $('#pricelistid').append($('<option/>').attr("value", '').text('เลือกราคา'));
            $('#pricelistid').val('').trigger('chosen:updated');

            var registrationtype = null;
            var registrationtypes = $("input[name=registrationtype]:checked");
            if(registrationtypes.length > 0)
                registrationtype = registrationtypes.val();

            var registerprovinceid = $("#registerprovinceid").chosen().val();
            if(registerprovinceid == null || registerprovinceid == '') registerprovinceid = 0;
            $('#registerprovinceid').children('option:not(:first)').remove();

            $.get('{{$pathPrefix}}carmodel/getsubmodelandcolorbyid/'+carmodelid+'/'+registrationtype+'/'+registerprovinceid, function(data){
                $.each(data.carsubmodels, function(i, option) {
                    $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#carsubmodelid').val(null).trigger('chosen:updated');

                colorprices = [];
                provinceregistrationfee = [];
                $.each(data.colors, function(i, option) {
                    colorprices[option.id] = option.car_model_colors[0].price;
                    $('#colorid').append($('<option/>').attr("value", option.id).text(option.code + ' - ' + option.name));
                });
                $('#colorid').val(null).trigger('chosen:updated');

                $.each(data.registerprovinces, function(i, option) {
                    var registrationfee = [];
                    registrationfee[0] = option.car_model_registers[0].individualregistercost;
                    registrationfee[1] = option.car_model_registers[0].companyregistercost;
                    registrationfee[2] = option.car_model_registers[0].governmentregistercost;
                    provinceregistrationfee[option.id] = registrationfee;
                    $('#registerprovinceid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                if(registerprovinceid == 0 || data.registerprovinces.length <= 0){
                    $('#registerprovinceid').val(null).trigger('chosen:updated');
                }
                else{
                    $('#registerprovinceid').val(registerprovinceid).trigger('chosen:updated');
                }

                $('#compulsorymotorinsurancefee').val(data.actcharged);
                Calregistrationfee();
                $('#colorprice').val(null);
                $('#totalcarprice').val(null);
            });
        }

        function FinacecompanyChange(sel) {
            var finacecompanyid = sel.value;
            if (finacecompanyid == null || finacecompanyid == '') return;
            $('#interestratetypeid').children('option:not(:first)').remove();

            $.get('{{$pathPrefix}}interestratetype/readSelectlist/' + finacecompanyid, function (data) {
                $.each(data, function (i, option) {
                    $('#interestratetypeid').append($('<option/>').attr("value", option.id).text(option.name));
                });
                $('#interestratetypeid').val(null).trigger('chosen:updated');
            });
        }

        var carprices = [];
        @foreach ($carprices as $data)
            carprices["{{$data->pricelistid}}"] = "{{$data->price}}";
        @endforeach

        function GetPrice() {
            var carsubmodelid = $("#carsubmodelid").chosen().val();
            var date = $('#date').val();
            if(carsubmodelid == null || carsubmodelid == '' || date == null || date == '') return;

            $.get('{{$pathPrefix}}pricelist/getprice/'+carsubmodelid+'/'+date, function(data){
                $('#pricelistid').children('option').remove();
                carprices = {};
                if(data.count == 0){
                    $('#pricelistid').append($('<option/>').attr("value", null).text('เลือกราคา'));
                    alert("ไม่พบข้อมูลราคา กรุณาเพิ่มข้อมูล ราคา ของรถรุ่นนี้ แล้วทำการเลือกรุ่น ใหม่อีกครั้ง");
                }

                $.each(data.pricelists, function(i, option) {
                    carprices[option.id] = option.sellingpricewithaccessories;
                    if(option.promotion != null && option.promotion != '')
                        $('#pricelistid').append($('<option/>').attr("value", option.id).text(option.sellingpricewithaccessories+' ('+option.promotion+')'));
                    else
                        $('#pricelistid').append($('<option/>').attr("value", option.id).text(option.sellingpricewithaccessories));
                });
                $('#pricelistid').trigger('chosen:updated');
                CalColorPrice();
            });
        }

        function PricelistChange() {
            CalTransferfee();
            CalColorPrice();
        }

        function CalColorPrice(){
            var pricelistid = $("#pricelistid").chosen().val();
            var carprice = carprices[pricelistid];
            if(carprice == null || carprice == '')
                carprice = 0;

            var colorid = $("#colorid").chosen().val();
            var colorprice = colorprices[colorid];
            if(colorprice == null || colorprice == '')
                colorprice = 0;

            $('#colorprice').val(parseFloat(colorprice).toFixed(2));
            $('#totalcarprice').val((parseFloat(carprice)+parseFloat(colorprice)).toFixed(2));
        }

        function Calregistrationfee(){
            var carmodelid = $("#carmodelid").chosen().val();
            if(carmodelid == null || carmodelid == '') return;
            var registerprovinceid = $("#registerprovinceid").chosen().val();
            if(registerprovinceid == null || registerprovinceid == '') return;
            var registrationtype = $("input[name=registrationtype]:checked").val();

            var registrationfee = provinceregistrationfee[registerprovinceid];
            if(registrationtype == 0) $('#registrationfee').val(registrationfee[0]);
            else if(registrationtype == 1) $('#registrationfee').val(registrationfee[1]);
            else $('#registrationfee').val(registrationfee[2]);

            CalTotalFree();
        }

        function CarobjectivetypeChange(){
            var carobjectivetype = $("input[name=carobjectivetype]:checked").val();
            if(carobjectivetype == 0){
                $(".financingfee").css("display","none");
                $(".transferfee").css("display","none");
                $(".transferoperationfee").css("display","none");
                $(".cashpledgeredlabel").css("display","block");
                $(".registrationtype").css("display","block");
            }
            else if(carobjectivetype == 1){
                var purchasetype = jQuery( 'input[name=purchasetype]:checked' ).val();
                if(purchasetype == 0){
                    $(".financingfee").css("display","none");
                }
                else if(purchasetype == 1) {
                    $(".financingfee").css("display","block");
                }

                $(".transferfee").css("display","block");
                $(".transferoperationfee").css("display","block");
                $(".cashpledgeredlabel").css("display","none");
                $(".registrationtype").css("display","none");

                CalTransferfee();
            }
        }

        function CashpledgepaymenttypeChange() {
            var cashpledgepaymenttype = $("input[name=cashpledgepaymenttype]:checked").val();
            if (cashpledgepaymenttype == 0) {
                $(".cashpledgepaymenttype1").css("display", "none");
                $('#cashpledgechargepercent').val(null);
                $('#cashpledgechargeamount').val(null);
                CalTotalFree();
            }
            else if (cashpledgepaymenttype == 1) {
                $(".cashpledgepaymenttype1").css("display", "block");
            }
        }

        function CalCashpledgechargeamount() {
            var cashpledgepaymenttype = $("input[name=cashpledgepaymenttype]:checked").val();
            if (cashpledgepaymenttype == 1) {
                var cashpledgechargepercent = $('#cashpledgechargepercent').val();
                if (cashpledgechargepercent == null || cashpledgechargepercent == '')
                    cashpledgechargepercent = 0;

                var cashpledge = $('#cashpledge').val();
                if (cashpledge == null || cashpledge == '')
                    cashpledge = 0;

                var cashpledgechargeamount = (parseFloat(cashpledgechargepercent) / 100.00) * parseFloat(cashpledge);
                $('#cashpledgechargeamount').val(cashpledgechargeamount.toFixed(2));

                CalTotalFree();
            }
        }

        function PurchasetypeChange(){
            var purchasetype = $("input[name=purchasetype]:checked").val();
            var carobjectivetype = $("input[name=carobjectivetype]:checked").val();
            if(purchasetype == 0){
                $(".financingfee").css("display","none");
                $(".purchasetype1").css("display","none");
                $(".purchasetype11").css("display","none");
                $('#subsidise').val(null);
                CalTotalFree();
            }
            else if(purchasetype == 1){
                $(".purchasetype1").css("display","inline-block");
                $(".purchasetype11").css("display","block");

                if(carobjectivetype == 1)
                    $(".financingfee").css("display","block");
            }
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

        function CalTransferfee(){
            var carobjectivetype = $("input[name=carobjectivetype]:checked").val();
            if(carobjectivetype != 1) return;

            var pricelistid = $("#pricelistid").chosen().val();
            if(pricelistid == null || pricelistid == '') return;

            $.get('{{$pathPrefix}}carpreemption/getprice/'+pricelistid, function(data){
                var discount = $('#discount').val();
                if(discount == null || discount == '')
                    discount = 0;

                var subdown = $('#subdown').val();
                if(subdown == null || subdown == '')
                    subdown = 0;

                var transferfee = parseFloat(0.75/100.00) * (parseFloat(data) - parseFloat(discount) - parseFloat(subdown));
                $('#transferfee').val(transferfee);
            });
        }

        function DateChange(){
            var date = $('#date').val();
            if(date == null || date == '') return;

            var dateArr = date.split("-");
            var newrdate = new Date(dateArr[1]+'-'+dateArr[0]+'-'+dateArr[2]);

            var datewant = $('#datewantgetcar').val();
            if(datewant != null && datewant != ''){
                var datewantArr = datewant.split("-");
                var newdatewant = new Date(datewantArr[1]+'-'+datewantArr[0]+'-'+datewantArr[2]);

                if(newdatewant.getTime() < newrdate.getTime()){
                    alert("วันที่ต้องการรับรถ ต้องไม่น้อยกว่า วันที่จอง");
                    $('#datewantgetcar').val(null);
                    return;
                }
            }

            var dateapp = $('#approvaldate').val();
            if(dateapp != null && dateapp != ''){
                var dateappArr = dateapp.split("-");
                var newdateapp = new Date(dateappArr[1]+'-'+dateappArr[0]+'-'+dateappArr[2]);

                if(newdateapp.getTime() < newrdate.getTime()){
                    alert("วันที่อนุมัติ ต้องไม่น้อยกว่า วันที่จอง");
                    $('#approvaldate').val(null);

                }
            }
        }
    </script>

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> เพิ่มการจองใหม่</h3>
    @elseif($oper == 'edit')
        @if($carpreemption->status == 0)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> แก้ไขการจอง (จอง)</h3>
        @elseif($carpreemption->status == 1)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> แก้ไขการจอง (ชำระเงินแล้ว)</h3>
        @elseif($carpreemption->status == 2)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> แก้ไขการจอง (ยกเลิก)</h3>
        @elseif($carpreemption->status == 3)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> แก้ไขการจอง (ส่งรถก่อนชำระเงิน)</h3>
        @endif
    @elseif($oper == 'view')
        @if($carpreemption->status == 0)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ดูข้อมูลการจอง (จอง)</h3>
        @elseif($carpreemption->status == 1)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ดูข้อมูลการจอง (ชำระเงินแล้ว)</h3>
        @elseif($carpreemption->status == 2)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ดูข้อมูลการจอง (ยกเลิก)</h3>
        @elseif($carpreemption->status == 3)
            <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ดูข้อมูลการจอง (ส่งรถก่อนชำระเงิน)</h3>
        @endif
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
        {!! Form::model($carpreemption, array('url' => 'carpreemption/save', 'id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @elseif($oper == 'edit')
        {!! Form::model($carpreemption, array('url' => 'carpreemption/save', 'id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($carpreemption, array('id'=>'form-carpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

        <div class="form-group" style="margin-top:10px; @if(!Auth::user()->isadmin ) display: none; @endif" >
            {!! Form::label('provincebranchid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-3">
                {!! Form::select('provincebranchid', $provincebranchselectlist, null, array('id'=>'provinceid', 'class' => 'chosen-select')) !!}
            </div>
        </div>

        <div class="form-group" style="margin-top:10px;" >
            {!! Form::label('bookno', 'เล่มที่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
            <div class="col-sm-1">
                {!! Form::number('bookno',null,array('min' => '0','max' => '999')) !!}
            </div>
            {!! Form::label('no', 'เลขที่', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'width:50px;')) !!}
            <div class="col-sm-1">
                {!! Form::number('no',null,array('min' => '0','max' => '9999')) !!}
            </div>
            {!! Form::label('date', 'วันที่', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'width:50px;')) !!}
            <div class="col-sm-1">
                <div class="input-group">
                    {!! Form::text('date', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date', 'onchange'=>'DateChange();')) !!}
                        <span class="input-group-addon">
						    <i class="fa fa-calendar bigger-110"></i>
						</span>
                </div>
            </div>
            {!! Form::label('documentstatus', 'สถานะเอกสาร', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
            <div class="col-sm-1">
                {!! Form::select('documentstatus', array('0' => '0 - ยังไม่ยื่นเอกสาร', '1' => '1 - ทำสัญญารอผล', '2' => '2 - ผ่านพร้อมส่ง'), null, array('style'=>'font-size:14px; padding:5px 4px 6px; height:34px;')) !!}
            </div>
            {!! Form::label('contractdate', 'วันทำสัญญา', array('class' => 'col-sm-1 control-label no-padding-right','style'=>'width:160px;')) !!}
            <div class="col-sm-2">
                <div class="input-group">
                    {!! Form::text('contractdate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'contractdate')) !!}
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
                                @if($oper == 'new')
                                    <div class="form-group">
                                        <label class="col-sm-1 control-label no-padding-right " >ผู้สั่งจอง</label>
                                        <div class="col-sm-11" >
                                            <label>
                                                {!! Form::radio('customer-type',0, true, array('class' => 'ace', 'onchange'=>'NewCustomer("n")')) !!}
                                                <span class="lbl"> มีชื่อในระบบ</span>&nbsp;&nbsp;
                                                <div class="old-customer" style="display:inline-block">
                                                    {!! Form::select('bookingcustomerid', $customerselectlist, null, array('id'=>'bookingcustomerid', 'class' => 'chosen-select', 'style'=>'width:20%', 'onchange'=>'BookingCustomerChange(this)')) !!}
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
                                @else
                                    <div class="form-group">
                                        <label class="col-sm-1 control-label no-padding-right " >ผู้สั่งจอง</label>
                                        <div class="col-sm-11" >
                                            <label>
                                                {!! Form::text('bookingcustomername', null, array('id'=>'bookingcustomername','style'=>'width:300px;')) !!}
                                                {!! Form::hidden('customer-type',0) !!}
                                                {!! Form::hidden('bookingcustomerid') !!}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    {!! Form::label('bookingcustomeraddress', 'ที่อยู่', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-11">
                                        {!! Form::text('bookingcustomeraddress', null, array('style'=>'width:45%; min-width:250px;')) !!}
                                </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('bookingcustomerprovinceid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomerprovinceid', $provinceselectlist, null, array('id'=>'bookingcustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerProvinceChange(this)')) !!}
                                    </div>
                                    {!! Form::label('bookingcustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomeramphurid', $bookingcustomeramphurselectlist, null, array('id'=>'bookingcustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerAmphurChange(this)')) !!}
                                    </div>
                                    {!! Form::label('bookingcustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('bookingcustomerdistrictid', $bookingcustomerdistrictselectlist, null, array('id'=>'bookingcustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BookingCustomerDistrictChange(this)')) !!}
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
                                        {!! Form::select('bookingcustomeroccupationid', $occupationselectlist, null, array('id'=>'bookingcustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')) !!}
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
                                    {!! Form::label('', '', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        <label>
                                            {!! Form::radio('carobjectivetype', 0, true, array('class' => 'ace', 'onchange'=>'CarobjectivetypeChange();')) !!}
                                            <span class="lbl">  รถใหม่</span>
                                        </label>
                                        &nbsp;
                                        <label>
                                            {!! Form::radio('carobjectivetype', 1, false, array('class' => 'ace', 'onchange'=>'CarobjectivetypeChange();')) !!}
                                            <span class="lbl">  รถบริษัท (รถใช้งาน รถทดสอบ)</span>
                                        </label>
                                    </div>

                                    {!! Form::label('carmodelid', 'รถนิสสัน แบบ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-4">
                                        {!! Form::select('carmodelid', $carmodelselectlist, null, array('id'=>'carmodelid', 'class' => 'chosen-select', 'onchange'=>'CarModelChange(this)', 'style'=>'width:150px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('carsubmodelid', 'รุ่น', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('carsubmodelid', $carsubmodelselectlist, null, array('id'=>'carsubmodelid', 'class' => 'chosen-select', 'onchange'=>'GetPrice()')) !!}
                                    </div>
                                    {!! Form::label('colorid', 'สี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('colorid', $colorselectlist, null, array('id'=>'colorid', 'class' => 'chosen-select', 'onchange'=>'CalColorPrice()')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('pricelistid', 'ราคา', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('pricelistid', $priceselectlist, null, array('id'=>'pricelistid', 'class' => 'chosen-select', 'onchange'=>'PricelistChange()')) !!}
                                    </div>
                                    {!! Form::label('colorprice', 'ค่าสี', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('colorprice', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'colorprice', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>
                                    {!! Form::label('totalcarprice', 'รวม', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('totalcarprice', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'id'=>'totalcarprice', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('discount', 'ส่วนลด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('discount', null, array('step' => '1', 'min' => '0' ,'placeholder' => 'บาท', 'id'=>'discount', 'onchange'=>'CalTransferfee();')) !!}
                                    </div>
                                    {!! Form::label('subdown', 'Sub Down', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('subdown', null, array('step' => '1', 'min' => '0' ,'placeholder' => 'บาท', 'id'=>'subdown', 'onchange'=>'CalTransferfee();')) !!}
                                    </div>
                                    {!! Form::label('accessories', 'บวกอุปกรณ์หลอก', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('accessories', null, array('step' => '1', 'min' => '0' ,'placeholder' => 'บาท')) !!}
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
                                        {!! Form::select('oldcarbrandid', $oldcarbrandselectlist, null, array('id'=>'oldcarbrandid', 'class' => 'chosen-select', 'onchange'=>'OldCarBrandChange(this)')) !!}
                                    </div>
                                    {!! Form::label('oldcarmodelid', 'แบบ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::select('oldcarmodelid', $oldcarmodelselectlist, null, array('id'=>'oldcarmodelid', 'class' => 'chosen-select')) !!}
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
                                        {!! Form::number('oldcarprice', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท')) !!}
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
                                        <div class="col-sm-9">
                                            {!! Form::number('cashpledge', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'onchange'=>'CalCashpledgechargeamount();', 'id' => 'cashpledge')) !!}
                                            &nbsp;&nbsp;
                                            <label>
                                                <span class="lbl">  จ่ายด้วย</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('cashpledgepaymenttype', 0, true, array('class' => 'ace', 'onchange'=>'CashpledgepaymenttypeChange();')) !!}
                                                <span class="lbl">  เงินสด</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('cashpledgepaymenttype', 1, false, array('class' => 'ace', 'onchange'=>'CashpledgepaymenttypeChange();')) !!}
                                                <span class="lbl">  บัตรเครดิต</span>&nbsp;&nbsp;
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group cashpledgepaymenttype1">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('cashpledgechargepercent', '% ค่าธรรมเนียม', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::number('cashpledgechargepercent', null, array('step' => '0.01','placeholder' => '%', 'min'=>'0', 'max'=>'100', 'style'=>'width:70px;', 'onchange'=>'CalCashpledgechargeamount();', 'id' => 'cashpledgechargepercent')) !!}
                                            &nbsp;&nbsp;&nbsp;
                                            {!! Form::label('cashpledgechargeamount', 'จำนวนเงิน') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('cashpledgechargeamount', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id' => 'cashpledgechargeamount')) !!}
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('cashpledgechargefree', 1, true, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'cashpledgechargefree')) !!}
                                                <span class="lbl" style="width:80px;">  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('purchasetype', '2. ซื้อรถยนต์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            <label style="padding-top: 7px;">
                                                {!! Form::radio('purchasetype', 0, true, array('class' => 'ace', 'onchange'=>'PurchasetypeChange();')) !!}
                                                <span class="lbl">  เงินสด</span>
                                            </label>
                                            &nbsp;
                                            <label style="padding-top: 7px;">
                                                {!! Form::radio('purchasetype', 1, false, array('class' => 'ace', 'onchange'=>'PurchasetypeChange();')) !!}
                                                <span class="lbl">  เช่าซื้อกับบริษัท</span>
                                            </label>
                                            &nbsp;&nbsp;
                                            <div class="purchasetype1">
                                                {!! Form::select('finacecompanyid', $finacecompanyselectlist, null, array('id'=>'finacecompanyid', 'class' => 'chosen-select', 'onchange'=>'FinacecompanyChange(this)')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group purchasetype11">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('interestratetypeid', 'อัตราดอกเบี้ย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::select('interestratetypeid', $interestratetypeselectlist, null, array('id'=>'interestratetypeid', 'class' => 'chosen-select')) !!}
                                            &nbsp;&nbsp;
                                            {!! Form::label('interestratemode', 'Mode') !!}
                                            &nbsp;&nbsp;
                                            <label style="padding-top: 7px;">
                                                {!! Form::radio('interestratemode', 0, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  Beginning</span>
                                            </label>
                                            &nbsp;
                                            <label style="padding-top: 7px;">
                                                {!! Form::radio('interestratemode', 1, false, array('class' => 'ace')) !!}
                                                <span class="lbl">  Ending</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group purchasetype11">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('interest', '% ดอกเบี้ย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::number('interest', null, array('step' => '0.01','placeholder' => '%', 'min'=>'0', 'max'=>'100', 'style'=>'width:70px;')) !!}&nbsp;&nbsp;&nbsp;
                                            {!! Form::label('down', 'ดาวน์') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('down', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท')) !!}
                                            &nbsp;&nbsp;&nbsp;
                                            {!! Form::label('installments', 'จำนวนงวด') !!}
                                            &nbsp;&nbsp;
                                            {!! Form::number('installments', null, array('min' => '0','style'=>'width:70px;')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group purchasetype11">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('subsidise', 'SUBSIDISE', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-8">
                                            <label>
                                                {!! Form::number('subsidise', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'id'=>'subsidise', 'onchange'=>'CalTotalFree();')) !!}
                                            </label>
                                            <label><span class="lbl">  (หักค่าชดชเยดอกเบี้ยรถยนต์)</span></label>
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('subsidisefree', 1, true, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'subsidisefree')) !!}
                                                <span class="lbl" style="width:80px;">  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group financingfee">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('financingfee', 'ค่าจัดไฟแนนซ์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('financingfee', 3000, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group transferfee">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('transferfee', '3. ค่าโอน (0.75%)', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3 ">
                                            {!! Form::number('transferfee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group transferoperationfee">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('transferoperationfee', '4. ค่าดำเนินการโอน', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3 ">
                                            {!! Form::number('transferoperationfee', 2000, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group cashpledgeredlabel">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('cashpledgeredlabel', '3. ค่ามัดจำป้ายแดง', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3 ">
                                            {!! Form::number('cashpledgeredlabel', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group registrationtype">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('registrationtype', '4. ค่าจดทะเบียน', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-10">
                                            {!! Form::select('registerprovinceid', $registerprovinceselectlist, null, array('id'=>'registerprovinceid', 'class' => 'chosen-select', 'onchange'=>'Calregistrationfee()')) !!}
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::radio('registrationtype', 0, true, array('class' => 'ace', 'onchange'=>'Calregistrationfee();')) !!}
                                                <span class="lbl">  บุคคล</span>
                                            </label>
                                            &nbsp;
                                            <label>
                                                {!! Form::radio('registrationtype', 1, false, array('class' => 'ace', 'onchange'=>'Calregistrationfee();')) !!}
                                                <span class="lbl">  นิติบุคคล</span>&nbsp;&nbsp;
                                            </label>
                                            <label>
                                                {!! Form::radio('registrationtype', 2, false, array('class' => 'ace', 'onchange'=>'Calregistrationfee();')) !!}
                                                <span class="lbl">  ราชการ</span>&nbsp;&nbsp;
                                                {!! Form::number('registrationfee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100px;', 'id' => 'registrationfee', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('registrationfeefree', 1, false, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'registrationfeefree')) !!}
                                                <span class="lbl" style="width:50px;" >  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('insurancefee', '5. ค่าประกันภัย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-5">
                                            <label>
                                                {!! Form::number('insurancefee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'onchange'=>'CalTotalFree();')) !!}
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('insurancefeefree', 1, false, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'insurancefeefree')) !!}
                                                <span class="lbl" style="width:80px;">  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('compulsorymotorinsurancefee', '6. ค่า พ.ร.บ.', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-5">
                                            <label>
                                            {!! Form::number('compulsorymotorinsurancefee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('compulsorymotorinsurancefeefree', 1, false, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'compulsorymotorinsurancefeefree')) !!}
                                                <span class="lbl" style="width:80px;" >  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('accessoriesfee', '7. ค่าอุปกรณ์', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('accessoriesfee', null, array('step' => '0.01', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly', 'id'=>'accessoriesfee')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('implementfee', '8. ค่าดำเนินการ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-5">
                                            <label>
                                                {!! Form::number('implementfee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'id'=>'implementfee', 'onchange'=>'CalTotalFree();')) !!}
                                            </label>
                                            &nbsp;&nbsp;
                                            <label>
                                                {!! Form::checkbox('implementfeefree', 1, false, array('class' => 'ace', 'onchange'=>'CalTotalFree();', 'id' => 'implementfeefree')) !!}
                                                <span class="lbl" style="width:80px;">  แถม</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('giveawaywithholdingtax', '9. ภาษีหัก ณ ที่จ่าย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-8">
                                            <label>
                                                {!! Form::number('giveawaywithholdingtax', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท')) !!}
                                            </label>
                                            <label><span class="lbl">  (กรณีลูกค้าได้รับของแถม เช่น ทอง)</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('otherfee', '10. ค่าอื่นๆ (1)', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('otherfee', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                        {!! Form::label('otherfeedetail', 'รายละเอียด') !!}
                                        {!! Form::text('otherfeedetail', null, array('style' => 'width:300px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('otherfee2', '11. ค่าอื่นๆ (2)', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('otherfee2', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                        {!! Form::label('otherfeedetail2', 'รายละเอียด') !!}
                                        {!! Form::text('otherfeedetail2', null, array('style' => 'width:300px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('otherfee3', '12. ค่าอื่นๆ (3)', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-3">
                                            {!! Form::number('otherfee3', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;')) !!}
                                        </div>
                                        {!! Form::label('otherfeedetail3', 'รายละเอียด') !!}
                                        {!! Form::text('otherfeedetail3', null, array('style' => 'width:300px;')) !!}
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-sm-9 no-padding-left">
                                        {!! Form::label('datewantgetcar', 'วันที่ต้องการรับรถ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                {!! Form::text('datewantgetcar', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'datewantgetcar', 'onchange'=>'DateChange();')) !!}
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
                                        {!! Form::number('giveawayadditionalcharges', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'onchange'=>'GiveawayadditionalchargesChange();')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('totalfree', 'รวมแถมทั้งหมด', array('class' => 'col-sm-3 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        {!! Form::number('totalfree', null, array('step' => '1', 'min' => '0','placeholder' => 'บาท', 'style'=>'width:100%;', 'id'=>'totalfree', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
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
                                                    {!! Form::select('buyercustomerid', $customerselectlist, null, array('id'=>'buyercustomerid', 'class' => 'chosen-select', 'style'=>'width:25%', 'onchange'=>'BuyerCustomerChange(this)')) !!}
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
                                        {!! Form::select('buyercustomerprovinceid', $provinceselectlist, null, array('id'=>'buyercustomerprovinceid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerProvinceChange(this)')) !!}
                                    </div>
                                    {!! Form::label('buyercustomeramphurid', 'เขต/อำเภอ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomeramphurid', $buyercustomeramphurselectlist, null, array('id'=>'buyercustomeramphurid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerAmphurChange(this)')) !!}
                                    </div>
                                    {!! Form::label('buyercustomerdistrictid', 'แขวง/ตำบล', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('buyercustomerdistrictid', $buyercustomerdistrictselectlist, null, array('id'=>'buyercustomerdistrictid', 'class' => 'chosen-select', 'onchange'=>'BuyerCustomerDistrictChange(this)')) !!}
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
                                        {!! Form::select('buyercustomeroccupationid', $occupationselectlist, null, array('id'=>'buyercustomeroccupationid', 'class' => 'chosen-select', 'style'=>'width:15%;')) !!}
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
                                        {!! Form::select('salesmanemployeeid', $saleemployeeselectlist, null, array('id'=>'salesmanemployeeid', 'class' => 'chosen-select')) !!}
                                    </div>
                                    {!! Form::label('salesmanageremployeeid', 'ผู้จัดการฝ่ายขาย', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('salesmanageremployeeid', $salemanageremployeeselectlist, null, array('id'=>'salesmanageremployeeid', 'class' => 'chosen-select')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('approversemployeeid', 'ผู้อนุมัติ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::select('approversemployeeid', $approveremployeeselectlist, null, array('id'=>'approversemployeeid', 'class' => 'chosen-select')) !!}
                                    </div>
                                    {!! Form::label('approvaldate', 'วันที่อนุมัติ', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            {!! Form::text('approvaldate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy','id'=>'approvaldate', 'onchange'=>'DateChange();')) !!}
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

        function GiveawayadditionalchargesChange(){
            var totalgiveawayfree = 0;
            var datas = $("#grid-table-in-form").jqGrid('getGridParam', 'data');
            if (datas.length > 0) {
                datas.forEach(function (arrayItem) {
                    totalgiveawayfree = parseFloat(totalgiveawayfree) + parseFloat(arrayItem.price);
                });
            }

            var giveawayadditionalcharges = $('#giveawayadditionalcharges').val();
            if (giveawayadditionalcharges == null || giveawayadditionalcharges == '')
                giveawayadditionalcharges = 0;

            if (giveawayadditionalcharges > totalgiveawayfree)
                $('#giveawayadditionalcharges').val(parseFloat(totalgiveawayfree).toFixed(2));

            CalTotalFree();
            CalculateAccessoriesFee();
        }

        function CalTotalFree(){
            var totalfree = 0;
            var datas = $("#grid-table-in-form").jqGrid('getGridParam', 'data');
            if(datas.length > 0) {
                datas.forEach(function (arrayItem) {
                    totalfree = parseFloat(totalfree) + parseFloat(arrayItem.price);
                });
            }

            var giveawayadditionalcharges = $('#giveawayadditionalcharges').val();
            if(giveawayadditionalcharges == null || giveawayadditionalcharges == '')
                giveawayadditionalcharges = 0;

            if (giveawayadditionalcharges <= totalfree) {
                totalfree = parseFloat(totalfree) - parseFloat(giveawayadditionalcharges);
            }

            if($('#registrationfeefree').is(':checked')){
                var registrationfee = $('#registrationfee').val();
                if(registrationfee == null || registrationfee == '')
                    registrationfee = 0;
                totalfree = parseFloat(totalfree) + parseFloat(registrationfee);
            }

            if ($('#insurancefeefree').is(':checked')) {
                var insurancefee = $('#insurancefee').val();
                if (insurancefee == null || insurancefee == '')
                    insurancefee = 0;
                totalfree = parseFloat(totalfree) + parseFloat(insurancefee);
            }

            if($('#compulsorymotorinsurancefeefree').is(':checked')){
                var compulsorymotorinsurancefee = $('#compulsorymotorinsurancefee').val();
                if(compulsorymotorinsurancefee == null || compulsorymotorinsurancefee == '')
                    compulsorymotorinsurancefee = 0;
                totalfree = parseFloat(totalfree) + parseFloat(compulsorymotorinsurancefee);
            }

            if ($('#subsidisefree').is(':checked')) {
                var subsidise = $('#subsidise').val();
                if (subsidise == null || subsidise == '')
                    subsidise = 0;
                totalfree = parseFloat(totalfree) + parseFloat(subsidise);
            }

            if($('#implementfeefree').is(':checked')){
                var implementfee = $('#implementfee').val();
                if(implementfee == null || implementfee == '')
                    implementfee = 0;
                totalfree = parseFloat(totalfree) + parseFloat(implementfee);
            }

            if ($('#cashpledgechargefree').is(':checked')) {
                var cashpledgechargeamount = $('#cashpledgechargeamount').val();
                if (cashpledgechargeamount == null || cashpledgechargeamount == '')
                    cashpledgechargeamount = 0;
                totalfree = parseFloat(totalfree) + parseFloat(cashpledgechargeamount);
            }

            $('#totalfree').val(parseFloat(totalfree).toFixed(2));
        }

        function CalculateAccessoriesFee(){
            var datas = $("#grid-table-in-form2").jqGrid('getGridParam', 'data');

            var giveawayadditionalcharges = $('#giveawayadditionalcharges').val();
            if(giveawayadditionalcharges == null || giveawayadditionalcharges == '')
                giveawayadditionalcharges = 0;

            if(datas.length > 0) {
                var ids = [];
                datas.forEach(function (arrayItem) {
                    ids.push(arrayItem.giveawayid);
                });

                var giveawayids = ids.join();
                $.get('{{$pathPrefix}}carpreemption/calculateaccessoriesfee/'+giveawayids, function(data){
                    $('#accessoriesfee').val(parseFloat(giveawayadditionalcharges) + parseFloat(data));
                });
            }
            else{
                $('#accessoriesfee').val(giveawayadditionalcharges);
            }
        }

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

            if(this.id == "grid-table-in-form"){
                CalTotalFree();
            }
            if(this.id == "grid-table-in-form2"){
                CalculateAccessoriesFee();
            }

            return {};
        };

        $(document).ready(function() {
            var grid_selector = "#grid-table-in-form";
            var pager_selector = "#grid-pager";
            var grid_selector2 = "#grid-table-in-form2";
            var pager_selector2 = "#grid-pager2";

            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                resizeGridInForm('grid-table-in-form');
                resizeGridInForm('grid-table-in-form2');
            });
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            });

            var parent_column2 = $(grid_selector2).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector2).jqGrid( 'setGridWidth', parent_column2.width() );
                }
            });

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
                    url: window.location.origin+'/giveaway/check_saleprice',
                    data: { id:giveawayid,saleprice:value, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    async: false,
                    datatype: 'text',
                    success: function (data) {
                        if (!data) result = [true, ""];
                        else result = [false,"มูลค่าที่แถมห้ามต่ำกว่า "+data];
                    }
                });
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                        CalTotalFree();
                        return true;
                    },
                    processing: true
                }
            );

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
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                            CalculateAccessoriesFee();
                            return true;
                        },
                        processing: true
                    }
            );

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

            var cashpledgepaymenttype = jQuery('input[name=cashpledgepaymenttype]:checked').val();
            if (cashpledgepaymenttype == 0) {
                $(".cashpledgepaymenttype1").css("display", "none");
            }
            else {
                $(".cashpledgepaymenttype1").css("display", "block");
            }

            var purchasetype = jQuery( 'input[name=purchasetype]:checked' ).val();
            if(purchasetype == 0){
                $(".purchasetype1").css("display","none");
                $(".purchasetype11").css("display","none");
            }
            else{
                $(".purchasetype1").css("display","inline-block");
                $(".purchasetype11").css("display","block");
            }

            var carobjectivetype = jQuery( 'input[name=carobjectivetype]:checked' ).val();
            if(carobjectivetype == 0){
                $(".financingfee").css("display","none");
                $(".transferfee").css("display","none");
                $(".transferoperationfee").css("display","none");
            }
            else if(carobjectivetype == 1){
                if(purchasetype == 0){
                    $(".financingfee").css("display","none");
                }

                $(".cashpledgeredlabel").css("display","none");
                $(".registrationtype").css("display","none");
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
            @elseif($oper == 'edit')
                $("#bookingcustomername").prop("disabled", true);
            @endif

        });



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