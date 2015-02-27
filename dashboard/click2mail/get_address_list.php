<?php
include_once('Client/Click2mail_Mailingonline_Api.php');

// Create map with request parameters
//$params = array ('documentName' => 'Sample Letter', 'documentClass' => 'Letter 8.5 x 11', 'documentFormat' => 'PDF');
 $credentials = "costaspeppas:peppasGreekman2";
// Build Http query using params
//$query = http_build_query($params);
$listid = 155272;
//$url = "https://{costaspeppas}:{peppasGreekman2}@rest.click2mail.com/molpro/addressLists/".$listid;
$url = "https://{costaspeppas}:{peppasGreekman2}@rest.click2mail.com/molpro/addressLists
/numberOfLists=500";


$username = 'costaspeppas';
$password = 'peppasGreekman2';
//echo basename(TEST1.pdf); die;

$headers = array(
        	"method:GET",	        
	        //"Accept: text/xml",
	        "Cache-Control: no-cache",
	        "Pragma: no-cache",	
			//"Content-Type: application/xml",			
	       // "Content-length: ".strlen($fields),
	        "Authorization: Basic " . base64_encode($credentials)
        );

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
$data = curl_exec($ch); 
curl_close($ch);   
var_dump($data);
//echo $data;
die;

       
?>
