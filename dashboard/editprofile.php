<?php 
$page = 'Account'; 
include("header.php");
if(isset($_POST['submit']) && $_POST['sUserFullName'] != ''){
    
    $data['sUserFullName']      =   $_POST['sUserFullName'];
    $data['sUserEmail']         =   $_POST['sUserEmail'];
    $data['sUserBusinessName']  =   $_POST['sUserBusinessName'];

    $where = "nUserId = '".$_SESSION['nUserID']."'";
    $data = dbRowUpdate('users', $data, $where);

    if($data == TRUE){
        $_SESSION['success_msg'] = 'Profile information updated successfully';
        header('location:editprofile.php');
        exit;
    } 
    else {
        $_SESSION['error_msg'] = 'Profile information not updated. Please try again.';
        header('location:editprofile.php');
        exit;
    }
}
$sPorts = $login_data[0]['sPorts'];
?>
<link rel="stylesheet" href="<?php echo $base_url;?>css/colorbox.css" />
<script src="<?php echo $base_url;?>js/jquery.colorbox.js"></script>
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
                    <form class="form-horizontal" action="" method="post" name="frmeditprofile" id="frmeditprofile">
                        <input type="hidden" name="sServerName" value="pro.turbo-smtp.com">
                        <fieldset>
                            <div class="control-group">
                                <h2> Profile information</h2>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserFullName">Full name</label>
                                <div class="controls">
                                    <input type="text" class="account-input span8" id="sUserFullName" name="sUserFullName" value="<?php echo $login_data[0]['sUserFullName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserEmail">Email</label>
                                <div class="controls">
                                    <input type="text" class="account-input span8" id="sUserEmail" name="sUserEmail" value="<?php echo $login_data[0]['sUserEmail'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserBusinessName">Business name</label>
                                <div class="controls">
                                    <input type="text" class="account-input span8" id="sUserBusinessName" name="sUserBusinessName" value="<?php echo $login_data[0]['sUserBusinessName'];?>" >
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save profile information</button>
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

    $(".ajax").colorbox();

    $("#frmeditprofile").validate({
        rules: {
            sUserFullName: {
                required: true,
            },
            sUserEmail: {
                required: true,
                email: true
            }
        },
        messages: {
            sUserFullName: "Please Enter Your Name",
            sUserEmail: {
                required:"Please enter Email Address",
                email : "Please enter a Valid Email Address"
            }
        }
    });
});
</script>
<?php include("footer.php");?>
       
