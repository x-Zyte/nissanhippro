@extends('app')
@section('title','รายงาน')

@section('content')
    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> รายงาน</h3>
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
                                <div class="form-group">
                                    {!! Form::label('provinceid', 'จังหวัด', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2" style="width: 200px;">
                                        {!! Form::select('provinceid', $provincebranchselectlist, null, array('class' => 'chosen-select')); !!}
                                    </div>
                                    {!! Form::label('orderbytype', 'เรียงตาม', array('class' => 'col-sm-1 control-label no-padding-right')) !!}
                                    <div class="col-sm-2"  style="width: 200px;">
                                        {!! Form::select('orderbytype', array('1' => 'วันที่รับรถ', '2' => 'แบบ/รุ่นและวันที่รับรถ'), null, array('class' => 'chosen-select')); !!}
                                    </div>

                                    <div class="col-sm-1" style="margin-left: 20px;">
                                        <button id="btnSubmit" class="btn btn-sm btn-primary" type="submit">
                                            Generate
                                        </button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize
            $(window).on('resize.chosen', function() {
                var w = $('.chosen-select').parent().width();
                $('.chosen-select').next().css({'width':180});
            }).trigger('resize.chosen');
        });
    </script>
@endsection