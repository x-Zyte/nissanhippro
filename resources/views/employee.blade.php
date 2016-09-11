@extends('app')
@section('title','พนักงาน')
@section('menu-employee-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-male"></i> พนักงาน</h3>

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

            var candeletedata = false;
            if('{{Auth::user()->isadmin}}' == '1' || '{{Auth::user()->candeletedata}}' == '1'){
                candeletedata = true;
            }

            $(grid_selector).jqGrid({
                url:'employee/read',
                datatype: "json",
                colNames:['รหัส','คำนำหน้า', 'ชื่อจริง', 'นามสกุล','วันที่เริ่มการเป็นพนักงาน','วันที่สิ้นสุดการเป็นพนักงาน',
                    'ชื่อเข้าใช้ระบบ','วันที่เริ่มให้เข้าใช้ระบบ','วันที่สิ้นสุดให้เข้าใช้ระบบ', 'อีเมล์', 'โทรศัพท์', 'เป็นผู้ดูแล',
                    'สาขา', 'แผนก','ทีม', 'สามารถลบข้อมูลได้', 'เปิดใช้งาน','บันทึกเพิ่มเติม'],
                colModel:[
                    /*{name:'myac',index:'', width:80, fixed:true, sortable:false, resize:false,
                        formatter:'actions',
                        formatoptions:{
                            keys:true,
                            delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback}
                            //editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
                        }
                    },*/
                    //{hidden: true},
                    {name:'code',index:'code', width:70,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'title',index:'title', width:50, editable: true,edittype:"select",formatter:'select',editoptions:{value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว" },align:'left'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว" }},
                    {name:'firstname',index:'firstname', width:100,editable: true,editoptions:{size:"25",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'lastname',index:'lastname', width:100,editable: true,editoptions:{size:"25",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'workingstartdate',index:'workingstartdate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center',editrules:{required:true,edithidden:true}
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']},hidden: true},
                    {name:'workingenddate',index:'workingenddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                        ,editrules:{custom: true, custom_func: check_workingenddate,edithidden:true},hidden: true},
                    {name:'username',index:'username', width:100,editable: true,editoptions:{size:"25",maxlength:"50"},align:'left'},
                    {name:'loginstartdate',index:'loginstartdate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']},editrules:{edithidden:true},hidden: true},
                    {name:'loginenddate',index:'loginenddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']},editrules:{edithidden:true},hidden: true},
                    {name:'email',index:'email', width:120,editable: true,editoptions:{size:"25",maxlength:"50"},editrules:{edithidden:true},align:'left',hidden: true},
                    {name:'phone',index:'phone', width:100,editable: true,editoptions:{size:"15",maxlength:"20"},editrules:{edithidden:true},align:'left',hidden: true},
                    {name:'isadmin',index:'isadmin', width:60, editable: true,edittype:"checkbox",formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }
                        ,editoptions: {value:"1:0",
                            dataEvents :[{type: 'change', fn: function(e){
                                var checked = $(e.target).is(':checked');
                                if(checked){
                                    $('#tr_branchid').hide();
                                    $('#tr_departmentid').hide();
                                    $('#tr_teamid').hide();
                                    $('#tr_candeletedata').hide();

                                    $('#branchid').val(null);
                                    $('#departmentid').val(null);
                                    $('#teamid').val(null);
                                    $('#candeletedata').prop("checked", false);
                                }
                                else{
                                    $('#tr_branchid').show();
                                    $('#tr_departmentid').show();
                                    $('#tr_candeletedata').show();
                                }
                            }}]
                        }
                    },
                    {name:'branchid',index:'branchid', width:240, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$branchselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$branchselectlist}}" }
                        ,editrules:{custom: true, custom_func: check_branch}},
                    {name:'departmentid',index:'departmentid', width:200, editable: true,edittype:"select",formatter:'select'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$departmentselectlist}}" }
                        ,editrules:{custom: true, custom_func: check_department}
                        ,editoptions:{value: "{{$departmentselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                if(thisval == 6){
                                    $('#tr_teamid').show();
                                }
                                else{
                                    $('#tr_teamid').hide();
                                    $('#teamid').val(null);
                                }
                            }}]
                        }
                    },
                    {name:'teamid',index:'teamid', width:70, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$teamselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$teamselectlist}}" },align:'center'},
                    {name:'candeletedata',index:'candeletedata', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'active',index:'active', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"1"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'remarks',index:'remarks', width:150,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"35"},align:'left'}
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

                editurl: "employee/update",//nothing is saved
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
                    });
                    //resize on sidebar collapse/expand
                    var parent_column = $("#"+subgrid_table_id).closest('[class*="col-"]');
                    $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                        if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                            $("#"+subgrid_table_id).jqGrid( 'setGridWidth', parent_column.width() );
                        }
                    });

                    $("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
                    jQuery("#"+subgrid_table_id).jqGrid({
                        url:'employeepermission/read?employeeid='+row_id,
                        datatype: "json",
                        colNames:['เมนูที่สามารถเข้าถึง'],
                        colModel:[
                            {name:'menu',index:'menu', width:150, editable: true,edittype:"select",formatter:'select',
                                editoptions: {value: "รับรถเข้าสต๊อก:รับรถเข้าสต๊อก;ลูกค้ามุ่งหวัง:ลูกค้ามุ่งหวัง;การจอง:การจอง;ยกเลิกการจอง:ยกเลิกการจอง;การชำระเงิน:การชำระเงิน;รายละเอียดเพื่อการบันทึกบัญชี:รายละเอียดเพื่อการบันทึกบัญชี;ป้ายแดง:ป้ายแดง;คืนเงินมัดจำป้ายแดง:คืนเงินมัดจำป้ายแดง;พนักงาน:พนักงาน;รายงาน:รายงาน;การตั้งค่าทั่วไป:การตั้งค่าทั่วไป;การตั้งค่ารถ:การตั้งค่ารถ;การตั้งค่าการขาย:การตั้งค่าการขาย"}
                                ,align:'left',stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"]
                                ,
                                value: "รับรถเข้าสต๊อก:รับรถเข้าสต๊อก;ลูกค้ามุ่งหวัง:ลูกค้ามุ่งหวัง;การจอง:การจอง;ยกเลิกการจอง:ยกเลิกการจอง;การชำระเงิน:การชำระเงิน;รายละเอียดเพื่อการบันทึกบัญชี:รายละเอียดเพื่อการบันทึกบัญชี;ป้ายแดง:ป้ายแดง;คืนเงินมัดจำป้ายแดง:คืนเงินมัดจำป้ายแดง;พนักงาน:พนักงาน;รายงาน:รายงาน;การตั้งค่าทั่วไป:การตั้งค่าทั่วไป;การตั้งค่ารถ:การตั้งค่ารถ;การตั้งค่าการขาย:การตั้งค่าการขาย"
                            }
                            }
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

                        editurl: "employeepermission/update",
                        caption: "สิทธิ์การเข้าถึงเมนู",
                        height:'100%'
                        //width:desired_width
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    employeeid: row_id
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
                                            .wrapInner('<div class="widget-header" />');
                                    style_edit_form(form);

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    employeeid: row_id
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
                                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />');
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
                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />');

                                    var dlgDiv = $("#viewmod" + jQuery("#"+subgrid_table_id)[0].id);
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

            function check_branch(value, colname) {
                var checked = $('#isadmin').is(':checked');
                if(checked) return [true, ""];
                else{
                    if(value != '' && value != null) return [true, ""];
                    else return [false,"กรุณาเลือกสาขา"];
                }
            }

            function check_department(value, colname) {
                var checked = $('#isadmin').is(':checked');
                if(checked) return [true, ""];
                else{
                    if(value != '' && value != null) return [true, ""];
                    else return [false,"กรุณาเลือกแผนก"];
                }
            }

            function check_workingenddate(value, colname) {
                if(value == null || value == '') return [true, ""];

                var workingstartdate = $('#workingstartdate').val();

                var workingstartdateArr = workingstartdate.split("-");
                var workingenddateArr = value.split("-");

                var newworkingstartdate = new Date(workingstartdateArr[1]+'-'+workingstartdateArr[0]+'-'+workingstartdateArr[2]);
                var newworkingenddate = new Date(workingenddateArr[1]+'-'+workingenddateArr[0]+'-'+workingenddateArr[2]);

                if(newworkingstartdate.getTime() < newworkingenddate.getTime()){
                    return [true, ""];
                }
                else{
                    return [false,"วันที่เริ่มการเป็นพนักงาน ต้องน้อยกว่า วันที่สิ้นสุดการเป็นพนักงาน"];
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
                    view: false,
                    viewicon : 'ace-icon fa fa-search-plus grey'
                },
                {
                    //edit record form
                    width: 600,
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                        style_edit_form(form);

                        var checked = $('#isadmin').is(':checked');
                        if(checked){
                            $('#tr_branchid').hide();
                            $('#tr_departmentid').hide();
                            $('#tr_teamid').hide();
                            $('#tr_candeletedata').hide();
                        }
                        else{
                            var departmentid = $('#departmentid').val();
                            if(departmentid != 6){
                                $('#tr_teamid').hide();
                            }
                        }

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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                .wrapInner('<div class="widget-header" />');
                        style_edit_form(form);

                        $('#tr_teamid').hide();

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