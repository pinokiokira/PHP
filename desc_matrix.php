<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$empmaster_id=$_SESSION['client_id'];
$curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));
$c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id,first_name,last_name from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];
$user = $vender['first_name'].' '.$vender['last_name'];

$date = date('Y-m-d');
$date_create = date('Y-m-d H:i:s');

$search = date('Y-m-d');
$search_for_disable = date('Y-m-d');
if(strtotime($date_create) > strtotime(date('Y-m-d 16:00:00'))){
	$search = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
	$search_for_disable = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
}
$d_none = 1;
if(isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != ""){
	if(strtotime($_REQUEST['search_date']) > strtotime($search_for_disable)){
		$d_none = 0;
	}
}

$search = (isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != "") ? mysql_real_escape_string($_REQUEST['search_date']) : $search;


$search_date = " AND vd.distribution_date = '" . $search . "'";

if(isset($_POST) && $_POST['Submit']=="Submit"){
	
	//echo "<pre>"; print_r($_POST); die;
	
	$confirm_captain = mysql_real_escape_string($_REQUEST['confirm_captain']);
	$confirm_puller = mysql_real_escape_string($_REQUEST['confirm_puller']);
    $bay_id = mysql_real_escape_string($_REQUEST['bay']);
    $client_id = mysql_real_escape_string($_SESSION['client_id']);
    $captain = mysql_real_escape_string($_REQUEST['captain']);
    $puller = mysql_real_escape_string($_REQUEST['puller']);
    $route = mysql_real_escape_string($_REQUEST['route']);
    $truck = mysql_real_escape_string($_REQUEST['truck']);
    $driver = mysql_real_escape_string($_REQUEST['driver']);
    $cc = mysql_real_escape_string($_REQUEST['cc']);
	$shipping_date = mysql_real_escape_string($_REQUEST['shipping_date']);
	
	
	$load_time_hours = mysql_real_escape_string($_REQUEST['load_time_hours']);
	$load_time_minutes = mysql_real_escape_string($_REQUEST['load_time_minutes']);
	
    //$route_time = mysql_real_escape_string($_REQUEST['route_time']);
	$route_time = mysql_real_escape_string($_REQUEST['route_time']);
    $hour_minute=explode(":",$route_time);
    $route_time_seconds=((int)$hour_minute[0]*3600)+((int)$hour_minute[1]*60);
    
	
	$load_time = $load_time_hours.':'.$load_time_minutes;
    $load_minute=explode(":",$load_time);
    $load_time_seconds=((int)$load_minute[0]*3600)+((int)$load_minute[1]*60);
	
	//$load_time = mysql_real_escape_string($_REQUEST['load_time']);
    /*$load_hour_minute=explode(":",$load_time);
    $load_time_seconds=((int)$load_hour_minute[0]*3600)+((int)$load_hour_minute[1]*60);*/
	
	$time_out = mysql_real_escape_string($_REQUEST['time_out']);
	$out_hour_minute=explode(":",$time_out);	
    $out_time_seconds=((int)$out_hour_minute[0]*3600)+((int)$out_hour_minute[1]*60);
    
	
	if($confirm_captain == 'yes'){
		$vendor_bays = "UPDATE vendor_bays SET bay_code = '".$captain."' WHERE vendor_bays_id = '".$bay_id."'";
		$res_bays = mysql_query($vendor_bays);
	}
	
	if($confirm_puller == 'yes'){
		$vendor_bays = "UPDATE vendor_bays SET bay_size='".$puller."' WHERE vendor_bays_id = '".$bay_id."'";
		$res_bays = mysql_query($vendor_bays);
	}
	
	if(isset($_POST['edit_id']) && strlen($_POST['edit_id']) > 0){
		//These were separated for edit and insert because the clock should disappear if the user edits the route or out time.
		/* if($out_time_seconds != 0){
			$out_time_seconds .= '-2';
		}
		if($route_time_seconds != 0){
			$route_time_seconds .= '-2';
		} */
		$bay_edit = '';
		if(isset($_REQUEST['bay'])){
			$bay_edit = "bay_id = '".$_REQUEST['bay']."',";
		}
		
		$ins_query2 = "UPDATE vendor_distribution SET
                        captain = '".$captain."',
						distribution_date = '".$shipping_date."',
						driver = '".$driver."',
                        puller = '".$puller."',
						$bay_edit
						last_on = 'VendorPanel',
						last_by = '".$_SESSION['client_id']."',
						last_datetime = '".$date_create."'
						WHERE vendors_distribution_id = '".$_POST['edit_id']."'";
		$res_ins2 = mysql_query($ins_query2);

		$ins_query = "UPDATE vendor_distribution_routes SET
			routes = '".$route."',
			route_time = '".$route_time_seconds."',
			load_time = '".$load_time_seconds."',
			time_out = '".$out_time_seconds."-2',
			vehicle = '".$truck."',
			cartons = '".$cc."',
			last_on = 'VendorPanel',
			last_by = '".$_SESSION['client_id']."',
			last_datetime = '".$date_create."' WHERE vendor_distribution_id = '".$_POST['edit_id']."'";
		$res_ins = mysql_query($ins_query);
		
		header('location:desc_matrix.php?search_date='.$search);
		die;
	}else{
		//Separation for insertion
		if($out_time_seconds != 0){
			$out_time_seconds .= '-1';
		}
		if($route_time_seconds != 0){
			$route_time_seconds=$route_time_seconds.'-1';
		}
		$ins_query2 = "INSERT INTO vendor_distribution SET
							bay_id = '".$bay_id."',
							captain = '".$captain."',
							distribution_date = '".$shipping_date."',
							vendor_id = '".$vendor_id."',
							driver = '".$driver."',
							puller = '".$puller."',
							created_by = '".$_SESSION['client_id']."',
							created_on = 'VendorPanel',
							created_datetime = '".$date_create."'";
		$res_ins2 = mysql_query($ins_query2);
		if($res_ins2) {
			$vendor_distribution_id = mysql_insert_id();//die;
		}
		if (!$vendor_distribution_id) {
			die('Could not query:' . mysql_error());
		}
		$ins_query = "INSERT INTO vendor_distribution_routes SET
							vendor_distribution_id = '".$vendor_distribution_id."',
							routes = '".$route."',
							route_time = '".$route_time_seconds."',
							load_time = '".$load_time_seconds."',
							time_out = '".$out_time_seconds."',
							vehicle = '".$truck."',
							cartons = '".$cc."',
							created_by = '".$_SESSION['client_id']."',
							created_on = 'VendorPanel',
							created_datetime = '".$date_create."'";
		$res_ins = mysql_query($ins_query);
	}
	
    //$res1 = mysql_query($query1);// or die(mysql_error());
    header('location:desc_matrix.php?search_date='.$shipping_date);

}
$limit = 500;

$alert_msg = '';
$search_where_in = '';
$get_bay_id = '';

//if(isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != ""){
	$sdate = $search;
	$search_where_vdr = '';
	if($_REQUEST['search_txt1']!="" && is_numeric($_REQUEST['search_txt1'])){
        $search_vdr = $_REQUEST['search_txt1'];
        $search_where_vdr = " AND (vdr.routes LIKE '".$search_vdr."%' OR vdr.vehicle Like '".$search_vdr."%')";
    }
	$s = "SELECT vd.bay_id FROM `vendor_distribution` AS `vd` LEFT JOIN `vendor_distribution_routes` AS `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code WHERE vd.distribution_date = '".$sdate."' $search_where_vdr GROUP BY bay_id";
	//echo $s;
	$sq = mysql_query($s);
	if(mysql_num_rows($sq) == 0){
		$search_where_in = " AND vendor_bays_id=0";
	}
	if(mysql_num_rows($sq) > 0){
		while($sf = mysql_fetch_array($sq)){
			$get_bay_id .= $sf['bay_id'];
			$get_bay_id .= ',';
		}
		$lastSpacePosition = strrpos($get_bay_id,",");
		$textWithoutLastWord =substr($get_bay_id,0,$lastSpacePosition);
		
		$search_where_in = " AND vendor_bays_id IN ($textWithoutLastWord)";
	}
	
	//if(strtotime($sdate) > strtotime(date('Y-m-d')) && mysql_num_rows($sq) == 0)
	if(mysql_num_rows($sq) == 0)
	{
		$alert_msg = '1';
	}
//}

if(isset($_REQUEST['search_txt1'])){
    $limit = 500;
    if($_REQUEST['search_txt1']!="" && !is_numeric($_REQUEST['search_txt1'])){
        $search2 = $_REQUEST['search_txt1'];
        $search_where = " AND (vb.bay_name LIKE '".$search2."%' OR vd.captain Like '".$search2."%' OR vd.puller Like '".$search2."%')";
    }
}
$sql = "SELECT vb.* FROM vendor_bays vb 
JOIN vendor_distribution vd ON vb.vendor_bays_id = vd.bay_id 
WHERE 1=1 $search_where $search_where_in GROUP BY vb.vendor_bays_id  LIMIT $limit"; //COMEBACKHERE TO REMOVE UNUSED VENDOR BAYS
//echo $sql;
if($_REQUEST['debug'] == '1'){
    echo '$sql : '. $sql;
}
$resultJobs = mysql_query($sql) or die(mysql_error());
$resultRows = mysql_num_rows($resultJobs);

if(isset($_GET['action']) && $_GET['action'] == 'updateTimeout'){
	
	$time = mysql_real_escape_string($_REQUEST['time']);
	$time_out = date('H:i');
	$out_hour_minute=explode(":",$time_out);	
    $time=((int)$out_hour_minute[0]*3600)+((int)$out_hour_minute[1]*60);
	
	$id = mysql_real_escape_string($_REQUEST['id']);
	$q = mysql_query("UPDATE `vendor_distribution_routes` SET `time_out` = '".$time."-2',`last_on` = 'VendorPanel',`last_datetime` = '".date('Y-m-d h:i:s')."' WHERE `vendor_distribution_id` = '".$id."'");
	
	if($q){
		$hours = floor($time / 3600);
		$hours = (strlen($hours) == 1) ? '0'.$hours : $hours;
		$minutes = floor(($time / 60) % 60);
		$minutes = (strlen($minutes) == 1) ? '0'.$minutes : $minutes;
		
		echo $hours.':'.$minutes;
	}
	exit;
}

if(isset($_GET['action']) && $_GET['action'] == 'updateTimeout2'){
	
	$time_route = date('H:i');
	$route_hour_minute=explode(":",$time_route);	
    $time=((int)$route_hour_minute[0]*3600)+((int)$route_hour_minute[1]*60);
	
	$id = mysql_real_escape_string($_REQUEST['id']);
	$q = mysql_query("UPDATE `vendor_distribution_routes` SET `route_time` = '".$time."-2',`last_on` = 'VendorPanel',`last_datetime` = '".date('Y-m-d h:i:s')."' WHERE `vendor_distribution_id` = '".$id."'");
	
	if($q){
		$hours = floor($time / 3600);
		$hours = (strlen($hours) == 1) ? '0'.$hours : $hours;
		$minutes = floor(($time / 60) % 60);
		$minutes = (strlen($minutes) == 1) ? '0'.$minutes : $minutes;		
		echo $hours.':'.$minutes;
	}
	exit;
}

if(isset($_GET['action']) && $_GET['action'] == 'getPuller'){
	$bay_id = mysql_real_escape_string($_REQUEST['bay_id']);
	$qry = mysql_query("SELECT puller FROM vendor_distribution WHERE bay_id = '".$bay_id."'");
	$option = array();
	while($fet = mysql_fetch_array($qry)){
		$option[] ="<option value='".$fet['puller']."'>".$fet['puller']."</option>";
	}
	echo $option;
	exit;
}

if(isset($_GET['search_date'])){
	if(strtotime($_GET['search_date']) > strtotime(date('Y-m-d'))){
		$disabled = 'disabled';
	}
}

if(isset($_GET['action']) && $_GET['action'] == 'copyMatrix'){
	$date = mysql_real_escape_string($_GET['date']);
	$distribution_date = mysql_real_escape_string($_GET['distribution_date']);
	//$date = date('Y-m-d', strtotime('-1 day', strtotime($distribution_date)));
	
	$sdate = mysql_real_escape_string($_REQUEST['search_date']);
	$s = "SELECT * FROM `vendor_distribution` AS `vd` LEFT JOIN `vendor_distribution_routes` AS `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code WHERE vd.distribution_date = '".$date."'";
	//echo $s.'   '.$distribution_date; die;
	$sq = mysql_query($s);
	if(mysql_num_rows($sq) > 0){
		while($f = mysql_fetch_array($sq)){
			$ins_query2 = "INSERT INTO vendor_distribution SET
							bay_id = '".$f['bay_id']."',
							captain = '".$f['captain']."',
							distribution_date = '".$distribution_date."',
							vendor_id = '".$f['vendor_id']."',
							puller = '".$f['puller']."',
							created_by = '".$_SESSION['client_id']."',
							created_on = 'VendorPanel',
							created_datetime = '".date('Y-m-d H:i:s')."'";
			$res_ins2 = mysql_query($ins_query2);
			if($res_ins2) {
				$vendor_distribution_id = mysql_insert_id();//die;
			}
			if (!$vendor_distribution_id) {
				die('Could not query:' . mysql_error());
			}
			//$vendor_distribution_id = 1;
            //Previous query, copying everything
//			$ins_query = "INSERT INTO vendor_distribution_routes SET
//								vendor_distribution_id = '".$vendor_distribution_id."',
//								routes = '".$f['routes']."',
//								route_time = '".$f['route_time']."',
//								load_time = '".$f['load_time']."',
//								time_out = '".$f['time_out']."',
//								vehicle = '".$f['vehicle']."',
//								cartons = '".$f['cartons']."',
//								created_by = '".$_SESSION['client_id']."',
//								created_on = 'VendorPanel',
//								created_datetime = '".date('Y-m-d H:i:s')."'";

            $ins_query = "INSERT INTO vendor_distribution_routes SET
								vendor_distribution_id = '".$vendor_distribution_id."',
								routes = '".$f['routes']."',
								vehicle = '".$f['vehicle']."',
								cartons = '".$f['cartons']."',
								created_by = '".$_SESSION['client_id']."',
								created_on = 'VendorPanel',
								created_datetime = '".date('Y-m-d H:i:s')."'";
			$res_ins = mysql_query($ins_query);
			}
		
	}
	echo 1;
	exit;
}

if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'deleteBay'){
	$id = mysql_real_escape_string($_REQUEST['delete_id']);
	mysql_query("DELETE FROM vendor_distribution WHERE vendors_distribution_id = '".$id."'");
	mysql_query("DELETE FROM vendor_distribution_routes WHERE vendor_distribution_id = '".$id."'");
	echo "1";
	exit;
}
if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'updateccValue'){
	$cc = $_REQUEST['cc'];
	$id = $_REQUEST['id'];
	mysql_query("update vendor_distribution_routes set cartons='".$cc."' where vendor_distribution_id = '".$id."'");
	echo "1";
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SoftPoint | VendorPanel</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.default.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" type="text/css" />
    <link rel="stylesheet" href="css/responsive-tables.css" />
    <link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="js/modernizr.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
    <link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
    <script type="text/javascript" src="js/jquery.jgrowl.js"></script>
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
    <!--<script type="text/javascript" src="js/jquery.blockUI.js"></script>-->
    <script type="text/javascript" src="js/responsive-tables.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
    <!--<script type="text/javascript" src="js/main.js"></script>-->
	
	<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
	<!--<script type="text/javascript" src="js/main.js"></script>-->
	<script type="text/javascript" src="js/jquery.timepicker.js"></script>
	<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
	
	
	
    <style>
        body {
            top:0px!important;
        }
        .goog-te-banner-frame{  margin-top: -50px!important; }
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
        table.table tbody tr.selected, table.table tfoot tr.selected {
            background-color: #808080;
        }
        .dataTables_filter input{ height:28px !important;}
        .dataTables_filter {
            top: 5px;
        }
		/*.topbar{ margin-left:0px !important; }*/
		
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
		
		
		
		.color-box span {
		  /*border: 1px solid rgb(204, 204, 204);
		  padding: 2px;
		  margin: 0px;
		  margin-right: 10px;
		  border-radius: 3px;*/
		  
		  padding-left: 20px;
		}
		/*.color-box {
		  border: 1px #aaa solid;
		  padding: 6px;
		  padding-right: 100px;
		  border-radius: 3px;
		  -moz-box-shadow: inset 0 0 2px 0 #888;
		  -webkit-box-shadow: inset 0 0 2px 0 #888;
		  box-shadow: inset 0 0 1px 0 #888;
		}*/
		.gray-box{
			background-color:#cecece;
		}
		.yellow-box {
		  background-color: #eaf55e;
		}
		.orange-box {
		  background-color: #ffb440;
		}
		.blue-box {
		  background-color: #0000ff;
		}
		.green-box {
		  background-color: #4caf50;
		}
		
		.tooltip-inner {
			min-width: 240px; /* the minimum width */
		}
		

		
    </style>
    <script type="text/javascript">
        function displayNewDriverInput() {
            if(jQuery('#driver :selected').text() == '- - - Add New Driver - - -') {
                jQuery('#newDriver').show();
                jQuery('#driver_chzn').hide();
            } else {
                jQuery('#newDriver').hide();
                jQuery('#driver_chzn').show();
            }
            if(jQuery('#driver2 :selected').text() == '- - - Add New Driver - - -') {
                jQuery('#newDriver2').show();
                jQuery('#driver2_chzn').hide();
            } else {
                jQuery('#driver2').show();
                jQuery('#driver2_chzn').hide();
            }
        }

        function displaySelectDriver() {
            if(jQuery('#newDriver').val() == '') {
                jQuery('#driver_chzn').show();
                jQuery('#newDriver').hide();
            }
            if(jQuery('#newDriver2').val() == '') {
                jQuery('#driver2_chzn').show();
                jQuery('#newDriver2').hide();
            }
        }

        jQuery(document).ready(function ($) {
			
			
			
			jQuery("[rel=tooltip]").tooltip({
				html:true,
				placement:'left',
			});
			
			jQuery('.check_done').click(function(){
				
				console.log(jQuery(this).data('check_comp'));
				
			});
			
			/* jQuery('#confirm_date').change(function(){
				console.log(jQuery(this).val());  <input type="text" id="confirm_date">
			}); */
			
			//$("#puller").chosen({ allow_single_deselect: true });
			<?php if($alert_msg == 1){ ?>
			
			jQuery('#confirmModal').modal('show');
			jQuery('#confirm_ok').click(function(){
				var pre_date = jQuery('#confirm_date').val();
				if(pre_date == ''){
					jAlert('Please select previous date!');
					return false;
				}
				jQuery.ajax({
					url: 'desc_matrix.php',
					type: "get",
					data: {action:'copyMatrix',date:pre_date,distribution_date:'<?=isset($_REQUEST['search_date']) ? $_REQUEST['search_date'] : $search;?>'},
					success: function (data) {
						console.log(data);
						if(data){
							location.reload();
						}
					}
				});
				console.log('pre_date : '+pre_date);
				
			});
			
			<?php } ?>
			
			<?php if(isset($_REQUEST['search_txt1'])){ ?>
			
				 $('#back_button').prop('disabled', false);
			
			<?php } ?>
			
			$('#back_button').click(function(){
				window.location='desc_matrix.php';
			});
			
			jQuery('.topbar').css('margin-left','0px');
			
			//jQuery( "#created_datetime" ).timepicker();
			
			
			/* jQuery('.myAddModal').on('hide', function() { 
	
				jQuery('#bay').val('');
				jQuery('#captain').val('');
				jQuery('#puller').val('');
				jQuery('#route').val('');
				jQuery('#truck').val('');
				jQuery('#created_datetime').val('');
				jQuery('#cc').val('');
				
			}); */
			
			jQuery('#search_date').datepicker({ 
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true,
			});
			
			jQuery('#confirm_date').datepicker({ 
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true,
				maxDate: '0',
			});
			
			
			
			jQuery('#shipping_date').datepicker({ 
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true,
			});
			
			/* jQuery('#shipping_date2').datepicker({ 
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true,
			}); */
			
			jQuery("#search_date").change(function(){
				if(jQuery(this).val()!=""){
                    let searchDate = new Date(jQuery(this).val());
                    let limitDate = new Date();
                    limitDate.setDate(new Date(limitDate.valueOf()).getDate()+90);
				    if (limitDate < searchDate){
                        jAlert('The date should not be higher than 90 days.');
                    } else {
                        window.location="desc_matrix.php?&search_date="+jQuery(this).val()+"&search_txt1=<?php echo isset($_GET['search_txt1']) ? $_GET['search_txt1'] : '' ?>";
                    }

				}
			});
			
            jQuery('#ser_go').live('click',function(){
                window.location="desc_matrix.php?&search_txt1="+jQuery('#search_txt1').val()+"&search_date=<?php echo isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>";
                /*var search_inpt = jQuery('#search_txt1').val();
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
                            window.location="desc_matrix.php?&search_txt1="+jQuery('#search_txt1').val();
                        }
                    }else{
                        jAlert(' Enter value to search');
                        return false;
                    }
                    return false;
                } else{
                    jAlert(' Enter value to search');
                    return false;
                }*/

            });

            jQuery('#global_tbl').dataTable({
                "sPaginationType": "full_numbers",
                "aaSorting": [[2, "asc"]],
                "bJQuery": true,
                "fnDrawCallback": function (oSettings) {
                    //  jQuery.uniform.update();
                }
            });

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

            jQuery('.leftpanel').css({'margin-left': '-260px'});
            jQuery('.rightpanel').css({'margin-left': '0px'});
            jQuery('.topbar').show();
            jQuery('.topbar').css('background','#272727');
            jQuery('.barmenu').css({'font-size': '18px','color': '#fff','background': 'url(../adminpanel/images/barmenu.png) no-repeat center center','width': '50px','height': '50px','display': 'block','cursor': 'pointer'});

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
    <script type="text/javascript">
		var check_captain = 0;
		var check_puller = 0;
		var check_captain2 = 0;
		var check_puller2 = 0;
        jQuery(document).ready(function ($) {

			
			jQuery("#captain").keyup(function(e){
				console.log(jQuery(this).val());
				check_captain = 1;
			});
			
			jQuery("#puller").keyup(function(e){
				console.log(jQuery(this).val());
				check_puller = 1;
			});
			
			$("#bay").change(function(){
				console.log($(this).find(':selected').data('captain'));
				console.log($(this).find(':selected').data('puller'));
				$('#captain').val($(this).find(':selected').data('captain'));
				$('#puller').val($(this).find(':selected').data('puller'));
				
				console.log('captain : '+$('#captain').val().length);
				if($('#captain').val().length > 0){
					check_captain2 = 1;
				}
				if($('#puller').val().length > 0){
					check_puller2 = 1;
				}
			});
			
			
			
			
			
			
			console.log('check_puller : '+check_puller);
            jQuery("#sub_form").click(function(e){
                if(jQuery('#driver :selected').text() != '- - - Add New Driver - - -') {
                    jQuery('#newDriver').val(jQuery('#driver2 :selected').val());
                }
                if(jQuery('#bay').val()==""){
                    jAlert('Please Select Bay!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#captain').val()==""){
                    jAlert('Please Enter Captain!','Alert Dialog');
                    e.preventDefault();
                }else if((jQuery('#load_time_minutes').val() > 59 || jQuery('#load_time_minutes').val() < 0) && jQuery('#load_time_minutes').val() != ''){
                    jAlert('Minutes should be between 0 and 59','Alert Dialog');
                    e.preventDefault();
                }else if((jQuery('#load_time_hours').val() > 23 || jQuery('#load_time_hours').val() < 0) && jQuery('#load_time_hours').val() != ''){
                    jAlert('Hours should be between 0 and 23','Alert Dialog');
                    e.preventDefault();
                } else if(jQuery('#puller').val()==""){
                    jAlert('Please enter Puller!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#puller').val()==""){
                    jAlert('Please enter Puller!','Alert Dialog');
                    e.preventDefault();
                }/* else if(jQuery('#route').val()==""){
                    jAlert('Please Select Route!','Alert Dialog');
                    e.preventDefault();
                } */else if(jQuery('#truck').val()==""){
                    jAlert('Please Select Truck!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#cc').val()==""){
                    jAlert('Please Enter CC!','Alert Dialog');
                    e.preventDefault();
                }else if( (check_captain === 1 && check_captain2 === 1) || (check_puller === 1 && check_puller2 === 1) ){
					jConfirm('Do you want to change Captain and puller for this bay?', 'Confirm!', function(r) {
						if(r){
							jQuery('#confirm_captain').val('yes');
							jQuery('#confirm_puller').val('yes');
							jQuery('#add_from_modal').submit();
						}else{
							jQuery('#add_from_modal').submit();
						}
					});
				}else{
					console.log('checkcheck : '+check_captain);
					jQuery('#add_from_modal').submit();
				}
				
				console.log('check_captain  '+check_captain);
				console.log('check_puller  '+check_puller);
				console.log('check_captain2  '+check_captain2);
				console.log('check_puller2  '+check_puller2);
                
				check_captain = 0;
				check_puller = 0;
				//check_captain2 = 0;
				//check_puller2 = 0;
				console.log('check : '+check_captain);
            });
			
			jQuery("#captain2").keyup(function(e){
				console.log(jQuery(this).val());
				check_captain = 1;
			});
			
			jQuery("#puller2").keyup(function(e){
				console.log(jQuery(this).val());
				check_puller = 1;
			});
			
			jQuery("#sub_form2").click(function(e){
                if(jQuery('#driver2 :selected').text() != '- - - Add New Driver - - -') {
                    jQuery('#newDriver2').val(jQuery('#driver2 :selected').val());
                }
                if(jQuery('#bay2').val()==""){
                    jAlert('Please Select Bay!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#captain2').val()==""){
                    jAlert('Please Enter Captain!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#puller2').val()==""){
                    jAlert('Please enter Puller!','Alert Dialog');
                    e.preventDefault();
                }else if(jQuery('#truck2').val()==""){
                    jAlert('Please Select Truck!','Alert Dialog');
                    e.preventDefault();
                } else if((jQuery('#load_time_minutes2').val() > 59 || jQuery('#load_time_minutes2').val() < 0) && jQuery('#load_time_minutes2') != ''){
                    jAlert('Minutes should be between 0 and 59','Alert Dialog');
                    e.preventDefault();
                }else if((jQuery('#load_time_hours2').val() > 23 || jQuery('#load_time_hours2').val() < 0) && jQuery('#load_time_hours2').val() != ''){
                    jAlert('Hours should be between 0 and 23','Alert Dialog');
                    e.preventDefault();
                } else if(jQuery('#cc2').val()==""){
                    jAlert('Please Enter CC!','Alert Dialog');
                    e.preventDefault();
                }else if( check_captain === 1 || check_puller === 1){
					jConfirm('Do you want to change Captain and puller for this bay?', 'Confirm!', function(r) {
						if(r){
							jQuery('#confirm_captain').val('yes');
							jQuery('#confirm_puller').val('yes');
							jQuery('#edit_from_modal').submit();
						}else{
							jQuery('#edit_from_modal').submit();
						}
					});
				}else{
					jQuery('#edit_from_modal').submit();
				}
                
				check_captain = 0;
				check_puller = 0;
				
				
				console.log('check : '+check_captain);
            });
			
			jQuery("#delete_matrix").click(function(e){
				var search_date = jQuery(this).data('search_date');
				var insert_date = jQuery(this).data('insert_date');
				var delete_id = jQuery(this).data('delete_id');
				
				if(search_date != insert_date){
					jAlert('You can only delete today bay route!','Alert Dialog');
					return false
				}
				
				jConfirm('Are you sure you want to delete this bay route?', 'Confirm!', function(r) {
					if(r){
						jQuery.ajax({
							type: "GET",
							url: 'desc_matrix.php',
							data: ({action 	: 'deleteBay',delete_id:delete_id}),
							success: function(data){
								if(data){
									window.location.reload();
								}
							}
						});
					}
				});
				
				console.log('search_date : '+search_date);
				console.log('insert_date : '+insert_date);
				console.log('delete_id : '+delete_id);
			});
			
			
			jQuery('.get_hour_min').click(function(){
				
				console.log('Hello');
				var result = jQuery('#route_time2').val().split(':');
				
				jQuery('.timepicker-hour').html(result[0]);
				jQuery('.timepicker-minute').html(result[1]);
				
			}); 
			
			jQuery('.get_hour_min_timeout').click(function(){
				
				console.log('Hello');
				var result = jQuery('#time_out2').val().split(':');
				
				jQuery('.timepicker-hour').html(result[0]);
				jQuery('.timepicker-minute').html(result[1]);
				
			});
			
			/* jQuery(".updateNewcc").keyup(function (e) {
  
			  if (e.keyCode == 13) {
			   console.log("put function call here");
			  }
			  console.log('HELLO');
			}); */
			
        });
		
		function updatecc(id,check,cc){
			
			if(check == 1){
				jAlert("This Bay is completed and can't be edited","Alert Dialog");
				return false;
			}
            if(check == 2){
                jAlert("This Bay already passed the shipping date","Alert Dialog");
                return false;
            }
			if(typeof(cc) == 'undefined'){
				cc = 0;
			}
 
			jQuery('.cc_class_'+id).html('<input type="textbox" name="updateNewcc" id="updateNewcc" onKeyPress="return isNumberKey(event,'+id+')" value="'+cc+'" style="width:30px">');
			//console.log('cc: '+cc);
			
		}
		
		function isNumberKey(e,id){
			//console.log("id : "+id);
			if(e.which == 46){
				if(jQuery(this).val().indexOf('.') != -1) {
					return false;
				}
			}
			
			if (e.which != 13 && e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
				return false;
			}
			
			var edValue = document.getElementById("updateNewcc");
			var cc = edValue.value;
			
			if (e.keyCode == 13) {
				jQuery.ajax({
					type: "GET",
					url: 'desc_matrix.php',
					data: ({action 	: 'updateccValue',cc : cc,id:id}),
					success: function(data){
						console.log(data);
						jQuery('.cc_class_'+id).html(cc);
						window.location.reload();
					}
				});
			}	
			
		}
		
		
		
		function openEditModal(id,check){
			
			if(check == 1){
				jAlert("This Bay is completed and can't be edited","Alert Dialog");
				return false;
			}
            if(check == 2){
                jAlert("This Bay already passed the shipping date","Alert Dialog");
                return false;
            }
			
			console.log('vehicle : '+jQuery('#getEditValue_'+id).data('vehicle'));
			jQuery('#route2').val(jQuery('#getEditValue_'+id).data('route_code'));
			jQuery('#truck2').val(jQuery('#getEditValue_'+id).data('vehicle'));
			jQuery('#cc2').val(jQuery('#getEditValue_'+id).data('cartons'));
			jQuery('#route_time2').val(jQuery('#getEditValue_'+id).data('hours')+':'+jQuery('#getEditValue_'+id).data('minutes'));
			//jQuery('#route_time_minutes2').val(jQuery('#getEditValue_'+id).data('minutes'));
			jQuery('#load_time_hours2').val(jQuery('#getEditValue_'+id).data('load_hours'));
			jQuery('#load_time_minutes2').val(jQuery('#getEditValue_'+id).data('load_minutes'));
			jQuery('#time_out2').val(jQuery('#getEditValue_'+id).data('out_hours')+':'+jQuery('#getEditValue_'+id).data('out_minutes'));
			
			jQuery('#bay2').val(jQuery('#getEditValue_'+id).data('bay_name'));
			jQuery('#captain2').val(jQuery('#getEditValue_'+id).data('captain'));
			jQuery('#puller2').val(jQuery('#getEditValue_'+id).data('puller'));
            jQuery('#driver2').val(jQuery('#getEditValue_'+id).data('driver'));
			
			jQuery('#driver2').trigger("liszt:updated");

            jQuery('#shipping_date2').val(jQuery('#getEditValue_'+id).data('distribution_date'));
			
			jQuery('#created_by2').val(jQuery('#getEditValue_'+id).data('created_by'));
			jQuery('#created_on2').val(jQuery('#getEditValue_'+id).data('created_on'));
			jQuery('#created_datetime2').val(jQuery('#getEditValue_'+id).data('created_datetime'));
			jQuery('#last_on2').val(jQuery('#getEditValue_'+id).data('last_on'));
			jQuery('#last_by2').val(jQuery('#getEditValue_'+id).data('last_by'));
			jQuery('#last_datetime2').val(jQuery('#getEditValue_'+id).data('last_datetime'));
			
			console.log('shipping_date2 : '+jQuery('#shipping_date2').val());
			console.log('search_date : '+jQuery('#getEditValue_'+id).data('search_date'));
			
			if(jQuery('#getEditValue_'+id).data('search_date') < jQuery('#shipping_date2').val()){
				jQuery('#route_time2').val('');
				jQuery('#time_out2').val('');
				jQuery('#cc2').val('');
			}
			
			jQuery('#edit_id').val(id);
			
			if(jQuery('#shipping_date2').val() == jQuery('#getEditValue_'+id).data('search_date')){
				jQuery('#bay2').removeAttr("disabled");
			}
			
			jQuery('#delete_matrix').attr('data-search_date', jQuery('#getEditValue_'+id).data('search_date'));
			jQuery('#delete_matrix').attr('data-insert_date', jQuery('#shipping_date2').val());
			jQuery('#delete_matrix').attr('data-delete_id', id);
			
			jQuery('#editModal').modal('show');
		}
		
		function updateTimeout(time,id){
			jQuery.ajax({
				type: "GET",
				url: 'desc_matrix.php',
				data: ({action 	: 'updateTimeout',time : time,id:id}),
				success: function(data){
					console.log(data);
					jQuery("clock_hide_"+id).css("display","none");
					jQuery('.change_'+id).html(data);
					
				}
			});
			
		}
		function updateTimeout2(id){
			jQuery.ajax({
				type: "GET",
				url: 'desc_matrix.php',
				data: ({action 	: 'updateTimeout2',id:id}),
				success: function(data){
					console.log(data);
					jQuery("clock2_hide_"+id).css("display","none");
					jQuery('.change2_'+id).html(data);
					
				}
			});
			
		}
		

    </script>
    <style>
        /*.ui-datepicker{ z-index: 1100 !important; }*/

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
            text-align:center !important;
        }
        .btn-large{
            margin-left: 3px;
        }
        .modal-body{
            max-height: 521px!important;
        }
        #add_from_modal label{
            float: left;
        }
		#edit_from_modal label{
            float: left;
        }
        table tr td:first-child{
            width: 21%;
        }
        table tr td select{
            width: 100%;
            margin-bottom: 10px;
        }
        .bootstrap-datetimepicker-widget{
            width: 50px;
        }
        .table th{
            font-size: 9px!important;
        }
        .table{
            margin-bottom: 0!important;
        }
		.matrix_head{ font-size:0.7rem !important; padding:0px !important; text-align:center;}
		.clock{ float:right !important; margin-top: 2px !important; }
    </style>
</head>
<!--head-->

<body>
<div class="mainwrapper">
    <?php require_once('require/top_matrix.php'); ?>
    <?php require_once('require/left_nav.php'); 
	$cols = "colspan='7'";
	?>
    <div class="rightpanel">
        <ul class="breadcrumbs">
            <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Distribution</li>
            <li><span class="separator"></span> Matrix </li>
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
            <div style="float: right;margin-top: 20px;" class="messagehead">
				<p style="float:left;margin-right:10px;margin-top: 10px;">Shipping Date: </p>
                <p style="float:left;">
                <span><input type="text" placeholder="Search" value="<?php echo $_REQUEST['search_txt1']; ?>" name="search_txt1" id="search_txt1" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;"></span>
				<span><input type="text" value="<?php echo isset($_REQUEST['search_date']) ? $_REQUEST['search_date'] : $search; ?>" name="search_date" id="search_date" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;"></span>
                <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
                </p>
                <button id="ser_go" style="float:left" class="btn btn-primary btn-large">Go</button>

                <!-- Button trigger modal -->
				<input type="submit" id="back_button" style="" class="btn btn-large <?php echo isset($_REQUEST['search_txt1']) ? 'btn-primary' : '' ?>" value="Back" disabled>
                <button type="button" class="btn btn-success btn-large" data-toggle="modal" data-target="#addModal">
                    Add
                </button>
				<button id="show_legend" class="btn btn-large" rel='tooltip'
                        data-original-title='<table style="">
                        <tr>
                            <td class="color-box"><span class="gray-box"></span></td>
                            <td style="text-align: left;">Nothing Loaded</td>
                        </tr>
                        <tr>
                            <td class="color-box"><span class="yellow-box"></span></td>
                            <td style="text-align: left;">Some routes Loaded</td>
                        </tr>
                        <tr>
                            <td class="color-box"><span class="orange-box"></span></td>
                            <td style="text-align: left;">All Routes loaded</td>
                        </tr>
                        <tr>
                            <td class="color-box"><span class="blue-box"></span></td>
                            <td style="text-align: left;">All Routes loaded and some time out</td>
                        </tr>
                        <tr><td class="color-box"><span class="green-box"></span></td>
                        <td style="text-align: left;">All loaded and All time out</td></tr></table>'>Legend</button>
				
                <!-- Modal -->
				<!--<table><tr><td class="color-box" width="35%"><span class="gray-box"></span></td><td>Nothing Loaded</td></tr><tr><td class="color-box" width="38%"><span class="yellow-box"></span></td><td>Some routes Loaded</td></tr><tr><td class="color-box" width="35%"><span class="orange-box"></span></td><td>All Routes loaded</td></tr><tr><td class="color-box" width="35%"><span class="blue-box"></span></td><td>All Routes loaded and some time out</td></tr><tr><td class="color-box" width="35%"><span class="green-box"></span></td><td>All loaded and All time out</td></tr></table>-->
                
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function(event){
                    jQuery('#routTimePicker').datetimepicker({
                        timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,
                    });
                    jQuery('#route_time').keyup(parseTime);
                    jQuery('#route_time2').keyup(parseTime);
                    jQuery('#time_out').keyup(parseTime);
                    jQuery('#time_out2').keyup(parseTime);

                    function parseTime(event) {
                        let input = jQuery(event.target);
                        let time = input.val();
                        if (time.length === 4 && !time.includes(":")) {
                            var hours = time.substr(0, 2);
                            var minutes = time.substr(2);
                            input.val(`${hours}:${minutes}`);
                            console.log(input.val());
                        }
                    }
					/* jQuery( "#route_time2" ).timepicker({
						
						timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,
						
					}); */
					
					jQuery('#routTimePicker2').datetimepicker({
                        timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,
                    });
					
					jQuery('#loadTimePicker').datetimepicker({
                        timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,

                    });
					
					jQuery('#timeOutPicker').datetimepicker({
                        timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,

                    });
					
					jQuery('#timeOutPicker2').datetimepicker({
                        timeFormat: 'hh:mm',
                        pickSeconds: false,
                        pickDate: false,

                    });
					
					
                });
            </script>
            <div class="pageicon"><span class="iconfa-truck"></span></div>
            <div class="pagetitle">
                <h5>The Following are Matrix</h5>
                <h1>Matrix</h1>
            </div>

        </div>
        <!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner" >
                <div class="row row-fluid" id="#!" style="margin-left: 11px;">

                    <?php
					
                    if($resultRows > 0){
                    $r = 1;$g = 1;
                while($row = mysql_fetch_array($resultJobs)){ //COMEBACKHERE    
					
					$vendor_distribution_query2 = mysql_query(
						'SELECT
							SUM(vdr.route_time) as route_time,
							SUM(vdr.load_time) as load_time,
							vdr.time_out as time_out,
							SUM(vdr.cartons) as cartons
						FROM
							`vendor_distribution` as `vd`
						LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
						WHERE
						`bay_id` = '.$row['vendor_bays_id'].' '.$search_date);
					 while($vendor_distribution_rows = mysql_fetch_assoc($vendor_distribution_query2)) {
						 $route_time = $vendor_distribution_rows['route_time'];
						 $load_time = $vendor_distribution_rows['load_time'];
						 $time_out = $vendor_distribution_rows['time_out'];
						 $time_out = explode('-',$time_out);
						 $time_out = $time_out['0'];
						 $cartons = $vendor_distribution_rows['cartons'];
					 }
					
					$cp_qry = mysql_query("SELECT vd.puller,vd.captain FROM vendor_bays vb JOIN vendor_distribution vd ON vd.bay_id = vb.vendor_bays_id  WHERE vd.bay_id = '".$row['vendor_bays_id']."' " . $search_date);
					$cp_fetch = mysql_fetch_array($cp_qry);
                    if($g%3==1){$g=1;}
                    if($g <> 1){$marginClass = " margin-left:30px;";}else{$marginClass = "";}
                    $vendor_distribution_query = mysql_query("SELECT vd.distribution_date,vd.captain,vd.puller,vd.driver ,vdr.vendor_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, vdr.route_time, vdr.load_time, vdr.time_out, vdr.cartons,
							vdr.created_by,vdr.created_on,vdr.created_datetime,vdr.last_on,vdr.last_by,vdr.last_datetime
                            FROM `vendor_distribution` as `vd`
                            LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
							LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code
                            WHERE `bay_id` = '".$row['vendor_bays_id']."' " . $search_date . " ORDER BY vdr.vendor_distribution_routes_id ASC ");
						  
					
					if(isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != ""){
						$vendor_distribution_query = mysql_query("SELECT vd.distribution_date,vd.captain,vd.puller,vd.driver ,vd.vendors_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, vdr.route_time, vdr.load_time, vdr.time_out, vdr.cartons,
						vdr.created_by,vdr.created_on,vdr.created_datetime,vdr.last_on,vdr.last_by,vdr.last_datetime
                        FROM `vendor_distribution` as `vd`
                        LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
						LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code
                        WHERE `bay_id` = '".$row['vendor_bays_id']."' ".$search_date." ORDER BY vdr.vendor_distribution_routes_id ASC ");
						/* echo "SELECT vd.vendors_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, vdr.route_time, vdr.load_time, vdr.time_out, vdr.cartons
                        FROM `vendor_distribution` as `vd`
                        LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
						LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code
                        WHERE `bay_id` = '".$row['vendor_bays_id']."' ".$search_date." ORDER BY vdr.vendor_distribution_routes_id ASC "; */
                        
						/* if(mysql_num_rows($vendor_distribution_query) <= 0){
								
							$search = date('Y-m-d', strtotime('-1 day', strtotime($search)));
							//$search2 =  mysql_real_escape_string($_REQUEST['search_date']).' 15:59:59';
							$search_date = " AND vd.distribution_date = '".$search."'";
							$vendor_distribution_query = mysql_query("SELECT vd.distribution_date,vd.captain,vd.puller,vd.vendors_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, '' as route_time, '' as load_time, '' as time_out, '0' as cartons
							FROM `vendor_distribution` as `vd`
							LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
							LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code
							WHERE `bay_id` = '".$row['vendor_bays_id']."' ".$search_date." ORDER BY vdr.vendor_distribution_routes_id ASC ");
								
							$cartons = '0'; 
						} */
					}  

                    ?>
					
					<?php
						$out_qry2 = mysql_query('SELECT time_out,puller FROM `vendor_distribution` as `vd`
							LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
							WHERE vd.bay_id = '.$row['vendor_bays_id'].' AND vd.puller != "" ' . $search_date . ' GROUP BY vd.puller ORDER BY vdr.vendor_distribution_routes_id DESC');
							
						$out_qry = mysql_query('SELECT time_out,puller FROM `vendor_distribution` as `vd`
							LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
							WHERE `bay_id` = '.$row['vendor_bays_id'].' ' . $search_date . ' ORDER BY vdr.vendor_distribution_routes_id DESC');
							
						$last_out_time = mysql_fetch_array($out_qry);
						
						$out_hours = floor($last_out_time['time_out'] / 3600);
						$out_hours = (strlen($out_hours) == 1) ? '0'.$out_hours : $out_hours;
						$out_minutes = floor(($last_out_time['time_out'] / 60) % 60);
						$out_minutes = (strlen($out_minutes) == 1) ? '0'.$out_minutes : $out_minutes;
						
						
						if ($load_time != 0) {
                            $load_hours = floor($load_time / 3600);
                            $load_hours = (strlen($load_hours) == 1) ? '0' . $load_hours : $load_hours;
                            $load_minutes = floor(($load_time / 60) % 60);
                            $load_minutes = (strlen($load_minutes) == 1) ? '0' . $load_minutes : $load_minutes;
                        }
						
						$hours = floor($route_time / 3600);
						$hours = (strlen($hours) == 1) ? '0'.$hours : $hours;
						$minutes = floor(($route_time / 60) % 60);
						$minutes = (strlen($minutes) == 1) ? '0'.$minutes : $minutes;
						
					?>
					<?php
						$bgcolor_query = "SELECT vdr.route_time,vdr.time_out FROM  
						`vendor_distribution` as `vd` 
						LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id 
						WHERE `bay_id` = '".$row['vendor_bays_id']."' " . $search_date;
						
						$route_load = 0;
						$time_out = 0;
						$bgcolor_exe = mysql_query($bgcolor_query);
						$route_time_background = "background:#cecece;";$route_time_border = "border: 2px solid #cecece;";
						
						while($bgcolor_fetch = mysql_fetch_array($bgcolor_exe)){
							$check = strpos($bgcolor_fetch['route_time'],"-2");
							if($check){
								$route_load++;
								$route_time_background = "background:#eaf55e;";$route_time_border = "border: 2px solid #eaf55e;";
							}
							$check2 = strpos($bgcolor_fetch['time_out'],"-2");
							if($check2){
								$time_out++;
							}
						}
						$allowed = '';
						if(mysql_num_rows($bgcolor_exe) === $route_load){
							$route_time_background = "background:#ffb440;"; $route_time_border = "border: 2px solid #ffb440;";
                            $allowed = '2';
                        }
						if(mysql_num_rows($bgcolor_exe) === $route_load && $time_out > 0){
							$route_time_background = "background:blue;";$route_time_border = "border: 2px solid blue;";
                            $allowed = '2';
                        }
						if(mysql_num_rows($bgcolor_exe) === $route_load && mysql_num_rows($bgcolor_exe) === $time_out){
							$route_time_background = "background:#5cab34;";$route_time_border = "border: 2px solid #5cab34;";
							$allowed = '1';
						}
						if(mysql_num_rows($bgcolor_exe) === 0){
							$route_time_background = "";$route_time_border = "";
                        }
						
						$style = '';
						$backcolor = '';
						$check_comp = '0';
						if(isset($_GET['search_date'])){
							if(strtotime($search) < strtotime(date('Y-m-d')) && ($allowed == '1' || $allowed == '2')){
								$style = 'pointer-events: none;';
								$backcolor = 'background-color : #d8d8d8';
								$check_comp = $allowed;
							}
						}
					?>

                    <div class="span3" style="width:24.1%; /*float:left;*/ <?php // echo $marginClass;?>  overflow:auto;">
                        <div class="clearfix1">
                            <h4 class="widgettitle" style="<?php echo $route_time_background ?>"><?php  echo $row["bay_name"];?></h4>
                        </div>
                        <div class="widgetcontent" style="max-height: 417px;height: 417px;overflow-x:hidden;<?=$route_time_border?>">
                            <div class="load table-wrapper-scroll-y ">
                                <!--List items-->
                                <div id="inv_table">
                                    <table id="itm_tbl" class="table table-bordered table-infinite">
                                        <colgroup>
                                            <col class="con0" style=""/>
                                        </colgroup>
                                        <thead>
<!--                                        <tr>-->
<!--                                            <th class="head1"></th>-->
<!--                                            <th class="head2"></th>-->
<!--                                        </tr>-->
                                        </thead>
                                        <tbody>
                                        <tr style="text-align: center;"><td>Captain : </td><td colspan="9"><?php echo '<b>'.$cp_fetch["captain"].'</b>';?></td></tr>
                                        <?php
                                            $i = 0;
                                        ?>
                                        <tr style="text-align: center; <?=$backcolor?>"><td>Puller : </td>
                                            <td colspan="9"><?php $comma = (mysql_num_rows($out_qry2) == '1') ? '' : ','; while($f=mysql_fetch_assoc($out_qry2)){ ?>
                                                    <?php echo '<b>'.$f["puller"].$comma.' </b>';?><?php
                                            $i++;
                                            $comma = (mysql_num_rows($out_qry2) - 1 == $i) ? '' : ',';
                                            } ?>
                                        </td></tr>
										<td style="font-size: 12px;">Tot Load TM: </td><td><?php echo "<b>$load_hours:$load_minutes</b>";?></td>
										<td style="font-size: 12px;width:27%;">Tot Case Cnt: </td style="width:11%;"><td colspan="3"><?php echo "<b>".$cartons."</b>";?></td>
										<td style="font-size: 12px;">Lst Route Out: </td><td colspan="3"><?php echo "<b>$out_hours:$out_minutes</b>";?></td>
                                        </tbody>
                                    </table>
                                </div>
                                <!--End List Items-->
                                <table id="itm_tbl" class="table table-bordered table-infinite" style="table-layout:fixed;">
                                    <colgroup>
										<col class="con0"  style="width:10%;"/>
										<col class="con1"  style="width:10%"/>
										<col class="con0"  style="width:18%"/>
										<col class="con1"  style="width:15%"/>
										<col class="con0"  style="width:10%"/>
										<col class="con1"  style="width:17%"/>
										<col class="con0"  style="width:10%"/>
									</colgroup>
                                    <thead>
                                    <tr>
                                        <th class="matrix_head center">Route</th>
										<th class="matrix_head center">Load Time</th>
                                        <th class="matrix_head center">Truck</th>
                                        <th class="matrix_head center">Route Loaded</th>
										<th class="matrix_head center">CC</th>
										<th class="matrix_head center">Time Out</th>
										<th class="matrix_head center">A</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while($vendor_distribution_rows = mysql_fetch_assoc($vendor_distribution_query)){	
										$route = explode('-',$vendor_distribution_rows['route_time']);		
										$hours = floor($route['0'] / 3600);
										$hours = (strlen($hours) == 1) ? '0'.$hours : $hours;
                                        $minutes = floor(($route['0'] / 60) % 60);
										$minutes = (strlen($minutes) == 1) ? '0'.$minutes : $minutes;

										if ($vendor_distribution_rows['load_time'] != 0) {
                                            $load_hours = floor($vendor_distribution_rows['load_time'] / 3600);
                                            $load_hours = (strlen($load_hours) == 1) ? '0'.$load_hours : $load_hours;
                                            $load_minutes = floor(($vendor_distribution_rows['load_time'] / 60) % 60);
                                            $load_minutes = (strlen($load_minutes) == 1) ? '0'.$load_minutes : $load_minutes;
                                        }
										
										$out = explode('-',$vendor_distribution_rows['time_out']);	
										$out_hours = floor($out['0'] / 3600);
										$out_hours = (strlen($out_hours) == 1) ? '0'.$out_hours : $out_hours;
                                        $out_minutes = floor(($out['0'] / 60) % 60);
										$out_minutes = (strlen($out_minutes) == 1) ? '0'.$out_minutes : $out_minutes;
										
										$created_user = mysql_fetch_array( mysql_query("select first_name,last_name from employees_master where empmaster_id='".$vendor_distribution_rows['created_by']."'"));
										$last_user = mysql_fetch_array( mysql_query("select first_name,last_name from employees_master where empmaster_id='".$vendor_distribution_rows['last_by']."'"));
									?>
										<tr style="text-align: center;">
											<td><?= $vendor_distribution_rows['route_code'] ?></td>
											<td><?= $load_hours.':'.$load_minutes ?></td>
											<td><?= $vendor_distribution_rows['vehicle'] ?></td>
											<td class="change2_<?=$vendor_distribution_rows['id'];?>">
                                                <?php
                                                    //if (DateTime::createFromFormat('Y-m-d', $vendor_distribution_rows["distribution_date"]) >= DateTime::createFromFormat('Y-m-d', $search_for_disable)) { 
														if($d_none == 1){
													?>
                                                        <?= $hours.':'.$minutes ?><?php if($route['1'] != '2' && $check_comp != 2){ ?> &nbsp <i title="Update route loaded" class="iconfa-time clock clock2_hide_<?=$vendor_distribution_rows['id']?>" onClick="updateTimeout2(<?php echo $vendor_distribution_rows['id']; ?>)"></i><?php }else{ echo ''; } ?>
												<?php } //} ?>
                                            </td>
											<td class="right newClasscc cc_class_<?=$vendor_distribution_rows['id']?>" ondblclick="updatecc(<?= $vendor_distribution_rows['id'] ?>,<?= $check_comp ?>,<?= $vendor_distribution_rows['cartons'] ?>);">
                                                <?php
                                                //if (DateTime::createFromFormat('Y-m-d', $vendor_distribution_rows["distribution_date"]) >= DateTime::createFromFormat('Y-m-d', $search_for_disable)) { 
													if($d_none == 1){
												?>
                                                    <?= $vendor_distribution_rows['cartons'] ?></td>
												<?php } //} ?>
											<td class="change_<?=$vendor_distribution_rows['id'];?>">
                                                <?php
                                                //if (DateTime::createFromFormat('Y-m-d', $vendor_distribution_rows["distribution_date"]) >= DateTime::createFromFormat('Y-m-d', $search_for_disable)) { 
													if($d_none == 1){
												?>
                                                <?= $out_hours.':'.$out_minutes ?><?php if($out['1'] != '2' && $check_comp != 2){ ?> &nbsp <i title="Update timeout" class="iconfa-time clock clock_hide_<?=$vendor_distribution_rows['id']?>" onClick="updateTimeout(<?php echo $vendor_distribution_rows['route_time']; ?>,<?php echo $vendor_distribution_rows['id']; ?>)"></i><?php }else{ echo ''; } ?>
												<?php } //} ?>
                                            </td>
											<td class="center"><img src="images/Edit.png" 
											data-route_code="<?php echo $vendor_distribution_rows['route_code'] ?>" 
											data-cartons="<?php echo $vendor_distribution_rows['cartons'] ?>"
											data-vehicle="<?php echo $vendor_distribution_rows['vehicle'] ?>"
											data-hours="<?php echo $hours ?>"
											data-minutes="<?php echo $minutes ?>"
											data-load_hours="<?php echo $load_hours ?>"
											data-load_minutes="<?php echo $load_minutes ?>"
											data-out_hours="<?php echo $out_hours ?>"
											data-out_minutes="<?php echo $out_minutes ?>"
											data-bay_name="<?php echo $row['vendor_bays_id'] ?>"
											data-captain="<?php echo $vendor_distribution_rows["captain"] ?>"
											data-puller="<?php echo $vendor_distribution_rows["puller"] ?>"
                                            data-driver="<?php echo $vendor_distribution_rows["driver"] ?>"
											data-distribution_date="<?php echo $vendor_distribution_rows["distribution_date"] ?>"
											
											data-created_by="<?php echo $created_user["first_name"].' '.$created_user["last_name"] ?>"
											data-created_on="<?php echo $vendor_distribution_rows["created_on"] ?>"
											data-created_datetime="<?php echo $vendor_distribution_rows["created_datetime"] ?>"
											data-last_on="<?php echo $vendor_distribution_rows["last_on"] ?>"
											data-last_by="<?php echo $last_user["first_name"].' '.$last_user["last_name"] ?>"
											data-last_datetime="<?php echo $vendor_distribution_rows["last_datetime"] ?>"
											data-search_date="<?php echo $search_for_disable ?>"
											onClick="openEditModal(<?= $vendor_distribution_rows['id'] ?>,<?= $check_comp ?>);" id="getEditValue_<?= $vendor_distribution_rows['id'] ?>" title="Edit" style="height:10px;width:10px;"></td>
										</tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <?php if($r%4==0){?> </div><div class="row row-fluid" id="#!" style="margin-left: 11px;"><?php }?>
                    <?php $r++;?>
                    <?php $g++;?>
                    <?php } ?>
                    <?php }else{?>
                        <div class="col-md-4 span8 " style="width:24.1%;float:left; overflow:auto;">
                            <div class="clearfix1">
                                <h4 class="widgettitle">Bay Name</h4>
                            </div>
                            <div class="widgetcontent">
                                <div class="load">
                                    <!--List items-->
                                    <div id="inv_table">
                                        <table id="itm_tbl" class="table table-bordered table-infinite">
                                            <colgroup>
                                                <col class="con0" style=""/>
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th class="head0 center">Details</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr><td>No data available in table</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--End List Items-->
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <!--row-fluid-->

                <?php include_once 'require/footer.php'; ?>
                <!--footer-->

            </div>
            <!--maincontentinner-->
        </div>
        <!--maincontent-->
    </div>
    <!--rightpanel-->

</div>
<!--mainwrapper-->
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

<style>
    .modal-body label { margin-top: 3px; }
    .modal-body td { vertical-align: top; }
    .btn-default{ color: #000 !important; }
    .chzn-container{ width: 283px !important; margin-bottom: 10px; }
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

<?php if (isset($_SESSION['ins_Loc'])) { ?>
    <script>
        jAlert('Record updated.','Alert');
        return false;
        <?php unset($_SESSION['ins_Loc']); ?>
    </script>
<?php } ?>

<script type="text/javascript">
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
		//jQuery("#driver").chosen();
		//jQuery("#driver2").chosen();
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
//                if(myForm.sale_variance.value == ''){
//                    jAlert('Please enter sale variance!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_delivery_type.value == ''){
//                    jAlert('Please enter default delivery type!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_terms.value == ''){
//                    jAlert('Please enter default terms!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_payment_type.value == ''){
//                    jAlert('Please enter default payment type!','Alert');
//                    return false;
//                }
//                if(myForm.note.value == ''){
//                    jAlert('Please enter note!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.reminder.value == ''){
//                    jAlert('Please enter reminder!','Alert');
//                    flag = 1; return false;
//                }
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
//                if(myForm.sale_variance.value == ''){
//                    jAlert('Please enter sale variance!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_delivery_type.value == ''){
//                    jAlert('Please enter default delivery type!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_terms.value == ''){
//                    jAlert('Please enter default terms!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_payment_type.value == ''){
//                    jAlert('Please enter default payment type!','Alert');
//                    return false;
//                }
//                if(myForm.note.value == ''){
//                    jAlert('Please enter note!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.reminder.value == ''){
//                    jAlert('Please enter reminder!','Alert');
//                    flag = 1; return false;
//                }
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
        myForm.created_datetime.value = '<?php echo date('Y-m-d h:i');?>';                               
        jQuery('#created_datetime').val('<?php echo date('Y-m-d h:i:s');?>');
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
	
	
    /* REINSTATE AFTER WORKING
setTimeout(function () { 
  location.reload();
}, 60 * 1000);
*/
</script>
<?php //print_r($_SESSION); ?>
</body>
</html>
<script>
    <?php if ($firstrow != "") { ?>
    jQuery(document).ready(function () {
        jQuery("#<?php echo $firstrow; ?>").trigger("click");
        flag_menu=0;
        jQuery("#mobile-nav-toggle").trigger("click");
    });
    <?php } ?>
</script>
<div id="addModal" class="modal hide fade myAddModal" style="left:53%;width:500px">
	<div class="modal-header" style="padding:18px 15px;">
		<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
		<h3>New Bay Route</h3>
	</div>
	<form id="add_from_modal" name="add_from_modal" action="" method="post" class="edit_from" >
	<div class="modal-body" id="mymodal_html2">
		
			<input type="hidden" name="Submit" value="Submit">
			<input type="hidden" value="no" id="confirm_captain" name="confirm_captain">
			<input type="hidden" value="no" id="confirm_puller" name="confirm_puller">
			<table>
				<tr>
					<td>
						<label>Bay:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?> style="width:20%">

						<select name="bay" id="bay" style="width:285px">
							<?php $res_routes = mysql_query("SELECT vendor_bays_id,bay_name,bay_code,bay_size FROM vendor_bays");?>
							<option value="">- - - Select Bay - - -</option>
							<?php while($row = mysql_fetch_array($res_routes)){
								//"SELECT captain,puller FROM vendor_distribution WHERE bay_id = '".$row['vendor_bays_id']."' " . " AND distribution_date='" . $date . "'";
								/*
								 * This query was not pulling the current captain and puller, but rather an old captain and puller. Before it was grabbing the oldest captain
								 * and puller in the database for that bay. I've changed it so that it grabs from distribution_date of the searched date. This can be changed
								 * to the current date if that is needed, but I do not see a reason why we would want the oldest captain and puller in the database.
								 */
								$fetch_cap_pull = mysql_fetch_array(mysql_query("SELECT captain,puller FROM vendor_distribution WHERE bay_id = '".$row['vendor_bays_id']."' AND distribution_date = '" . $search . "'")); //GOHERE
								?>
								
								<option value="<?= $row['vendor_bays_id'];  ?>" data-bays_id="<?= $row['vendor_bays_id'];?>" data-captain="<?= $fetch_cap_pull['captain'];?>" data-puller="<?= $fetch_cap_pull['puller'];?>" ><?= $row['bay_name'];?></option>
							<?php } ?>
						</select>

					</td>
				</tr>
				<tr>
					<td>
						<label>Shipping Date:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
							<span class="field">
								<input type="text" class="input-xlarge" id="shipping_date" value="<?=$search?>" name="shipping_date">
							 </span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Captain:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
							<span class="field">
								<input type="text" class="input-xlarge" id="captain" value="" name="captain">
							 </span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Puller:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<span class="field" id="group_span">
							 <input type="text" class="input-xlarge" id="puller" value="" name="puller">
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Route:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<select name="route" id="route" style="width:285px">
							<?php
							$res_routes = mysql_query("SELECT * FROM vendor_routes");?>
							<option value="">- - - Select Route - - -</option>
							<?php  while($row_routes = mysql_fetch_assoc($res_routes)){
								?>

								<option value="<?= $row_routes['route_code'];  ?>"><?= $row_routes['route_name'];?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>Truck:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<select name="truck" id="truck" style="width:285px">
							<?php
							$res_veh = mysql_query("SELECT * FROM vendor_vehicles WHERE status='active'");?>
							<option value="">- - - Select Truck - - -</option>
							<?php  while($row_veh = mysql_fetch_assoc($res_veh)){
								?>

								<option value="<?= $row_veh['vehicle_name'];  ?>>"><?= $row_veh['vehicle_name'];  ?></option>

							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>Route Loaded:</label>
					</td>
					<td <?= $cols ?>>
						<div class="field">
							  <div id="routTimePicker" class="input-append">
								<input data-format="hh:mm" type="text" name="route_time" id="route_time" style="width:243px">
								<span class="add-on">
								  <i class="iconfa-time"></i>
								</span>
							  </div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Load Time:</label>
					</td>
					
					<td><label>Hours:</label></td>
					<td>
						<input type="number" name="load_time_hours" id="load_time_hours" style="width:50px" min="0" max="23">
					</td>
					<td><label>Minutes:</label></td>
					<td>
						<input type="number" name="load_time_minutes" id="load_time_minutes" style="width:50px" min="0" max="59">
					</td>
					
				</tr>
				
				<tr>
					<td>
						<label>Time Out:</label>
					</td>
					<td <?= $cols ?>>
						<div class="field">
							  <div id="timeOutPicker" class="input-append">
								<input data-format="hh:mm" type="text" name="time_out" id="time_out" style="width:243px">
								<span class="add-on">
								  <i class="iconfa-time"></i>
								</span>
							  </div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Case Count:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge"  id="cc" value="" name="cc">
						 </span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Driver:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" name="driver" id="newDriver" onfocusout="displaySelectDriver();" value="" style="display: none;">
							<select name="driverselect" id="driver" data-placeholder="Choose a driver..." class="uniformselect select-xlarge" style="color: #555555" onchange="displayNewDriverInput()">
								<option value="">- - - Select Driver - - -</option>
								<option value="-1">- - - Add New Driver - - -</option>
								<?php   
									
									$sqlctry = "SELECT driver FROM vendor_distribution where driver != '' GROUP BY driver ORDER BY driver ASC";
									$resultctry =mysql_query($sqlctry);
									while ($rowctry = mysql_fetch_assoc($resultctry)){
										
								?>
								 <option value="<?php echo $rowctry["driver"]?>"><?php echo $rowctry["driver"]?></option>
								<?php }?> 
							</select>
						 </span>
					</td>
				</tr>

				<tr>
					<td>
						<label>Created By:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_by" value="<?=$user?>" name="created_by">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Created On:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_on" value="VendorPanel" name="created_on">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Created Date & Time:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_datetime" value="<?=date('Y:m:d H:i:s')?>" name="created_datetime">
						 </span>
					</td>
				</tr>
			</table>

	</div>
	</form>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn btn-default reset" type="reset">Cancel</a>
		<button type="button" class="btn btn-primary" id="sub_form"> Submit</button>
	</div>
</div>
<div id="editModal" class="modal hide fade myAddModal" style="left:53%;width:500px">
	<div class="modal-header" style="padding:18px 15px;">
		<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true">&times;</button>
		<h3>Edit Bay Route</h3>
	</div>
	<div class="modal-body" id="mymodal_html2">
		<form id="edit_from_modal" name="edit_from_modal" action="" method="post" class="edit_from" >
			<input type="hidden" name="Submit" value="Submit">
			<input type="hidden" value="no" id="confirm_captain" name="confirm_captain">
			<input type="hidden" value="no" id="confirm_puller" name="confirm_puller">
			
			<input type="hidden" value="" id="edit_id" name="edit_id">
			<table>
				<tr>
					<td>
						<label>Bay:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?> style="width:20%">

						<select name="bay" id="bay2" style="width:285px" disabled data-date="<?=$search.' == '.date('Y-m-d')?>">
							<?php $res_routes = mysql_query("SELECT vendor_bays_id,bay_name,bay_code,bay_size FROM vendor_bays");?>
							<option value="">- - - Select Bay - - -</option>
							<?php while($row = mysql_fetch_array($res_routes)){
								$fetch_cap_pull = mysql_fetch_array(mysql_query("SELECT captain,puller FROM vendor_distribution WHERE bay_id = '".$row['vendor_bays_id']."'"));
								?>
								
								<option value="<?= $row['vendor_bays_id'];  ?>" data-bays_id="<?= $row['vendor_bays_id'];?>" data-captain="<?= $fetch_cap_pull['captain'];?>" data-puller="<?= $fetch_cap_pull['puller'];?>" ><?= $row['bay_name'];?></option>
							<?php } ?>
						</select>

					</td>
				</tr>
				<tr>
					<td>
						<label>Shipping Date:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
							<span class="field">
								<input type="text" readonly class="input-xlarge" id="shipping_date2" value="" name="shipping_date">
							 </span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Captain:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
							<span class="field">
								<input type="text" class="input-xlarge" id="captain2" value="" name="captain">
							 </span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Puller:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<span class="field" id="group_span">
							 <input type="text" class="input-xlarge" id="puller2" value="" name="puller">
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<label>Route:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<select name="route" id="route2" style="width:285px">
							<?php
							$res_routes = mysql_query("SELECT * FROM vendor_routes");?>
							<option value="">- - - Select Route - - -</option>
							<?php  while($row_routes = mysql_fetch_assoc($res_routes)){
								?>

								<option value="<?= $row_routes['route_code'];  ?>"><?= $row_routes['route_name'];?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>Truck:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<select name="truck" id="truck2" style="width:285px">
							<?php
							$res_veh = mysql_query("SELECT * FROM vendor_vehicles WHERE status='active'");?>
							<option value="">- - - Select Truck - - -</option>
							<?php  while($row_veh = mysql_fetch_assoc($res_veh)){
								?>

								<option value="<?= $row_veh['vehicle_name']; ?>"><?= $row_veh['vehicle_name'];  ?></option>

							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label>Route Loaded:</label>
					</td>
					<td <?= $cols ?>>
						<div class="field">
							  <div id="routTimePicker2" class="input-append">
								<input data-format="hh:mm" type="text" name="route_time" id="route_time2" style="width:243px">
								<span class="add-on get_hour_min">
								  <i class="iconfa-time"></i>
								</span>
							  </div>
							  
							 <!-- <span class="field" style="margin:0;"><span class="input-append bootstrap-timepicker">
									<input name="route_time" value="" type="text" class="input-short" id="route_time2" style="width:193px" >
								<span class="add-on"><i class="iconfa-time"></i> </span></span> </span>-->
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<label>Load Time:</label>
					</td>
					
					<td><label>Hours:</label></td>
					<td>
<!--						<select name="load_time_hours" id="load_time_hours2" style="width:60px">-->
<!--							--><?php //for($i=0;$i<=23;$i++){ ?>
<!--								<option value="--><?//= (strlen($i) == 1) ? '0'.$i : $i ?><!--">--><?//= (strlen($i) == 1) ? '0'.$i : $i ?><!--</option>-->
<!--							--><?php //} ?>
<!--						</select>-->
                        <input type="number" name="load_time_hours" id="load_time_hours2" style="width:50px" min="0" max="23">
					</td>
					<td><label>Minutes:</label></td>
					<td>
                        <input type="number" name="load_time_minutes" id="load_time_minutes2" style="width:50px" min="0" max="59">
                        <!--						<select name="load_time_minutes" id="load_time_minutes2" style="width:60px">-->
<!--							--><?php //for($i=0;$i<=59;$i++){ ?>
<!--								<option value="--><?//= (strlen($i) == 1) ? '0'.$i : $i ?><!--">--><?//= (strlen($i) == 1) ? '0'.$i : $i?><!--</option>-->
<!--							--><?php //} ?>
<!--						</select>-->
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Time Out:</label>
					</td>
					<td <?= $cols ?>>
						<div class="field">
							  <div id="timeOutPicker2" class="input-append">
								<input data-format="hh:mm" type="text" name="time_out" id="time_out2" style="width:243px">
								<span class="add-on get_hour_min_timeout">
								  <i class="iconfa-time"></i>
								</span>
							  </div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Case Count:<span style="color:#FF0000;">*</span></label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge"  id="cc2" value="" name="cc">
						 </span>
					</td>
				</tr>
                <tr>
                    <td>
                        <label>Driver:</label>
                    </td>
                    <td <?= $cols ?>>
						<span class="field">
                            <input type="hidden" class="input-xlarge" name="driver" id="newDriver2" onfocusout="displaySelectDriver();" style="display: none;" value="">
							<select name="driverselect" id="driver2" data-placeholder="Choose a driver..." class="uniformselect select-xlarge" style="color: #555555" onchange="displayNewDriverInput()">
								<option value="">- - - Select Driver - - -</option>
                                <option value="">- - - Add New Driver - - -</option>
								<?php   
									
									$sqlctry = "SELECT driver FROM vendor_distribution where driver != '' GROUP BY driver ORDER BY driver ASC";
									$resultctry =mysql_query($sqlctry);
									while ($rowctry = mysql_fetch_assoc($resultctry)){
										
								?>
								 <option value="<?php echo $rowctry["driver"]?>"><?php echo $rowctry["driver"]?></option>
								<?php }?>
							</select>
						 </span>
                    </td>
                </tr>
				<tr>
					<td>
						<label>Created By:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_by2" value="" name="created_by">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Created On:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_on2" value="" name="created_on">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Created Date & Time:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="created_datetime2" value="" name="created_datetime">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Last By:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="last_by2" value="" name="last_by">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Last On:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="last_on2" value="" name="last_on">
						 </span>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Last Date & Time:</label>
					</td>
					<td <?= $cols ?>>
						<span class="field">
							<input type="text" class="input-xlarge" disabled id="last_datetime2" value="" name="last_datetime">
						 </span>
					</td>
				</tr>
			</table>

	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn btn-default reset" type="reset">Cancel</a>
		<button type="button" class="btn btn-danger" id="delete_matrix"> Delete</button>
		<button type="button" class="btn btn-primary" id="sub_form2"> Submit</button>
	</div>
</div>


<div id="confirmModal" class="modal hide fade confirmModal" style="left:53%;width:500px">
	<div class="modal-header" style="padding:6px 15px;">
		<h3>Confirm!</h3>
	</div>
	<div class="modal-body" id="mymodal_html2">
		<h5 align="center">You can change date if you want to copy Matrix from previous date.</h5>
		<br>
		<h5 align="center"><span><input type="text" id="confirm_date"></span><h5>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button type="button" class="btn btn-primary" id="confirm_ok"> OK</button>
		<a data-dismiss="modal" href="#" class="btn btn-default reset" type="reset">Cancel</a>
	</div>
</div>

