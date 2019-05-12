<?php
 error_reporting(0);
 header("Access-Control-Allow-Origin: *");
session_name("VENDOR");
session_start();
include_once("../config/accessConfig.php");
$pathMain = $_SERVER['DOCUMENT_ROOT'];
include_once($pathMain."/mailer/send_smtp_mail_function.php");

if (file_exists("../includes/connectdb.php")){
     include_once("../includes/connectdb.php");
}else{
    include $_SERVER['DOCUMENT_ROOT'] ."\panels\internalaccess\connectdb.php";
}

 

 if (isset($_REQUEST["email"]) && $_REQUEST["email"]!=""){
     $email = mysql_real_escape_string($_REQUEST['email']);
     
     $ret = checkemail($email);
     $arr = array ($ret);
    echo json_encode($arr);
 }
        
function chkmail($email){		
include_once ($_SERVER['DOCUMENT_ROOT'].'/mailer/send_smtp_mail_function.php');	
$Subject = "VendorPanel Forgot Password";
$fullpath = '';
$contents = file_get_contents(API."panels/vendorpanel/email_forgot_password.html");
$urltoactivate = "<a href='".API."panels/vendorpanel/password_forgot.php?token=".md5($clientid)."'>Click here</a>";
$content = str_replace("[reset]", $urltoactivate, $contents);
 echo  Send_Smtp_mail('','SoftPoint',$email,"SoftPoint",$Subject,$content,$fullpath); 		
}		
		
function checkemail($email){
    $result = mysql_query("SELECT * FROM employees_master WHERE email = '{$email}'");
     if (mysql_num_rows($result)>0){
        $row = mysql_fetch_assoc($result);
        $clientid = $row["empmaster_id"];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: info@softpointcloud.com \r\n";
        $contents = file_get_contents(API."panels/vendorpanel/email_forgot_password.html");
        $urltoactivate = "<a href='".API."panels/vendorpanel/password_forgot.php?token=".md5($clientid)."'>Click here</a>";
        $contents = str_replace("[reset]", $urltoactivate, $contents);
		Send_Smtp_mail('','',$email, '', "vendorpanel Forgot Password",$contents,'');
        //mail($email,"vendorpanel Forgot Password",$contents,$headers);
		
		
		////////
		include_once ($_SERVER['DOCUMENT_ROOT'].'/mailer/send_smtp_mail_function.php');	
		$Subject = "VendorPanel Forgot Password";
		$fullpath = '';
		$contents = file_get_contents(API."panels/vendorpanel/email_forgot_password.html");
		$urltoactivate = "<a href='".API."panels/vendorpanel/password_forgot.php?token=".md5($clientid)."'>Click here</a>";
		$content = str_replace("[reset]", $urltoactivate, $contents);
		// Send_Smtp_mail('','SoftPoint',$email,"SoftPoint",$Subject,$content,$fullpath); 		

		
		
        return 1;
        
     }
     else return 0; 
}	 
  
	
	 
	


?>