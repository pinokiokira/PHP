<?
	include("init.php");
	include("class.EmployeeMaster.php");
	
	$empmaster_id=mysql_real_escape_string($_POST['client_id']);
	$image=mysql_real_escape_string($_POST['image']);
	
	try{
		if($empmaster_id>0 && $image!='')
		{			
			$employee = new EmployeeMaster();
			$employee->find($empmaster_id);
			$employee->setImage($image);
						
			if($employee->save($empmaster_id))
			{
				$response['success'] = true;
				$response['code'] = 0;
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Error in data save!';				
			}		
		}
		elseif($empmaster_id=='')
		{
			$response['code'] = 2;
			$response[2] = 'Invalid empmaster_id!';
		}
		else
		{
			$response['code'] = 3;
			$response[3] = 'Please choose image file!';
		}
		echo json_encode($response);
	}catch(Exception $e)
	{
		
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage(), 3,"error/error_log.log");
	}
	
?>