<?php
$host = 'localhost';
$database = 'salesmonkey'; 
$username = 'salesmonkeyu';
$password = 'reset123#';
$con = mysql_connect($host,$username,$password) or die('Cannot connect to the DB');
$res = mysql_select_db($database,$con) or die('Cannot select the DB');
?>
