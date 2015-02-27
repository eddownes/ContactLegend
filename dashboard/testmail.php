<?php
include('library/function.php');
sendmail1('21','2',$base_url);


function sendmail1($nCEmaiId,$nUserID,$base_url)
{

    $where = "nCEmaiId = '".$nCEmaiId."'";
    $champaign_data = getAnyData('*','campaignEmails',$where,null,null);
    $nCampaignid = $champaign_data[0]['nCampaignid'];

    $where = "u.nUserID = '".$nUserID."' AND bStatus = 1";
    $table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId";
    $login_data = getAnyData('*',$table,$where,null,null);

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
    
    for($i=0;$i<$tot_customer;$i++){

        $url = $base_url.'smtpmail/email.php?c='.$customer_data[$i]['nCampaignCustId'];
        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/>       <style type='text/css'>            /* Ink styles go here in production */          </style>    <style type='text/css'>      /* Your custom styles go here */    </style>  </head>  <body>    <table class='body'>      <tr>        <td class='center' align='center' valign='top'><center>".$message."  <!-- Email Content -->      <img src= '".$url."' alt='' style='border:opx;' title='' />          </center>        </td>      </tr>    </table>  </body></html>";

        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        /*$mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = $smtphost;
        $mail->Port = $smptpport; // or 587
        $mail->Username = $smtpusername;
        $mail->Password = $smtppassword;
        $mail->SetFrom($fromemail);*/

        /*$mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "plus.smtp.mail.yahoo.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username="zaptest@ymail.com";  // GMAIL username
        $mail->Password   = "test123#";
        $mail->SetFrom('zaptest@ymail.com');*/

        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username   = "php.zaptech@gmail.com";  // GMAIL username
        $mail->Password   = "zaptech123#";
        $mail->SetFrom('php.zaptech@gmail.com');
        
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        #$toemail = $customer_data[$i]['sCustomerEmail'];    
        $toemail = 'zaptest@ymail.com';
        $mail->AddAddress($toemail);
        #$mail->Send();
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo .'<br/>';
        } else {
            echo "Message sent! <br/>";
        }
    }

   /* $where = "nCEmaiId = '".$nCEmaiId."'";
    $data['sEmailScript'] = $message;
    $return = dbRowUpdate('campaignEmails', $data, $where);

    $where = "nCampaignid = '".$nCampaignid."'";
    $campaigns_data['nEmailsSent'] = $tot_customer;
    $campaigns_data['nEmailsOpened'] = '0';
    $campaigns_data['nDraft'] = '0';
    $return = dbRowUpdate('campaigns', $campaigns_data, $where);*/
}
?>
