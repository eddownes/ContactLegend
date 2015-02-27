<?php
include('library/function.php');
$where = 'c.nCampaignid = "'.$_REQUEST['cid'].'" AND cc.eEmailOpened = 1';
$tables = "campaigns as c Left Join campaigncustomers as cc on cc.nCampaignid = c.nCampaignid";
$field = "cc.*,c.sCampaignname";
$customerdata = getAnyData($field,$tables, $where, null, null);
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
		
		<td width="20%" align="left">Phone </td>
		
	</tr>
        
        
        <?php 
        $tot_customer = count($customerdata);
        $name = '';
        for($i=0;$i<$tot_customer;$i++)
        {
                $name = $customerdata[$i]['sCustomerFirstname'].' '.$customerdata[$i]['sCustomerLastname'];
                if($name == ''){
                        $name = '--';
                }
                $phone = '--';
                if($customerdata[$i]['sCustomerPhone'] != ''){
                        $phone = $customerdata[$i]['sCustomerPhone'];
                }
        ?>
        <tr>
                
                <td><?php echo $customerdata[$i]['sCustomerFirstname'];?> </td>
               
                <td><?php echo $customerdata[$i]['sCustomerLastname'];?> </td>
                
                <td class="value"><?php echo $customerdata[$i]['sCustomerEmail'];?> </td>
                
                <td class="value"><?php echo $phone;?> </td>
               
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
