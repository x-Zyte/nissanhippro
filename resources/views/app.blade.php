<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>@yield('title', 'Nissan Hippro Power')</title>

    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="resources/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="resources/assets/font-awesome/4.0.3/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="resources/assets/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="resources/assets/css/datepicker.css" />
    <link rel="stylesheet" href="resources/assets/css/ui.jqgrid.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="resources/assets/fonts/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="resources/assets/css/ace.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="resources/assets/css/ace-part2.min.css" />
    <![endif]-->
    <link rel="stylesheet" href="resources/assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="resources/assets/css/ace-rtl.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="resources/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="resources/assets/js/ace-extra.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="resources/assets/js/html5shiv.js"></script>
    <script src="resources/assets/js/respond.min.js"></script>
    <![endif]-->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="resources/assets/js/jquery.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="resources/assets/js/jquery-1.11.0.min.js"></script>
    <![endif]-->

    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='resources/assets/js/jquery.min.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='resources/assets/js/jquery1x.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='resources/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>

    <link rel="stylesheet" href="resources/assets/css/lightbox.css">
    <link rel="shortcut icon" href="resources/assets/img/favicon.ico">
</head>
<body class="skin-2">

@if ($employeeMenus = Auth::user()->employeePermissions()->lists('menu')) @endif

<div id="modal">
<img src="resources/assets/img/ajax-loader.gif" id="loading-indicator"/>
</div>
<style type="text/css">
    #loading-indicator {
        position: absolute;
        left: 50%;
        top: 50%;
    }
    #modal {
        z-index: 9999;
        display: none;
        position:absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index: 9999;
        -moz-opacity: 0.8;
        opacity: 0.8;
        filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
    }
</style>
<script type="text/javascript">
    $("body").css("overflow-x", "hidden");

    $(document).ajaxStart(function(event, request, settings) {
        $('#modal').show();
    });
    $(document).ajaxComplete(function(event, request, settings) {
        $('#modal').hide();
    });
</script>

<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>

    <div class="navbar-container" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="{{ url('/') }}" class="navbar-brand">
                <small>
                    <i class="fa fa-car"></i>
                    Nissan Hippro Power
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="javascript:void(0)" class="dropdown-toggle">
								<span class="user-info">
									<small>ยินดีต้อนรับ,</small>
                                    <b>{{ 'คุณ '.Auth::user()->firstname.' '.Auth::user()->lastname }}</b>
								</span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="{{ url('/auth/logout') }}">
                                <i class="ace-icon fa fa-power-off"></i>
                                ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <div id="sidebar" class="sidebar                  responsive">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">

        </div><!-- /.sidebar-shortcuts -->

        <ul class="nav nav-list">
            <li class="">
                <a href="javascript:void(0)">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-stock-class')"  style="display: none">
                <a href="javascript:void(0)">
                    <i class="menu-icon fa fa-archive"></i>
                    <span class="menu-text"> การคลัง </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-selling-class')"  style="display: none">
                <a href="javascript:void(0)">
                    <i class="menu-icon fa fa-barcode"></i>
                    <span class="menu-text"> การขาย </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-registration-class')"  style="display: none">
                <a href="javascript:void(0)">
                    <i class="menu-icon fa fa-pencil-square-o"></i>
                    <span class="menu-text"> การเงินและบัญชี </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-car-class')" @if(!Auth::user()->isadmin && !in_array("รถ", $employeeMenus)) style="display: none" @endif>
                <a href="{{ url('/car') }}">
                    <i class="menu-icon fa fa-car"></i>
                    <span class="menu-text"> รถ </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-customer-class')" @if(!Auth::user()->isadmin && !in_array("ลูกค้า", $employeeMenus)) style="display: none" @endif>
                <a href="javascript:void(0)" class="dropdown-toggle">
                    <i class="menu-icon fa fa-users"></i>
                    <span class="menu-text"> ลูกค้า </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="@yield('menu-customerexpect-class')">
                        <a href="{{ url('/customerexpect') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> ลูกค้ามุ่งหวัง </span>
                        </a>

                        <b class="arrow"></b>
                    </li>

                    <li class="@yield('menu-customerreal-class')">
                        <a href="{{ url('/customerreal') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> ลูกค้าจริง </span>
                        </a>

                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="@yield('menu-employee-class')" @if(!Auth::user()->isadmin && !in_array("พนักงาน", $employeeMenus)) style="display: none" @endif>
                <a href="{{ url('/employee') }}">
                    <i class="menu-icon fa fa-male"></i>
                    <span class="menu-text"> พนักงาน </span>
                </a>

                <b class="arrow"></b>
            </li>

            <li class="@yield('menu-settings-class')" @if(!Auth::user()->isadmin && !in_array("การตั้งค่าส่วนกลาง", $employeeMenus) && !in_array("การตั้งค่ารถ", $employeeMenus)) style="display: none" @endif>
                <a href="javascript:void(0)" class="dropdown-toggle">
                    <i class="menu-icon fa fa-cogs"></i>
                    <span class="menu-text"> การตั้งค่า </span>

                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>

                <ul class="submenu">
                    <li class="@yield('menu-settingcore-class')" @if(!Auth::user()->isadmin && !in_array("การตั้งค่าส่วนกลาง", $employeeMenus)) style="display: none" @endif>
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> ส่วนกลาง </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu @yield('menu-subsettingcore-class')" style="@yield('menu-subsettingcore-style')">
                            <li class="@yield('menu-settingbranch-class')">
                                <a href="{{ url('/branch') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    สาขาโชว์รูม
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingdepartment-class')">
                                <a href="{{ url('/department') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    แผนกพนักงาน
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingteam-class')">
                                <a href="{{ url('/team') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    ทีมการขาย
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingbank-class')">
                                <a href="{{ url('/bank') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    ธนาคาร-บัญชี
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settinginsurancecompany-class')">
                                <a href="{{ url('/insurancecompany') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    บริษัทประกัน
                                </a>

                                <b class="arrow"></b>
                            </li>
                        </ul>
                    </li>

                    <li class="@yield('menu-settingcar-class')" @if(!Auth::user()->isadmin && !in_array("การตั้งค่ารถ", $employeeMenus)) style="display: none" @endif>
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> รถ </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>

                        <ul class="submenu @yield('menu-subsettingcar-class')" style="@yield('menu-subsettingcar-style')">
                            <li class="@yield('menu-settingcarbrand-class')">
                                <a href="{{ url('/carbrand') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    ยี่ห้อรถ
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingcartype-class')">
                                <a href="{{ url('/cartype') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    ประเภทรถ/พ.ร.บ.
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingcolor-class')">
                                <a href="{{ url('/color') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    สีรถ
                                </a>

                                <b class="arrow"></b>
                            </li>

                            <li class="@yield('menu-settingcarmodel-class')">
                                <a href="{{ url('/carmodel') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    แบบรถ/สีและรุ่น
                                </a>

                                <b class="arrow"></b>
                            </li>

                            {{--<li class="@yield('menu-settingpricelist-class')">
                                <a href="{{ url('/pricelist') }}">
                                    <i class="menu-icon fa fa-minus"></i>
                                    รายการราคา
                                </a>

                                <b class="arrow"></b>
                            </li>--}}
                        </ul>
                    </li>
                </ul>
            </li>
        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>

    <div class="main-content">
        {{--<div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="javascript:void(0)">Home</a>
                </li>

                <li>
                    <a href="javascript:void(0)">Other Pages</a>
                </li>
                <li class="active">Blank Page</li>
            </ul><!-- /.breadcrumb -->

        </div>--}}

        <div class="page-content">
            <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="ace-icon fa fa-cog bigger-150"></i>
                </div>

                <div class="ace-settings-box clearfix" id="ace-settings-box">
                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                    <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                    <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                    <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                </select>
                            </div>
                            <span>&nbsp; Choose Skin</span>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                            <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                            <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                        </div>

                        {{--<div class="ace-settings-item">--}}
                            {{--<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />--}}
                            {{--<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>--}}
                        {{--</div>--}}

                        {{--<div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                            <label class="lbl" for="ace-settings-add-container">
                                Inside
                                <b>.container</b>
                            </label>
                        </div>--}}
                    </div><!-- /.pull-left -->

                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
                            <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
                            <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
                            <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                        </div>
                    </div><!-- /.pull-left -->
                </div><!-- /.ace-settings-box -->
            </div><!-- /.ace-settings-container -->


            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->

                    @yield('content')

                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Nissan Hippro Power</span>
							<!-- &copy; 2013-2014 -->
						</span>

                &nbsp; &nbsp;
						<span class="action-buttons">
							<a href="javascript:void(0)">
                                <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
                            </a>

							<a href="javascript:void(0)">
                                <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
                            </a>

							<a href="javascript:void(0)">
                                <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
                            </a>
						</span>
            </div>
        </div>
    </div>

    <a href="javascript:void(0)" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<script src="resources/assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->
<script src="resources/assets/js/date-time/bootstrap-datepicker.min.js"></script>
<script src="resources/assets/js/jqGrid/jquery.jqGrid.min.js"></script>
<script src="resources/assets/js/jqGrid/i18n/grid.locale-th.js"></script>

<!-- ace scripts -->
<script src="resources/assets/js/ace-elements.min.js"></script>
<script src="resources/assets/js/ace.min.js"></script>

<script src="resources/assets/js/sharefunctions.js"></script>
<script src="resources/assets/js/lightbox.min.js"></script>

</body>
</html>
