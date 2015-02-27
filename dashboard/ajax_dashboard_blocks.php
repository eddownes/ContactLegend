<?php
include('library/function.php'); 
$where = 'nUserID = "'.$_SESSION['nUserID'].'"';
$userName = getAnyData('sUserFullName', 'users', $where, null, null);

$responseData = array();

$temp_id = '';
$where = 'nUserID="'.$_SESSION['nUserID'].'"';
$ordrby = "order by nCampaignid desc";
$campaignData = getAnyData('*', 'campaigns', $where, null,$ordrby);
$totCampaign = count($campaignData);
$temp_id = $campaignData[0]['nCampaignid'];

// total sent
$result = mysql_query('SELECT SUM(nEmailsSent) AS sent_sum, SUM(nEmailsOpened) AS open_sum FROM campaigns WHERE nUserID="'.$_SESSION['nUserID'].'"'); 
$row = mysql_fetch_assoc($result); 
$sent_sum = $row['sent_sum'];
if($sent_sum == ''){
    $sent_sum = 0;
}
$open_sum = $row['open_sum'];

// average
$avg_open_rate = ($open_sum / $sent_sum) * 100; 
$aor = number_format((float)$avg_open_rate, 1, '.', '') . " %";

//Avarage Days to open
if($sent_sum == 0) { 
    $daysToOpen = '0 Days'; 
} else { 
    $daysToOpen = getavgdays().' Days'; 
}

$responseData["total_camp"] = $totCampaign;
$responseData["sent"] = $sent_sum;
$responseData["avg_open_rate"] = $aor;
$responseData["days_to_open"] = $daysToOpen;

echo json_encode($responseData);

exit;
?>