<?
	require_once 'require/security.php';
	include 'config/accessConfig.php';

	$empmaster_id=mysql_real_escape_string($_POST['client_id']);
	$salutation=mysql_real_escape_string($_POST['salutation']);
	$first_name=mysql_real_escape_string($_POST['first_name']);
	$last_name=mysql_real_escape_string($_POST['last_name']);
	$address=mysql_real_escape_string($_POST['address']);
	$address2=mysql_real_escape_string($_POST['address2']);
	$country=mysql_real_escape_string($_POST['country']);
	$currency=mysql_real_escape_string($_POST['currency']);
	$city=mysql_real_escape_string($_POST['city']);
	$state=mysql_real_escape_string($_POST['state']);
	$zip=mysql_real_escape_string($_POST['zip']);
	$region=mysql_real_escape_string($_POST['region']);
	$neighborhood=mysql_real_escape_string($_POST['neighborhood']);
	$telephone=mysql_real_escape_string($_POST['telephone']);
	$fax=mysql_real_escape_string($_POST['fax']);
	$mobile=mysql_real_escape_string($_POST['mobile']);
	$sex=mysql_real_escape_string($_POST['sex']);
	$dob=mysql_real_escape_string($_POST['dob']);
	$viewable=mysql_real_escape_string($_POST['viewable']);
	$activities=mysql_real_escape_string($_POST['activities']);
	$competencies=mysql_real_escape_string($_POST['competencies']);
	
	$Previous_Job_1_Company=mysql_real_escape_string($_POST['Previous_Job_1_Company']);
	$Previous_Job_1_Title=mysql_real_escape_string($_POST['Previous_Job_1_Title']);
	$Previous_Job_1_Location=mysql_real_escape_string($_POST['Previous_Job_1_Location']);
	$Previous_Job_1_Startdate=mysql_real_escape_string($_POST['Previous_Job_1_Startdate']);
	$Previous_Job_1_Enddate=mysql_real_escape_string($_POST['Previous_Job_1_Enddate']);
	$Previous_Job_1_Description=mysql_real_escape_string($_POST['Previous_Job_1_Description']);
	$Previous_Job_2_Company=mysql_real_escape_string($_POST['Previous_Job_2_Company']);
	$Previous_Job_2_Title=mysql_real_escape_string($_POST['Previous_Job_2_Title']);
	$Previous_Job_2_Location=mysql_real_escape_string($_POST['Previous_Job_2_Location']);
	$Previous_Job_2_Startdate=mysql_real_escape_string($_POST['Previous_Job_2_Startdate']);
	$Previous_Job_2_Enddate=mysql_real_escape_string($_POST['Previous_Job_2_Enddate']);
	$Previous_Job_2_Description=mysql_real_escape_string($_POST['Previous_Job_2_Description']);
	$Previous_Job_3_Company=mysql_real_escape_string($_POST['Previous_Job_3_Company']);
	$Previous_Job_3_Title=mysql_real_escape_string($_POST['Previous_Job_3_Title']);
	$Previous_Job_3_Location=mysql_real_escape_string($_POST['Previous_Job_3_Location']);
	$Previous_Job_3_Startdate=mysql_real_escape_string($_POST['Previous_Job_3_Startdate']);
	$Previous_Job_3_Enddate=mysql_real_escape_string($_POST['Previous_Job_3_Enddate']);
	$Previous_Job_3_Description=mysql_real_escape_string($_POST['Previous_Job_3_Description']);	
	
	
	$education=mysql_real_escape_string($_POST['education']);
	$languages=mysql_real_escape_string($_POST['languages']);
	$employment_type=mysql_real_escape_string($_POST['employment_type']);
	$emp_position1=mysql_real_escape_string($_POST['emp_position1']);
	$emp_position2=mysql_real_escape_string($_POST['emp_position2']);
	$emp_position3=mysql_real_escape_string($_POST['emp_position3']);
	$digital_image_name1 = mysql_real_escape_string($_POST['digital_image_name1']);
	if($digital_image_name1!=""){
	$StorePoint_image = 'vendors/'.$digital_image_name1;	
	}else{
	$StorePoint_image =	 mysql_real_escape_string($_POST['old_store_img']);
	}
	$StorePoint=mysql_real_escape_string($_POST['StorePoint']);
	$ChefedIN=mysql_real_escape_string($_POST['ChefedIN']);
	$StylistFN=mysql_real_escape_string($_POST['StylistFN']);
	$DeliveryPoint=mysql_real_escape_string($_POST['DeliveryPoint']);
	
	$Delivery_activated_datetime=mysql_real_escape_string($_POST['Delivery_activated_datetime']);
	$Delivery_trasporation=mysql_real_escape_string($_POST['Delivery_trasporation']);
	$Delivery_payment_method=mysql_real_escape_string($_POST['Delivery_payment_method']);
        
	$StorePoint_vendor_id=mysql_real_escape_string($_POST['StorePoint_vendor_id']);
	$vendor_id=mysql_real_escape_string($_POST['vendor_id']);
	
	if($vendor_id!="")
	{
		$StorePoint_vendor_id=$vendor_id;
	}
	
	$introduction=mysql_real_escape_string($_POST['introduction']);
	$services=mysql_real_escape_string($_POST['services']);
	$experience=mysql_real_escape_string($_POST['experience']);
	$chefedin_market=mysql_real_escape_string($_POST['chefedin_market']);
	$Chefedin_Business_Name=mysql_real_escape_string($_POST["Chefedin_Business_Name"]);
	$digital_image_names = mysql_real_escape_string($_POST['digital_image_names']);
	if($digital_image_names!=""){
	$ChefedIN_image = 'employee_master_images/'.$digital_image_names;	
	}else{
	$ChefedIN_image =	 mysql_real_escape_string($_POST['old_chefedin_img']);
	}
	$website=mysql_real_escape_string($_POST['website']);
	$references=mysql_real_escape_string($_POST['references']);
	$rate=mysql_real_escape_string($_POST['rate']);
	$StylistFN_Company= mysql_real_escape_string($_POST['StylistFN_Company']);
	$StylistFN_Description= mysql_real_escape_string($_POST['StylistFN_Description']);
	$StylistFN_Style= mysql_real_escape_string($_POST['StylistFN_Style']);
	$StylistFN_Located= mysql_real_escape_string($_POST['StylistFN_Located']);
	$StylistFN_location_id= mysql_real_escape_string($_POST['StylistFN_location_id']);
	$last_by=mysql_real_escape_string($_POST['last_by']);
	$last_on=mysql_real_escape_string($_POST['last_on']);
    $vendorname=mysql_real_escape_string($_POST['vendorname']);
    $vendortitle=mysql_real_escape_string($_POST['vendortitle']);
    $vendorcontact=mysql_real_escape_string($_POST['vendorcontact']);
    $vendoremail=mysql_real_escape_string($_POST['vendoremail']);
	$vendoraddress=mysql_real_escape_string($_POST['vendoraddress']);
    $vendoraddress2=mysql_real_escape_string($_POST['vendoraddress2']);
    $vendorcity=mysql_real_escape_string($_POST['vendorcity']);
    $vendorcountry=mysql_real_escape_string($_POST['vendorcountry']);
    $vendorstate=mysql_real_escape_string($_POST['vendorstate']);
    $vendorzip=mysql_real_escape_string($_POST['vendorzip']);
    $vendorcurrency=mysql_real_escape_string($_POST['vendorcurrency']);
    $vendorfax=mysql_real_escape_string($_POST['vendorfax']);
    $vendortype= implode(',',($_POST['vendortype']));
	
	 $vendorterm=implode(',',($_POST['vendorterm']));
	 $vendorPaymentType=implode(',',($_POST['vendorPaymentType']));
	 $vendorDeliveryType=implode(',',($_POST['vendorDeliveryType']));
	
    $vendorwebsite=mysql_real_escape_string($_POST['vendorwebsite']);
    $vendordescription=mysql_real_escape_string($_POST['vendordescription']);
    $vendorphone=mysql_real_escape_string($_POST['vendorphone']);
    $vendorlast_by=mysql_real_escape_string($_POST['last_by']);
	$vendorlast_on=mysql_real_escape_string($_POST['last_on']);
    $vendorlast_datetime=date("Y-m-d H:i:s");
	$vendorcreated_on=mysql_real_escape_string($_POST['vendorcreatedon']);
    $vendorcreated_by=mysql_real_escape_string($_POST['vendorcreatedby']);
    $vendorcreated_datetime=mysql_real_escape_string($_POST['vendordatetime']);
	
	$location_link=mysql_real_escape_string($_POST['location_link']);
	
	$sql = "INSERT INTO `vendors` SET `name` = '{$vendorname}',`StorePoint_image` = '{$StorePoint_image}',`location_link` = '{$location_link}',  title='{$vendortitle}', `contact` = '{$vendorcontact}', `email`='{$vendoremail}',
			`address`='{$vendoraddress}', `address2`='{$vendoraddress2}', `city`='{$vendorcity}', `state`= '{$vendorstate}', 
			`zip`='{$vendorzip}', `country`='{$vendorcountry}', `phone`='{$vendorphone}', `fax`='{$vendorfax}',
			 `website`='{$vendorwebsite}', `currency_id`='{$vendorcurrency}', `description`='{$vendordescription}', 
			 `type` = '{$vendortype}', `payment_types` = '{$vendorPaymentType}', `delivery_types` = '{$vendorDeliveryType}',
			  `created_by`='Self', `created_on`='VendorPanel',
			  `created_date`=NOW(),  terms_types = '{$vendorterm}' ";
	mysql_query($sql) or die($sql);
    $StorePoint_vendor_id = mysql_insert_id(); 
	$vendor_ins =true;
	echo $StorePoint_vendor_id;
	
?>