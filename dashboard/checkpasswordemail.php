<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="<?php echo $base_url;?>css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $base_url;?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $base_url;?>css/styles.css" rel="stylesheet" media="screen">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="<?php echo $base_url;?>js/jquery.js"></script>
<script src="<?php echo $base_url;?>js/jquery.validate.js"></script>
</head>
<body id="login">
    <div class="row-fluid">
        <div class="span12">
            <div class="header">
				<div class="container">
					<a href="#"><img src="images/logo.png" alt="logo"></a>
				</div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">&nbsp;</div>
    </div>
    <div class="container">
		<div class="form-signin ">
            <h1 class="form-signin-heading">Check your email</h1>
            <div class="forgotpass-text">Password reset instructions have beed sent to the email registered to this account. Check your spam and junk filters if they don't arrive.</div>
            <a href="#" onclick="window.location.href='login.php'">Back to sign in</a>
		</div>
    </div>
</body>
</html>