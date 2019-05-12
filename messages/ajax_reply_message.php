<?php 
require_once '../require/security.php';
include_once '../config/accessConfig.php';
if (mysql_real_escape_string($_POST["msgid"])!=""){
    $messagebody = mysql_real_escape_string(mysql_real_escape_string($_POST["message"]));
      $sql = "SELECT * FROM employee_messages WHERE id='{$_POST["msgid"]}'";
            
     $result = mysql_query($sql);
     $row = mysql_fetch_assoc($result);
     
     $sqlemp = "SELECT id FROM employees WHERE email = '{$_SESSION["email"]}' AND location_id = '{$row["location_id"]}'";
     $resultemp = mysql_query($sqlemp);
     $rowemp = mysql_fetch_assoc($resultemp);
     $employeeid = $rowemp["id"];
     $newsql = "INSERT INTO employee_messages (location_id,entered_by_emp_id,Subject,message,date,time,emp_id,readd,Message_type,priority,thread_id) 
         VALUES ('{$row["location_id"]}','{$employeeid}','".mysql_real_escape_string($row["Subject"])."','{$messagebody}',DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW(),'%h:%i:%s'),'{$row["entered_by_emp_id"]}','no','Location','{$row["priority"]}',{$row["thread_id"]})";
    // die($newsql);
     $result2 = mysql_query($newsql);
    if ($result2){
        print '1';
    }
    else print '0';
 }?>