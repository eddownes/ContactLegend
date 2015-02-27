<?php 
include('header.php');
error_reporting(E_ALL);
$mode = $_POST['mode'];
$nCampaignid = $_POST['nCampaignid'];
$nCEmaiId = $_POST['nCEmaiId'];
$dataEmail['nCampaignid'] = $nCampaignid;
$_SESSION['nCampaignid'] = $nCampaignid;
$dataEmail['sEmailSubject'] = mysql_real_escape_string($_POST['sEmailSubject']);
$dataEmail['sEmailScript'] = addslashes($_POST['sEmailScript']);
$dataEmail['dDateSent'] = date('Y-m-d H:i:s');
$where = "nCEmaiId = '".$nCEmaiId."'";
dbRowUpdate('campaignEmails', $dataEmail,$where);
if($nCampaignid != '' && $nCEmaiId != ''){
    $_SESSION['nCampaignid']    =   $nCampaignid;
    $_SESSION['nCEmaiId']       =   $nCEmaiId;
    $_SESSION['nFollowupId']    =   '';
    $_SESSION['nDirectEmailId'] =   '';
    $_SESSION['nCallId']        =   '';
    $_SESSION['sCallScript']    =   '';
    $_SESSION['semailletter']   =   '';
    $_SESSION['dScheduleTime']  =   '';
    $_SESSION['dCallSchedTime'] =   '';

    if($_POST['draft'] == 'Yes'){
        $_SESSION['success_msg']    =   'Campaign saved as draft.';
        header('location: userdashboard.php');
        exit;
    }
    else{
        $_SESSION['success_msg']    =   'Intro message saved successfully.';
        header('location:followupaction.php?id='.base64_encode($nCampaignid).'&f='.$_POST['f']);
        exit;
    }
}
?>