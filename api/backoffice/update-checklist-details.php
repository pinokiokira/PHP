<?php
$token = md5(http_build_query($_POST) . 'backofficesecure12');

if($token == $_GET['token2'] && $_POST['id'] != ''){
    require_once("../../includes/connectdb.php");

    $id = intval(mysql_real_escape_string($_POST['id']));

    $status = mysql_real_escape_string($_POST['status']);
    $description = mysql_real_escape_string($_POST['description']);
    $instructions = mysql_real_escape_string($_POST['instructions']);
    $priority = mysql_real_escape_string($_POST['priority']);
    $type = mysql_real_escape_string($_POST['type']);
    $required = mysql_real_escape_string($_POST['required']);
	if($required==""){
		$required = 'No';
	}

    $query = "UPDATE location_checklist_details
              SET status='" . $status . "',
                  description='" . $description . "',
                  instructions='" . $instructions . "',
                  priority='" . $priority . "',
                  type='" . $type . "',
                  required='" . $required . "'
              WHERE checklistdetails_id=" . $id;
    $result = mysql_query($query) or die(mysql_error());

    echo json_encode(array(
        'status' => 'success',
        'list' => '',
        'detail' => ''
    ));

}else{
    echo json_encode(array(
        'status' => 'fail',
        'list' => '',
        'detail' => ''
    ));

}