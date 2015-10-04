@extends('app')

@section('menu-car-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-car"></i> รถ</h3>

    <table id="grid-table"></table>

    <div id="grid-pager"></div>

    <script type="text/javascript">
        var $path_base = "..";//this will be used for editurl parameter
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
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

            var defaultProvince = '';
            var hiddenProvince = false;
            if('{{Auth::user()->isadmin}}' == '0'){
                defaultProvince = '{{$defaultProvince}}';
                hiddenProvince = true;
            }

            $(grid_selector).jqGrid({
                url:'car/read',
                datatype: "json",
                colNames:['จังหวัด','แบบ','รุ่น','ซื้อจาก','ชื่อดีลเลอร์อื่น','คันที่', 'วันที่ออก Do', 'วันที่รับรถเข้า', 'เลขเครื่อง', 'เลขตัวถัง', 'กุญแจ', 'สี', 'รถสำหรับ','ใบรับรถเข้า', 'ใบส่งรถให้ลูกค้า'],
                colModel:[
                    {name:'provinceid',index:'provinceid', width:150, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$provinceselectlist}}", defaultValue:defaultProvince},hidden:hiddenProvince
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$provinceselectlist}}" }},
                    {name:'carmodelid',index:'carmodelid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
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
                    {name:'receivetype',index:'receivetype', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "0:NMT;1:ดีลเลอร์อื่น"},align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:NMT;1:ดีลเลอร์อื่น" }},
                    {name:'dealername',index:'dealername', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},align:'left'},
                    {name:'no',index:'no', width:50,editable: true,editoptions:{size:"5"},align:'center'},
                    {name:'dodate',index:'dodate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'receiveddate',index:'receiveddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'engineno',index:'engineno', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'chassisno',index:'chassisno', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'keyno',index:'keyno', width:50,editable: true,editoptions:{size:"5"},editrules:{number:true},align:'center'},
                    {name:'colorid',index:'colorid', width:180, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$colorselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$colorselectlist}}" }},
                    {name:'objective',index:'objective', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "0:ขาย;1:ใช้งาน;2:ทดสอบ"},align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:ขาย;1:ใช้งาน;2:ทดสอบ" }},
                    /*{name:'issold',index:'issold', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                     ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'isregistered',index:'isregistered', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                     ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'isdelivered',index:'isdelivered', width:100, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                     ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},*/
                    {name:'receivecarfilepath',index:'receivecarfilepath',width:100,editable: true,edittype:'file',editoptions:{enctype:"multipart/form-data"},formatter:imageLinkFormatter,search:false,align:'center'},
                    {name:'deliverycarfilepath',index:'deliverycarfilepath',width:100,editable: true,edittype:'file',editoptions:{enctype:"multipart/form-data"},formatter:imageLinkFormatter,search:false,align:'center'}
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

            function uploadfiles(){
                var receivecarfilepath = $("#receivecarfilepath");
                var deliverycarfilepath = $("#deliverycarfilepath");
                if(receivecarfilepath.val() != '' || deliverycarfilepath.val() != ''){
                    var data = new FormData();
                    data.append('_token','{{ csrf_token() }}');
                    data.append('engineno',$('#engineno').val());
                    if(receivecarfilepath.val() != ''){
                        data.append('receivecarfile', receivecarfilepath.prop('files')[0]);
                    }
                    if(deliverycarfilepath.val() != ''){
                        data.append('deliverycarfile', deliverycarfilepath.prop('files')[0]);
                    }

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
                    del: true,
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var carmodelid = $('#carmodelid').val();
                        var carsubmodelid = $('#carsubmodelid').val();
                        var colorid = $('#colorid').val();

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

                        $('#tr_receivetype', form).hide();
                        $('#tr_no', form).hide();
                        $('#tr_keyno', form).hide();

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            $.get('car/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                                $(grid_selector).setColProp('colorid', { editoptions: { value: data.colorselectlist } });
                            });
                            return uploadfiles();
                        }else{
                            return [false,response.responseText];
                        }
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
                                .wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        $('#carsubmodelid').children('option:not(:first)').remove();
                        $('#colorid').children('option:not(:first)').remove();

                        $('#tr_no', form).hide();
                        $('#tr_keyno', form).hide();

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            $.get('car/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
                                $(grid_selector).setColProp('colorid', { editoptions: { value: data.colorselectlist } });
                            });
                            return uploadfiles();
                        }else{
                            return [false,response.responseText];
                        }
                    }
                },
                {
                    //delete record form
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        if(form.data('styled')) return false;

                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_delete_form(form);

                        form.data('styled', true);

                        var dlgDiv = $("#delmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
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
                            alert("ดำเนินการสำเร็จ")
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')

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