<?php
include('library/function.php');

$sch_where = "sdm.nCampaignid = '".$_REQUEST['cid']."' AND bStatus = 1";
$tables = "scheduledirectmails as sdm
		  Left Join campaigncustomers as cc on cc.nCampaignCustId = sdm.nCampaignCustId 
		  Left Join campaigns as c on c.nCampaignid = sdm.nCampaignid ";
$groupby = 'Group by sdm.nScheduleDirectMailD';	
$field = "cc.*,c.sCampaignname,sdm.sJobStatus";
$customerdata = getAnyData($field,$tables, $sch_where, null, null);
?>
<style>
.cboxContent{max-height: 240px !important;}
#content{min-height: 0 !important;}
</style>
<div class="span9" id="content">
    <div class="main_header"> Campaign recipients </div>
    <div class="sub_header"> <h2><?php echo $customerdata[0]['sCampaignname'];?></h2> </div>
    <div class="table_wrapper"> 
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr class="first_tr">
		<td width="18%" align="left">First Name </td>
		<td width="18%" align="left">Last Name </td>
		<td width="44%" align="left">Email </td>
		<td width="20%" align="left">Status </td>
	</tr>
        <?php 
        $tot_customer = count($customerdata);
        $name = '';
        for($i=0;$i<$tot_customer;$i++)
        {
            $name = $customerdata[$i]['sCustomerFirstname'].' '.$customerdata[$i]['sCustomerLastname'];
            if($name == ''){ $name = '--';}
                
        ?>
        <tr>
            <td><?php echo $customerdata[$i]['sCustomerFirstname'];?> </td>
            <td><?php echo $customerdata[$i]['sCustomerLastname'];?> </td>
            <td class="value"><?php echo $customerdata[$i]['sCustomerEmail'];?> </td>
            <td class="value"><?php echo $customerdata[$i]['sJobStatus'];?> </td>
        </tr>
        <?php
        }
        ?>
      
        </table>
        
    </div>
    
    <div class="popup_footer">
            
            <a href="javascript:void(0);" onclick="$(window).colorbox.close();" class="make_close">close</a>
            
    </div>
    
</div>
