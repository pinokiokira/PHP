<?php 
require_once 'require/security.php';
if(!isset($_SESSION['client_id']) || intval($_SESSION['client_id']) == ''){
 echo "0";
 exit();
}
$sId = $_REQUEST["sId"];
//echo "sid=".$sId;
//require_once 'require/security.php';
include_once 'config/accessConfig.php';
      
	  
	  function isImage($url)
{
 $params = array('http' => array(
			  'method' => 'HEAD'
		   ));
		   
		   $url = str_replace(" ","%20",$url);
		   
 $ctx = stream_context_create($params);
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
			
		if($primary_type=='1')	{$img= "primary-type/Default Primary Type - Restaurants.png";}
		
		if($primary_type=='2')	{$img= "primary-type/Default Primary Type - Bar.png";}
		
		if($primary_type=='3')	{$img= "primary-type/Default Primary Type - Lounge.png";}
		
		if($primary_type=='4')	{$img= "primary-type/Default Primary Type - Private.png";}
		
		if($primary_type=='7')	{$img= "primary-type/Default Primary Type - Club.png";}
		
		if($primary_type=='6')	{$img= "primary-type/Default Primary Type - Health.png";}
		
		if($primary_type=='9')	{$img= "primary-type/Default Primary Type - Home.png";}
		
		if($primary_type=='67')	{$img= "primary-type/Default Primary Type - Other.png";}
		
		if($primary_type=='71')	{$img= "primary-type/Default Primary Type - Quick Service.png";}
		
		if($primary_type=='5')	{$img= "primary-type/Default Primary Type - Retail.png";}
		
		if($primary_type=='10')	{$img= "primary-type/Default Primary Type - Travel.png";}
		
		if($primary_type=='8')	{$img= "primary-type/Default Primary Type - Recreation.png";}
			return $img;
  }
 ?> 
	<div style="text-align:left;">  
<?php
		/* $sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city,locations.image,locations.state,
												 locations.country,locations.phone,locations.primary_type,
												 location_types.name as lname,
												 emp_mas.first_name,emp_mas.last_name ,locations.website,locations.fax
												 from employee_master_location_storepoint a,locations,employees_master
												 emp_mas,location_types
												 where a.location_id = locations.id  
												 and a.emp_master_id=emp_mas.empmaster_id
												 and a.id='$sId'
												 and locations.primary_type = location_types.id";
												// and  a.emp_master_id = '".$_SESSION['client_id']."' ";
	
	 
		$result = mysql_query($sql);
		if (mysql_num_rows($result)==0){
			 $sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, 
					 a.emp_master_id,locations.id as location_id,
					 locations.name, locations.city,locations.image,locations.state,
					 locations.country,locations.phone,locations.primary_type,
					 location_types.name as lname,
					 emp_mas.first_name,emp_mas.last_name ,locations.website,locations.fax
					 from locations
					 LEFT JOIN employee_master_location_storepoint a ON a.location_id = locations.id
					 LEFT JOIN employees_master emp_mas ON a.emp_master_id=emp_mas.empmaster_id
					 LEFT JOIN location_types ON locations.primary_type = location_types.id
					 where locations.id ='".$_REQUEST['loc_id']."'";
					$result = mysql_query($sql);
		} */
			$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, 
					 a.emp_master_id,locations.id as location_id,
					 locations.name, locations.city,locations.image,locations.state,
					 locations.country,locations.phone,locations.primary_type,
					 location_types.name as lname,
					 emp_mas.first_name,emp_mas.last_name ,locations.website,locations.fax
					 from locations
					 LEFT JOIN employee_master_location_storepoint a ON a.location_id = locations.id
					 LEFT JOIN employees_master emp_mas ON a.emp_master_id=emp_mas.empmaster_id
					 LEFT JOIN location_types ON locations.primary_type = location_types.id
					 where locations.id ='".$_REQUEST['loc_id']."'";
					 
			$result = mysql_query($sql);
					
			if (mysql_num_rows($result)>0){
			if($row = mysql_fetch_assoc($result)){
				$img = "";
				/*if(trim($row['image']))
				{
					$img = API . "images/" . $row['image'];
					if(!isImage($img))
					{
                                            $img = API."panels/teampanel/images/".getPrimaryTypeImage($row['primary_type']); 
					}
				}
				else{
					$img = API."panels/teampanel/images/".getPrimaryTypeImage($row['primary_type']); 
				}*/
				$img = APIPHP . "images/" . $row['image'];
					if(!isImage($img))
					{
                                            $img = APIPHP."panels/teampanel/images/".getPrimaryTypeImage($row['primary_type']); 
					}
				
?>
                <div style="padding:20px;">
                    <table cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="width:100px;height:100px;vertical-align:bottom;" class="thumb">
							 <?php 
								if (isImage($img)) {
							 ?>
                        		<img src="<?php echo $img;?>" width="100" height="100" border="0"/>
                        
							<?php 
								} else{ ?>
									<img src="images/noimage.png" swidth="100" height="100" border="0" />
								<?php 
								}
							 ?>
							
							
							</td>
                            <!--<td style="width:5px;">&nbsp;</td>
                            <td style="vertical-align:bottom;">
                                <div style="text-align:left;"><b><?php echo $row['name'];?></b></div>
                            </td>-->
							<td style="width:10px;">&nbsp;</td> 
						<td style="vertical-align: bottom !important;  padding-top: 5%;" >
						<p style="margin-bottom: 2px; font-size:15px;">
							<?php $loc_name = explode("-",$row['name']); ?>
							<strong><?php echo $loc_name[0]; ?></strong>
							<?php if($loc_name[1]){ ?>
							<br>
							<span style="font-size:14px;"><?php echo $loc_name[1]; ?></span>
							<?php } ?>
							<br/>
							<b style="font-size:12px;"><?php echo $row['first_name']." ".$row['last_name']; ?></b><br/>
							<b style="font-size:12px;"><?php echo $row['city']; ?></b></p></td>
      						 <td>&nbsp;&nbsp;</td>
                        </tr>
					</table>
					</div>
<div style="clear:both; width:90%; min-height:250px; margin-top:8px; margin-left:5%; overflow-x:auto; ">
  <table cellpadding="10" cellspacing="5" width="100%">
                        <tr>
                          <td><strong>Primary Type: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['lname']; ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>City: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['city']; ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <!--<tr>
                          <td><strong>State: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['sname']; ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Country: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['cname']; ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>-->
                        <tr>
                          <td><strong>Telephone: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['phone']; ?></td>
    					</tr>
                        <tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Fax: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['fax']; ?></td>
    					</tr>
                        <tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Website: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['website']; ?></td>
    					</tr>
                    </table>
                </div>
<?php
			}
		
	}
	else{
		echo 'No Location Profile Found';
	}
?>
</div>