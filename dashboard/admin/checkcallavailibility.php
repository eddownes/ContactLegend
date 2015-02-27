<?php
include("../library/function.php");

$wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";

$sUsername = $_REQUEST['u'];
$sPassword = $_REQUEST['sCallPassword'];
                
$client = new SoapClient($wsdl, array(
   'soap_version' => SOAP_1_2,
   'login'        => $sUsername, 
   'password'     => $sPassword));    

$file_url = $base_url.'Banjaara.mp3';
$soundData = file_get_contents($file_url); 
$createSoundRequest = array(
    'Name' => 'My new sound',
    'Data' => $soundData);

$soundId = $client->createSound($createSoundRequest);

if($soundId != ''){
	echo 'true';
}
else{
	echo 'false';
}
?>