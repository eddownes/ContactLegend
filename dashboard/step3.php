<?php 
include('header.php');
if(!isset($_SESSION['nCampaignid'])){
    header('location:createCampaign.php');
    exit;
}
$where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
$champaign_data = getAnyData('*','campaignEmails',$where,null,null);
?>
<?php /*<script src="<?php echo $base_url?>library/FCKeditor/fckeditor.js"></script>
<script src="<?php echo $base_url?>library/FCKeditor/ckeditor.js"></script> */ ?>
<script src="<?php echo $base_url?>library/ckeditor/ckeditor.js"></script>
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
                    <h5><?php echo $champaign_data[0]['sEmailSubject'];?></h5>
                    <table width="100%" height="100%">
                        <tr>
                            <td valign="top">
                                <table width="100%" cellspacing="2" cellpadding="2">
                                <tr><td><textarea id="sEmailScript" class="testmail" name="sEmailScript"><?php echo $champaign_data[0]['sEmailScript'];?></textarea></td></tr>
                                <tr><td width="2%">&nbsp;</td></tr>
                                <tr><td><div id="lodingdiv_test"></div><input type="button" class="button" value="Send Test Mail" id="testmailbutton"></td></tr>
                                </table>
                            </td> 
                        </tr>
                        <tr><td><hr/></td></tr>
                        <tr>
                            <td id="tabs">
                                <ul>
                                    <li><a href="#tabs-1">Schedule Your Automated Call</a></li>
                                    <li><a href="#tabs-2">Schedule Your Direct Email</a></li>
                                    <li><a href="#tabs-3">Schedule Your Follow Up Email</a></li>
                                </ul>
                                <div id="tabs-1">
                                    <?php 
                                    include("edit_automatedCall.php");
                                    ?>
                                </div>
                                <div id="tabs-2">
                                    <?php 
                                    include("edit_directMail.php");
                                    ?>
                                </div>
                                <div id="tabs-3">
                                    <?php 
                                    include("edit_followupMail.php");
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>   
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo $base_url?>css/jquery-ui.css">
<link rel="stylesheet" href="css/tabs.css" type="text/css" media="screen" />
<style type="text/css">
    .cke_wysiwyg_div{
        height: 150px !important;
    }
</style>
<script type="text/javascript" src="js/tabs.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    
    $("#tabs").tabs();

    CKEDITOR.replace( 'sEmailScript' );

    $("#testmailbutton").click(function(){

        var sEmailScript = CKEDITOR.instances.sEmailScript.getData();
        dataString = "sEmailScript="+escape(sEmailScript);
        
        $.ajax({
            type: "POST",
            data: dataString,
            url: "sendtestmail.php",
            beforeSend: function() {
                $("#lodingdiv_test").append('<img src="images/loader.gif">');
            },
            success: function(msg){
                $("#div_inbox").html();
                $("#div_inbox").html(msg);
                alert("Test mail send successfully");
                $("#lodingdiv_test").remove();
            }
        });
    });
});


</script>

