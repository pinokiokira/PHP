<?php
include '../config/accessConfig.php';
try{
if(isset($_GET['url']) && $_GET['url']!='')
{
        $ch1= curl_init();
        $url = "api/generatetoken.php";
        curl_setopt($ch1,CURLOPT_URL, $url);
        curl_setopt($ch1,CURLOPT_RETURNTRANSFER,1);
        $result1 = curl_exec($ch1);
        curl_close($ch1);
        $token = $result1; 
if($_GET['url']=='login_process.php'){
	$url = API .'panels/teampanel/api/login_process.php';
}
elseif($_GET['url']=='signup_process'){
	$url = API .'panels/teampanel/api/signup_process.php?first_name='.$_POST['first_name'].'&last_name='.$_POST['last_name'].'&email='.$_POST['email'].'&password='.$_POST['password'];
}
else if($_GET['url']=='forgot-password.php'){
	
	$email = $_POST['email'];
	unset($_POST);
	
	$_POST['email'] = $email;
        $url = DOMAIN . '/teampanel/ajax/forgot_password_process.php';
}
else if($_GET['url']=='upload_resume'){
	$old_resume = $_POST['old_resume'];
	if($_FILES["resume"]["error"] == 0){
	if (isset($_FILES['resume']['name']) && $_FILES['resume']['name'] != '') {
        $filename = clean(basename(md5(date("D M j G:i:s T Y") . rand(1, 999999999999)) . '-' . $_FILES['resume']['name']));
        $upload_to_temp = "../temp_img/" . $filename;
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_to_temp)) {
            $ftp_path = "employee_master_resume/" . $filename;
            $ftphost = FTPHOST;
            $ftpusr = FTPUSER;
            $ftppwd = FTPPWD;
            if (file_exists($upload_to_temp)) {
                $conn_id = ftp_connect($ftphost) or die("Couldn't connect to $ftphost");
                $login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
                if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
                    unlink($upload_to_temp);
                    $resume = API."images/" . $ftp_path;
                    if ($old_resume != '') {
						$resume_filename = basename($old_resume);
						$resume_path  = "employee_master_resume/" .$resume_filename;
                        ftp_delete($conn_id, $resume_path);
                    }
                }else{
                    echo $filename . "<br/>";
                    echo $upload_to_temp . "<br/>";
                    echo $ftp_path . "<br/>";
					throw new Exception( "Error transferring file via ftp!!");
                }
            }else{
                throw new Exception( "File does not exist in img/temp!");
            }
        }else{
            throw new Exception( "Error moving file from temp location!");
        }
    }
	}
	else
		throw new Exception( 'Please upload resume');
	$url = API . 'panels/teampanel/api/upload_resume.php';
	$client_id = $_POST['resume_client_id'];
	unset($_POST);
	$_POST['resume'] = $resume;
	$_POST['client_id'] = $client_id ;
	
}
else if($_GET['url']=='upload_profile_photo'){
	$old_image = $_POST['old_image'];
	if($_FILES["profile_photo"]["error"] == 0){
		
	if (isset($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['name'] != '') {
        $filename = clean(basename(md5(date("D M j G:i:s T Y") . rand(1, 999999999999)) . '-' . $_FILES['profile_photo']['name']));
        $upload_to_temp = "../temp_img/" . $filename;
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_to_temp)) {
            $ftp_path = "employee_master_images/" . $filename;
            $ftphost = FTPHOST;
            $ftpusr = FTPUSER;
            $ftppwd = FTPPWD;
            if (file_exists($upload_to_temp)) {
                $conn_id = ftp_connect($ftphost) or die("Couldn't connect to $ftphost");
                $login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
                if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
                    unlink($upload_to_temp);
                    $image = API."images/" . $ftp_path;
                    if ($old_image != '') {
						$image_filename = basename($old_image);
						$image_path  = "employee_master_images/" .$image_filename;
                        ftp_delete($conn_id, $image_path);
                    }
                }else{
                    echo $filename . "<br/>";
                    echo $upload_to_temp . "<br/>";
                    echo $ftp_path . "<br/>";
					throw new Exception( "Error transferring file via ftp!!");
                }
            }else{
                throw new Exception( "File does not exist in img/temp!");
            }
        }else{
            throw new Exception( "Error moving file from temp location!");
        }
    }
	
	}
	else
		throw new Exception( 'Please upload image');
	$url = API . 'panels/teampanel/api/upload_profile_photo.php';
	$client_id = $_POST['profile_photo_client_id'];
	$email = $_POST['email'];
	unset($_POST);
	$_POST['image'] = $image;
	$_POST['client_id'] = $client_id ;
	$_POST['email'] = $email;
}
else if($_GET['url']=='delete_profile_photo'){
	$url = API . 'panels/teampanel/api/remove_profile_photo.php';
	$_POST['image'] = base64_encode(file_get_contents('../images/default_avatar.png'));
}
else if($_GET['url']=='check_availability'){
	$url = API . 'panels/teampanel/api/check_availability.php';
}
else if($_GET['url']=='return_client.php'){
	$url = API . 'panels/teampanel/api/return_client.php';
}
else if($_GET['url']=='return_vendor.php'){
	$url = API . 'panels/teampanel/api/return_vendor.php';
}
else if($_GET['url']=='return_location.php'){
	$url = API . 'panels/teampanel/api/return_location.php?token='.$token.'&client_id='.$_POST['client_id'];
}
else if($_GET['url']=='update_client.php'){
	$url = API . 'panels/teampanel/api/update_client.php';
}
else if($_GET['url']=='return_countryandtype.php'){
	$url = API . 'panels/teampanel/api/return_countryandtype.php';
}
else if($_GET['url']=='return_states.php'){
	$url = API . 'panels/teampanel/api/return_states.php';
}
else if($_GET['url']=='return_currency.php'){
	$url = API . 'panels/teampanel/api/return_currency.php';
}
else if($_GET['url']=='client.php'){
	$url = API . 'panels/teampanel/api/client.php';
}
else if($_GET['url']=='update_clientSM.php'){
	$url = API . 'panels/teampanel/api/update_clientSM.php?token='.$token;
}
else {
	$url = API . $_GET['url'];
}

if(isset($_POST['dob'])){
	$_POST['dob'] = date('m/d/Y',strtotime($_POST['dob']));
}
if(isset($_POST['document_issue_date'])){
	$_POST['document_issue_date'] = date('m/d/Y',strtotime($_POST['document_issue_date']));
}

$action_url = $_GET['url'];
unset($_GET['url']);//remove url from get query string

$query = array();
foreach($_GET as $key => $val){//create query array of GET parameters
    $query[] = $key . '=' . $val;
}
if(count($query) > 0){//create query string and append to url if query array is not empty
    $query = '?' . implode('&',$query);
    $url = $url . $query;
}
//-------
#$fp = fopen('data.txt', 'a');
#fwrite($fp, http_build_query($_POST));
#fclose($fp);
//----------

$context = NULL;//set default context to null
if(count($_POST) > 0){ // if POST has been sent then build options with correct headers
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($_POST),
        ),
    );
	$context  = stream_context_create($options);//create stream context
}

$result = file_get_contents($url, false, $context);//send to api
//---------------------------------------------------------
echo $result;//echo api result
}
}
catch(Exception $e)
{
	//error_log("\r\n ".date('Y-m-d H:i:s')." Code : ".$e->getCode()." Message : ".$e->getMessage(), 3,"/error/error_log.log");
	echo $e->getMessage();
}
function clean($var) {
    $specials = array(' ', '!', '@', '#', '$', '%', '^', '&', '(', ')', '_', '+', '`', '~', ',', ';', "'", ']', '[', '}', '{');
    $cleaned = strtolower($var);
    $cleaned = str_replace($specials, '-', $cleaned);
    $cleaned = str_replace('--------------------', '-', $cleaned);
    $cleaned = str_replace('-------------------', '-', $cleaned);
    $cleaned = str_replace('------------------', '-', $cleaned);
    $cleaned = str_replace('-----------------', '-', $cleaned);
    $cleaned = str_replace('----------------', '-', $cleaned);
    $cleaned = str_replace('---------------', '-', $cleaned);
    $cleaned = str_replace('--------------', '-', $cleaned);
    $cleaned = str_replace('-------------', '-', $cleaned);
    $cleaned = str_replace('------------', '-', $cleaned);
    $cleaned = str_replace('-----------', '-', $cleaned);
    $cleaned = str_replace('----------', '-', $cleaned);
    $cleaned = str_replace('---------', '-', $cleaned);
    $cleaned = str_replace('--------', '-', $cleaned);
    $cleaned = str_replace('-------', '-', $cleaned);
    $cleaned = str_replace('------', '-', $cleaned);
    $cleaned = str_replace('-----', '-', $cleaned);
    $cleaned = str_replace('----', '-', $cleaned);
    $cleaned = str_replace('---', '-', $cleaned);
    $cleaned = str_replace('--', '-', $cleaned);
    $cleaned = str_replace('-', '-', $cleaned);
    return $cleaned;
}
?>