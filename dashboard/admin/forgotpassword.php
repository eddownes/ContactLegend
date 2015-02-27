<?php
include("library/function.php");

if(isset($_POST['submit']) && $_POST['email'] != ''){


    $where = "u.sUserEmail = '".$_POST['email']."' AND u.bStatus = 1";
    $table = 'users as u LEFT JOIN user_smtpdetails as us on us.nUserID = u.nUserID';
    $login_data = getAnyData('u.nUserID,us.*',$table,$where,null,null);
    if($login_data[0]['nUserID'] != '')
    {

        $password = randomPassword();
        $message = "<table class='body'>
                    <tr>
                        <td class='center' align='center' valign='top'>
                            <center><h1>Contact Legend</h1></center>
                        </td>
                    </tr>
                    <tr>
                        <td class='center' align='center' valign='top'>
                            <center>Your Request for password , This is in response to your request for login password at Contact Legend. </center>
                        </td>
                    </tr>
                    <tr><td>Your Password: ".$password."</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>Thank You,</td></tr>
                    <tr><td>Contact Legend</td></tr>
                    </table>";

        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/></head>  <body>".$message."</body></html>";

        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = $login_data[0]['sServerName'];
        $mail->Port = $login_data[0]['sPorts']; // or 587
        $mail->IsHTML(true);
        $mail->Username = $login_data[0]['sUsername'];
        $mail->Password = $login_data[0]['sPassword'];
        $mail->SetFrom($fromemail);
        $mail->Subject = 'Forgot Password';
        $mail->Body = $body;
        $toemail = $_POST['email'];    
        $mail->AddAddress($toemail);
        $mail->Send();

        $sql_update = "update users set sUserPassword = '".$password."' where nUserID = '".$login_data[0]['nUserID']."'";
        mysql_query($sql_update);

        $_SESSION['success_msg'] = 'Email send successfully to you email address';
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
    <div class="container">
        <form class="form-signin" name="frmlogin" id="frmlogin" action="" method="post">
            <?php if(isset($_SESSION['error_msg'])){?>
            <div style="color:red;font-size: 17px;text-align: center;"><?php echo $_SESSION['error_msg'];?></div>
            <?php } else if(isset($_SESSION['success_msg'])){?>
            <div style="color:green;font-size: 17px;text-align: center;"><?php echo $_SESSION['success_msg'];?></div>
            <?php } ?>
            <h4 class="form-signin-heading">Contact Legend Forgot Password</h4>
            <input type="text" name="email" class="input-block-level" placeholder="Email address">
            <button class="btn btn-large btn-primary" name="submit" type="submit">Send Mail</button>
        </form>
    </div>
</body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
