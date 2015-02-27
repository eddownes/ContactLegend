<?php 
include("header.php");
if(isset($_POST['submit']) && $_POST['sEmail'] != '')
{

    $data['sFirstName']     =   $_POST['sFirstName'];
    $data['sLastName']      =   $_POST['sLastName'];
    $data['sEmail']         =   $_POST['sEmail'];
    $where = "nAdminUserId = '".$_SESSION['cl_nAdminUserId']."' AND bStatus = 1";
    $data = dbRowUpdate('adminusers', $data, $where);
    if($data == TRUE) 
    {
        $_SESSION['success_msg'] = 'Profile updated successfully';
        header('location:'.$admin_url.'editprofile.php');
        exit;
    } 
    else 
    {
        $_SESSION['error_msg'] = 'Error';
        header('location:'.$admin_url.'editprofile.php');
        exit;
    }
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
            <div class="navbar navbar-inner block-header"><div class="muted pull-left"><?php echo $admin_data[0]['sFirstName']."'s Profile";?></div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="" method="post" name="frmeditprofile" id="frmeditprofile">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="sFirstName">First Name</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sFirstName" name="sFirstName" value="<?php echo $admin_data[0]['sFirstName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sLastName">Last Name</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sLastName" name="sLastName" value="<?php echo $admin_data[0]['sLastName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sEmail">Email</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sEmail" name="sEmail" value="<?php echo $admin_data[0]['sEmail'];?>" >
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
    $("#frmeditprofile").validate({
        rules: {
            sFirstName: {
                required: true,
            },
            sLastName: {
                required: true,
            },
            sEmail: {
                required: true,
                email: true
            }
        },
        messages: {
            sEmail: "Please enter a valid email address"
        }
    });
});
</script>
<?php include("footer.php");?>
       