<?php
include('library/function.php');
include('smtpmail/classes/class.phpmailer.php');
error_reporting(E_ALL);
$current_time = strtotime(date("Y-m-d H:i:s"));
#$where = "bStatus = 0 ";

ini_set('default_socket_timeout', '120');
$where = "sc.bStatus = 0 ";
$table = 'schedulecalls as sc left join campaigncustomers as cc on cc.nCampaignCustId = sc.nCampaignCustId ';
$call_data 	=	getAnyData('sc.*,cc.sCustomerFirstname,cc.sCustomerLastname,cc.sCustomerPhone,cc.sCustomerEmail',$table,$where,null,null);
$tot_call 	= 	count($call_data);

// query to check whether cron is working by inserting into database below values
$sql = "insert into test_cronjob (`test_datetime`,`page`) VALUES ('".date("Y-m-d H:i:s")."','Auto Call')";
mysql_query($sql);


if($tot_call > 0) {
	for($i=0;$i<$tot_call;$i++) {
		
		$dtCreated 	 	= 	$call_data[$i]['dtCreated'];
		$dCallTime 		=  	$call_data[$i]['dCallTime'];
		$call_time		=  	strtotime($dCallTime);
		
		if($current_time >= $call_time) {

			$nCampaignid 	= 	$call_data[$i]['nCampaignid'];
			$soundId 		=  	$call_data[$i]['sSoundId'];
			
			$toNumber   	= 	$call_data[$i]['sCustomerPhone'];
			$toNumber = preg_replace('/[^0-9]/','',$toNumber);
			
			$toEmail   		= 	$call_data[$i]['sCustomerEmail'];

			if($soundId != '') {			
				$where 	= "nCampaignid = '".$nCampaignid."'";
				$table 	= "campaigns as c Left Join users as u on u.nUserId = c.nUserID";
				$field 	= 'c.nCallsMade,c.sCampaignname,u.sCallUsername,u.sCallPassword,u.nCallPhoneNo';
				$user_data	=	getAnyData($field,$table,$where,null,null);		
		    
				$fromNumber = $user_data[0]['nCallPhoneNo'];
				$fromNumber = preg_replace('/[^0-9]/','',$fromNumber);

				$wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";

				$client = new SoapClient($wsdl, array(
			    	'soap_version' => SOAP_1_2,
			    	'trace'		   => 1,
			        'login'        => $user_data[0]['sCallUsername'], 
			        'password'     => $user_data[0]['sCallPassword'])
				);

				$BroadcastName = $call_data[$i]['sCustomerFirstname'].' '.$call_data[$i]['sCustomerLastname'].' - '.$user_data[0]['sCampaignname'];

				#pr($client);

				/*$sendCallRequest = new stdclass();
				$sendCallRequest->BroadcastName = 'Example Broadcast';
				$sendCallRequest->ToNumber = $toNumber;
				$sendCallRequest->VoiceBroadcastConfig = new stdClass();
				$sendCallRequest->VoiceBroadcastConfig->FromNumber = $fromNumber; // PhoneNumber
				//$sendCallRequest->VoiceBroadcastConfig->AnsweringMachineConfig = 'LIVE_IMMEDIATE LIVE_WITH_AMD AM_AND_LIVE AM_ONLY'; // [AM_ONLY, AM_AND_LIVE, LIVE_WITH_AMD, LIVE_IMMEDIATE]
				$sendCallRequest->VoiceBroadcastConfig->AnsweringMachineConfig = 'LIVE_IMMEDIATE'; // [AM_ONLY, AM_AND_LIVE, LIVE_WITH_AMD, LIVE_IMMEDIATE]
				$sendCallRequest->VoiceBroadcastConfig->LiveSoundId = $soundId; // long
				$sendCallRequest->VoiceBroadcastConfig->LiveSoundId = $soundId; // long
				$sendCallRequest->VoiceBroadcastConfig->RetryConfig = new stdClass();
				$sendCallRequest->VoiceBroadcastConfig->RetryConfig->MaxAttempts = '2';
				$sendCallRequest->VoiceBroadcastConfig->RetryConfig->MinutesBetweenAttempts = '15';
				$sendCallRequest->VoiceBroadcastConfig->RetryConfig->RetryResults = 'BUSY NO_ANS'; */
				
				$sendCallRequest = array (
					'BroadcastName'        => $BroadcastName,
					'ToNumber'             => $toNumber,
					'VoiceBroadcastConfig' => array (
						'FromNumber'             => $fromNumber,
						'AnsweringMachineConfig' => 'AM_AND_LIVE',
						'LiveSoundId'            => $soundId,
						'MachineSoundId'         => $soundId,
						'LocalTimeZoneRestriction' => array (
							'BeginTime'  => '08:00:00',
							'EndTime'    => '20:00:00'
						),
						'RetryConfig'	     => array (
							'MaxAttempts'		  => '3',
							'MinutesBetweenAttempts'  => '15',
							'RetryResults'		  => 'BUSY,NO_ANS,AM'
						)
					)
				);

				#pr($sendCallRequest); 
				//$broadcastId = $client->sendCall($sendCallRequest);
				
				try {
				  	$broadcastId = $client->sendCall($sendCallRequest);

				  	$query = "update schedulecalls set sBroadcastId = '".$broadcastId."' , bStatus = '1' WHERE nScheduleCallId='".$call_data[$i]['nScheduleCallId']."'";
					$result = mysql_query($query);	
				} catch(Exception $e) {

				  	$message = "<table class='body'>
				  					<tr>
				  						<td valign='top'>
				  							<h1>Contact Legend</h1>
				  						</td>
				  					</tr>
				  					<tr>
				  						<td valign='top'>
				  							Currenty we got an error with callfire API.
				  						</td>
				  					</tr>
				  					<tr>
				  						<td valign='top'>
				  							You will get a call from contact legend once the callfire API error will get resolved.
				  						</td>
				  					</tr>
				  				</table>";
	    			$body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
	    						<html xmlns='http://www.w3.org/1999/xhtml'>  
		    						<head>    
		    							<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />   
		    							<meta name='viewport' content='width=device-width'/>
		    						</head>  
		    						<body>".$message."</body>
	    						</html>";
					$mail = new PHPMailer(true); // create a new object
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
					$mail->Subject = 'Contact lagend call notification';
			        $mail->Body = $body;
			        $mail->AddAddress($toEmail);
			        #$mail->Send();					

			        echo 'Message: ' .$e->getMessage();
			        $el = "\r\n ".$e->getMessage()."_".$nCampaignid."\r\n ";
			        error_log($el, 3, "error_log.log");
					//return;
				}

				echo 'Success';
			}
		}
	}
}
?>
