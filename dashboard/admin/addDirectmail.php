<?php 
include('library/function.php');
$mode = $_POST['mode'];
$nDraft = $_POST['nDraft'];
$page = $_POST['redirect_page'];
if($mode == 'Add')
{    
    if($_FILES["type"][0] != '') {
        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
        header('location:'.$page);
        exit;
    } else {

        $semailletter = 0;
        $nDirectEmailStatus = 0;
        $extension = end(explode(".", $_FILES['sDirectEmailBody']["name"]));
        if(strtolower($extension) == 'pdf'){
            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$_FILES["sDirectEmailBody"]["name"]);
            $sDirectEmailBody = $_FILES["sDirectEmailBody"]["name"];
            $nCampaignid = $_SESSION['nCampaignid'];
        
            $nCampaignid = $_SESSION['nCampaignid'];
            $query = "INSERT INTO `directemail` (`nDirectEmailId`,`nCampaignid`,`semailletter`,`sDirectEmailBody`,`nDirectEmailStatus`)VALUES (NULL , '$nCampaignid', '$semailletter', '$sDirectEmailBody', '$nDirectEmailStatus')";
            $result = mysql_query($query);
            $_SESSION['nDirectEmailId'] = $result;

            if($result == true){
                
                $page = $_POST['redirect_page'];
                $where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
                $emaildata['eDirectMode']  = 'Yes';
                $return_data = dbRowUpdate('campaignEmails', $emaildata, $where);
                $_SESSION['success_msg'] = 'Direct Email Registered!!';
                $_SESSION['semailletter'] = 'Email Script';
                $where = "nCampaignid = '".$nCampaignid."'";
                $camp_data['nDirectEmail']  = '1';
                $camp_data['nDraft']  = '1';
                $directdata = dbRowUpdate('campaigns', $camp_data, $where);

                if($_POST['sendmail2'] == 'Yes'){
                    sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
                }
                header('location:'.$page);
                exit;    
            }else{
                $_SESSION['error_msg'] = 'Direct Email Not Registered!!';
                header('location: step2.php');
                exit;
            }
        }
        else{
            if($_POST['sendmail2'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
            }
            else
            {
                $_SESSION['error_msg'] = 'Invalid File';
                header('location:'.$page);
                exit;                        
            }
        }  
    }      
}
else if($mode == 'Update')
{
    if(isset($_FILES["sDirectEmailBody"]['error'])) {
        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
        header('location: step3.php');
        exit;
    } else {
        $allowedExts = array('pdf');
        $extension = end(explode(".", $_FILES['sDirectEmailBody']["name"]));
        $sDirectEmailBody = '';
        if(strtolower($extension) == 'pdf'){

            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$_FILES["sDirectEmailBody"]["name"]);
            $sDirectEmailBody = $_FILES["sDirectEmailBody"]["name"];  
            $semailletter = 0;
            $nDirectEmailStatus = 0;   
            
            $sql_update = 'update directemail set sDirectEmailBody = "'.nl2br($sDirectEmailBody).'",nDirectEmailStatus="'.$nDirectEmailStatus.'",semailletter ="'.$semailletter.'" where nDirectEmailId = "'.$_SESSION['nDirectEmailId'].'"';
            mysql_query($sql_update);

            $page = $_POST['redirect_page'];

            if($_POST['sendmail2'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
            }
            header('location:'.$page);
            exit;    
            
        }
        else{
            if($_POST['sendmail2'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
            }
            else{
                $_SESSION['error_msg'] = 'Invalid File';
                header('location:'.$page);
                exit;
            }
        }
    }
}
?>