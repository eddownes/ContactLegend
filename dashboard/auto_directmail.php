<?php
include('library/function.php');
include('smtpmail/classes/class.phpmailer.php');
include_once('click2mail/Client/Click2mail_Mailingonline_Api.php');
error_reporting(E_ALL);
ini_set('default_socket_timeout', 120);

// query to check whether cron is working by inserting into database below values
$sql = "insert into test_cronjob (`test_datetime`,`page`) VALUES ('".date("Y-m-d H:i:s")."','Direct Mail')";
mysql_query($sql);

$whr = 'nDirectEmailStatus = 0';
$where = "bStatus = 0 ";
$directmail_data =	getAnyData('*','directemail',$whr,null,null);
$tot_directmail = count($directmail_data);

$where = "bStatus = 0 ";
$direct_data =	getAnyData('*','scheduledirectmails',$where,null,null);
$tot_direct = count($direct_data);

if($tot_directmail > 0)
{
	for($j=0;$j<$tot_directmail;$j++)
	{
		if($tot_direct > 0)
		{
			try {

				$address_data = '';
				$nScheduleDirectMailD = '';
				$nMailSent = '';
				$nCampaignid = '';
				$address_data = "<addressList>
					    <addressListName>Customers_".time()."</addressListName>
					    <addressMappingId>2</addressMappingId>
					    <addresses>";
				$sDirectmailUsername = '';
				$sDirectmailPassword = '';
				for($i=0;$i<$tot_direct;$i++){
					if($directmail_data[$j]['nDocumentId'] == $direct_data[$i]['nDocumentId']){	

						$nCampaignCustId 	= 	$direct_data[$i]['nCampaignCustId'];		
						$nDocumentId 		=  	$direct_data[$i]['nDocumentId'];
						$nCampaignid  		= 	$direct_data[$i]['nCampaignid'];
						$bNotifyEmail  		= 	$direct_data[$i]['bNotifyEmail'];

						$nScheduleDirectMailD.= $direct_data[$i]['nScheduleDirectMailD'].',';


						$where 	= "nCampaignid = '".$nCampaignid."'";
						$table 	= "  campaigns as c Left Join users as u on u.nUserId = c.nUserID 
										Left Join user_billdetails as ub on ub.nUserId = u.nUserID";
						$field 	=  'c.nMailSent,u.sDirectmailUsername,u.sUserEmail,u.sDirectmailPassword,ub.*';
						$user_data		=	getAnyData($field,$table,$where,null,null);

						$nMailSent = $user_data[0]['nMailSent'] + 1;
						
						$where = 'nCampaignCustId = '.$nCampaignCustId;
						$customer_data = getAnyData('*','campaigncustomers',$where,null,null);
						
						$sDirectmailUsername = $user_data[0]['sDirectmailUsername'];
						$sDirectmailPassword = $user_data[0]['sDirectmailPassword'];
						$sUserEmail = $user_data[0]['sUserEmail'];
						
						$credentials = "$sDirectmailUsername:$sDirectmailPassword";
						$url = "https://{$sDirectmailUsername}:{$sDirectmailPassword}@rest.click2mail.com/molpro/addressLists";

						if($customer_data['0']['sCustomerFirstname'] != "" && $customer_data['0']['sCustomerLastname'] != "" && $customer_data['0']['sCustomerAddress1'] != "" && $customer_data['0']['sCustomerAddress2'] != "" && $customer_data['0']['sCustomerCity'] != "" && $customer_data['0']['sCustomerState'] != "" && $customer_data['0']['sCustomerZip'] != ""){
							$address_data .= "
							        <address>
							            <First_name>".$customer_data['0']['sCustomerFirstname']."</First_name>
							            <Last_name>".$customer_data['0']['sCustomerLastname']."</Last_name>
							            <Organization></Organization>
							            <Address1>".$customer_data['0']['sCustomerAddress1']."</Address1>
							            <Address2>".$customer_data['0']['sCustomerAddress2']."</Address2>
							            <Address3>".$customer_data['0']['sCustomerAddress3']."</Address3>
							            <City>".$customer_data['0']['sCustomerCity']."</City>
							            <State>".$customer_data['0']['sCustomerState']."</State>
							            <Zip>".$customer_data['0']['sCustomerZip']."</Zip>
							            <Country_non-US>".$customer_data['0']['sCustomerCountry']."</Country_non-US>
							        </address>
							        ";
						}
					}
				}
				$address_data.="
					    </addresses>
					</addressList>";
				
				$nScheduleDirectMailD = rtrim($nScheduleDirectMailD,",");

				if($sDirectmailUsername != '' && $sDirectmailPassword != '')
				{
						
					$whr = "nCampaignid = '".$nCampaignid."'";
					$sql_cust = getAnyData('count(*) as total_cust','campaigncustomers',$whr,null,null);
					$tot_charge = ($sql_cust[0]['total_cust'] * 1.50);
					$tot_charge =  1.50;

					$crediturl = 'https://{$sDirectmailUsername}:{$sDirectmailPassword}@rest.click2mail.com/molpro/credit';
					$headers = array (
					    "method:POST",	        
					    "Cache-Control: no-cache",
					    "Pragma: no-cache",	
					    "Content-Type: application/xml",			
					    "Authorization: Basic ".base64_encode($credentials)
					); 

					$ch1 = curl_init();
				    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
				    curl_setopt($ch1, CURLOPT_URL, $crediturl);
				    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
				    $Balance = curl_exec($ch1);
				    curl_close($ch1);
				    preg_match_all('!\d+!', $Balance, $matches);
					$balance =  $matches[0][3];

				    if((floatval($balance) < floatval($tot_charge)) && ($bNotifyEmail == 0))
					{
						$click2mailurl = 'https://click2mail.com/usercredits';
						$message = "<table class='body'><tr><td class='center' align='center' valign='top'><center><h1>Contact Legend</h1></center></td></tr><tr><td class='center' align='center' valign='top'><center>There was a problem sending out your direct mail pieces. Your Click2Mail account has run out of credits.</center></td></tr><tr><td class='center' align='center' valign='top'><center>Please <a herf='".$click2mailurl."' target='_blank'>visit Click2Mail</a> add credits to your account so we can process your order.</center></td></tr></table>";
						
	        			$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/></head>  <body>".$message."</body></html>";

						$mail = new PHPMailer(); // create a new object
				        $mail->IsSMTP(); // enable SMTP
				        $mail->IsHTML(true);
				        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only        
				        $mail->SMTPAuth   = true;                  // enable SMTP authentication
				        //$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
				        $mail->Host       = "72.52.245.168";      // sets GMAIL as the SMTP server
				        $mail->Port       = 25;                   // set the SMTP port for the GMAIL server
				        $mail->Username   = "no-reply@contactlegend.com";  // GMAIL username
				        $mail->Password   = "t6[n6c{Py5Q1";
				        $mail->SetFrom('no-reply@contactlegend.com');
						$mail->Subject = 'Direct Mail Notification';
				        $mail->Body = $body;
				        $mail->AddAddress($sUserEmail);
				        $mail->Send();
						
						$el = "\r\n Direct Mail Credits for campaign id: #".$nCampaignid." \r\n ";
			        	error_log($el, 3, "error_log.log");

						echo $sql_update = "UPDATE scheduledirectmails set bNotifyEmail = 1 where nScheduleDirectMailD in (".$nScheduleDirectMailD.")";
						mysql_query($sql_update);
						return;
					}

					//echo $sql_update = "UPDATE scheduledirectmails set bNotifyEmail = 0 where nScheduleDirectMailD in (".$nScheduleDirectMailD.")";
					//mysql_query($sql_update);

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

					$el = "\r\n[".date('Y-m-d H:i:s')."] : Create Address List API Call \r\n ";
			        error_log($el, 3, "click2mail_log.log");
					$response = curl_exec($ch); 
					//pr($response); die;
					if(curl_errno($ch)) {
					  print curl_error($ch);
					  print "<br>Unable to create address list.";
					  $el = "\r\n Unable to create address list: #".$nCampaignid." \r\n ";
			          error_log($el, 3, "error_log.log");
					  exit();
					}			

					//$responseArray = json_decode(json_encode((array) simplexml_load_string($response)), 1);
					$xml = simplexml_load_string($response);
					$json = json_encode($xml);
					$responseArray = json_decode($json,TRUE);
					pr($responseArray); 
					curl_close($ch);			
					$nScheduleDirectMailD = rtrim($nScheduleDirectMailD,',');
					
					$soap_url = "https://soap.click2mail.com/";
					$document_id = $directmail_data[$j]['nDocumentId'];
					$data_list_id = $responseArray['id'];
					$api = new Click2mail_Mailingonline_Api($soap_url, $sDirectmailUsername, $sDirectmailPassword);
					/* $job_template_id = $responseArray['id'];
					$return_address = array(
						'name' => $user_data[0]['sName'], 
						'business' => $user_data[0]['sCompany'], 
						'address' => $user_data[0]['sAddress1']." ".$user_data[0]['sAddress2'], 
						'city' => $user_data[0]['sCity'], 
						'state' => $user_data[0]['sState'], 
						'zip' => $user_data[0]['sZip'], 
						'country' => $user_data[0]['sCountry'], 
						'ancillary_endorsement' => ' '
					); 

					
					//$response = $api->createJob($document_id,$data_list_id,$job_template_id,$return_address);
					$response = $api->createJob($document_id,$data_list_id,$document_id,$return_address);
					//pr($response);die;*/

					$rest_url='https://rest.click2mail.com/molpro/jobs';
					$username=$sDirectmailUsername;
					$password=$sDirectmailPassword;

					$fields=array(
						'documentClass' => urlencode("Letter 8.5 x 11"),
						'layout' => urlencode("Address on Separate Page"),
						'productionTime' => urlencode("Next Day"),
						'envelope' => urlencode("#10 Double Window"),
						'color' => urlencode("Black and White"),
						'paperType' => urlencode("White 24#"),
						'printOption' => urlencode("Printing both sides"),
						'documentId' => urlencode($document_id),
						'addressId' => urlencode($data_list_id)
					);

					$fields_string='';
					foreach ($fields as $key => $value){
						$fields_string .= $key.'='.$value.'&';
					}
					rtrim($fields_string, '&');

					$ch=curl_init();
					curl_setopt($ch,CURLOPT_URL, $rest_url);
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_USERPWD,"$username:$password");
					curl_setopt($ch,CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					$result=curl_exec($ch);
					curl_close($ch);
					//echo $result; 

					//$resultArray = json_decode(json_encode((array) simplexml_load_string($result)), 1);
					$xml1 = simplexml_load_string($result);
					$json1 = json_encode($xml1);
					$resultArray = json_decode($json1,TRUE);
					$el = "\r\n[".date('Y-m-d H:i:s')."] : Create Job API Call \r\n ";
			        error_log($el, 3, "click2mail_log.log");
					pr($resultArray); //die;

					$job_id = $resultArray["id"];
					echo "job id is " . $job_id . "\n";

					//die("soap api Create Job Done");

					echo "//submit a job \n";
					
					$billing_details = array (
						'bill_company' => $user_data[0]['sCompany'], //Required
						'bill_type' => "PrePaid" //Required
					);
					$el = "\r\n[".date('Y-m-d H:i:s')."] : Submit Job API Call \r\n ";
			        error_log($el, 3, "click2mail_log.log");
					//Pass empty string if no value is required
					$response1 = $api -> SubmitJob($job_id, $billing_details);
					// print_r($response);

					echo "job submission sucessful?? " . $response1 -> submit_job_result -> description . "\n";
					echo "job submission success id " . $response1 -> submit_job_result -> status_id . "\n";
					echo "<pre>"; print_r($response1); echo "\n";

					//die("soap api Submit Job Done");

					//check job status
					$response = $api -> CheckJobStatus($job_id);
					$sJobStatus = $response -> CheckJobStatusResult -> description;
					echo "<pre>"; print_r($response); echo "\n";
					echo "job status for the submitted job " . $response -> CheckJobStatusResult -> description . "\n";
					echo "job status id for the submitted job " . $response -> CheckJobStatusResult -> status_id . "\n";

					//view the proof
					$response = $api->CreateProof($job_id);
					echo "<pre>"; print_r($response); echo "\n";
					echo "proof url for the submitted job  " . $response -> preview_id . "\n\n";
					$el = "\r\n[".date('Y-m-d H:i:s')."] : Create Proof API Call \r\n ";
			        error_log($el, 3, "click2mail_log.log");
					$sql_update = "Update scheduledirectmails set bStatus = 1, sJobStatus = '".$sJobStatus."', nAddressListId = '".$responseArray['id']."' where nScheduleDirectMailD in (".$nScheduleDirectMailD.")";
					mysql_query($sql_update);

					$nEmailFollowUps = $nMailSent;
					$query = "update campaigns set nMailSent = '".$nMailSent."' WHERE nCampaignid ='".$nCampaignid."'";
					$result = mysql_query($query);	
				
					$nJobId = $job_id;
					if($directmail_data[$j]['nJobId'] != ''){
						$nJobId = $directmail_data[$j]['nJobId'].",".$job_id;	
					}
				
					$nAddressListId = $responseArray['id'];
					if($directmail_data[$j]['nAddressListId'] != ''){
						$nAddressListId = $directmail_data[$j]['nAddressListId'].",".$responseArray['id'];
					}
				
					$sql_update1 = "Update directemail set nDirectEmailStatus = 1 , nJobId = '".$nJobId."', nAddressListId = '".$nAddressListId."' where nDirectEmailId = ".$directmail_data[$j]['nDirectEmailId'];
					mysql_query($sql_update1);

					#echo '=================================================================================<br/>';
				}
			} catch(Exception $e) {
				print $e;
			}
		}
	}
}
?>
