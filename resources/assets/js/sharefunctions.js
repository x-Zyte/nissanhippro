function booleanFormatter( cellvalue, options, cell ) {
    if (cellvalue == '1') {
        return 'Yes';
    }else if(cellvalue == '0') {
        return 'No';
    }
}

function imageLinkFormatter (cellvalue, options, rowObjcet) {
    //return '<img src="'+cellvalue+'" alt="" height="42" width="42">';
    if(cellvalue != null) {
        var url = window.location.origin + cellvalue;
        return '<a href = "' + url + '" data-lightbox="' + cellvalue + '">View photo</a>';
    }
    else
        return '';
}

//switch element when editing inline
function aceSwitch( cellvalue, options, cell ) {
    setTimeout(function(){
        $(cell) .find('input[type=checkbox]')
            .addClass('ace ace-switch ace-switch-5')
            .after('<span class="lbl"></span>');
    }, 0);

    if (cellvalue == 'Yes') {
        return '1';
    }else if(cellvalue == 'No') {
        return '0';
    }
}
//enable datepicker
function pickDate( cellvalue, options, cell ) {
    setTimeout(function(){
        $(cell) .find('input[type=text]')
            .datepicker({format:'yyyy-mm-dd' , autoclose:true});
    }, 0);
}

function check_AZ09(value, colname) {
    var re = new RegExp("^[A-Z0-9]+$");
    return [re.test(value), value + " ต้องเป็นภาษาอังกฤษตัวพิมพ์ใหญ่ และตัวเลขเท่านั้น"];
}

function style_edit_form(form) {
    //enable datepicker on "sdate" field and switches for "stock" field
    //form.find('input[name=isadmin],input[name=active],input[type=checkbox]')
    form.find('input[type=checkbox]')
        .addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
    //don't wrap inside a label element, the checkbox value won't be submitted (POST'ed)
    //.addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

    //update buttons classes
    var buttons = form.next().find('.EditButton .fm-button');
    buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
    buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
    buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>');

    buttons = form.next().find('.navButton a');
    buttons.find('.ui-icon').hide();
    buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
    buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');

    form.css("max-height",($(window).height() - 250)+"px");

    $('input',form).keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) {
            $("#sData", form.next()).trigger("click");
            return false;
        }
    });
}

function style_delete_form(form) {
    var buttons = form.next().find('.EditButton .fm-button');
    buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
    buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
    buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>');

    form.css("max-height",($(window).height() - 250)+"px");
}

function style_search_filters(form) {
    form.find('.delete-rule').val('X');
    form.find('.add-rule').addClass('btn btn-xs btn-primary');
    form.find('.add-group').addClass('btn btn-xs btn-success');
    form.find('.delete-group').addClass('btn btn-xs btn-danger');

    form.css("max-height",($(window).height() - 250)+"px");
}
function style_search_form(form) {
    var dialog = form.closest('.ui-jqdialog');
    var buttons = dialog.find('.EditTable')
    buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
    buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
    buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');

    form.css("max-height",($(window).height() - 250)+"px");
}

function beforeDeleteCallback(e) {
    var form = $(e[0]);
    if(form.data('styled')) return false;

    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
    style_delete_form(form);

    form.data('styled', true);
}

function beforeEditCallback(e) {
    var form = $(e[0]);
    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
    style_edit_form(form);
}

//it causes some flicker when reloading or navigating grid
//it may be possible to have some custom formatter to do this as the grid is being created to prevent this
//or go back to default browser checkbox styles for the grid
function styleCheckbox(table) {
    /**
     $(table).find('input:checkbox').addClass('ace')
     .wrap('<label />')
     .after('<span class="lbl align-top" />')


     $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
     .find('input.cbox[type=checkbox]').addClass('ace')
     .wrap('<label />').after('<span class="lbl align-top" />');
     */
}

//unlike navButtons icons, action icons in rows seem to be hard-coded
//you can change them like this in here if you want
function updateActionIcons(table) {
    /**
     var replacement =
     {
         'ui-ace-icon fa fa-pencil' : 'ace-icon fa fa-pencil blue',
         'ui-ace-icon fa fa-trash-o' : 'ace-icon fa fa-trash-o red',
         'ui-icon-disk' : 'ace-icon fa fa-check green',
         'ui-icon-cancel' : 'ace-icon fa fa-times red'
     };
     $(table).find('.ui-pg-div span.ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
     */
}

//replace icons with FontAwesome icons like above
function updatePagerIcons(table) {
    var replacement =
    {
        'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
        'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
        'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
        'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
    };
    $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
        var icon = $(this);
        var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

        if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
    })
}

function enableTooltips(table) {
    $('.navtable .ui-pg-button').tooltip({container:'body'});
    $(table).find('.ui-pg-div').tooltip({container:'body'});
}

function centerGridForm(dlgDiv) {
    //var parentDiv = dlgDiv.parent(); // div#gbox_list
    //var dlgWidth = dlgDiv.width();
    //var parentWidth = parentDiv.width();
    ////var dlgHeight = dlgDiv.height();
    ////var parentHeight = parentDiv.height();
    ////var parentTop = parentDiv.offset().top;
    //var parentLeft = parentDiv.offset().left;
    ////dlgDiv[0].style.top =  Math.round(  (parentTop+160)  + (parentHeight-dlgHeight)/2  ) + "px";
    //dlgDiv[0].style.left = Math.round(  parentLeft + (parentWidth-dlgWidth  )/2 )  + "px";
    dlgDiv[0].style.top = (($(window).height() - dlgDiv.height())/2) + "px";
    dlgDiv[0].style.left = (($(window).width() - dlgDiv.width())/2) + "px";
}

function resizeGrid(){
    if($('#grid-table').width() < $(".page-content").width()){
        $('#grid-table').jqGrid( 'setGridWidth', $(".page-content").width());
        $('.ui-jqgrid' + " .ui-jqgrid-pager" + " #grid-pager_left").css("padding-right",($(".page-content").width()*0.275)+"px");
    }
    else{
        $('.ui-jqgrid' + ' .ui-jqgrid-view').css('overflow','auto');
        $('.ui-jqgrid' + " .ui-jqgrid-view").css("max-width",($(".page-content").width())+"px");
        $('.ui-jqgrid' + " .ui-jqgrid-pager").css("max-width",($(".page-content").width())+"px");
    }
}

function resizeSubGrid(subgrid_table_id){
    if($("#"+subgrid_table_id).width() < 600){
        $("#"+subgrid_table_id).jqGrid( 'setGridWidth', 600);
        $('.ui-subgrid' + ' .ui-jqgrid-view').css('overflow-y','auto');
        $('.ui-subgrid' + ' .ui-jqgrid-view').css('overflow-x','hidden');
    }
    else if($("#"+subgrid_table_id).width() > ($('#grid-table').width() - 55)){
        $('.ui-subgrid' + ' .ui-jqgrid-view').css('overflow','auto');
        $('.ui-subgrid' + " .ui-jqgrid-view").css("max-width",($('#grid-table').width() - 55)+"px");
        $('.ui-subgrid' + " .ui-jqgrid-pager").css("max-width",($('#grid-table').width() - 55)+"px");
    }
    else{
        $('.ui-subgrid' + ' .ui-jqgrid-view').css('overflow-y','auto');
        $('.ui-subgrid' + ' .ui-jqgrid-view').css('overflow-x','hidden');
    }
}