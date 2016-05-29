@extends('app')

@section('content')
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
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/report/carstock') }}">
                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                <div class="form-group">
                                    <div class="col-sm-offset-5 col-sm-5">
                                        <button type="submit" class="btn btn-primary">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
@endsection