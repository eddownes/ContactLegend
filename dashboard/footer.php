<?php
$where = "bPageStatus = '1'";
$cms_data = getAnyData('*','page',$where,null,null);
$tot_page = count($cms_data);
?>
</div>
 <hr>
	</div>
	<footer>
		<div class="footer-left"><p>&copy; Contact Legend 2014</p></div>
		<div class="footer-right">
			<?php
			for($i=0;$i<$tot_page;$i++)
			{
			?>
				<a href="cmspage.php?id=<?php echo base64_encode($cms_data[$i]['nPageId'])?>"><?php echo $cms_data[$i]['sPagetitle'];?></a>
			<?php
			}
			?>
		</div>
	</footer>
	<script src="<?php echo $admin_url;?>js/bootstrap.min.js"></script>
	<script src="<?php echo $admin_url;?>js/scripts.js"></script>
    </body>
</html>
<?php
unset($_SESSION['error_msg']);
unset($_SESSION['success_msg']);
unset($_SESSION['success_msg1']);
unset($_SESSION['success_msg2']);
unset($_SESSION['success_msg3']);
?>
