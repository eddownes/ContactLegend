<?php  $crr = explode('/',$_SERVER['REQUEST_URI']);
    $current = $crr['1'];?>
<div class="span3" id="sidebar">
    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
        <!-- <li class="active"><a href="javascript:void(0)"><i class="icon-chevron-right"></i> Dashboard</a></li> -->
        <li <?php if($current == 'myCampaigns.php'){?>class="active" <?php } ?>><a href="myCampaigns.php"><i class="icon-chevron-right"></i> Dashboard</a></li>
        <li <?php if($current == 'createCampaign.php'){?>class="active" <?php } ?>><a href="createCampaign.php"><i class="icon-chevron-right"></i> Create A Campaign</a></li>
    </ul>
</div>
