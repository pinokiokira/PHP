<?php
if(isset($_GET['storeroom'])){
   $query = 'location=' . $_GET['location'] . '&storeroom=' . $_GET['storeroom'] . '';
    if(isset($_GET['website'])) $query .= '&website';
}else{
    $query = 'location=' . $_GET['location'];
}
$token = md5($query . 'backofficesecure12');

if($_GET['token1'] == $token){
    if($_GET['location'] != ''){
        ob_start("ob_gzhandler");
        require_once("../../config/accessConfig.php");
		

        $location_id = mysql_real_escape_string($_GET['location']);

        $query = 'SELECT storeroom_id,stroom_id
              FROM location_inventory_storerooms
              WHERE location_id = ' . $location_id . '
              ORDER BY priority ASC';
        $result = mysql_query($query) or die(mysql_error());
        while($row = mysql_fetch_assoc($result)){
            $storerooms[] = $row;
        }

        if (!isset($_GET['storeroom'])) { //first screen, only return storerooms
            $response = array(
                'status' => 'success',
                'response' => array(
                    'storerooms' => $storerooms,
                )
            );
            echo json_encode($response);
        }

        if($_GET['storeroom'] != ''){
            $storeroom_id = mysql_real_escape_string($_GET['storeroom']);
            $curr_group ='';
            $append = '';
			if($_GET['keyword']!=""){
				$serch_key = $_GET['keyword'];
				$serch_where = " AND ((lii.id like '%{$serch_key}%' OR ii.description like '%{$serch_key}%' OR lii.local_item_desc like '%{$serch_key}%')
								OR ig1.description like '%{$serch_key}%' OR ig2.description like '%{$serch_key}%' OR iiu1.conversion_group like '%{$serch_key}%' OR iiu2.conversion_group like '%{$serch_key}%')";
			}
			if($_GET['market']!=""){
				$serch_where .= "AND (ig1.market = '".$_GET['market']."' || ig2.market = '".$_GET['market']."')";
			}
			
			if($_GET['group_id']!=""){
				$serch_where .= "AND (ig1.id = '".$_GET['group_id']."' || ig2.id = '".$_GET['group_id']."')";
			}

            if(isset($_GET['website'])){
                $append = ',lii.type,COALESCE(ii.item_id,lii.local_item_id) as item_id,COALESCE(iiu1.unit_type,iiu2.unit_type) as unit_type';
				$append2 = ',lii.type,COALESCE(ii.id,"") as item_id,COALESCE(iiu1.unit_type,iiu2.unit_type) as unit_type';
            }

            $query = "SELECT lii.id,
                            COALESCE(ii.description,lii.local_item_desc) AS item_name,
                            COALESCE(ig1.description,ig2.description) AS group_name,
                            COALESCE(iiu1.conversion_group,iiu2.conversion_group) AS unit_group,lii.status" . $append . "
                    FROM location_inventory_storeroom_items lisi
                    INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
                    LEFT JOIN inventory_items ii ON ii.id=lii.inv_item_id
                    LEFT JOIN inventory_groups ig1 ON ig1.id=ii.inv_group_id
                    LEFT JOIN inventory_groups ig2 ON ig2.id=lii.local_group_id
                    LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id=ii.unit_type
                    LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id=lii.local_unit_type
                    WHERE lisi.location_id=" . $location_id . " $serch_where AND lisi.storeroom_id=" . $storeroom_id . " AND lii.type<>'global'
					UNION ALL
					SELECT lii.id, 
					COALESCE(ii.description,lii.local_item_desc) AS item_name, 
					COALESCE(ig1.description,ig2.description) AS group_name, 
					COALESCE(iiu1.conversion_group,iiu2.conversion_group) AS unit_group,lii.status " . $append2 . "					
					FROM location_inventory_storeroom_items lisi 
					INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
					JOIN inventory_items ii ON ii.id=lii.inv_item_id 
					LEFT JOIN inventory_groups ig1 ON ig1.id=ii.inv_group_id 
					LEFT JOIN inventory_groups ig2 ON ig2.id=lii.local_group_id 
					LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id=ii.unit_type 
					LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id=lii.local_unit_type 
					WHERE lisi.location_id=" . $location_id . " $serch_where AND lisi.storeroom_id=" . $storeroom_id . " AND lii.type='global' 
                    ORDER BY group_name ASC,item_name ASC";
			if($_REQUEST['debug']!=''){
				echo '<br>'. $query .'<br>';
			}
            $result = mysql_query($query) or die(mysql_error());
            while($row = mysql_fetch_assoc($result)){
                if(!isset($_GET['website'])){//dont do mobile processing
                    if($curr_group != $row['group_name']){
                        $curr_group = $row['group_name'];
                    }
                    unset($row['group_name']);
                }

                $query2 = "SELECT COALESCE(sum(quantity),0) AS quantity
                       FROM location_inventory_counts lic
                       WHERE inv_item_id=" . $row['id'] . " AND storeroom_id=" . $storeroom_id . " AND id >=(
                           COALESCE((SELECT max(id)
                               FROM location_inventory_counts lic
                               WHERE inv_item_id=" . $row['id'] . " AND Type='Count' AND storeroom_id=" . $storeroom_id . "),0)
                       )
                       ORDER BY id DESC";
                $result2 = mysql_query($query2) or die(mysql_error());
                $qty = mysql_result($result2,0);

                $row['qty'] = $qty;

                if(!isset($_GET['website'])){
                    $items[$curr_group][] = $row;
                }else{
                    $items[] = $row;
                }
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

            $response = array(
                'status' => 'success',
                'response' => array(
                    'storerooms' => $storerooms,
                    'unit_types' => $unit_types,
                    'items' => $items
                )
            );


            echo json_encode($response);
        }
    }
}else{
    $response = array( 'status' => 'fail' );
    echo json_encode($response);
}
?>