<?php
date_default_timezone_set('America/Louisville');

$host = 'localhost';

/*$database = 'zaptech_innercircle'; 
$username = 'zaptech_inn';
$password = 'Sz9{2+T3nFP$';*/

$database = 'contactl_livedb'; 
$username = 'contactl_liveuse';
$password = 'M{ObU.Z[Or8(';

$con = mysql_connect($host,$username,$password) or die('Cannot connect to the DB');
$res = mysql_select_db($database,$con) or die(mysql_error());
ini_set('display_errors', 'On');
ini_set('memory_limit','512M');

ob_start();
error_reporting(0);
session_start();

$start = 0;
$limit = 24;

$pathAry = explode("/",$_SERVER['SCRIPT_NAME']);

//pr($pathAry); die;

$base_url = "http://contactlegend.com/dashboard/";
$base_path =  $_SERVER['DOCUMENT_ROOT']."/".$pathAry[1]."/";


$admin_url = $base_url."/admin/";
$admin_path =  $base_path.'/admin';

$webservice_url =  $base_url.'webservices/';

$upload_path =  $base_path.'upload/';
$upload_url =  $base_url.'upload/';

$tender_upload_path =  $upload_path.'tender/';
$tender_upload_url =  $upload_url.'tender/';

$bar_upload_path =  $upload_path.'bar/';
$bar_upload_url =  $upload_url.'bar/';

$category_upload_path =  $upload_path.'category/';
$category_upload_url =  $upload_url.'category/';

$event_upload_path =  $upload_path.'event/';
$event_upload_url =  $upload_url.'event/';

?>
