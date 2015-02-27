<?php 
include("header.php");
$mode = $_REQUEST['mode'];
$nPageId = $_REQUEST['id'];
if($mode == ''){
    $mode = 'Add';
}
else
{
    $where = "nPageId = '".$nPageId."'";
    $page_data = getAnyData('*','page',$where,null,null);
}
?>
<script src="<?php echo $base_url?>library/ckeditor/ckeditor.js"></script>
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
            <div class="navbar navbar-inner block-header"><div class="muted pull-left"><?php echo strtoupper($mode);?> CMS PAGE</div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="pageadd_a.php" method="post" name="frmspage" id="frmspage" style="width:95%;" >
                        <input type="hidden" name="mode" value="<?php echo $mode?>">
                        <input type="hidden" name="nPageId" value="<?php echo $nPageId?>">
                        <div class="control-group">
                            <label class="control-label" for="sPagetitle">Title</label>
                            <div class="controls">
                                <input type="text" class="span6" id="sPagetitle" name="sPagetitle" value="<?php echo $page_data[0]['sPagetitle'];?>" >
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="tPagedesc">Description</label>
                            <div class="controls">
                                <textarea name="tPagedesc" id="tPagedesc" cols="40" rows="10" placeholder="Write Your Message Here *"><?php echo $page_data[0]['tPagedesc'];?></textarea>
                            </div>
                        </div>
                        <div class="form-actions" style="float:right;">
                            <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                            <button type="reset" class="btn" onclick="window.location.href='pagelist.php'">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    //CKEDITOR.replace( 'tPagedesc',{fullPage : true},{extraPlugins : 'placeholder'});
    CKEDITOR.replace( 'tPagedesc');

    $("#frmspage").validate({
        rules: {
            sPagetitle: {
                required: true,
            },
        },
        messages: {
            sPagetitle: "Please enter a page title",
        }
    });
});
</script>
<?php include("footer.php");?>