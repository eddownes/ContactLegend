<?php
$sCallScript ='';
$dCallSchedTime = '';
$sDirectEmailBody = '';
$sSchEmailSubject = '';
$sSchEmailBody = '';
$mail_min = '';
$mail_hrs = '';
$phone_min = '';
$phone_hrs = '';
$direct_mode = 'Add';
$phone_mode = 'Add';
$followup_mode = 'Add';

$direct_display = 'none';
$phone_display = 'none';
$followup_display = 'none';

$direct_class = '';
$phone_class = '';
$followup_class = '';

if($_SESSION['nCallId'] != ''){

	$phone_mode = 'Update';
    $phone_display = '';
    $phone_class = 'first';
    
	$where = "nCallId = '".$_SESSION['nCallId']."'";
	$automatedCalldata = getAnyData('*','calls',$where,null,null);
	$sCallScript = $automatedCalldata[0]['sCallScript'];
	$t = explode(":",$automatedCalldata[0]['dCallSchedTime']);
	$dCallSchedTime = $automatedCalldata[0]['dCallSchedTime'];
    $phone_min = $t[1];
    $phone_hrs = $t[0];
    $scripturl = 'download_mp3.php?file='.$upload_url.$sCallScript;
}
$res = check_data_campaign($_SESSION['nCampaignid']);

if($_SESSION['nDirectEmailId'] != ''){
	$direct_mode = 'Update';
    $direct_display = '';
    $direct_class = 'second';
    if($phone_class == ''){
        $direct_class = 'first';    
    }    
	$where = "nDirectEmailId = '".$_SESSION['nDirectEmailId']."'";
	$data = getAnyData('*','directemail',$where,null,null);
    $sDirectEmailBody = $data[0]['sDirectEmailBody'];
    $downloadfileurl = 'download_mp3.php?file='.$upload_url.$sDirectEmailBody;
}
$res_directmail = check_data_campaign($_SESSION['nCampaignid']);

if($_SESSION['nFollowupId'] != ''){
    $followup_display = '';
	$where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
	$data = getAnyData('*','followupEmail',$where,null,null);
	$followup_mode = 'Update';
	$dScheduleTime = explode(":",$data[0]['dScheduleTime']);
    $followup_class = 'third';
    if($phone_class == '' && $direct_class == ''){
        $followup_class = 'first';    
    }
    else if($phone_class != '' && $direct_class == ''){
        $followup_class = 'second';    
    }
	$mail_min = $dScheduleTime[1];
	$mail_hrs = $dScheduleTime[0];
	$sSchEmailSubject = $data[0]['sSchEmailSubject'];
	$sSchEmailBody = $data[0]['sSchEmailBody'];
}

$res_followup = check_data_campaign($_SESSION['nCampaignid']);

?>

<script type="text/javascript">
  
$(function() 
{
    $('.upload_file_1').change( function(){
            var value1 = $(this).val();
            var filename1 = value1.replace(/^.*\\/, "");
            $("#filename_browse_1").html(filename1);
    });
    
    $('.upload_file_2').change( function(){
            var value2 = $(this).val();
            var filename2 = value2.replace(/^.*\\/, "");
            $("#filename_browse_2").html(filename2);
    });

    $('#btn_nxt').click(function(){
        var mp3_file = $('.upload_file_1').val();
        var mp3Extension = mp3_file.substring(mp3_file.lastIndexOf('.') + 1); 
        if($("#filename_browse_1").html() != "" && mp3Extension == "mp3"){
            $("#loading_img_call").show();
        }

        var pdf_file = $('.upload_file_2').val();
        var pdfExtension = pdf_file.substring(pdf_file.lastIndexOf('.') + 1); 
        if($("#filename_browse_2").html() != "" && pdfExtension == "pdf"){
            $("#loading_img_pdf").show();
        }
    });

    $('#save_exit').click(function(){
        var mp3_file = $('.upload_file_1').val();
        var mp3Extension = mp3_file.substring(mp3_file.lastIndexOf('.') + 1); 
        if($("#filename_browse_1").html() != "" && mp3Extension == "mp3"){
            $("#loading_img_call").show();
        }

        var pdf_file = $('.upload_file_2').val();
        var pdfExtension = pdf_file.substring(pdf_file.lastIndexOf('.') + 1); 
        if($("#filename_browse_2").html() != "" && pdfExtension == "pdf"){
            $("#loading_img_pdf").show();
        }
    });
 
});
        
</script>

<div class="fix_delete_popup" id="del_followupaction">
    <div class="fix_delete_popup_wrapper">
        <div class="fix_del_header">
            Delete follow up action <a href="javascript:void(0);" class="Del_top_close" type="button">1</a>
        </div>                        
        <div class="fix_del_content">            
            <p>Delete this follow up action? This cannot be undone and any data for this action will be deleted. </p>            
        </div>
        <div class="fix_del_footer">
            <a href="javascript:void(0)" class="red_del_link"> <!--Yes, delete this campaign--> Yes, delete this follow up action</a> 
            <a href="javascript:void(0)" class="cancel_gray"> Cancel </a> 
        </div>
    </div>
</div>


<div class="table_blocks_wrapper <?php echo $phone_class;?>" id="div_phone" style="display:<?php echo $phone_display;?>">
<table class="table_blocks" width="100%" cellpadding="3" cellspacing="3" border="0">
    <input type="hidden" name="phone_mode" id="phone_mode" value="<?php echo $phone_mode;?>">
    <tr><td colspan="3"></td></tr>
    <tr>
        <td class="labled" width="30%">What type of action?</td>
        <td width="5%"> </td>
        <td class="deleted" align="right"><a href="javascript:void(0)" id="del_phone" class="del_followupaction" >Delete this follow up action</a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <select name="actiontype" class="action_droupdown" id="sele_phone">
                <option value="Phone">Phone call</option>
                <option value="DirectMail">Direct mail message</option>
                <option value="Email">Email message</option>
            </select>
        </td>
    </tr>
    <tr><td class="labled" colspan="3">Add a call recording (mp3) <br> <p style="font-size: 11px; color: gray;">Note: Maximum allowed file size is 20MB. </p></td></tr>
    <tr>
        <td colspan="3">
            <input class="custom-file-input upload_file upload_file_1" type="file" name="sCallScript" id="sCallScript" <?php if($sCallScript == ''){?>  <?php }?>></br>
            <a href="javascript:void(0);" class="instead_select_button" onclick="document.getElementById('sCallScript').click()" title="Click or Drag Image">Select</a>
            <span style="margin-left: 10px;" id="filename_browse_1"></span>
            <span id="loading_img_call" style="display:none;">
                <img src='/dashboard/images/ajax-loader3.gif' alt='Uploading'/>
            </span>
            <div style="display: block; margin-top: 10px;"> <?php if($sCallScript != ''){ echo "<b> Uploaded File Name ::<a href='".$scripturl."'> ".$sCallScript."</a> </b><br/><br/>";}?> </div>
        </td>
    </tr>
    <tr>
        <td class="labled">Trigger this action</td>
        <td></td>
        <td style="height: 40px; position: relative;" class="table_blocks_content">
            
            <span style="position: absolute; right: 0px; top: 0px; width: 67%;"> 
                Call will never be triggered between 10pm and 8am in the
                prospect's area code. If call is triggered during this time it
                will be delivered at 8am on the following day. 
            </span>
            
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <select style="margin-right: 5px; width: 60px;" name="phone_hrs" id="phone_min">
                <?php for($i=0;$i<=23;$i++){?>
                    <option <?php if($phone_hrs == $i){ ?> selected="selected" <?php }?> value="<?php echo $i;?>" ><?php echo $i?></option>
                <?php } ?>
            </select><span style="position: relative; top: -3px;">hours</span>
            <select style="margin-right: 5px; width: 60px; margin-left: 10px;" name="phone_min" id="phone_min">
                <?php for($j=1;$j<=60;$j++){?>
                    <option  <?php if($phone_min == $j){ ?> selected="selected" <?php }?> value="<?php echo $j;?>" ><?php echo $j?></option>
                <?php } ?>
            </select> <span style="position: relative; top: -3px;" class="table_blocks_content">mins after the intro message is opened</span>
            <!-- <input data-format="hh:mm:ss" name="dCallSchedTime"  value="<?php echo $dCallSchedTime;?>" type="text"></input> -->
            <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
        </td>
    </tr>
</table>
</div>



<div class="table_blocks_wrapper <?php echo $direct_class;?>" id="div_direct" style="display:<?php echo $direct_display;?>">
<table class="table_blocks" width="100%" cellpadding="3" cellspacing="3" border="0">
    <input type="hidden" name="direct_mode" id="direct_mode" value="<?php echo $direct_mode;?>">
    <tr><td colspan="3"></td></tr>
    <tr>
        <td class="labled" width="30%">What type of action?</td>
        <td width="5%">&nbsp;</td>
        <td class="deleted" align="right"><a href="javascript:void(0)" id="del_direct" class="del_followupaction">Delete this follow up action</a></td>
    </tr>
    <tr>
        <td colspan="3">
            <select class="action_droupdown" name="actiontype1" id="sele_direct">
                <option value="DirectMail">Direct mail message</option>
                <option value="Email">Email message</option>
                <option value="Phone">Phone call</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="labled" valign="top" colspan="2">Add message creative (PDF) <br> <p style="font-size: 11px; color: gray;">Note: Maximum allowed file size is 20MB. </p></td>
        <td style="height: 40px; position: relative;" class="table_blocks_content">
            <span style="position: absolute; right: 0px; top: 0px; width: 67%;"> 
                Mail house will be notified to send out direct mail piece on the same day your prospect opens their e-mail.
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <input class="custom-file-input upload_file upload_file_2" type="file" name="sDirectEmailBody" id="sDirectEmailBody" <?php if($sDirectEmailBody == ''){?>  <?php }?>></br>            
            <a href="javascript:void(0);" class="instead_select_button" onclick="document.getElementById('sDirectEmailBody').click()" title="Click or Drag Image">Select</a>   
            <span style="margin-left: 10px;" id="filename_browse_2"></span>
            <span id="loading_img_pdf" style="display:none;">
                <img src='/dashboard/images/ajax-loader3.gif' alt='Uploading'/>
            </span>
            <?php if($sDirectEmailBody != ''){ echo "<b> Uploaded File Name :: <a href='".$downloadfileurl."'>  ".$sDirectEmailBody." </a></b><br/><br/>";}?>
        </td>
    </tr>    
</table>
</div>



<div class="table_blocks_wrapper <?php echo $followup_class;?>" id="div_followup" style="display:<?php echo $followup_display;?>">    
<table class="table_blocks" width="100%" cellpadding="3" cellspacing="3" border="0">
    <input type="hidden" name="followup_mode" id="followup_mode" value="<?php echo $followup_mode;?>">
    <tr><td colspan="3"></td></tr>
    <tr>
        <td class="labled" width="30%">What type of action?</td>
        <td width="5%">&nbsp;</td>
        <td class="deleted" align="right"><a href="javascript:void(0)" id="del_followup" class="del_followupaction">Delete this follow up action</a></td>
    </tr>
    <tr>
        <td colspan="3">
            <select class="action_droupdown" name="actiontype2" id="sele_followup">
                <option value="Email">Email message</option>
                <option value="Phone">Phone call</option>
                <option value="DirectMail">Direct mail message</option>
            </select>
        </td>
    </tr>
    
    <tr><td class="labled">Subject line</td></tr>
    <tr>
        <td colspan="3">
            <input style="width: 50%;" placeholder="" id="sSchEmailSubject" name="sSchEmailSubject" value="<?php echo $sSchEmailSubject;?>" type="text">
        </td>
    </tr>
    <tr><td class="labled">Message</td></tr>
    <tr>
        <td style="padding-bottom: 15px !important;" colspan="3">
            <textarea cols="15" rows="10" placeholder="Write Your Message Here" name="sSchEmailBody" id="sSchEmailBody"><?php echo $sSchEmailBody;?></textarea>
        </td>
    </tr>    
    <tr><td class="labled">Trigger this action</td></tr>
    <tr>
        <td colspan="3">
            <select style="margin-right: 5px; width: 60px;" name="mail_hrs" id="mail_hrs">
                <?php for($i=0;$i<=23;$i++){?>
                    <option <?php if($mail_hrs == $i){ ?> selected="selected" <?php }?> value="<?php echo $i;?>" ><?php echo $i?></option>
                <?php } ?>
            </select><span style="position: relative; top: -3px;">hours</span>
            <select style="margin-right: 5px; width: 60px; margin-left: 10px;" name="mail_min" id="mail_min">
                <?php for($j=1;$j<=60;$j++){?>
                    <option  <?php if($mail_min == $j){ ?> selected="selected" <?php }?> value="<?php echo $j;?>" ><?php echo $j?></option>
                <?php } ?>
            </select>
            <span style="position: relative; top: -3px;" class="table_blocks_content">mins after the intro message is opened</span>
        </td>
    </tr>    
</table>
</div>

