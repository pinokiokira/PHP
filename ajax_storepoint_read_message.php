<?php 
include_once 'includes/session.php';
include_once 'config/accessConfig.php';
if ($_POST["msgid"]!=""){
    switch ($_POST["action"]){
        case "read":
            $sql = "UPDATE employee_master_location_storepoint SET `read`='yes', read_date = date(now()), read_time = time(now()) WHERE id='{$_POST["msgid"]}'";
            break;
        
        case "unread":
           	$sql = "UPDATE employee_master_location_storepoint SET `read`='no' WHERE id='{$_POST["msgid"]}'";
            break;
        
        case "delete":
            $sql = "DELETE FROM employee_master_location_storepoint WHERE id='{$_POST["msgid"]}'";
            break;
    }
     $result = mysql_query($sql);
	// echo $sql;
    if ($result){
        print '1';
    }
    else print '0';
 }?>