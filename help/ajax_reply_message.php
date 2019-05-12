<?php 
include_once '../require/security.php';
include_once '../config/accessConfig.php';
if (mysql_real_escape_string($_POST["msgid"])!=""){
    
    $messagebody = mysql_real_escape_string($_POST["message"]);
     $sql = "SELECT * FROM help WHERE help_id='".mysql_real_escape_string($_POST["msgid"])."'";
            
     $result = mysql_query($sql);
     $row = mysql_fetch_assoc($result);
     
     $newsql = "INSERT INTO help (from_type,sent_datetime,status,from_employee_master,topic,to_type,to_admin,to_employee,to_corp,to_client,to_location,message,Ticket) 
         VALUES ('Team','".date("Y-m-d H:i")."','unread','{$_SESSION['client_id']}','".mysql_real_escape_string($row["topic"])."','".mysql_real_escape_string($row["from_type"])."','".mysql_real_escape_string($row["from_admin"])."',NULL,NULL,NULL,NULL,'".$messagebody."','".mysql_real_escape_string($row["Ticket"])."')";
     $result2 = mysql_query($newsql) or die(mysql_error());
     
    if ($result2){
        print '1';
    }
    else print '0';
 }?>