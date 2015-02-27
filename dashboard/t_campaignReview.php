<?php include('header.php');?>
<?php

/*
	$to      = 'test@test.com';
	$subject = 'Subject of the message';
	$message = 'Hi, this is the message I want to send.';
	$headers = 'From: test@test.com' . "\r\n" .
    	       'Reply-To: test@test.com' . "\r\n";
   	mail($to, $subject, $message, $headers);
*/

 if(isset($_POST['Email']))
 {
 	$to      = 'zaptest12@gmail.com';
	$subject = 'trail message';
	$message = 'Hi, this is the message I want to send.';
	$from = 'zaptest12@gmail.com';
   	$sent =mail($to, $subject, $message , "From: $from \n");
   	/*
	$from = $_POST["from"]; // sender
    $subject = $_POST["subject"];
    $message = $_POST["message"];
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    $message = wordwrap($message, 70);
    // send mail
    mail("webmaster@example.com",$subject,$message,"From: $from\n");

   	*/
   	if($sent)
   	{

   		echo "msg send successfully";
   	}
   	else
   	{
   		echo "Doesn't send successfully";
   	}
 }

if(isset($_POST['submit']))
	{
		$title=$_POST['sCampaignname'];
		$content=$_POST['sReviewContent'];
		$date=date('d.m.y h:i:s');
		$q=mysql_query("insert into campaignReview() values('','$title','$content','$date','1')") or die(mysql_error());
		if($q)
			{
				echo "data is added successfully";
			}
		else
			{
				echo "Doesn't work properly";
			}
	}

?>

<div class="span9" id="content">
    <div class="row-fluid">
       
        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">Campaign Review</div>
            </div>
            	<center><h2>Create New Campaign Review</h2></center>
            <div class="block-content collapse in">

                <div class="span12">

                	<form class="" id="CmpgnReview" name="Cmpgn" method="POST" action="">
						<input type="text" placeholder="Campaign Title" name="sCampaignname" id="sCampaignname" /></br>

						<textarea name="sReviewContent" id="sReviewContent" cols="60" rows="10"></textarea></br>
						<input type="submit" id="submit" name="submit" value="Save" />
						<input type="submit" name="Email" value="Email"/>
					</form>
                </div><!-- span12 -->
			</div>
		</div>
	</div>
</div>
