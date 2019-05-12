<?
	include("init.php");
        include_once '../../../internalaccess/url.php';
		include("class.EmployeeMaster.php");
	
		//echo '<br><pre>';
		//print_r($_POST);
		//echo '<br></pre>';
		//die();
	
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
    $vendortype=mysql_real_escape_string($_POST['vendortype']);
	
	 $vendorterm=mysql_real_escape_string($_POST['vendorterm']);	 
	 $vendorPaymentType=mysql_real_escape_string($_POST['vendorPaymentType']);
	 $vendorDeliveryType=mysql_real_escape_string($_POST['vendorDeliveryType']);
	
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
	
	//session_start();
    //$_SESSION['vendorstate'] = $vendorstate;
    //echo $_SESSION['vendorstate']."------";die;
	
	try{
		if(isset($empmaster_id) && $empmaster_id>0)
		{
			$list = explode('/',$dob);
			$month = $list[0];
			$day = $list[1];
			$year = $list[2];
			$dob = $year.'-'.$month.'-'.$day;
			
			//calculate under age
			
			$sql = "SELECT DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),'".$dob."')), '%Y')+0 AS age";
			$exe = mysql_query($sql);
			$row = mysql_fetch_array($exe);
			$age = $row['age'];
			$response['age'] = $age;
			if($age<18)
			{
				$response['underAge'] = 'You are under age. Additional information maybe required.';	
			}
				if($vendorcountry>0){
					$country_field = "`country`='{$vendorcountry}'";
				}else{
					$country_field ="`country`=NULL";
				}
				if($vendorstate>0){
					$state_field = "`state`='{$vendorstate}'";
				}else{
					$state_field = "`state`=NULL";
				}
			$vendor_ins = false;
            if ($StorePoint_vendor_id=="" && $StorePoint=="Yes" && $vendorname!=""){
                        if ($vendorcontact==""){
                            $vendorcontact = $vendorname;
                        }
						
                		$sql = "INSERT INTO `vendors` SET `name` = '{$vendorname}',`StorePoint_image` = '{$StorePoint_image}',`location_link` = '{$location_link}',  title='{$vendortitle}', `contact` = '{$vendorcontact}', `email`='{$vendoremail}',
                        `address`='{$vendoraddress}', `address2`='{$vendoraddress2}', `city`='{$vendorcity}', $state_field, 
                        `zip`='{$vendorzip}', $country_field, `phone`='{$vendorphone}', `fax`='{$vendorfax}',
                         `website`='{$vendorwebsite}', `currency_id`='{$vendorcurrency}', `description`='{$vendordescription}', 
                         `type` = '{$vendortype}', `payment_types` = '{$vendorPaymentType}', `delivery_types` = '{$vendorDeliveryType}',
						  `created_by`='Self', `created_on`='VendorPanel',
                          `created_date`=NOW(),  terms_types = '{$vendorterm}' ";
					//$response['sql1'] =  $sql;
				mysql_query($sql);
                      $StorePoint_vendor_id = mysql_insert_id(); 
					  $vendor_ins =true;
					  /*$sqlv = "INSERT INTO `vendor_terms` SET `vendor_id` = '{$StorePoint_vendor_id}', `terms` = '{$vendorterm}'";
					  mysql_query($sqlv) or die(mysql_error());*/
					         
				} else if ($StorePoint_vendor_id!="" && $StorePoint_vendor_id!=0){
					$check_vendor ="SELECT storepoint_vendor_id FROM employees_master WHERE empmaster_id = '".$empmaster_id."' AND storepoint_vendor_id <>'' && storepoint_vendor_id IS NOT NULL ";
					
					$res_empmaster = mysql_query($check_vendor);
					if(mysql_num_rows()==0){
						$vendor_ins =true;
					}
				
                   $sql = "UPDATE `vendors` SET `name` = '{$vendorname}',`StorePoint_image` = '{$StorePoint_image}',`location_link` = '{$location_link}', title='{$vendortitle}', `contact` = '{$vendorcontact}', `email`='{$vendoremail}',
                        `address`='{$vendoraddress}', `address2`='{$vendoraddress2}', `city`='{$vendorcity}', $state_field, 
                        `zip`='{$vendorzip}', $country_field, `phone`='{$vendorphone}', `fax`='{$vendorfax}',
                         `website`='{$vendorwebsite}', `currency_id`='{$vendorcurrency}', `description`='{$vendordescription}', 
                         `type` = '{$vendortype}', `payment_types` = '{$vendorPaymentType}', `delivery_types` = '{$vendorDeliveryType}',
                          `last_by`='{$last_by}', `last_on`='{$vendorlast_on}', 
                          `last_datetime`='{$vendorlast_datetime}',terms_types = '{$vendorterm}' WHERE id={$StorePoint_vendor_id}";
					
							mysql_query($sql);    
							
							/* $sqlv = "INSERT INTO `vendor_terms` SET `vendor_id` = '{$StorePoint_vendor_id}', `terms` = '{$vendorterm}'";
					 		 mysql_query($sqlv) or die(mysql_error());*/
				}  
				if($StorePoint_vendor_id!=""){
				$response['StorePoint_vendor_Id'] = $StorePoint_vendor_id;
				$response['vendor_ins'] = $vendor_ins;				
				if($vendorterm!=""){
							$terms = explode(',',$vendorterm);							
							foreach($terms as $term){
								
															
								 $teq =mysql_query("SELECT * from vendor_terms WHERE id ='".$term."' AND  vendor_id<>'".$StorePoint_vendor_id."'");
								if(mysql_num_rows($teq)>0){
								$resq = mysql_fetch_array($teq);
								 $insq = mysql_query("INSERT INTO vendor_terms SET 
										terms ='".$resq['terms']."',
										vendor_id ='".$StorePoint_vendor_id."'");								
								}
							}
							
						}
				
				}
                
			$employee = new EmployeeMaster();			
			$employee->find($empmaster_id);
			$employee->setSalutation($salutation);
			$employee->setFirstName($first_name);
			$employee->setLastName($last_name);
			$employee->setAddress($address);
			$employee->setAddress2($address2);
			$employee->setCountry($country);
			$employee->setCurrency($currency);
			$employee->setCity($city);
			$employee->setState($state);
			$employee->setZip($zip);
			$employee->setRegion($region);
			$employee->setNeighborhood($neighborhood);
			$employee->setTelephone($telephone);
			$employee->setFax($fax);
			$employee->setMobile($mobile);
			$employee->setSex($sex);
			$employee->setDob($dob);
			$employee->setViewable($viewable);
			$employee->setActivities($activities);
			$employee->setCompetencies($competencies);
			
			$employee->setPrevious_Job_1_Company($Previous_Job_1_Company);
			$employee->setPrevious_Job_1_Title($Previous_Job_1_Title);
			$employee->setPrevious_Job_1_Location($Previous_Job_1_Location);
			$employee->setPrevious_Job_1_Startdate($Previous_Job_1_Startdate);
			$employee->setPrevious_Job_1_Enddate($Previous_Job_1_Enddate);
			$employee->setPrevious_Job_1_Description($Previous_Job_1_Description);
			$employee->setPrevious_Job_2_Company($Previous_Job_2_Company);
			$employee->setPrevious_Job_2_Title($Previous_Job_2_Title);
			$employee->setPrevious_Job_2_Location($Previous_Job_2_Location);
			$employee->setPrevious_Job_2_Startdate($Previous_Job_2_Startdate);
			$employee->setPrevious_Job_2_Enddate($Previous_Job_2_Enddate);
			$employee->setPrevious_Job_2_Description($Previous_Job_2_Description);
			$employee->setPrevious_Job_3_Company($Previous_Job_3_Company);
			$employee->setPrevious_Job_3_Title($Previous_Job_3_Title);
			$employee->setPrevious_Job_3_Location($Previous_Job_3_Location);
			$employee->setPrevious_Job_3_Startdate($Previous_Job_3_Startdate);
			$employee->setPrevious_Job_3_Enddate($Previous_Job_3_Enddate);
			$employee->setPrevious_Job_3_Description($Previous_Job_3_Description);
			
			
			$employee->setEducation($education);
			$employee->setLanguages($languages);
			$employee->setEmploymentType($employment_type);
			$employee->setEmpPosition1($emp_position1);
			$employee->setEmpPosition2($emp_position2);
			$employee->setEmpPosition3($emp_position3);
			
			$employee->setStorePoint($StorePoint);
			$employee->setChefedIN($ChefedIN);
			$employee->setStylistFN($StylistFN);
			$employee->setDeliveryPoint($DeliveryPoint);
			
			$employee->setDelivery_trasporation($Delivery_trasporation);
			$employee->setDelivery_payment_method($Delivery_payment_method);
                        
                        $employee->setChefedIN_rate($rate);
						$employee->setChefedIN_image($ChefedIN_image);
                        $employee->setChefedIN_reference($references);
                        $employee->setChefedIN_website($website);
                        $employee->setChefedIN_market($chefedin_market);
                        $employee->setChefedIN_experience($experience);
                        $employee->setChefedIN_Services($services);
						
						
						
						
                        $employee->setStorePoint_vendor_Id($StorePoint_vendor_id);
                        $employee->setChefedIN_Introduction($introduction);
                        $employee->setChefedIN_Business_Name($Chefedin_Business_Name);
                        $employee->setStylistFN_Company($StylistFN_Company);
                        $employee->setStylistFN_Description($StylistFN_Description);
                        $employee->setStylistFN_Style($StylistFN_Style);
                        $employee->setStylistFN_Located($StylistFN_Located);
                        $employee->setStylistFN_location_id($StylistFN_location_id);
                        
                	$employee->setLast_by($last_by);
			$employee->setLast_on($last_on);
			
			if($employee->save($empmaster_id))
			{
				
                $response['success'] = true;
				$response['code'] = 0;
				
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Error in data save!';
				$response['success'] = false;
				
			}
			
			echo json_encode($response);
		}
		else{
				$response['code'] = 1;
				$response[1] = 'Invalid empmaster_id.';
				$response['success'] = false;
				echo json_encode($response);
		}	
	}catch(Exception $e)
	{
		
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>