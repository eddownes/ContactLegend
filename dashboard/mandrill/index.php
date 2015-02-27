<?php
require_once 'src/Mandrill.php';
require_once 'config.php';
$mandrill = new Mandrill('quprgpshtLiqo8VOWn2tcw');

/*
$id = 'fb4878985654444dbec55b1733825491';
    $result = $mandrill->messages->info($id);
//    echo "<pre>";
//    print_r($result);exit;
if(intval($result['opens']) > 0){
$sql = "UPDATE track_email SET eStatus='Read' WHERE vEmail='" . $result['email'] . "' AND vSentId='" . $result['_id'] . "'"; 
$result = mysql_query($sql) or die(mysql_error() . '  at Line:' . __LINE__ . "<br/>" . $sql);
}
print_r($result);exit;
*/
if(isset($_POST["send"])){
$toemail = $_POST["toemail"];
$fromemail = $_POST["fromemail"];
$subject = $_POST["subject"];
$message = $_POST["message"];
try {
    
    $message = array(
        'html' => $message,
        'subject' => $subject,
        'from_email' => $fromemail,
        'to' => array(
            array(
                'email' => $toemail
            )
        )
    );
    $async = false;
    $result = $mandrill->messages->send($message, $async);

    if(!empty($result) && $result[0]['status'] == 'sent')
    {
	$sql = "INSERT INTO track_email (`vEmail`,`vSentId`) VALUES ('".$toemail."','".$result[0]['_id']."')";
        $result = mysql_query($sql) or die(mysql_error() . '  at Line:' . __LINE__ . "<br/>" . $sql);
        echo "<span style='color:#006600;'>Message has been sent</span>";
    }else{
	echo "<span style='color:#800000;'>Error in sending mail</span>";
    }
} catch (Mandrill_Error $e) {
    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
    throw $e;
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Send mails using SMTP and PHP in PHP Mailer using our own server or gmail server by Asif18</title>
<meta name="keywords" content="send mails using smpt in php, php mailer for send emails in smtp, use gmail for smtp in php, gmail smtp server name"/>
<meta name="description" content="Send mails using SMTP and PHP in PHP Mailer using our own server or gmail server"/>
<style>
.as_wrapper{
	font-family:Arial;
	color:#333;
	font-size:14px;
}
.mytable{
	padding:20px;
	border:2px dashed #17A3F7;
	width:100%;
}
</style>
<body>
<div class="as_wrapper">
    <form action="" method="post" name="sendmail">
    <table class="mytable" width="500px;" style="margine:0 auto;">
    <tr>
	<td width="20%">To Email:</td>
    	<td width="80%"><input type="email" placeholder="To Email" name="toemail" /></td>
    </tr>
    <tr>
	<td width="20%">From Email:</td>
    	<td width="80%"><input type="email" placeholder="From Email" name="fromemail" /></td>
    </tr>
    <tr>
	<td>Subject:</td>
    	<td><input type="text" placeholder="Subject" name="subject" /></td>
    </tr>
    <tr>
	<td valign="top">Message:</td>
    	<td><textarea cols="50" rows="10" name="message"></textarea></td>
    </tr>
    <tr>
	<td></td>
    	<td><input type="submit" name="send" value="Send Mail" /></td>
    </tr>
    </table>
    </form>
</div>
</body>
</html>
