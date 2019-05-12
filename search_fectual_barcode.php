<?php   

// include_once 'includes/session.php';
include_once("config/accessConfig.php");
function get_http_response_code($url) {
    $headers = get_headers(trim($url));
    return substr($headers[0], 9, 3);
	}
	$search_val = $_REQUEST['search_val'];
	$vendor_id =  $_REQUEST['vendor_id'];
	/*EAN database*/
	

		function stdToArray($obj){
		  $reaged = (array)$obj;
		  foreach($reaged as $key => &$field){
			if(is_object($field))$field = stdToArray($field);
		  }
		  return $reaged;
		}
		
		$q_is_exist_barcode = "SELECT inv_i.id FROM 
inventory_items AS inv_i
JOIN vendor_items AS ve_i ON ve_i.inv_item_id=inv_i.id
WHERE 
inv_i.manufacturer_barcode = '". $search_val ."'
AND ve_i.vendor_id = '". $vendor_id ."'";
$query  =mysql_query($q_is_exist_barcode);
// $query  =mysql_query("SELECT * from inventory_items where manufacturer_barcode = '".$search_val."'");
	if(mysql_num_rows($query)>0){
		echo 'b_found';
	}else{
	
	$key_code = 'F45D224F54AA2F06';
				$jsonurl = 'http://eandata.com/feed/?v=3&keycode=F45D224F54AA2F06&mode=json&find='.$search_val;				
				$jsonurl = str_replace(" ","%20",$jsonurl);
				$geocode=file_get_contents($jsonurl);
				$output= (array)json_decode($geocode);
				$array = stdToArray($output);
				// print_r($array);
				foreach($array as $key=>$values){
				
					$image = $values['image'];
					if($values['image']!=""){
					break;
					}
				}		
				if(isset($array['product'])) {
					foreach($array['product'] as $value){
						echo $result = $value['product'].'^'.$value['long_desc'].'^'.$image;
						die();
					};
				}
				
				
				
	/*Ean Database*/
	
	
	/*fectual*/
	
	$key = 'b7tFhSS94ZceQeOCy3D7HGcKFzvSVP7L0ERq9HrQ';
	$secret = 'oYmNcDUxQpGFr8HEDxDZgpKbizzOoEuVKIDH1sfx';
	
	if($search_val!=""){
	require_once('factual-php-driver-master/Factual.php');
	$factual = new Factual($key,$secret);
	$tableName = "products-cpg";
	$query = new FactualQuery;
	$query->field("upc")->equal($search_val);//052000131512 
	$rest = $factual->fetch($tableName, $query); 
	$r_data = $rest->getData();		
		
	
	foreach($r_data as $value){
		for($i=0; $i<=count($value['image_urls']);$i++){
		if($value['image_urls'][$i]!=""  && (get_http_response_code($value['image_urls'][$i])!="404") ){ 
			$image = $value['image_urls'][$i];
		$i= count($value['image_urls']); }
		}				
		echo $result = $value['product_name'].'^'.$value['brand'].', '.$value['category'].'^'.$image;; 
		die();
		//echo $value['product_name'].', '.$value['brand'].', '.$value['category'];
	}	
	}
	}?>