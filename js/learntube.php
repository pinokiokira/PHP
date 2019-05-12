<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

//echo $_SESSION['client_id']."==>".$_SESSION['name']."==>".$_SESSION['email'];
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
if(mysql_real_escape_string(isset($_GET["lessid"])) && !mysql_real_escape_string(empty($_GET["lessid"]))){
$lessid = mysql_real_escape_string($_GET["lessid"]);
$employee_id=$_REQUEST['employee_id'];

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
WHERE training_lessons.lesson_id='$lessid' AND training_employee_lessons.employee_id IN('".$employeeID."')";
$v_result = mysql_query($v_sql) or die(mysql_error());
$vnumrows=mysql_num_rows($v_result);
$v_rows = mysql_fetch_array($v_result);
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
training_employee_transactions.tet_id,
employees_les.first_name, employees_les.last_name,
locations_les.name as loc_name
FROM
training_lessons
LEFT JOIN training_employee_lessons ON training_employee_lessons.lesson_id = training_lessons.lesson_id
LEFT JOIN training_products ON training_lessons.product = training_products.product_id
LEFT JOIN training_employee_transactions  ON training_lessons.lesson_id = training_employee_transactions.lesson_id and training_employee_transactions.purchase_author_id= training_lessons.author
LEFT JOIN training_video_groups ON training_lessons.group = training_video_groups.id
LEFT JOIN training_video_types ON training_lessons.type = training_video_types.id
LEFT JOIN employees as employees_les ON employees_les.id = training_employee_lessons.employee_id

LEFT JOIN employees ON employees.id = training_lessons.author
LEFT JOIN locations ON locations.id = training_lessons.author
LEFT JOIN users ON users.id = training_lessons.author
LEFT JOIN locations as locations_les ON locations_les.id = training_employee_lessons.location_id

WHERE training_employee_lessons.employee_id IN (".$employeeID.") AND training_lessons.status='active' AND
(training_employee_lessons.lesson_pass = '' OR training_employee_lessons.lesson_pass = 'No')
ORDER BY training_lessons.`name`"; //GROUP BY training_lessons.lesson_id


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
 
ORDER BY training_lessons.`name`";
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
<title>SoftPoint | TeamPanel</title>
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
/*.error{
	color: #FF0000;
	padding-left:10px;
}
.stdform label {
	width:90px !important;
	padding: 5px 10px 0 0 !important;
}
.stdform select {
	width:220px !important;
}
.tabbable > .tab-content {
	border: 2px solid #0866c6;
border-top: 0;
}

.tabbable > .nav-tabs {
	border: 2px solid #0866c6;
border-bottom: 0;
}

.DataTables_sort_icon{  background-image: url("images/sort.png");}*/

.sorting_asc {
background: url('images/sort_asc.png') no-repeat center right !important;
background-color: #333333 !important;
}
.sorting_desc {
background: url('images/sort_desc.png') no-repeat center right !important;
background-color: #333333 !important;
}
table.table tbody tr.ui-selected,table.table tfoot tr.ui-selected{background-color:rgb(128,128,128);}
//table.table tbody tr .ui-selected{background: #ffffff;}
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
#licence_table th{ font-size:11px;}

.table th, .table td
{
	padding:1%;
}

.tdname{
	font-size:1.1em;
	font-weight:bold;
}

.ui-tabs-panel {
    color: #000000;
}

@media screen and (max-width: 1152px) {
	.table th, .table td
	{
		padding:0.4%;
	}
}
</style>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
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

	/*if(tabno!='ui-id-1')
	{
		jQuery("#new_tab").hide();
		jQuery("#comp_tab").show();
	}else if(tabno=='1'){
		jQuery("#new_tab").hide();
		jQuery("#comp_tab").show();
	}else if(tabno!='ui-id-2')
	{
		jQuery("#new_tab").show();
		jQuery("#comp_tab").hide();
	}
	else
	{
		jQuery("#new_tab").hide();
		jQuery("#comp_tab").show();
        //jQuery("#passdata").css("display","none");
	}*/
	
	
	
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
				"aaSorting": [[1,'asc']],
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
</script>
</head>

<body>

<div class="mainwrapper">
    
    <?php require_once('require/top.php');?>
    
    <?php require_once('require/left_nav.php');?>
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="dashboard.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>LearnTube</li>
            
            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
                <ul class="dropdown-menu pull-right skin-color">
                    <li><a href="default">Default</a></li>
                    <li><a href="navyblue">Navy Blue</a></li>
                    <li><a href="palegreen">Pale Green</a></li>
                    <li><a href="red">Red</a></li>
                    <li><a href="green">Green</a></li>
                    <li><a href="brown">Brown</a></li>
                </ul>
            </li>
        </ul>
        
        <div class="pageheader">
            <!--<form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>-->
            <div class="pageicon"><span class="iconfa-facetime-video"></span></div>
            <div class="pagetitle">
                <h5>The following lessons are assigned to you</h5>
                <h1>LearnTube</h1>
                <a href="learntube_training_lessons_take_lesson.php?lessonid=1&employee_id=1"><img src="images/pricetag.png" border="0"  style="height:16px;width:22px;"></a>
                <a href="learntube_showdetails.php?lesson_id=1$client_id=1?iframe=true&width=550&height=460"  rel="prettyPhoto[iframes]" class="zoom" data-gal="prettyPhoto[gallery]"><img src="images/pricetag.png" border="0"  style="height:16px;width:22px;"></a>
            </div>
        </div><!--pageheader-->
       	<?php //echo "<pre>"; print_r($_SESSION); 
		
		?>
        <div class="maincontent">
             <div class="maincontentinner">
                <div class="row-fluid">
         		<div class="span13" style="width:70%;"> 
					<div class="tabbedwidget tab-primary">
						<ul>
							<li><a href="#e-7">New</a></li>
							<li><a href="#e-8">Completed</a></li>
						</ul>
						<div id="e-7">
                        	<div style="overflow-x:scroll;">
							<table id="licence_table" class="table table-bordered responsive">
                    			<colgroup>
									<col class="con0" style="align: center;" />
									<col class="con1" />
									<col class="con0" />
                                    <col class="con1" />
									<col class="con0" />
									<col class="con1" />
									<col style="overflow:hidden;" class="con0" />
									<col style="overflow:hidden;" class="con1" />
									<col class="con0" />
									<col class="con1" />
									<col class="con0" />
								</colgroup>
                   				 <thead>
                        		 <tr>
									<th style="width:7%;" class="head1">Image</th> 	 	 	 	 	 	 	 	
									<th style="width:24%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Employee</th>
                                    <!--  
                                    <th style="width:12%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Location</th>
									-->
									<th style="width:12%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Name</th>
									<th style="width:12%;" class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Description</th>
									<th style="width:26%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Product</th>
									<!-- 
									<th style="width:7%;" class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Type</th>
									<th style="width:7%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Group</th>
									 -->
									<th style="width:6%;" class="head1" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Req</th>
									<th style="width:6%;" class="head0" role="columnheader" aria-controls="dyntable" aria-sort="ascending" aria-label="Status: activate to sort column ascending">Valid</th>
									<th style="width:7%;" class="head1">Action</th>
                        		 </tr>
                   				 </thead>
							 	 <tbody>
								 <?php 
								 $i = 1;
								// if($numrows > 1 || $numrows == 1){
								 while($rowss  = mysql_fetch_array($result2)){ ?>
                      			 <tr id="<?php echo $rowss["lesson_id"]._.$rowss["employee_id"]; ?>" class="gradeX cl_order <?php if(($rowss["lesson_id"]==$_REQUEST['lessid'] && $rowss["employee_id"]==$_REQUEST['employee_id']) && ($i==1 && $_REQUEST['lessid']=="")) {?>g_item odd ui-selectee ui-selected<?php } ?>" style="cursor:pointer;height:81px" onClick="window.location.href='learntube.php?flag&lessid=<?php echo $rowss["lesson_id"];?>&employee_id=<?php echo $rowss['employee_id'] ?>'">
									<td style="width:7%;">
									<?php if($rowss["lesson_image"] == "") { ?>
									<img src="images/noimage.png" alt="" id="personalimagephoto" width="60" height="60" />
									<?php } else {?>
									<img src="<?php echo API; ?>/images/<?php echo $rowss["lesson_image"];?>" alt="" id="personalimagephoto" width="60" height="60" />
									<?php } ?>
									</td>
									<td style="width:24%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["first_name"]." ".$rowss["last_name"]." (".$rowss["employee_id"].") at ".$rowss["loc_name"]." (ID# ".$rowss["location_id"].")";?></td>
									<!-- 
									<td style="width:12%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["loc_name"]." (ID# ".$rowss["location_id"].")";?></td>
                                     -->
                                    <td style="width:12%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["name"];?></td>
									<td style="width:12%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["lesson_descr"];?></td>
									<td style="width:26%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["proname"]."<br/>".$rowss["types"]."<br/>".$rowss["groupname"];?></td>
									<!-- 
									<td style="width:7%; overflow:hidden;word-wrap:break-word;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["types"];?></td>
									<td style="width:7%;overflow:hidden;word-wrap:break-word;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["groupname"];?></td>
									 -->
									<td style="width:6%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["lesson_req"];?></td>
									<td style="width:6%;" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rowss["valid_period"];?></td>
									<td style="width:7%;" class="center" <?php if($i==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>>
									<a href="learntube_training_lessons_take_lesson.php?lessonid=<?php echo $rowss["lesson_id"];?>&employee_id=<?php echo $rowss['employee_id'] ?>"><img src="images/279-videocamera.png" border="0"  style="height:16px;width:22px;"></a>
									</td>
                        		</tr>
								<?php $i++; } //} ?>
								<?php //else {?>
								<!--<tr>
									<td>There is no data to display.</td>
								</tr>-->
								<?php //} ?>
                    			</tbody>
               	 			</table>
                            </div>
						</div>
						<div id="e-8">
                        	<div style="overflow-x:scroll;">
							<table id="licence_table" class="table table-bordered responsive">
                    			<colgroup>
									<col class="con0" style="align: center;" />
									<col class="con1" />
									<col class="con0" />
									<col class="con1" />
									<col class="con0" />
									<col class="con1" />
									<col class="con0" />
									<col class="con1" />
									<col class="con0" />
								</colgroup>
                   				 <thead>
                        		 <tr>
									<th style="width:7%;" class="head1">Image</th> 
									<th style="width:12%;" class="head0">Employee</th>
                                    <th style="width:12%;" class="head0">Location</th>
									<th style="width:12%;" class="head0">Name</th>
									<th style="width:12%;" class="head1">Description</th>
									<th style="width:12%;" class="head0">Product</th>
									<th style="width:7%;" class="head1">Type</th>
									<th style="width:7%;" class="head0">Group</th>
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
                      			 <tr class="gradeX cl_order <?php if(($rows_comp["lesson_id"]==$_REQUEST['lessid'] && $rows_comp["employee_id"]==$_REQUEST['employee_id'])  || ($j==1 && $_REQUEST['lessid']=="")) {?>g_item odd ui-selectee ui-selected<?php } ?>" style="cursor:pointer;" onClick="window.location.href='learntube.php?flag&lessid=<?php echo $rows_comp["lesson_id"];?>&employee_id=<?php echo $rows_comp['employee_id'] ?>#e-8'">
									<td style="width:7%;">
									<?php if($rows_comp["lesson_image"] == "") { ?>
									<img src="images/noimage.png" alt="" id="personalimagephoto" width="60" height="60" />
									<?php } else {?>
									<img src="<?php echo API; ?>/images/<?php echo $rows_comp["lesson_image"];?>" alt="" id="personalimagephoto" width="60" height="60" />
									<?php } ?>
									</td>
									<td style="width:12%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["first_name"]." ".$rows_comp["last_name"];?></td>
									<td style="width:12%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["loc_name"]." (ID# ".$rows_comp["location_id"].")";?></td>
                                    <td style="width:12%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["name"];?></td>
									<td style="width:12%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["lesson_descr"];?></td>
									<td style="width:12%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["proname"];?></td>
									<td style="width:7%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["types"];?></td>
									<td style="width:7%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["groupname"];?></td>
									<td style="width:6%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["lesson_req"];?></td>
									<td style="width:6%;" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>><?php echo $rows_comp["valid_period"];?></td>
									<td style="width:7%;" class="center" <?php if($j==1 && !isset($_REQUEST["lessid"])) { ?>style="background-color:#cccccc;"<?php }?>>
									<a href="learntube_training_lessons_take_lesson.php?lessonid=<?php echo $rows_comp["lesson_id"];?>&employee_id=<?php echo $rows_comp['employee_id'] ?>"><img src="images/279-videocamera.png" border="0"  style="height:16px;width:22px;"></a>
									</td>
                        		</tr>
								<?php $j++; } ?>
                    			</tbody>
               	 			</table>
                            </div>
						</div>
						</div>
					</div> <!--span13-->
					<?php //if(mysql_real_escape_string(isset($_GET["flag"]))) { ?>
					<div class="span4 profile-left" id="new_tab">
						<?php  
							if(isset($_REQUEST["lessid"])){
								$l_ispass = $v_rows["lesson_pass"];
							}
							else{ $l_ispass = $rows["lesson_pass"]; }
						if($numrows>0 && $l_ispass!="Yes") { ?>
							<div class="widgetbox company-photo">
							  <h4 class="widgettitle">Details</h4>
							  <div class="widgetcontent">
                                <div class="profilethumb" style="text-align:left;">
								<!--<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />-->
								<?php 
									
								if(isset($_REQUEST["lessid"])){
									$l_image = $v_rows["lesson_image"];
								}
								else{ $l_image = $rows["lesson_image"]; }

								if($l_image == "") { ?>
									<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />
									<?php } else {?>
									<img src="<?php echo API; ?>/images/<?php echo $l_image;?>" alt="" id="personalimagephoto" width="80" height="80" />
									<?php } ?>
								&nbsp;&nbsp;
								<?php 
									if(isset($_REQUEST["lessid"])){
										$l_takentime = $v_rows["lesson_taken_datetime"];
									}
									else{ $l_takentime = $rows["lesson_taken_datetime"]; }
									
									
								
                                //if($l_takentime == "" || $l_takentime == "0000-00-00 00:00:00") { ?>
								<!--<img src="images/lesson_nottaken.png" alt="" id="personalimagephoto" width="80" height="80" title="Not Taken" />-->
								<?php  if($l_ispass == "Yes"){?>
								<img src="images/lesson_pass.png" alt="" id="personalimagephoto" title="Passed" />
								<?php } else if($l_ispass == "No"){?>
								<img src="images/lesson_notpass.png" alt="" id="personalimagephoto" title="Not Passed" />
								<?php } else {?>
								<img src="images/lesson_empty.png" alt="" id="personalimagephoto" title="" />
								<?php } ?>
								</div><!--profilethumb-->
								<br />
								<table width="100%">
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_name = $v_rows["name"];
								}
								else{ $l_name = $rows["name"]; }
								?>
								<tr>
								<td width="40%"><b>Name:</b></td>
								<td width="60%" class="tdname"><?php echo $l_name;?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_desc = $v_rows["lesson_descr"];
								}
								else{ $l_desc = $rows["lesson_descr"]; }
								?>
								<tr>
								<td width="40%"><b>Description:</b></td>
								<td width="60%"><?php echo $l_desc; ?></td>
								</tr>
                                <?php 
								if(isset($_REQUEST["lessid"])){
								$strEmpName = $v_rows["first_name"]." ".$v_rows["last_name"];
								}
								else{ $strEmpName = $rows["first_name"]." ".$rows["last_name"]; }
								?>
                                <tr>
								<td width="40%"><b>Employee:</b></td>
								<td width="60%"><?php echo $strEmpName; ?></td>
								</tr>
                                <?php 
								if(isset($_REQUEST["lessid"])){
								$loc_id = $v_rows["location_id"];
								}
								else{ $loc_id = $rows["location_id"]; }
								?>
                                <tr>
								<td width="40%"><b>Location:</b></td>
								<td width="60%"><?php echo getLocationName($loc_id); ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_at = $v_rows["author_type"];
								}
								else{ $l_at = $rows["author_type"]; }
								?>
								<tr>
								<td width="40%"><b>Author Type:</b></td>
								<td width="60%"><?php echo $l_at; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_a = $v_rows["AutherName"];
								}
								else{ $l_a = $rows["AutherName"]; }
								?>
								<tr>
								<td width="40%"><b>Author:</b></td>
								<td width="60%"><?php echo $l_a; ?></td>
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
								<td width="40%"><b>Group:</b></td>
								<td width="60%"><?php echo $l_grp; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_type = $v_rows["types"];
								}
								else{ $l_type = $rows["types"]; }
								?>
								<tr>
								<td width="40%"><b>Type:</b></td>
								<td width="60%"><?php echo $l_type; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_pro = $v_rows["proname"];
								}
								else{ $l_pro = $rows["proname"]; }
								?>
								<tr>
								<td width="40%"><b>Product:</b></td>
								<td width="60%"><?php echo $l_pro; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_req = $v_rows["lesson_req"];
								}
								else{ $l_req = $rows["lesson_req"]; }
								?>
								<tr>
								<td width="40%"><b>Required:</b></td>
								<td width="60%"><?php echo $l_req; ?></td>
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
								<td width="40%"><b>Priority:</b></td>
								<td width="60%"><?php echo $l_priority;?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_sd = $v_rows["lesson_started_datetime"];
								}
								else{ $l_sd = $rows["lesson_started_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>Start Date:</b></td>
								<td width="60%"><?php if($l_sd!='0000-00-00 00:00:00' && $l_sd!='') {echo date('m-d-Y H:m',strtotime($l_sd));} ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_td = $v_rows["lesson_taken_datetime"];
								}
								else{ $l_td = $rows["lesson_taken_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>Taken Date:</b></td>
								<td width="60%"><?php if($l_td!='0000-00-00 00:00:00' && $l_td!='') { echo date('m-d-Y H:m',strtotime($l_td)); } ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_ed = $v_rows["lesson_ended_datetime"];
								}
								else{ $l_ed = $rows["lesson_ended_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>End Date:</b></td>
								<td width="60%"><?php if($l_ed!='0000-00-00 00:00:00' && $l_ed!='') {echo date('m-d-Y H:m',strtotime($l_ed));} ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_vp = $v_rows["valid_period"];
								}
								else{ $l_vp = $rows["valid_period"]; 
									$lessid=$rows['lesson_id'];
								}
								
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
								
								?>
								<tr>
								<td width="40%"><b>Valid Period:</b></td>
								<td width="60%" style="color:<?php echo $strColor;?>"><?php if($validperiod!='0000-00-00 00:00:00' && $validperiod!='') {echo date('m-d-Y H:m',strtotime($validperiod));} ?></td>
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
								<td width="40%"><b>Score:</b></td>
								<td width="60%"><?php echo $l_score." of ".$intTotalVideo; ?></td>
								</tr>
								</table>
							  </div>
						</div>
						<?php 
						
						} ?>
                    </div> <!--span4 profile-left-->
					<div class="span4 profile-left" id="comp_tab">
						<?php 
						if(isset($_REQUEST["lessid"])){
							$l_ispass = $v_rows["lesson_pass"];
						}
						else{ $l_ispass = $c_rows["lesson_pass"]; }
						if(($cnumrows>0) && $l_ispass=="Yes") { ?>
							<div class="widgetbox company-photo">
							  <h4 class="widgettitle">Details</h4>
							  <div class="widgetcontent">
                                <div class="profilethumb" style="text-align:left;">
								<!--<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />-->
								<?php 
									
								if(isset($_REQUEST["lessid"])){
									$l_image = $v_rows["lesson_image"];
								}
								else{ $l_image = $c_rows["lesson_image"]; }

								if($l_image == "") { ?>
									<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />
									<?php } else {?>
									<img src="<?php echo API; ?>/images/<?php echo $l_image;?>" alt="" id="personalimagephoto" width="80" height="80" />
									<?php } ?>
								&nbsp;&nbsp;
								<?php 
									if(isset($_REQUEST["lessid"])){
										$l_takentime = $v_rows["lesson_taken_datetime"];
									}
									else{ $l_takentime = $c_rows["lesson_taken_datetime"]; }
									
									
								
                                //if($l_takentime == "" || $l_takentime == "0000-00-00 00:00:00") { ?>
								<!--<img src="images/lesson_nottaken.png" alt="" id="personalimagephoto" width="80" height="80" title="Not Taken" />-->
								<?php if($l_ispass == "Yes"){?>
								<img src="images/lesson_pass.png" alt="" id="personalimagephoto" title="Passed" />
								<?php } else if($l_ispass == "No"){?>
								<img src="images/lesson_notpass.png" alt="" id="personalimagephoto" title="Not Passed" />
								<?php } else {?>
								<img src="images/lesson_empty.png" alt="" id="personalimagephoto" title="" />
								<?php } ?>
																
								</div><!--profilethumb-->
								<br />
								<table width="100%" id="passdata">
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_name = $v_rows["name"];
								}
								else{ $l_name = $c_rows["name"]; }
								?>
								<tr>
								<td width="40%"><b>Name:</b></td>
								<td width="60%" class="tdname"><?php echo $l_name;?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_desc = $v_rows["lesson_descr"];
								}
								else{ $l_desc = $c_rows["lesson_descr"]; }
								?>
								<tr>
								<td width="40%"><b>Description:</b></td>
								<td width="60%"><?php echo $l_desc; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_at = $v_rows["author_type"];
								}
								else{ $l_at = $c_rows["author_type"]; }
								?>
								<tr>
								<td width="40%"><b>Author Type:</b></td>
								<td width="60%"><?php echo $l_at; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_a = $v_rows["AutherName"];
								}
								else{ $l_a = $c_rows["AutherName"]; }
								?>
								<tr>
								<td width="40%"><b>Author:</b></td>
								<td width="60%"><?php echo $l_a; ?></td>
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
								<td width="40%"><b>Group:</b></td>
								<td width="60%"><?php echo $l_grp; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_type = $v_rows["types"];
								}
								else{ $l_type = $c_rows["types"]; }
								?>
								<tr>
								<td width="40%"><b>Type:</b></td>
								<td width="60%"><?php echo $l_type; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_pro = $v_rows["proname"];
								}
								else{ $l_pro = $c_rows["proname"]; }
								?>
								<tr>
								<td width="40%"><b>Product:</b></td>
								<td width="60%"><?php echo $l_pro; ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_req = $v_rows["lesson_req"];
								}
								else{ $l_req = $c_rows["lesson_req"]; }
								?>
								<tr>
								<td width="40%"><b>Required:</b></td>
								<td width="60%"><?php echo $l_req; ?></td>
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
								<td width="40%"><b>Priority:</b></td>
								<td width="60%"><?php echo $l_priority;?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_sd = $v_rows["lesson_started_datetime"];
								}
								else{ $l_sd = $c_rows["lesson_started_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>Start Date:</b></td>
								<td width="60%"><?php if($l_sd!='0000-00-00 00:00:00' && $l_sd!='') {echo date('m-d-Y H:m',strtotime($l_sd));} ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_td = $v_rows["lesson_taken_datetime"];
								}
								else{ $l_td = $c_rows["lesson_taken_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>Taken Date:</b></td>
								<td width="60%"><?php if($l_td!='0000-00-00 00:00:00' && $l_td!='') { echo date('m-d-Y H:m',strtotime($l_td)); } ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_ed = $v_rows["lesson_ended_datetime"];
								}
								else{ $l_ed = $c_rows["lesson_ended_datetime"]; }
								?>
								<tr>
								<td width="40%"><b>End Date:</b></td>
								<td width="60%"><?php if($l_ed!='0000-00-00 00:00:00' && $l_ed!='') {echo date('m-d-Y H:m',strtotime($l_ed));} ?></td>
								</tr>
								<?php 
								if(isset($_REQUEST["lessid"])){
								$l_vp = $v_rows["valid_period"];
								}
								else{ $l_vp = $c_rows["valid_period"]; 
									$lessid=$c_rows['lesson_id'];
								}
								
								$svquery="SELECT DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY) as Valid_period,
										  DATEDIFF(now(),date_format(DATE_ADD(created_datetime,INTERVAL ".$l_vp." DAY),'%Y-%m-%d')) AS DiffDate
										  FROM training_lessons WHERE lesson_id=".$lessid;
								$svresult=mysql_query($svquery);
								$svrow=mysql_fetch_object($svresult);
								$validperiod=$svrow->Valid_period;
								$DiffDate=$svrow->DiffDate;
								?>
								<tr>
								<td width="40%"><b>Valid Period:</b></td>
								<td width="60%"><?php if($validperiod!='0000-00-00 00:00:00' && $validperiod!='') {echo date('m-d-Y H:m',strtotime($validperiod));} ?></td>
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
								<td width="40%"><b>Score:</b></td>
								<td width="60%"><?php echo $l_score ." of ".$intTotalVideo; ?></td>
								</tr>
								</table>
							  </div>
						</div>
						<?php } ?>
                    </div> <!--span4 profile-left-->
					<?php //} 
						
					 ?>
					 <input type="hidden" id="hidLessonPass" name="hidLessonPass" value="<?php echo $l_ispass;?>">
					 <input type="hidden" id="hidLessonID" name="hidLessonID" value="<?php echo $lessid;?>">
        </div><!--row-fluid-->
                    
                    <?php include_once 'require/footer.php';?><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
       
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->



</body>
</html>
