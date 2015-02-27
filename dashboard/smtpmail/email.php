<?php
include('../library/function.php');

$nScheduleFollowMailId = mysql_real_escape_string($_GET['f']);
$nCampaignCustId = mysql_real_escape_string($_GET['c']);

if($nScheduleFollowMailId != ''){
	$query = "update schedulefollowemails set bFollowUpMailOpen = '1' WHERE nScheduleFollowMailId='".$nScheduleFollowMailId."'";
	$result = mysql_query($query);	
}
else{

	$where = "nCampaignCustId = '".$nCampaignCustId."'";
	$data = getAnyData('nCampaignid,eEmailOpened,sCustomerEmail,sCustomerPhone','campaigncustomers',$where,null,null);

	$nCampaignid = $data[0]['nCampaignid'];
	$eEmailOpened = $data[0]['eEmailOpened'];
	$sCustomerEmail = $data[0]['sCustomerEmail'];
	$sCustomerPhone = $data[0]['sCustomerPhone'];
	
	$date = date("Y-m-d h:i:s");
	
	if($eEmailOpened == '0'){

		$query = "update campaigncustomers set eEmailOpened = '1' , dtUpdated = '".$date."' WHERE nCampaignCustId='".$nCampaignCustId."'";
		$result = mysql_query($query);

		$where = "c.nCampaignid = '".$nCampaignid."'";
		$table  = 'campaigns as c';
		$campaign_data 	=	getAnyData('c.dCreatedDate,c.nEmailsOpened,c.nAvgDays,c.nFollowEmail,c.nCall,c.nDirectEmail',$table,$where,null,null);

		$nFollowEmail 	= 	$campaign_data[0]['nFollowEmail'];
		$nCall 			= 	$campaign_data[0]['nCall'];
		$nDirectEmail 	= 	$campaign_data[0]['nDirectEmail'];
		$nAvgDays 		= 	$campaign_data[0]['nAvgDays'];
		$dCreatedDate	= 	$campaign_data[0]['dCreatedDate'];
		
		if($nFollowEmail == 1){

			$where 	= 	"nCampaignid = '".$nCampaignid."'";
			$f_data =	getAnyData('nFollowupId,dScheduleTime,dtCreated','followupEmail',$where,null,null);

			$dScheduleTime = $f_data[0]['dScheduleTime'];	
			
			$timeArray = explode(":", $dScheduleTime);

			$hours = $timeArray[0];
			$minutes = $timeArray[1];
			$seconds = $timeArray[2];

			$newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +".$hours." hours"));
			$newTime = date("Y-m-d H:i:s",strtotime($newTime." +".$minutes." minutes"));
			$newTime = date("Y-m-d H:i:s",strtotime($newTime." +".$seconds." seconds"));

			//$dScheduleTime = strtotime($dScheduleTime) + strtotime(date("H:i:s"));
			
			$followup_data['nCampaignid'] 		=	$nCampaignid;
			$followup_data['nFollowupId'] 		=	$f_data[0]['nFollowupId'];
			$followup_data['dScheduleTime'] 	=	$newTime;
			$followup_data['nCampaignCustId'] 	= 	$nCampaignCustId;
			$followup_data['sCustomerEmail'] 	= 	$sCustomerEmail;
			$followup_data['dtCreated'] 		= 	date("Y-m-d H:i:s");
			$followup_data['bStatus'] 			= 	0;

			$schedult_followupid = dbRowInsert('schedulefollowemails', $followup_data);

		}

		if($nCall == 1){

			$where 	= 	"nCampaignid = '".$nCampaignid."'";
			$c_data =	getAnyData('dCallSchedTime,nCallId,sSoundId','calls',$where,null,null);
			
			#$dCallSchedTime = strtotime($c_data[0]['dCallSchedTime']) + strtotime(date("H:i:s"));		
			$dCallSchedTime = $c_data[0]['dCallSchedTime'];

			$timeCallArray = explode(":", $dCallSchedTime);

			$cHours = $timeCallArray[0];
			$cMinutes = $timeCallArray[1];
			$cSeconds = $timeCallArray[2];

			$newTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +".$cHours." hours"));
			$newTime = date("Y-m-d H:i:s",strtotime($newTime." +".$cMinutes." minutes"));
			$newTime = date("Y-m-d H:i:s",strtotime($newTime." +".$cSeconds." seconds"));
			
			$call_data['nCampaignid'] 		=	$nCampaignid;
			$call_data['nCampaignCustId'] 	= 	$nCampaignCustId;
			$call_data['nCallId'] 			= 	$c_data[0]['nCallId'];
			$call_data['sSoundId'] 			= 	$c_data[0]['sSoundId'];
			$call_data['sCustomerPhone']	= 	$sCustomerPhone;
			$call_data['dCallTime'] 		=	$newTime;
			$call_data['dtCreated'] 		= 	date("Y-m-d H:i:s");
			$call_data['bStatus'] 			= 	0;		
		
	    	$schedult_followupid = dbRowInsert('schedulecalls', $call_data);
		}

		if($nDirectEmail == 1){		

			$where 	= 	"nCampaignid = '".$nCampaignid."'";
			$d_data =	getAnyData('nDirectEmailId,nDocumentId','directemail',$where,null,null);
			
			$direct_data['nCampaignid'] 		=	$nCampaignid;
			$direct_data['nDirectEmailId'] 		= 	$d_data[0]['nDirectEmailId'];
			$direct_data['nDocumentId'] 		= 	$d_data[0]['nDocumentId'];
			$direct_data['nCampaignCustId'] 	= 	$nCampaignCustId;
			$direct_data['dtCreated'] 			= 	date("Y-m-d");
			$direct_data['bStatus'] 			= 	0;

	    	$schedult_followupid = dbRowInsert('scheduledirectmails', $direct_data);

	    	$query = "update directemail set nDirectEmailStatus = '0' WHERE nDirectEmailId='".$d_data[0]['nDirectEmailId']."'";
			$result = mysql_query($query);
		}

		$d1 = explode(' ',$dCreatedDate);    
	    $now = time();
	    $your_date = strtotime($d1[0]);
	    $datediff = $now - $your_date;
	    $diff_days =  floor($datediff/(60*60*24));
	    $day = (($diff_days + $nAvgDays)/2);

		$nEmailsOpened 	= 	$campaign_data[0]['nEmailsOpened'] + 1;
		$query = "update campaigns set nAvgDays = '".$nAvgDays."', nEmailsOpened = '".$nEmailsOpened."' WHERE nCampaignid='".$nCampaignid."'";
		$result = mysql_query($query);	
	}
}
header("Location: $base_url");exit;
?>
