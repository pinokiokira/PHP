<?php

$databasereadjson = 0;
require_once 'require/security.php';
include 'config/accessConfig.php';


$response = array();

if($_GET['group'] != ''){
	$vendor_id = trim($_REQUEST['vendor_id']);
    $group = mysql_real_escape_string($_GET['group']);
	
	$is_edit = trim($_REQUEST['is_edit']);
	
	if($is_edit == 'y'){
		$query = "SELECT id as id,description,unit_type,manufacturer_barcode as barcode
              FROM inventory_items
              WHERE inv_group_id='". $group ."'
				ORDER BY description ASC
		";
	} else {
		/* $query = "SELECT id as id,description,unit_type,manufacturer_barcode as barcode
              FROM inventory_items
              WHERE inv_group_id='". $group ."'
			  
			  AND id NOT IN (
					SELECT DISTINCT(ii.id) AS id
					FROM inventory_groups ig
					INNER JOIN inventory_items ii ON ig.id = ii.inv_group_id	
					INNER JOIN vendor_items AS vi ON vi.inv_item_id = ii.id
					WHERE vi.vendor_id='". $vendor_id ."'
					GROUP BY ii.id
				)
			  
				ORDER BY description ASC
		"; */
		$query = "SELECT id as id,description,unit_type,manufacturer_barcode as barcode
              FROM inventory_items
              WHERE inv_group_id='". $group ."'
			  
			  AND id NOT IN (
					SELECT DISTINCT(ii.id) AS id
					FROM inventory_groups ig
					INNER JOIN inventory_items ii ON ig.id = ii.inv_group_id	
					INNER JOIN vendor_items AS vi ON vi.inv_item_id = ii.id
					GROUP BY ii.id
				)
			  
				ORDER BY description ASC
		";
	}

    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $response[] = $row;
    }
}

echo $_GET['callback'] . '(' . json_encode($response) . ')';