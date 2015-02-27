<?php include('library/function.php');
$nCampaignid = $_POST['id'];
$where = 'nCampaignId = "'.$_POST['id'].'"';
$campaignname = getAnyData('sCampaignname', 'campaigns', $where, null, null);?>
<div class="span12" id="content">
    <div class="row-fluid">
        <div class="block">
            <div class="navbar navbar-inner block-header">
                <div class="muted pull-left"><?php echo $campaignname[0]['sCampaignname'].'&nbsp;'. 'summary'?></div>
            </div>
            <div class="block-content collapse in">
              <div class="span12">
                <div class="left">
                    <div id="data">
                        <table id="userlist" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" >
                            <tbody>
                            <?php 
                            //$query=mysql_query("SELECT * FROM campaigns WHERE nCampaignid=11");

                            $where ='nCampaignid="'.$nCampaignid.'"';
                            $campaignData = getAnyData('*', 'campaigns', $where, null,null);
                            $totCampaign = count($campaignData);
                            //pr($campaignData);
                            if(!empty($totCampaign)){?>
                            <?php for($i=0;$i<$totCampaign;$i++){?>
                            <tr>
                                <td><strong>Sent : </strong><?php echo $campaignData[$i]['nEmailsSent']?></td>
                                <td><strong>Opened : </strong><?php echo $campaignData[$i]['nEmailsOpened']?></td>
                            </tr>
                            <tr>
                                <td>Calls Made</td>
                                <td>&nbsp;&nbsp;<?php echo $campaignData[$i]['nCallsMade']; ?></td>
                            </tr>
                            <tr>
                                <td>Mail Sent</td>
                                <td>&nbsp;&nbsp;<?php echo $campaignData[$i]['nMailSent']; ?></td>
                            </tr>
                            <tr>
                                <td>Email Follow Ups</td>
                                <td>&nbsp;&nbsp;<?php echo $campaignData[$i]['nEmailFollowUps']; ?></td>
                            </tr>
                            <?php } 
                            }else{
                                echo '<td>'.'No records to Display.'.'</td>';}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="right">
                    <iframe src="drawGraphs.php?nCampaignid=<?php echo $nCampaignid;?>" scrolling="none" width="475px" height="500px"></iframe>
                </div>
              </div>
		    </div>
	   </div>
	</div>
</div>
