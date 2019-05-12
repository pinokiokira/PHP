<?
	include("init.php");
	include("class.currency.php");	
	try{
				$response['success'] = true;
				$currency = new currency();
				$rows = $currency->fetchAll();
				$response['currency']=$rows;			
			    echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>