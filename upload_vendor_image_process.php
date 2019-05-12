<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$baseUploadfileName = basename($_FILES['images']['name']);
$uploadfileNameArr = explode(".", $baseUploadfileName);
$filename = md5("attendance" . rand(999,9999)) . "-" . ".".$uploadfileNameArr[count($uploadfileNameArr)-1];
move_uploaded_file( $_FILES["images"]["tmp_name"], "temp_img/" . $filename);


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

	
		$target_ftp_path = "vendors/";
		$ftp_path = $target_ftp_path . $file_with_path;
		
		$local_file = $target_path;
		 $conn_id = ftp_connect($ftphost,FTPPORT) or die("Couldn't connect to $ftphost");
		$login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
		ftp_pasv ($conn_id, FTPPASIVE);
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
		ftp_close($conn_id);
		
		//unlink($target_path);
}

echo $filename;
?>



