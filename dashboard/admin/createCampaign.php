<script src="library/FCKeditor/fckeditor.js"></script>
<script src="library/FCKeditor/ckeditor.js"></script>
<?php 
include('header.php');
$mode = 'Add';
$sEmailScript = 'Write Your Message Here *';
if($_REQUEST['id'] != ''){
	$mode = 'Update';
	$nCampaignid = base64_decode($_REQUEST['id']);
	$where = "c.nCampaignid = '".$nCampaignid."'";
	$table = " campaigns as c Left join campaignEmails ce on ce.nCampaignid = c.nCampaignid";
	$user_data = getAnyData('c.sCampaignname,c.sCampaignCSV,ce.sEmailScript,ce.sEmailSubject,ce.nCEmaiId',$table,$where,null,null);
	$sEmailScript = $user_data[0]['sEmailScript'];
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
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">Create A Campaign</div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">
					<form enctype='multipart/form-data' class="" id="createCmpgn" name="createCmpgn" method="POST" action="addCampaign.php">
						<input type="hidden" name="nDraft" id="nDraft" value="">
						<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $nCampaignid;?>">
						<input type="hidden" name="nCEmaiId" id="nCEmaiId" value="<?php echo $user_data[0]['nCEmaiId'];?>">
						<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>">
						<input type="hidden" name="filename" id="filename" value="<?php echo $user_data[0]['sCampaignCSV'];?>">
						<table width="100%" cellpadding="3" cellspacing="3" border="0">
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td>
								<input type="text" placeholder="Campaign Name*" name="sCampaignname" id="sCampaignname" value="<?php echo $user_data[0]['sCampaignname'];?>" /></br>
								<span style="color:#A3A3A3">(This name is for your reference only) </span>
							</td>
						</tr>
						<tr><td><hr/></td></tr>
						<tr>
							<td>
								<label for="sCampaignCSV">Upload Customer List*</label>
								<input type="file" class="" name="sCampaignCSV" id="sCampaignCSV" />&nbsp;&nbsp;
								</br></br>
								<?php if($user_data[0]['sCampaignCSV']!= ''){ echo '<b> Uploaded File Name :: '.$user_data[0]['sCampaignCSV'] .'</b></br></br>';}?>
								<a style="float:left" href="<?php echo $upload_url;?>contactlegend.csv">Download Sample CSV From Here</a><br/>
								<span style="color:#A3A3A3">
									(Please make sure that the first row in sample CSV should not be deleted.<br/>
									If you are using your CSV then make sure that the first raw  is as per sample CSV.)
								</span>
							</td>
						</tr>
						<tr><td><hr/></td></tr>
						<tr>
							<td>
								<input type="text" placeholder="Email Subject*" name="sEmailSubject" id="sEmailSubject" value="<?php echo $user_data[0]['sEmailSubject'];?>" />
							</td>
						</tr>
						<tr><td>Write Your Message Here *</td></tr> 
						<tr>
							<td>
								<textarea name="sEmailScript" id="sEmailScript" cols="40" rows="10" placeholder="Write Your Message Here *"><?php echo $user_data[0]['sEmailScript'];?></textarea>
							</td>
						</tr>
						<tr><td><hr/></td></tr>
						<tr>
							<td align="right">
								<input type="button" id="btn_savedraft" class="button" name="" value="Save as Draft" />
								&nbsp;&nbsp;&nbsp;
								<input type="button" id="btn_savenext" class="button" value=" Save & Next" />
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
$("#btn_savenext").click(function(){
	$("#createCmpgn").submit();
});

CKEDITOR.replace( 'sEmailScript',{fullPage : true},{extraPlugins : 'placeholder'});

$("#btn_savedraft").click(function(){
	$("#nDraft").attr('value','1');
	$("#createCmpgn").submit();
});

var id = "<?php echo $_REQUEST['id']?>";
$(document).ready(function(){
	
	if(id == '')
	{
		$('#createCmpgn').validate({
			ignore: [],
            debug: false,
			rules:{
				sCampaignname:{
					required: true,
				},
				sCampaignCSV:{
					required: true,
					extension: "csv",
				},
				sEmailSubject:{
					required: true,
				},
				sEmailScript:{
					required: false,
					/*required: function(textarea) {
						CKEDITOR.instances[textarea.id].updateElement(); // update textarea
			          	var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
			          	return editorcontent.length === 0;
                	}*/
                }
			},
			messages:{
				sCampaignname: "Please Enter Campaign Name",
				sCampaignCSV : {
					required : "Please Upload the CSV File",
					extension : "Please upload csv file only!!",
				},
				sEmailSubject : "Please Enter the Email Subject",
				sEmailScript :{
					required: "Please Enter the Email Script",
                }
			},
		}); 
	}
	else{

		$('#createCmpgn').validate({
			rules:{
				sCampaignname:{
					required: true,
				},
				sEmailSubject:{
					required: true,
				},
				sCampaignCSV:{
					extension: "csv",
				},
				sEmailScript:{
                	required: false,
                }
			},
			messages:{
				sCampaignname: "Please Enter Campaign Name",
				sEmailSubject : "Please Enter the Email Subject",
				sCampaignCSV : {
					extension : "Please upload csv file only!!"
				},
				sEmailScript :{
					required: "Please Enter the Email Script"				
                }
			}
		});
	}


	//deal with copying the ckeditor text into the actual textarea
	/*CKEDITOR.on('instanceReady', function () {
	    $.each(CKEDITOR.instances, function (instance) {
	        CKEDITOR.instances[instance].document.on("keyup", CK_jQ);
	        CKEDITOR.instances[instance].document.on("paste", CK_jQ);
	        CKEDITOR.instances[instance].document.on("keypress", CK_jQ);
	        CKEDITOR.instances[instance].document.on("blur", CK_jQ);
	        CKEDITOR.instances[instance].document.on("change", CK_jQ);
	    });
	});

	function CK_jQ() {
	    for (instance in CKEDITOR.instances) {
	        CKEDITOR.instances[instance].updateElement();
	    }
	}*/

});


</script>
