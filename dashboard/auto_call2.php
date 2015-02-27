<?php
include('library/function.php');

error_reporting(E_ALL);
$current_time = strtotime(date("Y-m-d H:i:s"));
$where = "bStatus = 1 AND bCallStatus = 0 AND sCallState NOT LIKE 'INVALID'";
$call_data 	=	getAnyData('*','schedulecalls',$where,null,null);
$tot_call 	= 	count($call_data);

// query to check whether cron is working by inserting into database below values
$sql = "insert into test_cronjob (`test_datetime`,`page`) VALUES ('".date("Y-m-d H:i:s")."','Auto Call2')";
mysql_query($sql);


if($tot_call > 0) {

	for($i=0;$i<$tot_call;$i++) {
		
		$dtCreated 	 	= 	$call_data[$i]['dtCreated'];
		$dCallTime 		=  	$call_data[$i]['dCallTime'];
		$call_time		=  	strtotime($dCallTime);
		
		if($current_time >= $call_time) {

			$broadcastId   	= 	$call_data[$i]['sBroadcastId'];
			$nCampaignid 	= 	$call_data[$i]['nCampaignid'];

			
			$where 	= "nCampaignid = '".$nCampaignid."'";

			$where 	= "nCampaignid = '".$nCampaignid."'";
			$table 	= "campaigns as c Left Join users as u on u.nUserId = c.nUserID";
			$field 	= 'c.nCallsMade,u.sCallUsername,u.sCallPassword,u.nCallPhoneNo';
			$user_data	=	getAnyData($field,$table,$where,null,null);


			/*$wsdl = "http://callfire.com/api/1.1/wsdl/callfire-service-http-soap12.wsdl";
			$client = new SoapClient($wsdl, array(
				'soap_version' => SOAP_1_2,
				'login'        => $user_data[0]['sCallUsername'], 
				'password'     => $user_data[0]['sCallPassword'])
			); */

			$apiUsername = $user_data[0]['sCallUsername'];
			$apiPassword = $user_data[0]['sCallPassword'];

			$url = "https://www.callfire.com/api/1.1/rest/call?BroadcastId=".$broadcastId;
			$credentials = $apiUsername.":".$apiPassword;
			$headers = array (
			    "method:GET",	        
			    "Cache-Control: no-cache",
			    "Pragma: no-cache",	
			    "Content-Type: application/xml",			
			    "Authorization: Basic ".base64_encode($credentials)
			);

			$ch1 = curl_init();
		    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch1, CURLOPT_URL, $url);
		    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		    $res = curl_exec($ch1);
		    curl_close($ch1);

		    $responseArray = json_decode(json_encode((array) simplexml_load_string($res)), 1);

		    pr($responseArray); 

		    /* Response Array
				Array
				(
				    [@attributes] => Array
				        (
				            [totalResults] => 1
				        )

				    [Call] => Array
				        (
				            [@attributes] => Array
				                (
				                    [id] => 312799843003
				                )

				            [FromNumber] => 16463679756
				            [ToNumber] => 16155456314
				            [State] => FINISHED
				            [BatchId] => 1731315003
				            [BroadcastId] => 2499267003
				            [ContactId] => 232881175003
				            [Inbound] => false
				            [Created] => 2014-10-21T16:20:04Z
				            [Modified] => 2014-10-21T16:20:43Z
				            [FinalResult] => LA
				            [CallRecord] => Array
				                (
				                    [@attributes] => Array
				                        (
				                            [id] => 182506425003
				                        )

				                    [Result] => LA
				                    [FinishTime] => 2014-10-21T16:20:42Z
				                    [BilledAmount] => 1.0
				                    [OriginateTime] => 2014-10-21T16:20:04Z
				                    [AnswerTime] => 2014-10-21T16:20:14Z
				                    [Duration] => 28
				                )

				        )

				)
		    */

			// $query = new stdclass();
			// $query->MaxResults = 100; // long   
			// $query->FirstResult = 0; // long   
			// $query->BroadcastId = (int)$broadcastId; // long 2476255003
			// $query = (array) $query;
			
			// $response = $client->QueryCalls($query);
			//pr($response);

			// $id = $response->Call->id;
			
			// $request = new stdclass();
			// $request->Id = $id;
			// $callresponse = $client->GetCall($request);
			$State =  $responseArray["Call"]["State"]; 

			if($State != "FINISHED"){
				$query = "update schedulecalls set sCallState = '".$State."' WHERE nScheduleCallId='".$call_data[$i]['nScheduleCallId']."'";
				$result = mysql_query($query);	
			} else {

				$sCallStartTime 	= 	$responseArray["Call"]["CallRecord"]["OriginateTime"];
				$sCallEndTime 		=	$responseArray["Call"]["CallRecord"]["FinishTime"];
				$sCallFinalResult 	=	$responseArray["Call"]["FinalResult"];
				$sCallDuration 		=	$responseArray["Call"]["CallRecord"]["Duration"];

				$query1 = "update schedulecalls set 
						sCallState = '".$State."', 
						bCallStatus = '1',
						sCallStartTime = '".$sCallStartTime."',
						sCallEndTime = '".$sCallEndTime."',
						sCallFinalResult = '".$sCallFinalResult."',
						sCallDuration = '".$sCallDuration."' 
					WHERE nScheduleCallId='".$call_data[$i]['nScheduleCallId']."'";
				$result1 = mysql_query($query1);	

				$nCallsMade = $user_data[0]['nCallsMade'] + 1;
				$query2 = "update campaigns set nCallsMade = '".$nCallsMade."' WHERE nCampaignid='".$nCampaignid."'";
				$result2 = mysql_query($query2);
			}

			echo 'Success';
		}
	}
}
?>
