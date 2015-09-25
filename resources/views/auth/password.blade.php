<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Forgot Password</title>

    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="../resources/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../resources/assets/font-awesome/4.0.3/css/font-awesome.min.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="../resources/assets/fonts/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="../resources/assets/css/ace.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../resources/assets/css/ace-part2.min.css" />
    <![endif]-->
    <link rel="stylesheet" href="../resources/assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="../resources/assets/css/ace-rtl.min.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="../resources/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- ace settings handler -->
    <script src="../resources/assets/js/ace-extra.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="../resources/assets/js/html5shiv.js"></script>
    <script src="../resources/assets/js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="../resources/assets/img/favicon.ico">
</head>

<body class="login-layout light-login">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-car green"></i>
                            <span class="red">Nissan</span>
                            <span class="grey" id="id-text2">Hippo</span>
                            <span class="blue" id="id-company-text">Power</span>
                            {{--<i class="ace-icon fa fa-leaf green"></i>--}}
                        </h1>
                        <h4 class="blue" id="id-company-text"></h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="forgot-box" class="forgot-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header red lighter bigger">
                                        <i class="ace-icon fa fa-key"></i>
                                        กู้คืนรหัสผ่าน
                                    </h4>

                                    <div class="space-6"></div>
                                    <p>
                                        กรอกอีเมล์ของคุณเพื่อรับข้อมูลในการกู้คืนรหัสผ่าน
                                    </p>

                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            <strong>ขออภัย!</strong> มีปัญหาบางอย่างกับการป้อนข้อมูลของคุณ<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ url('/password/email') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="อีเมล์" name="email" value="{{ old('email') }}"/>
															<i class="ace-icon fa fa-envelope"></i>
														</span>
                                            </label>

                                            <div class="clearfix">
                                                <button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
                                                    <i class="ace-icon fa fa-lightbulb-o"></i>
                                                    <span class="bigger-110">ส่งให้ฉัน!</span>
                                                </button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div><!-- /.widget-main -->

                                <div class="toolbar center">
                                    <a href="{{ url('/auth/login') }}" data-target="#login-box" class="back-to-login-link">
                                        <i class="ace-icon fa fa-arrow-left"></i>
                                        กลับสู่หน้าลงชื่อเข้าสู่ระบบ
                                    </a>
                                </div>
                            </div><!-- /.widget-body -->
                        </div><!-- /.forgot-box -->
                    </div><!-- /.position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="../resources/assets/js/jquery.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="../resources/assets/js/jquery-1.11.0.min.js"></script>
<![endif]-->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='../resources/assets/js/jquery.min.js'>"+"<"+"/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='../resources/assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='../resources/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="../resources/assets/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->

<!-- ace scripts -->
<script src="../resources/assets/js/ace-elements.min.js"></script>
<script src="../resources/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
</script>
</body>
</html>
