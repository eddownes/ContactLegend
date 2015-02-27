<?php
include('library/function.php');
$where = 'c.nCampaignid = "'.$_REQUEST['cid'].'" AND sc.bCallStatus = 1 AND sc.bStatus = 1';
$tables = "campaigns as c 
			Left Join schedulecalls as sc on sc.nCampaignid = c.nCampaignid
			Left Join campaigncustomers as cc on cc.nCampaignCustId = sc.nCampaignCustId";
$field = "cc.*,c.sCampaignname,sc.sCallState,sc.sCallFinalResult,sc.sCallStartTime,sc.sCallEndTime";
#echo "Select $field from $tables WHERE $where";
$customerdata = getAnyData($field,$tables, $where, null, null);
#pr($customerdata);die;
?>
<style>

.cboxContent{max-height: 240px !important;}
#content{min-height: 0 !important;}
</style>

<div class="span10" id="content">
    
    <div class="main_header"> Campaign recipients </div>
    <div class="sub_header"> <h2><?php echo $customerdata[0]['sCampaignname'];?></h2> </div>
    <div class="table_wrapper">
 
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            
            <tr class="first_tr">
                    
                    <td width="10%" align="left">Name </td>
                   
                    <td align="left">Email </td>
                    
                    <td width="16%" align="left">Phone </td>
                    
                    <td width="12%" align="left">Call Status </td>
                   
                    <td width="12%" align="left">Call Result </td>
                    
                    <td width="20%" align="left">Call Start Time </td>
                   
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
            ?>
            <tr>
                   
                    <td><?php echo $name;?> </td>
                    
                    <td><?php echo $customerdata[$i]['sCustomerEmail'];?> </td>
                    
                    <td><?php echo $customerdata[$i]['sCustomerPhone'];?> </td>
                    
                    <td class="value"><?php echo $customerdata[$i]['sCallState'];?> </td>
                    
                    <td class="value">
                        <?php 
                            if($customerdata[$i]['sCallFinalResult'] == "LA"){
                                echo "Live Answer";
                            } else if($customerdata[$i]['sCallFinalResult'] == "AM") {
                                echo "Voicemail";
                            } else {
                                echo $customerdata[$i]['sCallFinalResult'];
                            }
                        ?> 
                    </td>
                    
                    <td class="value"><?php echo $customerdata[$i]['sCallStartTime'];?> </td>
                    
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