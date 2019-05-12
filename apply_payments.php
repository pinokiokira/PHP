<?php
include_once 'require/security.php';
include_once("config/accessConfig.php");

$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

$id=$_REQUEST["id"];
$amount=$_REQUEST["amount"];
$purchase_id = $_REQUEST['purchase_id'];
$location_id=$_REQUEST["location_id"];

$email = $_SESSION['email'];
$qry_is_scheduling = "SELECT id FROM employees WHERE email='". $email ."'";
$rs_is_scheduling = mysql_query($qry_is_scheduling) or die($qry_is_scheduling .'-----'. mysql_error());
if($rs_is_scheduling && mysql_num_rows($rs_is_scheduling) > 0 ) {
	$row_is_scheduling = mysql_fetch_assoc($rs_is_scheduling);
	$row_is_scheduling = $row_is_scheduling['id'];
}

/* $jsonurl = API."api/locationtime.php?location_id=".$location_id;
 $json = file_get_contents($jsonurl,0,null,null);
 $datetimenow= $json ;*/

$i=0;
$j=0;
//->juni [req REQ_018] - 2014-09-21 - split mesages
$recordsWith0 = 0;
$recordsTotal = 0;
//<-juni [req REQ_018] - 2014-09-21
foreach($amount as $val){
/*for($r=0;$r<=$i;$r++)
{*/
	//if($amount[$i]!=""){
	if($amount[$i]!="" && $amount[$i] > 0){ // juni [req REQ_018] - 2014-09-21 -  added default value 0
		$recordsTotal ++;
		$query = "update purchases_payments 
		set 
		`applied_amount`=applied_amount+$amount[$i],
		last_datetime ='".date('Y-m-d H:i:s')."', 
		last_on='VendorPanel',
		last_by='".$_SESSION['client_id']."' 
		where `id`='$id'";
		$qry=mysql_query($query) or die(mysql_error());
		
		/*$query_check ="select id from purchases_payments_applied where location_id = '".$_SESSION['loc']."' AND vendor_id = '".$_REQUEST['vendor_id']."' AND purchase_id = $purchase_id[$i]";
		$res_check = mysql_query($query_check);
		if(mysql_num_rows($res_check)>0){
		$update =" update purchases_payments_applied set `amount_applied`=amount_applied+$amount[$i], datetime = '".$datetimenow."' where location_id = '".$_SESSION['loc']."' AND vendor_id = '".$_REQUEST['vendor_id']."' AND purchase_id = $purchase_id[$i]";
		$res_update = mysql_query($update);
		}else{*/
		$query1="INSERT INTO purchases_payments_applied SET 
		location_id = '".$_REQUEST['location_id']."',
		vendor_id = '".$vendor_id."',
		payment_id = '".$_REQUEST['payment_id']."',
		employee_id = '".$row_is_scheduling."',
		datetime = now(),
		purchase_id = '".$purchase_id[$i]."',
		amount_applied = '".$amount[$i]."'
		";
		$qry1=mysql_query($query1) or die(mysql_error());
		/*}*/
		
		$query2 = "UPDATE purchases SET applied_amount = applied_amount+$amount[$i] WHERE id='$purchase_id[$i]'";
		$qry2 = mysql_query($query2) or die(mysql_error());
	
	} else
		$recordsWith0++;
	
	
/*}*/
$i++;
}

if($recordsTotal > 0){
echo "<script language='javascript'>document.location.href='storepoint_payments.php?location=$location_id&msg=Payment Successful';</script>";
}else{
	if($recordsWith0 > 0 && $recordsTotal < 1)
		echo "<script language='javascript'>document.location.href='storepoint_payments.php?location=$location_id&msg=Please enter payment amount > 0 ';</script>";
	else
		echo "<script language='javascript'>document.location.href='storepoint_payments.php?location=$location_id&msg=Error Occured! Please Try Again Latter';</script>";
}
?>
