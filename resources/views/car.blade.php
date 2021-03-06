@extends('app')
@section('title','รับรถเข้าสต๊อก')
@section('menu-car-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-car"></i> รับรถเข้าสต๊อก</h3>

    <div class="panel-body" style="padding: 0px;">
        {!! Form::open(array('url' => 'car/import', 'files' => true, 'id'=>'form-import')) !!}
        <div id="import" class="form-group col-xs-12">
            <div class="col-xs-3">
                <input type="file" name="file" id="input-file">
            </div>
            {!! Form::submit('Import') !!}
        </div>
        {!! Form::close() !!}
    </div>

    <table id="grid-table"></table>

    <div id="grid-pager"></div>

    <script type="text/javascript">
        var $path_base = "..";//this will be used for editurl parameter
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        $('#modal').hide();

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

        $('#form-import').submit(function () { //listen for submit event
            if ($('#input-file').get(0).files.length === 0) {
                alert("กรุณาเลือกไฟล์");
                return false;
            }
            $('#modal').show();
            return true;
        });

        $(document).ready(function() {
            var grid_selector = "#grid-table";
            var pager_selector = "#grid-pager";

            //resize to fit page size
            $(window).on('resize.jqGrid', function () {
                resizeGrid();
            });
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            });

            var candeletedata = false;
            if('{{Auth::user()->isadmin}}' == '1' || '{{Auth::user()->candeletedata}}' == '1'){
                candeletedata = true;
            }

            var defaultProvince = '';
            var hiddenProvince = false;
            if('{{Auth::user()->isadmin}}' == '0'){
                defaultProvince = '{{$defaultProvince}}';
                hiddenProvince = true;
            }

            $(grid_selector).jqGrid({
                url:'car/read',
                datatype: "json",
                colNames:['จังหวัด','ประเภทข้อมูล','คันที่','เลขที่ Do', 'วันที่ออก Do', 'วันที่รับรถเข้า', 'เลขเครื่อง', 'เลขตัวถัง', 'รถสำหรับ', 'เลขกุญแจ','แบบ','รุ่น', 'สี','ซื้อจาก','ชื่อดีลเลอร์','จอดอยู่ที่','ใบรับรถเข้า','วันที่แจ้งขาย','ขายแล้ว','ส่งรถแล้ว'], //'ใบส่งรถให้ลูกค้า'],
                colModel:[
                    {name:'provinceid',index:'provinceid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$provinceselectlist}}", defaultValue:defaultProvince},hidden:hiddenProvince
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$provinceselectlist}}" }},
                    {
                        name: 'datatype',
                        index: 'datatype',
                        width: 70,
                        editable: true,
                        edittype: "select",
                        formatter: 'select',
                        align: 'center',
                        hidden: true,
                        editrules: {required: true, edithidden: true}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:เก่า;1:ปัจจุบัน" }
                        ,
                        editoptions: {
                            value: "0:เก่า;1:ปัจจุบัน", defaultValue: 1,
                        dataEvents :[{type: 'change', fn: function(e){
                            var thisval = $(e.target).val();
                            var objective = $('#objective').val();
                            if(thisval == 0){
                                $('#tr_no').show();
                                if(objective == 0)
                                    $('#tr_keyno').show();
                                else {
                                    $('#tr_keyno').hide();
                                    $('#keyno').val('');
                                }
                            }
                            else if(thisval == 1){
                                $('#tr_no').hide();
                                $('#no').val('');
                                $('#tr_keyno').hide();
                                $('#keyno').val('');
                            }
                        }}]
                    }},
                    {name:'no',index:'no', width:50,editable: true,editoptions:{size:"5"},align:'center',editrules:{custom: true, custom_func: check_no}},
                    {name:'dono',index:'dono', width:100,editable: true,editoptions:{size:"10"},align:'left',editrules:{required:true}},
                    {name:'dodate',index:'dodate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                        ,editrules:{required:true, custom: false, custom_func: check_dodate}},
                    {name:'receiveddate',index:'receiveddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                        ,editrules:{custom: true, custom_func: check_receiveddate}},
                    {name:'engineno',index:'engineno', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},
                        editrules:{custom: true, custom_func: check_engineno},align:'left'},
                    {name:'chassisno',index:'chassisno', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},
                        editrules:{required:true,custom: true, custom_func: check_AZ09},align:'left'},
                    {name:'objective',index:'objective', width:100, editable: true,edittype:"select",formatter:'select',align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:ขาย;1:ใช้งาน;2:ทดสอบ" }
                        ,editoptions:{value: "0:ขาย;1:ใช้งาน;2:ทดสอบ",
                        dataEvents :[{type: 'change', fn: function(e){
                            var thisval = $(e.target).val();
                            var datatype = $('#datatype').val();
                            if(thisval == 0){
                                if(datatype == 0)
                                    $('#tr_keyno').show();
                                else {
                                    $('#tr_keyno').hide();
                                    $('#keyno').val('');
                                }
                            }
                            else{
                                $('#tr_keyno').hide();
                                $('#keyno').val('');
                            }
                        }}]}},
                    {name:'keyno',index:'keyno', width:50,editable: true,editoptions:{size:"5"},align:'center'
                        ,editrules:{custom: true, custom_func: check_keyno}},
                    {name:'carmodelid',index:'carmodelid', width:150, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$carmodelselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('carsubmodel/readSelectlist/'+thisval, function(data){
                                    $('#carsubmodelid').children('option:not(:first)').remove();
                                    $.each(data, function(i, option) {
                                        $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });

                                $.get('carmodelcolor/readSelectlist/'+thisval, function(data){
                                    $('#colorid').children('option:not(:first)').remove();
                                    $.each(data, function(i, option) {
                                        $('#colorid').append($('<option/>').attr("value", option.id).text(option.code + ' - ' + option.name));
                                    });
                                });
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carmodelselectlist}}" }
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$carsubmodelselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carsubmodelselectlist}}" }},
                    {name:'colorid',index:'colorid', width:180, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$colorselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$colorselectlist}}" }},
                    {name:'receivetype',index:'receivetype', width:100, editable: true,edittype:"select",formatter:'select',align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:NMT;1:ดีลเลอร์อื่น" },
                        editoptions:{value: "0:NMT;1:ดีลเลอร์อื่น",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                if(thisval == 0){
                                    $('#tr_dealername').hide();
                                    $('#dealername').val('');
                                }
                                else{
                                    $('#tr_dealername').show();
                                }
                            }}]
                        }
                    },
                    {name:'dealername',index:'dealername', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},align:'left',
                        editrules:{custom: true, custom_func: check_dealername}},
                    {name:'parklocation',index:'parklocation', width:100,editable: true,editoptions:{size:"20",maxlength:"50"}
                        ,align:'left'},
                    {name:'receivecarfilepath',index:'receivecarfilepath',width:100,editable: true,edittype:'file',editoptions:{enctype:"multipart/form-data"},formatter:imageLinkFormatter,search:false,align:'center'},
                    {name:'notifysolddate',index:'notifysolddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                        },
                    {name:'issold',index:'issold', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'isdelivered',index:'isdelivered', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }}
                    /*{name:'isregistered',index:'isregistered', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }}*/
                ],
                viewrecords : true,
                rowNum:10,
                rowList:[10,20,30],
                pager : pager_selector,
                altRows: true,
                multiselect: true,
                multiboxonly: true,

                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        styleCheckbox(table);

                        updateActionIcons(table);
                        updatePagerIcons(table);
                        enableTooltips(table);
                    }, 0);
                },

                editurl: "car/update",
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            function check_dealername(value, colname) {
                var receivetype = $('#receivetype').val();
                if(receivetype == 0) return [true, ""];
                if(value == null || value == '') return [false,"กรุณาใส่ชื่อดีลเลอร์"];
                else return [true, ""];
            }

            function check_no(value, colname) {
                var datatype = $('#datatype').val();

                if(datatype == 0 && (value == null || value == ''))
                    return [false,"กรุณาใส่เลขคันที่"];

                return [true, ""];
            }

            function check_engineno(value, colname) {
                var datatype = $('#datatype').val();

                if(datatype == 0 && (value == null || value == ''))
                    return [false,"กรุณาใส่เลขเครื่อง"];

                if(datatype == 1 && (value == null || value == ''))
                    return [true, ""];

                var re = new RegExp("^[A-Z0-9]+$");
                return [re.test(value),"เลขเครื่อง " + value + " ต้องเป็นภาษาอังกฤษตัวพิมพ์ใหญ่ ตัวเลข เท่านั้น"];

                //return [true, ""];
            }

            function check_keyno(value, colname) {
                var datatype = $('#datatype').val();
                var objective = $('#objective').val();

                if(datatype == 0 && objective == 0 && (value == null || value == ''))
                    return [false,"กรุณาใส่เลขกุญแจ"];

                if(objective != 0) $('#keyno').val(null);

                return [true, ""];
            }

            function check_dodate(value, colname) {
                var receiveddate = $('#receiveddate').val();
                var a = new Date(value);
                var b = new Date(receiveddate);
                if(a.getTime() < b.getTime()) return [true, ""];
                else{
                    return [false,"วันที่ออก Do ต้องน้อยกว่า วันที่รับรถเข้า"];
                }
            }

            function check_receiveddate(value, colname) {
                var datatype = $('#datatype').val();
                if(datatype == 0 && (value == null || value == ''))
                    return [false,"กรุณาใส่วันที่รับรถเข้า"];
                if(datatype == 1 && (value == null || value == ''))
                    return [true, ""];

                var dodate = $('#dodate').val();
				
				var dodateArr = dodate.split("-");
				var receiveddateArr = value.split("-");
				
				var newDodate = new Date(dodateArr[1]+'-'+dodateArr[0]+'-'+dodateArr[2]);
				var newReceiveddate = new Date(receiveddateArr[1]+'-'+receiveddateArr[0]+'-'+receiveddateArr[2]);

                if(newDodate.getTime() < newReceiveddate.getTime()){
                    return [true, ""];
                }
                else{
                    return [false,"วันที่ออก Do ต้องน้อยกว่า วันที่รับรถเข้า"];
                }
            }

            function uploadfiles(){
                var receivecarfilepath = $("#receivecarfilepath");
                if(receivecarfilepath.val() != '' && receivecarfilepath.val() != null){
                    var data = new FormData();
                    data.append('_token','{{ csrf_token() }}');
                    data.append('engineno',$('#engineno').val());
                    data.append('chassisno',$('#chassisno').val());
                    data.append('receivecarfile', receivecarfilepath.prop('files')[0]);

                    return $.ajax({
                        url: 'car/upload',
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function(data, textStatus, jqXHR){
                            alert("ดำเนินการสำเร็จ");
                            jQuery(grid_selector).trigger('reloadGrid');
                            return [true,""];
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            alert(textStatus);
                            return [false,textStatus];
                        }
                    });
                }
                else{
                    alert("ดำเนินการสำเร็จ");
                    jQuery(grid_selector).trigger('reloadGrid');
                    return [true,""];
                }
            }

            //navButtons
            jQuery(grid_selector).jqGrid('navGrid',pager_selector,
                { 	//navbar options
                    edit: true,
                    editicon : 'ace-icon fa fa-pencil blue',
                    add: true,
                    addicon : 'ace-icon fa fa-plus-circle purple',
                    del: candeletedata,
                    delicon : 'ace-icon fa fa-trash-o red',
                    search: true,
                    searchicon : 'ace-icon fa fa-search orange',
                    refresh: true,
                    refreshicon : 'ace-icon fa fa-refresh green',
                    view: true,
                    viewicon : 'ace-icon fa fa-search-plus grey'
                },
                {
                    //edit record form
                    closeAfterEdit: true,
                    width: 600,
                    recreateForm: true,
                    viewPagerButtons : false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                        style_edit_form(form);

                        var carmodelid = $('#carmodelid').val();
                        var carsubmodelid = $('#carsubmodelid').val();
                        var colorid = $('#colorid').val();
                        var receivetype = $('#receivetype').val();
                        var datatype = $('#datatype').val();

                        if(receivetype == 0){
                            $('#tr_dealername', form).hide();
                        }
                        $('#tr_receivetype', form).hide();
                        if(datatype == 1){
                            $('#tr_no', form).hide();
                            $('#tr_keyno', form).hide();
                        }
                        $('#tr_datatype', form).hide();
                        $('#tr_issold', form).hide();
                        $('#tr_isdelivered', form).hide();
                        $('#tr_objective', form).hide();

                        $.get('carsubmodel/readSelectlist/'+carmodelid, function(data){
                            $('#carsubmodelid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#carsubmodelid').val(carsubmodelid);
                        });

                        $.get('carmodelcolor/readSelectlist/'+carmodelid, function(data){
                            $('#colorid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#colorid').append($('<option/>').attr("value", option.id).text(option.code + ' - ' + option.name));
                            });
                            $('#colorid').val(colorid);
                        });

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            showConfirmClose = false;
                            $.get('car/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                                $(grid_selector).setColProp('colorid', { editoptions: { value: data.colorselectlist } });
                            });
                            return uploadfiles();
                        }else{
                            return [false,response.responseText];
                        }
                    },
                    savekey: [true, 13],
                    modal:true,
                    onClose : function()
                    {
                        if(!showConfirmClose){
                            showConfirmClose = true;
                            return true;
                        }

                        if (confirm("คุณต้องการที่จะยกเลิกการ เพิ่ม/แก้ไข ข้อมูล ใช่หรือไม่!!")) return true;
                        else return false;
                    }
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                .wrapInner('<div class="widget-header" />');
                        style_edit_form(form);

                        $('#carsubmodelid').children('option:not(:first)').remove();
                        $('#colorid').children('option:not(:first)').remove();

                        $('#tr_dealername', form).hide();
                        $('#tr_no', form).hide();
                        $('#tr_keyno', form).hide();
                        $('#tr_issold', form).hide();
                        $('#tr_isdelivered', form).hide();

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            showConfirmClose = false;
                            $.get('car/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                                $(grid_selector).setColProp('colorid', { editoptions: { value: data.colorselectlist } });
                            });
                            return uploadfiles();
                        }else{
                            return [false,response.responseText];
                        }
                    },
                    savekey: [true, 13],
                    modal:true,
                    onClose : function()
                    {
                        if(!showConfirmClose){
                            showConfirmClose = true;
                            return true;
                        }

                        if (confirm("คุณต้องการที่จะยกเลิกการ เพิ่ม/แก้ไข ข้อมูล ใช่หรือไม่!!")) return true;
                        else return false;
                    }
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
                            centerGridForm(dlgDiv);
                        }

                        var totalRows = $(grid_selector).jqGrid('getGridParam', 'selarrrow');
                        var totalRowsCount = totalRows.length;
                        $("td.delmsg", form).html("คุณต้องการลบข้อมูลที่ถูกเลือก <b>ทั้งหมด " + totalRowsCount + " รายการ</b>" + " ใช่หรือไม่?");
                    },
                    onClick : function(e) {
                        alert(1);
                    },
                    delData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            alert("ดำเนินการสำเร็จ");
                            return [true,""];
                        }else{
                            return [false,response.responseText];
                        }
                    }
                },
                {
                    //search form
                    recreateForm: true,
                    afterShowSearch: function(e){
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />');
                        style_search_form(form);

                        var dlgDiv = $("#searchmodfbox_" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    afterRedraw: function(){
                        style_search_filters($(this));
                    }
                    ,
                    multipleSearch: true,
                    sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le', 'bw', 'bn', 'ew', 'en', 'cn', 'nc'],
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                    /**
                     multipleGroup:true,
                     showQuery: true
                     */
                },
                {
                    //view record form
                    recreateForm: true,
                    beforeShowForm: function(e){
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />');

                        var dlgDiv = $("#viewmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);

                        var receivetype = $('#receivetype').val();

                        if(receivetype == 0){
                            $('#tr_dealername').hide();
                        }
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                }
            )
        })
    </script>
@endsection