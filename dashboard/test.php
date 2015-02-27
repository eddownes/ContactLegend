<?php
include('smtpmail/classes/class.phpmailer.php');


$current_time = strtotime(date("Y-m-d h:i:s"));
echo 'Current Time	'.$current_time;
echo '<br/>';
$call_time		=  	strtotime("2014-06-19 05:45:36");
echo "Call time  	".$call_time;
die;
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
/*$mail->Host = 'smtp.gmail.com';
$mail->Port = '465'; // or 587
$mail->IsHTML(true);
$mail->Username = 'zaptest12@gmail.com';
$mail->Password = 'temp123#';*/

$mail->Host = 'pro.turbo-smtp.com'; //smtp.gmail.com;
$mail->Port = '465'; // or 587
$mail->IsHTML(true);
$mail->Username = 'costas@digitalcharis.com';//'php.zaptech@gmail.com';
$mail->Password = 'dkqHwtGX';//zaptech123#';


$mail->Subject = 'test';
$mail->Body = 'tetetetettet';
$toemail = 'abhishek@zaptechsolutions.com';    
$mail->AddAddress($toemail);
 if(!$mail->Send()){
    echo "Mailer Error: " . $mail->ErrorInfo;die;
}
else{
    echo "Message has been sent";die;
}


?>
