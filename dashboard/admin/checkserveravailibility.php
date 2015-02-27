<?php
include("../library/function.php");


require_once $base_path."turbosmtp/lib/TurboApiClient.php";

$sUsername =  'costas@digitalcharis.com';//$_REQUEST['u'];
$sPassword = 'dkqHwtGX';//$_REQUEST['sPassword'];


$email = new Email();
$email->setFrom("priyank@zaptechsolutions.com");
$email->setToList("php.zaptest1@gmail.com");
$email->setSubject("subject");
$email->setContent("content");

if(($response['message'] == 'OK') || (isset($response['message']))){
	echo 'true';
}else{
	echo 'false';
}

?>