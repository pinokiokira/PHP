<?php 
require_once 'require/security.php';
require_once 'config/accessConfig.php';
/*
$old_image = ($_REQUEST['oldimage'] != '') ? $_REQUEST['oldimage'] : '';

if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
    $date = date("m-d-y-H-i-s");
    $filename = md5("attendance" . rand(999,9999)) . "-" . $date . ".jpg";

    $upload_to_temp = "temp_img/" . $filename;
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_to_temp)) {
        $ftp_path = "employee_master_images/" . $filename;
        $ftphost = FTPDOMAIN;
        $ftpusr = FTPUSER;
        $ftppwd = FTPPASSWORD;
        if (file_exists($upload_to_temp)) {
            $conn_id = ftp_connect($ftphost) or die("Couldn't connect to $ftphost");
            $login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
            if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
                unlink($upload_to_temp);
                $image_up = $ftp_path;
                if ($old_image != '') {
                   // $old_image = str_replace(API."images/","",$old_image);
                    ftp_delete($conn_id, $old_image);
                }
                mysql_query("UPDATE employees_master SET image = '".$ftp_path."' WHERE empmaster_id = '".$_SESSION["client_id"]."'");
                $_SESSION['image'] = $ftp_path;
            }else{
                echo "Error transferring file via ftp!<br/>";
                echo $filename . "<br/>";
                echo $upload_to_temp . "<br/>";
                echo $ftp_path . "<br/>";
            }
        }else{
            echo "File does not exist in img/temp!";
        }
    }else{
        echo "Error moving file to temp location!<br>";
    //    echo "XXX" . $_FILES['image']['error'];
    }
}
*/
$baseUploadfileName = basename($_FILES['images']['name']);
$uploadfileNameArr = explode(".", $baseUploadfileName);
$filename = md5("attendance" . rand(999,9999)) . "-" . ".".$uploadfileNameArr[count($uploadfileNameArr)-1];
if(move_uploaded_file( $_FILES["images"]["tmp_name"], "temp_img/" . $filename)){


function clean($var){
	$specials = array(' ','!','@','#','$','%','^','&','(',')','_','+','`','~',',',';',"'",']','[','}','{');
	$cleaned = strtolower($var);
	$cleaned = str_replace($specials,'-',$cleaned);
	$cleaned = str_replace('--------------------','-',$cleaned);
	$cleaned = str_replace('-------------------','-',$cleaned);
	$cleaned = str_replace('------------------','-',$cleaned);
	$cleaned = str_replace('-----------------','-',$cleaned);
	$cleaned = str_replace('----------------','-',$cleaned);
	$cleaned = str_replace('---------------','-',$cleaned);
	$cleaned = str_replace('--------------','-',$cleaned);
	$cleaned = str_replace('-------------','-',$cleaned);
	$cleaned = str_replace('------------','-',$cleaned);
	$cleaned = str_replace('-----------','-',$cleaned);
	$cleaned = str_replace('----------','-',$cleaned);
	$cleaned = str_replace('---------','-',$cleaned);
	$cleaned = str_replace('--------','-',$cleaned);
	$cleaned = str_replace('-------','-',$cleaned);
	$cleaned = str_replace('------','-',$cleaned);
	$cleaned = str_replace('-----','-',$cleaned);
	$cleaned = str_replace('----','-',$cleaned);
	$cleaned = str_replace('---','-',$cleaned);
	$cleaned = str_replace('--','-',$cleaned);
	$cleaned = str_replace('-','-',$cleaned);
	return $cleaned;
	}



$digital_image_name = $filename;
$digital_image_delete = "N";

if($digital_image_name != '')
{	

	$target_path = "temp_img/";
	$file_with_path = $digital_image_name;
	$target_path = $target_path . $file_with_path;
	 $ftphost = FTPDOMAIN;
        $ftpusr = FTPUSER;
        $ftppwd = FTPPASSWORD;

	
		$target_ftp_path = "employee_master_images/";
		$ftp_path = $target_ftp_path . $file_with_path;
		
		$local_file = $target_path;
		$conn_id = ftp_connect($ftphost, 21);
		ftp_login($conn_id, $ftpusr, $ftppwd);
		ftp_pasv ($conn_id, true);
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
		ftp_close($conn_id);
		mysql_query("UPDATE employees_master SET image = '".$ftp_path."' WHERE empmaster_id = '".$_SESSION["client_id"]."'");
        $_SESSION['image'] = $ftp_path;
		//unlink($target_path);
		//unlink($target_path);
		 
}

echo $ftp_path;
//header('Location: editprofile.php');
}else{
	echo "Error Uploading File!";
	exit;
}

?>