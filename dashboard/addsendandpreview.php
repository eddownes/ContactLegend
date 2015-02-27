<?php
include('library/function.php');

$id = $_GET['id'];
$f = $_GET['f'];

// Check Smtp Details before Starting campaign
$where = "nUserID = '".$_SESSION['nUserID']."'";
$table = "user_smtpdetails";
$user_smtp_data = getAnyData('sServerName,sUsername,sPassword,sPorts',$table,$where,null,null);

if($user_smtp_data[0]['sServerName'] == '' || $user_smtp_data[0]['sUsername'] == '' || $user_smtp_data[0]['sPassword'] == '' || $user_smtp_data[0]['sPorts'] == '') {
	$_SESSION['error_msg'] = 'Please Enter SMTP details from account section to start campaign.';
    header('location:sendandpreview.php?id='.$id.'&f='.$f);
	exit;
}


// get users callfire details and uploaded file details
$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users";
$user_data = getAnyData('sCallUsername,sCallPassword',$table,$where,null,null);

$where = "nCallId = '".$_SESSION['nCallId']."'";
$automatedCalldata = getAnyData('*','calls',$where,null,null);
$sCallScript = $automatedCalldata[0]['sCallScript'];

if(isset($sCallScript)) {
	if($user_data[0]['sCallUsername'] != '' &&	$user_data[0]['sCallPassword']) {

		$file_url = $upload_url.$sCallScript;
	    $wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";
	    
	    $client = new SoapClient($wsdl, array(
	       'soap_version' => SOAP_1_2,
	       'login'        => $user_data[0]['sCallUsername'], 
	       'password'     => $user_data[0]['sCallPassword']));                  
	    
	    $soundData = file_get_contents($file_url); 
	    $createSoundRequest = array(
	        'Name' => 'My new sound',
	        'Data' => $soundData);

	    try {
	    	$soundId = $client->createSound($createSoundRequest);
	    	$where = "nCallId = '".$_SESSION['nCallId']."'";
		    $call_data['sSoundId']  = $soundId;
		    $directdata = dbRowUpdate('calls', $call_data, $where);
	    } catch (Exception $e) {
	    	$_SESSION['error_msg'] = 'MP3 file is not accepted by Callfire API. Please try again.';
		    header('location:sendandpreview.php?id='.$id.'&f='.$f);
			exit;
	    }

	    $where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
	    $camp_data['nCall']  = '1';
	    $camp_data['nDraft']  = '1';
	    $directdata = dbRowUpdate('campaigns', $camp_data, $where);
	    //$soundId = $client->createSound($createSoundRequest);
	} else {
		$_SESSION['error_msg'] = 'Please Enter callfire details from account section to use Phone Call followup action';
	    header('location:sendandpreview.php?id='.$id.'&f='.$f);
		exit;	
	}
}



// get User's click2mail details and uploaded pdf file details
$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users";
$user_data = getAnyData('sDirectmailUsername,sDirectmailPassword',$table,$where,null,null);

$sDirectmailUsername = $user_data[0]['sDirectmailUsername'];
$sDirectmailPassword = $user_data[0]['sDirectmailPassword'];

$where = "nDirectEmailId = '".$_SESSION['nDirectEmailId']."'";
$data = getAnyData('*','directemail',$where,null,null);
$sDirectEmailBody = $data[0]['sDirectEmailBody'];

if(isset($sDirectEmailBody)){
	if($sDirectmailUsername != '' && $sDirectmailPassword != '') {
		$soap_url = "http://soap.click2mail.com/";
		$credentials = "$sDirectmailUsername:$sDirectmailPassword";
		$url = 'https://{'.$sDirectmailUsername.'}:{'.$sDirectmailPassword.'}@rest.click2mail.com/molpro/documents';

		$file_path = $upload_path.$sDirectEmailBody;
		$time = time();
			            
		$myvars = array (
		    'documentName'   => 'Document_'. current(explode(".",$sDirectEmailBody)).'_'.$time,
		    'documentClass'  => 'Letter 8.5 x 11',
		    'documentFormat' => 'PDF',
		    'file'           => '@'. $file_path,
		);

		$remote_headers = array (
		    "method:POST",          
		    "Cache-Control: no-cache",
		    "Pragma: no-cache",         
		    "Authorization: Basic ".base64_encode($credentials)
		);

		//echo $myvars;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $remote_headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
		    print curl_error($ch);
		    $_SESSION['error_msg'] = 'Unable to upload PDF file to click2mail account using API. Please Check your PDF file.';
		    header('location:sendandpreview.php?id='.$id.'&f='.$f);
			exit; 
		}

		$responseArray = json_decode(json_encode((array) simplexml_load_string($response)), 1);	            

		curl_close($ch);

		if($responseArray["status"] != "0"){
			$_SESSION['error_msg'] = 'Click2Mail Error : Invalid PDF File : '. $responseArray["description"];
			header('location:sendandpreview.php?id='.$id.'&f='.$f);
			exit;
		}

		$documentId = $responseArray['id'];

		if($responseArray["status"] == "0"){
	        $sql_update = 'update directemail set nDocumentId ="'.$documentId.'" where nDirectEmailId = "'.$_SESSION['nDirectEmailId'].'"';
	        mysql_query($sql_update);
	    }

	} else {
		//Your Followup Integration settings have to be setup first. Click "Save and Exit" and then go to "Account" to setup your Followup Integrations
		$_SESSION['error_msg'] = 'Please Enter click2mail details from account section to use Direct Mail followup action';
	    header('location:sendandpreview.php?id='.$id.'&f='.$f);
		exit;
	}
}


/*
if($_SESSION['nDirectEmailId'] != ''){

	$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
	$table = " users";
	$user_data = getAnyData('sDirectmailUsername,sDirectmailPassword',$table,$where,null,null);

	$sDirectmailUsername = $user_data[0]['sDirectmailUsername'];
	$sDirectmailPassword = $user_data[0]['sDirectmailPassword'];
	$credentials = "$sDirectmailUsername:$sDirectmailPassword";
	$whr = "nCampaignid = '".$_SESSION['nCampaignid']."'";
	$sql_cust = getAnyData('count(*) as total_cust','campaigncustomers',$whr,null,null);
	$tot_charge = ($sql_cust[0]['total_cust'] * 1.50);
	
	#$tot_charge = 1.50;
	$crediturl = 'https://{$sDirectmailUsername}:{$sDirectmailPassword}@rest.click2mail.com/molpro/credit';
	$remote_headers = array (
	                    "method:POST",          
	                    "Cache-Control: no-cache",
	                    "Content-type: text/xml;charset=\"utf-8\"", 
	                    "Pragma: no-cache",    
	                    "Authorization: Basic ".base64_encode($credentials)
	                ); 

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $remote_headers);
	curl_setopt($ch, CURLOPT_URL, $crediturl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$Balance = curl_exec($ch);
	preg_match_all('!\d+!', $Balance, $matches);
	$balance =  $matches[0][3];
	if(floatval($balance) < floatval($tot_charge)){
		$_SESSION['notice_msg'] =  "You don't have enough credit in your direct mail account";
	}
}*/

$sql_update = 'update campaigns set nDraft = "0" where nCampaignid = "'.$_SESSION['nCampaignid'].'"';
mysql_query($sql_update);

//sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$base_url);
header('location:campaign_sent.php');
exit;
?>