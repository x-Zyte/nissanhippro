@extends('app')
@section('title','การยกเลิกการจอง')
@section('menu-cancelcarpreemption-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-ban"></i> การยกเลิกการจอง</h3>

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
                url: "cancelcarpreemption/read",
                datatype: "json",
                colNames: ['การจอง เล่มที่/เลขที่', 'ยกเลิกเนื่องจาก', 'คืนจำนวน', 'ไม่คืนจำนวน', 'ผู้อนุมัติ', 'วันที่อนุมัติ'],
                colModel:[
                    {name:'carpreemptionid',index:'carpreemptionid', width:100, formatter:'select',editoptions:{value: "{{$carpreemptionselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carpreemptionselectlist}}"}
                    },
                    {name:'cancelreasontype',index:'cancelreasontype', width:100, editable: true,edittype:"select",formatter:'select',align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "0:สัญญาไม่ผ่าน;1:ไม่มีรถ;2:อื่นๆ" },
                        editoptions:{value: "0:สัญญาไม่ผ่าน;1:ไม่มีรถ;2:อื่นๆ"}
                    },
                    //{name:'amount',index:'amount', width:100,align:'right',formatter:'number'
                        //,formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}
                    //},
                    {
                        name: 'refundamount', index: 'refundamount', width: 100, align: 'right', formatter: 'number'
                        , formatoptions: {decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {
                        name: 'confiscateamount',
                        index: 'confiscateamount',
                        width: 100,
                        align: 'right',
                        formatter: 'number'
                        ,formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {name:'approversemployeeid',index:'approversemployeeid', width:150, formatter:'select',editoptions:{value: "{{$approversemployeeselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$approversemployeeselectlist}}"}
                    },
                    {name:'approversemployeedate',index:'approversemployeedate', width:100,align:'left', sorttype:"date", formatter: "date",align:'center'
                        ,formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                    }
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

                editurl: "cancelcarpreemption/update",
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            //navButtons
            jQuery(grid_selector).jqGrid('navGrid',pager_selector,
                    { 	//navbar options
                        edit: false,
                        editicon : 'ace-icon fa fa-pencil blue',
                        add: false,
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
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
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
            ).jqGrid('navButtonAdd',pager_selector,
                    {caption: '',
                        buttonicon:'ace-icon fa fa-search-plus grey',
                        onClickButton: function(){
                            var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                            if(selRowId)
                                window.open("{{URL::to('cancelcarpreemption/view')}}"+'/'+selRowId,'_blank');
                            else
                                alert("กรุณาเลือกข้อมูล");
                        },
                        position:"first",
                        title:"ดูรายละเอียดข้อมูล"
                    }
            ).jqGrid('navButtonAdd',pager_selector,
                    {caption: '',
                        buttonicon:'ace-icon fa fa-pencil blue',
                        onClickButton: function(){
                            var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                            if(selRowId)
                                window.open("{{URL::to('cancelcarpreemption/edit')}}"+'/'+selRowId,'_blank');
                            else
                                alert("กรุณาเลือกข้อมูล");
                        },
                        position:"first",
                        title:"แก้ไขข้อมูล"
                    }
            ).jqGrid('navButtonAdd',pager_selector,
                    {caption: '',
                        buttonicon:'ace-icon fa fa-plus-circle purple',
                        onClickButton: function(){
                            window.open("{{URL::to('cancelcarpreemption/newcancelcarpreemption')}}",'_blank');
                        },
                        position:"first",
                        title:"เพิ่มข้อมูล"
                    }
            )

        })
    </script>
@endsection