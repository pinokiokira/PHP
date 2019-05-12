<?php
ob_start("ob_gzhandler");
header('Content-type: application/json');
session_start();
/*if ($_SESSION['bouser'] == "") {
    header("Location: index.php");
}*/
include_once("../../internalaccess/connectdb.php"); 
$item = mysql_real_escape_string($_GET['item']);
$vendor = mysql_real_escape_string($_GET['vendor']);
$loc_inv_id = mysql_real_escape_string($_GET['loc_inv_id']);
if($item != '' && $vendor != ''){
    $item_details = array();
	$option = "<option value=''>Select Unit</option>";
	$option .= "<option value='new'>Add New Unit</option>";
		
    $query = "SELECT id,pack_size,pack_unittype,qty_in_pack,qty_in_pack_unittype,tax_percentage as tax,price
              FROM vendor_items
              WHERE vendor_id = " . $vendor . " AND id=" . $item;
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
		$qty_on_hand = mysql_fetch_array(mysql_query("SELECT SUM(lic.quantity) AS qty
		FROM location_inventory_counts lic 
		LEFT JOIN employees ON lic.employee_id=employees.id 
		LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
		WHERE inv_item_id='".$loc_inv_id."' AND inventory_item_unittype.id = '".$row['qty_in_pack_unittype']."'"));
		
		$row['qty_on_hand'] = $qty_on_hand['qty'];
		
        $item_details['data'][]=$row;
    }
	
	$query2 =  "SELECT inventory_item_unittype.unit_type,inventory_item_unittype.id AS unit_id 
				FROM location_inventory_counts lic 
				LEFT JOIN employees ON lic.employee_id=employees.id 
				LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
				WHERE inv_item_id='".$loc_inv_id."' AND inventory_item_unittype.id != '' GROUP BY unit_type ORDER BY lic.date_counted DESC, lic.time_counted DESC";
	$result2 = mysql_query($query2) or die(mysql_error());
    while($row2 = mysql_fetch_assoc($result2)){
        
		$option .= "<option value='".$row2['unit_id']."'>".$row2['unit_type']."</option>";
    }
	$item_details['unit_type'][] = $option;
	//echo json_encode($item_details2);
    echo json_encode($item_details);
}
?>