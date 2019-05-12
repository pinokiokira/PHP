<?php
ob_start("ob_gzhandler");
require_once 'require/security.php';
include 'config/accessConfig.php'; 
header('Content-Type: application/json');

$response = array();
//  $response = array('code' => 0);

if($_GET['id'] != ''){

	$loc_id = $_GET['id'];
	$now=date("Y-m-d H:i:s");
	
	/*$queryemp = "select StorePoint_vendor_Id from employees_master where empmaster_id = '".$_SESSION['client_id']."'";
	$resemp = mysql_query($queryemp);
	$resultemp = mysql_fetch_array($resemp);*/
	
	$queryven = "select name from vendors where id = '".$_SESSION['StorePointVendorID']."'";
	$resven = mysql_query($queryven);
	$resultven = mysql_fetch_array($resven);

  $queryloc = "INSERT INTO employee_master_location_storepoint set 
	sent_by_type ='Employee Master',
	emp_master_id='".$_SESSION['client_id']."', 
	location_id='".$loc_id."',
	sent_datetime='".$now."',
	subject='New Vendor',
	message='".mysql_real_escape_string($resultven["name"])." has added you as a customer',
	`read`='No',
	reply = 'No'";
	//die();
     mysql_query($queryloc);
	
	$query = "SELECT l.id,l.name,l.city,l.phone from locations as l JOIN employee_master_location_storepoint as eml ON eml.location_id = l.id JOIN employees_master as em ON em.empmaster_id = eml.emp_master_id where em.StorePoint_vendor_Id = '".$_SESSION['StorePointVendorID']."'
					UNION
					SELECT l.id,l.name,l.city,l.phone from locations as l JOIN purchases as p On p.location_id = l.id WHERE p.vendor_id = '".$_SESSION['StorePointVendorID']."'
					order by name asc"; //group by id
					
		$result = mysql_query($query) or die(mysql_error());

    while($row = mysql_fetch_assoc($result)){
        if (!empty($row['name']))
            $response[] = $row;
    }
}
echo json_encode($response);