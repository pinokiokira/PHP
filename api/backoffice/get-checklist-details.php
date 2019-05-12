<?php
$token=md5('loc=' . $_GET['loc'] . '&detail=' . $_GET['detail'] . 'backofficesecure12');
if($token == $_GET['token2']){
    ob_start("ob_gzhandler");
    require_once("../../includes/connectdb.php");

    $details = array();

    $location_id = intval(mysql_real_escape_string($_GET['loc']));
    $detail = intval(mysql_real_escape_string($_GET['detail']));

    $query = "SELECT lcd.*,lc.checklist_name
              FROM location_checklist_details lcd
              INNER JOIN location_checklist lc ON lc.checklist_id=lcd.checklist_id
              WHERE lcd.location_id=$location_id AND checklistdetails_id=$detail
              LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $details[] = $row;
    }
    echo json_encode(array(
        'status' => 'success',
        'response' => $details
    ));

}else{
    echo json_encode(array(
        'status' => 'fail',
        'response' => ''
    ));
}
?>