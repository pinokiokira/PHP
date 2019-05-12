<?php
ob_start("ob_gzhandler");
session_start();
/*if ($_SESSION['bouser'] == "") {
    header("Location: index.php");
}*/
include_once("../../internalaccess/connectdb.php"); 
$group = mysql_real_escape_string($_GET['group']);
$vendor = mysql_real_escape_string($_GET['vendor']);
if($group != '' && $vendor != ''){
    $response = array();
    $query = "SELECT vi.id,ii.description
              FROM vendor_items vi
              INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
              WHERE vi.vendor_id = " . $vendor . " AND ii.inv_group_id=" . $group;
    $result = mysql_query($query) or die(mysql_error());
    while($row = mysql_fetch_assoc($result)){
        $response[] = $row;
    }
    echo json_encode($response);
}
?>