<?php
if(isset($_POST['submit'])){
	print_r($_FILES);die;
}
?>
<form enctype='multipart/form-data' method="POST" action="">
	<input type="file" name="tinyfile" id="tinyfile" />&nbsp;&nbsp;
	<input type="submit" name="submit"  />&nbsp;&nbsp;	
</form>
