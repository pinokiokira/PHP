<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: signup-form.php ,v 1.0 10:38 PM 7/4/2014 juni $
*  -> [req 1.33  - 04.07.2014]
		-> Code Indentation
		-> Make changes as requested
		-> Add password requirements pop'up
*/
require_once 'require/security.php';
include 'config/accessConfig.php';
require_once('require/openid-config.php'); 

$teammenu      = "active";
$teamDropDown  = "display:block;";
$teammenu123     = "active";

$client_id = $_SESSION['client_id'];

if($_REQUEST['action']=="delete"){
	$query = mysql_query("UPDATE employees_master SET image ='null' where empmaster_id=".$_SESSION["client_id"]); 
	if($query){
		$_SESSION['image'] = "";
		header('location:setup_editprofile.php');
	}
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

<style>
body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
.error{
	color: #FF0000;
	padding-left:10px;
}
.wizard .hormenu {
    margin-bottom: 33px;
}
.buttonPrevious{
		background: none repeat scroll 0 0 #0866C6 !important;
   		border-color: #0A6BCE !important;
    	color: #FFFFFF !important;
}
#popup_title {
    background-color: #0866C6 !important;
	    border-color: #0866C6;
}
.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px;  display:none; margin-top:10px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }

.error {
	color: #FF0000;
	padding-left:10px;
}
.modal-header .close {
	display:none;
}
.social td {
	vertical-align:middle;
	line-height: 36px;
	border-top: 0;
	border-bottom: 1px solid #dddddd;
}
.input-large .select-xlarge {
	height: 30px !important;
}
select {
	height: 30px !important;
}
.capital_word {
	text-transform:capitalize;
}

#pswd_info 
{
	position:absolute;
	bottom: -115px\9; /* IE Specific */
	width:250px;
	padding:9px;
	background:#fefefe;
	border:1px solid #ddd;
	left:72.1%;
	width:250px;
	z-index:10000;
}

#pswd_info h4 
{
	margin:0 0 10px 0;
	padding:0;
	font-weight:normal;
}
#pswd_info::before {
	width: 0; 
	height: 0; 
	border-top: 10px solid transparent;
	border-bottom: 10px solid transparent; 	
	border-right:10px solid #FFFFFF;
	content: "";
	position:absolute;
	top:-1px;
	left:0%;
	display:block;
}

#pswd_info .invalid 
{
	background: url(./images/closed_cancelled_terminated_16.png) no-repeat 1% 22%;
	padding-left:22px;
	line-height:24px;
	color:#ec3f41;
	font-size:12px;
	list-style:none;
}

#pswd_info .valid 
{
	background: url(./images/active_16.png) no-repeat 1% 22%;
	padding-left:22px;
	line-height:24px;
	color:#3a7d34;
	font-size:12px;
	list-style:none;
}

#pswd_info 
{
	 display:none;
}
.chzn-search input{ _height:28px !important; _width:260px !important;}
.chzn-container,.input-xlarge{ _width:281px !important;}
.chzn-container-multi .chzn-choices .search-field input{ height:30px !important;}
.tagsinput input{ width:auto !important;}
.ui-datepicker-month{ width:33% !important;}
.ui-datepicker-year{ width:33% !important; margin-left:5%; }


.social td {
	vertical-align:middle;
	line-height: 36px;
	border-top: 0;
	border-bottom: 1px solid #dddddd; 
}
ul.chzn-choices {
	height: 34px !important;
}
.chzn-container-multi .chzn-choices {

	height:auto;
} 
.no-results {
	display:none !important;
}
.stdform p, .stdform div.par {
	margin: 10px 0px;
}
</style>
</head>

<body>

<div class="mainwrapper">
    
    <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Setup<span class="separator"></span></li>
            <li>Profile</li>
            
            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
            
            <div class="pageicon"><span class="iconfa-cog"></span></div>
            <div class="pagetitle">
                <h5>Edit Your Personal Profile</h5>
                <h1>Edit Profile</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div class="span4 profile-left" style="height:310px;">
                        
                        <div class="widgetbox profile-photo">
                            <div class="headtitle">
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                       <li><a href="setup_upload_img_profile_process.php" role="button" data-toggle="modal">Change Photo</a></li>
                                      <li><a href="#" id="remove_photo_option">Remove Photo</a></li>
                                    </ul>
                                </div>
                                <h4 class="widgettitle">Profile Photo</h4>
                            </div>
                            <div class="widgetcontent" > <!--style="height:256px;max-height:255px;max-width:auto;"-->
                            <p style="margin:0;padding:0;text-align:center;"> (Image Size Required 225w x 225h) </p>
                                <div class="profilethumb" id="imagebox" style="padding-bottom: 7.5%;">
                                    <?php 	$query123 = 	mysql_query("select image from employees_master WHERE empmaster_id=".$_SESSION['client_id']);
					$client_image123 = mysql_fetch_array($query123);?>	
                                    <?php 
									if($client_image123["image"]!="" && $client_image123["image"]!="null" && $client_image123["image"]!=null)
									{	
										if (getimagesize(API."images/" .$client_image123["image"]) != false)
										{
											?>
											<img src="<?php echo API;?>images/<?php echo $client_image123["image"];?>" alt="" style="max-height: 235px;max-width: 358px;"/>
											<input type="hidden" name="oldimage" id="oldimage" value="<?php echo API;?>images/<?php echo $client_image123["image"];?>">
											<?php 
										}
									}
									else {
                                        ?>
                                    <img src="images/Default - User.png" alt="" class="img-polaroid" />
                                    <input type="hidden" name="oldimage" id="oldimage">
                                    <?php }?>
                                </div><!--profilethumb-->
                            </div>
                        </div>
                        <!-- <div class="widgetbox profile-photo">
                            <div class="headtitle">
                            	<div class="btn-group">
                                    <button data-toggle="dropdown" class="btn dropdown-toggle">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                      <li><a href="setup_upload_resume_process.php" role="button" data-toggle="modal">Upload Resume</a></li>
                                      <li><a href="#" id="remove_resume_option">Remove Resume</a></li>
                                 
                                    </ul>
                                </div>
                            
                                <h4 class="widgettitle">Resume</h4>
                            </div>
                            <div class="widgetcontent" style="height:110px;" id="resumebox">
                                <div style="display:table;width:100%;height: 100%;">
					        <?php if($_GET['upload_resume']=='success')echo 'Resume has been uploaded successfully!!';
                            $sql = "SELECT * FROM employees_master WHERE empmaster_id='{$_SESSION["client_id"]}'";
							$result = mysql_query($sql);
							$row = mysql_fetch_assoc($result);                    	
								
                            if ($row["resume"]!="" && file_exists($_SERVER["DOCUMENT_ROOT"] . "/images/" . $row["resume"])){
								?>
                            <div id="resume_file"></div>
                            
                            <div style="display: table-cell;vertical-align: middle;text-align: center;">
                                <a href="<?php echo API . "images/" . $row["resume"];?>" id="btn_view_resume" class="btn btn-primary" target="_blank">View</a>
                                <div id='preview'></div>
                            </div>
                             
                            <?php } else { echo "No resume uploaded yet.";?>
                            <?php }?>
                                </div></div>
                        </div>  -->   
                        </div>
                    
                        <!--span4-->
                        <div class="span8" style="width: 67%; margin-left: 14px;">
                            <div class="widgetbox login-information" style="height:auto;margin-bottom: 0;padding-bottom: 0;" id="clientDiv" >
                                    <?php require_once('ajax/editloginfo.php');?>
                            </div>
                            
                            <div class="widgetbox personal-information" style="padding-top: 20px;">
                                    <?php require_once('ajax/editpersonalinfo.php');?>
                            </div>
                                
                           
                        </div><!--span8-->
                    </div><!--row-fluid-->
                    
                    <?php include_once 'require/footer.php';?><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
       
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<div aria-hidden="false" aria-labelledby="imgModalLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="imgModal" style="width:auto">
   <!-- <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
        <h3 id="imgModalLabel">Profile Image</h3>
    </div>
    <div class="modal-body">
      <form id="form_image" action="upload_profile_image.php?empID=<?php echo $_SESSION["client_id"];?>" method="post" ENCTYPE="multipart/form-data">
          <input type="hidden" name="oldimage" value="<?php echo $_SESSION['image'];?>">
         <div class="par">
            <label>Image</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-append">
                <div class="uneditable-input span3">
                    <i class="iconfa-file fileupload-exists"></i>
                    <span class="fileupload-preview"></span>
                </div>
                <span class="btn btn-file"><span class="fileupload-new">Select file</span>
                <span class="fileupload-exists">Change</span>
                <input type="file" name="image"/></span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
        </div>
        <p class="stdformbutton">
          <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
          <button type="submit" id="submitimage" class="btn btn-primary">Submit</button>
        </p>
      </form>

      </div>-->
</div>
<div aria-hidden="false" aria-labelledby="imgModalLabel" role="dialog" tabindex="-1" class="modal hide fade in" id="resumeModal">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
        <h3 id="imgModalLabel">Resume</h3>
    </div>
    <div class="modal-body">
      <form id="form_resume" action="setup_upload_resume.php?empID=<?php echo $_SESSION["client_id"];?>" method="post" ENCTYPE="multipart/form-data">
          <input type="hidden" name="oldresume" value="<?php echo $row["resume"];?>">
         <div class="par">
            <label>Image</label>
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="input-append">
                <div class="uneditable-input span3">
                    <i class="iconfa-file fileupload-exists"></i>
                    <span class="fileupload-preview"></span>
                </div>
                <span class="btn btn-file"><span class="fileupload-new">Select file</span>
                <span class="fileupload-exists">Change</span>
                <input type="file" name="image"/></span>
                <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                </div>
            </div>
        </div>
        <p class="stdformbutton">
          <button data-dismiss="modal" class="btn btn-primary">Cancel</button>
          <button type="submit" id="submitimage" class="btn btn-primary">Submit</button>
        </p>
      </form>

      </div>
    
</div>
<form id="frmsocial" name="frmsocial" method="post">
<input type="hidden" name="signup_first_name" id="signup_first_name" />
    <input type="hidden" name="signup_last_name" id="signup_last_name" />
    <input type="hidden" name="signup_email" id="signup_email" />
    <input type="hidden" name="client_idsm" id="client_idsm" />
 <!--   <input type="hidden" name="empmaster_image" value="<?php echo $_SESSION['image'];?>"/> -->
	<input type="hidden" name="name_title" id="name_title" />
	<input type="hidden" name="phonesm" id="phonesm"/>
	<input type="hidden" name="smaddress" id="smaddress"/>
	<input type="hidden" name="countrysm" id="countrysm"/>
	<input type="hidden" name="smstate" id="smstate"/>
	<input type="hidden" name="smcity" id="smcity"/>
	<input type="hidden" name="smzip" id="smzip"/>	
	<input type="hidden" name="smgender" id="smgender"/>
	<input type="hidden" name="smdob" id="smdob"/>
	<input type="hidden" name="smprofile_image" id="smprofile_image"/>
	<input type="hidden" name="last_datetime" id="last_datetime" value="<?=date('Y-m-d H:i:s')?>"/>
	<input type="hidden" name="Created_datetime" id="Created_datetime" value="<?=date('Y-m-d H:i:s')?>"/>
	<input type="hidden" name="provider" id="provider"/>	
        <input type="hidden" name="unlinkprovider" id="unlinkprovider"/>	
	<input type="hidden" name="access_token" id="access_token"/>
</form>        
</body>
</html>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>

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
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript">
var google_api_client_id = '<?php echo GOOGLE_API_CLIENT_ID;?>';
var facebook_app_id = '<?php echo FACEBOOK_APP_ID;?>';
var base_url = '<?php echo BASE_URL; ?>';
</script>
<script type="text/javascript" src="includes/fbsm.js" /></script>
<script type="text/javascript" src="../internalaccess/url.js"></script>
<script type="text/javascript">
var client_id = <?php echo $client_id;?>;
var API_URL = 'ajax/proxy.php?url=';
</script>
<script type="text/javascript">
	function open_fb()
	{ 
		window.open ("includes/loginsm.php?provider=Facebook", "Facebook","location=1,status=1,scrollbars=1,width=500,height=500");
	}
	function open_gp()
	{		
		window.open ("includes/loginsm.php?provider=Google", "Google","location=1,status=1,scrollbars=1,width=500,height=500");
	}
	function open_linked(){		
		window.open("includes/loginsm.php?provider=LinkedIn", "Linked In","location=1,status=1,scrollbars=1,width=650,height=250");
	}
        function open_twitter(){		
		window.open("includes/loginsm.php?provider=Twitter", "Twitter","location=1,status=1,scrollbars=1,width=650,height=250");
	}
	function updateValue(id,valueS){	
		document.getElementById(id).value = valueS;
	}
	function validateForm(){
		setTimeout(function(){jQuery('#signup_form').submit()},2000);
	}
</script>
<script>
    jQuery(document).ready(function(){
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==1){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step1');
        <?php }?>    
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==2){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step2');
        <?php }?>    
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==3){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step3');
        <?php }?>    
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==6){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step6');
        <?php }?>    
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==7){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step7');
        <?php }?>    
        <?php if (isset($_GET["tab"]) && $_GET["tab"]==4){?>
                jQuery('.tabbedwidget').tabs('select', 'wiz1step4');
        <?php }?>    
       
    })
function DoSMlink(action){	
    jQuery.blockUI({ message: null });
    if (jQuery("#smprofile_image").val()!=""){
        jQuery.ajax({
             async: false,
             url: 'setup_upload_sm_profile_image.php',
             data:{profile_image:jQuery("#smprofile_image").val()},
             type: 'POST',
             success: function(data){
                     jQuery("#smprofile_image").val(data);

             }
         });
    }
    
    var form_data = jQuery('#frmsocial').serialize();
    form_data+="&client_id="+client_id+"&action="+action+"&empmaster_image="+jQuery("#oldimage").val();
        jQuery.ajax({
                url: 'api/update_clientSM.php',
                data:form_data,
                type: 'POST',
                dataType: 'JSON',
                success: function(data){
					
                        if(data.success){

                        jAlert('Profile Updated Successfully!', 'Edit Profile', function(){
                                window.location.href = "setup_editprofile.php?tab=3";
                                
                        });
               
                        				
                    }

                    jQuery.unblockUI();
            }
    });
}
function SMunlink(provider){	  
    jQuery("#unlinkprovider").val(provider);
    DoSMlink('unlink');
}

//juni -> 05.07.2014 -> Add password requirements pop'up
jQuery('#password').keyup(function () {
	var pswd = jQuery('#password').val();
	if(pswd.match(jQuery("#email").val())){
		jQuery('#u_name').removeClass('valid').addClass('invalid');
	} else	{
		jQuery('#u_name').removeClass('valid').addClass('valid');
	} if(pswd == "" ){
		jQuery('#u_name').removeClass('valid').addClass('invalid');
	}
	if ( pswd.length < 6 ) {
		jQuery('#length').removeClass('valid').addClass('invalid');
	} else {
		jQuery('#length').removeClass('invalid').addClass('valid');
	}
	//validate letter
	if ( pswd.match(/[A-z]/) ) {	
		jQuery('#letter').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#letter').removeClass('valid').addClass('invalid');
	}
	if ( pswd.match(/[0-9]/) ) {
		jQuery('#number').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#number').removeClass('valid').addClass('invalid');
	}
	//validate capital letter
	if ( pswd.match(/[A-Z]/) || pswd.match(/[\@#\$\%\^\&*()_+!]/) ) {
		jQuery('#capital').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#capital').removeClass('valid').addClass('invalid');
	}
	//validate number	
});
jQuery('#password').focus(function () {
		jQuery('#colorbox').css('width','470px');
		jQuery('#pswd_info').show();
		var offset = jQuery('#password').offset();
		var width = jQuery('#password').width();
		var new_left = offset.left+width;
		var new_left = new_left +30;
		//var new_hieght = offset.top+35;
		var new_hieght = offset.top;
		jQuery('#pswd_info').offset({ top:new_hieght, left: new_left})
		//jQuery.colorbox.resize({width: '670px',	height: '650px',}) ; //not neeed here , but kept for futer reference
	});
jQuery('#password').blur(function () {
	jQuery('#pswd_info').hide();
	var pass = jQuery("#password").val();
	jQuery.ajax({
		url: 'check_password.php',
		data: {value: pass},
		type: 'POST',
		dataType: 'json',
		success:function(data){	
			if(data.success == '1'){				
				jQuery("#last_five").val("1");
			} if(data.success == '0'){
				jQuery("#last_five").val("");
			}
		}
	});		
 });
 /* -> MOVED TO MAIN.JS
 jQuery("#update_login_info").click(function(){
	if (jQuery("#last_five").val() == "1") {
		jAlert("You can not use your old password!","Alert Dialog");	
		return false;
	} 
 });*/
</script>
<script type="text/javascript">
    
jQuery(document).ready(function(){
if(window.location.hash == "#googtrans(en|<?php echo $_SESSION['lang'];?>)"){
		//jQuery("input[type='text']").css("height","30px");
		//jQuery("input[type='password']").css("height","30px");
		jQuery(".chzn-container chzn-container-single").css("width","272px");
	}
	jQuery('#show_password').click(function(){
			if(jQuery(this).is(':checked')){
				jQuery('#password_text').show();
				jQuery('#user_password').hide();
			}
			else {
				jQuery('#user_password').show();
				jQuery('#password_text').hide();
			}
		});
                jQuery('#show_confirm_password').click(function(){
			if(jQuery(this).is(':checked')){
				jQuery('#confirmpassword_text').show();
				jQuery('#confirm_password').hide();
			}
			else {
				jQuery('#confirm_password').show();
				jQuery('#confirmpassword_text').hide();
			}
		});
		
		jQuery('#user_password').keyup(function() {
			jQuery('#password_text').val(jQuery('#user_password').val());
		});
		jQuery('#password_text').keyup(function() {
			jQuery('#user_password').val(jQuery('#password_text').val());
		});

		jQuery('#confirm_password').keyup(function() {
			jQuery('#confirmpassword_text').val(jQuery('#confirm_password').val());
		});
		jQuery('#confirmpassword_text').keyup(function() {
			jQuery('#confirm_password').val(jQuery('#confirmpassword_text').val());
		});
                
     jQuery('#remove_photo_option').click(function(){
            jConfirm('You are about to remove profile photo, Are you sure?', 'Remove Photo', function(r) {
                    if(r){
                            var cm = jQuery('#oldimage').val();
                           		window.location="setup_editprofile.php?action=delete&oldimage="+cm;
                            //jQuery("#form_image").attr("action","delete_profile_photo.php?oldimage="+cm);
                            //jQuery("#form_image").submit();
                          
                    }
            });
    });
    
    jQuery('#remove_resume_option').click(function(){
            jConfirm('You are about to remove resume, Are you sure?', 'Remove Resume', function(r) {
                    if(r){
                            var cm = jQuery('#oldresume').val();
                            
                            jQuery("#form_resume").attr("action","setup_delete_resume.php?oldresume="+cm);
                            jQuery("#form_resume").submit();
                          
                    }
            });
    });
       
    jQuery('[data-toggle="modal"]').click(function(e) {
	e.preventDefault();
	var url = jQuery(this).attr('href');
	if (url.indexOf('#') == 0) {
		jQuery(url).modal('open');
	} else {
		jQuery.get(url, function(data) {
			jQuery('<div class="modal hide fade" style="width:auto">' + data + '</div>').modal();
		}).success(function() { jQuery('input:text:visible:first').focus(); });
	}
});


  var $tabs = jQuery('.tabbedwidget').tabs({
            activate: function (event, ui) {
				 selected = ui.newTab.context.id;
              
              }
        });
   	
});

</script>
