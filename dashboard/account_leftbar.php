<?php  $crr = explode('/',$_SERVER['REQUEST_URI']); $current = $crr['2']; ?>
<div id="sidebar">
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <li <?php if($current == 'editprofile.php'){?>class="active" <?php } ?>><a href="editprofile.php"> Profile information</a></li>
        <li <?php if($current == 'edit_followup_setting.php'){?>class="active" <?php } ?>><a href="edit_followup_setting.php"> Followup integrations</a></li>
        <?php /* <li <?php if($current == 'edit_billinginfo.php'){?>class="active" <?php } ?>><a href="edit_billinginfo.php"> Billing information</a></li> */ ?>
        <li <?php if($current == 'changepassword.php'){?>class="active" <?php } ?>><a href="changepassword.php"> Change password</a></li>
    </ul>
</div>
