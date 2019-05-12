<?
	include("init.php");
	include("class.EmployeeMaster.php");	
	try{
		$empmaster_id=mysql_real_escape_string($_POST['client_id']);
		$password=mysql_real_escape_string($_POST['password']);
		if($empmaster_id>0 && $password!='')
		{
			$employee = new EmployeeMaster();
			$employee->setPassword($password);
			$employee->updatePassword($empmaster_id);
			$response['success'] = true;
			$response['code'] = 0;
		}
		elseif($empmaster_id=='')
		{
			$response['code'] = 1;
			$response[1] = 'Invalid empmaster_id';
			$response['success'] = false;
			
		}
		else
		{
			$response['code'] = 2;
			$response[2] = 'Password can\'t be blank!';
			$response['success'] = false;
		}
		echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>