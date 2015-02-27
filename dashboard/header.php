<?php 
include('library/function.php');
$current_time = strtotime(date("Y-m-d H:i:s"));
if(!isset($_SESSION['nUserID'])){
    header('location: login.php');
    exit;
}else{
    $where = "u.nUserID = '".$_SESSION['nUserID']."' AND bStatus = 1";
    $table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId
    Left Join user_billdetails as bd on bd.nUserId = u.nUserId";
    $login_data = getAnyData('*',$table,$where,null,null);
}

$class = $cls = '';
if($page == 'Account'){
    $class = 'Selected';
}
else if($page == 'FirstTime'){
    $cls = 'Selected';
}

$header_title = 'Create a new campaign ';
if((isset($_REQUEST['f'])) && (base64_decode($_REQUEST['f']) == 'No')){
    $header_title = 'Edit campaign ';
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<title>Welcome to ContactLegend.com</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="<?php echo $base_url;?>css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $base_url;?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="<?php echo $base_url;?>css/styles.css" rel="stylesheet" media="screen">
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="<?php echo $base_url;?>js/jquery.validate.js"></script>
</head>
<body>
    <div class="row-fluid">
        <div class="navbar navbar-fixed-top span12">
            <div class="header navbar-inner">
                <div class="container container-fluid">
                    <a class="span8" href="#" onclick="window.location.href='userdashboard.php'"><img src="images/logo.png" title="ContactLegend" alt="ContactLegend"></a>
                    <div class=" span4 nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="caret"></i> <!--<i class="icon-user"></i>--><p class="name"><?php echo ucfirst($login_data[0]['sUserFullName']);?></p></a>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" class="<?php echo $class;?>" href="editprofile.php">Account</a></li>
                                    <li><a tabindex="-1" class="<?php echo $cls;?>" href="emptydashboard.php">Help</a></li>
                                    <li><a tabindex="-1" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php  
        $crr = explode('/',$_SERVER['REQUEST_URI']); 
        $curr1 = explode('?',$crr['2']); 
        $current = $curr1[0]; 
    ?>
	<div id="sub-header" class="span12">
        <div class="span12">
            <div class="span9">
                <h2>
                    <?php if($current == 'userdashboard.php' || $current == 'myCampaigns.php' || $current == 'emptydashboard.php'){ ?>
                        Campaign Dashboard
                    <?php } else if($current == 'editprofile.php' || $current == 'edit_followup_setting.php' || $current == 'changepassword.php') { ?>
                        Account Settings
                    <?php } else { ?>
                        <?php echo $header_title;?>
                    <?php } ?>
                </h2>
            </div>
            <div class="span3">
                <?php if($current == 'userdashboard.php' || $current == 'myCampaigns.php' || $current == 'emptydashboard.php'){ ?>
                
                    <button class="btn btn-large btn-primary" onclick="window.location.href='createCampaign.php'" type="button">Create a new campaign</button>
                
                    
                <?php }else if(($current == 'createCampaign.php' || $current == 'intromessage.php' || $current == 'followupaction.php' || $current == "sendandpreview.php") && ($curr1[1] != '')) { ?>
                    <a id="del_campaigndata" class="del_followupactio" href="javascript:void(0)">Delete this campaign </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="container container-fluid">
        <div class="row-fluid">
    <script type="text/javascript">
    $(document).ready(function(){
        var id = "<?php echo $_GET['id'];?>";
        $("#del_campaigndata").click(function(){
            $("#del_campaign").fadeIn("slow");
        });

        $("#del_topreject, #delete_reject").click(function(){
            $("#del_campaign").fadeOut("slow");

        });

        $("#delete_confirm").click(function(){
            $("#del_campaign").fadeOut("slow");
            window.location.href = 'del_campaign.php?id='+id;
            return true;
        });
    });
    </script>

    <div class="fix_delete_popup" id="del_campaign">
    <div class="fix_delete_popup_wrapper">
        <div class="fix_del_header">
            Delete this campaign <a href="javascript:void(0);" class="Del_top_close" id="del_topreject"  type="button">1</a>
        </div>                        
        <div class="fix_del_content">
            <p>Delete this campaign? This cannot be undone and all the data for this campaign will be deleted. </p>
        </div>
        <div class="fix_del_footer">
            <a href="javascript:void(0)" id="delete_confirm" class="red_del_link"> Yes, delete this campaign</a> 
            <a href="javascript:void(0)" id="delete_reject" class="cancel_gray"> Cancel </a> 
        </div>
    </div>
</div>
