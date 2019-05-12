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
require_once 'require/security.php';
include 'config/accessConfig.php'; 
	$vendor_where = "";
if($_REQUEST['vendor']>0){
	$vendor_where = " AND vi.vendor_id='".$_REQUEST['vendor']."'";
}
 $market = $_POST['market'];
//if($market!='All'){
	$sqlmarket =  "  AND ig.Market='".$market."'";
//	}else
//	{
//	 $sqlmarket =' where 1';
//	}
 
 /*$sql = "SELECT distinct(ig.id) as id,ig.description 
		 FROM inventory_groups ig
		 INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id	
		 INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
		 $sqlmarket
		 ORDER BY ig.description ASC" ;*/
		 
		/*$sql = "SELECT distinct(ig.id) as id, ig.description,count(ii.id) as invs  FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
		 		INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
				where 1
				$sqlmarket $vendor_where
				group by ig.id
			 	ORDER BY ig.description ASC";*/

/*	if($market=='All'){
		$sql = "SELECT distinct(ig.id) as id, ig.description,count(ii.id) as invs  FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
		 		INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
				where 1
				$vendor_where
				group by ig.id
			 	ORDER BY ig.description ASC";
		} else{*/
		$sql = "SELECT distinct(ig.id) as id, ig.description,count(ii.id) as invs  FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
		 		INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
				where 1
				$sqlmarket $vendor_where
				group by ig.id
			 	ORDER BY ig.description ASC";
		/*}		 */
		//
	//			INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id
		
		//echo $sql;
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
				$data .= '<option rel="'.$result['invs'].'" value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		  if($_REQUEST['field']=='yes'){
				$data .= '<option value="Add_new_group"> - - -   Add New Group - - - </option>';
		  }
		}
		
		echo $data;exit;
  
?>
