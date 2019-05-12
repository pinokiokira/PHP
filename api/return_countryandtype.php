<?
	include("init.php");
	include("class.country.php");	
	try{
				$response['success'] = true;
				$country = new country();
				$rows = $country->fetchAll();
				$response['countries']=$rows;			
			    echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>