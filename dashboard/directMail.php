<?php 
include('library/function.php');
$mode = 'Add';
if($_SESSION['nDirectEmailId'] != ''){
	$mode = 'Update';
	$where = "nDirectEmailId = '".$_SESSION['nDirectEmailId']."'";
	$data = getAnyData('*','directemail',$where,null,null);
	$sDirectEmailBody = $data[0]['sDirectEmailBody'];
}
?>

<form name="directMail" id="directMail" method="POST" action="addDirectmail.php" enctype='multipart/form-data'>
	<input type="hidden" name="mode" value="<?php echo $mode;?>">
	<input type="hidden" name="nDraft" id="nDraft" value="0">
	<input type="hidden" name="redirect_page" id="redirect_page" value="">
	<input type="hidden" name="nDirectEmailId" value="<?php echo $_SESSION['nDirectEmailId']?>">
	<table width="100%" cellpadding="3" cellspacing="3" border="0">
	<tr><td><h4>Schedule Your Direct Email</h4></td></tr>
	<tr><td><hr/></td></tr>
	<!-- <tr>
		<td>
			<textarea rows="8" placeholder="Write Your Message Here *" cols="40" style="width:60%" id="sDirectEmailBody" name="sDirectEmailBody"><?php #echo $data[0]['sDirectEmailBody']?></textarea>
		</td>
	</tr> -->
	<tr>
		<td>
			<input type="file" name="sDirectEmailBody" <?php/* if($sDirectEmailBody == ''){ ?> required <?php } */?> id="sDirectEmailBody"></br>
            <?php if($sDirectEmailBody != ''){ echo "<b> Uploaded File Name :: ".$sDirectEmailBody." </b><br/><br/>";}?>
            (Supported Format : pdf)
            <br/>
            <div class="progress">
                <div class="bar"></div >
                <div class="percent">0%</div >        
                <div id="status"></div>         
            </div>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td align="center">
			<div id="lodingdiv_direct"></div>
			<div id="lodingdiv_direct1"></div>
			<input type="button" id="btn_back" class="button" value="Back"/>
            &nbsp;&nbsp;
            <input type="submit" id="btn_savedraft" class="button" value="Save as Draft"  />
            &nbsp;&nbsp;            
            <input type="submit" name="directMailSubmit" class="button" id="directMailSubmit" value="Save and Next">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	</table>	
</form>
<script src="http://malsup.github.com/jquery.form.js"></script>

<script type="text/javascript">
$('#directMailSubmit').click(function(){

	var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
	$("#redirect_page").attr('value','step3.php');
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
			status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            $("#lodingdiv_direct1").append('<img src="images/loader.gif">');
        },
        uploadProgress: function(event, position, total, percentComplete) {
        	var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
	    success: function() {
	    	var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
	    },
	    complete: function(xhr) {
			$("#lodingdiv_direct1").remove();
	       	window.location.href = 'step3.php';	    		       
	    }
	});
});

$("#btn_back").click(function(){
    var id = "<?php echo base64_encode($_SESSION['nCampaignid']);?>";
    window.location.href = "createCampaign.php?id="+id;
});

$("#btn_savedraft").click(function(){
	var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
	$("#nDraft").attr('value','1');
	$("#redirect_page").attr('value','step2.php');
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
			status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            $("#lodingdiv_direct1").append('<img src="images/loader.gif">');           
        },
        uploadProgress: function(event, position, total, percentComplete) {
        	var percentVal = percentComplete + '%';
        	bar.width(percentVal)
        	percent.html(percentVal);
        },
	    success: function() {
	    	var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
	    },
	    complete: function(xhr) {	    	
			$("#lodingdiv_direct1").remove();
	       	window.location.href = 'step2.php';	
		}
	});
});

</script>
