<?php
$token = md5(http_build_query($_POST) . 'backofficesecure12');

if($token == $_GET['token2']){
    require_once("../../includes/connectdb.php");

    $location_id = intval(mysql_real_escape_string($_POST['location_id']));
    $status = mysql_real_escape_string($_POST['status']);
	$dept = mysql_real_escape_string($_POST['dept']);
    $checklist_name = mysql_real_escape_string($_POST['name']);
    $starttime = mysql_real_escape_string($_POST['starttime']);
    $endtime = mysql_real_escape_string($_POST['endtime']);
    $mon = mysql_real_escape_string($_POST['monday']);
    $tue = mysql_real_escape_string($_POST['tuesday']);
    $wed = mysql_real_escape_string($_POST['wednesday']);
    $thr = mysql_real_escape_string($_POST['thursday']);
    $fri = mysql_real_escape_string($_POST['friday']);
    $sat = mysql_real_escape_string($_POST['saturday']);
    $sun = mysql_real_escape_string($_POST['sunday']);
	$dow_mon_employe_id = mysql_real_escape_string($_POST['dow_mon_employe_id']);
    $dow_tue_employe_id = mysql_real_escape_string($_POST['dow_tue_employe_id']);
    $dow_wed_employe_id = mysql_real_escape_string($_POST['dow_wed_employe_id']);
    $dow_thr_employe_id = mysql_real_escape_string($_POST['dow_thr_employe_id']);
    $dow_fri_employe_id = mysql_real_escape_string($_POST['dow_fri_employe_id']);
    $dow_sat_employe_id = mysql_real_escape_string($_POST['dow_sat_employe_id']);
    $dow_sun_employe_id = mysql_real_escape_string($_POST['dow_sun_employe_id']);
	
	$defEmp = mysql_real_escape_string($_POST['default_emp']);
    $created_by = mysql_real_escape_string($_POST['created_by']);
    $created_on = mysql_real_escape_string($_POST['created_on']);
    $date = date('Y-m-d H:i:s');

    $query = "INSERT INTO location_checklist
              SET location_id='" . $location_id . "',
                  status='" . $status . "',
                  checklist_name='" . $checklist_name . "',
                  starttime='" . $starttime . "',
                  endtime='" . $endtime . "',
                  dow_mon='" . $mon . "',
                  dow_tue='" . $tue . "',
                  dow_wed='" . $wed . "',
                  dow_thr='" . $thr . "',
                  dow_fri='" . $fri . "',
                  dow_sat='" . $sat . "',
                  dow_sun='" . $sun . "',
				   dow_mon_employe_id='" . $dow_mon_employe_id . "',
                  dow_tue_employe_id='" . $dow_tue_employe_id . "',
                  dow_wed_employe_id='" . $dow_wed_employe_id . "',
                  dow_thr_employe_id='" . $dow_thr_employe_id . "',
                  dow_fri_employe_id='" . $dow_fri_employe_id . "',
                  dow_sat_employe_id='" . $dow_sat_employe_id . "',
                  dow_sun_employe_id='" . $dow_sun_employe_id . "',
				  department='". $dept ."',
				  employee_id='". $defEmp ."',
                  created_by='" . $created_by . "',
                  created_on='" . $created_on . "',
                  created_datetime='" . $date . "'";
    $result = mysql_query($query) or die(mysql_error());
    $list = mysql_insert_id();

    echo json_encode(array(
        'status' => 'success',
        'list' => $list
    ));
}else{
    echo json_encode(array(
        'status' => 'fail',
        'list' => ''
    ));
}
?>