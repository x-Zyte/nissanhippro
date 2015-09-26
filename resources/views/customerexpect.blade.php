@extends('app')

@section('menu-customer-class','active hsub open')
@section('menu-customerexpect-class','active')

@section('content')

    <h3 class="header smaller lighter blue"><i class="ace-icon fa fa-users"></i> ลูกค้ามุ่งหวัง</h3>

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

            var defaultProvince = '';
            var hiddenProvince = false;
            if('{{Auth::user()->isadmin}}' == '0'){
                defaultProvince = '{{$defaultProvince}}';
                hiddenProvince = true;
            }

            $(grid_selector).jqGrid({
                url:'{{ url('/customerexpect/read') }}',
                datatype: "json",
                colNames:['จังหวัด', 'คำนำหน้า', 'ชื่อจริง', 'นามสกุล', 'เบอร์โทร1', 'เบอร์โทร2', 'อาชีพ', 'วันเกิด', 'ที่อยู่', 'จังหวัด', 'เขต/อำเภอ', 'แขวง/ตำบล', 'รหัสไปรษณีย์'],
                colModel:[
                    {name:'provinceid',index:'provinceid', width:100, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value: "{{$provinceselectlist}}", defaultValue:defaultProvince},hidden:hiddenProvince},
                    {name:'title',index:'title', width:70, editable: true,edittype:"select",formatter:'select',editoptions:{value: "นาย:นาย;นาง:นาง;นางสาว:นางสาว"},align:'left'},
                    {name:'firstname',index:'firstname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},editrules:{required:true},align:'left'},
                    {name:'lastname',index:'lastname', width:100,editable: true,editoptions:{size:"20",maxlength:"50"},align:'left'},
                    {name:'phone1',index:'phone1', width:100,editable: true,editrules:{required:true},editoptions:{size:"20",maxlength:"20"},align:'left'},
                    {name:'phone2',index:'phone2', width:100,editable: true,editoptions:{size:"20",maxlength:"20"},align:'left'},
                    {name:'occupationid',index:'occupationid', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "{{$occupationselectlist}}"}},
                    {name:'birthdate',index:'birthdate',width:100, editable:true, sorttype:"date", formatter: "date", unformat: pickDate, editoptions:{dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, align:'center'},
                    {name:'address',index:'address', width:150,editable: true,editoptions:{size:"50",maxlength:"200"},align:'left'},
                    {name:'addprovinceid',index:'addprovinceid', width:100, editable: true,edittype:"select",formatter:'select',align:'left',
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
                        }
                    },
                    {name:'amphurid',index:'amphurid', width:100, editable: true,edittype:"select",formatter:'select',align:'left',
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
                        }
                    },
                    {name:'districtid',index:'districtid', width:100, editable: true,edittype:"select",formatter:'select',align:'left',
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
                        }
                    },
                    {name:'zipcode',index:'zipcode', width:100,editable: true,editoptions:{size:"5",maxlength:"5"},align:'left'}
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

                editurl: "customerexpect/update",
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
                        url:'customerexpectation/read?customerid='+row_id,
                        datatype: "json",
                        colNames:['พนักงานที่ติดตาม','วันที่','แบบที่สนใจ1','แบบที่สนใจ2','แบบที่สนใจ3', 'สีที่สนใจ1', 'สีที่สนใจ2', 'สีที่สนใจ3',
                            'แนวโน้มการซื้อ','สิ่งที่ต้องการจากรถใหม่','ยี่ห้ออื่นที่พิจารณา','ข้อกำหนดในรถเก่า','งบประมาณ/เดือน','เงื่อนไขที่เสนอไป',
                            'เงื่อนไขไฟแนนซ์: ดาวน์','เงื่อนไขไฟแนนซ์: ดอกเบี้ย(%)','เงื่อนไขไฟแนนซ์: จำนวนงวด','นัดหมายครั้งถัดไป','หมายเหตุ'],
                        colModel:[
                            {name:'employeeid',index:'employeeid', width:150, editable: true,edittype:"select",formatter:'select',editrules:{required:true},editoptions:{value:"{{$employeeselectlist}}"},align:'left'},
                            {name:'date',index:'date',width:100, editable:true, sorttype:"date", formatter: "date", unformat: pickDate,
                                editoptions:{dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}}, editrules:{required:true}, align:'center'},
                            {name:'carmodelid1',index:'carmodelid1', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'carmodelid2',index:'carmodelid2', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'carmodelid3',index:'carmodelid3', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'colorid1',index:'colorid1', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'colorid2',index:'colorid2', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'colorid3',index:'colorid3', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value:"{{$carmodelselectlist}}"},align:'left'},
                            {name:'buyingtrends',index:'buyingtrends', width:100, editable: true,edittype:"select",formatter:'select',editoptions:{value: "0:A-HOT(7 วัน);1:B-HOT(15 วัน);2:C-HOT(30 วัน);3:เกิน 1 เดือน"},align:'left'},
                            {name:'newcarthingsrequired',index:'newcarthingsrequired', width:100,editable: true,editoptions:{size:"30",maxlength:"100"},align:'left',hidden: true,editrules:{edithidden:true}},
                            {name:'otherconsideration',index:'otherconsideration', width:100,editable: true,editoptions:{size:"30",maxlength:"100"},align:'left',hidden: true,editrules:{edithidden:true}},
                            {name:'oldcarspecifications',index:'oldcarspecifications', width:100,editable: true,editoptions:{size:"30",maxlength:"100"},align:'left',hidden: true,editrules:{edithidden:true}},
                            {name:'budgetpermonth',index:'budgetpermonth', width:100,editable: true,editrules:{number:true},align:'right',formatter:'number',editoptions:{size:"10"},
                                formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, defaultValue: '0.00'},hidden: true,editrules:{edithidden:true}},
                            {name:'conditionproposed',index:'conditionproposed', width:300,editable: true,edittype:'textarea',editoptions:{rows:"2",cols:"40"},align:'left',hidden: true,editrules:{edithidden:true}},
                            {name:'conditionfinancedown',index:'conditionfinancedown', width:100,editable: true,editrules:{number:true},align:'right',formatter:'number',editoptions:{size:"10"},
                                formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, defaultValue: '0.00'},hidden: true,editrules:{edithidden:true}},
                            {name:'conditionfinanceinterest',index:'conditionfinanceinterest', width:100,editable: true,editrules:{number:true},align:'right',formatter:'number',editoptions:{size:"10"},
                                formatoptions:{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2, defaultValue: '0.00'},hidden: true,editrules:{edithidden:true}},
                            {name:'conditionfinanceperiod',index:'conditionfinanceperiod', width:50,editable: true,editoptions:{size:"2",maxlength:"2"},
                                editrules:{number:true},align:'center',hidden: true,editrules:{edithidden:true}},
                            {name:'nextappointmentdate',index:'nextappointmentdate',width:100, editable:true, sorttype:"date", formatter: "date", unformat: pickDate,
                                editoptions:{dataInit:function(elem){$(elem).datepicker({format:'dd-mm-yyyy', autoclose:true});}},align:'center',hidden: true,editrules:{edithidden:true}},
                            {name:'remarks',index:'remarks', width:100,editable: true,editoptions:{size:"30",maxlength:"100"},align:'left',hidden: true,editrules:{edithidden:true}}
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

                        editurl: "customerexpectation/update",
                        caption: "ความคาดหวัง",
                        height:'100%'
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
                                width: 500,
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
                                    customerid: row_id
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
                                width: 500,
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
                                    customerid: row_id
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
                    view: true,
                    viewicon : 'ace-icon fa fa-search-plus grey'
                },
                {
                    //edit record form
                    closeAfterEdit: true,
                    width: 500,
                    recreateForm: true,
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
                    width: 500,
                    closeAfterAdd: true,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm : function(e) {
                        jQuery(grid_selector).jqGrid('resetSelection');
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                .wrapInner('<div class="widget-header" />')
                        style_edit_form(form);

                        $('#amphurid').children('option:not(:first)').remove();
                        $('#districtid').children('option:not(:first)').remove();

                        var dlgDiv = $("#editmod" + jQuery(grid_selector)[0].id);
                        centerGridForm(dlgDiv);
                    },
                    editData: {
                        _token: "{{ csrf_token() }}"
                    },
                    afterSubmit : function(response, postdata)
                    {
                        if(response.responseText == "ok"){
                            $.get('customerexpect/readSelectlistForDisplayInGrid', function(data){
                                $(grid_selector).setColProp('amphurid', { editoptions: { value: data.amphurselectlist } });
                                $(grid_selector).setColProp('districtid', { editoptions: { value: data.districtselectlist } });
                            });
                            alert("ดำเนินการสำเร็จ");
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