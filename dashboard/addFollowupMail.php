<?php 
include('library/function.php');

$data['dScheduleTime'] 		= 	mysql_real_escape_string($_POST['dScheduleTime']);
$data['sSchEmailSubject'] 	= 	mysql_real_escape_string($_POST['sSchEmailSubject']);
$data['sSchEmailBody'] 		= 	mysql_real_escape_string($_POST['sSchEmailBody']);
$data['dtCreated'] 			=	date("Y-m-d H:i:s");
$mode = $_POST['mode'];
$nDraft = $_POST['nDraft'];

if($data['dScheduleTime'] == '' || $data['dScheduleTime'] == 5){
	$data['dScheduleTime'] = '00:05';
}else{
	$data['dScheduleTime'] = ($data['dScheduleTime'])/60;
	$data['dScheduleTime'] = $data['dScheduleTime'].':00';
}

$page = $_POST['redirect_page'];

if($mode == 'Add'){
	$data['nCampaignid'] = $_SESSION['nCampaignid'];
	$result = dbRowInsert('followupEmail', $data);
	$_SESSION['nFollowupId'] = $result;

	$where = "nCampaignid = '".$data['nCampaignid']."'";
    $camp_data['nFollowEmail']  = '1';
    $camp_data['nDraft']  = '1';
    $_SESSION['success_msg'] = "Follow Up Email Registered successfully";
    $directdata = dbRowUpdate('campaigns', $camp_data, $where);

    if($result == true){

		$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
		$emaildata['eFollowUpMail']  = 'Yes';
    	$return_data = dbRowUpdate('campaignEmails', $emaildata, $where);	
		$_SESSION['dScheduleTime'] = $data['dScheduleTime'];
		if($_POST['sendmail1'] == 'Yes'){
			sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript2'],$base_url);
		}
		header('location:'.$page);
		exit;    
	}else{

		if($_POST['sendmail1'] == 'Yes'){
			sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript2'],$base_url);
		}
		$_SESSION['error_msg'] = "Follow Up Email not Registered";
	    header('location:'.$page);
	    exit;    
	}
}
else if($mode == 'Update'){

	$data_email['eEmailOpened'] = 0;		
	$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
	$return_data = dbRowUpdate('campaigncustomers', $data_email, $where);

	$where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
	$_SESSION['success_msg']    =   'Campaign updated successfully.';
	$return_data = dbRowUpdate('followupEmail', $data, $where);
	
	$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
	$emaildata['eFollowUpMail']  = 'Yes';
	$return_data = dbRowUpdate('campaignEmails', $emaildata, $where);	
	$_SESSION['dScheduleTime'] = $data['dScheduleTime'];
	if($_POST['sendmail1'] == 'Yes'){
		sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript2'],$base_url);
	}
	header('location:'.$page);
	exit;  
}


?>