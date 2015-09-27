@extends('app')

@section('menu-settings-class','active hsub open')
@section('menu-settingcar-class','active hsub open')
@section('menu-subsettingcar-class','nav-show')
@section('menu-subsettingcar-style','display: block;')
@section('menu-settingpricelist-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-btc"></i> รายการราคา</h3>

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
                url:"pricelist/read",
                datatype: "json",
                colNames:['แบบรถ', 'รุ่นรถ','ตั้งแต่วันที่','ถึงวันที่', 'ราคาขาย MSRP', 'ราคาอุปกรณ์ตกแต่ง', 'ราคาขายพร้อมอุปกรณ์ตกแต่ง', 'MARGIN',
                    'ส่งเสริมการขาย/Internal', 'คูปองน้ำมัน/Campaign', 'Total Campaign', 'Total Margin Campaign',
                    'ส่งเสริมการขาย/Internal','คูปอง../Extra Campaign','Total Margin + Campaign','โปรโมชั่น'],
                colModel:[
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
                        }
                    },
                    {name:'carsubmodelid',index:'carsubmodelid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$carsubmodelselectlist}}"}},
                    {name:'effectivefrom',index:'effectivefrom',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'},
                    {name:'effectiveto',index:'effectiveto',width:100, editable:true, sorttype:"date", formatter: "date", formatoptions: { srcformat:'Y-m-d', newformat:'d-m-Y' }, editoptions:{size:"10",dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'},
                    {name:'sellingprice',index:'sellingprice', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'accessoriesprice',index:'accessoriesprice', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'sellingpricewithaccessories',index:'sellingpricewithaccessories', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'margin',index:'margin', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusiveinternal',index:'execusiveinternal', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'ส่งเสริมการขาย/Internal (Execusive)'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusivecampaing',index:'execusivecampaing', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'คูปองน้ำมัน/Campaign (Execusive)'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusivetotalcampaing',index:'execusivetotalcampaing', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'execusivetotalmargincampaing',index:'execusivetotalmargincampaing', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'internal',index:'internal', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'ส่งเสริมการขาย/Internal'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'campaing',index:'campaing', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',formoptions:{label:'คูปอง../Extra Campaign'},
                        formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2}},
                    {name:'totalmargincampaing',index:'totalmargincampaing', width:100,editable: true,editoptions:{defaultValue:'0.00'},
                        editrules:{required:true, number:true},align:'right',formatter:'number',
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
                    {startColumnName: 'execusiveinternal', numberOfColumns: 4, titleText: 'NLTH Execusive'},
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
                        $('#tr_execusivetotalcampaing', form).hide();
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

                        $('#carsubmodelid').children('option:not(:first)').remove();

                        $('#tr_sellingpricewithaccessories', form).hide();
                        $('#tr_execusivetotalcampaing', form).hide();
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
                            $.get('pricelist/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('carsubmodelid', { editoptions: { value: data.carsubmodelselectlist } });
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