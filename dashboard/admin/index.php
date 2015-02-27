<?php include("header.php");?>
<div class="span9" id="content">
    <div class="row-fluid">
        <?php if(isset($_SESSION['success_msg'])){?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div style="color:green"><?php echo $_SESSION['success_msg'];?></div>
            </div>
        <?php } ?>
        <?php if(isset($_SESSION['error_msg'])){?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div style="color:green"><?php echo $_SESSION['error_msg_msg'];?></div>
            </div>
        <?php } ?>

        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">Recently Added Users</div>
            </div>
            <div class="block-content collapse in">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                    <tr>
                        <th>Full Name </th>
                        <th>Email Id </th>
                        <th>Business Name </th>
                        <th>Signup Date </th>
                    </tr>
                    <?php 
                    $where = "bStatus = '1'";
                    $limit = '0 , 5';
                    $orderby = 'order by nUserID desc';
                    $user_data = getAnyData('*', 'users', $where, $limit,$orderby);
                    $tot_rec = count($user_data);
                    if($user_data>0){
                        for($i=0;$i<$tot_rec;$i++){
                    ?>
                            <tr>
                                <td><?php echo $user_data[$i]['sUserFullName'];?></td>
                                <td><?php echo $user_data[$i]['sUserEmail'];?></td>
                                <td><?php echo $user_data[$i]['sUserBusinessName'];?></td>
                                <td><?php echo $user_data[$i]['dCreatedDate'];?></td>
                            </tr>                            
                    <?php 
                        }
                    }
                    ?>
                </table>
                <a class="btn btn-primary" href="userlist.php">See All Users</a>
            </div>
        </div>

        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">Most Active Users</div>
            </div>
            <div class="block-content collapse in">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                    <tr>
                        <th>Full Name </th>
                        <th>Total Campaign </th>
                    </tr>
                    <?php 
                    $where = "c.nUserID != ''";
                    $table = 'campaigns as c LEFT JOIN users as u on u.nUserID = c.nUserID';
                    $orderby = 'GROUP BY c.nUserID ORDER BY totalcampaign DESC';
                    $limit = '0 , 6';
                    $activeuserdata = getAnyData('u.sUserFullName,count( c.nCampaignid ) AS totalcampaign', $table, $where, $limit,$orderby);
                    $total_activeuser = count($activeuserdata);
                    if($total_activeuser>0){
                        for($i=0;$i<$total_activeuser;$i++){
                    ?>
                        <tr>
                            <td><?php echo $activeuserdata[$i]['sUserFullName'];?></td>
                            <td><?php echo $activeuserdata[$i]['totalcampaign'];?></td>
                        </tr>                            
                    <?php 
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">Activity</div>
            </div>
            <?php
            $sql_qry = "select count(nUserID) as todaysignup , (select count(nUserID) from users where dCreatedDate BETWEEN NOW() - INTERVAL 7 DAY AND NOW()) as weeksignup from users where dCreatedDate >= CURDATE()";
            $result_user = mysql_fetch_assoc(mysql_query($sql_qry));

            $sql_qry = "select sum(nEmailsSent) as totalemailsend , sum(nEmailsOpened) as totalemailopen from campaigns where dCreatedDate >= CURDATE()";
            $result_campaign = mysql_fetch_assoc(mysql_query($sql_qry));
            
            $totalemailopen = 0;
            $totalemailsend = 0;
            
            if($result_campaign['totalemailopen'] != ''){
                $totalemailopen = $result_campaign['totalemailopen'];
            }

            if($result_campaign['totalemailsend'] != ''){
                $totalemailsend = $result_campaign['totalemailsend'];
            }

            ?>
            <div class="block-content collapse in">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                    <tr>
                        <th width="75%">New Users SignUp Today </th>
                        <td align="center"><?php echo $result_user['todaysignup'];?></td>
                    </tr>
                    <tr>
                        <th>New Users SignUp This Week </th>
                        <td><?php echo $result_user['weeksignup'];?></td>
                    </tr>
                    <tr>
                        <th>Emails Sent Today</th>
                        <td><?php echo $result_campaign['totalemailsend'];?></td>
                    </tr>
                    <tr>
                        <th>Total Opened Emails</th>
                        <td><?php echo $result_campaign['totalemailopen'];?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">New User Signup Details</div>
            </div>
            <div class="block-content collapse in">
                <iframe src="adminDrawGraph.php" scrolling="none" width="98%" height="500px"></iframe>
            </div>
        </div>


    </div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php include("footer.php");?>