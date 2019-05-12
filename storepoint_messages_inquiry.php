<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$storepointlocid = $_REQUEST['storepointlocid'];
$pt = $_REQUEST['pt'];

$sql = "SELECT name FROM locations where id={$storepointlocid}";
$result = mysql_query($sql);
$rowlocation = @mysql_fetch_assoc($result);
$locationName = $rowlocation["name"];

if ($_POST['txtSubject']!= null){
   
   $messagebody = mysql_real_escape_string($_POST["txtMessage"]);
   $subject = mysql_real_escape_string($_POST["txtSubject"]);   
 $que = "INSERT INTO `employee_master_location_storepoint`
(`sent_by_type`, `emp_master_id`, `location_id`, `location_employee_id`, `sent_datetime`, `subject`, `message`, `read`, reply)
VALUES
('Employee Master',{$_SESSION['client_id']},'{$storepointlocid}', NULL,'".date("Y-m-d H:i:s")."','{$subject}','{$messagebody}','No', 'No')";
$res = mysql_query($que);
$msg = 1;
}


$sql = "SELECT * FROM employee_master_locations EL, employees_master EM WHERE EM.empmaster_id = EL.empmaster_id AND EM.email = '".$_SESSION['email']."' LIMIT 1;";
		$result = mysql_query($sql);
		$row_user = mysql_fetch_array($result);
		$tab=($_REQUEST['tab']=='')?'inbox':$_REQUEST['tab'];

if($_POST['sId'] != null){
	
		$sql = "SELECT * FROM employee_master_location_storepoint WHERE id = '".mysql_real_escape_string($_POST['sId'])."'";
		$result = mysql_query($sql);
		$row_subject = mysql_fetch_array($result);

	if($row_subject['location_employee_id']!=""){
		$location_emp_field = "location_employee_id='".$row_subject['location_employee_id']."'";
	}else{
		$location_emp_field = "location_employee_id=NULL";
	}
	$now=date("Y-m-d H:i:s");
	if($_POST["message"]!=''){
	$sql2 = "INSERT INTO employee_master_location_storepoint set 
	sent_by_type ='Employee Master',
	emp_master_id='".$row_user['empmaster_id']."', 
	location_id='".$row_subject['location_id']."',
	$location_emp_field,
	sent_datetime='".$now."',
	subject='".$row_subject['subject']."',
	message='".mysql_real_escape_string($_POST["message"])."',
	`read`='No',
	reply = 'Yes'"; /*reply = '".$row_subject['reply']."'"*/
		mysql_query($sql2);
	}
	
}

	
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
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
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
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>

<script type="text/javascript">
var client_id = '<?php echo $client_id;?>';
jQuery(document).ready(function(){
    <?php if ($msg==1){?>
            jAlert("Message has been sent successfully!");
    <?php } ?>                 
})
</script>
<style>
.paddingBox {
    padding: 15px;
    text-align: center;
}
.error {
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
.DataTables_sort_icon {
	background-image: url("images/sort.png");
}
.msgauthor {
	display:block;
}
.msgbody {
	display:block;
}
.messagemenu{margin-top:0;}
.messagepanel {
	float:left;
	width:70%;
}
.span4 {
	float:left;
	width:28.5%!important;
	min-height:600px;
	margin-left:1.5%!important;
}
.widgetcontent {
	padding:0;
	min-width:98%;
	/*height:600px; */
	min-height: 276px;
}
/*div, span{ line-height:19px !important ;}*/
 @media screen and (max-width: 960px) {
 .messagepanel {
float:left;
width:65%;
}
 .span4 {
float:left;
	width:28.5%!important;
	min-height:600px;
	margin-top:15px;
	margin-left:1.5%!important;
}
 .widgetcontent {
padding:0;
min-width:98%;
/*height:600px;*/
min-height: 276px;
}
}
/*.msglist {
	display:none;
}*/
#inbox_box {
	display:block;
}
.message_loading{
	width:100%; text-align:center;
}


.btn-success {
	color: white !important;
}


/* unvisited link */
.btn-success:link {
  color: white !important;
}

/* visited link */
.btn-success:visited {
  color: white !important;
}

/* mouse over link */
.btn-success:hover {
  color: white !important;
}

/* selected link */
.btn-success:active {
  color: white !important;
}


</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
	
	showJob();
	jQuery('#form_comment').submit(function(e){
	var message =  jQuery('#message').val();
	var sId   =  jQuery('#sId').val();
	
	if(message=='')
	{
		alert('Please Enter Message');
		return false;
	}
	
	if(sId=='')
	{
		alert('Please Select a message');
		return false;
	}
	
		});
		
			
	var first=1;
	
/*jQuery('#inbox_box li').each(function( index ) {
  if(index==0){ jQuery(this).addClass('selected');   
 		callajax(this); 
		jQuery(".msgreply").css("display","block"); 
   }
});*/


	 /*  jQuery(".showJobs").click(function(){

		   jQuery(".unread").attr("class","unread showJobs");
		   jQuery(this).attr("class","unread showJobs selected");
			jQuery(".msgreply").css("display","block");
			callajax(this);
			
		});*/ 
		<?
if($_REQUEST['tab']!='')
{
?>
function loadShowTab(id)
{
	jQuery(".box_type").attr("class","box_type");
	jQuery("#"+id+"_menu").attr("class","box_type active");
	
	var count = jQuery("#"+id+"_menu li").length;
	if(count==0){
	//jQuery("#fdetail").html(''); 
	jQuery("#details_box").html(''); 
	jQuery(".msgreply").css("display","none"); jQuery("#sId").attr("value",'');	
	/*jQuery("#jobId2").attr("value",''); */
	}else{jQuery(".msgreply").css("display","block");}
	
	/*jQuery(".msglist").css("display","none");
	jQuery("#"+id+"_box").css("display","block");*/
	
}
loadShowTab('<?=$_REQUEST['tab']?>');
<?
}
?>
		jQuery(".box_type a").click(function(){
			
		});
	
	});
	
function getStorepointLocation(sId)
{
			var dataurl = "getStorepointLocation.php?sId=" + sId;
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
					jQuery("#fdetail").css("display","block");
					jQuery("#fdetail").html(data);
				 }
				}
			});
}
function showJob()
{
	jQuery(".showJobs").click(function(){

		   jQuery(".unread").attr("class","unread showJobs");
		   jQuery(this).attr("class","unread showJobs selected");
			jQuery(".msgreply").css("display","block");
			callajax(this);
			
		}); 
}
function getStorepointMessageDetails(sId,option)
{
			jQuery("#details_box").html('<div  class="message_loading"><img alt="" src="images/loaders/loader6.gif"></div>');
			/*var dataurl = "getChefedinMessageDetails.php?id=" + sId+"&option="+option+"&jobId="+jobId0+"&jobId2="+jobId1;*/
			var dataurl = "storepoint_getStorepointMessageDetailsInq.php?id=" + sId+"&option="+option+"&sId="+jobId0+"&storepointlocid=<?php echo $storepointlocid; ?>";
			jQuery.ajax({
				url:dataurl,
				type:"get",
				cache:false,
				async:false,
				dataType:"text", 
				success:function (data) {
				if(data=='0'){
				//location.reload(); 
				return false;}	
				 else {
				 	jQuery("#details_box").html(data);
						if(option=="sent")
						{
							jQuery(".messageview").css("height","600px");
						}
						jQuery('#form_comment').submit(function(e){
								var message =  jQuery('#message').val();
								var sId   =  jQuery('#sId').val();
								
								if(message=='')
								{
								alert('Please Enter Message');
								return false;
								}
								
								if(sId=='')
								{
								alert('Please Select a message');
								return false;
								}
								
						});
				 }
				}
			});
}
var jobId0=0;
var jobId1=0;
function callajax(obj)
{
			var str = jQuery(obj).attr("id");
			var strs = str.split("-");

			var sId = strs[0];
			jobId0 =strs[0];
			jobId1 =strs[1];
			//getStorepointLocation(sId);

			getStorepointMessageDetails(strs[1],strs[2]);
	}
var delayTimerEmail;
function searchEmail(value,tab) {
   clearTimeout(delayTimerEmail);
 delayTimerEmail = setTimeout(function() {
 		   var url  = "storepoint_ajax_email_subject.php";
			  jQuery.ajax({
				type: "POST",
				url: url,
				data: ({
					value 	: value,
					sendby 	: tab,
					client_id:<?=$_SESSION['client_id']?>
				  }),
				cache: false,
				success: function(data)
				{
					jQuery("#emailSubject").html(data);
					  showJob();
				}
			  });
			
			  
 }, 1500); 
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
      <li>Storepoint <span class="separator"></span></li>
      <li>Inquiry</li>
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
    <div style="float:right;margin-top: 10px; margin-right:10px;">
    	<button class="btn btn-primary btn-large addcode" onClick="window.location='storepoint_clients.php'">Back</button>&nbsp;
        <a class="mymodal btn btn-large btn-success" href="#composeModal" data-toggle="modal">Add</a>
    </div>
      <div class="pageicon"><span class="iconfa-tags"></span></div>
      <div class="pagetitle">
        <h5>Manage Storepoint Inquiries</h5>
        <h1>StorePoint - <?php echo $locationName;?></h1>
      </div>
    </div>
   
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
         	<div class="messagepanel" >
            <div class="messagehead"> </div>
            <!--messagehead-->
            <div class="messagemenu">
              <ul>
                <li class="back"><a><span class="iconfa-chevron-left"></span> Back</a></li>
                <li id="inbox_menu" class="box_type active"><a href="storepoint_messages_inquiry.php?tab=inbox&storepointlocid=<?php echo $storepointlocid;?>&pt=<?php echo $pt;?>"><span class="iconfa-inbox"></span> Inbox</a></li>
                <li id="sent_menu" class="box_type"><a href="storepoint_messages_inquiry.php?tab=sent&storepointlocid=<?php echo $storepointlocid;?>&pt=<?php echo $pt;?>"><span class="iconfa-plane"></span> Sent</a></li>
                <li id="done_menu" class="box_type"><a href="storepoint_messages_inquiry.php?tab=done&storepointlocid=<?php echo $storepointlocid;?>&pt=<?php echo $pt;?>"><span class="iconfa-edit"></span> Done</a></li>
              </ul>
            </div>
            <div class="messagecontent">
              <div class="messageleft">
                <form class="messagesearch" method="post">
                  <input type="text" name="search" id="search" class="input-block-level" placeholder="Search message and hit enter..." onKeyUp="searchEmail(this.value,'<?=$tab?>')"/>
				  <input type="hidden" name="tab" value="<?=$_REQUEST['tab']?>" id="tab">
                </form>
                 <span id="emailSubject">
				<?
					if($_REQUEST['tab']=='' || $_REQUEST['tab']=='inbox')
					{
				?>
                <ul id="inbox_box" class="msglist">
                  <?php	
								$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.emp_master_id='".$_SESSION['client_id']."' AND a.location_id = '$storepointlocid' 
												  and locations.id =  '$storepointlocid'
												 and a.read = 'No' and a.sent_by_type='".$pt."'";
												 
												if ($_POST["search"]!=""){
													$sql.= " AND (a.subject like '%".mysql_real_escape_string($_POST["search"])."%' OR a.message like '%".mysql_real_escape_string($_POST["search"])."%' ) ";
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
                  <li class="unread showJobs selected" id="<?php echo $row["sId"]."-".$row['sId']."-inbox";?>">
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
				}
				if($_REQUEST['tab']=='sent')
				{
				?>
                <ul id="sent_box" class="msglist">
                  <?php
											$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time ,emp_mas.first_name,emp_mas.last_name 
												 from employee_master_location_storepoint a,  locations,employees_master emp_mas
												 where a.location_id = '$storepointlocid' and a.sent_by_type = '".$pt."' 
												 and a.emp_master_id=emp_mas.empmaster_id
												  and locations.id =  '$storepointlocid'
												 and  a.emp_master_id = '".$_SESSION['client_id']."'   ";
											if ($_POST["search"]!=""){
													$sql.= " AND (a.subject like '%".mysql_real_escape_string($_POST["search"])."%' OR a.message like '%".mysql_real_escape_string($_POST["search"])."%' ) ";
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
                  <li class="unread showJobs" id="<?php echo $row["sId"]."-".$row['sId']."-sent";?>">
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
				}
				if($_REQUEST['tab']=='done')
				{
				?>
                <ul id="done_box" class="msglist">
                  <?php
											$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												  
												 from employee_master_location_storepoint a,  locations
												 where a.emp_master_id='".$_SESSION['client_id']."' AND a.location_id = '$storepointlocid' and a.read = 'Yes'  
												  and a.sent_by_type='".$pt."' 
												  and locations.id =  '$storepointlocid'
												  and  a.emp_master_id = '".$_SESSION['client_id']."'
												  ";
												  if ($_POST["search"]!=""){
													$sql.= " AND (a.subject like '%".mysql_real_escape_string($_POST["search"])."%' OR a.message like '%".mysql_real_escape_string($_POST["search"])."%' ) ";
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
                  <li class="unread showJobs" id="<?php echo $row["sId"]."-".$row['sId']."-done";?>">
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
				}
				?>
                </span>
              </div>
              <!--messageleft-->
              
              <div class="messageright">
				   <div id="details_box">
					
				   </div>
              </div>
              <!--messageright--> 
            </div>
            <!--messagecontent--> 
          </div>
          <!--messagepanel-->
          
          <div class="span4">
            <div class="clearfix">
              <h4 class="widgettitle">Location Profile</h4>
            </div>
            <div class="widgetcontent">
              <div class="widgetbox" id="fdetail" > <!--style="display: none;"-->
			  	<div style="text-align:left;">  
<?php
// get the location profile
	
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
					 where locations.id ='".$_REQUEST['storepointlocid']."'";
												// and  a.emp_master_id = '".$_SESSION['client_id']."' ";
												//and a.location_employee_id = emp.id
	
	 
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
							echo '<div class="paddingBox">No Location Profile Found</div>';
						}
					?>
					</div>
			  </div>
            </div>
          </div>
        </div> <!--row-fluid-->
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
<div id="composeModal" class="modal hide fade" style="max-height:500px !important;">
       <input type="hidden" name="locationid" value="<?php echo $storepointlocid?>" />   
       
        <form id="frmCompose" name="frmCompose" action="" method="post" class="">
			<div class="modal-header" style="max-height:50px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Compose Message</h3>
			</div>
			<div class="modal-body" style="max-height:350px !important;">
				<table width="90%" height="100%">
					<tr>
						<td width="30%">
							<div class="rows_pop control-group"><b>Subject: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%">
							<div class="rows_pop control-group">
							<input type="text" class="input-large" name="txtSubject" value="" id="txtSubject" placeholder="Subject" title="Subject" style="width:310px;">
							</div>
						</td>
					</tr>
					<tr>
						<td width="30%">
							<div class="rows_pop"><b>Message: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%" style="vertical-align: top;">
							<div class="rows_pop control-group">
								<textarea name="txtMessage"  id="txtMessage" style="width: 310px;resize:none;height: 127px;"></textarea>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</form>  
			<div class="modal-footer" style="text-align: center;max-height:50px !important;">
				<p class="stdformbutton">
					<button id="btnCancel" data-dismiss="modal" class="btn btn-primary" style="padding: 5px 12px 5px;">Cancel</button>
					<button type="submit" id="btnCompose" name="btnCompose"  class="btn btn-primary" value="btn" style="padding: 5px 12px 5px;">Submit</button>
				</p>
			</div>		
	</div> 
</body>
</html>
<script>
jQuery(document).ready(function(){
    jQuery('#btnCompose').click(function(e){
		e.preventDefault();
						
		if (jQuery("#txtSubject").val()==""){
                        jAlert("Please enter subject!")
			return false;		
                }  
		if (jQuery("#txtMessage").val()==""){
                        jAlert("Please enter message!")
			return false;		
                }  
                jQuery(this).attr("disabled",true);
		jQuery('#frmCompose').submit();   
	});
})
</script>

