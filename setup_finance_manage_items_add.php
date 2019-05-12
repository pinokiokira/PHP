<?php
//
require_once 'require/security.php';
include 'config/accessConfig.php';
require_once('require/openid-config.php'); 

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

$setupHead      = "active";
$setupDropDown  = "display: block;";

	/*$jsonurl = API."API2/getlocationtime.php?intLocationID=".$_SESSION['loc']."&server_time=";*/
	$jsonurl = API."Panels/BusinessPanel/api/getlocationtime.php?intLocationID=".$_SESSION['loc']."&server_time=";
 	$json = file_get_contents($jsonurl,0,null,null);	
 	$datetimenow= $json ;
	$datetimenowk1=explode(",",$datetimenow);	
	$date_new = explode(":",$datetimenowk1['1']);
	$pieces = explode(" ",str_replace('"','',$date_new[1]));
	$cur_date = $pieces[0]; 
	$cur_time = $pieces[1].':'.$date_new[2].':'.str_replace('"}','',$date_new[3]); 	
	$ldatetitme = $cur_date.' '.$cur_time;


$financeHead    = "active";
$financeDropDown = "display: block;";
$set_back_invventoryDropDown  = "display: block;";
$plu_query=$rp->rp_fetch_array($rp->rp_query('SELECT MAX(CAST(plu AS SIGNED))+1 as max_plu from location_menu_articles where location_id ="'.$_SESSION['loc'].'"'));
$max_plu = $plu_query['max_plu'];
$financeMenu3 = "active";

function GetLocationTimeFromServer_general($intLocationID, $servertime){
	/*$jsonurl = API."API2/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);*/
	$rp = new db_class();

	$jsonurl = API."Panels/BusinessPanel/api/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);
    $json = file_get_contents($jsonurl);
    $dateTimeResult = json_decode($json);
    $dateTime = $dateTimeResult->servertolocation_datetime;
   return date('Y-m-d H:i',strtotime($dateTime));
}

function clean($var) {
	$rp = new db_class();
    $specials = array(' ', '!', '@', '#', '$', '%', '^', '&', '(', ')', '_', '+', '`', '~', ',', ';', "'", ']', '[', '}', '{');
    $cleaned = strtolower($var);
    $cleaned = str_replace($specials, '-', $cleaned);
    $cleaned = str_replace('--------------------', '-', $cleaned);
    $cleaned = str_replace('-------------------', '-', $cleaned);
    $cleaned = str_replace('------------------', '-', $cleaned);
    $cleaned = str_replace('-----------------', '-', $cleaned);
    $cleaned = str_replace('----------------', '-', $cleaned);
    $cleaned = str_replace('---------------', '-', $cleaned);
    $cleaned = str_replace('--------------', '-', $cleaned);
    $cleaned = str_replace('-------------', '-', $cleaned);
    $cleaned = str_replace('------------', '-', $cleaned);
    $cleaned = str_replace('-----------', '-', $cleaned);
    $cleaned = str_replace('----------', '-', $cleaned);
    $cleaned = str_replace('---------', '-', $cleaned);
    $cleaned = str_replace('--------', '-', $cleaned);
    $cleaned = str_replace('-------', '-', $cleaned);
    $cleaned = str_replace('------', '-', $cleaned);
    $cleaned = str_replace('-----', '-', $cleaned);
    $cleaned = str_replace('----', '-', $cleaned);
    $cleaned = str_replace('---', '-', $cleaned);
    $cleaned = str_replace('--', '-', $cleaned);
    $cleaned = str_replace('-', '-', $cleaned);
    return $cleaned;
}
function get_vendor_name($id){
	$rp = new db_class();
	$query = $rp->rp_fetch_array($rp->rp_query("SELECT name from vendors where id = '".$id."'"));
	return $query['name'];
}
	
if (isset($_POST['subform'])) {
	$menu_art_id = $rp->add_security($_POST['menu_art_id']);
	$plu_query=$rp->rp_fetch_array($rp->rp_query('SELECT MAX(CAST(plu AS SIGNED))+1 as max_plu from location_menu_articles where location_id ="'.$_SESSION['loc'].'"'));
	$max_plu = $plu_query['max_plu'];
	
	$plu_query=$rp->rp_fetch_array($rp->rp_query('SELECT MAX(CAST(priority AS SIGNED))+1 as max_priority from location_menu_articles where location_id ="'.$_SESSION['loc'].'"'));
	$max_priority = $plu_query['max_priority'];

 $query = "SELECT storeroom_id FROM location_inventory_storerooms WHERE location_id='" . $_SESSION['loc'] . "' AND stroom_id='General'";
    $result = $rp->rp_query($query) or die(mysql_error());
    $num_rows = $rp->rp_affected_rows($result);
    if($num_rows < 1){
        $query2 = "INSERT INTO location_inventory_storerooms SET location_id='" . $_SESSION['loc'] . "', description='General Storeroom', stroom_id='General'";
        $result2 = $rp->rp_query($query2) or die(mysql_error());
        $storeroom = $rp->rp_dbinsert_id();
    }else{
        $row = $rp->rp_fetch_array($result);
        $storeroom = $row['storeroom_id'];
    }
    /*$old_image = $_POST['old_image'];
    $image_up = '';
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        //echo "img up";

        $filename = clean(basename(md5(date("D M j G:i:s T Y") . rand(1, 999999999999)) . '-' . $_FILES['digital_image_name']['name']));
        $upload_to_temp = "./temp_img/" . $filename;
		
			 
		if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_to_temp)) {
		
            $ftp_path = "inventory/" . $filename;
            $ftphost = "ftp.softpoint.us";
            $ftpusr = "internal_update";
            $ftppwd = "UpdateInternal2012%";
            if (file_exists($upload_to_temp)) {
                $conn_id = ftp_connect($ftphost) or die("Couldn't connect to $ftphost");
                $login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
                if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
                    unlink($upload_to_temp);
                    $image_up = $ftp_path;
                    if ($old_image != '') {
                        ftp_delete($conn_id, $old_image);
                    }
                } else {
                    echo "Error transferring file via ftp!<br/>";
                    echo $filename . "<br/>";
                    echo $upload_to_temp . "<br/>";
                    echo $ftp_path . "<br/>";
                }
            } else {
                echo "File does not exist in img/temp!";
            }
        } else {
            echo "Error moving file from temp location!";
        }
    }*/	
	$dt = date('Y-m-d H:i:s');
	
    $id = $rp->add_security($_POST['id']);
    $group = $rp->add_security($_POST['group']);
    $item_id = $rp->add_security($_POST['item_id']);
    $priority = $rp->add_security($_POST['priority']);
    $unit = $rp->add_security($_POST['unit_type']);
    $desc = $rp->add_security($_POST['description']);
    $notes = $rp->add_security($_POST['notes1']);
	$default_brand = $rp->add_security($_POST['default_brand']);
	$default_vendor = $rp->add_security($_POST['default_vendor']);
	$default_vendor_search = $rp->add_security($_POST['default_vendor_search']);
	$default_cost_price = $rp->add_security($_POST['default_cost_price']);
	$default_price = $rp->add_security($_POST['default_price']);
	$default_manufacturer = $rp->add_security($_POST['default_manufacturer']);
    $type = $rp->add_security($_POST['type']);
	$local_unit_type_qty = $rp->add_security($_POST['local_unit_type_qty']);
	$local_produces_portions = $rp->add_security($_POST['local_produces_portions']);
	$local_produces_unit_type = $rp->add_security($_POST['local_produces_unit_type']);
	$inventory_count =  $rp->add_security($_POST['inventory_count']);
	$article_price =  $rp->add_security($_POST['article_price']);
	$low_alert_unittype =  $rp->add_security($_POST['low_alert_unittype']);
	$low_alert_count =  $rp->add_security($_POST['low_alert_count']);
	
	$image = "" ;
	$img = "" ;
	if($_POST['digital_image_name']!=""){
		if(strpos($_POST['digital_image_name'], 'http') !== FALSE) {
			$image=$_POST['digital_image_name'];
			$img = "local_item_image='$image',";
		} else {
			$image="inventory/".$_POST['digital_image_name'];
			$img = "local_item_image='$image',";
		}
	}
	
	if(($default_vendor=='' || $default_vendor==0)  && $default_vendor_search!=''){
		$check_vender = "SELECT id from vendors WHERE name = '".$default_vendor_search."'";		
		$res_vendor   = $rp->rp_query($check_vender);
		if($res_vendor && $rp->rp_affected_rows($res_vendor)>0){
			$row_vendor = $rp->rp_fetch_array($res_vendor);
			$default_vendor = $row_vendor['id'];
		}else{
			$insVendor = "INSERT INTO vendors SET 
						  name = '".$default_vendor_search."',
						  status = 'active',
						  created_by = '".$_SESSION['employee_id']."',
						  created_on = 'BusinessPanel',
						  created_date=NOW()";
			$res_v = $rp->rp_query($insVendor) or die(mysql_error());			  
			$default_vendor = $rp->rp_dbinsert_id();
		}
	}


	if ($default_vendor=="" || $default_vendor==null || empty($default_vendor)) {
		$default_vendor = $_REQUEST['default_vendor_req'];
	}
	//echo $default_vendor;
	//exit;
	
	
	$manu_barcode = '';
	
	$manu_barcode = $rp->add_security($_POST['barcode']);
	
	
    /*if ($image_up != '') {
        $image = $image_up;
    } else {
        $image = $old_image;
    }*/
    if($type != '' && $group !=''){
        if ($_POST['id'] != '') {
            $query = "UPDATE location_inventory_items SET
                        status='active',
                        type='$type',
                        local_item_id='$item_id',
                        local_group_id='$group',
                        local_unit_type='$unit',
                        local_item_desc='$desc',
                        ".$img."
                        local_item_notes='$notes',
						manufacturer_barcode = '$manu_barcode',						
						default_brand = '$default_brand',
						default_vendor = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
						default_cost_price = '$default_cost_price',
						default_price = '$default_price',
						taxable = '".$_POST['local_taxable']."',
						default_manufacturer = '$default_manufacturer',	
						local_unit_type_qty = '$local_unit_type_qty',
						local_produces_portions = '$local_produces_portions',
						local_produces_unit_type = '$local_produces_unit_type',			
						priority='$priority',
						low_alert_unittype = '".$low_alert_unittype."',
						low_alert_count = '".$low_alert_count."',
						last_by='" . $_SESSION['employee_id'] . "',
                        last_on='BusinessPanel',
                        last_datetime='$dt',
                        location_id=" . $_SESSION['loc'] . "
                 WHERE id=$id";

                

            $result = $rp->rp_query($query) or die(mysql_error());
			
			$msg="up";
        } else {
			
            if($_POST['new_add_type'] == 1){
                $group = $rp->add_security($_POST['group']);
                $unit_type = $rp->add_security($_POST['global_unittype']);
               ///there was mistake 
                $item_id = $rp->add_security($_POST['global_itemid']);
                $description = $rp->add_security($_POST['global_description']);
                $image = $rp->add_security($_POST['image']);
                $taxable = $rp->add_security($_POST['global_taxable']);
                $notes = $rp->add_security($_POST['global_notes']);
                $priority = $rp->add_security($_POST['global_priority']);
                $dt = date("Y-m-d H:i:s");

        	  	  $query = "INSERT INTO inventory_items SET
                                inv_group_id='$group',
                                unit_type='$unit_type',
                                description='$description',
                                image='$image',
                                taxable='$taxable',
                                notes='$notes',
								manufacturer_barcode = '$manu_barcode',
								vendor_default = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
                                item_id='$itm_id',
                                created_by='" . $_SESSION['employee_id'] . "',
                                created_on='BusinessPanel',
                                created_dt='$dt'";
                $result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
              	$inserted = $rp->rp_dbinsert_id();
          		    $query = "INSERT INTO location_inventory_items SET
                                location_id=".$_SESSION['loc'].",
                                inv_item_id='".$inserted."',
								priority='$priority',
								manufacturer_barcode = '$manu_barcode',
								taxable='$taxable',
                                status='active',
                                `type`='global',
                                default_vendor = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
								local_unit_type_qty = '$local_unit_type_qty',
								local_produces_portions = '$local_produces_portions',
								local_produces_unit_type = '$local_produces_unit_type',
								low_alert_unittype = '".$low_alert_unittype."',
								low_alert_count = '".$low_alert_count."',
								created_by='" . $_SESSION['employee_id'] . "',
                                created_on='BusinessPanel',
                                created_datetime='$dt'";

                             
                $result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
				$insert = $rp->rp_dbinsert_id();
				$query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$storeroom."',
                                inv_item_id='".$rp->rp_dbinsert_id()."'";
                $result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
				if($inventory_count>0){
					 $query_count = "INSERT INTO location_inventory_counts SET
								location_id=" . $_SESSION['loc'] . ",
								storeroom_id= '".$storeroom."',
								inv_item_id='".$insert."',
								Type='Count',
								employee_id=" . $_SESSION['employee_id'] . ",
								unit_type='".$unit."',
								quantity='".$inventory_count."',
								created_on='BusinessPanel',
								created_datetime=NOW(),
								created_by='".$_SESSION['employee_id']."',
								date_counted='".date('Y-m-d')."',
								time_counted='" . date('H:i:s') . "'";
					 $rp->rp_query($query_count);
				} 			
			
				$msg="ad";
				if($_POST['insert_menuarticle']=='Yes'){
					if($menu_art_id>0){
						$article = $menu_art_id;
					}else{
					$sql = $rp->rp_query("INSERT INTO location_menu_articles SET
							Status = 'Active',
							location_id = '".$_SESSION['loc']."',
							item = '".$description."',
							plu = '".$max_plu."',
							price='".$_POST['article_price']. "',
							priority = '".$max_priority."',
							barcode = '".$manu_barcod."',														
							taxable = '".$taxable."',							
							description='".$description."',
							image = '".$image."',
							retail = 'Yes',							
							article_type = 'Other',
							created_on ='BusinessPanel',
							created_datetime='".$ldatetitme."',
							created_by = '".$_SESSION['employee_id']."'") or die($sql.'<br>'.mysql_error());
			$article = $rp->rp_dbinsert_id();
			}
							 
			$query = "INSERT INTO location_inventory_recipe SET
                         location_id = " . $_SESSION['loc'] . ",
                         cost='".$_POST['article_price']. "',
                         menu_article_id='".$article."',
						 created_by = '".$_SESSION['employee_id']."',
						 created_on = 'BusinessPanel',
						 created_datetime = now(),
                         type='item'";
           
            $result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
            $id = $rp->rp_dbinsert_id();

            $query = "INSERT INTO location_inventory_recipe_details SET
                            recipe_id='".$id."',
                            inv_item_id='".$insert."',
							location_id='".$_SESSION["loc"]."',
                            unit_type=(SELECT id FROM inventory_item_unittype WHERE unit_type='EACH')";
            $result = $rp->rp_query($query) or die($query.'262 <br>'.mysql_error());
				
				
				}
            }else
			{
				//echo $image;
				//echo '<pre>';
				//print_r($_POST);
				//echo 'ok2';exit(0);
				
				$group = $rp->add_security($_POST['group']);
                $unit_type = $rp->add_security($_POST['global_unittype']);
                //$item_id = $rp->add_security($_POST['item']);
                $description = $rp->add_security($_POST['global_description']);
                $taxable = $rp->add_security($_POST['global_taxable']);
                $notes = $rp->add_security($_POST['global_notes']);
                $priority = $rp->add_security($_POST['global_priority']);
				$dt = date("Y-m-d H:i:s");
				
				$inv_itemquery = $rp->rp_fetch_array($rp->rp_query("SELECT * from inventory_items where id = '".$_POST['item']."'"));
                if($_POST['type'] =='global'){
					$item_id = trim($_POST['item']);
					if($item_id == 'add_new_item'){
						$item_id = $rp->add_security($_POST['global_itemid']);
						$fields = '';
						if($unit_type != ''){
							$fields .= " unit_type='$unit_type', ";
						}
						$qry_ins_g_item = "INSERT INTO inventory_items SET
									status = 'active',
									item_id='". $item_id ."',
									inv_group_id = '". $group ."',
									taxable='$taxable',
	                                notes='$notes',
									description = '". $description ."',
									". $fields ."
									vendor_default = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
									created_by='" . $_SESSION['employee_id'] . "',
									created_on='BusinessPanel',
									created_dt='$dt'
						";
						$rp->rp_query($qry_ins_g_item) or ($qry_ins_g_item .':'. mysql_error());
						$item_id=$rp->rp_dbinsert_id();
						
					}
				
					// $squery="SELECT id FROM location_inventory_items WHERE local_group_id='".$group."' AND inv_item_id=".$_POST['item'];
					$squery="SELECT id FROM location_inventory_items WHERE location_id = '".$_SESSION['loc']."' AND local_group_id='".$group."' AND inv_item_id=".$item_id;
					$sresult=$rp->rp_query($squery);
					$stotalrow=$rp->rp_affected_rows($sresult);
					
					if($stotalrow> 0) {
					//echo 'ok1';exit(0);
						$res = $rp->rp_fetch_array($sresult);
						$queryup="UPDATE location_inventory_items SET
							status='active',
							local_group_id='$group',
							priority='$priority',
							taxable='$taxable',
							default_vendor = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
							last_by='" . $_SESSION['employee_id'] . "',
                            last_on='BusinessPanel',
                            last_datetime='$dt'
							WHERE inv_item_id = '" . $rp->add_security($_POST['item'])."'"; 

						
								
						$result = $rp->rp_query($queryup) or die(mysql_error());
						$msg="up";
						$insert = $res['id'];
					}else{
					
						$manufacturer_barcode = $rp->add_security(trim($_POST['manufacturer_barcode']));
						$vendor_default = $rp->add_security(trim($_POST['vendor_default']));
						
						//inv_item_id='" . $rp->add_security($_POST['item']) . "',
                  		$query = "INSERT INTO location_inventory_items SET
								status='active',
								local_group_id='$group',
								type='global',
								inv_item_id='". $item_id ."',
								priority='$priority',
								local_item_desc = '".$inv_itemquery['description']."',
								local_unit_type='".$inv_itemquery['unit_type']."',								
								local_item_notes='".$inv_itemquery['notes']."',
								manufacturer_barcode = '". $manufacturer_barcode ."',
								default_vendor='". ((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
								default_brand = '".$inv_itemquery['brand']."',
								default_manufacturer = '".$inv_itemquery['manufacturer']."',	
								taxable='".$_POST['global_taxable']."',
								location_id='" . $_SESSION['loc']."',
								created_by='" . $_SESSION['employee_id'] . "',
                                created_on='BusinessPanel',
                                created_datetime='$dt'";

                                //print_r("Ex query 446: ".$query);
                                //exit;
					//echo 'ok2..';exit(0);
						$result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
						$insert = $rp->rp_dbinsert_id();
						
						if($inventory_count>0){
							 $query_count = "INSERT INTO location_inventory_counts SET
										location_id=" . $_SESSION['loc'] . ",
										storeroom_id= '".$storeroom."',
										inv_item_id='".$insert."',
										Type='Count',
										employee_id=" . $_SESSION['employee_id'] . ",
										unit_type='".$unit."',
										quantity='".$inventory_count."',
										created_on='BusinessPanel',
										created_datetime=NOW(),
										created_by='".$_SESSION['employee_id']."',
										date_counted='".date('Y-m-d')."',
										time_counted='" . date('H:i:s') . "'";
							 $rp->rp_query($query_count);
						} 	
						
						
						
						$query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$storeroom."',
                                inv_item_id='".$rp->rp_dbinsert_id()."'";
						$result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
						$msg="ad";
                    }
					
					if($_POST['insert_menuarticle']=='Yes'){
						if($menu_art_id>0){
							$article = $menu_art_id;
						}else{
							$sql = $rp->rp_query("INSERT INTO location_menu_articles SET
									Status = 'Active',
									location_id = '".$_SESSION['loc']."',
									item = '".$inv_itemquery['description']."',
									plu = '".$max_plu."',
									price='".$_POST['article_price']. "',
									priority = '".$max_priority."',
									barcode = '".$inv_itemquery['manufacturer_barcode']."',														
									taxable = '".$_POST['global_taxable']."',							
									description='".$inv_itemquery['description']."',
									image = '".$inv_itemquery['image']."',
									retail = 'Yes',							
									article_type = 'Other',
									created_on ='BusinessPanel',
									created_datetime='".$ldatetitme."',
									created_by = '".$_SESSION['employee_id']."'") or die($sql.'<br>'.mysql_error());
							$article = $rp->rp_dbinsert_id();
						}				 
						$query = "INSERT INTO location_inventory_recipe SET
									 location_id = " . $_SESSION['loc'] . ",
									 cost='".$_POST['article_price']. "',
									 menu_article_id='".$article."',
									 created_by = '".$_SESSION['employee_id']."',
									 created_on = 'BusinessPanel',
									 created_datetime = now(),
									 type='item'";
			   
						$result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
						$id = $rp->rp_dbinsert_id();

						$query = "INSERT INTO location_inventory_recipe_details SET
										recipe_id='".$id."',
										inv_item_id='".$insert."',
										location_id='".$_SESSION["loc"]."',
										unit_type=(SELECT id FROM inventory_item_unittype WHERE unit_type='EACH')";
						$result = $rp->rp_query($query) or die($query.' 359 <br>'.mysql_error());
					}
				}else{
					$query = "INSERT INTO location_inventory_items SET
                        location_id=" . $_SESSION['loc'].",
						priority='$priority',
                        status='active',
                        type='$type',
                        local_item_id='$item_id',
                        local_group_id='$group',
                        local_item_desc='$desc',
                        local_item_notes='".$rp->add_security($_POST['notes1'])."',
                        local_item_image='$image',
                        local_unit_type='$unit',
						local_unit_type_qty = '$local_unit_type_qty',
						local_produces_portions = '$local_produces_portions',
						local_produces_unit_type = '$local_produces_unit_type',
						default_vendor = '".((empty($vendor_default)) ? $_REQUEST['default_vendor_req'] : $vendor_default) ."',
						taxable = '".$rp->add_security($_POST['local_taxable'])."',
						default_manufacturer='$default_manufacturer',
						default_brand= '$default_brand',
						default_vendor='".$default_vendor."',
						default_price = '$default_price',
						default_cost_price = '$default_cost_price',
						manufacturer_barcode = '".$rp->add_security($_POST['barcode'])."',
						low_alert_unittype = '".$rp->add_security($_POST['low_alert_unittype'])."',
						low_alert_count = '".$rp->add_security($_POST['low_alert_count'])."',
						created_by='" . $_SESSION['employee_id'] . "',
                        created_on='BusinessPanel',
                        created_datetime='$dt'";

                        
						
                    $result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
					$insert = $rp->rp_dbinsert_id();
					
					if($inventory_count>0){
							 $query_count = "INSERT INTO location_inventory_counts SET
										location_id=" . $_SESSION['loc'] . ",
										storeroom_id= '".$storeroom."',
										inv_item_id='".$insert."',
										Type='Count',
										employee_id=" . $_SESSION['employee_id'] . ",
										unit_type='".$unit."',
										quantity='".$inventory_count."',
										created_on='BusinessPanel',
										created_datetime=NOW(),
										created_by='".$_SESSION['employee_id']."',
										date_counted='".date('Y-m-d')."',
										time_counted='" . date('H:i:s') . "'";
							 $rp->rp_query($query_count);
						} 	
					
					
					$query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$storeroom."',
                                inv_item_id='".$insert."'";
					$result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
				
				
					if($_POST['insert_menuarticle']=='Yes'){
						if($menu_art_id>0){
							$article = $menu_art_id;
						}else{
							$sql = $rp->rp_query("INSERT INTO location_menu_articles SET
									Status = 'Active',
									location_id = '".$_SESSION['loc']."',
									item = '".$item_id."',
									plu = '".$max_plu."',
									priority = '".$max_priority."',
									price='".$_POST['article_price']. "',
									barcode = '".$rp->add_security($_POST['barcode'])."',														
									taxable = '".$_POST['local_taxable']."',							
									description='".$desc."',
									image = '".$image."',
									retail = 'Yes',							
									article_type = 'Other',
									created_on ='BusinessPanel',
									created_datetime='".$ldatetitme."',
									created_by = '".$_SESSION['employee_id']."'") or die($sql.'<br>'.mysql_error());
							$article = $rp->rp_dbinsert_id();
						}		 
						$query = "INSERT INTO location_inventory_recipe SET
									 location_id = " . $_SESSION['loc'] . ",
									 cost='".$_POST['article_price']. "',
									 menu_article_id='".$article."',
									 created_by = '".$_SESSION['employee_id']."',
									 created_on = 'BusinessPanel',
									 created_datetime = now(),
									 type='item'";
			   
						$result = $rp->rp_query($query) or die($query.'<br>'.mysql_error());
						$id = $rp->rp_dbinsert_id();

						$query = "INSERT INTO location_inventory_recipe_details SET
										recipe_id='".$id."',
										inv_item_id='".$insert."',
										location_id='".$_SESSION["loc"]."',
										unit_type=(SELECT id FROM inventory_item_unittype WHERE unit_type='EACH')";
						$result = $rp->rp_query($query) or die($query.'439 <br>'.mysql_error());
					
					
					}
					$msg="ad";
					if($menu_art_id>0){
						header('Location:setup_retail_item_link.php?msg=add');
					}else{
						header('Location: setup_backoffice_manage_items.php?group_id='.$_POST['s_group_id'].'&market='.$_POST['s_market'].'&msg='.$msg);
					}
                }
            }
        }
    }
	
	//this is for setup process
if (isset($_POST['step']) && $_POST['step']=="5")
{	
	header("Location: setup_process.php?step=6");  
}
else
{
	if($menu_art_id>0){
			header('Location:setup_retail_item_link.php?msg=add');
		}else{
  			header('Location: setup_backoffice_manage_items.php?group_id='.$_POST['s_group_id'].'&market='.$_POST['s_market'].'&msg='.$msg);
		}
}
}   
if ($_REQUEST['id'] != '') {
    $query1 = "SELECT lii.*,lii.local_item_id, lii.local_group_id, lii.local_unit_type,lii.local_item_desc,lii.local_item_image,lii.local_item_notes, lii.type, lii.priority, lii.local_unit_type_qty, lii.local_produces_portions, lii.local_produces_unit_type,lic.quantity,
	lii.default_brand, lii.default_vendor,v.name as default_vendor_name, lii.default_price, lii.default_cost_price, lii.default_manufacturer,ig.Market,lii.taxable as local_taxable,lii.manufacturer_barcode, CONCAT(e.first_name,' ',e.last_name) as created_emp ,CONCAT(le.first_name,' ',le.last_name) as last_emp
              FROM location_inventory_items lii
              LEFT JOIN inventory_groups ig ON lii.local_group_id=ig.id
              LEFT JOIN inventory_item_unittype liu ON lii.local_unit_type=liu.id
			  LEFT JOIN employees as e on e.id = lii.created_by
			  LEFT JOIN vendors as v on v.id = lii.default_vendor
			  LEFT JOIN employees as le on le.id = lii.last_by
			  LEFT JOIN location_inventory_counts as lic on lic.inv_item_id = lii.id
              WHERE lii.id=" . $rp->add_security($_REQUEST['id']);
    $result1 = $rp->rp_query($query1) or die(mysql_error());
    $row1 = $rp->rp_fetch_array($result1) or die(mysql_error());
 }
 
 $emp = $rp->rp_query("select first_name,last_name from employees where id='".$_SESSION['employee_id']."'");
 $fetch_emp = $rp->rp_fetch_array($emp);
 
 if(!isset($_REQUEST['id'])){
	$row1['created_on'] = 'BusinessPanel';
	$row1['created_emp'] = $fetch_emp['first_name'].' '.$fetch_emp['last_name'];
 }
 
$market_whr = "";
if($_SESSION['access_pos']=='yes'){
	$market_whr .= " OR ig.Market = 'Restaurant' OR ig.Market = 'Bar'";	
}
if($_SESSION["access_hotel"]=='yes'){
	$market_whr .= " OR ig.Market = 'Hotel'";	
}
if($_SESSION["access_register"]=='yes'){
	$market_whr .= " OR ig.Market = 'Retail'";	
}
if($_GET['menu_art']>0){
	$query2 = "SELECT DISTINCT ig.id, ig.description
           FROM inventory_groups ig where ig.Market = 'Retail'  
           ORDER BY description ASC";
}else{
	if(!empty($row1['Market'])){ $sqlmarket =  " where ig.Market='".$row1['Market']."'";}else { $sqlmarket = " where ig.Market = 'All'";}
/*$query2 = "SELECT DISTINCT ig.id, ig.description
           FROM inventory_groups ig $sqlmarket  $market_whr 
           ORDER BY description ASC";*/
$query2 = "SELECT DISTINCT ig.id, ig.description
           FROM inventory_groups ig $sqlmarket  
           ORDER BY description ASC";		   
		   
}
$result2 = $rp->rp_query($query2) or die(mysql_error());

$query3 = "SELECT id, unit_type,description FROM inventory_item_unittype ORDER BY unit_type ASC";
$result3 = $rp->rp_query($query3) or die(mysql_error());



	//this is for setup process
	if (isset($_GET['step']) && $_GET['step']=="5")
	{
		$step=5;
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<style>
.all_p{ display:none;}
<?php if($_REQUEST['id'] == '') {
?> .non-global {
 display: none;
}
<?php
}else{
?>
.all_p{ display:block;}
.barcode_p{ display:block;}
<?php } ?>
.global {
 display: none;
}
.only_prep{
	display: none;
}
.global-new {
	display: none;
}

.progress {
	border: 1px solid #DDDDDD;
	border-radius: 3px 3px 3px 3px;
	display: none;
	margin-top: 10px;
	padding: 1px;
	position: relative;
	width: 100%;
}
.bar {
	background-color: #B4F5B4;
	border-radius: 3px 3px 3px 3px;
	height: 20px;
	width: 0;
}
.percent {
	display: inline-block;
	left: 48%;
	position: absolute;
	top: 3px;
}
.btn-delete {
	display: none;
}
.ed {
	display: none;
}
.ul {
	display: inline;
}
.hasimage .ed {
	display: inline;
}
.hasimage .ul {
	display: none;
}
.hasimage .btn-delete {
	display: inline-block;
}
label {
	width:88px !important;
}
.field {
	display: block;
	margin-left: 103px !important;
	position: relative;
}
.stdform p, .stdform div.par {
    margin: 5px 0 !important;
}
input.span5, textarea.span5, .uneditable-input.span5 {
	width: 70%;
}
.dataTables_paginate .paginate_active {
    background: none repeat scroll 0 0 #0866C6;
    color: #FFFFFF;
}
.mediaWrapper{
	min-height:280px;
}
.stdform span.field, .stdform div.field {
    display: block;
    margin-left: 200px !important;
    position: relative;
}
.stdform label {
    float: left;
    padding: 5px 20px 0 0;
    text-align: right;
    width: 250px !important;
}
.selectouter12 {
   	background: none repeat scroll 0 0 #ffffff;
    border: 1px solid #c9c9c9;
    /*float: left;*/
	margin-left:270px !important;
    height: 28px;
    line-height: 5px;
    /*margin: 0 0 7px;    */
    position: relative;
    width: 221.5px;
}
.dd .ddTitle .ddTitleText {
    padding: 4px 16px 5px 7px !important;
}
<?php if($row1['type'] == 'prep'){ ?>
	.none_prep{
		display:none;
	}
	.only_prep{
		display:block;
	}
<?php } ?>
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/custom_webz.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery.dd1.js"></script>
<link rel="stylesheet" type="text/css" href="css/dd.css" >
<script type="text/javascript" src="js/webcam/webcam.js"></script>
<script type="text/javascript">

	jQuery(document).ready(function($){
		console.log($('#type').val());
		intRoomHeight =1; //jQuery(".widgetcontent").height();
		jQuery('#unit_type').msDropDown();
		jQuery('#low_alert_unittype').msDropDown();
		jQuery('#local_produces_unit_type').msDropDown();
		var filename = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
		if(filename=="setup_finance_manage_items_add.php"){
			//$(".colorbox").hide();
		}else{
			//$(".colorbox").show();
		}

    	$('#type').change(function () {
			console.log($(this).val());
			$('#barcode').attr('disabled',false);
			$('.all_p').show();
			$('.only_prep').hide();			
            var val = $(this).val();
			
		//// ///// Start changes done by Atul johri - 22-08-2016 ///	
			if(val == 'prep'){
				
				$("#barcodeLbl").text("Local Barcode");
				$(".dummy-market").val("Restaurant");
					var market = "Restaurant";
					jQuery.ajax({
						type: "POST",
						url: "chkmarketgroup2.php",
						data: { market: market,iType: val}
					})
					.done(function(msg) {
						jQuery("#group").html(msg);
					});
			}else{
				$(".dummy-market").val("");
				$("#group").val("");
			}
		///// End changes done by Atul johri - 22-08-2016 ///	
			
            if(val == 'global'){
			$('#barcode').attr('disabled',true);
			$('#image_drop').hide();
			$(".colorbox").hide();
                $('.loaded').remove();
                $('.colorbox').before('<div class="loaded"></div>');
                $('#item_form').unbind('submit');
                //$('#item_form').submit(function (e) {
				$('#submit_from').click(function(e){
				e.preventDefault();
				if ($('#type').val() == '') {
					jAlert('Select Type!','Alert');
					return false;
				}else if ($('#dummy_market').val() == '') {      ///// changes done by Atul johri - 22-08-2016 ///
					jAlert('1. Select A Market!','Alert');
					return false;
				}else if ($('#group').val() == '') {
                        jAlert('Select A Group!','Alert');
                        return false;
				}else if ($('#item').val() == '') {
                        jAlert('Select An Item!','Alert');
                        return false;
						}
				<?php /*?>	else if($('#type').val() == 'global' && $('#global_taxable').val() == 'yes'){
						jAlert('Global Item can not be taxable.','Alert');
						return false;
					}<?php */?>
						
				else if(allnumeric($("#global_priority").val())==false && $("#global_priority").val()!=""){
						jAlert('Enter Numerical Value Only In Priority Field.','Alert');
						return false;
					}
				/*else if(jQuery("#type").val()!="global"){
							if(jQuery("#item_id").val()==""){	
								jAlert('Please Enter Abbreviation!','Alert');
                        		return false;
                    			}
						}*/
				/*else if(jQuery("#type").val()=="local" && jQuery("#type").val()=="prep" && jQuery("#description").val()=="" ){
						jAlert('Please Enter Name!','Alert');
                        return false;
                    	}*/					    
                   else if(jQuery('#dummy_market').val()=='Retail'){
				   	
				   if($('#menu_art_id').val()>0){
				   		open_reciepePopup('no');
						submit_allform('submit');
				   }else{
				   		jConfirm('Would you like to create a Menu Article for this Inventory item?','Confirm Dialog',function(r){
							if(r){
								open_reciepePopup('Yes');
						 		return false;	
							}else{
								$('#item_form').submit();
								return true;
							}
						});
					}
				   }else {
				   		$('#item_form').submit();
                        return true;
						
                    }
                });
                
                $('.global').show();
                $('.non-global').hide();
                
                
				$("#group").change(function() {	
                    var grp = $(this).val();
                    $('.loaded').remove();
                    $('.colorbox').before('<div class="loaded"></div>');
                    $.ajax({
                        url: 'ajax/getInvItmByGrpJson.php?g='+grp,
                        dataType:'JSON',
                        success:function(data){
                            //add dropdown options
                            var dropdown = 'item';
                            clearOptions(dropdown);
                            addOption(dropdown,'---Select Item---','');
							addOption(dropdown,'---Add New Item---','add_new_item');
                            if(jQuery('#type').val()!='global'){
							addOption(dropdown,'Add New Item','new');
							}
                            if (data.length){
	                            for(var i=0;i<data.length;i++){										
	                                addOption(dropdown,data[i].description,data[i].id)
	                            }
                            }
                            
                            //add event handler
                            $('#item').off('change.item');
                            $('#item').on('change.item', function(){
								// alert($(this).val());
								if($(this).val() == 'add_new_item'){
									$('.global-new').show();
								}
								else{
								
										$.get('checkimage.php?t=g&item='+$(this).val(),function(data){										
									  if(data==1){
									  $(".colorbox").hide();								  
									  }
									  else{
									  //$(".colorbox").show();
									  }
											
									   
									});
								
									$.get('checkItem.php?t=g&item='+$(this).val(),function(data){
										if(data == 1){
											jAlert('Notice: This Item Already Exists In Your Inventory.','Alert');
											$('#item').val('');
										}
									});
								
									var itm = $(this).val();
									if(itm == 'new'){
										$('.global-new').show();
										$('.loaded').remove();
										$('#new_add_type').val('1');
										$(".colorbox").show();
									}else{									
										$(".colorbox").hide();
										$('.global-new').hide();
										$('.loaded').remove();
										$('#new_add_type').val('');
										$('.colorbox').before('<div class="loaded"></div>');
										$.get('loadItemData.php?i='+itm, function(data){
											$('.loaded').replaceWith(data);
											$("#imageLink").attr("href","upload_digital_menu_image.php?id="+itm);
										});
										$.get('loadItemData.php?i='+itm+'&type=image', function(data){
											jQuery('#imagebox').html('<img src="'+data+'" onerror="this.src=\'images/defimgpro.png\'" class="img-polaroid" style="height:250px;">');
										});	
									}
								}
                            });
                        }
                    });
                });
            }else{
				$('#image_drop').show();
				$(".colorbox").show();
                $('#description').blur(function(){
					if($('#description').val()!=""){
                    $.get('checkItem.php?type=desc&t=ng&item='+$(this).val(),function(data){
                        if(data != 2){
                            jAlert('Notice: This item already exists in your inventory under group ' + data + '.','Alert');
                            $('#description').val('');
                        }
                    });
				  }
                });
                
                $('#item_id').blur(function(){
					if($('#item_id').val()!=""){
                    $.get('checkItem.php?type=abbre&t=ng&item='+$(this).val(),function(data){
                        if(data != 2){
                            jAlert('Notice:  This item already exists in your inventory under group ' + data + '.','Alert');
                            $('#item_id').val('');
                        	}
                    	});
					}
                });
                $('#item_form').unbind('submit');              				
				//$('#item_form').submit(function () {
				$('#submit_from').click(function(e){
				e.preventDefault();
										
                    var id_value = jQuery('#digital_image_name').val();//document.getElementById('digital_image_name').value;
                    var valid_extensions = /(.jpg|.jpeg|.png|.bmp)$/i;
                    if ($('#type').val() == '') {
                        jAlert('Select Type!','Alert');
                        return false;
                    } else if ($('#dummy_market').val() == '') {      ///// changes done by Atul johri - 22-08-2016 ///
						jAlert('Select A Market!','Alert');
						return false;
					}else if ($('#group').val() == '') {
                        jAlert('Select A Group!','Alert');
                        return false;
                    } else if ($('#description').val() == '') {
                        jAlert('Enter An Item Name!','Alert');
                        return false;
                      ////solved  
                          } else if ($('#item_id').val() == '') {
                        jAlert('Enter Abbrevation!','Alert');
                        return false;
                        
                    }else if(allnumeric(jQuery("#priority").val())==false && jQuery("#priority").val()!=""){
						jAlert('Enter Numerical Values Only In Priority Feild!','Alert');
						return false;
					/*}else if($('#type').val() == 'prep') {
					 	jConfirm('Please go to Setup > Resturent > Chef > Prepared Items Inorder to put in the details of what this Preperation item is made of.','Alert',function(r){
							if(r){
								return true;
							}else{
								return true;
							}
						});*/
					}else if(jQuery('#dummy_market').val()=='Retail'){
						
				   if($('#menu_art_id').val()>0){
				   		open_reciepePopup('no');
						submit_allform('submit');
				   }else{
				   		jConfirm('Would you like to create a Menu Article for this Inventory item?','Confirm Dialog',function(r){
							if(r){
								open_reciepePopup('Yes');
						 		return false;	
							}else{
								$('#item_form').submit();
								return true;
							}
						});
					}
				   }else{
				   		$('#item_form').submit();
						return true;
						
					}
                });
				
				$('.none_prep').show();
				$('.only_prep').hide();
                $('.non-global').show();
                $('.global').hide();
                $('.loaded').remove();
                $('.colorbox').before('<div class="loaded"></div>');
                $('#group').unbind('change');
				if(val == 'prep'){
				$('.none_prep').hide();
				$('.only_prep').show();
				}
            }
        });
        
        $('#item_form').submit(function () {
            if ($('#type').val() == '') {
                jAlert('Select Type!','Alert');
                return false;
            }else if ($('#dummy_market').val() == '') {      ///// changes done by Atul johri - 22-08-2016 ///
                jAlert('Select A Market!','Alert');
                return false;
            }else if ($('#group').val() == '') {
                jAlert('Select A Group!','Alert');
                return false;
            }else if($('#type').val() == 'global' && $('#global_taxable').val() == 'yes'){
						jAlert('Global Item can not be taxable.','Alert');
						return false;
			}
			//return false;
			
        });
        
        function clearOptions(id) {
            document.getElementById(id).options.length = 0;
        }
        function addOption(selectbox, text, value) { // for adding options do dropdown
            var dropdown = document.getElementById(selectbox);
            var optn = document.createElement("OPTION");
            optn.text = text;
            optn.value = value;
            dropdown.options.add(optn);
        }

        
    });
</script>
<script type="text/javascript">
  
    function GetXmlHttpObject() {
        var xmlHttp = null;
        try {
            xmlHttp = new XMLHttpRequest();
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }
        return xmlHttp;
    }
    function ShowMenuGroup(fid, toid, loc, item_id,sel) {
	
	if (fid != "") {
			 var url = "getMenuGroup.php?menu_id=" + fid + "&sid=" + Math.random() + "&loc=" + loc;
			jQuery.ajax({
			url: url,
			cache: false
			}).done(function( html ) {
				
                    mearray = html.split("[]");
                    for (i = 0; i < mearray.length; i++) {
                        if (mearray[i] != "") {
                            valcat = mearray[i].split("{}");
                            oOption = document.createElement("Option");
                            oOption.text = valcat[1];
                            oOption.value = valcat[0];
							if(valcat[0]==sel)
							{
								oOption.selected = true;
							}
                            document.getElementById(toid).options.add(oOption);
                        }
                    }
                
			});
			
		} else {
            document.getElementById(toid).length = 1;
            document.getElementById(item_id).length = 1;
        }
	}
    function ShowItem(fid, toid, loc, menu_id,sel) {
	  if (fid  != "") {
				
			  var url = "getItem.php?menu_group=" + fid + "&sid=" + Math.random() + "&loc=" + loc + "&menu_id=" + document.getElementById(menu_id).value;
           jQuery.ajax({
			url: url,
			cache: false
			}).done(function( html ) {
                    mearray = html.split("[]");
                    for (i = 0; i < mearray.length; i++) {
                        if (mearray[i] != "") {
							$.get('checkItem.php?t=g&item='+valcat[0],function(data){
                                    if(data ==1){
									valcat = mearray[i].split("{}");
									oOption = document.createElement("Option");
									oOption.text = valcat[1];
									oOption.value = valcat[0];
									if(valcat[0]==sel)
									{
										oOption.selected = true;
									}
									document.getElementById(toid).options.add(oOption);
									}
                         });
                        }
						
                    }
                });
			
		
			} else {
            document.getElementById(toid).length = 1;
        }}
    function fechar() {
    //    parent.$.fancybox.close();
    }
    function validDM() {
        if (document.dmImage.menu_id.value == "") {
            jAlert('Select Menu!','Alert');
            return false;
        }
        if (document.dmImage.menu_group.value == "") {
            jAlert('Select Menu Group!','Alert');
            return false;
        }
        if (document.dmImage.item_id.value == "") {
            jAlert('Select Item!','Alert');
            return false;
        }
        if ((document.getElementById("image").value) != '' && !(check_extension(document.getElementById("image").value))) {

            return false;
        }
        if (document.dmImage.priority.value == "") {
            jAlert('Enter Priority!','Alert');
            return false;
        }else if(allnumeric(jQuery("#priority").val())==false){
			jAlert('Enter Numerical Values Only In Priority Field!','Alert');
			return false;
		}else{
	        return true;
		}	
    }
    var hash = {
        '.jpg': 1,
        '.JPG': 1,
        '.png': 1,
        '.PNG': 1,
        '.gif': 1,
        '.GIF': 1,
        '.jpeg': 1,
        '.JPEG': 1
    };
    function check_extension(filename) {
        var re = /\..+$/;
        var ext = filename.match(re);
  //      var submitEl = document.getElementById(submitId);
        if (hash[ext]) {
    //        submitEl.disabled = false;
            return true;
        } else {
            jAlert("Invalid Filename! Image Must Be png, jpg or gif.","Alert");
      //      submitEl.disabled = true;

            return false;
        }
    }
</script>
<script>
function open_reciepePopup(shoow){
	
	if(jQuery('#type').val()=='global'){
		
		jQuery('#RE_barcode').val(jQuery('#barcode_global').val());
		if(jQuery('#item').val()=='new'){
			jQuery('#RE_article_taxable').val(jQuery('#global_taxable1').val());
			jQuery('#RE_local_item_id,#RE_prep_shortnamec').val(jQuery('#global_description').val());			
		}else{
		jQuery('#RE_local_item_id,#RE_prep_shortnamec').val(jQuery('#item').find(":selected").text());		
		jQuery('#RE_article_taxable').val(jQuery('#global_taxable').val());		
		}
	}else{
		jQuery('#RE_local_item_id,#RE_prep_shortnamec').val(jQuery('#description').val());
		jQuery('#RE_article_taxable').val(jQuery('#local_taxable').val());
		jQuery('#RE_barcode').val(jQuery('#barcode').val());
		jQuery('#RE_price').val(jQuery('#default_price').val());
		jQuery('#RE_local_item_desc').val(jQuery('#notes1').val());
	}
	if(shoow=='Yes'){
	jQuery('#rc_image').attr('src',jQuery('#imagebox img').attr('src'));
	jQuery('#receipe_modal').modal('show');
	}
}
function submit_allform(val){
	if(val=='cancel'){
		jQuery('#insert_menuarticle').val('No');
	}else{
		jQuery('#article_price').val(jQuery('#RE_price').val());
		jQuery('#insert_menuarticle').val('Yes');
	}
	jQuery('#item_form').submit();
}
function imgdigital_btn()
{
			jQuery('#tempImage').click().change(function(evt){
				handleFileSelect(evt,'imgdigital');
				jQuery('body').focus();
			});
}
function handleFileSelect(evt,id) {
    var files = evt.target.files; // FileList object
	 var formdata;
		if (window.FormData) {
				formdata = new FormData();
		}
	  f = files[0];
      // Only process image files.
      if (!f.type.match('image.*')) {
        return false;
      }
	  
      var reader = new FileReader();
      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
			document.getElementById(id).innerHTML='<img class="imgpreview img-polaroid" src="'+e.target.result+'" title="'+theFile.name+'"/>';
			global_imgdigital_upload=true;
		};
      })(f);
      reader.readAsDataURL(f);
	  if (!formdata) {
			formdata.append("image", f);
	  }
}

jQuery(document).ready(function(){
        
		
		jQuery('#add-on A').click(function(){		
		if(jQuery(this).attr('rel')=='vendor'){
		jQuery('#keyword').val(jQuery('#default_vendor_search').val());
		}else{
		if(jQuery('#keyword').val().length<4){
		jAlert('Please enter More than 3 Characters','Alert Dialog');
		return false;
		}
		}
		getclients(2);
	});

		/* setTimeout(function() {
			//alert("S");
			if (jQuery('#type').val()!="") {
				window.location.reload(true);
			}
		},1500); */

});

function getclients1(){
	if(jQuery('#default_vendor_search').val().length<4){
		jAlert('Please enter More than 3 Characters!','Alert Dialog');
		return false;
	}else{
	jQuery('#add-on .icon-search').trigger('click');
	}
}
function getclients(val)
{var str;
if(val=="1"){
 	str = document.getElementById('default_vendor_search').value;
	}
else{	
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
     document.getElementById('default_vendor_search').value=str;
	 document.getElementById("modalcontent").innerHTML=xmlhttp.responseText;
	// document.getElementById('keyword').value="";
   // document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
xmlhttp.open("GET","vendor_search.php?q="+str,true);
xmlhttp.send();
}else{
jQuery('#modalcontent').html('');

}
}
function loadVendor(id,email,phone,name,image)
{
	jQuery('#default_vendor_search').val(name);	
	jQuery('#default_vendor').val(id);	
	jQuery('#filter_modal').modal('toggle');
}
</script>
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
</head>
<body>
<div id="pop_div" style="display:none;"></div>
<div class="mainwrapper">
  <?php include_once 'require/top.php';?>
  <div class="leftpanel">
    <?php include_once 'require/left_nav.php';?>
  </div>
  <!-- leftpanel -->
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="messages.php"><i class="iconfa-home"></i> </a> <span
					class="separator"></span></li>
      <li>Setup</li>
      <li><span class="separator"></span></li>
      <li>Inventory</li>
	  <li><span class="separator"></span></li>
      <li>Manage Items</li>
      <li class="right"><a href="" data-toggle="dropdown"
					class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
    <?php if($_GET['menu_art']>0){ ?>
    	<div style="float:right;margin-top: 11px;"> <a href="setup_retail_item_link.php" class="btn btn-primary btn-large">Back</a> </div>
	<?php }else{ ?>	
      <div style="float:right;margin-top: 11px;"> <a href="setup_backoffice_manage_items.php?group_id=<?php echo $_REQUEST['group_id']; ?>&market=<?php echo $_REQUEST['market']; ?>" class="btn btn-primary btn-large">Back</a> </div>
      <?php } ?>
      <div class="pageicon"> <span class="iconfa-cog"></span> </div>
      <div class="pagetitle">
        <h5>The manage items setup module allows you to manage all of your global, local, and preparation items.</h5>
        <h1>Manage Items</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
      <div class="row-fluid">
      <div class="span8" style="width:66% !important;">
        <div class="widgetbox">
          <h4 class="widgettitle"> <?php echo !isset($_REQUEST['id']) ? 'Add Inventory Items' : 'Edit Inventory Items'; ?> </h4>
          <div class="widgetcontent" style="min-height: 289px;">
            <form name="item_form" id="item_form" class="stdform" action="setup_finance_manage_items_add.php" method="post" enctype="multipart/form-data">
              <input type="hidden" id="menu_art_id" name="menu_art_id" value="<?php echo $_GET['menu_art']; ?>" >
              <input type="hidden" name="s_group_id" value="<?php echo $_GET['group_id']; ?>">
              <input type="hidden" name="s_market" value="<?php echo $_GET['market']; ?>">	
              <input type="hidden" name="subform" value="subform">
              <input id="id" type="hidden" name="id" value="<?php echo $rp->add_security($_REQUEST['id'])?>"/>
              <input type="hidden" name="new_add_type" id="new_add_type" value="" />

              <input type="hidden" name="default_vendor_req" value="<?php echo $_REQUEST['def_vendor']; ?>">

				<?php
					/*
					if(!isset($_GET['id']) || $_GET['id'] == ''){
				?>
				<p class="" style="margin-bottom:14px !important;">
                    <label>Barcode:</label>
                    <span class="field input-append"> 
                        <input style="width: 183px; padding:4px 5px !important;" onBlur="getbarcode_add(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode_global_add" id="barcode_global_add"  class="input-short" value="" />
                        <span class="add-on" style="height:20px;" >
    	                    <a href="javascript:void(0);" rel="client" onClick="getbarcode_add(2)" class="icon-search" ></a>
                        </span>
                        <span style="display:none;" id="ture_barcode" class="add-on1">
	                        <img style="height:15px; width:15px; margin-left:5px; margin-top:6px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
                        </span>
                    </span>
                </p>
                <?php
					}
					*/
                ?>

              <p>
                <label>Type:<span style="color:red">*</span></label>
                <span class="field">
                <?php if($_REQUEST['id']>0){ ?>
                <input type="hidden" name="type" value="<?php echo $row1['type'];?>">
                <select name="type1" style="width:223px;" id="type" class="uniformselect" disabled="disabled">
                <?php }else{?>
                <select name="type" style="width:223px;" id="type" class="uniformselect">
                <?php } ?>
                  <option value="">---Select Type---</option>
				  <?php /*?><?php if($_REQUEST['id'] == ''){ ?>
                  <option value="global">Global</option>
                  <?php }?>			  
<?php */?>       
				  <option value="global" <?php if($row1['type'] == 'global') {echo "selected='selected'";} ?>>Global</option>          
				  <option value="local" <?php if($row1['type'] == 'local') {echo "selected='selected'";} ?>>Local</option>
                  <option value="prep" <?php if($row1['type'] == 'prep') {echo "selected='selected'";} ?>>Prep</option>
                </select>
                </span> </p>
				<?php if ($_REQUEST['id'] != '') {?>
                 <p >
              		<label>Status:<span style="color:red">*</span></label>
              		<span class="field">
                    	<select style="width:223px;" name="status" id="status" class="uniformselect">
                          <option <?php if($row1['status']=='active'){ echo 'Selected'; } ?>  value="active">Active</option>
                          <option <?php if($row1['status']=='inactive'){ echo 'Selected'; } ?> value="inactive">Inactive</option>
                        </select>
                    </span>
              </p><?php }?>
                <p class="non-global" style="margin-bottom:14px !important;">
                    <label id="barcodeLbl"><?php if($row1['type'] == 'prep') { echo "Local";}?>&nbsp;Barcode:</label>
                    <span class="field input-append"> 
                        <input style="width: 183px; padding:4px 5px !important;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="<?php echo $row1['manufacturer_barcode']; ?>" />
                        <input type="hidden" id="barcode_valid" value="">
                        <span class="add-on" style="height:20px;" > 
        	                <!--<a href="" rel= 'client' onClick="getbarcode(1)" data-toggle="modal" data-target="#filter_modal" data-refresh="true" class="icon-search" style="position: relative;"> </a> -->
    	                    <a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search" ></a>
                        </span>
                        <span style="display:none;" id="ture_barcode" class="add-on1">
	                        <img style="height:15px; width:15px; margin-left:5px; margin-top:6px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
                        </span>
                    </span>
                </p>
                
              <p class="all_p">
              
               <label>Market:<span style="color:red">*</span></label>
                   <span class="field">
                          <select <?php if($_GET['menu_art']>=0){ $row1['Market']='Retail';  } ?>   class="dummy-market"  id="dummy_market" style="width:223px;" name="market">
                              <option value="">- - - Select Market - - -</option>
								<?php
								
									$sql = $rp->rp_query("SELECT * FROM inventory_market");
									while($fetch = $rp->rp_fetch_array($sql)) {?>
										<option value="<?php echo $fetch['description'];?>" <?php if($fetch['description'] == $row1['Market']){echo "selected";}?>>
											<?php echo $fetch['description'];?>
										</option>
									<?php }?>
                          </select>
                          <?php if($_GET['menu_art']>0){?>
                          	<script>
								jQuery(document).ready(function(){
									jQuery("#dummy_market").trigger('change');
								});
							</script>
                          <?php } ?>
                  </span>
              </p>
              <p class="all_p" >
                <label>Group:<span style="color:red">*</span></label>
                <span class="field">
                <select style="width:223px;" id="group" name="group" class="uniformselect">
                  <option value="">---Select Group---</option>
				    <?php 
						if(!empty($row1['local_group_id'])){
					while ($row2 = $rp->rp_fetch_array($result2)) { ?>
                  <option value="<?php echo $row2['id'];?>" <?php if ($row2['id'] == $row1['local_group_id']) {echo "selected='selected'";} ?>><?php echo $row2['description'];?></option>
                  <?php } }?>
                  
                </select>
                </span> </p>
             <p class="global">
                <label>Item:<span style="color:red">*</span></label>
                <span class="field">
                <select style="width:223px;" class="uniformselect" id="item" name="item">
                  <option>---Select Item---</option>
                  <option>Select group first!</option>
                </select>
                </span> </p>
             
                
              
              <p class="non-global">
                <label>Abbreviation:<span style="color:red">*</span></label>
                <span class="field">
                <input name="item_id" id="item_id" type="text" class="input-large" value="<?php echo $row1['local_item_id'];?>">
                </span> </p>
              <p class="non-global">
                <label>Name:<span style="color:red">*</span></label>
                <span class="field">
                <input name="description" id="description" type="text" class="input-large" value="<?php echo $row1['local_item_desc'];?>">
                </span> </p>
              <p class="non-global">
                <label>Priority:</label>
                <span class="field">
                <input name="priority" id="priority" type="text" class="input-large" value="<?php echo $row1['priority'];?>">
                </span> </p>
                <p class="non-global">
                <label>Notes:</label>
                <span class="field">                
                <textarea name="notes1"  id="notes1" class="" style="width:210px;" rows="7" cols="15"><?php echo $row1['local_item_notes'];?></textarea>
                </span> </p>
              
                 
				
				
              	
              
                
                <p class="non-global">
					<label>Taxable:</label>
					<span class="field">
						<select class="uniformselect" id="local_taxable" style="width:223px;" name="local_taxable">
	                        <!--<option value="">---Select Taxable---</option>-->
	                        <option <?php if($row1['local_taxable']=='no'){ echo 'selected';} ?> value="no">No</option>
	                        <option <?php if($row1['local_taxable']=='yes'){ echo 'selected';} ?> value="yes">Yes</option>
	                    </select>
					</span>
				</p>
                <p class="non-global">              	
                <label>Unit Type:</label>
                <div class="non-global selectouter12 select_w3" style="margin-bottom:15px;">
                <select style="width:223px;" class="uniformselect" id="unit_type" name="unit_type">
                  <option value="">---Select Unit Type here---</option>
                  <?php while ($row3 = $rp->rp_fetch_array($result3)) { ?>
                  <option data-description="<?php echo $row3['description']; ?>" value="<?php echo $row3['id'];?>" <?php if ($row3['id'] == $row1['local_unit_type']) {echo "selected='selected'";} ?>><?php echo $row3['unit_type'];?></option>
                  <?php } $rp->rp_data_seek($result3, 0); ?>
                </select>
                </div>
                
                 </p>
                 <p class="non-global none_prep">
					<label>Count:</label>
					<span class="field">
						<input name="inventory_count" id="inventory_count" type="text" class="input-large" value="<?php echo $row1['quantity']; ?>">
					</span>
				</p>
                 <p class="only_prep">
					<label>Unit Type Quantity:</label>
					<span class="field">
						<input name="local_unit_type_qty" id="local_unit_type_qty" type="text" class="input-large" value="<?php echo $row1['local_unit_type_qty'];?>">
					</span>
				</p>
				<p class="only_prep">
					<label>Produces Portions:</label>
					<span class="field">
						<input name="local_produces_portions" id="local_produces_portions" type="text" class="input-large" value="<?php echo $row1['local_produces_portions'];?>">
					</span>
				</p>				
                 <p class="only_prep">              	
                <label>Produces Unit Type:</label>
                <div class="only_prep selectouter12 select_w3" style="margin-bottom:15px;">
                <select style="width:270px;" class="uniformselect" id="local_produces_unit_type" name="local_produces_unit_type">
                  <option value="">---Select Produces Unit Type---</option>
                  <?php 
				  while ($row3 = $rp->rp_fetch_array($result3)) { ?>
                  <option data-description="<?php echo $row3['description']; ?>" value="<?php echo $row3['id'];?>" <?php if ($row3['id'] == $row1['local_unit_type']) {echo "selected='selected'";} ?>><?php echo $row3['unit_type'];?></option>
                  <?php } $rp->rp_data_seek($result3, 0); ?>
                </select>
                </div>
                
                 </p>
                
                
             <p class="non-global none_prep">
					<label>Default Manufacturer:</label>
					<span class="field">
						<input name="default_manufacturer" id="default_manufacturer" type="text" class="input-large" value="<?php echo $row1['default_manufacturer'];?>">
					</span>
				</p>
			<p class="non-global none_prep">
					<label>Default Brand:</label>
					<span class="field">
						<input name="default_brand" id="default_brand" type="text" class="input-large" value="<?php echo $row1['default_brand'];?>">
					</span>
				</p>
				<!--<p class="non-global none_prep">
					<label>Default Vendor:</label>
					<span class="field">
						<input name="search_vendor" id="search_vendor" autocomplete='off' type="text" class="input-large" value="<?php echo get_vendor_name($row1['default_vendor']);?>">
                        <input type="hidden" id="default_vendor" value="<?php echo $row1['default_vendor'];?>" name="default_vendor">
                        
					</span>
				</p>-->
                <div class="non-global none_prep">
					<label>Default Vendor:</label>
					<span class="field">
                    <div id="clientsearch" class="input-append">
						<input style="width:184px;" name="default_vendor_search" id="default_vendor_search" onKeyDown="javascript:if(event.keyCode==13){getclients1(2);return false;}" type="text" class="input-large" value="<?php echo $row1['default_vendor_name'];?>">
                        <span id="add-on" class="add-on" style="height:22px;" > <a href="" rel= 'vendor' data-toggle="modal" data-target="#filter_modal" data-refresh="true" class="icon-search" style="position: relative;"> </a> </span>
                        <input name="default_vendor" type="hidden"   value="<?php echo $row1['default_vendor'];?>"  id="default_vendor" />
					</div>
                    </span>
				</div>
                
                <p class="non-global none_prep">
					<label>Default Cost Price:</label>
					<span class="field">
						<input name="default_cost_price" id="default_cost_price" onKeyUp="checkDec(this);" onBlur="add_decimal(this)" type="text" class="input-large" value="<?php echo number_format($row1['default_cost_price'],2,'.',',');?>">
					</span>
				</p>
				
				<p class="non-global none_prep">
					<label>Default Retail Price:</label>
					<span class="field">
						<input name="default_price" id="default_price" type="text" onKeyUp="checkDec(this);" onBlur="add_decimal(this)" class="input-large" value="<?php echo number_format($row1['default_price'],2,'.',',');?>">
					</span>
				</p>
				
				
				
				
				<p class="non-global">              	
                <label>Low Alert Unit Type:</label>
                <div class="non-global selectouter12 select_w3" style="margin-bottom:15px;">
                <select style="width:223px;" class="uniformselect" id="low_alert_unittype" name="low_alert_unittype">
                  <option value="">---Select Low Alert Unit Type---</option>
                  <?php 
				  $result_alert = $rp->rp_query("SELECT id, unit_type,description FROM inventory_item_unittype ORDER BY unit_type ASC") or die(mysql_error());
				  while ($row_alert = $rp->rp_fetch_array($result_alert)) { ?>
                  <option data-description="<?php echo $row_alert['description']; ?>" value="<?php echo $row_alert['id'];?>" <?php if ($row_alert['id'] == $row1['low_alert_unittype']) {echo "selected='selected'";} ?>><?php echo $row_alert['unit_type'];?></option>
                  <?php } $rp->rp_data_seek($result_alert, 0); ?>
                </select>
                </div>
                
                 </p>
				
				
					<p class="non-global ">
					<label>Low Alert Count:</label>
					<span class="field">
						<input type="text" name="low_alert_count" class="input-large" id="low_alert_count" onKeyUp="checkDec(this);" onBlur="add_decimal(this);" value="<?php echo $row1['low_alert_count'];?>" >
					</span>
				</p>
				
				
				<p class="non-global ">
					<label>Created By:</label>
					<span class="field">
						<input type="text" readonly name="created_by" class="input-large" id="created_by" value="<?php echo $row1['created_emp'];?>" >
					</span>
				</p>
				
			 
			
				
				
				<p class="non-global ">
					<label>Created On:</label>
					<span class="field">
						<input type="text" readonly name="created_on" class="input-large" id="created_on" value="<?php echo $row1['created_on'];?>" >
					</span>
				</p>
				
				
				<p class="non-global ">
					<label>Created Date & Time:</label>
					<span class="field">
						<input type="text" readonly name="created_datetime" class="input-large" id="created_datetime" value="<?php echo GetLocationTimeFromServer_general($_SESSION['loc'],$row1['created_datetime']); ?>" >
					</span>
				</p>
				
				<p class="non-global ">
					<label>Last By:</label>
					<span class="field">
						<input type="text" readonly name="last_by" class="input-large" id="last_by" value="<?php echo $row1['last_emp'];?>" >
					</span>
				</p>
				
				
				
				<p class="non-global ">
					<label>Last On:</label>
					<span class="field">
						<input type="text" readonly name="last_on" class="input-large" id="last_on" value="<?php echo $row1['last_on'];?>" >
					</span>
				</p>
				
				<p class="non-global ">
					<label>Last Date & Time:</label>
					<span class="field">
						<input type="text" readonly name="last_datetime" class="input-large" id="last_datetime" value="<?= $row1['last_datetime']; ?><?php //echo isset($_REQUEST['id']) ? GetLocationTimeFromServer_general($_SESSION['loc'],$row1['last_datetime']) : '' ?>" >
					</span>
				</p>
				
				
              <!--copy pasted code start-->
              
              <!--pasted code over-->
              <!--								
									
									<div class="par non-global">
									<label>Image:</label>
									<div data-provides="fileupload"
										class="fileupload fileupload-new">
										<div class="input-append">
											<div class="uneditable-input span3">
												<i class="iconfa-file fileupload-exists"></i>
												<span class="fileupload-preview"></span>
											</div>
											<span class="btn btn-file">
												<span class="fileupload-new">Select file</span>
												<span class="fileupload-exists">Change</span>
												<input type="file" name="image" id="image">
												
											</span>
											<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
											<input type="hidden" name="old_image" id="old_image" value="<?php echo $row1['local_item_image']; ?>"/>
										</div>
									</div>
									</div>
									-->
              <!--comment over from me--->
              <!--
								<p class="non-global">
					                <label>Image:</label>
					                <span class="field">
					                    <img src="<?php echo API;?>images/<?php echo $row1['local_item_image'];?>" style="margin-bottom: 15px; width:100px;"/>
					                    <input type="file" name="image" id="image"/>
					                    <input type="hidden" name="old_image" id="old_image" value="<?php echo $row1['local_item_image']; ?>"/>
					                </span>
					            </p>
					             -->
              <div class="global-new">
                <p>
                  <label>Name:</label>
                  <span class="field">
                  <input name="global_description" id="global_description" type="text" class="input-large" value="">
                  </span> </p>
                <p>
                  <label>Item ID:</label>
                  <span class="field">
                  <input name="global_itemid" id="global_itemid" type="text" class="input-large" value="">
                  </span> </p>
                <p>
                  <label>Priority:</label>
                  <span class="field">
                  <input name="global_priority" id="global_priority" type="text" class="input-large" value="">
                  </span> </p>
                <p>
                  <label>Unit Type:</label>
                  <span class="field">
                  <select style="width:223px;" name="global_unittype" id="global_unittype" class="uniformselect">
                    <option value="">---Select Unit type---</option>
                    <?php 
					$query3 = "SELECT id, unit_type,description FROM inventory_item_unittype ORDER BY unit_type ASC";
					$result3 = $rp->rp_query($query3);
					while ($row3 = $rp->rp_fetch_array($result3)) { ?>
                    <option value="<?php echo $row3['id'];?>" <?php if ($row3['id'] == $row1['local_unit_type']) {echo "selected='selected'";} ?>><?php echo $row3['unit_type'];?></option>
                    <?php } $rp->rp_data_seek($result3, 0); ?>
                  </select>
                  </span> </p>
                <p>
                  <label>Notes:</label>
                  <span class="field">
                  <textarea name="global_notes" id="global_notes" class="" style="width:210px;" rows="5" cols="22"></textarea>
                  </span> </p>
                <p>
                  <label>Taxable:</label>
                  <span class="field">
                  <select style="width:223px;" name="global_taxable" id="global_taxable1" class="uniformselect">
                    <!--<option value="">---Select Taxable---</option>-->
                    <option value='no'>No</option>
                    <option value='yes'>Yes</option>
                  </select>
                  </span> </p>
                  
                <?php if($row1['local_item_image'] != ''){?>  
                <p>
                  <label>Image:</label>
                  <span class="field"> <img  src="<?php echo APIIMAGE;?>images/<?php echo $row1['local_item_image'];?>" onerror="this.src='images/defimgpro.png'" style="margin-bottom: 15px;width:100px;" /> </span> </p><?php } ?>
              </div>
              
              <input id="step_child" name="step" type="hidden" value="<?php echo $step;?>" />
              <input type="hidden" name="digital_image_name" id="digital_image_name" value="">
              <?php if($_GET['menu_art']>0){ ?>
			  <input type="hidden" name="insert_menuarticle" id="insert_menuarticle" value="Yes" >
			  <?php }else{ ?>
              <input type="hidden" name="insert_menuarticle" id="insert_menuarticle" value="No" >              
              <?php } ?>
              <input type="hidden" name="article_price" value="" id="article_price" >
              <div class="loaded"></div>
             <p class="colorbox all_p" style="display:none !importnat;"></p>
                 <!--<label style="width:87px;">Image:</label>
                <span class="field" style="width:25%">
                <?php $str=explode('/',$row1[local_item_image]) ?>
                <input type="hidden" name="digital_image_name" id="digital_image_name" value=" ">
                <input type="hidden" name="digital_image_delete" id="digital_image_delete" value="N">
               </span>
               </p> -->
              <div id="last_row">
                <p style="margin-left:5px;">
                  <button id="submit_from" type="submit" class="btn btn-primary">Submit</button>
                  <?php if($_REQUEST['id']==""){?>
                  <button type="button" onClick="window.location.href='setup_finance_manage_items_add.php'" class="btn btn-primary">Reset</button>
                  <?php } ?>
                </p>
              </div>
            </form>
          </div>
        </div>
        </div>
        <div class="span4" style="width:33% !important; margin-left:1% !important;">
        	<div class="widgetbox profile-photo">
                                    <div class="headtitle">
                                        <div id="image_drop" style=" <?php if($row1['type']!="global" && $row1['type']!="" ){ ?> display:block; <?php }else{?> display:none; <?php } ?> " class="btn-group">
                                            <button  data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                                            <ul  class="dropdown-menu"  >
                                              <li>
                                              <a data-target="#imageModal" href="upload_manage_item_image.php?id=<?php echo $_REQUEST['id']; ?>"  data-toggle="modal" id="imageLink">Upload Images</a> </span> </p></li>
                                              <li><a href="#imgTakephoto" data-toggle="modal">Take Photo</a></li>
                                              
                                            </ul>
                                        </div>    

                                        <h4 class="widgettitle">Item Image</h4>
                                    </div>
                                    <div class="widgetcontent">
									<p style="margin:0;padding:0;text-align:center;"> (Image Size Required 225w x 225h) </p>
                                        <div class="profilethumb">
                                            <div id="imagebox">
                                            
                                            <?php
												if($row1['local_item_image'] != ""){
													if(strpos($row1['local_item_image'], 'http') !== FALSE) {
											?>
                                            			<img class="img-polaroid"  src="<?php echo $row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" data-img="1" />
											<?php
														//$img_path=$row1['digital_image_name'];
													} else {
											?>
                                            			<img class="img-polaroid"  src="<?php echo APIIMAGE."images/".$row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" data-img="2" />
											<?php
														//$img_path=APIIMAGE ."images/". $row1['local_item_image'];
													}
												} else {
											?>
                                            		<img src="images/defimgpro.png" style="height:250px;" alt="" class="img-polaroid" id="mainimage" data-img="3" />
											<?php
												}
                                            ?>
                                            
											<?php /* if($row1['local_item_image']!="" ){ ?>
                                            <img class="img-polaroid"  src="<?php echo APIIMAGE."images/".$row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" >
                                            <?php }else{ ?>
											<img src="images/defimgpro.png" style="height:250px;" alt="" class="img-polaroid" id="mainimage"/>
											<?php } */ ?>
                                            
											</div>											
                                        </div><!--profilethumb-->
                                        
                                    </div>  
                            </div>
        </div>
        </div>
        <?php include_once 'require/footer.php';?>
        <!--footer-->
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->
<div id="imageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Add/Edit Image</h3>
  </div>
  <div class="modal-body " id="mymodalhtml"> </div>
  <div class="modal-footer" style="text-align:center;">
    <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
    <button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
  </div>
</div>

<div id="filter_modal" style="height:600px !important;" class="modal hide fade">

    <div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
      <h3>Search Vendor</h3>
      <br>
      <label style="width:auto !important;">Search:&nbsp;&nbsp;
		  <div class="input-append">
			  <input name="keyword" id="keyword" type="text"  onKeyUp="javascript:getclients(2)"  
			  tabindex="0" style="width:400px;"  />
			  <span class="add-on" ><a href="javascript:void(0);" class="icon-search" ></a></span>
		  </div>
      </label>
    </div>
    <div class="modal-body" id="modalcontent" style="height:415px;"> </div>
    <div class="modal-footer" style="text-align: center;">
      <p >
        <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
      </p>
    </div>
</div>

<!--------receipr popup--------->

<div id="receipe_modal" style="height:547px !important;" class="modal hide fade">
	<div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
      <h3>Add Menu Article</h3>
    </div>
    <div class="modal-body" style="min-height:400px;">
    	<table class="table" style="border-color:#FFFFFF;table-layout: fixed;">
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Status:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input type="text" style="width:230px;height:27px; padding:0 6px;" id="status" readonly name="status" value="Active">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Location:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="location" name="location" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="<?php echo $_SESSION['loc_name']; ?>">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Item:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_local_item_id" name="RE_local_item_id" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="">
            </td>
         </tr>
          <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Description:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_local_item_desc" name="RE_local_item_desc" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Article Type:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_article_type" name="RE_article_type" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="Other">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Price:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_price" name="RE_price" onKeyUp="checkDec(this);" onBlur="add_decimal(this)" type="text" style="width:230px;height:27px; padding:0 6px;"    value="">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Plu:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_plu" name="RE_plu" type="text" style="width:230px;height:27px; padding:0 6px;" readonly  value="<?php echo $max_plu; ?>">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Taxable:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_article_taxable" name="RE_article_taxable" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Image:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <span class="imagebox">
            	<img id="rc_image" onerror="this.src='images/defimgpro.png'	" src="images/defimgpro.png"  style="height:80px; width:80px;" class="image-polaride">
            </span>
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Barcode:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_barcode" name="RE_barcode" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="">
            </td>
         </tr>
         <tr>
            <td style="width:25%;border:none;padding-top:12px;" valign="top">Shortname:</td>
            <td style="padding-left: 10px;border:none;" valign="middle">
            <input id="RE_prep_shortnamec" name="RE_prep_shortname" type="text" style="width:230px;height:27px; padding:0 6px;"  readonly  value="">
            </td>
         </tr>
        </table>
    </div>
    <div class="modal-footer" style="text-align: center;">
      <p >
        <button data-dismiss="modal" onClick="submit_allform('cancel')" class="btn">Cancel</button>
        <button data-dismiss="modal" onClick="submit_allform('submit')" class="btn btn-primary">Submit</button>
      </p>
    </div>
</div>

<!--------------over------------------>


<div id="imgTakephoto" class="modal hide fade" >
                             <div class="modal-header">
                                 <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                                 <h3 id="imgModalLabel1">Take Photo</h3>
                             </div>
                             <div class="modal-body">
                                  <div class="par" style="text-align: center;">
                                     <div id="webcam"> 
                                        <script>
                                                        jQuery("#webcam").html(webcam.get_html(320, 240));
                                                       // document.write(webcam.get_html(320, 240));
                                                    </script> 
                                      </div>
                                      <div style="margin:10px 0 !important;">
                                      <input type=button value="Configure..." class="btn" onClick="webcam.configure();">
                                      &nbsp;&nbsp;
                                      <input type=button value="Take Snapshot" class="btn" onClick="webcam.snap();">
                                      &nbsp;&nbsp;
                                      <input type="hidden" name="tookimage" id="tookimage" value="">
                                      </div>
                                 </div>
                                 <p class="stdformbutton" style="margin-left:0px; text-align:center;">
                                   <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
                                   <button data-dismiss="modal" class="btn btn-primary">Save Changes</button>
                                 </p>

                          </div>
                     </div>

</body>
</html>
<script>
var global_imgdigital_upload=false;
var global_imgdigital_upload=false;
var global_croped_image =false;

    webcam.set_api_url('webcam_upload_inventory.php?path=inventory');
        webcam.set_quality(90); // JPEG quality (1 - 100)
        webcam.set_shutter_sound(false); // play shutter click sound

        webcam.set_hook('onComplete', 'my_callback_function');
                
        function my_callback_function(response) {
			var res = response.split("/"); 			
            jQuery("#digital_image_name").val(res[1]);
            jQuery('#tempImage').val();
            jQuery("#mainimage").attr("src",'<?php echo APIIMAGE?>images/'+response);
        }
	</script>
<script>
var codeid=0;
var cObject="";
function resetBtn()
{
	if(codeid==0)
	{
		document.getElementById("digital_menu_form").reset();
	}
	else
	{
		if(confirm("Do you want to discard your changes?"))
		{
			loadData(cObject);
		}
	}
}

function loadData(cObject)
{		
		jQuery("#menu_id").val(cObject.data("menuid"));
		jQuery("#glass_add").val(cObject.data("glassad"));
		jQuery("#priority").val(cObject.data("proty"));
       
        jQuery("#codeid").val(cObject.data("id"));
		codeid=cObject.data("id");	
		jQuery("#imageLink").attr("href","upload_digital_menu_image.php?id="+codeid);
      	jQuery("#videoLink").attr("href","upload_digital_menu_video.php?id="+codeid);
		if(cObject.data("image")!="")
		{
			
			jQuery("#imagebox").html('<img src="<?php echo APIIMAGE;?>images/'+cObject.data("image")+'" width="100px;">');
		}
		else
		{
			jQuery("#imagebox").html('');
		}
		if(cObject.data("video")!="")
		{
			var video='<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" >';
               video+=' <param name="movie" value="player.swf"/>';
             video+='   <param name="allowfullscreen" value="true"/>';
            video+='    <param name="allowscriptaccess" value="always"/>';
            video+='    <param name="wmode" value="opaque"/>';
            video+='    <param name="flashvars" value="file=<?php echo APIIMAGE; ?>images/'+cObject.data("video")+'&image=images/softpoint.jpg"/>';
           video+='     <embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf" allowscriptaccess="always" allowfullscreen="true" flashvars="file=<?php echo APIIMAGE; ?>images/'+cObject.data("video")+'&image=images/softpoint.jpg"/>';
            video+='    </object>';
			jQuery("#video_canvas").html(video);
		}
		else
		{
			jQuery("#video_canvas").html('');
		}
		
		ShowMenuGroup(cObject.data("menuid"),'menu_group','<?=$_SESSION['loc']?>','item_id',cObject.data("menugroup"));
		ShowItem(cObject.data("menugroup"),'item_id','<?=$_SESSION['loc']?>','menu_id',cObject.data("itemid"));
		
    
}

jQuery(document).on('click', '[data-toggle="datafill"]', function(e){
            e.preventDefault();
            cObject = jQuery(this);
			loadData(cObject);
          
});
 jQuery(document).ready(function(){ 
 
 jQuery('#videoModal').on('hidden', function() {
    jQuery(this).removeData('modal');
});
  jQuery('#imageModal').on('hidden', function() {
    jQuery(this).removeData('modal');
	jQuery(".modal-backdrop").remove();
});
         
   /* jQuery(".codedata").click(function(){
		alert("");
		cObject=jQuery(this);
	    loadData(cObject);
    });
    */
    jQuery(".addcode").click(function(){
		 codeid=0;
		 cObject="";
	     jQuery("#menu_id").val("");
		 jQuery("#menu_group").val("");
		 jQuery("#item_id").val("");
		 jQuery("#glass_add").val("");
		 jQuery("#glass_add").val("");
		 jQuery("#priority").val("");
		 jQuery("#menu_group").val("");
		 jQuery("#item_id").val("");
		 
		 
		jQuery("#imagebox").html('');
		jQuery("#video_canvas").html('');
        jQuery("#codeid").val("");
		
		jQuery("#digital_image_name").val("");
		jQuery("#digital_image_delete").val("N");
		jQuery("#digital_video_name").val("");
		jQuery("#digital_video_delete").val("N");
		
		jQuery("#imageLink").attr("href","upload_digital_menu_image.php?id=0");
      	jQuery("#videoLink").attr("href","upload_digital_menu_video.php?id=0");
		
    })
	jQuery("#upload_image").click(function(){
		jQuery("#colorbox_img").attr("href","upload_digital_menu_image.php?id="+codeid);
		jQuery("#colorbox_img").click();
});
	
	jQuery("#upload_video").click(function(){
		jQuery("#colorbox_video").attr("href","upload_digital_menu_video.php?id="+codeid);
		jQuery("#colorbox_video").click();
	});
	
//		jQuery(".colorbox a").colorbox();
	
	
	
	
	 /*jQuery('#search_vendor').typeahead({
				source: function (query, process) {
					return jQuery.ajax({
						url: 'vendor_autocomplete.php',
						type: 'post',
						data: { query: query,  autoCompleteClassName:'autocomplete',
						selectedClassName:'sel',
						attrCallBack:'rel',
						identifier:'estado'},
						dataType: 'json',
						success: function (result) {
			//alert(result);
							var resultList = result.map(function (item) {
								var aItem = { id: item.id, name: item.label};
								return JSON.stringify(aItem);
							});
						//	alert(resultList);
							return process(resultList);
			
						}
					});
				},
			
			matcher: function (obj) {
					var item = JSON.parse(obj);
					return ~item.name.toLowerCase().indexOf(this.query.toLowerCase());
				},
			
				sorter: function (items) {       
				   var beginswith = [], caseSensitive = [], caseInsensitive = [], item;
					while (aItem = items.shift()) {
						var item = JSON.parse(aItem);
						if (!item.name.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(JSON.stringify(item));
						else if (~item.name.indexOf(this.query)) caseSensitive.push(JSON.stringify(item));
						else caseInsensitive.push(JSON.stringify(item));
					}
			
					return beginswith.concat(caseSensitive, caseInsensitive);
			
				},
			
				highlighter: function (obj) {
					var item = JSON.parse(obj);
					var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
					var locvalue=item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
						return '<strong>' + match + '</strong>'
					})
				//alert(locvalue);
					return locvalue;//+" (ID#: "+item.id+")";
				},
			
				updater: function (obj) {
					var item = JSON.parse(obj);
					jQuery('#default_vendor').attr('value', item.id);
					
					//estadoCallback();
					var nm=item.name+" (ID#: "+item.id+")";
					//alert(item.name);
					jQuery('#search_vendor').attr('value', item.name);
					return item.name;
				}
			});*/
	
	
	
	
	
});
  jQuery(document).on('change','#dummy_market',function(){
	
	  var market = jQuery(this).val();
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup2.php",
		data: { market: market}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group").html(msg);
		});
	
	});
	
var bar_xhr2 = null;
function getbarcode_add(val){
	var search_val = jQuery("#barcode_global_add").val();
	/*var search_val = "";
	if(val==1){
	search_val = jQuery("#barcode").val();
	jQuery("#keyword").val(search_val);
	}else{
	search_val = jQuery("#keyword").val();
	jQuery("#barcode").val(search_val);
	}*/
	
	if(search_val!=""){
		if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
			jConfirm('Invalid Barcode Number!', 'Alert!', function(r) {
			//jQuery('#barcode').focus();		     
		});
		jQuery('#popup_cancel').remove();
		return false;
	}
	if( search_val.length<6){
		jConfirm('Please Enter Minimum 6 Digit Valid Barcode Number Only!', 'Alert!', function(r) {
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
	if(bar_xhr2!=null){
		bar_xhr2.abort();
		bar_xhr2 =null;
	}
	bar_xhr2 = jQuery.ajax({
		url:'search_fectual_barcode.php',
		type:'POST',
		data:{search_val:search_val},
		success:function(data){
			//jQuery('#modalcontent').html(data);
			if(data){
				// if(data=="b_found"){
				if(data=="lii_found"){
					//jAlert('This barcode already in use!','Alert Dialog');
					jAlert('This Item is already configured for your Location!', 'Alert Dialog');
					jQuery('#item_id').val("");
					jQuery('#description').val("");
					jQuery('#global_description').val("");
					jQuery('#notes1').val("");
					jQuery('#global_notes').val("");
					jQuery('#manufacturer_barcode').val('');
					jQuery('#ture_barcode').hide();
				} else if(data=="ii_found"){
					jAlert('This is a Global Item please select it as a Global Item!', 'Alert Dialog');
					jQuery('#item_id').val("");
					jQuery('#description').val("");
					jQuery('#global_description').val("");
					jQuery('#notes1').val("");
					jQuery('#global_notes').val("");
					jQuery('#manufacturer_barcode').val('');
					jQuery('#ture_barcode').hide();
				}else{
					
					jQuery('#barcode_valid').val('Yes');
					var data = data.split('^');
					//jQuery('#item_id').val(data[0]);
					jQuery('#item_id').val("");
					//jQuery('#description').val(data[0]);
					jQuery('#description').val("");
					//jQuery('#global_description').val(data[0]);
					jQuery('#global_description').val("");
					//jQuery('#notes1').val(data[1]);
					jQuery('#notes1').val("");
					//jQuery('#global_notes').val(data[1]);
					jQuery('#global_notes').val("");
					console.log(data[2]);
					if(data[2]!=""){
					//jQuery('#imagebox').html('<img src="'+data[2]+'" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					jQuery('#imagebox').html('<img src="" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					//jQuery('#digital_image_name').val(data[2]);
					jQuery('#digital_image_name').val("");
					}else{
					jQuery('#mainimage').html('');
					jQuery('#digital_image_name').val('');
					}
					jQuery('#ture_barcode').show();				
					jQuery('#manufacturer_barcode').val(search_val);
					
					/* jQuery('#barcode_valid').val('Yes');
					var data = data.split('^');
					jQuery('#item_id').val(data[0]);
					jQuery('#description').val(data[0]);
					jQuery('#global_description').val(data[0]);
					jQuery('#notes1').val(data[1]);
					jQuery('#global_notes').val(data[1]);
					console.log(data[2]);
					if(data[2]!=""){
					jQuery('#imagebox').html('<img src="'+data[2]+'" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					jQuery('#digital_image_name').val(data[2]);
					}else{
					jQuery('#mainimage').html('');
					jQuery('#digital_image_name').val('');
					}
					jQuery('#ture_barcode').show();				
					jQuery('#manufacturer_barcode').val(search_val); */
				}
			}else{
				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#manufacturer_barcode').val('');
				jQuery('#ture_barcode').hide();
				jQuery('#item_id').val("");
				jQuery('#description').val("");
				jQuery('#global_description').val("");
				jQuery('#notes1').val("");
				jQuery('#global_notes').val("");
				jQuery('#imagebox').html('');
				jQuery('#digital_image_name').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}
}

	
var bar_xhr = null;
function getbarcode(val){
	var search_val = jQuery("#barcode").val();
	/*var search_val = "";
	if(val==1){
	search_val = jQuery("#barcode").val();
	jQuery("#keyword").val(search_val);
	}else{
	search_val = jQuery("#keyword").val();
	jQuery("#barcode").val(search_val);
	}*/
	
	if(search_val!=""){
		if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
			jConfirm('Invalid Barcode Number!', 'Alert!', function(r) {
			//jQuery('#barcode').focus();		     
		});
		jQuery('#popup_cancel').remove();
		return false;
	}
	if( search_val.length<6){
		jConfirm('Please Enter Minimum 6 Digit Valid Barcode Number Only!', 'Alert!', function(r) {
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
	if(bar_xhr!=null){
		bar_xhr.abort();
		bar_xhr =null;
	}
	bar_xhr = jQuery.ajax({
		url:'search_fectual_barcode.php',
		type:'POST',
		data:{search_val:search_val},
		success:function(data){
			//jQuery('#modalcontent').html(data);
			if(data){
				// if(data=="b_found"){
				if(data=="lii_found"){
					//jAlert('This barcode already in use!','Alert Dialog');
					jAlert('This Item is already configured for your Location!', 'Alert Dialog');
					jQuery('#item_id').val("");
					jQuery('#description').val("");
					jQuery('#global_description').val("");
					jQuery('#notes1').val("");
					jQuery('#global_notes').val("");
					jQuery('#manufacturer_barcode').val('');
					jQuery('#ture_barcode').hide();
				} else if(data=="ii_found"){
					jAlert('This is a Global Item please select it as a Global Item!', 'Alert Dialog');
					jQuery('#item_id').val("");
					jQuery('#description').val("");
					jQuery('#global_description').val("");
					jQuery('#notes1').val("");
					jQuery('#global_notes').val("");
					jQuery('#manufacturer_barcode').val('');
					jQuery('#ture_barcode').hide();
				}else{
					
					jQuery('#barcode_valid').val('Yes');
					var data = data.split('^');
					//jQuery('#item_id').val(data[0]);
					jQuery('#item_id').val("");
					//jQuery('#description').val(data[0]);
					jQuery('#description').val("");
					//jQuery('#global_description').val(data[0]);
					jQuery('#global_description').val("");
					//jQuery('#notes1').val(data[1]);
					jQuery('#notes1').val("");
					//jQuery('#global_notes').val(data[1]);
					jQuery('#global_notes').val("");
					console.log(data[2]);
					if(data[2]!=""){
					//jQuery('#imagebox').html('<img src="'+data[2]+'" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					jQuery('#imagebox').html('<img src="" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					//jQuery('#digital_image_name').val(data[2]);
					jQuery('#digital_image_name').val("");
					}else{
					jQuery('#mainimage').html('');
					jQuery('#digital_image_name').val('');
					}
					jQuery('#ture_barcode').show();				
					jQuery('#manufacturer_barcode').val(search_val);
					
					/* jQuery('#barcode_valid').val('Yes');
					var data = data.split('^');
					jQuery('#item_id').val(data[0]);
					jQuery('#description').val(data[0]);
					jQuery('#global_description').val(data[0]);
				//	jQuery('#notes1').val(data[1]);
					jQuery('#global_notes').val(data[1]);
					console.log(data[2]);
					if(data[2]!=""){
					jQuery('#imagebox').html('<img src="'+data[2]+'" width="250px;"  alt="" class="img-polaroid" id="mainimage">');
					jQuery('#digital_image_name').val(data[2]);
					}else{
					jQuery('#mainimage').html('');
					jQuery('#digital_image_name').val('');
					}
					jQuery('#ture_barcode').show();				
					jQuery('#manufacturer_barcode').val(search_val); */
				}
			}else{
				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#manufacturer_barcode').val('');
				jQuery('#ture_barcode').hide();
				jQuery('#item_id').val("");
				jQuery('#description').val("");
				jQuery('#global_description').val("");
				jQuery('#notes1').val("");
				jQuery('#global_notes').val("");
				jQuery('#imagebox').html('');
				jQuery('#digital_image_name').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}
}
function MakechangeBarcode(){
	jQuery('#barcode_valid').val('');
	}
function add_decimal(inp){
	var val = parseFloat(jQuery(inp).val());	
	console.log(val);	
	if(val!="" && val!=null && !isNaN(val)){
		if(val!=0){
		val = val.toFixed(2);
		}else{
		val = 0.00;
		}
		jQuery(inp).val(val);
	}else{
		jQuery(inp).val(0.00);
	}
	
}

function checkDec(el){
 var ex = /^[0-9]+\.?[0-9]*$/;
 console.log(el.value);	
 if(ex.test(el.value)==false){
	jQuery(el).val('');
	   el.value = el.value.substring(0,el.value.length - 1);
  
  }
}

</script>
