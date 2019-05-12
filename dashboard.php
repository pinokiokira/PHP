<?php 

if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
	ob_start("ob_gzhandler"); 
}else{ 
	ob_start();
}
require_once 'require/security.php';
require_once 'config/accessConfig.php';


error_reporting(E_ERROR | E_PARSE);

if(isset($_POST) && $_POST['reqtype']=='updateStatustoread' && $_POST['msgId']>0){
	$update = "UPDATE employee_request SET request_off_status='Cancelled', `read` = 'Yes' WHERE emp_request_id = '".$_POST['msgId']."'";
	mysql_query($update) or die(mysql_error());
	if($update){
		echo 'Yes';
	}
	exit();
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
<style>
body {
	top:0px!important;
}
.goog-te-banner-frame {
	margin-top: -50px!important;
}
.maincontentinner {
	padding: 15px 20px 20px;
}
.label-left.form-horizontal .control-label {
	text-align:left;
	font-weight:bold;
}

#cboxOverlay {
	display: none !important;
}
.blockUI.blockOverlay {
	display: none !important;
}

</style>
<style>
.error {
	color: #FF0000;
	padding-left:10px;
}
.colorbutton {
	border-color: #BBBBBB;
	box-shadow: none;
	display: inline-block;
	margin-bottom: 5px;
	background: none repeat scroll 0 0 #EEEEEE;
	color: #FFFFFF !important;
	font-size: 14px;
	text-decoration:none !important;
	padding: 7px 12px 9px;
	text-shadow: none;
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	background-color: #F5F5F5;
	text-align: center;
	vertical-align: middle;
	cursor:pointer;
}
.green {
	background-color:#0C0;
}
.red {
	background-color: #FF0000;
}
.orange {
	background-color: #FF6600;
}
.yellow {
	background-color: #FFFF00;
}
.blue {
	background-color: #0000CC;
}
.lightgray {
	background-color: #D3D3D3;
}
.darkgray {
	background-color: #A9A9A9;
}
.fc-header-title h2 {
	font-size:19px !important;
}
.label-left.form-horizontal .control-label {
	font-weight: bold;
	text-align: left;
}
.btn.btn-default {
	color:#333 !important;
}
 @media screen and (max-width: 1280px) {
 .row-fluid .span8 {
 width:63.812%;
}
}
@media screen and (max-width: 1600px) {
 .fc-header-title h2 {
 font-size: 14px !important;
}
}
.msglist {
	height:auto !important;
}
.msglist li:last-child {
  border-bottom: none !important;
}
.msglist li p {
	line-height: 10px;
	margin: 0px;
}
.widgetcontent {
	overflow: auto;
}
.btn{ padding:9px 12px 9px;}
/*.maincontentinner {
	padding:20px 9px 20px 20px;
}*/
.pswd_info{position:relative;width:320px;padding:0px;background:#ddd;border:0px solid #ddd;left:2%;}
.pswd_info::before {width: 0;height: 0;border-top: 10px solid transparent;border-bottom: 10px solid transparent;border-right:10px solid #ddd;content: "";position:absolute;top:-1px;left:-2.5%;display:block;}
table.pswd_info tr td{ border:none; color:#000 !important; text-align:left; padding:2px; font-size:14px; }
table.pswd_info tr th{ text-align:center !important; }

.ref_info{position:absolute;width:260px;padding:0px;background:#ddd;border:0px solid #ddd;left:22%;font-family: 'RobotoRegular', 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px;line-height: 15px;}
.ref_info::before {width: 0;height: 0;border-top: 10px solid transparent;border-bottom: 10px solid transparent;border-right:10px solid #ddd;content: "";position:absolute;top:-1px;left:-2.5%;display:block;}
table.ref_info tr td{ border:none; color:#000 !important; text-align:left; padding:5px 5px 0 5px; }
table.ref_info tr th{ text-align:center !important; }

.loc_info{position:absolute;width:300px;padding:0px;background:#ddd;border:0px solid #ddd;left:22%;font-family: 'RobotoRegular', 'Helvetica Neue', Helvetica, sans-serif;font-size: 12px;line-height: 15px;}
.loc_info::before {width: 0;height: 0;border-top: 10px solid transparent;border-bottom: 10px solid transparent;border-right:10px solid #ddd;content: "";position:absolute;top:-1px;left:-2.5%;display:block;}
table.loc_info tr td{ border:none; color:#000 !important; text-align:left; padding:5px 5px 0 5px; }
table.loc_info tr th{ text-align:center !important; }
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>

</head>
<body>
<div id="requestmodal" class="modal hide fade">
  <div class="modal-header" >
    <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
    <h3>Request Details</h3>
  </div>
  <div class="modal-body" style="max-height:500px !important;" id="requestbody"> </div>
  <div class="modal-footer" style="text-align: center;">
    <p class="stdformbutton">
      <button id="btnCancel" data-dismiss="modal" class="btn btn-default">Close</button>
    </p>
  </div>
</div>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="dashboard.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Dashboard</li>
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
      <?php
                if (!isset($_GET['sdate'])) {
                   // $sdate = date("Y-m-d", strtotime("-30 days", strtotime(date("Y-m-d"))));
                    $sdate = date('Y-m-01');
                } else {
                    $sdate = $_GET['sdate'];
                }

                if (!isset($_GET['edate'])) {
                    //$edate = date("Y-m-d");
					$edate  = date('Y-m-t');
                } else {
                    $edate = $_GET['edate'];
                }
                
                
                ?>
      <script type="text/javascript">
                           jQuery(function() {
        jQuery( "#sdate" ).datepicker({
            changeMonth: true,
			dateFormat:"yy-mm-dd",
            changeYear: true,
			onSelect: function(selected) {
        	var date = new Date(selected);
			date.setDate(date.getDate()-1);
    		date.setMonth(date.getMonth()+1);
			jQuery( "#edate" ).datepicker("setDate",date);
			}
			/*** comment for now
			onSelect: function(dateText, inst) {				
				var d = new Date(dateText);
				jQuery("#calendar").fullCalendar("gotoDate", d.getYear(), d.getMonth(), d.getDate());
			},
				****/		
        });
    });
	jQuery('#sdate').keypress(function(event) {		
					return false;
	});
	
	
	 jQuery(function() {
        jQuery( "#edate" ).datepicker({
            changeMonth: true,
			dateFormat:"yy-mm-dd",
            changeYear: true,
			/*** comment for now
			onSelect: function(dateText, inst) {				
				var d = new Date(dateText);
				jQuery("#calendar").fullCalendar("gotoDate", d.getYear(), d.getMonth(), d.getDate());
			},
				****/		
        });
    });
	jQuery('#edate').keypress(function(event) {		
					return false;
	});
	jQuery(document).ready(function(e) {
		//alert('1');
        //jQuery('.fc-week .fc-state-highlight').prev('.fc-week td').css('background-color','red');
		//jQuery('.fc-state-highlight').parent().children().css('background-color','red');
		var i=0;
		jQuery('.fc-border-separate > tbody  > tr').each(function() {
			jQuery(this).find('td').each(function () {
						//elem = jQuery(this);
						if (jQuery(this).hasClass('fc-state-highlight')) { i=1;
						   return false;
						   }
						   jQuery(this).css('background-color','#ccc');
				});
				if(i==1)
					return false;
		});
   
	jQuery('.fc-button-prev,.fc-button-today,.fc-button-next').live('click',function(){
		var i=0;
		jQuery('.fc-border-separate > tbody  > tr').each(function() {
			jQuery(this).find('td').each(function () {
				//var today = jQuery('.fc-today').data('date');
				var date = new Date();
				var d = date.getDate();
				if(d<10){ d = '0'+d;  }
				var m = date.getMonth()+1;
				if(m<10){ m = '0'+m; }
				var y = date.getFullYear();
				var today = y+'-'+m+'-'+d;
				var this_date = jQuery(this).data('date');
				if(this_date<today){
						//elem = jQuery(this);
						
						if (jQuery(this).hasClass('fc-state-highlight')) { i=1;
						   return false;
						   }
						   jQuery(this).css('background-color','#ccc');
						   }
						});
				if(i==1)
					return false;
		});
	
	});
	
});
 </script>
      <link rel="stylesheet" href="css/jquery.animateSlider.css">
      <script src="js/newsTicker.js"></script>
      <script>
 
 jQuery(function () {
	jQuery('#newsList').newsTicker();
});


 </script>
      <style>
.newsCss{
	height:auto;
	padding:5px;
}
</style>
      <div class="taPanel" style="display:block;float:right;color:#000;padding: 15px;">
        <form method="get" id="fdate" name="fdate">
          <table border="0" cellpadding="0" cellspacing="0" >
            <tbody>
              <tr>
                <td align="right"><table style="width:auto;display: block;float: right; ">
                    <tbody>
					<tr>
                        <td style="vertical-align:top;"><label style="display: inline-block;position: relative;bottom: 5px;">Start Date:</label>
               <input class="input-small" name="sdate" id="sdate" value="<?=$sdate?>" type="text" onChange="lastMonth(this.value)" style="font-size:13px; height:32px; "/>
                        </td>
                        <td style="padding-left:5px; vertical-align:top;"><p>
                            <label style="display: inline-block;position: relative;bottom: 5px;">End Date:</label>
                            <input class="input-small" name="edate" id="edate"  value="<?=$edate?>" type="text" style="font-size: 13px; height: 32px;"/>
                          </p></td>
                        <td style="padding-left:5px;vertical-align: top;"><p>
                            <button class="btn btn-primary btn-large" onClick="javascript:return searchValidate();" style="margin-top:0px; height:41px;">Go</button>
                          </p></td>
                        <!-- <td style="padding-left:5px;"><form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter...">
            </form></td>-->
                      </tr>
					
					
                <!--       <tr>
                        <td><label style="display: inline-block;position: relative;bottom: 5px;">Start Date:</label>
                          <input class="input-small" name="sdate" id="sdate" value="<?=$sdate?>" type="text" onChange="lastMonth(this.value)" style="font-size: 13px; height: 38px;"/>
                        </td>
                        <td style="padding-left:5px;"><p>
                            <label style="display: inline-block;position: relative;bottom: 5px;">End Date:</label>
                            <input class="input-small" name="edate" id="edate"  value="<?=$edate?>" type="text" style="font-size: 13px; height: 38px;"/>
                          </p></td>
                        <td style="padding-left:15px;vertical-align: top;"><p>
                            <button class="btn btn-primary btn-large" onClick="javascript:return searchValidate();" style="margin-right: -15px;margin-top: -1px;">Go</button>
                          </p></td>
                        <td style="padding-left:5px;"><form action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter...">
            </form></td>-->
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
      <script type="text/javascript">
	   		function lastMonth(date)
			{
				var arr = date.split("-");
				var dd = new Date(arr[0], arr[1], 0);
				var lastDay= dd.getDate();		
				var edate=arr[0]+'-'+arr[1]+'-'+lastDay;
				//alert(lastDay);
				document.getElementById('edate').value=edate;
			}
	   </script>
      <div class="pageicon"><span class="iconfa-laptop"></span></div>
      <div class="pagetitle">
        <h5>View All Dashboard Information</h5>
        <h1>Dashboard</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <div class="span8" id="locationsDiv" statuswhr="Active">
            <h4 class="widgettitle">My Contacts &amp; Referrals</h4>
            <div class="widgetcontent">
			  <h4 style="font-size:21px;">Referrals</h4>
              <div>
			  <?php 
			  	$sqlanncref = "SELECT Refer_Location,announcements_referrals_id,refer_locarion_link,refer_team_employee_master_link,Refer_Location_Contact,Refer_phone,Refer_email,
							Created_datetime,refer_team_name,refer_team_email
							 FROM announcements_referrals WHERE from_employee_master='".mysql_real_escape_string($_SESSION["client_id"])."'";
				$resanncref = mysql_query($sqlanncref);
				 if (mysql_num_rows($resanncref)>0){
			  ?>	
              		<br>
					<ul id="medialist" class="listfile">
					 <?php 
					 while($rowanncref = mysql_fetch_array($resanncref)){
					 ?>
						<li class="image">
						   <!--<a href="#"><img src="images/thumbs/image1.png" alt="" style="width:120px; height:100px;" /></a>-->
						   <span class="locthumb" id="<?php echo $rowanncref["announcements_referrals_id"];?>">
                       <?php if($rowanncref['Refer_Location'] != ""){ 
					   
					   		$sqlMaster = "select image,name from locations where id = '".$rowanncref["refer_locarion_link"]."'";
								$resultMaster = mysql_query($sqlMaster);
								$rowMaster = mysql_fetch_array($resultMaster);
								$image = APIPHP."images/".$rowMaster['image'];
								if(!isImage($image))
								{
									//$image = API."panels/teampanel/images/".getPrimaryTypeImage($rowMaster['primary_type']); 
									$image = "images/Default - Location.png";
								}
								$reflocname= $rowMaster['name'];
					   		$refimage = $image; 
					   } else { 
							$sqlMaster = "select image,first_name,last_name from employees_master where empmaster_id = '".$rowanncref["refer_team_employee_master_link"]."'";
								$resultMaster = mysql_query($sqlMaster);
								$rowMaster = mysql_fetch_array($resultMaster);
								$image = APIPHP."images/".$rowMaster['image'];
								if(!isImage($image))
								{
									//$image = API."panels/teampanel/images/".getPrimaryTypeImage($rowMaster['primary_type']); 
									$image = "images/Default - User.png";
								}
								$refempname= $rowMaster['first_name']." ".$rowMaster['last_name'];
					   		$refimage = $image; 
					   }
					   ?>
                        <img src="<?php echo $refimage; ?>" alt="" style="max-height: 100px;max-width: 120px;height:100px;width:120px;" onMouseOver="rtooltip_res('<?php echo $rowanncref["announcements_referrals_id"]; ?>')" onmouseleave="rtooltip_res1('<?php echo $rowanncref["announcements_referrals_id"]; ?>')" id="rimage<?php echo $rowanncref["announcements_referrals_id"];?>">
						<table class="ref_info <?php echo $rowanncref["announcements_referrals_id"]; ?>" id="ref_res<?php echo $rowanncref["announcements_referrals_id"]; ?>" style="display:none;">
						 <?php if($rowanncref['Refer_Location'] != ""){ ?>
							<tr>
								<td style="width:20%;"><strong><?php echo $rowanncref['Refer_Location']; ?></strong></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['Refer_Location_Contact']; ?></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['Refer_phone']; ?></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['Refer_email']; ?></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['Created_datetime']; ?></td>
							</tr>
							<?php if($rowanncref['refer_locarion_link'] != ""){ ?>
							<tr>
								<td>Linked To: <?php echo $reflocname." (ID: ".$rowanncref['refer_locarion_link'].")"; ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td style="width:20%;"><strong><?php echo $rowanncref['refer_team_name']; ?></strong></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['refer_team_email']; ?></td>
							</tr>
							<tr>
								<td><?php echo $rowanncref['Created_datetime']; ?></td>
							</tr>
							<?php if($rowanncref['refer_team_employee_master_link'] != ""){ ?>
							<tr>
								<td>Linked To: <?php echo $refempname." (ID: ".$rowanncref['refer_team_employee_master_link'].")"; ?></td>
							</tr>
							<?php } ?>
							<?php } ?>
						</table>
                        <?php  ?>
                        </span>
							   <span class="filename">
								   <?php 
									   if($rowanncref['Refer_Location'] != ""){
										echo $rowanncref['Refer_Location']; 
									   } else { echo $rowanncref['refer_team_name'];}
								   ?>
							   </span>
						</li>
					<?php } ?>
					</ul>
                <br class="clearall" />
				<?php } else{ ?>
					No current referrals!
					<!--<ul id="medialist" class="listfile">
						<li class="image">
							<span class="locthumb" id="67">
							<img src="images/Default - Location.png" alt="" style="max-height: 100px;max-width: 120px;height:100px;width:120px;">
							</span>
						</li>
					</ul>-->
					
				<?php } ?>
                
			  </div>
              	  <br>  	
				  <h4 style="font-size:21px;">My Locations</h4>
				  <div>
				  <?php $sqlempmasemail = "select email from employees_master where empmaster_id = '".$_SESSION['client_id']."'";
			$exeempmasemail = mysql_query($sqlempmasemail);
			$resempmasemail = mysql_fetch_array($exeempmasemail);
			$sqlreq="SELECT em.location_id, em.status, em.emp_id, em.id, em.first_name, em.last_name, loc.primary_type FROM employees em
                                        INNER JOIN locations loc ON loc.id = em.location_id
                                    WHERE  em.email='".$resempmasemail["email"]."' AND loc.status='active' AND em.status='A' ORDER BY loc.name";
				$exereq = mysql_query($sqlreq);
				if(mysql_num_rows($exereq)>0){
				?>
                	<br>
				  	<div id="LocationsLinkedWithEmployee123"> 
						<ul id="medialist" class="listfile">
						<?php while($row = mysql_fetch_array($exereq)){
								$status = $row['status']=='A'?'Active':'Inactive';
								$location_id = $row['location_id'];
                                $primary_type = $row['primary_type'];
								
								$value=mysql_query("select name from location_types where id='".$primary_type."'");
                                $type=mysql_fetch_assoc($value);
                                $primarytypename=$type['name'];
								
								$sqlMaster = "select name,image,primary_type,address,address2,city,state,zip,country,phone,created_date from Locations where id = '".$location_id."'";
								$resultMaster = mysql_query($sqlMaster);
								$rowMaster = mysql_fetch_array($resultMaster);
								$image = APIPHP."images/".$rowMaster['image'];
								if(!isImage($image))
								{
									$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
								}
								
								$sqlstate = "select name from states where id = '".$rowMaster["state"]."'";
								$resultstate = mysql_query($sqlstate);
								$rowstate = mysql_fetch_array($resultstate);
								
								$sqlcountry = "select name from countries where id = '".$rowMaster["country"]."'";
								$resultcountry = mysql_query($sqlcountry);
								$rowcountry = mysql_fetch_array($resultcountry);
						 ?>
							<li class="image">
								<span class="locthumb">
								<img src="<?php echo $image; ?>" alt="" style="max-height: 100px;max-width: 120px;height:100px;width:120px;" onMouseOver="ltooltip_res('<?php echo $row["id"]; ?>')" onmouseleave="ltooltip_res1('<?php echo $row["id"]; ?>')" id="limage<?php echo $row["id"];?>">
						<table class="loc_info <?php echo $row["id"]; ?>" id="loc_res<?php echo $row["id"]; ?>" style="display:none;">
							<tr>
								<td style="width:20%;"><?php echo $status; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $rowMaster['name']." (".$location_id.")"; ?></strong></td>
							</tr>
							<tr>
								<td>Employee: <?php echo $row["first_name"]." ".$row["last_name"]." (ID: ".$row["emp_id"].")"; ?></td>
							</tr>
							<?php if($rowMaster['address'] != "") { 
								if($rowMaster['address'] != "" && $rowMaster['address2'] != "")
									$locaddres2 = ", ".$rowMaster['address2'];
									else $locaddres2 = "";
							?>
							<tr>
								<td><?php echo $rowMaster['address'].$locaddres2; ?></td>
							</tr>
							<?php } ?>
							<?php if($rowMaster['city'] != "") { ?>
							<tr>
								<td><?php echo $rowMaster['city'].", ".$rowstate["name"].", ".$rowMaster['zip'].", ".$rowcountry['name']; ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>Phone: <?php echo $rowMaster['phone']; ?></td>
							</tr>
							<tr>
								<td>Created: <?php echo $rowMaster['created_date']; ?></td>
							</tr>
						</table>
								</span>
								<span class="filename">
								   <?php echo $rowMaster['name'];  ?>
							   </span>
							</li>
						<?php } ?>
						<?php	$sqlreqassoc="SELECT em.location_id, em.Status,em.id, loc.primary_type FROM employee_master_locations em
                                        INNER JOIN locations loc ON loc.id=em.location_id
                                    WHERE em.empmaster_id='".$_SESSION['client_id']."' AND loc.status='active' AND em.Status='Active' ";
				$exereqassoc = mysql_query($sqlreqassoc);
				if(mysql_num_rows($exereqassoc)>0){
				?>
				<?php while($row = mysql_fetch_array($exereqassoc)){
								$location_id = $row['location_id'];
                                $primary_type = $row['primary_type'];
								
								$sqlMaster = "select name,image,primary_type,address,address2,city,state,zip,country,phone,created_date from Locations where id = '".$location_id."'";
								$resultMaster = mysql_query($sqlMaster);
								$rowMaster = mysql_fetch_array($resultMaster);
								$image = APIPHP."images/".$rowMaster['image'];
								if(!isImage($image))
								{
									$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
								}
								
								$sqlstate = "select name from states where id = '".$rowMaster["state"]."'";
								$resultstate = mysql_query($sqlstate);
								$rowstate = mysql_fetch_array($resultstate);
								
								$sqlcountry = "select name from countries where id = '".$rowMaster["country"]."'";
								$resultcountry = mysql_query($sqlcountry);
								$rowcountry = mysql_fetch_array($resultcountry);
						 ?>
							<li class="image">
								<span class="locthumb">
								<img src="<?php echo $image; ?>" alt="" style="max-height: 100px;max-width: 120px;height:100px;width:120px;" onMouseOver="ltooltip_res('<?php echo $row["id"]; ?>')" onmouseleave="ltooltip_res1('<?php echo $row["id"]; ?>')" id="limage<?php echo $row["id"];?>">
						<table class="loc_info <?php echo $row["id"]; ?>" id="loc_res<?php echo $row["id"]; ?>" style="display:none;">
							<tr>
								<td style="width:20%;"><?php echo $status; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $rowMaster['name']." (".$location_id.")"; ?></strong></td>
							</tr>
							<!--<tr>
								<td>Employee: <?php echo $row["first_name"]." ".$row["last_name"]." (".$row["emp_id"] . "-". $row["id"].")"; ?></td>
							</tr>-->
							<?php if($rowMaster['address'] != "") { 
								if($rowMaster['address'] != "" && $rowMaster['address2'] != "")
									$locaddres2 = ", ".$rowMaster['address2'];
									else $locaddres2 = "";
							?>
							<tr>
								<td><?php echo $rowMaster['address'].$locaddres2; ?></td>
							</tr>
							<?php } ?>
							<?php if($rowMaster['city'] != "") { ?>
							<tr>
								<td><?php echo $rowMaster['city'].", ".$rowstate["name"].", ".$rowMaster['zip'].", ".$rowcountry['name']; ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td>Phone: <?php echo $rowMaster['phone']; ?></td>
							</tr>
							<tr>
								<td>Created: <?php echo $rowMaster['created_date']; ?></td>
							</tr>
						</table>
								</span>
								<span class="filename">
								   <?php echo $rowMaster['name'];  ?>
							   </span>
							</li>
						<?php } } ?>
						</ul>
					</div>
				<?php }else{ ?>
                Not linked to any Location as an employee.
                <?php } ?>
				  </div>
            </div>
          </div>
          <!--span6-->
          <?php 
				   $cur_date = date('Y-m-d');
				   $query_annc = "SELECT Employee_master_announcements_id,Subject,Message,Action,Url_link
				   				 FROM announcements WHERE Status = 'Active' AND Product = 'TeamPanel' AND Start_date <= '".$cur_date."' AND End_date >= '".$cur_date."'";
				   $res_annc = mysql_query($query_annc) or die(mysql_error());
				   $count = mysql_num_rows($res_annc);
				    ?>
          <div style="width:32.4%;margin-left: 18px;" class="span4">
            <h5 class="subtitle capital_word">Announcements</h5>
            <div class="divider15"></div>
            <div class="alert alert-block">
              <?php if($count>0){?>
              <!--<button data-dismiss="alert" class="close" type="button">×</button>-->
			  <button class="close" type="button">×</button>
              <?php } ?>
              <p style="margin: 0px 0">
                <input type="hidden" name="annc_count" id="annc_count" value="<?php echo $count; ?>" >
				
              <div id="newsData" class="newsCss"></div>
              <ul id="newsList">
                <?php if($count>0){ $i=0; while($row_annc = mysql_fetch_array($res_annc)){
						 ?>
                <li class="news-item<?php echo $row_annc["Employee_master_announcements_id"]; ?>">
                  <div style="font-size:16px; margin-bottom:5px; font-weight:normal;"><?php echo $row_annc['Subject']; ?>!</div>
                  <?php echo $row_annc['Message']; ?>
                  <input type="hidden" id="referral_subject_<?php echo $row_annc['Employee_master_announcements_id'];?>" value="<?php echo $row_annc['Subject']; ?>" />
				  <input type="hidden" name="anncdivid" id="anncdivid" value="<?php echo $row_annc["Employee_master_announcements_id"]; ?>" >
                  <?php echo $row_annc['Message']; ?>
                  <input type="hidden" id="referral_message_<?php echo $row_annc['Employee_master_announcements_id'];?>" value="<?php echo $row_annc['Message']; ?>" />
                  <?php if($row_annc['Action']=='Link'){?>
                  <br>
                  <div style="text-align:center; width:100%; margin-top:10px;"><a class="btn btn-primary" href="Http://<?php echo $row_annc['Url_link']; ?>" target="_blank" >Select</a></div>
                  <?php }else if($row_annc['Action']=='Referral'){ ?>
                  <br>
                  <div style="text-align:center; width:100%; margin-top:10px;">
                    <button class="btn btn-success btn-large" id="refferral_<?php echo $row_annc['Employee_master_announcements_id'];?>" href="#announcementsformmodal" onClick="showmessageinfo(<?php echo $row_annc['Employee_master_announcements_id'];?>);" data-toggle="modal">Referral</button>
                  </div>
                  <?php }else if($row_annc['Action']=='Team'){ ?>
				   <br>
                  <div style="text-align:center; width:100%; margin-top:10px;">
                    <button class="btn btn-success btn-large" id="teamrefferral_<?php echo $row_annc['Employee_master_announcements_id'];?>" href="#teamannouncementsformmodal" onClick="teamshowmessageinfo(<?php echo $row_annc['Employee_master_announcements_id'];?>);" data-toggle="modal">Referral</button>
                  </div>
				 <?php } ?> 
                </li>
                <?php $i++; } }else{ ?>
                <li>
                  <div>No Current Announcements</div>
                </li>
                <?php } ?>
              </ul>
              </p>
            </div>
			<?php 
				if(mysql_num_rows($exereq)>0){
			?>
            <h4 class="widgettitle" style= "text-align:center;">REQUESTS</h4>
            <div class="widgetcontent" style="max-height: 183px;overflow-y: auto;">
			  <?php
                $sql = "Select empmsg.emp_request_id,
						empmsg.subject, 
						empmsg.thread_id,
						empmsg.read, 
						empmsg.location_id,
						CONCAT(empmsg.startdate,' ',empmsg.starttime) as startdate,
						empmsg.request_off_status,  
						emp.first_name as fsendemp, 
						emp.last_name as lsendemp, 
                        emp.image as senderimage, 
						emp.emp_id
                        from employee_request empmsg 
                        left join employees emp on empmsg.emp_id = emp.id 
                        where  (empmsg.emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}'))";
                        
                         $sql .= " AND empmsg.startdate > DATE_SUB(NOW(), INTERVAL 15 DAY) ORDER BY empmsg.created_datetime DESC"; //echo $sql;
                        $result = mysql_query($sql) or die(mysql_error());
                    if (mysql_num_rows($result)>0){
            ?>
			<ul class="msglist">
                <?php 
                    $firstRecord = True;
                    $msgstyle = "";
                    while ($row=mysql_fetch_assoc($result)){
                ?>
                <li class="getmessage<?php echo $msgstyle;?>" data-id="<?php echo $row["emp_request_id"];?>" data-subject="<?php echo $row["subject"];?>" data-thread="<?php echo $row["thread_id"];?>" data-status="<?php echo $row["read"];?>" id="message<?php echo $row["emp_request_id"];?>">
                  <?php if ($row["senderimage"]!=""){?>
                  <div class="thumb"><img src="<?php echo APIPHP. "images/". $row["senderimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                  <?php }else{?>
                  <div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
                  <?php }?>
                  <div class="summary"> <span class="date pull-right"><small><?php echo date("Y-m-d H:i",strtotime($row["startdate"]));?></small><br>
                    <?php if ($row["request_off_status"]=="Accepted"){?>
                    <span id="img_<?php echo $row["emp_request_id"];?>" style="color:green;float: right;margin-top: 5px;margin-right: 30%;"><img src="images/greencheck.png" title="Accepted" alt="Accepted"/></span>
                    <?php }else if ($row["request_off_status"]=="Declined" || $row["request_off_status"]=="Cancelled"){ ?>
                    <span id="img_<?php echo $row["emp_request_id"];?>" style="color:red;float: right;margin-top: 5px;margin-right: 30%;"><img src="images/redcross.png" title="<?=$row["request_off_status"];?>" alt="<?=$row["request_off_status"];?>"></span>
                    <?php } else if ($row["request_off_status"]=="Pending"){ ?>
                    <span id="img_<?php echo $row["emp_request_id"];?>" style="color:red;float: right;margin-top: 5px;margin-right: 30%;"><img src="images/pendingicon.png" style="width: 20px;" title="Pending" alt="Pending"></span>
                    <?php }else if ($row["request_off_status"]=="Modified"){ ?>
                    <span id="img_<?php echo $row["emp_request_id"];?>" style="color:red;float: right;margin-top: 5px;margin-right: 30%;"><img src="images/Inactive & Missing Punch - 16.png" style="width: 20px;" title="Modified" alt="Modified"></span>
                    <?php } ?>
                    </span>
                    <h4><?php echo $row["fsendemp"] . " " . $row["lsendemp"] . "  (".$row["location_id"].")";?></h4>
                    <p><strong>
                      <?php if ($row["subject"] == "") {echo "No Subject";} else{ 
					  			$sub = explode("-",$row["subject"]);
								echo str_replace('Dayoff','Day Off',$sub[0]." ".$sub[1]);
								echo "<br>".$sub[2];
							}?>
                      </strong> </p>
					  
                    <p> <?php echo charlimit($row["message"],30);?></p>
                  </div>
                </li>
                <?php $msgstyle = ""; }?>
              </ul>
			  <?php } else {echo "<div style='padding-left: 5px;padding-top: 8px;font-size: 15px;'>No Active Requests.</div>";} ?>
			</div>
			<?php } ?>
              
           	
			<?php 
				$sqlstaffpoint = "select jobs.subject, jobs.message, jobs.sent_by_type , jobs.sent_datetime, jobs.id as jobId, jobs.emp_master_id,
												job.id, job.job, job.description, job.status, job.posted_date, job.start_date, job.end_date,
												 job.time_from, job.time_to, job.days_from, job.days_to, job.contact, job.type, job.requirements, 
												 COALESCE(jobs.location_id,corporate.id) as location_id, COALESCE(corporate.name,locations.name) as name, locations.city ,
												 corporate.id as cid,corporate.name as cname ,corporate.city as ccity,corporate.image as cimage, corporate.primary_type as cprimary_type
												 , DATE_FORMAT(jobs.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(jobs.sent_datetime,'%H:%i') as time 
												 from employee_master_job_inquiries jobs, location_jobs job, locations,corporate
												 where jobs.location_job_id = job.id  and jobs.read = 'No' 
												 and jobs.emp_master_id = '".$_SESSION['client_id']."' and jobs.sent_by_type<>'Employee Master' and (jobs.location_id = locations.id) group by jobs.id 
												 
												 UNION 

												 select jobs.subject, jobs.message, jobs.sent_by_type , jobs.sent_datetime, jobs.id as jobId, jobs.emp_master_id,
												job.id, job.job, job.description, job.status, job.posted_date, job.start_date, job.end_date,
												 job.time_from, job.time_to, job.days_from, job.days_to, job.contact, job.type, job.requirements, 
												 COALESCE(jobs.location_id,corporate.id) as location_id, COALESCE(corporate.name,locations.name) as name, locations.city ,
												 corporate.id as cid,corporate.name as cname ,corporate.city as ccity,corporate.image as cimage, corporate.primary_type as cprimary_type
												 , DATE_FORMAT(jobs.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(jobs.sent_datetime,'%H:%i') as time 
												 from employee_master_job_inquiries jobs, location_jobs job, locations,corporate
												 where jobs.location_job_id = job.id  and jobs.read = 'No' 
												 and jobs.emp_master_id = '".$_SESSION['client_id']."' and jobs.sent_by_type<>'Employee Master' and (jobs.corporate_id = corporate.id) group by jobs.id order by jobId desc
												 ";
												 
												 $resultstaffpoint = mysql_query($sqlstaffpoint);
												 if (mysql_num_rows($resultstaffpoint)>0){
			?>
			<h4 class="widgettitle" style= "text-align:center;">StaffPoint</h4>
            <div class="widgetcontent">
			 <ul class="msglist" style="cursor:auto;">
				<?php 
					while ($rowstaff = mysql_fetch_array($resultstaffpoint)) {
												$image = "";
												if($rowstaff['sent_by_type'] != null && $rowstaff['sent_by_type'] == "Employee Master"){
													$sqlMaster = "select * from employees_master where empmaster_id = '".$rowstaff['emp_master_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
												}else if($rowstaff['sent_by_type'] != null && $rowstaff['sent_by_type'] == "Location"){
													$sqlMaster = "select name,image,primary_type from Locations where id = '".$rowstaff['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
													
													$sqlstaffLoc = "select count(message) as totalmessages from employee_master_job_inquiries where `read` = 'No' 
												 and emp_master_id = '".$_SESSION['client_id']."' and sent_by_type<>'Employee Master' and location_id = '".$rowstaff['location_id']."'";
													$resultstaffLoc = mysql_query($sqlstaffLoc);
													$rowstaffLoc = mysql_fetch_array($resultstaffLoc);
													
												}else if($rowstaff['sent_by_type'] != null && $rowstaff['sent_by_type'] == "Corporate"){
													$image = APIPHP."images/".$rowstaff['cimage'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowstaff['cprimary_type']); 
													}
												}
				
				?>
				<li>
				    <div class="thumb">
					<a href="staffpoint.php">
					<img onerror="this.src='images/default - location.png'" src="<?php echo $image; ?>" alt="" style="width:50px; height:40px;" onMouseOver="tooltip_res(<?php echo $rowstaff["jobId"]; ?>)" onmouseleave="tooltip_res1(<?php echo $rowstaff["jobId"]; ?>)" />
					</a>
					
					</div>
					<table class="pswd_info <?php echo $rowstaff["jobId"]; ?>" id="pswd_res<?php echo $rowstaff["jobId"]; ?>" style="display:none;">
						<tr>
							<td style="width:20%;"><?php echo $rowstaff['name']; ?></td>
						</tr>
						<tr>
							<td>Messages: <?php echo "(".$rowstaffLoc["totalmessages"].")"; ?></td>
						 </tr>
					</table>
                  </li>
                  <?php } ?>
                  <?php 
			/*$sqlstore = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id and a.read = 'No' and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location' order by sId desc";*/
			$sqlstore = "select a.subject, a.message, a.sent_by_type ,a.`read`, a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,
(select count(message)as totalmessages from employee_master_location_storepoint where emp_master_id = '".$_SESSION['client_id']."' and `read`='No' and sent_by_type='Location' ) as totalmessages , locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time 
												 from employee_master_location_storepoint a,  locations
												 where a.location_id = locations.id  and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location' and a.read = 'No' group by a.location_id order by sId desc";
												
												 $resultstore = mysql_query($sqlstore);
												// $resstoretemp = mysql_fetch_array($resultstore);
												 
												 $empmaster_id=$_SESSION['client_id'];
												$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
												$vendor_id = $vender['StorePoint_vendor_Id'];
												$sqlpurchase = "select * from purchases where vendor_id = '".$vendor_id."' and status = 'Ordered' group by vendor_id";
												$respurchase = mysql_query($sqlpurchase);
												
												/*$sqlstoreLoc = "select count(message) as totalmessages from employee_master_location_storepoint where `read` = 'No' and emp_master_id = '".$_SESSION['client_id']."' and sent_by_type='Location' and location_id = '".$rowstore['location_id']."'";
													$resultstoreLoc = mysql_query($sqlstoreLoc);
													$rowstoreLoc = mysql_fetch_array($resultstoreLoc);	*/
												
												 if (mysql_num_rows($resultstore)>0 || mysql_num_rows($respurchase)>0){
			
			?>
			<h4 class="widgettitle" style= "text-align:center;">StorePoint</h4>
            <div class="widgetcontent">
				 <ul class="msglist" style="cursor:auto;">
				 	<?php 
					if (mysql_num_rows($resultstore)>0){
					while ($rowstore = mysql_fetch_array($resultstore)) {
												$image = "";
												$dollarimage = "";
												 if($rowstore['sent_by_type'] != null && $rowstore['sent_by_type'] == "Location"){
													$sqlMaster = "select l.image,l.primary_type,l.city,s.name state,l.phone from Locations l
														LEFT JOIN states as s ON s.id = l.state  where l.id = '".$rowstore['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);													
													$image = APIPHP."images/".$rowMaster['image'];
													$city = $rowMaster['city'];
													$state = $rowMaster['state'];
													$phone = $rowMaster['phone'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
												
													 if (mysql_num_rows($respurchase)>0){
													 	$dollarimage = "dollar";
													 }
													
												}
					
					?>
					<li>
					<div class="thumb" <?php if($dollarimage){ ?>style="position: relative;display: inline-block;"<?php } ?>>
					<a id="restri_res<?php echo $rowstore["sId"]; ?>" href="storepoint.php">
					<img src="<?php echo $image; ?>" alt="" style="width:50px; height:40px;" onMouseOver="tooltip_res(<?php echo $rowstore["sId"]; ?>)" onmouseleave="tooltip_res1(<?php echo $rowstore["sId"]; ?>)" />
					</a>
					<?php if($dollarimage){ ?>
					<span class="dollar" style="position: absolute; top: 0;right: 0;cursor: pointer;"><img src="images/dollar_16_16.png"></span>				<?php } ?>
					</div>
					<table class="pswd_info <?php echo $rowstore["sId"]; ?>" id="pswd_res<?php echo $rowstore["sId"]; ?>" style="display:none;">
						<tr>
							<td style="width:7%;"><strong><?php echo $rowstore['name']; ?></strong></td>
						</tr>
                        <tr>
							<td><?php echo $city.', '.$state; ?></td>
						 </tr>
                         <tr>
                         	<td><?php echo $phone; ?></td>
                         </tr>
						<tr>
							<td>Messages: <?php echo "(".$rowstore["totalmessages"].")"; ?></td>
						 </tr>
					</table>
					 </li>
					  <?php } ?>
					<?php } else { 
					while ($rowstore = mysql_fetch_array($respurchase)) {
												$image = "";
												
													$sqlMaster = "select l.image,l.primary_type,l.city,s.name state,l.phone from Locations l
														LEFT JOIN states as s ON s.id = l.state  where l.id = '".$rowstore['location_id']."'";
													$resultMaster = mysql_query($sqlMaster);
													$rowMaster = mysql_fetch_array($resultMaster);
													$image = APIPHP."images/".$rowMaster['image'];
													$city = $rowMaster['city'];
													$state = $rowMaster['state'];
													$phone = $rowMaster['phone'];
													if(!isImage($image))
													{
														$image = APIPHP."images/".getPrimaryTypeImage($rowMaster['primary_type']); 
													}
					
												$sqlstoreLoc = "select count(message) as totalmessages from employee_master_location_storepoint where `read` = 'No' and emp_master_id = '".$_SESSION['client_id']."' and sent_by_type='Location' and location_id = '".$rowstore['location_id']."'";
													$resultstoreLoc = mysql_query($sqlstoreLoc);
													$rowstoreLoc = mysql_fetch_array($resultstoreLoc);
													
													$sqlpurchasecount = "select COUNT(*) as totalpur from purchases where vendor_id = '".$vendor_id."' and status = 'Ordered'";
													$respurchasecount = mysql_query($sqlpurchasecount);	
													$rowpurchasecount = mysql_fetch_array($respurchasecount);
					?>
					<li>
					<div class="thumb" style="position: relative;display: inline-block;">
					<a href="storepoint.php">
					<img src="<?php echo $image; ?>" alt="" style="width:50px; height:40px;" onMouseOver="tooltip_res('<?php echo $rowstore["id"]; ?>')" onmouseleave="tooltip_res1('<?php echo $rowstore["id"]; ?>')" />
					</a>
					<span class="dollar" style="position: absolute; top: 0;right: 0;cursor: pointer;"><img src="images/dollar_16_16.png"></span>
					</div>
					<table class="pswd_info <?php echo $rowstore["id"]; ?>" id="pswd_res<?php echo $rowstore["id"]; ?>" style="display:none;">
						<tr>
							<td style="width:20%;"><strong><?php echo $rowMaster['name']; ?></strong></td>
						</tr>
                        <tr>
							<td><?php echo $city.', '.$state; ?></td>
						 </tr>
                         <tr>
                         	<td><?php echo $phone; ?></td>
                         </tr>
						<tr>
							<td>Messages: <?php echo "(".$rowstoreLoc["totalmessages"].")"; ?></td>
						 </tr>
						<tr>
							<td>Purchase: <?php echo "(".$rowpurchasecount["totalpur"].")"; ?></td>
						 </tr>
					</table>
					 </li>
					  <?php } ?>
					<?php } ?>
                    <?php } ?>
                </ul>
			</div>
			<?php } ?>
			
            <?php 
				if($is_employee){
			?>
                <h4 class="widgettitle" style="text-align:center;">CALENDAR</h4>
                <div class="widgetcontent">
                  <div id='calendar' style="padding:0px 15px"></div>
                  <br>
                  <div style="padding:0px 15px;"><a class="colorbutton green" style=""><small>Available</small></a> <a class="colorbutton red "><small>Unavailable</small></a> <a class="colorbutton orange"><small>Sick, Vacation</small></a> <a class="colorbutton yellow"><small  style="color:#000 !important; ">Request off</small></a> <a class="colorbutton blue" style="color:#FFF;"><small>Assigned</small></a> </div>
                </div>
			<?php
				}
			?>
            
          </div>
          <!--span6-->
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
<div id="announcementsformmodal" class="modal hide fade">
  <form id="announcementsform" name="announcementsform" method="post" class="form-horizontal label-left">
    <div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
      <h3>Referral Announcements</h3>
    </div>
    <div class="modal-body" style="max-height:500px !important;">
      <div class="alert alert-block">
        <div id="referral_announcements_subject" style="font-size:16px; margin-bottom:5px; font-weight:normal;"></div>
        <span id="referral_announcements_message"></span> </div>
      <h4>Please Enter the Business you would like to Refer to us:</h4>
      <hr>
      <div class="control-group">
        <label class="control-label">Business Name:<span style="color:red;vertical-align:text-bottom;">*</span></label>
        <div class="controls">
          <input type="text" name="business[name]" value="" id="businessname" placeholder="Name" title="Business Name" style="width:310px; height:30px;">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Contact:<span style="color:red;vertical-align:text-bottom;">*</span></label>
        <div class="controls">
          <input type="text" name="business[contact]" value="" id="businesscontact" placeholder="Contact" title="Business Contact" style="width:310px;height:30px;">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Phone:<span style="color:red;vertical-align:text-bottom;">*</span></label>
        <div class="controls">
          <input type="text" name="business[phone]" value="" onKeyDown="check_number(event,this.value)" id="businessphone" placeholder="Phone" title="Business Phone" style="width:310px;height:30px;">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Email:<span style="color:red; vertical-align:text-bottom;">*</span></label>
        <div class="controls">
          <input type="text" name="business[email]" value="" id="businessemail" placeholder="Email" title="Business Email" style="width:310px;height:30px;">
        </div>
      </div>
    </div>
    <div class="modal-footer" style="text-align: center;">
      <p class="stdformbutton">
        <button id="btnCancel" data-dismiss="modal" class="btn btn-default">Cancel</button>
        <button type="submit" id="btnrefferel" name="btnrefferel"  class="btn btn-primary">Submit</button>
      </p>
    </div>
  </form>
</div>
<div id="teamannouncementsformmodal" class="modal hide fade">
  <form id="teamannouncementsform" name="teamannouncementsform" method="post" class="form-horizontal label-left">
    <div class="modal-header" >
      <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
      <h3>Referral Announcements</h3>
    </div>
    <div class="modal-body" style="max-height:500px !important;">
      <div class="alert alert-block">
        <div id="teamreferral_announcements_subject" style="font-size:16px; margin-bottom:5px; font-weight:normal;"></div>
        <span id="teamreferral_announcements_message"></span> </div>
      <h4>Please Enter the Team Member you would like to Refer to us:</h4>
      <hr>
      <div class="control-group">
        <label class="control-label">Name : <span style="color:red;">*</span></label>
        <div class="controls">
          <input type="text" name="team[name]" value="" id="teamname" placeholder="Name" title="Name" style="width:310px; height:30px;">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Email : <span style="color:red;">*</span></label>
        <div class="controls">
          <input type="text" name="team[email]" value="" id="teamemail" placeholder="Email" title="Email" style="width:310px;height:30px;">
        </div>
      </div>
    </div>
    <div class="modal-footer" style="text-align: center;">
      <p class="stdformbutton">
        <button id="btnCancel" data-dismiss="modal" class="btn btn-default">Cancel</button>
        <button type="submit" id="teambtnrefferel" name="teambtnrefferel"  class="btn btn-primary">Submit</button>
      </p>
    </div>
  </form>
</div>


<!--mainwrapper-->
</body>
</html>
<script>
	
jQuery(document).ready(function(e){
			
        
        
	jQuery(document,"#announcementsform").keypress(function(e){
		if(e.keyCode==13 && jQuery("#announcementsformmodal").hasClass('in')){
			jQuery('#btnrefferel').trigger('click');
			e.preventDefault();
		}
	});	
	jQuery(document,"#teamannouncementsform").keypress(function(e){
		if(e.keyCode==13 && jQuery("#teamannouncementsformmodal").hasClass('in')){
			jQuery('#teambtnrefferel').trigger('click');
			e.preventDefault();
		}
	});	
		
	
	jQuery('#btnrefferel').click(function(e){
		e.preventDefault();
               
		if (jQuery("#businessname").val()==""){
                        jAlert("Please enter business name!")
			return false;		
                }  
		if (jQuery("#businesscontact").val()==""){
                        jAlert("Please enter contact!")
			return false;		
                }
		if (jQuery("#businessphone").val()==""){
                        jAlert("Please enter phone!")
			return false;		
                }
		var phone_filter = '[0-9]{10}$';
		if(!jQuery('#businessphone').val().match(phone_filter)){
			jAlert("Please Enter Valid Phone Number & minimum length of 10 characters!", 'Alert Dialog');
			return false;
		}
		if(jQuery('#businessphone').val()!="" && jQuery('#businessphone').val().length<10)
		{
			jAlert("Phone Number has a minimum length of 10 characters!", 'Alert Dialog');
			return false;
		}
		/*phonenumber = jQuery("#businessphone").val().replace("+","");			
		phonenumber = phonenumber.replace("-","");			
		phonenumber = phonenumber.replace(")","");			
		phonenumber = phonenumber.replace("(","");			
		if (!jQuery.isNumeric(phonenumber)){
                        jAlert("Please enter valid phone!")
			return false;		
		}
		
		if (phonenumber.length<=5){
                        jAlert("Please enter at least 6 digit phone number!")
			return false;	
			
		}*/	
		if (jQuery("#businessemail").val()==""){
                        jAlert("Please enter email!")
			return false;		
		}
		if (!IsEmail(jQuery("#businessemail").val())){
                        jAlert("Please enter valid email!")
			return false;		
		} 
		
		jQuery(this).attr("disabled",true);
		jQuery('#announcementsform').submit();   
	});
	
	jQuery('#teambtnrefferel').click(function(e){
		e.preventDefault();
               
		if (jQuery("#teamname").val()==""){
             jAlert("Please enter name!");
			 return false;		
        }  
		if (jQuery("#teamemail").val()==""){
            jAlert("Please enter email!");
			return false;		
		}
		if (!IsEmail(jQuery("#teamemail").val())){
            jAlert("Please enter valid email!");
			return false;		
		} 
		jQuery(this).attr("disabled",true);
		jQuery('#teamannouncementsform').submit();   
	});
});
function check_number(e,val){
			// Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110 ]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
}
function showmessageinfo(id){
	subject	=	jQuery("#referral_subject_"+id).val();
	message	=	jQuery("#referral_message_"+id).val();
	jQuery("#referral_announcements_subject").html(subject);
	jQuery("#referral_announcements_message").html(message);
	
}
function teamshowmessageinfo(id){
	subject	=	jQuery("#referral_subject_"+id).val();
	message	=	jQuery("#referral_message_"+id).val();
	jQuery("#teamreferral_announcements_subject").html(subject);
	jQuery("#teamreferral_announcements_message").html(message);
	
}
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
/*if (jQuery('#LocationsLinkedWithEmployee').contents().length == 0){*/

<?php
//juni
if($_REQUEST["day"] == 1) { ?>
	jQuery(document).ready(function(){ // wait for 2 seconds as the elements re-position themselves
		setTimeout(
			function() 	{
				jAlert("Your password is about to expire. Please change your password to avoid account lockouts!","Alert Dialog");
			}, 2000);	
	});
<?php } ?>
</script>
<?php
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
function charlimit($string, $limit) {

        return substr($string, 0, $limit) . (strlen($string) > $limit ? "..." : '');
    }



    $sqlft = "SELECT count(ping_Id) as countrecords FROM employee_master_ping WHERE (Ping_type='Signin' OR Ping_type='Signout') AND Empmaster_id='".$_SESSION["client_id"] ."'";
    $resultft = mysql_query($sqlft);
	
    $rowft = mysql_fetch_assoc($resultft);
    if ($rowft["countrecords"]==1){
        $_SESSION["First_timeuser"]="Y";
    }
function array_trim(&$value) 
                        { 
                            $value = trim($value); 
                        }
function dates_range($date1, $date2)
	{
		if ($date1<$date2)
		{
			$dates_range[]=$date1;
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1!=$date2)
			{
				$date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
				$dates_range[]=date('Y-m-d', $date1);
			}
		}
		else if($date1==$date2)
		{
			$dates_range[]=$date1;
		}
		return $dates_range;
	}
	
	function time_range($date1, $date2)
	{
		if ($date1<$date2)
		{
			$dates_range[]=$date1;
			$date1=strtotime(date("Y-m-d").$date1);
			$date2=strtotime(date("Y-m-d").$date2);
			while ($date1<$date2)
			{
				$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
				$dates_range[]=date('H:i:00', $date1);
			}
		}
		else if($date1==$date2)
		{
			$dates_range[]=$date1;
		}
		return $dates_range;
	}
	function time_range_final($date1, $date2)
	{
		
		if ($date1<$date2)
		{
			
			$date_first=strtotime($date1);
			$date_first=mktime(date("H", $date_first), date("i", $date_first),date("s", $date_first), date("m", $date_first), date("d", $date_first), date("Y", $date_first));
			$check_day=date('l', $date_first);
			$dates_range[]=date('Y-m-d_l_H:i:00', $date_first);
			
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1<$date2)
			{
				$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
				$check_day=date('l', $date1);
				$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
			}
		}
		else if($date1==$date2)
		{
			$check_day=date('l', $date1);
			$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
		}
		return $dates_range;
	
	}
function round_sec2hm($sec) {	
    $hours = floor($sec / 3600);
    $mins = floor(($sec - ($hours*3600)) / 60);
    if($hours < 1){$hours = 00;}

    if ($mins < 8) {
        $duration = $hours . '.00';
    } elseif ($mins < 23) {
        $duration = $hours . '.15';
    } elseif ($mins < 38) {
        $duration = $hours . '.30';
    } elseif ($mins < 53) {
        $duration = $hours . '.45';
    } else {
        $hours++;
        $duration = $hours . '.00';
    }

    return $duration;
}
function getEmployees()
	{
		$email = $_SESSION['email'];
		$sql = 'SELECT * FROM employees WHERE email="' . $email . '"';
		$res = mysql_query($sql);
		
		$employee_ids = array();
		while($row = mysql_fetch_array($res))
		{
			$employee_ids[] = $row['id'];
		}
		return $employee_ids;
	}
	function getScheduleIDs($employee_ids) 
	{
		$date = new DateTime('NOW');
		$today = $date->format('Y-m-d');
		
		$ids = join(',', $employee_ids);
		if($ids == ''){
			$ids = '1';
		}
		
	    $sql = "SELECT * FROM employees_schedules WHERE employee_id IN ($ids) AND date !='0000-00-00'";// AND date='" . $today . "'";
		//echo $sql;
		$res = mysql_query($sql);
		$rows = array();
		while($row = mysql_fetch_array($res)){
		$rows[] = $row['schedule_id'];
		}
		
		return $rows;
	}
function getSchedules($schedule_ids,$employee_ids) 
	{
		
            $schedule_ids = array_unique($schedule_ids, SORT_REGULAR);
            $ids = join(",", $schedule_ids);
             
            $employee_ids = join(',', $employee_ids);
          
	    $sql = "SELECT ls.*,es.date as e_date,es.id as empschedid,l.name as location_name FROM location_schedules as ls LEFT JOIN locations AS l ON l.id = ls.location_id LEFT JOIN employees_schedules as es ON es.schedule_id = ls.id";
		$sql .= " WHERE ls.id IN($ids) AND es.employee_id in ($employee_ids)";
             //  echo $sql; 
		$res = mysql_query($sql);
		$rows = array();
		if($res){
			while($row = mysql_fetch_array($res)){
				$rows[] = $row;
				
			}
		}
               // var_dump($rows);
		return $rows;
	}
if(isset($_POST['business']) && !empty($_POST['business'])){
	$refferral_msgSent = false; 
	$newsql = "INSERT INTO announcements_referrals SET announcements_referrals_id=null, status='New', product='TeamPanel', from_employee_master='".mysql_real_escape_string($_SESSION["client_id"])."', from_location_id=null, from_location_emp_id=null, from_client_id=null, Refer_Location='".mysql_real_escape_string($_POST['business']['name'])."', Refer_Location_contact='".mysql_real_escape_string($_POST['business']['contact'])."', Refer_phone='".mysql_real_escape_string($_POST['business']['phone'])."', Refer_email='".mysql_real_escape_string($_POST['business']['email'])."', SoftPoint_notes=null, SoftPoint_by=null, SoftPoint_datetime=null, Created_on='TeamPanel', Created_by='".mysql_real_escape_string($_SESSION["client_id"])."', Created_datetime=NOW()";
		$result2 = mysql_query($newsql) or die(mysql_error());
		if ($result2){
			$refferral_msgSent = true;
			$_SESSION['refferral_msgSent']	=	true;
			@header("location:dashboard.php");
			exit;
		}     
	   
}
	

if(isset($_POST['team']) && !empty($_POST['team'])){
	$refferral_msgSent = false; 
	$newsql = "INSERT INTO announcements_referrals SET announcements_referrals_id=null, status='New', product='TeamPanel', from_employee_master='".mysql_real_escape_string($_SESSION["client_id"])."', from_location_id=null, from_location_emp_id=null, from_client_id=null, Refer_Location=null, Refer_Location_contact=null, Refer_phone=null, Refer_email=null, refer_team_name='".mysql_real_escape_string($_POST['team']['name'])."', refer_team_email='".mysql_real_escape_string($_POST['team']['email'])."', SoftPoint_notes=null, SoftPoint_by=null, SoftPoint_datetime=null, Created_on='TeamPanel', Created_by='".mysql_real_escape_string($_SESSION["client_id"])."', Created_datetime=NOW()";
		$result2 = mysql_query($newsql) or die(mysql_error());
		if ($result2){
			$refferral_msgSent = true;
			$_SESSION['refferral_msgSent']	=	true;
			@header("location:dashboard.php");
			exit;
		}     
	   
}

if ($_GET['sdate'] != '' && $_GET['edate'] != '') {
    $start = date('Y-m-d', strtotime($_GET['sdate']));
    $end = date('Y-m-d', strtotime($_GET['edate']));
} elseif ($_GET['sdate'] != '' && $_GET['edate'] == '') {
    $start = date('Y-m-d', strtotime($_GET['sdate']));
    $endd = date("Y-m-d");
} elseif ($_GET['edate'] != '' && $_GET['sdate'] == '') {
    $start = date("Y-m-d");
    $end = date('Y-m-d', strtotime($_GET['edate']));
} else {
    $start = date("Y-m-1");
    $end = date("Y-m-d");
}



/**********************************custopn google chart code start hear ***************************/
	if ($_GET['sdate'] != '' && $_GET['edate'] != '') {
    $start = date('Y-m-d', strtotime($_GET['sdate']));
    $end = date('Y-m-d', strtotime($_GET['edate']));
	} elseif ($_GET['sdate'] != '' && $_GET['edate'] == '') {
    $start = date('Y-m-d', strtotime($_GET['sdate']));
    $endd = date("Y-m-d");
	} elseif ($_GET['edate'] != '' && $_GET['sdate'] == '') {
    $start = date("Y-m-d");
    $end = date('Y-m-d', strtotime($_GET['edate']));
	} else {
    $start = date("Y-m-1");
	 $end1 = date("Y-m-d");
	$ts = strtotime($end1);
	$end = date('Y-m-t', $ts);
	}
	$date = $start;
	$end_date = $end;

	
	$query = "SELECT distinct(emp.id), emp.first_name, emp.last_name, emp.emp_id, emp.department
          FROM employees emp INNER JOIN employees_master emas ON emas.email = emp.email
          WHERE emas.email = '{$_SESSION['email']}'";
		  $result = mysql_query($query) or die(mysql_error());

	      while($row = mysql_fetch_array($result)){
				if(!isset($employee_info[$row['id']])){//add employee info to array
				$employee_info[$row['id']] = array(
				'first_name' => $row['first_name'],
				'last_name' => $row['last_name'],
				'department' => $row['department'],
				'period_total' => 0
				);
				$tofullname = $row['first_name']. " " . $row['last_name'] . " (" . $row['emp_id'] .")"  ;
				$empID =  $row['id']; 
    		}
	}
	
	$sql2="select ee.location_id as id  from employees_entry as ee JOIN employees as em ON em.id = ee.employee_id WHERE em.email = '".$_SESSION['email']."' GROUP BY ee.`location_id` ";
	$r=mysql_query($sql2);
	$clocation="";
	
	
	while($loc_id=mysql_fetch_assoc($r))
	{
	$location=mysql_query("select name from locations where id='$loc_id[id]'");	
	$location_name=mysql_fetch_assoc($location);
	$clocation.="'".$location_name['name']."',";
	}
	
	
 /*while (strtotime($date) <= strtotime($end_date)) {
		
	$sql2="select ee.location_id as id  from employees_entry as ee JOIN employees as em ON em.id = ee.employee_id WHERE em.email = '".$_SESSION['email']."' GROUP BY ee.`location_id` ";
	$r=mysql_query($sql2);
	$strduration="";
	
	//echo $sql2;
	//echo 'ok';exit(0);
	while($loc_id=mysql_fetch_assoc($r))
	{
		
	$location=mysql_query("select name from locations where id='$loc_id[id]'");	
	$location_name=mysql_fetch_assoc($location);
	$locname[].=$location_name['name'];
	$query2 = "SELECT id,  IF(manual = 'add' OR manual = 'edit', CAST(concat(modified_date ,' ', modified_time) AS datetime), CAST(concat(date,' ',time) AS datetime)) as attendancedate,employee_id,date,time,image,punch_type,manual,date_format( modified_date,'%m/%d/%Y') modified_date,modified_time
               FROM employees_entry 
               WHERE manual <> 'delete' AND employee_id in (select id from employees where email = '{$_SESSION['email']}')  and IF(manual = 'add' OR manual = 'edit',modified_date,date) = STR_TO_DATE('" . $date . "','%Y-%m-%d') 
             and location_id='$loc_id[id]' ORDER BY modified_date DESC, modified_time DESC, attendancedate desc";
			 $result2 = mysql_query($query2) or die(mysql_error());
    $out_id = 0;
    $out_datetime = 0;
    $out_date = 0;
    $out_time = 0;
    $out_image = '';
    $i = 0;
    $mins = 0;
    $hours = 0;
    $minSec = 0;
    $duration = 0;
    $out = false; //used to signify multiple out punches, gets set to true if there is an out punch
    $in = false; //used to signify multiple in punches, gets set to true if there is an in punch
    $punch_sets = array();
	
	
		
	
    while ($row_attendance = mysql_fetch_array($result2)) {
        if($row_attendance['punch_type'] == 'out'){
            if(!$out){
                $out = true;//row was a punch out
                $out_id = $row_attendance['id'];
                $out_date = substr($row_attendance['attendancedate'],0,10);
                $out_time = substr($row_attendance['attendancedate'],11,5);
                $out_datetime = $row_attendance['attendancedate'];
                $out_image = $row_attendance['image'];
            }else{//signifies (out == true) and multiple consecutive punch outs
                //first add previous iteration variables to set array
                $arr = array(
                    'in_id' => '',
                    'in_date' => '',
                    'in_time' => '',
                    'in_image' => '',
					'location' => $location_name['name'],
                    'out_id' => $out_id,
                    'out_date' => $out_date,
                    'out_time' => $out_time,
                    'out_image' => $out_image,
                    'duration' => ''
                );
                $punch_sets[$i] = $arr;
                $i++;
                //now redefine varibles for current iteration
                $out_id = $row_attendance['id'];
                $out_date = substr($row_attendance['attendancedate'],0,10);
                $out_time = substr($row_attendance['attendancedate'],11,5);
                $out_datetime = $row_attendance['attendancedate'];
                $out_image = $row_attendance['image'];
            }
        }elseif($row_attendance['punch_type'] == 'in'){
            $in_id = $row_attendance['id'];
            $in_date = substr($row_attendance['attendancedate'],0,10);
            $in_time = substr($row_attendance['attendancedate'],11,5);
            $in_image = $row_attendance['image'];
			
            $duration = round_sec2hm(strtotime($out_datetime) - strtotime($row_attendance['attendancedate']));
            //add to array and set out to false
            $out = false;//set to false to new row
            if($duration <= 72000){
                $arr = array(
                    'in_id' => $in_id,
                    'in_date' => $in_date,
                    'in_time' => $in_time,
                    'in_image' => $in_image,
					'location' => $location_name['name'],
                    'out_id' => $out_id,
                    'out_date' => $out_date,
                    'out_time' => $out_time,
                    'out_image' => $out_image,
                    'duration' => $duration
                );
                $punch_sets[$i] = $arr;
                $i++;
                $in = true;
                $out_id = '';//reinitalize out variables to nothing
                $out_date = '';
                $out_time = '';
                $out_datetime = '';
                $out_image = '';
            }else{
                $arr = array(
                    'in_id' => '',
                    'in_date' => '',
                    'in_time' => '',
                    'in_image' => '',
					'location' => $location_name['name'],
                    'out_id' => $out_id,
                    'out_date' => $out_date,
                    'out_time' => $out_time,
                    'out_image' => $out_image,
                    'duration' => ''
                );
                $punch_sets[$i] = $arr;
                $i++;
                $arr = array(
                    'in_id' => $in_id,
                    'in_date' => $in_date,
                    'in_time' => $in_time,
                    'in_image' => $in_image,
					'location' => $location_name['name'],
                    'out_id' => '',
                    'out_date' => '',
                    'out_time' => '',
                    'out_image' => '',
                    'duration' => ''
                );
                $punch_sets[$i] = $arr;
                $i++;
                $in = true;
                $out_id = '';//reinitalize out variables to nothing
                $out_date = '';
                $out_time = '';
                $out_datetime = '';
                $out_image = '';
            }
        }
		
    }
	
	$strduration.=$duration.",";
	 if($out){//last record is a unpaired punch out
        $arr = array(
            'in_id' => '',
            'in_date' => '',
            'in_time' => '',
            'in_image' => '',
			'location' => $location_name['name'],
            'out_id' => $out_id,
            'out_date' => $out_date,
            'out_time' => $out_time,
            'out_image' => $out_image,
			
            'duration' => ''
        );
        $punch_sets[$i] = $arr;
    }
    $i=0;
	
    /*foreach($punch_sets as $punch){
        if($punch['duration'] > 0){
			 $punch['in_date'];
            $punches[$punch['in_date']][$row['id']][] = $punch;
        }
    }	*/	 
			 
	
	/*}
	$ordate = date("M d", strtotime($date));
	$duar = round_sec2hm($strduration);
	$chartlable = "['".$ordate."',".substr($clocation,0,-1)."]";
	$chartdata.= "['".$ordate."',".substr($strduration,0,-1)."],";
	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	 
}*/

/*********************************end google chart ********************************/
?>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.min.js"></script>
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
<script src="//www.flotcharts.org/flot/jquery.flot.js" type="text/javascript"></script>
<script src="//www.flotcharts.org/flot/jquery.flot.time.js" type="text/javascript"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript">
var client_id = <?php echo $_SESSION['client_id'];?>;
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function errorHandler(errorMessage) {
				//curisosity, check out the error in the console
				console.log(errorMessage);
			
				//simply remove the error, the user never see it
				google.visualization.errors.removeError(errorMessage.id);
			}
		function drawChart() {
			var mnc = jQuery('#sdate').val();
			var MincTempDate = new Date(mnc);
			var mxc = jQuery('#edate').val();
			var Maxctempdate = new Date(mxc);
			var MaxcOrdate = ((Maxctempdate.getDate())<=9)? '0'+(Maxctempdate.getDate()): Maxctempdate.getDate();
			var MaxcOrmonth = ((Maxctempdate.getMonth()+1)<=9)? '0'+(Maxctempdate.getMonth()+1): Maxctempdate.getMonth()+1;
			var MaxcOryear = Maxctempdate.getFullYear();
			var MincOrdate = ((MincTempDate.getDate())<=9)? '0'+(MincTempDate.getDate()): MincTempDate.getDate();
			var MincOrmonth = ((MincTempDate.getMonth()+1)<=9)? '0'+(MincTempDate.getMonth()+1): MincTempDate.getMonth()+1;
			var MincOryear = MincTempDate.getFullYear();
			var mincDateT = MincOryear+'-'+MincOrmonth+'-'+(MincOrdate);
			var maxcDateT = MaxcOryear+'-'+MaxcOrmonth+'-'+MaxcOrdate;
			var data = google.visualization.arrayToDataTable([
				<?php echo $chartlable; ?>,
				<?php echo $chartdata;?>
			]);
			var options = {
				/*  title:'Business Performance',
				hAxis:{title:'Date', titleTextStyle:{color:'red'}},
				isStacked: true
				title: 'Business Performance',  
				backgroundColor: '#FFCBA0',*/
				series: {0:{color: '#FF0000', visibleInLegend: true},1:{color: '#ff9900', visibleInLegend: true}, 2:{color: '#109618', visibleInLegend: true}, 3:{color: '#990099', visibleInLegend: true},4:{color: '#3366cc', visibleInLegend:true}},
				titleTextStyle: { fontSize: 20,color: '#ffffff' },
				isStacked: true,
				focusTarget:'category',
				bar: { groupWidth: '60%' },
				chartArea:{left:50,top:40,width:"93%",height:"82%",backgroundColor: '#fff'},
				hAxis: { viewWindowMode: 'Explicit' ,textStyle:{ color: '#666666',
					fontName: 'Trebuchet MS,Arial,Helvetica,sans-serif',
					fontSize: 10
				}},
				vAxis: { textStyle:{ color: '#666666',
					fontName: 'Trebuchet MS,Arial,Helvetica,sans-serif',
					fontSize: 10
				}},
				legend:{position: 'none', textStyle: {color: 'black', fontSize: 14}},
				tooltip: {textStyle: {color: '#000000'}, showColorCode: true}
			};
			//var chart = new google.visualization.ColumnChart(document.getElementById('chartplace'));
			//google.visualization.events.addListener(chart, 'error', errorHandler);
			//chart.draw(data, options);
			
		}
	</script>
<?php //echo $_SESSION['client_id']; die();?>
<script type="text/javascript">
//var client_id = <?php echo $client_id;?>;





function searchValidate() {

        var startDate = new Date(document.getElementById("sdate").value);
        var endDate = new Date(document.getElementById("edate").value);
 
        if (startDate > endDate) {

            alert('Start date is after end date. Please revise search.');
            return false;

        }
        else {
            
            document.forms.fdate.submit();
            return true;
        }
}
function tooltip_res(id){
		jQuery("#pswd_res"+id).show();
		var offset = jQuery('#restri_res'+id).offset();
		var width = jQuery('#restri_res'+id).width();
		var new_left = offset.left+width;
		//var new_left = new_left +30;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top - 30;
		jQuery('#pswd_res'+id).offset({ top:new_hieght, left: new_left});
		
}
function tooltip_res1(id){
		jQuery("#pswd_res"+id).hide();
		var offset = jQuery('#restri_res'+id).offset();
		var width = jQuery('#restri_res'+id).width();
		var new_left = offset.left+width;
		var new_left = new_left +30;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#pswd_res'+id).offset({ top:new_hieght, left: new_left});
}
function rtooltip_res(id){
		jQuery("#ref_res"+id).show();
		var offset = jQuery('#rimage'+id).offset();
		var width = jQuery('#rimage'+id).width();
		var new_left = offset.left+width;
		var new_left = new_left +20;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#ref_res'+id).offset({ top:new_hieght, left: new_left});
		
}
function rtooltip_res1(id){
		jQuery("#ref_res"+id).hide();
		var offset = jQuery('#rimage'+id).offset();
		var width = jQuery('#rimage'+id).width();
		var new_left = offset.left+width;
		var new_left = new_left +30;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#ref_res'+id).offset({ top:new_hieght, left: new_left});
}
function ltooltip_res(id){
		jQuery("#loc_res"+id).show();
		var offset = jQuery('#limage'+id).offset();
		var width = jQuery('#limage'+id).width();
		var new_left = offset.left+width;
		var new_left = new_left +20;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#loc_res'+id).offset({ top:new_hieght, left: new_left});
		
}
function ltooltip_res1(id){
		jQuery("#loc_res"+id).hide();
		var offset = jQuery('#limage'+id).offset();
		var width = jQuery('#limage'+id).width();
		var new_left = offset.left+width;
		var new_left = new_left +30;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#loc_res'+id).offset({ top:new_hieght, left: new_left});
}
</script>
<?php unset($locationName); ?>
<script type='text/javascript'>
		
		jQuery(document).ready(function() {
	
                <?php if (isset($_SESSION["First_timeuser"]) && $_SESSION["First_timeuser"]=="Y"){
                        unset($_SESSION["First_timeuser"]);
						$_SESSION["First_timeuser"] = '';
                    ?>
                        jQuery("#popup_message").css("text-align","left");
                        jAlert("<strong>Welcome to TeamPanel!</strong>\n\n Our Sites are designed to assist in better managing your operations and streamline communications within your location, as well as with your clients or employees.\n\n Please start by completing the Setup beginning with your Profile. \n\n Once you have completed the Setup section, you're ready to start using your system. \n\n If you have any questions or comments please use the HELP option found in the top right corner so that we may better assist you.\n\n Kind Regards,\n\n The SoftPoint Team\n info@softpointcloud.com","Welcome");
                        jQuery("#popup_message").css("text-align","left");
                 <?php  }?>        
        
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var calendar = jQuery('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText: {
				prev: '&laquo;',
				next: '&raquo;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'today',
				month: 'M',
				week: 'W',
				day: 'D'
			},
			selectable: true,
			selectHelper: true,
			/*select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},*/
			editable: true,
			events: [
			<?php
			$empmaster_id=$_SESSION['client_id'];
			$sql1="SELECT start_date,end_date,start_time,end_time,availability_type,Request_location_id as location_id  FROM employees_master_availability where empmaster_id = '".$empmaster_id."'";
			$res1=mysql_query($sql1);
			
			while($data = mysql_fetch_assoc($res1))
			{
			$sdate=$data['start_date'];
			$edate=$data['end_date'];
			$stime=explode(":",$data['start_time']);
			$etime=explode(":",$data['end_time']);
			
		
		$location=mysql_query("select name from locations where id='$data[location_id]'");	
		$location_name=mysql_fetch_assoc($location);
       $dates_range[]=$sdate;
       $date1=strtotime($sdate);
       $date2=strtotime($edate);
	   
       while ($date1<$date2)
       {
           $date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
           $dates_range[]=date('Y-m-d', $date1);
		   
       }
	   $c=0;
	  
	   foreach($dates_range as $r)
	   {
		    $dayname = date('l' , strtotime($r));
			$dayval=$data[strtolower($dayname)];
			$y=explode("-",$r);
			if($dayval=='Y')
			{
			
			
			if($data['availability_type'] == 'Unavailable' & $sdate<=$r & $r<=$edate)	
			{
				
			?>
			
				
				
				{
					title: '<?php echo $data['availability_type'];?>',
					start: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $stime[0];?>, <?php echo $stime[1];?>),
					end: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $etime[0];?>, <?php echo $etime[1];?>),
					color  :'#FF0000',
					textColor: '#333333',
					allDay: false
				},
				
				
			<?	
			}	
			else if(($data['availability_type'] == 'Sick' || $data['availability_type'] == 'Vacation' ) & $sdate<=$r & $r<=$edate)	
			{
			?>
			
				
				
				{
					title: '<?php echo $data['availability_type'];?>',
					start: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $stime[0];?>, <?php echo $stime[1];?>),
					end: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $etime[0];?>, <?php echo $etime[1];?>),
					color  :'#FF6600',
					textColor: '#333333',
					allDay: false
				},
				<?php
			
				?>
				<?
				}
			else if($data['availability_type'] == 'Request Off'  & $sdate<=$r & $r<=$edate)	
			{
			?>
			
				
				
				{
					title: '<?php echo $data['availability_type'];?>',
					start: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $stime[0];?>, <?php echo $stime[1];?>),
					end: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $etime[0];?>, <?php echo $etime[1];?>),
					color  :'#FFFF00',
					textColor: '#333333',
					allDay: false
				},
				
				
				
				<?
				}
				else if($data['availability_type'] == 'Available'  & $sdate<=$r & $r<=$edate)	
			{
			?>
			
				
				
				{
					title: '<?php echo $data['availability_type'];?>',
					start: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $stime[0];?>, <?php echo $stime[1];?>),
					end: new Date(<?php echo $y[0];?>, <?php echo $y[1]-1;?>, <?php echo $y[2];?>, <?php echo $etime[0];?>, <?php echo $etime[1];?>),
					color  :'#00CC00',
					textColor: 'white',
					allDay: false
				},
				<?php
			}
				?>
				
				
				
				
		<?


			}
			$c++;
			}
			
			}
			$employee_ids = getEmployees();
			$schedule_ids = getScheduleIDs($employee_ids);
			$schedule_rows = getSchedules($schedule_ids,$employee_ids);
			
			
				//$jason_ids = implode("','", $schedule_ids); 				
				//var_dump($schedule_rows);
				foreach($schedule_rows as $row)
				{
					if($row['status'] != 'A')
						continue;
					
					$day=dates_range($row['e_date'] ,$row['e_date']);
					foreach($day as $day_key=>$day_value)
					{	
						
						$sdate = date('D M d Y', strtotime($day_value));
						if(strtotime($row['endtime']) < strtotime($row['starttime']) || ($row['starttime'] == '00:00:00' && $row['endtime'] == '00:00:00')) {
							$sedate = date('D M d Y', strtotime($sdate . ' + 1 day'));
							$fsdate = $sdate.' '.$row['starttime'];// .' '.'GMT+0530 (India Standard Time)';
							$fedate = $sedate.' '.$row['endtime'];// .' '.'GMT+0530 (India Standard Time)';
						} 
						else
						{				
							$fsdate = $sdate.' '.$row['starttime'];// .' '.'GMT+0530 (India Standard Time)';
							$fedate = $sdate.' '.$row['endtime'];// .' '.'GMT+0530 (India Standard Time)';
						}
				?>
						{
							title:'<?php echo addslashes($row['location_name']);?>',
							start:'<?php echo $fsdate; ?>',
							end: '<?php echo $fedate; ?>',
							id:'<?php echo $row['id']; ?>',
							allDay: false,
							color  :'#0000CC',
							textColor: '#FFFFFF',
                                                        type:'',
                                                        empsched:'<?php echo $row['empschedid']; ?>'
						},
				
				<?php 
					}
				}
			$sqlrequest = "SELECT employee_request.*, loc.name as location_name FROM employee_request INNER JOIN locations loc ON employee_request.location_id = loc.id where emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}') AND request_off_status!='Declined' AND request_off_status!='Cancelled' AND request_off_status!='Accepted'";
				$res2=mysql_query($sqlrequest);
				$displayDay = true;
				while($row=mysql_fetch_assoc($res2))
				{
					$day = dates_range($row['startdate'] ,$row['enddate']);
					//echo '<pre>';	
					//print_r($day);
					//echo '</pre>';
                     
					if(is_array($day) && count($day)>0){
						foreach($day as $day_key=>$day_value)
						{	
						//echo $displayDay;
						$sdate = date('D M d Y', strtotime($day_value));
						$dayname = date('l' , strtotime($day_value));
												//echo '<br>'.$day_value;
                                                $datename = date("D",strtotime($day_value));
                                                if ($row["request_dow"]!=""){
                                                    $request_dow = explode(",",$row["request_dow"]);
                                                    array_walk($request_dow,'array_trim');
                                                    //var_dump($request_dow)."<br>";
													//echo '<br>';
													//print_r($request_dow);
													//echo '<br> In Array =>'.in_array($datename, $request_dow);
                                                    if (in_array($datename, $request_dow)){
                                                     	$displayDay = true;                                                    
                                                    }else{
														$displayDay = false;                                                    
													}  
                                                
                                                } 
												
                        if ($displayDay){
                                                
						if($row['starttime'] == '00:01:00' && $row['endtime'] == '23:59:00') {
                                                    $all_day ="All Day";
						}else{
                                                    $all_day =" ";
						}
						if($row['starttime'] == '00:00:00' && $row['endtime'] == '00:00:00') {
							$sedate = date('D M d Y', strtotime($sdate . ' + 1 day'));
							$fsdate = $sdate.' '.$row['starttime'];// .' '.'GMT+0530 (India Standard Time)';
							$fedate = $sedate.' '.$row['endtime'];// .' '.'GMT+0530 (India Standard Time)';
						} 
						else
						{				
							$fsdate = $sdate.' '.$row['starttime'];// .' '.'GMT+0530 (India Standard Time)';
							$fedate = $sdate.' '.$row['endtime'];// .' '.'GMT+0530 (India Standard Time)';
						}
                                                
				?>
						
					
					
						{
						title:'<?php echo $all_day." ".$row['request_type']; if ($row["request_off_status"]=="Accepted") echo "- Accepted";?>'+'<?php echo '\n'.addslashes($row['location_name']);?>',
						start:'<?php echo $fsdate; ?>',
						end: '<?php echo $fedate; ?>',
						allDay: false,
                                                <?php if ($row["request_off_status"]=="Accepted"){?>
						color  :'red',
						textColor: 'black'
                                                <?php }else {?>
                                                color  :'#FFFF00',
						textColor: '#333333'
                                                <?php }?>    
						
						},
					<?php
					
                     }
				
				} 
					}
			}

	?>

			]
		});
		
		jQuery(".close").click(function() {
		
			var anndivid = jQuery("#anncdivid").val();
			jQuery(".news-item"+anndivid).remove();
			jQuery('#newsList').newsTicker();
			/*var anll = jQuery('#newsList li').length;
			alert(anll);*/
			if ( jQuery('#newsList li').length == 0 ) {
				jQuery('#newsList').remove();
				jQuery('#newsData').remove();
				jQuery('.alert').html("<div>No Current Announcements</div>");
				jQuery('.close').hide();
			}
		
		
		});
	
	});

jQuery(".getmessage").live("click",function(){
                jQuery.ajax({
                type: "POST",
                url: "ajax-request-display.php",
                data: {msgid:jQuery(this).data("id")},
                success: function( data ){
                        jQuery('#requestbody').html(data);
                        jQuery('#requestmodal').modal('show');
                }
            }); 
        })
</script>
<style>
.error {
	color: #FF0000;
	padding-left:10px;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){

	<?php  if (isset($_SESSION['refferral_msgSent']) && $_SESSION['refferral_msgSent']){ 
	?>
        jAlert("Your referral has been sent!");
         
 <?php   } 
 unset($_SESSION['refferral_msgSent']);
 ?>
});

function ChangeStatusToRead(msgId){
	if(msgId>0){
		jConfirm("Are you sure you want to Cancel this Request?","Confirm Dialog",function(r){
			if(r){
				jQuery.ajax({
					type: "POST",
					url: "dashboard.php",
					data: {msgId:msgId,'reqtype':'updateStatustoread'},
					success: function( data ){							
							jQuery('#requestmodal').modal('hide');
							jQuery("#img_"+msgId+" img").attr('src','images/redcross.png').attr('title','Cancelled').attr('alt','Cancelled');
							window.location.reload();
					}
				}); 	
			}
		});
	}
}
</script>