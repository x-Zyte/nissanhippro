@extends('app')
@section('title','แบบรถ/สี/รุ่น/ทะเบียน')
@section('menu-settings-class','active hsub open')
@section('menu-settingcar-class','active hsub open')
@section('menu-subsettingcar-class','nav-show')
@section('menu-subsettingcar-style','display: block;')
@section('menu-settingcarmodel-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-car"></i> แบบรถ/สี/รุ่น/ทะเบียน</h3>

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
            })
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            })

            var candeletedata = false;
            if('{{Auth::user()->isadmin}}' == '1' || '{{Auth::user()->candeletedata}}' == '1'){
                candeletedata = true;
            }

            var defaultCarBrand = '{{$defaultCarBrand}}';

            $(grid_selector).jqGrid({
                url:'{{ url('/carmodel/read') }}',
                datatype: "json",
                colNames:['ประเภทรถ', 'ยี่ห้อ', 'ชื่อแบบ','ค่าทะเบียน(บุคคล)','ค่าดำเนินการ(บุคคล)','ค่าทะเบียน(บริษัท)','ค่าดำเนินการ(บริษัท)', 'รายละเอียด'],
                colModel:[
                    {name:'cartypeid',index:'cartypeid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$cartypeselectlist}}"},editrules:{required:true},align:'left',
                        stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$cartypeselectlist}}" }},
                    {name:'carbrandid',index:'carbrandid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carbrandselectlist}}", defaultValue:defaultCarBrand},editrules:{required:true},align:'left',
                        stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carbrandselectlist}}" }},
                    {name:'name',index:'name', width:250,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'individualregistercost',index:'individualregistercost', width:100,editable: true,editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'implementingindividualregistercost',index:'implementingindividualregistercost', width:100,editable: true,editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'companyregistercost',index:'companyregistercost', width:100,editable: true,editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'implementingcompanyregistercost',index:'implementingcompanyregistercost', width:100,editable: true,editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'detail',index:'detail', width:200,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"40"},editrules:{},align:'left'}
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

                editurl: "carmodel/update",
                caption: "",
                height:'100%',
                subGrid: true,
                subGridOptions : {
                    plusicon : "ace-icon fa fa-plus center bigger-110 blue",
                    minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
                    openicon : "ace-icon fa fa-chevron-right center orange"
                },
                subGridRowExpanded: function(subgrid_id, row_id) {
                    var subgrid_table_id, pager_id;
                    subgrid_table_id = subgrid_id+"_t";
                    pager_id = "p_"+subgrid_table_id;

                    //resize to fit page size
                    $(window).on('resize.jqGridSubGrid', function () {
                        resizeSubGrid(subgrid_table_id);
                    })
                    //resize on sidebar collapse/expand
                    var parent_column = $("#"+subgrid_table_id).closest('[class*="col-"]');
                    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                        if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                            $("#"+subgrid_table_id).jqGrid( 'setGridWidth', parent_column.width() );
                        }
                    })

                    $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
                    jQuery("#"+subgrid_table_id).jqGrid({
                        url:'carsubmodel/read?carmodelid='+row_id,
                        datatype: "json",
                        colNames:['รหัส', 'ชื่อรุ่น','ชื่อรุ่น(ออกใบกำกับภาษี)', 'รายละเอียด'],
                        colModel:[
                            {name:'code',index:'code', width:200,editable: true,editoptions:{size:"30",maxlength:"50"}
                                ,editrules:{required:true,custom: true, custom_func: check_AZ09HyphenSquarebracket},align:'left'},
                            {name:'name',index:'name', width:250,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                            {name:'taxinvoicename',index:'taxinvoicename', width:150,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                            {name:'detail',index:'detail', width:200,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"40"},editrules:{},align:'left'}
                        ],
                        viewrecords : true,
                        rowNum:10,
                        rowList:[10,20,30],
                        pager : pager_id,
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

                        editurl: "carsubmodel/update",
                        caption: "รุ่นของแบบรถ",
                        height:'100%'
                    });

                    $(window).triggerHandler('resize.jqGridSubGrid');

                    jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    carmodelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
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
                                    jQuery("#"+subgrid_table_id).jqGrid('resetSelection');
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                            .wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    carmodelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
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
                                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                        style_delete_form(form);

                                        form.data('styled', true);

                                        var dlgDiv = $("#delmod" + jQuery("#" + subgrid_table_id)[0].id);
                                        centerGridForm(dlgDiv);
                                    }

                                    var totalRows = $("#"+subgrid_table_id).jqGrid('getGridParam', 'selarrrow');
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                    style_search_form(form);

                                    var dlgDiv = $("#searchmodfbox_" + jQuery("#"+subgrid_table_id)[0].id);
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

                                    var dlgDiv = $("#viewmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}"
                                }
                            }
                    )

                    var subgrid_table_id2, pager_id2;
                    subgrid_table_id2 = subgrid_id+"_tt";
                    pager_id2 = "p_"+subgrid_table_id2;

                    $(window).on('resize.jqGridSubGrid2', function () {
                        resizeSubGrid(subgrid_table_id2);
                    })

                    var parent_column2 = $("#"+subgrid_table_id2).closest('[class*="col-"]');
                    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                        if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                            $("#"+subgrid_table_id2).jqGrid( 'setGridWidth', parent_column2.width() );
                        }
                    })

                    $("#"+subgrid_id).append("<table id='"+subgrid_table_id2+"' class='scroll'></table><div id='"+pager_id2+"' class='scroll'></div>");
                    jQuery("#"+subgrid_table_id2).jqGrid({
                        url:'carmodelcolor/read?carmodelid='+row_id,
                        datatype: "json",
                        colNames:['สี'],
                        colModel:[
                            {name:'colorid',index:'colorid', width:600, editable: true,edittype:"select",formatter:'select',editoptions:{value:'{{$colorselectlist}}'},align:'left'
                                ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$colorselectlist}}" }
                                ,editrules:{required:true,custom: true, custom_func: check_color}}
                        ],
                        viewrecords : true,
                        rowNum:10,
                        rowList:[10,20,30],
                        pager : pager_id2,
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

                        editurl: "carmodelcolor/update",
                        caption: "สีของแบบรถ",
                        height:'100%'
                    });

                    $(window).triggerHandler('resize.jqGridSubGrid2');

                    function check_color(value, colname) {
                        var selRowId = $("#"+subgrid_table_id2).jqGrid ('getGridParam', 'selrow');
                        var carmodelid = row_id;
                        if(selRowId == null) selRowId = 0;
                        $.ajax({
                            url: 'carmodelcolor/check_color',
                            data: { id:selRowId,carmodelid:carmodelid,colorid:value, _token: "{{ csrf_token() }}" },
                            type: 'POST',
                            async: false,
                            datatype: 'text',
                            success: function (data) {
                                if (!data) result = [true, ""];
                                else result = [false,"แบบรถนี้ รหัสสี "+data+" มีอยู่แล้ว"];
                            }
                        })
                        return result;
                    }

                    jQuery("#"+subgrid_table_id2).jqGrid('navGrid',"#"+pager_id2,
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
                                beforeShowForm : function(e) {
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id2)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    carmodelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
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
                                    jQuery("#"+subgrid_table_id2).jqGrid('resetSelection');
                                    var form = $(e[0]);
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                            .wrapInner('<div class="widget-header" />')
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id2)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    carmodelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
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
                                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                        style_delete_form(form);

                                        form.data('styled', true);

                                        var dlgDiv = $("#delmod" + jQuery("#" + subgrid_table_id2)[0].id);
                                        centerGridForm(dlgDiv);
                                    }

                                    var totalRows = $("#"+subgrid_table_id2).jqGrid('getGridParam', 'selarrrow');
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                    style_search_form(form);

                                    var dlgDiv = $("#searchmodfbox_" + jQuery("#"+subgrid_table_id2)[0].id);
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

                                    var dlgDiv = $("#viewmod" + jQuery("#"+subgrid_table_id2)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}"
                                }
                            }
                    )
                }
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
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

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
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
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