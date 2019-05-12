<?php
include '../config/accessConfig.php';
if(intval($_POST['client_id']) != ''){
	session_name("VENDOR");
	session_start();
	if(isset($_POST['client_id'])){
		$_SESSION['client_id'] = $_POST['client_id'];
	}
        
        if(isset($_POST['password'])){
		$_SESSION['password'] = $_POST['password'];
	}
        
	if(isset($_POST['first_name'])){
		$_SESSION['first_name'] = $_POST['first_name'];
	}
	if(isset($_POST['last_name'])){
		$_SESSION['last_name'] = $_POST['last_name'];
	}
	if(isset($_POST['email'])){
		$_SESSION['email'] = $_POST['email'];
	}
	if(isset($_POST['name'])){
		$_SESSION['name'] = $_POST['name'];
	}
	if(isset($_POST['image'])){
		$_SESSION['image'] = $_POST['image'];
	}
	$_SESSION['accessStorePoint'] = ($_POST['StorePoint']=='')?"No":$_POST['StorePoint'];
	$_SESSION['accessChefedIN'] = ($_POST['ChefedIN']=='')?"No":$_POST['ChefedIN'];
	$_SESSION['accessStylistFN'] =($_POST['StylistFN']=='')?"No":$_POST['StylistFN'];
	$_SESSION['DeliveryPoint'] =($_POST['DeliveryPoint']=='')?"No":$_POST['DeliveryPoint'];
	if(isset($_POST['StorePointVendorID']) && $_POST['StorePointVendorID']!="" && $_POST['StorePointVendorID']!=0){
		$_SESSION['StorePointVendorID'] =$_POST['StorePointVendorID'];
	}
	$_SESSION['ChefedIN_Business_Name'] =$_POST['ChefedIN_Business_Name'];

	$_SESSION["latitude"]="";
	if (isset($_POST["latitude"])){
		$_SESSION["latitude"]= $_POST["latitude"];
	}

	$_SESSION["longitude"]="";
	if (isset($_POST["longitude"])){
		$_SESSION["longitude"]=$_POST["longitude"];
	}
} else {
	header('Location: /panel/index.php');
}
?>