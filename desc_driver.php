<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$empmaster_id=$_SESSION['client_id'];
$curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));
$c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id,first_name,last_name from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

$user = $vender['first_name'].' '.$vender['last_name'];

$search = date('Y-m-d');
if(strtotime($date_create) > strtotime(date('Y-m-d 16:00:00'))){
	$search = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
}
$search = (isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != "") ? mysql_real_escape_string($_REQUEST['search_date']) : $search;

$search_date = " vd.distribution_date = '" . $search . "'";

$search_where = '';
if(isset($_REQUEST['search_txt1'])){
    $limit = 500;
    if($_REQUEST['search_txt1']!=""){
        $search2 = $_REQUEST['search_txt1'];
        $search_where = " AND (vd.driver LIKE '%".$search2."%' OR vdr.routes Like '%".$search2."%' OR vdr.vehicle Like '%".$search2."%')";
    }
}

$q = "SELECT count(*) as total_records FROM `vendor_distribution` as `vd`
LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id WHERE $search_date $search_where AND vdr.routes != ''";
//echo $q;
$query_total = mysql_query($q);
$fet_total = mysql_fetch_array($query_total);
$total_records = ($fet_total['total_records']/13);

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
	<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
	
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
    <script type="text/javascript" src="js/responsive-tables.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
	<script type="text/javascript" src="js/jquery.timepicker.js"></script>
    <style>
        body {
            top:0px!important;
        }
        .goog-te-banner-frame{  margin-top: -50px!important; }
        .error {
            color: #FF0000;
            padding-left:10px;
        }
        .span4 {
            float:left;
            width:28.5%!important;
            min-height:600px;
            margin-left:1.5%!important;
        }
        table.table tbody tr.selected, table.table tfoot tr.selected {
            background-color: #808080;
        }
        .dataTables_filter input{ height:28px !important;}
        .dataTables_filter {
            top: 5px;
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
        jQuery(document).ready(function ($) {
			
			jQuery('#search_date').datepicker({ 
				dateFormat: 'yy-mm-dd', 
				changeMonth: true, 
				changeYear: true,
			});
			
			jQuery("#search_date").change(function(){
				if(jQuery(this).val()!=""){
					window.location="desc_driver.php?&search_date="+jQuery(this).val()+"&search_txt1=<?php echo isset($_GET['search_txt1']) ? $_GET['search_txt1'] : '' ?>";
				}
			});
			
			jQuery('#ser_go').live('click',function(){
                window.location="desc_driver.php?&search_txt1="+jQuery('#search_txt1').val()+"&search_date=<?php echo isset($_GET['search_date']) ? $_GET['search_date'] : '' ?>";
            });
			
			<?php if(isset($_REQUEST['search_txt1'])){ ?>
			
				 $('#back_button').prop('disabled', false);
			
			<?php } ?>
			
			$('#back_button').click(function(){
				window.location='desc_driver.php';
			});

        });
       
    </script>

    <style>

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
		.diverStyle{ padding:12px !important; font-size:16px !important;font-weight:bold; }
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
            <li><span class="separator"></span> Driver </li>
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
            <div style="float: right;margin-top: 20px;" class="messagehead">
			
				<p style="float:left;margin-right:10px;margin-top: 10px;">Shipping Date: </p>
                <p style="float:left;">
                <span><input type="text" placeholder="Search" value="<?php echo $_REQUEST['search_txt1']; ?>" name="search_txt1" id="search_txt1" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;"></span>
				<span><input type="text" value="<?php echo isset($_REQUEST['search_date']) ? $_REQUEST['search_date'] : $search; ?>" name="search_date" id="search_date" class="go_search2" style="width: 120px; padding-left: 5px; height: 32px; float:right; margin-right:10px;"></span>
                <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
                </p>
				
                <button id="ser_go" style="/*float:left*/" class="btn btn-primary btn-large">Go</button>
				<input type="submit" id="back_button" style="" class="btn btn-large <?php echo isset($_REQUEST['search_txt1']) ? 'btn-primary' : '' ?>" value="Back" disabled>
                <!--<button type="button" class="btn btn-success btn-large" data-toggle="modal" data-target="#addModal">Add</button>
				<button id="show_legend" class="btn btn-large" rel='tooltip' data-original-title='<table><tr><td class="color-box"><span class="gray-box"></span></td><td>Nothing Loaded</td></tr><tr><td class="color-box"><span class="yellow-box"></span></td><td>Some routes Loaded</td></tr><tr><td class="color-box"><span class="orange-box"></span></td><td>All Routes loaded</td></tr><tr><td class="color-box"><span class="blue-box"></span></td><td>All Routes loaded and some time out</td></tr><tr><td class="color-box"><span class="green-box"></span></td><td>All loaded and All time out</td></tr></table>'>Legend</button>-->
				
            </div>
            <div class="pageicon"><span class="iconfa-truck"></span></div>
            <div class="pagetitle">
                <h5>This Board assigns Drivers to Routes and Trucks by Date</h5>
                <h1>Driver Schedule Board</h1>
            </div>

        </div>
        <!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner" >
                <div class="row row-fluid" id="#!" style="margin-left: 11px;">

                    <?php
                    //".$search_date." 
					if(ceil($total_records) > 0){
                    $r = 1;$g = 1;
					$start = 0;
					for($i=1;$i<=ceil($total_records);$i++){
						
                    $vendor_distribution_q = "SELECT vd.distribution_date,vd.captain,vd.puller,vd.driver ,vdr.vendor_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, vdr.route_time, vdr.load_time, vdr.time_out, vdr.cartons,
							vdr.created_by,vdr.created_on,vdr.created_datetime,vdr.last_on,vdr.last_by,vdr.last_datetime
                            FROM `vendor_distribution` as `vd`
                            LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
							LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code WHERE $search_date $search_where AND vdr.routes != ''
                            ORDER BY vdr.routes ASC limit $start, 13";
					$vendor_distribution_query = mysql_query($vendor_distribution_q);
						  
					
					if(isset($_REQUEST['search_date']) && trim($_REQUEST['search_date']) != ""){
						$vendor_distribution_q = "SELECT vd.distribution_date,vd.captain,vd.puller,vd.driver ,vd.vendors_distribution_id as id,vr.route_code,vdr.routes, vdr.vehicle, vdr.route_time, vdr.load_time, vdr.time_out, vdr.cartons,
						vdr.created_by,vdr.created_on,vdr.created_datetime,vdr.last_on,vdr.last_by,vdr.last_datetime
                        FROM `vendor_distribution` as `vd`
                        LEFT JOIN `vendor_distribution_routes` as `vdr` ON vd.vendors_distribution_id= vdr.vendor_distribution_id
						LEFT JOIN `vendor_routes` `vr` ON vdr.routes = vr.route_code WHERE $search_date $search_where AND vdr.routes != ''
                        ORDER BY vdr.routes ASC limit $start, 13";
						$vendor_distribution_query = mysql_query($vendor_distribution_q);
					} 
					//echo $vendor_distribution_q;
                    ?>					

                    <div class="span3" style="width:24.1%;">
                        <div class="clearfix1">
                            <h4 class="widgettitle" style="">LIST</h4>
                        </div>
                        <div class="widgetcontent" style="/*max-height: 417px;height: 417px;*/overflow-x:hidden;">
                            <div class="load table-wrapper-scroll-y ">
                                
                                <!--End List Items-->
                                <table id="itm_tbl" class="table table-bordered table-infinite" style="table-layout:fixed;">
                                    <colgroup>
										<col class="con0"  style="width:10%;"/>
										<col class="con1"  style="width:10%"/>
										<col class="con0"  style="width:30%"/>
									</colgroup>
                                    <thead>
                                    <tr>
                                        <th class="matrix_head center">Route</th>
										<th class="matrix_head center">Truck</th>
                                        <th class="matrix_head center">Driver</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while($vendor_distribution_rows = mysql_fetch_assoc($vendor_distribution_query)){	
									?>
										<tr style="text-align: center;">
											<td class="diverStyle"><?= $vendor_distribution_rows['routes'] ?></td>
											<td class="diverStyle"><?= $vendor_distribution_rows['vehicle'] ?></td>
											<td class="diverStyle"><?= $vendor_distribution_rows['driver'] ?></td>
										</tr>
                                    <?php $start++; } ?>
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

</body>
</html>

