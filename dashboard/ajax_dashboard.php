<?php
include('library/function.php'); 
$where = 'nUserID = "'.$_SESSION['nUserID'].'"';
$userName = getAnyData('sUserFullName', 'users', $where, null, null);

$temp_id = '';
$where = 'nUserID="'.$_SESSION['nUserID'].'"';
$ordrby = "order by nCampaignid desc";
$campaignData = getAnyData('*', 'campaigns', $where, null,$ordrby);
$totCampaign = count($campaignData);
$temp_id = $campaignData[0]['nCampaignid'];
?>


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
            $dateins =  date("m/j/Y g:i a", strtotime($campaignData[$i]['dCreatedDate'])).'<br/>';
            
    ?>
    <tr>
        <td>
            <?php if($campaignData[$i]['nDraft'] == '1'){?>
                <a class="anchor_draft" href="createCampaign.php?id=<?php echo base64_encode($campaignData[$i]['nCampaignid']);?>">Draft</a>
            <?php } else {?>
                <span class="anchor_draft">Started</span>
            <?php } ?>

            <?php if($campaignData[$i]['nDraft'] == '1'){?>
                <a href="createCampaign.php?id=<?php echo base64_encode($campaignData[$i]['nCampaignid']);?>"><?php echo $campaignData[$i]['sCampaignname']?></a>
            <?php } else {?>
                <?php echo $campaignData[$i]['sCampaignname']?>
            <?php } ?>
            
            <?php if($campaignData[$i]['nDraft'] != '1'){?>    
                <p class="sent_dt_tm">Started on <?php echo $dateins;?><!-- 06/06/2014 at 5:34PM --></p>
            <?php } else {?>
                <p class="sent_dt_tm">Not started yet</p>
            <?php } ?>
        </td>
        <?php if($campaignData[$i]['nDraft'] != '1'){?>
        <!-- <td>
            <?php #if($campaignData[$i]['nDraft'] == '1'){?>
                <a href="createCampaign.php?id=<?php #echo base64_encode($campaignData[$i]['nCampaignid']);?>"> Yes </a>
            <?php# } else { ?>
                No
            <?php #}?>
        </td> -->
        
        <td class='inline bigger <?php if($campaignData[$i]['nEmailsSent'] == '0') { echo "light-opa"; } ?>'>
            <?php if($campaignData[$i]['nEmailsSent'] != '0'){ ?>
            <a class='ajax cboxElement' href="popup_sentmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $campaignData[$i]['nEmailsSent']?>
            </a>
            <?php } else { ?>
            <?php echo "-"; ?>
            <?php } ?>                                    
            <img class="arrow_img" src="images/arrow.png" alt="">                                    
        </td>
        <td class="bigger <?php if($campaignData[$i]['nEmailsOpened'] == '0') { echo "light-opa"; } ?>">
            <?php if($campaignData[$i]['nEmailsOpened'] != '0'){ ?>
            <a class='ajax cboxElement' href="popup_openmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $campaignData[$i]['nEmailsOpened']?>
            </a>
            <?php } else { ?>
                <?php echo "-"; ?>
            <?php } ?>
            
            <img class="arrow_img" src="images/arrow.png" alt="">
            
        </td>
        
        <td style="width: 1%;"></td>
        
        <td class="bigger <?php if($campaignData[$i]['nCallsMade'] == '0') { echo "light-opa"; } ?>">
            
            <img class="follow_icons phone" src="images/enable_phone.png" alt="">
            
            <?php if($campaignData[$i]['nCallsMade'] != '0'){ ?>
            <a class='ajax cboxElement' href="popup_callmade.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $campaignData[$i]['nCallsMade']?>
            </a>
            <?php } else { ?>
                <?php echo "-"; ?>
            <?php } ?>
        </td>
        <td class="bigger <?php if($campaignData[$i]['nMailSent'] == '0') { echo "light-opa"; } ?>">
            
            <img class="follow_icons report" src="images/enable_report.png" alt="">
            
            <?php if($campaignData[$i]['nMailSent'] != '0'){ ?>
            <a class='ajax cboxElement' href="popup_directmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $campaignData[$i]['nMailSent']?>
            </a>
            <?php } else { ?>
                <?php echo "-"; ?>
            <?php } ?>
            
        </td>
        <td class="bigger <?php if($tot_follwup == '0') { echo "light-opa"; } ?>">
            
            <img class="follow_icons message" src="images/enable_message.png" alt="">
            
            <?php if($tot_follwup != '0'){ ?>
            <a class='ajax cboxElement' href="popup_followupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $tot_follwup;?>
            </a>
            <?php } else { ?>
                <?php echo "-"; ?>
            <?php } ?>
            
            <img class="arrow_img" src="images/arrow.png" alt="">
            
        </td>
        
        
        
        <td class="bigger <?php if($tot_openfollwup == '0') { echo "light-opa"; } ?>">
            <?php if($tot_openfollwup != '0'){ ?>
            <a class='ajax cboxElement' href="popup_openfollowupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                <?php echo $tot_openfollwup;?>
            </a>
            <?php } else { ?>
                <?php echo "-"; ?>
            <?php } ?>
        </td>
        <td class="bigger gray">
            <?php 
                if($campaignData[$i]['nEmailsSent'] != '0'){ 
                    $open_rate = ( $campaignData[$i]['nEmailsOpened'] / $campaignData[$i]['nEmailsSent'] ) * 100;
                    echo number_format((float)$open_rate, 1, '.', ''). " %";
                } else {
                    echo "0 %";
                }
            ?>
        </td>
        <?php } else { ?>
        <td colspan="8"></td>
        <?php }?>
        <td class="td_anchor_edit">
             <?php if($campaignData[$i]['nDraft'] == '1'){?>
                <a class="btn-primary anchor_edit" href="createCampaign.php?id=<?php echo base64_encode($campaignData[$i]['nCampaignid'])?>&f=<?php echo base64_encode('No');?>">Edit</a>
                <a class="anchor_edit anchor_delete" href="javascript:void(0)" id="<?php echo base64_encode($campaignData[$i]['nCampaignid']);?>">Delete</a>
             <?php } else { ?>
                <a class="anchor_edit anchor_delete" href="javascript:void(0)" id="<?php echo base64_encode($campaignData[$i]['nCampaignid']);?>">Delete</a>
             <?php } ?> 
        </td>
    </tr>
    
    <tr class="spacer"> </tr>
    
    <?php } 
    }else{
        echo '<tr><td colspan="9">No records to Display.</td></tr>';
    }
    ?>
