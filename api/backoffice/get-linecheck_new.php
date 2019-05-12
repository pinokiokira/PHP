<?php
ob_start("ob_gzhandler");

if(isset($_GET['storeroom'])){
    $query = 'location=' . $_GET['location'] . '&storeroom=' . $_GET['storeroom'] . '';
    if(isset($_GET['website'])) $query .= '&website';
}else{
    $query = 'location=' . $_GET['location'];
}
$token = md5($query . 'backofficesecure12');


if($token = $_GET['token2']){
    require_once("../../includes/connectdb.php");
    if($_GET['location'] != ''){
        $location_id = mysql_real_escape_string($_GET['location']);

        if (!isset($_GET['storeroom'])) { //first screen, only return storerooms
            $query = "SELECT storeroom_id,stroom_id
              FROM location_inventory_storerooms
              WHERE location_id = " . $location_id ." AND line='yes'
              ORDER BY priority ASC";
            $result = mysql_query($query) or die(mysql_error());
            while($row = mysql_fetch_assoc($result)){
                $storerooms[] = $row;
            }
            $response = array(
                'status' => 'success',
                'response' => array(
                    'storerooms' => $storerooms
                )
            );
            echo json_encode($response);
        }else{
            $storeroom_id = mysql_real_escape_string($_GET['storeroom']);

            $query = "SELECT COALESCE(ii.description,lii.local_item_desc) AS item_name,lili.id,lili.area,lili.shelflife,lili.storage_unit,lili.par_unit_type,lili.par,lili.quality_spec,lili.temp_req
                  FROM location_inventory_line_items lili
                  INNER JOIN location_inventory_items lii ON lii.id=lili.inv_item_id
                  LEFT JOIN inventory_items ii ON ii.id=lii.inv_item_id
                  WHERE lili.status='active' AND lili.location_id=" . $location_id . " AND storeroom_id=" . $storeroom_id . "
                  ORDER BY lili.priority ASC";
            $result = mysql_query($query) or die(mysql_error());
            while($row = mysql_fetch_assoc($result)){
                $items[] = $row;
            }

            $volume = array();
            $weight = array();
            $package = array();

            $query = "SELECT id,unit_type,conversion_group
                  FROM inventory_item_unittype
                  ORDER BY conversion_group DESC, unit_type ASC";
            $result = mysql_query($query) or die(mysql_error());
            while($row = mysql_fetch_assoc($result)){
                switch($row['conversion_group']){
                    case 'volume':
                        $volume[] = $row;
                        break;
                    case 'weight':
                        $weight[] = $row;
                        break;
                    case 'package':
                        $package[] = $row;
                        break;
                }
            }

            $unit_types = array(
                'volume' => $volume,
                'weight' => $weight,
                'package' => $package
            );
			$post = array();
            $response = array(
                'status' => 'success',
                'response' => array(
                    'unit_types' => $unit_types,
                    'items' => $items,
					'post' => $_REQUEST
                )
            );


            echo json_encode($response);
        }
    }
}else{
    $response = array(
        'status' => 'fail'
    );
    echo json_encode($response);
}
?>