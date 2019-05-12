<?php 
include_once 'includes/session.php';
include_once("config/accessConfig.php");



function inventory_market_combo($locationID,$vendor_id,$selected) {
	if ($locationID!="") {
	$vendor_whrii = '';
	$vendor_whrLii = '';	
	if($vendor_id>0){
		$vendor_whrii = " AND ii.vendor_default='". $vendor_id ."'";
		$vendor_whrLii = " AND lii.default_vendor ='". $vendor_id ."'";
	}	
	$sql1 = "SELECT distinct(market) from ((SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_whrii)
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_whrLii)) as market ORDER BY market";
		//echo $sql1;exit;
		$output = mysql_query($sql1) or die(mysql_error());								
		$rows = mysql_num_rows($output);	
		$data .= '<option value="">- - - Select Market - - -</option>';	
		if ($rows > 0 && $rows != '') {				
			while ($result = mysql_fetch_assoc($output)) {
				$market = $result['market'];				
				if ($result['market'] == $selected) {
					$sel1 = ' selected="selected"';
				} else {
					$sel1 = '';
				}
				$data .= '<option value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -  No Market Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Market Found  - - - </option>';
    }    
return $data;
}


function inventory_group_combo($locationID,$req_market,$groupID) {

    
	$mval ='';
	$sqlval='';
	$limit = 500;
	if (isset($req_market)&& trim($req_market)!='') {
		$mval = $req_market;
		$sqlval =  " where ig.Market='".$mval."'";	
	}else{ 
		$limit = 0;
	}

    
	if ($locationID!="") {		
		$sql = 	"SELECT distinct(tbl.id),tbl.description from (
			(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global' $sqlval AND lii.location_id = '".$locationID."' 
		ORDER BY ig.description ASC)
UNION ALL 
		(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND  lii.type<>'global' $sqlval AND lii.location_id = '".$locationID."' 
		ORDER BY ig.description ASC)) as tbl ORDER BY description LIMIT $limit";
		//echo $sql;exit;
		$output = mysql_query($sql) or die(mysql_error());								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value="">- - - Select Group - - -</option>';
			while ($result = mysql_fetch_assoc($output)) {				
				$id = $result['id'];
				$description = $result['description'];
				if ($id == $groupID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Item Group Found - - - </option>';
    }    
    return $data;
}
 
 
 
if(isset($_POST) && $_POST['r_type']=='get_market'){
	echo inventory_market_combo($_SESSION['loc'],$_POST['Ser_vendors'],'');
	exit();
} 

if(isset($_POST) && $_POST['r_type']=='get_group'){
	echo inventory_group_combo($_SESSION['loc'],$_POST['Ser_market'],'');
	exit();
}

if(isset($_POST) && $_REQUEST['r_type'] =='sub_st_item'){
	
	
	
	$currect_strrom = mysql_real_escape_string($_POST['currect_strrom']);
	$ser_unit = $_POST['ser_unit'];
	$st_ins_item = $_POST['st_ins_item'];
	$st_qty = $_POST['st_qty'];
	for($i=0;$i<count($st_ins_item);$i++){
		$item_id = mysql_real_escape_string($st_ins_item[$i]);
		$unit_id = mysql_real_escape_string($ser_unit[$i]);
		$qty = mysql_real_escape_string($st_qty[$i]);		
		if($item_id>0 && $qty!=''){
			$check_if_exist = "SELECT id FROM location_inventory_storeroom_items WHERE location_id = '".$_SESSION['loc']."' AND storeroom_id = '".$currect_strrom."' AND inv_item_id = '".$item_id."'";
			$res_exist = mysql_query($check_if_exist);
			if(mysql_num_rows($res_exist)<1){
					$query = "INSERT INTO location_inventory_storeroom_items SET
						location_id='".$_SESSION['loc']."',
						storeroom_id='".$currect_strrom."',
						inv_item_id='".$item_id."'";
					$result = mysql_query($query) or die(mysql_error());	
					
					
					
					$count_q = "INSERT INTO location_inventory_counts SET 
								`location_id` = '".$_SESSION['loc']."', 
								`storeroom_id` = '".$currect_strrom."', 
								`inv_item_id` = '".$item_id."', 
								`Type` = 'Count', 
								`date_counted` = '".date('Y-m-d')."', 
								`time_counted` = '".date('H:i:s')."', 
								`quantity` = '".$qty."', 
								`unit_type` = '".$unit_id."', 
								`created_by` = '".$_SESSION['employee_id']."', 
								`created_on` = 'BusinessPanel', 
								`created_datetime` = NOW(), 
								`employee_id` = '".$_SESSION['employee_id']."'"; 
					mysql_query($count_q) or die(mysql_error());
					
					
			}
		}
	}
	echo true;
	exit();
	
}


function returnUnitTypes($select,$unitArr){
	foreach($unitArr as $unit){
			if($select == $unit['id']){
				$selected = "selected='selected'";
			}else{
				$selected ='';
			}
			if($opt_lbl != $unit['conversion_group']){
				$response .= '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($unit['conversion_group']) .'</option>';
			}
			$opt_lbl = $unit['conversion_group'];
			
			$response .= '<option '.$selected.' value="'.$unit['id'].'" data-description="'. $unit['description'] .'" >'.$unit['unit_type'].'</option>';
		}
    return $response;

}
 
if($_REQUEST['st'] != ''){
$st = $_REQUEST['st'];	

$unit_types5 = array();
	 $query10 = "SELECT *
		   FROM inventory_item_unittype
		   ORDER BY conversion_group,unit_type";
	
		   
$result10 = mysql_query($query10) or die(mysql_error());
while ($row10 = mysql_fetch_assoc($result10)) {
	$unit_types5[] = $row10;
}





?>

<div class="Searchform">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="10%">Vendor: </td>
            <?php 
                $qry_vendors = "SELECT distinct(id),name FROM vendors WHERE 
										id IN (SELECT DISTINCT(default_vendor)	FROM location_inventory_items WHERE 
											(default_vendor!='' AND default_vendor IS NOT NULL) AND location_id = '".$_SESSION['loc']."' AND type<>'global')
										OR 
										id IN (SELECT DISTINCT(vendor_default)	FROM inventory_items ii JOIN location_inventory_items lii ON lii.inv_item_id=ii.id  WHERE 
											(ii.vendor_default!='' AND ii.vendor_default IS NOT NULL) AND lii.location_id = '".$_SESSION['loc']."' AND lii.type = 'global')	
											ORDER BY vendors.name ASC";
				$rs_vendors = mysql_query($qry_vendors) or die($qry_vendors .'-----'. mysql_error());
				
			?>
            
            
            <td width="40%">
            	<select id="Ser_vendors" onChange="getSerMarket(this.value);"  style="width:86%; margin-top:10px;" name="Ser_vendors">
                	<option value="">- - - Select Vendor - - -</option>
                    <?php 
						while($row_vendors = mysql_fetch_assoc($rs_vendors)){
							$selected = ($_REQUEST['Ser_vendors'] == $row_vendors['id']) ? 'selected' : '';
							echo '<option value="'. $row_vendors['id'] .'"'. $selected .' >'. $row_vendors['name'] .' (ID:'. $row_vendors['id'] .')</option>';
						}
					?>
                </select>
            </td>
            <td width="10%">Market: </td>
            <td width="40%">
            	<select id="Ser_market" onChange="getSerGroup(this.value);" style="width:86%; margin-top:10px;" name="Ser_market">            		
                    <?php echo inventory_market_combo($_SESSION['loc'],$_REQUEST['Ser_vendors'],$_REQUEST['Ser_market']); ?>
                </select>
             </td>
        </tr> 
        <tr>
        	<td width="10%">Group: </td>
            <td width="40%">
                <select id="Ser_group" style="width:86%; margin-top:10px;" name="Ser_group">
                	<?php echo inventory_group_combo($_SESSION['loc'],$_REQUEST['Ser_market'],$_REQUEST['Ser_group']); ?>
                </select>
            </td>
            <td width="10%">Search: </td>
            <td width="40%"><input type="text" style="width:79%; margin-top:10px;" name="Ser_item" id="Ser_item" value="<?=$_REQUEST['Ser_item'];?>"></td>
        </tr>  
        <tr> <td colspan="100%" style="text-align:center;"><input type="button" class="btn btn-primary" id="serach_se_item" onclick="get_ser_items()" value="Search" /></td> </tr>
    </table>
    <br />
    <form name="submit_inv_store" id="submit_inv_store" action="" method="post" >
       <input type="hidden" name="currect_strrom" value="<?=$st;?>" />   
    <table width="100%" class="table table-bordered table-infinite" id="search_items_table">
    	<colgroup>                  
            <col class="con0" style="width:5%"/>
            <col class="con1" style="width:45%"/>            
            <col class="con0" style="width:35%"/>
            <col class="con1" style="width:15%"/>
       </colgroup>
       <thead>
            <tr>
                <th class="head0" style="">T</th>
                <th class="head1">Item</th>
                <th class="head0">Unit Type</th>
                <th class="head1 center">Qty</th>
			</tr>
       </thead>
       <tbody>   
       
       <?php
	   	if($_REQUEST['Ser_vendors']!='' || $_REQUEST['Ser_group']!='' || $_REQUEST['Ser_item']!=''){
			
			$strWhere = "";
			$strWhere1 = "";
			
			if($_REQUEST['Ser_vendors']!=''){
				$strWhere .= " AND lii.default_vendor = '".$_REQUEST['Ser_vendors']."' ";
				$strWhere1 .= " AND ii.vendor_default = '".$_REQUEST['Ser_vendors']."' ";
			}
			if($_REQUEST['Ser_group']!=''){
				$strWhere .= " AND ig.id = '".$_REQUEST['Ser_group']."' ";
				$strWhere1 .= " AND ig.id = '".$_REQUEST['Ser_group']."' ";
			}
			if($_REQUEST['Ser_market']!=''){
				$strWhere .= " AND ig.Market = '".$_REQUEST['Ser_market']."' ";
				$strWhere1 .= " AND ig.Market = '".$_REQUEST['Ser_market']."' ";
			}
			if($_REQUEST['Ser_item']!=''){
				$strWhere .= " AND lii.local_item_desc LIKE '%".$_REQUEST['Ser_item']."%' ";
				$strWhere1 .= " AND ii.description LIKE '%".$_REQUEST['Ser_item']."%' ";
			}
			
			
			
			
			$query2 = "SELECT tab.id,tab.description,tab.default_unit,tab.unit_name,tab.type FROM
						(SELECT lii.id,lii.local_item_desc description,iiu.id as default_unit,iiu.description as unit_name ,lii.type              
						   FROM location_inventory_items lii 
						   INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
						   left JOIN inventory_items ii ON ii.id=lii.inv_item_id
						   LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type			   			   			   
						   WHERE lii.location_id='".$_SESSION['loc']."' 
						   AND lii.type != 'global' AND lii.id NOT in(select inv_item_id FROM location_inventory_storeroom_items where location_id = '".$_SESSION['loc']."' AND storeroom_id='".$st."') $strWhere
					UNION ALL
						 SELECT lii.id,ii.description as description,iiu.id as default_unit,iiu.description as unit_name ,lii.type               
						   FROM location_inventory_items lii 						   
						   INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
						   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
						   LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type			   			   			   
						   WHERE lii.location_id='".$_SESSION['loc']."'
						   AND lii.type = 'global' AND lii.id NOT in(select inv_item_id FROM location_inventory_storeroom_items where location_id = '".$_SESSION['loc']."' AND storeroom_id='".$st."')  $strWhere1) as tab GROUP BY tab.id";
						   
						   
			$res2 = mysql_query($query2) or die(mysql_error());
			if($res2 && mysql_num_rows($res2)>0){ 
				while($row2 = mysql_fetch_assoc($res2)){
					$type ='';
					if(strtolower($row2['type'])=='prep'){
						$type = 'P';
					}else if(strtolower($row2['type'])=='local'){
						$type = 'L';
					}else{
						$type = 'G';	
					}
				
				 ?>
					<tr>
                    	
                    	<td><?=$type;?></td>
                        <td><?=$row2['description'];?></td>
                        <td>
							<div class="selectouter12 select_w3">
                                     <select name="ser_unit[]" class="ser_unitselect" style="margin: 0px;">
                                        <option value="">Unit Type</option>
										<?php 
                                            echo returnUnitTypes($row2['default_unit'],$unit_types5);
                                        ?>
                                      </select>
                            </div>  
                        </td>
                        <td>
                        <input type="text" style="width:75% !important" name="st_qty[]" value=""  />
                        <input type="hidden" name="st_ins_item[]" value="<?=$row2['id'];?>"  />
                        
                        </td>
                    </tr>
				<?php }
			}
		}
	   ?>    
       
       </tbody>  
    </table>
    </form>
</div>
<script>
	jQuery(document).ready(function($) {
        $('#search_items_table').dataTable({
            //"sPaginationType": "full_numbers",
			"bPaginate": false,
			"bFilter":false,
			"bInfo":false,
            "aaSorting": [],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
                
            }
        });
	});
	
function getSerMarket(val){
	jQuery.ajax({
		url:'get_location_item_notinstoreroom.php',
		type:'POST',
		data:{r_type:'get_market',Ser_vendors:val},
		success:function(data){
			jQuery("#Ser_market").html(data);
		}
	});
}
function getSerGroup(val){
	jQuery.ajax({
		url:'get_location_item_notinstoreroom.php',
		type:'POST',
		data:{r_type:'get_group',Ser_market:val},
		success:function(data){
			jQuery("#Ser_group").html(data);
		}
	});
}		
function get_ser_items(){
	var Ser_vendors = jQuery("#Ser_vendors").val();
	var Ser_market = jQuery("#Ser_market").val();
	var Ser_group = jQuery("#Ser_group").val();
	var Ser_item = jQuery("#Ser_item").val();
	
	if(Ser_vendors=='' && Ser_group=="" && Ser_item==''){
		jAlert("Please enter serach criteria","Alert Dialog");
		return false;
	}
	jQuery("#loading-header").show().css('z-index','9999');
	var st = '<?=$_REQUEST['st'];?>';
		jQuery.ajax({
			url:'get_location_item_notinstoreroom.php',
			type:'POST',
			data:{st:st,Ser_vendors:Ser_vendors,Ser_market:Ser_market,Ser_group:Ser_group,Ser_item:Ser_item},
			success:function(data){
				jQuery("#search_st_item_modal_body").html(data);
				jQuery("#search_st_item_modal").modal('show');
				jQuery('.ser_unitselect').msDropDown();				
				jQuery("#loading-header").hide().css('z-index','99');
			}
		});
}
</script>

<?php } ?>
