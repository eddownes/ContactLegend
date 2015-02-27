<?php 
$mode = 'Add';
if($_SESSION['nFollowupId'] != ''){
	$where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
	$data = getAnyData('*','followupEmail',$where,null,null);
	$mode = 'Update';
	$dScheduleTime = $data[0]['dScheduleTime'];
}
$res_followup = check_data_campaign($_SESSION['nCampaignid']);
?>
<form enctype="multipart/form-data" id="followupMail" name="followupMail" method="POST" action="addFollowupMail.php">
	<input type="hidden" name="nDraft" id="nDraft" value="0">
	<input type="hidden" name="redirect_page" id="redirect_page" value="">
	<input type="hidden" name="mode" value="<?php echo $mode;?>">
	<input type="hidden" name="nFollowupId" value="<?php echo $_SESSION['nFollowupId']?>">
	<input type="hidden" name="sendmail1" id="sendmail1" value="">
	<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $_SESSION['nCampaignid']?>">
    <input type="hidden" name="sEmailScript2" id="sEmailScript2" value="">
	<input id="dScheduleTime" name="dScheduleTime" type="hidden" value="">
	<input type="hidden" id="has_followuprecord" value="<?php echo $res_followup;?>">
	<table width="100%" cellpadding="3" cellspacing="3" border="0">
	<tr><td><h4>Schedule Your Follow Up Email</h4></td></tr>
	<tr><td><hr/></td></tr>
	<tr>
		<td>
			<ul class="timeTabs">
				<li class="timeLink"><a id="link1" href="javascript:void(0)" <?php if($dScheduleTime == '00:05:00'){?> class='active' <?php }?> onclick="passValue('5','link1')">5 Min</a></li>
				<li class="timeLink"><a id="link2" href="javascript:void(0)" <?php if($dScheduleTime == '01:00:00'){?> class='active' <?php }?> onclick="passValue('60','link2')">1 Hr</a></li>
				<li class="timeLink"><a id="link3" href="javascript:void(0)" <?php if($dScheduleTime == '12:00:00'){?> class='active' <?php }?> onclick="passValue('720','link3')">12 Hrs</a></li>
				<li class="timeLink"><a id="link4" href="javascript:void(0)" <?php if($dScheduleTime == '24:00:00'){?> class='active' <?php }?> onclick="passValue('1440','link4')">24 Hrs</a></li>
			</ul>
			<p class="para1">After customer opens initial email</br>Default 5 Mins</p>
		</td>
	</tr>
	<tr>
		<td>
			<input id="sSchEmailSubject" name="sSchEmailSubject" value="<?php echo $data[0]['sSchEmailSubject'];?>" required type="text" placeholder="Subject Line">
		</td>
	</tr>
	<tr>
		<td>
			<textarea cols="15" rows="10" placeholder="Write Your Message Here *" name="sSchEmailBody" id="sSchEmailBody"><?php echo $data[0]['sSchEmailBody'];?></textarea>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="right">
			<div id="lodingdiv2"></div>
			<div id="lodingdiv_2"></div>
        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" id="btn_back1" class="button" value="Back"/>
            &nbsp;&nbsp;
            <input type="submit" id="followupMailSubmit" class="button" value="Save as Draft"  />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" id="edit_followupMailSubmit" class="button" value="Start Campaign" >
		</td>
	</tr>
	</table>
</form>
<div id="success"></div>
<script type="text/javascript">
function passValue(value, id){
    $('#link1').removeClass('active');
	$('#link2').removeClass('active');
	$('#link3').removeClass('active');
	$('#link4').removeClass('active');
	$('#dScheduleTime').attr('value', value);
	$('#'+id).addClass('active');
}

$('#followupMailSubmit').click(function(){
	$('#followupMail').validate();
	$("#redirect_page").attr('value','step3.php');
	$('form').ajaxForm({
		beforeSend: function() {
	        $("#lodingdiv2").append('<img src="images/loader.gif">');
		},
		success: function(msg) {},
	    complete: function(xhr) {
	        alert("Campaign saved as draft successfully");
	        $("#lodingdiv2").remove();
	    }
	});
});
$("#btn_back1").click(function(){
    window.location.href = "step2.php";
});

// CKEDITOR.replace( 'sSchEmailBody',{fullPage : true},{extraPlugins : 'placeholder'});
CKEDITOR.replace( 'sSchEmailBody' );

$('#edit_followupMailSubmit').click(function(){
	
	$("#sSchEmailSubject").removeAttr('required');
	var submit = $("#has_followuprecord").val();
    var file = $("#sSchEmailSubject").val();

    if((submit == 0) && (file == '')){
        alert('You have to select atleast one follow up action');
        return false;
    }
    
	$("#redirect_page").attr('value','myCampaigns.php');
	$("#sendmail1").attr('value','Yes');
    
    var sEmailScript = CKEDITOR.instances.sEmailScript.getData();
	sEmailScript = sEmailScript.replace(/&nbsp;/gi,'');

    $("#sEmailScript2").attr('value',sEmailScript);
    
	$('form').ajaxForm({
		 beforeSend: function() {
	        $("#lodingdiv2").append('<img src="images/loader.gif">');
		},
		success: function(msg) {
			 
		},
	    complete: function(xhr) {
	    	alert("Campaign started successfully");
	        $("#lodingdiv2").remove();
	        window.location.href = 'myCampaigns.php';
	    }
	});
});

</script>
