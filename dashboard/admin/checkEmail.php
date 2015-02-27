<?php 
include('../library/function.php');
$query="select count(sUserEmail) from users where sUserEmail='".mysql_real_escape_string($_REQUEST['sUserEmail'])."'";
$response=mysql_query($query);
$result=mysql_fetch_array($response);
if($result['count(sUserEmail)'] == 0){
	echo 'true';
}
else{
	echo 'true';
}
?>