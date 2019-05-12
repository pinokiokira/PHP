<?
	include("init.php");
	include("class.state.php");	
	try{
				$country_id=mysql_real_escape_string($_POST['country']);
				$response['success'] = true;
				$state = new state();
				$state->setCountryId($country_id);
				$rows = $state->fetchAll();
				$response=$rows;			
			    echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>