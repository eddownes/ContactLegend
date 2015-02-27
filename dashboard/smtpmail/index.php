<?php
include "classes/class.phpmailer.php"; // include the class name
$toemail = "chirag@zaptechsolutions.com";
$fromemail = "costas@digitalcharis.com";
$smtphost = "pro.turbo-smtp.com";
$smptpport = "465";
$smtpusername = "costas@digitalcharis.com";
$smtppassword = "dkqHwtGX";
$subject = "testing email";
$message = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/>       <style type='text/css'>            /* Ink styles go here in production */          </style>    <style type='text/css'>      /* Your custom styles go here */    </style>  </head>  <body>    <table class='body'>      <tr>        <td class='center' align='center' valign='top'>          <center>			<h1>This is test content</h1>          <!-- Email Content -->		<img src= 'http://innercirclestaging.com/smtpmail/email.php?c=1' />          </center>        </td>      </tr>    </table>  </body></html>";
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
$mail->AddAddress($toemail);//$mail->AddAttachment("http://innercirclestaging.com/smtpmail/email.php?c=1"); //AddAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream')
 if(!$mail->Send()){
	echo "<span style='color:#ff0000;'>Mailer Error: " . $mail->ErrorInfo."</span>";
}
else{
	echo "<span style='color:#006600;'>Message has been sent</span>";
}