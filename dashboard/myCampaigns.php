<?php 
include('header.php');
$where = 'nUserID = "'.$_SESSION['nUserID'].'"';
$userName = getAnyData('sUserFullName', 'users', $where, null, null);
?>
<div class="span12" id="content">
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
        <?php
            $temp_id = '';
            $where = 'nUserID="'.$_SESSION['nUserID'].'"';
            $ordrby = "order by nCampaignid desc";
            $campaignData = getAnyData('*', 'campaigns', $where, null,$ordrby);
            $totCampaign = count($campaignData);
            $temp_id = $campaignData[0]['nCampaignid'];
        ?>
        <div class="span12">
            <p>&nbsp;</p>
        </div>
        <div class="span12">
            <div class="statics-block span3">
                <p>Total Emails Sent</p>
                <?php 
                    $result = mysql_query('SELECT SUM(nEmailsSent) AS sent_sum, SUM(nEmailsOpened) AS open_sum FROM campaigns'); 
                    $row = mysql_fetch_assoc($result); 
                    $sent_sum = $row['sent_sum'];
                    $open_sum = $row['open_sum'];
                ?>
                <p class="num"><?php echo $sent_sum; ?></p>
            </div>
            <div class="statics-block span3">
                <p>Active Campaigns</p>
                <p class="num"><?php echo $totCampaign; ?></p>
            </div>
            <div class="statics-block span3">
                <p>Average Open Rate</p>
                <p class="num">
                    <?php 
                    $avg_open_rate = ($open_sum / $sent_sum) * 100; 
                    echo number_format((float)$avg_open_rate, 1, '.', '') . " %";
                    ?>
                </p>
            </div>
            <div class="statics-block span3">
                <p>Avarage Days to Open</p>
                <p class="num">1 Day</p>
            </div>
        </div>
        <div class="span12">
            <p>&nbsp;</p>
        </div>
        <div class="">
            <div class="">
                <div class="span12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="userlist">
                        <thead>
                            <tr>
                                <th>Campaign Name</th>
                                <th>Draft</th>
                                <th>Sends</th>
                                <th>Opens</th>
                                <th colspan="3">Follow Up Actions</th>
                                <th>Follow Up Opens</th>
                                <th>Open Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($totCampaign)){
                                for($i=0;$i<$totCampaign;$i++){
                                    
                                    $where = ' sf.bFollowUpMailSent = "1" AND sf.bStatus = "1" and sf.nCampaignid = "'.$campaignData[$i]['nCampaignid'].'"';
                                    $tables = "schedulefollowemails as sf ";
                                    $followupmail = getAnyData('nScheduleFollowMailId',$tables, $where, null, null);
                                    $tot_follwup = count($followupmail);

                                    $openwhere = 'sf.bFollowUpMailOpen = "1" AND sf.bFollowUpMailSent = "1" AND sf.bStatus = "1" and sf.nCampaignid = "'.$campaignData[$i]['nCampaignid'].'"';
                                    $openfollowupmail = getAnyData('nScheduleFollowMailId',$tables, $openwhere, null, null);
                                    $tot_openfollwup = count($openfollowupmail);

                                    $sch_where = "nCampaignid = '".$campaignData[$i]['nCampaignid']."' AND bCallStatus = 1";
                                    $schedule_data = getAnyData('*','schedulecalls', $sch_where, null, null);

                                    $sch_where = "nCampaignid = '".$campaignData[$i]['nCampaignid']."' AND bStatus = 1";
                                    $schedule_directdata = getAnyData('*','scheduledirectmails', $sch_where, null, null);
                                    $tot_direct = count($schedule_directdata);
                                    
                            ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0)"> 
                                        <?php echo $campaignData[$i]['sCampaignname']?>
                                    </a>
                                </td>
                                <td>
                                    <?php if($campaignData[$i]['nDraft'] == '1'){?>
                                        <a href="createCampaign.php?id=<?php echo base64_encode($campaignData[$i]['nCampaignid']);?>"> Yes </a>
                                    <?php } else { ?>
                                        No
                                    <?php }?>
                                </td>
                                <td class='inline'>
                                    <?php if($campaignData[$i]['nEmailsSent'] != '0'){ ?>
                                    <a class='ajax' href="popup_sentmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nEmailsSent']?>
                                    </a>
                                    <?php } else { ?>
                                    <?php echo $campaignData[$i]['nEmailsSent']?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($campaignData[$i]['nEmailsOpened'] != '0'){ ?>
                                    <a class='ajax' href="popup_openmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nEmailsOpened']?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo $campaignData[$i]['nEmailsOpened']?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(count($schedule_data) > '0'){ ?>
                                    <a class='ajax' href="popup_callmade.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo count($schedule_data);?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo '0';?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($tot_direct != '0'){ ?>
                                    <a class='ajax' href="popup_directmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $tot_direct;?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo '0';?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($tot_follwup != '0'){ ?>
                                    <a class='ajax' href="popup_followupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $tot_follwup;?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo $tot_follwup;?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($tot_openfollwup != '0'){ ?>
                                    <a class='ajax' href="popup_openfollowupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $tot_openfollwup;?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo $tot_openfollwup;?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php 
                                        if($campaignData[$i]['nEmailsSent'] != '0'){ 
                                            $open_rate = ( $campaignData[$i]['nEmailsOpened'] / $campaignData[$i]['nEmailsSent'] ) * 100;
                                            echo number_format((float)$open_rate, 1, '.', ''). " %";
                                        } else {
                                            echo "0 %";
                                        }
                                    ?>
                                </td>
                                 
                            </tr>
                            
                            <?php
                            
                            } 
                            }else{
                                echo '<td>'.'No records to Display.'.'</td>';}?>
                        </tbody>
                    </table>
                </div><!-- span12 -->
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
<link rel="stylesheet" href="<?php echo $base_url;?>css/colorbox.css" />
<script src="<?php echo $base_url;?>js/jquery.colorbox.js"></script>
<script type="text/javascript">
    $(".ajax").colorbox();
</script>
<script src="<?php echo $base_url;?>js/DT_bootstrap.js"></script>
<link href="<?php echo $base_url.'css/DT_bootstrap.css';?>" rel="stylesheet" media="screen">
