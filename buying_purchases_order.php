<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

$storeHead     = "active";
$storeDropDown     = "display: block;";
$backofficeMenu5      = "active";

$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$login_vendor_id = $vender['StorePoint_vendor_Id'];

$purid = $_GET["purchase_id"];

$jsonurl = "api/getlocationtime.php?intLocationID=".$_GET['loc_id']."&server_time=";
$json = @file_get_contents($jsonurl,0,null,null);
$datetimenow= $json ;
$datetimenowk1=explode(",",$datetimenow);
$date_new = explode(":",$datetimenowk1['1']);
$pieces = explode(" ",str_replace('"','',$date_new[1]));
$cur_date = $pieces[0];
$cur_time = $pieces[1].':'.$date_new[2].':'.str_replace('"}','',$date_new[3]);
$ldatetitme = $cur_date.' '.$cur_time;

function get_empmaster($emp_id){
	$query =mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees_master where empmaster_id =".$emp_id));
	return $query['name'];
}
function add_breket($val){
	if($val<0){
		$val = '('.number_format(abs($val),2,'.',',').')';
	}else{
		$val = number_format($val,2,'.',',');
	}
	return '$'.$val;
}

function check_vendor_to_location_items($vendor_id,$location_id,$ven_item_id,$qty){

		$time = date("H:i:s",strtotime($ldatetitme));
   	 	$date = date("Y-m-d",strtotime($ldatetitme));

				$query = "SELECT storeroom_id FROM location_inventory_storerooms WHERE location_id='".$location_id."' AND stroom_id='General'";
				$result = mysql_query($query) or die(mysql_error());
				$num_rows = mysql_num_rows($result);
				if($num_rows < 1){
					$query2 = "INSERT INTO location_inventory_storerooms SET location_id='" . $location_id . "', description='General Storeroom', stroom_id='General'";
					$result2 = mysql_query($query2) or die(mysql_error());
					$storeroom = mysql_insert_id();
				}else{
					$row = mysql_fetch_array($result);
					$storeroom = $row['storeroom_id'];
				}

		$check_query = "SELECT id from location_inventory_items where location_id = '".$location_id."' and inv_item_id =(select inv_item_id from vendor_items where id = '".$ven_item_id."') and type ='global'";
		$res_ceck = mysql_query($check_query) or die(mysql_error());
		$ven_row = mysql_fetch_array(mysql_query("SELECT inv_item_id,price FROM vendor_items WHERE inv_item_id = '".$ven_item_id."'"));
		$inv_q = mysql_fetch_array(mysql_query("SELECT brand,taxable,manufacturer_barcode,manufacturer,inv_group_id,description,notes,unit_type FROM inventory_items WHERE id ='".$ven_row['inv_item_id']."'"));
		$taxable	= ($inv_q['ni_taxable'] != '') ? mysql_real_escape_string($inv_q['ni_taxable']) : '0';
		if(mysql_num_rows($res_ceck)==0){

				$query = "INSERT INTO location_inventory_items
				SET location_id = '".$location_id . "',
				 taxable='".$taxable."',
				 manufacturer_barcode='".mysql_real_escape_string($inv_q['manufacturer_barcode'])."',
				 inv_item_id='".mysql_real_escape_string($ven_row['inv_item_id'])."',
				 status='active',
				 created_by = '".$_SESSION['employee_id']."',
				 created_on ='BusinessPanel',
				 created_datetime='".date('Y-m-d H:i:s')."',
				 priority='0',
				 total_count='".$qty."',
				 total_needed='0.00',
				 local_group_id = '".mysql_real_escape_string($inv_q['inv_group_id'])."',
				 local_item_desc = '".mysql_real_escape_string($inv_q['description'])."',
				 local_item_notes = '".mysql_real_escape_string($inv_q['notes'])."',
				 local_unit_type = '".mysql_real_escape_string($inv_q['unit_type'])."',
				 default_manufacturer = '".mysql_real_escape_string($inv_q['manufacturer'])."',
				 default_brand='".mysql_real_escape_string($inv_q['brand'])."'";
		$result = mysql_query($query) or die(mysql_error());
        $new_item_id = mysql_insert_id();

		$query4 = "INSERT INTO location_inventory_storeroom_items SET
						location_id=".$location_id.",
						storeroom_id='".$storeroom."',
						inv_item_id='".$new_item_id."'";
		$result = mysql_query($query4) or die(mysql_error());
		}else{
			$row = mysql_fetch_array($res_ceck);
			$new_item_id = $row['id'];

		}

}

$curr = mysql_fetch_array(mysql_query("SELECT g.symbol from global_currency g join locations l on l.currency_id = g.id where l.id = '".$_GET['loc_id']."'"));
$c_sym = $curr['symbol'];
function unitCheck($var){
    if(!is_numeric($var)){
        $query1="select id from inventory_item_unittype where unit_type like '$var'";
        $result1= mysql_query($query1) or die(mysql_error());
        $num_row = mysql_num_rows($result1);
        if($num_row == 0 && $var != ''){
            $query2="insert into inventory_item_unittype set unit_type='$var'";
            $result2= mysql_query($query2) or die(mysql_error());
            $var = mysql_insert_id();
        }else{
            $row=mysql_fetch_array($result1);
            $var = $row['id'];
        }
    }
    return $var;
}

function GetLocationTimeFromServer($intLocationID, $servertime){
	
	$jsonurl =API."panels/BusinessPanel/api/getlocationtime.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);
 	$json = @file_get_contents($jsonurl,0,null,null);
 	$datetimenow= $json ;
	$datetimenowk1=explode(",",$datetimenow);
	$date_new = explode(":",$datetimenowk1['10']);
	$pieces = explode(" ",str_replace('"','',$date_new[1]));
	$cur_date = $pieces[0];
	$cur_time = $pieces[1].':'.$date_new[2];
	$ldatetitme = $cur_date.' '.$cur_time;
	return date('Y-m-d H:i',strtotime($ldatetitme));
}
function checkTerms($terms,$vendor_id){
    $terms = mysql_real_escape_string($terms);
    if($terms!="") {
        $query = "SELECT id FROM vendor_terms WHERE terms LIKE '$terms'";
        $result = mysql_query($query) or die(mysql_error());
        $num_row = mysql_num_rows($result);
        if($num_row == 0 && $terms != ''){
           $query = "INSERT INTO vendor_terms SET terms='$terms', vendor_id='$vendor_id'";
            $result= mysql_query($query) or die(mysql_error());
            $terms = mysql_insert_id();
        }else{
            $row = mysql_fetch_array($result);
            $terms = $row['id'];
        }
    }
    return $terms;
}

$status = '';

if ($_POST['purchaseSubmit'] == "1") {

	//echo "<pre>"; print_r($_POST); die;
	
	if($_POST['reorder']!='Yes'){
    $purchase_id = mysql_real_escape_string($_POST['purchase_id']);
	}
    $vendor_id = mysql_real_escape_string($_POST['vendor']);
    $status = mysql_real_escape_string($_POST['status']);
    $shopping_datetime = mysql_real_escape_string($_POST['shopping_datetime']);
	$delivery_method = mysql_real_escape_string($_POST['delivery_method']);
	$payment_type = mysql_real_escape_string($_POST['payment_type']);

	if($_POST['terms_new']=="Yes"){
    $terms = checkTerms($_POST['terms'],$vendor_id);
	}else{
	$terms = $_POST['terms'];
	}

    $po = mysql_real_escape_string($_POST['po']);
    $comments = mysql_real_escape_string($_POST['comments']);
	
	/* if($status == 'Shipped'){
		$taxtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['taxTotal1']));
		$subtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['subTotal1']));
		$total = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['total1']));
	}else{
		$taxtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['taxTotal']));
		$subtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['subTotal']));
		$total = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['total']));
	} */
	
	$taxtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['taxTotal']));
	$subtotal = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['subTotal']));
	$total = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['total']));
	
	$vendor_invoice_num = mysql_real_escape_string($_POST['vendor_invoice_num']);

    $date = date('Y-m-d H:i:s');
	$order_emp = '';
	$order_datetime = '';
	$cancelled_emp ='';
	$cancelled_datetime ='';


	//$fet_loc = mysql_fetch_array(mysql_query("SELECT location_link FROM vendors WHERE id = '".$vendor_id."' LIMIT 10"));
	//$fet_loc = mysql_fetch_array(mysql_query("SELECT location_id FROM purchases WHERE vendor_id = '".$vendor_id."' AND vendor_invoice_num = '".$vendor_invoice_num."' LIMIT 10"));
	//$_GET['loc_id'] = $fet_loc['location_id'];

	$ins_fields =",shopping_datetime=NOW(),
                  shopping_employee_id='" . $_SESSION['employee_id'] . "',
				  created_on='BusinessPanel',
				  created_by='".$_SESSION['employee_id']."',
				  created_datetime = now()";
	$update_fields =",last_on='BusinessPanel',
				  last_by='".$_SESSION['employee_id']."',
				  last_datetime = now()";

	$purchase_fields = "vendor_id='$vendor_id',
						buying_vendor_id='$login_vendor_id',
                        status='$status',
                        buying_vendor_purchase_order='$po',
                        terms='$terms',
						delivery_method='$delivery_method',
						payment_type ='$payment_type',
                        subtotal='$subtotal',
                        tax_total='$taxtotal',
                        total='$total',
						vendor_invoice_num = '$vendor_invoice_num',
                        comments='$comments',
                        lastchange_datetime=NOW(),
                        lastchange_employee_id='" . $_SESSION['employee_id'] . "',
						applied_amount = '0.00'";

    if($status == 'Ordered'){
        $order_emp = $_SESSION['employee_id'];
        $order_datetime = $date;
	$purchase_fields .=",order_datetime=NOW(),
                        order_employee_id='$order_emp'";
    }elseif($status == 'Cancelled'){
        $cancelled_emp = $_SESSION['employee_id'];
        $cancelled_datetime = $date;
	$purchase_fields .=",cancelled_datetime=NOW(),
                        cancelled_employee_id='$cancelled_emp'";
	}else if($status=="Completed"){
		$completed_employee_id = $_SESSION['employee_id'];
        $completed_datetime = $date;
		$purchase_fields .=",completed_datetime=NOW(),
                        completed_employee_id='$completed_employee_id'";
	}

	$msg = '';
    if($purchase_id != ''){//Submitted previously created order
        //Update purchases record
        $query = "UPDATE vendor_purchases SET " . $purchase_fields . $update_fields . " WHERE vendor_purchases_id=". $purchase_id;
        $result = mysql_query($query) or die(mysql_error());
        //Delete all purchase items
        $query = "DELETE FROM vendor_purchases_items WHERE vendor_purchases_id=" . $purchase_id;
        $result = mysql_query($query) or die(mysql_error());
		$msg = 'UP';
    }else{
        //Insert into purchases
        $query = "INSERT INTO vendor_purchases SET " . $purchase_fields . $ins_fields;

        $result = mysql_query($query) or die(mysql_error());
        $purchase_id = mysql_insert_id();
		$msg = 'ADD';
    }
    //Insert purchase items
    for($i=0;$i<count($_POST['item']);$i++){
        $item = mysql_real_escape_string($_POST['item'][$i]);
        $pack_size = mysql_real_escape_string($_POST['pack_size'][$i]);
        $pack_unittype = unitCheck(mysql_real_escape_string($_POST['pack_unittype'][$i]));
        $qty_in_pack = mysql_real_escape_string($_POST['qty_in_pack'][$i]);
        $qty_pack_unittype = unitCheck(mysql_real_escape_string($_POST['qty_pack_unittype'][$i]));
        $qty = mysql_real_escape_string($_POST['qty'][$i]);
        $price = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['price'][$i]));
        $tax_percentage = mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['tax_percentage'][$i]));
		$rec_pack_size = isset($_POST['rec_pack_size'][$i]) ? mysql_real_escape_string($_POST['rec_pack_size'][$i]) : '';
        $r_qtypkunit = isset($_POST['r_qtypkunit'][$i]) ? unitCheck(mysql_real_escape_string($_POST['r_qtypkunit'][$i])) : '';
        $rec_qty_in_pack = isset($_POST['rec_qty_in_pack'][$i]) ? mysql_real_escape_string($_POST['rec_qty_in_pack'][$i]) : '';
        $rec_pack_unittype = isset($_POST['rec_pack_unittype'][$i]) ?  unitCheck(mysql_real_escape_string($_POST['rec_pack_unittype'][$i])) : '';
        $rec_qty = isset($_POST['rec_qty'][$i]) ?  mysql_real_escape_string($_POST['rec_qty'][$i]) : '';
        $rec_price = isset($_POST['rec_price'][$i]) ?  mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['rec_price'][$i])) : 0;
        $rec_tax_percentage = isset($_POST['rec_tax_percentage'][$i]) ?  mysql_real_escape_string(preg_replace("/[^0-9.]/", "", $_POST['rec_tax_percentage'][$i])) : 0;
		
		$received = isset($_POST['received'][$i]) ?  mysql_real_escape_string($_POST['received'][$i]) : 'no';
		
		$rec = ''; 
		if(isset($_POST['rec_pack_unittype'][$i])){
			$rec = ",shipped_quantity='$rec_qty',
                    shipped_pack_size='$rec_pack_size',
                    shipped_pack_unittype='$rec_pack_unittype',
                    shipped_qty_in_pack='$rec_qty_in_pack',
                    shipped_qty_in_pack_unittype='$r_qtypkunit',
                    shipped_price='$rec_price',
                    shipped_tax_percentage='$rec_tax_percentage'";
		}
		
		check_vendor_to_location_items($vendor_id,$_GET['loc_id'],$item,$qty);
		check_vendor_to_location_items($vendor_id,$_GET['loc_id'],$item,$rec_qty);
        //Insert purchase items 
        $query = "INSERT INTO vendor_purchases_items SET
                        vendor_purchases_id='$purchase_id',
                        inv_item_id='$item',
                        vendor_id='$vendor_id',
						buying_vendor_id='$login_vendor_id',
                        ordered_quantity='$qty',
                        ordered_pack_size='$pack_size',
                        ordered_pack_unittype='$pack_unittype',
                        ordered_qty_in_pack='$qty_in_pack',
                        ordered_qty_in_pack_unittype='$qty_pack_unittype',
                        ordered_price='$price',
						received='$received',
                        ordered_tax_percentage='$tax_percentage'".$rec;
        $result = mysql_query($query) or die(mysql_error());

		if($status == 'Ordered'){
		$select_item = mysql_fetch_array(mysql_query("SELECT inv_item_id from vendor_items where id ='".$item."'"));

		$total_quntity = mysql_query("UPDATE vendor_items_inventory_counts SET quantity = quantity-'".floatval($qty)."' where inv_item_id = '".$select_item['inv_item_id']."'  AND vendor_id ='".$vendor_id."'") or die(mysql_error());
		}
    }
	if($msg == 'UP'){
		if(isset($_GET['po'])){
			header("Location: purchase_orders.php?msg=Purchase Updated Successfully!");
		}elseif(isset($_GET['intake'])){
			header("Location: receiving_intake.php?msg=Purchase Updated Successfully!");
		}else{
			header("Location: buying.php?msg=Purchase Updated Successfully!");
		}
	}else{
		if(isset($_GET['po'])){
			header("Location: purchase_orders.php.php?msg=Purchase Added Successfully!");
		}elseif(isset($_GET['intake'])){
			header("Location: receiving_intake.php?msg=Purchase Added Successfully!");
		}else{
			header("Location: buying.php?msg=Purchase Added Successfully!");
		}
	}
}

if ($_GET["purchase_id"] != ""){//Viewing a previously submitted purchase

	$purchase_id = mysql_real_escape_string($_GET["purchase_id"]);
	$query2 = "SELECT p.vendor_id,p.buying_vendor_id, shopping_datetime, lastchange_datetime, p.status,
					  p.terms, subtotal, tax_total, total, vendors.name vname, p.comments,p.delivery_method,p.payment_type,p.vendor_invoice_num,p.buying_vendor_purchase_order
				FROM vendor_purchases p
				INNER JOIN vendors ON vendor_id = vendors.id
				LEFT JOIN vendors_terms_types vt ON vt.vendors_terms_types=p.terms
				WHERE p.vendor_purchases_id='".$purchase_id."'";

	$result2 = mysql_query($query2) or die(mysql_error());
	$row2 = mysql_fetch_array($result2);

	$vendor_id = $row2['vendor_id'];
	$vendor_name = $row2['vname'];
	$vendor_invoice_num = $row2['vendor_id'].'-'.$row2['vendor_invoice_num'];
	$buying_vendor_purchase_order = $row2['buying_vendor_id'].'-'.$row2['buying_vendor_purchase_order'];
	$status = $row2['status'];
	$shopping_datetime = $row2['shopping_datetime'] == null ? $row2['lastchange_datetime'] : $row2['shopping_datetime'] ;
	$lastchange_datetime = $row2['lastchange_datetime'];
	$terms = $row2['terms'];
	$subtotal = $row2['subtotal'];
	$taxtotal = $row2['tax_total'];
	$total = $row2['total'];
	$po = $row2['po'];
	$comments = $row2['comments'];
	$payment_type = $row2['payment_type'];
	$delivery_method = $row2['delivery_method'];

	$disabled_1 = "disabled='disabled'";
	if($status != 'Shopping'){
		$comment_read_only = 'readonly';
		$disabled = "disabled='disabled'";
		$hide = 'display:none;';
		$disabled1 = "disabled='disabled'";
	}
	if($_REQUEST['reorder']=='Yes'){
		$disabled = "disabled='disabled'";
		$hide = '';
		$disabled1 = "";
	}
	if($status == 'Ordered' || $status == 'Shopping'){
		$disabled_1 = "";
		$hide = '';
	}

	//Get terms for vendor
		$termst =0;
		$query777 = mysql_query("SELECT default_terms from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_GET['loc_id']."' LIMIT 1");
		if(mysql_num_rows($query777)>0){
			$res777 = mysql_fetch_array($query777);
			if($res777['default_terms']!=""){
				$termst = $res777['default_terms'];
			}
		}
		$query77 = mysql_fetch_array(mysql_query("SELECT terms_types from vendors where id ='" . $vendor_id ."'"));
		if($query77['terms_types']!="" && $termst==0){
		$termst = $query77['terms_types'];
		}
	$query7 = "SELECT vendors_terms_types as id,code as terms FROM vendors_terms_types WHERE vendors_terms_types in(".$termst.")";
	$result7 = mysql_query($query7) or die(mysql_error());
	//Get unit type list
	$query8 = "select id,unit_type from inventory_item_unittype";
	$result8 = mysql_query($query8);
	//Get groups for vendor
	$query9 = "SELECT DISTINCT ig.id, ig.description as `group`
			   FROM vendor_items vi
			   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
			   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
			   WHERE vi.vendor_id='" . $vendor_id . "'
			   ORDER BY ig.description ASC";
	
	$result9 = mysql_query($query9) or die(mysql_error());
	//Get purchase items ordered_pack_size 
	
	$query3 = "SELECT vi.vendor_id,vi.inv_item_id as vi_inv_item_id,pi.inv_item_id,ii.inv_group_id,pi.ordered_quantity,vi.qty_in_pack_size as ordered_pack_size,vi.pack_unittype as vd_pack_unittype,vi.qty_in_pack_unittype as vd_qty_in_pack_unittype,pi.ordered_qty_in_pack,pi.ordered_qty_in_pack_unittype,pi.ordered_price,pi.ordered_tax_percentage,
			   pi.received_pack_size,pi.received_pack_unittype,pi.received_qty_in_pack,pi.received_qty_in_pack_unittype,pi.received_quantity,pi.received_price,pi.received_tax_percentage,pi.received,
			   pi.shipped_quantity,pi.shipped_price,pi.shipped_tax_percentage,pi.shipped
			   FROM vendor_purchases_items pi
			   INNER JOIN vendor_items vi ON vi.inv_item_id=pi.inv_item_id
			   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
			   WHERE pi.vendor_purchases_id='" . $purchase_id . "' AND vi.vendor_id='" . $vendor_id . "'
			   ORDER BY ii.inv_group_id ASC";
	
	if(isset($_REQUEST['history']) && $_REQUEST['history'] == 'Yes'){ 
		$query3 = "SELECT vi.vendor_id,vi.inv_item_id as vi_inv_item_id,pi.inv_item_id,ii.inv_group_id,pi.received_quantity as ordered_quantity,pi.received_pack_size as ordered_pack_size,pi.received_pack_unittype as ordered_pack_unittype,pi.received_qty_in_pack as ordered_qty_in_pack,pi.received_qty_in_pack_unittype as ordered_qty_in_pack_unittype,pi.received_price as ordered_price,pi.received_tax_percentage as ordered_tax_percentage,
			   pi.received_pack_size,pi.received_pack_unittype,pi.received_qty_in_pack,pi.received_qty_in_pack_unittype,pi.received_quantity,pi.received_price,pi.received_tax_percentage,pi.received,
			   pi.shipped_quantity,pi.shipped_price,pi.shipped_tax_percentage,pi.shipped
			   FROM vendor_purchases_items pi
			   INNER JOIN vendor_items vi ON vi.inv_item_id=pi.inv_item_id
			   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
			   WHERE pi.vendor_purchases_id='" . $purchase_id . "' AND vi.vendor_id='" . $vendor_id . "'
			   ORDER BY ii.inv_group_id ASC";
	}
	
	$result3 = mysql_query($query3) or die(mysql_error());
	if(mysql_num_rows($result3) > 0){
		$curr_grp = 0;
		$grp_str = " AND  ii.inv_group_id IN (";//this string is appended to the items query, so that the query only gets items for each group that is apart of the pending order
		$first = true;
		while($row3 = mysql_fetch_array($result3)){
			if($curr_grp != $row3['inv_group_id']){
				if(!$first){
					$grp_str .= ",";
				}
				$first = false;
				$curr_grp = $row3['inv_group_id'];
				$grp_str .= $row3['inv_group_id'];
			}
		}
		$grp_str .= ")";
		mysql_data_seek($result3,0);
	}
	//Select items
	$curr_grp = 0;
	$first = true;
	$items_by_group = array();//Contains arrays of items; array key is group id
	$curr_array = array();//Array of one group; gets added to items by group when all items of a group have been added to curr_array
	
	$qry = "SELECT iiu.id,iiu.unit_type FROM vendor_items vi 
			INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id 
			INNER JOIN inventory_item_unittype iiu ON iiu.id = vi.pack_unittype
			WHERE vi.vendor_id='" . $vendor_id . $grp_str . "' 
			GROUP BY vi.pack_unittype ORDER BY ii.inv_group_id ASC";
	
	$result_unit = mysql_query($qry) or die(mysql_error());

	$query5 = "SELECT vi.inv_item_id as id,ii.description as item, ii.inv_group_id
			   FROM vendor_items vi
			   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
			   WHERE vi.vendor_id='" . $vendor_id . $grp_str . "'
			   ORDER BY ii.inv_group_id ASC";
			   
	
	$result5 = mysql_query($query5) or die(mysql_error());
	while($row5 = mysql_fetch_array($result5,MYSQL_ASSOC)){
		if($curr_grp == $row5['inv_group_id']){//more items from the same group
			$curr_array[] = $row5;
		}else{//new group
			if(!$first){
				$items_by_group[$curr_grp] = $curr_array;
			}
			$first = false;
			$curr_array = array();
			$curr_array[] = array('id'=>$row5['id'],'item'=>$row5['item']);
			$curr_grp = $row5['inv_group_id'];
		}
	}
	$items_by_group[$curr_grp] = $curr_array;//Add the last item array to items by group
}else{//New purchase
	$shopping_datetime = date('Y-m-d',strtotime($ldatetitme));
	$vendor_id = mysql_real_escape_string($_REQUEST['vendor']);
	if ($vendor_id != '') {
		$comment_read_only = '';
		$disabled = '';
		$disabled1 = '';
		//Get terms for vendor
		 $termst =0;
		 $query777 = mysql_query("SELECT default_terms from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_GET['loc_id']."' LIMIT 1");
		if(mysql_num_rows($query777)>0){
			$res777 = mysql_fetch_array($query777);
			if($res777['default_terms']!=""){
				$termst = $res777['default_terms'];
			}
		}
		$query77 = mysql_fetch_array(mysql_query("SELECT terms_types from vendors where id ='" . $vendor_id ."'"));
		if($query77['terms_types']!="" && $termst ==0){
		$termst = $query77['terms_types'];
		}
		$query7 = "SELECT vendors_terms_types as id,code as terms FROM vendors_terms_types WHERE vendors_terms_types in(".$termst.")";
		$result7 = mysql_query($query7) or die(mysql_error());
		//Get unit types
		$query8 = "select id,unit_type from inventory_item_unittype";
		$result8 = mysql_query($query8);
		//Get groups for vendor
		$query9 = "SELECT DISTINCT ig.id, ig.description as `group`
				   FROM vendor_items vi
				   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
				   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				   WHERE vi.vendor_id='" . $vendor_id . "'
				   ORDER BY ig.description ASC";
		$result9 = mysql_query($query9) or die(mysql_error());

		$query10 = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(vendor_invoice_num AS UNSIGNED))+1, 100000) as maxid FROM vendor_purchases WHERE vendor_id={$vendor_id}"));
		if($query10['maxid']>100000){
		$vendor_invoice_num =$query10['maxid'];
		}else{
		$vendor_invoice_num =100000;
		}
		
	}else{
		$comment_read_only = "readonly";
		$disabled = "disabled='disabled'";
		$disabled1 = "disabled='disabled'";
		$hide = 'display:none;';
	}	

	/* $query6 = "SELECT v.id,v.name from locations as l JOIN employee_master_location_storepoint as emls ON emls.location_id = l.id JOIN employees_master as e ON e.empmaster_id = emls.emp_master_id JOIN vendors as v ON v.id = e.StorePoint_vendor_Id	where l.id = '".$_SESSION['loc']."'
UNION
SELECT v.id,v.name from locations as l JOIN vendor_purchases as p ON p.location_id = l.id JOIN vendors as v ON v.id = p.vendor_id where l.id = '".$_GET['loc_id']."'
group by v.id"; */

	$query6 = "SELECT v.id,v.name FROM locations AS l JOIN employee_master_location_storepoint AS emls ON emls.location_id = l.id JOIN employees_master AS e ON e.empmaster_id = emls.emp_master_id 
JOIN vendors AS v ON v.id = e.StorePoint_vendor_Id WHERE l.id = '".$_GET['loc_id']."' GROUP BY v.id";

	$result6 = mysql_query($query6) or die(mysql_error());
}

if ($_POST['newitemSubmit'] == "1") {

$digital_image_name = $_REQUEST['digital_image_name'];
$old_image = $_REQUEST['oldimage'];
if($digital_image_name != ''){
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
 $priority = ($max_priority['priority'] == '') ? $max_priority['priority'] : 0;
 echo $priority;
 $gquery = "INSERT INTO inventory_groups SET
		group_id = '".$max_group['grp']."',
		priority = '".$priority."',
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
	$taxable	= ($_REQUEST['ni_taxable'] != '') ? mysql_real_escape_string($_REQUEST['ni_taxable']) : '0';
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
	$taxable	= ($_REQUEST['ni_taxable'] != '') ? mysql_real_escape_string($_REQUEST['ni_taxable']) : '0';
	$up_inv = mysql_query("UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' WHERE id = '".$inv_item."'") or die(mysql_error());
	$inv_item_id = $inv_item;
}

$vendor_id = mysql_real_escape_string($_REQUEST['sivendor']);
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

}
	$group1_inp = $_REQUEST['group1_inp'];
	$market_inp = $_REQUEST['market_inp'];
	$res1 = mysql_query($query1) or die(mysql_error());
	header('location:backoffice_purchases.php?flag&purchase_id='.$purid.'&msg=Item Added/Updated Successfully!');

}

if($_REQUEST['reorder']=='Yes'){
			$query10 = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(vendor_invoice_num AS UNSIGNED))+1, 100000) as maxid FROM vendor_purchases WHERE vendor_id={$vendor_id}"));
			if($query10['maxid']>100000){
			$vendor_invoice_num =$query10['maxid'];
			}else{
			$vendor_invoice_num =100000;
			}
	$status = "Shopping";
	$shopping_datetime = $cur_date;
	$lastchange_datetime = '';

}
$shopping_datetime = ($shopping_datetime == '1969-12-31' || $shopping_datetime == '') ? date('Y-m-d h:i:s') : $shopping_datetime;
$qry_status = mysql_fetch_array(mysql_query("SELECT status FROM vendor_purchases WHERE vendor_purchases_id='".$_GET['purchase_id']."'"));
$full_status = $qry_status['status'];

$qry_status = mysql_fetch_array(mysql_query("SELECT status FROM vendor_purchases WHERE vendor_purchases_id='".$_GET['purchase_id']."'"));
$full_status = $qry_status['status'];

?>


<?php if(isset($_REQUEST['purchase_id'])){ ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>

<style type="text/css">
table.table-infinite input[type="text"], table.table-infinite select {
    width: 98% !important;
}
.maincontentinner  select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-top-left-radius:0;
  border-top-right-radius:0;
  color:#555555;
  display:inline-block;
  font-size:12px;
  margin-bottom:0px;
  padding:4px 4px!important;
  vertical-align:middle;
  width:98%;
}
.maincontentinner select {
  height:30px;
  line-height:30px;
}


.ui-datepicker-month{
	width:70px;
	margin-right:5px;

}
.ui-datepicker-year{
		width:70px
}
.ui-datepicker-calendar td {
padding: 1px;
background: #fff;
font-size: 11px;
}

.totals {
  background-color:#FFFFFF;
  font-size:10pt;
  width:100%;
  border-bottom-color:#DDDDDD;
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-bottom-style:solid;
  border-collapse:separate;
  border-right-color:#DDDDDD;
  border-right-style:solid;
  border-width:1px 1px 1px 0;
}
table.totals thead {
  cursor:pointer;
}
.title3 {
  color:white;
  cursor:default;
  font-size:15px;
  font-weight:normal;
  height:25px;
  padding:5px 0;
  text-align:center;
}
table.totals td {
  border-top-color:#DDDDDD;
  border-top-style:solid;
  border-top-width:1px;
  line-height:20px;
  padding:8px;
  text-align:left;
  vertical-align:top;
   border-left-color:#DDDDDD;
  border-left-style:solid;
  border-left-width:1px;
}
#totals_tbl tr td { float:right; }
.cart_table .cart_sub input, .cart_table .cart_sub input:focus{ padding:4px !important }
.itm_inpt{
text-align:right;}
a.btn{color:#fff !important;}
#item_results li{ list-style-type: none !important; }

.modal .modal-body {
    max-height: 420px;
    overflow-y: auto;
}
.ui-state-highlight{
	background: none repeat scroll 0 0 #FFFFFF !important;
	color:#5B5B5B !important;
	}
.ui-state-active{
	 background: none repeat scroll 0 0 #0866c6 !important;
     color: #fff !important;
}
.btn-received, .btn-success:link{
	 background: none repeat scroll 0 0 #86d628;
    border-color: #6db814;
}
.btn-success.disabled, .btn-success[disabled] {
    background-color: #d3d3d3 !important;
}
.table th, .table td {
vertical-align:middle;
}

.line3 {
		background-color: #808080;
		color:#000000 !important;
		}
.tab-primary.ui-tabs .ui-tabs-nav li a{
	background-color: inherit;
	}
.cart_table span {
    float: none !important;
	}
.cart_table td {
	text-align:right !important;
}
.cart_table .cart_sub input{
	padding:4px !important;
}
.dataTables_filter input{
	width:auto !important;
}
.dataTables_length,.dataTables_filter{
	display:none;
}
#totals_tbl .active {
    display: table-row !important;
}
.jGrowl-message { color: #FFFFFF !important; }
</style>
<script type="text/javascript">
jQuery('#order_frm .check').live('change', function () {
		jQuery('#sub_btn').prop('disabled', false);
	});
jQuery(document).ready(function(){
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']!=""){ ?>
		var msg = '<?php echo $_REQUEST['msg']; ?>';
		jAlert(msg,'Alert Dialog');
<?php } ?>
jQuery("#searchdate").datepicker({ changeMonth: true,
                changeYear: true,
				dateFormat:"yy-mm-dd",
                yearRange: "-100:+0"});

}); // end document.ready
jQuery('#group').live('change',function(){
	var group_id  = jQuery(this).val();
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
		getinventory(jQuery(this).val());
	}

});
jQuery('#inv_item').live('change',function(){

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
		jQuery('#ni_notes').val('');
		jQuery('#ni_barcode').val('');
		jQuery('#ni_manufacture').val('');
		jQuery('#ni_brand').val('');
		jQuery('#ni_model').val('');
		jQuery('#vendor_internal_number').val('');
		jQuery('#imagebox').html('');
		jQuery('#digital_image_name').val('');
		jQuery('#pack_size').val('');
		jQuery('#qty_in_pack').val('');
		jQuery('#ni_taxable').val('');
		jQuery('#price').val('');
		jQuery('#purchased_from_vendor').val('');
	}else{
		jQuery('#drop_span').show();
		jQuery('#new_span').hide();
		jQuery('#inv_item_type').val('old');
		jQuery('#inv_item1').val('');
		jQuery('#new_inv_div,#new_inv_div1').hide();
	}


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
function getinventory(group){
    states = jQuery.Deferred();
    jQuery.ajax({
        url:'get_inventory_items.php',
        data: { group: group},
        dataType: 'jsonp',
        jsonpCallback: 'returnItem'
    });
}
function returnItem(data){

    var group = jQuery('#inv_item');
    group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
	group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
    for(var i=0;i<data.length;i++){
        group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
    }

    states.resolve();
}
jQuery('#inv_item').live('change',function(){
	if(jQuery('#inv_item').val()!="new_inv_item" && jQuery('#inv_item').val()!=""){
	jQuery('#name').val(jQuery("#inv_item :selected").text());
	jQuery('#pack_unit_type').val(jQuery("#inv_item :selected").attr('rel'));
	jQuery('#barcode').val(jQuery("#inv_item :selected").attr('barcode'));
	}else{
	jQuery('#name').val('');
	jQuery('#pack_unit_type').val('');
	jQuery('#barcode').val('');
	}

});
jQuery('#inv_item1').live('change',function(){
if(jQuery('#inv_item1').val()!=""){
	jQuery('#name').val(jQuery('#inv_item1').val());
}else{
	jQuery('#name').val('');
	}

});

function validate()
{
	if(jQuery('#status').val()==""){
	jAlert('Please Select status!','Alert Dialog');
	return false;
	}else if(jQuery('#d_market').val()==""){
	jAlert('Please Select Market!','Alert Dialog');
	return false;
	}else if(jQuery('#group').val()==""){
	jAlert('Please Select Group!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item').val()=="" && jQuery('#inv_item_type').val()!='new'){
	jAlert('Please Select Inventory Item!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item1').val()=="" && jQuery('#inv_item_type').val()=='new'){
	jAlert('Please Enter Inventory Item!','Alert Dialog');
	return false;
	}else if(jQuery('#name').val()==""){
	jAlert('Please Enter Name!','Alert Dialog');
	return false;
	}else if(jQuery('#pack_size').val()==""){
	jAlert('Please Enter Pack Size!','Alert Dialog');
	return false;
	}else if(jQuery('#pack_unit_type').val()==""){
	jAlert('Please Select Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack').val()==""){
	jAlert('Please Enter Quantity In Pack!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack_unit_type').val()==""){
	jAlert('Please Select Quantity In Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#ni_taxable').val()==""){
	jAlert('Please Select Taxable!','Alert Dialog');
	return false;
	}else if(jQuery('#price').val()==""){
	jAlert('Please Enter Price!','Alert Dialog');
	return false;
	}else{
	jQuery('#edit_from input,#edit_from select').attr('disabled',false);
		jQuery.ajax({
			url:'ajax_Add_vendor_items.php',
			type:'POST',
			data:jQuery("#edit_from").serialize(),
			success:function(data){
				jAlert(data,'Alert Dialog');
				jQuery('#myModal').modal('hide');
				return false;
			}
		});
	return false;
	}
}
function get_group(market,group){

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

    <?php include_once 'require/top.php';?>

    <div class="leftpanel">

        <?php include_once 'require/left_nav.php';?>

    </div><!-- leftpanel -->

    <div class="rightpanel">

        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Sales <span class="separator"></span></li>
			<li>Orders</li>
            <li class="right">
                    <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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

        <div class="pageheader" id="date_search_box">


            <div class="pull-right" style="margin-top: 14px;">
				<input type="hidden" id="hid_same_as_orderd" name="hid_same_as_orderd" value="no" />
				 <button  class="btn btn-primary btn-large" onclick="leaveAlert()">Back</button>
				 <?php if($status == 'Ordered'){ ?><a href="#" class="btn btn-success btn-large qty_on_hand_button" style="margin-right:3px">Qty on Hand</a><a href="#" class="btn btn-success btn-large same_as_orderd">Same as Ordered</a> <?php } ?>
                 <?php if($status != "Completed"){?>
                <a href="#" class="" id="submit_order" title="Submit Order" style="<?php if($_GET['purchase_id'] == '' || $_GET['purchase_id'] == '0'){ echo 'pointer-events: none;cursor: default;'; } ?>">
					<button  id="sub_btn" class="btn btn-large <?php echo ($_GET['purchase_id'] == '' || $_GET['purchase_id'] == '0') ? 'btn-default' : 'btn-success' ?>" >Submit</button>
				</a>
                <?php }else{ ?>
                <a href="buying_purchases_order.php?flag&purchase_id=<?=$_REQUEST['purchase_id']; ?>&reorder=Yes" class="" title="Reorder">
				<button  id="Reorder" class="btn btn-success btn-large">Reorder</button></a>
                <?php } ?>
            </div>
			



            <div class="pageicon"><span class="iconfa-tags"></span></div>
            <div class="pagetitle">
                <h5>Display All Sales Order Information</h5>
                <h1>Order Details</h1>
            </div>
        </div><!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                
					<!--<div class="span8">-->
                      <div class="span12">
						<?php if(isset($_GET["flag"])) { ?>
						<div class="clearfix">
						<h4 class="widgettitle">Details</h4>
						</div>
						<form id="order_frm" method="post">
							<table class="topTbl table table-bordered table-infinite">
								<tr>
									<td style="width:15%;" >Vendor:<span style="color:#FF0000;">* </span></td>
									<td style="width:35%;">
										<?php if ($_GET['purchase_id'] != '') { ?>
											<input type="text" value="<?=$vendor_name.' (ID: '.$vendor_id.')';?>" disabled="disabled" style="" class="frmstyle1" size="12"/>
											<input type="hidden" value="<?=$vendor_id?>" name="vendor" id="vendor" size="12"/>
										<?php } else { ?>
											<select id="vendor" name="vendor" class="frmstyle1" style="width:99.8% !important">
												<option value=""> - - - Select Vendor - - - </option>
                                                <option value="find"> - - - Find Vendor - - -</option>
												<?php while($row6 = mysql_fetch_array($result6)){?>
													<option value="<?=$row6['id'];?>" <?php if($vendor_id == $row6['id']){echo "selected='selected'"; }?>><?=$row6['name'];?></option>
												<?php } ?>
											</select>
                                           <input type="hidden" value="<?php if(isset($_POST['vendor'])) echo $_POST['vendor']; else echo $_SESSION['StorePointVendorID']; ?>" name="vendor_id" id="vendor_id" >
										<?php }?>
									</td>
                                    <?php  if($status == "Shipped"){ ?>
                                    <td style="width:15%;" >Shipped Date:<span style="color:#FF0000;">* </span></td>

									<td style="width:35%;" >
										<input type="text" name="shopping_datetime" value="<?=date('Y-m-d',strtotime($lastchange_datetime));?>1" size="12" id="shopping_datetime" class="frmstyle1" <?=$disabled;?>>
									</td>
                                    <?php } else { ?>
									<td style="width:15%;" >Shopping Date:<span style="color:#FF0000;">* </span></td>

									<td style="width:35%;" >
										<input type="text" name="shopping_datetime" value="<?= date('Y-m-d',strtotime($shopping_datetime));?>" size="12" id="shopping_datetime" class="frmstyle1" <?=$disabled;?>>
									</td>
                                    <?php } ?>
								</tr>
								<tr>
									<td>Vendor Invoice:
                                    </td>
                                    <td>
										<input type="hidden" name="vendor_invoice_num" id="vendor_invoice_num" value="<?=$row2['vendor_invoice_num'];?>">
										<input type="text" name="vendor_invoice_num2" id="vendor_invoice_num2" value="<?=$vendor_invoice_num;?>" size="12" class="frmstyle1" readonly>
									</td>
									<td >Terms:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="terms" id="terms" class="frmstyle1 terms" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Select Terms - - - </option>
											<?php if($vendor_id != ''){
												while ($row7 = mysql_fetch_array($result7)){ ?>
													<option value='<?=$row7['id']?>' <?php if($terms == $row7['id']){echo "selected='selected'"; }?>
                                                        <?= mysql_num_rows($result7) == 1 ? 'selected' : ''?>><?=$row7['terms']?></option>
												<?php }
											} ?>
										</select>
                                        <input type="hidden" name="terms_new" id="terms_new" value="no" >

									</td>
								</tr>
								<tr>
                                	<td >Status:<span style="color:#FF0000;">* </span></td>
									<td><?php if($full_status == "Completed"){ ?>
                                        <select id="status" name="status" class="frmstyle1" disabled style="width:99.8% !important">
                                            <option value="Completed">Completed</option>
                                        </select>
                                        <?php }else if($full_status == "Shipped"){ ?>
                                        <select id="status" name="status" class="frmstyle1" style="width:99.8% !important">
                                        	<option value="Shipped">Shipped</option>
                                            <!--<option value="Completed">Completed</option>-->
                                        </select>
                                        <?php }else{ ?>
										<select id="status" name="status" class="frmstyle1" <?php // echo $disabled1;?> style="width:99.8% !important">
											<option value="Shopping" <?php if ($full_status == "Shopping"){ echo "selected='selected'";} ?>>Shopping</option>
                                            <option value="Ordered" <?php if ($full_status == "Ordered"){echo "selected='selected'";} ?>>Ordered</option>
											<option value="Shipped" <?php if ($full_status == "Shipped"){echo "selected='selected'";} ?>>Shipped</option>
										</select>
                                        <?php } ?>
									</td>

									<td>Payment Type:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="payment_type" id="payment_type" class="frmstyle1" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Payment Type - - - </option>
											<?php
											$types = 0;
											$query777 = mysql_query("SELECT default_payment_type from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_GET['loc_id']."' LIMIT 1");
											if(mysql_num_rows($query777)>0){
												$res777 = mysql_fetch_array($query777);
												if($res777['default_payment_type']!=""){
													$types = $res777['default_payment_type'];
												}
											}
											$vpayment = mysql_fetch_array(mysql_query("SELECT payment_types from vendors where id ='".$vendor_id."'"));
											if($vpayment['payment_types']!='' && $types == 0){
												$types = $vpayment['payment_types'];
											}
											$pay_query = mysql_query("SELECT * from vendors_payment_types where vendors_payments_id in(".$types.") order by code");
												while ($row_p = mysql_fetch_array($pay_query)){ ?>
													<option value='<?=$row_p['vendors_payments_id']?>' <?php if($payment_type == $row_p['vendors_payments_id']){echo "selected='selected'"; }?>
                                                        <?= mysql_num_rows($pay_query) == 1 ? 'selected' : ''?>><?=$row_p['code']?></option>
												<?php }
											 ?>
										</select>

									</td>
								</tr>
								<tr>
									<td>Internal Purchase Order#:</td>
									<td>
										<input type="hidden" name="po" id="po" value="<?=$row2['buying_vendor_purchase_order'];?>">
										<input type="text" name="po2" id="po2" value="<?=$buying_vendor_purchase_order;?>" size="12" class="frmstyle1" <?=$disabled;?>>
									</td>
									<td>Delivery Method:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="delivery_method" id="delivery_method" class="frmstyle1" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Select Delivery Method - - - </option>
											<?php
												$delivery_types = 0;
												$query777 = mysql_query("SELECT default_delivery_type from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_GET['loc_id']."' LIMIT 1");
												if(mysql_num_rows($query777)>0){
													$res777 = mysql_fetch_array($query777);
													if($res777['default_delivery_type']!=""){
														$delivery_types = $res777['default_delivery_type'];
													}
												}
												$vdel = mysql_fetch_array(mysql_query("SELECT delivery_types from vendors where id ='".$vendor_id."'"));
												if($vdel['delivery_types']!='' && $delivery_types==0){
													$delivery_types = $vdel['delivery_types'];
												}
												$del_query = mysql_query("SELECT * from vendors_delivery_types where vendors_delivery_types_id in(".$delivery_types.") order by code");
												while ($row_d = mysql_fetch_array($del_query)){ ?>
													<option value='<?=$row_d['vendors_delivery_types_id']?>' <?php if($delivery_method == $row_d['vendors_delivery_types_id']){echo "selected='selected'"; }?>
                                                        <?= mysql_num_rows($del_query) == 1 ? 'selected' : ''?>><?=$row_d['code']?></option>
												<?php }
											 ?>
										</select>
                                    </td>
								</tr>
                                 <tr>
                                 	<td rowspan="2">Comments:</td>
									<td rowspan="2">
										<textarea name="comments" id="comments" style="resize: none;" class="frmstyle1" <?=$comment_read_only;?>><?=$comments?></textarea>
									</td>

                                	<td>Last Change:</td>
                                    <td><input type="text" name="lastchange_datetime" value="<?php if($lastchange_datetime!="" && $lastchange_datetime!="0000-00-00 00:00:00"){echo GetLocationTimeFromServer($_GET['loc_id'],$lastchange_datetime); } ;?>" size="12" id="lastchange_datetime" disabled="disabled" class="frmstyle1" <?=$disabled;?>></td>
                                  </tr>
                                  <tr>
                                  	<td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>

							</table>

							<table  class="table table-bordered table-infinite" id="item_tbl">
								<tbody>

                                	<?php 
										if($status == "Ordered"){ 
									?>
                                    <tr>
                                        <td colspan="12" style="padding:0px;">
                                        <h4 class="widgettitle">Items</h4></td>
                                    </tr>
									<tr class="title">
										<td>Group</td>
										<td>Item Description &nbsp;
										</td>
										<td class="itm_sel_th">Pack Unit Type</td>
										<td class="itm_inpt_th">Qty In Pack</td>
										<td class="itm_sel_th">Qty In Pack Unit Type</td>
										<td class="itm_inpt_th">Qty In Pack Size</td>
										<td class="itm_inpt_th">Qty on Hand</td>
										<td class="itm_inpt_th">Qty</td>
										<td class="itm_inpt_th">Price</td>
										<td class="itm_inpt_th">Tax %</td>
										<td class="itm_inpt_th get_width">Amount</td>
										<td style='width:60px;text-align:center;'>Remove</td>
									</tr>
                                    <?php }else{ ?>
                                    <tr>
                                        <td colspan="12" style="padding:0px;">
										<input class="btn btn-success btn_table_top" type="button" id="addItem" value="Add New Item" <?=$disabled1;?> style="<?=$hide;?>">
                                        <h4 class="widgettitle">Items</h4></td>
                                    </tr>
									<tr class="title">
										<td>Group</td>
										<td>Item Description &nbsp;
										</td>
										<td class="itm_sel_th">Pack Unit Type</td>
										<td class="itm_inpt_th">Qty In Pack</td>
										<td class="itm_sel_th">Qty In Pack Unit Type</td>
										<td class="itm_inpt_th">Qty In Pack Size</td>
										<td class="itm_inpt_th">Qty on Hand</td>
										<td class="itm_inpt_th">Qty</td>
										<td class="itm_inpt_th">Price</td>
										<td class="itm_inpt_th">Tax %</td>
										<td class="itm_inpt_th get_width">Amount</td>
										<td style='width:60px;text-align:center;'><?php echo ($status != "Shipped") ? 'Remove' : '' ?></td>
									</tr>
                                    <?php } ?>
									<?php if($_GET['purchase_id'] != ''){
										
											
										$k=0;
										$createJsArrays = '';
										$addToJsArray = '';
										$ordr_subtotal = '0.00';
										$ordr_tax_total = '0.00';
										while($row3 = mysql_fetch_assoc($result3)){
											$itm_sub = floatval($row3['ordered_quantity'])*floatval($row3['ordered_price']);
											$itm_tax = floatval($row3['ordered_tax_percentage'])*.01*floatval($itm_sub);
											$itm_total = $itm_sub + $itm_tax;
											$ord_subtotal = $ord_subtotal + $itm_sub;
											$ord_tax_total = $ord_tax_total + $itm_tax;

											if($row3['received'] == 'yes'){
												$ordr_subtotal = $ordr_subtotal+ ($row3['received_price']*$row3['received_quantity']);
												$ordr_tax_total = $ordr_tax_total + number_format(((($row3['received_price']*$row3['received_tax_percentage']/100))*$row3['received_quantity']),2,'.',',');
											}
											
											$pack_unittype = ($row3['vd_pack_unittype']) ? $row3['vd_pack_unittype'] : '';
											$unittype = "SELECT * from inventory_item_unittype WHERE id = '".$pack_unittype."' ORDER BY conversion_group, unit_type";
											$pack_unittype_qry = mysql_query($unittype);
											$pack_unittype_fetch = mysql_fetch_array($pack_unittype_qry);
											
											$qty_in_pack_unittype = ($row3['vd_qty_in_pack_unittype']) ? $row3['vd_qty_in_pack_unittype'] : '';
											$unittype = "SELECT * from inventory_item_unittype WHERE id = '".$qty_in_pack_unittype."' ORDER BY conversion_group, unit_type";
											$qty_in_pack_unittype_qry = mysql_query($unittype);
											$qty_in_pack_unittype_fetch = mysql_fetch_array($qty_in_pack_unittype_qry);
																
											
											/* $q = mysql_fetch_array(mysql_query('SELECT lii.id AS loc_inv_id
												FROM vendor_items vi
												INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
												INNER JOIN location_inventory_items lii ON lii.inv_item_id = vi.inv_item_id
												WHERE vi.vendor_id = "'.$row3['vendor_id'].'" AND ii.inv_group_id="'.$row3['inv_group_id'].'" AND lii.inv_item_id="'.$row3['vi_inv_item_id'].'"'));

											$qry = "SELECT inventory_item_unittype.unit_type,inventory_item_unittype.id AS unit_id 
													 FROM location_inventory_counts lic 
													 LEFT JOIN employees ON lic.employee_id=employees.id 
													 LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
													 WHERE inv_item_id='".$q['loc_inv_id']."' AND inventory_item_unittype.id != '' GROUP BY unit_type ORDER BY lic.date_counted DESC, lic.time_counted DESC";
											
											$result_unit2 = mysql_query($qry) or die(mysql_error()); */
											
											$qty_on_hand = mysql_fetch_array(mysql_query("SELECT SUM(lic.quantity) AS qty
															FROM location_inventory_counts lic 
															LEFT JOIN employees ON lic.employee_id=employees.id 
															LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
															WHERE inv_item_id='".$q['loc_inv_id']."' AND inventory_item_unittype.id = '".$row3['ordered_pack_unittype']."'"));
										

											$createJsArrays .= "items[" . $k . "] = new Array('',0,'','','',0,0,0,0,0,0);";
											$addToJsArray .= "items[" . $k . "][0] = parseFloat('" . $row3['inv_item_id'] . "');";//inv_item_id(vendor_items.id)
											$addToJsArray .= "items[" . $k . "][1] = parseFloat('" . $row3['ordered_pack_size'] . "');";//pack size
											$addToJsArray .= "items[" . $k . "][2] = parseFloat('" . $row3['ordered_pack_unittype'] . "');";//pack unit type
											$addToJsArray .= "items[" . $k . "][3] = parseFloat('" . $row3['ordered_qty_in_pack'] . "');";//quantity in pack
											$addToJsArray .= "items[" . $k . "][4] = parseFloat('" . $row3['ordered_qty_in_pack_unittype'] . "');";//qty_in_pack_unittype
											$addToJsArray .= "items[" . $k . "][5] = parseFloat('" . $row3['ordered_quantity'] . "');";//quantity
											$addToJsArray .= "items[" . $k . "][6] = parseFloat('" . $row3['ordered_price'] . "');";//price
											$addToJsArray .= "items[" . $k . "][7] = parseFloat('" . $row3['ordered_tax_percentage'] . "');";//quantity
											$addToJsArray .= "items[" . $k . "][8] = parseFloat('" . $itm_sub . "');";//quantity
											$addToJsArray .= "items[" . $k . "][9] = parseFloat('" . $itm_tax . "');";//quantity
											$addToJsArray .= "items[" . $k . "][10] = parseFloat('" . $itm_total . "');";//quantity
											
											if($status == "Ordered" || $status == "Shopping"){
											?>
											<tr id="<?=$k?>" class="item_row oomt <?= $k . "_ord" ?>" data-inv_group_id="<?=$row3['inv_group_id']?>">
												<td style="text-align: center;">
													<?php
														$inv_group_id = '';
														
														$query9 = "SELECT DISTINCT ig.id, ig.description as `group`
																   FROM vendor_items vi
																   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
																   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
																   WHERE vi.vendor_id='" . $vendor_id . "' AND ig.id = '".$row3['inv_group_id']."'
																   ORDER BY ig.description ASC";
														$result9 = mysql_query($query9) or die(mysql_error());
														$row9 = mysql_fetch_array($result9);
														$inv_group_id = $row9['group'];
														
													?>
													
													<input readonly value="<?=$inv_group_id;?>" type='text' class='frmstyle1 group' <?=$disabled_1;?> <?php echo ($status == "Ordered") ? 'disabled' : '' ?> style="width: 50px;" />
													
												</td>
												<td>
													<?php
														$inv_item_id = '';
														foreach($items_by_group[$row3['inv_group_id']] as $val){ 
															if($val['id'] == $row3['inv_item_id']){
																$inv_item_id = $val['item'];
															}
														}
													?>
													<input readonly value="<?=$inv_item_id;?>"  type='text' class='frmstyle1 item' name='item_name[]' <?=$disabled_1;?> <?php echo ($status == "Ordered") ? 'disabled' : '' ?> style="width: 50px;" />
													<input value="<?=$row3['inv_item_id'];?>"  type='hidden' name='item[]'/>
													
												</td>
												<td>
													<input readonly type="text" value="<?= $pack_unittype_fetch['unit_type'] ?>" <?php echo ($status == "Ordered") ? 'disabled' : '' ?> class="itm_sel pack_unittype" name="pack_unittype[]" <?=$disabled_1;?> style="width: 50px;"> 
												</td>
												<td style="text-align: center;">
													<input readonly <?php echo ($status == "Ordered") ? 'disabled' : '' ?> type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" <?=$disabled_1;?> value="<?=number_format($row3['ordered_qty_in_pack'],2)?>" style="width: 50px;"/>
												</td>
												<td>
													<input readonly type="text" value="<?= $qty_in_pack_unittype_fetch['unit_type'] ?>" <?php echo ($status == "Ordered") ? 'disabled' : '' ?> class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" <?=$disabled_1;?> style="width: 50px;"> 
												</td>
												<td style="text-align: center;">
													<input readonly <?php echo ($status == "Ordered") ? 'disabled' : '' ?> type='text' class="itm_inpt pack_size" name="pack_size[]" value="<?=(is_numeric($row3['ordered_pack_size'])) ? number_format($row3['ordered_pack_size']) : $row3['ordered_pack_size'];?>" <?=$disabled_1;?> style="width: 50px; <?=(is_numeric($row3['ordered_pack_size'])) ? 'text-align:right !important' : 'text-align:left !important';?>" />
												</td>
												<td style="text-align: center;">
													<input disabled type='text' class="itm_inpt qty_on_hand" name="qty_on_hand[]" value="<?= $qty_on_hand['qty']; ?>" <?=$disabled_1;?> style="width: 50px;"/>
												</td>
												<td style="text-align: center;">
													<?php if($status == 'Ordered'){ ?>
													<input readonly type='text' class="itm_inpt qty" name="qty[]" value="<?=number_format($row3['ordered_quantity'],2)?>" <?=$disabled_1;?> style="width: 50px;"/>
													<?php }else{ ?>
													<input type='text' class="itm_inpt qty" name="qty[]" value="<?=number_format($row3['ordered_quantity'],2)?>" <?=$disabled_1;?> style="width: 50px;"/>
													<?php } ?>
												</td>
												<td style="text-align: center;">
													<?php if($status == 'Ordered'){ ?>
													<input readonly type='text' class="itm_inpt price" name="price[]" value="<?=number_format($row3['ordered_price'],2)?>" <?=$disabled_1;?> />
													<?php }else{ ?>
													<input type='text' class="itm_inpt price" name="price[]" value="<?=number_format($row3['ordered_price'],2)?>" <?=$disabled_1;?> />
													<?php } ?>
												</td>
												<td style="text-align: center;">
													<input <?php echo ($status == "Ordered") ? 'disabled' : '' ?> type='text' class="itm_inpt tax_percentage" name="tax_percentage[]" value="<?='$'.$row3['ordered_tax_percentage']?>" <?=$disabled_1;?> readonly />
												</td>
												<td style="text-align: center;">
													<input <?php echo ($status == "Ordered") ? 'disabled' : '' ?> type='text' class="itm_inpt amt" value="" readonly <?=$disabled_1;?>  />
												</td>
												<td style="text-align:center;">
												<?php if($status != 'Ordered'){ ?>
													<img style="<?=$hide;?>" src="images/Delete.png" class="remove" <?php echo ($status == "Ordered") ? 'disabled' : '' ?>/>
												<?php } ?>
												</td>
											</tr>
											<?php }elseif($status == "Shipped"){ 
											?>

                                            <tr id="<?=$k?>" class="ramt <?= $k . "_ship" ?>" style="/*outline: 1px solid #0866c6;*/">
												
												<td style="color:#0866c6;">
												<?=$n?> (Shipped)
												</td>
												<td>&nbsp;
												<?php
													$inv_item_id = '';
													foreach($items_by_group[$row3['inv_group_id']] as $val){ 
														if($val['id'] == $row3['inv_item_id']){
															$inv_item_id = $val['item'];
														}
													}
												?>
												<input value="<?=$inv_item_id;?>" type='hidden' class='frmstyle1' name='item_name[]' />
												<input value="<?=$row3['inv_item_id'];?>"  type='hidden' name='item[]'/>
												</td>
												<td >
													<input readonly type="text" value="<?= $pack_unittype_fetch['unit_type'] ?>"  class="itm_sel pack_unittype" id="pack_unittype" name="pack_unittype[]"style="width: 50px;"> 
												</td>
												<td>
													<input disabled="disabled" type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" value="<?=number_format($row3['ordered_qty_in_pack'],2)?>"/>
												</td>
												<td>
													<input readonly type="text" value="<?= $qty_in_pack_unittype_fetch['unit_type'] ?>" class="itm_sel qty_pack_unittype" id="r_qtypkunit_<?php echo $n; ?>" name="qty_pack_unittype[]" style="width: 50px;"> 
												</td>
												<td>
													<input type="hidden" value="yes" name="received[]">
													<input  disabled="disabled" type='text' class="itm_inpt pack_size" name="pack_size[]" value="<?=(is_numeric($row3['ordered_pack_size'])) ? number_format($row3['ordered_pack_size']) : $row3['ordered_pack_size'];?>" />
												</td>
												<td>
                                                <input disabled onKeyPress="return isNumberKey(event)" type='text' class="itm_inpt qty_on_hand" name="qty_on_hand[]" value="<?= $qty_on_hand['qty'] ?>" />
												</td>
                                            	<td>
                                                <input onKeyPress="return isNumberKey(event)" type='text' class="itm_inpt qty" name="qty[]" value="<?=$row3['ordered_quantity']?>" />
												</td>
												<td>
                                                <input type='text' class="itm_inpt price" name="price[]" data-price_val = "<?=number_format($row3['ordered_price'],2)?>" value="<?='$'.number_format($row3['ordered_price'],2)?>" />
												</td>
												<td>
                                                <input disabled="disabled" type='text' class="itm_inpt tax_percentage tax_<?=$k?>" name="tax_percentage[]" value="<?=($row3['ordered_tax_percentage'] != "" ? $row3['ordered_tax_percentage'] : '$0.00')?>" />
												</td>
												<td>
                                                <input  type='text' class="itm_inpt amt amt_<?=$k?>" value="<?php echo '$'.number_format((($row3['ordered_price']+($row3['ordered_price']*$row3['ordered_tax_percentage']/100))*$row3['ordered_quantity']),2,'.',',') ?>" readonly name="r_amount" />
												</td>
												<td class="center">
                                                <input type="checkbox" name="received_chkb[]" <?php if($row3['received'] == 'yes'){ echo "checked='checked'"; } ?> class="check" />
                                				<input type="hidden" value="<?=$row3['received']?>" name="received[]" class="check_val" />
												</td>
												
											</tr>
                                            <?php } ?>
										<?php $k++; }
									} ?>
								</tbody>
							</table>
							<?php if($purchase_id != '' || $vendor_id != ''){
								//if($status == "Completed"){
									if($status == "Ordered"){
							?>
								<table id="totals_tbl" align="right" style="margin-top:10px;" class="cart_table">
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Subtotal:</span>
												<input type="text" name="subTotal" id="sub_total_vari1" class="itm_inpt" style="margin-right:5px;" value="<?=add_breket($ord_subtotal-$ordr_subtotal)?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>

									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Tax:</span>
												<input type="text" name="taxTotal" id="tax_vari1" class="itm_inpt" style="margin-right:5px;" value="<?=add_breket($ord_tax_total-$ordr_tax_total)?>" readonly <?=$disabled;?>>
											</div>
										</td>
									</tr>
									
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Total:</span>
												<input type="text" name="total" id="total_vari1" class="itm_inpt" style="margin-right:5px;" value="<?=add_breket(($ord_subtotal-$ordr_subtotal)+($ord_tax_total-$ordr_tax_total))?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>
								</table>
								
                            	<!--<table class="totals" id="totals_tbl1" style="float:right; margin-top:25px; width:40%; ">
								<thead>
									<tr class="title3">
									<td colspan="4" style="background-color:#0866C6;text-align:center;">Totals</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: center; font-weight: bold;background-color:#333333;color:#FFFFFF;"></td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Ordered</td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Shipped</td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Variance</td>
									</tr>
								<tr>
								<td>Subtotal:</td>
								<td>
                                <input type="text" name="subTotal" id="ordSubTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="subTotal1" id="subTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" id="sub_total_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal-$ordr_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								<tr>
								<td>Tax:</td>
								<td>
                                <input type="text" name="taxTotal" id="ordTaxTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="taxTotal1" id="taxTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" id="tax_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_tax_total-$ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								<tr>
								<td>Grand Total:</td>
								<td>
                                <input type="text" name="total" id="ordTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal+$ord_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="total1" id="total1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_subtotal+$ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" id="total_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket(($ord_subtotal-$ordr_subtotal)+($ord_tax_total-$ordr_tax_total))?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								</tbody>
                                </table>-->
                            <?php }else{ 
									if($status == "Shipped"){ 
							?>
								<!--<table id="totals_tbl" align="right" style="margin-top:10px;" class="cart_table">
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Subtotal:</span>
												<input type="text" name="subTotal" id="subTotal_shipped" class="itm_inpt" value="<?='$'.number_format($subtotal,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>

									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Tax:</span>
												<input type="text" name="taxTotal" id="taxTotal_shipped" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($taxtotal,2);?>" readonly <?=$disabled;?>>
											</div>
										</td>
									</tr>
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Total:</span>
												<input type="text" name="total" id="total_shipped" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($total,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>
								</table>-->
								
								<table id="totals_tbl" align="right" style="margin-top:10px;" class="cart_table">
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Subtotal:</span>
												<input type="text" name="subTotal" id="subTotal" class="itm_inpt" value="<?='$'.number_format($subtotal,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>

									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Tax:</span>
												<input type="text" name="taxTotal" id="taxTotal" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($taxtotal,2);?>" readonly <?=$disabled;?>>
											</div>
										</td>
									</tr>
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Total:</span>
												<input type="text" name="total" id="total" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($total,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>
								</table>
							<?php
									}else{
							?>
								<table id="totals_tbl" align="right" style="margin-top:10px;" class="cart_table">
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Subtotal:</span>
												<input type="text" name="subTotal" id="subTotal" class="itm_inpt" value="<?='$'.number_format($subtotal,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>

									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Tax:</span>
												<input type="text" name="taxTotal" id="taxTotal" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($taxtotal,2);?>" readonly <?=$disabled;?>>
											</div>
										</td>
									</tr>
									<tr class="active">
										<td>
										  <div class="cart_sub">
											<span>Total:</span>
												<input type="text" name="total" id="total" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($total,2);?>" readonly <?=$disabled;?>>
										  </div>
										</td>
									</tr>
								</table>
							<?php } } } ?>
							<input type="hidden" name="purchaseSubmit" id="purchaseSubmit" value="" />
							<input type="hidden" name="purchase_id" value="<?=$_GET['purchase_id'];?>" />
                            <input type="hidden" name="reorder" id="reorder" value="<?php echo $_GET['reorder']; ?>" >
						</form>
					<?php } 
					
					?>
                    <div id="clone_table_div">
					<?php if($vendor_id != ''){ 
						if(isset($_GET['purchase_id'])){ ?>
						<!--Hidden table tr to insert when new item is clicked-->
						<table style="display: none;" id="clone_tbl">
							<tr>
								<td>
									<!--<select class='frmstyle2 group' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Group</option>
										<?php while($row9 = mysql_fetch_array($result9)){ ?>
											<option value="<?=$row9['id']?>"><?=$row9['group'];?></option>
										<?php } ?>
									</select>-->
									
									<input type='text' class="frmstyle2 group" name="item[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td>
									<!--<select class='frmstyle2 item' name='item[]' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Item</option>
									</select>-->
									<input type='text' class="frmstyle2 item" name="item[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td>
									<select class="itm_sel pack_unittype" name="pack_unittype[]" <?=$disabled;?> style="width: 100px;" id="pack_unittype2">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php /* while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0) */?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td>
									<select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" <?=$disabled;?> style="width: 100px;">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php /* while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0)*/?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt pack_size" name="pack_size[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' disabled class="itm_inpt qty_on_hand" name="qty_on_hand[]" onKeyPress="return isNumberKey(event)" value="" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty" name="qty[]" onKeyPress="return isNumberKey(event)" value="" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt price" name="price[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt tax_percentage" name="tax_percentage[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt amt" value="" <?=$disabled;?> style="width: 70px;" readonly />
								</td>

								<td style="text-align:center; " class="last_td">
									<img style="" src="images/Delete.png" class="remove" />
								</td>
							</tr>
						</table>
					<?php }else{ ?>
						<table style="display: none;" id="clone_tbl">
							<tr>
								<td>
									<select class='frmstyle2 group' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Group</option>
										<?php while($row9 = mysql_fetch_array($result9)){ ?>
											<option value="<?=$row9['id']?>"><?=$row9['group'];?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select class='frmstyle2 item' name='item[]' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Item</option>
									</select>
								</td>
								<td>
									<select class="itm_sel pack_unittype" name="pack_unittype[]" <?=$disabled;?> style="width: 100px;">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0)?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td>
									<select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" <?=$disabled;?> style="width: 100px;">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0)?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt pack_size" name="pack_size[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' disabled class="itm_inpt qty_on_hand" name="qty_on_hand[]" onKeyPress="return isNumberKey(event)" value="" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty" name="qty[]" onKeyPress="return isNumberKey(event)" value="" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt price" name="price[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt tax_percentage" name="tax_percentage[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt amt" value="" <?=$disabled;?> style="width: 70px;" readonly />
								</td>
                                
								<td style="text-align:center; ">
									<img style="" src="images/Delete.png" class="remove" />
								</td>
							</tr>
						</table>
					<?php } 
					}
					?>
                    </div>
					</div>
                </div><!--row-fluid-->
                  <?php include_once 'require/footer.php';?>
                <!--footer-->

            </div><!--maincontentinner-->
        </div><!--maincontent-->

    </div><!--rightpanel-->

</div><!--mainwrapper-->


<script type="text/javascript">

		var items = new Array();//stores all current order item details
        <?=$createJsArrays;?>
        <?=$addToJsArray;?>
        var i = jQuery('#item_tbl').find('tr:last').prop('id');//get id of last row
        if(i == ''){//means no previous items(new order)
            i=0;
        }else{
            i++;//add 1 to make the row numbering consecutive
        }


function date_search(date){
	text_s = jQuery('#search_txt').val();
	window.location="buying_purchases_order.php?date="+date+"&text_s="+text_s;
}
function text_search(text_s){
	var date = jQuery('#searchdate').val();
	//window.location="buying_purchases_order.php?date="+date+"&text_s="+text_s;
	var acttable = jQuery('.ui-tabs-active a').html();
	if(acttable=='Ordered'){
		jQuery('#vnd_tbl_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Shopping'){
		jQuery('#vnd_tbl_shopping_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Completed'){
		jQuery('#vnd_tbl_completed_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Shipped'){
		jQuery('#vnd_tbl_shipped_filter input').val(text_s).trigger('keyup');
	}

}


    var dfr;//holds global deferred
	 jQuery(function() {
        jQuery( "#shopping_datetime" ).datepicker({
            changeMonth: true,
			dateFormat:"yy-mm-dd",
            changeYear: true,
			minDate: 'today',
        });
    });
	jQuery('#shopping_datetime').keypress(function(event) {
					return false;
	});
	jQuery(document).ready(function(e) {
        jQuery('.fc-state-highlight').prev('td').css('background-color','#CCC');
    });

	jQuery(window).resize(function() {
			var width = jQuery('.get_width').width()-4;
			var twidht = jQuery('#item_tbl tr td:last').width()+18
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");
	});
    jQuery(document).ready(function ($) {




		jQuery('#vnd_tbl,#vnd_tbl_shopping,#vnd_tbl_completed,#vnd_tbl_shipped').dataTable({
            "sPaginationType": "full_numbers",
			 "aaSorting": [[2,'desc']],
			"bAutoWidth": false,
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });



		$('.save_item').live('click', function(){

			<?php if($purid != ""){ ?>
				$('#newitemSubmit').val('1');
				$('#edit_from').submit();
			<?php } else{ ?>



						$('#newitemSubmit').val('1');
						$.ajax({
						url: 'save-new-ven-item.php',
						data: $('#edit_from').serialize(),
						type: 'POST',
						dataType: 'json',
						success: function(data){

								$('#myModal').modal('hide');
								jAlert('Item Added Successfully!','Alert Dialog');
								$('#edit_from')[0].reset();
								//return false;
						}
					});
			<?php } ?>
		});

       // $('#shopping_datetime').datepicker({dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true});
        $('#ordDt').datepicker({dateFormat: 'yy-mm-dd'});
        <?php if(isset($_GET['flag'])){ ?>
            $('#purchase_orders').hide();
            $('#item_tbl').css('width','100%');
            $('#totals_tbl').css('width','60%');
			//$('#totals_tbl tr td input').css('width','150px');
			var width = jQuery('.get_width').width()-4;
			var twidht = jQuery('#item_tbl tr td:last').width()+18;
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");
        <?php } ?>
       jQuery('.new_purchase').click(function(){
            <?php if(isset($_GET['flag'])){ ?>

                jConfirm('Do you want to close the current purchase order?','Confirm Dialog',function(r){
                if(r){
                    document.location = 'buying_purchases_order.php?flag';
                }
				});
            <?php }else{ ?>
                document.location = 'buying_purchases_order.php?flag';
            <?php } ?>
        });

<?php /*?><?php if($purchase_id != '' || $vendor_id != ''){ ?><?php */?>//add new vendor item only if vendor is selected
           $('#add-item').live('click',function(){
		   if(jQuery("#vendor").val()=="" || jQuery("#vendor").val()=="find"){
		   		jAlert("Please Select Vendor First!","Alert Dialog");
				return false;
		   }
		   jQuery("#sivendor_id").val(jQuery("#vendor").val());
		   jQuery('#ture_barcode').hide();
		   	$('#edit_from')[0].reset();
			   //alert();
			   $('#myModal').modal('show');
		/*$.ajax({
			url: 'popups/manage-items-bkup.php',
			data: { vendor: <?=$vendor_id?> },
			async: false,
			success: function(data){
				$('#popup').html(data);
				$('#myModal').modal('show');

			}
		})*/
	});


	$('.btn_save_item').live('click', function(){
		var ok = true;
		$('.required').each(function(){
			if($(this).val() == ''){
				ok = false;
				$(this).css('border-color','red');
			}else{
				$(this).css('border-color','');
			}
		});
		if($('#taxable').val() == 'yes' && $('#tax_percentage').val() == ''){
			ok = false;
			$('#tax_percentage').css('border-color','red');
		}else{
			$('#tax_percentage').css('border-color','');
		}
		if(ok){
			$.ajax({
				url: 'ajax/save-new-ven-item.php',
				data: $('#new_ven_itm_frm').serialize(),
				type: 'POST',
				dataType: 'json',
				success: function(data){
					//add group to item dropdowns if not already exists
					if(data.code == 1){
						var group = $('#group').val();
						var groupTxt = $('#group').children("option:selected").text();
						if(!$('select.group option[value*=' + group + ']').length > 0){
							$('select.group').append("<option value='" + group + "'>" + groupTxt + "</option>");
						}
						//dfr = $.Deferred();//global deferred object
						$('#addItem').click();
						//var rowid = $('tr.item_row :last').prop('id');
						var rowid = $('#item_tbl').find('tr:last').prop('id');
						$('#' + rowid + ' .group').val(data.group).trigger('change');
						setTimeout(function(){
						$('#' + rowid + ' .item').val(data.vendor_item).trigger('change');
						},1000);
						$('#myModal').modal('hide');
					}else if(data.code == 2){
						jAlert('This item already exists for selected vendor!','Alert Dialog');
					}

				}
			});
		}else{
			jAlert('The highlighted fields are required!','Alert Dialog');
		}
	});
        <?php /*?><?php } ?><?php */?>



        var subEnabl = false;//set to true if submit button has been enabled
        $('#vendor').change(function(){//Submit form when vendor changes to load groups
            if($(this).val() == 'find'){
				//window.location.href = "storepoint_vendors.php?#findvendors";
				jQuery('#filter_modal').modal('toggle');
			}else{
				$('#order_frm').submit();
			}
        });
        //$('#addItem').live('click',function(){//add new item
				let id=0;
				var sameorder_tr=[];
		$("#addItem").live('click',function () {

            $('#clone_tbl tr:last').clone(true).insertAfter('#item_tbl tr:last');
            $('#item_tbl tr:last').removeClass().addClass('item_row').prop('id', id);
            items[id] = new Array(
                "", //index 0 == inv_item_id(vendor_items.id)
                0, //index 1 == pack_size
                "", //index 2 == pack_unittype
                "", //index 3 == qty_in_pack
                "", //index 4 == qty_in_pack_unittype
                0, //index 5 == quantity
                0, //index 6 == price
                0, //index 7 == tax %
                0, //index 8 == price*quantity(subtotal)
                0, //index 9 == calculated tax total
                0  //index 10 == calculated total(qty*price+calculated tax)
               );
            id++;
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
            if($("#status option[value='Ordered']").length == 0){//add ordered option if not already added
                $('#status').append('<option value="Ordered">Ordered</option>');
            }

						var width = jQuery('.amt').width()-4;
						var twidht = jQuery('#item_tbl tr td:last').width()+18;
						jQuery("#totals_tbl").css("margin-right",twidht+"px");
						jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");

        });

		<?php if($purid == ""){ ?>
		if($("#vendor").val()!="" && $("#vendor").val()!="find"){
			$('#addItem').trigger('click');
		}
		<?php } ?>


    function add_row_func(){
      var group = $('#item_tbl tr:last .group').attr("value");
			var item = $('#item_tbl tr:last .item').attr("value");
			var pack_size = $('#item_tbl tr:last .pack_size').attr("value");
			var pack_unittype = $('#item_tbl tr:last .pack_unittype').attr("value");
			var qty_in_pack = $('#item_tbl tr:last .qty_in_pack').attr("value");
			var qty_pack_unittype = $('#item_tbl tr:last .qty_pack_unittype').attr("value");
			var qty = $('#item_tbl tr:last .qty').attr("value");
			var price = $('#item_tbl tr:last .price').attr("value");
			var tax_percentage = $('#item_tbl tr:last .tax_percentage').attr("value");
			//var amt = $('#item_tbl tr:last .amt').attr("value");

			id = Number($('#item_tbl tr:last').attr("id"));
			id = id + 1;
			$('#clone_tbl tr:last').clone(true).insertAfter('#item_tbl tr:last');
			$('#item_tbl tr:last').removeClass().addClass('item_row').prop('id', id);
			items[id] = new Array(
					"", //index 0 == inv_item_id(vendor_items.id)
					0, //index 1 == pack_size
					"", //index 2 == pack_unittype
					"", //index 3 == qty_in_pack
					"", //index 4 == qty_in_pack_unittype
					0, //index 5 == quantity
					0, //index 6 == price
					0, //index 7 == tax %
					0, //index 8 == price*quantity(subtotal)
					0, //index 9 == calculated tax total
					0  //index 10 == calculated total(qty*price+calculated tax)
				 );
      let idx=id-1;
			$('#item_tbl tr:last .group').remove();
			$('#item_tbl tr:last .item').remove();
			$('#item_tbl tr:last .pack_size').attr("value", pack_size);
			$('#item_tbl tr:last .pack_unittype').attr("value", pack_unittype);
			$('#item_tbl tr:last .qty_in_pack').attr("value", qty_in_pack);
			$('#item_tbl tr:last .qty_pack_unittype').attr("value", qty_pack_unittype);
			$('#item_tbl tr:last .qty').attr("value", qty);
			$('#item_tbl tr:last .price').attr("value", price);
			$('#item_tbl tr:last .tax_percentage').attr("value", tax_percentage);
			//$('#item_tbl tr:last .amt').attr("value", amt);
      var txt="<input type='checkbox' class='ipt_last_td'>";
			$('#item_tbl tr:last .last_td').empty();
			$('#item_tbl tr:last .last_td').append(txt);
      sameorder_tr.push(id);
			id++;
			if(!subEnabl){
					enableSubmit();
					subEnabl = true;
			}
			if($("#status option[value='Ordered']").length == 0){//add ordered option if not already added
					$('#status').append('<option value="Ordered">Ordered</option>');
			}

			var width = jQuery('.amt').width()-4;
			var twidht = jQuery('#item_tbl tr td:last').width()+18;
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");

		}
    $('.sameasorderd').click(function(){
       for(let j=0; j<sameorder_tr.length; j++){
				 let k=sameorder_tr[j];
				 // $('#'+k).find('.item').attr("value", '');
				 // $('#'+k).find('.group').attr("value", '');
				 $('#'+k).find('.pack_size').attr("value", '');
				 $('#'+k).find('.pack_unittype').attr("value", '');
				 $('#'+k).find('.qty_in_pack').attr("value", '');
				 $('#'+k).find('.qty_pack_unittype').attr("value", '');
				 $('#'+k).find('.qty').attr("value", '');
				 $('#'+k).find('.price').attr("value", '');
				 $('#'+k).find('.tax_percentage').attr("value", '');
				//$('#'+k).find('.amt').attr("value", '');
				 $('#'+k).find('.ipt_last_td').attr("checked", 'checked');
			 }
		});

		$('#status').change(function(){
			if(jQuery('#status').val()!='Shopping'){
				if(jQuery('.item_row').length==0 || jQuery('#0 .item').val()==""){
					jAlert('Please add items in order to submit PO as Ordered!','Alert Dialog');
					$('#status').val('Shopping');
					return false;
				}
            jConfirm("Changing order to "+jQuery('#status').val()+" will no longer allow you to modify. Continue?","Confirm Dialog",function(r){
            if(r){
                if(!subEnabl){
                    enableSubmit();
                    subEnabl = true;
                }
				if(jQuery('#status').val()=='Cancelled'){
					jQuery('#add-item').hide();
				}else{
					/* if(jQuery('#status').val()=='Ordered'){
						add_row_func();
					} */
					jQuery('#add-item').show();
				}
            }else{
                $('#status').val('Shopping');

            }

			});
			}else{
				$('#status').val('Shopping');
				$('#addItem').prop('disabled',false);
                $('.remove,#addItem').unbind('click').css('opacity',1);
			}
        });
		
        $('.remove').live('click', function(){//remove item
			var rowid = $(this).closest('tr').prop('id');
			jConfirm("Do you want to remove the item?","Confirm Dialog",function(r){
			delete items[rowid];
			$(this).closest('tr').remove();
				if(r){
					recalcTotals();
					if(!subEnabl){
						enableSubmit();
						subEnabl = true;
					}
					if(items[0] == undefined){
						$("#status option[value='Ordered']").remove();
					}
					jQuery('#sub_btn').prop('disabled',false);
					return false;
				}
			});
        });
		
        $('.group').live('change', function(){
            var rowid = $(this).closest('tr').prop('id');

			//var items = new Array([]);

			var vendorID ;
			var dfr;

			var vendorID = $("#vendor_id").val();
			if(typeof($("#vendor_id").val()) == 'undefined'){
				var vendorID = $("#vendor").val();
			}
			var location_id = '<?= $_SESSION['loc'] ?>';

            $.ajax({
                url: 'ajax/load-vendor-items.php',
                //data: { group: $(this).val(), vendor: '<?=$vendor_id?>' },
				 data: { group: $(this).val(), vendor: vendorID, location_id: location_id },
                dataType: 'json',
                success: function(data){
                    var select = $("#" + rowid + " select.item");
                    select.empty();
                    select.append('<option value="">Select Item</option>');
                    $(data).each(function(a,b){
                        select.append('<option value="' +  b.id + '" data-loc_inv_id="' +  b.loc_inv_id + '">' + b.description + '</option>');
                    });
                    dfr.resolve();
                }
            });
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
        $('.item').live('change', function(){
            var rowid = $(this).closest('tr').prop('id');
			console.log('rowid =>'+ rowid);
			
			var vendor_id = $("#vendor_id").val();
			if(typeof($("#vendor_id").val()) == 'undefined'){
				var vendor_id = $("#vendor").val();
			}
			//console.log('DATA : '+$(this).find(':selected').attr('data-loc_inv_id'));
			var loc_inv_id = $(this).find(':selected').attr('data-loc_inv_id');
			
			if(typeof(items[rowid])=='undefined'){
				items[0] = new Array('',0,'','','',0,0,0,0,0,0);
			}
            $.ajax({
                url: 'ajax/get-vendor-item-details.php',
                data: { item: $(this).val(), vendor: vendor_id, loc_inv_id:loc_inv_id },
                dataType: 'json',
                success: function(datas){
					
                    data = datas['data'][0];
                    //add response to items array
                    items[rowid][0] = data.id; //inv_item_id(vendor_items.id)
                    items[rowid][1] = data.pack_size;//pack_size
                    items[rowid][2] = datas['unit_type'];//pack_unit_type
                    items[rowid][3] = data.qty_in_pack;//quantity in pack
                    items[rowid][4] = datas['unit_type'];//quantity in pack unittype
                    items[rowid][6] = data.price;//price
                    items[rowid][7] = data.tax;//tax %
					
					console.log(datas['unit_type'][0]);
					//$(".pack_unittype").html("<option value=''>Select Unit</option><option value='new'>Add New Unit</option><option value='24'>KG</option><option value='8'>CASE</option><option value='35'>Box</option>");
					$("#" + rowid + " .pack_unittype").html(datas['unit_type']);
					$("#" + rowid + " .qty_pack_unittype").html(datas['unit_type']);
					
                    $("#" + rowid + " .pack_size").val(data.pack_size);
                    $("#" + rowid + " .pack_unittype").val(data.pack_unittype);
                    $("#" + rowid + " .qty_in_pack").val(data.qty_in_pack);
                    $("#" + rowid + " .qty_pack_unittype").val(data.qty_in_pack_unittype);
					$("#" + rowid + " .qty_on_hand").val(data.qty_on_hand);
                    $("#" + rowid + " .price").val(data.price);
                    $("#" + rowid + " .tax_percentage").val(data.tax);
					$("#" + rowid + " .qty").keyup();

                    recalcItemTotal(rowid);
					console.log('rowid : '+rowid);
                    if(!subEnabl){//enable submit
                        enableSubmit();
                        subEnabl = true;
                    }
                }
            });
        });
        $('.qty,.price,.tax_percentage').live('keyup', function(){
            var rowid = $(this).closest('tr').prop('id');
            var clas = $(this).prop('class').split(' ')[1];
            var index;
            switch(clas){
                case 'qty':
                    index = 5;
                    break;
                case 'price':
                    index = 6;
                    break;
                case 'tax_percentage':
                    index = 7;
                    break;
            }
			console.log('live Keyup : '+rowid);
            items[rowid][index] = numChk($(this));//add qty to items array
            recalcItemTotal(rowid);
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
		
		$('.rec_qty').live('keyup', function(){
            var rowid = $(this).closest('tr').prop('id');
            var clas = $(this).prop('class').split(' ')[1];
            var index;
            switch(clas){
				case 'rec_qty':
                    index = 55;
                    break;
            }
			console.log('live Keyup2 : '+rowid);
            items[rowid][index] = numChk($(this));//add qty to items array
			recalcItemTotal2(rowid);
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
		
        $('#btnSearch').click(function(){
            $('#searchFrm').submit();
        });
        $('.pack_unittype,.qty_pack_unittype').on('change', function () {//add new unit input for element
            var rowid = $(this).closest('tr').prop('id');
            var clas = $(this).prop('class').split(' ')[1];
            if ($(this).val() == "new") {
                $("#" + rowid + " ." + clas).replaceWith("<input type='text' class='frmstyle2 " + clas + "' name='" + clas + "[]' style='width:150px;' <?=$disabled;?>>");
            }
        });
        $(document).on('change', 'select#terms', function () {//add new terms
            if ($(this).val() == "new") {
                var currDropdown = $(this).clone();
                $(document).on('click', '.icon_clear', function () {
                    $(".clearable").replaceWith(currDropdown);
                    $('#terms').val('');
					$('#terms_new').val('no');
                });
				$('#terms_new').val('Yes');
                $("#terms").replaceWith("<span class='clearable'><input type='text' class='frmstyle1 xinput' id='terms' style='width:100%;' name='terms' <?=$disabled;?>><span style='left: 98%;position: relative; top: -27px;' class='icon_clear'>X</span></span>");
                $('.icon_clear').delay(200).fadeIn(300);
            }
        });

        /*$('#date_search_box').on('paste keyup change','input',function(){
            if (!this.value) {
                $('#date_search_x').fadeOut(300);
                filter('','vnd_tbl',2,'clear');
            }else{
                $('#search_x').trigger('click');
                $('#date_search_x').delay().fadeIn(300);
                filter(this.value,'vnd_tbl',2,'search');
            }
        });
        $('#date_search_x').on('click',function(){
            $('#date_search_box').find('input').val('');
            filter('','vnd_tbl',2,'clear');
            $(this).fadeOut(300);
        });
        if($('#date_search_box').find('input').val() != ''){
            $('#date_search_x').show();
        }*/

        $('.search_box').on('paste keyup','input',function(){
            if (!this.value) {
                $('#search_x').fadeOut(300);
                filter('','vnd_tbl',3,'clear');
            }else{
                $('#date_search_x').trigger('click');
                $('#search_x').delay().fadeIn(300);
                filter(this.value,'vnd_tbl',3,'search');
            }
        });
        $('#search_x').on('click',function(){
            $('.search_box').find('input').val('');
            filter('','vnd_tbl',3,'clear');
            $(this).fadeOut(300);
        });
        if($('.search_box').find('input').val() != ''){
            $('#search_x').show();
        }

        if($('#status').val() == 'Cancelled'){//onpageload if status is cancelled, disable removing items
            $('.remove').unbind('click').css('opacity',.3);
        }
        $('#submit_order').click(function(e){
			jQuery('#hid_same_as_orderd').val('yes');
			e.preventDefault();
			if(submitValidation()){
				if($('#hid_same_as_orderd').val() == 'yes') {
					jConfirm("Would you like to change status to Shipped?", 'Confirm', function(r){
						if(r){
							$('#status').val('Shipped');
							$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
							$('#purchaseSubmit').val('1');
							$('#order_frm').submit();
						}else{
							$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
							$('#purchaseSubmit').val('1');
							$('#order_frm').submit();
						}
					});
				} else {
					$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
					$('#purchaseSubmit').val('1');
					$('#order_frm').submit();
				}
			}

		});
		
		$('input[type=checkbox].check').on('click', function () {
					console.log('HELLLLO');
					var rowid = $(this).closest('tr').prop('id');
					var rowid1 = $(this).closest('tr').prop('id');					
					rowid = rowid.split('_')[0];
					index = 11; //alert(rowid +" "+ index);
					//var rowid = $(this).closest('tr').next().prop('id');
					if ($(this).is(':checked')) {
						//addDisabled('Yes', rowid1);
						console.log('Yes');
						$('.' + rowid1 + '_ship .check_val').val('yes');
						
						$('.' + rowid1 + '_ship .qty').removeAttr('readonly');
						$('.' + rowid1 + '_ship .price').removeAttr('readonly');
						
						items[rowid][index] = true;
						items[rowid][12] = jQuery('#'+rowid1).find('.rec_qty').val();
						items[rowid][17] = jQuery('#'+rowid1).find('.rec_price').val();
					} else {
						//addDisabled('No', rowid1);
						$('.' + rowid1 + '_ship .check_val').val('no');
						items[rowid][index] = false;
						$('.' + rowid1 + '_ship .qty').attr('readonly','readonly');
						$('.' + rowid1 + '_ship .price').attr('readonly','readonly');
					}
					recalcItemTotal(rowid);
					
					if (!subEnabl) {
						enableSubmit();
						subEnabl = true;
					}
					
				});

				$('input[type=checkbox].sd_check').on('click', function () {
					var rowid = $(this).closest('tr').prop('id');
					var rowid1 = $(this).closest('tr').prop('id');
					console.log('roid=>' + rowid);
					rowid = rowid.split('_')[0];
					index = 29; //alert(rowid +" "+ index);
					//var rowid = $(this).closest('tr').next().prop('id');
					if ($(this).is(':checked')) {
						//addDisabled('Yes', rowid1);
						console.log('Yes');
						$('.' + rowid1 + '_ship .check_val').val('yes');
						items[rowid][index] = true;
					} else {
						//addDisabled('No', rowid1);
						$('.' + rowid1 + '_ship .check_val').val('no');
						items[rowid][index] = false;
					}
					recalcshippedItemTotal(rowid);
					if (!subEnabl) {
						enableSubmit();
						subEnabl = true;
					}
				});

        function filter (term, _id,cols,type){
            var suche = term.toLowerCase();
            var table = document.getElementById(_id);
            var ele;
            if(type == 'search'){
                for (var r = 2; r < table.rows.length; r++){
                    for(var i=2;i<=cols;i++){
                        ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g,"");
                        if (ele.toLowerCase().indexOf(suche)>=0 ){
                            table.rows[r].style.display = '';
                            break;
                        }else{
                            table.rows[r].style.display = 'none';
                        }
                    }
                }
            }else{
                for (r = 1; r < table.rows.length; r++){
                    table.rows[r].style.display = '';
                }
            }
        }
        function hideDropOptions(ele,val){
            ele.html('');
            $('#clone_tbl .price_unit option').each(function(){
                if($(this).val() == val){
                   ele.append($(this).clone(false));
                }
            });
        }
        function numChk(val){
            if(isNaN(val.val())){
                jAlert('This is a numeric field only!','Alert Dialog');
                val.val(0);
                return 0;
            }else{
                return val.val();
            }
        }
		function recalcItemTotal2(rowid){
			
		   /* console.log('items[rowid][2] : '+items[rowid][2]);
			console.log('items[rowid][3] : '+items[rowid][3]);
			console.log('items[rowid][4] : '+items[rowid][4]);
			console.log('items[rowid][55] : '+items[rowid][55]);
			console.log('items[rowid][6] : '+items[rowid][6]);
			console.log('items[rowid][7] : '+items[rowid][7]);
			console.log('items[rowid][8] : '+items[rowid][8]);
			console.log('items[rowid][9] : '+items[rowid][9]);
			console.log('items[rowid][10] : '+items[rowid][10]); */
			console.log('AMT2 : '+$('.amt_'+rowid).val());
			var ite = items[rowid][55] * items[rowid][6];
			if(ite == 'NaN'){
				ite = '0.00';
			}
			/* if(ite > items[rowid][10]){
				jAlert("Please enter Shipped quantity less than ordered");
				jQuery('.rec_qty').val('');
				$('.amt_'+rowid).val('');
				return false;
			} */
			console.log('ite : '+ite);
			$('.amt_'+rowid).val('$'+ite.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			
			var sub_ship = $('#subTotal1').val() + ite;
            console.log('subTotal1subTotal1 '+$('#subTotal1').val());
			
			items[rowid][8] = round2Decimals(nullCheck(items[rowid][5]) * nullCheck(items[rowid][6]));//calculate subtotal (qty*price) 
			items[rowid][9] = round2Decimals(nullCheck(items[rowid][7]) * .01 * nullCheck(items[rowid][8]));//calculate and add tax to items array
			items[rowid][10] = round2Decimals(nullCheck(items[rowid][8]) + nullCheck(items[rowid][9]));//calculate and round then add to array
			
			if (items[rowid][29]) {//item is received
				
				items[rowid][30] = round2Decimals(nullCheck(items[rowid][22]) * round2Decimals(nullCheck(items[rowid][27])));//calculate received subtotal (price*qty)		
				items[rowid][31] = round2Decimals(nullCheck(items[rowid][28]) * .01 * round2Decimals(nullCheck(items[rowid][30])));//calculate and add tax to items array
				items[rowid][32] = round2Decimals(nullCheck(items[rowid][30]) + round2Decimals(nullCheck(items[rowid][31])));//calculate and round then add to arra

			} else {//item not received

				items[rowid][30] = round2Decimals(nullCheck(items[rowid][22]) * round2Decimals(nullCheck(items[rowid][27])));//calculate received subtotal (price*qty)		
				items[rowid][31] = round2Decimals(nullCheck(items[rowid][28]) * .01 * round2Decimals(nullCheck(items[rowid][30])));//calculate and add tax to items array
				items[rowid][32] = round2Decimals(nullCheck(items[rowid][30]) + round2Decimals(nullCheck(items[rowid][31])));//calculate and round then add to array
			}
			
            //$('#' + rowid + ' td input.amt').val('$'+(items[rowid][10]).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			
		}
        function recalcItemTotal(rowid){
			/* console.log('items[rowid][2] : '+items[rowid][2]);
			console.log('items[rowid][3] : '+items[rowid][3]);
			console.log('items[rowid][4] : '+items[rowid][4]);
			console.log('items[rowid][55] : '+items[rowid][55]);
			console.log('items[rowid][6] : '+items[rowid][6]);
			console.log('items[rowid][7] : '+items[rowid][7]);
			console.log('items[rowid][8] : '+items[rowid][8]);
			console.log('items[rowid][9] : '+items[rowid][9]);
			console.log('items[rowid][10] : '+items[rowid][10]); */
			
			console.log('r_amount : '+$('.amt_'+rowid).val());
			console.log('amount : '+$('#' + rowid + ' td input.r_amount').val());
			
			items[rowid][88] = $('.amt_'+rowid).val();
			items[rowid][99] = $('.tax_'+rowid).val();
			
		
			console.log('AMT : '+$('#' + rowid + ' td input.amt').val());
            items[rowid][8] = round2Decimals(nullCheck(items[rowid][5])*nullCheck(items[rowid][6]));//calculate sutotal (price*qty)
            items[rowid][9] = round2Decimals(nullCheck(items[rowid][7])*.01*nullCheck(items[rowid][8]));//calculate and add tax to items array
            items[rowid][10] = round2Decimals(nullCheck(items[rowid][8]) + nullCheck(items[rowid][9]));//calculate and round then add to array
            $('#' + rowid + ' td input.amt').val('$'+(items[rowid][10]).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			
			recalcTotals();
        }
        function nullCheck(val){
            return val != null || val != '' ? val : 0;
        }
		
		function recalcTotals(){
            var subtotal = 0;
            var taxtotal = 0;
            var total = 0;
			var ver_subtotal = 0;
			var ver_taxtotal = 0;
			var ver_total = 0;
			var ship_subtotal = 0.00;
			var ship_taxtotal = 0;
			var ship_total = 0;
			
            for(var k=0;k<items.length;k++){
                if(items[k] != undefined){
                    subtotal += items[k][8];
                    taxtotal += items[k][9];
                    total += items[k][10];
                }
				
				if (items[k][88] != undefined) {
					var subTotal1 = items[k][88].substring(1,items[k][88].length)
					ship_subtotal += parseInt(subTotal1);
					
					var taxTotal1 = items[k][99].substring(1,items[k][99].length)
					ship_taxtotal += parseInt(taxTotal1);
				}
				
								
				console.log('ship_taxtotal ======= '+ship_taxtotal); 
            }
			
			ship_total = (ship_subtotal + ship_taxtotal);
			
			console.log('ship_total : '+ship_total);
			console.log('rec_subtotalrec_subtotal : '+ship_taxtotal);
			
			ver_subtotal = (subtotal - ship_subtotal);
			ver_taxtotal = (taxtotal - ship_taxtotal);
			ver_total = (total - ship_total);
					
            $('#subTotal').val('$'+(subtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $('#taxTotal').val('$'+(taxtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $('#total').val('$'+(total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			
			$('#subTotal1').val('$'+(ship_subtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			$('#taxTotal1').val('$'+(ship_taxtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			$('#total1').val('$'+(ship_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			
			$('#sub_total_vari1').val('$'+(ver_subtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			$('#tax_vari1').val('$'+(ver_taxtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
			$('#total_vari1').val('$'+(ver_total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        }
		
		function addBreacket(val) {
			if (val < 0) {
				val = parseFloat(Math.abs(val)).toFixed(2);
				val = "(" + val + ")";
			} else {
				val = parseFloat(val).toFixed(2);
			}
			return val;
		}
		
        function round2Decimals(rnd){
            return Math.round(rnd*Math.pow(10,2))/Math.pow(10,2);
        }
        function enableSubmit(){
            //$('#submit_order').unbind('click').prop('src','images/submit.png').click(function(e){
				$('#hid_same_as_orderd').val('yes')
				$('#submit_order').unbind('click').prop('disabled',false).click(function(e){
                e.preventDefault();
                if(submitValidation()){
					if($('#hid_same_as_orderd').val() == 'yes') {
						jConfirm("Would you like to change status to Shipped?", 'Confirm', function(r){
							if(r){
								$('#status').val('Shipped');
								$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
								$('#purchaseSubmit').val('1');
								jQuery('.pinned').html('');
								$('#order_frm').submit();
							}else{
								$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
								$('#purchaseSubmit').val('1');
								jQuery('.pinned').html('');
								$('#order_frm').submit();
							}
						});
					}else{
						$('form#order_frm input:disabled,form#order_frm select:disabled').prop('disabled', false);
						$('#purchaseSubmit').val('1');
						jQuery('.pinned').html('');
						$('#order_frm').submit();
					}

					/* $('#order_frm input,#order_frm select').attr('disabled',false);

                    $('#purchaseSubmit').val('1');
                    $('#order_frm').submit(); */

                }
            });
        }
        function submitValidation(){
            if($('#vendor').val() == ''){
                jAlert('Please select a vendor!','Alert Dialog');
                //$('#vendor').closest('td').addClass('control-group error');
                return false;
           /* }else if($('#po').val() == ''){
                jAlert('Please insert a PO!','Alert Dialog');
                //$('#po').closest('td').addClass('control-group error');
                return false;*/
            }else if($('#shopping_datetime').val() == ''){
                jAlert('Please insert a shopping date!','Alert Dialog');
                //$('#shopping_datetime').closest('td').addClass('control-group error');
                return false;
            }else if($('#terms').val() == ''){
                jAlert('Please select terms!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
			}else if($('#payment_type').val() == ''){
                jAlert('Please select Payment Type!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
			}else if($('#delivery_method').val() == ''){
                jAlert('Please select Delivery Method!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
            }else{
                return checkInputs();
            }
        }
        function checkInputs(){
            var succ = true;
            for(var j=0;j<items.length;j++){
                if(document.getElementById(j)){
                    var gr = $('#'+j).find('.group');
                    var it = $('#'+j).find('.item');
                    var ps = $('#'+j).find('.pack_size');
                    var put = $('#'+j).find('.pack_size_unittype');
                    var qip = $('#'+j).find('.qty_in_pack');
                    var qipu = $('#'+j).find('.qty_pack_unittype');
                    var qt = $('#'+j).find('.qty');
                    var pr = $('#'+j).find('.price');
                    var tax = $('#'+j).find('.tax_percentage');

                    if(gr.val() == ''){
                        succ = false;
                        jAlert('Please select a group!','Alert Dialog');
                        //gr.closest('td').addClass('control-group error');
                        break;
                    }else if(it.val() == ''){
                        succ = false;
                        jAlert('Please select an item!','Alert Dialog');
                        //it.closest('td').addClass('control-group error');
                        break;
                    }else if(put.val() == ''){
                        succ = false;
                        jAlert('Please select a pack unit!','Alert Dialog');
                        //put.closest('td').addClass('control-group error');
                        break;
                    }else if(qip.val() == ''){
                        succ = false;
                        jAlert('Please enter quantity pack!','Alert Dialog');
                        //qip.closest('td').addClass('control-group error');
                        break;
                    }else if(qipu.val() == ''){
                        succ = false;
                        jAlert('Please select a quantity pack unit!','Alert Dialog');
                        //qipu.closest('td').addClass('control-group error');
                        break;
                    }else if(qt.val() == ''){
                        succ = false;
                        jAlert('Please enter a quantity!','Alert Dialog');
                        //qt.closest('td').addClass('control-group error');
                        break;
                    }else if(pr.val() == ''){
                        succ = false;
                        jAlert('Please enter a price!','Alert Dialog');
                        //pr.closest('td').addClass('control-group error');
                        break;
                    }else if(tax.val() == ''){
                        succ = false;
                        jAlert('Please enter a tax percentage!','Alert Dialog');
                        //tax.closest('td').addClass('control-group error');
                        break;
                    }
                }
            }
            return succ;
        }
        $('#item_tbl input,#item_tbl select,#item_tbl textarea').on('change keyup', function(){
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
		$('.topTbl input,.topTbl select,.topTbl textarea').on('change keyup', function(){
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });

		$('#popup').on('keyup','#item_search',function(e){ //alert('asd');
	if($(this).val().length > 3){
		$.ajax({
			url: 'ajax/get-items-by-term.php?s=' + $(this).val(),
			dataType:'json',
			success:function(data){
				$('#item_results').html('');
				$(data).each(function(a,b){
					$('#item_results').append("<li data-value='" + b.id + "'>" + b.description + "</li>");
				});
			}
		});
	}else{
		$('#popup table tr td input[type=text],#popup table tr td select').each(function(){
			$(this).val('');
		});
		$('#item').val('');
		$('#item_results').html('');
	}
});

$('#item_results li').live('click',function(){ //alert('asd');
	$.ajax({
		url: 'ajax/get-item-by-id.php?id=' + $(this).data('value'),
		dataType:'json',
		success:function(data){
			$('div#popup #item').val(data.id);
			$('div#popup #item_id').val(data.item_id);
			$('div#popup #description').val(data.description);
			//$('div#popup #group').val(data.inv_group_id);
			$('div#popup #notes').val(data.notes);
			$('div#popup #pack_unittype').val(data.unit_type);
			$('div#popup #manufacturer').val(data.manufacturer);
			$('div#popup #brand').val(data.brand);
			$('div#popup #model_number').val(data.model_number);
			$('div#popup #taxable').val(data.taxable);
		}
	});
});

        <?php
        if($purchase_id != ''){
            for($i=0;$i<$k;$i++){
                echo "recalcItemTotal(" . $i .");";
            }
        }?>
    });
</script>


<!-- Modal -->
<div id="myModal" class="modal hide fade">
<form id="edit_from" onSubmit="return validate()" name="frm" action="" method="post" >
                  <input type="hidden" name="code_id" value="" id="code_id">
				   <input type="hidden" name="newitemSubmit" value="" id="newitemSubmit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Manage Vendor Items</h3>
  </div>
  <div class="modal-body" style="height:415px;">
  		<div style="padding:10px;clear:both;">
            <table width="100%" style="margin-bottom:20px;">
				 <tr>
                      <td width="150" align="left"><label>Status:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						<select name="status" id="status" style="width:277px;">
							<option value="" > - - - Select Status - - -</option>
							<option selected="selected" value="active">Active</option>
							<option value="inactive" >Inactive</option>
                   		 </select>
					  </td>
                 </tr>
				  <tr>
                      <td width="150" align="left"><label>Barcode: <span style="color:red"> *</span></label></td>
					  <td id="barcode_p" width="220">
							<span class="field input-append" style="margin:0;">
							<input style="width: 240px;margin-bottom: 10px;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="" />
							<input type="hidden" id="barcode_valid" value="">
							<span class="add-on">
							<a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search"></a>
							</span>
							<span style="display:none;" id="ture_barcode" class="add-on1">
								<img style="height:20px; width:20px; margin-left:5px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
							</span>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Market:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<select id="d_market" onChange="get_group(this.value)" style="width:277px;" name="d_market">
                             <option value="">- - - Select Market - - -</option>
                              <option value="All">All</option>
                              <option value="Bar">Bar</option>
                              <option value="Hotel">Hotel</option>
                              <option value="Restaurant">Restaurant</option>
                              <option value="Retail">Retail</option>
                              <option value="Other">Other</option>
                            </select>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Group:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<span class="field" id="group_span">
								<?php //$query1=mysql_query("SELECT id,description from inventory_groups order by description"); ?>
								<select name="group" id="group" style="width:277px;">
								  <option value="" >- - - Select Group - - -</option>
								  <?php /*while($row_g = mysql_fetch_array($query1)){?>
								  <option value="<?php echo $row_g['id']; ?>" ><?php echo $row_g['description']; ?></option>
								  <?php }*/ ?>
								</select>
							</span>
                    		<input type="hidden" name="group_ins_type" id="group_ins_type" value="old">
                    		<span class="field" id="new_group_span"  style="display:none;">
								<input type="text" class="input-xlarge" id="new_gruop" value="" name="new_gruop" style="width: 266px;margin-bottom: 10px;" >
								 <span style="vertical-align:super;margin-left: -25px;" id="group_cancel_btn">
								 	<i class="icon-remove"></i>
								 </span>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Inventory Items:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<span class="field" id="drop_span">
								<select id="inv_item" name="inv_item" style="width: 277px;margin-bottom:10px;">
								  <option value="" >- - -Select Inventory Item - - -</option>
								  <option value='new_inv_item'>- - - Add New Inventory Item - - -</option>
								</select>
							</span>
                   			<input type="hidden" name="inv_item_type" id="inv_item_type" value="old">
                    		<span class="field" id="new_span" style="display:none;">
                    			<input type="text" class="input-xlarge" id="inv_item1" value="" name="inv_item_new" style="width: 266px;margin-bottom: 10px;" >
                    			<span style="vertical-align:super;  margin-left: -25px;" id="cancel_btn">
									<i style="" class="icon-remove"></i>
								</span>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Name :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="name" value="" name="name" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Notes:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_notes" value="" name="ni_notes" >
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Barcode:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_barcode" value="" name="ni_barcode">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Manufacture:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_manufacture" value="" name="ni_manufacture">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Brand:</label></td>
					  <td width="220">
							 <input type="text" class="input-xlarge" id="ni_brand" value="" name="ni_brand">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Model Number:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_model" value="" name="ni_model">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Image:</label></td>
					  <td width="220">
						<span id="imagebox"> </span>
                        <span class="field" style="margin:0; width:100%; float:left;">
                        	<input type="hidden" value="" id="upc_search_image" value="upc_search_image">
							<input type="hidden" name="oldimage" id="oldimage" value="">
							<input type="hidden" name="digital_image_name" id="digital_image_name" value="">
							<input type="hidden" name="digital_image_delete" id="digital_image_delete" value="N">
							<a data-target="#imageModal" href="upload_inv_item_img.php" role="button" class="btn btn-primary" data-toggle="modal" id="imageLink" style="padding:3px  ">Upload Images</a>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Internal Number:</label></td>
					  <td width="220">
						<input type="text" class="input-xlarge" id="vendor_internal_number" value="" name="vendor_internal_number" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Pack Size :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
                    	<input type="text" class="input-xlarge" id="pack_size" value="" name="pack_size" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Pack Unit Type :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						<div class="selectouter12 select_w3">
					<?php
						$unittype = "SELECT * from inventory_item_unittype ORDER BY unit_type";
						$res_unit_type = mysql_query($unittype);
					?>
                    <select name="pack_unit_type" id="pack_unit_type" style="width:277px;" >
                      <option value="" >- - - Select Unit Type - - -</option>
                      <?php while($row_unit_type= mysql_fetch_array($res_unit_type)){ ?>
                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>">
					  <?php echo $row_unit_type['unit_type'];?>
                      </option>
                      <?php } ?>
                    </select>
                    </div>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Qty In Pack:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							 <input type="text" class="input-xlarge" id="qty_in_pack" value="" name="qty_in_pack" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Qty In Pack Unit Type:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<div class="selectouter12 select_w3">
                    <select name="qty_in_pack_unit_type" id="qty_in_pack_unit_type" style="width:277px;" >
                      <option value="" >- - - Select Unit Type - - -</option>
                      <?php
						$res_unit_type = mysql_query($unittype);
						while($row_unit_type= mysql_fetch_array($res_unit_type)){ ?>
                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
                      <?php } ?>
                    </select>
                   </div>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Taxable:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						  <select onChange="javascript:if(this.value=='yes'){jQuery('#tax_per').show();}else{jQuery('#tax_per').hide();}" style="width:277px;" id="ni_taxable" name="ni_taxable">
                        <option value="">- - - Select Taxable - - -</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        </select>
					  </td>
                 </tr>
				  <tr id="tax_per" style="display:none;">
                      <td width="150" align="left"><label>Tax Percentage:</label></td>
					  <td width="220">
						<input type="text" class="input-xlarge" id="tax_percentage" value="" name="tax_percentage" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				  <tr>
                      <td width="150" align="left"><label>Price:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						  <input type="text" onBlur="fix(this.value,'price')" onKeyPress="javascript:if(event.keyCode==13){fix(this.value,'price');return false;}" class="input-xlarge" id="price" value="" name="price" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Promotion:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="promotion" value="" name="promotion" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Promotion Price:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="promotion_price" value="" name="promotion_price" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Color:</label></td>
					  <td width="220">
						  <select name="inv_color" id="inv_color" style="width:277px;" >
                    	<option value="">- - -Select Color- - -</option>
                        <?php $cquery = mysql_query("select id,code from inventory_items_color");
							while($cres = mysql_fetch_array($cquery)){ ?>
                            	<option value="<?php echo $cres['id']; ?>"><?php echo $cres['code']; ?></option>
                            <?php } ?>
                    </select>
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Size:</label></td>
					  <td width="220">
						  <select name="inv_size" id="inv_size" style="width:277px;" >
                    	<option value="">- - -Select Size- - -</option>
                        <?php $squery = mysql_query("select id,code from inventory_items_size");
							while($sres = mysql_fetch_array($squery)){ ?>
                            	<option value="<?php echo $sres['id']; ?>"><?php echo $sres['code']; ?></option>
                            <?php } ?>
                    </select>
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Type:</label></td>
					  <td width="220">
						  <select name="inv_type" id="inv_type" style="width:277px;" >
                    	<option value="">- - -Select Type- - -</option>
                        <?php $tquery = mysql_query("select id,code from inventory_items_type");
							while($tres = mysql_fetch_array($tquery)){ ?>
                            	<option value="<?php echo $tres['id']; ?>"><?php echo $tres['code']; ?></option>
                            <?php } ?>
                    </select>
					  </td>
                 </tr>
				 <!-- <tr>
                      <td align="left" colspan="2"><h3 style="margin-bottom:10px;"> Supplied Information </h3></td>

                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Purchase From:</label></td>
					  <td width="220">
						  <div id="clientsearch" class="input-append">
                            <input autocomplete="off" name="vendors" onKeyDown="javascript:if(event.keyCode==13){getvendor1(2);return false;}"  type="text" id="vendors" tabindex="0"  style="width:240px;height:21px;"  />
                            <span style="height:21px;" class="add-on" > <a href="" rel= 'client' data-toggle="modal" data-target="#filter_modal" data-refresh="true" class="icon-search" style="position: relative;"> </a> </span>
                            <input name="purchased_from_vendor" type="hidden"   value=" "  id="vendor_id" />
                          </div>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Purchase Price:</label></td>
					  <td width="220">
						   <input type="text" class="input-xlarge" id="purchased_price" value="" name="purchased_price" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Last Purchase:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="purchased_last" value="" name="purchased_last" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>-->
				 <tr>
                      <td width="150" align="left"><label>Created On:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" readonly id="created_on" value="BusinessPanel" name="created_on" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Created By:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="created_by" readonly value="<?=$_SESSION['user_full_name'];?>" name="created_by" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Created Date & Time:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="created_datetime" readonly value="<?php echo $ldatetitme;//echo date('Y-m-d h:i:s',strtotime($ldatetitme));?>" name="created_datetime" style="width: 266px;margin-bottom:10px;"> <?php //echo date('Y-m-d H:i:s'); ?>
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>Last On:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="last_on" value="BusinessPanel" readonly name="last_on">
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>By:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="last_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="last_by">
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>Date & Time:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="Last_datetime" readonly value="<?php echo date('Y-m-d h:i:s',strtotime($ldatetitme)); ?>" name="last_datetime">
					  </td>
                 </tr>
				  <!--<button class="btn btn-primary"  name="Submit" value="Submit"> Submit</button>
                  <button class="btn btn-primary reset" type="reset">Reset</button>-->
			</table>
		</div>
		<input name="sivendor" type="hidden" value="<?php echo $vendor_id; ?>"  id="sivendor_id" />
                  <input type="hidden" id="group1_inp" name="group1_inp" value="<?php echo $_REQUEST['group1']; ?>" >
                  <input type="hidden" id="market_inp" name="market_inp" value="<?php echo $_REQUEST['market']; ?>" >


  </div>
  <div class="modal-footer" style="text-align:center;">
   <p>
    <button class="btn btn-default btn_cancel" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button name="Submit" class="btn btn-primary save_item">Submit</button>
   </p>
  </div>
    </form>
</div>
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
         <!-- <a href="#" id="client_add"> <img id="ai" src="images/Add_16.png"></a>-->

		  <a href="#" id="open_filter_modal" style="position:absolute;margin-left:5px;"><button style="background: #5b5b5b; border-color: #6e6e6e;padding:4px;width:30px" class="btn btn-success btn-large"><i class="fa fa-filter"></i></button></a>


      </label>
    </div>
    <div class="modal-body" id="modalcontent" style="height:415px;"> </div>
    <div class="modal-footer" style="text-align: center;">
      <p >
        <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
      </p>
    </div>
</div>

<div id="filterformmodal" class="modal hide fade">
<?php
$sql_vt = "SELECT vendor_type_id, code FROM vendors_types";
$res_vt = mysql_query($sql_vt);

$sql_it = "SELECT id,group_id,description FROM inventory_groups";
$res_it = mysql_query($sql_it);
?>
    <form id="filterform" name="filterform" method="post" action="" class="form-horizontal label-left">

		<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h3>Filters</h3>
		</div>
		<div class="modal-body" style="max-height:500px !important;">
			<div class="control-group">
				<label class="control-label">Name: </label>
				<div class="controls">
					 <input type="text" name="filters[name]" value="" id="filtersname" placeholder="Name" title="Name" style=" height:32px; width:322px;">
				</div>
			</div>

			<!--<div class="control-group">
				<label class="control-label">Type: </label>
				<div class="controls">
					 <input type="text" name="filters[type]" value="" id="filterstype" placeholder="Type" title="Type" style="width:310px;">
				</div>
			</div>-->
			<div class="control-group">
				<label class="control-label">Vendor Type: </label>
				<div class="controls">
					 <select multiple name="filters[vtype][]" value="" id="filtersvtype" style="width:325px;">
					 <option value="all">All</option>
					 <?php while($result_vt = mysql_fetch_array($res_vt)){?>
						<option value="<?php echo $result_vt["vendor_type_id"];?>"><?php echo $result_vt["code"];?></option>
						<?php } ?>
					 </select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Item Type: </label>
				<div class="controls">
					 <select multiple name="filters[itype][]" value="" id="filtersitype" style="width:325px;">
					 <option value="all">All</option>
					 <?php while($result_it = mysql_fetch_array($res_it)){?>
						<option value="<?php echo $result_it["id"];?>"><?php echo $result_it["description"];?></option>
						<?php } ?>
					 </select>
				</div>
			</div>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
				<button id="btnCancel" data-dismiss="modal" class="btn btn-default">Cancel</button>
				<button type="button" id="btnfilter" name="btnfilter"  class="btn btn-primary">Submit</button>
			</p>
		</div>
	</form>
</div>
</body>
</html>

<script type="text/javascript" src="js/timepicker.js"></script>
<script>
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
 	str = document.getElementById('vendor').value;
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
     document.getElementById('vendor').value=str;
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
	jQuery.ajax({
		url:'search_fectual_barcode.php',
		type:'POST',
		data:{search_val:search_val},
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

				jQuery('#inv_item').val('new_inv_item').trigger('change');
				jQuery('#inv_item1').val(data[0]).trigger('change');
				}
			}else{

				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				jQuery('#inv_item').val('').trigger('change');
				jQuery('#ni_barcode').val('');
				jQuery('#inv_item1').val('').trigger('change');
				jQuery('#ture_barcode').hide();
				jQuery('#ni_notes').val("");
				jQuery('#imagebox').html('');
				jQuery('#upc_search_image').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}

}
function loadVendor(id,email,phone,name,image)
{
	//console.log('yes');
	//jQuery('#vendor').val(id);
	//jQuery('#vendor').text(name);	
	
	jQuery('#vendor_id').val(id);	
	
	var vendor_already_exist = 0;
	
	jQuery('#vendor option').map(function () {
		if (jQuery(this).val() == id) 
		{
			vendor_already_exist = 1;
		}
	});
	
	if(vendor_already_exist == 0)
	{
		jQuery('#vendor').append(jQuery('<option>', {
				value: id,
				text: name.replace("~~~~", "'", 'g')
			}));
	}
	
	/*jQuery('#item_tbl .frmstyle2 .group').map(function () {
			jQuery(this).append(jQuery('<option>', {
				value: id,
				text: name
			}));
	});*/
	
	var groupOptions = '';
	var unitOptions = '';
	var trtdelements = '';
	var totals_tbl_trtdelements = '';
	var clone_table_addeliment = '';
	
	//jQuery("#vendor").prop("selectedIndex", id);
	jQuery("#vendor").val(id);
	
	jQuery('#terms').empty();
	jQuery('#payment_type').empty();
	jQuery('#delivery_method').empty();
	jQuery('#totals_tbl').remove();

	jQuery.ajax({
				url:'populate_vendor_details_from_popup.php',
				type:'POST',
				data:{q:id},
				dataType: 'json',
				success:function(data){
					if(data.flag)
					{
						//jQuery('#terms').val('');
						//jQuery('#payment_type').val('');
						//jQuery('#delivery_method').val('');
						
						
						jQuery("#vendor_invoice_num").val(data.vendor_invoice_num);
						
						jQuery.each(data.terms, function (i, item) {
							jQuery('#terms').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						
						jQuery.each(data.payment, function (i, item) {
							jQuery('#payment_type').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						jQuery.each(data.delivery, function (i, item) {
							jQuery('#delivery_method').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						jQuery.each(data.group, function (i, item) {
							groupOptions += '<option value="'+item.value+'">'+item.text+'</option>';
						});
						
						jQuery.each(data.units, function (i, item) {
							unitOptions += '<option value="'+item.value+'">'+item.text+'</option>';
						});
						
							
							items[0] = new Array('',0,'','','',0,0,0,0,0,0);
							i = 1;
						
							trtdelements  = '<tr>';
							trtdelements  += '<td style="padding:0px;" colspan="11">';
							trtdelements  += '<input type="button" style="" value="Add New Item" id="addItem" class="btn btn-success btn_table_top">';
							trtdelements  += '<h4 class="widgettitle">Items</h4></td>';
							trtdelements  += '</tr>';
							trtdelements  += '<tr class="title">';
							trtdelements  += '<td>Group</td>';
							trtdelements  += '<td>Item &nbsp;';
							trtdelements  += '<img style="cursor:pointer;position:relative;bottom: -3px;height: 14px; " title="Add New Vendor Item" alt="Add New Item" id="add-item" src="images/add.png">';
							trtdelements  += '</td>';
							trtdelements  += '<td class="itm_inpt_th">Pack Size</td>';
							trtdelements  += '<td class="itm_sel_th">Pack Unit</td>';
							trtdelements  += '<td class="itm_inpt_th">Qty Pack</td>';
							trtdelements  += '<td class="itm_sel_th">Qty Pack Unit</td>';
							trtdelements  += '<td class="itm_inpt_th">Qty</td>';
							trtdelements  += '<td class="itm_inpt_th">Price</td>';
							trtdelements  += '<td class="itm_inpt_th">Tax %</td>';
							trtdelements  += '<td class="itm_inpt_th get_width">Amount</td>';
							trtdelements  += '<td style="width:60px;text-align:center;">Remove</td>';
							trtdelements  += '</tr>';
							
							trtdelements += '<tr class="item_row" id="0">';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 115px;" class="frmstyle2 group">';
							//trtdelements += '<option value="">Select Group</option>';							
							//trtdelements += '<option value="20">Canned Produce</option>';		
							//trtdelements += '<option value="15">Egg</option>';
							trtdelements += groupOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 115px;" name="item[]" class="frmstyle2 item">';
							trtdelements += '<option value="">Select Item</option>';
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" style="width: 50px;" name="pack_size[]" class="itm_inpt pack_size">';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<input type="text" style="width: 50px;" name="qty_in_pack[]" class="itm_inpt qty_in_pack">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<select style="width: 100px;" name="pack_unittype[]" class="itm_sel pack_unittype">';
							/*trtdelements += '<option value="">Select Unit</option>';
							trtdelements += '<option value="new">Add New Unit</option>';
							trtdelements += '<option value="1">GAL</option>';
							trtdelements += '<option value="2">LBS</option>';
							trtdelements += '<option value="3">GROSS</option>';
							trtdelements += '<option value="4">DOZ</option>';*/
							trtdelements += unitOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 100px;" name="qty_pack_unittype[]" class="itm_sel qty_pack_unittype">';
							/*trtdelements += '<option value="">Select Unit</option>';
							trtdelements += '<option value="new">Add New Unit</option>';
							trtdelements += '<option value="1">GAL</option>';
							trtdelements += '<option value="2">LBS</option>';
							trtdelements += '<option value="3">GROSS</option>';
							trtdelements += '<option value="4">DOZ</option>';*/
							trtdelements += unitOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" style="width: 50px;" value="1" name="qty[]" class="itm_inpt qty">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" name="price[]" class="itm_inpt price">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" name="tax_percentage[]" class="itm_inpt tax_percentage">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" style="width: 70px;" value="" class="itm_inpt amt">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align:center; ">';
							trtdelements += '<img class="remove" src="images/Delete.png" style="">';
							trtdelements += '</td>';
							trtdelements += '</tr>';
						
							//jQuery('#item_tbl tbody').append(trtdelements);
							jQuery('#item_tbl tbody').html(trtdelements);
							
							
							
							totals_tbl_trtdelements  = '<table align="right" class="cart_table" style="margin-top: 10px; width: 60%; margin-right: 78.5px;" id="totals_tbl">';
							totals_tbl_trtdelements += '<tbody><tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Subtotal:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" class="itm_inpt" id="subTotal" name="subTotal" style="width: 121.5px ! important;">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '<tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Tax:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" style="width: 121.5px ! important;" class="itm_inpt" id="taxTotal" name="taxTotal">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '<tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Total:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" style="width: 121.5px ! important;" class="itm_inpt" id="total" name="total">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '</tbody></table>';
							
							jQuery( "#item_tbl" ).after( totals_tbl_trtdelements );
							
							
							clone_table_addeliment = clone_table_addeliment + '<table style="display: none;" id="clone_tbl">';
							clone_table_addeliment = clone_table_addeliment +  '<tr><td><select class="frmstyle2 group" style="width: 115px;">';
							clone_table_addeliment = clone_table_addeliment + groupOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';
							clone_table_addeliment = clone_table_addeliment + '<td><select class="frmstyle2 item" name="item[]" style="width: 115px;"><option value="">Select Item</option>';
							clone_table_addeliment = clone_table_addeliment + '</select></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt pack_size" name="pack_size[]" style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt qty_in_pack" name="qty_in_pack[]" style="width: 50px;"/></td>';						
							clone_table_addeliment = clone_table_addeliment + '<td><select class="itm_sel pack_unittype" name="pack_unittype[]" style="width: 100px;">';
							clone_table_addeliment = clone_table_addeliment + unitOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td><select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" style="width: 100px;">';
							clone_table_addeliment = clone_table_addeliment + unitOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt qty" name="qty[]" value="1" style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt price" name="price[]" readonly style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt tax_percentage" name="tax_percentage[]" readonly /></td>';							
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt amt"  readonly style="width: 70px;"/></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align:center; "><img style="" src="images/Delete.png" class="remove" /></td>';	
							clone_table_addeliment = clone_table_addeliment + '</tr></table>'	
							jQuery( "#clone_table_div").html(clone_table_addeliment);
					}
				}
			});
	
		
	jQuery('#status').prop("disabled", false); 
	jQuery('#po').prop("disabled", false); 
	jQuery('#comments').prop("disabled", false); 
	jQuery('#terms').prop("disabled", false); 
	jQuery('#payment_type').prop("disabled", false); 
	jQuery('#delivery_method').prop("disabled", false); 
	jQuery('#add-item').show();
	
	jQuery('#filter_modal').modal('toggle');

	//jQuery('#order_frm').submit();
}
jQuery(document).ready(function(){

jQuery('.add-on A').live('click',function(){
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

	jQuery( "#searchdate" ).datepicker();
	jQuery( "#order_datetime" ).datepicker({dateFormat: 'yy-mm-dd'});

	jQuery('#open_filter_modal').live('click',function(){
	//jQuery('#filter_modal').modal('hide');
	 jQuery("#modalcontent").html('');
	 jQuery('#filterformmodal').modal('show');
	})



	jQuery('#btnfilter').live('click',function(){

		if( jQuery('#filtersvtype').val() == null && jQuery('#filtersitype').val() == null &&  jQuery('#filtersname').val().length<4)
		{
			jAlert('Please enter More than 3 Characters!','Alert Dialog');
			return false;
		}

		else
		{
			jQuery.ajax({
				url:'vendor_search_by_filter.php',
				type:'POST',
				data:jQuery("#filterform").serialize(),
				success:function(data){
					//jQuery('#modalcontent').html(data);
					if(data){
						jQuery("#modalcontent").html(data);
						jQuery('#filterformmodal').modal('hide');
					}else{

					}
				}
			});
		}

	});


});

jQuery('.same_as_orderd').click(function () {
	
	
	jQuery('#sub_btn').prop('disabled', false);	
	jQuery('#item_tbl tr').each(function () {
		var id = jQuery(this).attr('id');
		if (id != null && id != 'undefined') {
			//console.log('id : '+id);
			if (jQuery('#' + id).hasClass('oomt')) {
				console.log('Hello Test : '+jQuery('.' + id + '_ord').find('.qty').val());	
				console.log('--- '+jQuery('.' + id + '_ship').find('.rec_qty').val());
				jQuery('.' + id + '_ship' ).find('.rec_qty').val(jQuery('.' + id + '_ord').find('.qty').val());
				jQuery('.' + id + '_ship' ).find('.r_amount').val(jQuery('.' + id + '_ord').find('.amt').val());
				
				if(!jQuery('.'+id+'_ship').find('.check').attr('checked')){									
					jQuery(jQuery('.'+id+'_ship').find('.check')).click();
				}
				
				jQuery('#subTotal1').val(jQuery('#ordSubTotal1').val());
				jQuery('#taxTotal1').val(jQuery('#ordTaxTotal1').val());
				jQuery('#total1').val(jQuery('#ordTotal1').val());
				
				jQuery('#sub_total_vari1').val('$0.00');
				jQuery('#tax_vari1').val('$0.00');
				jQuery('#total_vari1').val('$0.00');
				
				/* jQuery('#' + id ).find('.rec_pack_size').val(jQuery('.' + id + '_ship').find('.ship_pack_size').val());
				jQuery('#' + id ).find('.rec_pack_unittype').val(jQuery('.' + id + '_ship').find('.ship_pack_unittype').val());
				jQuery('#' + id ).find('.r_qtypk').val(jQuery('.' + id + '_ship').find('.ship_qtypk').val());
				jQuery('#' + id ).find('.rec_qty_pack_unittype').val(jQuery('#' + id + '_ship').find('.ship_qty_pack_unittype').val());
				
				jQuery('#' + id ).find('.rec_price').val(jQuery('.' + id + '_ship').find('.ship_price').val());
				jQuery('#' + id ).find('.rec_tax_percentage').val(jQuery('.' + id + '_ship').find('.ship_tax_percentage').val());
				jQuery('#' + id ).find('.rec_qty_in_pack').val(jQuery('.' + id + '_ship').find('.ship_qtypk').val());
				jQuery('#' + id ).find('.r_amount').val(jQuery('.' + id + '_ship').find('.ship_amount').val());
				jQuery('#' + id ).find('.rec_amt').val(jQuery('.' + id + '_ship').find('.ship_amount').val());
				if(!jQuery('#'+id+'_rec').find('.check').attr('checked')){
					jQuery(jQuery('#'+id+'_rec').find('.check')).click();
				} */

			}
		}
	});
	
	jQuery('.rec_qty').prop('disabled', true);	
	jQuery('#hid_same_as_orderd').val('yes');

});
function leaveAlert() {
    jConfirm('Are you sure you want to leave without saving?','Confirm Dialog',function(r){
        if(r){
			<?php if(isset($_REQUEST['po'])){ ?>
				document.location = 'purchase_orders.php';
			<?php }elseif(isset($_REQUEST['intake'])){ ?>
				document.location = 'receiving_intake.php';
			<?php }else{ ?>
				document.location = 'buying.php';
			<?php } ?>
        }
    });
}

jQuery('.qty_on_hand_button').click(function () {
	
	jQuery('#sub_btn').prop('disabled', false);	
	jQuery('#item_tbl tr').each(function () {
		var id = jQuery(this).attr('id');
		if (id != null && id != 'undefined') {
			if (jQuery('#' + id).hasClass('oomt')) {
				jQuery('.' + id + '_ord').find('.qty').val(jQuery('.' + id + '_ord').find('.qty_on_hand').val());
				jQuery('.' + id + '_ship' ).find('.rec_qty').val(jQuery('.' + id + '_ord').find('.qty_on_hand').val());
				
				
				jQuery('.' + id + '_ord' ).find('.amt').val(jQuery('.' + id + '_ord' ).find('.qty').val() * jQuery('.' + id + '_ord' ).find('.price').val().replace('$',''));
				jQuery('.' + id + '_ship' ).find('.r_amount').val(jQuery('.' + id + '_ship' ).find('.rec_qty').val() * jQuery('.' + id + '_ship' ).find('.rec_price').val().replace('$',''));
				
				var ord_sub = parseInt(jQuery('.amt').val()) + parseInt(jQuery('.' + id + '_ord' ).find('.amt').val());
				var ord_tax = parseInt(jQuery('.rec_tax_percentage').val()) + parseInt(jQuery('.' + id + '_ord' ).find('.rec_tax_percentage').val());
				console.log('ord : '+ord_tax);
				jQuery('#ordSubTotal1').val('$'+ord_sub.toFixed(2));
				jQuery('#ordTotal1').val('$'+ord_sub.toFixed(2));
				
				jQuery('#subTotal1').val(jQuery('#ordSubTotal1').val());
				jQuery('#taxTotal1').val(jQuery('#ordTaxTotal1').val());
				jQuery('#total1').val(jQuery('#ordTotal1').val());
				
				jQuery('#sub_total_vari1').val('$0.00');
				jQuery('#tax_vari1').val('$0.00');
				jQuery('#total_vari1').val('$0.00');
				
				jQuery('.' + id + '_ord' ).find('.amt').val(jQuery('.' + id + '_ord' ).find('.amt').val());
				jQuery('.' + id + '_ord' ).find('.r_amount').val(jQuery('.' + id + '_ord' ).find('.r_amount').val());
				
				if(!jQuery('.'+id+'_ship').find('.check').attr('checked')){									
					jQuery(jQuery('.'+id+'_ship').find('.check')).click();
				}
			}
		}
	});
	
	jQuery('.rec_qty').prop('disabled', true);	
	jQuery('#hid_same_as_orderd').val('yes');

});
</script>
<?php
	}else{
?>
	<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>

<style type="text/css">
table.table-infinite input[type="text"], table.table-infinite select {
    width: 98% !important;
}
.maincontentinner  select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-top-left-radius:0;
  border-top-right-radius:0;
  color:#555555;
  display:inline-block;
  font-size:12px;
  margin-bottom:0px;
  /*padding:0px;*/
  padding:4px 4px!important;
  vertical-align:middle;
  width:98%;
}
.maincontentinner select {
  height:30px;
  line-height:30px;
}


.ui-datepicker-month{
	width:70px;
	margin-right:5px;
	 
}
.ui-datepicker-year{
		width:70px
}
.ui-datepicker-calendar td {
padding: 1px;
background: #fff;
font-size: 11px;
}

.totals {
  background-color:#FFFFFF;
 /* border:0 solid #FFFFFF;*/
  font-size:10pt;
  width:100%;
  border-bottom-color:#DDDDDD;
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-bottom-style:solid;
  border-collapse:separate;
  border-right-color:#DDDDDD;
  border-right-style:solid;
  border-width:1px 1px 1px 0;
}
table.totals thead {
  cursor:pointer;
}
.title3 {
  /*background-image:url(http://www.managepoint.mobi/panel/images/menu_bg.jpg);*/
  color:white;
  cursor:default;
  font-size:15px;
  font-weight:normal;
  height:25px;
  padding:5px 0;
  text-align:center;
}
table.totals td {
 /* height:25px;
  padding:10px 0;
  text-indent:5px;
  vertical-align:middle;*/
  border-top-color:#DDDDDD;
  border-top-style:solid;
  border-top-width:1px;
  line-height:20px;
  padding:8px;
  text-align:left;
  vertical-align:top;
   border-left-color:#DDDDDD;
  border-left-style:solid;
  border-left-width:1px;
}
#totals_tbl tr td { float:right; }
.cart_table .cart_sub input, .cart_table .cart_sub input:focus{ padding:4px !important }
.itm_inpt{
text-align:right;}
a.btn{color:#fff !important;}
#item_results li{ list-style-type: none !important; }


/*.modal{ width:845px; left: 43%; }*/
.modal .modal-body {
    max-height: 420px;
    overflow-y: auto;
}
.ui-state-highlight{
	background: none repeat scroll 0 0 #FFFFFF !important;
	color:#5B5B5B !important;
	}
.ui-state-active{
	 background: none repeat scroll 0 0 #0866c6 !important;
     color: #fff !important;
}
.btn-received, .btn-success:link{
	 background: none repeat scroll 0 0 #86d628;
    border-color: #6db814;
}
.btn-success.disabled, .btn-success[disabled] {
    background-color: #d3d3d3 !important;
}
.table th, .table td {
vertical-align:middle;
}


.line3 {
		background-color: #808080;
		color:#000000 !important;
		}
.tab-primary.ui-tabs .ui-tabs-nav li a{
	background-color: inherit;
	}
.cart_table span {
    float: none !important;
	}
.cart_table td {
	text-align:right !important;
}
.cart_table .cart_sub input{
	padding:4px !important;
}
.dataTables_filter input{
	width:auto !important;
}
/*.dataTables_length,.dataTables_filter{
	display:none;
}*/
#totals_tbl .active {
    display: table-row !important;
}
.jGrowl-message { color: #FFFFFF !important; }
.ui-state-disabled{ background-color:gray !important; text-align:center !important; }
</style>
<!--<script type="text/javascript" src="js/chosen.jquery.min.js"></script>-->
<script type="text/javascript">
jQuery(document).ready(function(){
<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']!=""){ ?>
		var msg = '<?php echo $_REQUEST['msg']; ?>';
		jAlert(msg,'Alert Dialog');
<?php } ?>
jQuery("#searchdate").datepicker({ changeMonth: true,
                changeYear: true,
				dateFormat:"yy-mm-dd",
                yearRange: "-100:+0"});		
				
}); // end document.ready
jQuery('#group').live('change',function(){	
	var group_id  = jQuery(this).val();
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
		getinventory(jQuery(this).val());
	}
	
});	
jQuery('#inv_item').live('change',function(){
	
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
		jQuery('#ni_notes').val('');
		jQuery('#ni_barcode').val('');
		jQuery('#ni_manufacture').val('');
		jQuery('#ni_brand').val('');
		jQuery('#ni_model').val('');
		jQuery('#vendor_internal_number').val('');
		jQuery('#imagebox').html('');
		jQuery('#digital_image_name').val('');
		jQuery('#pack_size').val('');
		jQuery('#qty_in_pack').val('');
		jQuery('#ni_taxable').val('');
		jQuery('#price').val('');
		jQuery('#purchased_from_vendor').val('');
	}else{
		jQuery('#drop_span').show();
		jQuery('#new_span').hide();
		jQuery('#inv_item_type').val('old');
		jQuery('#inv_item1').val('');
		jQuery('#new_inv_div,#new_inv_div1').hide();		
	}
	
	
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
function getinventory(group){		
    states = jQuery.Deferred();
    jQuery.ajax({
        url:'get_inventory_items.php',
        data: { group: group},
        dataType: 'jsonp',
        jsonpCallback: 'returnItem'
    });
}
function returnItem(data){
	
    var group = jQuery('#inv_item');
    group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
	group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
    for(var i=0;i<data.length;i++){
        group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
    }
	
    states.resolve();
}
jQuery('#inv_item').live('change',function(){
	if(jQuery('#inv_item').val()!="new_inv_item" && jQuery('#inv_item').val()!=""){
	jQuery('#name').val(jQuery("#inv_item :selected").text());
	jQuery('#pack_unit_type').val(jQuery("#inv_item :selected").attr('rel'));
	jQuery('#barcode').val(jQuery("#inv_item :selected").attr('barcode'));
	}else{
	jQuery('#name').val('');
	jQuery('#pack_unit_type').val('');
	jQuery('#barcode').val('');
	}
	
});
jQuery('#inv_item1').live('change',function(){
if(jQuery('#inv_item1').val()!=""){
	jQuery('#name').val(jQuery('#inv_item1').val());
}else{
	jQuery('#name').val('');
	}

});	


	jQuery('#order_frm').live('change',function(){
		console.log('CHANGEEEEEEEEEEEEE');
		jQuery('#sub_btn').prop('disabled',false);
	});
function validate()
{
	if(jQuery('#status').val()==""){
	jAlert('Please Select status!','Alert Dialog');
	return false;
	}else if(jQuery('#d_market').val()==""){
	jAlert('Please Select Market!','Alert Dialog');
	return false;
	}else if(jQuery('#group').val()==""){
	jAlert('Please Select Group!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item').val()=="" && jQuery('#inv_item_type').val()!='new'){
	jAlert('Please Select Inventory Item!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item1').val()=="" && jQuery('#inv_item_type').val()=='new'){
	jAlert('Please Enter Inventory Item!','Alert Dialog');
	return false;		
	}else if(jQuery('#name').val()==""){
	jAlert('Please Enter Name!','Alert Dialog');
	return false;
	}else if(jQuery('#pack_size').val()==""){
	jAlert('Please Enter Pack Size!','Alert Dialog');
	return false;
	}else if(jQuery('#pack_unit_type').val()==""){
	jAlert('Please Select Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack').val()==""){
	jAlert('Please Enter Quantity In Pack!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack_unit_type').val()==""){
	jAlert('Please Select Quantity In Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#ni_taxable').val()==""){
	jAlert('Please Select Taxable!','Alert Dialog');
	return false;
	}else if(jQuery('#price').val()==""){
	jAlert('Please Enter Price!','Alert Dialog');
	return false;
	}else{
	jQuery('#edit_from input,#edit_from select').attr('disabled',false);
		jQuery.ajax({
			url:'ajax_Add_vendor_items.php',
			type:'POST',
			data:jQuery("#edit_from").serialize(),
			success:function(data){
				jAlert(data,'Alert Dialog');
				jQuery('#myModal').modal('hide');
				return false;
			}
		});
	return false;
	}
}
function get_group(market,group){

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
	
	//var charCode = (evt.which) ? evt.which : event.keyCode;
    //return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
</script>
</head>

<body>

<div class="mainwrapper">
    
    <?php include_once 'require/top.php';?>

    
    <div class="leftpanel">
        
        <?php include_once 'require/left_nav.php';?>
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>StorePoint <span class="separator"></span></li><li>Purchases</li>
            <li class="right">
                    <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
        
        <div class="pageheader" id="date_search_box">
        
        	<?php if(!isset($_GET['flag'])){?>
            <!--<form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pull-right" style=" margin-top: 14px;">
                <a href="#" class="btn btn-success btn-large new_purchase pull-right" id="new_purchase">Add</a>
            <form action="results.html" method="post" class="pull-right" style="margin-right:10px;">
				<input type="text" onChange="date_search(this.value)" name="searchdate" value="<?php echo $_REQUEST['date']; ?>" id="searchdate" placeholder="<?php echo $_SESSION['Select Date to Search'];?>" style="font-size: 12px;padding: 10px 10px !important;width: 220px;"/>
                <input type="text" id="search_txt" onKeyUp="text_search(this.value)" value="<?php echo $_REQUEST['text_s']; ?>" name="keyword" placeholder="<?php echo $_SESSION['To search type and hit enter'];?>..." style="background: url('./images/icons/search.png') no-repeat scroll 215px 10px #FFFFFF;font-size: 12px;padding: 10px 10px !important;width: 220px;" /> <!--height:30px;width:210px;-->
            </form>         
            </div>
			
                 <?php }else{?>            
            <div class="pull-right" style="margin-top: 14px;">
				 <a href="purchase_orders.php" class="btn btn-primary btn-large">Back</a>
				 <!--<a href="#" class="btn btn-success btn-large new_purchase" id="new_purchase">Add</a>-->
				 
                 <?php if($status != "Completed"){?>
                 <a href="#" class="" id="submit_order" title="Submit Order">
				<button  id="sub_btn" class="btn btn-success btn-large" <?php if($_REQUEST['reorder']!='Yes') { ?> disabled="disabled" <?php } ?> >Submit</button></a>
                <?php }else{ ?>
                <a href="purchase_orders.php?flag&purchase_id=<?=$_REQUEST['purchase_id']; ?>&reorder=Yes" class="" title="Reorder">
				<button  id="Reorder" class="btn btn-success btn-large">Reorder</button></a>
                <?php } ?>
            </div>
			<?php } ?>
                        
        
            
            <div class="pageicon"><span class="iconfa-tags"></span></div>
            <div class="pagetitle">
                <h5>Display All Back Office Purchases Information</h5>
                <h1>Purchases</h1>
            </div>
        </div><!--pageheader-->					
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                <?php if(!isset($_GET['flag'])){ //if(!isset($_GET['purchase_id'])){?>
                    <div class="span12">
                        <div class="tabbedwidget  tab-primary">
                            <ul style="height:38px;">
                                <li style="width:170px;text-align:center;"><a class="capital_word" href="#active" >Ordered</a></li>
                                <li style="width:170px;text-align:center;display:none;"><a class="capital_word" href="#shipped" >Shipped</a></li>
								<li style="width:170px;text-align:center;"><a class="capital_word" href="#shopping" >Shopping</a></li>
                                <li style="width:170px;text-align:center;"><a class="capital_word" href="#completed" >Completed</a></li>
                            </ul>
							
					<!-- 	<div class="clearfix">
						<h4 class="widgettitle">Purchase Orders</h4>			
						</div> -->
						 <div  id="active">
							<table class="table sortable table-bordered table-infinite" id="vnd_tbl">
								<thead>
									<tr class="title">                                 
                                        <th class="head1" style="width: 5%;text-align: center;">Image</th>
                                        <th class="head0" style="width: 3%;text-align: center;">Status</th>
                                        <th class="head1" style="width: 9%;">Date & Time</th>
                                        <th class="head0" style="width: 8%;">Invoice #</th>
                                        <th class="head1" style="width: 5%;">PO #</th>
                                        <th class="head0" style="width: 37%;">Vendor</th>										
                                        <th class="head0" style="width: 13%;">CONTACT</th>
                                        <th class="head1" style="width: 7%; text-align: right;">Qty</th>
                                        <th class="head0" style="width: 8%; text-align: right;">Total</th>
                                        <th class="head1" style="width: 5%;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i=0;
										while ($row1 = mysql_fetch_array($result1)) {
											$i++;
											$class = '';
											if ($row1["id"] == $_GET["purchase_id"]) {
												$class = "class='line3'";
											}
											?>
											<tr <?=$class;?> style="cursor:pointer;">
												<td style="text-align:center;"><img src="<?=APIIMAGE.'images/'.$row1['image'];?>" onerror="this.src='images/default - location.png'" style="height:50px; width:50px;"  ></td>
                                                <td style="text-align:center;" onClick="window.location.href='backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>'">
													<?php
													$status_left = $row1['status'];
													$date = $row1["shopping_datetime"];
													if ($status_left == 'Cancelled') {
														$status_img = 'cancelled.png';
														$status_msg = 'Cancelled';
														$date = $row1["order_datetime"];
													} else if ($status_left == 'Shopping') {
														$status_img = 'shopping.png';
														$status_msg = 'Shopping';
														$date = $row1["shopping_datetime"];
													} else if ($status_left == 'Ordered' && $row1["order_datetime"]!="") {
														$status_img = 'Ordered - 16.png';
														$status_msg = 'Ordered';
														$date = $row1["order_datetime"];
													} else if ($status_left == 'Shipped') {
														$status_img = 'Shipped - 16.png';
														$status_msg = 'Shipped';
														$date = $row1["order_datetime"];
													}
													
													?>
													<img src="images/<?=$status_img?>" alt="<?=$status_msg?>" title="<?=$status_msg?>" />
												</td>
                                                <td><?= $date; ?></td>
                                                <td style="text-align:center;"><?=$row1['vendor_invoice_num'];?></td>
                                                <td style="text-align:center;"><?=$row1['po'];?></td>
												<td>
												<?php 
												echo '<strong>'.$row1["name"].'</strong><br>'; 
												echo $row1["representative"].'<br>';
												echo $row1["city"].', '.$row1["v_state"].'<br>';
												?></td>												
												<td><?=$row1["phone"].'<br>'.$row1["v_email"]; ?></td>
												<td style="text-align: right;"><?=number_format($row1["qty"],2,'.',','); ?></td>
												<td style="text-align: right;"><?=$c_sym.' '.$row1["total"]; ?></td>
											  <td class="center"><a href="backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>" title="Edit"><span class="icon-pencil"></span></a></td>
											</tr>
										<?php }
									?>
								</tbody>
								<!--<tfoot><tr class="tblend"><td colspan="5"></td></tr></tfoot>-->
							</table>
							</div><!--active-->
                            
                            
                            <div  id="shipped">
							<table class="table sortable table-bordered table-infinite" id="vnd_tbl_shipped">
								<thead>
									<tr class="title">                                 
                                        <th class="head1" style="width: 5%;text-align: center;">Image</th>
                                        <th class="head0" style="width: 3%;text-align: center;">Status</th>
                                        <th class="head1" style="width: 9%;">Date & Time</th>
                                        <th class="head0" style="width: 8%;">Invoice #</th>
                                        <th class="head1" style="width: 5%;">PO #</th>
                                        <th class="head0" style="width: 37%;">Vendor</th>										
                                        <th class="head0" style="width: 13%;">CONTACT</th>
                                        <th class="head1" style="width: 7%; text-align: right;">Qty</th>
                                        <th class="head0" style="width: 8%; text-align: right;">Total</th>
                                        <th class="head1" style="width: 5%;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
									
										$i=0;
										while ($row1 = mysql_fetch_array($result_shipped)) {
											$i++;
											$class = '';
											if ($row1["id"] == $_GET["purchase_id"]) {
												$class = "class='line3'";
											}
											?>
											<tr <?=$class;?> style="cursor:pointer;">
												<td style="text-align:center;"><img src="<?=APIIMAGE.'images/'.$row1['image'];?>" onerror="this.src='images/default - location.png'" style="height:50px; width:50px;"  ></td>
                                                <td style="text-align:center;" onClick="window.location.href='backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>'">
													<?php
													$status_left = $row1['status'];
													$date = $row1["shopping_datetime"];
													if ($status_left == 'Cancelled') {
														$status_img = 'cancelled.png';
														$status_msg = 'Cancelled';
														$date = $row1["order_datetime"];
													} else if ($status_left == 'Shopping') {
														$status_img = 'shopping.png';
														$status_msg = 'Shopping';
														$date = $row1["shopping_datetime"];
													} else if ($status_left == 'Ordered' && $row1["order_datetime"]!="") {
														$status_img = 'Ordered - 16.png';
														$status_msg = 'Ordered';
														$date = $row1["order_datetime"];
													} else if ($status_left == 'Shipped') {
														$status_img = 'Shipped - 16.png';
														$status_msg = 'Shipped';
														$date = $row1["order_datetime"];
													}
													
													?>
													<img src="images/<?=$status_img?>" alt="<?=$status_msg?>" title="<?=$status_msg?>" />
												</td>
                                                <td><?=GetLocationTimeFromServer($_SESSION['loc'],$date); ?></td>
                                                <td style="text-align:center;"><?=$row1['vendor_invoice_num'];?></td>
                                                <td style="text-align:center;"><?=$row1['po'];?></td>
												<td>
												<?php 
												echo '<strong>'.$row1["name"].'</strong><br>'; 
												echo $row1["representative"].'<br>';
												echo $row1["city"].', '.$row1["v_state"].'<br>';
												?></td>												
												<td><?=$row1["phone"].'<br>'.$row1["v_email"]; ?></td>
												<td style="text-align: right;"><?=number_format($row1["qty"],2,'.',','); ?></td>
												<td style="text-align: right;"><?=$c_sym.' '.$row1["total"]; ?></td>
											  <td class="center"><a href="backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>" title="Edit"><span class="icon-pencil"></span></a></td>
											</tr>
										<?php }
									 ?>
								</tbody>
								<!--<tfoot><tr class="tblend"><td colspan="5"></td></tr></tfoot>-->
							</table>
							</div><!--shipped-->
                            
							<div  id="shopping">
							<table class="table sortable table-bordered table-infinite" id="vnd_tbl_shopping">
								<thead>
									<tr class="title">                                 
										<th class="head1" style="width: 5%;text-align: center;">Image</th>
                                        <th class="head0" style="width: 3%;text-align: center;">Status</th>
                                        <th class="head1" style="width: 9%;">Date & Time</th>
                                        <th class="head0" style="width: 8%;">Invoice #</th>
                                        <th class="head1" style="width: 5%;">PO #</th>
                                        <th class="head0" style="width: 37%;">Vendor</th>										
                                        <th class="head0" style="width: 13%;">CONTACT</th>
                                        <th class="head1" style="width: 7%; text-align: right;">Qty</th>
                                        <th class="head0" style="width: 8%; text-align: right;">Total</th>
                                        <th class="head1" style="width: 5%;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
										$i=0;
										while ($row1 = mysql_fetch_array($resultShopping)) {
											$i++;
											$class = '';
											if ($row1["id"] == $_GET["purchase_id"]) {
												$class = "class='line3'";
											}
											?>
											<tr <?=$class;?> style="cursor:pointer;">
												<td style="text-align:center;"><img src="<?=APIIMAGE.'images/'.$row1['image'];?>" onerror="this.src='images/default - location.png'" style="height:50px; width:50px;"  ></td>                                                                                                
												<td style="text-align:center;" onClick="window.location.href='backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>'">
													<?php
													$status_left = $row1['status'];
													
														$status_img = 'shopping.png';
														$status_msg = 'Shopping';
													
													?>
													<img src="images/<?=$status_img?>" alt="<?=$status_msg?>" title="<?=$status_msg?>" />
												</td>
												<td><?=GetLocationTimeFromServer($_SESSION['loc'],$row1["shopping_datetime"]); ?></td>
                                                <td style="text-align:center;"><?=$row1['vendor_invoice_num'];?></td>
                                                <td style="text-align:center;"><?=$row1['po'];?></td>
												<td>
											  <?php 
												echo '<strong>'.$row1["name"].'</strong><br>'; 
												echo $row1["representative"].'<br>';
												echo $row1["city"].', '.$row1["v_state"].'<br>';
												?></td>	
												<td><?=$row1["phone"].'<br>'.$row1["v_email"]; ?></td>
												<td style="text-align: right;"><?=number_format($row1["qty"],2,'.',','); ?></td>
												<td style="text-align: right;"><?=$c_sym.' '.$row1["total"]; ?></td>
											  <td class="center"><a href="backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>" title="Edit"><span class="icon-pencil"></span></a></td>
											</tr>
										<?php }
									 ?>
								</tbody>
							</table>
							</div> <!--end shopping-->
							<div  id="completed">
							<table class="table sortable table-bordered table-infinite" id="vnd_tbl_completed">
								<thead>
									<tr class="title">                                 
										<th class="head1" style="width: 5%;text-align: center;">Image</th>
                                        <th class="head0" style="width: 3%;text-align: center;">Status</th>
                                        <th class="head1" style="width: 9%;">Date & Time</th>
                                        <th class="head0" style="width: 8%;">Invoice #</th>
                                        <th class="head1" style="width: 5%;">PO #</th>
                                        <th class="head0" style="width: 37%;">Vendor</th>										
                                        <th class="head0" style="width: 13%;">CONTACT</th>
                                        <th class="head1" style="width: 7%; text-align: right;">Qty</th>
                                        <th class="head0" style="width: 8%; text-align: right;">Total</th>
                                        <th class="head1" style="width: 5%;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
										$i=0;
										while ($row1 = mysql_fetch_array($resultCompleted)) {
											$i++;
											$class = '';
											if ($row1["id"] == $_GET["purchase_id"]) {
												$class = "class='line3'";
											}
											?>
											<tr <?=$class;?> style="cursor:pointer;">
												<td style="text-align:center;"><img src="<?=APIIMAGE.'images/'.$row1['image'];?>" onerror="this.src='images/default - location.png'" style="height:50px; width:50px;"  ></td>                                                                                                
												<td style="text-align:center;" onClick="window.location.href='backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>'">
													<?php
													$status_left = $row1['status'];
													if ($status_left == 'Cancelled') {
														$status_img = 'Cancelled & Terminated - 16.png';
														$status_msg = 'Cancelled';
													} elseif ($status_left == 'Completed') {
														$status_img = 'Active, Corrected, Delivered - 16.png';
														$status_msg = 'Completed';
													}
													?>
													<img src="images/<?=$status_img?>" alt="<?=$status_msg?>" title="<?=$status_msg?>" />
												</td>
												<td><?=GetLocationTimeFromServer($_SESSION['loc'],$row1["shopping_datetime"]); ?></td>
                                                <td style="text-align:center;"><?=$row1['vendor_invoice_num'];?></td>
                                                <td style="text-align:center;"><?=$row1['po'];?></td>
												<td>
											  <?php 
												echo '<strong>'.$row1["name"].'</strong><br>'; 
												echo $row1["representative"].'<br>';
												echo $row1["city"].', '.$row1["v_state"].'<br>';
												?></td>	
												<td><?=$row1["phone"].'<br>'.$row1["v_email"]; ?></td>
												<td style="text-align: right;"><?=number_format($row1["qty"],2,'.',','); ?></td>
												<td style="text-align: right;"><?=$c_sym.' '.$row1["total"]; ?></td>
											  <td class="center"><a href="backoffice_purchases.php?flag&purchase_id=<?=$row1["id"];?>" title="Edit"><span class="icon-pencil"></span></a></td>
											</tr>
										<?php }
									 ?>
								</tbody>
								<!--<tfoot><tr class="tblend"><td colspan="5"></td></tr></tfoot>-->
							</table>
							</div><!--completed-->							
						</div><!--tabbedwidget-->
					</div>
                                        <?php } ?>
					<!--<div class="span8">-->
                      <div class="span12">
						<?php if(isset($_GET["flag"])) { ?>
						<div class="clearfix">
						<h4 class="widgettitle">Details</h4>			
						</div>
						<form id="order_frm" method="post">
							<table class="topTbl table table-bordered table-infinite">
								<tr>
									<td style="width:15%;" >Vendor:<span style="color:#FF0000;">* </span></td>
									<td style="width:35%;">
										<?php if ($_GET['purchase_id'] != '') { ?>
											<input type="text" value="<?=$vendor_name?>" disabled="disabled" style="" class="frmstyle1" size="12"/>
											<input type="hidden" value="<?=$vendor_id?>" name="vendor" id="vendor" size="12"/>
										<?php } else { ?>
											<select id="vendor" name="vendor" class="frmstyle1" style="width:99.8% !important">
												<option value=""> - - - Select Vendor - - - </option>
                                                <option value="find"> - - - Find Vendor - - - </option>
												<?php while($row6 = mysql_fetch_array($result6)){?>
													<option value="<?=$row6['id'];?>" <?php if($vendor_id == $row6['id']){echo "selected='selected'"; }?>><?=$row6['name'];?></option>
												<?php } ?>
											</select>
                                           <input type="hidden" value="<?php if(isset($_POST['vendor'])) echo $_POST['vendor']; else echo $_SESSION['StorePointVendorID']; ?>" name="vendor_id" id="vendor_id" > 
										<?php }?>
									</td>
                                    <?php  if($status == "Shipped"){ ?>
                                    <td style="width:15%;" >Shipped Date:<span style="color:#FF0000;">* </span></td>
                                    
									<td style="width:35%;" >
										<input type="text" name="shopping_datetime" value="<?=date('Y-m-d',strtotime($lastchange_datetime));?>" size="12" id="shopping_datetime" class="frmstyle1" <?=$disabled;?>>
									</td>
                                    <?php } else { ?>
									<td style="width:15%;" >Shopping Date:<span style="color:#FF0000;">* </span></td>
                                    
									<td style="width:35%;" >
										<input type="text" name="shopping_datetime" value="<?= date('Y-m-d',strtotime($shopping_datetime));?>" size="12" id="shopping_datetime" class="frmstyle1" <?=$disabled;?>>
									</td>
                                    <?php } ?>
								</tr>
								<tr>
									<td>Vendor Invoice:
                                    </td>
                                    <td><input type="text" name="vendor_invoice_num" id="vendor_invoice_num" value="<?=$vendor_invoice_num;?>" size="12" class="frmstyle1" readonly></td>
									<td >Terms:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="terms" id="terms" class="frmstyle1 terms" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Select Terms - - - </option>											
											<?php if($vendor_id != ''){
												while ($row7 = mysql_fetch_array($result7)){ ?>
													<option value='<?=$row7['id']?>' <?php if($terms == $row7['id']){echo "selected='selected'"; }?>><?=$row7['terms']?></option>
												<?php }
											} ?>
										</select>
                                        <input type="hidden" name="terms_new" id="terms_new" value="no" >
										
									</td>
								</tr>
								<tr>
                                	<td >Status:<span style="color:#FF0000;">* </span></td>
									<td><?php if($status == "Completed"){ ?>
                                        <select id="status" name="status" class="frmstyle1" disabled style="width:99.8% !important">                                        	
                                            <option value="Completed">Completed</option>
                                        </select>
                                        <?php }else if($status == "Shipped"){ ?>
                                        <select id="status" name="status" class="frmstyle1" style="width:99.8% !important">
                                        	<option value="Shipped">Shipped</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                        <?php }else{ ?>
										<select id="status" name="status" class="frmstyle1" <?=$disabled1;?> style="width:99.8% !important">
											<option value="Shopping" <?php if ($status == "Shopping"){ echo "selected='selected'";} ?>>Shopping</option>
                                            <option value="Ordered" <?php if ($status == "Ordered"){echo "selected='selected'";} ?>>Ordered</option>
											<?php
											if($purchase_id != '' && $_GET['reorder']!='Yes'){?>
                                            	
												<option value="Cancelled" <?php if ($status == "Cancelled"){echo "selected='selected'";} ?>>Cancelled</option>
												
                                                
											<?php } ?>
										</select>
                                        <?php } ?>
									</td>
									
									<td>Payment Type:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="payment_type" id="payment_type" class="frmstyle1" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Payment Type - - - </option>											
											<?php
											$types = 0;
											$query777 = mysql_query("SELECT default_payment_type from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_SESSION['loc']."' LIMIT 1");
											if(mysql_num_rows($query777)>0){
												$res777 = mysql_fetch_array($query777);
												if($res777['default_payment_type']!=""){
													$types = $res777['default_payment_type'];	
												}
											}
											$vpayment = mysql_fetch_array(mysql_query("SELECT payment_types from vendors where id ='".$vendor_id."'"));
											if($vpayment['payment_types']!='' && $types == 0){ 
												$types = $vpayment['payment_types'];
											}
											$pay_query = mysql_query("SELECT * from vendors_payment_types where vendors_payments_id in(".$types.") order by code");							
												while ($row_p = mysql_fetch_array($pay_query)){ ?>
													<option value='<?=$row_p['vendors_payments_id']?>' <?php if($payment_type == $row_p['vendors_payments_id']){echo "selected='selected'"; }?>><?=$row_p['code']?></option>
												<?php }
											 ?>
										</select>
										
									</td>
								</tr>
								<tr>
									<td>Internal Purchase Order#:</td>
									<td>
										<input type="text" name="po" id="po" value="<?=$po;?>" size="12" class="frmstyle1" <?=$disabled;?>>
									</td>
									<td>Delivery Method:<span style="color:#FF0000;">* </span></td>
									<td>
                                    	<select name="delivery_method" id="delivery_method" class="frmstyle1" <?=$disabled?> style="width:99.8% !important">
											<option value=''> - - - Select Delivery Method - - - </option>											
											<?php
												$delivery_types = 0;
												$query777 = mysql_query("SELECT default_delivery_type from vendor_locations where  vendor_id = '".$vendor_id."' AND location_id = '".$_SESSION['loc']."' LIMIT 1");
												if(mysql_num_rows($query777)>0){
													$res777 = mysql_fetch_array($query777);
													if($res777['default_delivery_type']!=""){
														$delivery_types = $res777['default_delivery_type'];	
													}
												}											
												$vdel = mysql_fetch_array(mysql_query("SELECT delivery_types from vendors where id ='".$vendor_id."'"));
												if($vdel['delivery_types']!='' && $delivery_types==0){
													$delivery_types = $vdel['delivery_types'];
												}
												$del_query = mysql_query("SELECT * from vendors_delivery_types where vendors_delivery_types_id in(".$delivery_types.") order by code");	
												while ($row_d = mysql_fetch_array($del_query)){ ?>
													<option value='<?=$row_d['vendors_delivery_types_id']?>' <?php if($delivery_method == $row_d['vendors_delivery_types_id']){echo "selected='selected'"; }?>><?=$row_d['code']?></option>
												<?php }
											 ?>
										</select>
                                    </td>
								</tr>
                                 <tr>
                                 	<td rowspan="2">Comments:</td>
									<td rowspan="2">
										<textarea name="comments" id="comments" style="resize: none;" class="frmstyle1" <?=$disabled;?>><?=$comments?></textarea>
									</td>
                                 
                                	<td>Last Change:</td>
                                    <td><input type="text" name="lastchange_datetime" value="<?php if($lastchange_datetime!="" && $lastchange_datetime!="0000-00-00 00:00:00"){echo GetLocationTimeFromServer($_SESSION['loc'],$lastchange_datetime); } ;?>" size="12" id="lastchange_datetime" disabled="disabled" class="frmstyle1" <?=$disabled;?>></td>
                                  </tr>
                                  <tr>
                                  	<td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                    
							</table>

							<table class="table table-bordered table-infinite" id="item_tbl">
								<tbody>
                                
                                	<?php if($status == "Completed"){ ?>
                                    <tr>
                                        <td colspan="11" style="padding:0px;">
                                          <input class="btn btn-success btn_table_top" type="button" id="addItem" value="Add New Item" <?=$disabled1;?> style="<?=$hide;?>">
                                        <h4 class="widgettitle">Ordered Items / Received Items</h4></td>
                                    </tr>
                                    <tr>
												
												<th class="head1" >Group</th>
												<th class="head0" >Item Description &nbsp;<img src="images/add.png" id="add-item" alt="Add New Item" title="Add New Vendor Item" style="cursor:pointer;position:relative;bottom: -3px;height: 14px;" />													
												</th>
												<th class="head1" >Pack Unit Type</th>
												<th class="head0" >Qty In Pack</th>
												<th class="head0" >Qty In Pack Unit type</th>
												<th class="head1" >Qty In Size</th>
												<th class="head1" >Qty</th>
												<th class="head0" >Price</th>
												<th class="head1" >Tax %</th>
												<th class="head0" >Amount</th>
												<th class="head0" >Received</th>
											</tr>
                                    <?php }else{ ?>
                                    <tr>
                                        <td colspan="11" style="padding:0px;">
                                          <input class="btn btn-success btn_table_top" type="button" id="addItem" value="Add New Item" <?=$disabled1;?> style="<?=$hide;?>">
                                        <h4 class="widgettitle">Items</h4></td>
                                    </tr>
									<tr class="title">
										<td>Group</td>
										<td>Item Description &nbsp;
										<?php /*<img src="images/add.png" id="add-item" alt="Add New Item" title="Add New Vendor Item" style="cursor:pointer;position:relative;bottom: -3px;height: 14px; <?php echo $hide; ?>"/>  */?>
										</td>
										<td class="itm_sel_th">Pack Unit Type</td>
										<td class="itm_inpt_th">Qty In Pack</td>
										<td class="itm_sel_th">Qty In Pack Unit type</td>
										<td class="itm_inpt_th">Qty In Size</td>
										<td class="itm_inpt_th">Qty</td>
										<td class="itm_inpt_th">Price</td>
										<td class="itm_inpt_th">Tax %</td>
										<td class="itm_inpt_th get_width">Amount</td>
										<td style='width:60px;text-align:center;'>Remove</td>
									</tr>
                                    <?php } ?>
									<?php if($_GET['purchase_id'] != ''){
										$k=0;
										$createJsArrays = '';
										$addToJsArray = '';
										$ordr_subtotal = '0.00';
										$ordr_tax_total = '0.00';
										$arr = array();
										while($row3 = mysql_fetch_assoc($result3)){
											$itm_sub = floatval($row3['ordered_quantity'])*floatval($row3['ordered_price']);
											$itm_tax = floatval($row3['ordered_tax_percentage'])*.01*floatval($itm_sub);
											$itm_total = $itm_sub + $itm_tax;
											$ord_subtotal = $ord_subtotal + $itm_sub;
											$ord_tax_total = $ord_tax_total + $itm_tax;
											
											if($row3['received'] == 'yes'){
												$ordr_subtotal = $ordr_subtotal+ ($row3['received_price']*$row3['received_quantity']);
												$ordr_tax_total = $ordr_tax_total + number_format(((($row3['received_price']*$row3['received_tax_percentage']/100))*$row3['received_quantity']),2,'.',',');
											}
											
											$q = mysql_query('SELECT lii.id AS loc_inv_id
												FROM vendor_items vi
												INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
												INNER JOIN location_inventory_items lii ON lii.inv_item_id = vi.inv_item_id
												WHERE vi.vendor_id = "'.$row3['vendor_id'].'" AND ii.inv_group_id="'.$row3['inv_group_id'].'" AND lii.inv_item_id="'.$row3['vi_inv_item_id'].'" AND lii.location_id = "272867"');
											$q = mysql_fetch_array($q);
											
											$qry = "SELECT inventory_item_unittype.unit_type,inventory_item_unittype.id AS unit_id 
													 FROM location_inventory_counts lic 
													 LEFT JOIN employees ON lic.employee_id=employees.id 
													 LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
													 WHERE inv_item_id='".$q['loc_inv_id']."' AND inventory_item_unittype.id != '' GROUP BY unit_type ORDER BY lic.date_counted DESC, lic.time_counted DESC";
											//echo $qry;
											$result_unit2 = mysql_query($qry) or die(mysql_error());
											
											$createJsArrays .= "items[" . $k . "] = new Array('',0,'','','',0,0,0,0,0,0);";
											$addToJsArray .= "items[" . $k . "][0] = parseFloat('" . $row3['inv_item_id'] . "');";//inv_item_id(vendor_items.id)
											$addToJsArray .= "items[" . $k . "][1] = parseFloat('" . $row3['ordered_pack_size'] . "');";//pack size
											$addToJsArray .= "items[" . $k . "][2] = parseFloat('" . $row3['ordered_pack_unittype'] . "');";//pack unit type
											$addToJsArray .= "items[" . $k . "][3] = parseFloat('" . $row3['ordered_qty_in_pack'] . "');";//quantity in pack
											$addToJsArray .= "items[" . $k . "][4] = parseFloat('" . $row3['ordered_qty_in_pack_unittype'] . "');";//qty_in_pack_unittype
											$addToJsArray .= "items[" . $k . "][5] = parseFloat('" . $row3['ordered_quantity'] . "');";//quantity
											$addToJsArray .= "items[" . $k . "][6] = parseFloat('" . $row3['ordered_price'] . "');";//price
											$addToJsArray .= "items[" . $k . "][7] = parseFloat('" . $row3['ordered_tax_percentage'] . "');";//quantity
											$addToJsArray .= "items[" . $k . "][8] = parseFloat('" . $itm_sub . "');";//quantity
											$addToJsArray .= "items[" . $k . "][9] = parseFloat('" . $itm_tax . "');";//quantity
											$addToJsArray .= "items[" . $k . "][10] = parseFloat('" . $itm_total . "');";//quantity
											?>
											<tr id="<?=$k?>" class="item_row">
												<td>
													<select class='frmstyle2 group' <?=$disabled;?> style="width: 115px;">
														<option value="">Select Group</option>
														<?php while($row9 = mysql_fetch_array($result9)){ ?>
															<option value="<?=$row9['id']?>" <?php if($row9['id'] == $row3['inv_group_id']){ echo "selected='selected'"; }?>><?=$row9['group'];?></option>
														<?php }
														mysql_data_seek($result9,0);?>
													</select>
												</td>
												<td>
													<select class='frmstyle2 item' name='item[]' <?=$disabled;?> style="width: 115px;">
														<option value="">Select Item</option>
														<?php foreach($items_by_group[$row3['inv_group_id']] as $val){ ?>
															<option data-loc_inv_id="<?php echo $q['loc_inv_id']; ?>" value="<?=$val['id']?>" <?php if($val['id'] == $row3['inv_item_id']){ echo "selected='selected'"; }?>><?=$val['item'];?></option>
														<?php }?>
													</select>
												</td>
												<td>
													<select class="itm_sel pack_unittype" name="pack_unittype[]" <?=$disabled;?> style="width: 100px;">
														<option value="">Select Unit</option>
														<option value="new">Add New Unit</option>
														<?php while($row8 = mysql_fetch_assoc($result_unit2)){?>
															<option value="<?=$row8['unit_id']?>" <?php if($row8['unit_id'] == $row3['ordered_pack_unittype']){ echo "selected='selected'"; } ?>><?=$row8['unit_type'];?></option>
														<?php }
														mysql_data_seek($result_unit2,0)?>
													</select>
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" <?=$disabled;?> value="<?=number_format($row3['ordered_qty_in_pack'],2)?>" style="width: 50px;"/>
												</td>
												<td>
													<select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" <?=$disabled;?> style="width: 100px;">
														<option value="">Select Unit</option>
														<option value="new">Add New Unit</option>
														<?php while($row8 = mysql_fetch_assoc($result_unit2)){?>
															<option value="<?=$row8['unit_id']?>" <?php if($row8['unit_id'] == $row3['ordered_qty_in_pack_unittype']){ echo "selected='selected'"; } ?>><?=$row8['unit_type'];?></option>
														<?php }
														mysql_data_seek($result_unit2,0)?>
													</select>
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt pack_size" name="pack_size[]" value="<?=number_format($row3['ordered_pack_size'],2)?>" <?=$disabled;?> style="width: 50px;" />
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt qty" name="qty[]" value="<?=number_format($row3['ordered_quantity'],2)?>" <?=$disabled1;?> style="width: 50px;"/>
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt price" name="price[]" value="<?='$'.number_format($row3['ordered_price'],2)?>" <?=$disabled;?> readonly />
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt tax_percentage" name="tax_percentage[]" value="<?='$'.$row3['ordered_tax_percentage']?>" <?=$disabled;?> readonly />
												</td>
												<td style="text-align: center;">
													<input type='text' class="itm_inpt amt" value="" readonly <?=$disabled;?>  />
												</td>
												<td style="text-align:center;">
													<img style="<?=$hide;?>" src="images/Delete.png" class="remove" />
												</td>
											</tr>
                                            
                                            <?php if($status == "Completed"){ ?>
                                            
                                            <tr id="<?=$k."_rec"?>" class="ramt" style="outline: 1px solid #0866c6;"> 
												<td style="color:#0866c6;">
												<?=$n?> (Received)
												</td>
												<td>&nbsp;</td>
												<td>
                                                <select disabled="disabled" class="itm_sel rec_pack_unittype" id="rec_pack_unittype" name="rec_pack_unittype[]" >
                                                    <option value="">Select Unit</option>
                                                    <option value="new">Add New Unit</option>
                                                    <?php while($row8 = mysql_fetch_assoc($result_unit)){?>
                                                    <option value="<?=$row8['id']?>" <?php if($row8['id'] == $row3['received_pack_unittype']){ echo "selected='selected'"; } ?>><?=$row8['unit_type'];?></option>
                                                    <?php }
                                                    mysql_data_seek($result_unit,0)?>
                                                </select>
												</td>												
												
												<td>
                                                <input disabled="disabled" type='text' class="itm_inpt r_qtypk" name="rec_qty_in_pack[]" value="<?=number_format($row3['received_qty_in_pack'],2)?>"/>
												</td>
												<td>
												<select disabled="disabled" class="itm_sel rec_qty_pack_unittype" id="r_qtypkunit_<?php echo $n; ?>" name="r_qtypkunit[]">
                                                    <option value="">Select Unit</option>
                                                    <option value="new">Add New Unit</option>
                                                    <?php while($row8 = mysql_fetch_assoc($result_unit)){?>
                                                    <option value="<?=$row8['id']?>" <?php if($row8['id'] == $row3['received_qty_in_pack_unittype']){ echo "selected='selected'"; } ?>><?=$row8['unit_type'];?></option>
                                                    <?php }
                                                    mysql_data_seek($result_unit,0)?>
                                                </select>
												</td>
												<td>
                                                <input  disabled="disabled" type='text' class="itm_inpt rec_pack_size" name="rec_pack_size[]" value="<?=number_format($row3['received_pack_size'],2)?>" />
												</td>
										<!-- 	</tr>
											<tr id="<?=$k."_rec"?>" class="ramt"> -->
                                            	<td>
                                                <input disabled="disabled" type='text' class="itm_inpt rec_qty" name="rec_qty[]" value="<?=$row3['received_quantity']?>" />
												</td>
												<td>
                                                <input  disabled="disabled" type='text' class="itm_inpt rec_price" name="rec_price[]" value="<?='$'.$row3['received_price']?>" />
												</td>
												<td>
                                                <input disabled="disabled" type='text' class="itm_inpt rec_tax_percentage" name="rec_tax_percentage[]" value="<?='$'.$row3['received_tax_percentage']?>" />
												</td>
												<td>
                                                <input disabled="disabled" type='text' class="itm_inpt r_amount" value="<?php echo '$'.number_format((($row3['received_price']+($row3['received_price']*$row3['received_tax_percentage']/100))*$row3['received_quantity']),2,'.',',') ?>" readonly name="r_amount" />
												</td>
												<td class="center">
                                                <input disabled='disabled' type="checkbox" name="received_chkb[]" <?php if($row3['received'] == 'yes'){ echo "checked='checked'"; } ?> class="check" />
                                				<!--<input type="hidden" value="<?=$row3['received']?>" name="received[]" class="check_val" />
                                                <div id="menu_div_<?php echo $row["inv_item_id"]; ?>">
												<?php if($row3['market']=='Retail' && strtolower($_SESSION["access_register"])=='yes'){
													$cehck_item  = item_check_fun($row3['inv_item_id']);
													if($cehck_item=='Yes'){
												 ?>
                                                	<span class="icon-thumbs-up"></span>
                                                <?php }else{ ?>
                                                	<a href="javascript:void(0);" onClick="save_menu('<?php echo $row3["inv_item_id"]; ?>')" >
													<img src="images/Save_receipe.png" >	
                                                    </a>
												<?php }  } ?>
                                                </div>-->
												</td>
												<!--<td>&nbsp;</td>
												<td>&nbsp;</td>-->
											</tr>
                                            <?php } ?>
										<?php $k++; }
									} ?>
								</tbody>
							</table>
							<?php if($purchase_id != '' || $vendor_id != ''){ 
								if($status == "Completed"){
							?>
                            	<table class="totals" id="totals_tbl1" style="float:right; margin-top:25px; width:40%; ">
								<thead>
									<tr class="title3">
									<td colspan="4" style="background-color:#0866C6;text-align:center;">Totals</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: center; font-weight: bold;background-color:#333333;color:#FFFFFF;"></td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Ordered</td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Received</td>
										<td style="text-align: center; font-weight: bold;font-size:11px;background-color:#333333;color:#FFFFFF;text-transform:uppercase;">Variance</td>
									</tr>
								<tr>
								<td>Subtotal:</td>
								<td>
                                <input type="text" id="ordSubTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="subTotal1" id="subTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" id="sub_total_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal-$ordr_subtotal)?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								<tr>
								<td>Tax:</td>
								<td>
                                <input type="text" id="ordTaxTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="taxTotal" id="taxTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" id="tax_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_tax_total-$ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								<tr>
								<td>Grand Total:</td>
								<td>
                                <input type="text" id="ordTotal1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ord_subtotal+$ord_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td>
                                <input type="text" name="total" id="total1" class="itm_inpt" style="width:100%;" value="<?=add_breket($ordr_subtotal+$ordr_tax_total)?>" readonly <?=$disabled;?>>
								</td>
								<td> 
                                <input type="text" id="total_vari1" class="itm_inpt" style="width:100%;" value="<?=add_breket(($ord_subtotal-$ordr_subtotal)+($ord_tax_total-$ordr_tax_total))?>" readonly <?=$disabled;?>>
								</td>
								</tr>
								</tbody>
                                </table>
                            <?php }else{ ?>
								<table id="totals_tbl" align="right" style="margin-top:10px;" class="cart_table">
									<tr class="active">
										<td>
                      <div class="cart_sub">
                        <span>Subtotal:</span>
  											<input type="text" name="subTotal" id="subTotal" class="itm_inpt" value="<?='$'.number_format($subtotal);?>" readonly <?=$disabled;?>>
                      </div>
										</td>
									</tr>
                                   
									<tr class="active">
										<td>
                      <div class="cart_sub">
                        <span>Tax:</span>
  											<input type="text" name="taxTotal" id="taxTotal" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($taxtotal);?>" readonly <?=$disabled;?>>
                    </div>
										</td>
									</tr>
									<tr class="active">
										<td>
                      <div class="cart_sub">
                        <span>Total:</span>
  											<input type="text" name="total" id="total" class="itm_inpt" style="margin-right:5px" value="<?='$'.number_format($total);?>" readonly <?=$disabled;?>>
                      </div>
										</td>
									</tr>
								</table>
							<?php } } ?>
							<input type="hidden" name="purchaseSubmit" id="purchaseSubmit" value="" />
							<input type="hidden" name="purchase_id" value="<?=$_GET['purchase_id'];?>" />
                            <input type="hidden" name="reorder" id="reorder" value="<?php echo $_GET['reorder']; ?>" >
						</form>
					<?php } ?>
                    <div id="clone_table_div">
					<?php if($vendor_id != ''){ ?>
						<!--Hidden table tr to insert when new item is clicked-->
						<table style="display: none;" id="clone_tbl">
							<tr>
								<td>
									<select class='frmstyle2 group' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Group</option>
										<?php while($row9 = mysql_fetch_array($result9)){ ?>
											<option value="<?=$row9['id']?>"><?=$row9['group'];?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select class='frmstyle2 item' name='item[]' <?=$disabled;?> style="width: 115px;">
										<option value="">Select Item</option>
									</select>
								</td>
								<td>
									<select class="itm_sel pack_unittype" name="pack_unittype[]" <?=$disabled;?> style="width: 100px;">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0)?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty_in_pack" name="qty_in_pack[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td>
									<select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" <?=$disabled;?> style="width: 100px;">
										<option value="">Select Unit</option>
										<option value="new">Add New Unit</option>
										<?php while($row8 = mysql_fetch_assoc($result8)){ ?>
											<option value="<?=$row8['id']?>"><?=$row8['unit_type'];?></option>
										<?php }
										mysql_data_seek($result8,0)?>
									</select>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt pack_size" name="pack_size[]" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt qty" name="qty[]" onKeyPress="return isNumberKey(event)" value="" <?=$disabled;?> style="width: 50px;"/>
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt price" name="price[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt tax_percentage" name="tax_percentage[]" <?=$disabled;?> readonly />
								</td>
								<td style="text-align: center;">
									<input type='text' class="itm_inpt amt" value="" <?=$disabled;?> style="width: 70px;" readonly />
								</td>
                                
								<td style="text-align:center; ">
									<img style="" src="images/Delete.png" class="remove" />
								</td>
							</tr>
						</table>
					<?php } ?>
                    </div>
					</div>
                </div><!--row-fluid-->
                  <?php include_once 'require/footer.php';?>
                <!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
 

<script type="text/javascript">

		var items = new Array();//stores all current order item details
        <?=$createJsArrays;?>
        <?=$addToJsArray;?>
        var i = jQuery('#item_tbl').find('tr:last').prop('id');//get id of last row
        if(i == ''){//means no previous items(new order)
            i=0;
        }else{
            i++;//add 1 to make the row numbering consecutive
        }


function date_search(date){
	text_s = jQuery('#search_txt').val();
	window.location="backoffice_purchases.php?date="+date+"&text_s="+text_s;
}
function text_search(text_s){
	var date = jQuery('#searchdate').val();
	//window.location="backoffice_purchases.php?date="+date+"&text_s="+text_s;
	var acttable = jQuery('.ui-tabs-active a').html();
	if(acttable=='Ordered'){
		jQuery('#vnd_tbl_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Shopping'){
		jQuery('#vnd_tbl_shopping_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Completed'){
		jQuery('#vnd_tbl_completed_filter input').val(text_s).trigger('keyup');
	}else if(acttable=='Shipped'){
		jQuery('#vnd_tbl_shipped_filter input').val(text_s).trigger('keyup');
	}
	
}


    var dfr;//holds global deferred
	 jQuery(function() {
        jQuery( "#shopping_datetime" ).datepicker({
            changeMonth: true,
			dateFormat:"yy-mm-dd",
            changeYear: true,
			minDate: 'today',
			/*** comment for now
			onSelect: function(dateText, inst) {				
				var d = new Date(dateText);
				jQuery("#calendar").fullCalendar("gotoDate", d.getYear(), d.getMonth(), d.getDate());
			},
				****/		
        });
    });
	jQuery('#shopping_datetime').keypress(function(event) {		
					return false;
	});
	jQuery(document).ready(function(e) {
        jQuery('.fc-state-highlight').prev('td').css('background-color','#CCC');
    });
	
	jQuery(window).resize(function() {		
			var width = jQuery('.get_width').width()-4;						
			var twidht = jQuery('#item_tbl tr td:last').width()+18
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");
	});
    jQuery(document).ready(function ($) {
	
		
		
		
		jQuery('#vnd_tbl,#vnd_tbl_shopping,#vnd_tbl_completed,#vnd_tbl_shipped').dataTable({
            "sPaginationType": "full_numbers", 
			 "aaSorting": [[2,'desc']],
			"bAutoWidth": false,           
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
		
		
		
		$('.save_item').live('click', function(){
		
			<?php if($purid != ""){ ?>
				$('#newitemSubmit').val('1');
				$('#edit_from').submit();
			<?php } else{ ?>
						
						
						
						$('#newitemSubmit').val('1');
						$.ajax({
						url: 'save-new-ven-item.php',
						data: $('#edit_from').serialize(),
						type: 'POST',
						dataType: 'json',
						success: function(data){
								
								$('#myModal').modal('hide');
								jAlert('Item Added Successfully!','Alert Dialog');
								$('#edit_from')[0].reset();
								//return false;
						}
					});
			<?php } ?>
		});
	
       // $('#shopping_datetime').datepicker({dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true});
        $('#ordDt').datepicker({dateFormat: 'yy-mm-dd'});
        <?php if(isset($_GET['flag'])){ ?>
            $('#purchase_orders').hide();
            $('#item_tbl').css('width','100%');
            $('#totals_tbl').css('width','60%');
			//$('#totals_tbl tr td input').css('width','150px');
			var width = jQuery('.get_width').width()-4;						
			var twidht = jQuery('#item_tbl tr td:last').width()+18;
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");
        <?php } ?>
       jQuery('.new_purchase').click(function(){ 
            <?php if(isset($_GET['flag'])){ ?>
				
                jConfirm('Do you want to close the current purchase order?','Confirm Dialog',function(r){
                if(r){
                    document.location = 'purchase_orders.php?flag';
                }
				});
            <?php }else{ ?>
                document.location = 'purchase_orders.php?flag';
            <?php } ?>
        });

//add new vendor item only if vendor is selected
           $('#add-item').live('click',function(){
		   if(jQuery("#vendor").val()=="" || jQuery("#vendor").val()=="find"){
		   		jAlert("Please Select Vendor First!","Alert Dialog");
				return false;
		   }
		   jQuery("#sivendor_id").val(jQuery("#vendor").val());
		   jQuery('#ture_barcode').hide();
		   	$('#edit_from')[0].reset();
			   //alert(); 
			   $('#myModal').modal('show');
		
	});
			
			
	$('.btn_save_item').live('click', function(){
		var ok = true;
		$('.required').each(function(){
			if($(this).val() == ''){
				ok = false;
				$(this).css('border-color','red');
			}else{
				$(this).css('border-color','');
			}
		});
		if($('#taxable').val() == 'yes' && $('#tax_percentage').val() == ''){
			ok = false;
			$('#tax_percentage').css('border-color','red');
		}else{
			$('#tax_percentage').css('border-color','');
		}
		if(ok){
			$.ajax({
				url: 'ajax/save-new-ven-item.php',
				data: $('#new_ven_itm_frm').serialize(),
				type: 'POST',
				dataType: 'json',
				success: function(data){
					//add group to item dropdowns if not already exists
					if(data.code == 1){
						var group = $('#group').val();
						var groupTxt = $('#group').children("option:selected").text();
						if(!$('select.group option[value*=' + group + ']').length > 0){
							$('select.group').append("<option value='" + group + "'>" + groupTxt + "</option>");
						}
						//dfr = $.Deferred();//global deferred object
						$('#addItem').click();
						//var rowid = $('tr.item_row :last').prop('id');
						var rowid = $('#item_tbl').find('tr:last').prop('id');
						$('#' + rowid + ' .group').val(data.group).trigger('change');
						setTimeout(function(){
						$('#' + rowid + ' .item').val(data.vendor_item).trigger('change');
						},1000);
						$('#myModal').modal('hide');
					}else if(data.code == 2){
						jAlert('This item already exists for selected vendor!','Alert Dialog');
					}
					
				}
			});
		}else{
			jAlert('The highlighted fields are required!','Alert Dialog');
		}
	});
        <?php /*?><?php } ?><?php */?>
		        
		

        var subEnabl = false;//set to true if submit button has been enabled
        $('#vendor').change(function(){//Submit form when vendor changes to load groups
            if($(this).val() == 'find'){
				//window.location.href = "storepoint_vendors.php?#findvendors";
				jQuery('#filter_modal').modal('toggle');
			}else{
				$('#order_frm').submit();
			}
        });
        //$('#addItem').live('click',function(){//add new item
		$("#addItem").live('click',function () {     
			
            $('#clone_tbl tr:last').clone(true).insertAfter('#item_tbl tr:last');
            $('#item_tbl tr:last').removeClass().addClass('item_row').prop('id', i);
            items[i] = new Array(
                "", //index 0 == inv_item_id(vendor_items.id)
                0, //index 1 == pack_size
                "", //index 2 == pack_unittype
                "", //index 3 == qty_in_pack
                "", //index 4 == qty_in_pack_unittype
                0, //index 5 == quantity
                0, //index 6 == price
                0, //index 7 == tax %
                0, //index 8 == price*quantity(subtotal)
                0, //index 9 == calculated tax total
                0  //index 10 == calculated total(qty*price+calculated tax)
               );
            i++;
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
            if($("#status option[value='Ordered']").length == 0){//add ordered option if not already added
                $('#status').append('<option value="Ordered">Ordered</option>');
            }
			
			var width = jQuery('.amt').width()-4;								
			var twidht = jQuery('#item_tbl tr td:last').width()+18;
			jQuery("#totals_tbl").css("margin-right",twidht+"px");
			jQuery("#totals_tbl tr td input").css("cssText", "width:"+width+"px !important;");
			
        });
        
		<?php if($purid == ""){ ?>
		if($("#vendor").val()!="" && $("#vendor").val()!="find"){
			$('#addItem').trigger('click');
		}
		<?php } ?>
		
		$('#status').change(function(){
			if(jQuery('#status').val()!='Shopping'){
				if(jQuery('.item_row').length==0 || jQuery('#0 .item').val()==""){
					jAlert('Please add items in order to submit PO as Ordered!','Alert Dialog');
					$('#status').val('Shopping');
					return false;
				}			
            jConfirm("Changing order to Ordered or Cancelled will no longer allow you to modify. Continue?","Confirm Dialog",function(r){
            if(r){
                if(!subEnabl){
                    enableSubmit();
                    subEnabl = true;
                }
				if(jQuery('#status').val()=='Cancelled'){
					jQuery('#add-item').hide();	
				}else{
					jQuery('#add-item').show();	
				}
                
				jQuery('#sub_btn').trigger('click');
            }else{
                $('#status').val('Shopping');
				
            }
				
			});
			}else{
				$('#status').val('Shopping');
				$('#addItem').prop('disabled',false);
                $('.remove,#addItem').unbind('click').css('opacity',1);
			}
        });
        $('.remove').live('click', function(){//remove item
            var rowid = $(this).closest('tr').prop('id');
            delete items[rowid];
            $(this).closest('tr').remove();
            recalcTotals();
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
            if(items[0] == undefined){
                $("#status option[value='Ordered']").remove();
            }
			jQuery('#sub_btn').prop('disabled',false);
            return false;
        });
        $('.group').live('change', function(){
            var rowid = $(this).closest('tr').prop('id');
			
			//var items = new Array([]);
			
			var vendorID ;
			var dfr;
			
			 vendorID = '<?=$vendor_id?>';
			 
			 //if(vendorID == '' || vendorID == null || vendorID == 'undefined')
				 vendorID = $("#vendor_id").val();
			 
			var vendorID = $("#vendor_id").val();
			if(typeof($("#vendor_id").val()) == 'undefined'){
				var vendorID = $("#vendor").val();
			}
			var location_id = '<?= '272867' ?>';
			
            $.ajax({
                url: 'ajax/load-vendor-items.php',
                //data: { group: $(this).val(), vendor: '<?=$vendor_id?>' },
				data: { group: $(this).val(), vendor: vendorID, location_id: location_id },
                dataType: 'json',
                success: function(data){
                    var select = $("#" + rowid + " select.item");
                    select.empty();
                    select.append('<option value="">Select Item</option>');
                    $(data).each(function(a,b){
                        select.append('<option value="' +  b.id + '" data-loc_inv_id="' +  b.loc_inv_id + '">' + b.description + '</option>');
                    });
                    dfr.resolve();
                }
            });
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
        $('.item').live('change', function(){
            var rowid = $(this).closest('tr').prop('id');
			console.log('rowid =>'+ rowid);
			var vendor_id = $("#vendor_id").val();
			if(typeof($("#vendor_id").val()) == 'undefined'){
				var vendor_id = $("#vendor").val();
			}
			
			console.log('vendor_id : '+vendor_id);
			var loc_inv_id = $(this).find(':selected').attr('data-loc_inv_id');
			if(typeof(items[rowid])=='undefined'){				
				items[0] = new Array('',0,'','','',0,0,0,0,0,0);
			}
            $.ajax({
                url: 'ajax/get-vendor-item-details.php',
				data: { item: $(this).val(), vendor: vendor_id, loc_inv_id:loc_inv_id },
                dataType: 'json',
                success: function(datas){					
                    data = datas['data'][0];
                    //add response to items array
                    items[rowid][0] = data.id; //inv_item_id(vendor_items.id)
                    items[rowid][1] = data.pack_size;//pack_size
                    items[rowid][2] = datas['unit_type'];//pack_unit_type
                    items[rowid][3] = data.qty_in_pack;//quantity in pack
                    items[rowid][4] = datas['unit_type'];//quantity in pack unittype
                    items[rowid][6] = data.price;//price
                    items[rowid][7] = data.tax;//tax %
					
					console.log(datas['unit_type'][0]);
					//$(".pack_unittype").html("<option value=''>Select Unit</option><option value='new'>Add New Unit</option><option value='24'>KG</option><option value='8'>CASE</option><option value='35'>Box</option>");
					$("#" + rowid + " .pack_unittype").html(datas['unit_type']);
					$("#" + rowid + " .qty_pack_unittype").html(datas['unit_type']);
					
                    $("#" + rowid + " .pack_size").val(data.pack_size);
                    $("#" + rowid + " .pack_unittype").val(data.pack_unittype);
                    $("#" + rowid + " .qty_in_pack").val(data.qty_in_pack);
                    $("#" + rowid + " .qty_pack_unittype").val(data.qty_in_pack_unittype);
                    $("#" + rowid + " .price").val(data.price);
                    $("#" + rowid + " .tax_percentage").val(data.tax);
					$("#" + rowid + " .qty").keyup();

                    recalcItemTotal(rowid);

                    if(!subEnabl){//enable submit
                        enableSubmit();
                        subEnabl = true;
                    }
                }
            });
        });
        $('.qty,.price,.tax_percentage').live('keyup', function(){
            var rowid = $(this).closest('tr').prop('id');
            var clas = $(this).prop('class').split(' ')[1];
            var index;
            switch(clas){
                case 'qty':
                    index = 5;
                    break;
                case 'price':
                    index = 6;
                    break;
                case 'tax_percentage':
                    index = 7;
                    break;
            }
            items[rowid][index] = numChk($(this));//add qty to items array
            recalcItemTotal(rowid);
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
        $('#btnSearch').click(function(){
            $('#searchFrm').submit();
        });
        $('.pack_unittype,.qty_pack_unittype').on('change', function () {//add new unit input for element
            var rowid = $(this).closest('tr').prop('id');
            var clas = $(this).prop('class').split(' ')[1];
            if ($(this).val() == "new") {
                $("#" + rowid + " ." + clas).replaceWith("<input type='text' class='frmstyle2 " + clas + "' name='" + clas + "[]' style='width:150px;' <?=$disabled;?>>");
            }
        });
        $(document).on('change', 'select#terms', function () {//add new terms
            if ($(this).val() == "new") {
                var currDropdown = $(this).clone();
                $(document).on('click', '.icon_clear', function () {
                    $(".clearable").replaceWith(currDropdown);
                    $('#terms').val('');
					$('#terms_new').val('no');
                });
				$('#terms_new').val('Yes');
                $("#terms").replaceWith("<span class='clearable'><input type='text' class='frmstyle1 xinput' id='terms' style='width:100%;' name='terms' <?=$disabled;?>><span style='left: 98%;position: relative; top: -27px;' class='icon_clear'>X</span></span>");
                $('.icon_clear').delay(200).fadeIn(300);
            }
        });

        $('.search_box').on('paste keyup','input',function(){
            if (!this.value) {
                $('#search_x').fadeOut(300);
                filter('','vnd_tbl',3,'clear');
            }else{
                $('#date_search_x').trigger('click');
                $('#search_x').delay().fadeIn(300);
                filter(this.value,'vnd_tbl',3,'search');
            }
        });
        $('#search_x').on('click',function(){	
            $('.search_box').find('input').val('');
            filter('','vnd_tbl',3,'clear');
            $(this).fadeOut(300);
        });
        if($('.search_box').find('input').val() != ''){
            $('#search_x').show();
        }

        if($('#status').val() == 'Cancelled'){//onpageload if status is cancelled, disable removing items
            $('.remove').unbind('click').css('opacity',.3);
        }
        $('#submit_order').click(function(){
		if(submitValidation()){
			//jAlert('Please Change something for Save','Alert Dialog');
			//return false;
		}
		
		});

        function filter (term, _id,cols,type){
            var suche = term.toLowerCase();
            var table = document.getElementById(_id);
            var ele;
            if(type == 'search'){
                for (var r = 2; r < table.rows.length; r++){
                    for(var i=2;i<=cols;i++){
                        ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g,"");
                        if (ele.toLowerCase().indexOf(suche)>=0 ){
                            table.rows[r].style.display = '';
                            break;
                        }else{
                            table.rows[r].style.display = 'none';
                        }
                    }
                }
            }else{
                for (r = 1; r < table.rows.length; r++){
                    table.rows[r].style.display = '';
                }
            }
        }
        function hideDropOptions(ele,val){
            ele.html('');
            $('#clone_tbl .price_unit option').each(function(){
                if($(this).val() == val){
                   ele.append($(this).clone(false));
                }
            });
        }
        function numChk(val){
            if(isNaN(val.val())){
                jAlert('This is a numeric field only!','Alert Dialog');
                val.val(0);
                return 0;
            }else{
                return val.val();
            }
        }
        function recalcItemTotal(rowid){
			console.log('rowid : '+rowid);
			console.log('AMT : '+$('#' + rowid + ' td input.amt').val());
            items[rowid][8] = round2Decimals(nullCheck(items[rowid][5])*nullCheck(items[rowid][6]));//calculate sutotal (price*qty)
            items[rowid][9] = round2Decimals(nullCheck(items[rowid][7])*.01*nullCheck(items[rowid][8]));//calculate and add tax to items array
            items[rowid][10] = round2Decimals(nullCheck(items[rowid][8]) + nullCheck(items[rowid][9]));//calculate and round then add to array
            $('#' + rowid + ' td input.amt').val('$'+(items[rowid][10]).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            recalcTotals();
        }
        function nullCheck(val){
            return val != null || val != '' ? val : 0;
        }
        function recalcTotals(){
            var subtotal = 0;
            var taxtotal = 0;
            var total = 0;
            for(var k=0;k<items.length;k++){
                if(items[k] != undefined){
                    subtotal += items[k][8];
                    taxtotal += items[k][9];
                    total += items[k][10];
                }
            }
            $('#subTotal').val('$'+(subtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $('#taxTotal').val('$'+(taxtotal).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
            $('#total').val('$'+(total).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        }
        function round2Decimals(rnd){
            return Math.round(rnd*Math.pow(10,2))/Math.pow(10,2);
        }
        function enableSubmit(){			
            //$('#submit_order').unbind('click').prop('src','images/submit.png').click(function(e){
				$('#submit_order').unbind('click').prop('disabled',false).click(function(e){
                e.preventDefault();
                if(submitValidation()){
					$('#order_frm input,#order_frm select').attr('disabled',false);
					
                    $('#purchaseSubmit').val('1');
                    $('#order_frm').submit();
					
                }
            });
        }
        function submitValidation(){
            if($('#vendor').val() == ''){
                jAlert('Please select a vendor!','Alert Dialog');
                //$('#vendor').closest('td').addClass('control-group error');
                return false;
            }else if($('#shopping_datetime').val() == ''){
                jAlert('Please insert a shopping date!','Alert Dialog');
                //$('#shopping_datetime').closest('td').addClass('control-group error');
                return false;
            }else if($('#terms').val() == ''){
                jAlert('Please select terms!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
			}else if($('#payment_type').val() == ''){
                jAlert('Please select Payment Type!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
			}else if($('#delivery_method').val() == ''){
                jAlert('Please select Delivery Method!','Alert Dialog');
               // $('#terms').closest('td').addClass('control-group error');
                return false;
            }else{
                return checkInputs();
            }
        }
        function checkInputs(){
            var succ = true;
            for(var j=0;j<items.length;j++){
                if(document.getElementById(j)){
                    var gr = $('#'+j).find('.group');
                    var it = $('#'+j).find('.item');
                    var ps = $('#'+j).find('.pack_size');
                    var put = $('#'+j).find('.pack_size_unittype');
                    var qip = $('#'+j).find('.qty_in_pack');
                    var qipu = $('#'+j).find('.qty_pack_unittype');
                    var qt = $('#'+j).find('.qty');
                    var pr = $('#'+j).find('.price');
                    var tax = $('#'+j).find('.tax_percentage');

                    if(gr.val() == ''){
                        succ = false;
                        jAlert('Please select a group!','Alert Dialog');
                        //gr.closest('td').addClass('control-group error');
                        break;
                    }else if(it.val() == ''){
                        succ = false;
                        jAlert('Please select an item!','Alert Dialog');
                        //it.closest('td').addClass('control-group error');
                        break;
                    }else if(put.val() == ''){
                        succ = false;
                        jAlert('Please select a pack unit!','Alert Dialog');
                        //put.closest('td').addClass('control-group error');
                        break;
                    }else if(qip.val() == ''){
                        succ = false;
                        jAlert('Please enter quantity pack!','Alert Dialog');
                        //qip.closest('td').addClass('control-group error');
                        break;
                    }else if(qipu.val() == ''){
                        succ = false;
                        jAlert('Please select a quantity pack unit!','Alert Dialog');
                        //qipu.closest('td').addClass('control-group error');
                        break;
                    }else if(qt.val() == ''){
                        succ = false;
                        jAlert('Please enter a quantity!','Alert Dialog');
                        //qt.closest('td').addClass('control-group error');
                        break;
                    }else if(pr.val() == ''){
                        succ = false;
                        jAlert('Please enter a price!','Alert Dialog');
                        //pr.closest('td').addClass('control-group error');
                        break;
                    }else if(tax.val() == ''){
                        succ = false;
                        jAlert('Please enter a tax percentage!','Alert Dialog');
                        //tax.closest('td').addClass('control-group error');
                        break;
                    }
                }
            }
            return succ;
        }
        $('#item_tbl input,#item_tbl select,#item_tbl textarea').on('change keyup', function(){
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
		$('.topTbl input,.topTbl select,.topTbl textarea').on('change keyup', function(){
            if(!subEnabl){
                enableSubmit();
                subEnabl = true;
            }
        });
		
		$('#popup').on('keyup','#item_search',function(e){ //alert('asd');
	if($(this).val().length > 3){
		$.ajax({
			url: 'ajax/get-items-by-term.php?s=' + $(this).val(),
			dataType:'json',
			success:function(data){
				$('#item_results').html('');
				$(data).each(function(a,b){
					$('#item_results').append("<li data-value='" + b.id + "'>" + b.description + "</li>");
				});
			}
		});
	}else{
		$('#popup table tr td input[type=text],#popup table tr td select').each(function(){
			$(this).val('');
		});
		$('#item').val('');
		$('#item_results').html('');
	}
});

$('#item_results li').live('click',function(){ //alert('asd');
	$.ajax({
		url: 'ajax/get-item-by-id.php?id=' + $(this).data('value'),
		dataType:'json',
		success:function(data){
			$('div#popup #item').val(data.id);
			$('div#popup #item_id').val(data.item_id);
			$('div#popup #description').val(data.description);
			$('div#popup #notes').val(data.notes);
			$('div#popup #pack_unittype').val(data.unit_type);
			$('div#popup #manufacturer').val(data.manufacturer);
			$('div#popup #brand').val(data.brand);
			$('div#popup #model_number').val(data.model_number);
			$('div#popup #taxable').val(data.taxable);
		}
	});
});
		
        <?php
        if($purchase_id != ''){
            for($i=0;$i<$k;$i++){
                echo "recalcItemTotal(" . $i .");";
            }
        }?>
    });
</script>


<!-- Modal -->
<div id="myModal" class="modal hide fade">
<form id="edit_from" onSubmit="return validate()" name="frm" action="" method="post" >
                  <input type="hidden" name="code_id" value="" id="code_id">
				   <input type="hidden" name="newitemSubmit" value="" id="newitemSubmit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Manage Vendor Items</h3>
  </div>
  <div class="modal-body" style="height:415px;">
  		<div style="padding:10px;clear:both;">
            <table width="100%" style="margin-bottom:20px;">
				 <tr>
                      <td width="150" align="left"><label>Status:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						<select name="status" id="status" style="width:277px;">
							<option value="" > - - - Select Status - - -</option>
							<option selected="selected" value="active">Active</option>
							<option value="inactive" >Inactive</option>
                   		 </select>
					  </td>
                 </tr>
				  <tr>
                      <td width="150" align="left"><label>Barcode: <span style="color:red"> *</span></label></td>
					  <td id="barcode_p" width="220">
							<span class="field input-append" style="margin:0;"> 
							<input style="width: 240px;margin-bottom: 10px;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="" />
							<input type="hidden" id="barcode_valid" value="">
							<span class="add-on"> 
							<a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search"></a>
							</span>
							<span style="display:none;" id="ture_barcode" class="add-on1">
								<img style="height:20px; width:20px; margin-left:5px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
							</span>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Market:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<select id="d_market" onChange="get_group(this.value)" style="width:277px;" name="d_market">
                             <option value="">- - - Select Market - - -</option>
                              <option value="All">All</option>
                              <option value="Bar">Bar</option>
                              <option value="Hotel">Hotel</option>
                              <option value="Restaurant">Restaurant</option>
                              <option value="Retail">Retail</option>
                              <option value="Other">Other</option>
                            </select>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Group:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<span class="field" id="group_span">
								<select name="group" id="group" style="width:277px;">
								  <option value="" >- - - Select Group - - -</option>
								</select>
							</span>
                    		<input type="hidden" name="group_ins_type" id="group_ins_type" value="old">
                    		<span class="field" id="new_group_span"  style="display:none;">
								<input type="text" class="input-xlarge" id="new_gruop" value="" name="new_gruop" style="width: 266px;margin-bottom: 10px;" >
								 <span style="vertical-align:super;margin-left: -25px;" id="group_cancel_btn">
								 	<i class="icon-remove"></i>
								 </span>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Inventory Items:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<span class="field" id="drop_span">
								<select id="inv_item" name="inv_item" style="width: 277px;margin-bottom:10px;">
								  <option value="" >- - -Select Inventory Item - - -</option>
								  <option value='new_inv_item'>- - - Add New Inventory Item - - -</option>
								</select>
							</span>
                   			<input type="hidden" name="inv_item_type" id="inv_item_type" value="old">
                    		<span class="field" id="new_span" style="display:none;">
                    			<input type="text" class="input-xlarge" id="inv_item1" value="" name="inv_item_new" style="width: 266px;margin-bottom: 10px;" >
                    			<span style="vertical-align:super;  margin-left: -25px;" id="cancel_btn">
									<i style="" class="icon-remove"></i>
								</span> 
							</span> 
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Name :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="name" value="" name="name" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Notes:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_notes" value="" name="ni_notes" >
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Barcode:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_barcode" value="" name="ni_barcode">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Manufacture:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_manufacture" value="" name="ni_manufacture">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Brand:</label></td>
					  <td width="220">
							 <input type="text" class="input-xlarge" id="ni_brand" value="" name="ni_brand">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Model Number:</label></td>
					  <td width="220">
							<input type="text" class="input-xlarge" id="ni_model" value="" name="ni_model">
					  </td>
                 </tr>
				 <tr class="new_inv_div" style="display:none;">
                      <td width="150" align="left"><label>Image:</label></td>
					  <td width="220">
						<span id="imagebox"> </span>
                        <span class="field" style="margin:0; width:100%; float:left;">
                        	<input type="hidden" value="" id="upc_search_image" value="upc_search_image">
							<input type="hidden" name="oldimage" id="oldimage" value="">
							<input type="hidden" name="digital_image_name" id="digital_image_name" value="">
							<input type="hidden" name="digital_image_delete" id="digital_image_delete" value="N">
							<a data-target="#imageModal" href="upload_inv_item_img.php" role="button" class="btn btn-primary" data-toggle="modal" id="imageLink" style="padding:3px  ">Upload Images</a>
							</span>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Internal Number:</label></td>
					  <td width="220">
						<input type="text" class="input-xlarge" id="vendor_internal_number" value="" name="vendor_internal_number" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Pack Size :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
                    	<input type="text" class="input-xlarge" id="pack_size" value="" name="pack_size" style="width: 266px;margin-bottom: 10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Pack Unit Type :<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						<div class="selectouter12 select_w3">
					<?php 
						$unittype = "SELECT * from inventory_item_unittype ORDER BY unit_type";
						$res_unit_type = mysql_query($unittype);
					?>
                    <select name="pack_unit_type" id="pack_unit_type" style="width:277px;" >
                      <option value="" >- - - Select Unit Type - - -</option>
                      <?php while($row_unit_type= mysql_fetch_array($res_unit_type)){ ?>
                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>">
					  <?php echo $row_unit_type['unit_type'];?>
                      </option>	
                      <?php } ?>
                    </select>
                    </div>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Qty In Pack:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							 <input type="text" class="input-xlarge" id="qty_in_pack" value="" name="qty_in_pack" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Qty In Pack Unit Type:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
							<div class="selectouter12 select_w3">
                    <select name="qty_in_pack_unit_type" id="qty_in_pack_unit_type" style="width:277px;" >
                      <option value="" >- - - Select Unit Type - - -</option>
                      <?php
						$res_unit_type = mysql_query($unittype);
						while($row_unit_type= mysql_fetch_array($res_unit_type)){ ?>
                      <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
                      <?php } ?>
                    </select>
                   </div>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Taxable:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						  <select onChange="javascript:if(this.value=='yes'){jQuery('#tax_per').show();}else{jQuery('#tax_per').hide();}" style="width:277px;" id="ni_taxable" name="ni_taxable">
                        <option value="">- - - Select Taxable - - -</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                        </select>
					  </td>
                 </tr>
				  <tr id="tax_per" style="display:none;">
                      <td width="150" align="left"><label>Tax Percentage:</label></td>
					  <td width="220">
						<input type="text" class="input-xlarge" id="tax_percentage" value="" name="tax_percentage" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				  <tr>
                      <td width="150" align="left"><label>Price:<span style="color:#FF0000;">*</span></label></td>
					  <td width="220">
						  <input type="text" onBlur="fix(this.value,'price')" onKeyPress="javascript:if(event.keyCode==13){fix(this.value,'price');return false;}" class="input-xlarge" id="price" value="" name="price" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Promotion:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="promotion" value="" name="promotion" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Promotion Price:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="promotion_price" value="" name="promotion_price" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Color:</label></td>
					  <td width="220">
						  <select name="inv_color" id="inv_color" style="width:277px;" >
                    	<option value="">- - -Select Color- - -</option>
                        <?php $cquery = mysql_query("select id,code from inventory_items_color");
							while($cres = mysql_fetch_array($cquery)){ ?>
                            	<option value="<?php echo $cres['id']; ?>"><?php echo $cres['code']; ?></option>
                            <?php } ?>                        
                    </select>
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Size:</label></td>
					  <td width="220">
						  <select name="inv_size" id="inv_size" style="width:277px;" >
                    	<option value="">- - -Select Size- - -</option>
                        <?php $squery = mysql_query("select id,code from inventory_items_size");
							while($sres = mysql_fetch_array($squery)){ ?>
                            	<option value="<?php echo $sres['id']; ?>"><?php echo $sres['code']; ?></option>
                            <?php } ?>                        
                    </select>
					  </td>
                 </tr>
				 <tr class="retails" style="display:none;">
                      <td width="150" align="left"><label>Type:</label></td>
					  <td width="220">
						  <select name="inv_type" id="inv_type" style="width:277px;" >
                    	<option value="">- - -Select Type- - -</option>
                        <?php $tquery = mysql_query("select id,code from inventory_items_type");
							while($tres = mysql_fetch_array($tquery)){ ?>
                            	<option value="<?php echo $tres['id']; ?>"><?php echo $tres['code']; ?></option>
                            <?php } ?>                        
                    </select>
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Created On:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" readonly id="created_on" value="BusinessPanel" name="created_on" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Created By:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="created_by" readonly value="<?=$_SESSION['user_full_name'];?>" name="created_by" style="width: 266px;margin-bottom:10px;">
					  </td>
                 </tr>
				 <tr>
                      <td width="150" align="left"><label>Created Date & Time:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="created_datetime" readonly value="<?php echo $ldatetitme;//echo date('Y-m-d h:i:s',strtotime($ldatetitme));?>" name="created_datetime" style="width: 266px;margin-bottom:10px;"> <?php //echo date('Y-m-d H:i:s'); ?>
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>Last On:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="last_on" value="BusinessPanel" readonly name="last_on">
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>By:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="last_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="last_by">
					  </td>
                 </tr>
				 <tr class="for_edit" style="display:none;">
                      <td width="150" align="left"><label>Date & Time:</label></td>
					  <td width="220">
						  <input type="text" class="input-xlarge" id="Last_datetime" readonly value="<?php echo date('Y-m-d h:i:s',strtotime($ldatetitme)); ?>" name="last_datetime">
					  </td>
                 </tr>
			</table>
		</div> 
		<input name="sivendor" type="hidden" value="<?php echo $vendor_id; ?>"  id="sivendor_id" />
                  <input type="hidden" id="group1_inp" name="group1_inp" value="<?php echo $_REQUEST['group1']; ?>" >
                  <input type="hidden" id="market_inp" name="market_inp" value="<?php echo $_REQUEST['market']; ?>" >
				 
               
  </div>
  <div class="modal-footer" style="text-align:center;">
   <p>
    <button class="btn btn-default btn_cancel" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button name="Submit" class="btn btn-primary save_item">Submit</button>
   </p>
  </div>
    </form>
</div>
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
		  <a href="#" id="open_filter_modal" style="position:absolute;margin-left:5px;"><button style="background: #5b5b5b; border-color: #6e6e6e;padding:4px;width:30px" class="btn btn-success btn-large"><i class="fa fa-filter"></i></button></a>
      </label>
    </div>
    <div class="modal-body" id="modalcontent" style="height:415px;"> </div>
    <div class="modal-footer" style="text-align: center;">
      <p >
        <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
      </p>
    </div>
</div>

<div id="filterformmodal" class="modal hide fade">
<?php 
$sql_vt = "SELECT vendor_type_id, code FROM vendors_types";
$res_vt = mysql_query($sql_vt);

$sql_it = "SELECT id,group_id,description FROM inventory_groups";
$res_it = mysql_query($sql_it);
?>
    <form id="filterform" name="filterform" method="post" action="" class="form-horizontal label-left">
        
		<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
			<h3>Filters</h3>
		</div>
		<div class="modal-body" style="max-height:500px !important;">
			<div class="control-group">
				<label class="control-label">Name: </label>
				<div class="controls">
					 <input type="text" name="filters[name]" value="" id="filtersname" placeholder="Name" title="Name" style=" height:32px; width:322px;">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Vendor Type: </label>
				<div class="controls">
					 <select multiple name="filters[vtype][]" value="" id="filtersvtype" style="width:325px;">
					 <option value="all">All</option>
					 <?php while($result_vt = mysql_fetch_array($res_vt)){?>
						<option value="<?php echo $result_vt["vendor_type_id"];?>"><?php echo $result_vt["code"];?></option>
						<?php } ?>
					 </select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Item Type: </label>
				<div class="controls">
					 <select multiple name="filters[itype][]" value="" id="filtersitype" style="width:325px;">
					 <option value="all">All</option>
					 <?php while($result_it = mysql_fetch_array($res_it)){?>
						<option value="<?php echo $result_it["id"];?>"><?php echo $result_it["description"];?></option>
						<?php } ?>
					 </select>
				</div>
			</div>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
				<button id="btnCancel" data-dismiss="modal" class="btn btn-default">Cancel</button>
				<button type="button" id="btnfilter" name="btnfilter"  class="btn btn-primary">Submit</button>
			</p>
		</div>
	</form>    
</div>
</body>
</html>

<script type="text/javascript" src="js/timepicker.js"></script>
<script>
function getvendor1(){
	if(jQuery('#vendors').val().length<4){
		jAlert('Please enter More than 3 Characters!','Alert Dialog');
		return false;
	}else{
	jQuery('#filter_modal').modal('toggle');
	jQuery('#keyword').val(jQuery('#vendors').val());
	GetVendor(1);
	
	}
}
function GetVendor(val){
var str;
if(val=="1"){
 	str = document.getElementById('vendor').value;
	}
else{	
	str = jQuery('#keyword').val();
	}
if(str.length>2){
document.getElementById('keyword').value=str;
 document.getElementById("modalcontent").innerHTML="";
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     document.getElementById('vendor').value=str;
	 document.getElementById("modalcontent").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","vendor_search.php?q="+str,true);
xmlhttp.send();
}else{
jQuery('#modalcontent').html('');

}

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
	  	 jConfirm('Please Enter Minimum 6 Digit Valid Barcode Number Only!', 'Alert!', function(r) {
         //jQuery('#barcode').focus();		     
          });
         jQuery('#popup_cancel').remove();
         return false;	
	  }
	  var length ="";
	 length = search_val.length;
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
		data:{search_val:search_val},
		success:function(data){
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
				
				jQuery('#inv_item').val('new_inv_item').trigger('change');
				jQuery('#inv_item1').val(data[0]).trigger('change');				
				}
			}else{			
				
				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				jQuery('#inv_item').val('').trigger('change');
				jQuery('#ni_barcode').val('');
				jQuery('#inv_item1').val('').trigger('change');
				jQuery('#ture_barcode').hide();
				jQuery('#ni_notes').val("");				
				jQuery('#imagebox').html('');
				jQuery('#upc_search_image').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}

}
function loadVendor(id,email,phone,name,image)
{
	
	jQuery('#vendor_id').val(id);	
	
	var vendor_already_exist = 0;
	
	jQuery('#vendor option').map(function () {
		if (jQuery(this).val() == id) 
		{
			vendor_already_exist = 1;
		}
	});
	
	if(vendor_already_exist == 0)
	{
		jQuery('#vendor').append(jQuery('<option>', {
				value: id,
				text: name.replace("~~~~", "'", 'g')
			}));
	}
	
	
	var groupOptions = '';
	var unitOptions = '';
	var trtdelements = '';
	var totals_tbl_trtdelements = '';
	var clone_table_addeliment = '';
		
	jQuery("#vendor").val(id);
	
	jQuery('#terms').empty();
	jQuery('#payment_type').empty();
	jQuery('#delivery_method').empty();
	jQuery('#totals_tbl').remove();

	jQuery.ajax({
				url:'populate_vendor_details_from_popup.php',
				type:'POST',
				data:{q:id},
				dataType: 'json',
				success:function(data){
					if(data.flag)
					{
						
						jQuery("#vendor_invoice_num").val(data.vendor_invoice_num);
						
						jQuery.each(data.terms, function (i, item) {
							jQuery('#terms').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						
						jQuery.each(data.payment, function (i, item) {
							jQuery('#payment_type').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						jQuery.each(data.delivery, function (i, item) {
							jQuery('#delivery_method').append(jQuery('<option>', { 
								value: item.value,
								text : item.text 
							}));
						});
						
						jQuery.each(data.group, function (i, item) {
							groupOptions += '<option value="'+item.value+'">'+item.text+'</option>';
						});
						
						jQuery.each(data.units, function (i, item) {
							unitOptions += '<option value="'+item.value+'">'+item.text+'</option>';
						});
						
							
							items[0] = new Array('',0,'','','',0,0,0,0,0,0);
							i = 1;
						
							trtdelements  = '<tr>';
							trtdelements  += '<td style="padding:0px;" colspan="11">';
							trtdelements  += '<input type="button" style="" value="Add New Item" id="addItem" class="btn btn-success btn_table_top">';
							trtdelements  += '<h4 class="widgettitle">Items</h4></td>';
							trtdelements  += '</tr>';
							trtdelements  += '<tr class="title">';
							trtdelements  += '<td>Group</td>';
							trtdelements  += '<td>Item &nbsp;';
							trtdelements  += '<img style="cursor:pointer;position:relative;bottom: -3px;height: 14px; " title="Add New Vendor Item" alt="Add New Item" id="add-item" src="images/add.png">';
							trtdelements  += '</td>';
							trtdelements  += '<td class="itm_inpt_th">Pack Size</td>';
							trtdelements  += '<td class="itm_sel_th">Pack Unit</td>';
							trtdelements  += '<td class="itm_inpt_th">Qty Pack</td>';
							trtdelements  += '<td class="itm_sel_th">Qty Pack Unit</td>';
							trtdelements  += '<td class="itm_inpt_th">Qty</td>';
							trtdelements  += '<td class="itm_inpt_th">Price</td>';
							trtdelements  += '<td class="itm_inpt_th">Tax %</td>';
							trtdelements  += '<td class="itm_inpt_th get_width">Amount</td>';
							trtdelements  += '<td style="width:60px;text-align:center;">Remove</td>';
							trtdelements  += '</tr>';
							
							trtdelements += '<tr class="item_row" id="0">';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 115px;" class="frmstyle2 group">';
							trtdelements += groupOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 115px;" name="item[]" class="frmstyle2 item">';
							trtdelements += '<option value="">Select Item</option>';
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" style="width: 50px;" name="pack_size[]" class="itm_inpt pack_size">';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<input type="text" style="width: 50px;" name="qty_in_pack[]" class="itm_inpt qty_in_pack">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<select style="width: 100px;" name="pack_unittype[]" class="itm_sel pack_unittype">';
							trtdelements += unitOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td>';
							trtdelements += '<select style="width: 100px;" name="qty_pack_unittype[]" class="itm_sel qty_pack_unittype">';
							trtdelements += unitOptions;
							trtdelements += '</select>';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" style="width: 50px;" value="1" name="qty[]" class="itm_inpt qty">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" name="price[]" class="itm_inpt price">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" name="tax_percentage[]" class="itm_inpt tax_percentage">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align: center;">';
							trtdelements += '<input type="text" readonly="" style="width: 70px;" value="" class="itm_inpt amt">';
							trtdelements += '</td>';
							trtdelements += '<td style="text-align:center; ">';
							trtdelements += '<img class="remove" src="images/Delete.png" style="">';
							trtdelements += '</td>';
							trtdelements += '</tr>';
						
							
							jQuery('#item_tbl tbody').html(trtdelements);
							totals_tbl_trtdelements  = '<table align="right" class="cart_table" style="margin-top: 10px; width: 60%; margin-right: 78.5px;" id="totals_tbl">';
							totals_tbl_trtdelements += '<tbody><tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Subtotal:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" class="itm_inpt" id="subTotal" name="subTotal" style="width: 121.5px ! important;">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '<tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Tax:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" style="width: 121.5px ! important;" class="itm_inpt" id="taxTotal" name="taxTotal">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '<tr class="active">';
							totals_tbl_trtdelements += '<td>';
							totals_tbl_trtdelements += '<div class="cart_sub">';
							totals_tbl_trtdelements += '<span>Total:</span>';
							totals_tbl_trtdelements += '<input type="text" readonly="" value="" style="width: 121.5px ! important;" class="itm_inpt" id="total" name="total">';
							totals_tbl_trtdelements += '</div>';
							totals_tbl_trtdelements += '</td>';
							totals_tbl_trtdelements += '</tr>';
							totals_tbl_trtdelements += '</tbody></table>';
							
							jQuery( "#item_tbl" ).after( totals_tbl_trtdelements );
							
							
							clone_table_addeliment = clone_table_addeliment + '<table style="display: none;" id="clone_tbl">';
							clone_table_addeliment = clone_table_addeliment +  '<tr><td><select class="frmstyle2 group" style="width: 115px;">';
							clone_table_addeliment = clone_table_addeliment + groupOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';
							clone_table_addeliment = clone_table_addeliment + '<td><select class="frmstyle2 item" name="item[]" style="width: 115px;"><option value="">Select Item</option>';
							clone_table_addeliment = clone_table_addeliment + '</select></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt pack_size" name="pack_size[]" style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt qty_in_pack" name="qty_in_pack[]" style="width: 50px;"/></td>';						
							clone_table_addeliment = clone_table_addeliment + '<td><select class="itm_sel pack_unittype" name="pack_unittype[]" style="width: 100px;">';
							clone_table_addeliment = clone_table_addeliment + unitOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td><select class="itm_sel qty_pack_unittype" name="qty_pack_unittype[]" style="width: 100px;">';
							clone_table_addeliment = clone_table_addeliment + unitOptions;
							clone_table_addeliment = clone_table_addeliment + '</select></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt qty" name="qty[]" value="1" style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt price" name="price[]" readonly style="width: 50px;"/></td>';
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt tax_percentage" name="tax_percentage[]" readonly /></td>';							
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align: center;"><input type="text" class="itm_inpt amt"  readonly style="width: 70px;"/></td>';	
							clone_table_addeliment = clone_table_addeliment + '<td style="text-align:center; "><img style="" src="images/Delete.png" class="remove" /></td>';	
							clone_table_addeliment = clone_table_addeliment + '</tr></table>'	
							jQuery( "#clone_table_div").html(clone_table_addeliment);
					}
				}
			});
	
		
	jQuery('#status').prop("disabled", false); 
	jQuery('#po').prop("disabled", false); 
	jQuery('#comments').prop("disabled", false); 
	jQuery('#terms').prop("disabled", false); 
	jQuery('#payment_type').prop("disabled", false); 
	jQuery('#delivery_method').prop("disabled", false); 
	jQuery('#add-item').show();
	
	jQuery('#filter_modal').modal('toggle');
}
jQuery(document).ready(function(){

jQuery('.add-on A').live('click',function(){
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

	jQuery( "#searchdate" ).datepicker();
	jQuery( "#order_datetime" ).datepicker({dateFormat: 'yy-mm-dd'});
	
	jQuery('#open_filter_modal').live('click',function(){
	 jQuery("#modalcontent").html('');
	 jQuery('#filterformmodal').modal('show');
	})
	
	
	
	jQuery('#btnfilter').live('click',function(){
	
		if( jQuery('#filtersvtype').val() == null && jQuery('#filtersitype').val() == null &&  jQuery('#filtersname').val().length<4)
		{
			jAlert('Please enter More than 3 Characters!','Alert Dialog');
			return false;
		}
		
		else
		{
			jQuery.ajax({
				url:'vendor_search_by_filter.php',
				type:'POST',
				data:jQuery("#filterform").serialize(),
				success:function(data){
					if(data){
						jQuery("#modalcontent").html(data);
						jQuery('#filterformmodal').modal('hide');
					}else{
					
					}
				}
			});
		}
	
	});

	
});
</script>
<?php
	}
?>