<?php
include('library/function.php');
include('smtpmail/classes/class.phpmailer.php');
$current_time = strtotime(date("Y-m-d H:i:s"));
$where = "bStatus = 0 ";
$follow_data =	getAnyData('*','schedulefollowemails',$where,null,null);
$tot_follow = count($follow_data);
echo $base_url;
// query to check whether cron is working by inserting into database below values
$sql = "insert into test_cronjob (`test_datetime`,`page`) VALUES ('".date("Y-m-d H:i:s")."','Followup')";
mysql_query($sql);

if($tot_follow > 0){
	
	for($i=0;$i<$tot_follow;$i++){
		
		$dtCreated 	 	= 	$follow_data[$i]['dtCreated'];		
		$dScheduleTime 	=  	$follow_data[$i]['dScheduleTime'];
		$follow_time	=  	strtotime($dScheduleTime);

		if($current_time >= $follow_time){
			$nCampaignid 	= 	$follow_data[$i]['nCampaignid'];
			$toemail 	 	=  	$follow_data[$i]['sCustomerEmail'];
			$where 			= 	"nFollowupId = '".$follow_data[$i]['nFollowupId']."'";
			$table 			= 	"followupEmail";
			$email_data		=	getAnyData('*',$table,$where,null,null);

			$where 	= "nCampaignid = '".$nCampaignid."'";
			$table 	= "  campaigns as c 
						Left Join users as u on u.nUserId = c.nUserID
					    Left Join user_smtpdetails as sd on sd.nUserId = c.nUserId
					 ";
			$field 	= 'c.nEmailFollowUps,u.sUserFullName,u.sUserEmail,sd.sServerName,sd.sUsername,sd.sPassword,sd.sPorts';
			
			$user_data		=	getAnyData($field,$table,$where,null,null);

			#$url = $base_url.'smtpmail/email.php?f='.$follow_data[$i]['nScheduleFollowMailId'];
			$url = 'http://contactlegend.com/dashboard/smtpmail/email.php?f='.$follow_data[$i]['nScheduleFollowMailId'];
			
			$fromemail		=	$user_data[0]['sUsername'];
			$smtphost		=	$user_data[0]['sServerName'];
			$smptpport		=	$user_data[0]['sPorts'];
			$smtpusername 	= 	$user_data[0]['sUsername'];
			$smtppassword 	= 	$user_data[0]['sPassword'];
			$subject 		= 	nl2br($email_data[0]['sSchEmailSubject']);
			$message 		=	$email_data[0]['sSchEmailBody'];

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
											".$message."  <!-- Email Content -->  
											<img style='visibility: hidden;' src= '".$url."' alt='' style='border:0px;' title='' />
										</td>
									</tr>
								</table>
							</body>
						</html>";

			$mail = new PHPMailer(true); // create a new object
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			$mail->Host = $smtphost;
			if($smtphost == "pro.turbo-smtp.com"){
				$mail->Port = "465"; // or 587
				$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			} else {
				$mail->Port = $smptpport; // or 587
				$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
			}
			$mail->IsHTML(true);
			$mail->Username = $smtpusername;
			$mail->Password = $smtppassword;
			$mail->SetFrom($fromemail);
			$mail->Subject = $subject;
			$mail->Body = $body;
			$mail->AddAddress($toemail);
			#$mail->Send();
			if(!$mail->Send()) {
				echo "Followup:".$el = $mail->ErrorInfo."_".$nCampaignid;
	            error_log($el, 3, "error_log.log");
	            //echo "Mailer Error: " . $mail->ErrorInfo .'<br/>';
	        } else {
	            error_log("Message sent! \r\n", 3, "error_log.log");
	            //echo "Message sent! <br/>";
	        }

			$query = "update schedulefollowemails set bStatus = '1' , bFollowUpMailSent = '1' WHERE nScheduleFollowMailId='".$follow_data[$i]['nScheduleFollowMailId']."'";
			$result = mysql_query($query);	

			$nEmailFollowUps = $user_data[0]['nEmailFollowUps'] + 1;
			$query = "update campaigns set nEmailFollowUps = '".$nEmailFollowUps."' WHERE nCampaignid='".$nCampaignid."'";
			$result = mysql_query($query);	


			$bTotalEmailSend = $email_data[0]['bTotalEmailSend']+1;
			$query = "update followupEmail set bTotalEmailSend = '".$bTotalEmailSend."' WHERE nFollowupId='".$email_data[0]['nFollowupId']."'";
			$result = mysql_query($query);	

			echo 'Success';
			sleep(1);
		}
	}
}
?>