<?php
require_once 'src/Mandrill.php';
require_once 'config.php';
$mandrill = new Mandrill('quprgpshtLiqo8VOWn2tcw');

$sql = "SELECT `vSentId` FROM `track_email` WHERE `eStatus`='Unread'";

$result = mysql_query($sql) or die(mysql_error() . '  at Line:' . __LINE__ . "<br/>" . $sql);
while($row = mysql_fetch_assoc($result)) {
    $data[] = $row;
}


//echo "<pre>";
//print_r($data);exit;

if(!empty($data)){
    $info = array();
    for($i=0; $i<count($data); $i++){
	$id = $data[$i]['vSentId'];
	if($id != "")
	{
	    $info = $mandrill->messages->info($id);
	    if(intval($info['opens']) > 0){
		$sql = "UPDATE track_email SET eStatus='Read' WHERE vEmail='" . $info['email'] . "' AND vSentId='" . $info['_id'] . "'"; 
		$res = mysql_query($sql) or die(mysql_error() . '  at Line:' . __LINE__ . "<br/>" . $sql);
	    }
	}
	
    }	
}

?>
