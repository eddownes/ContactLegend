<?php
require_once 'library/config.php';
if(isset($_SESSION['nUserID'])) 
{  
	session_destroy();  
	$_SESSION['success_msg'] = 'Log out successfully.';
	header('Location:login.php');
}else{
header('Location:login.php');
} 
?>
