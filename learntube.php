<?php
require_once 'require/security.php';
include 'config/accessConfig.php'; ;
//$_SESSION['client_id']."==>".$_SESSION['name']."==>".$_SESSION['email'];

$_SESSION['SESS_EmpIds']=GetEmployees($_SESSION['email']);
function GetEmployees($strEmail){
	$strEmpIds="";
	$sequery="SELECT id, emp_id, first_name, last_name, location_id FROM employees WHERE status='A' AND email='".$strEmail."'";
	$seresult=mysql_query($sequery);
	while($serow=mysql_fetch_object($seresult)){
		$strEmpIds=$strEmpIds.$serow->id.",";
	}
	return $strEmpIds=substr($strEmpIds,0,-1);
}

function getLocationName($locid) {
    $sql = "SELECT name FROM locations where id=" . $locid;
    $rs = mysql_query($sql);
    $d = mysql_fetch_array($rs);
    $nameloc = $d["name"];
    
	return $nameloc;
}
$employeeID = $_SESSION['SESS_EmpIds'];
if(isset($_GET["lessid"]) && !empty($_GET["lessid"])){
$lessid = mysql_real_escape_string($_GET["lessid"]);
$employee_id=$_REQUEST['employee_id'];
if($employee_id!=""){
	$emp_fitler = " AND training_employee_lessons.employee_id IN(".$employee_id.")";
}

if(isset($_REQUEST['tet_id']) && $_REQUEST['tet_id']!=""){
$v_sql = "SELECT
training_employee_transactions.owner_author_id as author_id,
training_employee_transactions.purchase_author_id as employee_id,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name  WHEN training_lessons.author_type='Admin' THEN users.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
employees_les.first_name, employees_les.last_name
FROM
training_lessons
LEFT JOIN training_employee_transactions ON training_employee_transactions.lesson_id = training_lessons.lesson_id AND training_employee_transactions.tet_id ='".$_REQUEST['tet_id']."' 
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees_master as employees_les ON employees_les.empmaster_id = training_employee_transactions.purchase_author_id
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
WHERE training_lessons.lesson_id='$lessid'";// AND training_employee_lessons.employee_id IN(".$employee_id.")"; //

}else{
$v_sql = "SELECT
training_employee_lessons.author_id,
training_employee_lessons.product_id,
training_employee_lessons.lesson_id,
training_employee_lessons.employee_id,
training_employee_lessons.lesson_priority,
training_employee_lessons.author_type as auth,
training_employee_lessons.lesson_req,
training_employee_lessons.location_id,
training_employee_lessons.tel_id,
training_employee_lessons.lesson_ended_datetime,
training_employee_lessons.lesson_started_datetime,
training_employee_lessons.lesson_video_score,
training_employee_lessons.lesson_pass,
training_employee_lessons.lesson_valid_period,
training_employee_lessons.lesson_taken_datetime,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name  WHEN training_lessons.author_type='Admin' THEN users.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
employees_les.first_name, employees_les.last_name
FROM
training_lessons
LEFT JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_lessons.lesson_id
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees as employees_les ON employees_les.id = training_employee_lessons.employee_id
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
WHERE training_lessons.lesson_id='$lessid'".$emp_fitler;// AND training_employee_lessons.employee_id IN(".$employee_id.")"; //
} 

$v_result = mysql_query($v_sql) or die(mysql_error());
$vnumrows=mysql_num_rows($v_result);
$v_rows = mysql_fetch_array($v_result);

}
if($employeeID==""){
	$employeeID = "00";
}
$sql = "SELECT
training_employee_lessons.author_id,
training_employee_lessons.product_id,
training_employee_lessons.lesson_id,
training_employee_lessons.employee_id,
training_employee_lessons.lesson_priority,
training_employee_lessons.author_type as auth,
training_employee_lessons.lesson_req,
training_employee_lessons.location_id,
training_employee_lessons.tel_id,
training_employee_lessons.lesson_ended_datetime,
training_employee_lessons.lesson_started_datetime,
training_employee_lessons.lesson_video_score,
training_employee_lessons.lesson_pass,
training_employee_lessons.lesson_valid_period,
training_employee_lessons.lesson_taken_datetime,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name WHEN training_lessons.author_type='Admin' THEN users.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
0 as tet_id,
employees_les.first_name, employees_les.last_name,
locations_les.name as loc_name
FROM
training_lessons
LEFT JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_lessons.lesson_id
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees as employees_les ON employees_les.id = training_employee_lessons.employee_id
LEFT JOIN training_employee_transactions  ON training_employee_transactions.lesson_id = training_lessons.lesson_id and training_employee_transactions.purchase_author_id = '".$_SESSION['client_id']."'  AND purchase_author_type = 'Team'
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations as locations_les ON locations_les.id = training_employee_lessons.location_id

WHERE training_employee_lessons.employee_id IN (".$employeeID.") AND training_lessons.status='active' AND
(training_employee_lessons.lesson_pass = '' OR training_employee_lessons.lesson_pass = 'No') 
group BY training_lessons.lesson_id

union all
SELECT
training_employee_lessons.author_id,
training_employee_lessons.product_id,
training_employee_lessons.lesson_id,
training_employee_transactions.purchase_author_id as employee_id,
training_employee_lessons.lesson_priority,
training_employee_lessons.author_type as auth,
training_employee_lessons.lesson_req,
training_employee_lessons.location_id,
training_employee_lessons.tel_id,
training_employee_lessons.lesson_ended_datetime,
training_employee_lessons.lesson_started_datetime,
training_employee_lessons.lesson_video_score,
training_employee_lessons.lesson_pass,
training_employee_lessons.lesson_valid_period,
training_employee_lessons.lesson_taken_datetime,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name WHEN training_lessons.author_type='Admin' THEN users.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
training_employee_transactions.tet_id,
employees_les.first_name, employees_les.last_name,
locations_les.name as loc_name
FROM
training_employee_transactions
LEFT JOIN training_lessons ON training_lessons.lesson_id = training_employee_transactions.lesson_id
INNER JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_employee_transactions.lesson_id 
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees_master as employees_les ON employees_les.empmaster_id = training_employee_transactions.purchase_author_id
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations as locations_les ON locations_les.id = training_employee_lessons.location_id
WHERE training_employee_transactions.purchase_author_id ='".$_SESSION['client_id']."' AND purchase_author_type = 'Team' AND
(training_employee_lessons.lesson_pass IS NULL OR training_employee_lessons.lesson_pass = 'No' OR training_employee_lessons.lesson_pass = ' ') 
group BY training_lessons.lesson_id ORDER BY `tet_id` desc";
//ORDER BY `name` 
//JOIN training_employee_transactions  ON training_lessons.lesson_id = training_employee_transactions.lesson_id and training_employee_transactions.purchase_author_id ='".$_SESSION['client_id']."' AND purchase_author_type = 'Team'

$result = mysql_query($sql) or die(mysql_error());
$result2 = mysql_query($sql) or die(mysql_error());
$numrows = mysql_num_rows($result);
$rows  = mysql_fetch_array($result);

$sql_completed = "SELECT
training_employee_lessons.author_id,
training_employee_lessons.product_id,
training_employee_lessons.lesson_id,
training_employee_lessons.employee_id,
training_employee_lessons.lesson_priority,
training_employee_lessons.author_type as auth,
training_employee_lessons.lesson_req,
training_employee_lessons.location_id,
training_employee_lessons.tel_id,
training_employee_lessons.lesson_ended_datetime,
training_employee_lessons.lesson_started_datetime,
training_employee_lessons.lesson_video_score,
training_employee_lessons.lesson_pass,
training_employee_lessons.lesson_valid_period,
training_employee_lessons.lesson_taken_datetime,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
employees_les.first_name, employees_les.last_name,
locations_les.name as loc_name
FROM
training_lessons
LEFT JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_lessons.lesson_id
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees as employees_les ON employees_les.id = training_employee_lessons.employee_id
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
LEFT JOIN locations as locations_les ON locations_les.id = training_employee_lessons.location_id
WHERE
training_employee_lessons.lesson_pass = 'Yes' AND training_employee_lessons.employee_id IN (".$employeeID.")
AND training_lessons.status='active' 
group BY training_employee_lessons.lesson_id

union all
SELECT
training_employee_lessons.author_id,
training_employee_lessons.product_id,
training_employee_lessons.lesson_id,
training_employee_transactions.purchase_author_id as employee_id,
training_employee_lessons.lesson_priority,
training_employee_lessons.author_type as auth,
training_employee_lessons.lesson_req,
training_employee_lessons.location_id,
training_employee_lessons.tel_id,
training_employee_lessons.lesson_ended_datetime,
training_employee_lessons.lesson_started_datetime,
training_employee_lessons.lesson_video_score,
training_employee_lessons.lesson_pass,
training_employee_lessons.lesson_valid_period,
training_employee_lessons.lesson_taken_datetime,
training_lessons.lesson_id,
training_lessons.product,
training_lessons.author_type,
CASE WHEN training_lessons.author_type='Location' THEN locations.name ELSE CONCAT(employees.first_name,employees.last_name) END as AutherName,
training_lessons.author,
training_lessons.`group`,
training_lessons.type,
training_lessons.module,
training_lessons.version,
training_lessons.`status`,
training_lessons.`name`,
training_lessons.keywords,
training_lessons.lesson_descr,
training_lessons.lesson_image,
training_lessons.valid_period,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
employees_les.first_name, employees_les.last_name,
locations_les.name as loc_name
FROM
training_lessons
LEFT JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_lessons.lesson_id
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees as employees_les ON employees_les.id = training_employee_lessons.employee_id
LEFT JOIN training_employee_transactions  ON training_employee_transactions.lesson_id = training_lessons.lesson_id and training_employee_transactions.purchase_author_id = '".$_SESSION['client_id']."'  AND purchase_author_type = 'Team'
LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations as locations_les ON locations_les.id = training_employee_lessons.location_id
WHERE training_employee_transactions.purchase_author_id ='".$_SESSION['client_id']."' AND purchase_author_type = 'Team' AND
training_employee_lessons.lesson_pass = 'Yes'
GROUP BY training_employee_lessons.lesson_id";
//ORDER BY training_lessons.`name`";
 
$res_comp = mysql_query($sql_completed) or die(mysql_error());
$res_comp2 = mysql_query($sql_completed) or die(mysql_error());
$cnumrows=mysql_num_rows($res_comp);
$c_rows  = mysql_fetch_array($res_comp);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.min.js"></script>
<script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/detectizr.min.js"></script>

<style>
body {
top:0px!important;
}
.receipt, .receipt tbody, .receipt thead, .receipt tr, .receipt th, .receipt td{
line-height:30px !important;
font-size:12px;
}
.maincontentinner{
	padding:15px 20px 20px;

}
.goog-te-banner-frame{  margin-top: -50px!important; }
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
table.table tbody tr.ui-selected, table.table tfoot tr.ui-selected {
	background-color:rgb(128,128,128);
}
//table.table tbody tr .ui-selected {
background: #ffffff;
}
.progress {
	position: relative;
	width: 100%;
	border: 1px solid #ddd;
	padding: 1px;
	border-radius: 3px;
	display: none;
	margin-top: 10px;
}
.bar {
	background-color: #B4F5B4;
	width: 0%;
	height: 20px;
	border-radius: 3px;
}
.percent {
	position: absolute;
	display: inline-block;
	top: 3px;
	left: 48%;
}
.ui-datepicker-month {
	width: 70px
}
.ui-datepicker-year {
	width: 70px
}
#licence_table th {
	font-size:11px;
}
.table th, .table td {
	padding:1%;
}
.tdname {
	font-size:1.1em;
	font-weight:bold;
}
.ui-tabs-panel {
	color: #000000;
}
.pp_details {
	display:none;
}
.modal-header {
	border-bottom: 1px solid #EEEEEE;
	padding: 9px 15px;
}
.modal-header .close {
	margin-top: 2px;
}
.close {
	text-shadow: 1px 1px rgba(255, 255, 255, 0.4);
}
.close {
	color: #000000;
	float: right;
	font-size: 20px;
	font-weight: bold;
	line-height: 20px;
	opacity: 0.2;
	text-shadow: 0 1px 0 #FFFFFF;
}
.modal-body {
	overflow-y:hidden;
}
.line3, .line4{ background-color:#808080;}
@media screen and (max-width: 1152px) {
 .table th, .table td {
 padding:0.4%;
}
}
</style>

<script type="text/javascript" src="js/jquery.form.js"></script>

<script type="text/javascript">
 var tab_index=0;
function tabSettings(tabno)
{
tab_index=tabno;

	if(tabno=='ui-id-1' || tabno=="0")	
	{
		jQuery("#new_tab").show();
		jQuery("#comp_tab").hide();
	}else if(tabno=="ui-id-2" || tabno=="1"){
		jQuery("#new_tab").hide();
		jQuery("#comp_tab").show();
	}
}
    jQuery(document).ready(function(){
			var les_id = '<?php echo $_REQUEST['lessid']._.$_REQUEST['employee_id']; ?>'
				if(les_id!=""){
						jQuery('#'+les_id).addClass('g_item');
						jQuery('#'+les_id).addClass('odd');
						jQuery('#'+les_id).addClass('ui-selectee');
						jQuery('#'+les_id).addClass('ui-selected');				
				}else{
						jQuery('#licence_table tr:first-child').addClass('g_item');
						jQuery('#licence_table tr:first-child').addClass('odd');
						jQuery('#licence_table tr:first-child').addClass('ui-selectee');
						jQuery('#licence_table tr:first-child').addClass('ui-selected');
				}
			
			jQuery('#licence_table,#bo_table').dataTable({
				"sPaginationType": "full_numbers",
				"aaSorting": [],
				"bJQuery": true,
				"fnDrawCallback": function(oSettings) {
					jQuery.uniform.update();
				}
			});
		
		var $tabs = jQuery('.tabbedwidget').tabs({
				activate: function (event, ui) {
					 selected = ui.newTab.context.id;
					tabSettings(selected);
				  }
			});
		var selected = $tabs.tabs('option', 'active');
		tabSettings(selected);
		
		jQuery("#addRegister a").colorbox();
			 jQuery("#registerFilter a").colorbox();
		
    });
	var $j = jQuery.noConflict();
	$j(document).ready(function() {
			$j('[data-toggle="modal"]').bind('click',function(e) {
				 e.preventDefault();
				var url = $j(this).attr('href');
				if (url.indexOf('#') == 0) {
					$j('#response_modal').modal('open');
				} else {
					$j.get(url, function(data) {
					$j('#response_modal').html(data);
					$j('#response_modal').modal();
					}).success(function() { 
						/*$j('input:text:visible:first').focus(); */
					});
				}
			});
		});  
</script>
</head>
<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>LearnTube</li>
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
      <div class="pageicon"><span class="iconfa-facetime-video"></span></div>
      <div class="pagetitle">
        <h5>The following lessons are assigned to you</h5>
        <h1>LearnTube</h1>
      </div>
    </div>
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
         <div class="span8">
         	<div class="tabbedwidget tab-primary">
            <ul>
                <li><a href="#e-7">New</a></li>
                <li><a href="#e-8">Completed</a></li>
            </ul>
            <div id="e-7">
            	<table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
                    <col class="con0" style="align: center; width:5%;" />
                    <col class="con1" style="width:20%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:27%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:7%;"/>
                    <col class="con0" style="width:9%;"/>
                    <col class="con1" style="width:7%;"/>
                    </colgroup>
                    <thead>
                      <tr>
                        <th  class="head1">Image</th>
                        <th  class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Employee</th>
                        <!--  
                                    <th style="width:12%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Location</th>
									-->
                        <th  class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Name</th>
                        <th  class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Description</th>
                        <th  class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Product</th>
                        <!-- 
									<th style="width:7%;" class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Type</th>
									<th style="width:7%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Group</th>
									 -->
                        <th class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Req</th>
                        <th class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Valid</th>
                        <th class="head1">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
								 $i = 1;
								// if($numrows > 1 || $numrows == 1){
								 while($rowss  = mysql_fetch_array($result2)){ 
								 
								$query2 = "SELECT tet_id from training_employee_transactions WHERE purchase_author_id ='".$_SESSION['client_id']."' AND purchase_author_type = 'Team' AND tet_id='".$rowss["tet_id"]."'";
								 $res2 = mysql_query($query2);
								 if(mysql_num_rows($res2)>0){
								 $tet_id = "yes";
								 }
								 /*$row2  = mysql_fetch_array($res2); 
								 if($row2['id']>0){
								 	$tet_id = "yes";
								 }*/
								 
								
								 ?>
                      <tr id="<?php echo $rowss["lesson_id"].'_'.$rowss["employee_id"].'_'.$i; ?>" class="gradeX cl_order <?php if($i==1) {?>g_item line3<?php } ?>" style="cursor:pointer;height:81px" >
                        <td style="width:7%; min-width:70px !important;"  onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)">
						<?php if($rowss["lesson_image"] == "") { ?>
                          <img src="images/noimage.png" alt="" id="personalimagephoto" style="width:50px; height:50px;" />
                          <?php } else {?>
                          <img onerror="this.src='images/noimage.png'" src="<?php echo APIPHP; ?>/images/<?php echo $rowss["lesson_image"];?>" alt="" id="personalimagephoto" style="width:50px; height:50px;"  />
                          <?php } ?>
                        </td>
                        <td onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)"><?php echo $rowss["first_name"]." ".$rowss["last_name"]." (".$rowss["employee_id"].") at <b>".$rowss["loc_name"]." (ID# ".$rowss["location_id"]."</b>)";?></td>
                        <!-- 
									<td style="width:12%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?> style="background-color:#808080;"<?php }?>><?php echo $rowss["loc_name"]." (ID# ".$rowss["location_id"].")";?></td>
                                     -->
                        <td  onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)"><?php echo $rowss["name"];?>&nbsp;</td>
                        <td  onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)" ><?php echo $rowss["lesson_descr"];?>&nbsp;</td>
                        <td  onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)" ><?php echo $rowss["proname"]."<br/>".$rowss["types"]."<br/>".$rowss["groupname"];?></td>
                        <!-- 
									<td style="width:7%; overflow:hidden;word-wrap:break-word;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#808080;"<?php }?>><?php echo $rowss["types"];?>&nbsp;</td>
									<td style="width:7%;overflow:hidden;word-wrap:break-word;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#808080;"<?php }?>><?php echo $rowss["groupname"];?>&nbsp;</td>
									 -->
                        <td style="width:6%;" onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)" ><?php echo $rowss["lesson_req"];?>&nbsp;</td>
                        <?php $l_vp = $rowss["valid_period"];
									$svquery="SELECT date_format(DATE_ADD(created_datetime, INTERVAL ".$l_vp." DAY),'%Y-%m-%d') as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id='".$rowss["lesson_id"] ."'";
										  $svresult=mysql_query($svquery) or die($svquery .'------------------');
										  if(mysql_num_rows($svresult) > 0){
											  $svrow=mysql_fetch_object($svresult);
											  $validperiod=$svrow->Valid_period;
												/* Written by Amit*/
												/* if($rowss["lesson_valid_period"] == '0' || $rowss["lesson_started_datetime"] == '0000-00-00 00:00:00') {
													$validperiod = "";
												} else {
													$dayPeriod = "0";
													$datePeriod = $rowss["lesson_started_datetime"];
													if($rowss["lesson_valid_period"] != "") {
														$dayPeriod = $rowss["lesson_valid_period"];										
													}
													 $validperiod = date('m-d-Y', strtotime($datePeriod. ' + '.$dayPeriod.' days'));
												} */
												if($validperiod!='0000-00-00 00:00:00' && $validperiod!=''){
													$validperiod = date('Y-m-d',strtotime($validperiod));
												}
											}
										?>
                        <td   onClick="get_receipt_new(<?php echo $rowss["lesson_id"];?>,<?php echo $rowss['employee_id'] ?>,<?php echo $rowss['tet_id']; ?>,<?php echo $i; ?>)"><?php echo $validperiod;?></td>
                        <td  class="center" ><a href="learntube_training_lessons_take_lesson.php?lessonid=<?php echo $rowss["lesson_id"]; if($rowss['tet_id']>0){?>&emp_master_id=<?php echo $rowss['employee_id']; }else{?>&employee_id=<?php echo $rowss['employee_id']; }?>"><img src="images/279-videocamera.png" title="Take Lesson" alt="Take Lesson" border="0"  style="height:16px;width:22px;"></a>
                          <?php 
						 
						  if($rowss['tet_id']>0)
						  {
						  ?>
                          <a href="learntube_showdetails.php?tet_id=<?php echo $rowss['tet_id']?>" target="#response_modal" data-toggle="modal"><img src="images/pricetag.png" title="Purchase Lesson" alt="Purchase Lesson" border="0"  style="height:16px;width:22px;"></a>
                          <div id="response_modal" class="modal hide fade" ></div>
                          <?php } ?>
                        </td>
                      </tr>
                      <?php $i++; }?>
                    </tbody>
              </table>
            </div>
            <div id="e-8">
            <div style="/*overflow-x:scroll;*/">
                  <table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
					<col class="con0" style="align: center; width:5%;" />
                    <col class="con1" style="width:20%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:27%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:7%;"/>
                    <col class="con0" style="width:9%;"/>
                    <col class="con1" style="width:7%;"/>
                    <!--<col class="con0" style="align: center;" />
                    <col class="con1" />
                    <col class="con0" />
                    <col class="con1" />
                    <col class="con0" />
                    <col class="con1" />
                    <col class="con0" />
                    <col class="con1" />
                    <col class="con0" />-->
                    </colgroup>
                    <thead>
                      <tr>
                        <th style="width:7%;" class="head1">Image</th>
                        <th style="width:12%;" class="head0">Employee</th>
                        <!--<th style="width:12%;" class="head0">Location</th>-->
                        <th style="width:12%;" class="head0">Name</th>
                        <th style="width:12%;" class="head1">Description</th>
                        <th style="width:12%;" class="head0">Product</th>
                        <!--<th style="width:7%;" class="head1">Type</th>
                        <th style="width:7%;" class="head0">Group</th>-->
                        <!--<th class="head1" style="width:20%;">End Date</th>
									<th class="head0">Score</th>-->
                        <th style="width:6%;" class="head1">Req</th>
                        <th style="width:6%;" class="head0">Valid</th>
                        <th style="width:7%;" class="head1">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 			  
					 	
								 $j = 1;
								 while($rows_comp  = mysql_fetch_array($res_comp2)){ ?>
                      <tr id="<?php echo $rows_comp['employee_id'].''.$rows_comp["lesson_id"]; ?>" class="gradeX cl_order <?php if(($rows_comp["lesson_id"]==$_REQUEST['lessid'] && $rows_comp["employee_id"]==$_REQUEST['employee_id'])  || ($j==1 && $_REQUEST['lessid']=="")) {?>g_item line4<?php } ?>" style="cursor:pointer;" onClick="get_receipt_comp(<?php echo $rows_comp["lesson_id"];?>,<?php echo $rows_comp['employee_id'] ?>)">
                        <td style="width:7%; min-width:70px !important;"><?php if($rows_comp["lesson_image"] == "") { ?>
                          <img src="images/noimage.png" alt="" id="personalimagephoto" style="width:50px; height:50px;"  />
                          <?php } else {?>
                          <img onerror="this.src='images/noimage.png'" src="<?php echo APIPHP; ?>/images/<?php echo $rows_comp["lesson_image"];?>" alt="" id="personalimagephoto" style="width:50px; height:50px;"  />
                          <?php } ?>
                        </td>
                        <td style="width:12%;"><?php echo $rows_comp["first_name"]." ".$rows_comp["last_name"]." (".$rows_comp["employee_id"].") at <b>".$rows_comp["loc_name"]." (ID# ".$rows_comp["location_id"]."</b>)";?>&nbsp;</td>
						
                        <?php /* ?><td style="width:12%;"><?php echo $rows_comp["loc_name"]." (ID# ".$rows_comp["location_id"].")";?>&nbsp;</td> <?php */ ?>
                        <td style="width:12%;"><?php echo $rows_comp["name"];?>&nbsp;</td>
                        <td style="width:12%;"><?php echo $rows_comp["lesson_descr"];?>&nbsp;</td>
                        <td style="width:12%; word-wrap: break-word; padding: 0.5% !important;"><?php echo $rows_comp["proname"]."<br/>".$rows_comp["types"]."<br/>".$rows_comp["groupname"];?>&nbsp;</td>
                        <?php /* ?><td style="width:7%; word-wrap: break-word; padding: 0.5% !important;"><?php echo $rows_comp["types"];?>&nbsp;</td>
                        <td style="width:7%; word-wrap: break-word; padding: 0.5% !important;"><?php echo $rows_comp["groupname"];?>&nbsp;</td><?php */ ?>
                        <td style="width:6%; word-wrap: break-word; padding: 0.5% !important;"><?php echo $rows_comp["lesson_req"];?>&nbsp;</td>
                        <td style="width:6%; word-wrap: break-word; padding: 0.5% !important;">
						<?php 
						$l_vp1 = $rows_comp["valid_period"];
						$svquery1="SELECT date_format(DATE_ADD(created_datetime, INTERVAL ".$l_vp1." DAY),'%Y-%m-%d') as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp1." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id='".$rows_comp["lesson_id"] ."'";
										  $svresult1=mysql_query($svquery1) or die($svquery1 .'------------------');
										  if(mysql_num_rows($svresult1) > 0){
																  $svrow1=mysql_fetch_object($svresult1);
																  $validperiod1=$svrow1->Valid_period;
						
												/* Written by Amit*/
														/* if($rows_comp["lesson_valid_period"] == '0' || $rows_comp["lesson_started_datetime"] == '0000-00-00 00:00:00') {
															$validperiod1 = "";
														} else {
															$dayPeriod = "0";
															$datePeriod = $rows_comp["lesson_started_datetime"];
															if($rows_comp["lesson_valid_period"] != "") {
																$dayPeriod = $rows_comp["lesson_valid_period"];										
															}
															 $validperiod1 = date('m-d-Y', strtotime($datePeriod. ' + '.$dayPeriod.' days'));
														} */	
														if($validperiod1!='0000-00-00 00:00:00' && $validperiod1!=''){
															$validperiod1 = date('Y-m-d',strtotime($validperiod1));
														}
										}
						 echo $validperiod1; ?></td>
                        <td style="width:7%;" class="center"><a href="learntube_training_lessons_take_lesson.php?lessonid=<?php echo $rows_comp["lesson_id"];?>&employee_id=<?php echo $rows_comp['employee_id'] ?>"><img src="images/279-videocamera.png" border="0"  style="height:16px;width:22px;"></a> </td>
                      </tr>
                      <?php $j++; } ?>
                    </tbody>
                  </table>
              </div>
            </div>
            </div>
         </div>
         <div class="span4 profile-left" id="new_tab" style="width: 32.5%;margin-left: 27px;">            
            	
                <div class="widgetbox company-photo">                
              	<h4 class="widgettitle">Details</h4>
              	<div class="widgetcontent" style="padding-bottom: 25px;padding-top: 0 !important;">
                
                <table class="receipt" width="100%">
				<tr>
				<?php if($numrows==0) {
                                                    echo "Nothing to Display";
                                                    
                                                    }else{ ?>
                <div class="profilethumb" style="text-align:left;">
                  <?php  
							if(isset($_REQUEST["lessid"])){
								$l_ispass = $v_rows["lesson_pass"];
							}
							else{ $l_ispass = $rows["lesson_pass"]; }
						
                                             
									
								if(isset($_REQUEST["lessid"])){
									$l_image = $v_rows["lesson_image"];
								}
								else{ $l_image = $rows["lesson_image"]; }

								if($l_image == "") { ?>
                  <td style="width:25%;"><img src="images/noimage.png" alt="" id="personalimagephoto" style="width:80px; height:80px;"  /></td>
                  <?php } else {?>
                  <td style="width:25%;"><img onerror="this.src='images/noimage.png'" src="<?php echo APIPHP; ?>/images/<?php echo $l_image;?>" alt="" id="personalimagephoto" style="width:80px; height:80px;" /></td>
                  <?php } ?>
                  &nbsp;&nbsp;
                  <?php 
									if(isset($_REQUEST["lessid"])){
										$l_takentime = $v_rows["lesson_taken_datetime"];
									}
									else{ $l_takentime = $rows["lesson_taken_datetime"]; }
									
									
								
                                 ?>
                  <!--<img src="images/lesson_nottaken.png" alt="" id="personalimagephoto" width="80" height="80" title="Not Taken" />-->
                  <?php  if($l_ispass == "Yes"){?>
                  <td><img src="images/lesson_pass.png" alt="" id="personalimagephoto" title="Passed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                  <?php } else if($l_ispass == "No"){?>
                  <td><img src="images/lesson_notpass.png" alt="" id="personalimagephoto" title="Failed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                  <?php } else {?>
                  <td><img src="images/lesson_empty.png" alt="" id="personalimagephoto" title="New" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                  <?php } ?>
                </div>
				</tr>
                 
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_name = $v_rows["name"];
								}
								else{ $l_name = $rows["name"]; }
								?>
                  <tr>
                    <td width=""><b>Name:</b></td>
                    <td width="" class="tdname"><?php echo $l_name;?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_desc = $v_rows["lesson_descr"];
								}
								else{ $l_desc = $rows["lesson_descr"]; }
								?>
                  <tr>
                    <td width=""><b>Description:</b></td>
                    <td width=""><?php echo $l_desc; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$strEmpName = $v_rows["first_name"]." ".$v_rows["last_name"];
								}
								else{ $strEmpName = $rows["first_name"]." ".$rows["last_name"]; }
								?>
                                
                  <tr>
                    <td width=""><b>Employee:</b></td>
                    <td width=""><?php echo $strEmpName; ?></td>
                  </tr>
                  
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$loc_id = $v_rows["location_id"];
								}
								else{ $loc_id = $rows["location_id"]; }
								?>
                  <tr>
                    <td width=""><b>Location:</b></td>
                    <td width=""><?php echo getLocationName($loc_id); ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_at = $v_rows["author_type"];
								}
								else{ $l_at = $rows["author_type"]; }
								?>
                  <tr>
                    <td width=""><b>Author Type:</b></td>
                    <td width=""><?php echo $l_at; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_a = $v_rows["AutherName"];
								}
								else{ $l_a = $rows["AutherName"]; }
								?>
                  <tr>
                    <td width=""><b>Author:</b></td>
                    <td width=""><?php echo $l_a; ?></td>
                  </tr>
                  <?php /*?><tr>
								<td width="40%"><b>Location:</b></td>
								<td width="60%"><?php echo getLocationName($_SESSION['loc']); ?></td>
								</tr><?php */?>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_grp = $v_rows["groupname"];
								}
								else{ $l_grp = $rows["groupname"]; }
								?>
                  <tr>
                    <td width=""><b>Group:</b></td>
                    <td width=""><?php echo $l_grp; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_type = $v_rows["types"];
								}
								else{ $l_type = $rows["types"]; }
								?>
                  <tr>
                    <td width=""><b>Type:</b></td>
                    <td width=""><?php echo $l_type; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_pro = $v_rows["proname"];
								}
								else{ $l_pro = $rows["proname"]; }
								?>
                  <tr>
                    <td width=""><b>Product:</b></td>
                    <td width=""><?php echo $l_pro; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_req = $v_rows["lesson_req"];
								}
								else{ $l_req = $rows["lesson_req"]; }
								?>
                  <tr>
                    <td width=""><b>Required:</b></td>
                    <td width=""><?php echo $l_req; ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_pri = $v_rows["lesson_priority"];
								}
								else{ $l_pri = $rows["lesson_priority"]; }
								
								switch($l_pri){
									case 1 : $l_priority = "High"; break;
									case 2 : $l_priority = "Normal"; break;
									case 3 : $l_priority = "Low"; break;
									default : $l_priority = ""; break;
								}
								
								
								?>
                  <tr>
                    <td width=""><b>Priority:</b></td>
                    <td width=""><?php echo $l_priority;?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_sd = $v_rows["lesson_started_datetime"];
								}
								else{ $l_sd = $rows["lesson_started_datetime"]; }
								?>
                  <tr>
                    <td width=""><b>Start Date:</b></td>
                    <td width=""><?php if($l_sd!='0000-00-00 00:00:00' && $l_sd!='') {echo date('Y-m-d H:i',strtotime($l_sd));} ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_td = $v_rows["lesson_taken_datetime"];
								}
								else{ $l_td = $rows["lesson_taken_datetime"]; }
								?>
                  <tr>
                    <td width=""><b>Taken Date:</b></td>
                    <td width=""><?php if($l_td!='0000-00-00 00:00:00' && $l_td!='') { echo date('Y-m-d H:i',strtotime($l_td)); } ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_ed = $v_rows["lesson_ended_datetime"];
								}
								else{ $l_ed = $rows["lesson_ended_datetime"]; }
								?>
                  <tr>
                    <td width=""><b>End Date:</b></td>
                    <td width=""><?php if($l_ed!='0000-00-00 00:00:00' && $l_ed!='') {echo date('Y-m-d H:i',strtotime($l_ed));} ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_vp = $v_rows["valid_period"];
								}
								else{ $l_vp = $rows["valid_period"]; 
									$lessid=$rows['lesson_id'];
								}
								
								/* $svquery="SELECT date_format(DATE_ADD(created_datetime, INTERVAL ".$l_vp." DAY),'%Y-%m-%d') as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id=".$lessid; */
								
								$svquery="SELECT (DATE_ADD(created_datetime, INTERVAL ".$l_vp." DAY)) as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id=".$lessid;								
								
								$svresult=mysql_query($svquery);
								$svrow=mysql_fetch_object($svresult);
								$validperiod=$svrow->Valid_period;
								$DiffDate=$svrow->DiffDate;
								$strColor="black";
								if($DiffDate>0){
									$strColor="red";
								}

								/* Amit */ 
								/* $dayPeriod = "0";
									$datePeriod ='0000-00-00 00:00:00';

									if(isset($_REQUEST["lessid"])){
										if($v_rows["lesson_valid_period"] != "") {
											$dayPeriod = $v_rows["lesson_valid_period"];										
										}
									  $datePeriod = $v_rows["lesson_started_datetime"];		
									}
									else{ $l_vp = $rows["valid_period"]; 
										$lessid=$rows['lesson_id'];
										if($rows["lesson_valid_period"] != "") {
											$dayPeriod = $rows["lesson_valid_period"];										
										}
									  $datePeriod = $rows["lesson_started_datetime"];	
									}
								if($dayPeriod == '0' || $datePeriod == '0000-00-00 00:00:00') {
									$validperiod = "";
								} else {
								     $validperiod = date('Y-m-d H:i', strtotime($datePeriod. ' + '.$dayPeriod.' days'));
								} */
								
				
								?>
                  <tr>
                    <td width=""><b>Valid Period:</b></td>
                    <td width="" style="color:<?php echo $strColor;?>"><?php if($validperiod!='0000-00-00 00:00:00' && $validperiod!='') {echo date('Y-m-d H:i',strtotime($validperiod));} ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_score = $v_rows["lesson_video_score"];
								}
								else{ $l_score = $rows["lesson_video_score"]; }
								
								//$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos WHERE lesson_id='".$lessid."'";
								$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos JOIN training_videos ON training_videos.video_id = training_lesson_videos.video_id  WHERE training_videos.status='active' AND lesson_id='".$lessid."'";
								$lsresult=mysql_query($lsquery);
								$lsrow=mysql_fetch_object($lsresult);
								$intTotalVideo=$lsrow->TotalVideo;
								?>
                  <tr>
                    <td width=""><b>Score:</b></td>
                    <td width=""><?php echo $l_score." of ".$intTotalVideo; ?></td>
                  </tr>
                   
                </table>
                <?php 
						
						} ?>
                  </div>
           		</div>
          </div>
         <div class="span4 profile-left" id="comp_tab" style="float: right;margin-left: 0;width: 33%;">
          <div class="widgetbox company-photo">
            <h4 class="widgettitle">Details</h4>
            <div class="widgetcontent" style="padding-bottom: 25px;padding-top: 0 !important;">
             
              <table class="receipt" width="100%" id="passdata">
				<!-- <tr> -->
				 <?php 
						if(isset($_REQUEST["lessid"])){
							$l_ispass = $v_rows["lesson_pass"];
						}
						else{ $l_ispass = $c_rows["lesson_pass"]; }
						?>
            <?php if(($cnumrows>0) && $l_ispass=="Yes") {  ?>
            <tr>	
              <div class="profilethumb" style="text-align:left;">
              
                
                <?php 
									
								if(isset($_REQUEST["lessid"])){
									$l_image = $v_rows["lesson_image"];
								}
								else{ $l_image = $c_rows["lesson_image"]; }

								if($l_image == "") { ?>
                <td style="width:25%;"><img src="images/noimage.png" alt="" id="personalimagephoto" style="width:80px; height:80px;" /></td>
                <?php } else {?>
                <td style="width:25%;"><img onerror="this.src='images/noimage.png'" src="<?php echo APIPHP; ?>/images/<?php echo $l_image;?>" alt="" id="personalimagephoto" style="width:80px; height:80px;" /></td>
                <?php } ?>
                &nbsp;&nbsp;
                <?php 
									if(isset($_REQUEST["lessid"])){
										$l_takentime = $v_rows["lesson_taken_datetime"];
									}
									else{ $l_takentime = $c_rows["lesson_taken_datetime"]; }
									
									
								
                                 ?>
                
                <?php if($l_ispass == "Yes") { ?>
                <td><img src="images/lesson_pass.png" alt="" id="personalimagephoto" title="Passed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } else if($l_ispass == "No"){?>
                <td><img src="images/lesson_notpass.png" alt="" id="personalimagephoto" title="Failed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } else {?>
                <td><img src="images/lesson_empty.png" alt="" id="personalimagephoto" title="New" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } ?>
              </div>
              
              <br />
				</tr>
			  
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_name = $v_rows["name"];
								}
								else{ $l_name = $c_rows["name"]; }
								?>
                <tr>
                  <td width=""><b>Name:</b></td>
                  <td width="" class="tdname"><?php echo $l_name;?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_desc = $v_rows["lesson_descr"];
								}
								else{ $l_desc = $c_rows["lesson_descr"]; }
								?>
                <tr>
                  <td width=""><b>Description:</b></td>
                  <td width=""><?php echo $l_desc; ?></td>
                </tr>
				<?php 
								if(isset($_REQUEST["lessid"])){
								$strEmpName = $v_rows["first_name"]." ".$v_rows["last_name"];
								}
								else{ $strEmpName = $c_rows["first_name"]." ".$c_rows["last_name"]; }
								?>
                                
                  <tr>
                    <td width=""><b>Employee:</b></td>
                    <td width=""><?php echo $strEmpName; ?></td>
                  </tr>
                  
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$loc_id = $v_rows["location_id"];
								}
								else{ $loc_id = $c_rows["location_id"]; }
								?>
                  <tr>
                    <td width=""><b>Location:</b></td>
                    <td width=""><?php echo getLocationName($loc_id); ?></td>
                  </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_at = $v_rows["author_type"];
								}
								else{ $l_at = $c_rows["author_type"]; }
								?>
                <tr>
                  <td width=""><b>Author Type:</b></td>
                  <td width=""><?php echo $l_at; ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_a = $v_rows["AutherName"];
								}
								else{ $l_a = $c_rows["AutherName"]; }
								?>
                <tr>
                  <td width=""><b>Author:</b></td>
                  <td width=""><?php echo $l_a; ?></td>
                </tr>
                <?php /*?><tr>
								<td width="40%"><b>Location:</b></td>
								<td width="60%"><?php echo getLocationName($_SESSION['loc']); ?></td>
								</tr><?php */?>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_grp = $v_rows["groupname"];
								}
								else{ $l_grp = $c_rows["groupname"]; }
								?>
                <tr>
                  <td width=""><b>Group:</b></td>
                  <td width=""><?php echo $l_grp; ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_type = $v_rows["types"];
								}
								else{ $l_type = $c_rows["types"]; }
								?>
                <tr>
                  <td width=""><b>Type:</b></td>
                  <td width=""><?php echo $l_type; ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_pro = $v_rows["proname"];
								}
								else{ $l_pro = $c_rows["proname"]; }
								?>
                <tr>
                  <td width=""><b>Product:</b></td>
                  <td width=""><?php echo $l_pro; ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_req = $v_rows["lesson_req"];
								}
								else{ $l_req = $c_rows["lesson_req"]; }
								?>
                <tr>
                  <td width=""><b>Required:</b></td>
                  <td width=""><?php echo $l_req; ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_pri = $v_rows["lesson_priority"];
								}
								else{ $l_pri = $c_rows["lesson_priority"]; }
								switch($l_pri){
									case 1 : $l_priority = "High"; break;
									case 2 : $l_priority = "Normal"; break;
									case 3 : $l_priority = "Low"; break;
									default : $l_priority = ""; break;
								}
								?>
                <tr>
                  <td width=""><b>Priority:</b></td>
                  <td width=""><?php echo $l_priority;?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_sd = $v_rows["lesson_started_datetime"];
								}
								else{ $l_sd = $c_rows["lesson_started_datetime"]; }
								?>
                <tr>
                  <td width=""><b>Start Date:</b></td>
                  <td width=""><?php if($l_sd!='0000-00-00 00:00:00' && $l_sd!='') {echo date('Y-m-d H:i',strtotime($l_sd));} ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_td = $v_rows["lesson_taken_datetime"];
								}
								else{ $l_td = $c_rows["lesson_taken_datetime"]; }
								?>
                <tr>
                  <td width=""><b>Taken Date:</b></td>
                  <td width=""><?php if($l_td!='0000-00-00 00:00:00' && $l_td!='') { echo date('Y-m-d H:i',strtotime($l_td)); } ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_ed = $v_rows["lesson_ended_datetime"];
								}
								else{ $l_ed = $c_rows["lesson_ended_datetime"]; }
								?>
                <tr>
                  <td width=""><b>End Date:</b></td>
                  <td width=""><?php if($l_ed!='0000-00-00 00:00:00' && $l_ed!='') {echo date('Y-m-d H:i',strtotime($l_ed));} ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_vp = $v_rows["valid_period"];
								}
								else{ $l_vp = $c_rows["valid_period"]; 
									$lessid=$c_rows['lesson_id'];
								}
								
								/* $svquery="SELECT date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d') as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id=".$lessid; */
								
								$svquery="SELECT (DATE_ADD(created_datetime, INTERVAL ".$l_vp." DAY)) as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id=".$lessid;		
										  
								$svresult=mysql_query($svquery);
								$svrow=mysql_fetch_object($svresult);
								$validperiod=$svrow->Valid_period;
								$DiffDate=$svrow->DiffDate;
								/* Amit */ 
									/* $dayPeriod = "0";
									$datePeriod ='0000-00-00 00:00:00';

									if(isset($_REQUEST["lessid"])){
										if($v_rows["lesson_valid_period"] != "") {
											$dayPeriod = $v_rows["lesson_valid_period"];										
										}
									  $datePeriod = $v_rows["lesson_started_datetime"];		
									}
									else{ 
										if($c_rows["lesson_valid_period"] != "") {
											$dayPeriod = $c_rows["lesson_valid_period"];										
										}
									  $datePeriod = $c_rows["lesson_started_datetime"];	
									}
								if($dayPeriod == '0' || $datePeriod == '0000-00-00 00:00:00') {
									$validperiod = "";
								} else {
								     $validperiod = date('Y-m-d H:i', strtotime($datePeriod. ' + '.$dayPeriod.' days'));
								} */

								?>
                <tr>
                  <td width=""><b>Valid Period:</b></td>
                  <td width=""><?php if($validperiod!='0000-00-00 00:00:00' && $validperiod!='') {echo date('Y-m-d H:i',strtotime($validperiod));} ?></td>
                </tr>
                <?php 
								if(isset($_REQUEST["lessid"])){
								$l_score = $v_rows["lesson_video_score"];
								}
								else{ $l_score = $c_rows["lesson_video_score"]; }

								
								//$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos WHERE lesson_id='".$lessid."'";
								$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos JOIN training_videos ON training_videos.video_id = training_lesson_videos.video_id  WHERE training_videos.status='active' AND lesson_id='".$lessid."'";
								$lsresult=mysql_query($lsquery);
								$lsrow=mysql_fetch_object($lsresult);
								$intTotalVideo=$lsrow->TotalVideo;
								
								?>
                <tr>
                  <td width=""><b>Score:</b></td>
                  <?php if($intTotalVideo<$l_score){ ?>
                  <td width=""><?php echo $intTotalVideo ." of ".$intTotalVideo; ?></td>
                  <?php }else{?>
                  <td width=""><?php echo $l_score ." of ".$intTotalVideo; ?></td>
                  <?php } ?>
                </tr>
                <?php } else { echo "Nothing to Display";} ?>
              </table>
           
          <?php // } else { echo "Nothing to Display";} ?>
        </div>
         
          <input type="hidden" id="hidLessonPass" name="hidLessonPass" value="<?php echo $l_ispass;?>">
          <input type="hidden" id="hidLessonID" name="hidLessonID" value="<?php echo $lessid;?>">
         </div>
         
         </div>
         <?php include_once 'require/footer.php';?>
        </div>

      </div>
    </div>
  </div>
  </div>
  <?php // include_once 'require/footer.php';?>
  </body>
  </html>
  <script>
  function get_receipt_new(lessid,employee_id,tet_id,row_no){
  			jQuery('.line3').removeClass('line3');
			jQuery('#'+lessid+'_'+employee_id + '_' + row_no).addClass('line3'); 
  		jQuery.ajax({
			url:'learntube_receipt.php?flag',
			type:'GET',
			data:{lessid:lessid,employee_id:employee_id,tet_id:tet_id,type:'new'},
			success:function(data){
				jQuery('#new_tab').html(data);
			}	
		});
  }
  function get_receipt_comp(lessid,employee_id){
  		jQuery('.line4').removeClass('line4');
		jQuery('#'+employee_id+''+lessid).addClass('line4'); 	
  		jQuery.ajax({
			url:'learntube_receipt.php?flag',
			type:'GET',
			data:{lessid:lessid,employee_id:employee_id,type:'comp'},
			success:function(data){
				jQuery('#comp_tab').html(data);
			}	
		});
  }
  </script>
  
  