<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$empmaster_id=$_SESSION['client_id'];
if($_POST['submit_purchase_order'] == '1'){
	$i=0;
	$total_price = 0;
	$count_price = count($_POST['price']);
	for($a=0;$a<$count_price;$a++){
		$total_price += $_POST['price'][$a];
	}
	
	$query10 = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(vendor_invoice_num AS UNSIGNED))+1, 100000) as maxid FROM vendor_purchases WHERE vendor_id='".$_POST['vendor_id']."'"));
	if($query10['maxid']>=100000){
		$vendor_invoice_num =$query10['maxid'];
	}else{
		$vendor_invoice_num =100000;
	}
	
	$query11 = mysql_fetch_array(mysql_query("SELECT IFNULL(MAX(CAST(buying_vendor_purchase_order AS UNSIGNED))+1, 100000) as maxid FROM vendor_purchases WHERE buying_vendor_id='".$_POST['login_vendor_id']."'"));
	if($query11['maxid']>=100000){
		$buying_vendor_purchase_order =$query11['maxid'];
	}else{
		$buying_vendor_purchase_order =100000;
	}
	
	$vender_purchase_ins_qry = "INSERT INTO vendor_purchases(vendor_id,buying_vendor_id,vendor_invoice_num,buying_vendor_purchase_order,status,subtotal,tax_total,total,shopping_datetime,created_on,created_by,created_datetime)
	VALUES('".$_POST['vendor_id']."','".$_POST['login_vendor_id']."','".$vendor_invoice_num."','".$buying_vendor_purchase_order."','Shopping','".$total_price."','0','".$total_price."',now(),'VendorPanel','".$_SESSION['client_id']."',now())";
	
	mysql_query($vender_purchase_ins_qry);
	$vendor_purchase_id = mysql_insert_id();
	
	$vendor_purchases_qty = "SELECT *, inventory_items.description AS NAME FROM vendor_items 
						LEFT JOIN inventory_items ON vendor_items.inv_item_id=inventory_items.id 
						WHERE vendor_items.vendor_id = '".$_POST['vendor_id']."' AND vendor_items.status = 'active'";
	$vendor_purchases_exe = mysql_query($vendor_purchases_qty);
	$i=0;
	while($fetch_vp = mysql_fetch_array($vendor_purchases_exe)){
		/* for ($i = 0; $i < count($_POST['id']); $i++) {
		    if ($_POST['id'][$i] == $fetch_vp['inv_item_id']) {
                $value = $_POST['qty'][$i];
                break;
            }
        }
		if ($value != '' && $value != 0) { */
		
            $vender_purchase_ins_qry = "INSERT INTO vendor_purchases_items(vendor_purchases_id,inv_item_id,vendor_id,buying_vendor_id,ordered_quantity,ordered_pack_size,ordered_pack_unittype,ordered_qty_in_pack,ordered_qty_in_pack_unittype,ordered_price,ordered_tax_percentage)
		VALUES('".$vendor_purchase_id."','".$fetch_vp['inv_item_id']."','".$_POST['vendor_id']."','".$_POST['login_vendor_id']."',
		'".$_POST['qty'][$i]."','".$fetch_vp['pack_size']."','".$fetch_vp['pack_unittype']."','".$fetch_vp['qty_in_pack']."','".$fetch_vp['qty_in_pack_unittype']."','".$fetch_vp['price']."','".$fetch_vp['tax_percentage']."')";
			
			if($_POST['qty'][$i] != "" && $_POST['qty'][$i] != 0){
				mysql_query($vender_purchase_ins_qry);
			}
			
        //}
		$i++;
	}
	$f = mysql_fetch_array(mysql_query("SELECT location_link FROM vendors WHERE id = '".$_POST['login_vendor_id']."'"));
	header("location:buying_purchases_order.php?flag&purchase_id=".$vendor_purchase_id."&loc_id=".$f['location_link']);
}

function get_empmaster($emp_id){
	$query = mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees_master where empmaster_id =".$emp_id);
	if($query && mysql_num_rows($query) > 0){
		$query = mysql_fetch_array($query);
		return $query['name'];
	} else {
		return '';
	}
	
}

if(isset($_POST) && $_POST['Submit']=="Submit"){
	
//echo "<pre>"; print_r($_POST); die;
$digital_image_name = $_REQUEST['digital_image_name'];
$old_image = $_REQUEST['oldimage'];
	if($digital_image_name != '')
	{	

			$target_path = "temp_img/";
			$file_with_path = $digital_image_name;
			$target_path = $target_path . $file_with_path;
			 $ftphost = FTPDOMAIN;
					$ftpusr = FTPUSER;
					$ftppwd = FTPPASSWORD;


					$target_ftp_path = "inventory/";
					$ftp_path = $target_ftp_path . $file_with_path;

					$local_file = $target_path;
					$conn_id = ftp_connect($ftphost, 21);
					ftp_login($conn_id, $ftpusr, $ftppwd);
					ftp_pasv ($conn_id, true);
					$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
					ftp_close($conn_id);
					unlink($target_path);
					$image = $ftp_path;

	}else{
		$image = $old_image;
	}

$code_id = mysql_real_escape_string($_REQUEST['code_id']);
$name = mysql_real_escape_string($_REQUEST['name']);
$d_market = mysql_real_escape_string($_REQUEST['d_market']);
$status = mysql_real_escape_string($_REQUEST['status']);
$vendor_internal_number = mysql_real_escape_string($_REQUEST['vendor_internal_number']);
$pack_size = mysql_real_escape_string($_REQUEST['pack_size']);
$pack_unit_type = mysql_real_escape_string($_REQUEST['pack_unit_type']);
$qty_in_pack = mysql_real_escape_string($_REQUEST['qty_in_pack']);
$qty_in_pack_unit_type = mysql_real_escape_string($_REQUEST['qty_in_pack_unit_type']);
$qty_in_pack_size = mysql_real_escape_string($_REQUEST['qty_in_pack_size']);
$pack_weight = mysql_real_escape_string($_REQUEST['pack_weight']);

$price_by_weight = mysql_real_escape_string($_REQUEST['price_by_weight']);
$price_by_weight_unittype= mysql_real_escape_string($_REQUEST['price_by_weight_unittype']);

$lead_time= mysql_real_escape_string($_REQUEST['lead_time']);
$stock= mysql_real_escape_string($_REQUEST['stock']);

$taxable= mysql_real_escape_string($_REQUEST['taxable']);
$tax_type= mysql_real_escape_string($_REQUEST['tax_type']);
$tax_amount= mysql_real_escape_string($_REQUEST['tax_amount']);

$splitable=mysql_real_escape_string($_REQUEST['splitable']);
$splitable_price=mysql_real_escape_string($_REQUEST['splitable_price']);
$splits=mysql_real_escape_string($_REQUEST['splits']);
$splits_minimum=mysql_real_escape_string($_REQUEST['splits_minimum']);


$tax_percentage = mysql_real_escape_string($_REQUEST['tax_percentage']);
$price = mysql_real_escape_string($_REQUEST['price']);
$promotion = mysql_real_escape_string($_REQUEST['promotion']);
$promotion_price = mysql_real_escape_string($_REQUEST['promotion_price']);
$inv_item_type = mysql_real_escape_string($_REQUEST['inv_item_type']);
$inv_item = mysql_real_escape_string($_REQUEST['inv_item']);
$inv_item_new = mysql_real_escape_string($_REQUEST['inv_item_new']);
$group_id = mysql_real_escape_string($_REQUEST['group']);
$purchased_from_vendor = mysql_real_escape_string($_REQUEST['purchased_from_vendor']);
$purchased_price = mysql_real_escape_string($_REQUEST['purchased_price']);
$purchased_last = mysql_real_escape_string($_REQUEST['purchased_last']);
$group_ins_type = mysql_real_escape_string($_REQUEST['group_ins_type']);
$new_gruop = mysql_real_escape_string($_REQUEST['new_gruop']);

$vendor_id = mysql_real_escape_string($_REQUEST['purchase_vendor_id']);



if($group_ins_type=='new'){
				$q_max_group = mysql_query("SELECT IFNULL(MAX(CAST(group_id AS UNSIGNED))+1, 1000) as grp from inventory_groups");
				if($q_max_group && mysql_num_rows($q_max_group) > 0){
					$max_group = mysql_fetch_array($q_max_group);
				} else {
					$max_group = array();
				}
				
				$q_max_priority = mysql_query("SELECT IFNULL(MAX(CAST(priority AS UNSIGNED))+1, 1000) as priority from inventory_groups");
				if($q_max_priority && mysql_num_rows($q_max_priority) > 0){
					$max_priority = mysql_fetch_array($q_max_priority);
				} else {
					$max_priority = array();
				}
			 
			 if(count($max_priority) > 0 && count($max_group) > 0){
			 	$gquery = "INSERT INTO inventory_groups SET
					group_id = '".$max_group['grp']."',
					priority = '".$max_priority['priority']."',
					description = '".$new_gruop."',
					Market = '".$d_market."'";
					$res_g = mysql_query($gquery);// or die(mysql_error());
					if($res_g){
					$group = mysql_insert_id();
					}
			 }
			 
					
}else{
	$group = $group_id;
}
if($d_market=='Retail'){
		$inV_fields = "color ='".mysql_real_escape_string($_REQUEST['inv_color'])."',size ='".mysql_real_escape_string($_REQUEST['inv_size'])."',type ='".mysql_real_escape_string($_REQUEST['inv_type'])."',";
	}

if($inv_item_type=="new"){
	$query_last = mysql_fetch_array(mysql_query("SELECT max(CAST(id AS UNSIGNED)+1) as counts  from inventory_items"));
	$last_item_id = $query_last['counts'];
	
	$manufacturer_barcode = mysql_real_escape_string($_REQUEST['ni_barcode']);
	// $taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	//$image = mysql_real_escape_string($_REQUEST['image']);
	$model_number = mysql_real_escape_string($_REQUEST['ni_model']);
	$manufacturer = mysql_real_escape_string($_REQUEST['ni_manufacture']);
	$brand = mysql_real_escape_string($_REQUEST['ni_brand']);
	$notes = mysql_real_escape_string($_REQUEST['ni_notes']);
	$fields = "";
	
	
	// description = '".$inv_item_new."', 
	 $ins_query = "INSERT INTO inventory_items SET
				  item_id='".$last_item_id."',
				  inv_group_id = '".$group."',				  
				  status ='active',
				  description = '". $name ."', 
				  unit_type = '".$pack_unit_type."',
				  notes = '".$notes."',
				  brand = '".$brand."',
				  manufacturer = '".$manufacturer."',
				  model_number = '".$model_number."',
				  image = '".$image."',
				  $inV_fields
				  taxable = '".$taxable."',
				  manufacturer_barcode = '".$manufacturer_barcode."', 
				  
				  vendor_default='".$vendor_id  ."', 
				  vendor_id='". $vendor_id ."', 
				  
				  created_by = '".$_SESSION['client_id']."',
				  created_on = 'VendorPanel',
				  created_dt = '".date('Y-m-d H:i:s')."'";				 
				  // echo $ins_query;exit;
				 $res_ins=mysql_query($ins_query);//or die(mysql_error());
				 if($res_ins){
				 $inv_item_id = mysql_insert_id();
				 }
}else{
//echo "UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' WHERE id = '".$inv_item."'";
//exit;

	$ex_field = " , description = '". $name ."' ";

	// $taxable	= mysql_real_escape_string($_REQUEST['ni_taxable']);
	$taxable	= mysql_real_escape_string($_REQUEST['taxable']);
	$up_inv = mysql_query("UPDATE inventory_items SET $inV_fields taxable = '".$taxable."' ". $ex_field ." WHERE id = '".$inv_item."'");// or die(mysql_error());
	$inv_item_id = $inv_item;
}


$query_c ="SELECT id from vendor_items_inventory_counts WHERE vendor_id='".$vendor_id."' AND  inv_item_id='".$inv_item_id."'";
$query_check = mysql_query($query_c);
if(mysql_num_rows($query_check)==0){
	 $ins_count ="INSERT INTO vendor_items_inventory_counts SET 
				vendor_id='".$vendor_id."',
				inv_item_id='".$inv_item_id."',
				quantity='0',
				date_counted=DATE_FORMAT(now(),'%Y-%m-%d')";
				mysql_query($ins_count);	
}

if($code_id!=""){
				   $query1 = "UPDATE vendor_items SET
				   status = '".$status."',
				   pack_size ='".$pack_size."',				   
				   pack_unittype ='".$pack_unit_type."',
				   inv_item_id='".$inv_item_id."',
				   vendor_internal_number = '".$vendor_internal_number."',
				   qty_in_pack ='".$qty_in_pack."',
				   qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
				   qty_in_pack_size ='".$qty_in_pack_size."',
				   pack_weight ='".$pack_weight."',
				   
				   price_by_weight='".$price_by_weight."',
				   price_by_weight_unittype='".$price_by_weight_unittype."',
				   
				   lead_time='".$lead_time."',
				   stock='".$stock."',
				   
				   taxable='".$taxable."',
				   tax_type='".$tax_type."',
				   tax_amount='".$tax_amount."',
				   
				   
				   splitable='".$splitable."',
				   splitable_price='".$splitable_price."',
				   splits='".$splits."',
				   splits_minimum='".$splits_minimum."',

				   
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   promotion_price ='".$promotion_price."',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',
				   last_by = '".$_SESSION['client_id']."',
				   last_on = 'VendorPanel',
				   last_datetime = '".date('Y-m-d H:i:s')."'
				   WHERE id =".$code_id; //inv_item_id='".$inv_item_id."',
				
}else{
				   $query1 = "INSERT INTO vendor_items SET	
				   status = '".$status."',				
				   pack_size ='".$pack_size."',
				   pack_unittype ='".$pack_unit_type."',
				   qty_in_pack ='".$qty_in_pack."',
				   vendor_internal_number = '".$vendor_internal_number."',
				   qty_in_pack_unittype ='".$qty_in_pack_unit_type."',
				   qty_in_pack_size ='".$qty_in_pack_size."',
				   pack_weight ='".$pack_weight."',
				   
				   price_by_weight='".$price_by_weight."',
				   price_by_weight_unittype='".$price_by_weight_unittype."',
				   
				   lead_time='".$lead_time."',
				   stock='".$stock."',
				   
				   taxable='".$taxable."',
				   tax_type='".$tax_type."',
				   tax_amount='".$tax_amount."',
				   
				   splitable='".$splitable."',
				   splitable_price='".$splitable_price."',
				   splits='".$splits."',
				   splits_minimum='".$splits_minimum."',
				   
				   
				   tax_percentage ='".$tax_percentage."',
				   price ='".$price."',
				   promotion ='".$promotion."',
				   vendor_id = '".$vendor_id."',
				   purchased_from_vendor = '".$purchased_from_vendor."',
				   purchased_price = '".$purchased_price."',
				   purchased_last = '".$purchased_last."',
				   inv_item_id='".$inv_item_id."',
				   created_by = '".$_SESSION['client_id']."',
				   created_on = 'VendorPanel',
				   created_datetime = '".date('Y-m-d H:i:s')."',
				   promotion_price ='".$promotion_price."'";

}	
	$group1_inp = $_REQUEST['group1_inp'];
	$market_inp = $_REQUEST['market_inp'];
	
	if($group1_inp == ''){
		$group1_inp = $group_id;
	}
	if($market_inp == ''){
		$market_inp = $d_market;
	}
	//echo $query1;die;
	$res1 = mysql_query($query1);
	header('location:buying.php?vendor_id='.$vendor_id);
}

$unittype = "SELECT * from inventory_item_unittype ORDER BY conversion_group, unit_type";
$res_unit_type = mysql_query($unittype);

if(isset($_GET['action']) && $_GET['action'] == 'change_inv_status'){
	$id = $_REQUEST['id'];
	$sq = mysql_fetch_array(mysql_query("SELECT status FROM vendor_items WHERE inv_item_id  = '".$id."'"));
	$status = ($sq['status'] == 'active') ? 'inactive' : 'active';
	$q = mysql_query("UPDATE vendor_items SET status='".$status."' where inv_item_id='".$id."'");
	
	if($status == 'active'){
		$status_img = 1;
	} else {
		$status_img = 0;
	}
	
	echo $status_img;
	die;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SoftPoint | VendorPanel</title>
        <link rel="stylesheet" href="css/style.default.css" type="text/css" />
        <link rel="stylesheet" href="css/responsive-tables.css" />
        <link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
        <link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
        <link rel="stylesheet" href="css/dd.css" type="text/css">

        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
        <script type="text/javascript" src="js/modernizr.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
        <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
        <link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
        <script type="text/javascript" src="js/jquery.alerts.js"></script>
        <script type="text/javascript" src="js/elements.js"></script>
        <script type="text/javascript" src="prettify/prettify.js"></script>
        <script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/jquery.form.min.js"></script>
        <script type="text/javascript" src="js/jquery.alerts.js"></script>
        <script type="text/javascript" src="js/jquery.blockUI.js"></script>
        <script type="text/javascript" src="js/responsive-tables.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
		<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="js/jquery.dd.js"></script>
		<script type="text/javascript" src="js/jquery.session.js"></script>
		
        <?php 
        	if(!empty($_GET['vendor_id'])) {
        		$vendor_id = $_GET['vendor_id'];
        		echo "<script>jQuery(document).ready(function() {jQuery('#row_".$vendor_id."').addClass('selected'); jQuery('#row_".$vendor_id."').trigger('click')});</script>";
        	}

        ?>

        <style>
        	div#itemModal {
			    top: 4%;
			}
			.modal-body{
				max-height: 75vh;
			}
			.pbwut{
				display: none;
			}
            .selectouter12 {
				background: none repeat scroll 0 0 #ffffff; border: 1px solid #c9c9c9; float: left;
				height: 32px; line-height: 5px; margin: 0 0 7px; position: relative; width: 271px;
			}
            div#licence_table_length label { text-transform:capitalize; }
            body { top:0px!important; }
            tr{ background:none !important; }
            tr.selected { background: gray !important; }
            .goog-te-banner-frame{ margin-top:-50px!important; }
            .error { color:#FF0000;padding-left:10px; }
            /*.row-fluid .span4 { width: 32.6239%; margin-left:10px; }*/
            .span4 { float:left; width:28.5%!important; min-height:600px; margin-left:1.5%!important; }
            /*.unread showJobs selected{background-color:#cccccc;}*/
            table.table tbody tr.selected, table.table tfoot tr.selected { background-color: #808080; }
            .dataTables_filter input{ height:22px !important;}
            .dataTables_filter { top: 10px; }
        </style>
        <script type="text/javascript">
		//var vendor_id = '29';
		//var vendor_id = jQuery('#purchase_vendor_id').val();
		//console.log('vendor_id : '+vendor_id);
		
		
		
          jQuery(document).ready(function ($) {
			
			jQuery('#inv_item1').focus();
				
            jQuery('#pack_unit_type').msDropdown();
            jQuery('#qty_in_pack_unit_type_buying').msDropdown();
            jQuery('#price_by_weight_unittype').msDropdown();
			
			
			console.log(jQuery.session.get("compareLeftContent"));
			
			jQuery("#add_vendor").click(function () {
				jQuery('#addVendor').modal('show');
			});

			jQuery('#addVendor').on('hidden.bs.modal', function(e){
				jQuery('.clear').val('');
			})


			
			jQuery("#add_item").click(function () {
				jQuery('#ture_barcode').hide();
				jQuery('#itemModal').find('form')[0].reset();
				jQuery('#itemModal').modal('show');
				var v = jQuery('#price_by_weight').val();
				jQuery('#qty_in_pack_unit_type_buying').trigger( "click" );
				if(v==''){
					jQuery('#price_by_weight option[value=No]').attr('selected','selected');					
				}

				if(v=='Yes'){
					jQuery('.pbwut').removeClass('pbwut');
				}
				else{
					jQuery('.pbwut').addClass('pbwut');	
				}
				

			});
			
			jQuery(".purchase_orders").click(function () {
				console.log('purchase_orders');
				jQuery('#add_item').hide();
				jQuery('.deactive_submit_button').hide();
			});
			
			jQuery(".items_data").click(function () {
				console.log('items_data');
				jQuery('#add_item').show();
				jQuery('.deactive_submit_button').show();
			});
			
			
			
				jQuery('#detail_table').DataTable({
				   "sPaginationType": "full_numbers",
				   "bFilter": true,
				   "aaSorting": [[2, "asc"]],
				   "aLengthMenu": [5,10, 25, 50,100],
					"pageLength": 5,
				   "bScrollInfinite": false,
				   "fnDrawCallback": function (oSettings) {
					   jQuery.uniform.update();
				   }
			    });
				
				jQuery('#purchase_orders_table').DataTable({
				   "sPaginationType": "full_numbers",
				   "bFilter": true,
				   "aaSorting": [[2, "asc"]],
				   "aLengthMenu": [5,10, 25, 50,100],
					"pageLength": 5,
				   "bScrollInfinite": false,
				   "fnDrawCallback": function (oSettings) {
					   jQuery.uniform.update();
				   }
			    });
			   
                var t = jQuery('#licence_table').DataTable( {
                    select: true,
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[2, "asc"]],
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    },
                } );
                t.off("select").on( "select", function( e, dt, type, indexes ) {
                    console.log( e, dt, type, indexes );
                } );
                t.off("select").on( "deselect", function( e, dt, type, indexes ) {
                    console.log( e, dt, type, indexes );
                } );
                
                jQuery(".cl_order1").live('click', function () {
                    var ths  = jQuery(this).parents('tr');
                    console.log(ths);
                    jQuery(".gradeX").attr("class", "gradeX cl_order");
                    jQuery(ths).attr("class", "gradeX cl_order selected");
                    callajax1(ths);
                });
                
                jQuery(".cl_order").click(function () {
                    var ths  = jQuery(this).parents('tr');
                    console.log(ths);
                    jQuery(".gradeX").attr("class", "gradeX cl_order");
                    jQuery(ths).attr("class", "gradeX cl_order selected");
                    callajax(ths);

                });
				
				jQuery('#purchased_last').datepicker({
					 dateFormat: 'yy-mm-dd',
					 inline:true,
					 changeMonth:true,
					 changeYear:true
				});

				jQuery('#inv_item').live('change',function(){
					// alert();
					var inv_itm_val = jQuery(this).val();
					if(inv_itm_val=="new_inv_item"){
						jQuery('#drop_span').hide();
						jQuery('#new_span').show();
						jQuery('#inv_item_type').val('new');
						jQuery('#inv_item1').val('');
						jQuery('#inv_item1').focus();
						var status = jQuery('#status').val();
						var group = jQuery('#group').val();
						jQuery('#new_inv_div,#new_inv_div1').show();
						
						jQuery('#pack_unit_type').msDropdown().data("dd").destroy();
						jQuery('#pack_unit_type').msDropdown();
						jQuery('#qty_in_pack_unit_type_buying').msDropdown().data("dd").destroy();
						jQuery('#qty_in_pack_unit_type_buying').msDropdown();
						jQuery('#price_by_weight_unittype').msDropdown().data("dd").destroy();
						jQuery('#price_by_weight_unittype').msDropdown();
						jQuery('#ni_notes').val('');
						jQuery('#ni_barcode').val('');
						jQuery('#ni_manufacture').val('');
						jQuery('#ni_brand').val('');
						jQuery('#ni_model').val('');
						jQuery('#vendor_internal_number').val('');
						jQuery('#imagebox').html('');
						jQuery('#digital_image_name').val('');
						jQuery('#pack_size').val('');
						jQuery('#qty_in_pack_size').val('');
						jQuery('#pack_weight').val('');
						
						
						
						jQuery('#price_by_weight').val('No');
						//jQuery('#price_by_weight_unittype').val('');
						
						
						jQuery('#lead_time').val('');
						jQuery('#stock').val('');
						
						jQuery('#taxable').val('');
						jQuery('#tax_type').val('');
						jQuery('#tax_amount').val('');
						jQuery('#taxable').trigger('change');
						
						
						jQuery('#splitable').val('');
						jQuery('#splitable_price').val('');
						jQuery('#splits').val('');
						jQuery('#splits_minimum').val('');
						jQuery('#splitable').trigger('change');
						
						
						
						
						jQuery('#qty_in_pack').val('');
						// jQuery('#ni_taxable').val('');
						jQuery('#price').val('');
						jQuery('#purchased_from_vendor').val('');
					}else{
						jQuery('#drop_span').show();
						jQuery('#new_span').hide();
						jQuery('#inv_item_type').val('old');
						jQuery('#inv_item1').val('');
						jQuery('#new_inv_div,#new_inv_div1').hide();		
					}
					
					
				});

            });
            function getStorepointLocation(sId, loc_id)
            {
                var dataurl = "storepoint_getStorepointLocationInq.php?sId=" + sId + "&loc_id=" + loc_id;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,
                    dataType: "text",
                    success: function (data) {
                        if (data == '0') {
                            //location.reload();
                            return false;
                        }
                        else {
                            jQuery("#fdetail").css("display", "block");
                            jQuery("#fdetail").html(data);
                        }
                    }
                });
            }
            function callajax1(obj){
                var jobId = jQuery(obj).attr("id");
                var loc_id = jQuery(obj).attr("l_id");
                //jQuery("#".jobId).css("background-color","#808080");
                jQuery("#loc_id").val(loc_id);

                getStorepointLocation(jobId, loc_id);

                getEditLocation(jobId, loc_id);
            
            }
            function callajax(obj)
            {
                var jobId = jQuery(obj).attr("id");
                var loc_id = jQuery(obj).attr("l_id");
                //jQuery("#".jobId).css("background-color","#808080");
                jQuery("#loc_id").val(loc_id);

                getStorepointLocation(jobId, loc_id);

                //getEditLocation(jobId, loc_id);
            }
            var submitAction = true;
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


        <style>
        td.dataTables_empty {
            text-align: center;
        }
            .ui-datepicker{ z-index: 1100 !important; }
            
            #default_delivery_type_chzn ul.chzn-choices li.search-field input[type="text"] {
                _height: 32px !important;
                height: 14px !important;
            }
            #default_terms_chzn ul.chzn-choices li.search-field input[type="text"] {
                _height: 32px !important;
                height: 14px !important;
            }
            #default_payment_type_chzn ul.chzn-choices li.search-field input[type="text"] {
                _height: 32px !important;
                height: 14px !important;
            }
            .textCenter 
            {
                 text-align:ceter !important;
            }
            /*#details_of_vendor {
                background: #FFF; height: 80px; vertical-align: middle; text-align: center;
            }*/
        .fixed_height{
			max-height:586px !important;
			height:586px !important;
		} 
		
		.textboxwidth{
			width:256px !important;
		}
		.chzn-search input{ _height:28px !important; _width:260px !important;}
		.chzn-container,.input-xlarge{ _width:281px !important;}
		.chzn-container-multi .chzn-choices .search-field input{ height:30px !important;}
		.tagsinput input{ width:auto !important;}
		.ui-datepicker-month{ width:33% !important;}
		.ui-datepicker-year{ width:33% !important; margin-left:5%; }


		.social td {
			vertical-align:middle;
			line-height: 36px;
			border-top: 0;
			border-bottom: 1px solid #dddddd; 
		}
		ul.chzn-choices {
			height: 34px !important;
		}
		.chzn-container-multi .chzn-choices {

			height:auto;
		} 

        </style>
    </head>
    <!--head-->
    <body>
        <div class="mainwrapper">
            <?php require_once('require/top.php'); ?>
            <?php require_once('require/left_nav.php'); ?>
            <div class="rightpanel">
                <ul class="breadcrumbs">
                    <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
                    <li>Buying</li>
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
                    <!--<form action="results.html" method="post" class="searchbar">
                              <input type="text" name="keyword" placeholder="To search type and hit enter..." />
                          </form>-->
                    <div class="pageicon"><span class="iconfa-credit-card"></span></div>
                    <div class="pagetitle">
                        <h5>List of Wholesalers Vendor Purchases From</h5>
                        <h1>Buying</h1>
                    </div>
					<div><button style="margin-right:-10px;float:right;margin-top:-8px;" id="add_vendor" type="button" class="btn btn-success">Add Vendor</button></div>
                </div>
                <!--pageheader-->
				
				<div class="maincontent">
					<div class="maincontentinner" >
						<div class="row-fluid" id="inventoryTable" style="margin: 6px;">
							  <div class="span7" style="width:48%">
								<div class="widgetbox">
									<h4 class="widgettitle">Vendors</h4>
									<div class="widgetcontent">

										<table id="licence_table" class="table table-bordered table-infinite">
											<colgroup>
												<col class="con1" style="width:4%;" />
												<col class="con0" style="width:3%;"/>
												<col class="con1" style="width:25%;"/>
												<col class="con0" style="width:10%;"/>
												<col class="con1" style="width:10%;"/>
												<col class="con0" style="width:10%;"/>
												<col class="con1" style="width:8%;"/>
												<col class="con0" style="width:4%;"/>
											</colgroup>
											<thead>
												<tr>
													<th class="head0 center" >Image</th>
													<th class="head1" >S</th>
													<th class="head0" >Name</th>
													<th class="head1" >phone</th>
													<th class="head1" >Type</th>                        
													<th class="head0" >Terms</th>
													<th class="head1" >Lst Pur</th>
													<th class="head0 center" >A</th> 
												</tr>
											</thead>
											<tbody>
											<?php 
												//$res_act = mysql_query("SELECT * FROM vendors WHERE TRIM(name) <> 'Peddler\'s Son' and name is not NULL and name <> '' AND type is not null and type <> ''  ");
                                                $res_act = mysql_query("SELECT * FROM vendors
                                                            WHERE vendors.id != '".$_SESSION['StorePointVendorID']."' 
                                                            AND type is not null AND type <> ''");
															
												while($row_act = mysql_fetch_assoc($res_act)){
											?>
												<tr onClick="loadDetails(<?php echo $row_act['id']?>)" data-comments="" id="row_<?php echo $row_act['id']?>">
													<td style="line-height: 0px !important;text-align: center;vertical-align: middle;">&nbsp;<img style="height:25px;width:25px" onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['StorePoint_image']; ?>" ></td>													                           
													<td class="center"> 
													<?php
														if($row_act['status'] == 'active'){
															echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
														} else {
															echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
														}
													?>
													</td>		
													<td><?php echo '<b>'.$row_act['name']. '</b> (ID: '.$row_act['id'].')'; ?><br>
														<?php echo $row_act['address']." ".$row_act['address2']; ?><br>
														<?php if(!empty($row_act['city'])){echo $row_act['city'];} ?>,
														<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo $arr['name'];} ?>,
														<?php echo $row_act['zip']; ?>
													</td>
													<td><?php echo $row_act['phone']; ?><br><?php echo $row_act['contact']; ?></td>                          
													<td><?php 
															$exploded = explode(",", $row_act['type']); 
															foreach ($exploded as $type) {
																$innerType = mysql_fetch_array(mysql_query("SELECT * FROM vendors_types WHERE vendor_type_id = '".$type."'"));
																echo $innerType['code'];
																echo "<br>";
															}       ?></td>                            
													<td class="left"><?php echo $row_act['terms_types']; ?>
														<?php 
															$exploded = explode(",", $row_act['terms_types']); 
															foreach ($exploded as $type) {
																$innerType = mysql_fetch_array(mysql_query("SELECT * FROM vendors_terms_types WHERE vendors_terms_types = '".$type."'"));
																echo $innerType['code'];
																echo "<br>";
															}     ?>
													</td>
													<td><?php
														$f = mysql_fetch_array(mysql_query("SELECT lastchange_datetime FROM vendor_purchases WHERE vendor_id = '".$row_act['id']."' ORDER BY vendor_purchases_id DESC LIMIT 1"));
														echo ($f['lastchange_datetime'] != '0000-00-00 00:00:00') ? $f['lastchange_datetime'] : '';
													?></td>
													<td class="center" style="vertical-align:middle;">
													<!--<a href="backoffice_purchases.php?flag&purchase_id=" >
													<img title="Edit"  src="images/edit.png" ></a>-->
													<img  src="images/icons/search.png"></td>
													<!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
													<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
												</tr>
											<?php } ?>
											</tbody>
										</table>

									</div>
								</div>
							  </div>
							  
							  <div class="span5"style="width: 816px;"> <!-- 42.94% -->
								<div class="widgetbox"> 
									<h4 class="widgettitle itemnameD">Details  <button style="margin-right:-10px;float:right;margin-top:-8px;display:none" id="add_item" type="button" class="btn btn-success">Add Item</button></h4>
									<div class="widgetcontent fixed_height" id="details_of_vendor">
										<!-- Start tabls -->
											<h5>Select a Vendor</h5>
										<!-- end tabls -->
									</div>
								</div>
							  </div>
							 
						</div>
							
						  <div style="clear: both;"></div>
						<?php include_once 'require/footer.php';?>
					</div><!--maincontentinner-->
					 
						<!--footer-->
				</div><!--maincontent-->
				
            </div>
        </div>
            <!--rightpanel-->
        <!--mainwrapper-->
		
		<div id="submitPurchaseOrder" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3>Submit Purchase Order</h3>
            </div>
            <div class="modal-body" id="submitPurchaseOrder_html"></div>
            <div class="modal-footer" style="text-align:center;">
                <a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
                <button  onClick="" class="btn btn-primary">Submit</button>
            </div>
        </div>
		
		<div id="itemModal" class="modal hide fade" style="width:500px">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3>Add Item</h3>
            </div>
			<form id="edit_from" onSubmit="return validate()" name="frm" action="" method="post" >
            <div class="modal-body" id="itemModal_html" style="overflow-x: hidden">
                  <input type="hidden" name="code_id" value="" id="code_id">
				  <input type="hidden" name="purchase_vendor_id" value="" id="purchase_vendor_id">
                  <table>
					<tr>
						<td>
							<label>Status:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
							<select name="status" id="status" style="width:270px;">
								<option value="" > - - - Select Status - - -</option>
								<option selected="selected" value="active">Active</option>
								<option value="inactive" >Inactive</option>
							</select>
							</span>
						</td>
					</tr>
					
					<tr id="barcode_p">
						<td>
							<label style="width:107px">Barcode:</label>
						</td>
						<td  style="width:20%">
							<span class="field input-append" style="/*margin:0;*/"> 
							<input style="width: 230px;"  onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="barcode" id="barcode"  class="input-short" value="" />
							<input type="hidden" id="barcode_valid" value="">
							<span class="add-on" > 
							<a href="javascript:void(0);" rel="client" onClick="getbarcode(2)" class="icon-search" ></a>
							</span>
							<span style="display:none;" id="ture_barcode" class="add-on1">
							<img style="margin-left:5px; position: relative; right: 50px; top: 5px;" src="images/Active, Corrected, Delivered - 16.png" title="Found">
							</span>
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Market:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select id="d_market" onChange="get_group(this.value,'',true)" style="width:270px;" name="d_market">
									<option value="">- - - Select Market - - -</option>
									<option value="All">All</option>
									<option value="Bar">Bar</option>
									<option value="Hotel">Hotel</option>
									<option value="Restaurant">Restaurant</option>
									<option value="Retail">Retail</option>
									<option value="Other">Other</option>
								</select>
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Group:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field" id="group_span">
								<select name="group" id="group" style="width:270px;">
								  <option value="" >- - - Select Group - - -</option>
								</select>
							</span>
								<input type="hidden" name="group_ins_type" id="group_ins_type" value="old">
								<span class="field" id="new_group_span"  style="display:none;">
								<input type="text" class="input-xlarge textboxwidth" id="new_gruop" value="" name="new_gruop" >
								<span style="vertical-align:top;margin-left: 250px;" id="group_cancel_btn"><i style="margin-top: -32px;" class="icon-remove"></i></span> </span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Inventory Items:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field" id="drop_span">
							<select id="inv_item" name="inv_item" style="width:270px;">
								<option value="" >- - -Select Inventory Item - - -</option>
								<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>
							</select>
							</span>
							<input type="hidden" name="inv_item_type" id="inv_item_type" value="old">
							<span class="field" id="new_span" style="display:none;z-index:999999;position:relative;">
							<input type="text" class="input-xlarge textboxwidth" id="inv_item1" value="" name="inv_item_new" >
							<span style="margin-left: 250px;" id="cancel_btn"><i style="margin-top: -32px;" class="icon-remove"></i></span> </span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Name:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="name" value="" name="name">
							</span>
						</td>
					</tr>
								
					<tr>
						<td>
							<label>Pack Unit Type:<span style="color:#FF0000;">*</span></label>    
						</td>
						<td  style="width:20%">
							<span class="field">
								<div style="width:270px;" class="selectouter12 select_w3">
									<select name="pack_unit_type" id="pack_unit_type" style="width:270px;" >
										<option value="" >- - - Select Unit Type - - -</option>
										<?php
											$opt_lbl = '';
											while($row_unit_type=mysql_fetch_array($res_unit_type)) {
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
										?>
										<option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type'];?></option>
										<?php
											}
										?>
									</select>
								</div>
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Qty In Pack:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="qty_in_pack" value="" name="qty_in_pack">
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Qty In Pack Unit Type:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<div style="width:270px;" class="selectouter12 select_w3">
								<select name="qty_in_pack_unit_type" id="qty_in_pack_unit_type_buying" style="width:270px;" >
								  <option value="" >- - - Select Unit Type - - -</option>
								  <?php
									$res_unit_type = mysql_query($unittype);
									
									$opt_lbl = '';
									while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
										if($opt_lbl != $row_unit_type['conversion_group']){
											echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
										}
										$opt_lbl = $row_unit_type['conversion_group'];
									?>
									
								  <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
								  <?php } ?>
								</select>
						   </div>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Qty In Pack Size:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="qty_in_pack_size" value="" name="qty_in_pack_size">
							</span>
						</td>
					</tr>
					
					
					<tr id="tax_per" style="display:none;">
						<td>
							<label>Tax Percentage:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="tax_percentage" value="" name="tax_percentage">
							</span>
						</td>
					</tr>
										
					<tr>
						<td>
							<label>Price By Weight:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="price_by_weight" id="price_by_weight" style="width: 270px;">
									<option value="">--- Select Price by Weight ---</option>
									<?php
										$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'price_by_weight'");
										while ($row = mysql_fetch_row($type)) {
											foreach(explode("','",substr($row[1],6,-2)) as $option) {
												$selected = '';//($price_by_weight == $option)? 'selected' : '';
												echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
											}
										}
									?>
								</select>
							</span>
						</td>
					</tr>
					
					<tr class="pbwut">
						<td>
							<label>Price By Weight Unit Type:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<div class="selectouter12 select_w3">
									<select name="price_by_weight_unittype" id="price_by_weight_unittype" style="width:270px;" >
									  <option value="" >- - - Select Unit Type - - -</option>
									  <?php
										$res_unit_type = mysql_query($unittype);

										$opt_lbl = '';
										while($row_unit_type= mysql_fetch_array($res_unit_type)){
											if($row_unit_type['conversion_group'] == 'weight'){
												if($opt_lbl != $row_unit_type['conversion_group']){
													echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
												}
												$opt_lbl = $row_unit_type['conversion_group'];
											?>
										  <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
										<?php } } ?>
									</select>
								</div>
							</span>
						</td>
					</tr>
					

					<tr>
						<td>
							<label>Price:<span style="color:#FF0000;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" onBlur="fix(this.value,'price')" onKeyPress="return isNumberKey(event)" class="input-xlarge textboxwidth" id="price" value="" name="price">
							</span>
						</td>
					</tr>

					<tr>
						<td>
							<label>Lead Time:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="lead_time" value="" name="lead_time">
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Stock:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="stock" id="stock" style="width: 270px;">
									<option value="">--- Select Stock ---</option>
									<?php
										$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'stock'");
										while ($row = mysql_fetch_row($type)) {
											foreach(explode("','",substr($row[1],6,-2)) as $option) {
												$selected = $option == 'No' ? 'selected': '';//($stock == $option)? 'selected' : '';
												echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
											}
										}
									?>
								</select>
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Taxable:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="taxable" id="taxable" style="width: 270px;">
									<option value="">--- Select Taxable ---</option>
									<?php
										$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'taxable'");
										while ($row = mysql_fetch_row($type)) {
											foreach(explode("','",substr($row[1],6,-2)) as $option) {
                                                $selected = $option == 'No' ? 'selected': '';//($stock == $option)? 'selected' : '';
												echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
											}
										}
									?>
								</select>
							</span>
						</td>
					</tr>
					
					<tr class="vi_taxable">
						<td>
							<label>Tax Type:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="tax_type" id="tax_type" style="width: 270px;">
									<option value="">--- Select Tax Type ---</option>
									<?php
										$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'tax_type'");
										while ($row = mysql_fetch_row($type)) {
											foreach(explode("','",substr($row[1],6,-2)) as $option) {
												$selected = '';//($tax_type == $option)? 'selected' : '';
												echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
											}
										}
									?>
								</select>
							</span>
						</td>
					</tr>
					
                    <tr class="vi_taxable">
						<td>
							<label>Tax Amount:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="tax_amount" value="" name="tax_amount">
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							 <label>Promotion:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<!--<input type="text" class="input-xlarge textboxwidth" id="promotion" value="" name="promotion">-->
								<select name="promotion" id="promotion" style="width: 270px;">
									<option value="">--- Select Promotion ---</option>
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</span>
						</td>
					</tr>
                    
                    <tr>
						<td>
							<label>Promotion Price:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="promotion_price" value="" name="promotion_price">
							</span>
						</td>
					</tr>
					
					
					<tr class="retails" style="display:none;">
						<td>
							<label>Color:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="inv_color" id="inv_color" style="width:270px;" >
									<option value="">- - -Select Color- - -</option>
									<?php $cquery = mysql_query("select id,code from inventory_items_color");
										while($cres = mysql_fetch_array($cquery)){ ?>
											<option value="<?php echo $cres['id']; ?>"><?php echo $cres['code']; ?></option>
										<?php } ?>                        
								</select>
							</span>
						</td>
					</tr>
					
					<tr class="retails" style="display:none;">
						<td>
							<label>Size:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="inv_size" id="inv_size" style="width:270px;" >
									<option value="">- - -Select Size- - -</option>
									<?php $squery = mysql_query("select id,code from inventory_items_size");
										while($sres = mysql_fetch_array($squery)){ ?>
											<option value="<?php echo $sres['id']; ?>"><?php echo $sres['code']; ?></option>
										<?php } ?>                        
								</select>
							</span>
						</td>
					</tr>
					
					<tr class="retails" style="display:none;">
						<td>
							<label>Type:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="inv_type" id="inv_type" style="width:270px;" >
									<option value="">- - -Select Type- - -</option>
									<?php $tquery = mysql_query("select id,code from inventory_items_type");
										while($tres = mysql_fetch_array($tquery)){ ?>
											<option value="<?php echo $tres['id']; ?>"><?php echo $tres['code']; ?></option>
										<?php } ?>                        
								</select> 
							</span>
						</td>
					</tr>
					
					<tr><td><strong>Split Information </strong> </td></tr>
                    
                    <tr>
						<td>
							<label>Splitable:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="splitable" id="splitable" style="width: 270px;">
									<option value="">--- Select Splitable ---</option>
									<?php
										$type = mysql_query("SHOW COLUMNS FROM vendor_items WHERE Field = 'splitable'");
										while ($row = mysql_fetch_row($type)) {
											foreach(explode("','",substr($row[1],6,-2)) as $option) {
                                                $selected = $option == 'No' ? 'selected': '';//($stock == $option)? 'selected' : '';
												echo '<option value="'. $option .'" '. $selected .'>'. $option .'</option>';
											}
										}
									?>
								</select> 
							</span>
						</td>
					</tr>
                      <tr class="vi_splitable">
                          <td>
                              <label>Splitable Price:</label>
                          </td>
                          <td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="splitable_price" value="" name="splitable_price" />
							</span>
                          </td>
                      </tr>

                      <tr class="vi_splitable">
                          <td>
                              <label>Splits:</label>
                          </td>
                          <td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="splits" value="" name="splits" />
							</span>
                          </td>
                      </tr>

                      <tr class="vi_splitable">
                          <td>
                              <label>Splits Minimum:</label>
                          </td>
                          <td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="splits_minimum" value="" name="splits_minimum" />
							</span>
                          </td>
                      </tr>

					<tr><td><strong>Shipping Information </strong> </td></tr>
                    
                    <tr>
						<td>
							<label>Pack Size: (in inches)</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="pack_size" value="" name="pack_size">
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Pack Weight: (in pounds)</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="pack_weight" value="" name="pack_weight">
							</span>
						</td>
					</tr>
                    
					<tr><td><strong>Activity Information </strong> </td></tr>

					<tr>
						<td>
							<label>Internal Number:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="vendor_internal_number" value="" name="vendor_internal_number">
							</span>
						</td>
					</tr>
					
                    <tr>
						<td>
							<label>Created On:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" readonly id="created_on" value="VendorPanel " name="created_on">
							</span>
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Created By:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="created_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="created_by">
							</span>
						</td>
					</tr>
                    
                   <tr>
						<td>
							<label>Created Date & Time:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="created_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="created_datetime">
							</span>
						</td>
					</tr>
					
					<tr id="for_edit" style="display:none;">
						<td>
							<label>Last On:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="last_on" value="VendorPanel" readonly name="last_on">
							</span>
						</td>
					</tr>
					
					<tr id="for_edit" style="display:none;">
						<td>
							<label>Last By:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="last_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="last_by">
							</span>
						</td>
					</tr>
					
					<tr id="for_edit" style="display:none;">
						<td>
							<label>Last Date & Time:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" class="input-xlarge textboxwidth" id="Last_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="last_datetime">
							</span>
						</td>
					</tr>

                  <input type="hidden" id="group1_inp" name="group1_inp" value="" >
                  <input type="hidden" id="market_inp" name="market_inp" value="" >
				</table>
			</div>
			<div class="modal-footer" style="text-align:center;">
                <a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
                <button name="Submit" value="Submit" class="btn btn-primary">Submit</button>
            </div>
			</form>
            
        </div>
		
        <div id="mymodal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3>Inquiry</h3>
            </div>
            <div class="modal-body" id="mymodal_html">

            </div>
            <div class="modal-footer" style="text-align:center;">
                <a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
                <button  onClick="submitPostListing();" class="btn btn-primary">Submit</button>
            </div>
        </div>
		
		<div id="addVendor" class="modal hide fade" style="width:500px">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3>Add vendor</h3>
            </div>
            <form method="post" id="add_vendor_form">

            <div class="modal-body" id="addVendor_html">
				<table>
					<tr>
						<td>
							<label>Status:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorstatus" id="vendorstatus" class="select-xlarge clear" >
								 <option value="active">Active</option>
								 <option value="inactive">Inactive</option>
								 <option value="suspended">Suspended</option>
								 <option value="not_registered">Not Registered</option>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Name:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorname" id="vendorname" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Image: (Image Size Required 225w x 225h)</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<div class="par">
								  <input type="hidden" name="old_store_img" id="old_store_img" value=""/>
								  <span id="imagebox1" style="width:100%; display:none;"></span>
								  <image  id="old_store_image" onerror="this.src='images/noimage.png'" style="width:100px; display:none;" src=""/>
									<p class="colorboximg" style="/*margin:20px 218px;*/">
									<input type="hidden" name="digital_image_name1" id="digital_image_name1" value="">
									<input type="hidden" name="digital_image_delete1" id="digital_image_delete1" value="N">
									<a href="upload_storepoint_image.php" id="colorbox_img" style="display:none;"></a>
									<input type="button" name="upload_image" id="upload_image" value="Upload Image" rel="<?php echo $_GET['idads']; ?>" class="submit-green btn btn-primary">
								  </p>
								</div>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Contact:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorcontact" id="vendorcontact" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Title:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendortitle" id="vendortitle" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Email:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendoremail" id="vendoremail" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Address:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendoraddress" id="vendoraddress" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Address 2:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendoraddress2" id="vendoraddress2" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Country:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorcountry" id="vendorcountry" class="select-xlarge clear" >
									 <option value=""> - - - Select Country - - - </option>
									<?php   $selcountry = "";
										$defaultcountry = "";
										$sqlctry = "SELECT * FROM countries ORDER BY name ASC";
										$resultctry =mysql_query($sqlctry);
										while ($rowctry = mysql_fetch_assoc($resultctry)){
											if ($rowctry["is_default"]=="yes"){
												$selcountry = "selected";
												$defaultcountry = $rowctry["id"];
											}else $selcountry ="";
									?>
									 <option value="<?php echo $rowctry["id"]?>" <?php echo $selcountry;?>><?php echo $rowctry["name"]?></option>
									<?php }?> 
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>City:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorcity" id="vendorcity" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>State:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								
								  <?php 
								  
								  $myven  =   "SELECT StorePoint_vendor_Id,image,first_name,last_name,state,country,city FROM employees_master WHERE StorePoint='Yes' AND email='".$_SESSION['email']."'";
								  $quemyven =   mysql_query($myven) or die(mysql_error());
								  $rowmyven = mysql_fetch_array($quemyven);     
								  $myven2   =   "SELECT * FROM vendors WHERE id='".$rowmyven["StorePoint_vendor_Id"]."'";
								  $quemyven2  =   mysql_query($myven2) or die(mysql_error());
								  $rowmyven2  = mysql_fetch_array($quemyven2);
								  ?>
								<select name="vendorstate" id="vendorstate" class="select-xlarge clear" >
										 <option value=""> - - - Select State - - - </option>
										 <?php
										 
											  $sqlst2 = "";
											  if ($defaultcountry!="") {$sqlst2 = " WHERE country_id = {$defaultcountry}";}
											   $sqlst = "SELECT * FROM states " . $sqlst2 . " ORDER BY name ASC";
											$resultst =mysql_query($sqlst);
											while ($rowst = mysql_fetch_assoc($resultst)){
										?>
										 <option value="<?php echo $rowst["id"]?>" <?php if($rowmyven2['state']==$rowst["id"]) echo "selected" ?>><?php echo $rowst["name"]?></option>
										<?php }?> 
									</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Zip:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorzip" id="vendorzip" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Phone:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorphone" id="vendorphone" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Fax:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorfax" id="vendorfax" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Website:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorwebsite" id="vendorwebsite" class="input-xlarge clear"/>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Currency:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorcurrency" id="vendorcurrency" class="select-xlarge clear" >
									 <option value=""> - - - Select Currency - - - </option>
									 <? $sqlcur = "SELECT id,code FROM global_currency WHERE code='USD'";
										$resultcur = mysql_query($sqlcur);
										$rowcurrency = mysql_fetch_assoc($resultcur);
										echo "<option value='".$rowcurrency["id"]."' selected>".$rowcurrency["code"]."</option>";
										$sqlcur = "SELECT id,code FROM global_currency ORDER BY code";
										$resultcur = mysql_query($sqlcur);
										while ($rowcurrency = mysql_fetch_assoc($resultcur)){
											if ($rowcurrency['code']=="USD") continue;
									?><option value="<?php echo $rowcurrency["id"]?>" ><?php echo $rowcurrency["code"];?></option>
									<?php                    }
										?>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Description:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<textarea name="vendordescription" id="vendordescription" class="input-xlarge clear"></textarea>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Types of Products:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendortype[]" id="vendortype" data-placeholder=" - - - Select Type Of Products - - - " class="select-xlarge clear" multiple="multiple">                 
									 <? $sqltype = "SELECT vendor_type_id,code FROM vendors_types ORDER BY code";
										$resulttype = mysql_query($sqltype);
										while ($rowtype = mysql_fetch_assoc($resulttype)){
									?><option value="<?php echo $rowtype["vendor_type_id"]?>" ><?php echo $rowtype["code"];?></option>
									<?php                    }
										?>
									 
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Vendor Terms:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorterm[]" id="vendorterm" data-placeholder="- - - Select Vendor Terms - - -" class="chzn-select select-xlarge clear" multiple="multiple">                 
									 <? $sqlterms = "SELECT vendors_terms_types id,code FROM vendors_terms_types ORDER BY id";
									 /*$sqlterms = "SELECT id,terms FROM vendor_terms ORDER BY id";*/
										$resultterms = mysql_query($sqlterms);
										while ($rowterms = mysql_fetch_assoc($resultterms)){
									?><option value="<?php echo $rowterms["id"]?>" ><?php echo $rowterms["code"];?></option>
									<?php                    }
										?>
									 
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Payment Types:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorPaymentType[]" id="vendorPaymentType" data-placeholder=" - - - Select Payment Types - - - " class="select-xlarge clear" multiple="multiple">                 
									 <?php $sqlptype = "SELECT vendors_payments_id,code FROM vendors_payment_types ORDER BY code";
										$resultptype = mysql_query($sqlptype);
										while ($rowptype = mysql_fetch_assoc($resultptype)){
									?><option value="<?php echo $rowptype["vendors_payments_id"]?>" ><?php echo $rowptype["code"];?></option>
									<?php } ?>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Delivery Types:<span style="color:red;">*</span></label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<select name="vendorDeliveryType[]" id="vendorDeliveryType" data-placeholder=" - - - Select Delivery Types - - - " class="select-xlarge clear" multiple="multiple">                 
									 <?php $sqldtype = "SELECT vendors_delivery_types_id,code FROM vendors_delivery_types ORDER BY code";
										$resultdtype = mysql_query($sqldtype);
										while ($rowdtype = mysql_fetch_assoc($resultdtype)){
									?><option value="<?php echo $rowdtype["vendors_delivery_types_id"]?>" ><?php echo $rowdtype["code"];?></option>
									<?php } ?>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Created On:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorcreatedon" id="vendorcreatedon" value="vendorPanel" class="input-xlarge" disabled />
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Created By:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendorcreatedby" id="vendorcreatedby" class="input-xlarge" value="Self" disabled />
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<label>Created Date & Time:</label>
						</td>
						<td  style="width:20%">
							<span class="field">
								<input type="text" name="vendordatetime" id="vendordatetime" value="<?=date('Y-m-d H:i:s')?>" class="input-xlarge" disabled />
							</span>
						</td>
					</tr>
				</table>
				<input type="hidden" name="client_id" id="client_id" value="<?=$empmaster_id?>">
				<input type="hidden" name="email" id="current_email">
            </div>
            <div class="modal-footer" style="text-align:center;">
                <a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
                <button  type="submit" class="btn btn-primary" id="vendor_submit">Submit</button>
            </div>
			
			</form>
        </div>

        <style>
            .modal-body label { margin-top: 3px; }
            .modal-body td { vertical-align: top; }
            .btn-default{ color: #000 !important; }
            /*.chzn-container{ width: 310px !important; margin-bottom: 10px; }*/
            .default { height: 32px !important; }
            .search-field {  min-height: 28px !important; }
        </style>

        <div id="composeModal" class="modal hide fade" style="max-height:500px !important;">
            <form id="frmComposeEdit" name="" action="" method="post" class="">
                
                <div class="modal-header" style="max-height:50px !important;">
                    <button type="button" onClick="cancel_edit_popup()"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Edit Location Details</h3>
                </div>
                <div class="modal-body" id="editLoca_body" style="max-height:350px !important;">
                    <input type="hidden" name="editLocation" value="1010" />
                    <input type="hidden" id="loc_id" name="location_id" value="<?php echo $_SESSION['loc'];?>" />
                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['StorePointVendorID'];?>" />
                    
                </div>
                <div class="modal-footer" style="text-align: center;max-height:50px !important;">
                    <p class="stdformbutton">
                        <button type="button" id="btnCancel" data-dismiss="modal" class="btn btn-default" style="padding: 5px 12px 5px;" onClick="cancel_edit_popup();">Cancel</button>
                        <button type="button" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;" onClick="return validate_edit_popup();">Submit</button>
                    </p>
                </div>
            </form>
        </div>
        
       
        <div id="composeModalAdd" class="modal hide fade" style="max-height:500px !important;">
            <form id="frmCompose" name="frmCompose" action="" method="post" class="frmComposClss">
                
                <div class="modal-header" style="max-height:50px !important;">
                    <button type="button" onClick="cancel_edit_popup()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Add Location Details</h3>
                </div>
                <div class="modal-body" id="addLoca_body" style="max-height:350px !important;">
                    <input type="hidden" name="editLocation" value="1010" />
                    <input type="hidden" id="loc_id" name="location_id" value="<?php echo $_SESSION['loc'];?>" />
                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['StorePointVendorID'];?>" />
                    
                </div>
                <div class="modal-footer" style="text-align: center;max-height:50px !important;">
                    <p class="stdformbutton">
                        <button type="button" id="btnCancel" data-dismiss="modal" class="btn btn-default" style="padding: 5px 12px 5px;" onClick="cancel_edit_popup();">Cancel</button>
                        <button type="button" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;" onClick="return validate_edit_popup1();">Submit</button>
                    </p>
                </div>
            </form>
        </div>
		
		<div id="filter_modal" style="height:600px !important;" class="modal hide fade">

			<div class="modal-header" >
			  <button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
			  <h3>Search Vendor</h3>
			  <br>
			  <label>Search:&nbsp;&nbsp;
				  <div class="input-append">
					  <input name="keyword" id="keyword" type="text"  onKeyUp="javascript:GetVendor(2)"  
					  tabindex="0" style="width:400px;"  />
					  <span class="add-on" ><a href="javascript:void(0);" class="icon-search" ></a></span>
				  </div>
				  <!--<a href="#" id="client_add"> <img id="ai" src="images/Add_16.png"></a>-->
			  </label>
			</div>
			<div class="modal-body" id="modalcontent" style="height:415px;"> </div>
			<div class="modal-footer" style="text-align: center;">
			  <p >
				<button data-dismiss="modal" class="btn btn-primary">Cancel</button>
			  </p>
			</div>
		</div>
		
		<div id="StoreimageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 id="myModalLabel">Add/Edit Media</h3>
			</div>
			<div class="modal-body " id="mymodalhtml"></div>
				<div class="modal-footer" style="text-align:center;">
			<button aria-hidden="true" style="color:#333333 !important;" data-dismiss="modal" class="btn">Cancel</button>
			<button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
			</div>
		</div>
		

        <?php if (isset($_SESSION['ins_Loc'])) { ?>
        <script>                      
            jAlert('Record updated.','Alert');
            return false;            
            <?php unset($_SESSION['ins_Loc']); ?>
        </script>
        <?php } ?>
        
        <script type="text/javascript">
			jQuery(document).on("click","#upload_image", function() { 
				var clientid= jQuery(this).attr("rel");
				jQuery.ajax({
					url:'upload_storepoint_image.php?id='+clientid,
					type:'POST',
					success:function(data){
						jQuery('#mymodalhtml').html(data);
						jQuery('#StoreimageModal').modal('show');
					}
				});
			});
			
			function isNumberKey(e){
	
				if(e.which == 46){
					if(jQuery(this).val().indexOf('.') != -1) {
						return false;
					}
				}

				if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
					return false;
				}
			}
			
             function getaddLocation(id){
                var dataurl = "storepoint_get_vender_location_info.php?id=" + id + "&loc_id=";
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {                        
                           jQuery('#addLoca_body').html(data);
                           jQuery("#composeModalAdd").modal("show");
                    }
                });
            }
            
            jQuery(document).ready(function(event){
                jQuery("#default_terms").chosen();
                jQuery("#default_delivery_type").chosen();
                jQuery("#default_payment_type").chosen();
            });

            jQuery(function () {
                jQuery("#reminder").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    //minDate: 0
                });

            });
             function validate_edit_popup1(){
                var myForm = document.getElementById('frmCompose');
                var flag = 0;
                if(jQuery("#loc_id_search").val()==''){
                    jAlert('Please select Location Id from Search!','Alert');
                    flag = 1; return false;
                }else if(jQuery("#primary_contact").val()==''){               
                    jAlert('Please enter primary contact!','Alert');
                    flag = 1; return false;
                }
                if(flag == 0){
                    jQuery('.frmComposClss').submit();
                }
            }
            function validate_edit_popup(){
                var myForm = document.getElementById('frmComposeEdit');
                var flag = 0;
                if(jQuery("#loc_id").val()==''){
                    jAlert('Please select Location Id from Search!','Alert');
                    flag = 1; return false;
                }else if(jQuery("#primary_contact").val()==''){               
                    jAlert('Please enter primary contact!','Alert');
                    flag = 1; return false;
                }
                if(flag == 0){
                    jQuery('#frmComposeEdit').submit();
                }
            }
            function cancel_edit_popup(){ 
                 window.location.reload();
                 return false;              
                var myForm = document.getElementById('frmComposeEdit');
                myForm.primary_contact.value = '';
                myForm.sale_variance.value = '';
                myForm.default_delivery_type.value = '';
                myForm.default_terms.value = '';
                myForm.default_payment_type.value = '';
                myForm.primary_contact_employee_id.value = '';
                myForm.primary_contact_email.value = '';
                myForm.primary_contact_phone.value = '';
                myForm.note.value = '';
                myForm.reminder.value = '';                                            
                myForm.created_datetime.value = '<?php echo date('Y-m-d h:i');?>';                               jQuery('#created_datetime').val('<?php echo date('Y-m-d h:i:s');?>');                
                jQuery('#last_tbl').remove();
            }

            function getEditLocation(id, loc_id){
                var dataurl = "storepoint_get_vender_location_info.php?id=" + id + "&loc_id=" + loc_id;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {
                        if (data == 'notfound') {
                            cancel_edit_popup();
                            return false;
                        }
                        else {
                            jQuery('#editLoca_body').html(data);
                            

                        }
                    }
                });
            }
            
            function get_employee(emp_id){
                var dataurl = "storepoint_get_employee_info.php?emp_id=" + emp_id ;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {
                        var res = jQuery.parseJSON(data);
                        console.log("get_employee, res.first_name: " + res.first_name);
                        if(res.first_name!=undefined){
                        
                        jQuery('#primary_contact').val(res.first_name+' '+res.last_name);
                        }else{
                        
                        jQuery('#primary_contact').val(' ');
                        }
                        jQuery('#primary_contact_email').val(res.email);
                        jQuery('#primary_contact_phone').val(res.telephone);
                    }
                });
            }
                
        </script>



        <script type="text/javascript">
            var oTable2;
			var oTable;
            function loadDetails(vendor_id) {
				
				jQuery.ajax({
					//url: "get_buying_details.php",
					url: "get_buying_details.php",
					//data: {uid:'1',location_id:3016,tab:2,reminder:'all',status:'Contacted',name:'Sofia+Ferrai'},
				    data: { m: "get_vendor_details", vendor_id: vendor_id },
				    success: function (data) {
					   jQuery("#details_of_vendor").html(data);
					   oTable2 = jQuery('#detail_table_item').DataTable({
						   "sPaginationType": "full_numbers",
						   "bFilter": true,
						   "aLengthMenu": [5,10, 25, 50,100],
						   "pageLength": 5,
						   "aaSorting": [[1, "asc"]],
						   "bScrollInfinite": false,
						   "fnDrawCallback": function (oSettings) {
							   jQuery.uniform.update();
							}
				       });
						oTable = jQuery('#detail_table_purchase').DataTable({
						   "sPaginationType": "full_numbers",
						   "bFilter": true,
						   "aLengthMenu": [5,10, 25, 50,100],
						   "pageLength": 10,
						   "aaSorting": [[1, "desc"]],
						   "bScrollInfinite": false,
						   "fnDrawCallback": function (oSettings) {
							   jQuery.uniform.update();
							}
				       });
					   jQuery('#purchase_vendor_id').val(vendor_id);
					   jQuery('#group').attr('data-vendor_id_from_group', vendor_id);
					   jQuery('#barcode').attr('data-vendor_id_from_barcode', vendor_id);
				    }
				
				});
				
				
				jQuery('#add_item').show();

            }
			
			function loadDetailsPurchaseOrder(vendor_id) {

                jQuery.ajax({
                      method: "POST",
                      url: "buying.php",
                      data: { m: "get_vendor_details_of_po", vendor_id: vendor_id }
                    }).done(function( msg ) {
                        console.log(msg);
						jQuery("#details_of_vendor").html('');
						   jQuery("#details_of_vendor").html(msg);
						   jQuery('#detail_table').DataTable({
							   "sPaginationType": "full_numbers",
							   "bFilter": true,
							   "aaSorting": [[2, "asc"]],
							   "aLengthMenu": [5,10, 25, 50,100],
								"pageLength": 5,
							   "bScrollInfinite": false,
							   "fnDrawCallback": function (oSettings) {
								   jQuery.uniform.update();
							   },
						   });
                      });
				jQuery('#add_item').hide();	 
            }
			
			function loadDetails2(vendor_id) {
				jQuery('#add_item').hide();
				var html =  '<button type="button" class="btn btn-primary" onclick="loadDetails('+vendor_id+')">Items</button><button onclick="loadDetailsPurchaseOrder('+vendor_id+')" style="margin-left:5px" type="button" class="btn btn-primary">Purchase Orders</button>';
				jQuery("#details_of_vendor").html(html);
			}


        </script>



        <?php //print_r($_SESSION); ?>
    </body>
</html>
<script>
<?php if ($firstrow != "") { ?>
        jQuery(document).ready(function () {
            jQuery("#<?php echo $firstrow; ?>").trigger("click");
        });
<?php } ?>

jQuery(document).ready(function(event){
	
	jQuery('#group').live('change',function(){	
		var group_id  = jQuery(this).val();
		
		var vendor_id_from_group  = jQuery(this).data('vendor_id_from_group');
		console.log(vendor_id_from_group);
		if(group_id=='Add_new_group'){		
			jQuery('#group_span').hide();
			jQuery('#new_group_span').show();
			jQuery('#group_ins_type').val('new');
			jQuery('#new_gruop').val('');
			getinventory(0,'N',vendor_id_from_group);
		}else{
			jQuery('#group_span').show();
			jQuery('#new_group_span').hide();
			jQuery('#group_ins_type').val('old');
			jQuery('#new_gruop').val('');
			getinventory(jQuery(this).val(),'N',vendor_id_from_group);
		}
	});
	
	jQuery('#cancel_btn').live('click',function(){
		jQuery('#drop_span').show();
		jQuery('#new_span').hide();
		jQuery('#inv_item_type').val('old');
		jQuery('#inv_item1').val('');
		jQuery('#inv_item').val('');
	});
	
	jQuery('#group_cancel_btn').live('click',function(){
		jQuery('#group_span').show();
		jQuery('#new_group_span').hide();
		jQuery('#group_ins_type').val('old');
		jQuery('#new_gruop').val('');
		jQuery('#group').val('');
	});
	
	jQuery('.vi_taxable').hide();
	
	jQuery('#taxable').on('change', function(){
		if(jQuery('#taxable option:selected').val().toString().toLowerCase() == 'yes') {
			jQuery('.vi_taxable').show();
		} else {
			jQuery('.vi_taxable').hide();
		}
	});
	
	jQuery('.vi_splitable').hide();
	
	jQuery('#splitable').on('change', function(){
		if(jQuery('#splitable option:selected').val().toString().toLowerCase() == 'yes') {
			jQuery('.vi_splitable').show();
		} else {
			jQuery('.vi_splitable').hide();
		}
	});
	
	
	jQuery('.add-on A').click(function(){
		if(jQuery(this).attr('rel')=='client'){
		jQuery('#keyword').val(jQuery('#vendors').val());
		}else{
		if(jQuery('#keyword').val().length<4){
		jAlert('Please enter More than 3 Characters','Alert Dialog');
		return false;
		}
		}
		GetVendor(1);
	});
	jQuery('#client_add').click(function(){
		jQuery.ajax({
            type: "POST",
            url: "backoffice_payments_add_vendor.php"}).done(function(msg){
		jQuery("#mymodal_html5").html(msg);
		jQuery('#filter_modal').modal('toggle');
		jQuery("#mymodal5").modal('show');
		
		});
	});
	
});

function getinventory(group, is_edit, vendor_id){	
    states = jQuery.Deferred();	
    jQuery.ajax({
        url:'get_inventory_items.php',
        data: { 
			'group': group,
			'vendor_id': vendor_id
			,'is_edit': is_edit
		},
        dataType: 'jsonp',
        jsonpCallback: 'returnItem'
    });
}
function returnItem(data){

    var group = jQuery('#inv_item');
    group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
	group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
    for(var i=0;i<data.length;i++){
        group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
    }
	
    states.resolve();
}
jQuery('#inv_item').live('change',function(){
	if(jQuery('#inv_item').val()!="new_inv_item" && jQuery('#inv_item').val()!=""){
		jQuery('#name').val(jQuery("#inv_item :selected").text());
		jQuery('#pack_unit_type').val(jQuery("#inv_item :selected").attr('rel'));
		//jQuery('#barcode').val(jQuery("#inv_item :selected").attr('barcode'));
	}else{
		jQuery('#name').val('');
		jQuery('#pack_unit_type').val('');
		//jQuery('#barcode').val('');
	}
	
});
jQuery('#inv_item1').live('change',function(){
	if(jQuery('#inv_item1').val()!=""){
		jQuery('#name').val(jQuery('#inv_item1').val());
	}else{
	jQuery('#name').val('');
	}

});

function getbarcode(val){
	var search_val = jQuery("#barcode").val();
	
	var vendor_id_from_barcode = jQuery("#barcode").data('vendor_id_from_barcode');
	console.log('vendor_id_from_barcode : '+vendor_id_from_barcode);
	
	if(search_val!=""){
	if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
         jConfirm('Invalid Barcode Number!', 'Alert!', function(r) {
          });
         jQuery('#popup_cancel').remove();
         return false;
      }
	  if( search_val.length<6){
	  	 jConfirm('Please enter a minimum of 6 digit number only!', 'Alert!', function(r) {
              
          });
         jQuery('#popup_cancel').remove();
         return false;	
	  }
	  var length ="";
	 length = search_val.length;
	
	if(length<=8){
	var lst_two = search_val.substr(length-2,2);
	var ser_val = search_val.substr(0,length-2);
	var ser_length = ser_val.length
	var lp = 7-ser_length;
	for(var j=0;j<lp;j++){
		ser_val = "0"+ser_val;
	}
	
	var lst_two = "0000"+lst_two;
	var search_val = ser_val+""+lst_two;
	}
	jQuery('#barcode').val(search_val); 		
	jQuery.ajax({
		url:'search_fectual_barcode.php',
		type:'POST',
		data:{search_val:search_val
			,'vendor_id': vendor_id_from_barcode
		},
		success:function(data){
			data = data.trim()
			if(data){
				console.log(data);
				if(data=="b_found"){
					jAlert('This barcode already in use!','Alert Dialog');	
					jQuery('#inv_item').val('').trigger('change');				
					jQuery('#ni_barcode').val('');
					jQuery('#inv_item1').val('').trigger('change');
					jQuery('#ture_barcode').hide();
					jQuery('#ni_notes').val("");				
					jQuery('#imagebox').html('');
					jQuery('#upc_search_image').val('');
					jQuery('#ture_barcode').hide();
					jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				}else{
				
					//jQuery('#inv_item').val('new_inv_item').trigger('change');
					//jQuery('#inv_item1').val(data[0]).trigger('change');				
				
					jQuery('#barcode').val(search_val);
					var data = data.split('^');				
					jQuery('#ni_notes').val(data[1]);
					if(data[2]!=""){
					jQuery('#imagebox').html('<img src="'+data[2]+'" width="100px;" style="padding-bottom:5px;">');
					jQuery('#upc_search_image').val(data[2]);
					}else{
					jQuery('#smallImagebox').html('');
					jQuery('#upc_search_image').val('');				
					}
					jQuery('#ture_barcode').show();	
					jQuery('#ni_barcode').val(search_val).attr('readonly',true);
				}
			}else{			
				
				jAlert('UPC Barcode not found in database!','Alert Dialog');
				jQuery('#ni_barcode').val(search_val).attr('readonly',false);
				jQuery('#inv_item').val('').trigger('change');
				// jQuery('#ni_barcode').val('');
				jQuery('#inv_item1').val('').trigger('change');
				jQuery('#ture_barcode').hide();
				jQuery('#ni_notes').val("");				
				jQuery('#imagebox').html('');
				jQuery('#upc_search_image').val('');
			}
		}
	});
	}else if(val==2 && search_val==""){
		jQuery('#ture_barcode').hide();
	}
}

function MakechangeBarcode(){
	jQuery('#barcode_valid').val('');
} 

function get_group(market,group,trigger){

	if(market=='Retail'){
		jQuery('.retails').show();
	}else{
		jQuery('.retails').hide();
	}
	//jQuery('#group').val('').trigger('change');
	jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup.php?field=yes",
		data: { market: market}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group").html(msg);
		  if(group>0){
		  	jQuery('#group').val(group);
		  }
		  if(trigger){
			jQuery('#group').trigger('change');									
			jQuery('#inv_item').trigger('change');
		  }	
			jQuery('#name').val('');
		});
}

function validate(){
	if(jQuery('#status').val()==""){
	jAlert('Please Select status!','Alert Dialog');
	return false;
	}else if(jQuery('#d_market').val()==""){
	jAlert('Please Select Market!','Alert Dialog');
	return false;
	}else if(jQuery('#group').val()==""){
	jAlert('Please Select Group!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item').val()=="" && jQuery('#inv_item_type').val()!='new'){
	jAlert('Please Select Inventory Item!','Alert Dialog');
	return false;
	}else if(jQuery('#inv_item1').val()=="" && jQuery('#inv_item_type').val()=='new'){
	jAlert('Please Enter Inventory Item!','Alert Dialog');
	return false;		
	}else if(jQuery('#name').val()==""){
	jAlert('Please Enter Name!','Alert Dialog');
	return false;
	}/* else if(jQuery('#pack_size').val()==""){
	jAlert('Please Enter Pack Size!','Alert Dialog');
	return false;
	} */else if(jQuery('#pack_unit_type').val()==""){
	jAlert('Please Select Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack').val()==""){
	jAlert('Please Enter Quantity In Pack!','Alert Dialog');
	return false;
	}else if(jQuery('#qty_in_pack_unit_type_buying').val()==""){
	jAlert('Please Select Quantity In Pack Unit Type!','Alert Dialog');
	return false;
	}else if(jQuery('#price').val()=="" || !isValidPrice(jQuery('#price').val())){
	jAlert('Please Enter Price!','Alert Dialog');
	return false;
	}else{
	jQuery('#edit_from input,#edit_from select').attr('disabled',false);
	return true;
	}
}

function fix(num,id){	
	if(num){	
		num= parseFloat(num).toFixed(2);
	}else{
		num= '0.00';
	}
	
	if(!isNaN(num)){
	jQuery('#'+id).val(num);
	}else{
	jQuery('#'+id).val('0.00');
	}
}

function isValidPrice(num){	
	if(num){	
		var float_num = parseFloat(num).toFixed(2);
		if(float_num > 0.00) {
			return true;
		}
	}
	return false;
}

function getvendor1(){
	console.log('TESTT');
	if(jQuery('#vendors').val().length<4){
		jAlert('Please enter More than 3 Characters!','Alert Dialog');
		return false;
	}else{
		jQuery('#filter_modal').modal('toggle');
		jQuery('#keyword').val(jQuery('#vendors').val());
		GetVendor(1);
	}
}

function GetVendor(val){
	var str;
	var vendor_id = '29';
	if(val=="1"){
		str = document.getElementById('vendors').value;
		}
	else{	
		str = jQuery('#keyword').val();
		}
	if(str.length>2){
	document.getElementById('keyword').value=str;
	 document.getElementById("modalcontent").innerHTML="";
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		 document.getElementById('vendors').value=str;
		 document.getElementById("modalcontent").innerHTML=xmlhttp.responseText;
		// document.getElementById('keyword').value="";
	   // document.getElementById("livesearch").style.border="1px solid #A5ACB2";
		}
	  }
	xmlhttp.open("GET","vendor_search.php?vendor_id="+ vendor_id +"&q="+str,true);
	xmlhttp.send();
	}else{
	jQuery('#modalcontent').html('');

	}
}
function loadVendor(id,email,phone,name,image)
{
	jQuery('#vendors').val(name);	
	jQuery('#vendor_id').val(id);	
	jQuery('#filter_modal').modal('toggle');
}
</script>


<!-- main.js -->
<script>
jQuery(document).ready(function(event){
	jQuery('#vendorcountry').change(function () {
		var country_id = jQuery(this).find(":selected").val();
		getvendorStates(country_id);
		jQuery("#vendorcountry").chosen();
	});
	
	jQuery("#country,#currency,#vendorcountry,#vendorstate, #document_country, #country_birth, #salutation, #sex, #viewable").chosen();
	
	jQuery("#languages, #vendorterm, #vendortype, #vendorPaymentType, #vendorDeliveryType").chosen({ allow_single_deselect: true });
	
});

function getStates(country_id, state) {
			
	if(country_id>0){	
		if (typeof (state) === 'undefined') state = 0;
		jQuery(".chzn-results").click(function(){
			
		});
		jQuery.ajax({
			url: API_URL + 'return_states.php',
			data: { country: country_id },
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				jQuery('#state').html('');
				jQuery.unblockUI();
				jQuery('<option>', { value: '', text: '' }).appendTo('#state');
				for(i=1; i<data.length;i++){
						
						jQuery('<option>', { value: data[i]['id'], text: data[i]['name'] }).appendTo('#state');
				}
				//jQuery.each(data, function (i, obj) {
					//jQuery('<option>', { value: obj.id, text: obj.name }).appendTo('#state');
				//});
				jQuery("#state").val(state);
				jQuery("#state").chosen();
				jQuery("#state").trigger("liszt:updated");
				
			}
		});
	}
}

jQuery('#add_vendor_form').submit(function () {
    console.log('Hello ');
    if (jQuery("#vendorstatus").val() == "") {
        jAlert("Please select Vendor Status!");
        return false;
    } else if (jQuery("#vendorname").val() == "") {
        jAlert("Please select Vendor Name!");
        return false;
    } else if (jQuery("#vendorcontact").val() == "") {
        jAlert("Please select Vendor Contact!");
        return false;
    } else if (jQuery("#vendoraddress").val() == "") {
        jAlert("Please select Vendor Address!");
        return false;
    } else if (jQuery("#vendorcountry").val() == "") {
        jAlert("Please select Vendor Country!");
        return false;
    } else if (jQuery("#vendorcity").val() == "") {
        jAlert("Please select Vendor City!");
        return false;
    } else if (jQuery("#vendorstate").val() == "") {
        jAlert("Please select Vendor State!");
        return false;
    } else if (jQuery("#vendorzip").val() == "") {
        jAlert("Please select Vendor Zip!");
        return false;
    } else if (jQuery("#vendorphone").val() == "") {
        jAlert("Please insert Vendor Phone!");
        return false;
    } else if (jQuery("#vendortype").val() == "" || jQuery("#vendortype").val() === null) {
        jAlert("Please Select Vendor Type!");
        return false;
    } else if (jQuery("#vendorterm").val() == "" || jQuery("#vendorterm").val() === null) {
        jAlert("Please Select Vendor Term!");
        return false;
    } else if (jQuery("#vendorPaymentType").val() == "" || jQuery("#vendorPaymentType").val() === null) {
        jAlert("Please Select Vendor Payment Type!");
        return false;
    } else if (jQuery("#vendorDeliveryType").val() == "" || jQuery("#vendorDeliveryType").val() === null) {
        jAlert("Please Select Vendor Delivery Type!");
        return false;
    }
    var form_data = getFormData(jQuery('#add_vendor_form'));
    form_data['vendortype'] = jQuery("#vendortype").val();
    form_data['vendorterm'] = jQuery("#vendorterm").val();
    form_data['vendorPaymentType'] = jQuery("#vendorPaymentType").val();
    form_data['vendorDeliveryType'] = jQuery("#vendorDeliveryType").val();
    console.log('form_data : ');
    console.log(form_data);
    jQuery.blockUI({message: null});
    jQuery.blockUI({
        css: {backgroundColor: 'none', border: 'none'},
        message: '<img alt="" src="images/loaders/loader7.gif">'
    });
    jQuery.ajax({
        url: 'add_vendor.php',
        data: form_data,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            window.location.reload();
        }
    });
    return false;
});

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    jQuery.map(unindexed_array, function(n, i){
        if (n['name'].includes('[]')) {
            n['name'].replace('[]', '');
        }
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

jQuery('#price_by_weight').live('change', function(){
	var v = jQuery(this).val();

	if(v=='Yes'){
		jQuery('.pbwut').show();
	}
	else if(v=='No'){
		jQuery('.pbwut').hide();
	}
	else{
		jQuery('.pbwut').hide();
	}
})
</script>
