@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Home</div>

        <div class="panel-body">
            You are logged in!
        </div>

        {{--<div class="panel-body" style="padding: 0px;">
            {!! Form::open(array('url' => 'home/import', 'files' => true)) !!}
            <div id="import" class="form-group col-xs-12">
                <div class="col-xs-3">
                    <input type="file" name="file" id="input-file">
                </div>
                {!! Form::submit('Import') !!}
            </div>
            {!! Form::close() !!}
        </div>--}}
    </div>

    <script type="text/javascript">
        $('#input-file').ace_file_input({
            no_file: 'ยังไม่ได้เลือกไฟล์...',
            btn_choose: 'เลือกไฟล์',
            btn_change: 'เปลี่ยนไฟล์',
            droppable: false,
            onchange: null,
            thumbnail: false, //| true | large
            allowExt: ["xls", "xlsx"],
            allowMime: ["application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"]
            //whitelist:'gif|png|jpg|jpeg'
            //blacklist:'exe|php'
            //onchange:''
            //
        });
        //pre-show a file name, for example a previously selected file
        //$('#id-input-file-1').ace_file_input('show_file_list', ['myfile.txt'])

        function ResetFileInput(e) {
            var file_input = $(e.closest(".ace-file-input")).find("input[type=file]");
            file_input.ace_file_input('reset_input');
        }
    </script>
@endsection
