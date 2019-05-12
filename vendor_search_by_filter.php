<?php
include_once("includes/session.php");
include_once("config/accessConfig.php");

$sql = " SELECT v.* 
         FROM 
		 vendors  v  
		 LEFT JOIN  vendor_items vi ON v.id = vi.vendor_id
		 INNER JOIN  vendors_types vt ON v.id = vt.vendor_type_id
		 LEFT JOIN  inventory_items ini ON vi.inv_item_id = ini.item_id
		 LEFT JOIN  inventory_groups ing ON ini.inv_group_id = ing.id 
		 WHERE
		 v.status= 'active' 
		 ";	

   if(isset($_POST['filters']) && !empty($_POST['filters'])){

	if(isset($_POST['filters']['name']) && !empty($_POST['filters']['name']))
	{
		$q = mysql_real_escape_string($_POST['filters']['name']);
		
		$sql .=  " AND ( v.name like '%$q%' OR v.email like '%$q%' OR v.contact like '%$q%' OR v.city like '%$q%' OR v.address like '%$q%' ) ";
	}
	
	
	if(isset($_POST['filters']['vtype']) && !empty($_POST['filters']['vtype']))
	{
		if(!in_array('all',$_POST['filters']['vtype']))
		{
			$vtype = array();
			
			foreach($_POST['filters']['vtype'] as $mco=>$vtypes){
			
			   $vtype[] = " v.type = '".$vtypes."' ";
			}
			
			$sql .= " AND ( ".implode( " OR " , $vtype ) ." ) ";
			
		}
	}
	
	if(isset($_POST['filters']['itype']) && !empty($_POST['filters']['itype']))
	{
		if(!in_array('all',$_POST['filters']['itype']))
		{
			$itype = array();
			
			
			foreach($_POST['filters']['itype'] as $mco=>$itypes){
			
			   $itype[] = " ini.inv_group_id = '".$itypes."' ";
			}
			
			$sql .= " AND ( ".implode( " OR " , $itype ) ." ) ";
		}
	}								
	
}

$sql .= " GROUP BY v.id";

//echo $sql; exit;
							
$quemyproviders = 	mysql_query($sql) or die(mysql_error());
$quemyproviders_rows = mysql_num_rows($quemyproviders);
$i=1;
if($quemyproviders_rows >=1){

?>
<ul class='msglist' style='height: 430px;'>
<?
		  while($row=mysql_fetch_assoc($quemyproviders))
		  {
		   $name = str_replace(array("\n", "\r"), "", $row["name"]);
		   $name = str_replace("'","~~~~",$name);
?>

<li class="getmessage" onclick="javascript:loadVendor('<?=$row["id"]?>','<?=$row["email"]?>','<?=$row["phone"]?>','<?=$name?>','<?=str_replace(array("\n", "\r"), "", $row["StorePoint_image"])?>')">
 <div class='thumb'>
 <?
		   if($row['StorePoint_image']!="")
		       $result=  "<img src=\" ".APIIMAGE. "images/".$row["StorePoint_image"]."\" onerror=\"this.src='images/Default - User Icon.png'\" style=\"width:40px;height:40px;\" alt=\"\" />";
		   else if($row['sex']=='M')
		   		$result= "<img src=\"images/Male.png\" style=\"width:40px;height:40px;\" alt=\"\" />";
		   else if($row['sex']=='F')
		   		$result= "<img src=\"images/Female.png\" style=\"width:40px;height:40px;\" alt=\"\" />";
			else
		   		$result= "<img src=\"images/Default - User Icon.png\" style=\"width:40px;height:40px;\" alt=\"\" />";
				
				echo $result;
			 ?>
			</div>
			<div class="summary">
			<div style="width:100%; float:left; ">
  <div style="width:50%; float:left; ">
  <div style="float:left;width:100%; "><h4 ><?=$row['name']?></h4></div>
  <div style="float:left;width:100%; "><?=$row['phone']?></div>
   <div style="float:left;width:100%; "><?=$row['email']?></div>
  </div>
  
   <div style="width:50%; float:left; ">
   <div style="float:right;width:100%; text-align:right "><?=$row['contact']?></div>
  <div style="float:right;width:100%;text-align:right "><?=$row['address']?></div>
  </div>

</div>
			</div>
</li>
<?php
  }
		
 echo '</ul>';
  }

    ?>
	