<?
	include("init.php");
	include("class.EmployeeMaster.php");	
	try{
		$email=mysql_real_escape_string($_GET['signup_email']);
		if(isset($email) && $email!='')
		{
				$response['success'] = true;
				$response['code'] = 0;
				$employee = new EmployeeMaster();
				if($employee->isEmailExists($email))
					echo 'true';
				else
					echo 'false';
				
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Invalid empmaster_id';
				$response['success'] = false;
				
			}
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>