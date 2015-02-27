<?php
include("../library/function.php");
$sStateCode = $_POST['sStateCode'];
$where = 'sStateCode = "'.$sStateCode.'"';
$citydata  = getAnyData('*','cities',$where,null,'group by sCityName');
$html = "<option value=''>--Select--</option>";
$tot_city = count($citydata);
for($i=0;$i<$tot_city;$i++)
{
	$sele = '';
	if($citydata[$i]['iCityId'] == $iCityId)
	{
		$sele = 'Selected';
	}
	$html.="<option value='".$citydata[$i]['iCityId']."' ".$sele.">".$citydata[$i]['sCityName']."</option>";
}

echo $html;
?>