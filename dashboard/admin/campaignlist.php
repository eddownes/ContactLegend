<?php 
include("header.php");
$nUserID = $_REQUEST['id'];
$where  = "nUserID = '".$nUserID."'";
$user_data = getAnyData('sUserFullName', 'users', $where, null,null);
$sUserFullName = $user_data[0]['sUserFullName'];
$campaignData = getAnyData('*', 'campaigns', $where, null,null);
$tot_rec = count($campaignData);
?>
<link href="<?php echo $admin_url.'css/DT_bootstrap.css';?>" rel="stylesheet" media="screen">
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
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left"> <?php echo $sUserFullName."'s Campaign List";?></div>
                <div class="muted pull-right">
                    <button class="btn-primary" type="button" onclick="window.location='userlist.php'">Back</button>
                </div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="campaignlist">
                        <thead>
                            <tr>
                                <th>Campaign Name</th>
                                <th>Sent</th>
                                <th>Opened</th>
                                <th>Calls Made</th>
                                <th>Mail Sent</th>
                                <th>Email Followup</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($tot_rec > 0)
                            {
                                for($i=0;$i<$tot_rec;$i++)
                                {
                                    $class = 'even gradeX';
                                    if(($i%2)==0){$class = 'odd gradeX';}
                                ?>    
                                <tr class="<?php echo $class?>">
                                    <td><?php echo $campaignData[$i]['sCampaignname']?></td>
                                    <td><?php echo $campaignData[$i]['nEmailsSent']?></td>
                                    <td><?php echo $campaignData[$i]['nEmailsOpened']?></td>
                                    <td><?php echo $campaignData[$i]['nCallsMade']?></td>
                                    <td><?php echo $campaignData[$i]['nMailSent']?></td>
                                    <td><?php echo $campaignData[$i]['nEmailFollowUps']?></td>
                                </tr>
                            <?php
                                }
                            }
                            else
                            {
                            ?>
                                <tr><td colspan="6">No records to Display.</td></tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo $admin_url;?>vendors/datatables/js/jquery.dataTables.min.js"></script> 
<?php include("footer.php");?>
<script src="<?php echo $admin_url;?>js/DT_bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var tot_rec = '<?php echo $tot_rec;?>';
    if(tot_rec > 0){
        $('#campaignlist').dataTable( {
            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            }
        });
    }
});

</script>     