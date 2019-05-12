<?
	include("init.php");
	include("class.EmployeeMaster.php");
	include("class.login.php");
	$pathMain = $_SERVER['DOCUMENT_ROOT'];
	include_once($pathMain."/mailer/send_smtp_mail_function.php");
	$login = new login();
	error_reporting(E_ALL);
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	try{
		if($username!='' && $password!='')
		{
			if($login->verify($username, $password))
			{
				$employee = new EmployeeMaster();
				$employee->find($login->empmaster_id);
				if($employee->status=='A')
				{
					$response['success'] = true;
					$response['code'] = 0;
					$response['response'] = array(
						'client_id' => $employee->empmaster_id,
						'first_name' => $employee->first_name,
						'last_name' => $employee->last_name,
						'name' => $employee->first_name.' '.$employee->last_name,
						'email' =>  $employee->email,
                                                'image' =>  $employee->image,
						'StorePoint' =>  $employee->StorePoint,
						'ChefedIN' =>  $employee->ChefedIN,
						'StylistFN' =>  $employee->StylistFN,
						'DeliveryPoint' =>  $employee->DeliveryPoint,
                                                'StorePointVendorID' =>  $employee->StorePoint_vendor_Id,
                                                'ChefedIN_Business_Name' => $employee->ChefedIN_Business_Name
					);
				}
				else
				{
                                        if($employee->status=='N'){
                                            $subject = "VendorPanel Registration";
                                            $headers = 'MIME-Version: 1.0' . "\r\n";
                                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                                            $headers .= "From: info@softpointcloud.com \r\n";
                                            $fp = @fopen("../../../Emails/storepoint-empreg.html", "r");
                                            $contents = fread($fp, filesize("../../../Emails/storepoint-empreg.html"));
                                            fclose($fp);
                                            $contents = str_replace("[emp.first_name]", $employee->first_name, $contents);
                                            $contents = str_replace("[emp.last_name]", $employee->last_name, $contents);
                                            $contents = str_replace("[emp.email]", $employee->email, $contents);
                                            $contents = str_replace("[emp.password]", $password, $contents);
                                            $contents = str_replace("[token]", md5($employee->empmaster_id), $contents);
                                            $contents = str_replace("[API]", API, $contents);
                                            $message = $contents . " \r\n";
											Send_Smtp_mail('','',$employee->email, '', $subject, $message,'');
                                            mail($employee->email, $subject, $message, $headers);
                                            $response['code'] = 6;
                                            $response[5] = 'User is not active';
                                        }else{
                                           $response['code'] = 4;
                                            $response[4] = 'User is not active'; 
                                        }
					
				}
			}
			else
			{
				$response['code'] = 3;
				$response[3] = 'Invalid Email or Password';
			}
		}
		elseif($password=='')
		{
			$response['code'] = 2;
			$response[2] = 'Please enter password!';
		}
		elseif($username=='')
		{
			$response['code'] = 1;
			$response[1] = 'Please enter email!';
		}
		echo json_encode($response);
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>