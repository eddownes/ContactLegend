<?php 
include("../library/function.php");
$mode = $_REQUEST['mode'];
$nPageId = $_REQUEST['nPageId'];
if(($mode == 'Add') || ($mode == 'Update'))
{
    $data['sPagetitle'] =   $_POST['sPagetitle'];
    $data['sPagename'] =    strtolower(str_replace(' ', '', $_POST['sPagetitle']));
    $data['tPagedesc']  =   $_POST['tPagedesc'];
    $data['bPageStatus']        =   1;
    
    if($mode == 'Add')
    {
        $data['dtCreatedDate']  =   date("Y-m-d H:i:s");
        $return_data = dbRowInsert('page', $data);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'CMS page added  successfully.';
            header('location:'.$admin_url.'pagelist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in adding cms page.';
            header('location:'.$admin_url.'pageadd.php');
            exit;
        }
    }
    elseif($mode == 'Update')
    {
        $data['dtUpdatedDate']  =   date("Y-m-d H:i:s");
        $where = "nPageId = '".$nPageId."'";
        $return_data = dbRowUpdate('page', $data, $where);
        if ($return_data == TRUE) 
        {
            $_SESSION['success_msg'] = 'CMS Page updated  successfully.';
            header('location:'.$admin_url.'pagelist.php');
            exit;
        } 
        else 
        {
            $_SESSION['error_msg'] = 'Error in updating  cms page.';
            header('location:'.$admin_url.'pageadd.php?mode=Update&id='.$nPageId);
            exit;
        }
    }
}
else if($mode == 'Delete')
{
    $where_clause = " nPageId = '".$_REQUEST['id']."'";
    if(dbRowDelete('page', $where_clause))
    {
        $_SESSION['success_msg'] = "CMS PAGE Deleted Successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in Deleting CMS Page";
    }
    header("location:pagelist.php");
    exit;
}
else if($mode == 'Status')
{
    $status = 1;
    if($_GET['status'] == 1){ $status = 0;}
    $where_clause = " nPageId = '".$_REQUEST['id']."'";
    $data_b['bPageStatus'] = $status;
    $result = dbRowUpdate('page',$data_b, $where_clause);
    if ($result == TRUE) 
    {
        $_SESSION['success_msg'] = "Status updated successfully";
    }
    else
    {
        $_SESSION['error_msg'] = "Error in updating status";
    }
    header("location:pagelist.php");
    exit;
}
?>
