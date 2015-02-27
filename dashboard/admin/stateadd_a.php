<?php 
include("library/function.php");
error_reporting(0);
//pr($_REQUEST);die;
$mode = $_REQUEST['mode'];
$nStateId = $_REQUEST['nStateId'];
if(($mode == 'Add') || ($mode == 'Update'))
{
    $data['vStateName'] =   $_POST['vStateName'];
    $data['bStatus']        =   1;
    
    if($mode == 'Add')
    {
        $return_data = dbRowInsert('state', $data);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'State added  successfully.';
            header('location:'.$admin_url.'statelist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in adding state.';
            header('location:'.$admin_url.'stateadd.php');
            exit;
        }
    }
    elseif($mode == 'Update')
    {

        $where = "nStateId = '".$nStateId."'";
        $return_data = dbRowUpdate('state', $data, $where);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'State updated  successfully.';
            header('location:'.$admin_url.'statelist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in updating state.';
            header('location:'.$admin_url.'stateadd.php?mode=Update&id='.$nStateId);
            exit;
        }
    }
}
else if($mode == 'Delete')
{
    $where_clause = " nStateId = '".$_REQUEST['id']."'";
    if(dbRowDelete('state', $where_clause))
    {
        $_SESSION['success_msg'] = "State Deleted Successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in Deleting user";
    }
    header("location:statelist.php");
    exit;
}
else if($mode == 'Status')
{
    $status = 1;
    if($_GET['status'] == 1){ $status = 0;}
    $where_clause = " nStateId = '".$_REQUEST['id']."'";
    $data['bStatus'] = $status;
    $result = dbRowUpdate('state',$data, $where_clause);
    if ($result == TRUE) 
    {
        $_SESSION['success_msg'] = "State status updated successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in updating status";
    }
    header("location:statelist.php");
    exit;
}
?>
