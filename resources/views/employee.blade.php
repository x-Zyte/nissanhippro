@extends('app')

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
            })
            //resize on sidebar collapse/expand
            var parent_column = $(grid_selector).closest('[class*="col-"]');
            $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
                if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }
            })

            $(grid_selector).jqGrid({
                url:'employee/read',
                datatype: "json",
                colNames:['รหัส','คำนำหน้า', 'ชื่อจริง', 'นามสกุล','วันที่เริ่มการเป็นพนักงาน','วันที่สิ้นสุดการเป็นพนักงาน', 'ชื่อเข้าใช้ระบบ','วันที่เริ่มให้เข้าใช้ระบบ','วันที่สิ้นสุดให้เข้าใช้ระบบ', 'อีเมล์', 'โทรศัพท์', 'เป็นผู้ดูแล', 'สาขา', 'แผนก','ทีม', 'เปิดใช้งาน','บันทึกเพิ่มเติม'],
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
                    {name:'code',index:'code', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'title',index:'title', width:60, editable: true,edittype:"select",formatter:'select',editoptions:{value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว"},align:'left'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว" }},
                    {name:'firstname',index:'firstname', width:100,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'lastname',index:'lastname', width:100,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'workingstartdate',index:'workingstartdate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'workingenddate',index:'workingenddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'username',index:'username', width:100,editable: true,editoptions:{size:"30",maxlength:"50"},align:'left'},
                    {name:'loginstartdate',index:'loginstartdate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'loginenddate',index:'loginenddate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'email',index:'email', width:120,editable: true,editoptions:{size:"30",maxlength:"50"},align:'left'},
                    {name:'phone',index:'phone', width:100,editable: true,editoptions:{size:"20",maxlength:"20"},editrules:{},align:'left'},
                    {name:'isadmin',index:'isadmin', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'branchid',index:'branchid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$branchselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$branchselectlist}}" }},
                    {name:'departmentid',index:'departmentid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$departmentselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$departmentselectlist}}" }},
                    {name:'teamid',index:'teamid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$teamselectlist}}"},formoptions:{elmsuffix:'(เป็นผู้ดูแลไม่ต้องเลือก)'}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$teamselectlist}}" }},
                    {name:'active',index:'active', width:60, editable: true,edittype:"checkbox",editoptions: {value:"1:0", defaultValue:"1"},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }},
                    {name:'remarks',index:'remarks', width:300,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"40"},align:'left'}
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
                        url:'employeepermission/read?employeeid='+row_id,
                        datatype: "json",
                        colNames:['เมนูที่สามารถเข้าถึง'],
                        colModel:[
                            {name:'menu',index:'menu', width:150, editable: true,edittype:"select",formatter:'select',
                                editoptions:{value: "รถ:รถ;ลูกค้า:ลูกค้า;พนักงาน:พนักงาน;การตั้งค่าส่วนกลาง:การตั้งค่าส่วนกลาง;การตั้งค่ารถ:การตั้งค่ารถ"}
                                ,align:'left',stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "รถ:รถ;ลูกค้า:ลูกค้า;พนักงาน:พนักงาน;การตั้งค่าส่วนกลาง:การตั้งค่าส่วนกลาง;การตั้งค่ารถ:การตั้งค่ารถ" }}
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
                                del: true,
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
                                    employeeid: row_id
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
                                    employeeid: row_id
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
                                //delete record form
                                recreateForm: true,
                                beforeShowForm : function(e) {
                                    var form = $(e[0]);
                                    if(form.data('styled')) return false;

                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                    style_delete_form(form);

                                    form.data('styled', true);

                                    var dlgDiv = $("#delmod" + jQuery("#"+subgrid_table_id)[0].id);
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
                    del: true,
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
                            alert("ดำเนินการสำเร็จ")
                            return [true,""];
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

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
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