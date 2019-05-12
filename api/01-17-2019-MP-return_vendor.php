<?php
	include("init.php");
        include_once '../../../internalaccess/url.php';
	
        
	try{
		$vendor_id=mysql_real_escape_string($_POST['vendor_id']);
		if(isset($vendor_id) && $vendor_id>0)
		{
				
				$sql = "SELECT * FROM vendors WHERE id=".$vendor_id;
				/*$sql = "SELECT v.*, vt.id as vtid, vt.terms  FROM vendors v
						Left join vendor_terms vt on v.id = vt.vendor_id
						WHERE v.id='$vendor_id'";*/
                $result = mysql_query($sql);
                $countedrows = mysql_num_rows($result);
                if (mysql_num_rows($result)>0){						
                        $row = mysql_fetch_assoc($result);
                        $response['success'] = true;
				        $response['code'] = 0;
				        $response['id'] = $row["id"];
				        $response['status'] =  $row["status"];
				        $response['name'] =  $row["name"];
						$response['StorePoint_image'] =  $row["StorePoint_image"];
				        $response['contact'] =  $row["contact"];
				        $response['title'] =  $row["title"];
				        $response['email'] =  $row["email"];
				        $response['address'] =  $row["address"];
				        $response['address2'] =  $row["address2"];
				        $response['country'] =  $row["country"];
				        $response['city'] =  $row["city"];
				        $response['state'] =  $row["state"];
				        $response['zip'] =  $row["zip"];
				        $response['phone'] =  $row["phone"];
				        $response['fax'] =  $row["fax"];
				        $response['website'] =  $row["website"];
                        $response['description'] =  $row["description"];
				        $response['type'] =  $row["type"];
						
						$response['terms'] =  $row["terms_types"];
						$response['payment_types'] =  $row["payment_types"];
						$response['delivery_types'] =  $row["delivery_types"];
                        $response['currency'] =  $row["currency_id"];
				        $response['created_by'] = $row["created_by"];
				        $response['created_on'] = $row["created_on"];
				        $response['created_date'] = $row["created_date"];
				        $response['last_by'] = $row["last_by"];
				        $response['last_on'] = $row["last_on"];
				        $response['last_datetime'] = $row["last_datetime"];
        }
			else
			{
				$response['code'] = 1;
				$response[1] = 'Invalid vendor_id';
				$response['success'] = false;
				
			}	
	}
	else
	{
		$response['code'] = 2;
		$response[1] = 'Invalid vendor_id';
		$response['success'] = false;
				
	}
			echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>