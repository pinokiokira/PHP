<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$cltab 		 = $_REQUEST['cltab'];

$now=date("Y-m-d");

$sql_l = "SELECT
locations.id,
locations.status,
locations.email,
locations.`password`,
locations.primary_type,
locations.name,
locations.address,
locations.address2,
locations.city,
locations.state,
locations.zip,
locations.country,
locations.phone,
locations.image,
locations.createdon,
locations.date_added,
locations.created_by,
locations.created_date,
locations.last_datetime,
employee_master_location_stylistfn.id as styid,
employee_master_location_stylistfn.sent_by_type,
employee_master_location_stylistfn.emp_master_id,
employee_master_location_stylistfn.location_id,
employee_master_location_stylistfn.location_employee_id,
employee_master_location_stylistfn.sent_datetime,
employee_master_location_stylistfn.subject,
employee_master_location_stylistfn.message,
employee_master_location_stylistfn.`read`,
employee_master_location_stylistfn.read_date,
employee_master_location_stylistfn.read_time,
employee_master_location_stylistfn.reply,
location_types.id,
location_types.name as lname,
location_types.subtype,
(SELECT COUNT(message ) FROM employee_master_location_stylistfn WHERE employee_master_location_stylistfn.`read` = 'No' AND employee_master_location_stylistfn.location_id = locations.id AND employee_master_location_stylistfn.sent_by_type='Location') AS unread_count
FROM
locations
Inner Join employee_master_location_stylistfn ON locations.id = employee_master_location_stylistfn.location_id
Inner Join location_types ON locations.primary_type = location_types.id
WHERE employee_master_location_stylistfn.emp_master_id = '".$_SESSION['client_id']."'
GROUP BY employee_master_location_stylistfn.location_id";

$resultLocs = mysql_query($sql_l) or die(mysql_error());

$sql_c = "SELECT
clients.id,
clients.email,
clients.name,
clients.name_title,
clients.city,
clients.state,
clients.country,
clients.phone,
clients.image,
employee_master_location_stylistfn.id as styid,
employee_master_location_stylistfn.sent_by_type,
employee_master_location_stylistfn.emp_master_id,
employee_master_location_stylistfn.location_id,
employee_master_location_stylistfn.location_employee_id,
employee_master_location_stylistfn.client_id,
employee_master_location_stylistfn.sent_datetime,
employee_master_location_stylistfn.subject,
employee_master_location_stylistfn.message,
employee_master_location_stylistfn.`read`,
employee_master_location_stylistfn.read_date,
employee_master_location_stylistfn.read_time,
employee_master_location_stylistfn.reply,
(SELECT COUNT(message ) FROM employee_master_location_stylistfn WHERE employee_master_location_stylistfn.`read` = 'No' AND employee_master_location_stylistfn.client_id = clients.id AND employee_master_location_stylistfn.sent_by_type='Client') AS unread_count
FROM
clients 
Inner Join employee_master_location_stylistfn ON  clients.id = employee_master_location_stylistfn.client_id
WHERE employee_master_location_stylistfn.emp_master_id = '".$_SESSION['client_id']."'
GROUP BY employee_master_location_stylistfn.client_id
";
$resultClients = mysql_query($sql_c) or die(mysql_error());
//echo $numr = mysql_num_rows($resultLocs);
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
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | TeamPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
 <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
	<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="js/jquery.alerts.js"></script>
	<script type="text/javascript" src="js/elements.js"></script>
	<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<!--<script type="text/javascript" src="js/jquery.blockUI.js"></script>-->
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--<script type="text/javascript" src="js/main.js"></script>-->
<style>
.error {
	color: #FF0000;
	padding-left:10px;
}
/*.row-fluid .span4 {
    width: 32.6239%;
	margin-left:10px;
}*/
.span4 {
	float:left;
	width:28.5%!important;
	min-height:600px;
	margin-left:1.5%!important;
}
/*.unread showJobs selected{background-color:#cccccc;}*/
table.table tbody tr.selected, table.table tfoot tr.selected {
    background-color: #808080;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function($){
	
	jQuery('#global_tbl').dataTable({
		"sPaginationType": "full_numbers",
		"aaSorting": [[ 2, "asc" ]],
		"bJQuery": true,
		"fnDrawCallback": function(oSettings) {
		   //  jQuery.uniform.update();
		}
	});
	jQuery('#global_tbl2').dataTable({
		"sPaginationType": "full_numbers",
		"aaSorting": [[ 2, "asc" ]],
		"bJQuery": true,
		"fnDrawCallback": function(oSettings) {
		   //  jQuery.uniform.update();
		}
	});
	jQuery(".cl_order").click(function(){
			 jQuery(".gradeX").attr("class","gradeX cl_order");
		   jQuery(this).attr("class","gradeX cl_order selected");
			callajax(this);
			
		}); 
	jQuery(".cl_order_loc").click(function(){
			 jQuery(".gradeX_loc").attr("class","gradeX_loc cl_order_loc");
		   jQuery(this).attr("class","gradeX_loc cl_order_loc selected");
			callajax(this);
			
		});
	jQuery("#locc").click(function(){
			 //jQuery("#temp_div").css("display","block");
			 jQuery(".span4").html('');
			 jQuery(".cl_order_loc:first-child").click();
			
		}); 
	jQuery("#cli").click(function(){
			 //jQuery("#temp_div").css("display","block");
			 jQuery(".span4").html('');
			 jQuery(".cl_order:first-child").click();
			
		}); 
	
	jQuery(".cl_order:first-child").click();
	
	<?php if($cltab == "clients") {?>
		/*jQuery("#wiz1step1").css("display","block");
		jQuery("#wiz1step2").css("display","none");*/
		jQuery("#cli").click();
	
	<?php } else if($cltab == "locations") {?>
		/*jQuery("#wiz1step2").css("display","block");
		jQuery("#wiz1step1").css("display","none");*/
		jQuery("#locc").click();
	<?php } else{}?>
		
});
function getStylistfnLocation(sId,locOrClient)
{
			var dataurl = "stylistfn_getStylistfnLocationInq.php?sId="+ sId+"&locOrClient="+locOrClient;
			jQuery.ajax({
				url:dataurl,
				type:"get",
				cache:false,
				async:false,
				dataType:"text", 
				success:function (data) {
				if(data=='0'){
				//location.reload(); 
				return false;
				}	
				 else {	
				 	
				 	jQuery("#temp_div").css("display","none");
					jQuery("#fdetail").css("display","block");
					jQuery("#fdetail").html(data);
				 }
				}
			});
}
function callajax(obj)
{
			//var jobId = jQuery(obj).attr("id");
			var str = jQuery(obj).attr("id");
			var strs = str.split("-");

			var sId = strs[0];
			jobId0 =strs[0];
			jobId1 =strs[1];
			//alert(jobId1);
			getStylistfnLocation(sId,jobId1);
}
var submitAction=true;
function submitPostListing()
{
	/*var data = jQuery("#frmInquiry").serialize();
	if(jQuery("#message").val().length<=5)
	{
		alert("Please enter a proper message containing at least 6 characters.");
	}
	if(submitAction)
	{
		submitAction=false;
		jQuery.post("post_job_inquiry.php", data, function(response){
			alert("Thank you for inquiring about this job listing. Your message and allowed share profile has been sent to the employer.");
			submitAction=true;
			
			jQuery("#closePostListing").click();
		});
	}*/
				
}
</script>
</head>

<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="dashboard.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>StylistFN<span class="separator"></span></li>
      <li>Clients</li>
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
    </ul>
    <div class="pageheader"> 
      <div class="pageicon"><img src="images/t-shirt.png"></div>
      <div class="pagetitle">
        <h5>Browse through your clients and locations</h5>
        <h1>StylistFN - Clients / Businesses</h1>
      </div>
    </div>
    <!--pageheader-->
    <?php //echo "<pre>"; print_r($_SESSION); 
		
		?>
    <div class="maincontent">
      <div class="maincontentinner" style="padding-right:0;">
        <div class="row-fluid">
         <div class="span8 tabbedwidget tab-primary" id="tabs">
            <ul class="hormenu">
              <li> <a id="cli" href="#wiz1step1"> Clients </a> </li>
              <li> <a id="locc" href="#wiz1step2"> Locations </a> </li>
            </ul>
            <div id="wiz1step1" class="formwiz" style="background-color: white;">
            	<table class="table table-bordered responsive" id="global_tbl">
                <colgroup>
               		<col class="con0" style="width:2%;"/>
               		<col class="con1" style="width:10%;"/>
               		<col class="con1" style="width:5%;"/>
                	<col class="con0" style="width:2%;"/>
                </colgroup>
                <thead>
                  <tr>
                    <th class="head0">Image</th>
                    <th class="head1">Name</th>
                    <th class="head1">Last Message</th>
					<th class="head0">Action</th>
                  </tr>
                </thead>
                <tbody>
                	<?php
					$i = 1;
					  while($row = mysql_fetch_array($resultClients)){
						
						  $state = $row["state"];
						  $country = $row["country"];
						  
						  $sql_state = "SELECT name FROM states WHERE id = '$state'";
						  $res_state = mysql_query($sql_state);
						  $result_s = mysql_fetch_array($res_state);
						  
						  $sql_country = "SELECT name FROM countries WHERE id = '$country'";
						  $res_country = mysql_query($sql_country);
						  $result_c = mysql_fetch_array($res_country);
						  
						  $sql_last_msg = "SELECT DATE_FORMAT(sent_datetime,'%Y-%m-%d') as date FROM employee_master_location_stylistfn WHERE client_id = '".$row["client_id"]."' ORDER BY sent_datetime DESC";
						  $res_last_msg = mysql_query($sql_last_msg);
						  $result_lm = mysql_fetch_array($res_last_msg);
						  
						    $image = API."images/".$row["image"];
						  
					  ?>
                	  <tr class="gradeX cl_order <?php if($i == 1) echo "selected";?>" id="<?php echo $row["styid"]."-".$row["client_id"];?>" style="cursor:pointer;">
						<td class="center">
                        <?php 
							/*if (isImage($image)) {*/
							if ($row["image"] != "") {
						?>
                        <img src="<?php echo $image;?>" width="80" height="80"/>
                        
                        <?php 
							}
							else{ ?>
								<img src="images/noimage.png" width="80" height="80"/>
							<?php 
							}
							?>
                        </td>
						<td><?php echo "<strong>".$row["name"]."</strong>"."<br />".$row["city"].", ".$result_s["name"]."<br />".$result_c["name"];?></td>
                        <td><?php echo $result_lm["date"];?></td>
                        <td class="center" style="vertical-align:middle;">
                        <a href="stylistfn_messages_inquiry.php?stylistfnid=<?php echo $row["client_id"];?>&cltab=clients" style="color:#000000;">
						<div class="iconfa-envelope"></div>&nbsp; (<?php echo $row["unread_count"];?>)
						</a>
                        </td>
                      </tr>
                     <?php $i++;
					  }
					  ?>
                </tbody>
              </table>
            </div>
            <div id="wiz1step2" class="formwiz" style="background-color: white;">
            	 <table class="table table-bordered responsive" id="global_tbl2">
                <colgroup>
               		<col class="con0" style="width:2%;"/>
               		<col class="con1" style="width:10%;"/>
                	<col class="con0" style="width:10%;"/>
               		<col class="con1" style="width:5%;"/>
                	<col class="con0" style="width:2%;"/>
                </colgroup>
                <thead>
                  <tr>
                    <th class="head0">Image</th>
                    <th class="head1">Name</th>
                    <th class="head0">Primary Type</th>
                    <th class="head1">Last Message</th>
					<th class="head0">Action</th>
                  </tr>
                </thead>
                <tbody>
                	<?php
					$j = 1;
					  while($row = mysql_fetch_array($resultLocs)){
						
						  $state = $row["state"];
						  $country = $row["country"];
						  
						  $sql_state = "SELECT name FROM states WHERE id = '$state'";
						  $res_state = mysql_query($sql_state);
						  $result_s = mysql_fetch_array($res_state);
						  
						  $sql_country = "SELECT name FROM countries WHERE id = '$country'";
						  $res_country = mysql_query($sql_country);
						  $result_c = mysql_fetch_array($res_country);
						  
						  $sql_last_msg = "SELECT DATE_FORMAT(sent_datetime,'%Y-%m-%d') as date FROM employee_master_location_stylistfn WHERE 
						  location_id = '".$row["location_id"]."' ORDER BY sent_datetime DESC";
						  $res_last_msg = mysql_query($sql_last_msg);
						  $result_lm = mysql_fetch_array($res_last_msg);
						  
						    $image = API."images/".$row["image"];
						   if(!isImage($image))
							{
								$image = API."images/".getPrimaryTypeImage($row['primary_type']); 
							}
							
						  
					  ?>
                	  <tr class="gradeX_loc cl_order_loc <?php if($j == 1) echo "selected";?>" id="<?php echo $row["styid"]."-".$row["location_id"];?>" style="cursor:pointer;">
						<td class="center">
                        <?php 
							if (isImage($image)) {
						?>
                        <img src="<?php echo $image;?>" width="80" height="80"/>
                        
                        <?php 
							}
							else{ ?>
								<img src="images/noimage.png" width="80" height="80"/>
							<?php 
							}
							?>
                        </td>
						<td><?php echo "<strong>".$row["name"]."</strong>"."<br />".$row["city"].", ".$result_s["name"]."<br />".$result_c["name"];?></td>
						<td><?php echo $row["lname"];?></td>
                        <td><?php echo $result_lm["date"];?></td>
                        <td class="center" style="vertical-align:middle;">
                        <a href="stylistfn_messages_inquiry.php?stylistfnid=<?php echo $row["location_id"];?>&cltab=locations" style="color:#000000;">
						<div class="iconfa-envelope"></div>&nbsp; (<?php echo $row["unread_count"];?>)
						</a>
                        </td>
                      </tr>
                     <?php $j++;
					  }
					  ?>
                </tbody>
              </table>
            </div>
         </div>
          <!--<div class="span8" style="width:69%;float:left;">
          		<div class="clearfix">
                <h4 class="widgettitle">Clients</h4>
              </div>   
             
          </div>--> <!--end span8-->
         <!-- <div class="span4">
            <div class="clearfix">
              <h4 class="widgettitle">Client Details</h4>
            </div>
            <div class="widgetcontent">
              <div class="widgetbox" id="fdetail" style="display: none;"> 
			  </div>
            </div>
          </div>--> <!--end span4-->
          <div class="span4" id="temp_div" style="display:block;">
            <div class="clearfix">
              <h4 class="widgettitle">Profile</h4>
            </div>
            <div style="background-color: #FFFFFF;border: 1px solid #0866C6;height:300px;">&nbsp;</div>
          </div>
          <div style="display: none;" class="widgetbox" id="fdetail">
            
		  </div>
        </div>
        <!--row-fluid-->
        
        <?php include_once 'require/footer.php';?>
        <!--footer--> 
        
      </div>
      <!--maincontentinner--> 
    </div>
    <!--maincontent--> 
    
  </div>
  <!--rightpanel--> 
  
</div>
<!--mainwrapper-->
<div id="mymodal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Inquiry</h3>
	</div>
	<div class="modal-body" id="mymodal_html">
	
	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
		<button  onClick="submitPostListing();" class="btn btn-primary">Submit</button>
	</div>
	</div>
</body>
</html>
