<?php 
include('header.php');
?>
<style type="text/css">
    .campaign_container{padding: 40px 0; text-align: center;}
    .btn_dash{margin-top: 20px;}
    .btn_dash button{ background: #868685; border: none; border-radius: 2px; color: #FFF; height: 30px; width: 145px;  }
    .campaign_action{ color:#CACAC9;font-size: 14px;}
    .campagin_sent{margin-top: 20px;}
</style>
<div id="content" class="span12">
    <div class="row-fluid">
        <?php if(isset($_SESSION['notice_msg'])){?>
            <div class="alert alert-notice">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div><?php echo $_SESSION['notice_msg'];?></div>
            </div>
        <?php } ?>
        <div class="c_main_container">
            <div class="centeral_top campaign_container">
                <div><img src="<?php echo $base_url?>images/green_circle.png"></div>
                <div class="campagin_sent" ><p>Campaign Sent !</p></div>
                <div class="campaign_action">Woohoo! Your Campaign is on its way! </div>
                <div class="btn_dash"><button onclick="window.location.href='userdashboard.php'">Go to Dashboard</button></div>
            </div>
        </div>
    </div>
</div>
<?php 
include('footer.php');
unset($_SESSION['notice_msg']);
?>
