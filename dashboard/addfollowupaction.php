<?php
include('library/function.php');
include('library/PDFInfo.php');
//include_once('click2mail/Client/Click2mail_Mailingonline_Api.php');
$direct_mode = $_POST['direct_mode'];
$phone_mode = $_POST['phone_mode'];
$followup_mode = $_POST['followup_mode'];
$phone_del = $_POST['phone_del'];
$direct_del = $_POST['direct_del'];
$followup_del = $_POST['followup_del'];
$nCampaignid = $_POST['nCampaignid'];
$id = $_POST['Id'];
$f = $_POST['f'];
error_reporting(E_ALL);

//pr($_FILES); 
//function to check number pf pages in pdf
function getPDFPages($document)
{
    $cmd = "pdfinfo";           // Linux
    $cmd = "pdfinfo";  // Windows
    
//    echo $document; die;

    // Parse entire output
    exec("$cmd $document", $output);

    // Iterate through lines
    $pagecount = 0;
    foreach($output as $op)
    {
        // Extract the number
        if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1)
        {
            $pagecount = intval($matches[1]);
            break;
        }
    }

    return $pagecount;
}

// Direct Mail follow up action
if(!empty($_FILES['sDirectEmailBody']['type']))
{
	if($direct_mode == 'Add')
	{    
	    if(isset($_FILES["sDirectEmailBody"]['error']) && $_FILES["sDirectEmailBody"]['error'] != "0") 
	    {
	        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
	        header('location:followupaction.php?id='.$id);
			exit;
	    } 
	    else if($_FILES["sDirectEmailBody"]['size'] > '20971520')
	    {
        	$_SESSION['error_msg'] = 'File is too large to upload. Maximum allowed file size is 20MB.';
            header('location:followupaction.php?id='.$id);
			exit;
	    } 
	    else 
	    {
	        $semailletter = 0;
	        $nDirectEmailStatus = 0;
	        $fname = explode(".", $_FILES['sDirectEmailBody']['name']);
	        $extension = end($fname);
	        if(strtolower($extension) == 'pdf')
	        {
	        	/*$p = new PDFInfo;
				$p->load($_FILES["sDirectEmailBody"]["tmp_name"]);

				$page_count = $p->pages;
				if($page_count > 10 || $page_count == ""){
					$_SESSION['error_msg'] = 'API only allows 10 pages in PDF document. Please upload another PDF file.';
		            header('location:followupaction.php?id='.$id);
					exit;
				}*/
               
	            $file_name = str_replace(' ',"_", $_FILES["sDirectEmailBody"]["name"]);	            
	            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$file_name);
                
                $document = $upload_path.$file_name;   // Surround by double quotes if filename has spaces
                $page_count = getPDFPages($document);
               // echo $page_count; die;
                if($page_count > 10 || $page_count == "" || $page_count == 0 ){
					$_SESSION['error_msg'] = 'API only allows 10 pages in PDF document. Please upload another PDF file.';
					unlink($upload_path.$file_name);
		            header('location:followupaction.php?id='.$id);		            
					exit;
				}
				
	            $sDirectEmailBody = $file_name;  
	            $nCampaignid = $_SESSION['nCampaignid'];
	            chmod($upload_path.$sDirectEmailBody, 777);

	            
	            $nCampaignid = $_SESSION['nCampaignid'];
	            $query = "INSERT INTO `directemail` (`nDirectEmailId`,`nCampaignid`,`semailletter`,`sDirectEmailBody`,`nDirectEmailStatus`)VALUES (NULL , '$nCampaignid', '$semailletter', '$sDirectEmailBody', '$nDirectEmailStatus')";
	            $result = mysql_query($query);
	            $_SESSION['nDirectEmailId'] = mysql_insert_id();
	            
	            if($result == true){
	                
	                $where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
	                $emaildata['eDirectMode']  = 'Yes';
	                $return_data = dbRowUpdate('campaignEmails', $emaildata, $where);
	                $_SESSION['semailletter'] = 'Email Script';
	                $where = "nCampaignid = '".$nCampaignid."'";
	                $camp_data['nDirectEmail']  = '1';
	                $camp_data['nDraft']  = '1';
	                $directdata = dbRowUpdate('campaigns', $camp_data, $where);

	            }else{
	                $_SESSION['error_msg'] = 'Direct Email Not Registered!!';
	                header('location:followupaction.php?id='.$id);
	                exit;
	            }
	        }
	        else{
	            $_SESSION['error_msg'] = 'Invalid PDF File';
	            header('location:followupaction.php?id='.$id);
	            exit;                                   
	        }  
	    }      
	}
	else if($direct_mode == 'Update')
	{
	    if(isset($_FILES["sDirectEmailBody"]['error']) && $_FILES["sDirectEmailBody"]['error'] != "0") {
	        $_SESSION['error_msg'] =  "Error: " . $_FILES["sDirectEmailBody"]["error"] . "<br>";
	        header('location:followupaction.php?id='.$id.'&f='.$f);
	        exit;
	    } 
	    else if($_FILES["sDirectEmailBody"]['size'] > '20971520')
	    {
        	$_SESSION['error_msg'] = 'File is too large to upload. Maximum allowed file size is 20MB.';
            header('location:followupaction.php?id='.$id.'&f='.$f);
			exit;
	    }
	    else 
	    {
	        $allowedExts = array('pdf');
	        $f_name = explode(".", $_FILES['sDirectEmailBody']["name"]);
	        $extension = end($f_name);
	        $sDirectEmailBody = '';
	        if(strtolower($extension) == 'pdf'){

	        	/*$p = new PDFInfo;
				$p->load($_FILES["sDirectEmailBody"]["tmp_name"]);

				$page_count = $p->pages;
				if($page_count > 10 || $page_count == ""){
					$_SESSION['error_msg'] = 'API only allows 10 pages in PDF document. Please upload another PDF file.';
		            header('location:followupaction.php?id='.$id);
					exit;
				}*/
	        	
	        	$file_name = str_replace(' ',"_", $_FILES["sDirectEmailBody"]["name"]);	            
	            move_uploaded_file($_FILES["sDirectEmailBody"]["tmp_name"], $upload_path.$file_name);
	            
                $document = $upload_path.$file_name;   // Surround by double quotes if filename has spaces
                $page_count = getPDFPages($document);
                //echo $page_count; die;
                if($page_count > 10 || $page_count == "" || $page_count == 0 ){
					$_SESSION['error_msg'] = 'API only allows 10 pages in PDF document. Please upload another PDF file.';
					unlink($upload_path.$file_name);
		            header('location:followupaction.php?id='.$id);
					exit;
				}
                
                $sDirectEmailBody = $file_name;  
	            $semailletter = 0;
	            $nDirectEmailStatus = 0;

	            $documentId = "";
                $sql_update = 'update directemail set sDirectEmailBody = "'.nl2br($sDirectEmailBody).'",nDocumentId ="'.$documentId.'",nDirectEmailStatus="'.$nDirectEmailStatus.'",semailletter ="'.$semailletter.'" where nDirectEmailId = "'.$_SESSION['nDirectEmailId'].'"';
                mysql_query($sql_update);
	        }
	        else
	        {
				$_SESSION['error_msg'] = 'Invalid File';
                header('location:followupaction.php?id='.$id.'&f='.$f);
        		exit;
	        }
	    }
	}	
}

// Phone call follow up action
if((!empty($_FILES['sCallScript']['type']))  || (!empty($_POST['phone_hrs'])) )
{
	$where = "nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
	$table = " users";
	$user_data = getAnyData('sCallUsername,sCallPassword',$table,$where,null,null);
	$sCallTitle = mysql_real_escape_string($_POST['sCallTitle']);
	$dCallSchedTime = $_POST['phone_hrs'].":".$_POST['phone_min'].": 00";
	$eCallStatus = 0;
	$upload_dir = "upload/";
	$mode = $_POST['mode'];
	$nDraft = $_POST['nDraft'];
	$res_followup = check_data_campaign($_SESSION['nCampaignid']);

	if($phone_mode == 'Add')
	{
		if($_FILES["sCallScript"]['error'] != '0') {
			$_SESSION['error_msg'] =  "Error: " . $_FILES["sCallScript"]["error"] . "<br>";
			header('location:followupaction.php?id='.$id);
			exit;
	    } 
	    else if($_FILES["sCallScript"]['size'] > '20971520'){
        	$_SESSION['error_msg'] = 'File is too large to upload. Maximum allowed file size is 20MB.';
            header('location:followupaction.php?id='.$id);
			exit;
	    }  
	    else 
	    {
	    	$allowedExts = array('mp3','wav');
	        $extension = end(explode(".", $_FILES['sCallScript']["name"]));
	        if(in_array($extension, $allowedExts)){
	        	$file_name = str_replace(' ',"_", $_FILES["sCallScript"]["name"]);
	            move_uploaded_file($_FILES["sCallScript"]["tmp_name"], $upload_path.$file_name);
	            $sCallScript = $file_name;
	            $nCampaignid = $_SESSION['nCampaignid'];

	            $query = "INSERT INTO `calls` (`nCallId`,`nCampaignid`,`sCallTitle`,`dCallSchedTime`,`sCallScript`,`eCallStatus`)VALUES (NULL , '$nCampaignid', '$sCallTitle', '$dCallSchedTime', '$sCallScript', '$eCallStatus')";
	            $result = mysql_query($query);
	            
	            $_SESSION['nCallId'] = mysql_insert_id();
	            
	            if($result == true){

	                //$_SESSION['success_msg'] = 'Automated Call Registered!!';
	                $_SESSION['dCallSchedTime'] = $dCallSchedTime;
	                $_SESSION['sCallScript'] = $sCallScript;
	                $where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
	                $emaildata['eFollowUpMail']  = 'Yes';
	                $return_data = dbRowUpdate('campaignEmails', $emaildata, $where);

	                $file_url = $upload_url.$sCallScript;
	            }
	            else
	            {
	            	$_SESSION['error_msg'] = 'Automated Call Not Registered!!';
	            	header('location:followupaction.php?id='.$id);
					exit;
	            }
	        }
	        else
	        {
	        	$_SESSION['error_msg'] = 'Invalid File';
	        	header('location:followupaction.php?id='.$id);
				exit;
	        }        
	    }	
	}
	else
	{
		if($_FILES['sCallScript']['name'] != '')
	    { 
	        if($_FILES["sCallScript"]['error'] != '0') {
	        	$_SESSION['error_msg'] =  "Error: " . $_FILES["sCallScript"]["error"] . "<br>";
				header('location:followupaction.php?id='.$id.'&f='.$f);
				exit;
	        } 
	        else if($_FILES["sCallScript"]['size'] > '20971520'){
	            	$_SESSION['error_msg'] = 'File is too large to upload. Maximum allowed file size is 20MB.';
	                header('location:followupaction.php?id='.$id.'&f='.$f);
					exit;
	        } 
	        else 
	        {  
	        	$allowedExts = array('mp3','wav');
	            $extension = end(explode(".", $_FILES['sCallScript']["name"]));
	            $sCallScript = '';

	            $where = "nCampaignid = '".$_POST['nCampaignid']."'";
	            $camp_data['nCall']  = '1';
	            $camp_data['nDraft']  = '1';
	            $directdata = dbRowUpdate('campaigns', $camp_data, $where);

	            if(in_array($extension, $allowedExts)){
	                $file_name = str_replace(' ',"_", $_FILES["sCallScript"]["name"]);
	                move_uploaded_file($_FILES["sCallScript"]["tmp_name"], $upload_path.$file_name);
	                $sCallScript = $file_name;           

	                $where = "nCallId = '".$_SESSION['nCallId']."'";
	                $data_call['sCallTitle']     =   $sCallTitle;
	                $data_call['dCallSchedTime'] =   $dCallSchedTime;
	                $data_call['sCallScript']    =   $sCallScript;

	                $_SESSION['dCallSchedTime'] = $dCallSchedTime;
	                $_SESSION['sCallScript'] = $sCallScript;

	                $file_url = $upload_url.$sCallScript;
	            
	                $return_data = dbRowUpdate('calls', $data_call, $where);
	            }
	            else{            
	                $_SESSION['error_msg'] = 'Automated Call Not Registered!!';
	                header('location:followupaction.php?id='.$id.'&f='.$f);
					exit;
	            }
			}
	    }
	    else
	    {  
	        $where = "nCampaignid = '".$_POST['nCampaignid']."'";
	        $camp_data['nCall']  = '1';
	        $camp_data['nDraft']  = '1';
	        $directdata = dbRowUpdate('campaigns', $camp_data, $where);            
	        
	        $where = "nCallId = '".$_SESSION['nCallId']."'";
	        $data_call['sCallTitle']     =   $sCallTitle;
	        $data_call['dCallSchedTime'] =   $dCallSchedTime;
	        $return_data = dbRowUpdate('calls', $data_call, $where);
	        $_SESSION['dCallSchedTime'] = $dCallSchedTime;
	    }
	}
}

// follow up email 
if((!empty($_POST['dScheduleTime']))  || (!empty($_POST['sSchEmailSubject'])) )
{
	$data_mail['dScheduleTime'] 		= 	$_POST['mail_hrs'].":".$_POST['mail_min'].": 00";
	$data_mail['sSchEmailSubject'] 		= 	mysql_real_escape_string($_POST['sSchEmailSubject']);
	$data_mail['sSchEmailBody'] 		= 	addslashes($_POST['sSchEmailBody']);
	$data_mail['dtCreated'] 			=	date("Y-m-d H:i:s");	
	$where = "nUserID = '".$_SESSION['nUserID']."'";
	$table = " user_smtpdetails";
	$user_data = getAnyData('sServerName,sUsername,sPassword,sPorts',$table,$where,null,null);

	if($user_data[0]['sServerName'] != '' && $user_data[0]['sUsername'] != '' && $user_data[0]['sPassword'] != '' && $user_data[0]['sPorts'] != '')
	{
		if($data_mail['dScheduleTime'] == '' || $data_mail['dScheduleTime'] == 5){
			$data_mail['dScheduleTime'] = '00:05';
		}

		if($followup_mode == 'Add'){

			$data_mail['nCampaignid'] = $_SESSION['nCampaignid'];
			$result = dbRowInsert('followupEmail', $data_mail);
			$_SESSION['nFollowupId'] = $result;

			$where = "nCampaignid = '".$data_mail['nCampaignid']."'";
		    $camp_data['nFollowEmail']  = '1';
		    $camp_data['nDraft']  = '1';
		    //$_SESSION['success_msg'] = "Follow Up Email Registered successfully";
		    $directdata = dbRowUpdate('campaigns', $camp_data, $where);

		    if($result == true){
				$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
				$emaildata['eFollowUpMail']  = 'Yes';
		    	$return_data = dbRowUpdate('campaignEmails', $emaildata, $where);	
				$_SESSION['dScheduleTime'] = $data_mail['dScheduleTime'];
				   
			}else{

				$_SESSION['error_msg'] = "Follow Up Email not Registered";
				header('location:followupaction.php?id='.$id);
				exit;
			}
		}
		else if($followup_mode == 'Update')
		{
			$data_email['eEmailOpened'] = 0;		
			$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
			$return_data = dbRowUpdate('campaigncustomers', $data_email, $where);

			$where = "nFollowupId = '".$_SESSION['nFollowupId']."'";
			//$_SESSION['success_msg']    =   'Campaign updated successfully.';
			$return_data = dbRowUpdate('followupEmail', $data_mail, $where);
			
			$where = "nCampaignid = '".$_SESSION['nCampaignid']."'";
			$emaildata['eFollowUpMail']  = 'Yes';
			$return_data = dbRowUpdate('campaignEmails', $emaildata, $where);	
			$_SESSION['dScheduleTime'] = $data_mail['dScheduleTime'];
		}
	}
	else
	{
		$_SESSION['error_msg'] = "Please Enter followup integration details from account section to use this followup action";
		header('location:followupaction.php?id='.$id);
		exit;
	}
}

if($phone_del == 'Yes'){
	$_SESSION['nCallId'] = '';
	$sql_delphone = mysql_query("Delete from calls where nCampaignid = '".$nCampaignid."'");
}

if($direct_del == 'Yes'){
	$_SESSION['nDirectEmailId'] = '';
	$sql_delphone = mysql_query("Delete from directemail where nCampaignid = '".$nCampaignid."'");
}

if($followup_del == 'Yes'){
	$_SESSION['nFollowupId'] = '';
	$sql_delphone = mysql_query("Delete from followupEmail where nCampaignid = '".$nCampaignid."'");
}

if($_POST['draft'] == 'Yes'){
	header('location:userdashboard.php');
	exit;
}
else{
	header('location:sendandpreview.php?id='.$id.'&f='.$f);
	exit;
}

function tofloat($num) {
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
  
    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
}

?>