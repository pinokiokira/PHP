<?php
ob_start("ob_gzhandler");
session_start();
/*if ($_SESSION['bouser'] == "") {
    header("Location: ../index.php");
}*/
if($_GET['s'] != ''){
    include_once("../../internalaccess/connectdb.php"); 

    $response = array();
    $str = mysql_real_escape_string($_GET['s']);

    $query = "SELECT ii.id, ii.description
              FROM inventory_items ii
              WHERE description LIKE '%" . $str . "%'
              LIMIT 10";
    $result = mysql_query($query) or die(mysql_error());

    while($row = mysql_fetch_assoc($result)){
        $response[] = $row;
    }

    echo json_encode($response);
}
?>