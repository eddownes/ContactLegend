<?php
require_once('class.phpmailer.php');

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->Username = 'parth.c@zaptechsolutions.com';
$mail->Password = 'zaptech1414i';
$mail->SMTPAuth = true;

$mail->From = 'parth.c@zaptechsolutions.com';
$mail->FromName = 'Parth';
$mail->AddAddress('parth.c@zaptechsolutions.com');
$mail->AddReplyTo('parth.c@zaptechsolutions.com', 'Information');

$mail->IsHTML(true);
$mail->Subject    = "PHPMailer Test Subject via Sendmail, basic";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
$mail->Body    = "Hello";

if(!$mail->Send())
{
  echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
  echo "Message sent!";
}
?>
