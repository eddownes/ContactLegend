<?php 
include('library/function.php');

$mode = $_POST['mode'];
$nDraft = $_POST['nDraft'];
$page = $_POST['redirect_page'];

$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users";
$user_data = getAnyData('sDirectmailUsername,sDirectmailPassword',$table,$where,null,null);

$sDirectmailUsername = $user_data[0]['sDirectmailUsername'];
$sDirectmailPassword = $user_data[0]['sDirectmailPassword'];

$credentials = "$sDirectmailUsername:$sDirectmailPassword";
$url = 'https://{$sDirectmailUsername}:{$sDirectmailPassword}@rest.click2mail.com/molpro/documents';

if($mode == 'Add')
{    
    if(isset($_FILES["sDirectEmailBody"]['error']) && $_FILES["sDirectEmailBody"]['error'] != "0") {
        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
        header('location:'.$page);
        exit;
    } else {
        $semailletter = 0;
        $nDirectEmailStatus = 0;
        $fname = explode(".", $_FILES['sDirectEmailBody']['name']);
        $extension = end($fname);
        if(strtolower($extension) == 'pdf'){
            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$_FILES["sDirectEmailBody"]["name"]);
            $sDirectEmailBody = $_FILES["sDirectEmailBody"]["name"];
            $nCampaignid = $_SESSION['nCampaignid'];
            chmod($upload_path.$sDirectEmailBody, 777);
        
            $nCampaignid = $_SESSION['nCampaignid'];
            $query = "INSERT INTO `directemail` (`nDirectEmailId`,`nCampaignid`,`semailletter`,`sDirectEmailBody`,`nDirectEmailStatus`)VALUES (NULL , '$nCampaignid', '$semailletter', '$sDirectEmailBody', '$nDirectEmailStatus')";
            $result = mysql_query($query);
            $_SESSION['nDirectEmailId'] = mysql_insert_id();

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

                /* == Upload Document API Call and save Documnet Id in DB :: Start == */

                $file_path = $upload_path.$sDirectEmailBody;
                $time = time();
                
                $myvars = array (
                    'documentName'   => 'Document_'. current(explode(".",$sDirectEmailBody)).'_'.$time,
                    'documentClass'  => 'Letter 8.5 x 11',
                    'documentFormat' => 'PDF',
                    'file'           => '@'. $file_path,
                );

                $remote_headers = array (
                    "method:POST",          
                    "Cache-Control: no-cache",
                    "Pragma: no-cache",         
                    "Authorization: Basic ".base64_encode($credentials)
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $remote_headers);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    print curl_error($ch);
                    print "<br>Unable to upload file.";
                    exit();
                }
                //print_r($response);

                $responseArray = json_decode(json_encode((array) simplexml_load_string($response)), 1);
                //echo "Add <pre>";
                //print_r($responseArray);

                curl_close($ch);
                if($responseArray["status"] != "0"){
                    $_SESSION['error_msg'] = 'ERROR : Invalid File : '. $responseArray["description"];
                    unset($_SESSION['success_msg']);
                    $where = 'nDirectEmailId = '.$_SESSION['nDirectEmailId'];
                    dbRowDelete('directemail',$where);
                    unset($_SESSION['nDirectEmailId']);
                    return;
                }

                if($responseArray["status"] == "0"){                
                    $documentId = $responseArray['id'];
                    $where = "nDirectEmailId = '".$_SESSION['nDirectEmailId']."'";
                    $directmail_data['nDocumentId']  = $documentId;
                    $directdata = dbRowUpdate('directemail', $directmail_data, $where);

                    $where = "nCampaignid = '".$nCampaignid."'";
                    $camp_data['nDirectEmail']  = '1';
                    $camp_data['nDraft']  = '1';
                    $directdata = dbRowUpdate('campaigns', $camp_data, $where);
                }

                //die("Done");

                /* == API Code :: End == */

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
    if(isset($_FILES["sDirectEmailBody"]['error']) && $_FILES["sDirectEmailBody"]['error'] != "0") {
        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
        header('location: step3.php');
        exit;
    } else {
        $allowedExts = array('pdf');
        $f_name = explode(".", $_FILES['sDirectEmailBody']["name"]);
        $extension = end($f_name);
        $sDirectEmailBody = '';
        if(strtolower($extension) == 'pdf'){
            
            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$_FILES["sDirectEmailBody"]["name"]);
            $sDirectEmailBody = $_FILES["sDirectEmailBody"]["name"];  
            $semailletter = 0;
            $nDirectEmailStatus = 0;

            /* == Upload Document API Call and save Documnet Id in DB :: Start == */

            $file_path = $upload_path.$sDirectEmailBody;
            $time = time();
            
            $myvars = array (
                'documentName'   => 'Document_'. current(explode(".",$sDirectEmailBody)).'_'.$time,
                'documentClass'  => 'Letter 8.5 x 11',
                'documentFormat' => 'PDF',
                'file'           => '@'. $file_path,
            );

            $remote_headers = array (
                "method:POST",          
                "Cache-Control: no-cache",
                "Pragma: no-cache",         
                "Authorization: Basic ".base64_encode($credentials)
            );

            //echo $myvars;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $remote_headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                print curl_error($ch);
                print "<br>Unable to upload file.";
                exit();
            }
            
            $responseArray = json_decode(json_encode((array) simplexml_load_string($response)), 1);
            
            curl_close($ch);

            $page = $_POST['redirect_page'];
            
            if($responseArray["status"] != "0"){
                
                $_SESSION['error_msg'] = 'ERROR : Invalid File : '. $responseArray["description"];
                return;
            }

            $documentId = $responseArray['id'];

            /* == API Code :: End == */

            if($responseArray["status"] == "0"){
                $sql_update = 'update directemail set sDirectEmailBody = "'.nl2br($sDirectEmailBody).'",nDocumentId ="'.$documentId.'",nDirectEmailStatus="'.$nDirectEmailStatus.'",semailletter ="'.$semailletter.'" where nDirectEmailId = "'.$_SESSION['nDirectEmailId'].'"';
                mysql_query($sql_update);
            }

            if($_POST['sendmail2'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
            }
            header('location:'.$page);
            exit;    
            
        }
        else{

            if($_POST['sendmail2'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript3'],$base_url);
                return;
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
