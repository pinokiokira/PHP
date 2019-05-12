<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id =$vender['StorePoint_vendor_Id'];
if(isset($_POST) && $_POST['sub']=="enter_sub"){
	$last_on = 'VendorPanel';
	$last_by = $empmaster_id;
	$group1  = ($_POST['group1']);
	$market  = ($_POST['market']);
	//echo "<pre>"; print_r($_POST);
	//die;
	if($_POST['change_sub']=="enter_sub"){
		for($j=0;$j<=$_POST['total_count'];$j++){
			$query = '';
			$item_id    = mysql_real_escape_string($_POST['inv_it_id'][$j]);
			$qty 	      = mysql_real_escape_string($_POST['qty'][$j]);
			$cou_id  	  = mysql_real_escape_string($_POST['count_id'][$j]);
			$unit_type  = mysql_real_escape_string($_POST['unit_type'][$j]);
			$p_unit_type = mysql_real_escape_string($_POST['p_unittype'][$j]);
			$msg="";
		 	if($qty!=""){
				if($cou_id!=""){
					$selq = "SELECT quantity FROM vendor_items_inventory_counts WHERE id ='".$cou_id."'";
					$resq = mysql_query($selq);
					$rowq = mysql_fetch_assoc($resq);
					$dqty = $rowq['quantity'];
					
					if($qty != $dqty){
						$query = "INSERT INTO vendor_items_inventory_counts SET
							quantity='".$qty."', vendor_id = '".$vendor_id."',
							inv_item_id='".$item_id."', pack_unittype='".$p_unittype."', date_counted=NOW(),
							last_on='". $last_on ."', last_by='". $last_by ."'";
					}
				}else{
					$query = "INSERT INTO vendor_items_inventory_counts SET
						quantity='".$qty."', vendor_id = '".$vendor_id."',
						inv_item_id ='".$item_id."', pack_unittype='".$p_unittype."', date_counted=NOW(),
						last_on='". $last_on ."', last_by='". $last_by ."'";
				}
				
			}
			
			if($query != ''){
				$res = mysql_query($query) or die($query .'-----'. mysql_error());
			}
		}
		header('location:inventory.php?group1='. $group1 .'&market='. $market .'&msg=Inventory Was Updated Successfully!');
	}else{
		header('location:inventory.php?group1='. $group1 .'&market='. $market .'&msg=Add change to the Group!');
	}
} // END OF CONDITION IF(isset($_POST) && $_POST['sub']=="enter_sub").
$group_where = "";
$limit =0;
if($_REQUEST['market']!=""){
	$group_where = "AND ig.Market='".$_REQUEST['market']."'";
	$limit = 500;
}
if($_REQUEST['group1']!=""){
	$group_where .= "AND ii.inv_group_id='".$_REQUEST['group1']."'";
	$limit = 500;
}
if(isset($_REQUEST['search_txt1'])){
	$limit = 500;
	if($_REQUEST['search_txt1']!=""){
		$serch = $_REQUEST['search_txt1'];
		$group_where .= " AND (ii.description LIKE '%".$serch."%' OR ii.item_id LIKE '%".$serch."%')";		
	}
}

$q_count = mysql_fetch_array(mysql_query("select count(vi.id) as count from vendor_items as vi
										 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
										 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
										 where vi.vendor_id = '".$vendor_id."' $group_where"));

if (isset($_REQUEST['get_latest_movements'])) {
	$movements = @json_decode(file_get_contents(API.'panels/VendorPanel/api/backoffice/get-movements.php'),true);
	print_r($movements);
	exit;
}
										 
$total_count = $q_count['count'];
if($q_count['count']>500){
	$limit = 0;
}

	// viic.id as count_id,
 /*$query_act = "SELECT ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' $group_where group by vi.id LIMIT $limit";
*/


if($_REQUEST['group1']=="" && $_REQUEST['market']!="" && $_REQUEST['market']!="All"){

	 $query_act = "SELECT ig.id as group_id, ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size, vi.vendor_id,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.pack_unittype as unit_type, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description,ii.item_id, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' AND ig.Market='".$_REQUEST['market']."' group by vi.id";
}


else if($_REQUEST['group1']!="" && $_REQUEST['market']==""){

	$query_act = "SELECT ig.id as group_id, ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size, vi.vendor_id,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.pack_unittype as unit_type, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description,ii.item_id, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' AND ii.inv_group_id='".$_REQUEST['group1']."' group by vi.id";
}

else if($_REQUEST['group1']!="" && $_REQUEST['market']!=""){

	$query_act = "SELECT ig.id as group_id, ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size, vi.vendor_id,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.pack_unittype as unit_type, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description,ii.item_id, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' AND ii.inv_group_id='".$_REQUEST['group1']."' group by vi.id";
}

else if($_REQUEST['group1']=="" && $_REQUEST['market']=="All"){

	$query_act = "SELECT ig.id as group_id, ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size, vi.vendor_id,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.pack_unittype as unit_type, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description,ii.item_id, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' group by vi.id LIMIT 500";
} else {

	$query_act = "SELECT ig.id as group_id, ig.description as group_name, vi.vendor_internal_number, vi.splitable, vi.pack_size, vi.vendor_id,

 				(SELECT viic1.id from vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as count_id,
				
				vi.inv_item_id,iiu.unit_type as pack_unittype, vi.pack_unittype as unit_type, vi.qty_in_pack, iiu1.unit_type as qty_in_pack_unittype, vi.tax_percentage, vi.price, vi.promotion, vi.promotion_price, ii.description,ii.item_id, ii.image,				 
				(SELECT viic1.quantity from  vendor_items_inventory_counts viic1 WHERE viic1.vendor_id = vi.vendor_id AND viic1.inv_item_id =vi.inv_item_id ORDER BY viic1.id desc limit 1) as quantity,
				(SELECT viic2.date_counted from  vendor_items_inventory_counts viic2 WHERE viic2.vendor_id = vi.vendor_id AND viic2.inv_item_id =vi.inv_item_id ORDER BY viic2.id desc limit 1) as date_counted 
				 FROM vendor_items as vi
				 LEFT JOIN inventory_item_unittype as iiu ON iiu.id = vi.pack_unittype
				 LEFT JOIN inventory_item_unittype as iiu1 ON iiu1.id = vi.qty_in_pack_unittype				 
				 LEFT JOIN vendor_items_inventory_counts viic ON viic.vendor_id = vi.vendor_id AND viic.inv_item_id =vi.inv_item_id 
				 LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id
				 INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				 WHERE vi.vendor_id ='".$vendor_id."' AND ii.inv_group_id='".$_REQUEST['group1']."' group by vi.id LIMIT 500";
}

	if($_REQUEST['debug']!=""){
		echo 'Act => '.$query_act;
	}
$res_act = mysql_query($query_act) or die(mysql_error());
function jRender_inventory_market_combo($nameAndID,$vendor_id, $cClass = null, $cStyle = null) {
	if($_REQUEST['market']!=""){
		$market = $_REQUEST['market'];
		$group_where = "AND ig.Market='$market'";
		$q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi LEFT JOIN inventory_items inv_itm ON inv_itm.id=vi.inv_item_id INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id  where vi.vendor_id = '".$vendor_id."' $group_where "));
	}else{
		$q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi where vi.vendor_id = '".$vendor_id."' "));
	}

	$total_count = $q_count['counts'];

	$class = "input-xlarge" ;
	$style = 'width:90%;height:23px;';
	if ($cClass!="")
		$class  = $cClass;
	if ($cStyle!="")
		$style  = $cStyle;
	$data = '<select name="market" onChange="javascript:jQuery(\'#total_rows\').val(jQuery(this).find(\'option:selected\').attr(\'rel\'))" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 

	//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
	$sql = "SELECT distinct(ig.Market) as market,count(ii.id) as invs
		FROM inventory_groups ig
		INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
		INNER JOIN vendor_items lii ON lii.inv_item_id=ii.id 
		where lii.vendor_id ='".$vendor_id."' AND market !='NULL' group by ig.Market ORDER BY market ASC" ;
		//echo $sql;exit;
			
	$output = mysql_query($sql) or die(mysql_error());								
	$rows = mysql_num_rows($output);
	
	if ($rows > 0 && $rows != '') {	
		$data .= '<option rel="'.$total_count.'" value="All"> - - -  Select Market - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
			//print_r($result);exit;
			
			$market = $result['market'];
			//echo $market;
			if ($result['market'] == $_REQUEST['market']) { $sel1 = ' selected="selected"'; } else { $sel1 = ''; }
			$data .= '<option rel="'.$result['invs'].'" value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
		}
	} else {
		$data .= '<option value=""> - - -  No Market Found  - - - </option>';
	}
    $data .= '</select>';
	return $data;
}

if (isset($_REQUEST['get_availability'])) {
	$l_id = $_REQUEST['location_id'];
  $st = $_REQUEST['storeroom_id'];
 	//	 $token1 = md5('location=' . $_SESSION['loc'] . 'backofficesecure12');
  $token1 = md5('location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website' . 'backofficesecure12');
	$token = base64_encode(strtotime('-7 hours')."+0");
	$marktmp = $market;
	$keywwds = $keyword;
	$token1tmp = $token1;
	$tokentmp = $token;
	$sttemp = $st;
	$available = 0;

	$ajax_query = "SELECT viic.quantity as qty FROM vendor_items_inventory_counts viic 
		LEFT JOIN vendor_items vi ON vi.inv_item_id = viic.inv_item_id
		LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id 
		INNER JOIN inventory_groups ig ON ig.id = ii.inv_group_id
    WHERE viic.inv_item_id='".$_REQUEST['inv_item_id']."' 
		AND viic.pack_unittype = '".$_REQUEST['unit_type']."' 
		AND viic.vendor_id = '".$_REQUEST['vendor_id']."' ";
		if($_REQUEST['group_id'] != '') $ajax_query .=  "AND ig.id = '".$_REQUEST['group_id']."' ";
		$ajax_query .= "GROUP BY viic.id DESC";

		$qry = mysql_query($ajax_query);

	if(mysql_num_rows($qry) > 0){
		$fet_qty = mysql_fetch_array($qry);
		$available = sprintf("%.2f", $fet_qty['qty']);
		if($available == null){
			$available = 0;
		}
		echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available, 'query1'=>$ajax_query));
		exit;
	}
	
	echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available, 'query2'=>$ajax_query));
	exit;

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
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />

<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js">
</script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>

<style>
#global_tbl_filter input[type="text"] { height: auto !important; }
/*.dataTables_paginate { position: absolute;bottom: -32px;right: 10px; }*/

/*.dataTables_filter { position: absolute;top: -50px !important;right: 0px; }*/
.widgetcontent { background: #fff;padding: 15px 12px;border: 3px solid #0866c6;border-top: 0;margin-bottom: 20px; }
body { top:0px!important; }
.goog-te-banner-frame{  margin-top: -50px!important; }
.error { color: #FF0000;padding-left:10px; }
/*.row-fluid .span4 { width: 32.6239%;margin-left:10px;}*/
.span4 { float:left;width:28.5%!important;min-height:600px;margin-left:1.5%!important; }
/*.unread showJobs selected{background-color:#cccccc;}*/
table.table tbody tr.selected, table.table tfoot tr.selected { background-color: #808080; }
.table th, .table td{ padding:6px 8px 0 8px !important;vertical-align:middle; }
.line3{	background-color:#808080;color:#000000 !important; }
</style>
<script type="text/javascript">
jQuery(document).ready(function(){

	<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']!=''){?>
		var msg = '<?php echo $_REQUEST['msg']; ?>';
		jAlert(msg,'Alert Dialog');
	<?php } ?>
	jQuery(window).resize(function() {
		serch_pos();
	})
	jQuery('#global_tbl').dataTable({
		sPaginationType: "full_numbers",
		bPaginate: true,
		aaSorting: [[ 1, 'asc' ]],
        oLanguage: {
            sLengthMenu: "Show _MENU_",
            sInfo: "_START_ to _END_ of _TOTAL_",
            sSearch: "Search:",
            sInfoEmpty: "0 to 0 of 0",
            oPaginate: {
                sFirst: "First",
                sLast: "Last",
                sNext: "Next",
                sPrevious: "Prev"
            }
        },
		bJQuery: true,
		fnDrawCallback: function(oSettings) {
		   //  jQuery.uniform.update();
		}
	});
	if(jQuery('#global_tbl>tbody>tr:first>td').html()!="No data available in table"){
	jQuery('#global_tbl>tbody>tr:first').addClass('line3');
	}
	// serch_pos();
	// jQuery('#global_tbl_info').hide();
	
	
//	jQuery(".cl_order").click(function(){
//			 jQuery(".gradeX").attr("class","gradeX cl_order");
//		   jQuery(this).attr("class","gradeX cl_order selected");
//			callajax(this);
//			
//		}); 
if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
			//jQuery("input[type='text']").css("height","30px");
			jQuery('.go_search2').css("height","42px");
	}
		
});
/*jQuery('#ser_go').live('click',function(){	
	var val = jQuery('#serch_drop').val();
	if(val!=""){
		window.location="storepoint_inventory.php?group="+val;
	}

});*/
jQuery("[id*='qty_']").live('change',function(){
jQuery('#change_msg').val('enter_sub');

});
function dosubmit(val){
	var qtyval  = jQuery("#"+val).val();
	if(qtyval != 0){
		jQuery('#submit').show();
		jQuery('#disablebutton').hide();
	} else{
		jQuery('#disablebutton').show();
		jQuery('#submit').hide();
	}
}
function serch_pos(){
	var offset = jQuery('#global_tbl').offset();
	var left = offset.left;
	var top = offset.top;
	var width = jQuery('#global_tbl').width();
	jQuery('#global_tbl_filter').offset({ top: top+5, left: width+65});
	}
function getStorepointLocation(sId)
{
			var dataurl = "storepoint_getStorepointLocationInq.php?sId=" + sId;
			jQuery.ajax({
				url:dataurl,
				type:"get",
				cache:false,
				async:false,
				dataType:"text", 
				success:function (data) {
				if(data=='0'){
				//location.reload(); 
				return false;
				}	
				 else {	
					jQuery("#fdetail").css("display","block");
					jQuery("#fdetail").html(data);
				 }
				}
			});
}
function callajax(obj)
{
			var jobId = jQuery(obj).attr("id");
			//jQuery("#".jobId).css("background-color","#808080");
			getStorepointLocation(jobId);
}
var submitAction=true;
function submitPostListing()
{
	/*var data = jQuery("#frmInquiry").serialize();
	if(jQuery("#message").val().length<=5)
	{
		alert("Please enter a proper message containing at least 6 characters.");
	}
	if(submitAction)
	{
		submitAction=false;
		jQuery.post("post_job_inquiry.php", data, function(response){
			alert("Thank you for inquiring about this job listing. Your message and allowed share profile has been sent to the employer.");
			submitAction=true;
			
			jQuery("#closePostListing").click();
		});
	}*/
				
	
}
</script>

</head>

<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="dashboard.php"><i class="iconfa-home"></i> </a> <span
		class="separator"></span></li>
	<li>eCommerce</li>
      <li class="right"> <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
    		<!--<div style="float:right;margin-top: 11px;"> 
      		<p style="float:left;">
            	<?php 
					$query1=mysql_query("SELECT ig.id,ig.description from vendor_items as vi INNER JOIN inventory_items ii ON ii.id = vi.inv_item_id 
										INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id  where vi.vendor_id ='".$vendor_id."'  GROUP BY ig.id");
				?>
            	<select id="serch_drop" name="search_drop" class="input-large search-choice" style="height:44px; margin:0px 10px 0 0;">
                <option selected value="all" >All</option>
                <?php
				
				while($row= mysql_fetch_array($query1)){
				if($row['id']==$_REQUEST['group']){
				$selected ="selected='selected'";
				}else{
				$selected ="";
				}
				?>
                
                <option <?php echo $selected; ?> value="<?php echo $row['id']; ?>"><?php echo $row['description']; ?></option>
                <?php } ?>
                </select>
            </p>
            <button id="ser_go" style="float:left" class="btn btn-primary btn-large">Go</button>
            </div>-->
            
            <div style="float: right;margin-top: 10px;" class="messagehead">
		        <p style="float:left;">
		          <?php 
							if($_REQUEST['market']!="All" && $_REQUEST['market']!=""){
								$sqlmarket =  " AND Market='".$_REQUEST['market']."'";
							} else{
								$sqlmarket =  " AND Market='All'";
							}
							$query1=mysql_query("SELECT distinct(ig.id) as id, ig.description,count(ii.id) as invs FROM inventory_groups ig
												INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id	
												INNER JOIN vendor_items as vi ON vi.inv_item_id = ii.id
												where vi.vendor_id='".$vendor_id."'
												$sqlmarket 
												group by ig.id
												ORDER BY ig.description ASC") or die(mysql_error());
						?>
		           <span>                    
					<input type="text" placeholder="Search" value="<?php echo $_REQUEST['search_txt1']; ?>" name="search_txt1" id="search_txt1" class="go_search2" style="width: 120px; padding-left: 5px; max-height: 32px !important; float:right; margin-right:10px;">
				    </span>
		          <select id="group_id" name="search_drop" onChange="javascript:jQuery('#total_rows').val(jQuery(this).find('option:selected').attr('rel'))" class="input-large search-choice" style=" width:170px; float:right; height:42px; margin:0px 10px 0 0;">
		            <option value="">- - - Select Item Group - - -</option>
		            <?php
						
						while($row= mysql_fetch_array($query1)){
						if($row['id']==$_REQUEST['group1']){
						$selected ="selected='selected'";
						$total_count = $row['invs'];
						}else{
						$selected ="";
						}
						?>
		            <option <?php echo $selected; ?> rel="<?php echo $row['invs']; ?>" value="<?php echo $row['id']; ?>"><?php echo $row['description']; ?></option>
		            <?php } ?>
		          </select>
		          <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
		            
		            <?php echo jRender_inventory_market_combo('dummy_market',$vendor_id, 'dummy-market', "float:right; width:170px; height:42px;padding: 8px 8px;margin:0 10px 0 0;"); ?>
		            <!--<select class="dummy-market"  id="dummy_market" style="float:right; width:170px; height:45px;padding: 8px 8px;margin:0 10px 0 0;" name="market">
		             <option value="">- - - Select Market - - -</option>
		              <option <?php if($_REQUEST['market']=='All'){ echo 'selected';} ?> value="All">All</option>
		              <option <?php if($_REQUEST['market']=='Bar'){ echo 'selected';} ?> value="Bar">Bar</option>
		              <option <?php if($_REQUEST['market']=='Hotel'){ echo 'selected';} ?> value="Hotel">Hotel</option>
		              <option <?php if($_REQUEST['market']=='Restaurant'){ echo 'selected';} ?> value="Restaurant">Restaurant</option>
		              <option <?php if($_REQUEST['market']=='Retail'){ echo 'selected'; } ?> value="Retail">Retail</option>
		              <option <?php if($_REQUEST['market']=='Other'){ echo 'selected'; } ?> value="Other">Other</option>
		            </select>-->
		          
		        </p>
		        <button id="ser_go" style="float:left" class="btn btn-primary btn-large">Go</button>
		        <!--<form action="results.html" method="post" class="searchbar">
		                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
		            </form>-->
		        <!--<button id="addcode" style="margin-left:10px;" class="btn btn-success btn-large">Add</button>-->
		    </div>
      <div class="pageicon"><span class="iconfa-book"></span></div>
      <div class="pagetitle">
        <h5>The Following are items available to locations</h5>
        <h1>eCommerce</h1>
      </div>
    </div>
    <!--pageheader-->
 
    <div class="maincontent">
      <div class="maincontentinner">
       
        <!--row-fluid-->
        <div class="row-fluid">
        	
          <div class="span8" style="width:100%;float:left;">
          		<div class="clearfix">
                <h4 class="widgettitle">Inventory</h4>
              </div>
              <div class="widgetcontent">
              <!-- <div id="global_tbl_length" class="dataTables_length">
              <label><span>&nbsp;&nbsp;</span></label>
              </div>  -->  
              <form name="sub" action="" method="post"  style="overflow: hidden;">
              <input type="hidden" name="change_sub" class="change_msg" value="enter" id="change_msg" >
              <input type="hidden" name="sub" value="enter_sub" >
              <input type="hidden" name="group1" value="<?php echo isset($_GET['group1']) ? trim($_GET['group1']) : '' ; ?>" />
              <input type="hidden" name="market" value="<?php echo isset($_GET['market']) ? trim($_GET['market']) : '' ; ?>" />
              
              <table class="table table-bordered responsive" id="global_tbl">
                <colgroup>
               		<col class="con0" style="width:3%;"/>
                    <col class="con1" style="width:8%;"/>
               		<col class="con0" style="width:5%;"/>
                	<col class="con1" style="width:15%;"/>
               		<col class="con0" style="width:4%;"/>
                	<col class="con1" style="width:4%;"/>
               		<col class="con0" style="width:6%;"/>
                	<col class="con1" style="width:5%;"/>
               		<col class="con0" style="width:5%;"/>
                	<col class="con1" style="width:5%;"/>
               		<col class="con0" style="width:5%;"/>
                	<col class="con1" style="width:5%;"/>
                    <col class="con0" style="width:9%;"/>
									<col class="con1" style="width:6.5%;"/>
									<col class="con0" style="width:6.5%;"/>
                </colgroup>
                <thead>
                  <tr>
                    <th class="head0">Image</th>
                    <th class="head1">Group</th>
					<th class="head0">Item Code</th>
                    <th class="head1">Item Description</th>
                    <th class="head0">Pack Unit Type</th>
					<th class="head1">qty in Pack</th>
                    <th class="head0">qty in Pack Unit Type</th>
                    <th class="head1">qty in Pack Size</th>
                    <th class="head1">Splitable</th>
                    <th class="head0">price</th>
                    <th class="head0">Promo</th>
                    <th class="head1">Promo Price</th>
					<th class="head0">last counted</th>
                    <th class="head1">Current QTY</th>
										<th class="head0">New QTY</th>
                  </tr>
                </thead>
                <tbody>	
                    <?php
						$i=0;
						if (isset($_REQUEST['debug'])) {
							echo "<br><br>";
							print_r("SQL :      ".$query_act);
							echo "<br><br>";
						}
						while($row_act = mysql_fetch_array($res_act)){
						/* echo "SELECT SUM(lic.quantity) AS total_qty FROM location_inventory_counts lic 
											LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
											WHERE lic.inv_item_id='".$row_act['loc_inv_id']."' AND inventory_item_unittype.unit_type = '".$row_act['qty_in_pack_unittype']."'";
						echo "<br>"; */			
						/*$qry = mysql_query("SELECT ABS(MAX(viic.quantity)) AS total_qty 
								FROM vendor_items_inventory_counts viic 
								JOIN vendor_items vi ON vi.inv_item_id = viic.inv_item_id 
								LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=vi.pack_unittype
								LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id 
    						INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id 
    						INNER JOIN location_inventory_items loc_inv ON loc_inv.inv_item_id=ii.id 
								WHERE viic.inv_item_id='".$row_act['inv_item_id']."' 
								AND inventory_item_unittype.unit_type = '".$row_act['qty_in_pack_unittype']."'
								AND vi.vendor_id = '".$vendor_id."'
								AND ig.id = '".$row_act['group_id']."'
								GROUP BY viic.id DESC");*/
						
						$qry = mysql_query("SELECT viic.quantity as total_qty FROM vendor_items_inventory_counts viic 
						LEFT JOIN vendor_items vi ON vi.inv_item_id = viic.inv_item_id
						LEFT JOIN inventory_items ii ON ii.id = vi.inv_item_id 
						INNER JOIN inventory_groups ig ON ig.id = ii.inv_group_id
						WHERE viic.inv_item_id='".$row_act['inv_item_id']."' 
						AND viic.vendor_id = '".$vendor_id."' AND ig.id = '".$row_act['group_id']."' GROUP BY viic.id DESC");
						
						$fetch = mysql_fetch_array($qry);
						$fetch['total_qty'] = ($fetch['total_qty'] > 0) ? $fetch['total_qty'] : '0';
						$q = mysql_query("SELECT storeroom_id FROM location_inventory_counts WHERE inv_item_id = '".$row_act['loc_inv_id']."' GROUP BY storeroom_id");
						$d = '';
						while($f = mysql_fetch_array($q)){
							$d .= $f['storeroom_id'].',';
						}
						$q2 = mysql_query("SELECT SUM(quantity) as quantity FROM location_inventory_counts WHERE inv_item_id = '".$row_act['loc_inv_id']."'");
						$f2 = mysql_fetch_array($q2);
						echo "<script>console.log('asdasd".$f2."');</script>";
						?>
                    	<tr onClick="select_this(<?php echo $i; ?>)" id="row_<?php echo $i; ?>" class="gradeX">
                        	<input type="hidden" name="count_id[]" id="c_id" value="<?php echo $row_act['count_id']; ?>" >
														<input type="hidden" name="inv_it_id[]" id="inv_it_id" value="<?php echo $row_act['inv_item_id']; ?>" > 
														<input type="hidden" name="unit_type[]" ud="unit_type" value="<?=$row_act['pack_unittype']?>">
														<input type="hidden" name="p_unittype[]" id="p_unittype" value="<?=$row_act['qty_in_pack_unittype']?>">

                            
                        	<td>
                        	<?php if($row_act['image'] != ''){ ?>
                        	<img src="<?php echo APIPHP.'images/'.$row_act['image']; ?>" width="34" height="34" onerror="this.src='images/noimage.png'">
                        	<?php }else{?>
                            <img src="images/noimage.png" width="34" height="34" onerror="this.src='images/noimage.png'">
                            <?php } ?>
                        	</td>
                            <td><?php echo $row_act['group_name']; ?></td>
							<td><?php echo $row_act['inv_item_id']; ?></td>
                            <td><?php echo $row_act['description']; ?></td>
							<td><?=$row_act['pack_unittype']?></td>
														<td class="center"><?php echo $row_act['qty_in_pack']; ?></td>
							<td class="center"><?php echo $row_act['qty_in_pack_unittype']; ?></td>
                            <td><?php echo $row_act['pack_size']; ?></td>
                            <td><?php echo $row_act['splitable']; ?></td>
                            <td class="right"><?php echo $row_act['price']; ?></td>
                           
                            <td><?php if($row_act['promotion'] !="") echo $row_act['promotion']; else echo "-"; ?></td>
                            <td class="right"><?php echo $row_act['promotion_price']; ?></td>
                            <td><?php if($row_act['date_counted'] !="") echo $row_act['date_counted']; else echo "-";?></td>
							<td class ="right">
								<?php echo number_format($row_act['quantity'],2);?>
							</td>
							<td class ="right" style="padding-top:10px !important;">
							<input class="ins qty_total_<?php echo $row_act['loc_inv_id'] ?>" style="width:83%" id="qty_<?php echo $i; ?>" 
								type="text" style="width:40%;" name="qty[]" value="<?php // echo number_format($fetch['total_qty'],2); ?>" 
								onKeyUp="dosubmit('qty_<?php echo $i; ?>');">
								
							</td>
                        </tr>
                    <?php $i++; } ?>
                    
                    
                </tbody>
                
              </table>
              <input type="hidden" id="total_count" name="total_count" value="<?php echo $i; ?>" >
              <input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>" >
			<?php //if($i != 0){ ?>
              <input style="float: right; margin: 10px 0;display:none;" type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary" >
			  <?php //} else{ ?>
			  <input style="float: right; margin: 10px 0;background-color:#cccccc; border: 1px solid #cccccc; cursor:auto;" type="button" id="disablebutton" name="disablebutton" value="Submit" class="btn btn-primary" >
			   <?php //} ?>
              </form></div>
          </div> <!--end span8-->
          
        </div>        
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
<script>
function select_this(id){
	jQuery('.line3').removeClass('line3');
	jQuery("#row_"+id).addClass('line3');
}  

jQuery('#ser_go').live('click',function(){
	
	var total_rec = jQuery('#total_rows').val();
	if(total_rec > 499){
		var search_inpt = jQuery('#search_txt1').val();
		if (search_inpt!=null) {
			search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
			if (search_inpt.length > 0) {
				if(search_inpt.length < 3) {
					jAlert('Please enter 3 or more characters');
					return false;
				}else{
					var val = jQuery('#group_id').val();
					var dummy_market = jQuery("#dummy_market").val();
					if(val==""){ /*val = 'All';*/ }
					window.location="inventory.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
				}
			} else{
				jAlert(' Enter value to search');
				return false;
			}  
			return false;
		} else{
			jAlert(' Enter value to search');
			return false;
		}
	}else{							
		var val = jQuery('#group_id').val();
		var dummy_market = jQuery("#dummy_market").val();
		if(val==""){
			/*val = 'All';*/
		}
		window.location="inventory.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
	}
});

jQuery(document).on('change','#dummy_market',function(){
	
	  var market = jQuery(this).val();
	  var vendor = '<?php echo $vendor_id; ?>';
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup_byvendor.php",
		data: { market: market,vendor:vendor}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group_id").html(msg);
		});
	
	}); 
</script>


<script type="text/javascript">
		
	jQuery('.unitleft').change(function() {
		
		var unit_type_selected = jQuery(this).val();
		var location_id = '<?php echo $_SESSION['loc']; ?>';
		var storeroom_id = jQuery(this).data('storeroom_id');
		var inv_item_id = jQuery(this).data('inv_item_id');
		var vendor = '<?php echo $vendor_id; ?>';
		var group = jQuery('#group_id').val();
		
		console.log('unit_type_selected : '+unit_type_selected);
		console.log('location_id : '+location_id);
		console.log('storeroom_id : '+storeroom_id);
		console.log('inv_item_id : '+inv_item_id);
		console.log('vendor_id : '+vendor);
		console.log('group_id : '+group);
		
		jQuery.ajax({
			method: "POST",
			url: "get_enterinv_data.php",
			data: { 
				get_availability: 1,
				inv_item_id: inv_item_id, 
				unit_type: unit_type_selected, 
				location_id: "<?php echo $_SESSION['loc']; ?>",
				vendor_id: vendor,
				group_id: group,
				storeroom_id: storeroom_id,
			}
		}).done(function( available ) {
			var sd = JSON.parse(available);
			console.log('sd.ResponseMessage : '+sd.ResponseMessage);
			jQuery('.qty_total_'+inv_item_id).val(sd.ResponseMessage); 
		});
	});	

</script>


<script type="text/javascript">
	jQuery('.ins').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});
</script>