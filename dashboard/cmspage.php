<?php
include("header.php");
$nPageId = base64_decode($_REQUEST['id']);
$where = "bPageStatus = '1' and nPageId = '".$nPageId."'";
$cmspage_data = getAnyData('*','page',$where,null,null);
if(($nPageId != '') && ($cmspage_data[0]['sPagetitle'] == '')){
    header("location:404.php");
    exit;
}
?>
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
                <div class="muted pull-left"><?php echo $cmspage_data[0]['sPagetitle'];?></div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">					
					<table width="100%" cellpadding="3" cellspacing="3" border="0">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td>
							<?php echo $cmspage_data[0]['tPagedesc'];?>	
						</td>
					</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');?>