<?php 
include('header.php');
ini_set("auto_detect_line_endings", "1");
ini_set('memory_limit','512M');
$data['sCampaignname'] = mysql_real_escape_string($_POST['sCampaignname']);
$data['nUserID'] = $_SESSION['nUserID'];
$data['dCreatedDate'] = date('Y-m-d H:i:s');
$data['nDraft'] = 1;
$mode = $_POST['mode'];
$extension = end(explode(".", $_FILES['sCampaignCSV']["name"]));
error_reporting(E_ALL);

if($mode == 'Add')
{
    $sql = mysql_query("Select count(*) as total from campaigns where sCampaignname = '".$_POST['sCampaignname']."' AND nUserID = '".$_SESSION['nUserID']."'");
    $data_res = mysql_fetch_assoc($sql);
    if($data_res['total'] <= 0){        
        if(($_FILES["sCampaignCSV"]["type"] != '') && ($extension == 'csv')){
            $upload_dir = 'csv/';
            $nameArray = explode('.',$_FILES['sCampaignCSV']['name']);
            $fname = $nameArray[0];
            $fExt = $nameArray[1];
            $fileName = $fname.'_'.strtotime(date('Y-m-d H:i:s')).'_'.$data['nUserID'].'.'.$fExt;
            if(!move_uploaded_file($_FILES['sCampaignCSV']['tmp_name'], $upload_dir.$fileName)){
                $_SESSION['error_msg'] = 'File not Uploaded';
                header('location:createCampaign.php');
                exit;
            }

            $data['sCampaignCSV'] = $fname.'_'.strtotime(date('Y-m-d H:i:s')).'_'.$data['nUserID'].'.'.$fExt;
            $nCampaignid = dbRowInsert('campaigns', $data);
            $dataEmail['nCampaignid'] = $nCampaignid;
            $_SESSION['nCampaignid'] = $nCampaignid;
            $dataEmail['dDateSent'] = date('Y-m-d H:i:s');
            $nCEmaiId = dbRowInsert('campaignEmails', $dataEmail);
            $date = date('Y-m-d H:i:s');
            $fileName = $upload_dir.$data['sCampaignCSV'];
            $ignoreFirstRow = 1;
            $remark = NULL;
            if (($handle = fopen($fileName, "r")) !== FALSE) {
                $header = fgetcsv($handle);

                $error_header[] = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');  
                $error_header1[] = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');  
                $error_header2 = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');
                $result = array_diff($header,$error_header2);
                $tot_result = count($result);                
                if($tot_result == '0'){
                    while (($dataCSV = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($ignoreFirstRow != 0){
                            $tot_row = count($dataCSV);
                            if($tot_row == 11){
                                if(checkEmpty($dataCSV[0]) && checkEmpty($dataCSV[1]) && checkEmpty($dataCSV[2]) && checkEmpty($dataCSV[3]) && checkEmpty($dataCSV[4]) && checkEmpty($dataCSV[5]) && checkEmpty($dataCSV[6]) && checkEmpty($dataCSV[7]) && checkEmpty($dataCSV[8]) && checkEmpty($dataCSV[9]) && checkEmpty($dataCSV[10])){
                                    echo 'Yes';
                                }
                                else
                                {
                                    if(checkEmpty($dataCSV[2]))
                                    {
                                        $remark .= "Email Address can not be empty \n";
                                    }
                                    if((!checkEmpty($dataCSV[2])) && (!check_email_address($dataCSV[2])))
                                    {
                                        if($remark != '') {$remark.= ',';}
                                        $remark .= "Invalid Email Address";
                                    }

                                    if((!checkEmpty($dataCSV[2])) && (!check_duplicate_email_address($dataCSV[2],$nCampaignid)))
                                    {
                                        if($remark != '') {
                                            $remark .= ',';
                                        }
                                        $remark .= "Duplicate email record has been discarded.";
                                    }

                                    if(!$remark)
                                    {
                                        $query = "INSERT INTO `campaigncustomers` (`nCampaignid`,`sCustomerFirstname`,`sCustomerLastname`,`sCustomerEmail`,`sCustomerPhone`,`sCustomerAddress1`,`sCustomerAddress2`,`sCustomerAddress3`,`sCustomerCity`,`sCustomerState`,`sCustomerZip`,`sCustomerCountry`,`dUploadDate`,`eEmailOpened`)VALUES ('$nCampaignid','$dataCSV[0]','$dataCSV[1]','$dataCSV[2]','$dataCSV[3]','$dataCSV[4]','$dataCSV[5]','$dataCSV[6]','$dataCSV[7]','$dataCSV[8]','$dataCSV[9]','$dataCSV[10]','$date','0')";
                                        mysql_query($query);
                                        $error_header1[] = $dataCSV;
                                    }
                                    else
                                    {
                                        array_push($dataCSV, $remark);
                                        $error_header[] = $dataCSV;
                                        $error_header1[] = $dataCSV;
                                    }
                                }
                            }
                            else
                            {
                                $remark = "The CSV file you uploaded is invalid. Please make sure you are using our template.";
                                array_push($dataCSV, $remark);
                                $error_header[] = $dataCSV;
                            }
                            unset($dataCSV,$remark);
                        }
                        $ignoreFirstRow++;
                    }
                }
                else
                {
                    $_SESSION['error_msg'] = "The CSV file you uploaded is invalid. Please make sure you are using our template.";
                    header("location:createCampaign.php");
                    exit;
                }
                fclose($handle);
            }
        }
        else
        {
            $_SESSION['error_msg'] = "Upload only csv files.";
            header("location:createCampaign.php");
            exit;
        }

        if(count($error_header) >1){   
            $csv_err_name = "errcsv_".date("U").".csv";
            $fp           = fopen($base_path."/error_csv/$csv_err_name", "w");
            
            foreach ($error_header1 as $fields){
                fputcsv($fp, $fields);
            }
            fclose($fp);
            
            $link = '<a href="'.$base_url.'download.php?file='.$csv_err_name.'" target="_blank">Click Here</a>';
            $_SESSION['error_msg'] = "There are errors in the CSV file you uploaded. ".$link." to download our error report which shows invalid records. You can correct those errors and re-upload the file, or you can ignore and choose `Next` to proceed with valid records only.";
            header("location:createCampaign.php?id=".base64_encode($nCampaignid));
            exit;
        }

        if($nCampaignid == TRUE && $nCEmaiId == TRUE){
            $_SESSION['nCampaignid']    =   $nCampaignid;
            $_SESSION['nCEmaiId']       =   $nCEmaiId;
            $_SESSION['nFollowupId']    =   '';
            $_SESSION['nDirectEmailId'] =   '';
            $_SESSION['nCallId']        =   '';
            $_SESSION['sCallScript']    =   '';
            $_SESSION['semailletter']   =   '';
            $_SESSION['dScheduleTime']  =   '';
            $_SESSION['dCallSchedTime'] =   '';

            if($_POST['draft'] == 'Yes'){
                $_SESSION['success_msg']    =   'Campaign saved as draft successfully.';
                $id = base64_encode($nCampaignid);
                header("location:userdashboard.php");
                exit;
            }
            else{
                $where = "nCampaignid = '".$nCampaignid."'";
                $table = 'campaigncustomers';
                $cust_data = getAnyData('nCampaignCustId',$table,$where,null,null);
                if(count($cust_data) >0){
                    $_SESSION['success_msg']    =   'Campaign added successfully.';
                    header('location: intromessage.php?id='.base64_encode($nCampaignid));
                    exit;    
                }
                else
                {
                    $_SESSION['error_msg'] = "The CSV file you uploaded doesn't have any valid records. Please make sure you are using our template.";
                    header("location:createCampaign.php?id=".base64_encode($nCampaignid));
                    exit;
                }
            }
        }
    }
    else
    {
        $_SESSION['error_msg'] = "Campaign name already used.";
        header("location:createCampaign.php");
        exit;
    }
}
else
{
    $nCampaignid = $_POST['nCampaignid'];
    $sql = mysql_query("Select count(*) as total from campaigns where sCampaignname = '".$_POST['sCampaignname']."' AND nCampaignid != '".$nCampaignid."' AND nUserID = '".$_SESSION['nUserID']."'");
    $data_res = mysql_fetch_assoc($sql);
    if($data_res['total'] <= 0){
        if(($_FILES["sCampaignCSV"]["type"] != '') && ($extension == 'csv')){
            $upload_dir = 'csv/';
            $nameArray = explode('.',$_FILES['sCampaignCSV']['name']);
            $fname = $nameArray[0];
            $fExt = $nameArray[1];
            $fileName = $fname.'_'.strtotime(date('Y-m-d H:i:s')).'_'.$data['nUserID'].'.'.$fExt;
            if(!move_uploaded_file($_FILES['sCampaignCSV']['tmp_name'], $upload_dir.$fileName)){
                $_SESSION['error_msg'] = 'File not Uploaded';
                header('location:createCampaign.php');
                exit;
            }
        }
        else{
            $fileName = $_POST['filename'];
        }

        $data['sCampaignCSV'] = $fileName;
        
        $where_clause = "nCampaignid = '".$nCampaignid."'";
        $return_data = dbRowUpdate('campaigns', $data, $where_clause);
        
        $nCEmaiId = $_POST['nCEmaiId'];
        $dataEmail['nCampaignid'] = $nCampaignid;
        $_SESSION['nCampaignid'] = $nCampaignid;
        $dataEmail['dDateSent'] = date('Y-m-d H:i:s');
        $where = "nCEmaiId = '".$nCEmaiId."'";

        dbRowUpdate('campaignEmails', $dataEmail,$where);
        $date = date('Y-m-d H:i:s');   

        $fileName = $upload_dir.$data['sCampaignCSV'];
        $ignoreFirstRow = 1;
        $remark = NULL;
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            dbRowDelete('campaigncustomers',$where_clause);
            $header = fgetcsv($handle);
            $error_header[] = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');  
            $error_header1[] = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');  
            $error_header2 = array('Firstname','Lastname','Email','Phone','Address1','Address2','Address3','City','State','Zip','Country(Non Us)');
            $result = array_diff($header,$error_header2);
            $tot_result = count($result);
            if($tot_result == '0'){
                while (($dataCSV = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if($ignoreFirstRow != 0){
                        $tot_row = count($dataCSV);
                        if($tot_row == 11){
                            if(checkEmpty($dataCSV[0]) && checkEmpty($dataCSV[1]) && checkEmpty($dataCSV[2]) && checkEmpty($dataCSV[3]) && checkEmpty($dataCSV[4]) && checkEmpty($dataCSV[5]) && checkEmpty($dataCSV[6]) && checkEmpty($dataCSV[7]) && checkEmpty($dataCSV[8]) && checkEmpty($dataCSV[9]) && checkEmpty($dataCSV[10])){
                                echo 'Yes';
                            }
                            else 
                            {
                                if(checkEmpty($dataCSV[2]))
                                {
                                    #$remark .= "$header[2] can not be empty \n";
                                    $remark .= "Email Address can not be empty \n";
                                }
                                if((!checkEmpty($dataCSV[2])) && (!check_email_address($dataCSV[2])))
                                {
                                    if($remark != '') {$remark.= ',';}
                                    $remark .= "Invalid Email Address";
                                }

                                if((!checkEmpty($dataCSV[2])) && (!check_duplicate_email_address($dataCSV[2],$nCampaignid)))
                                {
                                    if($remark != '') {
                                        $remark .= ',';
                                    }
                                    $remark .= "Duplicate email record has been discarded.";
                                }

                                if(!$remark)
                                {
                                    $query = "INSERT INTO `campaigncustomers` (`nCampaignid`,`sCustomerFirstname`,`sCustomerLastname`,`sCustomerEmail`,`sCustomerPhone`,`sCustomerAddress1`,`sCustomerAddress2`,`sCustomerAddress3`,`sCustomerCity`,`sCustomerState`,`sCustomerZip`,`sCustomerCountry`,`dUploadDate`,`eEmailOpened`)VALUES ('$nCampaignid','$dataCSV[0]','$dataCSV[1]','$dataCSV[2]','$dataCSV[3]','$dataCSV[4]','$dataCSV[5]','$dataCSV[6]','$dataCSV[7]','$dataCSV[8]','$dataCSV[9]','$dataCSV[10]','$date','0')";
                                    mysql_query($query);                                
                                    $error_header1[] = $dataCSV;
                                }
                                else
                                {
                                    array_push($dataCSV, $remark);
                                    $error_header[] = $dataCSV;
                                    $error_header1[] = $dataCSV;
                                }
                            }
                        }
                        else
                        {
                            $remark = "The CSV file you uploaded is invalid. Please make sure you are using our template.";
                            array_push($dataCSV, $remark);
                            $error_header[] = $dataCSV;
                        }
                        unset($dataCSV,$remark);   
                    }
                    $ignoreFirstRow++;
                }
            }
            else
            {
                $_SESSION['error_msg'] =  "The CSV file you uploaded is invalid. Please make sure you are using our template.";                
                header("location:createCampaign.php?id=".base64_encode($nCampaignid));
                exit;        
            }
            fclose($handle);
        }


        if(count($error_header) >1)
        {   
            $csv_err_name = "errcsv_".date("U").".csv";
            $fp           = fopen($base_path."/error_csv/$csv_err_name", "w");
            
            foreach ($error_header1 as $fields){
                fputcsv($fp, $fields);
            }
            
            fclose($fp);
            
            $link = '<a href="'.$base_url.'download.php?file='.$csv_err_name.'" target="_blank">Click Here</a>';
            
            $_SESSION['error_msg'] = "There are errors in the CSV file you uploaded. ".$link." to download our error report which shows invalid records. You can correct those errors and re-upload the file, or you can ignore and choose `Next` to proceed with valid records only.";

            header("location:createCampaign.php?id=".base64_encode($nCampaignid));
            exit;
        }
        
        if($nCampaignid != '' && $nCEmaiId != ''){
            $_SESSION['nCampaignid']    =   $nCampaignid;
            $_SESSION['nCEmaiId']       =   $nCEmaiId;
            $_SESSION['nFollowupId']    =   '';
            $_SESSION['nDirectEmailId'] =   '';
            $_SESSION['nCallId']        =   '';
            $_SESSION['sCallScript']    =   '';
            $_SESSION['semailletter']   =   '';
            $_SESSION['dScheduleTime']  =   '';
            $_SESSION['dCallSchedTime'] =   '';

            if($_POST['draft'] == 'Yes'){
                $_SESSION['success_msg']    =   'Campaign saved as draft.';
                header("location:userdashboard.php");
                exit;
            }
            else{

                $where = "nCampaignid = '".$nCampaignid."'";
                $table = 'campaigncustomers';
                $cust_data = getAnyData('nCampaignCustId',$table,$where,null,null);
                if(count($cust_data) >0){
                    $_SESSION['success_msg']    =   'Campaign updated successfully.';
                    header('location: intromessage.php?id='.base64_encode($nCampaignid)."&f=".base64_encode("No"));
                    exit;    
                }
                else
                {
                    $_SESSION['error_msg'] = "The CSV file you uploaded doesn't have any valid records. Please make sure you are using our template.";
                    header("location:createCampaign.php?id=".base64_encode($nCampaignid)."&f=".base64_encode("No"));
                    exit;
                }                
            }
        }
    }
    else
    {
        $_SESSION['error_msg'] = "Campaign name already used.";
        header("location:createCampaign.php?id=".base64_encode($nCampaignid)."&f=".base64_encode("No"));
        exit;
    }

}
?>