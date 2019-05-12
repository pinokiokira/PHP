<?php
$token = md5(http_build_query($_POST) . 'backofficesecure12');

if($token == $_GET['token2'] && $_POST['checklist'] != ''){
    require_once("../../includes/connectdb.php");

    $location_id = intval(mysql_real_escape_string($_POST['location_id']));
    $checklist = mysql_real_escape_string($_POST['checklist']);
    $status = mysql_real_escape_string($_POST['status']);
    $description = mysql_real_escape_string($_POST['description']);
    $instructions = mysql_real_escape_string($_POST['instructions']);
    $priority = mysql_real_escape_string($_POST['priority']);
    $type = mysql_real_escape_string($_POST['type']);
    $required = mysql_real_escape_string($_POST['required']);
    $created_by = mysql_real_escape_string($_POST['created_by']);
    $created_on = mysql_real_escape_string($_POST['created_on']);
    $datetime = date('Y-m-d H:i:s');
	if($required==""){
		$required = 'No';
	}

    $query = "INSERT INTO location_checklist_details
              SET checklist_id='" . $checklist . "',
                  location_id='" . $location_id . "',
                  status='" . $status . "',
                  description='" . $description . "',
                  instructions='" . $instructions . "',
                  priority='" . $priority . "',
                  type='" . $type . "',
                  required='" . $required . "',
                  created_by='" . $created_by . "',
                  created_on='" . $created_on . "',
                  created_datetime='" . $datetime . "'";
    $result = mysql_query($query) or die(mysql_error());
    $detail = mysql_insert_id();

    echo json_encode(array(
        'status' => 'success',
        'list' => $checklist,
        'detail' => $detail
    ));

}else{
    echo json_encode(array(
        'status' => 'fail',
        'list' => '',
        'detail' => ''
    ));

}