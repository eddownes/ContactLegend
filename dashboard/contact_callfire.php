<?php
include('library/function.php');
error_reporting(E_ALL);
$wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";

$client = new SoapClient($wsdl, array(
       'soap_version' => SOAP_1_2,
       'login'        => "739f9846f526", 
       'password'     => "217dbbed5c103071"
       )
); 


$where = "sc.bStatus = 0 ";
$table = 'schedulecalls as sc left join campaigncustomers as cc on cc.nCampaignCustId = sc.nCampaignCustId ';
$call_data 	=	getAnyData('cc.sCustomerFirstname,cc.sCustomerLastname,cc.sCustomerPhone,sc.nCampaignCustId',$table,$where,null,null);
$tot_call 	= 	count($call_data);
pr($call_data);die;
for($i=0;$i<$tot_call;$i++){
	$request = new stdClass();
	$request->ContactListId = 199503001; // long required
	$request->ContactSource = new stdClass(); // required
	$request->ContactSource->Contact = array();
	
	echo $call_data[$i]['sCustomerFirstname'].'<br/>';

	$request->ContactSource->Contact[$i]['firstName'] = $call_data[$i]['sCustomerFirstname'];
	$request->ContactSource->Contact[$i]['lastName'] = $call_data[$i]['sCustomerLastname'];
	$request->ContactSource->Contact[$i]['mobilePhone'] = $call_data[$i]['sCustomerPhone'];
	$data =	$client->AddContactsToList($request);

	pr($data);die;
}


//199503001
/*$request = new stdClass();
$request->Name = 'My Contacts'; // string required
$request->ContactSource = new stdClass(); // required
$request->ContactSource->ContactId = '57139509001 169746049001'; // required idList
$response = $client->AddContactsToList($request);
print_r($response);*/

/*$queryContactsRequest = array(
  "MaxResults"		=> "1000",
  "FirstResult"		=> "0"
);


$request->ContactSource->Contact[0]['homePhone'] = "13105551212";
$request->ContactSource->Contact[0]['workPhone'] = "13105551222";
$request->ContactSource->Contact[1]['firstName'] = "Jane";
$request->ContactSource->Contact[1]['lastName'] = "Doe";
$request->ContactSource->Contact[1]['mobilePhone'] = "13105552121";
$request->ContactSource->Contact[1]['homePhone'] = "13105552121";
$request->ContactSource->Contact[1]['workPhone'] = "13105552111";
$client->AddContactsToList($request);*/


/*$response = $client->QueryContacts($queryContactsRequest);
$contactArray = $response -> Contact;
echo "<pre>"; print_r($contactArray); die;

foreach ($contactArray as $key) {
	
	if(!isset($key -> firstName)){
		$where = "sCustomerPhone = ".$key -> homePhone;
		#echo $sql = "select sCustomerFirstname,sCustomerLastname from campaigncustomers where sCustomerPhone = ".$key -> homePhone;
		$customer_data 	=	getAnyData('sCustomerFirstname,sCustomerLastname','campaigncustomers',$where,null,null);
		$total_cust = count($customer_data);
		echo "<pre>"; print_r($customer_data);
		if(isset($customer_data[0]['sCustomerFirstname'])){
			$update_queryContactsRequest = array(
				"id"			=> $key -> id,
		  		"lastName"		=> $customer_data[0]['sCustomerLastname'],
		  		"firstName"		=> $customer_data[0]['sCustomerFirstname']
			);

			$update_response = $client->UpdateContacts($update_queryContactsRequest);
			pr($update_response);
		}

	}
}
*/
?>
