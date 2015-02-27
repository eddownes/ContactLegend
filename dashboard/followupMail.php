<?php 
include('library/function.php');
$mode = 'Add';
if($_SESSION['nFollowupId'] != ''){
	$where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
	$data = getAnyData('*','followupEmail',$where,null,null);
	$mode = 'Update';
	$dScheduleTime = $data[0]['dScheduleTime'];
}
?>
<?php /*<script src="<?php echo $base_url?>library/FCKeditor/fckeditor.js"></script>
<script src="<?php echo $base_url?>library/FCKeditor/ckeditor.js"></script> */ ?>
<script src="<?php echo $base_url?>library/ckeditor/ckeditor.js"></script>
<form enctype="multipart/form-data" id="followupMail" name="followupMail" method="POST" action="addFollowupMail.php">
	<input type="hidden" name="nDraft" id="nDraft" value="0">
	<input type="hidden" name="mode" value="<?php echo $mode;?>">
	<input type="hidden" name="redirect_page" id="redirect_page" value="">
	<input type="hidden" name="nFollowupId" value="<?php echo $_SESSION['nFollowupId']?>">
	<input id="dScheduleTime" name="dScheduleTime" type="hidden" value="">
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
		<td align="center">
        	<input type="button" id="btn_back" class="button" value="Back"/>
            &nbsp;&nbsp;
            <input type="submit" id="btn_savedraft" class="button" value="Save as Draft"  />
            &nbsp;&nbsp;            
            <input type="submit" class="button" value="Save & Next" id="followupMailSubmit">
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

// CKEDITOR.replace( 'sSchEmailBody',{fullPage : true},{extraPlugins : 'placeholder'});
CKEDITOR.replace( 'sSchEmailBody' );

$('#followupMailSubmit').click(function(){
	$("#redirect_page").attr('value','step3.php');
	tinyMCE.triggerSave();
	$('#followupMail').validate();
	$('form').ajaxForm({
		 beforeSend: function() {
	        //status.empty();
		},
		success: function() {
	        var percentVal = '100%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    complete: function(xhr) {
	        //status.html(xhr.responseText);
	        window.location.href = 'step3.php';
	    }
	});
});
$("#btn_back").click(function(){
    var id = "<?php echo base64_encode($_SESSION['nCampaignid']);?>";
    window.location.href = "createCampaign.php?id="+id;
});

$("#btn_savedraft").click(function(){
	$("#redirect_page").attr('value','step2.php');
	$("#nDraft").attr('value','1');
	tinyMCE.triggerSave();
	$('#followupMail').validate();
	$('form').ajaxForm({
		 beforeSend: function() {
	        //status.empty();
		},
		success: function() {
	        var percentVal = '100%';
	        bar.width(percentVal)
	        percent.html(percentVal);
	    },
	    complete: function(xhr) {
	        window.location.href = 'step2.php';
	    }
	});
});

function mail_send(){
	var sEmailScript = 'sEmailScript = ' + tinymce.get('sEmailScript').getContent();
    sEmailScript = sEmailScript.replace(/&nbsp;/gi,'');
    $.ajax({
        type: "GET",
        data: sEmailScript,
        url: "sendmail.php",
        beforeSend: function() {
            $("#lodingdiv2").append('<img src="images/loader.gif">');
        },
        success: function(msg){
            $("#div_inbox").html();
            alert(msg);
            $("#lodingdiv2").remove();
        }
    });
}
</script>
