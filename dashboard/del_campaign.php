<?php
include('library/function.php');
$nCampaignid = base64_decode($_REQUEST['id']);

$sql_dele_campaign = mysql_query("Delete from campaigns where nCampaignid = '".$nCampaignid."'");
$sql_dele_campaignemail = mysql_query("Delete from campaignEmails where nCampaignid = '".$nCampaignid."'");
$sql_dele_campaigndirect = mysql_query("Delete from directemail where nCampaignid = '".$nCampaignid."'");
$sql_dele_campaignphone = mysql_query("Delete from followupEmail where nCampaignid = '".$nCampaignid."'");
$sql_dele_campaigncall = mysql_query("Delete from calls where nCampaignid = '".$nCampaignid."'");
$sql_dele_campaigncall = mysql_query("Delete from campaigncustomers where nCampaignid = '".$nCampaignid."'");

$sql_dele_schedulecalls = mysql_query("Delete from schedulecalls where nCampaignid = '".$nCampaignid."'");
$sql_dele_scheduledirectmails = mysql_query("Delete from scheduledirectmails where nCampaignid = '".$nCampaignid."'");
$sql_dele_schedulefollowemails = mysql_query("Delete from schedulefollowemails where nCampaignid = '".$nCampaignid."'");

$_SESSION['success_msg'] = 'Campaign Deleted successfully';
header("location:userdashboard.php");
die;
?>