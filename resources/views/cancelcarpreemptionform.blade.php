@extends('app')

@if($oper == 'new')
    @section('title','เพิ่มยกเลิกการจองใหม่')
@elseif($oper == 'edit')
    @section('title','แก้ไขยกเลิกการจอง '.$cancelcarpreemption->bookno.'/'.$cancelcarpreemption->no)
@elseif($oper == 'view')
    @section('title','ดูข้อมูลยกเลิกการจอง '.$cancelcarpreemption->bookno.'/'.$cancelcarpreemption->no)
@endif

@section('menu-cancelcarpreemption-class','active')
@section('pathPrefix',$pathPrefix)

@section('content')
    <script type="text/javascript">
        function CarpreemptionChange(sel)
        {
            var carpreemptionid = sel.value;

            $.get('{{$pathPrefix}}carpreemption/getbyidforcancelcarpreemption/'+carpreemptionid, function(data){
                $('#customer').val(data.customer);
                $('#carmodel').val(data.carmodel);
                $('#carpreemptiondate').val(data.date);
                $('#cashpledge').val(data.cashpledge);
                $('#salesmanemployee').val(data.salesmanemployee);

                $amountapproved = $('#amountapproved');
                var amountapproved = $amountapproved.val();
                if(amountapproved == null || amountapproved == '')
                    amountapproved = 0;

                if(amountapproved > data.cashpledge)
                    $amountapproved.val(data.cashpledge);

                $amountapproved.prop("max", data.cashpledge);
            });
        }

        function RefundamountChange()
        {
            debugger;
            $refundamount = $('#refundamount');
            $refunddate = $('#refunddate');
            $refunddocno = $('#refunddocno');

            var refundamount = $refundamount.val();
            if (refundamount == null || refundamount == '')
                refundamount = 0;

            $confiscateamount = $('#confiscateamount');
            $confiscatedate = $('#confiscatedate');
            $confiscatedocno = $('#confiscatedocno');

            $cashpledge = $('#cashpledge');
            var cashpledge = $cashpledge.val();
            if(cashpledge == null || cashpledge == '')
                cashpledge = 0;

            if (parseFloat(refundamount) >= parseFloat(cashpledge)) {
                $refundamount.val(cashpledge);
                $confiscateamount.val(0);
                $confiscatedate.val(null);
                $confiscatedocno.val(null);
            }
            else if (parseFloat(refundamount) <= 0) {
                $refundamount.val(0);
                $refunddate.val(null);
                $refunddocno.val(null);
                $confiscateamount.val(cashpledge);
            }
            else {
                var confiscateamount = parseFloat(cashpledge) - parseFloat(refundamount);
                $confiscateamount.val(confiscateamount);
            }
        }

        function ConfiscateamountChange() {
            debugger;
            $confiscateamount = $('#confiscateamount');
            $confiscatedate = $('#confiscatedate');
            $confiscatedocno = $('#confiscatedocno');

            var confiscateamount = $confiscateamount.val();
            if (confiscateamount == null || confiscateamount == '')
                confiscateamount = 0;

            $refundamount = $('#refundamount');
            $refunddate = $('#refunddate');
            $refunddocno = $('#refunddocno');

            $cashpledge = $('#cashpledge');
            var cashpledge = $cashpledge.val();
            if (cashpledge == null || cashpledge == '')
                cashpledge = 0;

            if (parseFloat(confiscateamount) >= parseFloat(cashpledge)) {
                $confiscateamount.val(cashpledge);
                $refundamount.val(0);
                $refunddate.val(null);
                $refunddocno.val(null);
            }
            else if (parseFloat(confiscateamount) <= 0) {
                $confiscateamount.val(0);
                $confiscatedate.val(null);
                $confiscatedocno.val(null);
                $refundamount.val(cashpledge);
            }
            else {
                var refundamount = parseFloat(cashpledge) - parseFloat(confiscateamount);
                $refundamount.val(refundamount);
            }
        }
    </script>

    @if($oper == 'new')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-ban"></i> เพิ่มยกเลิกการจองใหม่</h3>
    @elseif($oper == 'edit')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-ban"></i> แก้ไขยกเลิกการจอง</h3>
    @elseif($oper == 'view')
        <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-ban"></i> ดูข้อมูลยกเลิกการจอง</h3>
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
        {!! Form::model($cancelcarpreemption, array('url' => 'cancelcarpreemption/save', 'id'=>'form-cancelcarpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @elseif($oper == 'edit')
        {!! Form::model($cancelcarpreemption, array('url' => 'cancelcarpreemption/save', 'id'=>'form-cancelcarpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
        {!! Form::hidden('id') !!}
    @elseif($oper == 'view')
        {!! Form::model($cancelcarpreemption, array('id'=>'form-cancelcarpreemption', 'class'=>'form-horizontal', 'role'=>'form')) !!}
    @endif

    <div class="form-group" style="margin-top:10px;" >
        {!! Form::label('carpreemptionid', 'ยกเลิกการจอง เล่มที่/เลขที่', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
        <div class="col-sm-3">
            @if($oper == 'new')
                {!! Form::select('carpreemptionid', $carpreemptionselectlist, null, array('id'=>'carpreemptionid', 'class' => 'chosen-select', 'onchange'=>'CarpreemptionChange(this)')) !!}
            @else
                {!! Form::select('carpreemptionid', $carpreemptionselectlist, null, array('id'=>'carpreemptionid', 'class' => 'chosen-select', 'onchange'=>'CarpreemptionChange(this)', 'disabled'=>'disabled')) !!}
                {!! Form::hidden('carpreemptionid') !!}
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 ">
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
                            <div class="form-group" style="padding-top:5px;">
                                {!! Form::label('toemployeeid', 'เรียน', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                <div class="col-sm-3">
                                    {!! Form::select('toemployeeid', $toemployeeselectlist, null, array('id'=>'toemployeeid', 'class' => 'chosen-select')) !!}
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group" style="padding-top:5px;">
                                {!! Form::label('customer', 'ชื่อลูกค้า', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                <div class="col-sm-3">
                                    {!! Form::text('customer', null, array('style'=>'width:250px;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                </div>

                                {!! Form::label('carmodel', 'แบบ/รุ่น รถ', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                <div class="col-sm-4">
                                    {!! Form::text('carmodel', null, array('style'=>'width:100%;', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                {!! Form::label('carpreemptiondate', 'ใบจองลงวันที่', array('class' => 'col-sm-2 control-label no-padding-right')) !!}
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::text('carpreemptiondate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'carpreemptiondate', 'class' => 'input-readonly', 'readonly'=>'readonly')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right">ยกเลิกเนื่องจาก</label>
                                <div class="col-sm-10">
                                    <label style="margin-right:20px;">
                                        {!! Form::radio('cancelreasontype', 0, false, array('class' => 'ace')) !!}
                                        <span class="lbl"> สัญญาไม่ผ่าน </span>
                                    </label>
                                    <label style="margin-right:20px;">
                                        {!! Form::radio('cancelreasontype', 1, false, array('class' => 'ace')) !!}
                                        <span class="lbl"> ไม่มีรถ </span>
                                    </label>
                                    <label style="margin-right:5px;">
                                        {!! Form::radio('cancelreasontype', 2, false, array('class' => 'ace')) !!}
                                        <span class="lbl"> อื่น ๆ </span>
                                    </label>
                                    {!! Form::text('cancelreasondetails', null, array('style'=>'width:200px;')) !!}
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="approve_value">จำนวนเงินจอง</label>
                                <div class="col-sm-3">
                                    {!! Form::number('cashpledge', null, array('style'=>'width:100px;', 'class' => 'input-readonly', 'readonly'=>'readonly','step' => '0.01', 'min' => '0', 'id'=>'cashpledge')) !!}
                                    <label>บาท</label>
                                </div>

                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="remark">หมายเหตุเพิ่มเติม</label>
                                <div class="col-sm-6">
                                    {!! Form::textarea('remark', null, ['size' => '0x1','class' => 'autosize-transition limited', 'style' => 'width:100%;']) !!}
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right"
                                       style="padding-top:6px;">อนุมัติ</label>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><label style="width: 60px; margin-left: 10px;">คืนเงิน</label></td>
                                        <td>{!! Form::number('refundamount', null, array('style'=>'width:100px;','step' => '0.01', 'min' => '0', 'id'=>'refundamount','onchange'=>'RefundamountChange();')) !!}</td>
                                        <td><label style="width: 30px; margin-left: 5px;">บาท</label></td>
                                        <td><label style="width: 70px; margin-left: 10px;">วันที่คืนจอง</label></td>
                                        <td>
                                            <div class="input-group">
                                                {!! Form::text('refunddate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'refunddate')) !!}
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td><label style="width: 80px; margin-left: 10px;">เลขที่เอกสาร</label></td>
                                        <td>{!! Form::text('refunddocno', null, array('style'=>'width:100px;','id'=>'refunddocno')) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"></label>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><label style="width: 60px; margin-left: 10px;">ไม่คืนเงิน</label></td>
                                        <td>{!! Form::number('confiscateamount', null, array('style'=>'width:100px;','step' => '0.01', 'min' => '0', 'id'=>'confiscateamount','onchange'=>'ConfiscateamountChange();')) !!}</td>
                                        <td><label style="width: 30px; margin-left: 5px;">บาท</label></td>
                                        <td><label style="width: 70px; margin-left: 10px;">วันที่ยึดจอง</label></td>
                                        <td>
                                            <div class="input-group">
                                                {!! Form::text('confiscatedate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'confiscatedate')) !!}
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td><label style="width: 80px; margin-left: 10px;">เลขที่เอกสาร</label></td>
                                        <td>{!! Form::text('confiscatedocno', null, array('style'=>'width:100px;','id'=>'confiscatedocno')) !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group" style="padding-top:15px;">
                                <label class="col-sm-2 control-label no-padding-right" for="sale_employee">พนักงานขาย</label>
                                <div class="col-sm-3">
                                    {!! Form::text('salesmanemployee', null, array('style'=>'width:250px;', 'class' => 'input-readonly', 'readonly'=>'readonly','id'=>'salesmanemployee')) !!}
                                </div>
                                <label class="col-sm-1 control-label no-padding-right" for="sale_date" >วันที่</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::text('salesmanemployeedate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'salesmanemployeedate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="account_employee">พนักงานบัญชี</label>
                                <div class="col-sm-3">
                                    {!! Form::select('accountemployeeid', $accountandfinanceemployeeselectlist, null, array('id'=>'accountemployeeid', 'class' => 'chosen-select')) !!}
                                </div>
                                <label class="col-sm-1 control-label no-padding-right" for="account_date" >วันที่</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::text('accountemployeedate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'accountemployeedate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="finance_employee">พนักงานการเงิน</label>
                                <div class="col-sm-3">
                                    {!! Form::select('financeemployeeid', $accountandfinanceemployeeselectlist, null, array('id'=>'financeemployeeid', 'class' => 'chosen-select')) !!}
                                </div>
                                <label class="col-sm-1 control-label no-padding-right" for="finance_date" >วันที่</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::text('financeemployeedate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'financeemployeedate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="customerbuy">ผู้อนุมัติ</label>
                                <div class="col-sm-3">
                                    {!! Form::select('approversemployeeid', $toemployeeselectlist, null, array('id'=>'approversemployeeid', 'class' => 'chosen-select')) !!}
                                </div>
                                <label class="col-sm-1 control-label no-padding-right" for="approve_date" >วันที่</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::text('approversemployeedate', null, array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'approversemployeedate')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>

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

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        $(document).ready(function() {
            //datepicker plugin
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            })
                //show datepicker when clicking on the icon
                    .next().on(ace.click_event, function(){
                        $(this).prev().focus();
                    });


            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function() {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width':189});

                $('#toemployeeid').width(250);
                $('#toemployeeid_chosen').width(250);

                $('#accountemployeeid').width(250);
                $('#accountemployeeid_chosen').width(250);

                $('#financeemployeeid').width(250);
                $('#financeemployeeid_chosen').width(250);

                $('#approversemployeeid').width(250);
                $('#approversemployeeid_chosen').width(250);

            }).trigger('resize.chosen');

            $('.date-picker').parent().width(140);
            $('.date-picker').width(90);

            @if($oper == 'view')
                $("#form-cancelcarpreemption :input").prop("disabled", true);
                $(".chosen-select").attr('disabled', true).trigger("chosen:updated");
            @endif

        });



        $('#form-cancelcarpreemption').submit(function(){ //listen for submit event
            return true;
        });
    </script>
@endsection