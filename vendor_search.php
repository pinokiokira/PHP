<?php
include_once("includes/session.php");
include_once("config/accessConfig.php");
//echo "client".$_SERVER['REQUEST_URI']."   ".$_GET['q'];

if($_GET['q']!="")
	{
		
		$q = mysql_real_escape_string($_GET['q']);
		
		//$query = mysql_query("select image,name, email, phone, id, sex  from clients where (name like '%$q%' or email like '%$q%' or id = '$q') limit 0, 8");		
		
	
	$query = mysql_query("SELECT * FROM vendors as vd WHERE vd.status = 'active' AND (vd.name like '%$q%' OR vd.email like '%$q%' OR vd.contact like '%$q%' OR vd.city like '%$q%' OR vd.address like '%$q%')");	

?>
<ul class='msglist' style='height: 430px;'>
<?
		  while($row = mysql_fetch_array($query))
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
<?
  }
		

  }

    ?>
	</ul>