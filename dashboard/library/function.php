<?php
require_once 'config.php';

function dbQuery($sql) 
{
  
  $result = mysql_query($sql) or die(mysql_error() . '  at Line:' . __LINE__ . "<br/>" . $sql);
  return $result;
}

function dbAffectedRows() 
{
  global $dbConn;
  return mysql_affected_rows($dbConn);
}

function dbFetchArray($result, $resultType = MYSQL_NUM) 
{
  return mysql_fetch_array($result, $resultType);
}

function dbFetchAssoc($result) 
{
  return mysql_fetch_assoc($result);
}

function dbFetchRow($result) 
{
  return mysql_fetch_row($result);
}

function dbFreeResult($result) 
{
  return mysql_free_result($result);
}

function dbNumRows($result) 
{
  return mysql_num_rows($result);
}

function dbSelect($dbName) 
{
  return mysql_select_db($dbName);
}

function dbInsertId() 
{
  return mysql_insert_id();
}

function dbnumfields($result) 
{
  return mysql_num_fields($export);
}

function generateRandomString($length = 8) 
{
  return substr(str_shuffle("XY01456abCDEcdefgLMhijklmn23opqrstVWuvwxyzABF7GHIJKNOPQRST89UZ"), 0, $length);
}

function numberFormat($amt = 0) 
{
  return number_format($amt, 2);
}

function pr($data) 
{
  echo "<pre/>";print_r($data);
}

function getAnyData($select = '*',$table,$where = null,$lmt = null,$ordrby = null) 
{
    $Content = array();
    $where = ($where == '') ? '' : ' WHERE '.$where;
    if(!strstr($lmt,'LIMIT'))
    {
        $lmt = ($lmt == '') ? '' : ' LIMIT '.$lmt;
    }
    
    $query = "SELECT $select FROM ".$table."  ".$where."  ".$ordrby." ".$lmt;
    $result = dbQuery($query);
    while($row = dbFetchAssoc($result)) 
    {
        $Content[] = $row;
    }
    return $Content;
}

function dbRowInsert($table_name, $form_data)
{
    $fields = array_keys($form_data);
    $sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`)VALUES('".implode("','",$form_data)."')";
    if(dbQuery($sql))
    {
        return mysql_insert_id();
    }
}

// the where clause is left optional incase the user wants to delete every row!
function dbRowDelete($table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;
     // run and return the query result resource
    if(dbQuery($sql)){
        return TRUE;
    }
}

// again where clause is left optional
function dbRowUpdate($table_name, $form_data, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";
    
    // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;
    // run and return the query result
    #echo $sql;#die;
   
    if(dbQuery($sql)){
        return TRUE;
    }
}

function get_imagedata($field,$table,$where)
{
    $sql = "select ".$field." from ".$table." where ".$where;
    $db_res = mysql_fetch_row(dbQuery($sql));
	  return $db_res[0];
}


function changeStatus($flag,$id,$tablename) 
{
    $sql = "UPDATE $tablename SET is_active = '$flag' WHERE id = '$id'  LIMIT 1";
    dbQuery($sql);
    return dbAffectedRows();
}

function formateDate($date) 
{
    return date("m-d-Y",strtotime($date));
}


function formateDateToYMD($date) 
{
    return date("Y-m-d",strtotime($date));
}


function random_string($type = 'alnum',$len = 8) 
{
    switch ($type) 
    {
      case 'basic' : return mt_rand();break;
      case 'alnum' :
      case 'numeric' :
      case 'nozero' :
      case 'alpha' :
        switch ($type) 
        {
            case 'alpha' : $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
            case 'alnum' : $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
            case 'numeric' : $pool = '0123456789';break;
            case 'nozero' : $pool = '123456789';break;
        }
        $str = '';
        for($i = 0; $i < $len; $i++) 
        {
            $str .= substr($pool,mt_rand(0,strlen($pool) - 1),1);
        }
        return $str;
      break;
      case 'unique' :
      case 'md5' :return md5(uniqid(mt_rand()));break;
      case 'encrypt' :
      case 'sha1' :
        $CI = & get_instance();
        $CI->load->helper('security');
        return do_hash(uniqid(mt_rand(),TRUE),'sha1');
        break;
    }
}

function checkEmpty($value)
{
    if(trim($value) == "")
    {
        return true;
    }
    else
    {
        return false;
    }
}

function check_email($email,$userid=null) 
{
    $condition = '';
    if($userid != null){
        $condition = " AND  nUserID != '".$userid."'";
    }
    
    $sql = mysql_query("Select count(*) as total from users where sUserEmail = '".$email."'".$condition);
    $db_res = mysql_fetch_assoc($sql);
    return $db_res['total'];
}



function check_email_address($email) 
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return 1;
    }
    else
    {
        return 0;
    }
}


function check_phone($phone) 
{
    #if(preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $phone)) {
    if(is_numeric($phone)) {  
        return 1;
    }
    else{
        return 0;
    }
}

function check_duplicate_email_address($email,$campaignid)
{
    $email = str_replace("'","",$email);
    $where = "nCampaignid = '".$campaignid."' AND sCustomerEmail = '".$email."'";
    $data = getAnyData('count(*) as total','campaigncustomers',$where);
    
    if(($data[0]['total']) > 0){
        return 0;
    }
    else
    {
        return 1;
    }
}

function check_data_campaign($campaignid){

    $total = '';
    $where = "nCampaignid = '".$campaignid."'";
    $data_call = getAnyData('count(*) as total_call','calls',$where);
    $data_followup = getAnyData('count(*) as total_follow','followupEmail',$where);
    $data_direct = getAnyData('count(*) as total_direct','directemail',$where);

    #echo $data_call[0]['total_call'] .'+'. $data_followup[0]['total_follow'].'+'.$data_direct[0]['total_direct'];
    $total = $data_call[0]['total_call'] + $data_followup[0]['total_follow']+$data_direct[0]['total_direct'];

    if($total>0){
        return 1;
    }
    else
    {
        return 0;
    }

}

function phone_format($string){
    $string = str_replace('-','', $string);
    $string = str_replace('(','', $string);
    $string = str_replace(')','', $string);
    $string = str_replace(' ','', $string);
    return $string;
}

function sendmail($nCEmaiId,$nUserID,$base_url)
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
                            ".$message." <img src= '".$url."' alt='' style='border:opx;' title='' />
                            </td>      
                        </tr>    
                    </table>  
                </body>
            </html>";
        
        $mail = new PHPMailer(); // create a new object
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = $smtphost;
        $mail->Port = $smptpport; // or 587
        $mail->Username = $smtpusername;
        $mail->Password = $smtppassword;
        $mail->SetFrom($fromemail);

        /*$mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
        $mail->Username="php.zaptech@gmail.com";  // GMAIL username
        $mail->Password   = "zaptech123#";
        $mail->SetFrom('php.zaptech@gmail.com');*/
        
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $toemail = $customer_data[$i]['sCustomerEmail'];    
        $mail->AddAddress($toemail);
        $mail->Send();
        sleep(1);
        if(!$mail->Send()) {
            error_log($mail->ErrorInfo, 3, "error_log");
            //echo "Mailer Error: " . $mail->ErrorInfo .'<br/>';
        } else {
            error_log("Message sent! <br/>", 3, "error_log");
            //echo "Message sent! <br/>";
        }
    }

    $where = "nCEmaiId = '".$nCEmaiId."'";
    $data['sEmailScript'] = $message;
    $return = dbRowUpdate('campaignEmails', $data, $where);

    $where = "nCampaignid = '".$nCampaignid."'";
    $campaigns_data['nEmailsSent'] = $tot_customer;
    $campaigns_data['nEmailsOpened'] = '0';
    //$campaigns_data['nDraft'] = '0';
    $campaigns_data['bStarted'] = '1';
    $return = dbRowUpdate('campaigns', $campaigns_data, $where);
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 15; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function getavgdays(){
    $sql = mysql_query("select SUM(nAvgDays) AS tot_avgday,count(*) as total_campaign from campaigns where nDraft = 0");
    $data = mysql_fetch_assoc($sql);
    $tot_day = $data['tot_avgday'] / $data['total_campaign'];
    return $tot_day;
}

function checkcallfiredetails($apiusername, $apipassword, $upload_url) 
{
    $file_url = $upload_url."checkdetails.mp3";
    $wsdl = "https://callfire.com/api/1.0/wsdl/callfire-service-http-soap12.wsdl";

    $client = new SoapClient($wsdl, array(
       'soap_version' => SOAP_1_2,
       'login'        => $apiusername, 
       'password'     => $apipassword
    ));
    
    $soundData = file_get_contents($file_url); 
    $createSoundRequest = array(
        'Name' => 'Test Sound (To varify callfire api details)',
        'Data' => $soundData
        );

    try{
        $soundId = $client->createSound($createSoundRequest);
    } catch (Exception $e){
        $soundId = "0";
    }

    if($soundId == "" || $soundId == "0"){
        return '0';
    } else {
        return '1';
    }
}

function checkclick2maildetails($click2mailusername, $click2mailpassword) {
    $credentials = $click2mailusername.':'.$click2mailpassword;
    $crediturl = 'https://{'.$click2mailusername.'}:{'.$click2mailpassword.'}@rest.click2mail.com/molpro/credit';
    $headers = array (
        "method:POST",          
        "Cache-Control: no-cache",
        "Pragma: no-cache", 
        "Content-Type: application/xml",            
        "Authorization: Basic ".base64_encode($credentials)
    ); 

    $ch1 = curl_init();

    curl_setopt($ch1, CURLOPT_HEADER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch1, CURLOPT_URL, $crediturl);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);

    $res = curl_exec($ch1);
    /*echo '<pre>'; $info = curl_getinfo($ch1);
    echo curl_error($ch1);
    print_r($info); 
    print_r($res);
    die; */
    curl_close($ch1);
    
    if(!$res){
        return '0';
    }

    $aa = explode(' ',$res);
    if($aa[0] != '401') {
        return '1';
    } else {
        return "0";
    }
}

function checksmtpdetails($smtpusername,$smtppassword,$smtphost,$smptpport,$toemail)
{
    #echo $smtpusername.",".$smtppassword.",".$smtphost.",".$smptpport.",".$toemail;die;
    $message = "<table class='body'>
                <tr>
                    <td class='center' align='center' valign='top'>
                        <center><h1>Contact Legend</h1></center>
                    </td>
                </tr>
                <tr>
                    <td class='center' align='center' valign='top'>
                        <center> This email is set to check whether the smtp details are correct or not.</center>
                    </td>
                </tr>
                <tr><td>If you are viewing this email means your smtp detail are correct.</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>Thank You.</td></tr>
                <tr><td>Contact Legend.</td></tr>
                </table>";

    $body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml'>  <head>    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />    <meta name='viewport' content='width=device-width'/>       <style type='text/css'>            /* Ink styles go here in production */          </style>    <style type='text/css'>      /* Your custom styles go here */    </style>  </head>  <body>    <table class='body'>      <tr>        <td class='center' align='center' valign='top'><center>".$message."  <!-- Email Content -->              </center>        </td>      </tr>    </table>  </body></html>";

    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled

    if (strpos($smtphost,'gmail') !== false || strpos($smtphost,'googlemail') !== false || strpos($smtphost,'google') !== false) {
        if($smptpport == '465'){
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        } else {
            $mail->SMTPSecure = 'tls';
        }
    } else {
        if($smptpport == '465'){
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
        }
    }

    #$mail->Host = 'ssl://smtp.gmail.com:465';
    $mail->Host = $smtphost;
    $mail->Port = $smptpport; // or 587
    $mail->Username = $smtpusername;
    $mail->Password = $smtppassword;
    $mail->SetFrom($toemail);

    $mail->IsHTML(true);
    $mail->Subject = 'Test Mail';
    $mail->Body = $body;
    $mail->AddAddress($toemail);
    
    if (!$mail->send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } else {
        return '1';
    }
}
?>