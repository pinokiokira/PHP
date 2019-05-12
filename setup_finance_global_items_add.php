<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");
$setupHead      = "active";
$setupDropDown  = "display: block;";

$financeHead    = "active";
$financeDropDown = "display: block;";
$set_back_invventoryDropDown  = "display: block;";

$financeMenu3 = "active";

function GetLocationTimeFromServer_general($intLocationID, $servertime){
	/*$jsonurl = API."API2/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);*/

	$jsonurl = API."Panels/BusinessPanel/api/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);
  $json = file_get_contents($jsonurl);
  $dateTimeResult = json_decode($json);
  $dateTime = $dateTimeResult->servertolocation_datetime;
  return $dateTime;
}

function clean($var) {
  $specials = array(' ', '!', '@', '#', '$', '%', '^', '&', '(', ')', '_', '+', '`', '~', ',', ';', "'", ']', '[', '}', '{');
  $cleaned = strtolower($var);
  $cleaned = str_replace($specials, '-', $cleaned);
  $cleaned = str_replace('--------------------', '-', $cleaned);
  $cleaned = str_replace('-------------------', '-', $cleaned);
  $cleaned = str_replace('------------------', '-', $cleaned);
  $cleaned = str_replace('-----------------', '-', $cleaned);
  $cleaned = str_replace('----------------', '-', $cleaned);
  $cleaned = str_replace('---------------', '-', $cleaned);
  $cleaned = str_replace('--------------', '-', $cleaned);
  $cleaned = str_replace('-------------', '-', $cleaned);
  $cleaned = str_replace('------------', '-', $cleaned);
  $cleaned = str_replace('-----------', '-', $cleaned);
  $cleaned = str_replace('----------', '-', $cleaned);
  $cleaned = str_replace('---------', '-', $cleaned);
  $cleaned = str_replace('--------', '-', $cleaned);
  $cleaned = str_replace('-------', '-', $cleaned);
  $cleaned = str_replace('------', '-', $cleaned);
  $cleaned = str_replace('-----', '-', $cleaned);
  $cleaned = str_replace('----', '-', $cleaned);
  $cleaned = str_replace('---', '-', $cleaned);
  $cleaned = str_replace('--', '-', $cleaned);
  $cleaned = str_replace('-', '-', $cleaned);
  return $cleaned;
}
	
if (isset($_POST['submit_form']) && $_POST['submit_form']!="") {	
  $id                   = mysql_real_escape_string($_POST['id']);    
  $group                = mysql_real_escape_string($_POST['group']);
  $item_id              = mysql_real_escape_string($_POST['item']);
	$local_item_id        = mysql_real_escape_string($_POST['item_id']);
	$desc                 = mysql_real_escape_string($_POST['description']);
  $priority             = mysql_real_escape_string($_POST['priority']);
  $unit                 = mysql_real_escape_string($_POST['local_unit_type']);
  $global_taxable       = mysql_real_escape_string($_POST['global_taxable']);
	$global_ecommerce     = mysql_real_escape_string($_POST['global_ecommerce']);
  $notes                = mysql_real_escape_string($_POST['local_item_notes']);
	$manufacturer_barcode = mysql_real_escape_string($_POST['local_barcode']);	
	$default_brand        = mysql_real_escape_string($_POST['default_brand']);
	$default_vendor       = mysql_real_escape_string($_POST['default_vendor']);
	$default_price        = mysql_real_escape_string($_POST['default_price']);
	$default_pack         = mysql_real_escape_string($_POST['default_pack']);
  $status               = mysql_real_escape_string($_POST['status']);
	$local_item_desc      = mysql_real_escape_string($_POST['local_item_desc']);	
	$local_group_id       = mysql_real_escape_string($_POST['local_group_id']);
	$default_manufacturer = mysql_real_escape_string($_POST['default_manufacturer']);
  $image = "" ;
  
  $queryE = mysql_query("SELECT inv_item_id FROM vendor_items WHERE inv_item_id = '".$_POST['inv_item_id']."'");
  //echo "SELECT inv_item_id FROM vendor_items WHERE inv_item_id = '".$_POST['inv_item_id']."' id = $id";
  if(mysql_num_rows($queryE) > 0){
    if($global_ecommerce == 'yes') { $status = 'active'; } else { $status = 'inactive'; }
    $query_status="UPDATE location_inventory_items SET status = '".$status."' WHERE id='".$id."'";  
          
    $result_status = mysql_query($query_status) or die(mysql_error());
  } else {
    $queryup="INSERT INTO vendor_items
      SET
        vendor_id = '".$default_vendor."', inv_item_id = '".$_POST['inv_item_id']."', status='".$status."', vendor_internal_number='0',
        pack_size='0', pack_unittype='".$unit."', qty_in_pack='0', qty_in_pack_unittype='".$unit."',
        tax_percentage='0.00', price='".$default_price."', promotion='', promotion_price='0.00',
        purchased_from_vendor='0', purchased_price='0.00', purchased_last='0000-00-00', created_on='VendorPanel',
        created_by='".$_SESSION['employee_id']."', created_datetime=NOW(), last_on='VendorPanel', last_by='".$_SESSION['employee_id']."', 
        last_datetime=NOW(), pack_weight='', qty_in_pack_size='', splitable='No', splitable_price='0.00', splits='0.00',
        splits_minimum='0', price_by_weight='No', price_by_weight_unittype='0', lead_time='0', stock='No', taxable='".$global_taxable."',
        tax_type='', tax_amount='0.00', description='".$local_item_desc."', notes='".$notes."', manufacturer='".$default_manufacturer."',
        model_number='', brand='".$default_brand."', manufacturer_barcode='".$manufacturer_barcode."'";  
          
    $result = mysql_query($queryup) or die(mysql_error());
  }

	if($_POST['digital_image_name']!="") $image="inventory/".$_POST['digital_image_name']; //local_group_id='$group',	inv_item_id = '$item_id',
  $queryup="UPDATE location_inventory_items 
            SET							
              status = '".$status."', local_unit_type = '".$unit."',
              priority='".$priority."', taxable='".$global_taxable."',
              ecommerce='".$global_ecommerce."', local_item_id='".$local_item_id."',
              local_item_desc='".$desc."', local_group_id = '".$local_group_id."',
              local_item_notes = '".$notes."', local_item_image = '".$image."',
              manufacturer_barcode = '".$manufacturer_barcode."', default_manufacturer = '".$default_manufacturer."',
              default_brand = '".$default_brand."', default_vendor = '".$default_vendor."',
              default_price = '".$default_price."',
              last_on = 'BusinessPanel', last_by = '".$_SESSION['employee_id']."', last_datetime = NOW()							
            WHERE id='".$id."'";  
          
  $result = mysql_query($queryup) or die(mysql_error());
  $msg="up";
  
  //if($image!=""){
    $query = "UPDATE inventory_items SET image='".$image."' WHERE id = '".$_POST['inv_item_id']."'";
    $li = mysql_query($query) or die(mysql_error());																		
  //}

  if (isset($_POST['step']) && $_POST['step']=="5") {	header("Location: setup_process.php?step=6"); }
  else {  header('Location: setup_backoffice_manage_items.php?group_id='.$_POST['s_group_id'].'&market='.$_POST['s_market'].'&vendor='.$default_vendor.'&msg='.$msg);}
}
if ($_REQUEST['id'] != '') {
			  $query1 = "SELECT ig.Market,lii.*,ii.manufacturer_barcode as global_barcode,ii.taxable as global_taxable,ii.model_number as global_model_number,ii.notes as global_notes,ii.manufacturer as global_manufacturer,ii.brand as global_brand,lii.taxable as local_taxable,lii.ecommerce as local_ecommerce,
			  CASE WHEN lii.type ='global' THEN ii.image 
			  ELSE
			  lii.local_item_image END as tr_image, CONCAT(e.first_name,' ',e.last_name) as created_emp ,CONCAT(le.first_name,' ',le.last_name) as last_emp,
			  ve.name as vendor_name, CONCAT(gve.name,' (ID: ',gve.id,')') as global_vendor_name,in_item.item_id AS item_code
              FROM location_inventory_items lii
			  JOIN inventory_items ii ON ii.id = lii.inv_item_id
        LEFT JOIN inventory_groups ig ON lii.local_group_id=ig.id
        LEFT JOIN inventory_item_unittype liu ON lii.local_unit_type=liu.id
			  LEFT JOIN employees as e on e.id = lii.created_by
			  LEFT JOIN vendors as ve On ve.id  = lii.default_vendor
			  LEFT JOIN vendors as gve On gve.id  = ii.vendor_default
			  LEFT JOIN employees as le on le.id = lii.last_by
			  LEFT JOIN inventory_items AS in_item ON in_item.id = lii.inv_item_id 
        WHERE lii.id=" . mysql_real_escape_string($_REQUEST['id']);
    $result1 = mysql_query($query1) or die(mysql_error());
    $row1 = mysql_fetch_array($result1) or die(mysql_error());
	

/*
echo "<pre>";	
print_r($row1);
die;*/
	
}
$query2 = "SELECT DISTINCT ig.id, ig.description FROM inventory_groups ig ORDER BY description ASC";
$result2 = mysql_query($query2) or die(mysql_error());

$query3 = "SELECT id, unit_type, description FROM inventory_item_unittype ORDER BY unit_type ASC";
$result3 = mysql_query($query3) or die(mysql_error());

//this is for setup process
if (isset($_GET['step']) && $_GET['step']=="5")	$step=5;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<style>
  .progress { border: 1px solid #DDDDDD; border-radius: 3px; display: none; margin-top: 10px;	padding: 1px;	position: relative;	width: 100%; }
  .bar { background-color: #B4F5B4;	border-radius: 3px;	height: 20px;	width: 0; }
  .percent { display: inline-block;	left: 48%; position: absolute; top: 3px; }
  .btn-delete {	display: none; }
  .ed {	display: none; }
  .ul { display: inline; }
  .hasimage .ed {	display: inline;}
  .hasimage .ul {	display: none; }
  .hasimage .btn-delete {	display: inline-block; }
  label {	width:180px !important; }
  .field { display: block; margin-left: 200px !important;	position: relative; }
  .stdform p, .stdform div.par { margin: 5px 0 !important; }
  input.span5, textarea.span5, .uneditable-input.span5 { width: 70%; }
  .dataTables_paginate .paginate_active { background: none repeat scroll 0 0 #0866C6; color: #FFFFFF; }
  .selectouter12{ width:222px !important;}
  .dd .ddTitle .ddTitleText { padding: 5px 20px 5px 5px !important;}
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/custom_webz.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>

<script type="text/javascript" src="js/jquery.dd1.js"></script>
<link rel="stylesheet" type="text/css" href="css/dd.css" >
<script type="text/javascript">
jQuery(document).ready(function(){
intRoomHeight =1;
jQuery('#local_unit_type').msDropDown();
	//	jQuery('#local_produces_unit_type').msDropDown();
  jQuery("#group").on('change',function() {
      var grp = jQuery(this).val();
      jQuery('.loaded').remove();
      jQuery('#last_row').before('<div class="loaded"></div>');
      jQuery.ajax({
          url: 'ajax/getInvItmByGrpJson.php?g='+grp,
          dataType:'JSON',
          success:function(data){
              //add dropdown options
              var dropdown = 'item';
              clearOptions(dropdown);
              addOption(dropdown,'---Select Item---','');
              addOption(dropdown,'Add New Item','new');
              if (data.length){
                for(var i=0;i<data.length;i++){										
                    addOption(dropdown,data[i].description,data[i].id)
                }
              }
              
              //add event handler
              jQuery('#item').off('change.item');
              jQuery('#item').on('change.item', function(){
                jQuery.get('checkimage.php?t=g&item='+jQuery(this).val(),function(data){										
                  if(data==1){
                  jQuery(".colorbox").hide();								  
                } else {
                  jQuery(".colorbox").show();
                }
              });

              jQuery.get('checkItem.php?t=g&item='+jQuery(this).val(),function(data){
                if(data == 1){
                  jAlert('Notice: This Item Already Exists In Your Inventory.','Alert');
                  jQuery('#item').val('');
                }
              });
              var itm = jQuery(this).val();
              if(itm == 'new'){
                jQuery('.global-new').show();
                jQuery('.new_item').show();
                jQuery('.loaded').remove();
                jQuery('#new_add_type').val('1');
              }else{
                jQuery('.global-new').hide();
                jQuery('.loaded').remove();
                jQuery('#new_add_type').val('');
                jQuery('#last_row').before('<div class="loaded"></div>');
                jQuery.get('loadItemData.php?i='+itm, function(data){
                  jQuery('.loaded').replaceWith(data);
                  jQuery("#imageLink").attr("href","upload_digital_menu_image.php?id="+itm);
                });
              }
            });
          }
      });
    });
  });

	function clearOptions(id) {
    document.getElementById(id).options.length = 0;
  }
	function addOption(selectbox, text, value) { // for adding options do dropdown
    var dropdown = document.getElementById(selectbox);
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    dropdown.options.add(optn);
  }
</script>
<script>
function imgdigital_btn()
{
  jQuery('#tempImage').click().change(function(evt){
    handleFileSelect(evt,'imgdigital');
    jQuery('body').focus();
  });
}
function handleFileSelect(evt,id) {
  var files = evt.target.files; // FileList object
  var formdata;
  if (window.FormData) {
      formdata = new FormData();
  }
  f = files[0];
  // Only process image files.
  if (!f.type.match('image.*')) {
    return false;
  }

  var reader = new FileReader();
  // Closure to capture the file information.
  reader.onload = (function(theFile) {
    return function(e) {
      document.getElementById(id).innerHTML='<img class="imgpreview img-polaroid" src="'+e.target.result+'" title="'+theFile.name+'"/>';
      global_imgdigital_upload=true;
    };
  })(f);
  reader.readAsDataURL(f);
  if (!formdata) {
    formdata.append("image", f);
  }
}

jQuery(document).ready(function(){
	jQuery('#add-on A').click(function(){
		if(jQuery(this).attr('rel')=='vendor'){
		  jQuery('#keyword').val(jQuery('#default_vendor_search').val());
		}else{
		  if(jQuery('#keyword').val().length<4){
		    jAlert('Please enter More than 3 Characters','Alert Dialog');
		    return false;
		  }
		}
		getclients(2);
	});
});

function getclients1(){
	if(jQuery('#default_vendor_search').val().length<4){
		jAlert('Please enter More than 3 Characters!','Alert Dialog');
		return false;
	}else{
	  jQuery('.icon-search').trigger('click');
	}
}
function getclients(val){
	var str;
	if(val=="1"){
		str = document.getElementById('default_vendor_search').value;
	}else{	
		str = jQuery('#keyword').val();
	}
	
  if(str.length>2){
	  document.getElementById('keyword').value=str;
	  document.getElementById("modalcontent").innerHTML="";
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        document.getElementById('default_vendor_search').value=str;
        document.getElementById("modalcontent").innerHTML=xmlhttp.responseText;
        // document.getElementById('keyword').value="";
        // document.getElementById("livesearch").style.border="1px solid #A5ACB2";
      }
    }
    xmlhttp.open("GET","vendor_search.php?q="+str,true);
    xmlhttp.send();
	}else{
	  jQuery('#modalcontent').html('');
	}
}
function loadVendor(id,email,phone,name,image)
{
	jQuery('#default_vendor_search').val(name);	
	jQuery('#default_vendor').val(id);	
	jQuery('#filter_modal').modal('toggle');
}
var bar_xhr = null;
function getbarcode(val){
  var search_val = jQuery("#local_barcode").val();
	
  if(search_val!=""){
    if (!search_val.match(/^\d*(?:\.\d{1,2})?$/)) { //replace all spaces (just to be sure)
      jConfirm('Invalid Barcode Number!', 'Alert!', function(r) {
      //jQuery('#barcode').focus();		     
      });
      jQuery('#popup_cancel').remove();
      return false;
    }
    if( search_val.length<6){
      jConfirm('Please Enter Minimum 6 Digit Valid Barcode Number Only!', 'Alert!', function(r) {
        //jQuery('#barcode').focus();		     
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
    jQuery('#local_barcode').val(search_val); 		
    if(bar_xhr!=null){
      bar_xhr.abort();
      bar_xhr =null;
    }
    bar_xhr = jQuery.ajax({
      url:'search_fectual_barcode.php',
      type:'POST',
      data:{search_val:search_val},
      success:function(data){
        //jQuery('#modalcontent').html(data);
        if(data){
          if(data=="b_found"){
            jAlert('This barcode already in use!','Alert Dialog');
            //jQuery('#txtDescri').val("");
            //jQuery('#notes').val("");
            //jQuery('#manufacturer_barcode').val('');
            jQuery('#ture_barcode').hide();
          }else{
          jQuery('#barcode_valid').val('Yes');
          var data = data.split('^');
          jQuery('#local_item_desc').val(data[0]);
          jQuery('#local_item_notes').val(data[1]);
          if(data[2]!=""){
          jQuery('#imagebox').html('<img src="'+data[2]+'" width="100px;" style="padding-bottom:5px;">');
          jQuery('#digital_image_name').val(data[2]);
          }else{
          jQuery('#smallImagebox').html('');
          jQuery('#upc_search_image').val('');
          }
          jQuery('#ture_barcode').show();				
          //jQuery('#manufacturer_barcode').val(search_val);
          }
        }else{
          jAlert('UPC Barcode not found in database!','Alert Dialog');
          //jQuery('#manufacturer_barcode').val('');
          jQuery('#ture_barcode').hide();
          jQuery('#txtDescri').val("");
          jQuery('#notes').val("");
          jQuery('#smallImagebox').html('');
          jQuery('#upc_search_image').val('');
        }
      }
    });
	}else if(val==2 && search_val==""){
		jAlert('Please Enter Value To Search Barcode!','Alert Dialog');
		jQuery('#ture_barcode').hide();
	}
}
function MakechangeBarcode(){
	jQuery('#barcode_valid').val('');
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
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/dd.css" >

</head>
<body>
<div id="pop_div" style="display:none;"></div>
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
      <li>Back Office</li>
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
      <div style="float:right; margin-right:4px;margin-top: 11px;"> <a href="setup_backoffice_manage_items.php?group_id=<?php echo $_REQUEST['group_id']; ?>&market=<?php echo $_REQUEST['market']; ?>" class="btn btn-primary btn-large">Back</a> </div>
      <div class="pageicon"> <span class="iconfa-cog"></span> </div>
      <div class="pagetitle">
        <h5>The manage items setup module allows you to manage all of your global, local and preparation items.</h5>
        <h1>Manage Items</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <form name="item_form" id="item_form" class="stdform" action="" method="post" enctype="multipart/form-data">
            <div class="span9" >
              <div class="widgetbox">
                <h4 class="widgettitle"> <?php echo !isset($_REQUEST['id']) ? 'Add Inventory Items' : 'Edit Inventory Items'; ?> </h4>
                <div class="widgetcontent">
                  <input type="hidden" name="subform" value="subform">
                  <input type="hidden" name="s_group_id" value="<?php echo $_GET['group_id']; ?>">
                  <input type="hidden" name="s_market" value="<?php echo $_GET['market']; ?>">
                  <input id="id" type="hidden" name="id" value="<?php echo mysql_real_escape_string($_REQUEST['id'])?>"/>
                  <input name="inv_item_id" id="inv_item_id" type="hidden" class="input-large" value="<?php echo $row1['inv_item_id'] ; ?>">
                  <input type="hidden" name="submit_form" value="submit_form">
                  <input type="hidden" name="new_add_type" id="new_add_type" value="" />
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" colspan="2">&nbsp;</td>
    <td valign="top" colspan="2" align="center"><h4>Global Item Information</h4></td>
  </tr>
  <tr>
    <td valign="top" width="10%" align="right">
    <label>Type:<span style="color:red">*</span></label>
    </td>
    <td width="40%" align="left">
    <select  style="width:223px;" name="type" id="type" class="uniformselect" disabled="disabled">
        <option value="">---Select Type---</option>
        <option selected="selected" value="global">Global</option>
      </select>
    </td>
    <td width="10%" valign="top" align="right">&nbsp;</td>
    <td width="40%" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Status:<span style="color:red">*</span></label></td>
    <td align="left"><select style="width:223px;" name="status" id="status" class="uniformselect">
        <option <?php if($row1['status']=='active'){ echo 'Selected'; } ?>  value="active">Active</option>
        <option <?php if($row1['status']=='inactive'){ echo 'Selected'; } ?> value="inactive">Inactive</option>
      </select>
    </td>
    <td valign="top" align="right"><label>Status:</label></td>
    <td align="left"><select style="width:223px;" disabled="disabled" name="status" id="status" class="uniformselect">
        <option <?php if($row1['status']=='active'){ echo 'Selected'; } ?>  value="active">Active</option>
        <option <?php if($row1['status']=='inactive'){ echo 'Selected'; } ?> value="inactive">Inactive</option>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Local barcode:</label></td>
    <td align="left"><input style="width: 210px; padding:4px 5px !important;" onBlur="getbarcode(1)" type="text" onKeyDown="MakechangeBarcode(); javascript:if(event.keyCode==13){getbarcode(2);  return false;}" name="local_barcode" id="local_barcode"  class="input-short" value="<?php echo $row1['manufacturer_barcode']; ?>" />
      <input type="hidden" id="barcode_valid" value="">
    </td>
    <td valign="top" align="right"><label>Manufacturer Barcode:</label></td>
    <td align="left"><input readonly name="manufacturer_barcode" id="manufacturer_barcode" type="text" class="input-large" value="<?php echo $row1['global_barcode'];?>"></td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Market:<span style="color:red">*</span></label></td>
    <td align="left"><select <?php if($_GET['menu_art']>0){ $row1['Market']='Retail'; echo 'disabled'; } ?>   class="dummy-market"  id="dummy_market" style="width:223px;" name="market">
        <option value="">- - - Select Market - - -</option>
        <?php
								
									$sql = mysql_query("SELECT * FROM inventory_market");
									while($fetch = mysql_fetch_assoc($sql)) {?>
        <option value="<?php echo $fetch['description'];?>" <?php if($fetch['description'] == $row1['Market']){echo "selected";}?>> <?php echo $fetch['description'];?> </option>
        <?php }?>
      </select>
    </td>
    <td valign="top" align="right"><label>Market:<span style="color:red">*</span></label></td>
    <td align="left"><select <?php if($_GET['menu_art']>0){ $row1['Market']='Retail'; echo 'disabled'; } ?> disabled="disabled"   class="dummy-market"  id="dummy_market" style="width:223px;" name="market">
        <option value="">- - - Select Market - - -</option>
        <?php
								
									$sql = mysql_query("SELECT * FROM inventory_market");
									while($fetch = mysql_fetch_assoc($sql)) {?>
        <option value="<?php echo $fetch['description'];?>" <?php if($fetch['description'] == $row1['Market']){echo "selected";}?>> <?php echo $fetch['description'];?> </option>
        <?php }?>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Group:</label></td>
    <td align="left"><select style="width:223px;" id="local_group_id" name="local_group_id" class="uniformselect input-large" >
        <option value="">---Select Group---</option>
        <?php 
						  $result2 = mysql_query($query2) or die(mysql_error());
						  while ($row2 = mysql_fetch_array($result2)) { ?>
        <option value="<?php echo $row2['id'];?>" <?php if ($row2['id'] == $row1['local_group_id'] ) {echo "selected='selected'";} ?>><?php echo $row2['description'];?></option>
        <?php } ?>
      </select>
    </td>
    <td valign="top" align="right"><label>Group:</label></td>
    <td align="left"><select style="width:223px;" id="group" name="group" class="uniformselect" disabled="disabled">
        <option value="">---Select Group---</option>
        <?php 
				  $result2 = mysql_query($query2) or die(mysql_error());
				  while ($row2 = mysql_fetch_array($result2)) { ?>
        <option value="<?php echo $row2['id'];?>" <?php if ($row2['description'] == $_REQUEST['group']) {echo "selected='selected'";} ?>><?php echo $row2['description'];?></option>
        <?php } ?>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Abbreviation:</label></td>
    <td align="left"><input name="item_id" id="item_id" type="text" class="input-large" value="<?php echo $row1['local_item_id'];?>">
    </td>
    <td align="right" valign="top"><label>Item Code:<span style="color:red">*</span></label></td>
    <td align="left"><input name="Item_id" id="Item_id" type="text" readonly class="input-large" value="<?php echo $row1['item_code'] ; ?>"></td>
  </tr>
  <?php
	$item_desc = ($row1['local_item_id'] != '') ? $row1['local_item_id'] : $row1['local_item_desc'];
  ?>
  <tr>
    <td valign="top" align="right"><label>Item Description:</label></td>
    <td align="left"><input name="description" id="description" type="text" class="input-large" value="<?php echo htmlspecialchars($item_desc);?>">
    </td>
    <td valign="top" align="right"><label>Item Description:</label></td>
    <td align="left"><input name="item_id" id="item_id" type="text" class="input-large" value="<?php echo htmlspecialchars($_REQUEST['item']); ?>" readonly></td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Priority:</label></td>
    <td align="left"><input name="priority" id="priority_1" type="text" class="input-large" value="<?php echo $row1['priority'];  ?>">
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Local Item Notes:</label></td>
    <td align="left"><textarea name="local_item_notes" id="local_item_notes" style="width:210px;" class="" rows="5" cols="22"><?php echo $row1['local_item_notes']; ?></textarea>
    </td>
    <td valign="top" align="right"><label>Notes:</label></td>
    <td align="left"><textarea readonly name="global_notes" id="global_notes" style="width:210px;" class="" rows="5" cols="22"><?php echo $row1['global_notes']; ?></textarea></td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Taxable:</label></td>
    <td align="left"><select style="width:223px;" name="global_taxable" id="global_taxable" class="uniformselect" >
        <option>---Select Taxable---</option>
        <option <?php if ($row1['local_taxable']=="no"){echo "selected='selected'";} ?> value='no'>No</option>
        <option <?php if ($row1['local_taxable']=="yes"){echo "selected='selected'";} ?> value='yes'>Yes</option>
      </select>
    </td>
    <td valign="top" align="right"><label>Global Taxable:</label></td>
    <td align="left"><select style="width:223px;" name="global_taxable" disabled="disabled" id="global_taxable" class="uniformselect" >
        <option>---Select Taxable---</option>
        <option <?php if ($row1['global_taxable']=="no"){echo "selected='selected'";} ?> value='no'>No</option>
        <option <?php if ($row1['global_taxable']=="yes"){echo "selected='selected'";} ?> value='yes'>Yes</option>
      </select></td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Master Unit of Measure:</label></td>
    <td align="left"> <div class="non-global selectouter12 select_w3" style="border-radius: 0px 0px 0 0; border: 1px solid #c3c3c3; width:72%; height: 27px; margin-top:-8px;"><select class="uniformselect"  name="local_unit_type" id="local_unit_type" >
                    <option value="">---Select Unit type---</option>
                    <?php
					 $result3 = mysql_query($query3) or die(mysql_error());
					 while ($row3 = mysql_fetch_array($result3)) { ?>
                    <option data-description="<?php echo $row3['description']; ?>" value="<?php echo $row3['id'];?>" <?php if ($row3['id'] == $row1['local_unit_type']) {echo "selected='selected'";} ?>><?php echo $row3['unit_type'];?></option>
                    <?php } mysql_data_seek($result3, 0); ?>
                  </select></div>
    </td>
    <td valign="top" align="right"><label>Master Unit of Measure:</label></td>
    <td align="left"><select  style="width:223px;" name="unittype" id="unittype" disabled="disabled" readonly class="uniformselect">
        <option value="">---Select Unit type---</option>
        <?php while ($row3 = mysql_fetch_array($result3)) { ?>
        <option value="<?php echo $row3['id'];?>" <?php if ($row3['unit_type'] == $_REQUEST['unit_type']) {echo "selected='selected'";} ?>><?php echo $row3['unit_type'];?></option>
        <?php } mysql_data_seek($result3, 0); ?>
      </select></td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Slot#:</label></td>
    <td align="left"><input name="slot" id="slot" type="text" class="input-large" value="<?php echo '';?>"></td>
    <td valign="top" align="right"><label>Manufacturer:</label></td>
    <td align="left"><input readonly name="Manufacture" id="Manufacture" type="text" class="input-large" value="<?php echo $row1['global_manufacturer'];?>"></td>
  </tr>
  <tr>
	<td valign="top" align="right"><label>Default Manufacturer:</label></td>
    <td align="left"><input name="default_manufacturer" id="default_manufacturer" type="text" class="input-large" value="<?php echo $row1['default_manufacturer'];?>"></td>
    
      <td align="right" valign="top"><label>Brand:</label></td>
    <td align="left"><input readonly name="Brand" id="Brand" type="text" class="input-large" value="<?php echo $row1['global_brand'];?>"></td>

  </tr>
  <tr>
	<td valign="top" align="right"><label>Default Brand:</label></td>
    <td align="left"><input name="default_brand" id="default_brand" type="text" class="input-large" value="<?php echo $row1['default_brand'];?>"></td>
    <td valign="top" align="right"><label>Default Vendor:</label></td>
    <td align="left"><input readonly name="modal_number" id="modal_number" type="text" class="input-large" value="<?php echo $row1['global_vendor_name'];?>"></td>
  </tr>
  <tr>
	<td valign="top" align="right"><label>Default Vendor:</label></td>
    <td align="left"> 
	<div id="clientsearch" class="input-append">
		<input style="width:184px;" name="default_vendor_search" id="default_vendor_search" onKeyDown="javascript:if(event.keyCode==13){getclients1(2);return false;}" type="text" class="input-large" value="<?php if($row1['vendor_name']!=""){ echo $row1['vendor_name'].' (ID: '.$row1['default_vendor'].' )'; } ?>">
		<span id="add-on" class="add-on" style="height:22px;" > <a href="" rel= 'vendor' data-toggle="modal" data-target="#filter_modal" data-refresh="true" class="icon-search" style="position: relative;"> </a> </span>
		<input name="default_vendor" type="hidden"   value="<?php echo $row1['default_vendor'];?>"  id="default_vendor" />
	</div></td>	
    <td valign="top" align="right"><label>Model Number:</label></td>
    <td align="left"><input readonly name="modal_number" id="modal_number" type="text" class="input-large" value="<?php echo $row1['global_model_number'];?>"></td>
  </tr>
  <tr>
	<td valign="top" align="right"><label>Cost:</label></td>
    <td align="left"><input name="default_price" id="default_price" type="text" class="input-large" value="<?php echo $row1['default_price'];?>" onKeyPress="return isNumberKey(event)"></td>
  
	<td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
	<td valign="top" align="right"><label>eCommerce:</label></td>
	<td align="left"><select style="width:223px;" name="global_ecommerce" id="global_ecommerce" class="uniformselect" >
        <option value=''>---Select eCommerce---</option>
        <option <?php if ($row1['local_ecommerce']=="no"){echo "selected='selected'";} ?> value='no'>No</option>
        <option <?php if ($row1['local_ecommerce']=="yes"){echo "selected='selected'";} ?> value='yes'>Yes</option>
      </select>
    </td>
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Created By:</label></td>
    <td align="left"><input type="text" readonly name="created_by" class="input-large" id="created_by" value="<?php echo $row1['created_emp'];?>" ></td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Created On:</label></td>
    <td align="left"><input type="text" readonly name="created_on" class="input-large" id="created_on" value="<?php echo $row1['created_on'];?>" >
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Created Date & Time:</label></td>
    <td align="left"><input type="text" readonly name="created_datetime" class="input-large" id="created_datetime" value="<?php echo GetLocationTimeFromServer_general($_SESSION['loc'],$row1['created_datetime']); ?>" >
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Last By:</label></td>
    <td align="left"><input type="text" readonly name="last_by" class="input-large" id="last_by" value="<?php echo $row1['last_emp'];?>" >
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Last On:</label></td>
    <td align="left"><input type="text" readonly name="last_on" class="input-large" id="last_on" value="<?php echo $row1['last_on'];?>" >
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" align="right"><label>Last Date & Time:</label></td>
    <td align="left"><input type="text" readonly name="last_datetime" class="input-large" id="last_datetime" value="<?php echo ($row1['last_datetime'] != '') ? GetLocationTimeFromServer_general($_SESSION['loc'],$row1['last_datetime']) : ''; ?>" >
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
  
    <tr>
    <td valign="top" align="left"> <input id="step_child" name="step" type="hidden" value="<?php echo $step;?>" />
              <div class="loaded"></div>
              <div id="last_row">
                <p style="margin-left:5px;">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <?php if($_REQUEST['id']==""){?>
                  <button type="button" onClick="window.location.href='setup_finance_manage_items_add.php'" class="btn btn-primary">Reset</button>
                  <?php } ?>
                </p>
              </div></td>
    <td align="left">&nbsp;
    </td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
  </tr>
</table>

                </div>
              </div>
            </div>
            <div class="span3" style="width:24.4% !important;float: left;">
              <div class="widgetbox profile-photo">
                <div class="headtitle">
                  <div id="image_drop" class="btn-group">
                    <button  data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                    <ul  class="dropdown-menu"  >
                      <li> <a data-target="#imageModal" href="upload_manage_item_image.php?id=<?php echo $_REQUEST['id']; ?>"  data-toggle="modal" id="imageLink">Upload Images</a> </span>
                        </p>
                      </li>
                    </ul>
                  </div>
                  <h4 class="widgettitle">Item Image</h4>
                </div>
                <div class="widgetcontent">
                  <p style="margin:0;padding:0;text-align:center;"> (Image Size Required 225w x 225h) </p>
                  <div class="profilethumb">
                    <div id="imagebox">
                      <?php if($row1['local_item_image']!="" ){ ?>
                      <img class="img-polaroid"  src="<?php echo APIIMAGE."images/".$row1['local_item_image']; ?>" onerror="this.src='images/defimgpro.png'" >
                      <?php }else{ ?>
                      <img src="images/defimgpro.png" style="height:250px;" alt="" class="img-polaroid" id="mainimage"/>
                      <?php } ?>
                    </div>
                    <?php $str=explode('/',$row1[tr_image]) ?>
                    <input type="hidden" name="digital_image_name" id="digital_image_name" value="<?php echo $str[1]; ?>" />
                  </div>
                  <!--profilethumb-->
                </div>
              </div>
            </div>
          </form>
        </div>
        <?php include_once 'require/footer.php';?>
        <!--footer-->
      </div>
    </div>
    <!--maincontentinner-->
  </div>
  <!--maincontent-->
</div>
<!--rightpanel-->
</div>
<!--mainwrapper-->
<div id="imageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Add/Edit Image</h3>
  </div>
  <div class="modal-body " id="mymodalhtml"> </div>
  <div class="modal-footer" style="text-align:center;">
    <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
    <button class="btn btn-primary" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
  </div>
</div>
<div id="filter_modal" style="height:600px !important;" class="modal hide fade">
  <div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
    <h3>Search Vendor</h3>
    <br>
    <label style="width:auto !important;">
    Search:&nbsp;&nbsp;
    <div class="input-append">
      <input name="keyword" id="keyword" type="text"  onKeyUp="javascript:getclients(2)" tabindex="0" style="width:400px;"  />
      <!--<span class="add-on" ><a href="javascript:void(0);" class="icon-search" ></a></span> </div>-->
    </label>
  </div>
  <div class="modal-body" id="modalcontent" style="height:415px;"> </div>
  <div class="modal-footer" style="text-align: center;">
    <p >
      <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
    </p>
  </div>
</div>
</body>
</html>
