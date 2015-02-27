<?php
require_once '../library/config.php';
if(isset($_SESSION['cl_nAdminUserId'])) 
{  
	session_destroy();  
	$_SESSION['success_msg'] = 'Log out successfully.';
	header('location:login.php');
}else{
header('location:login.php');
}
?>
