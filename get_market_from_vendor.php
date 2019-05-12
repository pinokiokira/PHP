<?php // get_market_from_vendor.php

if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	ob_start("ob_gzhandler"); 
} else { 
	ob_start();
}

header('Content-type: application/json; charset=UTF-8;');

require_once($_SERVER['DOCUMENT_ROOT'] .'\internalaccess\url.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'\internalaccess\connectdb.php');
require_once 'require/security.php';

$vendor_default = mysql_real_escape_string($_REQUEST['vendor_default']);

if(isset($_REQUEST['debug']) && $_REQUEST['debug'] != ''){
	$is_debug = true;
} else {
	$is_debug = false;
}
$debug = array();

$response = array();
$response_data = array();

if($vendor_default == ''){
	// $response = array('ResponseCode' => '0', 'Response' => 'put vendor_default.');
	
	if($_REQUEST['type']=='local'){
		$qry_get_market = "SELECT distinct(market) from ((SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."')
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."')) as market ORDER BY market";
	}else{
		$qry_get_market = "SELECT * FROM inventory_market";
	}
	
	
	
} else {
		
		if($_REQUEST['type']=='local'){
			$qry_get_market = "
				SELECT distinct(market) from (
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."' AND ii.vendor_default='". $vendor_default ."')
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."' AND lii.default_vendor='". $vendor_default ."' )
				) as market ORDER BY market";
		
		}else{
			/*$qry_get_market = "SELECT im.* 
				FROM
				inventory_market AS im
				INNER JOIN inventory_groups AS ig ON ig.Market=im.description
				INNER JOIN inventory_items AS ii ON ii.inv_group_id=ig.id
				WHERE ii.vendor_default='". $vendor_default ."'
				GROUP BY im.id";*/
				
				
    $qry_get_market ="SELECT distinct(tab.market) as description,tab.id from ((SELECT distinct(ig.Market) as market,im.id
				FROM inventory_groups ig
				JOIN inventory_market AS im ON ig.Market=im.description
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."' AND ii.vendor_default = '". $vendor_default ."')
				UNION ALL 
				(SELECT distinct(ig.Market) as market,im.id
				FROM inventory_groups ig	
				JOIN inventory_market AS im ON ig.Market=im.description			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$_SESSION['loc']."' AND lii.default_vendor = '". $vendor_default."')) as tab ORDER BY market";
				
		}
		
}
if($_REQUEST['debug']!=''){
	echo '<br>'.$qry_get_market;
}
	$debug['qry_get_market'] = $qry_get_market;
	$rs_get_market = mysql_query($qry_get_market) or $debug['mysql_error'] = mysql_error();
	
	$options = '<option value="">- - - Select Market - - -</option>';
	while( $row_get_market = mysql_fetch_assoc($rs_get_market) ){
		$selected = '';
		if($_REQUEST['type']=='local'){
			
				$options .= '<option value="'. $row_get_market['market'] .'">'. $row_get_market['market'] .'</option>';
			
		}else{
			$options .= '<option value="'. $row_get_market['description'] .'" data-id='. $row_get_market['id'] .' '. $selected .' >'. $row_get_market['description'] .'</option>';
		}
		
	}
	$response_data['options_market'] = $options;
	$response = array('ResponseCode' => '1', 'Response' => array('data' => $response_data));



if($is_debug){
	$response['debug'] = $debug;
}

echo json_encode($response);
exit(0);
?>