<?php 
include_once '../require/security.php';
include_once '../config/accessConfig.php';
if ($_POST["msgid"]!=""){
    switch ($_POST["action"]){
        case "read":
            $sql = "UPDATE help SET status='read', read_by_type = 'Team', read_by_employee_master = {$_SESSION['client_id']}, read_datetime = '".date("Y-m-d H:i")."' WHERE help_id='".mysql_real_escape_string($_POST["msgid"])."'";
            break;
        
        case "unread":
            $sql = "UPDATE help SET status='unread' WHERE help_id='".mysql_real_escape_string($_POST["msgid"])."'";
            break;
        
        case "delete":
            $sql = "DELETE FROM help WHERE help_id='(mysql_real_escape_string{$_POST["msgid"]})'";
            break;
    }
    //echo $sql;
     $result = mysql_query($sql);
    if ($result){
        print '1';
    }
    else print '0';
 }?>