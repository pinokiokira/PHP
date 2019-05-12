<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$trainingDropDown = "display: block;";
$trainingHead = "active";
$trainingMenu2 = "active";

$lesson_id = mysql_real_escape_string($_GET["lessonid"]); 
$_SESSION['SESS_LessonID']=$lesson_id;
$employee_id=$_REQUEST['employee_id'];
$emp_master_id = $_REQUEST['emp_master_id'];
if($emp_master_id!=""){
$_SESSION["employee_id"]=$emp_master_id;
}else{
$_SESSION["employee_id"]=$employee_id;
}

// Get Location ID
if($employee_id!=""){
$esquery="SELECT location_id FROM employees WHERE id=".$employee_id;
$esresult=mysql_query($esquery);
$esrow=mysql_fetch_object($esresult);
$location_id=$esrow->location_id;
$_SESSION["loc"]=$location_id;

 // Delete from temp table
 $dquery="DELETE FROM `training_video_questions_emp` WHERE emp_id=".$_SESSION["employee_id"];
 $dresult=mysql_query($dquery);
} 

if(isset($_REQUEST['flag'])){
	// Get Total Number of vidoes for the lesson
	//$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos WHERE lesson_id='".$lesson_id."'";
	$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos JOIN training_videos ON training_videos.video_id = training_lesson_videos.video_id  WHERE training_videos.status='active' AND lesson_id='".$lesson_id."'";
	$lsresult=mysql_query($lsquery);
	$lsrow=mysql_fetch_object($lsresult);
	$intTotalVideo=$lsrow->TotalVideo;

	$strresult=$_REQUEST['result'];
	if($_REQUEST['flag']=="que"){
		// Check If all vidoes are passed for this lesson
		
		$squery="SELECT video_pass, count(location_id) as totPass FROM `training_employee_videos` WHERE lesson_id='".$lesson_id."' AND employee_id='".$_SESSION["employee_id"]."' GROUP BY video_pass";
		$sresult=mysql_query($squery);
		$srow=mysql_fetch_object($sresult);
		$stotalrow=$srow->totPass;
		if($stotalrow>=$intTotalVideo && $strresult=="Pass"){ // If all vidoes of this lesson are pass
			$totPass=0;
			if($srow->totPass!=""){
				$totPass=$srow->totPass;
			}
		$uquery="UPDATE training_employee_lessons SET lesson_ended_datetime=now(), lesson_video_score=".$totPass.",lesson_pass='Yes',lesson_taken_datetime=now() WHERE employee_id='".$_SESSION["employee_id"]."' AND lesson_id='".$lesson_id."'";
		
			$uresult=mysql_query($uquery);
		}else{ // If fail and all vidoes of this lesson are not pass
			
			$squery="SELECT video_pass, count(location_id) as totPass FROM `training_employee_videos` WHERE video_pass='Yes' AND lesson_id='".$lesson_id."' AND employee_id='".$_SESSION["employee_id"]."' GROUP BY video_pass";
			$sresult=mysql_query($squery);
			$srow=mysql_fetch_object($sresult);
			$stotalrow=$srow->totPass;
			$totPass=0;
			if($srow->totPass!=""){
				$totPass=$srow->totPass;
			}
			
			$uquery="UPDATE training_employee_lessons SET lesson_ended_datetime='', lesson_video_score=".$totPass.",lesson_pass='',lesson_taken_datetime=now() WHERE employee_id='".$_SESSION["employee_id"]."' AND lesson_id='".$lesson_id."'";
			$uresult=mysql_query($uquery);
		}
	}
}

// IF test Requiest is No then mark lesson as completed
if($_REQUEST['required']!="" && $_REQUEST['required']=="no"){
	$lsquery="SELECT count(id) as TotalVideo FROM training_lesson_videos JOIN training_videos ON training_videos.video_id = training_lesson_videos.video_id  WHERE training_videos.status='active' AND lesson_id='".$lesson_id."'";
	$lsresult=mysql_query($lsquery);
	$lsrow=mysql_fetch_object($lsresult);
	$intTotalVideo=$lsrow->TotalVideo;
	
	// Check If all vidoes are passed for this lesson
	$squery="SELECT video_pass, count(location_id) as totPass FROM `training_employee_videos` WHERE lesson_id='".$lesson_id."' AND employee_id='".$_SESSION["employee_id"]."' GROUP BY video_pass";
	$sresult=mysql_query($squery);
	$srow=mysql_fetch_object($sresult);
	$stotalrow=$srow->totPass;
	if($stotalrow==$intTotalVideo){ // If all vidoes of this lesson are pass
		$totPass=0;
		if($srow->totPass!=""){
			$totPass=$srow->totPass;
		}
		$uquery="UPDATE training_employee_lessons SET lesson_ended_datetime=now(), lesson_video_score=".$totPass.",lesson_pass='Yes',lesson_taken_datetime=now() WHERE employee_id='".$_SESSION["employee_id"]."' AND lesson_id='".$lesson_id."'";
		$uresult=mysql_query($uquery);
	}
}

if(isset($_GET["videoid"]) && !empty($_GET["videoid"])){
$video_id = mysql_real_escape_string($_GET["videoid"]);
$v_sql = "SELECT * FROM training_videos WHERE video_id='$video_id'";
$v_result = mysql_query($v_sql) or die(mysql_error());
$v_rows = mysql_fetch_array($v_result);
}
/*$sql = "SELECT
training_videos.video_id,
training_videos.group,
training_videos.product,
training_videos.type,
training_videos.author_type,
training_videos.author_id,
training_videos.module,
training_videos.version,
training_videos.`status`,
training_videos.`name`,
training_videos.keywords,
training_videos.image,
training_videos.image_small,
training_videos.video,
training_videos.test_required,
training_videos.num_questions_display,
training_videos.req_num_correct_quesitons,
training_videos.created_datetime,
training_lesson_videos.id,
training_lesson_videos.lesson_id,
training_lesson_videos.video_id,
training_lesson_videos.priority,
training_lesson_videos.video_req_to_continue_lesson
FROM
training_videos
INNER JOIN training_lesson_videos ON training_lesson_videos.video_id = training_videos.video_id
WHERE
training_lesson_videos.lesson_id = '$lesson_id'
";*/
$sql = "SELECT
training_videos.video_id,
training_videos.group,
training_videos.product,
training_videos.type,
training_videos.author_type,
training_videos.author_id,
training_videos.module,
training_videos.version,
training_videos.`status`,
training_videos.`name`,
training_videos.keywords,
training_videos.image,
training_videos.image_small,
training_videos.video,
training_videos.test_required,
training_videos.num_questions_display,
training_videos.req_num_correct_quesitons,
training_videos.created_datetime,
training_lesson_videos.id,
training_lesson_videos.lesson_id,
training_lesson_videos.video_id,
training_lesson_videos.priority,
training_lesson_videos.video_req_to_continue_lesson,
training_products.product as proname,
training_video_groups.groupname,
training_video_types.types,
training_employee_videos.video_pass
FROM
training_videos
INNER JOIN training_lesson_videos ON training_lesson_videos.video_id = training_videos.video_id
LEFT JOIN training_products ON training_videos.product = training_products.product_id
LEFT JOIN training_video_groups ON training_videos.group = training_video_groups.id AND training_videos.group = training_video_groups.id
LEFT JOIN training_video_types ON training_video_types.group_id = training_video_groups.id AND training_videos.type = training_video_types.id AND training_videos.type = training_video_types.id
LEFT JOIN training_employee_videos ON (training_employee_videos.video_id = training_videos.video_id AND training_employee_videos.employee_id='".$_SESSION["employee_id"]."' AND training_employee_videos.lesson_id = training_lesson_videos.lesson_id)
WHERE training_videos.status='active' AND 
training_lesson_videos.lesson_id = '$lesson_id' GROUP BY training_videos.video_id ORDER BY priority
";
$result = mysql_query($sql) or die(mysql_error());
$result_ = mysql_query($sql) or die(mysql_error());
$rowsss  = mysql_fetch_array($result_);
/*$sql = "SELECT * FROM training_lesson_videos WHERE lesson_id= '$lesson_id'";								
$result = mysql_query($sql) or die(mysql_error());*/


$sql2 = "SELECT * FROM training_lessons WHERE lesson_id= '$lesson_id'";								
$result2 = mysql_query($sql2) or die(mysql_error());
$rows_name  = mysql_fetch_array($result2);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | TeamPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<!--<script type="text/javascript" src="js/flashobject.js"></script>-->
<script type="text/javascript" src="js/validation.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<link href="//vjs.zencdn.net/3.2/video-js.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/video/video.js"></script>
<script src="js/tabcontent.js" type="text/javascript"></script>
<link href="css/tabcontent.css" rel="stylesheet" type="text/css" />
<style>
.sorting_asc {
background: url('images/sort_asc.png') no-repeat center right !important;
background-color: #333333 !important;
}
.sorting_desc {
background: url('images/sort_desc.png') no-repeat center right !important;
background-color: #333333 !important;
}
table.table tbody tr.ui-selected,table.table tfoot tr.ui-selected{background-color:rgb(128,128,128);}
.progress {
	position: relative;
	width: 100%;
	border: 1px solid #ddd;
	padding: 1px;
	border-radius: 3px;
	display: none;
	margin-top: 10px;
}

.bar {
	background-color: #B4F5B4;
	width: 0%;
	height: 20px;
	border-radius: 3px;
}

.percent {
	position: absolute;
	display: inline-block;
	top: 3px;
	left: 48%;
}

.ui-datepicker-month {
	width: 70px
}

.ui-datepicker-year {
	width: 70px
}
#licence_table th{ font-size:11px;}

.ui-dialog-titlebar-close{
	display:none !important;
	visibility: hidden;
}
.no-close .ui-dialog-titlebar-close {display: none !important; visibility: hidden;}
.modal{
	width:617px;
}

.ui-dialog .ui-dialog-titlebar{
	background:none;
	font-size:2em !important;
	border-bottom: 1px solid #EEEEEE;
    padding: 9px 15px;
	
}

.ui-dialog .ui-dialog-title{
	line-height:25px !important;
}

.ui-widget-header {
	border:none;
	color:black;
	font-weight:normal;
}

.ui-dialog .ui-dialog-content {
	padding:0px;
	background-color:#F5F5F5 !important;
}

.ui-dialog{
	padding:0px;
}

#opaque {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    z-index: 99;
    display: none;
    background-color: black;
    filter: alpha(opacity=80);
    opacity: 0.8;
	transition:opacity 0.15s linear 0s;
}

.table th, .table td
{
	padding:1%;
}
.tdname{
	font-size:1.1em;
	font-weight:bold;
}

select[multiple], select[size]{
	height:30px;
}

.ui-tabs-panel {
    color: #000000;
}
#clientSpan{ line-height:6px !important;}
.dataTables_filter input { height:auto; }
@media screen and (max-width: 1152px) {
	.table th, .table td
	{
		padding:0.4%;
	}
}
@media screen and (min-width:1152px and max-width: 1280px) {
	.row-fluid .span4{width:33.2% !important; }

}
@media screen and (min-width:1281px and max-width: 1440px) {
	.row-fluid .span4{width:33% !important; }

}
@media screen and (min-width:1440px and max-width: 1700px) {
	.row-fluid .span4{width:33.6% !important; }

}
@media screen and (min-width:1700px ) {
	.row-fluid .span4{width:33.9% !important; }

}
.maincontentinner {
    padding: 20px 15px !important;
}

/*.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
	float:none;
	text-align:center;
}

.ui-dialog .ui-dialog-buttonpane {
	background-color: #F5F5F5;
    border-radius: 0 0 6px 6px;
    border-top: 1px solid #DDDDDD;
    box-shadow: 0 1px 0 #FFFFFF inset;
    margin-bottom: 0;

}

.ui-dialog .ui-dialog-buttonpane button
{
	background: none repeat scroll 0 0 #0866C6;
    border-color: #0A6BCE;
    color: #FFFFFF;
}*/

</style>
<script>
_V_.options.flash.swf = "js/video/video-js.swf";

jQuery(document).ready(function(){
	
	jQuery("body").append("<div id='opaque' style='display: none;'></div>");
    jQuery('#licence_table').dataTable({
        "sPaginationType": "full_numbers",
        "aaSorting": [[ 5, "asc" ]],
        "fnDrawCallback": function(oSettings) {
            jQuery.uniform.update();
        }
    });
	
	if(jQuery("#hidVideoID").val()>0){
		GetVideoDetails(jQuery("#hidVideoID").val());
	}
	
	jQuery("#btnVideo").click(function(){
		if(jQuery("#hidVideoURL").val()=="" && jQuery("#hidYoutubeURL").val()==""){
			//alert("Sorry there is currently no video configured, please try again later.");
			jAlert('Sorry there is currently no video configured, please try again later.', 'Alert Dialog');
		}else{
			UpdateLessonStartTime();
			jQuery("#SelectedVideoName").html(jQuery("#tdVideoName").html());
			var Browsername=GetBrowser();
			strImage=jQuery("#hidVideoImage").val();
			if(jQuery("#hidVideoURL").val()!=""){
				strVideo="<?php echo APIPHP; ?>/images/"+jQuery("#hidVideoURL").val();
				var vpid = "my_video_1";
				if(Browsername.indexOf("internet")!=-1){
					vpid = "player";
					jQuery("#flash1").html('<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="587" height="415"><param name="movie" value="player.swf"/><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always"/><param name="flashvars" value="file='+ strVideo +'&image='+strImage+'"/><embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf" width="587" height="415" allowscriptaccess="always" allowfullscreen="true" flashvars="file='+ strVideo +'&image='+strImage+'"/></object>');
				}else{ 
					vpid = "my_video_1";
					jQuery("#flash1").html('<video id="my_video_1" class="video-js vjs-default-skin" controls preload="auto" width="587" height="415" data-setup="{}"><source src="'+ strVideo +'" type="video/mp4"><source src="'+ strVideo +'" type="video/flv"><source src="'+ strVideo +'" type="video/ogg"><source src="'+ strVideo +'" type="video/webm"></video>');
				}
			}else if(jQuery("#hidYoutubeURL").val()!=""){
				vpid = "flash1";
				strURL=jQuery("#hidYoutubeURL").val();
				strURL=strURL.replace("www.youtube.com/watch?v=","www.youtube.com/embed/");
				strURL=strURL.replace("//youtu.be/","//www.youtube.com/embed/");
				strURL=strURL.replace("//youtu.be/","//www.youtube.com/embed/");
				jQuery("#flash1").html('<iframe id="flash1" width="585" height="408" src="'+strURL+'" frameborder="0" allowfullscreen></iframe>');
			}
			
			var isQuiz=document.getElementById("hidisQuiz").value;
			if(isQuiz=="Yes" || isQuiz==""){
				//UpdateVideoViews('view');
				var aud = document.getElementById(vpid);
				if(aud!=null && aud!=''){
					aud.onended = function() {
						jConfirm('This video requires you to take a short quiz. Would you like to start the quiz at this time?', 'Confirm', function(r) {
							if (r){
								jQuery("#filter_modal").hide();
								openpopup();
							}else{
								UpdateVideoViews('view');
								jQuery("#opaque").hide();
							}
						});
					}
				}
			}else{
				UpdateVideoViews('');
				
				var aud = document.getElementById(vpid);
				if(aud!=null && aud!=''){
					aud.onended = function() {
						console.log('End Video');
						jQuery("#btnFilterClose1").attr("disabled",false);
					};
				}
				
			}
			
			//jQuery("#innerwrapper").addClass("transparent_class");
			 jQuery("#opaque").show();
			jQuery("#filter_modal").show();
			//jQuery('#flash1').load('video_quiz.php?que=0');
			//UpdateLessonStartTime();
		}
		
	});
	
	jQuery("#btnFilterCancel").click(function(){
		jQuery("#flash1").html("");
		jQuery("#filter_modal").hide();
		jQuery("#opaque").hide();
		var isQuiz=document.getElementById("hidisQuiz").value;
		if(isQuiz=="Yes" || isQuiz==""){
			UpdateVideoViews('view');
		}else{
			
		}
	});
	
	jQuery("#btnFilterClose1").click(function(){
		jQuery("#flash1").html("");
		jQuery("#filter_modal").hide();
		jQuery("#opaque").hide();
		var isQuiz=document.getElementById("hidisQuiz").value;
		if(isQuiz=="Yes" || isQuiz==""){
			
		}else{
			UpdateVideoViews('');
				setTimeout(function(){
					window.location.href='learntube_training_lessons_take_lesson.php?required=no&lessonid='+<?php echo $lesson_id;?>+"&employee_id="+<?php echo $_SESSION['employee_id'];?>;
				},1000);
			
		}
	});
	
	jQuery("#btnFilterClose").click(function(){
			jQuery("#flash1").html("");
			jQuery("#filter_modal").hide();
			var isQuiz=document.getElementById("hidisQuiz").value;
			var video_id=document.getElementById("hidVideoID").value;
			var lesson_id=document.getElementById("hidLessonID").value;
			var hidisTLVRecord=document.getElementById("hidisTLVRecord").value;
			//alert(isQuiz);
			if(isQuiz=="Yes" || isQuiz==""){
				//openWin('video_test.php?video_id='+video_id,'Video Questionaries','600','650','','yes');
				/*if(confirm("This video requires you to take a short quiz, would you like to poceed to quiz at this time?")){
					//openpopup('video_test.php?video_id='+video_id+'&lesson_id='+lesson_id);
					openpopup();
				}else{
					UpdateVideoViews('view');
					jQuery("#opaque").hide();
				}*/
				
				jConfirm('This video requires you to take a short quiz. Would you like to start the quiz at this time?', 'Confirm', function(r) {
					if (r){
						openpopup();
					}else{
						UpdateVideoViews('view');
						jQuery("#opaque").hide();
					}
				});
				
			}else if(isQuiz=="No"){
				if(hidisTLVRecord>0){
					/*if(confirm("Would you like to take the optional Questionnaries for this Lesson?")){
						//openpopup('video_test.php?video_id='+video_id+'&lesson_id='+lesson_id);
						openpopup();
					}else{
						UpdateVideoViews('view');
						jQuery("#opaque").hide();
					}*/
					
					jConfirm('Would you like to take the optional Questionnaries for this Lesson?', 'Confirm', function(r) {
						if (r){
							openpopup();
						}else{
							UpdateVideoViews('view');
							jQuery("#opaque").hide();
						}
					});
				}else{
					UpdateVideoViews('');
					jQuery("#opaque").hide();
				}
			}else{
				jQuery("#opaque").hide();
			}
	});
	
	//jQuery('#reg_link').click(function(e) {
		//e.preventDefault();
		//jQuery('#register').load('add_event.php');
	//});
	
	
	
});	

function openpopup(){
	//jQuery("#innerwrapper").addClass("transparent_class");
	 jQuery("#opaque").show();
	var iframe = jQuery('<iframe frameborder="0" name="editframe" style="background: #FFFFFF;" marginwidth="0" marginheight="0" allowfullscreen></iframe>');
	var dialog = jQuery("<div style='background-color:#FFFFFF;'></div>").append(iframe).appendTo("body").dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		width: "auto",
		height: "auto",
		close: function () {
			iframe.attr("src", "");
		}/*,
		buttons : {
			Cancel: function() {
			  if(confirm("Would you like to cancel the Questionarie?")){
					UpdateVideoViews('view');
					window.parent.location.href='training_lessons_take_lesson.php?lessonid='+jQuery("#hidLessonID").val();
				}else{
					
				}
			},
			"Submit": function() {
			  	//jQuery("#hidSubmit").val("2");
	   			//jQuery("#frmQuiz").submit();
				window.frames['editframe'].document.forms['frmQuiz'].submit();
			}
		}*/
	});
	
	iframe.attr({
		width:587,
		height:350,
		src: "learntube_video_quiz.php?que=0"
	});
	dialog.dialog("option", "title", jQuery("#tdVideoName").html()).dialog("open");
}

/* Get Browser Name */
function GetBrowser(){
	 var userAgent = navigator.userAgent.toLowerCase();
	var userBrowserName  = navigator.appName.toLowerCase();
	// Figure out what browser is being used
	jQuery.browser = {
		version: (userAgent.match( /.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/ ) || [0,'0'])[1],
		safari: /webkit/.test( userAgent ),
		opera: /opera/.test( userAgent ),
		msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
		mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent ),
		name:userBrowserName
	};
	//alert($.browser.name+$.browser.version);
	return jQuery.browser.name;
}
/* End : Get Browser */
</script>
<?
	//include("includes/site_javascript.php");
?>
<!--<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>-->
</head>
<body>
<div class="mainwrapper" id="innerwrapper">
  <?php require_once('require/top.php');?>
    
    <?php require_once('require/left_nav.php');?>
  <!-- leftpanel -->
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="dashboard.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>LearnTube <!--<span class="separator"></span>Lessons--></li> 
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
	        <div style="position: absolute;margin-top: 11px;right: 16px;">
				 <a href="learntube.php" class="btn btn-primary btn-large">Back</a>
            </div>
      <div class="pageicon"><span class="iconfa-facetime-video"></span></div>
      <div class="pagetitle">
	  <h5>Watch each video in this lesson</h5>
        <h1>Lesson - [<?php echo $rows_name["name"];?>]</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
         		<div class="span13" style="width:65%;"> 
					<div class="clearfix">
						<h4 class="widgettitle">Lesson Videos</h4>			
					</div>
					<div class="widgetcontent">
						<table id="licence_table" class="table table-bordered responsive">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
										</colgroup>
										<thead>
										<tr>
												<th class="head1" style="width:17%;">Video Name</th>
												<th class="head0" style="width:14%;">Group</th>
												<th class="head1" style="width:14%;">Type</th>
												<th class="head0" style="width:14%;">Product</th>
												<th class="head1" style="width:10%;">Module</th>
												<th class="head0" style="width:10%;">Priority</th>
												
												<th class="head1" style="width:7%;">Passed</th>
												<th class="head0" style="width:7%;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											if(mysql_num_rows($result)>=1){
												$i = 1; $intVideoID=0;
											while($rows  = mysql_fetch_array($result)){
												if($i==1){
													$intVideoID=$rows['video_id'];
												}
											?>
											<!--<tr style="cursor:pointer;" onClick="window.location.href='training_lessons_take_lesson.php?flag&videoid=<?php echo $rows["video_id"];?>&lessonid=<?php echo $lesson_id;?>'">-->
											<tr onClick="GetVideoDetails('<?php echo $rows['video_id'];?>');" style="cursor:pointer;" id="tr_<?php echo $rows["video_id"];?>" class="gradeX cl_order <?php if($rows["video_id"]==$_REQUEST['videoid']) {?>g_item odd ui-selectee ui-selected<?php } ?>"> <!--  onClick="window.location.href='training_lessons_take_lesson.php?flag&videoid=<?php echo $rows["video_id"];?>&lessonid=<?php echo $lesson_id;?>'"  -->
											<?php //if(mysql_real_escape_string(isset($_GET["videoid"])) || $i=='1') { ?> <!--style="background-color:#DDDDDD;"--> <?php //}?>
												<td><?php echo $rows["name"];?></td>
												<td><?php echo $rows["groupname"];?></td>
												<td><?php echo $rows["types"];?></td>
												<td><?php echo $rows["proname"];?></td>
												<td><?php echo $rows["module"];?></td>
												<td><?php echo $rows["priority"];?></td>
											<!--	<td><?php echo $rows["test_required"];?></td>-->
												<td><?php echo $rows["video_pass"];?>&nbsp;</td>
												<td class="center">
												<a href="#">
													<img src="images/279-videocamera.png" border="0" style="height:16px;width:22px;">
												</a>
												</td>
											</tr>
											<?php $i++; } // end while
											}
											 ?>
											
									    </tbody>
						</table>
					</div>
				<input type="hidden" id="hidVideoID" name="hidVideoID" value="<?php echo $intVideoID;?>">
				<input type="hidden" id="hidEmpID" name="hidEmpID" value="<?php echo $_SESSION["employee_id"];?>">
				<input type="hidden" id="hidLessonID" name="hidLessonID" value="<?php echo $_REQUEST['lessonid'];?>">
				<input type="hidden" name="hidisQuiz" id="hidisQuiz" value="">
				<input type="hidden" name="hidisTLVRecord" id="hidisTLVRecord" value="">
				</div> <!--span13-->
				<div class="span4 profile-left">
					<div class="widgetbox company-photo" style="display:none" id="divVideoDetails">
							  <h4 class="widgettitle">Video</h4>
							  <div class="widgetcontent">
							 <?php /*?> <?php 
							  if(isset($_REQUEST["videoid"])){
							  	$v_video = $v_rows["video"];
							  }
							  else{ $v_video = $rowsss["video"];}
							  if($v_video != ""){?>
                               <div class="profilethumb" style="text-align:left;">
								<video width="320" height="240" controls style="width:100%;">
								  <source src="movie.mp4" type="video/mp4">
								  <source src="movie.ogg" type="video/ogg">
								  Your browser does not support the video tag.
								</video>
							   </div>
						<button href="javascript:void(0);" class="btn btn-primary" style="width:100%;">Start Lesson</button>
						<?php } else { ?>
						No Video Configured.
						<?php } ?>
						</div>-->
						<div class="widgetcontent">
							  <?php 
							  if($v_rows["image"] == ""){
							  ?>
							  	<img src="images/noimage.png" alt="" id="personalimagephoto" width="80" height="80" />
							 <?php } else{ ?>
							 
							 <img src="<?php echo $v_rows["image"]; ?>" alt="" id="personalimagephoto" width="80" height="80" />
							<?php } ?>
						</div><?php */?>
						
							<table width="100%" border="0" cellpadding="2" cellspacing="2">
								<tr>
									<td style="width:25%;"><img src="images/noimage.png" alt="" id="imgVideoImage" name="imgVideoImage" style="width:80px;height:80px"/></td>
									<td align="left" style="padding-left:0.5%;">
										<img alt="" title="" id="imgVideoResult" name="imgVideoResult" style="display:none; margin-bottom:25px" style="width:32px;height:32px"/>
									</td>
								</tr>
								
								<tr>
									<td><strong>Name:</strong> </td>
									<td style="padding-left:0.5%;" class="tdname" id="tdVideoName"></td>
								</tr>
								<tr>
									<td><strong>Group:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoGroup"></td>
								</tr>
								<tr>
									<td><strong>Type: </strong></td>
									<td style="padding-left:0.5%;" id="tdVideoType"></td>
								</tr>
								<tr>
									<td><strong>Product:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoProduct"></td>
								</tr>
								<tr>
									<td><strong>Module:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoModule"></td>
								</tr>
								<tr>
									<td><strong>Version:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoVersion"></td>
								</tr>
                                <tr>
									<td><strong>Video Required:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoreq"></td>
								</tr>
								<tr>
									<td><strong>Test Required:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoTest"></td>
								</tr>
								<tr>
									<td><strong>Last Taken:</strong> </td>
									<td style="padding-left:0.5%;" id="tdTestTaken"></td>
								</tr>
								<tr>
									<td><strong>Pass:</strong> </td>
									<td style="padding-left:0.5%;" id="tdVideoPass"></td>
								</tr>	
                                <tr>
									<td colspan="2" align="center">
										<button class="btn btn-primary" id="btnVideo" name="btnVideo" style="width:100%;">Watch Video</button>
										<input type="hidden" id="hidVideoURL" name="hidVideoURL" value="">
										<input type="hidden" id="hidYoutubeURL" name="hidYoutubeURL" value="">
										<input type="hidden" id="hidVideoImage" name="hidVideoImage" value="">
									</td>
								</tr>
									
							</table>
							</div>
					</div>
				</div> <!--span4-->
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

<div id="filter_modal" class="modal in fade" style="display:none">
	
	
		<div class="modal-header" >
			<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
			<h3 id="SelectedVideoName"></h3>
		</div>
		<div id="video">
			<div class="modal-body" id="flash1">
				
			</div>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p>
				<button id="btnFilterCancel" name="btnFilterCancel" type="button" class="btn btn-primary">Cancel</button>
				<button id="btnFilterClose" name="btnFilterClose" type="button" class="btn btn-primary">Quiz</button>
                <button id="btnFilterClose1" disabled="disabled" name="btnFilterClose" type="button" class="btn btn-primary">Close</button>
				<!--<button type="button" id="btnSearch" name="btnSearch" class="btn btn-primary">Submit</button>-->
			</p>
		</div>
</div>
</body>
</html>
<script>
function GetVideoDetails(intVideoID){
	jQuery("#hidVideoID").val(intVideoID);
	jQuery("[id*='tr_']").removeClass("ui-selected");
	jQuery("#tr_"+intVideoID).addClass("g_item odd ui-selectee ui-selected");
	jQuery("#imgVideoImage").attr("src","images/loading.gif");
	var intRandNum=Math.floor(Math.random()*5411);
	var lesson_id = '<?php echo $_REQUEST['lessonid']; ?>';
	data={"intRandNum":intRandNum,"intVideoID":intVideoID,"lesson_id":lesson_id};
	//alert('getvideodetails.php?intVideoID='+intVideoID);
	jQuery.ajax({	
			url: "learntube_getvideodetails.php",
			data: data, 
			type: "POST",
		 	dataType: 'json',
			error: function (req, stat, err) {
				console.log('req:');
				console.log(req);

				console.log('stat:');
				console.log(stat);

				console.log('err:');
				console.log(err);
			},
			success: function (mydetails) {
				//alert(mydetails.length);
				jQuery("#divVideoDetails").show();
				jQuery("#imgVideoImage").attr("src","images/noimage.png");
				jQuery("#hidVideoImage").val("");
				//alert(mydetails[0].image);
				if(mydetails[0].image!=""){
					jQuery("#imgVideoImage").attr("src","<?php echo APIPHP; ?>/images/"+mydetails[0].image);
					jQuery("#hidVideoImage").val("<?php echo APIPHP; ?>/images/"+mydetails[0].image);
				}
				jQuery("#tdVideoName").html(mydetails[0].name);
				jQuery("#tdVideoGroup").html(mydetails[0].groupname);
				jQuery("#tdVideoType").html(mydetails[0].types);
				jQuery("#tdVideoProduct").html(mydetails[0].proname);
				jQuery("#tdVideoModule").html(mydetails[0].module);
				jQuery("#tdVideoVersion").html(mydetails[0].version);
				jQuery("#tdVideoreq").html(mydetails[0].video_req_to_continue_lesson);
				jQuery("#tdVideoTest").html(mydetails[0].test_required);
				jQuery("#hidisQuiz").val(mydetails[0].test_required);
				if (mydetails[0].video != "")
	 				jQuery("#hidVideoURL").val(mydetails[0].video);
				else 
					jQuery("#hidVideoURL").val(mydetails[0].video_lesson);
				jQuery("#hidYoutubeURL").val(mydetails[0].video_youtube);
				if(mydetails[0].video_pass=="Yes"){
					jQuery("#imgVideoResult").attr("src","images/lesson_pass.png");
					jQuery("#imgVideoResult").attr("title","Passed");
				}else if(mydetails[0].video_pass=="No"){
					jQuery("#imgVideoResult").attr("src","images/lesson_notpass.png");
					jQuery("#imgVideoResult").attr("title","Fail");
				}else {
					jQuery("#imgVideoResult").attr("src","images/lesson_empty.png");
					jQuery("#imgVideoResult").attr("title","Not-passed");
				}
				jQuery("#imgVideoResult").show();
				
				jQuery("#hidisTLVRecord").val(mydetails[0].lesson_id);
				jQuery("#btnFilterClose").hide();
				jQuery("#btnFilterClose1").hide();
				if(parseInt(mydetails[0].TotQuestion)>0){
					jQuery("#btnFilterClose").show();					
				}else{
					jQuery("#btnFilterClose1").show();
				}
				if(mydetails[0].video_test_datetime){
				var string = mydetails[0].video_test_datetime.replace('/','-');
				 string = string.replace('/','-');
				 }else{
				 	var string="";
				 }
				jQuery("#tdTestTaken").html(string);
				jQuery("#tdVideoPass").html(mydetails[0].video_pass);
		  }
	 });
}
</script>