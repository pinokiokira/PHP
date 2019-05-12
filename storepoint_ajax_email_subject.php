<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$value=mysql_escape_string($_REQUEST['value']);
$sendby=mysql_escape_string($_REQUEST['sendby']);
$client_id=mysql_escape_string($_REQUEST['client_id']);


function isImage($url)
{
 $params = array('http' => array(
			  'method' => 'HEAD'
		   ));
 $ctx = stream_context_create($params);
    $url = str_replace(" ","%20",$url);
 $fp = @fopen($url, 'rb', false, $ctx);
 if (!$fp) 
	return false;  // Problem with url

$meta = stream_get_meta_data($fp);
if ($meta === false)
{
	fclose($fp);
	return false;  // Problem reading data from url
}

$wrapper_data = $meta["wrapper_data"];
if(is_array($wrapper_data)){
  foreach(array_keys($wrapper_data) as $hh){
	  if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
	  {
		fclose($fp);
		return true;
	  }
  }
}

fclose($fp);
return false;
}
function getPrimaryTypeImage($primary_type)
  {
 	 $img="";
			
		if($primary_type=='1')	{$img= "default_images/Default Primary Type - Restaurant.png";}
		
		if($primary_type=='2')	{$img= "default_images/Default Primary Type - Bar.png";}
		
		if($primary_type=='3')	{$img= "default_images/Default Primary Type - Lounge.png";}
		
		if($primary_type=='4')	{$img= "default_images/Default Primary Type - Private.png";}
		
		if($primary_type=='7')	{$img= "default_images/Default Primary Type - Club.png";}
		
		if($primary_type=='6')	{$img= "default_images/Default Primary Type - Health.png";}
		
		if($primary_type=='9')	{$img= "default_images/Default Primary Type - Home.png";}
		
		if($primary_type=='67')	{$img= "default_images/Default Primary Type - Other.png";}
		
		if($primary_type=='71')	{$img= "default_images/Default Primary Type - Quick Service.png";}
		
		if($primary_type=='5')	{$img= "default_images/Default Primary Type - Retail.png";}
		
		if($primary_type=='10')	{$img= "default_images/Default Primary Type - Travel.png";}
		
		if($primary_type=='8')	{$img= "default_images/Default Primary Type - Recreation.png";}
			return $img;
  }

switch($sendby)
{
	case "sent";
 ?>
	<ul id="sent_box" class="msglist">
                  <?php
												 
												 $sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time ,emp_mas.first_name,emp_mas.last_name 
												 from employee_master_location_storepoint a,  locations,employees_master emp_mas
												 where a.location_id = locations.id and a.sent_by_type = 'Employee Master' 
												 and a.emp_master_id=emp_mas.empmaster_id
												 and  a.emp_master_id = '".$_SESSION['client_id']."'   ";
												 
											if ($value!=""){
													$sql.= " AND (a.subject like '%".$value."%' OR a.message like '%".$value."%' ) ";
												}
												$sql.=" order by sId desc";
											$result = mysql_query($sql);
											
											while ($row = mysql_fetch_array($result)) {
												$image = "";
												$sqlMaster = "select image,primary_type from Locations where id = '".$row['location_id']."'";
												$resultMaster = mysql_query($sqlMaster);
												$rowMaster = mysql_fetch_array($resultMaster);
												$image = APIPHP."images/".$rowMaster['image'];
												if(!isImage($image))
												{
													$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
												}
										?>
                  <li class="unread showJobs" id="<?php echo $row["id"]."-".$row['sId']."-sent";?>">
                    <?
				  	if(isImage($image))
										{
				  ?>
				    <div class="thumb"><img src="<?php echo $image; ?>" alt="" /></div>
					<?
					}
					else
					{
					?>
					<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
					<?
					}
					?>
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
					<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo $row["message"];?></p>
                    </div>
                  </li>
                  <?php
											
											}	
											
										?>
                </ul>
				<?

	
	break;
	
	
	case "done";
	 ?>
	<ul id="done_box" class="msglist">
                  <?php
											$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												  
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id and a.read = 'Yes'  
												  and a.sent_by_type='Location' 
												  and  a.emp_master_id = '".$_SESSION['client_id']."'
												  ";
												  if ($value!=""){
													$sql.= " AND (a.subject like '%".$value."%' OR a.message like '%".$value."%' ) ";
												}
												$sql.="order by sId desc";
											$result = mysql_query($sql);
											
											while ($row = mysql_fetch_array($result)) {
												$image = "";
												if($row['sent_by_type'] != null && $row['sent_by_type'] == "Employee Master"){
													$sqlMaster = "select * from employees_master where empmaster_id = '".$row['emp_master_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
												}else if($row['sent_by_type'] != null && $row['sent_by_type'] == "Location"){
													$sqlMaster = "select image,primary_type from Locations where id = '".$row['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
													
												}
										?>
                  <li class="unread showJobs" id="<?php echo $row["id"]."-".$row['sId']."-compose";?>">
                   <?
				  	if(isImage($image))
										{
				  ?>
				    <div class="thumb"><img src="<?php echo $image; ?>" alt="" /></div>
					<?
					}
					else
					{
					?>
					<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
					<?
					}
					?>
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
					<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo $row["message"];?></p>
                    </div>
                  </li>
                  <?php
											
											}	
											
										?>
                </ul>
				<?
	break;
	
	
	
	case "inbox";
	?>
	<ul id="inbox_box" class="msglist">
                  <?php
									
									
									$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id and a.read = 'No' and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
												if ($value!=""){
													$sql.= " AND (a.subject like '%".$value."%' OR a.message like '%".$value."%' ) ";
												}
												$sql.="order by sId desc";
											$result = mysql_query($sql);
											while ($row = mysql_fetch_array($result)) {
												$image = "";
												if($row['sent_by_type'] != null && $row['sent_by_type'] == "Employee Master"){
													$sqlMaster = "select * from employees_master where empmaster_id = '".$row['emp_master_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
												}else if($row['sent_by_type'] != null && $row['sent_by_type'] == "Location"){
													$sqlMaster = "select image,primary_type from Locations where id = '".$row['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
													
												}
										?>
                  <li class="unread showJobs" id="<?php echo $row["id"]."-".$row['sId']."-compose";?>">
				  <?
				  	if(isImage($image))
										{
				  ?>
				    <div class="thumb"><img src="<?php echo $image; ?>" alt="" /></div>
					<?
					}
					else
					{
					?>
					<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
					<?
					}
					?>
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
						<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo $row["message"];?></p>
                    </div>
                  </li>
                  <?php
											
											}	
											
										?>
                </ul>
				<?
	
	break;
	
}

?>