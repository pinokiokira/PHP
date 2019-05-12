<?php
//include_once '../includes/session.php';
session_name("VENDOR");
session_start();
include_once("../config/accessConfig.php");

$_SESSION['unit_type']="";
$str="";
$location_inventory_id = mysql_real_escape_string($_GET["item"]);
//$inventoryItemCount = array();
if (($_GET['g'] != '' || $_GET['vendor']!='' || $_GET['serach_item']!='' ) && $_GET['item'] == '') { //echo "group";  //Group has been selected
	$search = array('Search Items', 'itm_tbl', 2); //new variables to append for js searching(now it is for items)
	//Set variables to append for sorting
	if ($_GET['o'] == 'a') {
		$order = " ORDER BY description ASC ";
		$o = 'd';
	} else {
		$o = 'a';
		$order = " ORDER BY description ASC ";
	}
	//Select all items for the selected group
	
	$strWHereii = "";
	$strWHereLii = "";
	if($_GET['g']!=''){
		$strWHereii .= " AND ii.inv_group_id='" . mysql_real_escape_string($_GET['g']) . "'";
		$strWHereLii .= " AND lii.local_group_id='" . mysql_real_escape_string($_GET['g']) . "'";
	}
	if($_GET['vendor']>0){
		$strWHereii .= " AND ii.vendor_default='" . mysql_real_escape_string($_GET['vendor']) . "'";
		$strWHereLii .= " AND lii.default_vendor='" . mysql_real_escape_string($_GET['vendor']) . "'";
	}
	if($_GET['serach_item']!=''){
		$strWHereii .= " AND ii.description LIKE  '%".mysql_real_escape_string($_GET['serach_item']) . "%'";
		$strWHereLii .= " AND lii.local_item_desc LIKE '%" . mysql_real_escape_string($_GET['serach_item']) . "%'";
	}
	
	
	//print_r($_GET);
	
	$query2 = "
			select tab.type as enttype,tab.id,tab.description, tab.group_id from ( 
			   (SELECT lii.type,lii.id,ii.description as description,ii.inv_group_id as group_id
			   FROM location_inventory_items lii
			   INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
			   LEFT JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id AND lic.quantity>0
			   WHERE lii.location_id=" . $_SESSION['loc'] . " AND lii.status='active' $strWHereii)
			   UNION
			   (SELECT lii.type,lii.id,lii.local_item_desc as description,lii.local_group_id as group_id
			   FROM location_inventory_items lii
			   LEFT JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id AND lic.quantity>0
			   WHERE lii.location_id=" . $_SESSION['loc'] . " AND lii.status='active' $strWHereLii)
			   ) as tab group by tab.id " . $order;	
	
	if($_REQUEST['debug']!=''){
		echo '<br>'.$query2;
	}
	$result2 = mysql_query($query2) or die(mysql_error());
	$i = 0; //Counts number of items and is submitted as $_POST['increment']
	$str .= '<div id="inv_table">
		<form id="count_form" name="count_form" action="" method="post">
			<input type="hidden" name="gotopage" id="gotopage" value="">
			<input type="hidden" name="market" value="'.$_REQUEST['market'].'"  id="market" />
			<input type="hidden" name="group_id" value="'.$_REQUEST['g'].'"  id="group_id" />
		<div style="width:100%;">
			<table id="itm_tbl" class="table table-bordered table-infinite">
				<colgroup>
					<col class="con0" style="width:20%;"/>
					<col class="con1" style="width:60%;"/>
					<col class="con0" style="width:20%;"/>
				</colgroup>
				<thead>
					<tr>
						<th class="head1 center">Type</th>
						<th class="head0 center">Item</th>
						<th class="head1 center">Unit</th>
					</tr>
				</thead>
				<tbody>';
    if (mysql_num_rows($result2) > 0) {
        while ($row2 = mysql_fetch_array($result2)) {
            $i++; //Add 1 for each item
            if ($row2['enttype'] == 'global') {
                $query3 = "SELECT lii.id loc_item_id, lic.id,lic.storeroom_id, ii.unit_type, lii.Type as enttype,lic.quantity, ig.description as item_group, ii.description as item,iiu.unit_type as unit
                           FROM location_inventory_items lii
                           INNER JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id
                           INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
                           INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
                           INNER JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id='".$row2['id']."' AND lisi.storeroom_id = lic.storeroom_id
                           LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
                           WHERE lii.location_id=" . $_SESSION['loc'] . " AND lic.location_id=" . $_SESSION['loc'] . " AND lic.inv_item_id=" . $row2['id'] . "
                           ORDER BY lisi.priority";//lic.storeroom_id, lic.id DESC
            } else {
                $query3 = "SELECT lii.id loc_item_id, lic.id,lic.storeroom_id, lii.local_unit_type as unit_type, lii.Type as enttype,lic.quantity, ig.description as item_group, lii.local_item_desc as item,iiu.unit_type as unit
                           FROM location_inventory_items lii
                           INNER JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id
                           INNER JOIN inventory_groups ig ON lii.local_group_id=ig.id
                           INNER JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id='".$row2['id']."' AND lisi.storeroom_id = lic.storeroom_id
                           LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
                           WHERE lii.location_id=" . $_SESSION['loc'] . "	AND lic.location_id=" . $_SESSION['loc'] . " AND lic.inv_item_id=" . $row2['id'] . "
                           ORDER BY lisi.priority"; //lic.storeroom_id, lic.id DESC
            }
			
			if( isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1' ) {
				echo '<br />---$query3---<br />';
				echo $query3;
				echo '<br />---$query3---<br />';
			}
			
            $result3 = mysql_query($query3) or die(mysql_error());
            $quantity = 0; //Set quantity to 0 for each item from location_inventory_items
            $newest_storeroom = 0; //Storeroom_id of newest count
            $newest_id = 0; //id of highest record id from counts
            $unit = '';
            $num_rows = mysql_num_rows($result3);
            if ($num_rows > 0) { //If record exists in location_inventory_counts
                $curr_stroom = 0;
                $break_on = 0;
                while ($row3 = mysql_fetch_array($result3)) {
                    $group = trim($row3['item_group']);
                    $item = trim($row3['item']);
                    $inv_item_id = $row3['loc_item_id'];
                    $storeroom_id = $row3['storeroom_id'];
                    $enttype =ucfirst($row3['enttype']); //ucfirst(substr($row3['Type'],0,1));
                    $group=$row3['group'];
                     $unit_type = $row3['unit_type'];
                    if ($row3['unit'] != '') {
                        $unit = $row3['unit'];
                    }
                    if ($row3['id'] > $newest_id) {
                        $newest_id = $row3['id'];
                        $newest_storeroom = $storeroom_id;
                    }
                    if ($curr_stroom == $storeroom_id) { //Check if previous and current iteration are for the same storeroom
                        if ($break_on != $storeroom_id) { //Check if break for this store room. Break means that a total has been computed for this room
                            if ($row3['Type'] == 'Count') { //If newest record is count, set quantity equal to it and break loop
                                $quantity += $row3['quantity'];
                                $break_on = $storeroom_id;
                            } else { //Newest is NOT purchase so loop and add until count is found
                                $quantity += $row3['quantity'];
                            }
                        }
                    } else { //First iteration for storeroom
                        $curr_stroom = $storeroom_id;
                        if ($row3['Type'] == 'Count') { //If newest record is count, set quantity equal to it and break loop
                            $quantity += $row3['quantity'];
                            $break_on = $storeroom_id;
                        } else { //Newest is NOT purchase so loop and add until count is found
                            $quantity += $row3['quantity'];
                        }
                    }
                }
                $query10 = "UPDATE location_inventory_items SET total_count=" . $quantity . " WHERE id=" . $row2['id'];
                $result10 = mysql_query($query10) or die(mysql_error());
                if ($_GET['item'] == $row2['id']) {
                    $class = "line3";
                } else {
                    $class = "";
                }
				$enttype = $enttype!=''?$enttype:'Global';
                $str .= '<tr id="item_'.$row2['group_id'].'_'.$row2['id'].'" rel_g='.$row2['group_id'].' class="item_row '.$class.'">
                        <td style="cursor:pointer;text-align:center;">'.$enttype.'</td>
                        <td style="cursor:pointer;">'.$item.'</td>

                    <td style="padding-left: 5px;">'.$unit.'</td>
                </tr>';

            } else { //Record does not exist in location_inventory_counts
                $query4 = "SELECT storeroom_id FROM location_inventory_storerooms WHERE location_id=" . $_SESSION['loc'] . " AND stroom_id='General'";
                $result4 = mysql_query($query4) or die(mysql_error());
                $num_rows = mysql_num_rows($result4);
                if ($num_rows < 1) { //No 'general' storeroom exists, so insert one and get id
                    $query5 = "INSERT INTO location_inventory_storerooms SET location_id=" . $_SESSION['loc'] . ", description='General Storeroom', stroom_id='General'";
                    $result5 = mysql_query($query5) or die(mysql_error());
                    $storeroom = mysql_insert_id();
                } else { //'general' store room exists, so retrieve id
                    $row4 = mysql_fetch_array($result4);
                    $storeroom = $row4['storeroom_id'];
                }
                $query = "SELECT id
                      FROM location_inventory_storeroom_items
                      WHERE location_id=" . $_SESSION['loc'] . " AND storeroom_id=$storeroom AND inv_item_id=" . $row2['id'];
                $result = mysql_query($query) or die(mysql_error());
                if (!mysql_num_rows($result) > 0) {
                    $query = "INSERT INTO location_inventory_storeroom_items
                          SET location_id=" . $_SESSION['loc'] . ",
                              storeroom_id=$storeroom,
                              inv_item_id=" . $row2['id'];
                    $result = mysql_query($query) or die(mysql_error());
                }
                $time = date("H:i:s");
                $date = date("Y-m-d");
                $query6 = "INSERT INTO location_inventory_counts SET
                                location_id = " . $_SESSION['loc'] . ",
                                inv_item_id=" . $row2['id'] . ",
                                Type='Start',
                                date_counted='$date',
                                time_counted='$time',
                                employee_id='1914',
                                created_on='BusinessPanel',
                                created_datetime=NOW(),
                                created_by='1914',
                                storeroom_id='$storeroom'";
                $result6 = mysql_query($query6) or die(mysql_error());
                if ($_GET['item'] == $row2['id']) {
                    $class = "line3";
                } else {
                    $class = "";
                }
				$row2['enttype'] = $row2['enttype']!=''?$row2['enttype']:'Global';
                $str .= '<tr id="item_'.$row2['id'].'" class="item_row '.$class.'">
                    <td style="cursor:pointer;text-align:center;">'.$row2['enttype'].'</td>
                    <td style="cursor:pointer;" class="'.$class.'">'.$row2['description'].'</td>
                    <td style="cursor:pointer;text-align:center;" class="'.$class.'">0</td>
                </tr>';
            }
        }
    } else {
        $str .= '';
    }
    $str .= '</tbody>
            </table>
            </form>
            </div>';
	echo $str;
}
else if ($_GET['g'] != '' && $_GET['item'] != '') { //echo "item"; //Item has been selected
	$str="";
	$item = mysql_real_escape_string($_GET['item']);
	//Load all possible storerooms for location
	$query8 = "SELECT storeroom_id, stroom_id
			   FROM location_inventory_storerooms
			   WHERE location_id = " . $_SESSION['loc'] . "
			   ORDER BY stroom_id ASC";
	$result8 = mysql_query($query8) or die(mysql_error());
	$query11 = "select COALESCE(iiu.conversion_group,iiu2.conversion_group) as conversion_group,ii.description as gdescription,lii.local_item_desc as ldescription,lii.type as itype  
				from location_inventory_items lii
				left join inventory_items ii on ii.id=lii.inv_item_id
				left join inventory_item_unittype iiu2 ON ii.unit_type=iiu2.id
				left join inventory_item_unittype iiu ON lii.local_unit_type=iiu.id
				where lii.id=" . $item;
	if($_REQUEST['debug']!=''){
		echo '<br> 11=>'.$query11.'<br>';
	}			
	$result = mysql_query($query11) or die(mysql_error());
	$conv_groupr = mysql_fetch_assoc($result);
	$conv_group = $conv_groupr['conversion_group'];
	$unit_types = array();
	
	
	if(strtolower($conv_groupr['itype'])!='global'){
		$itme_name = $conv_groupr['ldescription']; 
	}else{
		$itme_name = $conv_groupr['gdescription']; 
	}
	
	
	
	
	// $conv_group = '';echo '<-here ...';
	if($conv_group!=""){
			$query9 = "SELECT *
			   FROM inventory_item_unittype
			   WHERE conversion_group='$conv_group'
			   ORDER BY conversion_group,unit_type";
	}else{
		$query9 = "SELECT *
				   FROM inventory_item_unittype
				   ORDER BY conversion_group,unit_type";
	}
	$result9 = mysql_query($query9) or die(mysql_error());
	while ($row9 = mysql_fetch_assoc($result9)) {
		$unit_types[] = $row9;
	}

    $str .= '
		<input type="hidden" name="ditemName" id="ditemName" value="'.$itme_name.'" >
		<form id="count_frm" method="post">
			<input type="hidden" name="page" id="page" value="'.$_REQUEST['page'].'"  />
			<input type="hidden" name="count_submit" value="submitted"/>
			<input type="hidden" name="group_id" value="'.$_REQUEST['g'].'"  id="group_id" />
			<input type="hidden" name="vendor" value="'.$_REQUEST['vendor'].'"  />
			<input type="hidden" name="market" value="'.$_REQUEST['market'].'"  id="market" />
			<input type="hidden" name="item" value="'.$_GET['item'].'"/>
			<table class="table table-bordered table-infinite" id="itm_detail" >
				<colgroup>
					<col class="con0" style=""/>
					<col class="con1" style=""/>
					<col class="con0" style=""/>
					<col class="con1" style=""/>
					<col class="con0" style=""/>
				</colgroup>
				<thead>
					<tr>
						<th class="head0 center">Type</th>
						<th class="head1 center">Date</th>
						<th class="head0 center">Employee</th>
						<th class="head1 center">Unit Type</th>
						<th class="head0 right">Qty</th>
					</tr>
				</thead>
				<tbody>';
    $new_strooms = array();//this stores the storerooms where the item is not located
    $i = 0;
	$j = 0;
    while ($row8 = mysql_fetch_array($result8)) {
        if (isset($_GET['t'])) {
            $query7 = "SELECT lic.Type,lic.date_counted,lic.quantity,lic.time_counted,employees.first_name, employees.last_name,inventory_item_unittype.unit_type,lic.created_datetime,inventory_item_unittype.id as unit_id
                       FROM location_inventory_counts lic
                       LEFT JOIN employees ON lic.employee_id=employees.id
                       LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type
                       WHERE inv_item_id=" . mysql_real_escape_string($_GET['item']) . " AND lic.location_id=" . $_SESSION['loc'] . " AND lic.storeroom_id=" . $row8['storeroom_id'] . "
                       ORDER BY lic.date_counted DESC, lic.time_counted DESC";

        } else {
            $query7 = "SELECT Type,date_counted,time_counted,inv_item_id,lic.unit_type, quantity,e.first_name,e.last_name,iiu.unit_type as unit,lic.unit_type,lic.created_datetime,iiu.id as unit_id
                       FROM location_inventory_counts lic
                       LEFT JOIN employees e ON lic.employee_id=e.id
                       LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
                       WHERE inv_item_id=" . mysql_real_escape_string($_GET['item']) . " AND storeroom_id=" . $row8['storeroom_id'] . " AND lic.id >=(
                           COALESCE((SELECT max(id)
                               FROM location_inventory_counts lic
                               WHERE inv_item_id=" . mysql_real_escape_string($_GET['item']) . " AND Type='Count' AND storeroom_id=" . $row8['storeroom_id'] . "),0)
                       )
                       ORDER BY lic.id DESC";
        }
		if($_REQUEST['debug']!=''){
			echo '<br> query7 =>'.$query7.'<br>';
		}
        $result_7 = mysql_query($query7) or die(mysql_error());
        $num_rows = mysql_num_rows($result_7);
        $row_7 = mysql_fetch_array($result_7);
		if($row_7['unit_type']!=''){
			$u_type = $row_7['unit_type'];
			if($i==0){
				$u_id= $row_7['unit_id'];
				$i++;
			}
		}
        if ($num_rows > 0) {
			if($_REQUEST['debug']!=''){
				echo '<br><br> Have Count =>'.$num_rows.'<br>';
			}
			
            $total = 0;$lastTime = '';
            $stop = false;
            $str .= '<tr class="widgettitle">
                        <td colspan="6">'.$row8['stroom_id'].'</td>
                        <input type="hidden" name="storeroom[]" value="'.$row8['storeroom_id'].'"/>
                    </tr>
                    <tr>
                        <td>Count</td>
                        <td class="center">'.date('Y-m-d H:i').'</td>
                        <td >'.$_SESSION['user_full_name'].'</td>
                        <td>
							<div class="selectouter12 select_w3">
                            <select style="width:100px;margin-bottom: 2px !important;" name="unit[]" class="unitselect incntunit'.$j.'">
                                <option value="">Unit Type</option>';
								$opt_lbl = '';
                                foreach ($unit_types as $type) {
                                    if($type['unit_type']==$u_type){
                                        $selected = "selected";
                                    }else{
                                        $selected = "";
                                    }
									
									if($opt_lbl != $type['conversion_group']){
										$str .= '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($type['conversion_group']) .'</option>';
									}
									$opt_lbl = $type['conversion_group'];
									
                                    $str .= '<option '.$selected.' value="'.$type['id'].'" data-description="'. $type['description'] .'" >'.$type['unit_type'].'</option>';
                                }
                            $str .= '</select>
							</div>
                        </td>
                        <td style="width:15% !important;">
                            <input type="text" rel="'.$j.'"  style="width:80% !important;margin-bottom: 2px !important;" placeholder="'.$_SESSION['Count'].'" name="count[]" class="enable incnt"/>
                        </td>
                    </tr>';
            $result7 = mysql_query($query7) or die(mysql_error());
            $unit = "";
            while ($row7 = mysql_fetch_array($result7)) {
                $type = $row7['Type'];
                $qty = $row7['quantity'];
                if (!$stop) {
                    if ($type == 'Count') { //If newest record is count, set quantity equal to it and break loop
                        $total += $qty;
                        $stop = true;
                        $unit = $row7["unit_type"];
                        $lastTime = $lastTime>$row7['created_datetime']?$lastTime:$row7['created_datetime'];
                    } else { //Newest is NOT count so loop and add until count is found
                        $total += $qty;
                        $unit = $row7["unit_type"];
                        $lastTime = $lastTime>$row7['created_datetime']?$lastTime:$row7['created_datetime'];
                    }
                }
                $str .= '<tr style="height:25px;">
                            <td>'.$type.'</td>
                            <td style="max-width: 60px;" class="center">'.$row7["date_counted"] . ' ' . substr($row7["time_counted"], 0, 5).'</td>
                            <td>'.$row7["first_name"] . ' ' . $row7["last_name"].'</td>
                            <td>'.$row7["unit_type"].'</td>
                            <td style="text-align:right;">'.$qty.'</td>
                        </tr>';
            }
            $str .= "<tr><td colspan='6' style='text-align: right;'>Total: " . number_format($total,2,'.',''). "</td></tr>";
            if(!empty($total))
            {
               
			    if($_REQUEST['debug']!=''){
					echo '<br> Before <br><pre>';
					print_r($inventoryItemCount);
					echo '<br> +'.$total;
					echo '<br></pre>';
				}
			    $inventoryItemCount[$unit] = $inventoryItemCount[$unit]+$total;
				if($_REQUEST['debug']!=''){
					echo '<br> After <br><pre>';
					print_r($inventoryItemCount);
					echo '<br></pre>';
				}
            }
        } else {
            $new_strooms[] = $row8; //add because item does not exist in current storeroom
        }
		$j++;
    }
    if (count($new_strooms) > 0) {
        $str .= '<tr>
            <td style="width:15%;">New Storeroom</td>
            <td colspan="4">
                <select id="storeroom1" name="storeroom[]">
                    <option value="">Select Storeroom</option>';
                    foreach ($new_strooms as $val) {
                        $str .= '<option value="'.$val[0].'">'.$val[1].'</option>';
                    }
                $str .= '</select>
            </td>
        </tr>
        <tr>
            <td>Count</td>
            <td class="center">'.date('Y-m-d H:i').'</td>
            '.$_SESSION['first_name'] . ' ' . $_SESSION['last_name'].'
        <td>'.$_SESSION['user_full_name'].'</td>
            <td>
				<div class="selectouter12 select_w3">
				<select name="unit[]" style="width:100px;margin-bottom: 2px !important;" class="unitselect1">
                    <option value="">Unit Type</option>';
					$opt_lbl = '';
                    foreach ($unit_types as $type) {
                        if($type['id']==$u_id){
                                    $selected = "selected";
                                }else{
                                    $selected = "";
                                }
								
						if($opt_lbl != $type['conversion_group']){
							$str .= '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($type['conversion_group']) .'</option>';
						}
						$opt_lbl = $type['conversion_group'];
								
                        $str .= '<option '.$selected.' value="'.$type['id'].'" data-description="'. $type['description'] .'"  >'.$type['unit_type'].'</option>';
                    }
                $str .= '</select>
					</div>
            </td>
            <td>
                <input type="text" id="count1" class="enable" name="count[]" placeholder="'.$_SESSION['Count'].'" style="width:80% !important;margin-bottom: 2px !important;">
            </td>
        </tr>';
        }
    $str .= '<tbody>
            </table>
            </div>
            </form>';
    $cnt = count($inventoryItemCount);
    if($cnt>0)
        $strHypthothetical = '<table class="table table-bordered table-infinite">'.
        '<colgroup>
                <col style="" class="con0">
                <col style="" class="con0">
                <col style="" class="con0">
            </colgroup>
                    <thead>
                        <tr>
                            <th class="head0 left">Type</th>
                            <th class="head0 left">Unit</th>
                            <th class="head1 right">Amount</th>
              </tr>
        </thead>
        <tbody>';
    else
        $strHypthothetical = '<table class="table table-bordered table-infinite">'.
            '<colgroup>
                <col style="" class="con0">
                <col style="" class="con0">
            </colgroup>
                    <thead>
                        <tr>
                            <th class="head0 left">Type</th>
                            <th class="head1 right">Amount</th>
              </tr>
        </thead>
        <tbody>';

    //generate last count table html
    $strHypthothetical .= generateTableHtml($inventoryItemCount,"Last Count");
    $orderResult = getOrderAmount($location_inventory_id,$lastTime);

 
    $result = getHypotheticalCount($inventoryItemCount,$orderResult);
    $strHypthothetical .=generateTableHtml($result['soldSinceLastCount'],'Sold Since Last Count');
    $strHypthothetical .= generateTableHtml($result['hypotheticalCount'],'Hypothetical Count');
  
    $strHypthothetical.= '
    </tbody>
  </table>
  <p></p>';

	echo $strHypthothetical.$str;
}

function getOrderAmount($inventoryId,$lastTime)
{
   
    $sqlLocationInventoryRecipeDetails = "SELECT location_inventory_recipe_details.recipe_id,location_inventory_recipe_details.quantity,inventory_item_unittype.`unit_type` FROM location_inventory_recipe_details
                                        LEFT JOIN `inventory_item_unittype` ON location_inventory_recipe_details.`unit_type` = inventory_item_unittype.`id`
                                        WHERE location_inventory_recipe_details.`inv_item_id` = '$inventoryId'";
    $recipeIdResult = mysql_query($sqlLocationInventoryRecipeDetails) or die(mysql_error());
    $num_rows = mysql_num_rows($recipeIdResult);
    if($num_rows<1)
        return 0;
    while ($rowInventory = mysql_fetch_array($recipeIdResult)) {
        $recipeId = $rowInventory["recipe_id"];
        $quantity = $rowInventory["quantity"];
        $unit = $rowInventory["unit_type"];
        $sqlLocationInventoryRecipe = "SELECT menu_article_id FROM location_inventory_recipe WHERE location_inventory_recipe.`id` = $recipeId";
        $menuArticleIdResult = mysql_query($sqlLocationInventoryRecipe) or die(mysql_error());
        $num_rows = mysql_num_rows($menuArticleIdResult);
        if($num_rows<1)
            return 0;
        $row = mysql_fetch_array($menuArticleIdResult);
        $menuArticleId = $row["menu_article_id"];
        $sqlCount = "SELECT COUNT(*) as cnt FROM client_order_items WHERE location_id='".$_SESSION['loc']."' AND menu_item_id='".$menuArticleId."' and datetime>'$lastTime'";
       // $sqlCount = "SELECT COUNT(*) as cnt FROM client_order_items WHERE location_id='".$_SESSION['loc']."' AND menu_item_id='".$menuArticleId."'";

        $countResult = mysql_query($sqlCount) or die(mysql_error());
        $num_rows = mysql_fetch_array($countResult);
        $count = $num_rows["cnt"];
        $orderAmount = number_format($quantity * $count,2,'.','');

        if(isset($result[$unit]))
            $result[$unit] += $orderAmount;
        else
            $result[$unit] = $orderAmount;
    }
    return $result;
}

function generateTableHtml($dataArray,$strFirst)
{
    $cnt = count($dataArray);
    $strResult = "";
    if( $cnt > 0 )
    {
        $i = 0 ;
		if($_REQUEST['debug']!=''){
			echo '<br><pre>';
			print_r($dataArray);
			echo '<br></pre>';
		}
        foreach($dataArray as $key=>$value){
            if($i ==0)
                $strResult .="<tr >
                               <td rowspan='".$cnt."'class='left'>".$strFirst."</td>
                              <td class='left'>".$key."</td>
                              <td class='right'>".number_format($value,2,'.','')."</td>
                            </tr>";
            else
                $strResult .="<tr >
                              <td class='left'>".$key."</td>
                              <td class='right'>".number_format($value,2,'.','')."</td>
                            </tr>";
            $i++;
        }
    }
    else
    {
        if($strFirst!="Sold Since Last Count")
            $strResult .="<tr >
                                  <td class='left'>".$strFirst."</td>
                                  <td class='right'>0.00</td>
                                </tr>";

    }
    return $strResult;
}
function getHypotheticalCount($lastArray,$orderArray)
{
    $hypotheticalAmount = $lastArray;
    $soldSinceLastCount = array();
    if(empty($lastArray))
        return ;
    if(empty($orderArray))
    {
        $result ["hypotheticalCount"] = $hypotheticalAmount;
        $result["soldSinceLastCount"] = $soldSinceLastCount;
        return $result;
    }
    //echo json_encode($lastArray)."xxx".json_encode($orderArray);
	
    foreach($orderArray as $orderUnit=>$orderValue){
        //check hypothetical or Sold Since Last Count
        //if unit is equal
        if($lastArray[$orderUnit]>0){
            //$hypotheticalAmount[$orderUnit] = $hypotheticalAmount[$orderUnit]-$orderValue<0?0:$hypotheticalAmount[$orderUnit]-$orderValue;
            $hypotheticalAmount[$orderUnit] = $hypotheticalAmount[$orderUnit]-$orderValue;
            if($orderValue>0)
                $soldSinceLastCount[$orderUnit] = $orderValue;
            if($hypotheticalAmount[$orderUnit]<0){
									
                $sqlOrderUnitGroup = "SELECT conversion_group,factor FROM inventory_item_unittype WHERE unit_type = '$orderUnit'";
                $resultOrderUnitGroup = mysql_query($sqlOrderUnitGroup) or die(mysql_error());
                $num_rows = mysql_fetch_array($resultOrderUnitGroup);
                $orderUnitGroup = $num_rows["conversion_group"];
                $orderUnitFactor = $num_rows["factor"];
                    $count = 0;$firstUnit = '';
                    foreach ($hypotheticalAmount as $lastUnit => $lastValue) {														   
                        if ($orderUnit != $lastUnit) {									
                            $sqlLastUnitGroup = "SELECT conversion_group,factor FROM inventory_item_unittype WHERE unit_type = '$lastUnit'";
                            $resultLastUnitGroup = mysql_query($sqlLastUnitGroup) or die(mysql_error());
                            $num_rows = mysql_fetch_array($resultLastUnitGroup);
                            $lastUnitGroup = $num_rows["conversion_group"];
                            $lastUnitFactor = $num_rows["factor"];
                            if ($lastUnitGroup == $orderUnitGroup) {
									
                                if ($count < 1) {
									
                                    //do unit conversion
                                    // $temp = $lastValue - $orderValue *$lastUnitFactor/$orderUnitFactor<0?0:$lastValue - $orderValue *$lastUnitFactor/$orderUnitFactor;
                                    $temp = $lastValue + $hypotheticalAmount[$orderUnit] * $lastUnitFactor / $orderUnitFactor;
                                   // echo $temp . "xxx" . $hypotheticalAmount[$orderUnit] . "xxx" . $lastUnit . "xxx" . $orderUnit;

                                    if ($temp > 0) {
                                        $integer = floor($temp);
                                        $fraction = $temp - $integer;
                                        $fraction = $fraction * $orderUnitFactor / $lastUnitFactor;
                                        $hypotheticalAmount[$lastUnit] = $integer;
                                        if ($fraction > 0)
                                            $hypotheticalAmount[$orderUnit] = $fraction;

                                        //$soldSinceLastCount[$lastUnit] = $hypotheticalAmount[$lastUnit] - $orderValue*$lastUnitFactor/$orderUnitFactor<0?0:$hypotheticalAmount[$lastUnit] - $orderValue*$lastUnitFactor/$orderUnitFactor;
                                        if($orderValue>0)
                                            $soldSinceLastCount[$orderUnit] = $orderValue;
                                    } else {
                                        $hypotheticalAmount[$lastUnit] = 0;
                                        $hypotheticalAmount[$orderUnit] = 0;
                                    }
                                } else {
                                    /* $soldSinceLastCount[$firstUnit] = $lastArray[$firstUnit];
                                     unset($hypotheticalAmount[$firstUnit]);
                                     $hypotheticalAmount[$lastUnit] = $lastValue; //- $orderValue *$orderUnitFactor/$lastUnitFactor<0?0:$lastValue - $orderValue *$orderUnitFactor/$lastUnitFactor;*/
                                }
                                $count++;
                            }
                        }
                    }
            }

        }
        else {//if unit is not equal
            $sqlOrderUnitGroup = "SELECT conversion_group,factor FROM inventory_item_unittype WHERE unit_type = '$orderUnit'";
            $resultOrderUnitGroup = mysql_query($sqlOrderUnitGroup) or die(mysql_error());
            $num_rows = mysql_fetch_array($resultOrderUnitGroup);
            $orderUnitGroup = $num_rows["conversion_group"];
            $orderUnitFactor = $num_rows["factor"];
            if(empty($orderUnitFactor)){
                if($orderValue>0)
                    $soldSinceLastCount[$orderUnit] = $orderValue;
            }
            else{
                $count = 0;$firstUnit = '';
                foreach ($hypotheticalAmount as $lastUnit => $lastValue) {
                    $sqlLastUnitGroup = "SELECT conversion_group,factor FROM inventory_item_unittype WHERE unit_type = '$lastUnit'";
                    $resultLastUnitGroup = mysql_query($sqlLastUnitGroup) or die(mysql_error());
                    $num_rows = mysql_fetch_array($resultLastUnitGroup);
                    $lastUnitGroup = $num_rows["conversion_group"];
                    $lastUnitFactor = $num_rows["factor"];
                    if($lastUnitGroup==$orderUnitGroup){
                        if($count<1){
                            //do unit conversion
                           // $temp = $lastValue - $orderValue *$lastUnitFactor/$orderUnitFactor<0?0:$lastValue - $orderValue *$lastUnitFactor/$orderUnitFactor;
                            $temp = $lastValue - $orderValue *$lastUnitFactor/$orderUnitFactor;
                            $integer =  floor ($temp);
                            $fraction = $temp - $integer;
                            $fraction  = $fraction * $orderUnitFactor/$lastUnitFactor;
                            $hypotheticalAmount[$lastUnit] = $integer;
                            if($fraction>0)
                                $hypotheticalAmount[$orderUnit] = $fraction;

                            //$soldSinceLastCount[$lastUnit] = $hypotheticalAmount[$lastUnit] - $orderValue*$lastUnitFactor/$orderUnitFactor<0?0:$hypotheticalAmount[$lastUnit] - $orderValue*$lastUnitFactor/$orderUnitFactor;
                            if($orderValue>0)
                                $soldSinceLastCount[$orderUnit] = $orderValue;
                            $firstUnit = $lastUnit;
                        }
                        else{
                           /* $soldSinceLastCount[$firstUnit] = $lastArray[$firstUnit];
                            unset($hypotheticalAmount[$firstUnit]);
                            $hypotheticalAmount[$lastUnit] = $lastValue; //- $orderValue *$orderUnitFactor/$lastUnitFactor<0?0:$lastValue - $orderValue *$orderUnitFactor/$lastUnitFactor;*/
                        }
                        $count++;
                    }
                }
            }
        }
		
    }
	
	foreach($hypotheticalAmount as $key=>$val){
		if($val<0.10){
			$hypotheticalAmount[$key]=0.00;
		}
	}
	if($_REQUEST['debug']!=''){
		echo '<br> Hypoth => <pre>';
		print_r($hypotheticalAmount);
		echo '</pre><br>';
	}
    $result ["hypotheticalCount"] = $hypotheticalAmount;
    $result["soldSinceLastCount"] = $soldSinceLastCount;
    return $result;
}

function hypotheticaCount($hypotheticalArray){
    $orignalArray = $hypotheticalArray;
    foreach($hypotheticalArray as $hypotheticalUnit=>$hypotheticalAmount){
        if($hypotheticalAmount<0){
            $sqlOrderUnitGroup = "SELECT conversion_group,factor FROM inventory_item_unittype WHERE unit_type = '$hypotheticalUnit'";
            $resultOrderUnitGroup = mysql_query($sqlOrderUnitGroup) or die(mysql_error());
            $num_rows = mysql_fetch_array($resultOrderUnitGroup);
            $hypotheticalUnitGroup = $num_rows["conversion_group"];
            $hypotheticalUnitFactor = $num_rows["factor"];
            foreach($orignalArray as $originalUnit=>$originalAmount){
                if($hypotheticalUnit!=$originalUnit){
                }
            }
        }
    }
}


?>
