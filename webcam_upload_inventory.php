<?php
include_once("config/accessConfig.php");
include_once 'includes/session.php'; 


$location = 'general_images';
if(isset($_GET['path']) && $_GET['path'] != ''){
    $location = $_GET['path'];
}
if($_GET['old_image'] != ''){
    $old_image = $_GET['old_image'];
}

$date = date("m-d-Y");
$filename = md5("attendance" . rand(999,9999)) . "_" . $date . ".jpg";

//$jpeg_data = file_get_contents('php://input');

$jpeg_data = $_POST['imgBase64'];
$jpeg_data = str_replace('data:image/png;base64,', '', $jpeg_data);
$jpeg_data = str_replace(' ', '+', $jpeg_data);
$jpeg_data = base64_decode($jpeg_data);

$result = file_put_contents( "temp_img/" . $filename, $jpeg_data );

if (!$result) {
    echo "error writing locally" . $filename;
    exit();
}

$local_path = "temp_img/" . $filename;
$ftp_path = $location . "/" . $filename;

$ftphost = FTPDOMAIN;
$ftpusr = FTPUSER;
$ftppwd = FTPPASSWORD;

 $conn_id = ftp_connect($ftphost,FTPPORT) or die("Couldn't connect to $ftphost");
$login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
ftp_pasv ($conn_id, FTPPASIVE);

// upload a file
if (ftp_put($conn_id, $ftp_path, $local_path, FTP_BINARY)) {
    unlink($local_path);
    echo $ftp_path;
    //delete old image
    if($old_image != ''){
        ftp_delete($conn_id, $old_image);
    }
}else{
    echo "error writing to ftp" . $ftp_path;
}
// close the connection
ftp_close($conn_id);
?>