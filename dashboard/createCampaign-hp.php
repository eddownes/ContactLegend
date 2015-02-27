<?php include('header.php');?>
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
						<input type="text" placeholder="Campaign Name" name="sCampaignname" id="sCampaignname" /></br>

						<div class="uplaod-list"><label for="sCampaignCSV">Upload Customer List</label>
						<input type="file" class="" name="sCampaignCSV" id="sCampaignCSV" /></br></div>

						<input type="text" placeholder="Email Subject" name="sEmailSubject" id="sEmailSubject" /></br>

						<textarea name="sEmailScript" id="sEmailScript" cols="40" rows="10"></textarea></br>
						<input type="submit" id="sCampaignsave" name="" value="Save" />
					</form>
				</div><!-- span12 -->
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');?>

<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script type="text/javascript">
        tinymce.init({selector:'textarea'});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>wysiwyg/bootstrap-wysihtml5.css">
<script type="text/javascript">
$(document).ready(function(){
	$('#createCmpgn').validate({
		rules:{
			sCampaignname:{
				required: true,
			},
			sCampaignCSV:{
				required: true,
			},
			sEmailSubject:{
				required: true,
			},
			sEmailScript:{
				required: true,
			}
		},
		messages:{
			sCampaignname: "Please Enter Campaign Name",
			sCampaignCSV : "Please Upload the CSV File",
			sEmailSubject : "Please Enter the Email Subject",
			sEmailScript : "Please Enter the Email Script"
		}
	});
});
</script>
