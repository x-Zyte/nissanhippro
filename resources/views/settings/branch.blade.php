@extends('app')
@section('title','สาขาโชว์รูม')
@section('menu-settings-class','active hsub open')
@section('menu-settingcore-class','active hsub open')
@section('menu-subsettingcore-class','nav-show')
@section('menu-subsettingcore-style','display: block;')
@section('menu-settingbranch-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-share-alt"></i> สาขาโชว์รูม</h3>

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
                url:'{{ url('/branch/read') }}',
                datatype: "json",
                colNames:['ชื่อสาขา','ชื่อสำหรับออกใบกำกับภาษี','เลขประจำตัวผู้เสียภาษี', 'ที่อยู่', 'แขวง/ตำบล', 'เขต/อำเภอ', 'จังหวัด', 'รหัสไปรษณีย์','สำนักงานใหญ่','ช่องกุญแจ'],
                colModel:[
                    {name:'name',index:'name', width:150,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'taxinvoicename',index:'taxinvoicename', width:150,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'taxpayerno',index:'taxpayerno', width:100,editable: true,editoptions:{size:"30",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'address',index:'address', width:200,editable: true,editoptions:{size:"50",maxlength:"200"},editrules:{required:true},align:'left'},
                    {name:'districtid',index:'districtid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$districtselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('zipcode/read/'+thisval, function(data){
                                    $('#zipcode').val(data.code);
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    //$.each(data, function(i, option) {
                                    //$('#zipcodeid').append($('<option/>').attr("value", option.id).text(option.code));
                                    //});
                                });
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$districtselectlist}}" }
                        ,formoptions:{rowpos:7}
                    },
                    {name:'amphurid',index:'amphurid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$amphurselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('district/read/'+thisval, function(data){
                                    $('#districtid').children('option:not(:first)').remove();
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    $('#zipcode').val('');
                                    $.each(data, function(i, option) {
                                        $('#districtid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$amphurselectlist}}" }
                        ,formoptions:{rowpos:6}
                    },
                    {name:'provinceid',index:'provinceid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$provinceselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('amphur/read/'+thisval, function(data){
                                    $('#amphurid').children('option:not(:first)').remove();
                                    $('#districtid').children('option:not(:first)').remove();
                                    //$('#zipcodeid').children('option:not(:first)').remove();
                                    $('#zipcode').val('');
                                    $.each(data, function(i, option) {
                                        $('#amphurid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$provinceselectlist}}" }
                        ,formoptions:{rowpos:5}
                    },
                    {name:'zipcode',index:'zipcode', width:100,editable: true,editoptions:{size:"5",maxlength:"5"},editrules:{required:true, number:true},align:'left'},
                    {name:'isheadquarter',index:'isheadquarter', width:80, editable: true,edittype:"checkbox",
                        editrules:{custom: true, custom_func: check_headquarter},formatter: booleanFormatter,unformat: aceSwitch,align:'center'
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value: "1:Yes;0:No" }
                        ,editoptions: {value:"1:0",
                            dataEvents :[{type: 'change', fn: function(e){
                                var checked = $(e.target).is(':checked');
                                if(!checked){
                                    $('#tr_keyslot').hide();
                                    $('#keyslot').val(0);
                                }
                                else{
                                    $('#tr_keyslot').show();
                                }
                            }}]
                        }
                    },
                    {name:'keyslot',index:'keyslot', width:50,editable: true,editoptions:{size:"3"},
                        editrules:{number:true,custom: true, custom_func: check_keyslot},align:'center'}
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

                editurl: "branch/update",
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            function check_headquarter(value, colname) {
                if(value == 0) return [true, ""];
                var selRowId = $(grid_selector).jqGrid ('getGridParam', 'selrow');
                var provinceid = $('#provinceid').val();
                if(selRowId == null) selRowId = 0;
                $.ajax({
                    url: 'branch/check_headquarter',
                    data: { id:selRowId,provinceid:provinceid, _token: "{{ csrf_token() }}" },
                    type: 'POST',
                    async: false,
                    datatype: 'text',
                    success: function (data) {
                        if (!data) result = [true, ""];
                        else result = [false,"จังหวัดนี้มีสาขาสำนักงานใหญ่แล้ว"];
                    }
                })
                return result;
            }

            function check_keyslot(value, colname) {

                var isheadquarterchecked = $('#isheadquarter').is(':checked');
                if(isheadquarterchecked) {
                    var provinceid = $('#provinceid').val();
                    var isheadquarter = $('#isheadquarter').val();
                    $.ajax({
                        url: 'branch/check_keyslot',
                        data: {provinceid: provinceid, keyslot: value, _token: "{{ csrf_token() }}"},
                        type: 'POST',
                        async: false,
                        datatype: 'text',
                        success: function (data) {
                            if (!data) result = [true, ""];
                            else result = [false, "จำนวนช่องกุญแจ ต้องไม่น้อยกว่าหมายเลขกุญแจที่มากที่สุด ที่รถใน stock ใช้งานอยู่"];
                        }
                    })
                }
                else result = [true, ""];

                return result;
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
                    closeAfterEdit: true,
                    width: 600,
                    recreateForm: true,
                    viewPagerButtons : false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var provinceid = $('#provinceid').val();
                        var amphurid = $('#amphurid').val();
                        var districtid = $('#districtid').val();

                        $.get('amphur/read/'+provinceid, function(data){
                            $('#amphurid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#amphurid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#amphurid').val(amphurid);
                        });

                        $.get('district/read/'+amphurid, function(data){
                            $('#districtid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#districtid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#districtid').val(districtid);
                        });

                        var checked = $('#isheadquarter').is(':checked');
                        if(!checked){
                            $('#tr_keyslot').hide();
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
                            $.get('branch/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('amphurid', { editoptions: { value: data.amphurselectlist } });
                                $(grid_selector).setColProp('districtid', { editoptions: { value: data.districtselectlist } });
                            });
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        $('#amphurid').children('option:not(:first)').remove();
                        $('#districtid').children('option:not(:first)').remove();

                        $('#tr_keyslot').hide();
                        $('#keyslot').val(0);

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            $.get('branch/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('amphurid', { editoptions: { value: data.amphurselectlist } });
                                $(grid_selector).setColProp('districtid', { editoptions: { value: data.districtselectlist } });
                            });
                            alert("ดำเนินการสำเร็จ")
                            return [true,""];
                        }else{
                            return [false,response.responseText];
                        }
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