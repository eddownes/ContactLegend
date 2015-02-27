<?php 

include("header.php");
if(isset($_POST['submit']) && $_POST['NewPassword'] != '' && $_POST['CurrentPassword'] != '')
{
    $where = "nAdminUserId = '".$_SESSION['ppr_nAdminUserId']."' AND bStatus = 1";
    $adminuser_data = getAnyData('sPassword','adminusers',$where,null,null);
    $adminuser_data[0]['sPassword'] .'=='. md5($_POST['CurrentPassword']);
    if($adminuser_data[0]['sPassword'] == md5($_POST['CurrentPassword']))
    {
        $data['sPassword']     =   md5($_POST['NewPassword']);
        $data = dbRowUpdate('adminusers', $data, $where);
        if($data == TRUE) 
        {
            $_SESSION['success_msg'] = 'Password changed successfully';
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in password';
        }
    }
    else
    {
        $_SESSION['error_msg'] = 'Plese enter correct current password';
    }
    header('location:'.$admin_url.'changepwd.php');
    exit;
}
?>
<div class="span9" id="content">
    <div class="row-fluid">
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
        <div class="block">
            <div class="navbar navbar-inner block-header"><div class="muted pull-left">Change Password</div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="" method="post" name="frmchangepwd" id="frmchangepwd">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="Current Password">Current Password<em>*</em></label>
                                <div class="controls">
                                    <input type="password" class="span6" id="CurrentPassword" name="CurrentPassword" value="" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="New Password">New Password<em>*</em></label>
                                <div class="controls">
                                    <input type="password" class="span6" id="NewPassword" name="NewPassword" value="" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="Confirm Password">Confirm Password<em>*</em></label>
                                <div class="controls">
                                    <input type="password" class="span6" id="ConfirmPassword" name="ConfirmPassword" value="" >
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                <button type="reset" onclick="window.history.back()" class="btn btn-primary">Back</button>
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
                required: "Please enter confirmed password",
                minlength: "Your confirm password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            NewPassword: {
                required: "Please provide a new password",
                minlength: "Your new password must be at least 5 characters long"
            },
            CurrentPassword: {
                required: "Please enter Current Password"
            }
        }
    });
});
</script>
<?php include("footer.php");?>
       