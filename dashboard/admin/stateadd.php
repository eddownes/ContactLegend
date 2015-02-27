<?php 
include("header.php");
error_reporting(0);
$mode = $_REQUEST['mode'];
$nStateId = $_REQUEST['id'];
if($mode == '')
{
    $mode = 'Add';
}
else
{
    $where = "nStateId = '".$nStateId."'";
    $user_data = getAnyData('*','state',$where,null,null);
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
            <div class="navbar navbar-inner block-header"><div class="muted pull-left">ADD NEW STATE</div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="stateadd_a.php" method="post" name="frmstate" id="frmstate" >
                        <input type="hidden" name="mode" value="<?php echo $mode?>">
                        <input type="hidden" name="nStateId" value="<?php echo $nStateId?>">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="vStateName">State Name</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="vStateName" name="vStateName" value="<?php echo $user_data[0]['vStateName'];?>" >
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                <button type="reset" class="btn">Cancel</button>
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
    $("#frmstate").validate({
        rules: {
            vStateName: {
                required: true,
            },
        },
        messages: {
            vStateName: "Please enter a state name",
        }
    });
});
</script>
<?php include("footer.php");?>