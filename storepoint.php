<?php
require_once 'require/security.php';
include 'config/accessConfig.php'; ?>

<?php 
function msg_split($msgr){
		$msgarray = str_split($msgr,20);
		$msg = $msgarray[0];
		if($msgarray[1]!=""){
		$msg = $msg.'...';
		}
		return $msg;

}


if($_POST['sId'] != null){
	
		$sql = "SELECT * FROM employee_master_location_storepoint WHERE id = '".mysql_real_escape_string($_POST['sId'])."'";
		$result = mysql_query($sql);
		$row_subject = mysql_fetch_array($result);
		if($row_subject['location_employee_id']!=""){
		$l_emp_field = "location_employee_id='".$row_subject['location_employee_id']."'";
		}else{
		$l_emp_field = "location_employee_id=NULL";
		}
		

	
	$now=date("Y-m-d H:i:s");
	if($_POST["message"]!=''){
	$sql2 = "INSERT INTO employee_master_location_storepoint set 
	sent_by_type ='Employee Master',
	emp_master_id='".$row_subject['emp_master_id']."', 
	location_id='".$row_subject['location_id']."',
	$l_emp_field,
	sent_datetime='".$now."',
	subject='".mysql_real_escape_string($row_subject['subject'])."',
	message='".mysql_real_escape_string($_POST["message"])."',
	`read`='No',
	reply = 'Yes'";
	mysql_query($sql2) or die(mysql_error());
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
			
		if($primary_type=='1')	{$img= "Default Primary Type - Restaurants.png";}
		
		if($primary_type=='2')	{$img= "Default Primary Type - Bar.png";}
		
		if($primary_type=='3')	{$img= "Default Primary Type - Lounge.png";}
		
		if($primary_type=='4')	{$img= "Default Primary Type - Private.png";}
		
		if($primary_type=='7')	{$img= "Default Primary Type - Club.png";}
		
		if($primary_type=='6')	{$img= "Default Primary Type - Health.png";}
		
		if($primary_type=='9' )	{$img= "Default Primary Type - Home.png";}
		
		if($primary_type=='67')	{$img= "Default Primary Type - Other.png";}
		
		if($primary_type=='71')	{$img= "Default Primary Type - Quick Service.png";}
		
		if($primary_type=='5')	{$img= "Default Primary Type - Retail.png";}
		
		if($primary_type=='10')	{$img= "Default Primary Type - Travel.png";}
		
		if($primary_type=='8')	{$img= "Default Primary Type - Recreation.png";}
		
		if($primary_type=='78')	{$img= "Default Primary Type - Hotel.png";}
			return $img;
  }
  $sql = "SELECT * FROM employee_master_locations EL, employees_master EM WHERE EM.empmaster_id = EL.empmaster_id AND EM.email = '".$_SESSION['email']."' LIMIT 1;";
		$result = mysql_query($sql);
		$row_user = mysql_fetch_array($result);
		$tab=($_REQUEST['tab']=='')?'inbox':$_REQUEST['tab'];
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

<?php 
/* if(!isset($_REQUEST["lang"]) || $_REQUEST["lang"]=="")
{
	header("Location: messages.php?lang=".$_SESSION['lang']."#googtrans(en|".$_SESSION['lang'].")");
	exit;
} */
?>


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
</script>
<style>
body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
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
	if(count==0){ jQuery("#details_box").html(''); jQuery(".msgreply").css("display","none"); jQuery("#sId").attr("value",'');	
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
		
			/*
			jQuery(".box_type").attr("class","box_type");
			jQuery(this).parent().attr("class","box_type active");
			
			console.log(jQuery(this).parent().attr("id"));
			jQuery("#fdetail").html('');
			jQuery("#details_box").html('');
			
			if(jQuery(this).parent().attr("id") == "inbox_menu"){
				var count = jQuery("#inbox_box li").length;
				if(count==0){jQuery("#fdetail").html(''); jQuery("#details_box").html(''); jQuery(".msgreply").css("display","none"); jQuery("#jobId").attr("value",'');	jQuery("#jobId2").attr("value",''); }else{jQuery(".msgreply").css("display","block");}
				
				jQuery(".msglist").css("display","none");
				jQuery("#inbox_box").css("display","block");
				
			}else if(jQuery(this).parent().attr("id") == "sent_menu"){
				var count = jQuery("#sent_box li").length;			
				if(count==0){jQuery("#fdetail").html(''); jQuery("#details_box").html(''); jQuery(".msgreply").css("display","none"); jQuery("#jobId").attr("value",'');	jQuery("#jobId2").attr("value",'');}else{jQuery(".msgreply").css("display","block");}	
				
				jQuery(".msglist").css("display","none");
				jQuery("#sent_box").css("display","block");			
				
			}else if(jQuery(this).parent().attr("id") == "done_menu"){				
				var count = jQuery("#done_box li").length;			
				if(count==0){jQuery("#fdetail").html(''); jQuery("#details_box").html(''); jQuery(".msgreply").css("display","none"); jQuery("#jobId").attr("value",'');	jQuery("#jobId2").attr("value",'');}else{jQuery(".msgreply").css("display","block");}	
				
				jQuery(".msglist").css("display","none");
				jQuery("#done_box").css("display","block");			
			}
			
			return false;
			*/
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
function showJob1(id)
{	
			setTimeout(function(){
		   var ths = jQuery('#'+id);			
		   jQuery(".unread").attr("class","unread showJobs");
		   ths.attr("class","unread showJobs selected");
			jQuery(".msgreply").css("display","block");
			
			
			
			var strs = id.split("-");

			var sId = strs[0];
			jobId0 =strs[0];
			jobId1 =strs[1];
			getStorepointLocation(sId);

			getStorepointMessageDetails(strs[1],strs[2]);
			
		},2000);
}
function getStorepointMessageDetails(sId,option)
{
			jQuery("#details_box").html('<div  class="message_loading"><img alt="" src="images/loaders/loader6.gif"></div>');
			/*var dataurl = "getStorepointMessageDetails.php?id=" + sId+"&option="+option+"&jobId="+jobId0+"&jobId2="+jobId1;*/
			var dataurl = "storepoint_getStorepointMessageDetails.php?id=" + sId+"&option="+option+"&sId="+jobId0;
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
			getStorepointLocation(sId);

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
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Sales <span class="separator"></span></li><li>Messages</li>
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
      <?php require_once("lang_code.php");?>
    </ul>
    <div class="pageheader"> 
      <div class="pageicon"><span class="iconfa-user"></span></div>
      <div class="pagetitle">
        <h5>Manage Your Vendor Information</h5>
        <h1>Messages</h1>
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
                <li id="inbox_menu" class="box_type active"><a href="storepoint.php?tab=inbox&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-inbox"></span> Inbox</a></li>
                <li id="sent_menu" class="box_type"><a href="storepoint.php?tab=sent&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-envelope"></span> Sent</a></li>
                <li id="read_menu" class="box_type"><a href="storepoint.php?tab=read&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-share-alt"></span> Read</a></li>
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
									$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,a.location_employee_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id and a.read = 'No' and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
												 
												if ($_POST["search"]!=""){
													$sql.= " AND (a.subject like '%".mysql_real_escape_string($_POST["search"])."%' OR a.message like '%".mysql_real_escape_string($_POST["search"])."%' ) ";
												}
												$sql.="order by sId desc";
											$result = mysql_query($sql);
											$i = 0;
											while ($row = mysql_fetch_array($result)) {
											$i++;
											
												$image = "";
												if($row['sent_by_type'] != null && $row['sent_by_type'] == "Employee Master"){
													$sqlMaster = "select * from employees_master where empmaster_id = '".$row['emp_master_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
													$default_img= "images/Default - User.png";
												}else if($row['sent_by_type'] != null && $row['sent_by_type'] == "Location"){
													$sqlMaster = "select Locations.image,representative, COALESCE(location_types.subtype,location_types.id) as primary_type from Locations LEFT JOIN location_types ON location_types.id = Locations.primary_type where Locations.id = '".$row['location_id']."'";													
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$empq = mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees where id = '".$row['location_employee_id']."'"));
													$row['name'] = $row['name'].': '.$rowMaster['representative'];   
													$default_img= "images/Default - location.png";
													$image = APIPHP."images/".$rowMaster['image'];
													if(!isImage($image))
													{
														
														$image = APIPHP."panels/teampanel/images/primary-type/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
													
												}
												if($i==1){ ?>
													<script>
														showJob1('<?php echo $row["sId"]."-".$row['sId']."-inbox";?>');
													</script>
												<?php }
										?>
                  <li class="unread showJobs selected" id="<?php echo $row["sId"]."-".$row['sId']."-inbox";?>">
				  
				    <div class="thumb"><img onerror="this.src='<?php echo $default_img; ?>'" src="<?php echo $image; ?>" alt="" /></div>
					
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
						<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo msg_split($row["message"]);?></p>
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
											$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,a.location_employee_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time ,emp_mas.first_name,emp_mas.last_name 
												 from employee_master_location_storepoint a,  locations,employees_master emp_mas
												 where a.location_id = locations.id and a.sent_by_type = 'Employee Master' 
												 and a.emp_master_id=emp_mas.empmaster_id
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
												
												$empq = mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees where id = '".$row['location_employee_id']."'"));
												$row['name'] = $row['name'].': '.$empq['name'];
												
												$image = APIPHP."images/".$rowMaster['image'];
												if(!isImage($image))
												{
													$image = APIPHP."panels/teampanel/images/primary-type/".getPrimaryTypeImage($rowMaster['primary_type']); 
												}
												
												
										?>
                  <li class="unread showJobs" id="<?php echo $row["sId"]."-".$row['sId']."-sent";?>">
                    		    <div class="thumb"><img src="<?php echo $image; ?>" alt="" /></div>
					
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
					<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo msg_split($row["message"]);?></p>
                    </div>
                  </li>
                  <?php
											
											}	
											
										?>
                </ul>
                <?
				}
				if($_REQUEST['tab']=='read')
				{
				?>
                <ul id="done_box" class="msglist">
                  <?php
				  
				  
				  $sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,a.location_employee_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id and a.read = 'Yes' and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
				  
		
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
													$default_img= "images/Default - User.png";
												}else if($row['sent_by_type'] != null && $row['sent_by_type'] == "Location"){
													$sqlMaster = "select image,representative,primary_type from Locations where id = '".$row['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$default_img= "images/Default - location.png";
													$image = APIPHP."images/".$rowMaster['image'];
													$empq = mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees where id = '".$row['location_employee_id']."'"));
													$row['name'] = $row['name'].': '.$rowMaster['representative'];
													
													if(!isImage($image))
													{
														$image = APIPHP."panels/teampanel/images/primary-type/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
													
												}
												
										?>
                  <li class="unread showJobs" id="<?php echo $row["sId"]."-".$row['sId']."-read";?>">
                  
				    <div class="thumb"><img onerror="this.src='<?php echo $default_img; ?>'" src="<?php echo $image; ?>" alt="" /></div>
					
                    <div class="summary"> <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo $row["time"];?></small></span>
					<h4><?php echo $row["name"];?></h4>
                      <h4><?php echo $row["subject"];?></h4>
                      <p><?php echo msg_split($row["message"]);?></p>
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
            <div class="widgetcontent" style="min-height:100%;">
              <div class="widgetbox" id="fdetail" style=" padding:20px;">Nothing To Display!</div>
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

</body>
</html>
