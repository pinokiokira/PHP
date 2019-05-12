<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
require_once('require/openid-config.php'); 

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();



function hideZeros($val){
	$rp = new db_class();
    if($val == 0){
        return '';
    }else{
        return $val;
    }
}
if($_POST['conf'] == 1){
    if($_POST['active'] != ''){
        foreach($_POST['active'] as $active){
            $query = "UPDATE `location_inventory_items` SET `status` = 'inactive' WHERE `id` = " . $rp->add_security($active);
            $result = $rp->rp_query($query) or die(mysql_error());
        }
    }

    $str = substr($_POST['inactive_ids'], 0, strlen($_POST['inactive_ids'])-1);
    if($str != ''){
        $ids = explode(",",$str);
        if(!empty($ids)){
            foreach($ids as $id){
                if(isset($_POST['inactive'])){
                    if(!in_array($id,$_POST['inactive'])){
                        $query = "UPDATE location_inventory_items SET status='active' WHERE id=" . $rp->add_security($id);
                        $result = $rp->rp_query($query) or die(mysql_error());
                    }
                }else{
                    $query = "UPDATE location_inventory_items SET status='active' WHERE id=" . $rp->add_security($id);
                    $result = $rp->rp_query($query) or die(mysql_error());
                }

            }
        }
    }
}

if(isset($_POST['delete_item_id'])){ 
	$undeletable=array();
	$table = "";
    $delete = $rp->add_security($_POST['delete_item_id']);
         $delete = $rp->add_security($delete);
        
		$query = "select * from location_inventory_counts where  inv_item_id='".$delete."'";
		$result_q = $rp->rp_query($query) or die(mysql_error());
        $num_rows = $rp->rp_affected_rows($result_q);
		
		$row_q = $rp->rp_fetch_array($result_q);
		
		$line = $rp->rp_query("SELECT lili.id from  location_inventory_line_items as lili JOIN  location_inventory_items as lii ON lili.inv_item_id = lii.id where lii.id = '".$delete."'");
		$line_count = $rp->rp_affected_rows($line);
		if(($num_rows==0  && $line_count==0) ||  ($num_rows==1 && $row_q['Type']=='Start' && $row_q['quantity']=='0.00' && $rowds['id']==0 && $line_count==0) ){			
			$query5 = $rp->rp_query("DELETE FROM location_inventory_storeroom_items where inv_item_id='".$delete."'"); 
			$query3 = "DELETE FROM location_inventory_counts
                     WHERE inv_item_id='".$delete."' and Type='Start' and quantity='0.00'";
            $result3 = $rp->rp_query($query3) or die('Error2: '.mysql_error());	
			
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
									
			 $table .= $check_query['description'].' Is used in; ';
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
			 $table .= ' Tables.';
			 $undeletable[] = $delete;
			 
		}
    
	
	
}
if(isset($_REQUEST['group_id'])){
$vendor_where1 = '';
$vendor_where2 = '';		
if($_REQUEST['group_id']>0){
	if($_REQUEST['market']!=""){
		$market_where  = " AND ig.Market= '".$_REQUEST['market']."'";
	}
	$group_where = " AND ig.id= '".$_REQUEST['group_id']."'";
}
if($_REQUEST['vendor']>0){
	
	$vendor_where1 =" AND ii.vendor_default = '". $_REQUEST['vendor'] ."'";
	$vendor_where2 =" AND lii.default_vendor = '". $_REQUEST['vendor'] ."'";
}
$query1 = "(SELECT DISTINCT lii.inv_item_id,ii.image as local_item_image,ii.item_id AS item_code, lii.id,lii.local_item_desc as description, ig.description as `group`, lii.`type`, lii.`status`, lii.`priority`, iiu.`unit_type`, ii.id as item_id,lii.taxable,lii.ecommerce,lii.default_vendor,lii.default_brand,ve.name as vendor_name,lii.manufacturer_barcode
            FROM location_inventory_items lii
            LEFT JOIN inventory_items ii ON lii.inv_item_id=ii.id
            LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
			LEFT JOIN vendors ve ON ve.id=lii.default_vendor
            WHERE lii.location_id=" . $_SESSION['loc'] . " and lii.type = 'global' $market_where $group_where $vendor_where1 )
        UNION ALL
            (SELECT DISTINCT 0 as inv_item_id,lii.local_item_image,'' AS item_code,lii.id,lii.local_item_desc as description, ig.description as `group`, lii.`type`, lii.`status`, lii.`priority`, iiu.`unit_type`, lii.local_item_id as item_id,lii.taxable,lii.ecommerce,lii.default_vendor,lii.default_brand,ve.name as vendor_name
				,lii.manufacturer_barcode
            FROM location_inventory_items lii
            LEFT JOIN inventory_groups ig ON lii.local_group_id=ig.id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
			LEFT JOIN vendors ve ON ve.id=lii.default_vendor
            WHERE lii.location_id=" . $_SESSION['loc'] . " $market_where $group_where $vendor_where2 and lii.type<>'global'
            ) ORDER BY priority ASC, `item_id` ASC, `group` ASC";
if($_REQUEST['aj']!=''){
	echo '<br><br>'.$query1.'<br><br>';
}

$result1 = $rp->rp_query($query1) or die(mysql_error());
}
//For each item of the location, check if it is used in any associated tables and add to array if not used; array key is location_inventory_item id
		$deleteable = array();
		 $query2 = "SELECT lii.id
           FROM location_inventory_items lii
           LEFT JOIN location_inventory_counts lic ON lic.inv_item_id=lii.id AND lic.location_id=" . $_SESSION['loc'] . "
           LEFT JOIN location_inventory_order_needed lion ON lii.id=lion.inv_item_id AND lion.location_id=" . $_SESSION['loc'] . "
           LEFT JOIN location_inventory_line_items lili ON lili.inv_item_id=lii.id AND lili.location_id=" . $_SESSION['loc'] . "
           LEFT JOIN purchase_items pi ON lii.id=pi.inv_item_id AND pi.location_id=" . $_SESSION['loc'] . "
           LEFT JOIN location_inventory_recipe_details lird ON lird.inv_item_id=lii.id AND lird.location_id=" . $_SESSION['loc'] . "
           LEFT JOIN location_inventory_items_prep_details liipd ON liipd.ingredient_item_id=lii.id AND liipd.location_id=" . $_SESSION['loc'] . "
           WHERE lii.location_id=" . $_SESSION['loc'] . " AND lic.id is null AND lion.id is null AND lili.id is null AND lird.id is null AND liipd.id is null AND pi.id is null";
$result2 = $rp->rp_query($query2) or die(mysql_error());


while($row2 = $rp->rp_fetch_array($result2)){
  $deleteable[$row2['id']] = 1;
	}
$group_id  = '';
if (isset($_GET['group_id'])&&trim($_GET['group_id'])!='') {
	$group_id = $_GET['group_id'];	
}

function jRender_inventory_group_combo_ones($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null) {

	$rp = new db_class();
    $class = "input-xlarge" ;
	$mval ='';
	$sqlval='';
	$limit = 500;
	if (isset($_GET['market'])&& trim($_GET['market'])!='') {
		$mval = $_GET['market'];
		$sqlval =  " where ig.Market='".$mval."'";	
	}else{
		$limit = 0;
	}	
	
	$vendor_where1 = '';
	$vendor_where2 = '';	
	if($_GET['vendor']>0){
		$vendor_where1 =" AND ii.vendor_default = '". $_REQUEST['vendor'] ."'";
		$vendor_where2 =" AND lii.default_vendor = '". $_REQUEST['vendor'] ."'";	
	}

    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
		/*"SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
				$sqlval	AND lii.location_id = '".$locationID."'	
				ORDER BY ig.description ASC" ;*/
		$sql = 	"SELECT distinct(tbl.id),tbl.description from (
			(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global' $sqlval AND lii.location_id = '".$locationID."' $vendor_where1
		ORDER BY ig.description ASC)
UNION ALL 
		(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND  lii.type<>'global' $sqlval AND lii.location_id = '".$locationID."' $vendor_where2 
		ORDER BY ig.description ASC)) as tbl ORDER BY description LIMIT $limit";
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
    $data .= '</select>';
    return $data;
}


function jRender_inventory_market_combo($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null,$vendor) {

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
	$vendor_where1 = '';
	$vendor_where2 = '';	
	if($vendor>0){
		$vendor_where1 =" AND ii.vendor_default = '". $_REQUEST['vendor'] ."'";
		$vendor_where2 =" AND lii.default_vendor = '". $_REQUEST['vendor'] ."'";	
	}	
	$sql1 = "SELECT distinct(market) from ((SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_where1)
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_where2)) as market ORDER BY market";
		//echo $sql;exit;
		$output = $rp->rp_query($sql1) or die(mysql_error());								
		$rows = $rp->rp_affected_rows($output);	
			
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -  Select Market - - - </option>';
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
		  $data .= '<option value=""> - - -  No Markets Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Markets Found  - - - </option>';
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
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}

.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right
		!important;
	background-color: #333333 !important;
}
#change_status_form .pinned{
	width: 16%;
}
#change_status_form .scrollable > table{
	margin-left: 16%;
}
.left{ text-align:left;}
.dataTables_paginate .paginate_active {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #454545;
}
.btn-primary.disabled, .btn-primary[disabled]{
	background-color:#d3d3d3 !important;
}
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="js/jquery.uniform.min.js"></script> -->
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($){
        // dynamic table
		var msg="<?php echo $_REQUEST['msg']; ?>";
						if(!(msg=="")){
							if(msg=="ad"){
								jAlert('The Item has been added successfully.','Item Added','Alert');
								}else{
								jAlert('The Item has been updated successfully.','Item Updated','Alert')
								}
       					}
        jQuery('#dyntable').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 2, "asc" ]],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
            }
        });
		
		<?php if(count($undeletable) > 0){ ?>
            /*jAlert('The following item ids could not be deleted: <?php echo implode(',\n', $undeletable)?>.' +
                '\nThey have already been used in one or more of the' +
                '\nfollowing ManagePanel pages: Inventory, Prep Items,' +
                '\nLine Items, Order Needed and/or Recipes.', 'Alert');*/
				jAlert('This Inventory item cannot be deleted at this time, it is currently active, <?php echo $table; ?>!','Alert Dialog');
				
				//jAlert('Please add items to the local list before submitting!', 'Alert');
        <?php } ?>
		/*
        $('.search_box').on('paste keyup','input',function(){
            if (!this.value) {
                $('#search_x').fadeOut(300);
                filter('','item_tbl',6,'clear');
            }else{
                $('#search_x').delay().fadeIn(300);
                filter(this.value,'item_tbl',6,'search');
            }
        });
        $('#search_x').on('click',function(){
            $('.search_box').find('input').val('');
            filter('','item_tbl',6,'clear');
            $(this).fadeOut(300);
        });
        if($('.search_box').find('input').val() != ''){
            $('#search_x').show();
        }
        */
        
        var toggle = 0;
        jQuery.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
            if ( toggle && !jQuery(aData[6]).is(':checked') ){
                return false;
            }
	        return true;
	    });
        jQuery('#toggle').click(function(){
        	toggle = 1 - toggle;
        	jQuery('#dyntable').dataTable().fnDraw();
        	/*
            if( jQuery('#dyntable .active').is(":visible") ){
            	jQuery('#dyntable .active').hide();
            	jQuery('#dyntable .inactive').show();
            }else{
            	jQuery('#dyntable .active').show();
            	jQuery('#dyntable .inactive').hide();
            }
            */
        });
        
		jQuery(document).on('click', '.delete', function(e){
			var id = jQuery(this).attr('id');			
				jConfirm('Are you sure you want to delete this item?', 'Confirm Delete', function(r) {
             if(r){            
            	jQuery('#delete_item_id').val(id);
				
            	jQuery('#delete_item').submit();
            	}
        	});
		});
        
        $("input[type='checkbox']").click(function(){
            $('#submit_btn').removeAttr("disabled");
            $('#submit_btn').click(function(){
                $('#change_status_form').submit();
            });
            $("input[type='checkbox']").unbind('click');
        });
        
    });
	jQuery(document).on('change','#dummy_market',function(){
	
	  var market = jQuery(this).val();
	  var vendor = jQuery("#vendor").val();
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup.php",
		data: { market: market,vendor:vendor}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group_id").html(msg);
		});
	
	});
	function group_formSubmit(){
 		 document.group_form.submit();
	}
	
function change_vendor(){
	var vendor_val = jQuery('#vendor').val();	
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
}
</script>
<style>
#dyntable_wrapper{ border:2px solid #0866C6 !important;}

</style>
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
      <li><a href="messages.php"><i class="iconfa-home"></i> </a> <span
					class="separator"></span></li>
      <li>Setup</li>
      <li><span class="separator"></span></li>
      <li>Inventory</li>
	  <li><span class="separator"></span></li>
      <li>Manage Items</li>
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
      <div style="float:right;margin-top: 11px;">
      <a href="#" class="btn btn-primary btn-large" onClick="group_formSubmit()">Go</a>
	  <a id="add_button" onclick="sendToAdd();"><button class="btn btn-success btn-large">Add</button></a>
	  	
	  	 <!--<a href="setup_finance_manage_items_add.php" style="color: #fff;" id="add_button" class="btn btn-success btn-large">
        <button id="toggle" class="btn btn-primary btn-large">Filter</button>-->
        <button disabled id="submit_btn" class="btn btn-primary btn-large">Submit</button>
      </div>
      <form action="<?=basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'];?>" name="group_form" id="group_form" method="get">
				<?=jRender_inventory_group_combo_ones('group_id',$_SESSION['loc'],$group_id,'dummy','float:right; width:180px; height:43px;padding: 8px 8px;margin:12px 10px 0 0;',$_REQUEST['dummy_market']);?>
						
				<?=jRender_inventory_market_combo('dummy_market',$_SESSION['loc'],$group_id,'dummy-market','float:right; width:180px; height:43px;padding: 8px 8px;margin:12px 10px 0 0;',$_REQUEST['vendor']);?>
                 <?php
						$qry_vendors = "SELECT * FROM vendors WHERE 
						id IN ( SELECT DISTINCT(ii.vendor_default) FROM inventory_items as  ii 
						JOIN location_inventory_items lii ON lii.inv_item_id = ii.id  WHERE lii.type='global' and lii.location_id = '".$_SESSION['loc']."'  AND
						(ii.vendor_default!='' AND ii.vendor_default IS NOT NULL) ) 
						OR 
						id IN ( SELECT DISTINCT(default_vendor) FROM location_inventory_items WHERE type<>'global' AND location_id = '".$_SESSION['loc']."' AND  (default_vendor!='' AND default_vendor IS NOT NULL))
						ORDER BY vendors.name ASC";
                        $rs_vendors = $rp->rp_query($qry_vendors);
						
                    ?>
                <select id="vendor" name="vendor" class="dummy-market" style="float: right;height: 43px;margin: 12px 10px 0 0;padding: 8px; width: 180px;" onChange="change_vendor(); " >
                    	<option value="">- - - Select Vendor - - -</option>
                        <?php
							while($row_vendors = $rp->rp_fetch_array($rs_vendors)){
                                $selected = ($_REQUEST['vendor'] == $row_vendors['id']) ? 'selected' : '';
                                echo '<option value="'. $row_vendors['id'] .'" data-id='. $row_vendors['id'] .' '. $selected .' >'. $row_vendors['name'] .' (ID:'. $row_vendors['id'] .')</option>';
                            }
                        ?>
                    </select>
                </form>
      <div class="pageicon"> <span class="iconfa-cog"></span> </div>
      <div class="pagetitle">
        <h5>The manage items setup module allows you to manage all of your global, local and preparation items.</h5>
        <h1>Manage Items</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
      <h4 class="widgettitle">Inventory Items</h4>
        <form id="change_status_form" name="change_status_form" method="post">
          <table id="dyntable" class="table table-bordered responsive">
            <colgroup>
            <col class="" />            
            <col class="con0" style="width:5%;"/>
			<col class="con1" style="width:5%;"/>
            <col class="con0" style="width:10%;"/>
            <col class="con1" style="width:14%;"/>
            <col class="con0" style="width:10%;"/>
            <col class="con1" style="width:5%;"/>
            <col class="con0" style="width:10%;"/>
            <col class="con1" style="width:10%;"/>
            <col class="con0" style="width:11%;"/>
            <col class="con1" style="width:5%;"/>
			<col class="con0" style="width:5%;"/>
            <col class="con1" style="width:5%;"/>
            <col class="con0" style="width:7%;"/>
            </colgroup>
            <thead>
              <tr>
              	
                
                <th class="head0 left">Image</th>
                <th class="head1 center">S</th>
				<th class="head0 left">Type</th>
                <th class="head1 left">Group</th>
                <th class="head0 left">Item Code</th>
                <th class="head1 left">Item Description</th>
                <th class="head0 left">Priority</th>
                <th class="head1 left">Unit Type</th>
                <th class="head0 left">Def Brand</th>
                <th class="head1 left">Def Vendor</th>
                <th class="head0 left">Taxable</th>
				<th class="head0 left">eCommerce</th>
                <th class="head1 left">Inactive</th>
                <th class="head0 center nosort" style="width: 4%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row1 = $rp->rp_fetch_array($result1)){
			  	$deletable = TRUE;
   				if( $rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_counts WHERE inv_item_id = ".$row1['id'])) > 0 ||  $rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_line_items WHERE inv_item_id = ".$row1['id'])) > 0 ||
					$rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_order_needed WHERE inv_item_id = ".$row1['id'])) > 0 ||
					$rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_recipe_details WHERE inv_item_id = ".$row1['id'])) > 0 ||
					$rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_items_prep_details WHERE inv_item_id = ".$row1['id'])) > 0 ||
					$rp->rp_affected_rows($rp->rp_query("SELECT id FROM location_inventory_storeroom_items WHERE inv_item_id = ".$row1['id'])) > 0 )
					{
					 //$deletable = FALSE;
    				}
				
		
								if ( empty($row1['group']) ){
									continue;
								}
                       			if($row1['status'] == 'active'){ $status = 'gradeX active'; } else { $status = 'gradeX inactive'; };
								if($row1['type'] == 'global'){
								$url = "setup_finance_global_items_add.php?id=".$row1['id']."&group=".$row1['group']."&item=".$row1['description']."&unit_type=".$row1['unit_type']."&group_id=".$_REQUEST['group_id']."&market=".$_REQUEST['market'];
								}else{
								$url = "setup_finance_manage_items_add.php?id=".$row1['id']."&group_id=".$_REQUEST['group_id']."&market=".$_REQUEST['market'];
								}
                                
                        		?>
              <tr class="<?php echo $status;  ?>" <?php if($undelet[$row1['id']] == 1){?>" style='background-color:#FF0000 !important' <?php } ?>">
                
                <td style="text-align: center;">
                	<!--<img src="<?php echo APIIMAGE.'images/'.$row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" style="height:34px; width:34px;" >-->
                    <?php
						if($row1['local_item_image'] != ""){
							if(strpos($row1['local_item_image'], 'http') !== FALSE) {
					?>
								<img src="<?php echo $row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" style="height:34px; width:34px;" data-img="1" />
					<?php
								//$img_path=$row1['digital_image_name'];
							} else {
					?>
								<img src="<?php echo APIIMAGE."images/".$row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" style="height:34px; width:34px;" data-img="2" />
					<?php
								//$img_path=APIIMAGE ."images/". $row1['local_item_image'];
							}
						} else {
					?>
							<img src="images/defimgpro.png" alt="" id="mainimage" style="height:34px; width:34px;" data-img="3" />
					<?php
						}
					?>
                </td>
				<td style="text-align:center;"><?php if($row1['status'] == 'active'){?><img src="images/Active, Corrected, Delivered.png" title="Active"><?php }?>
				<?php if($row1['status'] == 'inactive'){?><img src="images/Inactive & Missing Punch.png" title="Inactive"><?php }?></td>
                <td><?php echo ucfirst($row1['type']); ?></td>
                <td><?php echo $row1['group']; ?></td>
                <td><?php echo $row1['item_code']; ?></td>
                <td><?php echo $row1['description'];?></td>
                <td style="text-align: center;"><?php echo hideZeros($row1['priority']); ?></td>
                <td><?php echo $row1['unit_type'];?></td>
                <td><?php echo $row1['default_brand'];?></td>
                <td><?php if($row1['vendor_name']!=""){ echo $row1['vendor_name'].' (ID: '.$row1['default_vendor'].')'; }?></td>
                <td><?php echo ucfirst($row1['taxable']);?></td>
                <td><?php echo ucfirst($row1['ecommerce']);?></td>
				
                <?php if($row1['status'] == 'inactive'){
                            			$inactive = $inactive . $row1['id'] . ","; ?>
                <td  style="text-align: center;"><input type='checkbox' name="inactive[]" value='<?php echo $row1['id']; ?>' checked /></td>
                <?php } else { ?>
                <td style="text-align: center;"><input type='checkbox' name="active[]" value='<?php echo $row1['id']; ?>' /></td>
                <?php } ?>
                <?php if($row1['type'] == 'global'){ ?>
                <td style="text-align: center;">
                	<?php
						if($row1['manufacturer_barcode'] != ''){
					?>
							<img alt="" title="" src="images/barcode_16_16.png">
                    <?php
						}
                    ?>
				<?php //if($deleteable[$row1['id']] == 1){ ?>
                	<a  href="setup_finance_global_items_add.php?id=<?php echo $row1['id']; ?>&group=<?php echo $row1['group']; ?>&item=<?php echo htmlentities($row1['description']);?>&unit_type=<?php echo $row1['unit_type'];?>&group_id=<?php echo $_REQUEST['group_id']; ?>&market=<?php echo $_REQUEST['market'];?>" class="edit">				
				<img alt="" title="Edit" src="images/Edit - 16.png"></span></a>&nbsp;
                 <?php /* <span  <?php if ($deletable) { echo 'class="delete" id="'.$row1['id'].'"';} else { ?> onClick="javascript:jAlert('This Item is in use and can\'t be deleted at this time.');" <?php } ?> style="cursor: pointer;"> <img alt="" title="Edit" src="images/Delete - 16.png"> </span> */?>
                  <?php //} ?>
                </td>
                <?php } else { ?>
                <td style="text-align: center;">
                	<?php
						if($row1['manufacturer_barcode'] != ''){
					?>
							<img alt="" title="" src="images/barcode_16_16.png">
                    <?php
						}
                    ?>
				<a  href="setup_finance_manage_items_add.php?id=<?php echo $row1['id']; ?>&group_id=<?php echo $_REQUEST['group_id']; ?>&market=<?php echo $_REQUEST['market'];?>" class="edit">				
				<img alt="" title="Edit" src="images/Edit - 16.png"></span></a>&nbsp;
                  <?php  //if($deleteable[$row1['id']] == 1){ ?>
                  <?php /*<span class="delete" id="<?php echo $row1['id'];?>" style="cursor: pointer;"> <img alt="" title="Edit" src="images/Delete - 16.png"> </span> */?>
                  <?php //} ?>
                </td>
                <?php } ?>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <input name='inactive_ids' value="<?php echo $inactive; ?>" type="hidden" />
          <input name='conf' value="1" type="hidden" />
		  
        </form>
        <form method="post" id="delete_item">
          <input type="hidden" id="delete_item_id" name="delete_item_id" value="" />
        </form>
        <!--row-fluid-->
        <?php include_once 'require/footer.php';?>
        <!--footer-->
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->
</body>
</html>
<!------add_item---popup---->
<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div style="max-height:400px; min-height:inherit; " class="modal-body " id="mymodalhtml"></div>
</div>

<script type="text/javascript">
	

	function sendToAdd() {
		var default_vendor = jQuery('#vendor').val();
		console.log(default_vendor);
		document.location.href = "setup_finance_manage_items_add.php?group_id=<?php echo $_REQUEST['group_id']; ?>&market=<?php echo $_REQUEST['market']; ?>&def_vendor="+default_vendor,true;
	}


</script>