<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Login</title>

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
</head>

<body class="login-layout light-login">
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Firstname</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Lastname</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Phone</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}">
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Admin</label>
                            <div class="col-md-2">
                                <select name="isadmin" class="form-control">
                                    <option value=1>Yes</option>
                                    <option value=0 selected="selected">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Branch</label>
                            <div class="col-md-3">
                                <select name="branchid" class="form-control">
                                    <option value=null>Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Department</label>
                            <div class="col-md-3">
                                <select name="departmentid" class="form-control">
                                    <option value=null>Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Role</label>
                            <div class="col-md-3">
                                <select name="roleid" class="form-control">
                                    <option value=null>Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Team</label>
                            <div class="col-md-3">
                                <select name="teamid" class="form-control">
                                    <option value=null>Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Active</label>
                            <div class="col-md-2">
                                <select name="active" class="form-control">
                                    <option value=1 selected="selected">Yes</option>
                                    <option value=0>No</option>
                                </select>
                            </div>
                        </div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
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
