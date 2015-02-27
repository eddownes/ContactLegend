<?php
$toNumber = '16463679756';
$fromNumber = '5088281388';
#$soundId 	= '488242001';


$wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";
$client = new SoapClient($wsdl, array(
   'soap_version' => SOAP_1_2,
   'login'        => '6c4f00e88e9f', 
   'password'     => 'e206f9ab8eaf2d25')); 

$file_url = 'http://localhost/contactlegend/upload/galliyan.mp3';
$soundData = file_get_contents($file_url); 
$createSoundRequest = array(
    'Name' => 'My new sound',
    'Data' => $soundData);

$soundId = $client->createSound($createSoundRequest); 


$wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";
$client = new SoapClient($wsdl, array(
	'soap_version' => SOAP_1_2,
	'login'        => '6c4f00e88e9f', 
	'password'     => 'e206f9ab8eaf2d25'));

$sendCallRequest = array(
      'ToNumber'             => $toNumber,
      'VoiceBroadcastConfig' => array(
          'FromNumber'             => $fromNumber,
          'AnsweringMachineConfig' => 'LIVE_IMMEDIATE',
          'BroadcastName'          => 'Example Broadcast',
          'LiveSoundId'            => $soundId));

$broadcastId = $client->sendCall($sendCallRequest);

echo '<pre/>';
print_r($broadcastId);
die;
?>