<?php
ob_start("ob_gzhandler");
session_start();
/*if ($_SESSION['bouser'] == "") {
    header("Location: ../index.php");
}*/
if($_GET['id'] != ''){
    include_once("../../internalaccess/connectdb.php"); 

    $id = intval(mysql_real_escape_string($_GET['id']));

    $query = "SELECT ii.*
              FROM inventory_items ii
              WHERE ii.id=$id
              LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    echo json_encode($row);
}
?>