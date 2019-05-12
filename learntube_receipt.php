<?php
require_once 'require/security.php';
include 'config/accessConfig.php'; 

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

if(isset($_REQUEST['tet_id']) && $_REQUEST['tet_id']>0){
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
<style>
.receipt, .receipt tbody, .receipt thead, .receipt tr, .receipt th, .receipt td{
line-height:30px !important;
}
</style>
				<?php if($_REQUEST['type']=='new'){ ?>	
				<div class="widgetbox company-photo">                
              	<h4 class="widgettitle">Details</h4>
              	<div class="widgetcontent"   style="padding-bottom: 25px;padding-top: 0 !important;">
                
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
                    <td width=""><?php if($l_sd!='0000-00-00 00:00:00' && $l_sd!='') {echo date('Y-m-d H:m',strtotime($l_sd));} ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_td = $v_rows["lesson_taken_datetime"];
								}
								else{ $l_td = $rows["lesson_taken_datetime"]; }
								?>
                  <tr>
                    <td width=""><b>Taken Date:</b></td>
                    <td width=""><?php if($l_td!='0000-00-00 00:00:00' && $l_td!='') { echo date('Y-m-d H:m',strtotime($l_td)); } ?></td>
                  </tr>
                  <?php 
								if(isset($_REQUEST["lessid"])){
								$l_ed = $v_rows["lesson_ended_datetime"];
								}
								else{ $l_ed = $rows["lesson_ended_datetime"]; }
								?>
                  <tr>
                    <td width=""><b>End Date:</b></td>
                    <td width=""><?php if($l_ed!='0000-00-00 00:00:00' && $l_ed!='') {echo date('Y-m-d H:m',strtotime($l_ed));} ?></td>
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
								// lesson_started_datetime
								
								/* Written by Amit*/
								/* if($c_rows["lesson_valid_period"] == "0" || $v_rows["lesson_valid_period"] == "0") {
									$validperiod = "";
								} else {
									$dayPeriod = "0";
									$datePeriod = $v_rows["lesson_started_datetime"];
									if($v_rows["lesson_valid_period"] != "") {
										$dayPeriod = $v_rows["lesson_valid_period"];										
									}
								     $validperiod = date('Y-m-d', strtotime($$datePeriod. ' + '.$dayPeriod.' days'));
								}
								
								$dayPeriod = "0";
									$datePeriod ='0000-00-00 00:00:00';

									if(isset($_REQUEST["lessid"])){
										if($v_rows["lesson_valid_period"] != "") {
											$dayPeriod = $v_rows["lesson_valid_period"];										
										}
									  $datePeriod = $v_rows["lesson_started_datetime"];		
									}
									else{ 
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
                <?php }else{ ?>
                <div class="widgetbox company-photo">
            <h4 class="widgettitle">Details</h4>
            <div class="widgetcontent" style="padding-bottom: 25px;padding-top: 0 !important;">
              
              <table width="100%" id="passdata" class="receipt">
				<tr>
					<?php 
						if(isset($_REQUEST["lessid"])){
							$l_ispass = $v_rows["lesson_pass"];
						}
						else{ $l_ispass = $c_rows["lesson_pass"]; }
						?>
            <?php if(($cnumrows>0) && $l_ispass=="Yes") {  ?>
              <div class="profilethumb" style="text-align:left;">
              
                <!--<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />-->
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
                
                <?php if($l_ispass == "Yes"){?>
                <td><img src="images/lesson_pass.png" alt="" id="personalimagephoto" title="Passed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } else if($l_ispass == "No"){?>
                <td><img src="images/lesson_notpass.png" alt="" id="personalimagephoto" title="Failed" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } else {?>
                <td><img src="images/lesson_empty.png" alt="" id="personalimagephoto" title="New" style="margin-bottom:25px;width:32px; height:32px;"/></td>
                <?php } ?>
              </div>
              <!--profilethumb-->
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
                  <td width="" class=""><?php echo $l_name;?></td>
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

								/* Written by Amit*/
								
								/* if($c_rows["lesson_valid_period"] == "0" || $v_rows["lesson_valid_period"] == "0") {
									$validperiod = "";
								} else {
									$dayPeriod = "0";
									$datePeriod = $v_rows["lesson_started_datetime"];
									if($v_rows["lesson_valid_period"] != "") {
										$dayPeriod = $v_rows["lesson_valid_period"];										
									}
								     $validperiod = date('Y-m-d', strtotime($$datePeriod. ' + '.$dayPeriod.' days'));
								} */
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
										if($rows["lesson_valid_period"] != "") {
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
              </table>
           
          <?php } else { echo "Nothing to Display";} ?>
        </div>
         
          <input type="hidden" id="hidLessonPass" name="hidLessonPass" value="<?php echo $l_ispass;?>">
          <input type="hidden" id="hidLessonID" name="hidLessonID" value="<?php echo $lessid;?>">
         </div>
				<?php } ?>