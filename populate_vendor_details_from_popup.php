<?php
error_reporting(0);
include_once("includes/session.php");
include_once("config/accessConfig.php");

$sql = " SELECT * FROM vendors WHERE id =".$_REQUEST['q'];	
$res = 	mysql_query($sql) or die(mysql_error());
$num_rows = mysql_num_rows($res);

$i=1;

$payment = array();
$delivery = array();
$terms = array();
$group = array();
$unit = array();

$payment[0]['value'] = '';
$payment[0]['text'] = '- - - Payment Type - - -';

$delivery[0]['value'] = '';
$delivery[0]['text'] = '- - - Select Delivery Method - - -';

$terms[0]['value'] = '';
$terms[0]['text'] = '- - - Select Terms - - -';

$group[0]['value'] = '';
$group[0]['text'] = 'Select Group';

$unit[0]['value'] = '';
$unit[0]['text'] = 'Select Unit';

if($num_rows >=1)
{
	$row=mysql_fetch_array($res);
	
	$payment_types  = $row['payment_types'];
	$delivery_types = $row['delivery_types'];
	$terms_types    = $row['terms_types'];
	
	$sql_payment = " SELECT vendors_payments_id,code FROM vendors_payment_types WHERE vendors_payments_id  IN (".$payment_types.") ";	
	$res_payment = 	mysql_query($sql_payment);
	$num_rows_payment = mysql_num_rows($res_payment);
	if($num_rows_payment > 0)
	{
		$a = 1;
		while($row_payment = mysql_fetch_array($res_payment))
		{
			$payment[$a]['value']	= $row_payment['vendors_payments_id'];
			$payment[$a]['text']	= $row_payment['code'];
			$a ++;
		}
	}
	
	$sql_delivery = " SELECT vendors_delivery_types_id,code FROM vendors_delivery_types WHERE vendors_delivery_types_id  IN (".$delivery_types.") ";	
	$res_delivery = 	mysql_query($sql_delivery);
	$num_rows_delivery = mysql_num_rows($res_delivery);
	if($num_rows_delivery > 0)
	{
		$a = 1;
		while($row_delivery = mysql_fetch_array($res_delivery))
		{
			$delivery[$a]['value']  = $row_delivery['vendors_delivery_types_id'];
			$delivery[$a]['text']	  = $row_delivery['code'];
			$a ++;
		}
	}
	
	$sql_terms = " SELECT vendors_terms_types,code FROM vendors_terms_types WHERE vendors_terms_types  IN (".$terms_types.") ";	
	$res_terms = 	mysql_query($sql_terms);
	$num_rows_terms = mysql_num_rows($res_terms);
	if($num_rows_terms > 0)
	{
		$a = 1;
		while($row_terms = mysql_fetch_array($res_terms))
		{
			$terms[$a]['value'] = $row_terms['vendors_terms_types'];
			$terms[$a]['text']	  = $row_terms['code'];
			$a ++;
		}
	}
	
	
	$sql_group = "SELECT DISTINCT ig.id, ig.description 
                   FROM vendor_items vi
                   INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
                   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
                   WHERE vi.vendor_id='" . $_REQUEST['q'] . "'
                   ORDER BY ig.description ASC";
    $res_group = mysql_query($sql_group) ;
	
	$num_rows_group = mysql_num_rows($res_group);
	if($num_rows_group > 0)
	{
		$a = 1;
		while($row_group = mysql_fetch_array($res_group))
		{
			$group[$a]['value'] = $row_group['id'];
			$group[$a]['text']	= $row_group['description'];
			$a ++;
		}
	}
	
	$sql_unit = " select id,unit_type from inventory_item_unittype ";	
	$res_unit = 	mysql_query($sql_unit);
	$num_rows_unit = mysql_num_rows($res_unit);
	if($num_rows_unit > 0)
	{
		$a = 1;
		while($row_unit = mysql_fetch_array($res_unit))
		{
			$unit[$a]['value'] = $row_unit['id'];
			$unit[$a]['text']  = $row_unit['unit_type'];
			$a ++;
		}
	}
	
	$query10 = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(vendor_invoice_num AS UNSIGNED))+1, 100000) as maxid FROM vendor_purchases WHERE vendor_id=".$_REQUEST['q']));
			if($query10['maxid']>100000){
			$vendor_invoice_num =$query10['maxid'];
			}else{
			$vendor_invoice_num =100000;
			}
	
	
	$json_array  = array(
							'flag' => true,
							'payment' =>$payment,
							'delivery' =>$delivery,
							'terms' =>$terms,
							'group' => $group,
							'units' => $unit,
							'vendor_invoice_num' => $vendor_invoice_num 
				   		);
	
}
else
{
	$json_array  = array('flag' => false);
}

echo json_encode($json_array);
?>
	