<script src="library/ckeditor/ckeditor.js"></script>
<?php 
include('header.php');
$page = "createcampaign";
$mode = 'Add';
$sEmailScript = 'Write Your Message Here *';
$campaignurl = 'createCampaign.php';
if($_REQUEST['id'] != ''){
	$campaignurl = 'createCampaign.phpcreateCampaign.php?id='.$_REQUEST['id'];
	$mode = 'Update';
	$nCampaignid = base64_decode($_REQUEST['id']);
	$where = "c.nCampaignid = '".$nCampaignid."'";
	$table = " campaigns as c Left join campaignEmails ce on ce.nCampaignid = c.nCampaignid";
	$user_data = getAnyData('c.sCampaignname,c.sCampaignCSV,ce.sEmailScript,ce.sEmailSubject,ce.nCEmaiId',$table,$where,null,null);
	$sEmailScript = $user_data[0]['sEmailScript'];
}
?>

<script type="text/javascript">
$(function() 
{
    $('.upload_file').change( function(){
            var value = $(this).val();
            var filename = value.replace(/^.*\\/, "");
 			//console.log(filename);
            $("#filename_browse").html(filename);
    });
});        
</script>

<style type="text/css">
.cke_wysiwyg_div{height: 150px !important;}
</style>
<div class="span12" id="content">
    <div class="row-fluid">
        <?php if(isset($_SESSION['success_msg'])){?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div><?php echo $_SESSION['success_msg'];?></div>
            </div>
        <?php } ?>
        <?php if(isset($_SESSION['error_msg'])){?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div><?php echo $_SESSION['error_msg'];?></div>
            </div>
        <?php } ?>
        <div class="c_main_container">
            <div class="/*block-content*/ collapse in">
                <div class="span12">
					<form class="c_form" enctype='multipart/form-data' id="createCmpgn" name="createCmpgn" method="POST" action="addCampaign.php">
						<input type="hidden" name="nDraft" id="nDraft" value="">
						<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $nCampaignid;?>">
						<input type="hidden" name="nCEmaiId" id="nCEmaiId" value="<?php echo $user_data[0]['nCEmaiId'];?>">
						<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>">
						<input type="hidden" name="filename" id="filename" value="<?php echo $user_data[0]['sCampaignCSV'];?>">
						<input type="hidden" name="draft" id="btn_draft" >
    					<table width="100%" cellpadding="5" cellspacing="5" border="0">		
                        	<tr><td><?php include_once("campaign_title.php");?></td></tr>
							<tr>
                                <td>
                                    <table class="internal" width="100%" cellpadding="5" cellspacing="5" border="0">						
                                        <tr><td><b>Campaign details</b></td></tr>
                                        <tr>
                                            <td class="labled td_pad_bottom">Campaign name</td>
                                        </tr>
                                        <tr>
                                            <td><input class="half_txt" type="text" name="sCampaignname" id="sCampaignname" value="<?php echo $user_data[0]['sCampaignname'];?>" /></br></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label style="margin-bottom: 12px;" class="full_lbl labled" for="sCampaignCSV">Recipient list</label>
                                                <input class="upload_file" type="file" class="" name="sCampaignCSV" id="sCampaignCSV" />
                                                <a href="javascript:void(0);" style="clear: both; float: left;" class="instead_select_button" onclick="document.getElementById('sCampaignCSV').click()" title="Click or Drag Image">Select list</a>
                                                
                                                <span style="margin-left: 10px;" id="filename_browse"></span>
                                                <span id="loading_img" style="display:none;">
                                                	<img src='images/ajax-loader3.gif' alt='Uploading'/>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php if($user_data[0]['sCampaignCSV']!= ''){ echo '<b> Uploaded File Name :: <a href="download_csv.php?file='.$user_data[0]['sCampaignCSV'].'">'.$user_data[0]['sCampaignCSV'] .'</a></b></br></br>';}?>
                                            </td>
                                        </tr>    
                                        <tr>
                                            <td>
                                                <span class="body_size">
                                                    Your list needs to be in CSV format.
                                                    <a href="<?php echo $upload_url;?>contactlegend.csv">Download the template</a>
                                                    to get started.
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
							<tr>
                            	<td class="tr_footer">
                                    <a href="javascript:void(0)" id="save_exit" class="save_exit">Save and exit</a>
                                    <button class="nxt_long btn btn-large btn-primary f_right" id="step1_next" name="draft" value="No" type="submit">Next</button>
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
var id = "<?php echo $_REQUEST['id']?>";
$(document).ready(function(){

	$("#save_exit").click(function(){
		$("#btn_draft").attr('value','Yes');
		if($("#filename_browse").html() != ""){
			$("#loading_img").show();
			setTimeout(function(){$("#createCmpgn").submit()},2500);
		} else {
			$("#createCmpgn").submit();
		}
	});

	$("#step1_next").click(function(){
		if($("#filename_browse").html() != ""){
			$("#loading_img").show();
		}
	});

    $.validator.addMethod("customvalidation",
		function (value, element) {
        	return /^[A-Za-z\d.'_ -]+$/.test(value);
    	},
    "Sorry, no special characters allowed");

	if(id == '')
	{
		$('#createCmpgn').validate({
			ignore: [],
            debug: false,
			rules:{
				sCampaignname:{
					required: true,
					customvalidation:true
				},
				sCampaignCSV:{
					required: true,
					extension: "csv",
				}
			},
			messages:{
				sCampaignname:  { 
					required : "Please Enter Campaign Name",
					customvalidation : "Please enter valid Campaign Name"
				},
				sCampaignCSV : {
					required : "Please Upload the CSV File",
					extension : "Please upload csv file only!!",
				}
			},
		}); 
	}
	else{

		$('#createCmpgn').validate({
			rules:{
				sCampaignname:{
					required: true,
					customvalidation:true
				},
				sCampaignCSV:{
					extension: "csv",
				}
			},
			messages:{
				sCampaignname:  { 
					required : "Please Enter Campaign Name",
					customvalidation : "Please enter valid Campaign Name"
				},
				sCampaignCSV : {
					extension : "Please upload csv file only!!"
				}
			}
		});
	}
});

</script>
