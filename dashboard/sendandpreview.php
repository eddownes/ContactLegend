<?php 
include('header.php');
$page = 'previewandsend';
$campaign_title ='';
$campaign_csv ='';
$sEmailSubject = '';
$id = $_REQUEST['id'];

if($_REQUEST['id'] != ''){
    $nCampaignid = base64_decode($_REQUEST['id']);
    $where = "c.nCampaignid = '".$nCampaignid."'";
    $table = " campaigns as c Left join campaignEmails ce on ce.nCampaignid = c.nCampaignid";
    $campaign_data = getAnyData('c.sCampaignname,c.sCampaignCSV,ce.sEmailSubject',$table,$where,null,null);
    $campaign_title = $campaign_data[0]['sCampaignname'];
    $campaign_csv = $campaign_data[0]['sCampaignCSV'];
    $sEmailSubject = $campaign_data[0]['sEmailSubject'];
    if($_SESSION['nCallId'] != ''){
        $t = explode(":",$_SESSION['dCallSchedTime']);
        $dCallSchedTime = $_SESSION['dCallSchedTime'];
        $h = $t[0];
        if(($h > '0') && ($h < '10' )){ $h = '0'.$h;}
        $m = $t[1];
        if(($m > '0') && ($m < '10' )){ $m = '0'.$m;}
        $s = $t[2];
    }
    if($_SESSION['nFollowupId'] != ''){
        $where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
        $data = getAnyData('dScheduleTime','followupEmail',$where,null,null);
        $dScheduleTime = explode(":",$data[0]['dScheduleTime']);
        $mail_min = $dScheduleTime[1];
        $mail_hrs = $dScheduleTime[0];
        #if(($mail_hrs > '0') && ($mail_hrs < '10' )){ $mail_hrs = '0'.$mail_hrs;}
        #if(($mail_min > '1') && ($mail_min < '10' )){ $mail_min = '0'.$mail_min;}
    }
}
?>
<div id="content" class="span12">
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
        <div class="c_main_container">
            <?php include_once("campaign_title.php");?>
            <div class="c_main_content">
                <h2>Preview and send</h2>
                <p class="c_review">Please review your campaign before sending.</p>
                <div class="detail c_blocks">
                    <div class="c_blocks_header">
                        <span class="left">Campaign details</span>
                        <a href="createCampaign.php?id=<?php echo $id;?>" class="edit">Edit</a>
                    </div>
                    <p class="c_blocks_content"><?php echo $campaign_title;?></p>
                    <a href="javascript:void(0)" class="c_blocks_anchor"><?php echo $campaign_csv;?></a>
                </div>
                <div class="intro c_blocks">
                    <div class="c_blocks_header">
                        <span class="left">Intro message</span>
                        <a href="intromessage.php?id=<?php echo $id;?>" class="edit">Edit</a>
                    </div>
                    <p class="c_blocks_content"><?php echo $sEmailSubject;?></p>
                </div>
                <div class="c_blocks actions">
                    <div class="c_blocks_header">
                        <span class="left">Follow up action</span>
                        <a href="followupaction.php?id=<?php echo $id;?>" class="edit">Edit</a>
                    </div>
                   
                    <div class="c_block_wrapper">
                        <?php if($_SESSION['nCallId'] != ''){?>
                        <span class="left"><img alt="" src="images/c_wright.png">Phone message</span> <span class="right"><?php echo $h?> hours and <?php echo $m?> minutes after intro message is opened</span>
                        <?php } ?>
                        <?php if($_SESSION['nDirectEmailId'] != ''){?>
                        <span class="left"><img alt="" src="images/c_wright.png">Direct mail</span> <span class="right"><!-- 0 days and 3 hours after intro message is opened --></span>
                        <?php } ?>
                        <?php if($_SESSION['nFollowupId'] != ''){?>
                        <span class="left"><img alt="" src="images/c_wright.png">Follow up email message</span> <span class="right"><?php echo $mail_hrs?> hours and <?php echo $mail_min?> minutes after intro message is opened</span>
                        <?php } ?>
                    </div>
                </div>
                <div class="c_blocks">
                    <div class="c_blocks_header">
                        <span class="left">IMPORTANT:</span>
                    </div> 
                    <div class="c_block_wrapper">
                        <p>Each of your 3rd Party Followup Integrations have limited credits to send e-mails, phone calls and direct mail each day.</p>

                        <p>(For instance, Turbo SMTP basic package allows for 200 e-mails per day.)</p>

                        <p>Please login to each of your providers (SMTP, Voice Broadcast, Direct Mail) to make sure each account has enough credits to fulfill this campaign.</p>

                        <p>Running out of credits with a provider will result in your campaign not being completed. You can always upgrade or fund your 3rd party accounts.</p>
                        
                        <p><input type="checkbox" name="camp_checked" id="camp_checked" value="1" /> I have verified that my 3rd party accounts have enough credits to fulfill this campaign.</p>
                    </div>                  
                </div>
            </div>
            <div class="c_main_footer clearfix">
                <a href="javascript:void(0);" id="save_exit" class="save_exit">Save and exit</a>
                <button class="btn btn-large btn-primary f_right preview_bigger" id="send_camp" type="button">Send Campaign</button>
            </div>            
        </div>
    </div>
</div>
<?php include('footer.php');?>
<script type="text/javascript">
$(function() {
    $("#save_exit").click(function(){
        window.location.href='userdashboard.php';
    });

    $("#send_camp").click(function(){
        if($('#camp_checked').is(':checked')) {
            window.location.href='addsendandpreview.php?id=<?php echo $_REQUEST["id"]; ?>&f=<?php echo $_REQUEST["f"]; ?>';
        } else {
            alert("Please verify that you checked 3rd party account by checking the checkbox.");
        }
    });
});
</script>