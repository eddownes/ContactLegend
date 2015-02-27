<?php 
include("header.php");
error_reporting(0);
$mode = $_REQUEST['mode'];
$nCityId = $_REQUEST['id'];
if($mode == '')
{
    $mode = 'Add';
}
else
{
    $where = "nCityId = '".$nCityId."'";
    $city_data = getAnyData('*','city',$where,null,null);
}
$state_data = getAnyData('*','state','bStatus = 1',null,null);
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
            <div class="navbar navbar-inner block-header"><div class="muted pull-left">ADD NEW City</div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="cityadd_a.php" method="post" name="frmcity" id="frmcity" >
                        <input type="hidden" name="mode" value="<?php echo $mode?>">
                        <input type="hidden" name="nCityId" value="<?php echo $nCityId?>">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="vCityName">City Name</label>
                                <div class="controls">
                                    <input type="text" class="span6" id="vCityName" name="vCityName" value="<?php echo $city_data[0]['vCityName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nStateId">State Name</label>
                                <div class="controls">
                                    <select name="nStateId">
                                    <option>--select--</option>    
                                    <?php
                                    for($i=0;$i<count($state_data);$i++)
                                    {
                                        $sele = '';
                                        if($state_data[$i]['nStateId'] == $city_data[0]['nStateId'])
                                        {
                                            $sele = 'selected';
                                        }
                                    ?>
                                    <option value="<?php echo $state_data[$i]['nStateId'];?>" <?php echo $sele;?>><?php echo $state_data[$i]['vStateName'];?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>                                    
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
    $("#frmcity").validate({
        rules: {
            vCityName: {
                required: true,
            },
            nStateId: {
                required: true,
            },
        },
        messages: {
            vCityName: "Please enter a city name",
            nStateId: "Please select a state name",
        }
    });
});
</script>
<?php include("footer.php");?>