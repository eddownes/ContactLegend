<?php
$page = 'Account'; 
include("header.php");
include('smtpmail/classes/class.phpmailer.php');
error_reporting(E_ALL);
if(isset($_POST['submit'])){
    
    $data['sCallUsername']      =   trim($_POST['sCallUsername']);
    $data['sCallPassword']      =   trim($_POST['sCallPassword']);    
    $data['nCallPhoneNo']       =   $_POST['nCallPhoneNo'];
    $data['sDirectmailUsername']       =   trim($_POST['sDirectmailUsername']);
    $data['sDirectmailPassword']       =   trim($_POST['sDirectmailPassword']);

    $smtp_data['sServerName']   =   trim($_POST['sServerName']);
    $smtp_data['sUsername']     =   trim($_POST['sUsername']);
    $smtp_data['sPassword']     =   trim($_POST['sPassword']);
    $smtp_data['sPorts']        =   $_POST['sPorts'];

    if(($_POST['sServerName'] != '') && ($_POST['sUsername'] != '') && ($_POST['sPassword']!= '') && ($_POST['sPorts']!= '') && ($_POST['nFollowupMail'] == "1")){
        

        #$returnval = checksmtpdetails($_POST['sUsername'],trim($_POST['sPassword']),$_POST['sServerName'],$_POST['sPorts'],$_POST['sUsername']);
        
        $smtpusername = $_POST['sUsername'];
        $smtppassword = trim($_POST['sPassword']);
        $smtphost = $_POST['sServerName'];
        $smptpport = $_POST['sPorts'];
        $toemail = $_POST['sUsername'];


        $message = "<table class='body'>
                <tr>
                    <td class='center' align='center' valign='top'>
                        <center><h1>Contact Legend</h1></center>
                    </td>
                </tr>
                <tr>
                    <td class='center' align='center' valign='top'>
                        <center> This email is set to check whether the smtp details are correct or not.</center>
                    </td>
                </tr>
                <tr><td>If you are viewing this email means your smtp detail are correct.</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>Thank You.</td></tr>
                <tr><td>Contact Legend.</td></tr>
                </table>";

        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/>       <style type='text/css'>            /* Ink styles go here in production */          </style>    <style type='text/css'>      /* Your custom styles go here */    </style>  </head>  <body>    <table class='body'>      <tr>        <td class='center' align='center' valign='top'><center>".$message."  <!-- Email Content -->              </center>        </td>      </tr>    </table>  </body></html>";

        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled

        if (strpos($smtphost,'gmail') !== false || strpos($smtphost,'googlemail') !== false || strpos($smtphost,'google') !== false) {
            if($smptpport == '465'){
                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            } else {
                $mail->SMTPSecure = 'tls';
            }
        } else {
            if($smptpport == '465'){
                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            }
        }

        #$mail->Host = 'ssl://smtp.gmail.com:465';
        $mail->Host = $smtphost;
        $mail->Port = $smptpport; // or 587
        $mail->Username = $smtpusername;
        $mail->Password = $smtppassword;
        $mail->SetFrom($toemail);

        $mail->IsHTML(true);
        $mail->Subject = 'Test Mail';
        $mail->Body = $body;
        $mail->AddAddress($toemail);
        
        if (!$mail->send()) {
            $returnval =  "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $returnval = '1';
        }

        if($returnval != '1'){
            #$_SESSION['error_msg'] = 'Please Enter Correct SMTP Details.';
            $_SESSION['error_msg'] = $returnval;
            header('location:edit_followup_setting.php');
            exit;
        } elseif($returnval == '1'){
            $_SESSION['success_msg1'] = "Your e-mail SMTP details have been saved. We sent a test message to your account e-mail - please check to make sure you received it (don't forget to check your spam and bulk folders.)";
        }
    }

    if(($_POST['sCallUsername'] != "") && ($_POST['sCallPassword'] != "") && ($_POST['nCallDetails'] == "1")) {
        $returnval = checkcallfiredetails(trim($_POST['sCallUsername']), trim($_POST['sCallPassword']), $upload_url);
        if($returnval != '1'){
            $_SESSION['error_msg'] = 'Please enter correct CallFire API details.';
            header('location:edit_followup_setting.php');
            exit;
        } elseif($returnval == '1'){
            $_SESSION['success_msg2'] = "Your Voice Broadcast details have been saved.";
        }
    }

    if(($_POST['sDirectmailUsername'] != "") && ($_POST['sDirectmailPassword'] != "") && ($_POST['nDirectMails'] == "1")){
        $returnval = checkclick2maildetails(trim($_POST['sDirectmailUsername']), trim($_POST['sDirectmailPassword']));
        if($returnval != '1'){
            $_SESSION['error_msg'] = 'Please enter correct Click2Mail details.';
            header('location:edit_followup_setting.php');
            exit;
        } elseif($returnval == '1'){
            $_SESSION['success_msg3'] = 'Your Direct Mail details have been saved. IMPORTANT: If using Click2Mail make sure you have activated their API (In your Click2Mail account goto "My Account" -> "Preferences" and under "User API" click the start now button.)';
        }
    }

    $where = "nUserId = '".$_SESSION['nUserID']."'";
    $smtp_data['nUserID']   =   $_SESSION['nUserID'];
    $data = dbRowUpdate('users', $data, $where);
    $return_smtpdata = dbRowUpdate('user_smtpdetails', $smtp_data, $where);

    if($data == TRUE){
        //$_SESSION['success_msg'] = "Follow up integrations updated successfully. You should have received a test e-mail from your SMTP provider - that's just us verifying your SMTP details.";
        header('location:edit_followup_setting.php');
        exit;
    } 
    else {
        $_SESSION['error_msg'] = 'Follow-up settings not updated. Please try again.';
        header('location:edit_followup_setting.php');
        exit;
    }
}
$sPorts = $login_data[0]['sPorts'];
?>
<link rel="stylesheet" href="<?php echo $base_url;?>css/colorbox.css" />
<script src="<?php echo $base_url;?>js/jquery.colorbox.js"></script>
<div class="span12" id="content">
    <?php if(isset($_SESSION['success_msg'])){?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['success_msg'];?></div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['success_msg1'])){?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['success_msg1'];?></div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['success_msg2'])){?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['success_msg2'];?></div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['success_msg3'])){?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['success_msg3'];?></div>
        </div>
    <?php } ?>
    <?php if(isset($_SESSION['error_msg'])){?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div style="color:green"><?php echo $_SESSION['error_msg'];?></div>
        </div>
    <?php } ?>
    <div class="row-fluid">
        <div class="span3">
            <?php include("account_leftbar.php"); ?>
        </div>
        <div class="block span9">
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="" method="post" name="frmeditfollowup" id="frmeditfollowup">
                        <fieldset>
                            <div class="control-group"><h2>Followup integrations</h2></div>
                            <div id="userserverdetails">
                                <div class="control-group"><h5>E-mail Settings</h5></div>
                                <div>
                                    <!-- <label class="control-label" for="sServerName">Turbo SMTP</label> -->
                                    Turbo SMTP <input type="checkbox" <?php if($login_data[0]['nFollowupMail'] == '1'){?> checked="checked" <?php } ?> class="span1" id="nFollowupMail" name="nFollowupMail" value="<?php echo $login_data[0]['nFollowupMail'];?>" >    
                                </div>
                                <div id="userfollowup_content" <?php if($login_data[0]['nFollowupMail'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                    <div class="control-group">
                                        <label class="control-label" for="sServerName">Server name</label>
                                        <div class="controls">
                                            <input type="text" class="account-input span8" id="sServerName" name="sServerName" value="<?php echo $login_data[0]['sServerName'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sUsername">Server username</label>
                                        <div class="controls">
                                            <input type="text" class="account-input span8" id="sUsername" name="sUsername" value="<?php echo $login_data[0]['sUsername'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sPassword">Server password</label>
                                        <div class="controls">
                                            <input type="password" class="account-input span8" id="sPassword" name="sPassword" value="<?php echo $login_data[0]['sPassword'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sPorts">Server port number</label>
                                        <div class="controls">
                                            <select name="sPorts" id="sPorts">
                                                <option>--Select Port--</option>
                                                <option value="25" <?php if($sPorts == '25'){?> selected = 'Selected' <?php }?>>25</option>
                                                <option value="465" <?php if($sPorts == '465'){?> selected = 'Selected' <?php }?>>465</option>                                            
                                                <option value="587" <?php if($sPorts == '587'){?> selected = 'Selected' <?php }?>>587</option>
                                            </select>
                                            <!-- <input type="text" class="span6" id="sPorts" name="sPorts" value="<?php #echo $login_data[0]['sPorts'];?>" > -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="usercalldetails">
                                <div class="control-group">
                                    <h5>Voice Broadcast Settings</h5>
                                </div>
                                <div>
                                    <!-- <label class="control-label" for="sCallUsername">Call Fire</label> -->
                                    Call Fire <input type="checkbox" class="span1" <?php if($login_data[0]['nCallDetails'] == '1'){?> checked="checked" <?php } ?> id="nCallDetails" name="nCallDetails" value="<?php echo $login_data[0]['nCallDetails'];?>" >
                                </div>
                                <div id="usercallfire_content" <?php if($login_data[0]['nCallDetails'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                    <div class="control-group">
                                        <label class="control-label" for="sCallUsername">App Login <br>(Goto Callfire Settings -> API Access)</label>
                                        <div class="controls">
                                            <input type="text" class="account-input span8" id="sCallUsername" name="sCallUsername" value="<?php echo $login_data[0]['sCallUsername'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sCallPassword">App Password</label>
                                        <div class="controls">
                                            <input type="password" class="account-input span8" id="sCallPassword" name="sCallPassword" value="<?php echo $login_data[0]['sCallPassword'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="nCallPhoneNo">Phone</label>
                                        <div class="controls">
                                            <input type="text" class="account-input span8" id="nCallPhoneNo" name="nCallPhoneNo" value="<?php echo $login_data[0]['nCallPhoneNo'];?>" >
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="userdirectmaildetails">
                                <div class="control-group">
                                    <h5>Direct Mail - Coming Soon!</h5>
                                </div>
                                
<!--                                                              
                                <div>
-->                                
                                    <!-- <label class="control-label" for="sDirectmailUsername">Click2Mail</label> -->
                                    
<!--                                    
                                    Click2Mail
                                    <input type="checkbox" class="span1" <?php if($login_data[0]['nDirectMails'] == '1'){?> checked="checked" <?php } ?> id="nDirectMails" name="nDirectMails" value="<?php echo $login_data[0]['nDirectMails'];?>" >
                                </div>
                                <div id="userdirectmaildetails_content" <?php if($login_data[0]['nDirectMails'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                    <div class="control-group">
                                        <label class="control-label" for="sDirectmailUsername">Username</label>
                                        <div class="controls">
                                            <input type="text" class="account-input span8" id="sDirectmailUsername" name="sDirectmailUsername" value="<?php echo $login_data[0]['sDirectmailUsername'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="sDirectmailPassword">Password</label>
                                        <div class="controls">
                                            <input type="password" class="account-input span8" id="sDirectmailPassword" name="sDirectmailPassword" value="<?php echo $login_data[0]['sDirectmailPassword'];?>" >
                                        </div>
                                    </div>
                                </div>
                            </div>
 -->                           
                            
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save integrations</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $(".ajax").colorbox();

    $('#nFollowupMail').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#userfollowup_content").toggle(this.checked);
    });

    $('#nCallDetails').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#usercallfire_content").toggle(this.checked);
    });
    
    $('#nDirectMails').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#userdirectmaildetails_content").toggle(this.checked);
    }); 

    $("#frmeditfollowup").validate({
        rules: {
            sServerName:{
                required: true,
            },
            sUsername:{
                required: true,
            },
            sPassword:{
                required: true,
            },
            sPorts:{
                required: true,
                number: true
            },
            sCallUsername:{
                required: true,
            },
            sCallPassword:{
                required: true,
            },
            nCallPhoneNo:{
                required: true,
                number: true
            },
            sDirectmailUsername:{
              required: true  
            },
            sDirectmailPassword:{
              required: true  
            }
        },
        messages: {
            sServerName: "Please enter server name",
            sUsername: "Please enter server username",
            sPassword: "Please enter server password",
            sPorts: {
                required:"Please select server port",
                number : "Please select a valid server port"
            },
            sCallUsername: "Please enter callfire api username",
            sCallPassword: "Please enter callfire api password",
            nCallPhoneNo: {
                required:"Please enter phone number used in callfire",
                number : "Please enter only numbers with no spaces or special characters. For example: 5555555555"
            },
            sDirectmailUsername: {
                required : "Please enter click2mail username"
            },
            sDirectmailPassword: {
                required : "Please enter click2mail password"
            }
        }
    });
});
</script>
<?php include("footer.php");?>
       
