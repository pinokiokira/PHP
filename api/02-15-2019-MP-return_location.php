
<?php
	include("init.php");
	include("class.Employee.php");
	include("class.EmployeeMaster.php");
	include("class.location.php");
	include("class.state.php");
	include("class.country.php");
 	try{
		$empmaster_id=mysql_real_escape_string($_REQUEST['client_id']);
		$statusWhr=mysql_real_escape_string($_POST['statuswhr']);
		if(isset($empmaster_id) && $empmaster_id>0)
		{
				$response['success'] = true;
				$response['code'] = 0;
				$employeeMaster = new EmployeeMaster();
				$employee = new Employee();
				$employeeMaster->find($empmaster_id);
				$empmaster_id = $employeeMaster->empmaster_id;
				$employee->setEmail($employeeMaster->email);
				$rows = $employee->fetchAll();
				$emp_id = $rows[0]['emp_id'];
				$last_name = $rows[0]['last_name'];
				$first_name = $rows[0]['first_name'];
				
				//fetch locations Associated With Employee
				$locationsAssociatedWithEmployee='';
				$location = new location();
				$state = new state();
				$country = new country();
				$sql="SELECT em.location_id, em.Status,em.id, loc.primary_type FROM employee_master_locations em
                                        INNER JOIN locations loc ON loc.id=em.location_id
                                    	WHERE em.empmaster_id='".$empmaster_id."' ";
				if($statusWhr=='Active'){
					$sql .= " AND loc.status='active' AND em.Status='Active'"; 
				}						
				$exe = mysql_query($sql);
				while($row = mysql_fetch_array($exe)){
					$location_id = $row['location_id'];
                                        $primary_type = $row['primary_type'];
					$location->find($location_id);
					$state->find($location->state);
					$country->find($location->country);
                                        
                                        
                                        
                                        $value=mysql_query("select name from location_types where id='".$primary_type."'");
                                        $type=mysql_fetch_assoc($value);
                                        $primarytypename=$type['name'];
                                        
                                        $file = API."images/".$location->image;
                                        if ($location->image!=""){

                                                $file_headers = @get_headers($file); 
                                                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
//                                                        $imagefile=API."panels/teampanel/images/primary-type/Default Primary Type - ".$primarytypename.".png";
                                                	$imagefile = API.'images/default_images/Default Primary Type - Restaurant.png';
												}
                                                else {
                                                        $imagefile=API."images/".$location->image; 
                                                } 
                                        }else{
//                                            $imagefile=API."panels/teampanel/images/primary-type/Default Primary Type - ".$primarytypename.".png";
                                        	$imagefile = API.'images/default_images/Default Primary Type - Restaurant.png';
										}
					$locationsAssociatedWithEmployee .= '<div class="locBox"><div class="img-box"><img style="float:left; margin-right: 10px; margin-top:8px;width:50px;height:50px"  src="'.$imagefile.'"></div>';
					$locationsAssociatedWithEmployee .= '<div class="side" style=" height:auto;" >';
					$locationsAssociatedWithEmployee .= 'Status: '.$row['Status'];
					$locationsAssociatedWithEmployee .= '<br>';
					$locationsAssociatedWithEmployee .= '<strong>'.$location->name.' ('.$location->id.')</strong>';
					$locationsAssociatedWithEmployee .= '<br>';
					/*$locationsAssociatedWithEmployee .= 'Employee: '.$first_name.' '.$last_name.' ('.$emp_id . "-".$row['id'].")";
					$locationsAssociatedWithEmployee .= '<br>';*/
					/*$locationsAssociatedWithEmployee .= '</div>';*/
                                        if ($location->address !=""){
                                            $locationsAssociatedWithEmployee .= $location->address;
                                        }
                                        if ($location->address !="" && $location->address2 !=""){
                                            $locationsAssociatedWithEmployee .= ', ';
                                        }
                                        if ($location->address2 !="" ){
                                            $locationsAssociatedWithEmployee .= $location->address2;
                                        }
                                        
					$locationsAssociatedWithEmployee .= '<br>';
                                        if ($location->city !=""){
                                            $locationsAssociatedWithEmployee .= $location->city;
                                        }
                                        if ($location->city !="" && $state->name !=""){
                                            $locationsAssociatedWithEmployee .= ', ';
                                        }
                                        if ($state->name !=""){
                                            $locationsAssociatedWithEmployee .= $state->name;
                                        }
                                        if ($location->zip !="" && $state->name !=""){
                                            $locationsAssociatedWithEmployee .= ', ';
                                        }
                                        if ($location->zip !=""){
                                            $locationsAssociatedWithEmployee .= $location->zip;
                                        }
                                        if ($location->zip !="" && $country->name !=""){
                                            $locationsAssociatedWithEmployee .= ', ';
                                        }
                                        if ($country->name !=""){
                                            $locationsAssociatedWithEmployee .= $country->name;
                                        }
                                        
					
					$locationsAssociatedWithEmployee .= '<br>';
					$locationsAssociatedWithEmployee .= 'Phone: '.$location->phone;
					$locationsAssociatedWithEmployee .= '<br>';
					$locationsAssociatedWithEmployee .= 'Created: '.$location->created_date;
					$locationsAssociatedWithEmployee .= '<br><br>';
					$locationsAssociatedWithEmployee .= '</div></div>';
				}
				$response['LocationsAssociatedWithEmployee'] = $locationsAssociatedWithEmployee;
				//fetch locations Linked With Employee
				$locationsLinkedWithEmployee='';
				$sql="SELECT em.location_id, em.status, em.emp_id, em.id, em.first_name, em.last_name, loc.primary_type FROM employees em
                                        INNER JOIN locations loc ON loc.id = em.location_id
                                    	WHERE em.email='".$employeeMaster->email."'"; 
						if($statusWhr=='Active'){
								//$sql .= " AND loc.status='active' AND em.status='A'"; 
							 }			
										
 							$sql .= " AND loc.status='active' AND  em.status='A'ORDER BY loc.name";
				$exe = mysql_query($sql);
				while($row = mysql_fetch_array($exe)){
					$status = $row['status']=='A'?'Active':'Inactive';
					$location_id = $row['location_id'];
                                        $primary_type = $row['primary_type'];
					$location->find($location_id);
					$state->find($location->state);
					$country->find($location->country);
                                        
                                        $value=mysql_query("select name from location_types where id='".$primary_type."'");
                                        $type=mysql_fetch_assoc($value);
                                        $primarytypename=$type['name'];
                                        
                                        $file = API."images/".$location->image;
                                        if ($location->image!=""){

                                                $file_headers = @get_headers($file); 
                                                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                                                       // $imagefile=API."panels/teampanel/images/primary-type/Default Primary Type - ".$primarytypename.".png";
                                                	$imagefile = API.'images/default_images/Default Primary Type - Restaurant.png';
												}
                                                else {
                                                        $imagefile=API."images/".$location->image; 
                                                } 
                                        }else{
                                          //  $imagefile=API."panels/clientpanel/images/primary-type/Default Primary Type - ".$primarytypename.".png";
									$imagefile = API.'images/default_images/Default Primary Type - Restaurant.png';
                                        }
                                        
					$locationsLinkedWithEmployee .= '<div class="locBox"><div class="img-box"><img style="float:left; margin-right:10px; margin-top:8px;width:50px;height:50px"    src="'.$imagefile.'"></div>';
					$locationsLinkedWithEmployee .= '<div class="side" style=" height:auto;" >';
					$locationsLinkedWithEmployee .= 'Status: '.$status;
					$locationsLinkedWithEmployee .= '<br>';
					$locationsLinkedWithEmployee .= '<strong>'.$location->name.' ('.$location->id.')</strong>';
					$locationsLinkedWithEmployee .= '<br>';
					$locationsLinkedWithEmployee .= "Employee: ".$row["first_name"]." ".$row["last_name"]." (ID: ".$row["emp_id"].")<br>";
					//$locationsLinkedWithEmployee .= "<br>".$sql."<br>";
					/*$locationsLinkedWithEmployee .= '</div>';*/
					//$locationsLinkedWithEmployee .= $location->address.', '.$location->address2;
					//$locationsLinkedWithEmployee .= '<br>';
					//$locationsLinkedWithEmployee .= $location->city.', '.$state->name.', '.$location->zip.', '.$country->name;
					if ($location->address !=""){
                                            $locationsLinkedWithEmployee .= $location->address;
                                        }
                                        if ($location->address !="" && $location->address2 !=""){
                                            $locationsLinkedWithEmployee .= ', ';
                                        }
                                        if ($location->address2 !="" ){
                                            $locationsLinkedWithEmployee .= $location->address2;
                                        }
                                        
					$locationsLinkedWithEmployee .= '<br>';
                                        if ($location->city !=""){
                                            $locationsLinkedWithEmployee .= $location->city;
                                        }
                                        if ($location->city !="" && $state->name !=""){
                                            $locationsLinkedWithEmployee .= ', ';
                                        }
                                        if ($state->name !=""){
                                            $locationsLinkedWithEmployee .= $state->name;
                                        }
                                        if ($location->zip !="" && $state->name !=""){
                                            $locationsLinkedWithEmployee .= ', ';
                                        }
                                        if ($location->zip !=""){
                                            $locationsLinkedWithEmployee .= $location->zip;
                                        }
                                        if ($location->zip !="" && $country->name !=""){
                                            $locationsLinkedWithEmployee .= ', ';
                                        }
                                        if ($country->name !=""){
                                            $locationsLinkedWithEmployee .= $country->name;
                                        }
					$locationsLinkedWithEmployee .= '<br>';
					$locationsLinkedWithEmployee .= 'Phone: '.$location->phone;
					$locationsLinkedWithEmployee .= '<br>';
					$locationsLinkedWithEmployee .= 'Created: '.$location->created_date;
					$locationsLinkedWithEmployee .= '<br><br>';
					$locationsLinkedWithEmployee .= '</div></div>';
				}
				$response['LocationsLinkedWithEmployee'] = $locationsLinkedWithEmployee;
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Invalid empmaster_id';
				$response['success'] = false;
				
			echo 'ok';
			}
			echo json_encode($response);
		
	}catch(Exception $e)
	{
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage()." when attempting the query ".$sql, 3,"error/error_log.log");
	}
	
?>