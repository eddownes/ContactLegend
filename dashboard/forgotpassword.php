<?php
include("library/function.php");
include('smtpmail/classes/class.phpmailer.php');

if(isset($_POST['submit']) && $_POST['email'] != ''){

    $where = "u.sUserEmail = '".$_POST['email']."' AND u.bStatus = 1";
    $table = 'users as u';
    $login_data = getAnyData('u.nUserID',$table,$where,null,null);

    if($login_data[0]['nUserID'] != '')
    {

        $where = "bStatus = '1'";
        $table = 'adminusers';
        $admin_data = getAnyData('sEmail',$table,$where,null,null);
        $skey = randomPassword();
        $restpwdlink = $base_url.'resetpassword.php?k='.$skey;
        $message = "<table class='body'>
                        <tr>
                            <td align='center' valign='top'>
                                <center><h1>Contact Legend</h1></center>
                            </td>
                        </tr>
                        <tr><td align='left' valign='top'>We received your request to reset your Contact Legend password.</td></tr>
                        <tr><td>You can reset your password by clicking here: <a href=".$restpwdlink." target='_blank'>Reset Password</a></td></tr>
                        <tr><td>The above reset password link will only be valid for 24 hours.</td></tr>
                        <tr><td>If you did not initiate this password reset then simply do nothing and it will remain the same.</td></tr>
                        <tr><td>If you need further help please contact Support.</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr><td>Thank you,</td></tr>
                        <tr><td>Contact Legend</td></tr>
                    </table>";

        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/></head>  <body>".$message."</body></html>";

        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->IsHTML(true);
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        
        /*$mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = $login_data[0]['sServerName'];
        $mail->Port = $login_data[0]['sPorts']; // or 587
        $mail->Username = $login_data[0]['sUsername'];
        $mail->Password = $login_data[0]['sPassword'];
        $mail->SetFrom($fromemail);*/

        $mail->SMTPAuth   = true;                // enable SMTP authentication
        //$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host = '72.52.245.168';
        $mail->Port = '25'; // or 587           // set the SMTP port for the GMAIL server
        $mail->Username = 'no-reply@contactlegend.com';
        $mail->Password = 't6[n6c{Py5Q1';
        $fromemail = 'no-reply@contactlegend.com';
        $mail->SetFrom($fromemail);

        $mail->Subject = 'Forgot Password';
        $mail->Body = $body;
        $toemail = $_POST['email'];    
        $mail->AddAddress($toemail);
        $mail->Send();


        $sql_delete = "delete from user_resetpwd where nUserId = '".$login_data[0]['nUserID']."'";
        mysql_query($sql_delete);

        $sql_ins = "insert into user_resetpwd(`nUserId`,`sKey`,`dCreatedDate`) VALUES ('".$login_data[0]['nUserID']."','".$skey."','".date("Y-m-d H:i:s")."')";
        mysql_query($sql_ins);

        $_SESSION['success_msg'] = 'Check your email for password reset instructions';
        header("location:login.php");
        exit;
    }
    else
    {
        $_SESSION['error_msg'] = 'Sorry, we could not find your account with this e-mail address.';
        header("location:forgotpassword.php");
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
            email: {
                required: true,
                email: true,
                regexp:true
            }
        },
        messages: {
            email: {
                required: "Please provide an email address",
                email :"Please enter a valid email address",
                regexp:"Please enter a valid email address"
            }, 
        }
    });
});

</script>
</head>
<body id="login">
    <div class="row-fluid">
        <div class="span12">
            <div class="header">
                <div class="container" >
                    <a href="#"><img src="images/logo.png" alt="logo"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">&nbsp;</div>
    </div>
    <div class="container">
        <form class="form-signin" name="frmlogin" id="frmlogin" action="" method="post">
            <?php if(isset($_SESSION['error_msg'])){?>
            <div style="color:red;font-size: 17px;text-align: center;"><?php echo $_SESSION['error_msg'];?></div>
            <?php } else if(isset($_SESSION['success_msg'])){?>
            <div style="color:green;font-size: 17px;text-align: center;"><?php echo $_SESSION['success_msg'];?></div>
            <?php } ?>
            <h1 class="form-signin-heading">Forgot password?</h1>
            <div class="forgotpass-text">To reset your account password, enter the email address you registered with and we'll send a reset password link.</div>
            <input type="text" name="email" class="input-block-level" placeholder="Email">
            <button class="btn btn-large btn-primary btn-sign btn-forgot" name="submit" type="submit">Send reset link</button>
            <a href="#" onclick="window.location.href='login.php'">Back to sign in</a>
        </form>
    </div>
</body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
