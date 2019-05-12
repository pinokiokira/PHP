<?php
error_reporting(0);
require_once 'require/security.php';
include 'config/accessConfig.php';

function get_empmaster($emp_id){
    $query = mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees_master where empmaster_id =".$emp_id);
    if($query && mysql_num_rows($query) > 0){
        $query = mysql_fetch_array($query);
        return $query['name'];
    } else {
        return '';
    }
}

function status_img($status){

	if($status=="Active"){
		$res = '<img src="images/Active, Corrected, Delivered - 16.png" title="Active" >';
	}else{
		$res = '<img src="images/Inactive & Missing Punch - 16.png" title="Inactive" >';
	}
	return $res;
}

function jRender_inventory_market_combo($nameAndID,$vendor_id, $cClass = null, $cStyle = null) {
    if($_REQUEST['market']!=""){
    $market = $_REQUEST['market'];
    $group_where = "AND ig.Market='$market'";
    $q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi
        LEFT JOIN inventory_items inv_itm ON inv_itm.id=vi.inv_item_id INNER JOIN inventory_groups ig ON ig.id=inv_itm.inv_group_id
        WHERE vi.vendor_id = '".$vendor_id."' $group_where "));
    }else{
        $q_count = mysql_fetch_array(mysql_query("select count(vi.id) as counts from vendor_items as vi where vi.vendor_id = '".$vendor_id."' "));
    }

    $total_count = $q_count['counts'];

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="") $class  = $cClass;
    if ($cStyle!="") $style  = $cStyle;
    $data = '<select name="market" onChange="javascript:jQuery(\'#total_rows\').val(jQuery(this).find(\'option:selected\').attr(\'rel\'))" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">';

    //INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
    $sql = "SELECT distinct(ig.Market) as market,count(ii.id) as invs
        FROM inventory_groups ig
        INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
        INNER JOIN vendor_items lii ON lii.inv_item_id=ii.id
        WHERE lii.vendor_id ='".$vendor_id."' AND market !='NULL' group by ig.Market ORDER BY market ASC";

    $output = mysql_query($sql);// or die(mysql_error());
    $rows = mysql_num_rows($output);

    if ($rows > 0 && $rows != '') {
        $data .= '<option rel="'.$total_count.'" value=""> - - -  Select Market - - - </option>';
        while ($result = mysql_fetch_assoc($output)) {
            //print_r($result);exit;

            $market = $result['market'];
            //echo $market;
            if ($result['market'] == $_REQUEST['market']) {
                $sel1 = ' selected="selected"';
            } else {
                $sel1 = '';
            }
            $data .= '<option rel="'.$result['invs'].'" value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
        }
    } else {
        $data .= '<option value=""> - - -  No Market Found  - - - </option>';
    }
    $data .= '</select>';
    return $data;
}

$empmaster_id=$_SESSION['client_id'];
$curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));
$c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

$ven_info = "select * from vendors where id='". $vendor_id ."'";
$ven_info = mysql_query($ven_info);
$ven_info = mysql_fetch_assoc($ven_info);

if(isset($_POST) && $_POST['Submit']=="Submit"){

    $status = mysql_real_escape_string($_REQUEST['status']);
    $vendor_vehicles_id = mysql_real_escape_string($_REQUEST['vendor_vehicles_id']);
    $vehicle_code = mysql_real_escape_string($_REQUEST['vehicle_code']);
    $vehicle_name = mysql_real_escape_string($_REQUEST['vehicle_name']);
    $vehicle_description = mysql_real_escape_string($_REQUEST['vehicle_description']);
    $vehicle_size = mysql_real_escape_string($_REQUEST['vehicle_size']);
    $vehicle_weight = mysql_real_escape_string($_REQUEST['vehicle_weight']);
    $vehicle_range = mysql_real_escape_string($_REQUEST['vehicle_range']);

	if(!empty($vendor_vehicles_id)){
		$last_by = get_empmaster($_SESSION['client_id']);
		$last_on = 'VendorPanel';
		$last_datetime = date('Y-m-d H:i:s');

		$sql = "UPDATE vendor_vehicles SET status = '".$status."', vehicle_code = '".$vehicle_code."', vehicle_name = '".$vehicle_name."', vehicle_description = '". $vehicle_description ."', vehicle_size = '". $vehicle_size ."', vehicle_weight = '". $vehicle_weight ."', vehicle_range = '". $vehicle_range ."', last_by = '". $last_by."', last_on = '". $last_on ."', last_datetime = '".$last_datetime."' WHERE vendor_vehicles_id = '".$vendor_vehicles_id."'";
		$up_inv = mysql_query($sql); //or die(mysql_error());

	}else{
		$ins_query = "INSERT INTO vendor_vehicles SET
            status = '".$status."',
					  vehicle_code = '".$vehicle_code."',
					  vendor_id = '". $vendor_id ."',
					  vehicle_name = '".$vehicle_name."',
					  vehicle_description = '".$vehicle_description."',
					  vehicle_size = '".$vehicle_size."',
            vehicle_weight = '".$vehicle_weight."',
					  vehicle_range = '".$vehicle_range."',
					  created_by = '".$_SESSION['client_id']."',
					  created_on = 'VendorPanel',
					  created_datetime = '".date('Y-m-d H:i:s')."'";
					   //echo $ins_query;exit; die;
						//print_r("insert item :    ". $ins_query);

		$res_ins = mysql_query($ins_query);// or die(mysql_error());
		if($res_ins){
			$vendor_vehicles_id = mysql_insert_id();//die;
		}

	}

    $res1 = mysql_query($query1);// or die(mysql_error());
    // header('location:setup_vehicles.php?by=SoftPoint&vendor_vehicles_id='.$vendor_vehicles_id.'&msg=Item Added/Updated Successfully!');
    header('location:setup_vehicles.php?by=SoftPoint&vendor_vehicles_id='.$vendor_vehicles_id);
}
 $limit = 500;
if(isset($_REQUEST['search_txt1'])){
    $limit = 500;
	if($_REQUEST['search_txt1']!=""){
		$search = $_REQUEST['search_txt1'];
		$search_where = " AND (vb.vehicle_description LIKE '%".$search."%' OR vb.vehicle_name Like '%".$search."%' OR vb.vehicle_code Like '%".$search."%')";
	}
}

	$sql = "SELECT * FROM vendor_vehicles as vb
        WHERE vb.vendor_id='".$vendor_id."' $search_where LIMIT $limit";

        if($_REQUEST['debug'] == '1'){
            echo '$sql : '. $sql;
        }
$resultJobs = mysql_query($sql) or die(mysql_error());
if(isset($_GET['vendor_vehicles_id'])){
    $selected=$_GET['vendor_vehicles_id'];
}else{
    $selected=0;
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
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/dd.css" type="text/css">
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>

<script type="text/javascript" src="js/jquery.validate.min.js"></script>

<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<!--<script type="text/javascript" src="js/jquery.blockUI.js"></script>-->
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.dd.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<!--<script type="text/javascript" src="js/main.js"></script>-->

<style>
body {
    top:0px!important;
}
.widgetcontent {
    background: #fff;
    padding: 15px 12px;
    border: 3px solid #0866c6;
    border-top: 0;
    margin-bottom: 20px;
}
.goog-te-banner-frame {
    margin-top: -50px!important;
}
.error {
    color: #FF0000;
    padding-left:10px;
}
/*.row-fluid .span4 {
    width: 32.6239%;
    margin-left:10px;
}*/
.span4 {
    float:left;
    width:28.5%!important;
    min-height:600px;
    margin-left:1.5%!important;
}
/*.unread showJobs selected{background-color:#cccccc;}*/
table.table tbody tr.selected, table.table tfoot tr.selected { background-color: #808080; }
.selectouter12 {
    background: none repeat scroll 0 0 #ffffff; border: 1px solid #c9c9c9; float: left;
        height: 32px; line-height: 5px; margin: 0 0 7px; position: relative; width: 271px;
}

.ui-datepicker-calendar td span{
    color: #666; display: block; padding: 2px 8px;
    text-shadow: 1px 1px rgba(255, 255, 255, 0.3);
}
.ui-datepicker-month{ width:33% !important; margin-right:5%;}
.ui-datepicker-year{ width:33% !important;}

form#edit_from select,
form#edit_from input[type="text"],
div.input-append { margin: 0px; }
.dataTables_filter input { height: 20px; }
.input-append .add-on, .input-prepend .add-on { height: 20px; }
form#edit_from input[type="text"] { width: 256px; }

</style>

<script type="text/javascript">

var vendor_id = '<?php echo $vendor_id; ?>';

var intRoomHeight = jQuery('.widgetcontent').height();
function validate(){

}

jQuery(document).ready(function($){

    jQuery('#pack_unit_type').msDropDown();
    jQuery('#qty_in_pack_unit_type').msDropDown();
    jQuery('#price_by_weight_unittype').msDropDown();

    var msg = '<?php echo $_REQUEST['msg']; ?>';
    if(msg!=""){

        jAlert(msg,'Alert Dialog');
    }
    jQuery('#global_tbl').dataTable({
        "sPaginationType": "full_numbers",
        "aaSorting": [[ 1, "asc" ]],
        "bJQuery": true,
        "fnDrawCallback": function(oSettings) {
           //  jQuery.uniform.update();
        }
    });

    //jQuery("#global_tbl>tbody>tr:first").trigger('click');
    /*setTimeout(function(){
        jQuery("#global_tbl>tbody>tr:first").trigger('click');
    },1000);*/
    //jQuery("#global_tbl>tbody>tr:first").trigger('click');
    jQuery('#addcode').click(function(){

        //Simple

        jQuery('#vendor_vehicles_id').val('');
        jQuery('#vehicle_code').val('');
        jQuery('#vehicle_name').val('');
        jQuery('#vehicle_description').val('');
        jQuery('#vehicle_size').val('');
        jQuery('#vehicle_weight').val('');
        jQuery('#vehicle_range').val('');
        jQuery('#created_by').val('');
        jQuery('#created_on').val('');
        jQuery('#created_datetime').val('');

        //Extended
        jQuery('#edit_from input,#edit_from select').val('');
        jQuery('#for_edit').hide();
        jQuery('.reset').show();
        jQuery('#status').val('Active');

        jQuery('#created_on').val('VendorPanel');
        jQuery('#created_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
        jQuery('#created_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');

        jQuery('#last_on').val('VendorPanel');
        jQuery('#Last_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
        jQuery('#last_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
        jQuery('#barcode_p').show();

        //jQuery('#name').attr('readonly', false);
    });

    jQuery(".cl_order").live('click',function(){

        //console.log('market : '+jQuery(this).data('market'));

            jQuery('#vendor_vehicles_id').val(jQuery(this).data('vendor_vehiclesid'));
            jQuery('#vehicle_code').val(jQuery(this).data('vehicle_code'));
            jQuery('#vehicle_name').val(jQuery(this).data('vehicle_name'));
            jQuery('#vehicle_description').val(jQuery(this).data('vehicle_description'));
            jQuery('#vehicle_size').val(jQuery(this).data('vehicle_size'));
            jQuery('#vehicle_weight').val(jQuery(this).data('vehicle_weight'));
            jQuery('#vehicle_range').val(jQuery(this).data('vehicle_range'));
            jQuery('#last_on').val(jQuery(this).data('last_on'));
            jQuery('#last_datetime').val(jQuery(this).data('last_datetime'));
            jQuery('#last_by').val(jQuery(this).data('last_by'));
            jQuery('#created_on').val(jQuery(this).data('created_on'));
            jQuery('#created_datetime').val(jQuery(this).data('created_datetime'));
            jQuery('#created_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
            jQuery('.reset').hide();
		      	jQuery('#for_edit').show();
            jQuery('#status_hn').val(jQuery(this).data('status'));
            jQuery('#status').val(jQuery(this).data('status'));
            jQuery(".gradeX").attr("class","gradeX cl_order");
            jQuery(this).attr("class","gradeX cl_order selected");
        //}
        });
        if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
            // jQuery(".uneditable-input").css("height","32px");
            // jQuery("input[type='text']").css("height","32px");
            // jQuery("input[type='select']").css("width","274px");
            // jQuery(".go_search2").css("height","42px");

            //jQuery(".select-xlarge").css("height","30px");

            /*select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {*/
        }
        jQuery('#purchased_last').datepicker({
             dateFormat: 'yy-mm-dd',
             inline:true,
                changeMonth:true,
                changeYear:true
        });
        /*gsdgsd*/
});
jQuery('#group').live('change',function(){
    var group_id  = jQuery(this).val();
    console.log('group_id : '+group_id);
    if(group_id=='Add_new_group'){
        jQuery('#group_span').hide();
        jQuery('#new_group_span').show();
        jQuery('#group_ins_type').val('new');
        jQuery('#new_gruop').val('');
        getinventory(0);
    }else{
        jQuery('#group_span').show();
        jQuery('#new_group_span').hide();
        jQuery('#group_ins_type').val('old');
        jQuery('#new_gruop').val('');
        getinventory(jQuery(this).val(), 'y');
    }
});

jQuery('#inv_item').live('change',function(){
    var inv_itm_val = jQuery(this).val();
    if(inv_itm_val=="new_inv_item"){
        jQuery('#drop_span').hide();
        jQuery('#new_span').show();
        jQuery('#inv_item_type').val('new');
        jQuery('#inv_item1').val('');
        var status = jQuery('#status').val();
        var group = jQuery('#group').val();
        jQuery('#new_inv_div,#new_inv_div1').show();

        jQuery('#pack_unit_type').msDropdown().data("dd").destroy();
        jQuery('#pack_unit_type').msDropdown();
        jQuery('#qty_in_pack_unit_type').msDropdown().data("dd").destroy();
        jQuery('#qty_in_pack_unit_type').msDropdown();
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

function getinventory(group, is_edit){
    states = jQuery.Deferred();
    jQuery.ajax({
        url:'get_inventory_items.php',
        data: {
            'group': group,
            'vendor_id': vendor_id,
            'is_edit': is_edit
        },
        dataType: 'json'
        //jsonpCallback: 'returnItem'
    }).done(function(data) {
        console.log('DataData : '+data);
        var group = jQuery('#inv_item');
        group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
        group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
        for(var i=0;i<data.length;i++){
            console.log('data : '+data[i].id);
            group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
        }
        states.resolve();
    });
}

function returnItem(data){
    var group = jQuery('#inv_item');
    group.empty().append("<option value=''>- - - Select Inventory Items - - -</option>");
    group.append("<option value='new_inv_item'>- - - Add New Inventory Item - - -</option>");
    for(var i=0;i<data.length;i++){
        console.log('data : '+data[i].id);
        group.append("<option rel='"+data[i].unit_type+"' barcode='"+data[i].barcode+"' value='" + data[i].id + "'>" + data[i].description + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>");
    }
    states.resolve();
}

jQuery('#inv_item').live('change',function(){
    if(jQuery('#inv_item').val()!="new_inv_item" && jQuery('#inv_item').val()!=""){
        //jQuery('#name').val(jQuery("#inv_item :selected").text());
        jQuery('#pack_unit_type').val(jQuery("#inv_item :selected").attr('rel'));
        jQuery('#barcode').val(jQuery("#inv_item :selected").attr('barcode'));
    }else{
        //jQuery('#name').val('');
        jQuery('#pack_unit_type').val('');
        jQuery('#barcode').val('');
    }
});

jQuery('#ser_go').live('click',function(){
window.location="setup_vehicles.php?&search_txt1="+jQuery('#search_txt1').val();
  // var total_rec = jQuery('#total_rows').val();
  //   if(total_rec > 499){
  //       var search_inpt = jQuery('#search_txt1').val();
  //       if (search_inpt!=null) {
  //           search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
  //           if (search_inpt.length > 0) {
  //               if(search_inpt.length < 3) {
  //                   jAlert('Please enter 3 or more characters');
  //                   return false;
  //               }else{
  //                   var val = jQuery('#group_id').val();
  //                   var dummy_market = jQuery("#dummy_market").val();
  //                   if(val=="") val = 'All';
  //                   //window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
  //                   window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
  //               }
  //           }else{
  //               jAlert(' Enter value to search');
  //               return false;
  //           }
  //           return false;
  //       } else{
  //           jAlert(' Enter value to search');
  //           return false;
  //       }
  //   }else{
  //       var val = jQuery('#group_id').val();
  //       var dummy_market = jQuery("#dummy_market").val();
  //       if(val=="") val = 'All';
  //       //window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
  //       window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
  //   }
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
          //if(trigger){
            jQuery('#group').trigger('change');
            jQuery('#inv_item').trigger('change');
          //}
            //jQuery('#name').val('');
        });
}
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
</script>
</head>
<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Setup <span class="separator"></span></li>
      <li>Vehicles</li>
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
      <div style="float: right;margin-top: 10px;" class="messagehead">
        <p style="float:left;">
           <span>
            <input type="text" placeholder="Search" value="<?php echo $_REQUEST['search_txt1']; ?>" name="search_txt1" id="search_txt1" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;">
            </span>
          <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
            </p>
        <button id="ser_go" style="float:left" class="btn btn-primary btn-large">Go</button>
        <!--<form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
        <button id="addcode" style="margin-left:10px;" class="btn btn-success btn-large">Add</button>
      </div>
      <div class="pageicon"><span class="iconfa-cog"></span></div>
      <div class="pagetitle">
        <h5>List of the Vehicles and staging areas </h5>
        <h1>Setup - Vehicles</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <div class="span8" style="width:70%;float:left; overflow:auto;">
            <div class="clearfix">
              <h4 class="widgettitle">Vehicles</h4>
            </div>
            <div class="widgetcontent">
            <table class="table table-bordered responsive" id="global_tbl">
              <colgroup>
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              <col class="con0" />
              <col class="con1" />
              </colgroup>
              <thead>
                <tr>
                  <th class="head1">S</th>
                  <th class="head0">Vehicle Code</th>
                  <th class="head0">Vehicle Name</th>
                  <th class="head1">Vehicle Description</th>
                  <th class="head1">Vehicle Size</th>
                  <th class="head1">Vehicle Weight</th>
                  <th class="head1">Vehicle Range</th>
                  <th class="head0">Created Datetime</th>
                  <th class="head1" style="text-align:center;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  while($row = mysql_fetch_array($resultJobs)){
						//echo "<pre>";print_r($row);
					if($selected==0){
						$selected=$row["vendor_vehicles_id"];
					}
                   ?>
                <tr class="<?php if($selected==$row["vendor_vehicles_id"]){ echo "selected-row";} ?> gradeX cl_order" id="<?php echo $row["vendor_vehicles_id"];?>"
                        data-status = "<?php echo $row['status']; ?>"
                        data-vendor_vehiclesid ="<?php echo $row["vendor_vehicles_id"];?>"
                        data-vehicle_code ='<?php echo $row['vehicle_code']; ?>'
                        data-vendor_id = "<?php echo $row['vendor_id']; ?>"
                        data-vehicle_name = "<?php echo $row['vehicle_name']; ?>"
                        data-vehicle_description = "<?php echo $row['vehicle_description']; ?>"
                        data-vehicle_size="<?php echo $row['vehicle_size']; ?>"
                        data-vehicle_weight = "<?php echo $row['vehicle_weight']; ?>"
                        data-vehicle_range="<?php echo $row['vehicle_range']; ?>"
                        data-created_on="<?php echo $row['created_on']; ?>"
                        data-created_by="<?php echo $row['created_by']; ?>"
                        data-created_datetime="<?php echo $row['created_datetime']; ?>"
						            data-last_on="<?php echo $row['last_on']; ?>"
                        data-last_by="<?php echo $row['last_by']; ?>"
                        data-last_datetime="<?php echo $row['last_datetime']; ?>"
                        >
                  <td><?php echo status_img($row['status']); ?><span style="display:none;"><?php echo $row['status']; ?></span></td>
                  <td><?php echo $row['vehicle_code']; ?></td>
                  <td><?php echo $row['vehicle_name']; ?></td>
                  <td><?php echo $row['vehicle_description']; ?></td>
                  <td><?php echo $row['vehicle_size']; ?></td>
                  <td><?php echo $row['vehicle_weight']; ?></td>
                  <td><?php echo $row['vehicle_range']; ?></td>
                  <td><?php echo $c_symbol.$row['created_datetime']; ?></td>
                  <td class="center" style="vertical-align:middle;"><a href="#"><img src="images/edit.png"></a> </td>
                </tr>
                <?php
                $i++;     }
                      ?>
              </tbody>
            </table></div>
          </div>
          <!--end span8-->
          <div class="span4">

            <div class="widgetcontent" style="padding: 0px !important;">
                <div class="clearfix">
              <h4 class="widgettitle">Add/Edit Vehicles</h4>
            </div>
			<div class="widgetbox" id="fdetail" style="padding-left: 5%;">
				<form id="edit_from234" name="frm234" action="" method="post" class="edit_from" >
					<input type="hidden" name="vendor_vehicles_id" value="" id="vendor_vehicles_id">
          <p>
              <label>Status:<span style="color:#FF0000;">*</span></label>
              <span class="field">
              <select name="status" id="status" style="width:270px;">
                  <option value="" > - - - Select Status - - -</option>
                      <option selected="selected" value="Active">Active</option>
                      <option value="Inactive" >Inactive</option>
              </select>
              </span> </p>
          <p>
					<p>
						<label>Vehicle Code:<span style="color:#FF0000;">*</span></label>
						<span class="field">
							<!-- <input type="text" class="input-xlarge" id="vehicle_code" value="" name="vehicle_code"> -->
              <input style="width: 228px;" onBlur="getbarcode_vcode(1)" type="text" onKeyDown=" javascript:if(event.keyCode==13){getbarcode_vcode(2);  return false;}" name="vehicle_code" id="vehicle_code"  class="input-short" value="" />
						</span>
					</p>
					<p>
						<label>Vehicle Name:<span style="color:#FF0000;">*</span></label>
						<span class="field">
							<input type="text" class="input-xlarge" id="vehicle_name" value="" name="vehicle_name">
						</span>
					</p>
					<p>
						<label>Vehicle Description:<span style="color:#FF0000;">*</span></label>
						<span class="field"></span>
						<span class="field" id="group_span">
							<textarea class="input-xlarge" id="vehicle_description" value="" name="vehicle_description" rows="3" onBlur="getbarcode_description(1)" type="text" onKeyDown=" javascript:if(event.keyCode==13){getbarcode_description(2);  return false;}"></textarea>
						</span>
					</p>
					<p>
						<label>Vehicle Size:<span style="color:#FF0000;">*</span></label>
						<span class="field" id="drop_span">
							<input type="text" class="input-xlarge" id="vehicle_size" value="" name="vehicle_size">
						</span>
					</p>
          <p>
						<label>Vehicle Weight:<span style="color:#FF0000;">*</span></label>
						<span class="field"></span>
						<span class="field" id="drop_span">
              <!-- <input type="text" class="input-xlarge" id="vehicle_weight" value="" name="vehicle_weight"> -->
               <input style="width: 228px;" onBlur="getbarcode(1)" type="text" onKeyDown=" javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="vehicle_weight" id="vehicle_weight"  class="input-short" value="" />
						</span>
					</p>
					<p>
						<label>Vehicle Range:<span style="color:#FF0000;">*</span></label>
						<span class="field" id="drop_span">
							<!-- <input type="text" class="input-xlarge" id="vehicle_range" value="" name="vehicle_range"> -->
              <input style="width: 228px;" onBlur="getbarcode_two(1)" type="text" onKeyDown=" javascript:if(event.keyCode==13){getbarcode_two(2);  return false;}" name="vehicle_range" id="vehicle_range"  class="input-short" value="" />
						</span>
					</p>
					<p>
						<label>Created On:</label>
						<span class="field">
						<input type="text" class="input-xlarge" readonly id="created_on" value="VendorPanel " name="created_on">
						</span> </p>
					<p>
						<label>Created By:</label>
						<span class="field">
						<input type="text" class="input-xlarge" id="created_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="created_by">
						</span>
					</p>
					<p>
						<label>Created Date & Time:</label>
						<span class="field">
						<input type="text" class="input-xlarge" id="created_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="created_datetime">
						</span>
					</p>
					<div id="for_edit" style="display:none;">
						<p>
						  <label>Last On:</label>
						  <span class="field">
						  <input type="text" class="input-xlarge" id="last_on" value="VendorPanel" readonly name="last_on">
						  </span> </p>
						<p>
						  <label>Last By:</label>
						  <span class="field">
						  <input type="text" class="input-xlarge" id="last_by" readonly value="<?php echo get_empmaster($_SESSION['client_id']); ?>" name="last_by">
						  </span> </p>
						<p>
						  <label>Last Date & Time:</label>
						  <span class="field">
						  <input type="text" class="input-xlarge" id="Last_datetime" readonly value="<?php echo date('Y-m-d H:i:s'); ?>" name="last_datetime">
						  </span>
						</p>

					</div>
					<button class="btn btn-primary" id="sub_form" name="Submit" value="Submit"> Submit</button>
					<button class="btn btn-primary reset" type="reset">Reset</button>
				</p>
				</form>
			</div>
		</div>
    </div>
          <!--end span4-->
        </div>
        <!--row-fluid-->
        <?php include_once 'require/footer.php';?>
        <!--footer-->
        <script>
jQuery(document).ready(function(){
    jQuery('#taxable').on('change', function(){
        if(jQuery('#taxable option:selected').val().toString().toLowerCase() == 'yes') {
            jQuery('.vi_taxable').show();
        } else {
            jQuery('.vi_taxable').hide();
        }
    });

    jQuery('#splitable').on('change', function(){
        if(jQuery('#splitable option:selected').val().toString().toLowerCase() == 'yes') {
            jQuery('.vi_splitable').show();
        } else {
            jQuery('.vi_splitable').hide();
        }
    });

    jQuery('#price_by_weight').val('No');

    jQuery('#taxable').trigger('change');
    jQuery('#splitable').trigger('change');
});
function getbarcode(val){
  var search_val = jQuery("#vehicle_weight").val();

    if(search_val!=""){
    if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
        jConfirm('Invalid Vehicle Weight Number!', 'Alert!', function(r) {
        //jQuery('#barcode').focus();
        });
        jQuery('#popup_cancel').remove();
        return false;
  }
    if( search_val.length<3){
        jConfirm('Please enter a minimum of 3 digit number only!', 'Alert!', function(r) {
            //jQuery('#barcode').focus();
            });
            jQuery('#popup_cancel').remove();
            return false;
    }
    var length ="";
    length = search_val.length;
    /*var lp = 12-length;
    for(var j=0;j<lp;j++){
        search_val = "0"+search_val;
    }*/
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
        data:{
            search_val:search_val,
            'vendor_id': '<?php echo $vendor_id; ?>'
        },
        success:function(data){
            //jQuery('#modalcontent').html(data);
            if(data){
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

                    jQuery('#inv_item').val('new_inv_item').trigger('change');
                    jQuery('#inv_item1').val(data[0]).trigger('change');

                    // alert(search_val);
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
        //jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
        jQuery('#ture_barcode').hide();
    }
}
function getbarcode_two(val){
  var search_val = jQuery("#vehicle_range").val();

    if(search_val!=""){
    if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
        jConfirm('Invalid Vehicle Range Number!', 'Alert!', function(r) {
        //jQuery('#barcode').focus();
        });
        jQuery('#popup_cancel').remove();
        return false;
  }
    if( search_val.length<3){
        jConfirm('Please enter a minimum of 3 digit number only!', 'Alert!', function(r) {
            //jQuery('#barcode').focus();
            });
            jQuery('#popup_cancel').remove();
            return false;
    }
    var length ="";
    length = search_val.length;
    /*var lp = 12-length;
    for(var j=0;j<lp;j++){
        search_val = "0"+search_val;
    }*/
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
        data:{
            search_val:search_val,
            'vendor_id': '<?php echo $vendor_id; ?>'
        },
        success:function(data){
            //jQuery('#modalcontent').html(data);
            if(data){
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

                    jQuery('#inv_item').val('new_inv_item').trigger('change');
                    jQuery('#inv_item1').val(data[0]).trigger('change');

                    // alert(search_val);
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
        //jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
        jQuery('#ture_barcode').hide();
    }
}
function getbarcode_vcode(val){
  var search_val = jQuery("#vehicle_code").val();

    if(search_val!=""){
    if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
        jConfirm('Invalid Vehicle Code! Please enter only number', 'Alert!', function(r) {
        //jQuery('#barcode').focus();
        });
        jQuery('#popup_cancel').remove();
        return false;
  }
    if( search_val.length<3){
        jConfirm('Please enter a minimum of 3 digit number only!', 'Alert!', function(r) {
            //jQuery('#barcode').focus();
            });
            jQuery('#popup_cancel').remove();
            return false;
    }
    var length ="";
    length = search_val.length;
    /*var lp = 12-length;
    for(var j=0;j<lp;j++){
        search_val = "0"+search_val;
    }*/
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
        data:{
            search_val:search_val,
            'vendor_id': '<?php echo $vendor_id; ?>'
        },
        success:function(data){
            //jQuery('#modalcontent').html(data);
            if(data){
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

                    jQuery('#inv_item').val('new_inv_item').trigger('change');
                    jQuery('#inv_item1').val(data[0]).trigger('change');

                    // alert(search_val);
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
        //jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
        jQuery('#ture_barcode').hide();
    }
}
function getbarcode_description(val){
  var search_val = jQuery("#vehicle_description").val();

    if(search_val!=""){
        if( search_val.length>60){
            jConfirm('Please enter a max of 60 characters in Vehicle Description!', 'Alert!', function(r) {
                //jQuery('#barcode').focus();
                });
                jQuery('#popup_cancel').remove();
                return false;
        }
    }
}
function MakechangeBarcode(){
    jQuery('#barcode_valid').val('');
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
jQuery(document).ready(function(){
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
function getvendor1(){
    if(jQuery('#vendors').val().length<4){
        jAlert('Please enter More than 3 Characters!','Alert Dialog');
        return false;
    }else{
    //jQuery('.icon-search').trigger('click');
    jQuery('#filter_modal').modal('toggle');
    jQuery('#keyword').val(jQuery('#vendors').val());
    GetVendor(1);

    }
}
function GetVendor(val){
    var str;
    if(val=="1"){
        str = document.getElementById('vendors').value;
    }else{
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

<script type="text/javascript">
jQuery("#sub_form").click(function(e){
  if(jQuery('#status').val()==""){
		jAlert('Please Select status!','Alert Dialog');
		e.preventDefault();
	}else if(jQuery('#vehicle_code').val()==""){
        jAlert('Please Enter Vehicle Code!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#vehicle_name').val()==""){
        jAlert('Please Enter Vehicle Name!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#vehicle_description').val()==""){
        jAlert('Please Enter Vehicle Description!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#vehicle_size').val()==""){
        jAlert('Please Enter Vehicle Size!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#vehicle_weight').val()==""){
        jAlert('Please Enter Vehicle Weight!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#vehicle_range').val()==""){
        jAlert('Please Enter Vehicle Range!','Alert Dialog');
        e.preventDefault();
    }else{
        jQuery('#edit_from input,#edit_from select').attr('disabled',false);
        return true;
    }
});
</script>

<script type="text/javascript">
jQuery('#pack_unit_type').change(function() {
    //alert(jQuery(this).val());
    jQuery("#hidden_unit_type").val(jQuery(this).val());
});

jQuery('#name').blur(function() {
    var desc = jQuery('#name').val();
    if( desc.length>=3){
        console.log('enter');
        /*jQuery.ajax({
            url:'setup_items.php',
            data:{m:'check_item_exists', description: desc },
            type:'POST',
            dataType: 'json',
            success:function(data){
                //console.log(data);
                var str = '';
                if(data.flag == '1'){
                    jQuery.each(data.data,function(i,v){
                        str +=  v;
                    });
                    jConfirm(str, 'Duplicate Item, Do you want to Continue?', function(r) {
                        if(r){ }else{ jQuery("#name").val("");}
                    });
                    //jAlert(str, 'Duplicate');
                }
            }
        });*/
    }
});

jQuery('#vendor_internal_number').blur(function() {
    var item_code = jQuery('#vendor_internal_number').val();
    if( item_code.length>=3){
        jQuery.ajax({
            url:'setup_items.php',
            data:{m:'check_item_code_exists', item_code: item_code },
            type:'POST',
            dataType: 'json',
            success:function(data){
                //console.log(data);
                var str = '';
                if(data.flag == '1'){
                    jQuery.each(data.data,function(i,v){
                        str +=  v;
                    });
                    jConfirm(str, 'Duplicate Item, Do you want to Continue?', function(r) {
                        if(r){ }else{ jQuery("#vendor_internal_number").val("");}
                    });
                    //jAlert(str, 'Duplicate');
                }
            }
        });
    }
});
</script>
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
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->
</body>
<div id="imageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add/Edit Media</h3>
    </div>
    <div class="modal-body " id="mymodalhtml"></div>
    <div class="modal-footer" style="text-align:center;">
        <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
        <button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
    </div>
</div>
</html>
