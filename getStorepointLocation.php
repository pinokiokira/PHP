<?php 
require_once 'require/security.php';
if(!isset($_SESSION['client_id']) || intval($_SESSION['client_id']) == ''){
 echo "0";
 exit();
}

//echo "sid=".$sId;
//require_once 'require/security.php';
include_once 'config/accessConfig.php';

$sId = $_REQUEST["sId"];
	  
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
		
		if($primary_type=='78')	{$img= "primary-type/Default Primary Type - Hotel.png";}
			return $img;
  }
 ?> 
	<div style="text-align:left;">  
<?php
// get the location profile
	
						 $sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.website,locations.fax,locations.zip, locations.city,locations.address,locations.image,locations.state,locations.country,locations.phone,locations.primary_type,
												 location_types.name as lname,CONCAT(emp.first_name,' ',emp.last_name) as emp_name,locations.representative,
												  emp_mas.first_name,emp_mas.last_name 
												 from employee_master_location_storepoint a,locations,employees_master emp_mas,location_types,employees as emp
												 where a.location_id = locations.id  
												 and a.emp_master_id=emp_mas.empmaster_id
												 and emp.id = a.location_employee_id
												 and a.id='$sId'
												 and locations.primary_type = location_types.id group by a.id";
												// and  a.emp_master_id = '".$_SESSION['client_id']."' ";
												//and a.location_employee_id = emp.id
	
	 
		$result = mysql_query($sql);
	//echo $_SESSION['client_id'];
		if (mysql_num_rows($result)>0){
			if($row = mysql_fetch_assoc($result)){
				
			
				$img = "";
				if(trim($row['image']))
				{
					$img = API . "images/" . $row['image'];
					if(!isImage($img))
					{
                    $img = API."panels/teampanel/images/".getPrimaryTypeImage($row['primary_type']); 
					}
				}
				else{
					$img = API."panels/teampanel/images/".getPrimaryTypeImage($row['primary_type']); 
				}
				
?>
                <div style="padding:20px;">
                    <table cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td style="width:100px;height:100px;vertical-align:bottom;" class="thumb"><img onerror="this.src='images/Default - location.png'" src="<?php echo $img;?>" width="100" height="100" border="0"/></td>
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
                            <?php if($row['sent_by_type']=='Location'){								
							?>
							<b style="font-size:12px;"><?php echo $row['representative']; ?></b><br/>
                            <?php }else{ ?>
                            <b style="font-size:12px;"><?php echo $row['first_name']." ".$row['last_name']; ?></b><br/>
                            <?php } ?> 
							<b style="font-size:12px;"><?php echo $row['city']; ?></b></p></td>
      						 <td>&nbsp;&nbsp;</td>
                        </tr>
					</table>
					</div>
<div style="clear:both; width:90%; min-height:250px; margin-top:8px; margin-left:5%; ">
  <table cellpadding="10" cellspacing="5" width="100%">
                        <tr>
                          <td width="30%"><strong>Primary Type: </strong></td>
                          <td width="2%">&nbsp;</td>
                          <td width="68%"><?php echo $row['lname']; ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
                          <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Address: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php
						  $state = $row["state"];
						  $country = $row["country"];
						  
						  $sql_state = "SELECT name FROM states WHERE id = '$state'";
						  $res_state = mysql_query($sql_state);
						  $result_s = mysql_fetch_array($res_state);
						  
						  $sql_country = "SELECT name FROM countries WHERE id = '$country'";
						  $res_country = mysql_query($sql_country);
						  $result_c = mysql_fetch_array($res_country);
						  
						   echo $row['address'].'<br />';
						   if($row['city']!=""){
						   echo $row['city'].', ';
						   }
						   if($state!=""){
						   echo $result_s['name'].' ';
						   }
						   echo $row['zip'];
						   if($country!=""){
						   echo "<br />".$result_c['name'].' ';
						   }
						   
						    ?></td>
    					</tr>
						<tr>
						  <td>&nbsp;</td>
                          <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>                        
                        <tr>
                          <td><strong>Telephone: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['phone']; ?></td>
    					</tr>
                        <tr>
						  <td>&nbsp;</td>
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
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Website: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['website']; ?></td>
    					</tr>
                        <tr>
						  <td>&nbsp;</td>
                          <td>&nbsp;</td>
						  <td>&nbsp;</td>
						</tr>
                        <tr>
                          <td><strong>Location ID: </strong></td>
                          <td width="10">&nbsp;</td>
                          <td><?php echo $row['location_id']; ?></td>
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