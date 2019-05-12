<?php
ob_start("ob_gzhandler");

 
$token=md5('loc=' . $_GET['loc'] . '&list=' . $_GET['list'] . 'backofficesecure12');

if($token == $_GET['token2'] && $_GET['loc'] != ''){ 
    $return_database = 0;//stop db from echoing db out
    require_once($_SERVER['DOCUMENT_ROOT']."/Panels/BusinessPanel/config/accessConfig.php");

    $lists = array();
    $details = array();

    $day_str = array('mon','tue','wed','thr','fri','sat','sun');

    $location_id = mysql_real_escape_string($_GET['loc']);
	$dept = base64_decode( $_GET['dept'] );
	
	if($dept!= ""){
	
	$query1 = "SELECT *
               FROM location_checklist
               WHERE location_id='".$location_id."' AND department = '" . $dept . "'";
	}
	else{
	
	$query1 = "SELECT *
               FROM location_checklist
               WHERE location_id=" . $location_id;
	
	}
		   
    $result1 = mysql_query($query1) or die(mysql_error());
    while($row1 = mysql_fetch_assoc($result1)){
        $days = array();
        //parse results to get days in a comma separated string
        foreach($day_str as $day){
            if($row1['dow_' . $day] == 'Yes'){
                $days[] = ucfirst($day);
            }
            unset($row1['dow_' . $day]);
        }
        $row1['days'] = implode(',',$days);
        $row1['starttime'] = substr($row1['starttime'],0,5);
        $row1['endtime'] = substr($row1['endtime'],0,5);
        $lists[] = $row1;
    }

    if ($_GET['list'] != '') {
        $list = mysql_real_escape_string($_GET['list']);

        $query2 = "SELECT *
                   FROM location_checklist_details
                   WHERE checklist_id='" . $list . "' AND location_id = '".$location_id."'
                   ORDER BY priority ASC";
        $result2 = mysql_query($query2) or die(mysql_error());
        while($row2 = mysql_fetch_assoc($result2)){
            $details[] = $row2;
        }
    }

    $response = array(
        'status' => 'success',
        'response' => array(
            'lists' => $lists,
            'details' => $details
        )
    );
    echo json_encode($response);

}else{
    $response = array(
        'status' => 'fail',
        'response' => array(
            'lists' => '',
            'details' => ''
        )
    );

    echo json_encode($response);
}