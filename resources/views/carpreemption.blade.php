@extends('app')
@section('title','ใบจอง')
@section('menu-carpreemption-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-file-text-o"></i> ใบจอง</h3>

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

            $(grid_selector).jqGrid({
                url: "carpreemption/read",
                datatype: "json",
                colNames:['เล่มที่', 'เลขที่', 'วันที่', 'ชื่อผู้จอง', 'แบบ', 'รุ่น', 'สี', 'ราคา', 'ส่วนลด', 'ชื่อผู้ซื้อ', 'พนักงานขาย', 'ผู้จัดการฝ่ายขาย', 'ผู้อนุมัติ', 'วันที่อนุมัติ'],
                colModel:[
                    {name:'bookno',index:'bookno', width:100,align:'left'},
                    {name:'no',index:'no', width:100,align:'left'},
                    {name:'date',index:'date', width:100,align:'left', sorttype:"date", formatter: "date",align:'center'
                        ,formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                    },
                    {name:'bookingcustomerid',index:'bookingcustomerid', width:150, formatter:'select',editoptions:{value: "{{$bookingcustomerselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$bookingcustomerselectlist}}"}
                    },
                    {name:'carmodelid',index:'carmodelid', width:100, formatter:'select',editoptions:{value: "{{$carmodelselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carmodelselectlist}}"}
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:100, formatter:'select',editoptions:{value: "{{$carsubmodelselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carsubmodelselectlist}}"}
                    },
                    {name:'colorid',index:'colorid', width:100, formatter:'select',editoptions:{value: "{{$colorselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$colorselectlist}}"}
                    },
                    {name:'price',index:'price', width:100,align:'right',formatter:'number'
                        ,formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {name:'discount',index:'discount', width:100,align:'right',formatter:'number'
                        ,formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}
                    },
                    {name:'buyercustomerid',index:'buyercustomerid', width:150, formatter:'select',editoptions:{value: "{{$buyercustomerselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$buyercustomerselectlist}}"}
                    },
                    {name:'salesmanemployeeid',index:'salesmanemployeeid', width:150, formatter:'select',editoptions:{value: "{{$salesmanemployeeselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$salesmanemployeeselectlist}}"}
                    },
                    {name:'salesmanageremployeeid',index:'salesmanageremployeeid', width:150, formatter:'select',editoptions:{value: "{{$salesmanageremployeeselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$salesmanageremployeeselectlist}}"}
                    },
                    {name:'approversemployeeid',index:'approversemployeeid', width:150, formatter:'select',editoptions:{value: "{{$approversemployeeselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$approversemployeeselectlist}}"}
                    },
                    {name:'approvaldate',index:'approvaldate', width:100,align:'left', sorttype:"date", formatter: "date",align:'center'
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

                editurl: "carpreemption/update",
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
                    }
            ).jqGrid('navButtonAdd',pager_selector,
                    {caption: '',
                        buttonicon:'ace-icon fa fa-search-plus grey',
                        onClickButton: function(){
                            var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                            if(selRowId)
                                window.open("{{URL::to('carpreemption/view')}}"+'/'+selRowId,'_blank');
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
                                window.open("{{URL::to('carpreemption/edit')}}"+'/'+selRowId,'_blank');
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
                            window.open("{{URL::to('carpreemption/newcarpreemption')}}",'_blank');
                        },
                        position:"first",
                        title:"เพิ่มข้อมูล"
                    }
            )
        })
    </script>
@endsection