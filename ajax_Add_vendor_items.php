<?php 
	ob_start("ob_gzhandler");
	include_once 'includes/session.php';
	include_once("config/accessConfig.php");
	
if ($_POST['newitemSubmit'] == "1") {

//echo "Purchase Id: ".$purid; die();

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
$vendor_internal_number = mysql_real_escape_string($_REQUEST['vendor_internal_number']);
$pack_size = mysql_real_escape_string($_REQUEST['pack_size']);
$pack_unit_type = mysql_real_escape_string($_REQUEST['pack_unit_type']);
$qty_in_pack = mysql_real_escape_string($_REQUEST['qty_in_pack']);
$qty_in_pack_unit_type = mysql_real_escape_string($_REQUEST['qty_in_pack_unit_type']);
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

if($group_ins_type=='new'){
			 $max_group = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(group_id AS UNSIGNED))+1, 1000) as grp from inventory_groups"));
			 $max_priority = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(priority AS UNSIGNED))+1, 1000) as priority from inventory_groups"));
			 $gquery = "INSERT INTO inventory_groups SET
					group_id = '".$max_group['grp']."',
					priority = '".$max_priority['priority']."',
					description = '".$new_gruop."',
					Market = '".$d_market."'";
					$res_g = mysql_query($gquery) or die(mysql_error());
					if($res_g){
					$group = mysql_insert_id();
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
	$taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	//$image = mysql_real_escape_string($_REQUEST['image']);
	$model_number = mysql_real_escape_string($_REQUEST['ni_model']);
	$manufacturer = mysql_real_escape_string($_REQUEST['ni_manufacture']);
	$brand = mysql_real_escape_string($_REQUEST['ni_brand']);
	$notes = mysql_real_escape_string($_REQUEST['ni_notes']);
	$fields = "";
	
	
	
	 $ins_query = "INSERT INTO inventory_items SET
				  item_id='".$last_item_id."',
				  inv_group_id = '".$group."',				  
				  status ='active',
				  description = '".$inv_item_new."',				  
				  unit_type = '".$pack_unit_type."',
				  notes = '".$notes."',
				  brand = '".$brand."',
				  manufacturer = '".$manufacturer."',
				  model_number = '".$model_number."',
				  image = '".$image."',
				  $inV_fields
				  taxable = '".$taxable."',
				  manufacturer_barcode = '".$manufacturer_barcode."',				  				  
				  created_by = '".$_SESSION['employee_id']."',
				  created_on = 'BusinessPanel',
				  created_dt = '".date('Y-m-d H:i:s')."'";				 
				 $res_ins=mysql_query($ins_query)or die(mysql_error());
				 if($res_ins){
				 $inv_item_id = mysql_insert_id();
				 }
}else{
	$taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$up_inv = mysql_query("UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' WHERE id = '".$inv_item."'") or die(mysql_error());
	$inv_item_id = $inv_item;
}

$vendor_id = mysql_real_escape_string($_REQUEST['sivendor']);
//echo "Vendor id: ".$vendor_id;
//die();

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
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   promotion_price ='".$promotion_price."',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',
				   last_by = '".$_SESSION['employee_id']."',
				   last_on = 'BusinessPanel',
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
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   vendor_id = '".$vendor_id."',
				   inv_item_id='".$inv_item_id."',
				   created_by = '".$_SESSION['employee_id']."',
				   created_on = 'BusinessPanel',
				   created_datetime = now(),
				   promotion_price ='".$promotion_price."'";  
				   
				   // '".date('Y-m-d H:i:s')."'
				  
				   /*vendor_id = '',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',*/

}	
	$group1_inp = $_REQUEST['group1_inp'];
	$market_inp = $_REQUEST['market_inp']; 
	$res1 = mysql_query($query1) or die(mysql_error());	
	echo 'Item Added/Updated Successfully!';

}else{
	echo 'Error Occured. Please Try Again!';	
}
?>
