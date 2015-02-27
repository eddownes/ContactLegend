<?php 
include("library/function.php");
error_reporting(0);
//pr($_REQUEST);die;
$mode = $_REQUEST['mode'];
$nCityId = $_REQUEST['nCityId'];
if(($mode == 'Add') || ($mode == 'Update'))
{
    $data['vCityName'] =   $_POST['vCityName'];
    $data['nStateId'] =   $_POST['nStateId'];
    $data['bStatus']        =   1;
    
    if($mode == 'Add')
    {
        $return_data = dbRowInsert('city', $data);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'City added  successfully.';
            header('location:'.$admin_url.'citylist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in adding city.';
            header('location:'.$admin_url.'cityadd.php');
            exit;
        }
    }
    elseif($mode == 'Update')
    {

        $where = "nCityId = '".$nCityId."'";
        $return_data = dbRowUpdate('city', $data, $where);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'City updated  successfully.';
            header('location:'.$admin_url.'citylist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in updating city.';
            header('location:'.$admin_url.'cityadd.php?mode=Update&id='.$nCityId);
            exit;
        }
    }
}
else if($mode == 'Delete')
{
    $where_clause = " nCityId = '".$_REQUEST['id']."'";
    if(dbRowDelete('city', $where_clause))
    {
        $_SESSION['success_msg'] = "City Deleted Successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in Deleting city";
    }
    header("location:citylist.php");
    exit;
}
else if($mode == 'Status')
{
    $status = 1;
    if($_GET['status'] == 1){ $status = 0;}
    $where_clause = " nCityId = '".$_REQUEST['id']."'";
    $data['bStatus'] = $status;
    $result = dbRowUpdate('city',$data, $where_clause);
    if ($result == TRUE) 
    {
        $_SESSION['success_msg'] = "City status updated successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in updating city";
    }
    header("location:citylist.php");
    exit;
}
?>
