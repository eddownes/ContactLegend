<?php 
include("../library/function.php");
include('../smtpmail/classes/class.phpmailer.php');
$mode = $_REQUEST['mode'];
$nUserId = $_REQUEST['nUserId'];
#pr($_POST);
error_reporting(E_ALL);
if(($mode == 'Add') || ($mode == 'Update'))
{
    if($_POST['nFollowupMail'] == 1)
    {        
        #$returnval = checksmtpdetails($_POST['sUsername'],$_POST['sPassword'],$_POST['sServerName'],$_POST['sPorts'],$_POST['sUsername']);
        $smtpusername = $_POST['sUsername'];
        $smtppassword = $_POST['sPassword'];
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
            $returnval = "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $returnval = '1';
        }

        
        if($returnval != '1'){
            $_SESSION['error_msg'] = $returnval;
            if($nUserId != ''){
                header('location:'.$admin_url.'useradd.php?mode=Update&id='.$nUserId);
                exit;
            }
            else{
                header('location:'.$admin_url.'useradd.php');
                exit;
            }
        }
    }

    $data['sUserFullName']      =   $_POST['sUserFullName'];
    $data['sUserEmail']         =   $_POST['sUserEmail'];
    $data['sUserPassword']      =   $_POST['sUserPassword'];
    $data['sUserBusinessName']  =   $_POST['sUserBusinessName'];
    $data['sCallUsername']      =   $_POST['sCallUsername'];
    $data['sCallPassword']      =   $_POST['sCallPassword'];
    $data['nCallPhoneNo']       =   $_POST['nCallPhoneNo'];
    $data['nFollowupMail']      =   $_POST['nFollowupMail'];
    $data['nCallDetails']       =   $_POST['nCallDetails'];
    $data['nDirectMails']       =   $_POST['nDirectMails'];
    $data['sDirectmailUsername']       =   $_POST['sDirectmailUsername'];
    $data['sDirectmailPassword']       =   $_POST['sDirectmailPassword'];
    $data['bStatus']                   =   1;
    
    $smtp_data['sServerName']   =   $_POST['sServerName'];
    $smtp_data['sUsername']     =   $_POST['sUsername'];
    $smtp_data['sPassword']     =   $_POST['sPassword'];
    $smtp_data['sPorts']        =   $_POST['sPorts'];

    $bill_data['sName']         =   $_POST['sName'];
    $bill_data['sCompany']      =   $_POST['sCompany'];
    $bill_data['sAddress1']     =   $_POST['sAddress1'];
    $bill_data['sAddress2']     =   $_POST['sAddress2'];
    $bill_data['sCity']         =   $_POST['sCity'];
    $bill_data['sState']        =   $_POST['sState'];
    $bill_data['sZip']          =   $_POST['sZip'];
    $bill_data['sCountry']      =   $_POST['sCountry'];
    $bill_data['eBilltype']     =   $_POST['eBilltype'];
    $bill_data['nCreditCardNo'] =   $_POST['nCreditCardNo'];
    $bill_data['nCreditCardExpYear']    =   $_POST['nCreditCardExpYear'];
    $bill_data['nCreditCardExpMonth']   =   $_POST['nCreditCardExpMonth'];

    if($mode == 'Add')
    {
    	$tot_rec = check_email($_POST['sUserEmail'],null);
    	if($tot_rec <= 0)
    	{
    		$message = "<table class='body'>
		                    <tr>
		                        <td valign='top'>
		                            <center><h1>Contact Legend</h1></center>
		                        </td>
		                    </tr>
		                    <tr>
		                        <td valign='top'>You are successfully registered with us.</td>
		                    </tr>
		                    <tr>
		                        <td valign='top'>Your login details are as below.</td>
		                    </tr>
		                    <tr>
		                        <td valign='top'>Your email :: ".$_POST['sUserEmail']."</td>
		                    </tr>
		                    <tr>
		                        <td valign='top'>Your password: ".$_POST['sUserPassword']."</td>
		                    </tr>	                     
		                    <tr><td>You may login at <a href=".$base_url." target='_blank'>LOGIN URL</a></td></tr>
		                    <tr><td>&nbsp;</td></tr>
		                    <tr><td>Thank You.</td></tr>
		                    <tr><td>Contact Legend.</td></tr>
		                    </table>";

	        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/></head>  <body>".$message."</body></html>";

	        $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            $mail->Host = '72.52.245.168';
            $mail->Port = '465'; // or 587
            $mail->IsHTML(true);
            $mail->Username = 'no-reply@contactlegend.com';
            $mail->Password = 't6[n6c{Py5Q1';
            $mail->SetFrom('no-reply@contactlegend.com');
            $mail->Subject = 'Welcome to ContactLegend';
            $mail->Body = $body;
            $toemail = $_POST['sUserEmail'];    
            $mail->AddAddress($toemail);
            //$mail->Send();
            if (!$mail->send()) {
               echo "Mailer Error: " . $mail->ErrorInfo; 
            } 
		   	
    		$data['dCreatedDate']       =   date('Y-m-d H:i:s');
	        $return_data                =   dbRowInsert('users', $data);
	        $smtp_data['nUserID']       =   $return_data;
	        $bill_data['nUserID']       =   $return_data;
	        $smtp_data['dtCreatedDate']  =   date('Y-m-d H:i:s');
	        if($return_data == TRUE) {

	            $return_smtpdata = dbRowInsert('user_smtpdetails', $smtp_data);
	            $return_billdata = dbRowInsert('user_billdetails', $bill_data);
				
	            $_SESSION['success_msg'] = 'User added  successfully.';
	            header('location:'.$admin_url.'userlist.php');
	            exit;
	        }
	    }
	    else
        {        	
        	$_SESSION['error_msg'] = 'User email already register with us..';
            header('location:'.$admin_url.'useradd.php');
            exit;	
        }
    }
    elseif($mode == 'Update')
    {
        $data['dUpdatedDate']   =   date('Y-m-d H:i:s');
        $where = "nUserId = '".$nUserId."'";
        $smtp_data['nUserID']   =   $nUserId;

        $tot_rec = check_email($_POST['sUserEmail'],$nUserId);
    	if($tot_rec <= 0)
    	{

	        $return_data = dbRowUpdate('users', $data, $where);
	        $return_smtpdata = dbRowUpdate('user_smtpdetails', $smtp_data, $where);
	        $return_billdata = dbRowUpdate('user_billdetails', $bill_data, $where);
	        if ($return_data == TRUE) 
	        {
	            $_SESSION['success_msg'] = 'User updated  successfully.';
	            header('location:'.$admin_url.'userlist.php');
	            exit;
	        } 
	        else 
	        {
	            $_SESSION['error_msg'] = 'Error in updating user.';
	            header('location:'.$admin_url.'useradd.php?mode=Update&id='.$nUserId);
	            exit;
	        }
	    }
	    else
	    {
	    	$_SESSION['error_msg'] = 'Email address already available.';
            header('location:'.$admin_url.'useradd.php?mode=Update&id='.$nUserId);
            exit;
	    }
    }
}
else if($mode == 'Delete')
{
    $where_clause = " nUserId = '".$_REQUEST['id']."'";
    $userTable = dbRowDelete('users', $where_clause);
    if($userTable == 'TRUE')
    {
        $_SESSION['success_msg'] = "User Deleted Successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in Deleting user";
    }
    header("location:userlist.php");
    exit;
}
else if($mode == 'Status')
{ 
    $status = 1;
    if($_GET['status'] == 1){ $status = 0;}
    $where_clause = " nUserId = '".$_REQUEST['id']."'";
    $data['bStatus'] = $status;
    $result = dbRowUpdate('users',$data, $where_clause);
    if ($result == TRUE) 
    {
        $_SESSION['success_msg'] = "User status updated successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in updating status";
    }
    header("location:userlist.php");
    exit;
}

?>
