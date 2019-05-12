<?php
/*
*  @created Ionut Irofte - juniorionut @ elance
*  @version $Id: Signin.php ,v 1.0 10:38 PM 7/4/2014 juni $
*  -> [req 1.33  - 04.07.2014]
	-> New file which combines code to resemble the "behaviour" of  all existing panels (unexisting) 
	-> Do inserts and checks on login
*/

$pathMain = $_SERVER['DOCUMENT_ROOT'];
include_once($pathMain."/mailer/send_smtp_mail_function.php");
include_once("config/accessConfig.php");
session_name("VENDOR");
session_start();
if($_POST['username'] != ''){
	//call api
	$ch = curl_init();
	$url = API .'panels/vendorpanel/api/login_process.php';

	if (isset($_POST['rememberme']) && $_POST['rememberme']=="rememberme") {
		/* Set cookie to last 1 year */
		setcookie('usernameTP', $_POST['username'], time()+60*60*24*365);
	} else{
		unset($_COOKIE['usernameTP']);
		setcookie('usernameTP', '', time() - 3600);       
	}
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($_POST));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $_POST);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result = json_decode(curl_exec($ch),true);
	curl_close($ch);
	
	//print_r($result);
	
	if($result['success'] == 1){
	//if($result['status'] == 'success'){
		
		$_SESSION['client_id'] = $result['response']['client_id'];
		$_SESSION['empmaster_id'] = $result['response']['client_id'];
		$_SESSION['client_email'] = $result['response']['email'];
		$_SESSION['email'] = $result['response']['email'];
		$_SESSION['first_name'] = $result['response']['first_name'];
		$_SESSION['last_name'] = $result['response']['last_name'];
		$_SESSION['name'] = $result['response']['name'];
		$_SESSION['image'] = $result['response']['image'];
		$_SESSION['StylistFN_location_id'] = $result['response']['StylistFN_location_id'];
		$_SESSION['accessStorePoint'] = ($result['response']['StorePoint']=='')?"No":$result['response']['StorePoint'];
		$_SESSION['accessChefedIN'] = ($result['response']['ChefedIN']=='')?"No":$result['response']['ChefedIN'];
		$_SESSION['accessStylistFN'] =($result['response']['StylistFN']=='')?"No":$result['response']['StylistFN'];
		$_SESSION['DeliveryPoint'] =($result['response']['DeliveryPoint']=='')?"No":$result['response']['DeliveryPoint'];
		if(isset($result['response']['StorePointVendorID']) && $result['response']['StorePointVendorID']!="" && $result['response']['StorePointVendorID']!=0){
			$_SESSION['StorePointVendorID'] =$result['response']['StorePointVendorID'];
		}
		$_SESSION['ChefedIN_Business_Name'] =$result['response']['ChefedIN_Business_Name'];
		$_SESSION["latitude"]="";
		if (isset($result['response']["latitude"])){
			$_SESSION["latitude"]= $result['response']["latitude"];
		}
		$_SESSION["longitude"]="";
		if (isset($result['response']["longitude"])){
			$_SESSION["longitude"]=$result['response']["longitude"];
		}
		//
		$_SESSION["password"] = $_REQUEST['password'];
		//last time user changed password
		//12.07.2014 -> do not search for current password, any password
		$sql = "SELECT  DATE_FORMAT(last_datetime, '%m/%d/%Y') as last_datetime
					FROM employees_master_audit
		WHERE   password != ''  AND empmaster_id = ".$_SESSION['empmaster_id']."  AND last_datetime BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() + INTERVAL 1 DAY";				
		//WHERE   password = '".$_REQUEST['password']."'  AND empmaster_id = ".$_SESSION['empmaster_id']."  AND last_datetime BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() + INTERVAL 1 DAY";				
		//WHERE  password <> ''  AND empmaster_id = ".$_SESSION['client_id']."  AND last_datetime BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE()";					
		
		//echo $sql;
		$sql_qu  = mysql_query($sql)  or die(mysql_error());
		$num_of_days = mysql_num_rows($sql_qu);	
		//echo $num_of_days 
		//last time user logged in
		$status = $result['response']['status'];
		$created_on = date("Y-m-d H:i:s");
		//$sql= mysql_query("select * from employee_master_ping where empmaster_id = ".$_SESSION['client_id']." order by ping_id desc limit 1")  or die(mysql_error());
		$sql= "select * from employee_master_ping where empmaster_id = ".$_SESSION['empmaster_id']." order by ping_id desc limit 1";
		$sql_qu  = mysql_query($sql)  or die(mysql_error());
		$num_of_logs = mysql_num_rows($sql_qu);
		$res = mysql_fetch_array($sql_qu);
		$userid = $res['Datetime'];
		$date1 = date("Y-m-d", strtotime($userid));
		$date2 = date("Y-m-d", strtotime($created_on));
		$diff = abs(strtotime($date2) - strtotime($date1));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		//$days = (strtotime($date2) - strtotime($date1))  / (60 * 60 * 24);
		//		
		$status_failue = 0;
		$sql_sel= mysql_query("select * from employee_master_ping where empmaster_id = {$_SESSION['empmaster_id']}  order by ping_id desc limit 5")  or die(mysql_error());
		while($val = mysql_fetch_array($sql_sel)){
			if($val['Ping_type'] == "Signinfailure")	{				
				$status_failue = $status_failue + 1;
			}
		}
		if($num_of_logs != 0 && $days > 60)	{		
			echo json_encode(array(
            	'success' => true,
            	'error_code' => $result['code'],
			 	'day' => "1"	
			 	//'password_change' => "Y"
        	));	
		} elseif ($status == "I"){
			echo json_encode(array(
            	'success' => true,
            	'error_code' => $result['code'],
			 	'password_change' => "Y",
				'alertt' => "Y"
        	));
		} elseif($status_failue == 5)	{
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: info@softpointcloud.com \r\n";
			mysql_query("update employees_master set status = 'I' , last_on = 'vendorpanel' ,last_by = 'Unknown',last_datetime = '".$created_on."' where empmaster_id ={$_SESSION['empmaster_id']}")  or die(mysql_error());
			//juni -> 21.07.2014 -> do not hardcode
			//$contents = file_get_contents(API."panels/vendorpanel/email_account.html");
			//$contents = file_get_contents(API."panels/vendorpanel/email_account.html");
                        $contents = file_get_contents(API."panels/vendorpanel/email_account_reset.html");
                        $urltoactivate = "<a href='".API."panels/vendorpanel/password_reset.php?token=".md5($_SESSION['empmaster_id'])."'>Click here</a>";
                         $contents = str_replace("[reset]", $urltoactivate, $contents);
						 $contents = str_replace("[API]", API, $contents);
			Send_Smtp_mail('','',$_SESSION['client_email'], '', "vendorpanel Account Locked",$contents,'');
			//mail($_SESSION['client_email'],"vendorpanel Account Locked",$contents,$headers);
			echo json_encode(array(
				'success' => true,
				'error_code' => $result['code'],
				'failue' => "Y",
			));
		} elseif($num_of_days == 0){
			//15.07.2014 -> check if the audit has records first!
			$created_rows = 0;
			$sql = "SELECT  password,DATE_FORMAT(last_datetime, '%m/%d/%Y') as last_datetime
					FROM employees_master_audit
			WHERE   password != '' AND empmaster_id = ".$_SESSION['empmaster_id'] ." LIMIT 1";		
			$sql_qu  = mysql_query($sql) or die(mysql_error());
			$total_rows = mysql_num_rows($sql_qu);	
			if ($total_rows == 0) {
				//07.11.2014 -> if there are no records in client_audit, check creation date of client so that i don't force the new client to change password
				$sql= "select * from employees_master
					WHERE empmaster_id = ".$_SESSION['empmaster_id']." 
				AND created_datetime IS NOT NULL 
					AND created_datetime BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() + INTERVAL 1 DAY
				ORDER BY empmaster_id DESC LIMIT 1";
				$sql_qu  = mysql_query($sql) or die(mysql_error());
				$created_rows = mysql_num_rows($sql_qu);
			}					
			$sql = 
				"insert into employee_master_ping set 
					empmaster_id =".$_SESSION['empmaster_id']." ,
					ip = '".$_SERVER['REMOTE_HOST']."' ,
					Ping_type = 'Signin' ,
					datetime = '".date("Y-m-d H:i:s")."',
					longitude = '',
					latitude = '',
					created_on ='vendorpanel' ,
					created_by =".$_SESSION['empmaster_id'].",
					created_datetime = '".date("Y-m-d H:i:s")."'
					
			";
			$res = mysql_query($sql) or die(mysql_error());			
			if ($created_rows == 0)//user is older then 60 days
				echo json_encode(array( 
					'success' => true,
					'password_change' => "Y",
                                        'allsession'=>$_SESSION
				));	
			else
				echo json_encode(array(
					'success' => true,
                                        'allsession'=>$_SESSION
				));			
		}	else {
				$sql = 
				"insert into employee_master_ping set 
					empmaster_id =".$_SESSION['empmaster_id']." ,
					ip = '".$_SERVER['REMOTE_HOST']."' ,
					Ping_type = 'Signin' ,
					datetime = '".date("Y-m-d H:i:s")."',
					longitude = '',
					latitude = '',
					created_on ='vendorpanel' ,
					created_by =".$_SESSION['empmaster_id'].",
					created_datetime = '".date("Y-m-d H:i:s")."'
					
			";
			$res = mysql_query($sql) or die(mysql_error());					
			echo json_encode(array(
				'success' => true,
                                'allsession'=>$_SESSION
				// 'teamalert' => $_SESSION['empmaster_id']
			));
		}
		// header('Location: dashboard.php');
	} else {
		$created_on = date("Y-m-d H:i:s");
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql= mysql_query("select empmaster_id as id,email from employees_master where email = '".$username."' AND password = '".$password."' limit 1 ") or die(mysql_error());
		$res = mysql_fetch_array($sql);
		$id = $res['id'];
		$num_of_logs = mysql_num_rows($sql);
		if ($num_of_logs > 0 ) {	 // juni - 12.07.2014 -> null id on wrong email
			$email=$res["email"];
			$status_failue = 0;
			$sql_sel= mysql_query("select * from employee_master_ping where empmaster_id = ".$id."  order by ping_id desc limit 5") or die(mysql_error());
			while($val = mysql_fetch_array($sql_sel)){
				if($val['Ping_type'] == "Signinfailure")	{				
					$status_failue = $status_failue + 1;
				}
				echo $val['Ping_type'];
			}
			//have more then 5 sigin failures, sending mail
			if($status_failue == 5)	{
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: info@softpointcloud.com \r\n";
				mysql_query("update employees_master set status = 'I' where empmaster_id =".$id) or die(mysql_error());
				//juni -> 21.07.2014 -> do not hardcode
				//$contents = file_get_contents(API."panels/vendorpanel/email_account.html");
				//$contents = file_get_contents(API."panels/vendorpanel/email_account.html");
                                $contents = file_get_contents(API."panels/vendorpanel/email_account_reset.html");
                                $urltoactivate = "<a href='".API."panels/vendorpanel/password_reset.php?token=".md5($id)."'>Click here</a>";
                                $contents = str_replace("[reset]", $urltoactivate, $contents);
								$contents = str_replace("[API]", API, $contents);
				Send_Smtp_mail('','',$email, '', "vendorpanel Account Locked",$contents,'');
				//mail($email,"vendorpanel Account Locked",$contents,$headers);
				echo json_encode(array(
					'success' => false,
					'error_code' => $result['code'],
					'failue_st' => "Y"
				));
			} else	{
				$sql = 
					"insert into employee_master_ping set 
						empmaster_id =".$id." ,
						ip = '".$_SERVER['REMOTE_HOST']."' ,
						Ping_type = 'Signinfailure' ,
						datetime = '".date("Y-m-d H:i:s")."',
						longitude = '',
						latitude = '',
						created_on ='vendorpanel' ,
						created_by =".$id.",
						created_datetime = '".date("Y-m-d H:i:s")."'
						
				";
				$res = mysql_query($sql) or die(mysql_error());		      
				echo json_encode(array(
					'success' => false,
					'error_code' => $result['code']
				));
			}
		} else {
			echo json_encode(array(
					'success' => false,
					'error_code' => $result['code']
				));
		}
	}
} 	else{
	header('Location: index.php');
}
?>