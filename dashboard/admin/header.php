<?php
include('../library/function.php');

if((!isset($_SESSION['cl_nAdminUserId'])) && ($_SESSION['cl_nAdminUserId'] == ''))
{
    header("location:login.php");
    exit;
}
else
{
    $where = "nAdminUserId = '".$_SESSION['cl_nAdminUserId']."' AND bStatus = 1";
    $admin_data = getAnyData('*','adminusers',$where,null,null);
    $crr = explode('/',$_SERVER['REQUEST_URI']);
    if($crr['3'] !=''){
        $cur =  explode('?',$crr['3']);
        $current = $cur[0];
    }
}
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>Contact Legend</title>
        <link href="<?php echo $admin_url;?>css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo $admin_url;?>css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo $admin_url;?>css/styles.css" rel="stylesheet" media="screen">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="<?php echo $admin_url;?>vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="<?php echo $admin_url;?>js/jquery.js"></script>
        <script src="<?php echo $admin_url;?>js/jquery.validate.js"></script>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Contact Legend Admin Panel</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                <a href="javascript:void(0)" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> <?php echo ucfirst($admin_data[0]['sFirstName']).' '.ucfirst($admin_data[0]['sLastName']);?> <i class="caret"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="<?php echo $admin_url.'editprofile.php';?>">Profile</a></li>
                                    <li class="divider"></li>
                                    <li><a tabindex="-1" href="<?php echo $admin_url.'changepwd.php';?>">Change Password</a></li>
                                    <li class="divider"></li>
                                    <li><a tabindex="-1" href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        </ul>                        
                    </div>
                    <!--/.nav-collapse -->
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
            <?php include("leftbar.php");?>
