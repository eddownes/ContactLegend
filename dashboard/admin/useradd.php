<?php 
include("header.php");
error_reporting(0);
$mode = $_REQUEST['mode'];
$nUserId = $_REQUEST['id'];
if($mode == ''){
    $mode = 'Add';
    $title = 'ADD NEW USER';
}
else{
    $where = "u.nUserId = '".$nUserId."'";
    $title = "UPDATE USER";
    $table = " users as u Left Join user_smtpdetails as sd on sd.nUserId = u.nUserId
		Left Join user_billdetails as bd on bd.nUserId = u.nUserId";
    $user_data = getAnyData('u.*,sd.*,bd.*',$table,$where,null,null);
    $sPorts = $user_data[0]['sPorts'];
}
?>
<div class="span9" id="content">
    <div class="row-fluid">
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
        <div class="block">
            <div class="navbar navbar-inner block-header"><div class="muted pull-left"><?php echo $title;?></div></div>
            <div class="block-content collapse in">
                <div class="span12">
                    <form class="form-horizontal" action="useradd_a.php" method="post" name="frmuseradd" id="frmuseradd" enctype="multipart/form-data">
                        <input type="hidden" name="mode" value="<?php echo $mode?>">
                        <input type="hidden" name="nUserId" value="<?php echo $nUserId?>">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="sUserFullName">Full Name<em>*</em></label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sUserFullName" name="sUserFullName" value="<?php echo $user_data[0]['sUserFullName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserEmail">Email<em>*</em></label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sUserEmail" name="sUserEmail" value="<?php echo $user_data[0]['sUserEmail'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserPassword">Password<em>*</em></label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sUserPassword" name="sUserPassword" value="<?php echo $user_data[0]['sUserPassword'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sUserBusinessName">Business Name<em></em></label>
                                <div class="controls">
                                    <input type="text" class="span6" id="sUserBusinessName" name="sUserBusinessName" value="<?php echo $user_data[0]['sUserBusinessName'];?>" >
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nFollowupMail">Turbo SMTP</label>
                                <div class="controls">
                                    <input type="checkbox" <?php if($user_data[0]['nFollowupMail'] == '1'){?> checked="checked" <?php } ?> class="span6" id="nFollowupMail" name="nFollowupMail" value="<?php echo $user_data[0]['nFollowupMail'];?>" >
                                </div>
                            </div>
                            <div id="userserverdetails" <?php if($user_data[0]['nFollowupMail'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                <div class="control-group"><h5 style="margin-left:150px;">E-mail SMTP Settings</h5></div>
                                <div class="control-group">
                                    <label class="control-label" for="sServerName">Server Name<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sServerName" name="sServerName" value="<?php echo $user_data[0]['sServerName']?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sUsername">Server Username<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sUsername" name="sUsername" value="<?php echo $user_data[0]['sUsername'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sPassword">Server Password<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sPassword" name="sPassword" value="<?php echo $user_data[0]['sPassword'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sPorts">Ports<em>*</em></label>
                                    <div class="controls">
                                        <select name="sPorts" id="sPorts">
                                            <option>--Select Port--</option>
                                            <option value="25" <?php if($sPorts == '25'){?> selected = 'Selected' <?php }?>>25</option>
                                            <option value="465" <?php if($sPorts == '465'){?> selected = 'Selected' <?php }?>>465</option>                                            
                                            <option value="587" <?php if($sPorts == '587'){?> selected = 'Selected' <?php }?>>587</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nCallDetails">Call Fire</label>
                                <div class="controls">
                                    <input type="checkbox" class="span6" <?php if($user_data[0]['nCallDetails'] == '1'){?> checked="checked" <?php } ?> id="nCallDetails" name="nCallDetails" value="<?php echo $user_data[0]['nCallDetails'];?>" >
                                </div>
                            </div>
                            <div id="usercalldetails" <?php if($user_data[0]['nCallDetails'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                <div class="control-group"><h5 style="margin-left:165px;">Voice Broadcast Settings</h5></div>
                                <div class="control-group">
                                    <label class="control-label" for="sCallUsername">Username<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCallUsername" name="sCallUsername" value="<?php echo $user_data[0]['sCallUsername'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sCallPassword">Password<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCallPassword" name="sCallPassword" value="<?php echo $user_data[0]['sCallPassword'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="nCallPhoneNo">Phone<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="nCallPhoneNo" name="nCallPhoneNo" value="<?php echo $user_data[0]['nCallPhoneNo'];?>" >
                                        <br/><b style="color:red;">(Enter user's callfire verified phone no)</b>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="nDirectMails">Click2Mail</label>
                                <div class="controls">
                                    <input type="checkbox" class="span6" <?php if($user_data[0]['nDirectMails'] == '1'){?> checked="checked" <?php } ?> id="nDirectMails" name="nDirectMails" value="<?php echo $user_data[0]['nDirectMails'];?>" >
                                </div>
                            </div>
                            <div id="userdirectmaildetails" <?php if($user_data[0]['nDirectMails'] == '1'){?> style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
                                <div class="control-group"><h5 style="margin-left:165px;">Direct Mail Settings</h5></div>
                                <div class="control-group">
                                    <label class="control-label" for="sDirectmailUsername">Username<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sDirectmailUsername" name="sDirectmailUsername" value="<?php echo $user_data[0]['sDirectmailUsername'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sDirectmailPassword">Password<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sDirectmailPassword" name="sDirectmailPassword" value="<?php echo $user_data[0]['sDirectmailPassword'];?>" >
                                    </div>
                                </div>
                                <?php /*?>
                                <div class="control-group">
                                    <label class="control-label" for="sName">Billing Name<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sName" name="sName" value="<?php echo $user_data[0]['sName'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sCompany">Billing Company<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCompany" name="sCompany" value="<?php echo $user_data[0]['sCompany'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sAddress1">Billing Address1<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sAddress1" name="sAddress1" value="<?php echo $user_data[0]['sAddress1'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sAddress2">Billing Address2<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sAddress2" name="sAddress2" value="<?php echo $user_data[0]['sAddress2'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sCity">Billing City<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sCity" name="sCity" value="<?php echo $user_data[0]['sCity'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sState">Billing State<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sState" name="sState" value="<?php echo $user_data[0]['sState'];?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sZip">Billing Zip<em>*</em></label>
                                    <div class="controls">
                                        <input type="text" class="span6" id="sZip" name="sZip" value="<?php echo $user_data[0]['sZip'];?>" >
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
                                                if($user_data[0]['sCountry'] == $countrydata[$i]['sCountryCode'])
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
                                        <input type="radio" checked="checked" class="span6" id="eBilltype" name="eBilltype" value="Paypal" onclick="Get_billtype('Paypal')"> Paypal 
                                        &nbsp;&nbsp;
                                        <input type="radio" <?php if($user_data[0]['eBilltype']=='Credit Card'){?>checked="checked"<?php }?> class="span6" id="eBilltype" name="eBilltype" onclick="Get_billtype('CreditCard')" value="Credit Card"> Credit Card 
                                    </div>
                                </div>
                                <?php
                                $display = 'none';
                                if($login_data[0]['eBilltype'] == 'Credit Card'){
                                    $display = '';
                                }
                                ?>
                                <div style="display:<?php echo $display;?>;" id="div_ccinfo">
                                    <div class="control-group">
                                        <label class="control-label" for="nCreditCardNo">CreditCard No<em>*</em></label>
                                        <div class="controls">
                                            <input type="text" class="span6" id="nCreditCardNo" name="nCreditCardNo" value="<?php echo $user_data[0]['nCreditCardNo'];?>" >
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
                                                if($user_data[0]['nCreditCardExpYear'] == $year)
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
                                                if($user_data[0]['nCreditCardExpMonth'] == $i)
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
                                <?php */?>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-primary" onclick="window.history.back()">Back</button>
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
<link rel="stylesheet" type="text/css" href="<?php echo $admin_url?>css/jquery.fancybox.css" media="screen" />
<script type="text/javascript" src="<?php echo $admin_url?>js/jquery.fancybox.js"></script>
<link href="<?php echo $admin_url;?>vendors/datepicker.css" rel="stylesheet" media="screen">
<script src="<?php echo $admin_url;?>vendors/bootstrap-datepicker.js"></script>
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
    var base_url = '<?php echo $base_url?>';

    $('#nFollowupMail').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#userserverdetails").toggle(this.checked);
    });

    $('#nCallDetails').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#usercalldetails").toggle(this.checked);
    });
    
    $('#nDirectMails').click(function(){
        if(this.checked == true){ $(this).attr('value','1');}
        $("#userdirectmaildetails").toggle(this.checked);
    });    
    
    var val_sCallUsername = $("#sCallUsername").val();
        var val_sUsername = $("#sUsername").val();
    $("#frmuseradd").validate({
        /*$.validator.addMethod("checkServerAvailability",function(value,element){
            var val_sUsername = $("#sUsername").val();
            var val_sPassword = $("#sPassword").val();
            var val_sPorts    = $("#sPorts").val();
            $.ajax({
                url: "checkserveravailibility.php",
                type: 'POST',
                async: false,
                data: {sUsername:val_sUsername, sPassword:val_sPassword, sPorts:val_sPorts},
                success: function(msg) {
                    if(msg == 1){                    
                        return false;
                    }
                    else{
                        
                        return true;
                    }
                }
            });
        },"Sorry, this server details are wrong");


        $.validator.addMethod("checkCallAvailability",function(value,element){
            var val_sUsername = $("#sCallUsername").val();
            var val_sPassword = $("#sCallPassword").val();
            
            $.ajax({
                url: "checkcallavailibility.php",
                type: 'POST',
                async: false,
                data: {sUsername:val_sUsername, sPassword:val_sPassword},
                success: function(msg) {
                    if(msg == 1){                    
                        return false;
                    }
                    else{
                        
                        return true;
                    }
                }
            });
        },"Sorry, this server details are wrong");*/
        
        rules: {
            sUserFullName: {
                required: true,
            },
            sUserEmail: {
                required: true,
                email: true,
                remote: "checkEmail.php"
            },
            sUserPassword: {
                required: true,
                minlength: 6
            },
            sServerName:{
                required: true,
            },
            sUsername:{
                required: true,
            },
            sPassword:{
                required: true,
                //checkServerAvailability: false
                //remote: "checkserveravailibility.php?u="+val_sUsername
            },
            sPorts:{
                required: true,
                number: true                
            },
            sCallUsername:{
                required: true,
            },
            sCallPassword:{
                required: true,
                //checkCallAvailability: true
                //remote: "checkcallavailibility.php?u="+val_sCallUsername
            },
            nCallPhoneNo:{
                required: true,
                number: true
            },
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
            sDirectmailUsername:{
              required: true  
            },
            sDirectmailPassword:{
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
            sUserFullName: "Please Enter Your Name",
            sUserEmail: {
                required:"Please enter Email Address",
                email : "Please enter a Valid Email Address",
                remote : "Email Already Exists!!",
            },
            sUserPassword: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
            },
            sServerName: "Please Enter the Server Name",
            sUsername: "Please Enter the Server Username",
            sPassword: { 
                required :  "Please Enter the Server Password",
                //remote   : "Please enter correct server uesrname and password details"
            },
            sPorts: {
                required : "Please Enter the Server Port",
                number   : "Please enter a Valid  Server Port"
            },
            sCallUsername: "Please Enter call username",
            sCallPassword:{
                required : "Please Enter call password",
                //remote   : "Please enter correct call uesrname and password details"
            }, 
            nCallPhoneNo: {
                required : "Please Enter call phone",
                number   : "Please enter a Valid call phone"
            },
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
            sDirectmailUsername: {
                required : "Please Enter Direct Mail Username"
            },
            sDirectmailPassword: {
                required : "Please Enter Direct Mail Password"
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
    $(".datepicker").datepicker();
    $('.fancybox').fancybox();
});
</script>
<?php include("footer.php");?>
