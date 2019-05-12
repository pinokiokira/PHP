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
    if($status =='Active'){
        $res = '<img src="images/Active, Corrected, Delivered - 16.png" title="Active" >';
    }else{
        $res = '<img src="images/Inactive & Missing Punch - 16.png" title="Inactive" >';
    }
    return $res;
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
    $vendor_routes_id = mysql_real_escape_string($_REQUEST['vendor_routes_id']);
    $route_code = mysql_real_escape_string($_REQUEST['route_code']);
    $route_name = mysql_real_escape_string($_REQUEST['route_name']);
    $route_description = mysql_real_escape_string($_REQUEST['route_description']);
    $route_estimated_time = mysql_real_escape_string($_REQUEST['route_estimated_time']);
	$route_distance = mysql_real_escape_string($_REQUEST['route_distance']);
 
	if(!empty($vendor_routes_id)){ 
		$last_by = $_SESSION['client_id'];
		$last_on = 'VendorPanel';
		$last_datetime = date('Y-m-d H:i:s');

		$sql = "UPDATE vendor_routes SET  status = '".$status."', route_code = '".$route_code."', route_name = '".$route_name."', route_description = '". $route_description ."', route_estimated_time = '". $route_estimated_time ."', route_distance = '". $route_distance ."', last_by = '". $last_by."', last_on = '". $last_on ."', last_datetime = '".$last_datetime."' WHERE vendor_routes_id = '".$vendor_routes_id."'";
		$up_inv = mysql_query($sql); //or die(mysql_error());

	}else{ 
		$ins_query = "INSERT INTO vendor_routes SET
						status = '".$status."', 
					  route_code = '".$route_code."',
					  vendor_id = '". $vendor_id ."',
					  route_name = '".$route_name."',
					  route_description = '".$route_description."',
					  route_estimated_time = '".$route_estimated_time."',
					  route_distance = '".$route_distance."',
					  created_by = '".$_SESSION['client_id']."',
					  created_on = 'VendorPanel',
					  created_datetime = '".date('Y-m-d H:i:s')."'";
					   //echo $ins_query;exit; die;
						//print_r("insert item :    ". $ins_query);

		$res_ins = mysql_query($ins_query); // or die(mysql_error());
		if($res_ins){
			$vendor_routes_id = mysql_insert_id();//die;
		}
	}
	
    $res1 = mysql_query($query1);// or die(mysql_error());
    header('location:setup_routes.php?by=SoftPoint&vendor_routes_id='.$vendor_routes_id.'&msg=Item Added/Updated Successfully!');
}
 $limit = 500;
if(isset($_REQUEST['search_txt1'])){
    $limit = 500;
	if($_REQUEST['search_txt1']!=""){
		$search = $_REQUEST['search_txt1'];
		$search_where = " AND (vr.route_description LIKE '%".$search."%' OR vr.route_code LIKE '%".$search."%' OR vr.route_name LIKE '%".$search."%')";
	}
}

	$sql = "SELECT vr.status, vr.vendor_routes_id, vr.vendor_id, vr.route_code, vr.route_name, vr.route_description, vr.route_estimated_time, vr.route_distance, vr.created_on, vr.created_by, vr.created_datetime, vr.last_by, vr.last_on, vr.last_datetime FROM vendor_routes as vr
        WHERE vr.vendor_id='".$vendor_id."' $search_where LIMIT $limit";

        if($_REQUEST['debug'] == '1'){
            echo '$sql : '. $sql;
        }
$resultJobs = mysql_query($sql) or die(mysql_error());
if(isset($_GET['vendor_routes_id'])){
    $selected=$_GET['vendor_routes_id'];
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
<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

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

.ui-datepicker {
z-index: 10000 !important;
}


   .bootstrap-timepicker-widget.dropdown-menu:after {
    border-top: 6px solid #fff;
    top: 124px;
    border-bottom:0;
    }
    .bootstrap-timepicker-widget.dropdown-menu:before {
    border-top: 7px solid rgba(0,0,0,0.2);
    top: 125px;
    border-bottom:0;
    }

</style>

<script type="text/javascript">

	var vendor_id = '<?php echo $vendor_id; ?>';
	var intRoomHeight = jQuery('.widgetcontent').height();
	function validate(){
		
	}	
	
	jQuery(document).ready(function($){
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

    jQuery('#addcode').click(function(){
        jQuery('#vendor_routes_id').val('');
        jQuery('#route_code').val('');
        jQuery('#route_name').val('');
        jQuery('#route_description').val('');
        jQuery('#route_distance').val('');
		jQuery('#route_estimated_time').val('');
        jQuery('#created_by').val('');
        jQuery('#created_on').val('');
        jQuery('#created_datetime').val('');
		jQuery('.selected').removeClass('selected');
		jQuery('#status').val('Active');
		
        //Extended
        jQuery('#edit_from input,#edit_from select').val('');
        jQuery('#for_edit').hide();
        jQuery('.reset').show();

        jQuery('#created_on').val('VendorPanel');
        jQuery('#created_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
        jQuery('#created_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');

        jQuery('#last_on').val('VendorPanel');
        jQuery('#Last_datetime').val('<?php echo date('Y-m-d H:i:s'); ?>');
        jQuery('#last_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
        jQuery('#barcode_p').show();
    });

    jQuery(".cl_order").live('click',function(){			
		jQuery('#status').val(jQuery(this).data('status'));
		jQuery('#vendor_routes_id').val(jQuery(this).data('vendor_routesid'));
		jQuery('#route_code').val(jQuery(this).data('route_code'));
		jQuery('#route_name').val(jQuery(this).data('route_name'));
		jQuery('#route_description').val(jQuery(this).data('route_description'));
		jQuery('#route_estimated_time').val(jQuery(this).data('route_estimatedtime'));
		jQuery('#route_distance').val(jQuery(this).data('route_distance'));
		jQuery('#last_on').val(jQuery(this).data('last_on'));
		jQuery('#last_datetime').val(jQuery(this).data('last_datetime'));
		jQuery('#last_by').val(jQuery(this).data('last_by'));
		jQuery('#created_on').val(jQuery(this).data('created_on'));
		jQuery('#created_datetime').val(jQuery(this).data('created_datetime'));
		jQuery('#created_by').val('<?php echo get_empmaster($_SESSION['client_id']) ;?>');
		jQuery('.reset').hide();
		jQuery(".gradeX").attr("class","gradeX cl_order");
		jQuery(this).attr("class","gradeX cl_order selected");
		jQuery('#for_edit').show();

	//}
	});
        if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
        }        
        /*gsdgsd*/
});

jQuery('#cancel_btn').live('click',function(){
        jQuery('#drop_span').show();
        jQuery('#new_span').hide();
        jQuery('#inv_item_type').val('old');
        jQuery('#inv_item1').val('');
        jQuery('#inv_item').val('');
});

jQuery('#ser_go').live('click',function(){
	window.location="setup_routes.php?&search_txt1="+jQuery('#search_txt1').val();
  /*var total_rec = jQuery('#total_rows').val();
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
                    if(val=="") val = 'All';
                    //window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
                    window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
                }
            }else{
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
        if(val=="") val = 'All';
        //window.location="storepoint_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
        window.location="setup_items.php?group1="+val+"&market="+dummy_market+"&search_txt1="+jQuery('#search_txt1').val();
    }*/
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
      <li>Routes</li>
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
        <h5>List of the Routes and staging areas</h5>
        <h1>Setup - Routes</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <div class="span8" style="width:70%;float:left; overflow:auto;">
            <div class="clearfix">
              <h4 class="widgettitle">Routes</h4>
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
			  <col class="con1" />
              </colgroup>
              <thead>
                <tr>
                  <th class="head1">S</th>
                  <th class="head0">Route Code</th>
                  <th class="head0">Route Name</th>
                  <th class="head1">Route Description</th>
                  <th class="head1">Estimates Time</th>
                  <th class="head1">Route Distance</th>
                  <th class="head0">Created Datetime</th>
                  <th class="head1" style="text-align:center;">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php  
					while($row = mysql_fetch_array($resultJobs)){
						//echo "<pre>";print_r($row);
					if($selected==0){
						$selected=$row["id"];
					}
                   ?>
                <tr class="<?php if($selected==$row["vendor_routes_id"]){ echo "selected-row";} ?> gradeX cl_order" id="<?php echo $row["vendor_routes_id"];?>"
                        data-status ="<?php echo $row["status"];?>"
                        data-vendor_routesid ="<?php echo $row["vendor_routes_id"];?>"
                        data-route_code ='<?php echo $row['route_code']; ?>'
                        data-vendor_id = "<?php echo $row['vendor_id']; ?>"
                        data-route_name = "<?php echo $row['route_name']; ?>"
                        data-route_description = "<?php echo $row['route_description']; ?>"
                        data-route_distance="<?php echo $row['route_distance']; ?>"
                        data-route_estimatedtime="<?php echo $row['route_estimated_time']; ?>"
                        data-created_on="<?php echo $row['created_on']; ?>"
                        data-created_by="<?php echo $row['created_by']; ?>"
                        data-created_datetime="<?php echo $row['created_datetime']; ?>"
						data-last_on="<?php echo $row['last_on']; ?>"
                        data-last_by="<?php echo get_empmaster($row['last_by']); ?>"                        
                        data-last_datetime="<?php echo $row['last_datetime']; ?>"
                        >
                  <td><?php echo status_img($row['status']); ?><span style="display:none;"><?php echo $row['status']; ?></span></td>
                  <td><?php echo $row['route_code']; ?></td>
                  <td><?php echo $row['route_name']; ?></td>
                  <td><?php echo $row['route_description']; ?></td>
                  <td><?php echo $row['route_estimated_time']; ?></td>
                  <td><?php echo $row['route_distance']; ?></td>
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
              <h4 class="widgettitle">Add/Edit Route</h4>
            </div>
			<div class="widgetbox" id="fdetail" style="padding-left: 5%;">
				<form id="edit_from234" name="frm234" action="" method="post" class="edit_from" >
					<input type="hidden" name="vendor_routes_id" value="" id="vendor_routes_id">
					<p>
						<label>Status:<span style="color:#FF0000;">*</span></label>
						<span class="field">
							<select name="status" id="status" style="width:285px;">
								<option value="" > - - - Select Status - - -</option>
								<option selected="selected" value="Active">Active</option>
								<option value="Inactive" >Inactive</option>
							</select>
						</span>
					</p>
					<p>
						<label>Route Code:<span style="color:#FF0000;">*</span></label>
						<span class="field">
							<input type="text" class="input-xlarge" id="route_code" value="" name="route_code">
						</span>
					</p>
					<p>
						<label>Route Name:<span style="color:#FF0000;">*</span></label>
						<span class="field">
							<input type="text" class="input-xlarge" id="route_name" value="" name="route_name">
						</span>
					</p>
					<p>
						<label>Route Description:<span style="color:#FF0000;">*</span></label>
						<span class="field"></span>
						<span class="field" id="group_span">
							<textarea class="input-xlarge" id="route_description" value="" name="route_description" onBlur="getRoute_description(1)" type="text" onKeyDown=" javascript:if(event.keyCode==13){getRoute_description(2);  return false;}"></textarea>
						</span>
					</p>
					<p>
						<label>Route Estimated Time:<span style="color:#FF0000;">*</span></label>
						<!--span class="field" id="drop_span">
							<input type="time" class="input-xlarge" id="route_estimated_time" value="" name="route_estimated_time">
						</span-->
						<span class="field" style="margin:0;">
							<span class="input-append bootstrap-timepicker">
								<input name="route_estimated_time" value="<?php echo date("g:i A",strtotime($lnupdaterv['route_estimated_time'])); ?>" type="text" class="input-short" id="route_estimated_time" style="width:193px" >
								<span class="add-on">
									<i class="iconfa-time"></i> 
								</span>
							</span>
						</span>
					</p>
					<p>
						<label>Route Distance:<span style="color:#FF0000;">*</span></label>
						<span class="field" id="drop_span">
							<input type="text" class="input-xlarge" id="route_distance" value="" name="route_distance">
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

    jQuery('#splitable').on('change', function(){
        if(jQuery('#splitable option:selected').val().toString().toLowerCase() == 'yes') {
            jQuery('.vi_splitable').show();
        } else {
            jQuery('.vi_splitable').hide();
        }
    });

   jQuery( "#route_estimated_time" ).timepicker();
});

function getroutes_description(val){
  var search_val = jQuery("#route_description").val();

    if(search_val!=""){
        if( search_val.length>60){
            jConfirm('Please enter a max of 60 characters in Route Description!', 'Alert!', function(r) {
                //jQuery('#barcode').focus();
                });
                jQuery('#popup_cancel').remove();
                return false;
        }
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
	var search_val = jQuery("#route_description").val();
	if(jQuery('#status').val()==""){
        jAlert('Please Select status!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#route_code').val()==""){
        jAlert('Please Enter Route Code!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#route_name').val()==""){
        jAlert('Please Enter Route Name!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#route_description').val()==""){
        jAlert('Please Enter Route Description!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#route_estimated_time').val()==""){
        jAlert('Please Enter Route Estimated Time!','Alert Dialog');
        e.preventDefault();
    }else if(jQuery('#route_distance').val()==""){
        jAlert('Please Enter Route Distance!','Alert Dialog');
        e.preventDefault();
    }else if( search_val.length>60){
		jAlert('Please enter a max of 60 characters in Route Description!','Alert Dialog');
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

jQuery('#route_description').blur(function() {
    var desc = jQuery('#route_description').val();
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
</script>
<div id="filter_modal" style="height:600px !important;" class="modal hide fade">

    <div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
            aria-hidden="true">&times;</button>
      <h3>Search Route</h3>
      <br>
      <label>Search:&nbsp;&nbsp;
          <div class="input-append">
              <input name="keyword" id="keyword" type="text"  onKeyUp="javascript:GetVendor(2)"
              tabindex="0" style="width:400px;"  />
              <span class="add-on" ><a href="javascript:void(0);" class="icon-search" ></a></span>
          </div>
          <!--<a href="#" id=""> <img id="ai" src="images/Add_16.png"></a>-->
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
</html>
