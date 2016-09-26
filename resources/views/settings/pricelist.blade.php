@extends('app')
@section('title','รายการราคา')
@section('menu-settings-class','active hsub open')
@section('menu-settingselling-class','active hsub open')
@section('menu-subsettingselling-class','nav-show')
@section('menu-subsettingselling-style','display: block;')
@section('menu-settingpricelist-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> รายการราคา</h3>

    <div class="panel-body" style="padding: 0px;">
        {!! Form::open(array('url' => 'pricelist/import', 'files' => true, 'id'=>'form-import')) !!}
        <div id="import" class="form-group col-xs-12">
            <div class="col-xs-3">
                <input type="file" name="pricelist" id="input-file-pricelist">
            </div>
            {!! Form::submit('Import') !!}
        </div>
        {!! Form::close() !!}
    </div>

    <table id="grid-table"></table>

    <div id="grid-pager"></div>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        $('#modal').hide();

        $('#input-file-pricelist').ace_file_input({
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
            if ($('#input-file-pricelist').get(0).files.length === 0) {
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

            $(grid_selector).jqGrid({
                url:"pricelist/read",
                datatype: "json",
                colNames: ['แบบรถ', 'รุ่นรถ', 'ตั้งแต่วันที่', 'ถึงวันที่', 'ราคาขายพร้อมอุปกรณ์ตกแต่ง', 'ราคาขายผู้จำหน่าย', 'ราคาอุปกรณ์ตกแต่ง', 'MARGIN',
                    'WS 50%', 'DMS', 'wholesale', 'ส่งเสริมการขาย/Internal', 'คูปองน้ำมัน/Campaign', 'Total Margin Campaign',
                    'ส่งเสริมการขาย/Internal','คูปอง../Extra Campaign','Total Margin + Campaign','โปรโมชั่น'],
                colModel:[
                    {name:'carmodelid',index:'carmodelid', width:250, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$carmodelselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('carsubmodel/readSelectlist/'+thisval, function(data){
                                    $('#carsubmodelid').children('option:not(:first)').remove();
                                    $.each(data, function(i, option) {
                                        $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        }
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carmodelselectlist}}" }
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$carsubmodelselectlist}}"},align:'left'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carsubmodelselectlist}}" }},
                    {name:'effectivefrom',index:'effectivefrom',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, editrules:{required:true}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'effectiveto',index:'effectiveto',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {
                        name: 'sellingpricewithaccessories',
                        index: 'sellingpricewithaccessories',
                        width: 100,
                        editable: true,
                        editrules: {number: true},
                        align: 'right',
                        formatter: 'number',
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {name:'sellingprice',index:'sellingprice', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'accessoriesprice',index:'accessoriesprice', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'margin',index:'margin', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {
                        name: 'ws50', index: 'ws50', width: 100, editable: true,
                        editrules: {required: true, number: true}, align: 'right', formatter: 'number',
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {
                        name: 'dms', index: 'dms', width: 100, editable: true,
                        editrules: {required: true, number: true}, align: 'right', formatter: 'number',
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {
                        name: 'wholesale',
                        index: 'wholesale',
                        width: 100,
                        editable: true,
                        editrules: {required: true, number: true},
                        align: 'right',
                        formatter: 'number',
                        formoptions: {label: 'ส่งเสริมการขาย/Internal (Execusive)'},
                        formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {name:'execusiveinternal',index:'execusiveinternal', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'ส่งเสริมการขาย/Internal (Execusive)'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusivecampaing',index:'execusivecampaing', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'คูปองน้ำมัน/Campaign (Execusive)'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusivetotalmargincampaing',index:'execusivetotalmargincampaing', width:100,editable: true,
                        editrules:{number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'internal',index:'internal', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'ส่งเสริมการขาย/Internal'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'campaing',index:'campaing', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'คูปอง../Extra Campaign'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'totalmargincampaing',index:'totalmargincampaing', width:100,editable: true,
                        editrules:{number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'promotion',index:'promotion', width:150,editable: true,editoptions:{size:"30",maxlength:"50"},align:'left'}
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

                editurl: "pricelist/update",
                caption: "",
                height:'100%'
            });

            $(grid_selector).jqGrid('setGroupHeaders', {
                useColSpanStyle: true,
                groupHeaders:[
                    {startColumnName: 'margin', numberOfColumns: 3, titleText: 'ผลประโยชน์รวม'},
                    {startColumnName: 'wholesale', numberOfColumns: 4, titleText: 'NLTH Execusive'},
                    {startColumnName: 'internal', numberOfColumns: 3, titleText: 'กรณีเงินสด/ดอกเบี้ยปกติ'}
                ]
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

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
                    view: false,
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

                        $.get('carsubmodel/readSelectlist/'+carmodelid, function(data){
                            $('#carsubmodelid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#carsubmodelid').val(carsubmodelid);
                        });

                        $('#tr_sellingpricewithaccessories', form).hide();
                        $('#tr_execusivetotalmargincampaing', form).hide();
                        $('#tr_totalmargincampaing', form).hide();

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
                            $.get('pricelist/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                            });
                            alert("ดำเนินการสำเร็จ");
                            return [true,""];
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                        style_edit_form(form);

                        $('#carsubmodelid').children('option:not(:first)').remove();

                        $('#tr_sellingpricewithaccessories', form).hide();
                        $('#tr_execusivetotalmargincampaing', form).hide();
                        $('#tr_totalmargincampaing', form).hide();

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
                            $.get('pricelist/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                            });
                            alert("ดำเนินการสำเร็จ");
                            return [true,""];
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
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    }
                }
            )
        })
    </script>
@endsection