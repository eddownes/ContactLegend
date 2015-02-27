<?php 
include('header.php');
#error_reporting(E_ALL);
$page = "followupaction";
$mode = 'Update';
$display_content = '';
$tr_height = '200px';
$btn_disabled = 'disabled';
if(!isset($_SESSION['nCampaignid'])){
    header('location:createCampaign.php');
    exit;
}else{

	$nCampaignid = $_SESSION['nCampaignid'];
	$nCEmaiId = $_SESSION['nCEmaiId'];
	$Id = $_GET['id'];

    $where = "nCampaignid = '".$nCampaignid."'";
    $followupdata = getAnyData('nFollowupId,dScheduleTime','followupEmail',$where,null,null);
    $_SESSION['dScheduleTime'] = $followupdata[0]['dScheduleTime'];
    $_SESSION['nFollowupId'] = $followupdata[0]['nFollowupId'];

    $calldata = getAnyData('nCallId,dCallSchedTime','calls',$where,null,null);
    $_SESSION['dCallSchedTime'] = $calldata[0]['dCallSchedTime'];
    $_SESSION['nCallId'] = $calldata[0]['nCallId'];

    $directmaildata = getAnyData('nDirectEmailId,semailletter','directemail',$where,null,null);
    $_SESSION['semailletter'] = $directmaildata[0]['semailletter'];
    $_SESSION['nDirectEmailId'] = $directmaildata[0]['nDirectEmailId'];
    if(($_SESSION['nFollowupId'] != '') || ($_SESSION['nDirectEmailId'] != '') || ($_SESSION['nCallId'] != '')){
        $display_content = 'none';
        $tr_height = '0px';
        $btn_disabled = '';
    }    
}
?>

<style type="text/css">
.cke_wysiwyg_div{
    height: 150px !important;
}
</style>

<div id="content" class="span12">
    <div class="fix_delete_popup popup_phone ">
        <div class="fix_delete_popup_wrapper">
            <div class="fix_del_header">Follow up action <a href="javascript:void(0);" class="Del_top_close" type="button">1</a></div>                        
            <div class="fix_del_content"><p id="popup_content"></p></div>
            <div class="fix_del_footer"><a href="javascript:void(0)" class="cancel_gray"> Cancel </a></div>
        </div>
    </div>
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
        <div class="c_main_container">
            <div class=" collapse in">
                <div class="span12">
					<form enctype='multipart/form-data' class="c_form" id="createCmpgn" name="createCmpgn" method="POST" action="addfollowupaction.php">
            			<input type="hidden" name="nCampaignid" id="nCampaignid" value="<?php echo $nCampaignid;?>">
						<input type="hidden" name="nCEmaiId" id="nCEmaiId" value="<?php echo $nCEmaiId;?>">
						<input type="hidden" name="Id" id="Id" value="<?php echo $Id;?>">
						<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>">
                        <input type="hidden" name="draft" id="btn_draft" >
                        <input type="hidden" name="del_popup" id="del_popup">
                        <input type="hidden" name="f" id="f" value="<?php echo $_REQUEST['f'];?>">
                        <input type="hidden" name="phone_del" id="phone_del" value="No">
                        <input type="hidden" name="direct_del" id="direct_del" value="No">
                        <input type="hidden" name="followup_del" id="followup_del" value="No">
                        <table width="100%" cellpadding="3" cellspacing="3" border="0">
                        <tr><td><?php include_once("campaign_title.php");?></td></tr>
                        <tr>
                            <td>
                                <table class="internal" width="100%" cellpadding="3" cellspacing="3" border="0">
                                <tr><td><b>Follow up actions</b></td></tr>
                                <tr id="followup_msg" style="display:<?php echo $display_content?>">
                                    <td>
                                        Follow up actions are automated activities that take place after the message has been opned.
                                        Follow up </br> actions allow you to take advantage of follow up integrations.
                                    </td>
                                </tr>
                                <tr>
                                    <td id="content_data">
                                        <?php include_once("followup_content.php");?>
                                    </td>
                                </tr>
                                <tr><td><button class="btn" id="add_phonecontent" type="button">Add a follow up action</button></td></tr>
                                
                                <tr id="tr_height" style="height: <?php echo $tr_height?>;"></tr>
                                
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr_footer">
                                <a href="javascript:void(0)" <?php if($btn_disabled != ''){?> disabled="disabled" <?php } ?> id="save_exit" class="save_exit">Save and exit</a>
                                <button class="nxt_long btn btn-large btn-primary f_right" <?php if($btn_disabled != ''){?> disabled="disabled" <?php } ?> id="btn_nxt" name="draft" value="No" type="button">Next</button>
                            </td>
                        </tr>
                        </table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $base_url;?>js/additional-methods.js"></script>
<script src="<?php echo $base_url;?>js/bootstrap-datetimepicker.min.js"></script>
<script src="library/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    var sDirectEmailBody = '<?php echo $sDirectEmailBody?>';
    var sCallScript = '<?php echo $sCallScript?>';

    var req_sCallScript = true;
    /*var req_sDirectEmailBody = true;
    var req_sCallScript = true;*/

    $("#add_phonecontent").click(function(){

        $("#save_exit").removeAttr('disabled');
        $("#btn_nxt").removeAttr('disabled');
        $("#followup_msg").hide();
        $("#tr_height").hide();
        var phone_div = $('#div_phone').is(':visible');
        var direct_div = $('#div_direct').is(':visible');
        var followip_div = $('#div_followup').is(':visible');
        $("#content_data").show();
        var newclass = 'first';
        if($(".table_blocks_wrapper").hasClass('first')){
            var newclass = 'second';
        }
        
        if($(".table_blocks_wrapper").hasClass('second')){
            var newclass = 'third';
        }
        
        if(phone_div == false){
            $('#div_phone').show();
            $('html, body').animate({
                scrollTop: $('#div_phone').offset().top - 45
            }, 500);
            $('#div_phone').addClass(newclass);
        }
        else if(direct_div == false){
            $('#div_direct').show();
            $('html, body').animate({
                scrollTop: $('#div_direct').offset().top - 45
            }, 500);
            $('#div_direct').addClass(newclass);
        }
        else if(followip_div == false){
            $('#div_followup').show();
            $('html, body').animate({
                scrollTop: $('#div_followup').offset().top - 45
            }, 500);
            $('#div_followup').addClass(newclass);
        }
        else{
            $(this).attr('disabled','disabled');
        }
    });


    $(".action_droupdown").change(function(){

        var value = $(this).val();
        var id = $(this).attr('id');
        var div_id = '';   

        $("#sele_phone").val("Phone");
        $("#sele_followup").val("Email");
        $("#sele_direct").val("DirectMail");

        if(($('#div_phone').is(':visible')) && (value == 'Phone')){
            $("#popup_content").html('Phone call is already added for this campaign.');
            $(".fix_delete_popup.popup_phone").fadeIn("slow");
            return false;
        }
        if(($('#div_direct').is(':visible')) && (value == 'DirectMail')){
            $("#popup_content").html('Direct Mail is already added for this campaign.');
            $(".fix_delete_popup.popup_phone").fadeIn("slow");
            return false;
        }
        if(($('#div_followup').is(':visible')) && (value == 'Email')){
            $("#popup_content").html('Followup mail is already added for this campaign.');
            $(".fix_delete_popup.popup_phone").fadeIn("slow");
            return false;
        }

        if(value == 'Phone'){div_id = '#div_phone'; }
        if(value == 'DirectMail'){div_id = '#div_direct'; }
        if(value == 'Email'){div_id = '#div_followup'; }

        if(id == 'sele_phone'){
            $('#div_phone').hide();
            $("#dCallSchedTime").attr('required','required');
            $(div_id).show();
            $('html, body').animate({
                scrollTop: $(div_id).offset().top - 45
            }, 500);
            var cls = $("#div_phone").attr('class').split(' ');
            if(cls[1] == ""){
                var newcls = cls[2];
            }
            else{
                var newcls = cls[1];   
            }
            $(div_id).addClass(newcls);
            $('#div_phone').removeClass(newcls);
        }
        if(id == 'sele_direct'){
            $('#div_direct').hide();
            $(div_id).show();
            $('html, body').animate({
                scrollTop: $(div_id).offset().top - 45
            }, 500);
            var cls = $("#div_direct").attr('class').split(' ');
            if(cls[1] == ""){
                var newcls = cls[2];
            }
            else{
                var newcls = cls[1];   
            }
            $(div_id).addClass(newcls);  
            $('#div_direct').removeClass(newcls);         
        }
        if(id == 'sele_followup'){
            $('#div_followup').hide();
            $(div_id).show();
            $('html, body').animate({
                scrollTop: $(div_id).offset().top - 45
            }, 500);
            var cls = $("#div_followup").attr('class').split(' ');
            if(cls[1] == ""){
                var newcls = cls[2];
            }
            else{
                var newcls = cls[1];   
            }
            $(div_id).addClass(newcls);    
            $('#div_followup').removeClass(newcls);      
        }
 
        
        $("#sele_phone").val("Phone");
        $("#sele_followup").val("Email");
        $("#sele_direct").val("DirectMail");        
    });




    $('#createCmpgn').validate({
        ignore: [],
        debug: false,
        rules:{
            sCallScript:{
                required:{
                    depends: function(element){
                       var status = false;
                       if(($('#div_phone').is(':visible') === true) && (sCallScript == '')){
                           var status = true;
                       }
                       return status;
                    }
                },
                extension: 'mp3',
            },
            dCallSchedTime:{
                required:{
                    depends: function(element){
                       var status = false;
                       if( $('#div_phone').is(':visible') === true){
                           var status = true;
                       }
                       return status;
                    }
                }
            },
            sDirectEmailBody: {
                required:{
                    depends: function(element){
                        var status = false;
                        if( ($('#div_direct').is(':visible') === true) && (sDirectEmailBody == '')){
                           var status = true;
                        }
                        return status;
                    }
                },
                extension: 'pdf'  
            },
            sSchEmailSubject:{
                required:{
                    depends: function(element){
                       var status = false;
                       if( $('#div_followup').is(':visible') === true){
                           var status = true;
                       }
                       return status;
                    }
                }
            }/*,
            sSchEmailBody:{
                required:{
                    depends: function(element){
                       var status = false;
                       if( $('#div_followup').is(':visible') === true){
                           var status = true;
                       }
                       return status;
                    }
                }
            }*/
        },
        messages:{          
            sCallScript : {
                required : "Please Upload the MP3 File",
                extension : "Please upload mp3 file only!!"
            },
            dCallSchedTime :{
                required: "Please Enter the Call Schedule Time"               
            },
            sDirectEmailBody : {
                required : "Please Upload the PDF File",
                extension : "Please upload pdf file only!!"
            },
            sSchEmailSubject :{
                required: "Please Enter email subject"
            }/*,
            sSchEmailBody :{
                required: "Please Enter email message"
            }*/
        }
    }); 
    $('#createCmpgn').validate({});
 
    $("#save_exit").click(function(){
        $("#btn_draft").attr('value','Yes');
        $("#createCmpgn").submit();
    });

    $("#btn_nxt").click(function(){        
        $("#createCmpgn").submit();
    });

    $('#datetimepicker').datetimepicker({
        pickDate: false
    });
    
    
 
    $(".del_followupaction").click(function(){
        var id = $(this).attr('id');
        $("#del_popup").attr('value',id);
        $("#del_followupaction").fadeIn("slow");
    });

    $(".Del_top_close, .cancel_gray").click(function(){
        $("#del_followupaction").fadeOut("slow");
        $(".popup_phone").fadeOut("slow");
    });

    $(".red_del_link").click(function(){

        var del_id = $("#del_popup").attr('value');

        var newid = '';
        if(del_id == 'del_followup'){
            newid = '#div_followup';
        }
        else if(del_id == 'del_phone'){
            newid = '#div_phone';
        }
        else if(del_id == 'del_direct'){
            newid = '#div_direct';
        }
        var cls = $(newid).attr('class').split(' ');        

        $(".table_blocks_wrapper").each(function(){
            if(cls[1] == 'first')
            {
                if($(this).hasClass('table_blocks_wrapper second') || $(this).hasClass('table_blocks_wrapper  second')){
                    $(this).removeClass('second');
                    $(this).addClass('first');
                }
                if($(this).hasClass('table_blocks_wrapper third') || $(this).hasClass('table_blocks_wrapper  third')){
                    $(this).removeClass('third');
                    $(this).addClass('second');
                }
            }
            if(cls[1] == 'second')
            {
                if($(this).hasClass('table_blocks_wrapper third') || $(this).hasClass('table_blocks_wrapper  third')){
                    $(this).removeClass('third');
                    $(this).addClass('second');
                }
            }
            if(cls[1] == 'third')
            {
                if($(this).hasClass('table_blocks_wrapper third') || $(this).hasClass('table_blocks_wrapper  third')){
                    $(this).removeClass('third');
                }
            }
        });

        $(newid).removeClass(cls[1]);

        if(del_id == 'del_phone'){
            $('#div_phone').hide();
            $("#phone_del").attr('value','Yes');
            $("#dCallSchedTime").removeAttr('required');
            $("#sCallScript").removeAttr('required');
            $("#sCallScript").val("");
            $("#filename_browse_1").html("");
            $(".error").hide();
        }
        if(del_id == 'del_direct'){
            $('#div_direct').hide();
            $("#direct_del").attr('value','Yes');
            $("#sDirectEmailBody").removeAttr('required');
            $("#sDirectEmailBody").val("");
            $("#filename_browse_2").html("");
            $(".error").hide();
        }
        if(del_id == 'del_followup'){
            $('#div_followup').hide();
            $("#followup_del").attr('value','Yes');
            $("#sSchEmailSubject").removeAttr('required');
            $("#sSchEmailBody").removeAttr('required');
        }
        $('#add_phonecontent').removeAttr('disabled');

        $("#del_followupaction").fadeOut("slow");
    });

    CKEDITOR.replace('sSchEmailBody');
});
</script>
<?php include('footer.php');?>
