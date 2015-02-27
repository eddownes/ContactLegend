<?php
include_once('Client/Click2mail_Mailingonline_Api.php');

// Create map with request parameters
//$params = array ('documentName' => 'Sample Letter', 'documentClass' => 'Letter 8.5 x 11', 'documentFormat' => 'PDF');
 $credentials = "chirag_zaptech:Chirag123#";
// Build Http query using params
//$query = http_build_query($params);
$url = "https://stage-rest.click2mail.com/molpro/documents";


$username = 'chirag_zaptech';
$password = 'Chirag123#';
//echo basename(TEST1.pdf); die;
$fields = array(
    'documentName' => 'Sample Letter',
    'documentClass' => 'Letter 8.5 x 11',
	'documentFormat' => 'PDF',
	//'file' => '@TEST1.pdf',
	'file' => '@D:/wamp/www/click2mail/TEST1.pdf',
	
);

$headers = array(
        	"method:POST",	        
	        //"Accept: text/xml",
	        "Cache-Control: no-cache",
	        "Pragma: no-cache",	        
	       // "Content-length: ".strlen($fields),
	        "Authorization: Basic " . base64_encode($credentials)
        );

$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
        //curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
		
        // Apply the XML to our curl call
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); 

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
