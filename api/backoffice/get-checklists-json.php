<?php
//Returns json string of checklist id and name for a location
//Should be used for populating dropdowns

$token=md5('loc=' . $_GET['loc'] . 'backofficesecure12');
if($token == $_GET['token2']){
    ob_start("ob_gzhandler");
    require_once("../../includes/connectdb.php");

    $checklists = array();

    $location_id = intval(mysql_real_escape_string($_GET['loc']));

    $query = "SELECT checklist_id,checklist_name
              FROM location_checklist
              WHERE location_id=$location_id AND status='Active'";
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $checklists[] = $row;
    }
    echo json_encode(array(
        'status' => 'success',
        'response' => $checklists
    ));

}else{
    echo json_encode(array(
        'status' => 'fail',
        'response' => ''
    ));
}
?>