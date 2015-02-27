<?php
include_once('Client/Click2mail_Mailingonline_Api.php');

$credentials = "costaspeppas:peppasGreekman2";
$url = 'https://{costaspeppas}:{peppasGreekman2}@rest.click2mail.com/molpro/jobs
/documentClass = "Letter 8.5 x 11"
/layout = "Address on Separate Page"
/productionTime = "Next Day"
/envelope = "#10 Double Window"
/color = "Black and White"
/paperType = "White 24#"
/printOption = "Printing One side"
/documentId = "119089"
/addressId = "155272"';

/*$fields = array (
    'documentClass'  => 'Letter 8.5 x 11',
    'layout' 		     => 'Address on Separate Page',
    'productionTime' => 'Next Day',
    'envelope' 		   => '#10 Double Window',
    'color' 		     => 'Black and White',
    'paperType' 	   => 'White 24#',
    'printOption' 	 => 'Printing One side',
    'documentId' 	   => '119089',
    'addressId' 	   => '155272'
);*/

$headers = array (
	"method:POST",
	"Content-Type: text/plain;charset=utf-8",
  "Authorization: Basic ".base64_encode($credentials)
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_TIMEOUT, 600);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Apply the XML to our curl call
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
//curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
 
$response = curl_exec($ch); 

$info = curl_getinfo($ch);
echo "<pre>"; print_r($info);

if (curl_errno($ch)) {
  print curl_error($ch);
  print "<br>Unable to create address list.";
  exit();
}

print_r($response);

/*$responseArray = json_decode(json_encode((array) simplexml_load_string($response)), 1);
echo "<pre>";
print_r($responseArray);*/

curl_close($ch);

die("Done");

?>
