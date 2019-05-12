<?php
	include_once 'includes/session.php';
include_once("config/accessConfig.php");
	$menu_id=mysql_real_escape_string($_REQUEST['menu_id']);
	$loc=mysql_real_escape_string($_REQUEST['loc']);
	
	$query = " SELECT * from location_menu_group where id in (SELECT DISTINCT menu_group FROM location_menu_items WHERE location_id='$loc' AND menu_id='$menu_id') ";
	$output = mysql_query($query);
	$rows = mysql_num_rows($output);
	while($result = mysql_fetch_assoc($output)){
			$id = $result['id'];
			$name = $result['menu_group'];
			echo $id.'{}'. $name .' []';
	}
?>