<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: quick_activity.php,v 1.0 9:12 AM 1/19/2014 juni $
*  -> [req 1.12  - 02.16.2014]
	-> Code Indentation
	-> Make changes as requested
	-> Had a layout as a base, but had to change in some place
	-> NOT ALL Changes are not explained in code
*/
include_once("includes/session.php");
include_once("config/accessConfig.php");
 $market = $_POST['market'];
 $vendor = $_POST['vendor'];
 $groupID = $_POST['group_id'];
//if($market!=''){
	$sqlmarket =  " where ig.Market='".$market."'";
/*	}else
	{
	 $sqlmarket =' where 1';
	}*/
 /*$sql = "SELECT distinct(ig.id) as id,ig.description 
			FROM inventory_groups ig
				  $sqlmarket
		ORDER BY ig.description ASC" ;*/
 		
 	$vendor_where1 = '';
	$vendor_where2 = '';	
	if($vendor>0){
		$vendor_where1 =" AND ii.vendor_default = '". $vendor ."'";
		$vendor_where2 =" AND lii.default_vendor = '". $vendor ."'";	
	}
		
	if($_REQUEST['type']=='order_page'){
		 $sql = "SELECT distinct(tbl.id),tbl.description from (
(SELECT distinct(ig.id) as id,ig.description 
	FROM location_inventory_items lii LEFT JOIN inventory_items ii ON lii.inv_item_id=ii.id 
	LEFT JOIN location_inventory_counts as lic ON lic.inv_item_id = lii.id AND lic.Type = 'Purchase' 
	JOIN inventory_groups ig ON ig.id = ii.inv_group_id 
	$sqlmarket AND lii.location_id='".$_SESSION['loc']."' AND lii.type='global' AND lii.status='active' $vendor_where1) 
UNION (SELECT distinct(ig.id) as id,ig.description
	FROM location_inventory_items lii 
	LEFT JOIN location_inventory_counts as lic ON lic.inv_item_id = lii.id AND lic.Type = 'Purchase' 
	JOIN inventory_groups ig ON ig.id = lii.local_group_id
$sqlmarket AND lii.location_id='".$_SESSION['loc']."' AND lii.type<>'global' AND lii.status='active' AND lii.local_item_id is not null $vendor_where2) ORDER BY description ASC
) as tbl ORDER BY description";
	}else{
	
	 $sql = "SELECT distinct(tbl.id),tbl.description from (
			(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global' $sqlmarket AND lii.location_id = '".$_SESSION['loc']."' $vendor_where1 
		ORDER BY ig.description ASC)
UNION ALL 
		(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND  lii.type<>'global' $sqlmarket AND lii.location_id = '".$_SESSION['loc']."' $vendor_where2 
		ORDER BY ig.description ASC)) as tbl ORDER BY description";
	}	
		
				
		$output = mysql_query($sql) or die(mysql_error());								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Item Group - - - </option>';
			if($_REQUEST['field']=='yes'){
				$data .= '<option value="Add_new_group"> - - -   Add New Group - - - </option>';
			}
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				$id = $result['id'];
				$description = $result['description'];
				if ($id == $groupID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		  if($_REQUEST['field']=='yes'){
				$data .= '<option value="Add_new_group"> - - -   Add New Group - - - </option>';
		  }
		}
		
		echo $data;exit;
  
?>
