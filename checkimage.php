<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

$type = $_GET['t'];
$item = mysql_real_escape_string($_GET['item']);
/*$blur_type = $_GET['type'];*/
if($type == 'g' && $item != 'add_new_item'){
    $query = "SELECT local_item_image FROM location_inventory_items WHERE inv_item_id = $item AND location_id=" . $_SESSION['loc'];
    $result = mysql_query($query) or die(mysql_error());
	$num_row = mysql_num_rows($result);
	$row=mysql_fetch_array($result);
	echo $row['local_item_image'];
    if($row['local_item_image']!="" && $row['local_item_image']!='(NULL)'){
        echo 1;
    }else{
        echo 2;
    }
}/*elseif($type == 'ng'){
    if($blur_type == 'abbre'){
        $query = "SELECT ig.description FROM location_inventory_items lii
              LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
              WHERE local_item_id ='$item' AND location_id=" . $_SESSION['loc'];
        $result = mysql_query($query) or die(mysql_error());
        $num_row = mysql_num_rows($result);
        if($num_row > 0){
            $row = mysql_fetch_array($result);
            echo $row['description'];
        }else{
            echo 2;
        }
    }elseif($blur_type == 'desc'){
        $query = "SELECT ig.description FROM location_inventory_items lii
              LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
              WHERE local_item_desc ='$item' AND location_id=" . $_SESSION['loc'];
        $result = mysql_query($query) or die(mysql_error());
        $num_row = mysql_num_rows($result);
        if($num_row > 0){
            $row = mysql_fetch_array($result);
            echo $row['description'];
        }else{
            echo 2;
        }
    }
}*/
?>