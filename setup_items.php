<?php
error_reporting(0);
require_once 'require/security.php';
include 'config/accessConfig.php'; 

function get_empmaster($emp_id){
	$query = mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees_master where empmaster_id =".$emp_id);
	if($query && mysql_num_rows($query) > 0){
		$query = mysql_fetch_array($query);
		return $query['name'];
	} else {
		return '';
	}
}

function status_img($status){
	if($status =='active'){
		$res = '<img src="images/Active, Corrected, Delivered - 16.png" title="Active" >';
	}else{
		$res = '<img src="images/Inactive & Missing Punch - 16.png" title="Inactive" >';
	}
	return $res;
}

function jRender_inventory_market_combo($nameAndID,$vendor_id, $cClass = null, $cStyle = null) {
	if($_REQUEST['market']!=""){
	$market = $_REQUEST['market'];
	$group_where = "AND ig.Market='$market'";
	$q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi 
		LEFT JOIN inventory_items inv_itm ON inv_itm.id=vi.inv_item_id INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id 
		WHERE vi.vendor_id = '".$vendor_id."' $group_where "));
	}else{
		$q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi where vi.vendor_id = '".$vendor_id."' "));
	}

	$total_count = $q_count['counts'];
		
	$class = "input-xlarge" ;
	$style = 'width:90%;height:23px;';
	if ($cClass!="") $class  = $cClass;
	if ($cStyle!="") $style  = $cStyle;
	$data = '<select name="market" onChange="javascript:jQuery(\'#total_rows\').val(jQuery(this).find(\'option:selected\').attr(\'rel\'))" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 

	//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
	$sql = "SELECT distinct(ig.Market) as market,count(ii.id) as invs
		FROM inventory_groups ig
		INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
		INNER JOIN vendor_items lii ON lii.inv_item_id=ii.id 
		WHERE lii.vendor_id ='".$vendor_id."' AND market !='NULL' group by ig.Market ORDER BY market ASC";
			
	$output = mysql_query($sql);// or die(mysql_error());								
	$rows = mysql_num_rows($output);
			
	if ($rows > 0 && $rows != '') {
		$data .= '<option rel="'.$total_count.'" value=""> - - -  Select Market - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
			//print_r($result);exit;
			
			$market = $result['market'];
			//echo $market;
			if ($result['market'] == $_REQUEST['market']) {
				$sel1 = ' selected="selected"';
			} else {
				$sel1 = '';
			}
			$data .= '<option rel="'.$result['invs'].'" value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
		}
	} else {
		$data .= '<option value=""> - - -  No Market Found  - - - </option>';
	}
	$data .= '</select>';
	return $data;
}

$empmaster_id=$_SESSION['client_id'];
$curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));  
$c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

$ven_info = "select * from vendors where id='". $vendor_id ."'";
$ven_info = mysql_query($ven_info);
$ven_info = mysql_fetch_assoc($ven_info);

if(isset($_POST) && $_POST['Submit']=="Submit"){

	$digital_image_name = $_REQUEST['digital_image_name'];
	$old_image = $_REQUEST['oldimage'];
	if($digital_image_name != '')
	{	
		$target_path = "temp_img/";
		$file_with_path = $digital_image_name;
		$target_path = $target_path . $file_with_path;
		$ftphost = FTPDOMAIN;
		$ftpusr = FTPUSER;
		$ftppwd = FTPPASSWORD;

		$target_ftp_path = "inventory/";
		$ftp_path = $target_ftp_path . $file_with_path;

		$local_file = $target_path;
		$conn_id = ftp_connect($ftphost, 21);
		ftp_login($conn_id, $ftpusr, $ftppwd);
		ftp_pasv ($conn_id, true);
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
		ftp_close($conn_id);
		unlink($target_path);
		$image = $ftp_path;
	}else{
		$image = $old_image;
	}

	$code_id = mysql_real_escape_string($_REQUEST['code_id']);
	$name = mysql_real_escape_string($_REQUEST['name']);
	$d_market = mysql_real_escape_string($_REQUEST['d_market']);
	$status = mysql_real_escape_string($_REQUEST['status']);
	$i_id = mysql_real_escape_string($_REQUEST['vendor_internal_number']);
	$pack_size = mysql_real_escape_string($_REQUEST['pack_size']);
	$pack_unit_type = mysql_real_escape_string($_REQUEST['pack_unit_type']);
	$qty_in_pack = mysql_real_escape_string($_REQUEST['qty_in_pack']);
	$qty_in_pack_unit_type = mysql_real_escape_string($_REQUEST['qty_in_pack_unit_type']);
	$qty_in_pack_size = mysql_real_escape_string($_REQUEST['qty_in_pack_size']);
	$pack_weight = mysql_real_escape_string($_REQUEST['pack_weight']);

	$price_by_weight = mysql_real_escape_string($_REQUEST['price_by_weight']);
	$price_by_weight_unittype= mysql_real_escape_string($_REQUEST['price_by_weight_unittype']);

	$lead_time= mysql_real_escape_string($_REQUEST['lead_time']);
	$stock= mysql_real_escape_string($_REQUEST['stock']);

	$taxable= mysql_real_escape_string($_REQUEST['taxable']);
	$tax_type= mysql_real_escape_string($_REQUEST['tax_type']);
	$tax_amount= mysql_real_escape_string($_REQUEST['tax_amount']);

	$splitable=mysql_real_escape_string($_REQUEST['splitable']);
	$splitable_price=mysql_real_escape_string($_REQUEST['splitable_price']);
	$splits=mysql_real_escape_string($_REQUEST['splits']);
	$splits_minimum=mysql_real_escape_string($_REQUEST['splits_minimum']);

	$tax_percentage = mysql_real_escape_string($_REQUEST['tax_percentage']);
	$price = mysql_real_escape_string($_REQUEST['price']);
	$promotion = mysql_real_escape_string($_REQUEST['promotion']);
	$promotion_price = mysql_real_escape_string($_REQUEST['promotion_price']);
	$inv_item_type = mysql_real_escape_string($_REQUEST['inv_item_type']);
	$inv_item = mysql_real_escape_string($_REQUEST['inv_item']);
	$inv_item_new = mysql_real_escape_string($_REQUEST['inv_item_new']);
	$group_id = mysql_real_escape_string($_REQUEST['group']);
	$purchased_from_vendor = mysql_real_escape_string($_REQUEST['purchased_from_vendor']);
	$purchased_price = mysql_real_escape_string($_REQUEST['purchased_price']);
	$purchased_last = mysql_real_escape_string($_REQUEST['purchased_last']);
	$group_ins_type = mysql_real_escape_string($_REQUEST['group_ins_type']);
	$new_gruop = mysql_real_escape_string($_REQUEST['new_gruop']);

	/* Recources new fields */
	$resources_barcode = mysql_real_escape_string($_REQUEST['resources_barcode']);
	$resources_market = mysql_real_escape_string($_REQUEST['resources_market']);
	$resources_leadtime = mysql_real_escape_string($_REQUEST['resources_leadtime']);
	$resources_manufacturer = mysql_real_escape_string($_REQUEST['resources_manufacturer']);
	$resources_modelnumber = mysql_real_escape_string($_REQUEST['resources_modelnumber']);
	$resources_notes = mysql_real_escape_string($_REQUEST['resources_notes']);
	$resources_promotion = mysql_real_escape_string($_REQUEST['resources_promotion']);
	$resources_promotionprice = mysql_real_escape_string($_REQUEST['resources_promotionprice']);
	$resources_qtyinpack = mysql_real_escape_string($_REQUEST['resources_qtyinpack']);
	$resources_qtyinpacksize = mysql_real_escape_string($_REQUEST['resources_qtyinpacksize']);
	$resources_stock = mysql_real_escape_string($_REQUEST['resources_stock']);
	$resources_taxable = mysql_real_escape_string($_REQUEST['resources_taxable']);

if($group_ins_type=='new'){
	$q_max_group = mysql_query("SELECT IFNULL(MAX(CAST(group_id AS UNSIGNED))+1, 1000) as grp from inventory_groups");
	if($q_max_group && mysql_num_rows($q_max_group) > 0){
		$max_group = mysql_fetch_array($q_max_group);
	} else {
		$max_group = array();
	}
				
	$q_max_priority = mysql_query("SELECT IFNULL(MAX(CAST(priority AS UNSIGNED))+1, 1000) as priority from inventory_groups");
	if($q_max_priority && mysql_num_rows($q_max_priority) > 0){
		$max_priority = mysql_fetch_array($q_max_priority);
	} else {
		$max_priority = array();
	}
			 
	if(count($max_priority) > 0 && count($max_group) > 0){
	$gquery = "INSERT INTO inventory_groups SET
		group_id = '".$max_group['grp']."',
		priority = '".$max_priority['priority']."',
		description = '".$new_gruop."',
		Market = '".$d_market."'";
		$res_g = mysql_query($gquery);// or die(mysql_error());
		if($res_g){
		$group = mysql_insert_id();
		}
	}		
}else{
	$group = $group_id;
}
if($d_market=='Retail'){
	$inV_fields = "color ='".mysql_real_escape_string($_REQUEST['inv_color'])."',size ='".mysql_real_escape_string($_REQUEST['inv_size'])."',type ='".mysql_real_escape_string($_REQUEST['inv_type'])."',";
}

if($inv_item_type=="new"){
	$query_last = mysql_fetch_array(mysql_query("SELECT max(CAST(id AS UNSIGNED)+1) as counts  from inventory_items"));
	$last_item_id = $query_last['counts'];
	
	$manufacturer_barcode = mysql_real_escape_string($_REQUEST['ni_barcode']);
	// $taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	//$image = mysql_real_escape_string($_REQUEST['image']);
	$model_number = mysql_real_escape_string($_REQUEST['ni_model']);
	$manufacturer = mysql_real_escape_string($_REQUEST['ni_manufacture']);
	$brand = mysql_real_escape_string($_REQUEST['ni_brand']);
	$notes = mysql_real_escape_string($_REQUEST['ni_notes']);
	$fields = "";
	
	// description = '".$inv_item_new."', 
	//item_id='".$last_item_id."',
	$ins_query = "INSERT INTO inventory_items SET
				  item_id = '".$i_id."',
				  inv_group_id = '".$group."',				  
				  status ='active',
				  description = '". $name ."', 
				  unit_type = '".$pack_unit_type."',
				  notes = '".$notes."',
				  brand = '".$brand."',
				  manufacturer = '".$manufacturer."',
				  model_number = '".$model_number."',
				  image = '".$image."',
				  $inV_fields
				  taxable = '".$taxable."',
				  manufacturer_barcode = '".$manufacturer_barcode."', 
				  
				  vendor_default='".$vendor_id  ."', 
				  vendor_id='". $vendor_id ."', 
				  
				  created_by = '".$_SESSION['client_id']."',
				  created_on = 'VendorPanel',
				  created_dt = '".date('Y-m-d H:i:s')."'";				 
				  // echo $ins_query;exit;
					//print_r("insert item :    ". $ins_query);
					
	$res_ins=mysql_query($ins_query) or die(mysql_error());
	if($res_ins){
	$inv_item_id = mysql_insert_id();
	}
}else{
//echo "UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' WHERE id = '".$inv_item."'";
//exit;
	$inv_item = mysql_real_escape_string($_REQUEST['inv_item']);
	$ex_field = " , description = '". $name ."' ";
	// $taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	$i_id	= mysql_real_escape_string($_REQUEST['vendor_internal_number']);
	
	$up_inv = mysql_query("UPDATE inventory_items SET $inV_fields taxable = '".$taxable."', item_id = '".$i_id."' ". $ex_field ." WHERE id = '".$inv_item."'") or die(mysql_error());
	$inv_item_id = $inv_item;
	echo '<script> alert("' . $inv_item . '")</script>';
}

$query_c ="SELECT id from vendor_items_inventory_counts WHERE vendor_id='".$vendor_id."' AND  inv_item_id='".$inv_item_id."'";
$query_check = mysql_query($query_c);
if(mysql_num_rows($query_check)==0){
	$ins_count ="INSERT INTO vendor_items_inventory_counts SET 
			vendor_id='".$vendor_id."',
			inv_item_id='".$inv_item_id."',
			quantity='0',
			date_counted=DATE_FORMAT(now(),'%Y-%m-%d')";
	mysql_query($ins_count) or die(mysql_error());
}

if($code_id!=""){
	$query1 = "UPDATE vendor_items SET
		status = '".$status."',
		pack_size ='".$pack_size."',				   
		pack_unittype ='".$pack_unit_type."',
		qty_in_pack ='".$qty_in_pack."',
		qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
		qty_in_pack_size ='".$qty_in_pack_size."',
		pack_weight ='".$pack_weight."',
		price_by_weight='".$price_by_weight."',
		price_by_weight_unittype='".$price_by_weight_unittype."',
		lead_time='".$lead_time."',
		stock='".$stock."',
		taxable='".$taxable."',
		tax_type='".$tax_type."',
		tax_amount='".$tax_amount."',
		splitable='".$splitable."',
		splitable_price='".$splitable_price."',
		splits='".$splits."',
		splits_minimum='".$splits_minimum."',
		tax_percentage ='".$tax_percentage."',
		price ='".$price."',
		promotion ='".$promotion."',
		promotion_price ='".$promotion_price."',
		purchased_from_vendor = '".$purchased_from_vendor."',
		purchased_price = '".$purchased_price."',
		purchased_last = '".$purchased_last."',
		last_by = '".$_SESSION['client_id']."',
		last_on = 'VendorPanel',
		last_datetime = '".date('Y-m-d H:i:s')."'
		WHERE id =".$code_id; //inv_item_id='".$inv_item_id."',

}else{
	$query1 = "INSERT INTO vendor_items SET	
		status = '".$status."',				
		pack_size ='".$pack_size."',
		pack_unittype ='".$pack_unit_type."',
		qty_in_pack ='".$qty_in_pack."',
		qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
		qty_in_pack_size ='".$qty_in_pack_size."',
		pack_weight ='".$pack_weight."',
		
		price_by_weight='".$price_by_weight."',
		price_by_weight_unittype='".$price_by_weight_unittype."',
		
		lead_time='".$lead_time."',
		stock='".$stock."',
		
		taxable='".$taxable."',
		tax_type='".$tax_type."',
		tax_amount='".$tax_amount."',
		
		splitable='".$splitable."',
		splitable_price='".$splitable_price."',
		splits='".$splits."',
		splits_minimum='".$splits_minimum."',
		
		
		tax_percentage ='".$tax_percentage."',
		price ='".$price."',
		promotion ='".$promotion."',
		vendor_id = '".$vendor_id."',
		purchased_from_vendor = '".$purchased_from_vendor."',
		purchased_price = '".$purchased_price."',
		purchased_last = '".$purchased_last."',
		inv_item_id='".$inv_item_id."',
		created_by = '".$_SESSION['client_id']."',
		created_on = 'VendorPanel',
		created_datetime = '".date('Y-m-d H:i:s')."',
		promotion_price ='".$promotion_price."'";
}	
	$group1_inp = $_REQUEST['group1_inp'];
	$market_inp = $_REQUEST['market_inp'];
	
	if($group1_inp == ''){
		$group1_inp = $group_id;
	}
	if($market_inp == ''){
		$market_inp = $d_market;
	}

	$res1 = mysql_query($query1) or die(mysql_error());
	if($res1){
		header('location:setup_items.php?group1='.$group1_inp.'&market='.$market_inp.'&msg=Item Added/Updated Successfully!');
	}	
	else{
		header('location:setup_items.php?group1='.$group1_inp.'&market='.$market_inp.'&msg=Item Add/Update Failed!');
	}
}


if(isset($_POST) && $_POST['Submit2']=="Submit"){
	$digital_image_name = $_REQUEST['digital_image_name'];
	$old_image = $_REQUEST['oldimage'];
	if($digital_image_name != '')
	{	
		$target_path = "temp_img/";
		$file_with_path = $digital_image_name;
		$target_path = $target_path . $file_with_path;
		$ftphost = FTPDOMAIN;
		$ftpusr = FTPUSER;
		$ftppwd = FTPPASSWORD;

		$target_ftp_path = "inventory/";
		$ftp_path = $target_ftp_path . $file_with_path;

		$local_file = $target_path;
		$conn_id = ftp_connect($ftphost, 21);
		ftp_login($conn_id, $ftpusr, $ftppwd);
		ftp_pasv ($conn_id, true);
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
		ftp_close($conn_id);
		unlink($target_path);
		$image = $ftp_path;
	}else{
		$image = $old_image;
	}

$code_id = mysql_real_escape_string($_REQUEST['code_id']);
$name = mysql_real_escape_string($_REQUEST['name_hn']);
$d_market = mysql_real_escape_string($_REQUEST['d_market_hn']);
$status = mysql_real_escape_string($_REQUEST['status_hn']);
$vendor_internal_number = mysql_real_escape_string($_REQUEST['vendor_internal_number_hn']);
$pack_size = mysql_real_escape_string($_REQUEST['pack_size_hn']);
$pack_unit_type = mysql_real_escape_string($_REQUEST['pack_unit_type_hn']);
$qty_in_pack = mysql_real_escape_string($_REQUEST['qty_in_pack_hn']);
$qty_in_pack_unit_type = mysql_real_escape_string($_REQUEST['qty_in_pack_unit_type_hn']);
$qty_in_pack_size = mysql_real_escape_string($_REQUEST['qty_in_pack_size_hn']);
$pack_weight = mysql_real_escape_string($_REQUEST['pack_weight_hn']);

$price_by_weight = mysql_real_escape_string($_REQUEST['price_by_weight_hn']);
$price_by_weight_unittype= mysql_real_escape_string($_REQUEST['price_by_weight_unittype_hn']);

$lead_time= mysql_real_escape_string($_REQUEST['lead_time_hn']);
$stock= mysql_real_escape_string($_REQUEST['stock_hn']);

$taxable= mysql_real_escape_string($_REQUEST['taxable_hn']);
$tax_type= mysql_real_escape_string($_REQUEST['tax_type_hn']);
$tax_amount= mysql_real_escape_string($_REQUEST['tax_amount_hn']);

$splitable=mysql_real_escape_string($_REQUEST['splitable_hn']);
$splitable_price=mysql_real_escape_string($_REQUEST['splitable_price_hn']);
$splits=mysql_real_escape_string($_REQUEST['splits_hn']);
$splits_minimum=mysql_real_escape_string($_REQUEST['splits_minimum_hn']);

$tax_percentage = mysql_real_escape_string($_REQUEST['tax_percentage_hn']);
$price = mysql_real_escape_string($_REQUEST['price_hn']);
$promotion = mysql_real_escape_string($_REQUEST['promotion_hn']);
$promotion_price = mysql_real_escape_string($_REQUEST['promotion_price_hn']);
$inv_item_type = mysql_real_escape_string($_REQUEST['inv_item_type_hn']);
$inv_item = mysql_real_escape_string($_REQUEST['inv_item_hn']);
$inv_item_new = mysql_real_escape_string($_REQUEST['inv_item_new_hn']);
$group_id = mysql_real_escape_string($_REQUEST['group_hn']);
$purchased_from_vendor = mysql_real_escape_string($_REQUEST['purchased_from_vendor_hn']);
$purchased_price = mysql_real_escape_string($_REQUEST['purchased_price_hn']);
$purchased_last = mysql_real_escape_string($_REQUEST['purchased_last_hn']);
$group_ins_type = mysql_real_escape_string($_REQUEST['group_ins_type_hn']);
$new_gruop = mysql_real_escape_string($_REQUEST['new_gruop_hn']);

/* Recources new fields */

$resources_barcode = mysql_real_escape_string($_REQUEST['resources_barcode_hn']);
$resources_market = mysql_real_escape_string($_REQUEST['resources_market_hn']);
$resources_leadtime = mysql_real_escape_string($_REQUEST['resources_leadtime_hn']);
$resources_manufacturer = mysql_real_escape_string($_REQUEST['resources_manufacturer_hn']);
$resources_modelnumber = mysql_real_escape_string($_REQUEST['resources_modelnumber_hn']);
$resources_notes = mysql_real_escape_string($_REQUEST['resources_notes_hn']);
$resources_promotion = mysql_real_escape_string($_REQUEST['resources_promotion_hn']);
$resources_promotionprice = mysql_real_escape_string($_REQUEST['resources_promotionprice_hn']);
$resources_qtyinpack = mysql_real_escape_string($_REQUEST['resources_qtyinpack_hn']);
$resources_qtyinpacksize = mysql_real_escape_string($_REQUEST['resources_qtyinpacksize_hn']);
$resources_stock = mysql_real_escape_string($_REQUEST['resources_stock_hn']);
$resources_taxable = mysql_real_escape_string($_REQUEST['resources_taxable_hn']);


if($group_ins_type=='new'){
				$q_max_group = mysql_query("SELECT IFNULL(MAX(CAST(group_id AS UNSIGNED))+1, 1000) as grp from inventory_groups");
				if($q_max_group && mysql_num_rows($q_max_group) > 0){
					$max_group = mysql_fetch_array($q_max_group);
				} else {
					$max_group = array();
				}
				
				$q_max_priority = mysql_query("SELECT IFNULL(MAX(CAST(priority AS UNSIGNED))+1, 1000) as priority from inventory_groups");
				if($q_max_priority && mysql_num_rows($q_max_priority) > 0){
					$max_priority = mysql_fetch_array($q_max_priority);
				} else {
					$max_priority = array();
				}
			 
			 if(count($max_priority) > 0 && count($max_group) > 0){
			 	$gquery = "INSERT INTO inventory_groups SET
					group_id = '".$max_group['grp']."',
					priority = '".$max_priority['priority']."',
					description = '".$new_gruop."',
					Market = '".$d_market."'";
					$res_g = mysql_query($gquery);// or die(mysql_error());
					if($res_g){
					$group = mysql_insert_id();
					}
			 }
			 
					
}else{
	$group = $group_id;
}
if($d_market=='Retail'){
		$inV_fields = "color ='".mysql_real_escape_string($_REQUEST['inv_color'])."',size ='".mysql_real_escape_string($_REQUEST['inv_size'])."',type ='".mysql_real_escape_string($_REQUEST['inv_type'])."',";
	}

//if($inv_item_type=="new"){
	$query_last = mysql_fetch_array(mysql_query("SELECT max(CAST(id AS UNSIGNED)+1) as counts  from inventory_items"));
	$last_item_id = $query_last['counts'];
	
	$manufacturer_barcode = mysql_real_escape_string($_REQUEST['ni_barcode']);
	// $taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	//$image = mysql_real_escape_string($_REQUEST['image']);
	$model_number = mysql_real_escape_string($_REQUEST['ni_model']);
	$manufacturer = mysql_real_escape_string($_REQUEST['ni_manufacture']);
	$brand = mysql_real_escape_string($_REQUEST['ni_brand_hn']);
	$notes = mysql_real_escape_string($_REQUEST['ni_notes']);
	$fields = "";
	
	
	// description = '".$inv_item_new."', 
	 $ins_query = "INSERT INTO inventory_items SET
				  item_id='".$last_item_id."',
				  inv_group_id = '".$group."',				  
				  status ='active',
				  description = '". $name ."', 
				  unit_type = '".$qty_in_pack_unit_type."',
				  notes = '".$notes."',
				  brand = '".$brand."',
				  manufacturer = '".$manufacturer."',
				  model_number = '".$model_number."',
				  image = '".$image."',
				  $inV_fields
				  taxable = '".$taxable."',
				  manufacturer_barcode = '".$manufacturer_barcode."', 
				  
				  vendor_default='".$vendor_id  ."', 
				  vendor_id='". $vendor_id ."', 
				  
				  created_by = '".$_SESSION['client_id']."',
				  created_on = 'VendorPanel',
				  created_dt = '".date('Y-m-d H:i:s')."'";				 
				  // echo $ins_query;exit;
				 $res_ins=mysql_query($ins_query);//or die(mysql_error());
				 if($res_ins){
				 $inv_item_id = mysql_insert_id();
				 }

				//print_r('Quer1 :'.$ins_query);

/*}else{

	$ex_field = " , description = '". $name ."' ";

	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	$up_inv = mysql_query("UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' ". $ex_field ." WHERE id = '".$inv_item."'");// or die(mysql_error());
	$inv_item_id = $inv_item;
}*/


$query_c ="SELECT id from vendor_items_inventory_counts WHERE vendor_id='".$vendor_id."' AND  inv_item_id='".$inv_item_id."'";
$query_check = mysql_query($query_c);
if(mysql_num_rows($query_check)==0){
	 $ins_count ="INSERT INTO vendor_items_inventory_counts SET 
				vendor_id='".$vendor_id."',
				inv_item_id='".$inv_item_id."',
				quantity='0',
				date_counted=DATE_FORMAT(now(),'%Y-%m-%d')";
				mysql_query($ins_count);	
}

if($code_id!=""){
	$query1 = "UPDATE vendor_items SET
				   status = '".$status."',
				   pack_size ='".$pack_size."',				   
				   pack_unittype ='".$pack_unit_type."',
				   inv_item_id='".$inv_item_id."',
				   vendor_internal_number = '".$vendor_internal_number."',
				   qty_in_pack ='".$qty_in_pack."',
				   qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
				   qty_in_pack_size ='".$qty_in_pack_size."',
				   pack_weight ='".$pack_weight."',
				   
				   price_by_weight='".$price_by_weight."',
				   price_by_weight_unittype='".$price_by_weight_unittype."',
				   
				   lead_time='".$lead_time."',
				   stock='".$stock."',
				   
				   taxable='".$taxable."',
				   tax_type='".$tax_type."',
				   tax_amount='".$tax_amount."',
				   
				   
				   splitable='".$splitable."',
				   splitable_price='".$splitable_price."',
				   splits='".$splits."',
				   splits_minimum='".$splits_minimum."',

				   
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   promotion_price ='".$promotion_price."',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',
				   last_by = '".$_SESSION['client_id']."',
				   last_on = 'VendorPanel',
				   last_datetime = '".date('Y-m-d H:i:s')."'
				   WHERE id =".$code_id; //inv_item_id='".$inv_item_id."',
				
}else{
				   $query1 = "INSERT INTO vendor_items SET	
				   status = '".$status."',				
				   pack_size ='".$pack_size."',
				   pack_unittype ='".$pack_unit_type."',
				   qty_in_pack ='".$qty_in_pack."',
				   vendor_internal_number = '".$vendor_internal_number."',
				   qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
				   qty_in_pack_size ='".$qty_in_pack_size."',
				   pack_weight ='".$pack_weight."',
				   
				   price_by_weight='".$price_by_weight."',
				   price_by_weight_unittype='".$price_by_weight_unittype."',
				   
				   lead_time='".$lead_time."',
				   stock='".$stock."',
				   
				   taxable='".$taxable."',
				   tax_type='".$tax_type."',
				   tax_amount='".$tax_amount."',
				   
				   splitable='".$splitable."',
				   splitable_price='".$splitable_price."',
				   splits='".$splits."',
				   splits_minimum='".$splits_minimum."',
				   
				   
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   vendor_id = '".$vendor_id."',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',
				   inv_item_id='".$inv_item_id."',
				   created_by = '".$_SESSION['client_id']."',
				   created_on = 'VendorPanel',
				   created_datetime = '".date('Y-m-d H:i:s')."',
				   promotion_price ='".$promotion_price."'";

}	
	$group1_inp = $_REQUEST['group1_inp'];
	$market_inp = $_REQUEST['market_inp'];
	
	if($group1_inp == ''){
		$group1_inp = $group_id;
	}
	if($market_inp == ''){
		$market_inp = $d_market;
	}
	
	$res1 = mysql_query($query1);// or die(mysql_error());	
	//header('location:setup_items.php?group1='.$group1_inp.'&market='.$market_inp.'&msg=Item Added/Updated Successfully!');
	header('location:setup_items.php?group1=&market='.$market_inp.'&msg=Item Added/Updated Successfully!');
}

$group_where ="";
	$limit = 500;
	$search_where = "";
if($_REQUEST['group1']!="" && strtolower($_REQUEST['group1'])!="all"){
	$market = $_REQUEST['market'];
	$group_where = "AND inv_itm.inv_group_id=".$_REQUEST['group1']." AND ig.Market='$market'";
	$limit = 500;
}else if(strtolower($_REQUEST['group1'])=="all" && $_REQUEST['market']!=""){
	$market = $_REQUEST['market'];
	$group_where = "AND ig.Market='$market'";
	$limit = 500;
}
if(isset($_REQUEST['search_txt1'])){
	$limit = 500;
		if($_REQUEST['search_txt1']!=""){
			$serch = $_REQUEST['search_txt1'];
			$search_where = " AND (inv_itm.description LIKE '%".$serch."%' OR inv_itm.item_id LIKE '%".$serch."%')";		
		}
}

  $sql = "SELECT inv_itm.item_id as i_id,
        inv_itm.description,
			   inv_itm.image,
			   inv_itm.color as inv_color,
			   inv_itm.size as inv_size,
			   inv_itm.type as inv_type,
			   inv_itm.id as item_id,
			   inv_itm.brand as brand,
			   ven_itm.id,
			   ven_itm.pack_size,
			   ven_itm.pack_unittype as pack_unittype_id,
			   iiu1.unit_type as pack_unittype,
			   ven_itm.qty_in_pack,
			   ven_itm.qty_in_pack_unittype as qty_in_pack_unittype_id,
			   iiu2.unit_type as qty_in_pack_unittype,			   
			   inv_itm.inv_group_id,
			   ven_itm.status,
			   ven_itm.tax_percentage,
			   ven_itm.price,
			   ven_itm.promotion,			   
			   ven_itm.promotion_price,
			   ven_itm.vendor_internal_number,
			   ig.description as group_name,
			   ven_itm.purchased_last,
			   ven_itm.purchased_from_vendor,
			   v.name as purchased_from_vendor_name,
			   ven_itm.purchased_price,
			   ven_itm.created_on,
			   ven_itm.created_by,
			   ven_itm.created_datetime,
			   ven_itm.last_by,
			   ven_itm.last_on,
			   ven_itm.last_datetime,
			   inv_itm.taxable,
			   ig.Market
			   ,ven_itm.qty_in_pack_size
			   ,ven_itm.pack_weight
			   
			   	,ven_itm.price_by_weight
			   	,ven_itm.price_by_weight_unittype
			   	,ven_itm.lead_time
			   	,ven_itm.stock
			   	,ven_itm.taxable
			   	,ven_itm.tax_type
				,ven_itm.tax_amount
				
				,ven_itm.splitable
				,ven_itm.splitable_price
				,ven_itm.splits
				,ven_itm.splits_minimum
			   
		FROM   vendor_items ven_itm 
		LEFT JOIN inventory_items inv_itm ON inv_itm.id=ven_itm.inv_item_id
		LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = ven_itm.pack_unittype
		LEFT JOIN inventory_item_unittype as iiu2 ON iiu2.id = ven_itm.qty_in_pack_unittype
		INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id
		LEFT JOIN vendors as v ON v.id = ven_itm.purchased_from_vendor
		WHERE  ven_itm.vendor_id='".$vendor_id."' $group_where $search_where GROUP BY ven_itm.id LIMIT $limit";
		
		if($_REQUEST['debug'] == '1'){
			echo '$sql : '. $sql;
		}
$resultJobs = mysql_query($sql);// or die(mysql_error());
if(isset($_GET['id'])){
	$selected=$_GET['id'];
}else{
	$selected=0;
}
	
/* $unittype = "
SELECT * from inventory_item_unittype WHERE conversion_group = 'weight' 
UNION 
SELECT * from inventory_item_unittype WHERE conversion_group = 'volume' 
UNION 
SELECT * from inventory_item_unittype WHERE conversion_group = 'package' 
ORDER BY unit_type"; */
// $unittype = "SELECT * from inventory_item_unittype ORDER BY unit_type";
$unittype = "SELECT * from inventory_item_unittype ORDER BY conversion_group, unit_type";
$res_unit_type = mysql_query($unittype);

if($_REQUEST['group1']!="" && $_REQUEST['group1']!="all"){
	$market = $_REQUEST['market'];
	$group_where = "AND inv_itm.inv_group_id=".$_REQUEST['group1']." AND ig.Market='$market'";
	
	$q_count = mysql_query("select count(vi.id) as count LEFT JOIN inventory_items inv_itm ON inv_itm.id=vi.inv_item_id INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id from vendor_items as vi where vi.vendor_id = '".$vendor_id."' $group_where ");
	if($q_count && mysql_num_rows($q_count) > 0){
		$q_count = mysql_fetch_array($q_count);
	} else {
		$q_count = array();
	}
	
}else if($_REQUEST['group1']=="All" && $_REQUEST['market']!=""){
	$market = $_REQUEST['market'];
	$group_where = "AND ig.Market='$market'";
	
	$q_count = mysql_query("select count(vi.id) as count from vendor_items as vi LEFT JOIN inventory_items inv_itm ON inv_itm.id=vi.inv_item_id INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id  where vi.vendor_id = '".$vendor_id."' $group_where ");
	if($q_count && mysql_num_rows($q_count) > 0){
		$q_count = mysql_fetch_array($q_count);
	} else {
		$q_count = array();
	}
}else{
	$q_count = mysql_query("select count(vi.id) as count from vendor_items as vi where vi.vendor_id = '".$vendor_id."' ");
	if($q_count && mysql_num_rows($q_count) > 0){
		$q_count = mysql_fetch_array($q_count);
	} else {
		$q_count = array();
	}
}
$total_count = $q_count['count'];


if (isset($_REQUEST['m']) && $_REQUEST['m']=="check_item_exists") {
	$data = array();
	$searchsql = mysql_query("SELECT description,id FROM inventory_items WHERE description LIKE '".mysql_real_escape_string($_REQUEST['description'])."'");
	if (mysql_num_rows($searchsql)>0) {
		while($fet = mysql_fetch_array($searchsql)){
			$data[] =  '<b>'.$fet['description'].'</b><br>';
		}
	}
	if(!empty($data)){
		echo json_encode(array('flag'=>1,'data'=>$data));
	}else{
		echo json_encode(array('flag'=>0));
	}
	exit;
}
if (isset($_REQUEST['m']) && $_REQUEST['m']=="check_item_code_exists") {
	$data = array();
	$searchsql = mysql_query("SELECT item_id FROM inventory_items WHERE item_id LIKE '".mysql_real_escape_string($_REQUEST['item_code'])."'");
	if (mysql_num_rows($searchsql)>0) {
		while($fet = mysql_fetch_array($searchsql)){
			$data[] =  '<b>'.$fet['item_id'].'</b><br>';
		}
	}
	if(!empty($data)){
		echo json_encode(array('flag'=>1,'data'=>$data));
	}else{
		echo json_encode(array('flag'=>0));
	}
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/dd.css" type="text/css">
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>

<script type="text/javascript" src="js/jquery.validate.min.js"></script>

<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<!--<script type="text/javascript" src="js/jquery.blockUI.js"></script>-->
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.dd.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<!--<script type="text/javascript" src="js/main.js"></script>-->

<style>
.pbwut{
	display: none;
}
body {
	top:0px!important;
}
.widgetcontent {
    background: #fff;
    padding: 15px 12px;
    border: 3px solid #0866c6;
    border-top: 0;
    margin-bottom: 20px;
}
.goog-te-banner-frame {
	margin-top: -50px!important;
}
.error {
	color: #FF0000;
	padding-left:10px;
}
/*.row-fluid .span4 {
    width: 32.6239%;
	margin-left:10px;
}*/
.span4 {
	float:left;
	width:28.5%!important;
	min-height:600px;
	margin-left:1.5%!important;
}
/*.unread showJobs selected{background-color:#cccccc;}*/
table.table tbody tr.selected, table.table tfoot tr.selected { background-color: #808080; }
.selectouter12 {
   	background: none repeat scroll 0 0 #ffffff; border: 1px solid #c9c9c9; float: left;
		height: 32px; line-height: 5px; margin: 0 0 7px; position: relative; width: 271px;
}

.ui-datepicker-calendar td span{
    color: #666; display: block; padding: 2px 8px;
    text-shadow: 1px 1px rgba(255, 255, 255, 0.3);
}
.ui-datepicker-month{ width:33% !important; margin-right:5%;}
.ui-datepicker-year{ width:33% !important;}

form#edit_from select,
form#edit_from input[type="text"],
div.input-append { margin: 0px; }
.dataTables_filter input { height: 20px; }
.input-append .add-on, .input-prepend .add-on {	height: 20px; }
form#edit_from input[type="text"] {	width: 256px; }

</style>

<script type="text/javascript">
var vendor_id = '<?php echo $vendor_id; ?>';

var intRoomHeight = jQuery('.widgetcontent').height();
function validate(){
	
}

jQuery(document).ready(function($){

/*S.A. Lowell: Added this one in */
	jQuery('#group').live('change',function(){	
		var group_id  = jQuery(this).val();
		
		var vendor_id_from_group  = jQuery(this).data('vendor_id_from_group');
		console.log(vendor_id_from_group);
console.log('thatwasit');
		if(group_id=='Add_new_group'){		
			jQuery('#group_span').hide();
			jQuery('#new_group_span').show();
			jQuery('#group_ins_type').val('new');
			jQuery('#new_gruop').val('');
			getinventory2(0,'N',vendor_id_from_group);
		}else{
			jQuery('#group_span').show();
			jQuery('#new_group_span').hide();
			jQuery('#group_ins_type').val('old');
			jQuery('#new_gruop').val('');
			getinventory2(jQuery(this).val(),'N',vendor_id_from_group);
		}
	});
function getinventory2(group, is_edit, vendor_id){	
    states = jQuery.Deferred();	
    jQuery.ajax({
        url:'get_inventory_items.php',
        data: { 
			'group': group,
			'vendor_id': vendor_id
			,'is_edit': is_edit
		},
        dataType: 'jsonp',
        jsonpCallback: 'returnItem'
    });
}

	
	jQuery('#pack_unit_type').msDropDown();
	jQuery('#qty_in_pack_unit_type').msDropDown();
	jQuery('#price_by_weight_unittype').msDropDown();
	
	var msg = '<?php echo $_REQUEST['msg']; ?>';
	if(msg!=""){		
		
		jAlert(msg,'Alert Dialog');
	}
	jQuery('#global_tbl').dataTable({
		"sPaginationType": "full_numbers",
		"aaSorting": [[ 1, "asc" ]],
		"bJQuery": true,
		"fnDrawCallback": function(oSettings) {
		   //  jQuery.uniform.update();
		}
	});
	
	//jQuery("#global_tbl>tbody>tr:first").trigger('click');
	/*setTimeout(function(){
		jQuery("#global_tbl>tbody>tr:first").trigger('click');
	},1000);*/
	//jQuery("#global_tbl>tbody>tr:first").trigger('click');
	jQuery('#addcode').click(function(){
		
		//Simple
		/*jQuery('#status_hn').val('');
		jQuery('#vendor_internal_number_hn').val('');
		jQuery('#name_hn').val('');
		jQuery('#name_hn').attr('readonly', false);
		jQuery('#pack_size_hn').val('');
		jQuery('#ni_brand_hn').val('');
		jQuery('#group_hn').val('');
		jQuery('#qty_in_pack_unit_type_hn').val('');
		jQuery('#pack_unit_type_hn').val('');
		jQuery('#price_by_weight_hn').val('');
		jQuery('#price_by_weight_unittype_hn').val('');*/
		
		jQuery('#code_id').val('');
		jQuery('#name').val('');
		jQuery('#item_i_id').val('');
		jQuery('#d_market').val('');
		jQuery('#vendor_internal_number').val('');
		
		//Extended
		jQuery('#edit_from input,#edit_from select').val('');
		jQuery('#for_edit').hide();
		jQuery('.reset').show();
		jQuery('#status').val('active');
		jQuery("#group").html("<option value=''> - - - Select Item Group - - - </option>");
		jQuery("#inv_item").html("<option value=''>- - - Select Inventory Items - - -</option>");
		jQuery('.selected').removeClass('selected');
		jQuery('#created_on').val('VendorPanel');
		jQuery('#created_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
		jQuery('#created_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
		//jQuery('#name').val(jQuery(this).data('name'));
		jQuery('#group,#inv_item,#name').prop('disabled',false);
		jQuery('#group1_inp').val('<?php echo $_REQUEST['group1']; ?>');
		jQuery('#market_inp').val('<?php echo $_REQUEST['market']; ?>');
		jQuery('#last_on').val('VendorPanel');
		jQuery('#Last_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
		jQuery('#last_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
		jQuery('#barcode_p').show();
		//jQuery('#d_market').attr('disabled', false);
		//jQuery('#group').attr('disabled', false);
		//jQuery('#inv_item').attr('disabled', false);
		jQuery('#name').attr('readonly', false);
		jQuery('#tax_per').hide();
		var dd =  jQuery("#pack_unit_type").msDropdown().data("dd");
    	dd.setIndexByValue('');
		jQuery('#price_by_weight').val('No');
		var dd1 =  jQuery("#qty_in_pack_unit_type").msDropdown().data("dd");
    	dd1.setIndexByValue('');
		
		var dd2 =  jQuery("#price_by_weight_unittype").msDropdown().data("dd");
    	dd2.setIndexByValue('');
		
		jQuery('#taxable').val('');
		jQuery('#tax_type').val('');
		jQuery('#tax_amount').val('');
		jQuery('#taxable').trigger('change');
		
		jQuery('#splitable').val('');
		jQuery('#splitable_price').val('');
		jQuery('#splits').val('');
		jQuery('#splits_minimum').val('');
		jQuery('#splitable').trigger('change');
	});

	jQuery(".cl_order").live('click',function(){

		console.log('market : '+jQuery(this).data('market'));
		//if (jQuery(".ui-tabs-active > a").text()!=" Extended ") {
			
			console.log('market : '+jQuery(this).data('market'));
			get_group(jQuery(this).data('market'),jQuery(this).data('inv_group_id'),false);			
			jQuery('#pack_unit_type').msDropdown().data("dd").destroy();
			jQuery('#qty_in_pack_unit_type').msDropdown().data("dd").destroy();
			jQuery('#price_by_weight_unittype').msDropdown().data("dd").destroy();
    		var image = jQuery(this).data('image');
					
			jQuery('#inv_item').trigger('change');
			jQuery('#barcode_p').hide();
			jQuery('#for_edit').show();			
			jQuery(".gradeX").attr("class","gradeX cl_order");
		    jQuery(this).attr("class","gradeX cl_order selected");
			//callajax(this);
			jQuery('#group_hn').val(jQuery(this).data('inv_group_id'));
			getinventory(jQuery(this).data('inv_group_id'), 'y');
			//jQuery('#name').val(jQuery(this).data('name'));
			jQuery('#status_hn').val(jQuery(this).data('status'));
			jQuery('#vendor_internal_number_hn').val(jQuery(this).data('vendor_internal_number'));
			jQuery('#pack_size_hn').val(jQuery(this).data('pack_size'));
			jQuery('#ni_brand_hn').val(jQuery(this).data('brand'));
			jQuery('#code_id_hn').val(jQuery(this).data('id'));
			jQuery('#code_id').val(jQuery(this).data('id'));
			
			jQuery('#price_by_weight_hn').val(jQuery(this).data('price_by_weight'));
			jQuery('#price_by_weight_unittype_hn').val(jQuery(this).data('price_by_weight_unittype'));
			
			jQuery('#name_hn').val(jQuery(this).data('name'));
			jQuery('#name_hn').attr('readonly', true);
			
			
			jQuery('#pack_unit_type_hn').val(jQuery(this).data('pack_unittype'));	
			jQuery('#qty_in_pack_unit_type_hn').val(jQuery(this).data('qty_in_pack_unittype'));
			
			// jQuery('#ni_taxable').val(jQuery(this).data('taxable').toString().toLowerCase()).trigger('change');
			var item_id = jQuery(this).data('item_id');
			setTimeout(function(){
			jQuery('#inv_item').val(item_id).trigger('change');
			},1000);			
			jQuery('.reset').hide();			    		
			jQuery('#pack_unit_type').msDropDown();
			jQuery('#qty_in_pack_unit_type').msDropDown();
			jQuery('#price_by_weight_unittype').msDropDown();
			//jQuery('#d_market').attr('disabled', true);
			//jQuery('#group').attr('disabled', true);
			//jQuery('#inv_item').attr('disabled', true);

		//}else {

			console.log('CODEID3 : '+jQuery(this).data('id'));
			
			get_group(jQuery(this).data('market'),jQuery(this).data('inv_group_id'),false);			
			jQuery('#pack_unit_type').msDropdown().data("dd").destroy();
			jQuery('#qty_in_pack_unit_type').msDropdown().data("dd").destroy();
			jQuery('#price_by_weight_unittype').msDropdown().data("dd").destroy();
    		var image = jQuery(this).data('image');
			/*if(image!="" && image!=null){
				jQuery('#imagebox').html('<img width="100px;" onerror="this.src=\'images/noimage.png\'"  src="<?php echo API; ?>images/'+image+'">');
				jQuery('#oldimage').val(image);
			}else{
				jQuery('#imagebox').html('');
				jQuery('#oldimage').val(''); 
			}*/
			
			//jQuery('#group').trigger('change');	group		
			jQuery('#inv_item').trigger('change');
			jQuery('#barcode_p').hide();
			jQuery('#for_edit').show();			
			jQuery(".gradeX").attr("class","gradeX cl_order");
		    jQuery(this).attr("class","gradeX cl_order selected");
			//callajax(this);
			jQuery("#d_market").val(jQuery(this).data('market'));
			jQuery('#code_id').val(jQuery(this).data('id'));
			jQuery('#code_id_hn').val(jQuery(this).data('id'));
			jQuery('#group').val(jQuery(this).data('inv_group_id'));
			getinventory(jQuery(this).data('inv_group_id'), 'y');
			jQuery('#name').val(jQuery(this).data('name'));
			jQuery('#status').val(jQuery(this).data('status'));
			jQuery('#inv_color').val(jQuery(this).data('inv_color'));
			jQuery('#inv_size').val(jQuery(this).data('inv_size'));
			jQuery('#inv_type').val(jQuery(this).data('inv_type'));
			jQuery('#vendor_internal_number').val(jQuery(this).data('i_id'));
			jQuery('#item_i_id').val(jQuery(this).data('item_id'));
			jQuery('#pack_size').val(jQuery(this).data('pack_size'));
			jQuery('#qty_in_pack_size').val(jQuery(this).data('qty_in_pack_size'));
			jQuery('#pack_weight').val(jQuery(this).data('pack_weight'));
			
			jQuery('#price_by_weight').val(jQuery(this).data('price_by_weight'));
			jQuery('#price_by_weight_unittype').val(jQuery(this).data('price_by_weight_unittype'));
			
			jQuery('#lead_time').val(jQuery(this).data('lead_time'));
			jQuery('#stock').val(jQuery(this).data('stock'));
			
			jQuery('#taxable').val(jQuery(this).data('taxable'));
			jQuery('#tax_type').val(jQuery(this).data('tax_type'));
			jQuery('#tax_amount').val(jQuery(this).data('tax_amount'));
			jQuery('#taxable').trigger('change');
			
			jQuery('#splitable').val(jQuery(this).data('splitable'));
			jQuery('#splitable_price').val(jQuery(this).data('splitable_price'));
			jQuery('#splits').val(jQuery(this).data('splits'));
			jQuery('#splits_minimum').val(jQuery(this).data('splits_minimum'));
			jQuery('#splitable').trigger('change');
			
			jQuery('#pack_unit_type').val(jQuery(this).data('pack_unittype'));			
			jQuery('#qty_in_pack').val(jQuery(this).data('qty_in_pack'));
			jQuery('#qty_in_pack_unit_type').val(jQuery(this).data('qty_in_pack_unittype'));
			
			jQuery('#tax_percentage').val(jQuery(this).data('tax_percentage'));
			jQuery('#price').val(jQuery(this).data('price'));
			jQuery('#promotion').val(jQuery(this).data('promotion'));
			jQuery('#created_on').val(jQuery(this).data('created_on'));
			jQuery('#vendor_id').val(jQuery(this).data('purchased_from_vendor'));
			jQuery('#vendors').val(jQuery(this).data('purchased_from_vendor_name'));
			jQuery('#purchased_price').val(jQuery(this).data('purchased_price'));
			jQuery('#purchased_last').val(jQuery(this).data('purchased_last'));
			jQuery('#created_datetime').val(jQuery(this).data('created_datetime'));
			jQuery('#created_by').val(jQuery(this).data('created_by'));
			jQuery('#promotion_price').val(jQuery(this).data('promotion_price'));
			jQuery('#last_on').val(jQuery(this).data('last_on'));
			jQuery('#Last_datetime').val(jQuery(this).data('last_datetime'));
			jQuery('#last_by').val(jQuery(this).data('last_by'));
			// jQuery('#ni_taxable').val(jQuery(this).data('taxable').toString().toLowerCase()).trigger('change');
			var item_id = jQuery(this).data('item_id');
			setTimeout(function(){
			jQuery('#inv_item').val(item_id).trigger('change');
			},1000);			
			jQuery('.reset').hide();			    		
			jQuery('#pack_unit_type').msDropDown();
			jQuery('#qty_in_pack_unit_type').msDropDown();
			jQuery('#price_by_weight_unittype').msDropDown();
			//jQuery('#d_market').attr('disabled', true);
			//jQuery('#group').attr('disabled', true);
			//jQuery('#inv_item').attr('disabled', true);
			//jQuery('#name').attr('readonly', true);
		//}
		}); 
		if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
			// jQuery(".uneditable-input").css("height","32px");
			// jQuery("input[type='text']").css("height","32px");
			// jQuery("input[type='select']").css("width","274px");
			// jQuery(".go_search2").css("height","42px");
			
			//jQuery(".select-xlarge").css("height","30px");
			
			/*select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {*/
		}
		jQuery('#purchased_last').datepicker({
			 dateFormat: 'yy-mm-dd',
			 inline:true,
				changeMonth:true,
				changeYear:true
		});
		/*gsdgsd*/
});
jQuery('#group').live('change',function(){	
	var group_id  = jQuery(this).val();
	console.log('group_id : '+group_id);
	if(group_id=='Add_new_group'){		
		jQuery('#group_span').hide();
		jQuery('#new_group_span').show();
		jQuery('#group_ins_type').val('new');
		jQuery('#new_gruop').val('');
		getinventory(0);
	}else{
		jQuery('#group_span').show();
		jQuery('#new_group_span').hide();
		jQuery('#group_ins_type').val('old');
		jQuery('#new_gruop').val('');
		getinventory(jQuery(this).val(), 'y');
	}
});

jQuery('#inv_item').live('change',function(){
/*S.A. Lowell: Start*/
console.log('changefunction');
console.log(jQuery('#pack_unit_type').val());
if(jQuery(this).val()!=''&&jQuery(this).val()!='new_inv_item'&&jQuery(this).val()!='new_inv_item')jQuery('#name').val(jQuery("#inv_item").children("option").filter(":selected").text());
else{jQuery('#name').val('');}
/*
if(jQuery(this).val()!=''&&jQuery(this).val()!='new_inv_item'&&jQuery(this).val()!='new_inv_item')jQuery('#namelabeltochange').html(jQuery("#inv_item").children("option").filter(":selected").text()+': <span style="color:#FF0000;">*</span>');
else{jQuery('#namelabeltochange').html('Name: <span style="color:#FF0000;">*</span>');}*/
/*S.A. Lowell: End*/
	var inv_itm_val = jQuery(this).val();
	if(inv_itm_val=="new_inv_item"){
		jQuery('#drop_span').hide();
		jQuery('#new_span').show();
		jQuery('#inv_item_type').val('new');
		jQuery('#inv_item1').val('');
		var status = jQuery('#status').val();
		var group = jQuery('#group').val();
		jQuery('#new_inv_div,#new_inv_div1').show();
		
		jQuery('#pack_unit_type').msDropdown().data("dd").destroy();
		jQuery('#pack_unit_type').msDropdown();
		jQuery('#qty_in_pack_unit_type').msDropdown().data("dd").destroy();
		jQuery('#qty_in_pack_unit_type').msDropdown();
		jQuery('#price_by_weight_unittype').msDropdown().data("dd").destroy();
		jQuery('#price_by_weight_unittype').msDropdown();
		jQuery('#ni_notes').val('');
		jQuery('#ni_barcode').val('');
		jQuery('#ni_manufacture').val('');
		jQuery('#ni_brand').val('');
		jQuery('#ni_model').val('');
		jQuery('#vendor_internal_number').val('');
		jQuery('#imagebox').html('');
		jQuery('#digital_image_name').val('');
		jQuery('#pack_size').val('');
		jQuery('#qty_in_pack_size').val('');
		jQuery('#pack_weight').val('');
		
		jQuery('#price_by_weight').val('No');
		//jQuery('#price_by_weight_unittype').val('');
		
		jQuery('#lead_time').val('');
		jQuery('#stock').val('');
		
		jQuery('#taxable').val('');
		jQuery('#tax_type').val('');
		jQuery('#tax_amount').val('');
		jQuery('#taxable').trigger('change');
	
		jQuery('#splitable').val('');
		jQuery('#splitable_price').val('');
		jQuery('#splits').val('');
		jQuery('#splits_minimum').val('');
		jQuery('#splitable').trigger('change');
		
		jQuery('#qty_in_pack').val('');
		// jQuery('#ni_taxable').val('');
		jQuery('#price').val('');
		jQuery('#purchased_from_vendor').val('');
	}else{
		jQuery('#drop_span').show();
		jQuery('#new_span').hide();
		jQuery('#inv_item_type').val('old');
		jQuery('#inv_item1').val('');
		jQuery('#new_inv_div,#new_inv_div1').hide();		
	}
console.log(jQuery('#pack_unit_type').val());
});
jQuery('#cancel_btn').live('click',function(){
		jQuery('#drop_span').show();
		jQuery('#new_span').hide();
		jQuery('#inv_item_type').val('old');
		jQuery('#inv_item1').val('');
		jQuery('#inv_item').val('');
});
jQuery('#group_cancel_btn').live('click',function(){
		jQuery('#group_span').show();
		jQuery('#new_group_span').hide();
		jQuery('#group_ins_type').val('old');
		jQuery('#new_gruop').val('');
		jQuery('#group').val('');
});




function getinventory(group, is_edit){	

	states = jQuery.Deferred();	
	jQuery.ajax({
		url:'get_inventory_items.php',
		data: { 
			'group': group,
			'vendor_id': vendor_id,
			'is_edit': is_edit
		},
		dataType: 'json'
		//jsonpCallback: 'returnItem'
	}).done(function(data) {
		console.log('DataData : '+data);
		var group = jQuery('#inv_item');
		group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
		group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
		for(var i=0;i<data.length;i++){
			console.log('data : '+data[i].id);
			group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
		}
		states.resolve();
	});
}

function returnItem(data){
	var group = jQuery('#inv_item');
	group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
	group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
	for(var i=0;i<data.length;i++){
		console.log('data : '+data[i].id);
		group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
	}
	states.resolve();
}

jQuery('#inv_item').live('change',function(){
	if(jQuery('#inv_item').val()!="new_inv_item" && jQuery('#inv_item').val()!=""){
		//jQuery('#name').val(jQuery("#inv_item :selected").text());
		jQuery('#pack_unit_type').val(jQuery("#inv_item :selected").attr('rel'));
console.log('changedhere?');
jQuery('#pack_unit_type').trigger('change');
		jQuery('#barcode').val(jQuery("#inv_item :selected").attr('barcode'));
	}else{
		//jQuery('#name').val('');
		jQuery('#pack_unit_type').val('');
		jQuery('#barcode').val('');
	}
});

jQuery('#ser_go').live('click',function(){
	
  var total_rec = jQuery('#total_rows').val();
	if(total_rec > 499){
		var search_inpt = jQuery('#search_txt1').val();
		if (search_inpt!=null) {
			search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
			if (search_inpt.length > 0) {
				if(search_inpt.length < 3) {
					jAlert('Please enter 3 or more characters');
					return false;
				}else{
					var val = jQuery('#group_id').val();
					var dummy_market = jQuery("#dummy_market").val();
					if(val=="")	val = 'All';
					//window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
					window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();						
				}
			}else{
				jAlert(' Enter value to search');
				return false;
			}
			return false;
		} else{
			jAlert(' Enter value to search');
			return false;
		}
	}else{                                 
		var val = jQuery('#group_id').val();
		var dummy_market = jQuery("#dummy_market").val();
		if(val=="")	val = 'All';
		//window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
		window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
	}
});
jQuery(document).on('change','#dummy_market',function(){
	
	  var market = jQuery(this).val();
	  var vendor = '<?php echo $vendor_id; ?>';
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup_byvendor.php",
		data: { market: market,vendor:vendor}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group_id").html(msg);
		});
	
	});
function get_group(market,group,trigger){

	if(market=='Retail'){
		jQuery('.retails').show();
	}else{
		jQuery('.retails').hide();
	}
	//jQuery('#group').val('').trigger('change');
	jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup.php?field=yes",
		data: { market: market}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group").html(msg);
		  if(group>0){
		  	jQuery('#group').val(group);
		  }
		  //if(trigger){
			jQuery('#group').trigger('change');									
			jQuery('#inv_item').trigger('change');
		  //}	
			//jQuery('#name').val('');
		});
}
function isNumberKey(e){
	
	if(e.which == 46){
        if(jQuery(this).val().indexOf('.') != -1) {
            return false;
        }
    }
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}
</script>
</head>
<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Setup <span class="separator"></span></li>
      <li>eCommerce Items</li>
      <li class="right"> <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
        <ul class="dropdown-menu pull-right skin-color">
          <li><a href="default">Default</a></li>
          <li><a href="navyblue">Navy Blue</a></li>
          <li><a href="palegreen">Pale Green</a></li>
          <li><a href="red">Red</a></li>
          <li><a href="green">Green</a></li>
          <li><a href="brown">Brown</a></li>
        </ul>
      </li>
      <?php require_once("lang_code.php");?>
    </ul>
    <div class="pageheader">
      <div style="float: right;margin-top: 10px;" class="messagehead">
        <p style="float:left;">
          <?php 
					if($_REQUEST['market']!="All" && $_REQUEST['market']!=""){
						$sqlmarket =  " AND Market='".$_REQUEST['market']."'";
					} else{
						$sqlmarket =  " AND Market='All'";
					}
					$query1=mysql_query("SELECT distinct(ig.id) as id, ig.description,count(ii.id) as invs FROM inventory_groups ig
										INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id	
										INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
										where vi.vendor_id='".$vendor_id."'
										$sqlmarket 
										group by ig.id
										ORDER BY ig.description ASC");// or die(mysql_error());
				?>
           <span>                    
			<input type="text" placeholder="Search" value="<?php echo $_REQUEST['search_txt1']; ?>" name="search_txt1" id="search_txt1" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;">
		    </span>
          <select id="group_id" name="search_drop" onChange="javascript:jQuery('#total_rows').val(jQuery(this).find('option:selected').attr('rel'))" class="input-large search-choice" style=" width:170px; float:right; height:42px; margin:0px 10px 0 0;">
            <option value="all">- - - Select Item Group - - -</option>
            <?php
							while($row= mysql_fetch_array($query1)){
								if($row['id']==$_REQUEST['group1']){
									$selected ="selected='selected'";
									$total_count = $row['invs'];
								}else{
									$selected ="";
								}
						?>
            <option <?php echo $selected; ?> rel="<?php echo $row['invs']; ?>" value="<?php echo $row['id']; ?>"><?php echo $row['description']; ?></option>
            <?php } ?>
          </select>
          <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
            
            <?php echo jRender_inventory_market_combo('dummy_market',$vendor_id, 'dummy-market', "float:right; width:170px; height:42px;padding: 8px 8px;margin:0 10px 0 0;"); ?>
            <!--<select class="dummy-market"  id="dummy_market" style="float:right; width:170px; height:45px;padding: 8px 8px;margin:0 10px 0 0;" name="market">
             <option value="">- - - Select Market - - -</option>
              <option <?php if($_REQUEST['market']=='All'){ echo 'selected';} ?> value="All">All</option>
              <option <?php if($_REQUEST['market']=='Bar'){ echo 'selected';} ?> value="Bar">Bar</option>
              <option <?php if($_REQUEST['market']=='Hotel'){ echo 'selected';} ?> value="Hotel">Hotel</option>
              <option <?php if($_REQUEST['market']=='Restaurant'){ echo 'selected';} ?> value="Restaurant">Restaurant</option>
              <option <?php if($_REQUEST['market']=='Retail'){ echo 'selected'; } ?> value="Retail">Retail</option>
              <option <?php if($_REQUEST['market']=='Other'){ echo 'selected'; } ?> value="Other">Other</option>
            </select>-->
          
        </p>
        <button id="ser_go" style="float:left" class="btn btn-primary btn-large">Go</button>
        <!--<form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
        <button id="addcode" style="margin-left:10px;" class="btn btn-success btn-large">Add</button>
      </div>
      <div class="pageicon"><span class="iconfa-cog"></span></div>
      <div class="pagetitle">
        <h5>Items offered and sold via eCommerce</h5>
        <h1>Setup - eCommerce Items</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <div class="span8" style="width:70%;float:left; overflow:auto;">
            <div class="clearfix">
              <h4 class="widgettitle">Vendor Items</h4>
            </div>
            <div class="widgetcontent">
            <table class="table table-bordered responsive" id="global_tbl">
              <colgroup>
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              </colgroup>
              <thead>
                <tr>
                  <th class="head1">S</th>	
                  <th class="head0">Image</th>
                  <th class="head0">Item Description</th>
				  <th class="head1">Group</th>
                  <th class="head1">Pack Unit Type</th>
				  <th class="head1">Qty In Pack</th>
                  <th class="head0">Qty In Pack Unit Type </th>
                  <th class="head0">Qty In Pack Size</th>
                  <th class="head1">Tax</th>
                  <th class="head0">Price</th>
                  <th class="head1">Promo</th>
                  <th class="head0">Promo Price</th>
                  <th class="head1" style="text-align:center;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  while($row = mysql_fetch_array($resultJobs)){
				 if($selected==0){
							$selected=$row["id"];
										}
				   ?>
                <tr class="<?php if($selected==$row["id"]){ echo "selected-row";} ?> gradeX cl_order" id="<?php echo $row["id"];?>"
                      	data-id ="<?php echo $row["id"];?>"
                        data-name ='<?php echo $row['description']; ?>'
                        data-status = "<?php echo $row['status']; ?>"
						data-brand = "<?php echo $row['brand']; ?>"
                        data-image = "<?php echo $row['image']; ?>"
                        data-pack_size="<?php echo $row['pack_size']; ?>" 
                        data-pack_unittype="<?php echo $row['pack_unittype_id']; ?>"
                        data-qty_in_pack="<?php echo $row['qty_in_pack']; ?>" 
                        data-qty_in_pack_unittype = "<?php echo $row['qty_in_pack_unittype_id']; ?>"
                        data-tax_percentage="<?php echo $row['tax_percentage']; ?>"
                        data-price="<?php echo $row['price']; ?>" 
                        data-promotion="<?php echo $row['promotion']; ?>"
                        data-promotion_price="<?php echo $row['promotion_price']; ?>"
                        data-inv_group_id="<?php echo $row['inv_group_id']; ?>"
                        data-item_id="<?php echo $row['item_id']; ?>"
						data-i_id="<?php echo $row['i_id']; ?>"
                        data-purchased_price="<?php echo $row['purchased_price']; ?>"
                        data-purchased_from_vendor="<?php echo $row['purchased_from_vendor']; ?>"
                        data-purchased_from_vendor_name="<?php echo $row['purchased_from_vendor_name']; ?>"
                        data-purchased_last="<?php echo ($row['purchased_last'] != '0000-00-00') ? $row['purchased_last'] : ''; ?>"
                        data-created_on="<?php echo $row['created_on']; ?>"
                        data-created_datetime="<?php echo $row['created_datetime']; ?>"
                        data-created_by="<?php echo get_empmaster($row['created_by']); ?>"
                        data-last_on="<?php echo $row['last_on']; ?>"
                        data-last_datetime="<?php echo $row['last_datetime']; ?>"
                        data-last_by="<?php echo get_empmaster($row['last_by']); ?>"                        
                        data-vendor_internal_number="<?php echo $row['vendor_internal_number']; ?>"
                        data-taxable ="<?php echo $row['taxable']; ?>"
                        data-inv_color ="<?php echo $row['inv_color']; ?>"
                        data-inv_size ="<?php echo $row['inv_size']; ?>"
                        data-inv_type ="<?php echo $row['inv_type']; ?>"
                        data-market="<?php echo $row['Market']; ?>"
                        data-qty_in_pack_size="<?php echo $row['qty_in_pack_size']; ?>"
                        data-pack_weight="<?php echo $row['pack_weight']; ?>"
                        
                        data-price_by_weight="<?php echo $row['price_by_weight']; ?>"
                        data-price_by_weight_unittype="<?php echo $row['price_by_weight_unittype']; ?>"
                        
                        data-lead_time="<?php echo $row['lead_time']; ?>"
                        data-stock="<?php echo $row['stock']; ?>"
                        
                        data-taxable="<?php echo $row['taxable']; ?>"
                        data-tax_type="<?php echo $row['tax_type']; ?>"
                        data-tax_amount="<?php echo $row['tax_amount']; ?>" 
                        
                        data-splitable="<?php echo $row['splitable']; ?>" 
                        data-splitable_price="<?php echo $row['splitable_price']; ?>" 
                        data-splits="<?php echo $row['splits']; ?>" 
                        data-splits_minimum="<?php echo $row['splits_minimum']; ?>" 
                        >
                        
                  <td><?php echo status_img($row['status']); ?></td>	
                  <td style="padding: 4px 6px 2px;"><img onerror="this.src='images/noimage.png'" src="<?php echo ($row['image']!='')? APIPHP."images/".$row['image']:'images/noimage.png'; ?>" style="width:50px; height:50px; " alt="No Image"/></td>                  
                  <td><b><?php echo $row['description']; ?></b></td>
				  <td><?php echo $row['group_name']; ?></td>
				  <td><?php echo $row['pack_unittype']; ?></td>
				  <td class="right"><?php echo $row['qty_in_pack']; ?></td>
                  <td><?php echo $row['qty_in_pack_unittype']; ?></td>
				  <td class="right"><?php echo $row['pack_size']; ?></td>
                  <td><?php echo $row['tax_percentage']; ?></td>
                  <td style="text-align:right;"><?php echo $c_symbol.$row['price']; ?></td>
                  <td><?php if($row['promotion'] != "") echo $row['promotion']; else echo "-"; ?></td>
                  <td style="text-align:right;"><?php echo $c_symbol.$row['promotion_price']; ?></td>
                  <td class="center" style="vertical-align:middle;"><a href="#"><img src="images/edit.png"></a> </td>
                </tr>
                <?php
				$i++;	  }
					  ?>
              </tbody>
            </table></div>
          </div>
          <!--end span8-->
          <div class="span4">
            
            <div class="widgetcontent" style="padding: 0px !important;">
            	<div class="clearfix">
              <h4 class="widgettitle">Add/Edit Item</h4>
            </div>
				    <div class="widgetbox" id="fdetail" style="padding-left: 5%;">
					    <form id="edit_from234" name="frm234" action="" method="post" class="edit_from" >
					      <input type="hidden" name="code_id" value="" id="code_id">
								<input type="hidden" name="item_i_id" value="" id="item_i_id">
								<p>
									<label>Status:<span style="color:#FF0000;">*</span></label>
									<span class="field">
									<select name="status" id="status" style="width:284px;">
										<option value="" > - - - Select Status - - -</option>
											<option selected="selected" value="active">Active</option>
											<option value="inactive" >Inactive</option>
									</select>
									</span> 
								</p>
								
								 <p id="barcode_p">
			                 		<label style="width:107px">Barcode:</label>
			                        <span class="field input-append" style="margin:0;"> 
			                        <input style="width: 243px;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="" />
			                        <input type="hidden" id="barcode_valid" value="">
			                        <span class="add-on" > 
			                        
			                        <a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search" ></a>
			                        </span>
			                        <span style="display:none;" id="ture_barcode" class="add-on1">
			                        	<img style="height:20px; width:20px; margin-left:5px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
			                        </span>
			                        </span>
			                     </p>

								<p>
									<label>Market:<span style="color:#FF0000;">*</span></label>
									<span class="field">
										<select id="d_market" onChange="get_group(this.value,'',true)" style="width:284px;" name="d_market">
											<option value="">- - - Select Market - - -</option>
											<option value="All">All</option>
											<option value="Bar">Bar</option>
											<!--<option value="Hotel">Hotel</option>-->
											<option value="Restaurant">Restaurant</option>
											<option value="Retail">Retail</option>
											<!--<option value="Other">Other</option>-->
										</select>
									</span>
								</p>
								<p>
									<label>Group:<span style="color:#FF0000;">*</span></label>
									<span class="field"></span>
									<span class="field" id="group_span">
										<?php $query1=mysql_query("SELECT id,description from inventory_groups order by description"); ?>
										<select name="group" id="group" style="width:284px;">
											<option value="" >- - - Select Group - - -</option>
											<?php while($row_g = mysql_fetch_array($query1)){?>
											<option value="<?php echo $row_g['id']; ?>" ><?php echo $row_g['description']; ?></option>
											<?php } ?>
										</select>
									</span>
									<input type="hidden" name="group_ins_type" id="group_ins_type" value="old">
									<span class="field" id="new_group_span"  style="display:none;">
										<input type="text" class="input-xlarge" id="new_gruop" value="" name="new_gruop" >
										<span style="/*vertical-align:top;margin-left: -25px;*/" id="group_cancel_btn"><i style="margin-top: -3px;" class="icon-remove"></i></span>
									</span>
								</p>
<!--S.A. Lowell: Removed display:none-->
								<!--<p style="display:none">-->
								<p>
<!--S.A. Lowell: Changed text here-->
									<!--<label>Item Description:<span style="color:#FF0000;">*</span></label>-->
									<label>Inventory Items:<span style="color:#FF0000;">*</span></label>
									<span class="field" id="drop_span">
									<select id="inv_item" name="inv_item" style="width:284px;">
										<option value="" >- - -Select Inventory Item - - -</option>
										<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>
									</select>
									</span>
									<input type="hidden" name="inv_item_type" id="inv_item_type" value="old">
									<span class="field" id="new_span" style="display:none;">
										<input type="text" class="input-xlarge" id="inv_item1" value="" name="inv_item_new" >
										<span style="/*vertical-align:top;  margin-left: -25px;*/" id="cancel_btn"><i style="margin-top: -3px;" class="icon-remove"></i></span>
									</span> 
								</p>
<!--S.A. Lowell: Changed the following input-->		
					      <!--<p>                  
					        <label>Item Description:<span style="color:#FF0000;">*</span></label>
					        <span class="field">
										<textarea class="input-xlarge" id="name" value="" name="name"></textarea>
					        </span>
								</p>-->
	<p>                  
		<label id="namelabeltochange">Name:<span style="color:#FF0000;">*</span></label>
		<span class="field">
			<input type="text" class="input-xlarge" id="name" value="" name="name">
		</span>
	</p>


									<p style="width:100%;">                  
					                    <label>Pack Unit Type:<span style="color:#FF0000;">*</span> (The way Item is Packaged)</label>                    
					                    <div class="field" style="float: left;  margin-top: -15px;   padding-bottom: 8px;  width: 100% !important;">
					                    <div style="width:284px" class="selectouter12 select_w3">
					                    <select name="pack_unit_type" id="pack_unit_type" style="width:284px;" >
					                    	<option value="" >- - - Select Unit Type - - -</option>

					                    	<?php
											$res_unit_type = mysql_query($unittype);
											
											$opt_lbl = '';
											while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
											?>
											 <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
					                      <?php } ?>
					                    </select>
					                    </div>
					                    </div>                    
					                </p>

									<p>
					                    <label>Qty In Pack:<span style="color:#FF0000;">*</span></label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="qty_in_pack" value="" name="qty_in_pack">
					                    </span>
									</p>


									<p>
					                    <label>Qty In Pack Unit Type: <span style="color:#FF0000;">*</span></label>
					                    <div class="field" style="float: left;  margin-top: -15px;   padding-bottom: 8px;  width: 100% !important;">
					                    <div style="width:284px" class="selectouter12 select_w3">
					                    <select name="qty_in_pack_unit_type" id="qty_in_pack_unit_type" style="width:284px;" >
					                      <option value="" >- - - Select Unit Type - - -</option>
					                      <?php
											$res_unit_type = mysql_query($unittype);
											
											$opt_lbl = '';
											while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
											?>
					                        
					                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
					                      <?php } ?>
					                    </select>
					                   </div>
					                   </div> 
									</p>

									<p>
					                    <label>Qty In Pack Size:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="qty_in_pack_size" value="" name="qty_in_pack_size">
					                    </span> 
									</p>

					                  <div id="new_inv_div" style="display:none">
					                      <!--<p>                  
					                        <label>Notes:</label>
					                        <span class="field">
					                        <input type="text" class="input-xlarge" id="ni_notes" value="" name="ni_notes">
					                        </span> </p>-->
					                      
					                      <p style="display: none;">                  
					                        <label>Barcode:</label>
					                        <span class="field">
					                        <input type="text" class="input-xlarge" id="ni_barcode" value="" name="ni_barcode">
					                        </span> </p>
					                      
					                      <p>                  
					                        <label>Manufacturer:</label>
					                        <span class="field">
					                        <input type="text" class="input-xlarge" id="ni_manufacture" value="" name="ni_manufacture">
					                        </span> </p>
					                      
					                      <p>                  
					                        <label>Brand:</label>
					                        <span class="field">
					                        <input type="text" class="input-xlarge" id="ni_brand" value="" name="ni_brand">
					                        </span> </p>
					                     
					                      <!--<p>                  
					                        <label>Model Number:</label>
					                        <span class="field">
					                        <input type="text" class="input-xlarge" id="ni_model" value="" name="ni_model">
					                        </span> </p>-->
					                      
					                      <p>                  
					                        <label>Image:</label>
					                        <span class="field">
																		<span id="imagebox"> </span>
																		<span class="field" style="margin:0; width:100%; float:left;">
																			<input type="hidden" value="" id="upc_search_image">
																			<input type="hidden" name="oldimage" id="oldimage" value="">
																			<input type="hidden" name="digital_image_name" id="digital_image_name" value="">
																			<input type="hidden" name="digital_image_delete" id="digital_image_delete" value="N">
																			<a data-target="#imageModal" href="upload_inv_item_img.php" role="button" class="btn btn-primary" data-toggle="modal" id="imageLink" style="padding:3px  ">Upload Images</a>
																		</span>
					                        </span> </p> 
					                  </div>  
					                 

					                
									




									<p>
										<label>Price By Weight:</label>
										<span class="field">
											<select name="price_by_weight" id="price_by_weight" style="width: 284px;">
												<option value="">--- Select Price by Weight ---</option>
												<?php
													$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'price_by_weight'");
													while ($row = mysql_fetch_row($type)) {
														foreach(explode("','",substr($row[1],6,-2)) as $option) {
															$selected = ($price_by_weight == $option)? 'selected' : '';
															echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
														}
													}
												?>
											</select>
										</span>
									</p>
									<span class="pbwut">
									<p>
					                    <label>Price By Weight Unit Type:</label>
					                    <div class="field" style="float: left;  margin-top: -15px;   padding-bottom: 8px;  width: 100% !important;">
					                    <div style="width:284px" class="selectouter12 select_w3">
					                    <select name="price_by_weight_unittype" id="price_by_weight_unittype" style="width:284px;" >
					                      <option value="" >- - - Select Unit Type - - -</option>
					                      <?php
											$res_unit_type = mysql_query($unittype);

											$opt_lbl = '';
											while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
											if($row_unit_type['conversion_group'] == 'weight'){
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
											?>
					                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
											<?php } } ?>
					                    </select>
					                   </div>
					                   </div> 
									</p>
									</span>
									

									<p>
					                    <label>Price:<span style="color:#FF0000;">*</span></label>
					                    <span class="field">
					                    <input type="text" onBlur="fix(this.value,'price')" onKeyPress="javascript:if(event.keyCode==13){fix(this.value,'price');return false;}" class="input-xlarge" id="price" value="" name="price">
					                    </span> 
									</p>

									<p>
					                  <label>Lead Time:</label>
					                  <span class="field">
					                  	<input type="text" class="input-xlarge" id="lead_time" value="" name="lead_time">
					                  </span> 
					                </p>

					                <p>
										<label>Stock:</label>
										<span class="field">
											<select name="stock" id="stock" style="width: 284px;">
												<option value="">--- Select Stock ---</option>
												<?php
echo '<option value="Yes">Yes</option><option value="No" selected="selected">No</option>';

/*S.A. Lowell: removed below added above*/
/*
														$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'stock'");
														while ($row = mysql_fetch_row($type)) {
															foreach(explode("','",substr($row[1],6,-2)) as $option) {
																$selected = ($stock == $option)? 'selected' : '';
																echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
															}
														}
*/
												?>
											</select>
										</span>
									</p>

									 <p>
				                        <label>Taxable:</label>
				                        <span class="field">
				                        	<select name="taxable" id="taxable" style="width: 284px;">
		                                        <option value="">--- Select Taxable ---</option>
		                                        <?php
echo '<option value="Yes">Yes</option><option value="No" selected="selected">No</option>';

/*S.A. Lowell: removed below added above*/
/*
		                                            $type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'taxable'");
		                                            while ($row = mysql_fetch_row($type)) {
		                                                foreach(explode("','",substr($row[1],6,-2)) as $option) {
		                                                    $selected = ($taxable == $option)? 'selected' : '';
															echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
		                                                }
		                                            }
*/
		                                        ?>
				                            </select>
				                        </span>
					                    </p>

					                    <p class="vi_taxable">
					                        <label>Tax Type:</label>
					                        <span class="field">
					                        	<select name="tax_type" id="tax_type" style="width: 284px;">
					                                        <option value="">--- Select Tax Type ---</option>
					                                        <?php
					                                            $type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'tax_type'");
					                                            while ($row = mysql_fetch_row($type)) {
					                                                foreach(explode("','",substr($row[1],6,-2)) as $option) {
					                                                    $selected = ($tax_type == $option)? 'selected' : '';
																		echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
					                                                }
					                                            }
					                                        ?>
					                                    </select>
					                        </span>
					                    </p>

					                    <p class="vi_taxable">
					                    <label>Tax Amount:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="tax_amount" value="" name="tax_amount">
					                    </span> </p>

					                <p>
					                    <label>Promotion:</label>
					                    <span class="field">
																<select id="promotion" style="width: 284px;">
																	<option value="">--- Select Promotion ---</option>
																	<option value="Yes">Yes</option>
																	<option value="No">No</option>
																</select>
					                    </span> 
					                </p>

					                <p>
					                    <label>Promotion Price:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="promotion_price" value="" name="promotion_price">
					                    </span> 
					                </p>


									

										
									
									
									
									
								
										
					                  <!--<p>
					                    <label>Qty In Pack:<span style="color:#FF0000;">*</span></label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="qty_in_pack" value="" name="qty_in_pack">
					                    </span> </p>-->

									<div id="tax_per" style="display:none;"> 
					                  	<p>                  
					                  <!-- <p>
					                        <label>Taxable:<span style="color:#FF0000;">*</span></label>
					                        <span class="field">
					                        <select onChange="javascript:if(this.value=='yes'){jQuery('#tax_per').show();}else{jQuery('#tax_per').hide();}" style="width:284px;" id="ni_taxable" name="ni_taxable">
					                        <option value="">- - - Select Taxable - - -</option>
					                        <option value="yes">Yes</option>
					                        <option value="no">No</option>
					                        </select>
					                        </span> </p> -->
					                       
					                    <label>Tax Percentage:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="tax_percentage" value="" name="tax_percentage">
					                    </span> </p>
									</div>
					                    
					                
					                
					                    
					                    <!--<p>
					                    <label>Lead Time:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="lead_time" value="" name="lead_time">
					                    </span> </p>-->
					                    
					                    
					                    
					                   


					                    
					                  <p>
					                  <p class="retails" style="display:none;">
					                    <label>Color:</label>
					                    <span class="field">
					                    <select name="inv_color" id="inv_color" style="width:284px;" >
					                    	<option value="">- - -Select Color- - -</option>
					                        <?php $cquery = mysql_query("select id,code from inventory_items_color");
												while($cres = mysql_fetch_array($cquery)){ ?>
					                            	<option value="<?php echo $cres['id']; ?>"><?php echo $cres['code']; ?></option>
					                            <?php } ?>                        
					                    </select>                    
					                    </span> 
					                  </p>
					                  <p class="retails" style="display:none;">
					                    <label>Size:</label>
					                    <span class="field">
					                    <select name="inv_size" id="inv_size" style="width:284px;" >
					                    	<option value="">- - -Select Size- - -</option>
					                        <?php $squery = mysql_query("select id,code from inventory_items_size");
												while($sres = mysql_fetch_array($squery)){ ?>
					                            	<option value="<?php echo $sres['id']; ?>"><?php echo $sres['code']; ?></option>
					                            <?php } ?>                        
					                    </select>
					                    </span> 
					                  </p>
					                  <p class="retails" style="display:none;">
					                    <label>Type:</label>
					                    <span class="field">
					                    <select name="inv_type" id="inv_type" style="width:284px;" >
					                    	<option value="">- - -Select Type- - -</option>
					                        <?php $tquery = mysql_query("select id,code from inventory_items_type");
												while($tres = mysql_fetch_array($tquery)){ ?>
					                            	<option value="<?php echo $tres['id']; ?>"><?php echo $tres['code']; ?></option>
					                            <?php } ?>                        
					                    </select>                     
					                    </span> 
					                  </p>
					                  
					                  	<!-- Start Split Information -->
										<p><strong>Split Information </strong> </p>
					                    
					                    <p>
					                        <label>Splitable:</label>
					                        <span class="field">
					                        	<select name="splitable" id="splitable" style="width: 284px;">
					                                <option value="">--- Select Splitable ---</option>
					                                <?php
echo '<option value="Yes">Yes</option><option value="No" selected="selected">No</option>';
/*S.A. Lowell: removed this PHP and replaced with the yes and no just above this comment.*/
/*
					                                    $type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'splitable'");
					                                    while ($row = mysql_fetch_row($type)) {
					                                        foreach(explode("','",substr($row[1],6,-2)) as $option) {
					                                            $selected = ($splitable == $option)? 'selected' : '';
					                                            echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
					                                        }
					                                    }
*/
					                                ?>
					                            </select>
					                        </span>
					                    </p>

					                    <p class="vi_splitable">
					                        <label>Splitable Price:</label>
					                        <span class="field">
					                        	<input type="text" class="input-xlarge" id="splitable_price" value="" name="splitable_price" />
					                        </span>
					                    </p>
					                    <p class="vi_splitable">
					                        <label>Splits:</label>
					                        <span class="field">
					                        	<input type="text" class="input-xlarge" id="splits" value="" name="splits" />
					                        </span>
					                    </p>
					                    <p class="vi_splitable">
					                        <label>Splits Minimum:</label>
					                        <span class="field">
					                        	<input type="text" class="input-xlarge" id="splits_minimum" value="" name="splits_minimum" />
					                        </span>
					                    </p>

					                    <p><strong>Shipping Information</strong></p>

					                    <p>
<!--S.A. Lowell: Pack Size no longer required, changed HTML-->
						                    <label>Pack Size: (in inches)</label>
						                    <!--<label>Pack Size:<span style="color:#FF0000;">*</span> (in inches)</label>-->
						                    <span class="field">
						                    <input type="text" class="input-xlarge" id="pack_size" value="" name="pack_size">
						                    <?php /*<select class="input-xlarge" id="pack_size" name="pack_size">
						                    	<option value="">--- Please select Pack Size ---</option>
						                    	<?php 
						                    		$sql_packsizes = mysql_query("SELECT * FROM inventory_items_size");
						                    		while ($row = mysql_fetch_array($sql_packsizes)) {
						                    			echo "<option value=".$row['code'].">".$row['description']."</option>";
						                    		}
						                    	?>
						                    </select>*/ ?>
						                    </span> 
										</p>

										<p>
						                    <label>Pack Weight: (in pounds)</label>
						                    <span class="field">
						                    <input type="text" class="input-xlarge" id="pack_weight" value="" name="pack_weight" onKeyPress="return isNumberKey(event)">
						                    </span> 
										</p>


					                    
					                    
					                    <!-- End Split Information -->
					                  
					                   <p><strong> Supplier Information </strong> </p>
					                  
					                    <label>Purchase From:</label>
					                    <span class="field">                    
					                        <div id="clientsearch" class="input-append">
					                            <input autocomplete="off" name="vendors" onKeyDown="javascript:if(event.keyCode==13){getvendor1(2);return false;}"  type="text" id="vendors" tabindex="0"  style="width:243px;"  />
					                            <span class="add-on" > <a href="" rel= 'client' data-toggle="modal" data-target="#filter_modal" data-refresh="true" class="icon-search" style="position: relative;"> </a> </span>
					                            <input name="purchased_from_vendor" type="hidden"   value=" "  id="vendor_id" />
					                          </div>
					                    </span> 
					                  <p>

					                  <p>
					                    <label>Purchase Price:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="purchased_price" value="" name="purchased_price">
					                    </span> </p>
					                  <p>
					                  <p>
					                    <label>Last Purchase:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="purchased_last" value="" name="purchased_last">
					                    </span> </p>

					                   <!-- START RESOURCES -->					                  
					                   <p><strong> Resources </strong> </p>

					                <!--    <p id="barcode_p">
					                 		<label style="width:107px">Barcode:</label>
					                        <span class="field input-append" style="margin:0;"> 
					                        <input style="width: 228px;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="" />
					                        <input type="hidden" id="barcode_valid" value="">
					                        <span class="add-on" > 
					                        
					                        <a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search" ></a>
					                        </span>
					                        <span style="display:none;" id="ture_barcode" class="add-on1">
					                        	<img style="height:20px; width:20px; margin-left:5px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
					                        </span>
					                        </span>
					                     </p> -->

					                  

					                    <!--<p>
					                    <label>Manufacturer:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="resources_manufacturer" value="" name="resources_manufacturer">
					                    </span> </p>-->

					                    <p>                  
				                        <label>Manufacturer:</label>
				                        <span class="field">
				                        <input type="text" class="input-xlarge" id="ni_manufacture" value="" name="ni_manufacture">
				                        </span> </p>

					                    <p>                  
				                        <label>Model Number:</label>
				                        <span class="field">
				                        <input type="text" class="input-xlarge" id="ni_model" value="" name="ni_model">
				                        </span> </p>

					                    <p>                  
				                        <label>Notes:</label>
				                        <span class="field">
				                        <input type="text" class="input-xlarge" id="ni_notes" value="" name="ni_notes">
				                        </span> </p>

					                    

					                    

					                    <!--<p>
					                    <label>Qty In Pack Unit Type:<span style="color:#FF0000;">*</span></label>
					                    <div class="field" style="float: left;  margin-top: -15px;   padding-bottom: 8px;  width: 100% !important;">
					                    <div class="selectouter12 select_w3">
					                    <select name="qty_in_pack_unit_type" id="qty_in_pack_unit_type" style="width:284px;" >
					                      <option value="" >- - - Select Unit Type - - -</option>
					                      <?php
											$res_unit_type = mysql_query($unittype);
											
											$opt_lbl = '';
											while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
											?>
					                        
					                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
					                      <?php } ?>
					                    </select>
					                   </div>
					                   </div> </p>-->

					                  
					                <!--   <p>
					                    <label>Qty In Pack Size:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="qty_in_pack_size" value="" name="qty_in_pack_size">
					                    </span> 
														</p> -->
														
														<p style="display:none;">
															<label>Taxable:<span style="color:#FF0000;">*</span></label>
															<span class="field">
															<select onChange="javascript:if(this.value=='yes'){jQuery('#tax_per').show();}else{jQuery('#tax_per').hide();}" style="width:284px;" id="ni_taxable" name="ni_taxable">
															<option value="">- - - Select Taxable - - -</option>
															<option value="yes">Yes</option>
															<option value="no">No</option>
															</select>
															</span> 
														</p>

									<p><strong>Activity Information</strong></p>

					                  <p>
					                    <label>Internal Number:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="vendor_internal_number" value="" name="vendor_internal_number">
					                    <?php /*<select class="input-xlarge" id="vendor_internal_number" name="vendor_internal_number">
					                    	<option>- - - Please select Internal Number - - -</option>
					                    	<?php
					                    		$tmpquery = mysql_query("SELECT id, name from vendors");
												while ($vend = mysql_fetch_array($tmpquery)) {
													echo "<option value='".$vend['id']."'>".$vend['name']."</option>";
												}

					                    	?>
					                    </select> */?>
					                    </span> </p>

					                  <p>
					                    <label>Created On:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" readonly id="created_on" value="VendorPanel " name="created_on">
					                    </span> </p>
					                  <p>
					                    <label>Created By:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="created_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="created_by">
					                    </span> </p>
					                  <p>
					                    <label>Created Date & Time:</label>
					                    <span class="field">
					                    <input type="text" class="input-xlarge" id="created_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="created_datetime">
					                    </span> </p>
					                  <div id="for_edit" style="display:none;">
					                    <p>
					                      <label>Last On:</label>
					                      <span class="field">
					                      <input type="text" class="input-xlarge" id="last_on" value="VendorPanel" readonly name="last_on">
					                      </span> </p>
					                    <p>
					                      <label>Last By:</label>
					                      <span class="field">
					                      <input type="text" class="input-xlarge" id="last_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="last_by">
					                      </span> </p>
					                    <p>
					                      <label>Last Date & Time:</label>
					                      <span class="field">
					                      <input type="text" class="input-xlarge" id="Last_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="last_datetime">
					                      </span> 
															</p>

					                    <input type="hidden" name="hidden_unit_type">
					                  </div>
					                  <button class="btn btn-primary" id="sub_form" name="Submit" value="Submit"> Submit</button>
					                  <button class="btn btn-primary reset" type="reset">Reset</button>
					                  </p>
					                  <input type="hidden" id="group1_inp" name="group1_inp" value="<?php echo $_REQUEST['group1']; ?>" >
					                  <input type="hidden" id="market_inp" name="market_inp" value="<?php echo $_REQUEST['market']; ?>" >
					                </form>
					              </div>

				    </div>
          </div>
          <!--end span4-->
        </div>
        <!--row-fluid-->
        <?php include_once 'require/footer.php';?>
        <!--footer-->
        <script>
jQuery(document).ready(function(){
	jQuery('#taxable').on('change', function(){
		if(jQuery('#taxable option:selected').val().toString().toLowerCase() == 'yes') {
			jQuery('.vi_taxable').show();
		} else {
			jQuery('.vi_taxable').hide();
		}
	});
	
	jQuery('#splitable').on('change', function(){
		if(jQuery('#splitable option:selected').val().toString().toLowerCase() == 'yes') {
			jQuery('.vi_splitable').show();
		} else {
			jQuery('.vi_splitable').hide();
		}
	});
	
	jQuery('#price_by_weight').val('No');
	
	jQuery('#taxable').trigger('change');
	jQuery('#splitable').trigger('change');
});

function isNumberKey(e){
	
	if(e.which == 46){
        if(jQuery(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
	
	//var charCode = (evt.which) ? evt.which : event.keyCode;
    //return !(charCode > 31 && (charCode < 48 || charCode > 57));
}

function getbarcode(val){
var search_val = jQuery("#barcode").val();
	
	if(search_val!=""){
	if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
		jConfirm('Invalid Barcode Number!', 'Alert!', function(r) {
		//jQuery('#barcode').focus();		     
		});
		jQuery('#popup_cancel').remove();
		return false;
  }
	if( search_val.length<6){
		jConfirm('Please enter a minimum of 6 digit number only!', 'Alert!', function(r) {
			//jQuery('#barcode').focus();		     
			});
			jQuery('#popup_cancel').remove();
			return false;	
	}
	var length ="";
	length = search_val.length;
	/*var lp = 12-length;
	for(var j=0;j<lp;j++){
		search_val = "0"+search_val;
	}*/
	if(length<=8){
		var lst_two = search_val.substr(length-2,2);
		var ser_val = search_val.substr(0,length-2);
		var ser_length = ser_val.length
		var lp = 7-ser_length;
		for(var j=0;j<lp;j++){
			ser_val = "0"+ser_val;
		}
	
		var lst_two = "0000"+lst_two;
		var search_val = ser_val+""+lst_two;
	}
	jQuery('#barcode').val(search_val); 		
	jQuery.ajax({
		url:'search_fectual_barcode.php',
		type:'POST',
		data:{
			search_val:search_val,
			'vendor_id': '<?php echo $vendor_id; ?>'
		},
		success:function(data){
			//jQuery('#modalcontent').html(data);
			if(data){
				if(data=="b_found"){
					jAlert('This barcode already in use!','Alert Dialog');	
					jQuery('#inv_item').val('').trigger('change');				
					jQuery('#ni_barcode').val('');
					jQuery('#inv_item1').val('').trigger('change');
					jQuery('#ture_barcode').hide();
					jQuery('#ni_notes').val("");				
					jQuery('#imagebox').html('');
					jQuery('#upc_search_image').val('');
					jQuery('#ture_barcode').hide();
					jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				}else{
				
					jQuery('#inv_item').val('new_inv_item').trigger('change');
					jQuery('#inv_item1').val(data[0]).trigger('change');				
				
					// alert(search_val);
					jQuery('#barcode').val(search_val);
					var data = data.split('^');				
					jQuery('#ni_notes').val(data[1]);
					if(data[2]!=""){
					jQuery('#imagebox').html('<img src="'+data[2]+'" width="100px;" style="padding-bottom:5px;">');
					jQuery('#upc_search_image').val(data[2]);
					}else{
					jQuery('#smallImagebox').html('');
					jQuery('#upc_search_image').val('');				
					}
					jQuery('#ture_barcode').show();	
					jQuery('#ni_barcode').val(search_val).attr('readonly',true);
				}
			}else{			
				
				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				jQuery('#inv_item').val('').trigger('change');
				// jQuery('#ni_barcode').val('');
				jQuery('#inv_item1').val('').trigger('change');
				jQuery('#ture_barcode').hide();
				jQuery('#ni_notes').val("");				
				jQuery('#imagebox').html('');
				jQuery('#upc_search_image').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		//jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}
}
function MakechangeBarcode(){
	jQuery('#barcode_valid').val('');
	} 
function fix(num,id){	
	if(num){	
		num= parseFloat(num).toFixed(2);
	}else{
		num= '0.00';
	}
	
	if(!isNaN(num)){
	jQuery('#'+id).val(num);
	}else{
	jQuery('#'+id).val('0.00');
	}
}
jQuery(document).ready(function(){
	jQuery('.add-on A').click(function(){
		if(jQuery(this).attr('rel')=='client'){
		jQuery('#keyword').val(jQuery('#vendors').val());
		}else{
		if(jQuery('#keyword').val().length<4){
		jAlert('Please enter More than 3 Characters','Alert Dialog');
		return false;
		}
		}
		GetVendor(1);
	});
	jQuery('#client_add').click(function(){
		jQuery.ajax({
            type: "POST",
            url: "backoffice_payments_add_vendor.php"}).done(function(msg){
		jQuery("#mymodal_html5").html(msg);
		jQuery('#filter_modal').modal('toggle');
		jQuery("#mymodal5").modal('show');
		
		});
	});
});
function getvendor1(){
	if(jQuery('#vendors').val().length<4){
		jAlert('Please enter More than 3 Characters!','Alert Dialog');
		return false;
	}else{
	//jQuery('.icon-search').trigger('click');
	jQuery('#filter_modal').modal('toggle');
	jQuery('#keyword').val(jQuery('#vendors').val());
	GetVendor(1);
	
	}
}
function GetVendor(val){
	var str;
	if(val=="1"){
		str = document.getElementById('vendors').value;
	}else{	
		str = jQuery('#keyword').val();
	}
	if(str.length>2){
		document.getElementById('keyword').value=str;
		document.getElementById("modalcontent").innerHTML="";
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById('vendors').value=str;
				document.getElementById("modalcontent").innerHTML=xmlhttp.responseText;
				// document.getElementById('keyword').value="";
				// document.getElementById("livesearch").style.border="1px solid #A5ACB2";
			}
		}
		xmlhttp.open("GET","vendor_search.php?vendor_id="+ vendor_id +"&q="+str,true);
		xmlhttp.send();
	}else{
		jQuery('#modalcontent').html('');
	}
}
function loadVendor(id,email,phone,name,image)
{
	jQuery('#vendors').val(name);	
	jQuery('#vendor_id').val(id);	
	jQuery('#filter_modal').modal('toggle');
}	
</script>

<script type="text/javascript">
jQuery("#sub_form").click(function(e){
  if(jQuery('#status').val()==""){
		jAlert('Please Select status!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#d_market').val()==""){
		jAlert('Please Select Market!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#group').val()==""){
		jAlert('Please Select Group!','Alert Dialog');
		e.preventDefault();
	/*}else if(jQuery('#inv_item').val()=="" && jQuery('#inv_item_type').val()!='new'){
		jAlert('Please Select Inventory Item!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#inv_item1').val()=="" && jQuery('#inv_item_type').val()=='new'){
		jAlert('Please Enter Inventory Item!','Alert Dialog');
		e.preventDefault();*/
	}else if(jQuery('#name').val()==""){
		jAlert('Please Enter Item Description!','Alert Dialog');
		e.preventDefault();
	}
/*S.A. Lowell: No longer required.*/
/*
else if(jQuery('#pack_size').val()==""){
		jAlert('Please Enter Pack Size!','Alert Dialog');
		e.preventDefault();
	}
*/
else if(jQuery('#pack_unit_type').val()==""){
		jAlert('Please Select Pack Unit Type!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#qty_in_pack').val()==""){
		jAlert('Please Enter Quantity In Pack!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#qty_in_pack_unit_type').val()==""){
		jAlert('Please Select Qty In Pack Unit Type!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#price').val()==""){
		jAlert('Please Enter Price!','Alert Dialog');
		e.preventDefault();
	}else{
		jQuery('#edit_from input,#edit_from select').attr('disabled',false);
		return true;
	}
});
</script>

<script type="text/javascript">
jQuery('#pack_unit_type').change(function() {
	//alert(jQuery(this).val());
	jQuery("#hidden_unit_type").val(jQuery(this).val());
});

jQuery('#name').blur(function() {
	var desc = jQuery('#name').val();
	if( desc.length>=3){
		console.log('enter');
		/*jQuery.ajax({
			url:'setup_items.php',
			data:{m:'check_item_exists', description: desc },
			type:'POST',
			dataType: 'json',
			success:function(data){
				//console.log(data);
				var str = '';
				if(data.flag == '1'){	
					jQuery.each(data.data,function(i,v){
						str +=  v;
					});
					jConfirm(str, 'Duplicate Item, Do you want to Continue?', function(r) {
						if(r){ }else{ jQuery("#name").val("");}
					});
					//jAlert(str, 'Duplicate');
				}
			}
		});*/
	}
});

jQuery('#vendor_internal_number').blur(function() {
	var item_code = jQuery('#vendor_internal_number').val();
	if( item_code.length>=3){
		jQuery.ajax({
			url:'setup_items.php',
			data:{m:'check_item_code_exists', item_code: item_code },
			type:'POST',
			dataType: 'json',
			success:function(data){
				//console.log(data);
				var str = '';
				if(data.flag == '1'){	
					jQuery.each(data.data,function(i,v){
						str +=  v;
					});
					jConfirm(str, 'Duplicate Item, Do you want to Continue?', function(r) {
						if(r){ }else{ jQuery("#vendor_internal_number").val("");}
					});
					//jAlert(str, 'Duplicate');
				}
			}
		});
	}
});
</script>
<div id="filter_modal" style="height:600px !important;" class="modal hide fade">

    <div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
      <h3>Search Vendor</h3>
      <br>
      <label>Search:&nbsp;&nbsp;
		  <div class="input-append">
			  <input name="keyword" id="keyword" type="text"  onKeyUp="javascript:GetVendor(2)"  
			  tabindex="0" style="width:400px;"  />
			  <span class="add-on" ><a href="javascript:void(0);" class="icon-search" ></a></span>
		  </div>
          <!--<a href="#" id="client_add"> <img id="ai" src="images/Add_16.png"></a>-->
      </label>
    </div>
    <div class="modal-body" id="modalcontent" style="height:415px;"> </div>
    <div class="modal-footer" style="text-align: center;">
      <p >
        <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
      </p>
    </div>
</div>
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->
</body>
<div id="imageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Add/Edit Media</h3>
	</div>
	<div class="modal-body " id="mymodalhtml"></div>
	<div class="modal-footer" style="text-align:center;">
		<button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
		<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
	</div>
</div>
</html>
<script type="text/javascript">
	jQuery(function(){
		jQuery('#price_by_weight').live('change', function(){
			var v = jQuery(this).val();

			if(v=='Yes'){
				jQuery('.pbwut').show();
			}
			else if(v=='No'){
				jQuery('.pbwut').hide();
			}
			else{
				jQuery('.pbwut').hide();
			}
		});
	});
</script>