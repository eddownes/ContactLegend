<script src="library/ckeditor/ckeditor.js"></script>
<?php 
include('header.php');
$page = "intromessage";
$mode = 'Add';
$sEmailScript = 'Write Your Message Here *';
$f = base64_encode('Yes');
if(isset($_REQUEST['f'])){
    $f = base64_encode('No');
}
if($_REQUEST['id'] != ''){
	$mode = 'Update';
	$nCampaignid = base64_decode($_REQUEST['id']);
	$where = "c.nCampaignid = '".$nCampaignid."'";
	$table = " campaigns as c Left join campaignEmails ce on ce.nCampaignid = c.nCampaignid";
	$user_data = getAnyData('c.sCampaignname,c.sCampaignCSV,ce.sEmailScript,ce.sEmailSubject,ce.nCEmaiId',$table,$where,null,null);
	$sEmailScript = $user_data[0]['sEmailScript'];
}
?>
<style type="text/css">
    .cke_wysiwyg_div{
        height: 150px !important;
    }
</style>
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
        <div class="c_main_container">
            <div class="/*block-content*/ collapse in">
                <div class="span12">
					<form enctype='multipart/form-data' class="c_form" id="createCmpgn" name="createCmpgn" method="POST" action="addintromessage.php">
                        <input type="hidden" name="nDraft" id="nDraft" value="">
						<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $nCampaignid;?>">
						<input type="hidden" name="nCEmaiId" id="nCEmaiId" value="<?php echo $user_data[0]['nCEmaiId'];?>">
						<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>">
						<input type="hidden" name="filename" id="filename" value="<?php echo $user_data[0]['sCampaignCSV'];?>">
                        <input type="hidden" name="f" id="f" value="<?php echo $f;?>">
                        <input type="hidden" name="draft" id="btn_draft" value="No">
						<table width="100%" cellpadding="3" cellspacing="3" border="0">
                        <tr><td><?php include_once("campaign_title.php");?></td></tr>
                        <tr>
                            <td>
                                <table class="internal" width="100%" cellpadding="3" cellspacing="3" border="0">
                                <tr><td><b>Intro Message</b></td></tr>
                                <tr>
                                    <td>
                                        <label class="full_lbl labled" for="">Subject line</label>
                                        <input class="half_txt" type="text" placeholder="Email Subject*" name="sEmailSubject" id="sEmailSubject" value="<?php echo $user_data[0]['sEmailSubject'];?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td_pad_bottom"><label class="full_lbl labled" for="">Message</label></td>
                                </tr>    
                                <tr>
                                    <td>
                                        <textarea name="sEmailScript" id="sEmailScript" cols="40" rows="50" placeholder="Write Your Message Here *"><?php echo $user_data[0]['sEmailScript'];?></textarea>
                                    </td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr_footer">
                                <a href="javascript:void(0)" id="save_exit" class="save_exit">Save and exit</a>
                                <button class="nxt_long btn btn-large btn-primary f_right" type="submit">Next</button>
                            </td>
                        </tr>                   
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');?>
<script src="<?php echo $base_url;?>js/additional-methods.js"></script>
<script type="text/javascript">
//CKEDITOR.replace( 'sEmailScript',{toolbar : 'Basic' });
CKEDITOR.replace( 'sEmailScript' );

$("#save_exit").click(function(){
    $("#btn_draft").attr('value','Yes');
    $("#createCmpgn").submit();
});

var id = "<?php echo $_REQUEST['id']?>";
$(document).ready(function(){	
	$('#createCmpgn').validate({
		rules:{
			sEmailSubject:{
				required: true,
			},
			sEmailScript:{
            	required: false,
            }
		},
		messages:{			
			sEmailSubject : "Please Enter the Email Subject",
			sEmailScript :{
				required: "Please Enter the Email Script"				
            }
		}
	});
});
</script>