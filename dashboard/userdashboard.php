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
        
        <div class="span12"></div>

        <!-- refresh dashboard button -->
        <div class="span12">
            <button style="float:right;" id="refresh_btn" class="btn-primary" onclick="return get_refreshed();">Refresh</button>
        </div>

        <div class="span12"></div>

        <div class="span12">
            <div class="statics-block span3 email-sent">
                <div class="ajax_loader_block" style="display:none;" ><img src="images/ajax-loader4.gif" alt="Processing" /></div>
                <p>Total Emails Sent</p>
                <?php 
                    $result = mysql_query('SELECT SUM(nEmailsSent) AS sent_sum, SUM(nEmailsOpened) AS open_sum FROM campaigns WHERE nUserID="'.$_SESSION['nUserID'].'"'); 
                    $row = mysql_fetch_assoc($result); 
                    $sent_sum = $row['sent_sum'];
                    if($sent_sum == ''){
                        $sent_sum = 0;
                    }
                    $open_sum = $row['open_sum'];
                ?>
                <p class="num"><?php echo $sent_sum; ?></p>
            </div>
            <div class="statics-block span3 total-campaign">
                <div class="ajax_loader_block" style="display:none;" ><img src="images/ajax-loader4.gif" alt="Processing" /></div>
                <p>Active Campaigns</p>
                <p class="num"><?php echo $totCampaign; ?></p>
            </div>
            <div class="statics-block span3 open-rate">
                <div class="ajax_loader_block" style="display:none;" ><img src="images/ajax-loader4.gif" alt="Processing" /></div>
                <p>Average Open Rate</p>
                <p class="num">
                    <?php 
                    $avg_open_rate = ($open_sum / $sent_sum) * 100; 
                    echo number_format((float)$avg_open_rate, 1, '.', '') . " %";
                    ?>
                </p>
            </div>
            <div class="statics-block span3 days-open">
                <div class="ajax_loader_block" style="display:none;" ><img src="images/ajax-loader4.gif" alt="Processing" /></div>
                <p>Average Days to Open</p>
                <p class="num">
                    <?php if($sent_sum == 0) { echo '0 Days'; } else { echo getavgdays().' Days'; }?>
                </p>
            </div>
        </div>

        <div class="span12">
            <p></p>
        </div>

        <input type="hidden" id="de_campa">
        <div class="">
            <div class="">
                <div class="span12" id="content_table" style="position: relative;">
                    <div id="loading_content" style="display:none;" ><img src="images/ajax-loader4.gif" alt="Processing" /></div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="userlist">
                        <thead>
                            <tr>
                                <th>Campaign Name</th>
                               <!--  <th>Draft</th> -->
                                <th style="width: 8%;">Sends</th>
                                <th style="width: 8%;">Opens</th>
                                <th style="width: 22%;" colspan="4">Follow Up Actions</th>
                                <th style="width: 13%;">Follow Up Opens</th>
                                <th style="width: 10%;">Open Rate</th>
                            </tr>
                        </thead>
                        <tbody id="userlist_tbody">
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
                                    <a class='ajax' href="popup_sentmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nEmailsSent']?>
                                    </a>
                                    <?php } else { ?>
                                    <?php echo "-"; ?>
                                    <?php } ?>                                    
                                    <img class="arrow_img" src="images/arrow.png" alt="">                                    
                                </td>
                                <td class="bigger <?php if($campaignData[$i]['nEmailsOpened'] == '0') { echo "light-opa"; } ?>">
                                    <?php if($campaignData[$i]['nEmailsOpened'] != '0'){ ?>
                                    <a class='ajax' href="popup_openmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nEmailsOpened']?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo "-"; ?>
                                    <?php } ?>
                                    
                                    <img class="arrow_img" src="images/arrow.png" alt="">
                                    
                                </td>
                                
                                <td style="width: 1%;"></td>
                                
                                <td class="bigger <?php if($campaignData[$i]['nCallsMade'] == '0') { echo "light-opa"; } ?>">
                                    
                                    <img class="follow_icons phone" src="images/enable_phone.png" title="Voice Broadcast" alt="Voice Broadcast">
                                    
                                    <?php if($campaignData[$i]['nCallsMade'] != '0'){ ?>
                                    <a class='ajax' title="Voice Broadcast" href="popup_callmade.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nCallsMade']?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo "-"; ?>
                                    <?php } ?>
                                </td>
                                <td class="bigger <?php if($campaignData[$i]['nMailSent'] == '0') { echo "light-opa"; } ?>">
                                    
                                    <img class="follow_icons report" src="images/enable_report.png" title="Direct Mail" alt="Direct Mail">
                                    
                                    <?php if($campaignData[$i]['nMailSent'] != '0'){ ?>
                                    <a class='ajax' title="Direct Mail" href="popup_directmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $campaignData[$i]['nMailSent']?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo "-"; ?>
                                    <?php } ?>
                                    
                                </td>
                                <td class="bigger <?php if($tot_follwup == '0') { echo "light-opa"; } ?>">
                                    
                                    <img class="follow_icons message" src="images/enable_message.png" title="E-mail" alt="E-mail">
                                    
                                    <?php if($tot_follwup != '0'){ ?>
                                    <a class='ajax' title="E-mail" href="popup_followupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
                                        <?php echo $tot_follwup;?>
                                    </a>
                                    <?php } else { ?>
                                        <?php echo "-"; ?>
                                    <?php } ?>
                                    
                                    <img class="arrow_img" src="images/arrow.png" alt="">
                                    
                                </td>
                                
                                
                                
                                <td class="bigger <?php if($tot_openfollwup == '0') { echo "light-opa"; } ?>">
                                    <?php if($tot_openfollwup != '0'){ ?>
                                    <a class='ajax' href="popup_openfollowupmail.php?cid=<?php echo $campaignData[$i]['nCampaignid']?>">
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
                                echo '<td colspan="9">No records to Display.</td>';
                            }
                            ?>
                            
                            
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
   /* $(".ajax").colorbox();
    $(".anchor_delete").click(function(){
        var ans = confirm("Are you sure you want to  delete this campaign ?");
        if(ans == true){
            window.location.href = 'del_campaign.php?id='+$(this).attr('id');
            return true;
        }
        else{
            return false;
        }
    });*/
</script>
<script type="text/javascript">
$(".ajax").colorbox();

// function to refresh dashboard using ajax
function get_refreshed() {
    $("#loading_content").show();
    $(".statics-block .num").html('<img src="images/ajax-loader4.gif" alt="Processing" />');
    $('#userlist_tbody').css({
        opacity: 0.65,
    });
    $("#loading_content").css({
        "position": "absolute",
        "left": "48%",
        "bottom": "50%",
        "opacity": "1"
    });
    setTimeout(function(){  
        $.ajax({
            url: 'ajax_dashboard.php',
            success: function(data) {
                setTimeout(function(){  
                    $.ajax({
                        url: 'ajax_dashboard_blocks.php',
                        success: function(data) {
                            //console.log(data); 
                            //{"total_camp":4,"sent":"25","avg_open_rate":"0.0 %","days_to_open":"1 Days"}
                            var json_obj = JSON.parse(data);
                            $(".email-sent .num").html(json_obj.sent);
                            $(".total-campaign .num").html(json_obj.total_camp);
                            $(".open-rate .num").html(json_obj.avg_open_rate);
                            $(".days-open .num").html(json_obj.days_to_open);
                        }
                    });
                }, 500);
                $("#loading_content").hide();
                $('#userlist_tbody').css({
                    opacity: 1
                });
                $('#userlist_tbody').html(data);
            }
        });
    }, 1500);
}

$(document).ready(function(){
    $(".anchor_delete").click(function(){        
        $("#de_campa").attr('value',$(this).attr('id'));
        $("#del_campaign").fadeIn("slow");
    });

    $("#del_topreject, #delete_reject").click(function(){
        $("#del_campaign").fadeOut("slow");

    });

    $("#delete_confirm").click(function(){
        $("#del_campaign").fadeOut("slow");
        $("#de_campa").attr('value');
        window.location.href = 'del_campaign.php?id='+$("#de_campa").attr('value');
        return true;
    });
});
</script>
<script src="<?php echo $base_url;?>js/DT_bootstrap.js"></script>
<link href="<?php echo $base_url.'css/DT_bootstrap.css';?>" rel="stylesheet" media="screen">
