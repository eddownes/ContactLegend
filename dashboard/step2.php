<?php 
include('header.php');
if(!isset($_SESSION['nCampaignid'])){
    header('location:createCampaign.php');
    exit;
}else{

    $where = "nCampaignid = '".$_SESSION['nCampaignid']."'";

    $followupdata = getAnyData('nFollowupId,dScheduleTime','followupEmail',$where,null,null);
    $_SESSION['dScheduleTime'] = $followupdata[0]['dScheduleTime'];
    $_SESSION['nFollowupId'] = $followupdata[0]['nFollowupId'];

    $calldata = getAnyData('nCallId,dCallSchedTime','calls',$where,null,null);
    $_SESSION['dCallSchedTime'] = $calldata[0]['dCallSchedTime'];
    $_SESSION['nCallId'] = $calldata[0]['nCallId'];

    $directmaildata = getAnyData('nDirectEmailId,semailletter','directemail',$where,null,null);
    $_SESSION['semailletter'] = $directmaildata[0]['semailletter'];
    $_SESSION['nDirectEmailId'] = $directmaildata[0]['nDirectEmailId'];
    
}
?>
<style type="text/css">
    .cke_wysiwyg_div{
        height: 150px !important;
    }
</style>
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
                <div class="muted pull-left">Follow Up Action (Step 2)</div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">
                	<div class="automatedCall" id="automatedCall">
                        <?php if($_SESSION['dCallSchedTime'] != ''){ ?>
                        	<a href="javascript:void(0)" onclick="campaignData('automatedCall')">
                                <?php echo 'Voicemail - Set For '.$_SESSION['dCallSchedTime'].'</br>Voicemail Script '.$_SESSION['sCallScript'];?>
                            </a>
                        <?php } else {?>
                            <a href="javascript:void(0)" onclick="campaignData('automatedCall')">
                                Schedule Your Automated Call
                            </a>
                        <?php } ?>
                	</div>
                    <div class="directMail" id="directMail">
                        <?php if($_SESSION['semailletter'] != ''){ ?>
                            <a href="javascript:void(0)" onclick="campaignData('directMail')">
                                <?php echo 'Direct Mail - 48 Hrs</br>';?>
                            </a>
                        <?php } else {?>
                            <a href="javascript:void(0)" onclick="campaignData('directMail')">
                                Schedule Direct Mail
                            </a>
                        <?php } ?>
                    </div>
                    <div class="followupMail" id="followupMail">
                        <?php if($_SESSION['dScheduleTime'] != ''){ ?>
                            <a href="javascript:void(0)" onclick="campaignData('followupMail')">
                                <?php echo "Follow Up Mail - ".$_SESSION['dScheduleTime'];?>
                            </a>
                        <?php } else {?>
                            <a href="javascript:void(0)" onclick="campaignData('followupMail')">
                                Schedule Follow Up Email
                            </a>
                        <?php } ?>
                    </div>
                	<div id="div_inbox"></div>
                    <!--<div id="btn_button">
                        <input type="button" class="button" value="Save & Next" onclick="window.location='step3.php'" style="float:right;margin-right:265px;" >
                        <input type="button" id="btn_savedraft1" class="button" value="Save as Draft" style="float:right;margin-right:10px;" />
                        <input type="button" id="btn_back1" class="button" value="Back" style="float:right;margin-right:10px;"  />
                    </div>-->
                </div>
			</div>

		</div>
	</div>
</div>
<?php include('footer.php');?>
<script type="text/javascript">
function campaignData(file){
    $("#btn_button").hide();
    $.ajax({
	   type: "POST",
		url: file+".php",
		success: function(msg){
		    $("#div_inbox").html();
		    $("#div_inbox").html(msg);
        }
    });
}

$("#btn_savedraft1").click(function(){
    window.location.href = 'step2.php';
});

$("#btn_back1").click(function(){
    var id = "<?php echo base64_encode($_SESSION['nCampaignid']);?>";
    window.location.href = "createCampaign.php?id="+id;
});
</script>
