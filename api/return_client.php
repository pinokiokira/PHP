<?
	include("init.php");
        include_once '../../../internalaccess/url.php';
	include("class.EmployeeMaster.php");
        
	try{
		$empmaster_id=mysql_real_escape_string($_POST['client_id']);
		if(isset($empmaster_id) && $empmaster_id>0)
		{
				$response['success'] = true;
				$response['code'] = 0;
				$employee = new EmployeeMaster();
				$employee->find($empmaster_id);
				$response['id'] = $employee->empmaster_id;
				$response['email'] = $employee->email;
				$response['salutation'] = $employee->salutation;
				$response['first_name'] = $employee->first_name;
				$response['last_name'] = $employee->last_name;
				$response['sex'] = $employee->sex;
				$response['telephone'] = $employee->telephone;
				$response['fax'] = $employee->fax;
				$response['mobile'] = $employee->mobile;
				$response['address'] = $employee->address;
				$response['address2'] = $employee->address2;
				$response['city'] = $employee->city;
				$response['state'] = $employee->state;
				$response['zip'] = $employee->zip;
				$response['region'] = $employee->region;
				$response['neighborhood'] = $employee->neighborhood;
				$response['country'] = $employee->country;
				$response['currency'] = $employee->currency;
				$response['dob'] = $employee->dob;
                                if (getimagesize(API."images/".$employee->image) !== false) {
				$response['image'] = $employee->image;
                                }else $response['image'] = "";
				$response['resume'] = $employee->resume;
				$response['activities'] = $employee->activities;
				$response['competencies'] = $employee->competencies;
				
				$response['previousjob1_company'] = $employee->previousjob1_company;
				$response['previousjob1_title'] = $employee->previousjob1_title;
				$response['previousjob1_location'] = $employee->previousjob1_location;
				$response['previousjob1_startdate'] = $employee->previousjob1_startdate;
				$response['previousjob1_enddate'] = $employee->previousjob1_enddate;
				$response['previousjob1_description'] = $employee->previousjob1_description;
				$response['previousjob2_company'] = $employee->previousjob2_company;
				$response['previousjob2_title'] = $employee->previousjob2_title;
				$response['previousjob2_location'] = $employee->previousjob2_location;
				$response['previousjob2_startdate'] = $employee->previousjob2_startdate;
				$response['previousjob2_enddate'] = $employee->previousjob2_enddate;
				$response['previousjob2_description'] = $employee->previousjob2_description;
				$response['previousjob3_company'] = $employee->previousjob3_company;
				$response['previousjob3_title'] = $employee->previousjob3_title;
				$response['previousjob3_location'] = $employee->previousjob3_location;
				$response['previousjob3_startdate'] = $employee->previousjob3_startdate;
				$response['previousjob3_enddate'] = $employee->previousjob3_enddate;
				$response['previousjob3_description'] = $employee->previousjob3_description;
				
				$response['education'] = $employee->education;
				$response['languages'] = preg_split('/\r\n?/',$employee->languages);
				
				$response['viewable'] = $employee->viewable;
				$response['employment_type'] = $employee->employment_type;
				$response['emp_position1'] = $employee->emp_position1;
				$response['emp_position2'] = $employee->emp_position2;
				$response['emp_position3'] = $employee->emp_position3;
				$response['facebook_id'] = $employee->facebook_id;
				$response['google_id'] = $employee->google_id;
				$response['linkedin_id'] = $employee->linkedin_id;
                                $response['twitter'] = $employee->twitter_id;
                                $response['facebook_status'] = $employee->facebook_status;
				$response['google_status'] = $employee->google_status;
				$response['linkedin_status'] = $employee->linkedin_status;
                                $response['twitter_status'] = $employee->twitter_status;
                                
                                if (getimagesize(API."images/".$employee->profile_image) !== false) {
				$response['profile_image'] = $employee->profile_image;
                                }else $response['profile_image'] = "";
                                //$response['profile_image'] = $employee->profile_image;
                                
                                if (getimagesize(API."images/".$employee->google_image) !== false) {
				$response['google_image'] = $employee->google_image;
                                }else $response['google_image'] = "";
                               // $response['google_image'] = $employee->google_image;
                                
                                if (getimagesize(API."images/".$employee->linkedin_image) !== false) {
				$response['linkedin_image'] = $employee->linkedin_image;
                                }else $response['linkedin_image'] = "";
                              //  $response['linkedin_image'] = $employee->linkedin_image;
                                
                                if (getimagesize(API."images/".$employee->twitter_image) !== false) {
				$response['twitter_image'] = $employee->twitter_image;
                                }else $response['twitter_image'] = "";
                               // $response['twitter_image'] = $employee->twitter_image;
				
				
				$response['StorePoint'] = $employee->StorePoint;
				$response['ChefedIN'] = $employee->ChefedIN;
				$response['StylistFN'] = $employee->StylistFN;
				$response['DeliveryPoint'] = $employee->DeliveryPoint;
				$response['Delivery_activated_datetime'] = $employee->Delivery_activated_datetime;
				$response['Delivery_trasporation'] = $employee->Delivery_trasporation;
				$response['Delivery_payment_method'] = $employee->Delivery_payment_method;
                                $response['StorePoint_vendor_Id'] = $employee->StorePoint_vendor_Id;
                                $response['ChefedIN_Introduction'] = $employee->ChefedIN_Introduction;
                                $response['ChefedIN_Services'] = $employee->ChefedIN_Services;
                                $response['ChefedIN_experience'] = $employee->ChefedIN_experience;
                                $response['ChefedIN_market'] = $employee->ChefedIN_market;
                                $response['ChefedIN_website'] = $employee->ChefedIN_website;
                                $response['ChefedIN_reference'] = $employee->ChefedIN_reference;
                                $response['Chefedin_Business_Name'] = $employee->ChefedIN_Business_Name;
                                
                                $response['StylistFN_Company'] = $employee->StylistFN_Company;
                                $response['StylistFN_Description'] = $employee->StylistFN_Description;
                                $response['StylistFN_Style'] = $employee->StylistFN_Style;
                                $response['StylistFN_Located'] = $employee->StylistFN_Located;
                                $response['StylistFN_location_id'] = $employee->StylistFN_location_id;
								$response['ChefedIN_image'] = $employee->ChefedIN_image;                              
                                $response['ChefedIN_rate'] = $employee->ChefedIN_rate;
				$response['created_by'] = $employee->created_by;
				$response['created_on'] = $employee->created_on;
				$response['created_datetime'] = $employee->created_datetime;
				$response['last_by'] = $employee->last_by;
				$response['last_on'] = $employee->last_on;
				$response['last_datetime'] = $employee->last_datetime;
				
				
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Invalid empmaster_id';
				$response['success'] = false;
				
			}
			echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>