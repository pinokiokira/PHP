<?php 
require_once 'require/security.php';
include 'config/accessConfig.php';




require_once('require/openid-config.php'); 

if (isset($_REQUEST['debug'])) {
	echo "*-------------- TEST ------------------*";
	echo "<br>";
	echo $_SESSION['employee_id'];
	echo "<br>";
	echo $_SESSION['vendor_id'];
	echo "<br>";
	echo "Employee Master ID:";
	print_r($_SESSION['empmaster_id']);
	echo "<br>";
	print_r("SELECT employees.emp_id FROM employees INNER JOIN employee_master ON employee_master.email = employees.email WHERE employee_master.empmaster_id = '".$_SESSION['empmaster_id']."' LIMIT 1");

	$emp_id = mysql_fetch_array(mysql_query("SELECT employees.emp_id FROM employees INNER JOIN employees_master ON employees_master.email = employees.email WHERE employees_master.empmaster_id = '".$_SESSION['empmaster_id']."' LIMIT 1"));
	print_r("Employee Id:".$emp_id);
	echo "<br>";
	echo "*-------------- end TEST ------------------*";
	echo "<br>";	
}


if($_REQUEST['ids'] != ''){
    $values = explode(',', $_REQUEST['ids']);
    $time = date("H:i:s");
    $date = date("Y-m-d");
    $query = "SELECT storeroom_id FROM location_inventory_storerooms WHERE location_id='" . $_SESSION['loc'] . "' AND stroom_id='General'";
    $result = mysql_query($query) or die('Err 1'.mysql_error());
    $num_rows = mysql_num_rows($result);
    if($num_rows < 1){
        $query2 = "INSERT INTO location_inventory_storerooms SET location_id='" . $_SESSION['loc'] . "', description='General Storeroom', stroom_id='General'";
        $result2 = mysql_query($query2) or die('Err 2'.mysql_error());
        $storeroom = mysql_insert_id();
    }else{
        $row = mysql_fetch_array($result);
        $storeroom = $row['storeroom_id'];
    }
    foreach($values as $val){
		$inv_q = mysql_fetch_array(mysql_query("SELECT brand,taxable,manufacturer_barcode,manufacturer,inv_group_id,description,notes,unit_type,vendor_default from inventory_items where id = '".$val."'"));
		//juni -> 2014-09-16 - default brand is not inserted
		//priority,total_count,total_needed
        //$query = "INSERT INTO location_inventory_items SET location_id = " . $_SESSION['loc'] . ", inv_item_id=" . $val . ", status='active'";
		$query = "INSERT INTO location_inventory_items SET location_id = '" . $_SESSION['loc'] . "',
				 taxable='".mysql_real_escape_string($inv_q['taxable'])."',
				 manufacturer_barcode='".mysql_real_escape_string($inv_q['manufacturer_barcode'])."', 
				 inv_item_id='".mysql_real_escape_string($val)."', 
				 status='active', 
				 created_by = '".$_SESSION['employee_id']."',
				 created_on ='BusinessPanel',
				 created_datetime='".date('Y-m-d H:i:s')."',
				 priority='0',total_count='0.00',
				 total_needed='0.00',
				 local_group_id = '".mysql_real_escape_string($inv_q['inv_group_id'])."',
				 local_item_desc = '".mysql_real_escape_string($inv_q['description'])."',
				 local_item_notes = '".mysql_real_escape_string($inv_q['notes'])."',
				 local_unit_type = '".mysql_real_escape_string($inv_q['unit_type'])."',
				 default_manufacturer = '".mysql_real_escape_string($inv_q['manufacturer'])."',
				 default_vendor =  '".mysql_real_escape_string($inv_q['vendor_default'])."',
				 default_brand='".mysql_real_escape_string($inv_q['brand'])."'";
		//echo $query;
		//default_brand=(SELECT max(lii.default_brand) as  default_brand FROM  location_inventory_items lii WHERE lii.inv_item_id='".$val."')";
        $result = mysql_query($query) or die('Err 3'.mysql_error());
        $query3 = "INSERT INTO location_inventory_counts SET location_id = '" . $_SESSION['loc'] . "', inv_item_id='" . mysql_insert_id() . "', Type='Start', unit_type='".$inv_q['unit_type']."',quantity='0.00',date_counted='$date', time_counted='$time', employee_id='" . $_SESSION['employee_id'] . "', storeroom_id='$storeroom' ,created_by = '" . $_SESSION['employee_id'] . "',created_on ='BusinessPanel',created_datetime='".date('Y-m-d H:i:s')."'";
		//echo $query3;
		//exit;
				$query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$storeroom."',
                                inv_item_id='".mysql_insert_id()."'";
                $result = mysql_query($query)/*or die('Err 4'.mysql_error())*/;
		
        $result3 = mysql_query($query3)/* or die('Err 5'.mysql_error())*/;
    }
    //exit;
    /*if ($_POST["step"]!=""){
        $nextstep = intval($_POST["step"])+1;
        header("location:setup_process.php?step=".$nextstep);
    }*/
}
/*if($res){
			$sitem_id = mysql_insert_id();
			
			$query = "SELECT id
                      FROM location_inventory_storeroom_items
                      WHERE location_id=" . $_SESSION['loc'] . " AND inv_item_id=$sitem_id";
            $result = mysql_query($query) or die(mysql_error());
            if (!mysql_num_rows($result) > 0) {
				$st_id = mysql_fetch_array(mysql_query("SELECT storeroom_id from location_inventory_storerooms where stroom_id = 'General' ANd location_id = '63246'  LIMIT 1"));
				if($st_id['storeroom_id']>0){
                $query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$st_id['storeroom_id']."',
                                inv_item_id=$sitem_id";
                $result = mysql_query($query) or die(mysql_error());
				}
            }
		
	}
?>*/
$group_id  = '';
$marketval ='';
$sqlGroup = '';
$sqlmarket='';
if (isset($_REQUEST['group_id'])&&trim($_REQUEST['group_id'])!='') {
	$group_id = $_REQUEST['group_id'];
	$sqlGroup =  " AND ig.id=".$group_id;
}

if (isset($_REQUEST['market'])&& trim($_REQUEST['market'])!='') {
	$marketval = $_REQUEST['market'];	
	$sqlmarket =  " AND ig.Market='".$marketval."'";	
}
$vendor_and_where1 = "";
if(isset($_REQUEST['vendor']) && trim($_REQUEST['vendor'])!='') {
	$vendor_and_where1 = " AND lii.default_vendor = '". $_REQUEST['vendor'] ."' ";
}
$query2 = "SELECT lii.id as loc_item_id, ii.id,ii.description,ig.description as `group`,iiu.unit_type,lii.default_brand,  lii.default_price,ii.image,ii.model_number,ii.brand,ii.manufacturer 
          FROM location_inventory_items lii
          INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
          INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
          LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
          WHERE location_id = " . $_SESSION['loc'] .$sqlmarket. $vendor_and_where1 . $sqlGroup . " GROUP BY ii.id 
          ORDER BY `group` ASC, description ASC";
		  //echo $query2;
$result2 = mysql_query($query2) or die('Err 6'.mysql_error());
?>
<script>
	jQuery(document).ready(function(){
		jTable = jQuery('#local_tbl').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
            "bJQuery": true,
            			oLanguage: {
				sLengthMenu: "Show _MENU_",
                sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
				oPaginate: {
                    sNext: " >",
                    sPrevious: "<",
                    sLast:">>",
                    sFirst:"<<"
                }
           }
        });	
	});
</script>
<table class="table table-bordered table-infinite" id="local_tbl" >
								<colgroup>
									<col class="con0" style="width:8%;"/>
									<col class="con1" style="width:45%;"/>
									<col class="con0" style="width:30%;"/>
									<!--<col class="con1" style="width:8%;"/>-->
									<col class="con0" style="width:12%;"/>
									<!--<col class="con1" style="width:9%;"/>-->
									<col class="con0" style="width:5%;"/>
								</colgroup>
								<thead>
									<tr>
										<th class="head0 center">Image</th>
										<th class="head1 center">Item</th>
										<th class="head0 center">Manufacturer</th>
										<!--<th class="head1 center">Def. Pack</th>-->
										<th class="head0 center">Def. Unit</th>
										<!--<th class="head1 center">Def. Price</th>-->
										<th class="head0 center">Delete</th>
									</tr>
								</thead>
								<tbody>
									<?php while($row2 = mysql_fetch_array($result2)){
	                                    $del = '';
	                                    if(count($undeletable) > 0){
	                                        if(in_array($row2['loc_item_id'],$undeletable)){
	                                            $del = ' del';
	                                        }
	                                    }
	                                    ?>
	                                    <tr class="gradeX line4"  style="height:17px;"> <?php /*?><?php echo $del; ?><?php */?>
	                                        <td><img onerror="this.src='images/noimage.png'" src="<?php echo APIIMAGE.'images/'.$row2['image']; ?>" style="height:30px; width:30px;" ></td>
	                                        <td><?php echo $row2['description'].' ( UID: '.$row2['loc_item_id'].' )'; ?></td>
	                                        <td><?php echo $row2['manufacturer'].' '.$row2['brand'].' '.$row2['model_number']; ?></td>
	                                        <!--<td><?php echo $row2['default_pack']; ?></td>-->
	                                        <td><?php echo $row2['unit_type']; ?></td>
	                                        <!--<td style="text-align:right;"><?php echo $row2['default_price']; ?></td>-->
	                                        <td style="text-align:center;"><input type="checkbox" <?php //if ($del) echo 'checked="checked"'; ?> name="delete[]" value="<?php echo $row2['loc_item_id']; ?>" /></td>
	                                    </tr>
	                                <?php } ?>
								</tbody>
							</table>
                            
                           