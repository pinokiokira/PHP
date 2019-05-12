<?php
ob_start("ob_gzhandler");
$token=md5('loc=' . $_GET['loc'] . '&list=' . $_GET['list'] . 'backofficesecure12');

if($token == $_GET['token1']){
   // require_once("../../includes/connectdb.php");
 require_once($_SERVER['DOCUMENT_ROOT']."/Panels/BusinessPanel/config/accessConfig.php");
    $location_id = intval(mysql_real_escape_string($_GET['loc']));
    $list = intval(mysql_real_escape_string($_GET['list']));

    $checklist = array();

    $query = "SELECT *
              FROM location_checklist
              WHERE location_id=$location_id AND checklist_id='$list'
              LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $row['starttime'] = substr($row['starttime'],0,5);
        $row['endtime'] = substr($row['endtime'],0,5);
        $checklist[] = $row;
    }
    echo json_encode(array(
        'status' => 'success',
        'response' => $checklist
    ));

}else{
    echo json_encode(array(
        'status' => 'fail',
        'response' => ''
    ));
}
?>