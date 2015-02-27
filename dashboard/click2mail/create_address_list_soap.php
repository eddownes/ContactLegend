<?php
include_once('Client/Click2mail_Mailingonline_Api.php');

// Create map with request parameters
//$params = array ('documentName' => 'Sample Letter', 'documentClass' => 'Letter 8.5 x 11', 'documentFormat' => 'PDF');
 $credentials = "chirag_zaptech:Chirag123#";
// Build Http query using params
//$query = http_build_query($params);

$url = "http://stage-soap.click2mail.com/";


$username = 'chirag_zaptech';
$password = 'Chirag123#';
//echo basename(TEST1.pdf); die;
$client_app_id = $username;
$client_app_pwd = $password;
$address_mapping_id = 2;
$api = new Click2mail_Mailingonline_Api($url, $username, $password);

$data = array(0 => array(
				array('name' => 'First_name', 'value' => 'chirag'),
				array('name' => 'Last_name', 'value' => 'patel'),
				array('name' => 'Organization', 'value' => 'patel'),
				array('name' => 'Address1', 'value' => '41 sigma house'),
				array('name' => 'Address2', 'value' => 'sindhubhavan'),
				array('name' => 'Address3', 'value' => 'se'),
				array('name' => 'City', 'value' => 'Denever'),
				array('name' => 'State', 'value' => 'CA'),
				array('name' => 'Zip', 'value' => '55555'),
				array('name' => 'Country_non-US', 'value' => '')
						),						
			 1 => array(
				array('name' => 'First_name', 'value' => 'gfd'),
				array('name' => 'Last_name', 'value' => 'dg'),
				array('name' => 'Organization', 'value' => 'dfg'),
				array('name' => 'Address1', 'value' => '41 sigmadgfhouse'),
				array('name' => 'Address2', 'value' => 'sindhufdgbhavan'),
				array('name' => 'Address3', 'value' => 'se'),
				array('name' => 'City', 'value' => 'Denever'),
				array('name' => 'State', 'value' => 'VA'),
				array('name' => 'Zip', 'value' => '554355'),
				array('name' => 'Country_non-US', 'value' => '')
						));						

$response = $api->CreateAddressList($address_mapping_id, $data);
print_r($response);
die;

$address_data = "<addressList>
  <addressListName>Best Customers</addressListName>
  <addressMappingId>2</addressMappingId>
  <addresses>
    <address>
      <First_name>John</First_name>
	  <Last_name>Smith</Last_name>
	  <Organization>My Business</Organization>
      <Address1>123 Some Street</Address1>
	  <Address2>Suite 210</Address2>
	  <Address3></Address3>
      <City>Somewhere</City>
      <State>Va</State>
      <Zip>12345</Zip>
	  <Country_non-US></Country_non-US>
    </address>
	<address>
      <First_name>Mary</First_name>
	  <Last_name>Jason</Last_name>
	  <Organization>My Business</Organization>
      <Address1>123 Some Street</Address1>
	  <Address2>Suite 210</Address2>
	  <Address3></Address3>
      <City>Rio de Janerio</City>
      <State>CEP</State>
      <Zip>222021 001</Zip>
	  <Country_non-US>Brazil</Country_non-US>
    </address>
  </addresses>
</addressList>";

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
			"Content-Type: application/xml",			
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, $address_data); 

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
