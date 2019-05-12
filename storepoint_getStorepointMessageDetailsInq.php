<?php 
	require_once 'require/security.php';
	//session_start();
	include_once 'config/accessConfig.php';
	
	$option=$_REQUEST['option'];
	$storepointlocid = $_REQUEST['storepointlocid'];
	//echo $option;
	
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
	
	
if(!isset($_SESSION['client_id']) || intval($_SESSION['client_id']) == ''){
 echo "0";
 exit();
}
	  $str='';
	  $sql_check = "select emp_master_id,subject,location_id,`read` from employee_master_location_storepoint where id  = ".$_REQUEST["id"];
	  $res_check = mysql_query($sql_check);
	  $row_check = mysql_fetch_array($res_check);
	  
	  /*$sql_job = "select * from location_jobs where id  = ".$row_check["location_job_id"];
	  
	  $res_job = mysql_query($sql_job);
	  $row_job = mysql_fetch_array($res_job);*/
	  
?>
 <div class="messageview">
                  <div class="btn-group pull-right">
                    <button data-toggle="dropdown" class="btn dropdown-toggle" style="color:#000000 !important;">Actions <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a href="#">Print Message</a></li>
					  <?
					if($option!='sent')
					{
				?>
                      <li><a href="#">Mark as Unread</a></li>
					  <?
					  }
					  ?>
                    </ul>
                  </div>
                  <h1 class="subject"><?=$row_check['subject']?></h1>
                 <?
	

	 if($row_check['read']=='No' && $option!='sent')
	 {
		 $sql2 = "update employee_master_location_storepoint set read_date=CURDATE(),read_time=CURTIME(),`read` =  'Yes' where id  = ".$_REQUEST["id"];
		 mysql_query($sql2);
	 }
	  
	$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time  
										 from employee_master_location_storepoint a, locations
										 where a.emp_master_id ='".$_SESSION['client_id']."' AND   a.location_id = locations.id and a.subject = '".$row_check["subject"]."' order by sId desc";

	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result))
{	
	
	$image = "";
	if($row['sent_by_type'] != null && $row['sent_by_type'] == "Employee Master"){
		$sqlMaster = "select * from employees_master where empmaster_id = '".$row['emp_master_id']."'";
		$resultMaster = mysql_query($sqlMaster);
		$rowMaster = mysql_fetch_array($resultMaster);
		$image = APIPHP."images/".$rowMaster['image'];
		$name=$rowMaster["first_name"]." ".$rowMaster["last_name"];
	}else if(($row['sent_by_type'] != null && $row['sent_by_type'] == "Location") ){
		$sqlMaster = "select image,name,primary_type from Locations where id = '".$row['location_id']."'";
		$resultMaster = mysql_query($sqlMaster);
		$rowMaster = mysql_fetch_array($resultMaster);
		$image = APIPHP."images/".$rowMaster['image']; 
		$name=$rowMaster["name"];
		if(!isImage($image))
		{
			$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
		}
	}

		echo '<div class="msgauthor">';
		if(isImage($image))
		{
					echo '<div class="thumb"><img src="'.$image.'" alt="" /></div>';
		}
		else
		{
					echo '<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>';
		}			
	
		echo '<div class="authorinfo">';
		echo '<span class="date pull-right">'.$row["date"].' '.$row["time"].'</span>';
		echo '<h5><strong>'.$name.'</strong> </h5>';
		echo '<h5><strong>'.$row["subject"].'</strong> </h5>';
		echo '</div><!--authorinfo-->';
		echo '</div><!--msgauthor-->';
		echo '<div class="msgbody">';
		echo '<p style="width:100%;">'.$row["message"].'</p>';
		echo '</div><!--msgbody-->';
}
	
?>
                </div>
				<?
					if($option!='sent')
					{
				?>
                <!--messageview-->
                <form method="post" id="form_comment" action="storepoint_messages_inquiry.php?tab=<?php echo $option; ?>&storepointlocid=<?php echo $storepointlocid; ?>">
                  <input type="hidden" name="sId" id="sId" value="<?=$_REQUEST['id']?>" />
                    <!--<input type="hidden" name="jobId2" id="jobId2" value="<?=$_REQUEST['jobId2']?>" />-->
                  <div class="msgreply" style="height:220px; " >
				  <?
				  	$sqluser = "select * from employees_master where empmaster_id = '".$_SESSION['client_id']."'";
					$resultuser = mysql_query($sqluser);
					$rowuser = mysql_fetch_array($resultuser);
					$image = APIPHP."images/".$rowuser['image'];
				  ?>
				  
                    
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
                    <div class="reply">
                      <textarea style="height:80px; resize:none;" id="message" name="message" placeholder="Type something here to reply"></textarea>
                      <input type="submit" value="Submit" class="btn btn-primary"/>
                    </div>
                    <!--reply--> 
                    
                  </div>
                  <!--messagereply-->
                </form>

		<?
			}
		?>


 