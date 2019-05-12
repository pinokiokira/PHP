<?php

require_once 'require/security.php';
include 'config/accessConfig.php';
require_once('require/openid-config.php'); 

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

//juni ->
$group_id  = '';
$marketval ='';
$sqlGroup = '';
$sqlmarket='';
if (isset($_GET['group_id'])&&trim($_GET['group_id'])!='') {
	$group_id = $_GET['group_id'];
	$sqlGroup =  " AND ig.id=".$group_id;
}

if (isset($_GET['market'])&& trim($_GET['market'])!='') {
	$marketval = $_GET['market'];	
	$sqlmarket =  " AND ig.Market='".$marketval."'";	
}

if(isset($_GET['vendor'])&& trim($_GET['vendor'])!='') {
	$vendor_val = trim($_GET['vendor']);
	$vendor_and_where = " AND ii.vendor_default = '". $vendor_val ."' ";
	$vendor_and_where1 = " AND lii.default_vendor = '". $vendor_val ."' ";
} else {
	$vendor_val = '';
	$vendor_and_where = '';
	//$vendor_and_where1 = " AND lii.default_vendor = '". $vendor_val ."' ";
	$vendor_and_where1 = "";
}

if($_POST['ids'] != '' && $_POST['delete']==""){
    $values = explode(',', $_POST['ids']);
    $time = date("H:i:s");
    $date = date("Y-m-d");
    $query = "SELECT storeroom_id FROM location_inventory_storerooms WHERE location_id='" . $_SESSION['loc'] . "' AND stroom_id='General'";
    $result = $rp->rp_query($query) or die(mysql_error());
    $num_rows = $rp->rp_affected_rows($result);
    if($num_rows < 1){
        $query2 = "INSERT INTO location_inventory_storerooms SET location_id='" . $_SESSION['loc'] . "', description='General Storeroom', stroom_id='General'";
        $result2 = $rp->rp_query($query2) or die(mysql_error());
        $storeroom = $rp->rp_dbinsert_id();
    }else{
        $row = $rp->rp_fetch_array($result);
        $storeroom = $row['storeroom_id'];
    }
    foreach($values as $val){
		//juni -> 2014-09-16 - default brand is not inserted
		//priority,total_count,total_needed
        //$query = "INSERT INTO location_inventory_items SET location_id = " . $_SESSION['loc'] . ", inv_item_id=" . $val . ", status='active'";
		$query = "INSERT INTO location_inventory_items SET location_id = '" . $_SESSION['loc'] . "', inv_item_id='" . $val . "', status='active', created_by = '" . $_SESSION['employee_id'] . "',created_on ='BusinessPanel',created_datetime='".date('Y-m-d H:i:s')."',priority='0',total_count='0.00',total_needed='0.00',default_brand=(SELECT max(lii.default_brand) as  default_brand FROM  location_inventory_items lii WHERE lii.inv_item_id='".$val."')";
		//echo $query;
        $result = $rp->rp_query($query) or die(mysql_error());
        $query3 = "INSERT INTO location_inventory_counts SET location_id = '" . $_SESSION['loc'] . "', inv_item_id='" . $rp->rp_dbinsert_id() . "', Type='Start', unit_type='0',quantity='0.00',date_counted='$date', time_counted='$time', employee_id='" . $_SESSION['employee_id'] . "', storeroom_id='$storeroom' ,created_by = '" . $_SESSION['employee_id'] . "',created_on ='BusinessPanel',created_datetime='".date('Y-m-d H:i:s')."'";
		//echo $query3;
        $result3 = $rp->rp_query($query3) or die(mysql_error());
    }
    //exit;
    if ($_POST["step"]!=""){
        $nextstep = intval($_POST["step"])+1;
        header("location:setup_process.php?step=".$nextstep);
    }
}


if(isset($_POST['delete'])){ 
	$undeletable=array();
	$tables = "";
    foreach($_POST['delete'] as $delete){
         $delete = $rp->add_security($delete);
        /*/*$query1 = "SELECT COUNT(COALESCE(liipd.id,liipd2.id,lili.id,lion.id,lird.id)) as id
                  FROM location_inventory_items lii
                  LEFT JOIN location_inventory_items_prep_details liipd ON liipd.inv_item_id=lii.id
                  LEFT JOIN location_inventory_items_prep_details liipd2 ON liipd2.ingredient_item_id=lii.id
                  LEFT JOIN location_inventory_line_items lili ON lili.inv_item_id=lii.id
                  LEFT JOIN location_inventory_order_needed lion ON lion.inv_item_id=lii.id
                  LEFT JOIN location_inventory_recipe_details lird ON lird.inv_item_id=lii.id
                  WHERE lii.id = '" . $delete . "' AND lii.location_id = '".$_SESSION['loc']."'
                  LIMIT 1";*/
		//echo $query;exit;
		//juni -> 2014-09-14  -> clause move up (in the location_inventory_counts table) -> bad sql
		// WHERE lii.id = " . $delete . " AND lic.type != 'Start'
       /* $result1 = $rp->rp_query($query1) or die(mysql_error());
        $num_rows1 = $rp->rp_affected_rows($result1);
		$rowds = $rp->rp_fetch_array($result1);*/
        //if(!$num_rows){
		//if($num_rows<1){
            //$query2 = "DELETE FROM location_inventory_items
                    //  WHERE id = " . $delete;
            //$result2 = $rp->rp_query($query2) or die(mysql_error());
        //}else{
           // $undeletable[] = $delete;
        //}
		
		$query = "select * from location_inventory_counts where  inv_item_id='".$delete."'";
		$result_q = $rp->rp_query($query) or die(mysql_error());
        $num_rows = $rp->rp_affected_rows($result_q);
		
		$row_q = $rp->rp_fetch_array($result_q);
		$prep_detail = $rp->rp_query("SELECT id from location_inventory_items_prep_details Where inv_item_id = '".$delete."' OR ingredient_item_id =  '".$delete."'");
		$prep_count = $rp->rp_affected_rows($prep_detail);
		$line = $rp->rp_query("SELECT lili.id from  location_inventory_line_items as lili JOIN  location_inventory_items as lii ON lili.inv_item_id = lii.id where lii.id = '".$delete."'");
		$recipi_details = $rp->rp_query("SELECT lili.id from  location_inventory_recipe_details as lili JOIN  location_inventory_items as lii ON lili.inv_item_id = lii.id where lii.id = '".$delete."'");
		$line_count = $rp->rp_affected_rows($line);
		$recipe_count = $rp->rp_affected_rows($recipi_details);
		if(($num_rows==0  && $line_count==0 && $recipe_count==0 && $prep_count==0 ) ||  ($prep_count==0 && $recipe_count==0 && $num_rows==1 && $row_q['Type']=='Start' && $row_q['quantity']=='0.00' && $rowds['id']==0 && $line_count==0) ){			
			$query5 = $rp->rp_query("DELETE FROM location_inventory_storeroom_items where inv_item_id='".$delete."'"); 
			$query3 = "DELETE FROM location_inventory_counts
                     WHERE inv_item_id='".$delete."' and Type='Start' and quantity='0.00'";
            $result3 = $rp->rp_query($query3) or die('Error2: '.mysql_error());	
			
			$query2 = "DELETE FROM location_inventory_recipe_details
					   WHERE location_id = '" . $_SESSION['loc'] . "' AND  inv_item_id = " . $delete;
				$result2 = $rp->rp_query($query2) or die('Error1: '.mysql_error());	
			
			$query2 = "DELETE FROM location_inventory_items
					   WHERE id = " . $delete;
				$result2 = $rp->rp_query($query2) or die('Error1: '.mysql_error());			
		
		}else
		{
		
			$check_query=$rp->rp_fetch_array($rp->rp_query("SELECT ii.description, 
									(SELECT COUNT(lipd.id) from  location_inventory_items_prep_details as lipd where lipd.inv_item_id = lii.id) as lipd_id,
									(SELECT COUNT(lipd2.id) from  location_inventory_items_prep_details as lipd2 where lipd2.ingredient_item_id = lii.id) as lipd2_id, 
									(SELECT COUNT(lili.id) from  location_inventory_line_items as lili where lili.inv_item_id = lii.id) as lili_id,
									(SELECT COUNT(lion.id) from  location_inventory_order_needed as lion where lion.inv_item_id = lii.id) as lion_id,
									(SELECT COUNT(lird.id) from  location_inventory_recipe_details as lird where lird.inv_item_id = lii.id) as lird_id,
									(SELECT COUNT(cout.id) from  location_inventory_counts as cout where cout.inv_item_id = lii.id AND Type<>'Start' AND quantity<>'0.00') as cout_id
									from location_inventory_items as lii JOIN inventory_items ii ON ii.id = lii.inv_item_id where lii.id = '".$delete."' AND lii.location_id = '".$_SESSION['loc']."'"));
									
			 $table .= $check_query['description'].' Is used in table:';
			 if($check_query['lipd_id']>0 || $check_query['lipd2_id']>0 ){
			 $table .='location_inventory_items_prep_details, ';	
			 }
			 if($check_query['lili_id']>0){
			 $table .='location_inventory_line_items, ';
			 }
			 if($check_query['lion_id']>0){
			 $table .='location_inventory_order_needed, ';
			 }
			 if($check_query['lird_id']>0){
			 $table .='location_inventory_recipe_details, ';
			 }
			 if($check_query['cout_id']>0){
			 $table .=' location_inventory_counts';
			 }
			 $table .= ' <br>';
			 $undeletable[] = $delete;
			 $table = preg_replace("/(\r?\n){2,}/", "\n\n", $table);
			 
		}
    }
	
	
}

/*
if(isset($_POST['delete'])){
    foreach($_POST['delete'] as $delete){
        $delete = $rp->add_security($delete);
        $query = "SELECT COALESCE(lic.id,lisi.id,liipd.id,liipd2.id,lili.id,lion.id,lird.id) as id
                  FROM location_inventory_items lii
                  LEFT JOIN location_inventory_counts lic ON lic.inv_item_id=lii.id AND lic.type != 'Start'
                  LEFT JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id=lii.id
                  LEFT JOIN location_inventory_items_prep_details liipd ON liipd.inv_item_id=lii.id
                  LEFT JOIN location_inventory_items_prep_details liipd2 ON liipd2.ingredient_item_id=lii.id
                  LEFT JOIN location_inventory_line_items lili ON lili.inv_item_id=lii.id
                  LEFT JOIN location_inventory_order_needed lion ON lion.inv_item_id=lii.id
                  LEFT JOIN location_inventory_recipe_details lird ON lird.inv_item_id=lii.id
                  WHERE lii.id = " . $delete . " 
                  LIMIT 1";
		//echo $query;exit;
		//juni -> 2014-09-14  -> clause move up (in the location_inventory_counts table) -> bad sql
		// WHERE lii.id = " . $delete . " AND lic.type != 'Start'
        $result = $rp->rp_query($query) or die(mysql_error());
        $num_rows = $rp->rp_affected_rows($result);
        if(!$num_rows){
            $query2 = "DELETE FROM location_inventory_items
                      WHERE id = " . $delete;
            $result2 = $rp->rp_query($query2) or die(mysql_error());
			
			$query3 = "DELETE FROM location_inventory_counts
                     WHERE inv_item_id='".$delete."' and Type='Start' and quantity='0.00'";
            $result3 = $rp->rp_query($query3) or die(mysql_error());	
        }else{
            $undeletable[] = $delete;
        }
    }
}

*/
/* 
$query1 = "SELECT ii.id,ii.description,ig.description as `group`,iiu.unit_type, lii.default_brand, lii.default_pack, lii.default_price 
          FROM inventory_items ii
          LEFT OUTER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
          INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
          LEFT OUTER JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
          WHERE lii.id IS NOT NULL AND ii.`status`='active'
		  AND lii.inv_item_id NOT IN (SELECT DISTINCT(inv_item_id) FROM location_inventory_items WHERE location_id=" . $_SESSION['loc'] . ")
		  " . $sqlGroup . "
          ORDER BY `group` ASC, description ASC"; */
//juni -> 14.09.2014
//if($vendor_val != ''){
 if($_REQUEST['group_id']>0 || $vendor_val != ''){
 		 $query1 = "SELECT ii.id,ii.description,ii.item_id,ig.description as `group`,iiu.unit_type, lii.default_brand,  lii.default_price,ii.image,ii.model_number,ii.brand,ii.manufacturer,ii.vendor_default, v.name as def_vendor 
          FROM inventory_items ii
          LEFT OUTER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
          INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
          LEFT OUTER JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
          LEFT JOIN vendors as v ON ii.vendor_default=v.id
          WHERE ii.`status`='active'
		  AND ii.id NOT IN (SELECT DISTINCT(inv_item_id) FROM location_inventory_items WHERE location_id=" . $_SESSION['loc'] . " AND inv_item_id > 0)
		  " .$sqlmarket . $vendor_and_where . $sqlGroup . "
          GROUP BY ii.id ORDER BY `group` ASC, description ASC";
		  
if(isset($_REQUEST['debug']) && $_REQUEST['debug']=='1'){
	echo '<br />........query1:'. $query1;
}
$result1 = $rp->rp_query($query1) or die(mysql_error());

 $query2 = "SELECT lii.id as loc_item_id, ii.id,ii.description,ig.description as `group`,iiu.unit_type,lii.default_brand,  lii.default_price,ii.image,ii.model_number,ii.brand,ii.manufacturer 
          	FROM location_inventory_items lii
          	INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
          	INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
          	LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
          	WHERE location_id = " . $_SESSION['loc'] .$sqlmarket. $vendor_and_where1 . $sqlGroup . " GROUP BY ii.id 
          	ORDER BY `group` ASC, description ASC";
		  //echo $query2; //lii.default_pack,
$result2 = $rp->rp_query($query2) or die(mysql_error());

if(isset($_REQUEST['debug']) && $_REQUEST['debug']=='1'){
	echo '<br />........query2:'. $query2;
}

$groups = array();
$curgroup = '';
while($row1 = $rp->rp_fetch_array($result1)){
    if($curgroup != $row1['group']){
        $curgroup = $row1['group'];
        $groups[] = $curgroup;
    }
}
if($result1 && $rp->rp_affected_rows($result1)>0){
	mysql_data_seek($result1,0);
}

//while($row2 = $rp->rp_fetch_array($result2)){
//
//}
//mysql_data_seek($result2,0);
// } // if($_REQUEST['group_id']>0) END
} // if($vendor_val != '') END

function jRender_inventory_group_combo1($nameAndID,$locationID,$groupID,$market, $cClass = null, $cStyle = null) {
	$rp = new db_class();
    $class = "input-xlarge" ;
	$mval ='';
	$sqlval='';
	
	
	

    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if (isset($_GET['market'])&& trim($_GET['market'])!='') {
	$mval = $_GET['market'];
	$sqlval =  " where ig.Market='".$mval."'";
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
		$sql = "SELECT distinct(ig.id) as id,ig.description 
			FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id				
				$sqlval	
		ORDER BY ig.description ASC" ;
		//echo $sql;exit;
		$output = $rp->rp_query($sql) or die(mysql_error());								
		$rows = $rp->rp_affected_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Item Group - - - </option>';
			while ($result = $rp->rp_fetch_array($output)) {
				//print_r($result);exit;
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
	}else{
	$data .= '<option value=""> - - -  No Item Group Found - - - </option>';
	}
    $data .= '</select>';
    return $data;
	
}
function jRender_inventory_market_combo($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null) {
	$rp = new db_class();
    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="market" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
		$sql = "SELECT distinct(ig.Market) as market
   FROM inventory_groups ig where market !='NULL' AND market !='All' ORDER BY market ASC" ; /*INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
    INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
where market !='NULL' AND market !='All' AND  lii.location_id ='".$locationID."'*/
		//echo $sql;exit;
		$output = $rp->rp_query($sql) or die(mysql_error());								
		$rows = $rp->rp_affected_rows($output);
		
		if ($rows > 0 && $rows != '') {
			$seall = '';
			if($_REQUEST['market']=='All'){
			$seall = 'selected="selected"';
			}	
			$data .= '<option value=""> - - -  Select Market - - - </option>';
			$data .= '<option '.$seall.' value="All">All</option>';
			while ($result = $rp->rp_fetch_array($output)) {
				//print_r($result);exit;
				
				$market = $result['market'];
				//echo $market;
				if ($result['market'] == $_REQUEST['market']) {
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
    $data .= '</select>';
return $data;
  
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<style>
	tr{ background: none !important; }
tr.selected {
    background: gray !important;
}
.ui-selected{
  background: gray !important;
}
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}

.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right
		!important;
	background-color: #333333 !important;
}

table.table tbody tr.ui-selected,table.table tfoot tr.ui-selected{background: #808080;}
table.table tbody tr.ui-selecting,table.table tfoot tr.ui-selecting{background: #FCDDCF;}
table.table tbody tr.del{background-color:rgba(255, 5, 0, 0.78); color:white;}
#global_tbl tbody{cursor:pointer;}

.btn-group .btn{
	margin-bottom: 0;
	margin-top: 0;
	padding: 10px 19px;
	height:19px;
}
.btn-red{
	background-color:#FF0000;
}
.btn-red:hover{
	background-color:#FF0000;
}


#local_tbl td:last-child{
	text-align:center;
}
.btn.disabled, .btn[disabled]{
	opacity: 0.90;
	
	}
.btn-success.disabled, .btn-success[disabled]{
	background: none repeat scroll 0 0 #86d628;
    border-color: #6db814;
}
/* .table th, .table td {
  border-top: 1px solid #dddddd;
  line-height: 20px;
  padding: 8px;
  text-align: left;
  vertical-align: top;
} */

/* .table th, .table td {
	line-height:12px;
}
table-bordered thead:last-child tr:last-child > th:last-child, .table-bordered tbody:last-child tr:last-child > td:last-child, .table-bordered tbody:last-child tr:last-child > th:last-child, .table-bordered tfoot:last-child tr:last-child > td:last-child, .table-bordered tfoot:last-child tr:last-child > th:last-child {
	line-height:12px;
} */

#global_tbl tr td:first-child {
	text-align: center;
}
#global_tbl tr td img {
	height: 40px !important;
	width: 40px !important;
	vertical-align: middle !important;
}
#local_tbl tr td:first-child {
	text-align: center;
}
#local_tbl tr td img {
	height: 40px !important;
	width: 40px !important;
	vertical-align: middle !important;
}

</style>


<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="js/jquery.uniform.min.js"></script> -->
<!-- <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> -->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js" ></script>
<script type="text/javascript">
var globalAllSel = new Array();
 jQuery(document).ready(function($){
        // dynamic table


        var t = jQuery('#global_tbl').dataTable({
        	select: true,
		        select: {
	                style: 'single'
	            },
            "sPaginationType": "full_numbers",
			"bDestroy": true,
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
            },
			"preDrawCallback": function( settings ) {
				jQuery("#global_tbl .ui-selected").each(function() {
					if(globalAllSel.indexOf(jQuery(this).attr('id')) == -1){
                    	globalAllSel.push(eval(jQuery(this).attr('id')));
					}
				});
				jQuery("#global_tbl .chk_gi").each(function(){					
					if(jQuery(this).is(':checked')){
						if(globalAllSel.indexOf(jQuery(this).data('row_id')) == -1){
							globalAllSel.push(eval(jQuery(this).data('row_id')));							
						}
					}
				});
				console.log(globalAllSel);
			}
	    	} );
		    t.off("select").on( "select", function( e, dt, type, indexes ) {
		        console.log( e, dt, type, indexes );
		    } );
		    t.off("select").on( "deselect", function( e, dt, type, indexes ) {
		         console.log( e, dt, type, indexes );
		    } );

        /*jQuery('#global_tbl').dataTable({
            "sPaginationType": "full_numbers",
			"bDestroy": true,
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
            },
			"preDrawCallback": function( settings ) {
				$("#global_tbl .ui-selected").each(function() {
					if(globalAllSel.indexOf($(this).attr('id')) == -1){
                    	globalAllSel.push(eval($(this).attr('id')));
					}
				});
				$("#global_tbl .chk_gi").each(function(){					
					if(jQuery(this).is(':checked')){
						if(globalAllSel.indexOf(jQuery(this).data('row_id')) == -1){
							globalAllSel.push(eval(jQuery(this).data('row_id')));							
						}
					}
				});
				console.log(globalAllSel);
			}
        });*/


        var jTable = jQuery('#local_tbl').dataTable({
        	select: true,
		        select: {
	                style: 'single'
	            },
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
		    jTable.off("select").on( "select", function( e, dt, type, indexes ) {
		        console.log( e, dt, type, indexes );
		    } );
		    jTable.off("select").on( "deselect", function( e, dt, type, indexes ) {
		        console.log( e, dt, type, indexes );
		    } );


       /* jTable = jQuery('#local_tbl').dataTable({
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
        });*/
        
        <?php if(count($undeletable) > 0){ ?>
            /*jAlert('The following item ids could not be deleted: <?php echo implode(',\n', $undeletable)?>.' +
                '\nThey have already been used in one or more of the' +
                '\nfollowing : location_inventory_items,' +
                '\nlocation_inventory_counts. These items ' +
                '\nhave been left highlighted in red.','Alert Dialog');*/
			jAlert("This Inventory item cannot be deleted at this time, it's currently active:\n <?php echo $table; ?>","Alert Dialog");
        <?php } ?>

        var selectedList = new Array();
        var globalSel = new Array();
        var localSel = new Array();
        $("#global_tbl").selectable({			
            filter: ".g_item",
            stop: function() {
				//jQuery('#add').removeClass('disabled');
				//jQuery('#add').addClass("btn-success");
                globalSel = [];
                $(".ui-selected", this ).each(function() {
					console.log($(this).attr('id'));
					console.log(globalSel.indexOf($(this).attr('id')));
					if(globalSel.indexOf($(this).attr('id')) == -1){
                    	globalSel.push(eval($(this).attr('id')));
						console.log('adding items1');
						console.log(globalSel);
					}
                });
				console.log(globalSel);
				$("#global_tbl .chk_gi").each(function(){
					console.log('checked => '+jQuery(this).is(':checked')+'=>'+jQuery(this).data('row_id'));
					if(jQuery(this).is(':checked')){
						if(globalSel.indexOf(jQuery(this).data('row_id')) == -1){
							globalSel.push(eval(jQuery(this).data('row_id')));
							console.log('adding items');
							console.log(globalSel);
						}
					}
				});
				if(globalSel.length > 0){
					jQuery('#add').removeClass('disabled');
					jQuery('#add').addClass("btn-success");
				} else {
					jQuery('#add').addClass('disabled');
					jQuery('#add').removeClass("btn-success");
				}
				
				console.log(globalSel);
            }
        });
		
		jQuery('#chk_all_gi').live('click', function(){
			globalSel = [];
			jQuery('.chk_gi').attr('checked', false);
			jQuery(this).closest('tr').removeClass('ui-selected');
			jQuery('.g_item').each(function(i, v){
				var tr_id = jQuery(v).attr('id');				
				if(jQuery('#chk_all_gi').is(':checked')){
					jQuery('#chk_gi_'+ tr_id).attr('checked', true);					
					jQuery(this).closest('tr').addClass('ui-selected');
					if(globalSel.indexOf(eval(tr_id)) == -1){
						globalSel.push(eval(tr_id));
					}
				} else {
					console.log(globalSel);
					globalSel = [];
					globalAllSel = [];
					jQuery('#chk_gi_'+ tr_id).attr('checked', false);
					jQuery(this).closest('tr').removeClass('ui-selected');
				}
			});			
			
			console.log(globalSel.length);
			if(globalSel.length > 0){
				jQuery('#add').removeClass('disabled');
				jQuery('#add').addClass("btn-success");
			} else {
				jQuery('#add').addClass('disabled');
				jQuery('#add').removeClass("btn-success");
			}
		});
		jQuery(document).on('click','#global_tbl .chk_gi', function(e) {
			var chk_row_id = jQuery(this).data('row_id');
			console.log(chk_row_id);
			if(jQuery(this).is(':checked')){
                jQuery(this).closest('tr').addClass('ui-selected');
				
				if(globalSel.indexOf(chk_row_id) == -1) {
					globalSel.push(eval(chk_row_id));
				}
            }else{
                jQuery(this).closest('tr').removeClass('ui-selected');				
				if(globalSel.indexOf(chk_row_id) != -1) {										
					console.log(globalSel);
					globalSel.splice(globalSel.indexOf(chk_row_id), 1);
					globalAllSel.splice(globalAllSel.indexOf(chk_row_id), 1);
					console.log('after Remove');
					console.log(globalSel);
				}
            }

			
			console.log(globalSel);
			console.log(globalSel.length);
			if(globalSel.length > 0){
				jQuery('#add').removeClass('disabled');
				jQuery('#add').addClass("btn-success");
			} else {
				jQuery('#add').addClass('disabled');
				jQuery('#add').removeClass("btn-success");
			}
			jQuery('#chk_all_gi').attr('checked', false);
		});
		
		
        $('#add').click(function(){
			console.log(globalSel);
			if(globalAllSel.length>0){
				$.each(globalAllSel, function( key, value ) {
					if(globalSel.indexOf(value) == -1) {
						globalSel.push(eval(value));	  
					}
				});
			}
            if(globalSel.length > 0){
                var rows = $('#global_tbl .ui-selected').clone();
                $('#global_tbl .ui-selected').remove();
                
				rows.splice(0, 1);
                rows.each(function(){					
                    var id = $(this).attr('id'), rdata = [];
					$(this).find('td:last-child').remove();
					$('<td style="text-align:center;"><input type="checkbox" value="'+id+'" name="delete[]"></td>').appendTo(this);
                    $('td', this).each(function(i, v){						
						rdata.push( this.innerHTML );
                    });					
                    $('#local_tbl').dataTable().fnAddData(rdata);
                });
                selectedList = selectedList.concat(globalSel);                
                if(selectedList.length > 0 || $('tr.del').length > 0){
					$("#loading-header").show();
                    $('#ids').val(selectedList.join(','));                    
					$.ajax({
						url:'setup_backoffice_global_items_insert.php?group_id=<?php echo $_GET['group_id']; ?>&market=<?php echo $_GET['market'];?>&vendor=<?=$_GET['vendor'];?>',
						type:'POST',
						data:{ids:$('#ids').val()},
						success:function(data){
							if(data){
								jQuery('#g_items').html(data);
								globalSel = [];
								globalAllSel = [];
								selectedList = new Array();
								$('#ids').val('');
								var step = '<?php echo $_REQUEST['step']; ?>';
								if(step==5){								
									window.location.href='setup_process.php?step=6';
								}else {
									location.reload();
								}
							}
							getLeftitems();
						}						
					});
                }else{
                    jAlert('Please add items to the local list before submitting!','Alert Dialog');
                    return false;
                }
				
            }
            return false;
        });

        /* no longer use */
        $('#remove').click(function(){			
        	if(selectedList.length > 0 || $('tr.del').length > 0){
				jConfirm('Do you want to delete the selected item?','Confirm Dialog',function(r){
				if(r){
                $('#ids').val(selectedList.join(','));
                $('#global_frm').submit();
				}
				});
            }else{
                jAlert('Please add items to the local list before submitting!','Alert Dialog');
                return false;
            }
           	
        });
        
        $('#submit_btn').click(function(){
            if(selectedList.length > 0 || $('tr.del').length > 0){
                $('#ids').val(selectedList.join(','));
                $('#global_frm').submit();
            }else{
                jAlert('Please add items to the local list before submitting!','Alert Dialog');
                return false;
            }
        });
        
        $(document).on('click', '#local_tbl input[type=checkbox]', function(){
            if($(this).is(':checked')){
                $(this).closest('tr').addClass('del');
				
            }else{
                $(this).closest('tr').removeClass('del');
				//jQuery('#remove').removeClass("btn-success");
            }
        });
		
		jQuery(document).live('click','local_tbl input[type=checkbox]', function(e) {
				var aTrs = jTable.fnGetNodes();
				var checked = 0;
				for ( var i=0 ; i<aTrs.length ; i++ ){
					if(jQuery('input:checked', aTrs[i]).val()){
						checked++;
					}
				}				
				if (checked > 0) {
					jQuery('#remove').addClass("btn-red");
				} else {
					jQuery('#remove').removeClass("btn-red");
				}
		});

		// if (jQuery('#group_id').val()) {
		if((jQuery('#dummy_market').val() != '' && jQuery('#group_id').val() != '') || jQuery('#vendor').val() != ''){
			  jQuery('#global_items_left').show();
			  jQuery('#global_items_right').show();
		// }
		}
		
		
		setTimeout(function(){
			jQuery('#vendor').trigger('change');
		}, 100);
		
		
		
		
		
    });
//jQuery(document).live('change','#dummy_market',function(){
	//jQuery('#dummy_market').live('change',function(){
	function Getgroup(market){
	  //var market = jQuery(this).val();
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup1.php",
		data: { market: market}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group_id").html(msg);
		});
	
	}
function getLeftitems(){
	var vendor = jQuery("#vendor").val();
	var market = jQuery("#dummy_market").val();
	var group_id = jQuery("#group_id").val();
	jQuery.ajax({
		url:'setup_backoffice_global_items_left.php',
		type:'POST',
		data:{vendor:vendor,market:market,group_id:group_id},
		success:function(data){
			jQuery('#left_partData').html(data);
			jQuery("#loading-header").hide();
		}
	});

}		
function group_formSubmit(){
	if(jQuery('#group_id').val() == '') {
		jAlert('Please Select a Group','Alert Dialog');
		return false
	} 
	if(jQuery('#group_id').val()=='' && jQuery('#vendor').val() == '' && jQuery('#dummy_market').val()==''){
		// jAlert('Please Select an Item Group','Alert Dialog');
		jAlert('Please Select Market and a Group to proceed!','Alert Dialog');
		return false
	} 
	document.group_form.submit();
}
</script>
</head>

<body>
	<div class="mainwrapper">

		<?php include_once 'require/top.php';?>

		<div class="leftpanel">

			<?php include_once 'require/left_nav.php';?>

		</div>
		<!-- leftpanel -->
		<div class="rightpanel">

			<ul class="breadcrumbs">
				<li><a href="dashboard.php"><i class="iconfa-home"></i> </a> <span
					class="separator"></span></li>
				<li>Setup</li>
				<li><span class="separator"></span></li>
                <li>Inventory</li>
				<li><span class="separator"></span></li>
				<li>Global Items</li>
				<li class="right"><a href="" data-toggle="dropdown"
					class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
					<ul class="dropdown-menu pull-right skin-color">
						<li><a href="default">Default</a></li>
						<li><a href="navyblue">Navy Blue</a></li>
						<li><a href="palegreen">Pale Green</a></li>
						<li><a href="red">Red</a></li>
						<li><a href="green">Green</a></li>
						<li><a href="brown">Brown</a></li>
					</ul>
				</li>
                <?php require_once("lang_code.php");?>
			</ul>

			<div class="pageheader">
				<!--juni - 2014-09-14 - add dropdown-->
				<form action="<?=basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'];?>" name="group_form" id="group_form" method="get">
				<div style="margin-top: 16px; float:right">
                                    <input type="hidden" name="step" value="<?php echo $_GET["step"]?>" />
					<a href="#" class="btn btn-primary btn-large" onClick="group_formSubmit()">Go</a>
                    <!--<a href="#" style="color:#FFFFFF" class="btn btn-success btn-large" onClick="Add_new_item()">Add</a>-->
				</div>
						
						<?=jRender_inventory_group_combo1('group_id',$_SESSION['loc'],$group_id,'dummy',$_REQUEST['market'],'float:right; width:150px; height:43px;padding: 8px 8px;margin:16px 10px 0 0;');?>
						
		<?php /*?><?=jRender_inventory_market_combo('dummy_market',$_SESSION['loc'],$group_id,'dummy-market','float:right; width:280px; height:43px;padding: 8px 8px;margin:16px 10px 0 0;');?><?php */?>
					<?php
                        // $qry_inventory_market = "SELECT * FROM inventory_market";
						$qry_inventory_market = "SELECT * FROM inventory_market";
                        $rs_inventory_market = $rp->rp_query($qry_inventory_market) or die($qry_inventory_market .'-----'. mysql_error());
                    ?>
                    
                    <select class="dummy-market" onChange="Getgroup(this.value);"  id="dummy_market" style="float: right;height: 43px;margin: 16px 10px 0 0;padding: 8px; width: 140px;" name="market">
                        <option value="">- - - Select Market - - -</option>
                        <?php
                            while($row_inventory_market = $rp->rp_fetch_array($rs_inventory_market)){
								$selected = ($_REQUEST['market'] == $row_inventory_market['description']) ? 'selected' : '';
                                echo '<option '. $_REQUEST['market'] .' value="'. $row_inventory_market['description'] .'" data-id='. $row_inventory_market['id'] .' '. $selected .' >'. $row_inventory_market['description'] .'</option>';
                            }
                        ?>
                    </select>
                    
                    <?php
						$qry_vendors = "SELECT * FROM vendors WHERE id IN ( SELECT DISTINCT(vendor_default) FROM inventory_items WHERE (vendor_default!='' AND vendor_default IS NOT NULL) ) ORDER BY vendors.name ASC";
                        $rs_vendors = $rp->rp_query($qry_vendors) or die($qry_vendors .'-----'. mysql_error());
						
                    ?>
                    <select id="vendor" name="vendor" class="dummy-market" style="float: right;height: 43px;margin: 16px 10px 0 0;padding: 8px; width: 150px;" onChange="change_vendor(); " >
                    	<option value="">- - - Select Vendor - - -</option>
                        <?php
							while($row_vendors = $rp->rp_fetch_array($rs_vendors)){
                                $selected = ($_REQUEST['vendor'] == $row_vendors['id']) ? 'selected' : '';
                                echo '<option value="'. $row_vendors['id'] .'" data-id='. $row_vendors['id'] .' '. $selected .' >'. $row_vendors['name'] .' (ID:'. $row_vendors['id'] .')</option>';
                            }
                        ?>
                    </select>
				</form>			
				<div class="pageicon"><span class="iconfa-cog"></span></div>
				<div class="pagetitle">
					<h5>Global items are linked to our vendors and can be easily added to your location. Hold the CTRL key to add more than one at a time</h5>
					<h1>Global Items</h1>

				</div>
			</div>
			<!--pageheader-->
		<form id="global_frm" method="post">
			<input type="hidden" name="step" value="<?php echo $_GET["step"]?>" />
			<div class="maincontent">
				<div class="maincontentinner">
				
					<div class="row-fluid">
						<div class="span6" style="display:none;" id="global_items_right">
							<div >
								<div class="btn-group pull-right">
                                    <a id="add" class=" disabled btn btn-large"><h4>Add</h4></a>
                                </div>
                                
                                <h4 class="widgettitle">Global Items</h4>
							</div>
                            <div class="widgetcontent">
                            	<div id="left_partData">
								<table class="table table-bordered table-infinite" id="global_tbl" >
								<!-- <colgroup>
									<col class="con0" style="width:8%;"/>
									<col class="con1" style="width:45%;"/>
									<col class="con0" style="width:30%;"/>
									<col class="con1" style="width:8%;"/>
									<col class="con1" style="width:12%;"/>
									<col class="con1" style="width:9%;"/>
                                    <col class="con0" style="width: 5%;"/>
								</colgroup> -->

								<colgroup>
									<col class="con0" style="width:8%;">
									<col class="con1" style="width: 38%;">
									<col class="con0" style="width: 25%;">
									<col class="con1" style="width: 14%;">
									<col class="con1" style="width: 10%;">
									<!--<col class="con1" style="width:9%;"/>-->
                                    <col class="con0" style="width: 5%;">
								</colgroup>
								<thead>
									<tr>
										<th class="head0 center">Image</th>
										<th class="head1 center">Item Description</th>
										<th class="head0 center">Manufacturer</th>
										<th class="head1 center">Def. Vendor</th>
										<th class="head1 center">Def. Unit</th>
										<!--<th class="head1 center">Def. Price</th>-->
                                        <th class="head0 center">
                                        	<input type="checkbox" id="chk_all_gi" name="chk_all_gi" />
                                        </th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($query1!=''){
										$result1 = $rp->rp_query($query1) or die(mysql_error());
										while($row1 = $rp->rp_fetch_array($result1)){ ?>                                    	
	                                    <tr class="gradeX g_item gradeX<?php echo $row1['id']; ?>" id="<?php echo $row1['id']; ?>" style="height:17px;" rel="<?php echo $row1['id']; ?>">
	                                        <td style="text-align: center;"><img onerror="this.src='images/noimage.png'" src="<?php echo APIIMAGE.'images/'.$row1['image']; ?>" style="height:40px; width:40px; vertical-align: middle;" ></td>
	                                        <td><?php echo $row1['description']. ' (ID:'.$row1['item_id'].' UID:'.$row1['id'].')';?></td>
	                                        <td><?php echo $row1['manufacturer'].' '.$row1['brand'].' '.$row1['model_number']; ?></td>
	                                        <!--<td><?php echo $row1['default_pack']; ?></td>-->
	                                        <td><?php echo $row1['def_vendor']; ?></td>
	                                        <td><?php echo $row1['unit_type']; ?></td>
	                                        <!--<td style="text-align:right;"><?php echo $row1['default_price']; ?></td>-->
                                            <td class="head0 center">
                                                <input type="checkbox" id="chk_gi_<?php echo $row1['id']; ?>" name="chk_gi[]" class="chk_gi" data-row_id="<?php echo $row1['id']; ?>" />
                                            </td>
	                                    </tr>
	                                <?php } 
									}
									?>
								</tbody>
							</table>
                            </div>
                            </div>
						</div>
                        
						<div class="span6" style="float:right;display:none;margin-left:10px" id="global_items_left">
							<div >
								<div class="btn-group pull-right">
	                            	<a id="remove" class="btn btn-large"><h4>Remove</h4></a>
	                           	</div>
                                <h4 class="widgettitle">Global Items Used at this Location</h4>
							</div>
                            <div class="widgetcontent">
                            <div id="g_items">
							<table class="table table-bordered table-infinite" id="local_tbl" >
								<colgroup>
									<col class="con0" style="width:8%;"/>
									<col class="con1" style="width:45%;"/>
									<col class="con0" style="width:30%;"/>
									<!--<col class="con1" style="width:8%;"/>-->
									<col class="con1" style="width:12%;"/>
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
									<?php 
										if($result2 != '') {
											while($row2 = $rp->rp_fetch_array($result2)){
											$del = '';
											if(count($undeletable) > 0){
												if(in_array($row2['loc_item_id'],$undeletable)){
													$del = ' del';
												}
											}
											?>
											<tr class="gradeX line4"  style="height:17px;"> <?php /*?><?php echo $del; ?><?php */?>
												<td style="text-align: center;"><img onerror="this.src='images/noimage.png'" src="<?php echo APIIMAGE.'images/'.$row2['image']; ?>" style="height:40px; width:40px; vertical-align: middle;" ></td>
												<td><?php echo trim(str_replace('   ', '', $row2['description'])).' (UID: '.$row2['loc_item_id'].')'; ?></td>
												<td><?php echo $row2['manufacturer'].' '.$row2['brand'].' '.$row2['model_number']; ?></td>
												<!--<td><?php echo $row2['default_pack']; ?></td>-->
												<td><?php echo $row2['unit_type']; ?></td>
												<!--<td style="text-align:right;"><?php echo $row2['default_price']; ?></td>-->
												<td style="text-align:center;"><input type="checkbox" <?php //if ($del) echo 'checked="checked"'; ?> name="delete[]" value="<?php echo $row2['loc_item_id']; ?>" /></td>
											</tr>
	                                <?php 	} 
										}
									?>
								</tbody>
							</table>
                            </div>
                            </div>
						</div>
					</div>
				
            		<!--row-fluid-->
					<?php include_once 'require/footer.php';?>
					<!--footer-->

				</div>
				<!--maincontentinner-->
			</div>
			<!--maincontent-->
		<input type="hidden" value="" name="ids" id="ids"/>
		</form>	
		</div>
		<!--rightpanel-->

	</div>
	<!--mainwrapper-->

</body>
</html>
<script>
	function enable(){
	    if(first){
			jQuery('#submit_btn').addClass("btn-primary");
	        jQuery('#submit_btn').removeAttr("disabled").click(function(){
	            jQuery('#count_frm').submit();
	        });
	        first = false;
	    }
	}
	
	
	
/*
try{
    function filter (term, _id,cols,type){
        $('#group_search').val('');
        var suche = term.toLowerCase();
        var table = document.getElementById(_id);
        var ele;
        if(type == 'search'){
            for (var r = 2; r < table.rows.length; r++){
                for(var i=0;i<=cols;i++){
                    ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g,"");
                    if (ele.toLowerCase().indexOf(suche)>=0 ){
                        table.rows[r].style.display = '';
                        table.rows[r].setAttribute("class", "line1 g_item ui-selectee");
                        break;
                    }else{
                        table.rows[r].style.display = 'none';
                        table.rows[r].setAttribute("class", "");
                    }
                }
            }
        }else{
            for (r = 2; r < table.rows.length; r++){
                table.rows[r].style.display = '';
                table.rows[r].setAttribute("class", "line1 g_item ui-selectee");
            }
        }
    }
    function filterGroup (term, _id,cols,type){
        var suche = term.toLowerCase();
        var table = document.getElementById(_id);
        var ele;
        if(type == 'search'){
            for (var r = 1; r < table.rows.length; r++){
                for(var i=0;i<=cols;i++){
                    ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g,"");
                    if (ele.toLowerCase().indexOf(suche)>=0 ){
                        table.rows[r].style.display = '';
                        table.rows[r].setAttribute("class", "line1 g_item ui-selectee");
                        break;
                    }else{
                        table.rows[r].style.display = 'none';
                        table.rows[r].setAttribute("class", "");
                    }
                }
            }
        }else{
            for (r = 2; r < table.rows.length; r++){
                table.rows[r].style.display = '';
                table.rows[r].setAttribute("class", "line1 g_item ui-selectee");
            }
        }
    }
} catch(ex){
}
*/
function Add_new_item(){
jQuery.ajax({
	url:'Add_newitem_inv.php',
	type:'POST',
	success:function(data){
		jQuery("#mymodal_html5").html(data);
		jQuery("#mymodal5").modal('show');
	}
});


}

function change_vendor(){
	var vendor_val = jQuery('#vendor').val();
	// if(vendor_val != ''){
		return xhr = jQuery.ajax({
			url: 'get_market_from_vendor.php'
			,type: 'GET'
			,data: { 
				'vendor_default': vendor_val
				
			}
			,dataType: 'JSON' 
			,success: function (res) {
				if(res.ResponseCode == '1'){
					var options_market = res.Response.data.options_market;
					jQuery('#dummy_market').html(options_market);
					jQuery('#dummy_market').val('<?php echo $_REQUEST['market']; ?>');
				}
			}
		});
	// } // if(vendor_val != '') END
}

</script>
<div id="mymodal5" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add Inventory Item</h3>
	</div>
    <form action="manage_inv_item_add.php" enctype="multipart/form-data" onSubmit="return validate_item()" method="post" name="add_items" id="add_items">
	<div class="modal-body" id="mymodal_html5">	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn">Cancel</a>
		<button type="submit" name="editvendorsubmit" value="submitted" href="javascript:void(0);" class="btn btn-primary">Submit</button>
	</div>
    </form>
</div>

<script type="text/javascript">
jQuery(function(){

		jQuery("tbody").on('click','tr',function(){
	
	jQuery('.gradeX').removeClass('ui-selectee ui-selected');
	var clid= jQuery(this).attr("rel");
	jQuery('.gradeX'+clid).addClass('ui-selectee ui-selected');
	
	});
	
	 jQuery('#global_tbl_paginate').click( (event) => {
      if(jQuery(event.target).attr('class').indexOf('disabled') <= 1)
        jQuery('#global_tbl').find('tr').each( (k,v) =>{
          jQuery(v).removeClass('ui-selected');
          jQuery(v).removeClass('ui-selectee');
        });
    });
});

	jQuery(function(){
		jQuery('.gradeX').live('click',function(){
			//console.log('yes');
			jQuery('.gradeX').css('background','none');
			jQuery(this).css('background', 'none repeat scroll 0 0 gray');
			
			jQuery('.gradeX').removeClass('ui-selectee ui-selected');
			var clid= jQuery(this).attr("rel");
			jQuery('.gradeX'+clid).addClass('ui-selectee ui-selected');

		});

	});

	jQuery("tbody").on('click','tr',function(){
	
			jQuery('.gradeX').removeClass('ui-selectee ui-selected');
			var clid= jQuery(this).attr("rel");
			jQuery('.gradeX'+clid).addClass('ui-selectee ui-selected');
			
		});

</script>