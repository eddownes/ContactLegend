<?php
include("../library/function.php");
if(isset($_POST['submit']) && $_POST['email'] != '')
{
    $where = "sEmail = '".$_POST['email']."' AND sPassword = '".md5($_POST['password'])."' AND bStatus = 1";
    $login_data = getAnyData('nAdminUserId','adminusers',$where,null,null);
    if($login_data[0]['nAdminUserId'] != '')
    {
        $_SESSION['success_msg'] = 'Login successfully';
        $_SESSION['cl_nAdminUserId'] = $login_data[0]['nAdminUserId'];
        header("location:index.php");
        exit;
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
<title>Admin Login</title>
<link href="<?php echo $admin_url;?>css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $admin_url;?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $admin_url;?>css/styles.css" rel="stylesheet" media="screen">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="<?php echo $admin_url;?>js/jquery.js"></script>
<script src="<?php echo $admin_url;?>js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#frmlogin").validate({
        rules: {
            password: {
                required: true,
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            password: {
                required: "Please provide a password"
            },
            email: "Please enter a valid email address"
        }
    });
});
</script>
</head>
<body id="login">
    <div class="container">
        <?php if(isset($_SESSION['error_msg'])){?>
        <div style="color:red;font-size: 17px;text-align: center;"><?php echo $_SESSION['error_msg'];?></div>
        <?php } else if(isset($_SESSION['success_msg'])){?>
        <div style="color:green;font-size: 17px;text-align: center;"><?php echo $_SESSION['success_msg'];?></div>
        <?php } ?>
        <form class="form-signin" name="frmlogin" id="frmlogin" action="" method="post">
            <h3 class="form-signin-heading">Contact Legend Admin Login</h3>
            <input type="text" name="email" class="input-block-level" placeholder="Email address">
            <input type="password" name="password" class="input-block-level" placeholder="Password">
            <button class="btn btn-large btn-primary" name="submit" type="submit">Sign in</button>
        </form>
    </div>
</body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
?>
