<?php
include("library/function.php");

if(isset($_POST['submit']) && $_POST['email'] != ''){

    $where = "sUserEmail = '".$_POST['email']."' AND sUserPassword = '".$_POST['password']."' AND bStatus = 1";
    $login_data = getAnyData('nUserID,bFirstLogin','users',$where,null,null);
    
    $help_img = '<img width="16px"  alt="instructions" title="instructions" src="'.$admin_url.'images/help.png" />';
    
    if($login_data[0]['nUserID'] != '')
    {
        $_SESSION['nUserID'] = $login_data[0]['nUserID'];
		if($login_data[0]['bFirstLogin'] == 0) {
			//$_SESSION['success_msg'] = "Please set your turboSMTP, Callfire and Click2Mail details to use these in your campaigns. Click on ".$help_img." for instructions.";
			$sql = 'update users set bFirstLogin = 1 where nUserID = '.$login_data[0]['nUserID'];
			mysql_query($sql);
        	header("location:emptydashboard.php");
	        exit;
		} else {
			//$_SESSION['success_msg'] = 'Login successfully';
			header("location:userdashboard.php");
            exit;
		}
    }
    else
    {
        $_SESSION['error_msg'] = 'Please enter correct username and password';
        header("location:login.php");
        exit;
    }

}
?>
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
<script type="text/javascript">
$(document).ready(function() {

    $.validator.addMethod("regexp", function (value, element) {
    return true; //added by Costas
        var emailReg = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z]+\.[a-zA-Z.]{2,5}$/i);
        var valid = emailReg.test(value);
        if(!valid) {
            return false;
        } else {
            return true;
        }
    });

    $("#frmlogin").validate({
        rules: {
            password: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                regexp:true
            }
        },
        messages: {
            password: {
                required: "Please provide a password"
            },
            email: {
                required: "Please provide an email address",
                 email :"Please enter a valid email address",
                regexp:"Please enter a valid email address"
            }, 
        }
    });
});


function validateEmail(email){

return true; //added by Costas

    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var valid = emailReg.test(email);

    if(!valid) {
        return false;
    } else {
        return true;
    }
}
</script>
</head>
<body id="login">
	<div class="row-fluid">
		<div class="span12">
			<div class="header">
				<div class="container ">
					<a href="#"><img src="images/logo.png" alt="logo"></a>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">&nbsp;</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
		    <div class="container">
		        <form class="form-signin" name="frmlogin" id="frmlogin" action="" method="post">
		            <?php if(isset($_SESSION['error_msg'])){?>
		                <div style="color:red;font-size: 17px;text-align: center;"><?php echo $_SESSION['error_msg'];?></div>
		            <?php } else if(isset($_SESSION['success_msg'])){?>
		                <div style="color:green;font-size: 17px;text-align: center;"><?php echo $_SESSION['success_msg'];?></div>
		            <?php } ?>
		            <h1 class="form-signin-heading">Sign In</h1>
		            <input type="text" name="email" class="input-block-level" placeholder="Email">
		            <input type="password" name="password" class="input-block-level" placeholder="Password">
					<div class="remember">
						<input type="checkbox" name="remember" checked>
						<span class="rememberme">Remember me</span>
					</div>
		            <button class="btn btn-large btn-primary btn-sign" name="submit" type="submit">Sign In</button>
		            <!--<button class="btn btn-large btn-primary" name="submit" type="button" onclick="window.location.href='forgotpassword.php'">Forgot Password</button>-->
					<a href="#" onclick="window.location.href='forgotpassword.php'" >Forgot your password?</a>
		        </form>
                        <!-- <div class="singup">Need an account? <a href="#">Sign up now</a></div> -->
		    </div>
		</div>
	</div>
</body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
