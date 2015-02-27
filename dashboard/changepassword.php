<?php 
$page = 'Account'; 
include("header.php");
if(isset($_POST['submit']) && $_POST['NewPassword'] != '' && $_POST['CurrentPassword'] != '')
{
    $where = 'nUserID = "'.$_SESSION['nUserID'].'"';
    $user_data = getAnyData('sUserPassword','users',$where,null,null);
    #$user_data[0]['sUserPassword'] .'=='. md5($_POST['CurrentPassword']);
    if($user_data[0]['sUserPassword'] == $_POST['CurrentPassword']) {
        $data['sUserPassword']     =   $_POST['NewPassword'];
        $data = dbRowUpdate('users', $data, $where);
        if($data == TRUE) {
            $_SESSION['success_msg'] = 'Password changed successfully';
        } else {
            $_SESSION['error_msg'] = 'Error in password';
        }
    } else {
        $_SESSION['error_msg'] = 'Plese enter correct current password';
    }
    header('location:changepassword.php');
    exit;
}
?>
<div class="span12" id="content">
    <?php if(isset($_SESSION['success_msg'])){?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['success_msg'];?></div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['error_msg'])){?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['error_msg'];?></div>
        </div>
    <?php } ?>
    <div class="row-fluid">
        <div class="span3">
            <?php include("account_leftbar.php"); ?>
        </div>
        <div class="block span9">
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="" method="post" name="frmchangepwd" id="frmchangepwd">
                        <fieldset>
                            <div class="control-group">
                                <h2>Change password</h2>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="Current Password">Current password</label>
                                <div class="controls">
                                    <input type="password" class="account-input span8" id="CurrentPassword" name="CurrentPassword" value="" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="New Password">New password</label>
                                <div class="controls">
                                    <input type="password" class="account-input span8" id="NewPassword" name="NewPassword" value="" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="Confirm Password">Confirm password</label>
                                <div class="controls">
                                    <input type="password" class="account-input span8" id="ConfirmPassword" name="ConfirmPassword" value="" >
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Update password</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#frmchangepwd").validate({
        rules: {
            CurrentPassword: {
                required: true,
            },
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
                required: "Please re-enter new password",
                minlength: "Password must be at least 5 characters long",
                equalTo: "Please enter same password as above"
            },
            NewPassword: {
                required: "Please provide a new password",
                minlength: "Password must be at least 5 characters long"
            },
            CurrentPassword: {
                required: "Please enter current password"
            }
        }
    });
});
</script>
<?php include("footer.php");?>
       