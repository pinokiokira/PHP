<?php 
require_once '../require/security.php';
include_once '../config/accessConfig.php';
if ($_POST["msgid"]!=""){
    switch ($_POST["action"]){
        case "read":
            $sql = "UPDATE employee_messages SET readd='yes', seen_date = date(now()), seen_time = time(now()) WHERE id='".mysql_real_escape_string($_POST["msgid"])."'";
            break;
        
        case "unread":
            $sql = "UPDATE employee_messages SET readd='no' WHERE id='".mysql_real_escape_string($_POST["msgid"])."'";
            break;
        
        case "delete":
            $sql = "DELETE FROM employee_messages WHERE id='".mysql_real_escape_string($_POST["msgid"])."'";
            break;
    }
     $result = mysql_query($sql);
    if ($result){
        print '1';
    }
    else print '0';
 }?>