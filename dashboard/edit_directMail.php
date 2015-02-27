<?php 
$mode = 'Add';
if($_SESSION['nDirectEmailId'] != ''){
	$mode = 'Update';
	$where = "nDirectEmailId = '".$_SESSION['nDirectEmailId']."'";
	$data = getAnyData('*','directemail',$where,null,null);
	$sDirectEmailBody = $data[0]['sDirectEmailBody'];
}
$res_directmail = check_data_campaign($_SESSION['nCampaignid']);
?>

<form name="directMail" id="directMail" method="POST" action="addDirectmail.php" enctype='multipart/form-data'>
	<input type="hidden" name="mode" value="<?php echo $mode;?>">
	<input type="hidden" name="nDraft" id="nDraft" value="0">
	<input type="hidden" name="redirect_page" id="redirect_page" value="">
	<input type="hidden" name="nDirectEmailId" value="<?php echo $_SESSION['nDirectEmailId']?>">
	<input type="hidden" name="sendmail2" id="sendmail2" value="">
	<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $_SESSION['nCampaignid']?>">
    <input type="hidden" name="sEmailScript3" id="sEmailScript3" value="">
    <input type="hidden" id="has_directmailrecord" value="<?php echo $res_directmail;?>">
	<table width="100%" cellpadding="3" cellspacing="3" border="0">
	<tr><td><h4>Schedule Your Direct Email</h4></td></tr>
	<tr><td><hr/></td></tr>
	<tr>
		<td>
			<input type="file" name="sDirectEmailBody" <?php if($sDirectEmailBody == ''){ ?> required <?php } ?> id="sDirectEmailBody"></br>
            <?php if($sDirectEmailBody != ''){ echo "<b> Uploaded File Name :: ".$sDirectEmailBody." </b><br/><br/>";}?>
            (Supported Format : pdf)
            <br/>
            <div class="progress">
                <div class="bar bar_mail" id="bar"></div >
                <div class="percent percent_mail" id="percent">0%</div >        
                <div id="status" class="status status_mail"></div>         
            </div>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="right">
			<div id="lodingdiv1"></div>
			<div id="lodingdiv_1"></div>
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" id="btn_back2" class="button" value="Back"/>
            &nbsp;&nbsp;
            <input type="submit" id="directMailSubmit" class="button" value="Save as Draft"  />
            &nbsp;&nbsp;            
            <input type="submit" id="edit_directMailSubmit" class="button" value="Start Campaign">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	</table>	
</form>
<script src="http://malsup.github.com/jquery.form.js"></script>

<script type="text/javascript">

$('#directMailSubmit').click(function(){
	var bar1 = $(".bar");
	var percent1 = $('.percent');
	var status1 = $('#status');
	var val = $("#sDirectEmailBody").val();
	var ext = val.split(".")
	if(val != ''){	    		
		if(ext['1'] != 'pdf'){
			alert('Please add pdf files');
			return false;
		}
	}
	$('form').ajaxForm({
	    beforeSend: function() {
	    	status1.empty();
            var percentVal = '0%';
            bar1.width(percentVal)
            percent1.html(percentVal);
	       	$("#lodingdiv1").append('<img src="images/loader.gif">');
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
    	    var percentVal = percentComplete + '%';
            bar1.width(percentVal)
            percent1.html(percentVal);
	    },
	    success: function() {
	    	var percentVal = '100%';
	        bar1.width(percentVal)
	        percent1.html(percentVal);
	    },
	    complete: function(xhr) {
	    	alert("Campaign saved as draft successfully");	
	       	$("#lodingdiv1").remove();	       
	    }
	});
});

$("#btn_back2").click(function(){
    window.location.href = "step2.php";
});

$('#edit_directMailSubmit').click(function(){

	$("#sDirectEmailBody").removeAttr('required');

	var submit = $("#has_directmailrecord").val();
    var file = $("#sDirectEmailBody").val();
    var val = $("#sDirectEmailBody").val();
	var ext = val.split(".")
    if((submit == 0) && (file == '')){
        alert('You have to select atleast one follow up action');
        return false;
    }

	$("#redirect_page").attr('value','myCampaigns.php');
	$("#sendmail2").attr('value','Yes');

    var sEmailScript = CKEDITOR.instances.sEmailScript.getData();
	sEmailScript = sEmailScript.replace(/&nbsp;/gi,'');

    $("#sEmailScript3").attr('value',sEmailScript);

    var bar1 = $('.bar');
	var percent1 = $('.percent');
	var status1 = $('#status');
	if(val != ''){	    		
		if(ext['1'] != 'pdf'){
			alert('Please add pdf files');
			return false;
		}
	}	

	$('form').ajaxForm({
	    beforeSend: function() {
	    	status1.empty();
            var percentVal = '0%';
            bar1.width(percentVal)
            percent1.html(percentVal);		
	        $("#lodingdiv_1").append('<img src="images/loader.gif">');
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	    	var percentVal = percentComplete + '%';
            bar1.width(percentVal)
            percent1.html(percentVal);
	    },
	    success: function() {
	    	var percentVal = '100%';
	        bar1.width(percentVal)
	        percent1.html(percentVal);
	    },
	    complete: function(xhr) {
	    	alert("Campaign started successfully");
	       	$("#lodingdiv_1").remove();	       
	        window.location.href = "myCampaigns.php";     
	    }
	});
});

</script>
