<?php 
	// config and whatnot
    $config = dirname(__FILE__) . '/config.php'; 
	require_once( "Hybrid/Auth.php" );
	//include('includes/application.config.php'); 
	$user_data = NULL;
	
	// try to get the user profile from an authenticated provider
	try{

		$hybridauth = new Hybrid_Auth( $config );

		// selected provider name 
		$provider = @ trim( strip_tags( $_GET["provider"] ) );

		// check if the user is currently connected to the selected provider
		if( !  $hybridauth->isConnectedWith( $provider ) ){ 
			// redirect him back to login page
			$error = "You are not connected to $provider or your session has expired";
		}
		
		 
		// call back the requested provider adapter instance (no need to use authenticate() as we already did on login page)
		$adapter = $hybridauth->getAdapter( $provider );
		
		// grab the user profile
		$user_data = $adapter->getUserProfile();
		
		if($user_data->birthDay!='') {			
			$dob = $user_data->birthYear.'-'.$user_data->birthMonth.'-'.$user_data->birthDay;	
			$dob = date('Y-m-d',strtotime($dob)); 
		} else {
			$dob = '0000-00-00';
		}
		
		if($user_data->gender !='') {	
		   	if($user_data->gender == 'Female' || $user_data->gender == 'female' || $user_data->gender == 'f' || $user_data->gender == 'F') {		
				$gender = 'F';
			}
			else{
				$gender = 'M';
			}
		} else {
			$gender = '';
		}		
		
		$created_date = date('Y-m-d h:i:s a', time());  

		$user_data->status = 'success';
		$user_data->providerID = $adapter->id;
		$user_data->UniqueId = $UniqueId;
	//	session_destroy(); 	
		
 
?>
		
		<script type="text/javascript">
			window.opener.updateValue('signup_first_name',"<?=$user_data->firstName?>");
			window.opener.updateValue('signup_last_name',"<?=$user_data->lastName?>");
			window.opener.updateValue('signup_email',"<?=$user_data->email?>");
			window.opener.updateValue('client_idsm',"<?=$user_data->identifier?>");
			window.opener.updateValue('name_title',"<?=$user_data->displayName?>");
			window.opener.updateValue('phonesm',"<?=$user_data->phone?>");
			window.opener.updateValue('smaddress',"<?=$user_data->address?>");
			window.opener.updateValue('countrysm',"<?=$user_data->country?>");
			window.opener.updateValue('smstate',"<?=$user_data->region?>");
			window.opener.updateValue('smcity',"<?=$user_data->city?>");
			window.opener.updateValue('smzip',"<?=$user_data->zip?>");
			window.opener.updateValue('smgender',"<?=$gender?>");
			window.opener.updateValue('smdob',"<?=$dob?>");
			window.opener.updateValue('smprofile_image',"<?=$user_data->photoURL?>"); 
			window.opener.updateValue('last_datetime',"<?=$created_date?>");
			window.opener.updateValue('provider',"<?=$provider?>");
			window.opener.updateValue('access_token',"<?=$user_data->access_token?>");
                        window.opener.DoSMlink('link');
	//		window.opener.validateForm();
			window.close();
		</script> 

<?php 	
		
    }
	catch( Exception $e ) {
		// In case we have errors 6 or 7, then we have to use Hybrid_Provider_Adapter::logout() to 
		// let hybridauth forget all about the user so we can try to authenticate again.
		// Display the recived error, 
		// to know more please refer to Exceptions handling section on the userguide
		switch( $e->getCode() ) {
			case 0 : $error =  "Unspecified error."; break;
			case 1 : $error =  "Hybriauth configuration error."; break;
			case 2 : $error =  "Provider not properly configured."; break;
			case 3 : $error =  "Unknown or disabled provider."; break;
			case 4 : $error =  "Missing provider application credentials."; break;
			case 5 : $error =  "Authentication failed. ". "The user has canceled the authentication or the provider refused the connection."; 
			case 6 : $error =  "User profile request failed. Most likely the user is not connected ". "to the provider and he should to authenticate again."; 
				   $adapter->logout(); 
				   break;
			case 7 : echo "User not connected to the provider."; 
				   $adapter->logout();
				   break;
		}
		$data->status = 'error';
		$data->msg = $error;
		echo json_encode($data);
	}
?>

	
		