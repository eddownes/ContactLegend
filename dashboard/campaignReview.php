<?php include('header.php');?>
<?php

if(isset($_POST['Email']))
 {
 	$to      = $login_data[0]['sUserEmail'];/*'zaptest12@gmail.com'; $_SESSION['sUserEmail'];*/
 	//echo "$to";
	$subject = $_POST['sCampaignname']; //'trail message';
	$message = $_POST['sReviewContent']; //'Hi, this is the message I want to send.';
	$from = $login_data[0]['sUserEmail']; /*$_POST['sUserEmail'];'zaptest12@gmail.com';*/
   	$sent =mail($to, $subject, $message , "From: $from \n");
 
   	if($sent)
   	{

   		echo "Message sent successfully";
   	}
   	else
   	{
   		echo "Failed to send";
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
                <h4><?php
                	//$p = $login_data[0]['sUserEmail'];
                 //echo $p;/*ucfirst($login_data[0]['sUserEmail']);*/?></h4>

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
