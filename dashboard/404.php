<?php
include("header.php");
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
                <div class="muted pull-left">Page Not Found</div>
            </div>
            <div class="block-content collapse in">
                <div class="span12">					
					<table width="100%" cellpadding="3" cellspacing="3" border="0">
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td>
							<h3>Page Not Found</h3>
						</td>
					</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');?>