<?php
		include_once 'includes/session.php';
include_once("config/accessConfig.php");
	$menu_group=mysql_real_escape_string($_REQUEST['menu_group']);
	$loc=mysql_real_escape_string($_REQUEST['loc']);
	$menu_id=mysql_real_escape_string($_REQUEST['menu_id']);
	
	
	$query = "SELECT * from location_menu_articles where id in (SELECT DISTINCT item_id FROM location_menu_items WHERE location_id='$loc' AND menu_id='$menu_id' and menu_group='$menu_group') ";
	
	$output = mysql_query($query);
	$rows = mysql_num_rows($output);
	while($result = mysql_fetch_assoc($output)){
			$id = $result['id'];
			$name = $result['item'];
			echo $id.'{}'. $name .' []';
	}
?>