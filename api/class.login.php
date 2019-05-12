<?
	class login extends EmployeeMaster{
		
				
		public function verify($email, $password)
		{
			try{
				
				$sql="select * from employees_master where (email='$email' OR empmaster_id='$email') and binary password='$password'";
				$res=mysql_query($sql) or die('mysql error');
				
				if(mysql_num_rows($res)==0)
				{
					return false;
				}
				else
				{
					$row=mysql_fetch_array($res);
					$this->empmaster_id = $row['empmaster_id'];
					return true;
					
				}
			}
			catch(Exception $e)
			{
				
				error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
			}
		}
		
		
	}
?>