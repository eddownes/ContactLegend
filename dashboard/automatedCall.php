<?php 
include('library/function.php');
$mode = '';
if($_SESSION['nCallId'] != ''){
    $mode = 'Update';
    $where = "nCallId = '".$_SESSION['nCallId']."'";
    $automatedCalldata = getAnyData('*','calls',$where,null,null);
    $t = explode(":",$automatedCalldata[0]['dCallSchedTime']);
    $h = $t[0];
    $m = $t[1];
    $s = $t[2];
    $sCallScript = $automatedCalldata[0]['sCallScript'];
}
?>
<div id="textMsg"></div>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<form class="" id="automatedCall1" name="automatedCall1" method="POST" action="addCall.php" enctype="multipart/form-data" >
    <input type="hidden" name="mode" value="<?php echo $mode;?>">
    <input type="hidden" name="nDraft" id="nDraft" value="0">
    <input type="hidden" name="nCallId" value="<?php echo $_SESSION['nCallId']?>">
    <input type="hidden" name="nCampaignid" value="<?php echo $automatedCalldata[0]['nCampaignid']?>">
    <input type="hidden" name="redirect_page" id="redirect_page" value="">
    <input type="hidden" name="sendmail" id="sendmail" value="">
    <table width="100%" cellpadding="3" cellspacing="3" border="0">
    <tr><td><h4>Schedule Your Automated Call</h4></td></tr>
    <tr><td><hr/></td></tr>
    <tr>
        <td>
            Call Time :
            <div id="datetimepicker3" class="input-append">
                <input data-format="hh:mm:ss" required name="dCallSchedTime" value="<?php echo $automatedCalldata[0]['dCallSchedTime'];?>" type="text"></input>
                <span class="add-on">
                    <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                </span>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <input type="file" name="sCallScript" <?php if($sCallScript == ''){ ?> required <?php } ?> id="sCallScript"></br>
            <?php if($automatedCalldata[0]['sCallScript'] != ''){ echo "<b> Uploaded File Name :: ".$sCallScript." </b><br/><br/>";}?>
            (Supported Format : audio/mp3,audio/wav)
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
            <input type="button" id="btn_back" class="button" value="Back"/>
            &nbsp;&nbsp;
            <input type="submit" id="btn_savedraft_call" class="button" value="Save as Draft"  />
            &nbsp;&nbsp;            
            <input type="submit" id="callSubmit" name="callSubmit" class="button" value="Save & Next">
        </td>
    </tr>
    </table>    
    <!-- <output id="list"></output> -->
</form>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script type="text/javascript">

$(function() {
    $('#datetimepicker3').datetimepicker({
        pickDate: false
    });
});
$('#callSubmit').click(function(){
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
    $("#redirect_page").attr('value','step3.php');
    var val = $("#sCallScript").val();
    var ext = val.split(".")
    if(val != ''){              
        if((ext['1'] != 'mp3') && (ext['1'] != 'wav')){
            alert('Please add mp3 or wav files');
            return false;
        }
    }

    $('form').ajaxForm({
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            $("#lodingdiv").append('<img src="images/loader.gif">');            
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
           $("#lodingdiv").remove();
           window.location.href = 'step3.php';
        }
    });
});

$("#btn_back").click(function(){
    var id = "<?php echo base64_encode($_SESSION['nCampaignid']);?>";
    window.location.href = "createCampaign.php?id="+id;
});

$("#btn_savedraft_call").click(function(){

    $("#redirect_page").attr('value','step2.php');
    $("#nDraft").attr('value','1');
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
    var val = $("#sCallScript").val();
    var ext = val.split(".")
    
    if(val != ''){              
        if((ext['1'] != 'mp3') && (ext['1'] != 'wav')){
            alert('Please add mp3 or wav files');
            return false;
        }
    }

    $('form').ajaxForm({
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            $("#lodingdiv").append('<img src="images/loader.gif">');
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
           //status.html(xhr.responseText);
           $("#lodingdiv").remove();
           window.location.href = 'step2.php';
        }
    });
});
</script>
