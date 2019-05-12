<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

if($_POST['short_name'] != ''){
    $shortname = mysql_real_escape_string($_POST['short_name']);
    $description = mysql_real_escape_string($_POST['description']);
    $priority = mysql_real_escape_string($_POST['priority']);
    $located = mysql_real_escape_string($_POST['located']);
    $access = mysql_real_escape_string($_POST['access']);
    $line = mysql_real_escape_string($_POST['line']);

    if($_POST['id'] != ''){
        $query = "UPDATE location_inventory_storerooms SET
                        location_id = " . $_SESSION['loc'] . ",
                        stroom_id='$shortname',
                        description='$description',
                        priority='$priority',
                        line='$line',
                        located='$located',
                        access='$access'
                        WHERE storeroom_id = " . mysql_real_escape_string($_POST['id']);
        $result = mysql_query($query) or die(mysql_error());
		$msg="edit";		
    }else{
        $query = "INSERT INTO location_inventory_storerooms SET
                        location_id = " . $_SESSION['loc'] . ",
                        stroom_id='$shortname',
                        description='$description',
                        priority='$priority',
                        line='$line',
                        located='$located',
                        access='$access'";
        $result = mysql_query($query) or die(mysql_error());
		$msg="insert";
    }
	//echo $query;
	
	
		//this is for setup process
if (isset($_POST['step']) && $_POST['step']=="5")
{	
	header("Location: setup_process.php?step=6");  
}
else
{
    header("Location: setup_backoffice_storerooms.php?msg=".$msg);
}


}




/*

$terminal_name = isset($_REQUEST['terminal_name']) ? mysql_real_escape_string($_REQUEST['terminal_name']) : '';
$path = isset($_REQUEST['path']) ? mysql_real_escape_string($_REQUEST['path']) : '';
$cashier_bank = isset($_REQUEST['cashier_bank']) ? mysql_real_escape_string($_REQUEST['cashier_bank']) : '';

if (isset($_REQUEST['terminal_id']) && $_REQUEST['terminal_id'] > 0) { // location_id='$location_id',
    $sql = "UPDATE location_terminals set terminal_name='$terminal_name', path='$path', cashier_bank='$cashier_bank' WHERE id=" . mysql_real_escape_string($_REQUEST['terminal_id']);
    $result = mysql_query($sql) or die(mysql_error());
} else {
    $sql = "INSERT INTO location_terminals (location_id, terminal_name, path, cashier_bank) values ('" . $_SESSION['loc'] . "', '$terminal_name', '$path', '$cashier_bank')";
    $result = mysql_query($sql) or die(mysql_error());
}
header('Location: terminals.php');*/


?>