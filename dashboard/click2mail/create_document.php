<?php

//$res = https://{chirag_zaptech}:{Zaptech123#}@rest.click2mail.com/molpro/documents/documentName=Sample Letter"/documentClass="Letter 8.5 x 11"/documentFormat="PDF"/file = "@{filename_of_pdf}";

$url = "https://{chirag_zaptech}:{Chirag123#}@rest.click2mail.com/molpro/documents/documentName='Sample Letter'/documentClass='Letter 8.5 x 11'/documentFormat='PDF'/file='@{filename_of_pdf}'";
//$myvars = 'documentName=Sample Letter' . '&documentClass=Letter 8.5 x 11' . '&documentFormat=PDF' . '&file=@{filename_of_pdf}';

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
echo simplexml_load_string($response);
//print_r($response);


?>