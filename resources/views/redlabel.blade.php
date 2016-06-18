@extends('app')
@section('title','ป้ายแดง')
@section('menu-redlabel-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-ticket"></i> ป้ายแดง</h3>

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

            var defaultProvince = '';
            var hiddenProvince = false;
            if('{{Auth::user()->isadmin}}' == '0'){
                defaultProvince = '{{$defaultProvince}}';
                hiddenProvince = true;
            }

            $(grid_selector).jqGrid({
                url:'{{ url('redlabel/read') }}',
                datatype: "json",
                colNames:['จังหวัด', 'เลขทะเบียน', 'ชื่อลูกค้า', 'รถที่ใช้อยู่ เลขตัวถัง/เลขเครื่อง', 'เงินมัดจำ'],
                colModel:[
                    {name:'provinceid',index:'provinceid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$provinceselectlist}}", defaultValue:defaultProvince},hidden:hiddenProvince
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$provinceselectlist}}" }},
                    {name:'no',index:'no', width:100,editable: true,editoptions:{size:"30",maxlength:"20"},editrules:{required:true},align:'left'},
                    {name:'customerid',index:'customerid', width:150, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$customerselectlist}}"},align:'left',
                        stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$customerselectlist}}" }},
                    {name:'carid',index:'carid', width:200, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carselectlist}}"},align:'left',
                        stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carselectlist}}" }},
                    {name:'deposit',index:'deposit', width:100,editable: true,editrules:{number:true},align:'right'
                        ,formatter:'number',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}}
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

                editurl: "redlabel/update",
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

                    $.get('redlabel/checkbusy/'+row_id, function(data){
                        if(data == 1){
                            $("#add_" + subgrid_table_id).css("display", "none");
                        }
                        else{
                            $("#add_" + subgrid_table_id).css("display", "table-cell");
                        }
                    });

                    $.get('redlabelhistory/readCarPreemptionSelectlistForDisplayInGrid/'+row_id, function(data){
                        $("#"+subgrid_table_id).setColProp('carpreemptionid', { editoptions: { value: data.carpreemptionselectlist } });
                    });

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
                        url:'redlabelhistory/read?redlabelid='+row_id,
                        datatype: "json",
                        colNames:['วันที่เบิก','ใบจอง/ลูกค้า/เซล','วันที่คืน','หมายเหตุ'],
                        colModel:[
                            {name:'issuedate',index:'issuedate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                                ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                                ,searchrules:{required:true}
                                ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                                ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                                ,editrules:{custom: true, custom_func: check_issuedate}},
                            {name:'carpreemptionid',index:'carpreemptionid', width:500, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$carpreemptionselectlist}}"}
                                ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carpreemptionselectlist}}" }
                                ,editrules:{required:true}},
                            {name:'returndate',index:'returndate',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                                ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                                ,searchrules:{required:true}
                                ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                                ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                                ,editrules:{custom: true, custom_func: check_returndate}},
                            {name:'remarks',index:'remarks', width:150,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"35"},align:'left'}
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

                        editurl: "redlabelhistory/update",
                        caption: "การเบิก/การคืน",
                        height:'100%'
                        //width:desired_width
                    });

                    $(window).triggerHandler('resize.jqGridSubGrid');

                    function check_issuedate(value, colname) {
                        if(value == null || value == '') return [true, ""];

                        var issuedateArr = value.split("-");

                        var newissuedate = new Date(issuedateArr[1]+'-'+issuedateArr[0]+'-'+issuedateArr[2]);
                        var today = new Date();

                        if(newissuedate.getTime() <= today.getTime()){
                            return [true, ""];
                        }
                        else{
                            return [false,"วันที่เบิก ต้องน้อยกว่าหรือเท่ากับ วันที่ปัจจุบัน"];
                        }
                    }

                    function check_returndate(value, colname) {
                        if(value == null || value == '') return [true, ""];

                        var returndateArr = value.split("-");
                        var newreturndate = new Date(returndateArr[1]+'-'+returndateArr[0]+'-'+returndateArr[2]);
                        var today = new Date();

                        if(newreturndate.getTime() > today.getTime()){
                            return [false,"วันที่คืน ต้องน้อยกว่าหรือเท่ากับ วันที่ปัจจุบัน"];
                        }

                        var issuedate = $('#issuedate').val();
                        var issuedateArr = issuedate.split("-");
                        var newissuedate = new Date(issuedateArr[1]+'-'+issuedateArr[0]+'-'+issuedateArr[2]);

                        if(newissuedate.getTime() > newreturndate.getTime()){
                            return [false,"วันที่เบิก ต้องน้อยกว่า หรือเท่ากับ วันที่คืน"];
                        }

                        return [true, ""];
                    }

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

                                    var carpreemptionid = $('#carpreemptionid').val();

                                    $.get('redlabelhistory/readCarPreemptionSelectlist/'+carpreemptionid, function(data){
                                        $('#carpreemptionid').children('option:not(:first)').remove();
                                        $.each(data, function(i, option) {
                                            $('#carpreemptionid').append($('<option/>').attr("value", option.id).text(option.text));
                                        });
                                        $('#carpreemptionid').val(carpreemptionid);
                                    });

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    redlabelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
                                        $.get('redlabel/readSelectlistForDisplayInGrid', function(data){
                                            $(grid_selector).setColProp('customerid', { editoptions: { value: data.customerselectlist } });
                                        });
                                        $(grid_selector).trigger('reloadGrid',[{current:true}]);
                                        setTimeout(function(){
                                            $(grid_selector).expandSubGridRow(row_id);
                                        }, 1000);
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

                                    $('#tr_returndate').hide();

                                    $.get('redlabelhistory/readCarPreemptionSelectlist/0', function(data){
                                        $('#carpreemptionid').children('option:not(:first)').remove();
                                        $.each(data, function(i, option) {
                                            $('#carpreemptionid').append($('<option/>').attr("value", option.id).text(option.text));
                                        });
                                    });

                                    var dlgDiv = $("#editmod" + jQuery("#"+subgrid_table_id)[0].id);
                                    centerGridForm(dlgDiv);
                                },
                                editData: {
                                    _token: "{{ csrf_token() }}",
                                    redlabelid: row_id
                                },
                                afterSubmit : function(response, postdata)
                                {
                                    if(response.responseText == "ok"){
                                        showConfirmClose = false;
                                        $.get('redlabel/readSelectlistForDisplayInGrid', function(data){
                                            $(grid_selector).setColProp('customerid', { editoptions: { value: data.customerselectlist } });
                                        });
                                        $(grid_selector).trigger('reloadGrid',[{current:true}]);
                                        setTimeout(function(){
                                            $(grid_selector).expandSubGridRow(row_id);
                                        }, 1000);
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
                                        $(grid_selector).trigger('reloadGrid',[{current:true}]);
                                        setTimeout(function(){
                                            $(grid_selector).expandSubGridRow(row_id);
                                        }, 1000);
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
                    viewPagerButtons : false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);

                        $('#tr_customerid', form).hide();
                        $('#tr_carid', form).hide();
                        $('#tr_deposit', form).hide();
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

                        $('#tr_customerid', form).hide();
                        $('#tr_carid', form).hide();
                        $('#tr_deposit', form).hide();
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