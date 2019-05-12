<?php
require_once '../require/security.php';
include '../config/accessConfig.php';
@session_start();

if( $_GET['g'] != '' ){

    $response = array();
    $group = mysql_real_escape_string($_GET['g']);
	
	/*
	$query ="SELECT ii.id, ii.description, location_inventory_items.priority
              FROM inventory_items ii
			  LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id			           
	  		  LEFT JOIN location_inventory_items ON (location_inventory_items.inv_item_id = ii.id AND location_inventory_items.location_id='".$_SESSION['loc']."')
              WHERE ig.id=" . $group . " and ii.description !='' AND location_inventory_items.id IS NULL ";
	*/	  
  /*$query = "SELECT ii.id, ii.description
              FROM inventory_items ii
			  LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id			           
              WHERE ig.id=" . $group . " and ii.description !='' AND ii.id NOT IN (SELECT inv_item_id FROM location_inventory_items WHERE location_id='".$_SESSION['loc']."')";*/
			  
	$query ="SELECT ii.id, ii.description, location_inventory_items.priority
		FROM inventory_items ii
		LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id              
		LEFT JOIN location_inventory_items ON (location_inventory_items.inv_item_id = ii.id AND location_inventory_items.location_id='". $_SESSION['loc'] ."')
		WHERE ig.id=". $group ." and ii.description !='' 
		AND ii.id NOT IN (SELECT inv_item_id FROM location_inventory_items WHERE location_id='". $_SESSION['loc'] ."' AND location_inventory_items.inv_item_id IS NOT NULL)";
    $result = mysql_query($query) or die(mysql_error());

    while($row = mysql_fetch_assoc($result)){
        $response[] = $row;
    }

    echo json_encode($response);
}
?>