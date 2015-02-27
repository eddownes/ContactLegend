<?php
include("library/function.php");

$file_path  = $_GET['file'];
$path_parts = pathinfo($file_path);
$file_name  = $path_parts['basename'];
$file_ext   = $path_parts['extension'];
$file_path  = "upload/" . $file_name;

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$file_name);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
//header('Content-Transfer-Encoding: binary');
ob_clean();
flush();
readfile($file_path);

exit;

?>