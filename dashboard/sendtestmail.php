<?php
include('library/function.php');

$where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
$champaign_data = getAnyData('*','campaignEmails',$where,null,null);

$where = "u.nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId";
$login_data = getAnyData('*',$table,$where,null,null);
$toemail = $login_data[0]['sUserEmail']; 

$fromemail = $login_data[0]['sUsername'];
$smtphost = $login_data[0]['sServerName'];
$smptpport = $login_data[0]['sPorts'];
$smtpusername = $login_data[0]['sUsername'];
$smtppassword = $login_data[0]['sPassword'];
$subject = $champaign_data[0]['sEmailSubject'];
$msg = htmlspecialchars($_REQUEST['sEmailScript']);

$message = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/>       <style type='text/css'>            /* Ink styles go here in production */          </style>    <style type='text/css'>      /* Your custom styles go here */    </style>  </head>  <body>    <table class='body'>      <tr>        <td class='center' align='center' valign='top'>          <center> <img src= '".$url."' style='border:opx;' />".htmlspecialchars_decode($msg)." </center>        </td>      </tr>    </table>  </body></html>";

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = $smtphost;
$mail->Port = $smptpport; // or 587
$mail->IsHTML(true);
$mail->Username = $smtpusername;
$mail->Password = $smtppassword;
$mail->SetFrom($fromemail);
$mail->Subject = $subject;
$mail->Body = $message;
$mail->AddAddress($toemail);
 if(!$mail->Send()){
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else{
    echo "Message has been sent";
}
?>