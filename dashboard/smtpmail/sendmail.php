<?php
//include('library/function.php');
include('classes/class.phpmailer.php');

/*$where = "nCEmaiId = '".$_SESSION['nCEmaiId']."'";
$champaign_data = getAnyData('*','campaignEmails',$where,null,null);
$body = nl2br($champaign_data[0]['sEmailScript']);
$subject = nl2br($champaign_data[0]['sEmailSubject']);

$where = "u.nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
$table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId";
$login_data = getAnyData('*',$table,$where,null,null);
*/

$mail = new PHPMailer();
$mail->IsHTML(true);
$mail->IsSMTP();
$mail->SMTPAuth = true;
/*$mail->SMTPSecure = "ssl";
$mail->Host = 'pro.turbo-smtp.com';
$mail->Port = '465';
$mail->Username = 'costas@digitalcharis.com';
$mail->Password = 'Lt0vHblJ';*/
$mail->SMTPSecure = "ssl";
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 25;
$mail->Username = "php.zaptech@gmail.com";
$mail->Password = "zaptech1234#";
$fromname = 'testuesr';
$body = 'Teest mail';
$subject = 'asasasas';
#$To = trim($login_data[0]['sUserEmail'],"\r\n");
$To = "php.zaptech@gmail.com";
$tContent = '';
$tContent .="<table width='550px' colspan='2' cellpadding='4'>
        <tr><td align='left'><h3>SalesMonkey</h3></td></tr>
        <tr><td height='20'>&nbsp;</td></tr>
        <tr><td>".$body."</td></tr>
        </table>";
$mail->From = $admin_email;
$mail->FromName = $fromname;
$mail->Subject = $subject;
$mail->Body = $tContent;
$mail->AddAddress($To);
$mail->set('X-Priority','1'); //Priority 1 = High, 3 = Normal, 5 = low
$SentResult = $mail->Send();
if($SentResult) {
    echo 'Email send successfully.';
}
else{
    echo
     'There is some issue with sending email.';
}
?>