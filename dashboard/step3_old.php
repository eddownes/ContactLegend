<?php 
include('header.php');
$where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
$champaign_data = getAnyData('*','campaignEmails',$where,null,null);
?>
<link rel="stylesheet" href="css/tabs.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/tabs.js"></script>
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
            <div class="navbar navbar-inner block-header"><div class="muted pull-left">Follow Up Action (Step 3)</div></div>
            <div id="tab-container">  
                <div id="campaigns-tab" class="tab-content active">
                    <h1><center>Create New Campaign Review</center></h1> 
                    <h5><?php echo nl2br($champaign_data[0]['sEmailSubject']);?></h5>
                    <table width="100%" height="100%">
                        <tr>
                            <td width="48%" valign="top">
                                <table width="100%">
                                <tr><td><textarea id="sEmailScript" name="sEmailScript"><?php echo nl2br($champaign_data[0]['sEmailScript']);?></textarea></td></tr>
                                <tr><td width="2%">&nbsp;</td></tr>
                                <tr><td><input type="button" class="btn-primary" value="Send Test Mail" id="testmailbutton"></td></tr>
                                </table>
                            </td> 
                            <td width="2%">&nbsp;</td> 
                            <td width="50%" valign="top">
                                <table width="100%">
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" onclick="campaignData('edit_automatedCall')">
                                            <?php 
                                            if($_SESSION['dCallSchedTime']!=''){
                                                echo 'Voicemail - Set For '.$_SESSION['dCallSchedTime'].'</br>Voicemail Script '.$_SESSION['sCallScript'];
                                            }
                                            else{echo 'Schedule Your Automated Call';}?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" onclick="campaignData('edit_directMail')">
                                            <?php if($_SESSION['semailletter']!= ''){echo 'Direct Mail - 48 Hrs</br>'.$_SESSION['semailletter'];}else{echo "Schedule Direct Mail";}?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" onclick="campaignData('edit_followupMail')">
                                            <?php if($_SESSION['dScheduleTime']!=''){echo "Follow Up Mail - ".$_SESSION['dScheduleTime'];}else {echo "Schedule Follow Up Email"; }?>
                                        </a>
                                    </td>
                                </tr>
                                <tr><td id="div_inbox"></td></tr>
                                </table>
                            </td> 
                        </tr>
                        <tr><td colspan="3"><hr/></td></tr>
                        <tr>
                            <td align="right" id="lodingdiv"></td>
                            <td align="right" colspan="2"><input class="btn-primary" type="button" value="Send Mail" id="mailbutton"></td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                    </table>   
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    tinymce.init({selector:'textarea'});
    $("#testmailbutton").click(function(){
        var sEmailScript = 'sEmailScript = ' + tinymce.get('sEmailScript').getContent();
        sEmailScript = sEmailScript.replace(/&nbsp;/gi,'');
        $.ajax({
            type: "GET",
            data: sEmailScript,
            url: "sendtestmail.php",
            success: function(msg){
                $("#div_inbox").html();
                $("#div_inbox").html(msg);
            }
        });
    });

    $("#mailbutton").click(function(){
        var sEmailScript = 'sEmailScript = ' + tinymce.get('sEmailScript').getContent();
        sEmailScript = sEmailScript.replace(/&nbsp;/gi,'');
        $.ajax({
            type: "GET",
            data: sEmailScript,
            url: "sendmail.php",
            beforeSend: function() {
                $("#lodingdiv").append('<img src="images/loader.gif">');
            },
            success: function(msg){
                $("#div_inbox").html();
                alert(msg);
                $("#lodingdiv").remove();
            }
        });
    });
});


function campaignData(file){
    $.ajax({
       type: "POST",
        url: file+".php",
        success: function(msg){
            $("#div_inbox").html();
            $("#div_inbox").html(msg);
        }
    });
}

</script>