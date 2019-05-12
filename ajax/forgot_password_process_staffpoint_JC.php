<?php
 
 header("Access-Control-Allow-Origin: *");
session_name("TEAM");
session_start();
include_once("../config/accessConfig.php");

 include_once("../includes/connectdb.php");
 error_reporting(-1);


 if (isset($_REQUEST["email"]) && $_REQUEST['email']!=""){
     $email = mysql_real_escape_string($_REQUEST['email']);
     $site = mysql_real_escape_string($_REQUEST['site']);
     $type = mysql_real_escape_string($_REQUEST['type']);
     
    $ret = checkemail($email,$site,$type);
     $arr = array ($ret);


    echo json_encode($arr);
 }
        
function checkemail($email, $site, $type){

//locations
  if($type == "0"){

    
    $result = mysql_query("SELECT * FROM locations WHERE email = '{$email}'");
     if (mysql_num_rows($result)>0){
        $row = mysql_fetch_assoc($result);
        $clientid = $row["id"];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: info@softpointcloud.com \r\n";
        $contents = file_get_contents(API."panels/teampanel/email_forgot_password.html");
        $urltoactivate = "<a href='".API."panels/teampanel/password_forgot.php?token=".md5($clientid)."'>Click here</a>";
        $contents = str_replace("[reset]", $urltoactivate, $contents);
        mail($email,"TeamPanel Forgot Password",$contents,$headers);
        return 1;
        
     }
     else return 0; 
  }


//employee_master
  if($type == "1"){
        $result = mysql_query("SELECT * FROM employees_master WHERE email = '{$email}'");
     if (mysql_num_rows($result)>0){
        $row = mysql_fetch_assoc($result);
        $clientid = $row["empmaster_id"];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: info@softpointcloud.com \r\n";
        $contents = file_get_contents(API."panels/teampanel/email_forgot_password.html");
        $urltoactivate = "<a href='".API."panels/teampanel/password_forgot.php?token=".md5($clientid)."'>Click here</a>";
        $contents = str_replace("[reset]", $urltoactivate, $contents);
        mail($email,"TeamPanel Forgot Password",$contents,$headers);
        return 1;
        
     }
     else return 0; 
  }

}	 
  
	
	 
	


?>