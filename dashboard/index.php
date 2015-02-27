<?php 
include('header.php');
if(!isset($_SESSION['nUserID'])){
	header('location: login.php');
	exit;
}
else{
	header('location:userdashboard.php');
	exit;
}
?>