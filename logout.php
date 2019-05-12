<?php
require_once 'require/security.php';
include_once 'config/accessConfig.php';

$sql = 
	"insert into employee_master_ping set 
		empmaster_id =".$_SESSION['client_id']." ,
		ip = '".$_SERVER['REMOTE_HOST']."' ,
		ping_type = 'signout' ,
		datetime = '".date("Y-m-d H:i:s")."',
		longitude = '',
		latitude = '',
		created_on ='TeamPanel' ,
		created_by =".$_SESSION['client_id'].",
		created_datetime = '".date("Y-m-d H:i:s")."'
		
";
$res = mysql_query($sql) or die(mysql_error());
	
unset($_SESSION['client_id']);
unset($_SESSION['first_name']);
unset($_SESSION['last_name']);
unset($_SESSION['email']);
unset($_SESSION['name']);
unset($_SESSION['image']);
unset($_SESSION['accessStorePoint']);
unset($_SESSION['accessChefedIN']);
unset($_SESSION['accessStylistFN']);
unset($_SESSION['DeliveryPoint']);

unset($_SESSION);
session_destroy();
header('location: index.php');
?>