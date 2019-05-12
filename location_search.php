<?php
require_once 'require/security.php';
include 'config/accessConfig.php'; 
//echo "client".$_SERVER['REQUEST_URI']."   ".$_GET['q'];

if($_GET['q']!="")
	{
		
		$q = mysql_real_escape_string($_GET['q']);
		
		//$query = mysql_query("select image,name, email, phone, id, sex  from clients where (name like '%$q%' or email like '%$q%' or id = '$q') limit 0, 8");		
		
	
	$query = mysql_query("SELECT id,name,image,phone,city,address FROM locations WHERE status = 'active' AND (name like '%$q%' OR phone like '%$q%' OR city like '%$q%' OR id = '$q') LIMIT 50");	

?>
<ul class='msglist' style='height: 430px;'>
<?
		  while($row = mysql_fetch_array($query))
		  {
		  		$name = str_replace("'", "", $row["name"]);
?>
			
<li class="getmessage" onclick="javascript:loadLocation('<?=$row["id"]?>','<?=$name?>','<?=$row["phone"]?>','<?=$row["city"]?>')">
 <div class='thumb'>
 <?
		   if($row['image']!="")
		       $result=  "<img src=\" ".API. "images/".$row["image"]."\" onerror=\"this.src='images/Default - Location.png'\" style=\"width:40px;height:40px;\" alt=\"\" />";
			else
		   		$result= "<img src=\"images/Default - Location.png\" style=\"width:40px;height:40px;\" alt=\"\" />";
				
				echo $result;
			 ?>
			</div>
			<div class="summary">
			<div style="width:100%; float:left; ">
  <div style="width:50%; float:left; ">
  <div style="float:left;width:100%; "><h4 ><?=$row['name']?></h4></div>
  <div style="float:left;width:100%; "><?=$row['phone']?></div>
   <div style="float:left;width:100%;line-height:14px;"><?=$row['city']?></div>
  </div>
  
  <!-- <div style="width:50%; float:left; ">
   <div style="float:right;width:100%; text-align:right; line-height:14px; "><?=$row['address']?></div>
  <div style="float:right;width:100%;text-align:right "><?=$row['address']?></div>
  </div>-->

</div>
			</div>
</li>
<?
  }
		

  }

    ?>
	</ul>