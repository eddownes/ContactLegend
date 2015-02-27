<?php
include('library/function.php');
include('smtpmail/classes/class.phpmailer.php');
error_reporting(1);
$current_time = strtotime(date("Y-m-d H:i:s"));
//echo $base_url;
// query to check whether cron is working by inserting into database below values
$sql = "insert into test_cronjob (`test_datetime`,`page`) VALUES ('".date("Y-m-d H:i:s")."','Send Campaign')";
mysql_query($sql);

$where = "nDraft = 0 AND bStarted = 0 ";
$follow_data =	getAnyData('*','campaigns',$where,null,null);
$tot_follow = count($follow_data);

//pr($follow_data);

if($tot_follow > 0){
	
	for($i=0;$i<$tot_follow;$i++){
		
		$nCampaignid = $follow_data[$i]['nCampaignid'];
		$nUserID = $follow_data[$i]['nUserID'];		
		
		echo $query = "SELECT nCEmaiId FROM campaignEmails WHERE nCampaignid = ".$nCampaignid;
    	$result = dbQuery($query);
    	$row = dbFetchRow($result);
    	//pr($row);

		//sendmail($row[0],$nUserID,$base_url);
    	$nCEmaiId = $row[0];
    	
		$where = "nCEmaiId = '".$nCEmaiId."'";
	    $champaign_data = getAnyData('*','campaignEmails',$where,null,null);
	    //$nCampaignid = $champaign_data[0]['nCampaignid'];

	    $where = "u.nUserID = '".$nUserID."' AND bStatus = 1";
	    $table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId";
	    $login_data = getAnyData('*',$table,$where,null,null);
		pr($login_data);
		
	    $fromemail = $login_data[0]['sUsername'];
	    $smtphost = $login_data[0]['sServerName'];
	    $smptpport = $login_data[0]['sPorts'];
	    $smtpusername = $login_data[0]['sUsername'];
	    $smtppassword = $login_data[0]['sPassword'];
	    $subject = nl2br($champaign_data[0]['sEmailSubject']);
	    $message = $champaign_data[0]['sEmailScript'];

	    $where = "nCampaignid = '".$nCampaignid."'";
	    $customer_data = getAnyData('*','campaigncustomers',$where,null,null);
	    $tot_customer = count($customer_data);
	    //pr($customer_data); //die;*/
		
		if($fromemail != "" && $smtpusername != "" && $smtppassword != "" && $smtphost != "" && $smptpport != "") {
		    for($j=0;$j<$tot_customer;$j++){

		        #$url = $base_url.'smtpmail/email.php?c='.$customer_data[$j]['nCampaignCustId'];
		        $url_img = 'http://contactlegend.com/dashboard/smtpmail/email.php?c='.$customer_data[$j]['nCampaignCustId'];
		        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
		            <html xmlns='http://www.w3.org/1999/xhtml'>  
		                <head>
		                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    
		                    <meta name='viewport' content='width=device-width'/>       
		                </head>  
		                <body>    
		                    <table class='body'>      
		                        <tr>        
		                            <td valign='top'>
		                            ".$message." <img style='visibility: hidden;' src= 'http://contactlegend.com/dashboard/smtpmail/email.php?c=".$customer_data[$j]['nCampaignCustId']."' alt='' style='border:opx;' title='' />
		                            </td>      
		                        </tr>    
		                    </table>  
		                </body>
		            </html>";
		           echo $body;
		        $mail = new PHPMailer(true); // create a new object
		        $mail->IsSMTP(); // enable SMTP
		        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		        $mail->SMTPAuth = true; // authentication enabled
		        
		        $mail->Host = $smtphost;
				if($smtphost == "pro.turbo-smtp.com"){
					$mail->Port = "465"; // or 587
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
				} else {
					$mail->Port = $smptpport; // or 587
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
				}
		        $mail->Username = $smtpusername;
		        $mail->Password = $smtppassword;
		        $mail->SetFrom($fromemail);
		        $mail->IsHTML(true);
		        $mail->Subject = $subject;
		        $mail->Body = $body;
		        $toemail = $customer_data[$j]['sCustomerEmail'];    
		        $mail->AddAddress($toemail);
		        //$mail->Send();
		        sleep(1);
				try{
					if(!$mail->Send()) {
						//error_log($mail->ErrorInfo, 3, "error_log");
						echo "Mailer Error: " . $mail->ErrorInfo .'<br/>';
					} else {
						//error_log("Message sent! <br/>", 3, "error_log");
						//echo "Message sent! <br/>";
						
						$where = "nCampaignid = '".$nCampaignid."'";
						$campaigns_data['nEmailsSent'] = $tot_customer;
						$campaigns_data['nEmailsOpened'] = '0';
						//$campaigns_data['nDraft'] = '0';
						$campaigns_data['bStarted'] = '1';
						$return = dbRowUpdate('campaigns', $campaigns_data, $where);
					}
				} catch(Exception $e){
				
				}
		   	}
		}

		echo 'Success'; echo '<br>';
	}
}
?>