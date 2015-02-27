<?php include("header.php");?>
<link href="<?php echo $admin_url.'css/DT_bootstrap.css';?>" rel="stylesheet" media="screen">
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
                <div style="color:green"><?php echo $_SESSION['error_msg'];?></div>
            </div>
        <?php } ?>
        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left">USER LIST</div>
                <div class="muted pull-right">
                    <button class="btn-primary" name="adduser" type="button" onclick="window.location='useradd.php'">ADD NEW USER</button>
                </div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="userlist">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Business Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $user_data = getAnyData('*', 'users', '', null,null);
                            $tot_rec = count($user_data);
                            for($i=0;$i<$tot_rec;$i++)
                            {
                                $class = 'even gradeX';
                                if(($i%2)==0){$class = 'odd gradeX';}
                            ?>    
                            <tr class="<?php echo $class?>">
                                <td><?php echo $user_data[$i]['sUserFullName'];?></td>
                                <td><?php echo $user_data[$i]['sUserEmail'];?></td>
                                <td><?php echo $user_data[$i]['sUserBusinessName'];?></td>
                                <td class="center">
                                    <a href="<?php echo $admin_url;?>useradd_a.php?mode=Status&id=<?php echo $user_data[$i]['nUserID']?>&status=<?php echo $user_data[$i]['bStatus']?>" >
                                        <?php if($user_data[$i]['bStatus'] == 1){ echo 'Approved';} else { echo 'Disapproved';}?>
                                    </a>
                                </td>
                                <td class="center">
                                    <a href="javascript:void(0)" onclick="delete_user('<?php echo $user_data[$i]['nUserID']?>')" title="Delete">
                                        <img src="<?php echo $admin_url.'images/delete.png';?>">
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a href="<?php echo $admin_url;?>useradd.php?mode=Update&id=<?php echo $user_data[$i]['nUserID']?>" title="Edit">
                                        <img src="<?php echo $admin_url.'images/edit.png';?>">
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a href="<?php echo $admin_url;?>campaignlist.php?id=<?php echo $user_data[$i]['nUserID']?>" title="View Campaign">
                                        <img src="<?php echo $admin_url.'images/view.png';?>">
                                    </a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script src="<?php echo $admin_url;?>vendors/datatables/js/jquery.dataTables.min.js"></script> 
<?php include("footer.php");?>
<script src="<?php echo $admin_url;?>js/DT_bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#userlist').dataTable( {
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        }
    });
});
function delete_user(id)
{
    var ans = confirm("Are you sure, You want to delete this user");
    if(ans == true)
    {
        var url = '<?php echo $admin_url;?>useradd_a.php?mode=Delete&id='+id;
        window.location = url;
    }
    else
    {
        return false;
    }
}
</script>     