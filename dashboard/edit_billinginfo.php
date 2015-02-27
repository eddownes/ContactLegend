<?php 
include("header.php");
if(isset($_POST['submit']) && $_POST['nCreditCardNo'] != ''){

    $bill_data['sName']         =   $_POST['sName'];
    $bill_data['sCompany']      =   $_POST['sCompany'];
    $bill_data['sAddress1']     =   $_POST['sAddress1'];
    $bill_data['sAddress2']     =   $_POST['sAddress2'];
    $bill_data['sCity']         =   $_POST['sCity'];
    $bill_data['sState']        =   $_POST['sState'];
    $bill_data['sZip']          =   $_POST['sZip'];
    $bill_data['sCountry']      =   $_POST['sCountry'];
    $bill_data['eBilltype']     =   $_POST['eBilltype'];
    $bill_data['nCreditCardNo'] =   $_POST['nCreditCardNo'];
    $bill_data['nCreditCardExpYear']    =   $_POST['nCreditCardExpYear'];
    $bill_data['nCreditCardExpMonth']   =   $_POST['nCreditCardExpMonth'];

    $where = "nUserId = '".$_SESSION['nUserID']."'";
    $return_billdata = dbRowUpdate('user_billdetails', $bill_data, $where);

    if($return_billdata == TRUE){
        $_SESSION['success_msg'] = 'Billing information updated successfully';
        header('location:editprofile.php');
        exit;
    } 
    else {
        $_SESSION['error_msg'] = 'Data not updated. Please try again.';
        header('location:editprofile.php');
        exit;
    }
}
$sPorts = $login_data[0]['sPorts'];
?>
<link rel="stylesheet" href="<?php echo $base_url;?>css/colorbox.css" />
<script src="<?php echo $base_url;?>js/jquery.colorbox.js"></script>
<div id="sub-header" class="span12">
    <div class="span12">
        <h2>Account Settings</h2>
    </div>
</div>
<div class="span12" id="content">
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
    <div class="row-fluid">
        <div class="span3">
            <?php include("account_leftbar.php"); ?>
        </div>
        <div class="block span9">
            <div class="navbar navbar-inner block-header"><div class="muted pull-left"><?php echo $login_data[0]['sUserFullName']."'s Profile";?></div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="" method="post" name="frmeditbilling" id="frmeditbilling">
                        <input type="hidden" name="sServerName" value="pro.turbo-smtp.com">
                        <fieldset>
                            <div id="userdirectmaildetails">
                                <div class="control-group">
                                    <h5 style="margin-left:165px; float:left">Billing information</h5>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sName">Billing Name<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sName" name="sName" value="<?php echo $login_data[0]['sName'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sCompany">Billing Company<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCompany" name="sCompany" value="<?php echo $login_data[0]['sCompany'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sAddress1">Billing Address1<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sAddress1" name="sAddress1" value="<?php echo $login_data[0]['sAddress1'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sAddress2">Billing Address2<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sAddress2" name="sAddress2" value="<?php echo $login_data[0]['sAddress2'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sCity">Billing City<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCity" name="sCity" value="<?php echo $login_data[0]['sCity'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sState">Billing State<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sState" name="sState" value="<?php echo $login_data[0]['sState'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sZip">Billing Zip<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sZip" name="sZip" value="<?php echo $login_data[0]['sZip'];?>" >
                                    </div>
                                </div>
                                <?php
                                $countrydata = getAnyData('*','countries','',null,null);
                                $total_country = count($countrydata);
                                ?>
                                <div class="control-group">
                                    <label class="control-label" for="sCountry">Billing Country<em>*</em></label>
                                    <div class="controls">
                                        <select name="sCountry" id="sCountry">
                                            <?php
                                            for($i=0;$i<=$total_country;$i++)
                                            {
                                                $sele="";
                                                if($login_data[0]['sCountry'] == $countrydata[$i]['sCountryCode'])
                                                {
                                                    $sele='Selected';
                                                }
                                                
                                            ?>
                                                <option <?php echo $sele;?> value="<?php echo $countrydata[$i]['sCountryCode'];?>"><?php echo $countrydata[$i]['sCountryName'];?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="eBilltype">Billing Type<em>*</em></label>
                                    <div class="controls">
                                        <!-- <input type="radio" checked="checked" class="span6" id="eBilltype" name="eBilltype" value="Paypal" onclick="Get_billtype('Paypal')"> Paypal 
                                        &nbsp;&nbsp; -->
                                        <input type="radio" <?php if($login_data[0]['eBilltype']=='Credit Card'){?>checked="checked"<?php }?> class="span6" id="eBilltype" name="eBilltype" onclick="Get_billtype('CreditCard')" value="Credit Card"> Credit Card 
                                    </div>
                                </div>
                                <?php
                                $display = '';
                                if($login_data[0]['eBilltype'] == 'Credit Card'){
                                    $display = '';
                                }
                                ?>
                                <div style="display:<?php echo $display;?>;" id="div_ccinfo">
                                    <div class="control-group">
                                        <label class="control-label" for="nCreditCardNo">CreditCard No<em>*</em></label>
                                        <div class="controls">
                                            <input type="text" class="span6" id="nCreditCardNo" name="nCreditCardNo" value="<?php echo $login_data[0]['nCreditCardNo'];?>" >
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="nCreditCardExpYear">CreditCard Exp Year<em>*</em></label>
                                        <div class="controls">
                                            <select name="nCreditCardExpYear" id="nCreditCardExpYear">
                                            <?php
                                            $y = date("Y")-1;
                                            for($i=1;$i<=15;$i++)
                                            {
                                                $year = $y + $i;
                                                $sele="";
                                                if($login_data[0]['nCreditCardExpYear'] == $year)
                                                {
                                                    $sele='Selected';
                                                }
                                                
                                            ?>
                                                <option <?php echo $sele;?> value="<?php echo $year;?>"><?php echo $year;?></option>
                                            <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="nCreditCardExpMonth">CreditCard Exp Month<em>*</em></label>
                                        <div class="controls">
                                            <select name="nCreditCardExpMonth" id="nCreditCardExpMonth">
                                            <?php
                                            for($i=1;$i<=12;$i++)
                                            {
                                                $sele="";
                                                if($login_data[0]['nCreditCardExpMonth'] == $i)
                                                {
                                                    $sele='Selected';
                                                }
                                                $monthName = date("F", mktime(0, 0, 0, $i, 10));
                                            ?>
                                                <option <?php echo $sele;?> value="<?php echo $i;?>"><?php echo $monthName;?></option>
                                            <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save billing information</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">

function Get_billtype(var1){
    if(var1 != 'Paypal'){
        $("#div_ccinfo").show();
    }
    else{
        $("#div_ccinfo").hide();   
    }
}

$(document).ready(function() {

    $(".ajax").colorbox();

    $("#frmeditbilling").validate({
        rules: {
            nCreditCardNo:{
                required: true,
                number: true,
                 minlength: 16
            },
            nCreditCardExpMonth:{
                required: true
            },
            nCreditCardExpYear:{
                required: true
            },
            sName:{
                required: true
            },
            sAddress1:{
                required: true
            },
            sAddress2:{
              required: true  
            },
            sCompany:{
              required: true  
            },
            sCity:{
                required: true
            },
            sState:{
                required: true
            },
            sZip:{
              required: true  
            },
            sCountry:{
              required: true  
            }
        },
        messages: {
            nCreditCardNo: {
                required : "Please Enter Credit Card No",
                number   : "Please enter a Valid Credit Card No",
                minlength: "Your Credit Card No must be at least 16 characters long"
            },
            nCreditCardExpMonth: {
                required : "Please Enter CreditCard Exp Month"
            },
            nCreditCardExpYear: {
                required : "Please Enter CreditCard Exp Year"
            },
            sName: {
                required : "Please Enter Billing Name"
            },
            sAddress1: {
                required : "Please Enter Billing Address1"
            },
            sAddress2: {
                required : "Please Enter Billing Address2"
            },
            sCompany: {
                required : "Please Enter Billing Company"
            },
            sCity: {
                required : "Please Enter Billing City"
            },
            sState: {
                required : "Please Enter Billing State"
            },
            sZip: {
                required : "Please Enter Billing Zip"
            },
            sCountry: {
                required : "Please Enter Billing Country"
            }
        }
    });
});
</script>
<?php include("footer.php");?>
       
