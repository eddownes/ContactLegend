<?php 
include('library/function.php');
$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users";
$user_data = getAnyData('sCallUsername,sCallPassword',$table,$where,null,null);
$sCallTitle = mysql_real_escape_string($_POST['sCallTitle']);
$dCallSchedTime = $_POST['dCallSchedTime'];
$eCallStatus = 0;
$upload_dir = "upload/";
$mode = $_POST['mode'];
$nDraft = $_POST['nDraft'];
$res_followup = check_data_campaign($_SESSION['nCampaignid']);

if($mode == '' ){
    if($_FILES["type"][0] != '') {
        $_SESSION['error_msg'] =  "Error: " . $_FILES["sCallScript"]["error"] . "<br>";
        header('location: step2.php');
        exit;
    } 
    else 
    {
        $allowedExts = array('mp3','wav');
        $extension = end(explode(".", $_FILES['sCallScript']["name"]));
        if(in_array($extension, $allowedExts)){
            move_uploaded_file($_FILES["sCallScript"]["tmp_name"], $upload_path.$_FILES["sCallScript"]["name"]);
            $sCallScript = $_FILES["sCallScript"]["name"];
            $nCampaignid = $_SESSION['nCampaignid'];

            $query = "INSERT INTO `calls` (`nCallId`,`nCampaignid`,`sCallTitle`,`dCallSchedTime`,`sCallScript`,`eCallStatus`)VALUES (NULL , '$nCampaignid', '$sCallTitle', '$dCallSchedTime', '$sCallScript', '$eCallStatus')";
            $result = mysql_query($query);
            
            $_SESSION['nCallId'] = mysql_insert_id();
            
            if($result == true){

                $page = $_POST['redirect_page'];

                $_SESSION['success_msg'] = 'Automated Call Registered!!';
                $_SESSION['dCallSchedTime'] = $dCallSchedTime;
                $_SESSION['sCallScript'] = $sCallScript;
                $where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
                $emaildata['eFollowUpMail']  = 'Yes';
                $return_data = dbRowUpdate('campaignEmails', $emaildata, $where);

                $file_url = $upload_url.$sCallScript;
                $wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";
                
                $client = new SoapClient($wsdl, array(
                   'soap_version' => SOAP_1_2,
                   'login'        => $user_data[0]['sCallUsername'], 
                   'password'     => $user_data[0]['sCallPassword']));                  
                 
                $soundData = file_get_contents($file_url); 
                $createSoundRequest = array(
                    'Name' => 'My new sound',
                    'Data' => $soundData);

                $soundId = $client->createSound($createSoundRequest);
                $where = "nCallId = '".$_SESSION['nCallId']."'";
                $call_data['sSoundId']  = $soundId;
                $directdata = dbRowUpdate('calls', $call_data, $where);

                $where = "nCampaignid = '".$nCampaignid."'";
                $camp_data['nCall']  = '1';
                $camp_data['nDraft']  = '1';
                $directdata = dbRowUpdate('campaigns', $camp_data, $where);

                if($_POST['sendmail'] == 'Yes'){
                    sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript1'],$base_url);
                }
                header('location:'.$page);
                exit;    
            }else{
                if($_POST['sendmail'] == 'Yes'){
                    sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript1'],$base_url);
                }            
                else
                {
                    $_SESSION['error_msg'] = 'Automated Call Not Registered!!';
                    header('location: step2.php');
                    exit;
                }
            }
        }
        else{
            if($_POST['sendmail'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript1'],$base_url);
            }
            else
            {            
                $_SESSION['error_msg'] = 'Invalid File';
                header('location: step2.php');
                exit;
            }           
        }        
    }
}
else{
    
    if($_FILES['sCallScript']['name'] != '')
    { 
        if(isset($_FILES["sCallScript"]['error'])) {

            $_SESSION['error_msg'] =  "Error: " . $_FILES["sCallScript"]["error"] . "<br>";
            header('location: step3.php');
            exit;
        } else {
            
            $allowedExts = array('mp3','wav');
            $extension = end(explode(".", $_FILES['sCallScript']["name"]));
            $sCallScript = '';

            $where = "nCampaignid = '".$_POST['nCampaignid']."'";
            $camp_data['nCall']  = '1';
            $camp_data['nDraft']  = '1';
            $directdata = dbRowUpdate('campaigns', $camp_data, $where);

            if(in_array($extension, $allowedExts)){
                
                move_uploaded_file($_FILES["sCallScript"]["tmp_name"], $upload_path.$_FILES["sCallScript"]["name"]);
                $sCallScript = $_FILES["sCallScript"]["name"];           

                $where = "nCallId = '".$_SESSION['nCallId']."'";
                $data['sCallTitle']     =   $sCallTitle;
                $data['dCallSchedTime'] =   $dCallSchedTime;
                $data['sCallScript']    =   $sCallScript;

                $_SESSION['dCallSchedTime'] = $dCallSchedTime;
                $_SESSION['sCallScript'] = $sCallScript;

                $file_url = $upload_url.$sCallScript;
                $wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";
                
                $client = new SoapClient($wsdl, array(
                   'soap_version' => SOAP_1_2,
                   'login'        => $user_data[0]['sCallUsername'], 
                   'password'     => $user_data[0]['sCallPassword']));                  
                 
                
                $soundData = file_get_contents($file_url); 
                $createSoundRequest = array(
                    'Name' => 'My new sound',
                    'Data' => $soundData);

                $soundId = $client->createSound($createSoundRequest);
                $data['sSoundId']    =   $soundId;
            
                $return_data = dbRowUpdate('calls', $data, $where);
                $_SESSION['success_msg'] = 'Automated Call Registered!!';
            }
            else{            
                $_SESSION['error_msg'] = 'Automated Call Not Registered!!';
            }

            if($_POST['sendmail'] == 'Yes'){
                sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript1'],$base_url);
            }
            else{    
                header('location: step3.php');
                exit;
            }
        }
    }
    else{  
        $where = "nCampaignid = '".$_POST['nCampaignid']."'";
        $camp_data['nCall']  = '1';
        $camp_data['nDraft']  = '1';
        $directdata = dbRowUpdate('campaigns', $camp_data, $where);            
        
        $where = "nCallId = '".$_SESSION['nCallId']."'";
        $data['sCallTitle']     =   $sCallTitle;
        $data['dCallSchedTime'] =   $dCallSchedTime;
        $return_data = dbRowUpdate('calls', $data, $where);
        $_SESSION['dCallSchedTime'] = $dCallSchedTime;

        $page = $_POST['redirect_page'];
        
        if($_POST['sendmail'] == 'Yes'){
            sendmail($_SESSION['nCEmaiId'],$_SESSION['nUserID'],$_POST['sEmailScript1'],$base_url);
        }
            
        header('location:'.$page);
        exit;    
    }
}
?>