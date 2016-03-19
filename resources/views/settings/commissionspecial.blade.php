@extends('app')
@section('title','คอมมิชชั่น Special')
@section('menu-settings-class','active hsub open')
@section('menu-settingselling-class','active hsub open')
@section('menu-subsettingselling-class','nav-show')
@section('menu-subsettingselling-style','display: block;')
@section('menu-settingcommissionspecial-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> คอมมิชชั่น Special</h3>

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
                url:'{{ url('commissionspecial/read') }}',
                datatype: "json",
                colNames:['ไฟแนนซ์','ประเภทอัตราดอกเบี้ย', 'ชื่อ','วันที่เริ่ม', 'วันที่สิ้นสุด', 'แบบรถ','รุ่นรถ', 'งวด ตั้งแต่', 'งวด ถึง' ,'%ดาวน์ ตั้งแต่','%ดาวน์ ถึง', 'จำนวนเงิน'],
                colModel:[
                    {name:'finacecompanyid',index:'finacecompanyid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},align:'left',
                        editoptions:{value: "{{$finacecompanyselectlist}}",
                            dataEvents :[{type: 'change', fn: function(e){
                                var thisval = $(e.target).val();
                                $.get('interestratetype/readSelectlist/'+thisval, function(data){
                                    $('#interestratetypeid').children('option:not(:first)').remove();
                                    $.each(data, function(i, option) {
                                        $('#interestratetypeid').append($('<option/>').attr("value", option.id).text(option.name));
                                    });
                                });
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$finacecompanyselectlist}}" }
                    },
                    {name:'interestratetypeid',index:'interestratetypeid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$interestratetypeselectlist}}"},editrules:{required:true},align:'left',
                        stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$interestratetypeselectlist}}" }},
                    {name:'name',index:'name', width:150,editable: true,editoptions:{size:"40",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'effectivefrom',index:'effectivefrom',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center',editrules:{required:true}
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}},
                    {name:'effectiveto',index:'effectiveto',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }
                        ,editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}}, align:'center'
                        ,searchrules:{required:true}
                        ,searchoptions: { size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true,todayHighlight: true});}
                        ,sopt: ['eq', 'ne', 'lt', 'gt', 'ge', 'le']}
                        ,editrules:{required:true, custom: true, custom_func: check_effectiveto}},
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
                            }}]
                        },stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carmodelselectlist}}" }
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:100, editable: true,edittype:"select",formatter:'select'
                        ,editrules:{required:true}
                        ,editoptions:{value: "{{$carsubmodelselectlist}}"}
                        ,stype:'select',searchrules:{required:true},searchoptions: { sopt: ["eq", "ne"], value:"{{$carsubmodelselectlist}}" }},
                    {name:'frominstallment',index:'frominstallment', width:70,editable: true,editrules:{required:true, number:true},align:'center'
                        ,formatter:'number',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
                    {name:'toinstallment',index:'toinstallment', width:70,editable: true,editrules:{required:true, number:true},align:'center'
                        ,formatter:'number',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0}},
                    {name:'fromdownrate',index:'fromdownrate', width:70,editable: true,editrules:{required:true, number:true},align:'center'
                        ,formatter:'number',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'todownrate',index:'todownrate', width:70,editable: true,editrules:{required:true, number:true},align:'center'
                        ,formatter:'number',formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'amount',index:'amount', width:100,editable: true,
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}}
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

                editurl: "commissionspecial/update",
                caption: "",
                height:'100%'
            });

            $(window).triggerHandler('resize.jqGrid');//trigger window resize to make the grid get the correct size

            function check_effectiveto(value, colname) {
                if(value == null || value == '') return [true, ""];

                var effectivefrom = $('#effectivefrom').val();

                var effectivefromArr = effectivefrom.split("-");
                var effectivetoArr = value.split("-");

                var neweffectivefrom = new Date(effectivefromArr[1]+'-'+effectivefromArr[0]+'-'+effectivefromArr[2]);
                var neweffectiveto = new Date(effectivetoArr[1]+'-'+effectivetoArr[0]+'-'+effectivetoArr[2]);

                if(neweffectivefrom.getTime() < neweffectiveto.getTime()){
                    return [true, ""];
                }
                else{
                    return [false,"วันที่เริ่ม ต้องน้อยกว่า วันที่สิ้นสุด"];
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
                    closeAfterEdit: true,
                    width: 600,
                    recreateForm: true,
                    viewPagerButtons : false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        var finacecompanyid = $('#finacecompanyid').val();
                        var interestratetypeid = $('#interestratetypeid').val();

                        $.get('interestratetype/readSelectlist/'+finacecompanyid, function(data){
                            $('#interestratetypeid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#interestratetypeid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#interestratetypeid').val(interestratetypeid);
                        });

                        var carmodelid = $('#carmodelid').val();
                        var carsubmodelid = $('#carsubmodelid').val();

                        $.get('carsubmodel/readSelectlist/'+carmodelid, function(data){
                            $('#carsubmodelid').children('option:not(:first)').remove();
                            $.each(data, function(i, option) {
                                $('#carsubmodelid').append($('<option/>').attr("value", option.id).text(option.name));
                            });
                            $('#carsubmodelid').val(carsubmodelid);
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
                            $.get('commissionspecial/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('interestratetypeid', { editoptions: { value: data.interestratetypeselectlist } });
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
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        $('#interestratetypeid').children('option:not(:first)').remove();
                        $('#carsubmodelid').children('option:not(:first)').remove();

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
                            $.get('commissionspecial/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('interestratetypeid', { editoptions: { value: data.interestratetypeselectlist } });
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