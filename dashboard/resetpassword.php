<?php
include("library/function.php");
if(isset($_POST['submit']) && $_POST['NewPassword'] != '' && $_POST['ConfirmPassword'] != '')
{
    $nUserID = $_POST['nUserID'];
    $nResetPwdId = $_POST['nResetPwdId'];
    $data['sUserPassword']     =   $_POST['NewPassword'];
    $where = 'nUserID = '.$nUserID;
    $data = dbRowUpdate('users', $data, $where);

    $sql_delete = "delete from user_resetpwd where nResetPwdId = '".$login_data[0]['nResetPwdId']."'";
    mysql_query($sql_delete);

    if($data == TRUE) {
        $_SESSION['success_msg'] = 'Password updated successfully';
        header('location:login.php');
        exit;
    } else {
        $_SESSION['error_msg'] = 'Error in reset password';
        header('location:resetpassword.php');
        exit;
    }
}
else
{

    $sKey = $_GET['k'];
    $date1 = date("Y-m-d H:i:s");
    $sql_sele = mysql_query("select * from user_resetpwd where sKey = '".$sKey."'");
    $data = mysql_fetch_assoc($sql_sele);
    if(mysql_num_rows($sql_sele) > 0){
        $date2 = $data['dCreatedDate'];
        $dateDiff    = $date1 - $date2;   
        $fullDays    = floor($dateDiff/(60*60*24));   
        $nResetPwdId = $data['nResetPwdId'];
        $nUserID = $data['nUserId'];
        //$fullHours   = floor(($dateDiff-($fullDays*60*60*24))/(60*60));   
        /*$fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);     */
        if($fullDays >= '1'){
            $_SESSION['error_msg'] = 'Your link is expired';
            header('location:login.php');
            exit;
        }
    }
    else{
        $_SESSION['error_msg'] = 'Your link is expired';
        header('location:login.php');
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
   $("#frmresetpwd").validate({
        rules: {
            ConfirmPassword: {
                required: true,
                minlength: 5,
                equalTo: "#NewPassword"
            },
            NewPassword: {
                required: true,
                minlength: 5
            }
        },
        messages: {
            ConfirmPassword: {
                required: "Please enter confirmed password",
                minlength: "Your confirm password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            NewPassword: {
                required: "Please provide a new password",
                minlength: "Your new password must be at least 5 characters long"
            }
        }
    });
});
</script>
</head>
<body id="login">
	<div class="row-fluid">
		<div class="span12">
			<div class="header">
				<div class="container"><a href="#"><img src="images/logo.png" alt="logo"></a></div>
			</div>
		</div>
	</div>
	<div class="row-fluid"><div class="span12">&nbsp;</div></div>
    <div class="container">
        <form class="form-signin" name="frmresetpwd" id="frmresetpwd" action="" method="post">
            <input type="hidden" name="nUserID" value="<?php echo $nUserID;?>">
            <input type="hidden" name="nResetPwdId" value="<?php echo $nResetPwdId;?>">
            <?php if(isset($_SESSION['error_msg'])){?>
            <div style="color:red;font-size: 17px;text-align: center;"><?php echo $_SESSION['error_msg'];?></div>
            <?php } else if(isset($_SESSION['success_msg'])){?>
            <div style="color:green;font-size: 17px;text-align: center;"><?php echo $_SESSION['success_msg'];?></div>
            <?php } ?>
            <h1 class="form-signin-heading">Reset your password</h1>
			<div class="forgotpass-text">Please enter a new password.</div>
            <input type="password" name="NewPassword" id="NewPassword" class="input-block-level" placeholder="Password">
            <input type="password" name="ConfirmPassword" id="ConfirmPassword" class="input-block-level" placeholder="Password (again)">
            <button class="btn btn-large btn-primary btn-sign btn-forgot" name="submit" type="submit">Reset password</button>
        </form>
    </div>
</body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
