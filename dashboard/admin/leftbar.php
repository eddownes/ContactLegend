<div class="span3" id="sidebar">
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li <?php if(($current == '')|| ($current == 'index.php')){?>class="active"<?php } ?>><a href="<?php echo $admin_url;?>"><i class="icon-chevron-right"></i> Dashboard</a></li>
        <li <?php if(($current == 'useradd.php')|| ($current == 'userlist.php')){?>class="active"<?php } ?>><a href="<?php echo $admin_url.'userlist.php';?>"><i class="icon-chevron-right"></i> User</a></li>
        <li <?php if(($current == 'pageadd.php')|| ($current == 'pagelist.php')){?>class="active"<?php } ?>><a href="<?php echo $admin_url.'pagelist.php';?>"><i class="icon-chevron-right"></i> CMS Page</a></li>
    </ul>
</div>