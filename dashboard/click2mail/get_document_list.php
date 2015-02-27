<?php
include_once('Client/Click2mail_Mailingonline_Api.php');

// Create map with request parameters
//$params = array ('documentName' => 'Sample Letter', 'documentClass' => 'Letter 8.5 x 11', 'documentFormat' => 'PDF');
 $credentials = "chirag_zaptech:Chirag123#";
// Build Http query using params
//$query = http_build_query($params);
//$listid = 19752;
$url = "https://stage-rest.click2mail.com/molpro/documents";


$username = 'chirag_zaptech';
$password = 'Chirag123#';
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
echo $data;
die;

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
        //curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
		
        // Apply the XML to our curl call
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $address_data); 

        $data = curl_exec($ch); 

        if (curl_errno($ch)) {
        	print "Error: " . curl_error($ch);
        } else {
        	// Show me the result
			echo "<pre>";
        	var_dump($data);
			echo "</pre>";
        	curl_close($ch);
        }
echo $data;
?>
