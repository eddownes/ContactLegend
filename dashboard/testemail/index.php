<?php
$to = "php.zaptech@gmail.com";
$subject = "Test mail Template";

$message = file_get_contents("new_mail.html");
//$message = "test";
echo $message;

$from = "bhumik@zaptechsolutions.com";
//$headers = "From:" . $from;
//$headers = "From: . $from . \n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1";

$headers = "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";


$res = mail($to,$subject,$message,$headers);
if($res)
echo "SEND";
else
echo "Failed";
// mail("keyur@zapserver.com","Mail TEST","Live Mail SErver","From:keyurshah");
?> 