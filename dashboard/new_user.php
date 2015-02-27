<?php
include('library/function.php');
include('smtpmail/classes/class.phpmailer.php');

$username = $_REQUEST['name'];
$email = $_REQUEST['email'];
$password = generateRandomString();

if(isset($username) && isset($email) && isset($password))
{
    //pr($_REQUEST); die;
	$tot_rec = check_email($email,null);
	if($tot_rec <= 0)
	{
		$message = "<table class='body'>
	                    <tr>
	                        <td valign='top'>
	                            <center><h1>Contact Legend</h1></center>
	                        </td>
	                    </tr>
	                    <tr>
	                        <td valign='top'>You are successfully registered with us.</td>
	                    </tr>
	                    <tr>
	                        <td valign='top'>Your login details are as below.</td>
	                    </tr>
	                    <tr>
	                        <td valign='top'>Your email :: ".$email."</td>
	                    </tr>
	                    <tr>
	                        <td valign='top'>Your password: ".$password."</td>
	                    </tr>	                     
	                    <tr><td>You may login <a href=".$base_url." target='_blank'>by clicking here</a>.</td></tr>
	                    <tr><td>&nbsp;</td></tr>
	                    <tr><td>Thank You.</td></tr>
	                    <tr><td>Contact Legend.</td></tr>
					</table>";

        $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/></head>  <body>".$message."</body></html>";

        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        //$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = '72.52.245.168';
        $mail->Port = '25'; // or 587
        $mail->IsHTML(true);
        $mail->Username = 'no-reply@contactlegend.com';
        $mail->Password = 't6[n6c{Py5Q1';
        $mail->SetFrom('no-reply@contactlegend.com');
        $mail->Subject = 'Welcome to ContactLegend';
        $mail->Body = $body;
        $toemail = $email;    
        $mail->AddAddress($toemail);
        //$mail->Send();
        if (!$mail->send()) {
           echo "Mailer Error: " . $mail->ErrorInfo; exit;
        } 

        $data['sUserFullName']      =   $username;
	    $data['sUserEmail']         =   $email;
	    $data['sUserPassword']      =   $password;
	    $data['bStatus']            =   1;
	   	$data['dCreatedDate']       =   date('Y-m-d H:i:s');
        $return_data                =   dbRowInsert('users', $data);        
        
        $smtp_data['nUserID']       =   $return_data;

        if($return_data) {
            $return_smtpdata = dbRowInsert('user_smtpdetails', $smtp_data);
            $_SESSION['success_msg'] = 'Account has been created in our system. Please check your email for login credentials.';
            header('location: https://strategicinnercircle.clickfunnels.com/upsell');
            exit;
        } else {
            $_SESSION['success_msg'] = 'There is an error creating account. Please contact support.';
            header('location: https://strategicinnercircle.clickfunnels.com/upsell');
            exit;
        }
    } else {        	
    	echo  'User email already register with us..'; 
        $_SESSION['success_msg'] = 'Account has been created in our system. Please use "forgot password" to have your password emailed to you.';
        header('location: https://strategicinnercircle.clickfunnels.com/upsell');
        exit;
    }
}
?>