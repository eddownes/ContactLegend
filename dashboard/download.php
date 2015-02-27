<?php 
include('library/config.php');
if(isset($_REQUEST['file']))
{
	$file = $base_path."error_csv/".$_REQUEST['file'];
	
	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
	header('Cache-Control: private',false);
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="'.basename($file).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file));	// provide file size
	header('Connection: close');
	readfile($file);		// push it out
	exit();
}
?>