@extends('app')
@section('title','รายงาน')

@section('content')
    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> รายงาน</h3>

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

    <!-- สต็อครถ -->
    <div class="row">
        <div class="col-xs-1 col-sm-1"></div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title">สต็อครถ</h4>
                    <div class="widget-toolbar">
                        <a href="form-elements.html#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-body-inner" style="display: block;">
                        <div class="widget-main">
                            {!! Form::open(array('url' => 'report/carstock', 'id'=>'report-carstock', 'class'=>'form-horizontal', 'role'=>'form')) !!}
                                <table>
                                <tr>
                                    <td>จังหวัด</td>
                                    <td style="padding-left: 10px;">
                                        {!! Form::select('provinceid', $provincebranchselectlist, null, array('class' => 'chosen-select')); !!}
                                    </td>
                                    <td style="padding-left: 10px;">เรียงตาม</td>
                                    <td style="padding-left: 10px;">
                                        {!! Form::select('orderbytype', array('1' => 'วันที่รับรถ', '2' => 'แบบ/รุ่นและวันที่รับรถ'), null, array('class' => 'chosen-select')); !!}
                                    </td>
                                    <td style="padding-left: 10px;">วันที่</td>
                                    <td style="padding-left: 10px;">
                                        <div class="input-group">
                                            {!! Form::text('date', date("d-m-Y"), array('class' => 'form-control date-picker', 'data-date-format'=>'dd-mm-yyyy', 'id'=>'date', 'onchange'=>'DateChange();')) !!}
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td style="padding-left: 20px;">
                                        <button id="btnSubmit" class="btn btn-sm btn-primary" type="submit">
                                            Generate
                                        </button>
                                    </td>
                                </tr>
                                </table>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true
            })

            $('.date-picker').parent().width(140);
            $('.date-picker').width(90);

            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function() {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width':180});
            }).trigger('resize.chosen');
        });
    </script>
@endsection