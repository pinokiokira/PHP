<?
	ob_start("ob_gzhandler");
	include("init.php");
	include("class.EmployeeMaster.php");
	include_once("sendmail_smtp.php");
	
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}
	
	
	$business_name=mysql_real_escape_string($_POST['business_name']);
    $first_name=mysql_real_escape_string($_POST['first_name']);
	$last_name=mysql_real_escape_string($_POST['last_name']);
	$email=mysql_real_escape_string($_POST['email']);
	$password=mysql_real_escape_string($_POST['password']);
	$phone=mysql_real_escape_string($_POST['phone']);
        $address=mysql_real_escape_string($_POST['address']);
        $country=mysql_real_escape_string($_POST['country']);
        $state=mysql_real_escape_string($_POST['state']);
        $city=mysql_real_escape_string($_POST['city']);
        $zip=mysql_real_escape_string($_POST['zip']);
        $dob=mysql_real_escape_string($_POST['dob']);
        $profile_image=mysql_real_escape_string($_POST['profile_image']);
        $gender=mysql_real_escape_string($_POST['gender']);
		$site=mysql_real_escape_string($_POST['site']);
        $facebook=mysql_real_escape_string($_POST['facebook']);
        $facebook_id=mysql_real_escape_string($_POST['facebook_id']);
        $facebook_status=mysql_real_escape_string($_POST['facebook_status']);
        $linkedin_id=mysql_real_escape_string($_POST['linkedin_id']);
        $linkedin_image=mysql_real_escape_string($_POST['linkedin_image']);
        $linkedin_status=mysql_real_escape_string($_POST['linkedin_status']);
        $google_id=mysql_real_escape_string($_POST['google_id']);
        $google_image=mysql_real_escape_string($_POST['google_image']);
        $google_status=mysql_real_escape_string($_POST['google_status']);
       
								
	//$learntube=mysql_real_escape_string($_POST['learntube']);
	
	try{
		if($first_name=='')
		{
			$response['code'] = 2;
			$response[2] = 'First name can\'t be blank';
		}
		elseif($last_name=='')
		{
			$response['code'] = 3;
			$response[3] = 'Last name can\'t be blank';
		}
		elseif($email=='')
		{
			$response['code'] = 4;
			$response[4] = 'Email name can\'t be blank';
		}
		elseif($password=='')
		{
			$response['code'] = 5;
			$response[5] = 'Password name can\'t be blank';
		}        
		else
		{
			$employee = new EmployeeMaster();
			
			$employee->setFirstName($first_name);
			$employee->setLastName($last_name);
			$employee->setEmail($email);
			$employee->setPassword($password);
                        $employee->setBusName($business_name);
			
			if ($site=="staffpoint" )
			{	
				$employee->setStatus('N');
				$employee->setCreatedOn('StaffPoint Website');
				$employee->setSex('');
				$employee->setCreatedBy('Self');
				
				$employee->setStorePoint('Yes');
                                
				
			}
			else
			{
				if ($site=="learntube" )
				{
					$employee->setStatus('N');
					$employee->setCreatedOn('LearnTube Website');
					$employee->setSex('');
					$employee->setCreatedBy('Self');
				}
				elseif($site=="chefedin")
				{
					$employee->setStatus('N');
					$employee->setCreatedOn('ChefedIn Website');
					$employee->setSex('');
					$employee->setCreatedBy('Self');
					$employee->setChefedIN('Yes');
					$employee->setChefedIN_Business_Name($business_name);
					
					
				}
				elseif($site=="stylistfn")
				{
					$employee->setStatus('N');
					$employee->setCreatedOn('StylistFN Website');
					$employee->setSex('');
					$employee->setCreatedBy('Self');
					$employee->setStylistFN('Yes');
				}
				elseif($site=="softpointcloud")
				{
					$employee->setStatus('N');
					$employee->setCreatedOn('SoftPointCloud Website');
					$employee->setSex('');
					$employee->setCreatedBy('Self');
					
				}
				elseif($site=="storepoint")
				{
					$employee->setStatus('N');
					$employee->setCreatedOn('StorePoint Website');
					$employee->setSex('');
					$employee->setCreatedBy('Self');
					$employee->setStorePoint('Yes');
					
				}
				else
				{
					if ($site=="productsite" )
					{
						$employee->setStatus('N');
						$employee->setCreatedOn('ProductSite Website');
						$employee->setSex('');
						$employee->setCreatedBy('Self');
					}
					else
					{
						$employee->setStatus('I');	
						$employee->setCreatedOn('TeamPoint');
						$employee->setCreatedBy('Self');
						$employee->setPhone($phone);
						$employee->setAddress($address);
						$employee->setCountry($country);
						$employee->setCity($city);
						$employee->setState($state);
						$employee->setZip($zip);
						$employee->setSex($gender);
						if ($dob!=""){
							$employee->setDob(date("Y-m-d",strtotime($dob)));
						}
						$employee->setprofile_image($profile_image);
                                                $employee->setfacebook($facebook);
                                                $employee->setfacebook_id($facebook_id);
                                                $employee->setfacebook_status($facebook_status);
                                                $employee->setlinkedin_id($linkedin_id);
                                                $employee->setlinkedin_image($linkedin_image);
                                                $employee->setlinkedin_status($linkedin_status);
                                                $employee->setgoogle_id($google_id);
                                                $employee->setgoogle_image($google_image);
                                                $employee->setgoogle_status($google_status);
                                                
					}
				}
			}
			
			
			if($employee->isEmailExists($email))
			{
								//SendHTMLMail("bharatgami142@yahoo.com","subs of user","Content of user ","info@softpointcloud.com");
								$employee->save($id=0);
                                $sql = "SELECT empmaster_id FROM employees_master WHERE email='{$email}' AND password='{$password}'";
                                $result = mysql_query($sql);
                                $row = mysql_fetch_assoc($result);
                                $employees_master_id = $row["empmaster_id"];
								
								switch($site)
								{
									case "staffpoint":
										$subject = "SoftPoint Team Member Registration";
										$headers = 'MIME-Version: 1.0' . "\r\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
										$headers .= "From: info@softpointcloud.com \r\n";
										$fp = @fopen("../../../Emails/staffpoint-empreg.html", "r");
										$contents = fread($fp, filesize("../../../Emails/teampoint-empreg.html"));
										fclose($fp);
										$contents = str_replace("[emp.first_name]", $first_name, $contents);
										$contents = str_replace("[emp.last_name]", $last_name, $contents);
										$contents = str_replace("[emp.email]", $email, $contents);
										$contents = str_replace("[emp.password]", $password, $contents);
										$contents = str_replace("[token]", md5($employees_master_id), $contents);
										$contents = str_replace("[API]", API, $contents);
										$message = $contents . " \r\n";
										SendHTMLMail($email, $subject, $message, $headers);
									break;
									case "learntube":
										$subject = "SoftPoint Team Member Registration";
										$headers = 'MIME-Version: 1.0' . "\r\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
										$headers .= "From: info@softpointcloud.com \r\n";
										$fp = @fopen("../../../Emails/learntube-empreg.html", "r");
										$contents = fread($fp, filesize("../../../Emails/teampoint-empreg.html"));
										fclose($fp);
										$contents = str_replace("[emp.first_name]", $first_name, $contents);
										$contents = str_replace("[emp.last_name]", $last_name, $contents);
										$contents = str_replace("[emp.email]", $email, $contents);
										$contents = str_replace("[emp.password]", $password, $contents);
										$contents = str_replace("[token]", md5($employees_master_id), $contents);
										$contents = str_replace("[API]", API, $contents);
										$message = $contents . " \r\n";
										SendHTMLMail($email, $subject, $message, $headers);
									break;
									case "productsite":
										$subject = "SoftPoint Team Member Registration";
										$headers = 'MIME-Version: 1.0' . "\r\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
										$headers .= "From: info@softpointcloud.com \r\n";
										$fp = @fopen("../../../Emails/productsite-empreg.html", "r");
										$contents = fread($fp, filesize("../../../Emails/teampoint-empreg.html"));
										fclose($fp);
										$contents = str_replace("[emp.first_name]", $first_name, $contents);
										$contents = str_replace("[emp.last_name]", $last_name, $contents);
										$contents = str_replace("[emp.email]", $email, $contents);
										$contents = str_replace("[emp.password]", $password, $contents);
										$contents = str_replace("[token]", md5($employees_master_id), $contents);
										$contents = str_replace("[API]", API, $contents);
										$message = $contents . " \r\n";
										SendHTMLMail($email, $subject, $message, $headers);
									break;
									case "storepoint":
										$subject = "TeamPanel Registration";
										$headers = 'MIME-Version: 1.0' . "\r\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
										$headers .= "From: info@softpointcloud.com \r\n";
										$fp = @fopen("../../../Emails/storepoint-empreg.html", "r");
										$contents = fread($fp, filesize("../../../Emails/storepoint-empreg.html"));
										fclose($fp);
										$contents = str_replace("[emp.first_name]", $first_name, $contents);
										$contents = str_replace("[emp.last_name]", $last_name, $contents);
										$contents = str_replace("[emp.email]", $email, $contents);
										$contents = str_replace("[emp.password]", $password, $contents);
										$contents = str_replace("[token]", md5($employees_master_id), $contents);
										$contents = str_replace("[API]", API, $contents);
										$message = $contents . " \r\n";
										SendHTMLMail($email, $subject, $message, $headers);
									break;
									default:
										$subject = "VendorPanel Registration";
										$headers = 'MIME-Version: 1.0' . "\r\n";
										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
										$headers .= "From: info@softpointcloud.com \r\n";
										$fp = @fopen("../../../Emails/vendorpoint-empreg.html", "r");
										$contents = fread($fp, filesize("../../../Emails/vendorpoint-empreg.html"));
										fclose($fp);
										$contents = str_replace("[emp.first_name]", $first_name, $contents);
										$contents = str_replace("[emp.last_name]", $last_name, $contents);
										$contents = str_replace("[emp.email]", $email, $contents);
										$contents = str_replace("[emp.password]", $password, $contents);
										$contents = str_replace("[emp.master_id]", $employees_master_id, $contents);
										$contents = str_replace("[token]", md5($employees_master_id), $contents);
										$contents = str_replace("[API]", API, $contents);
										$message = $contents . " \r\n";
										//mail($email, $subject, $message, $headers);
										SendHTMLMail($email, $subject, $message, 'info@softpointcloud.com');
									break;
								}
				
				$response['success'] = true;
				$response['code'] = 0;
                                
			}
			else
			{
				$response['code'] = 1;
				$response[1] = 'Email already exists. If you forgot your Password, Select Forgot Password. If not, Use another email Address!';
				$response['success'] = false;
				
			}
		}
		echo json_encode($response);
	}catch(Exception $e)
	{
		$response['code'] = $e->getMessage();
		error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage(), 3,"error/error_log.log");
	}
	
?>