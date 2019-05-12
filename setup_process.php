<?php
	ob_start("ob_gzhandler");
    include_once 'includes/session.php';
	include_once("config/accessConfig.php");
	
/**
 * set menu active options for this page
 */
	//$setupDropDown = "display: block;";
	//$setupHead = "active";
	//$setupMenu1 = "active";
	//$_SESSION['curent_step']=9999;
	
	
	if (!isset($_SESSION['current_step']))
	{
		$location_id=$_SESSION["loc"];
		$sql="update location_installation set status='Profile' where location_id='$location_id'";
		$result=mysql_query($sql);
		$_SESSION['current_step']=0;
		
	}
		if($_SESSION['last_step']< $_GET['step']){
			$_SESSION['last_step'] = $_GET['step'];
		}
	
	
	
		if($_SESSION['last_step']>$_GET['step']){
			$_GET['step'] = $_SESSION['last_step'];
		}
		
		
		if (isset($_GET['step']) && $_GET['step']=="1")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Profile' where location_id='$location_id'";
			$result=mysql_query($sql);
			$step=1;
			$_SESSION['current_step']=1;
		}
		if (isset($_GET['step']) && $_GET['step']=="2")
		{
			$step=2;
			$_SESSION['current_step']=2;
		}
		
		if (isset($_GET['step']) && $_GET['step']=="3")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Employee',step_profile=now() where location_id='$location_id'";
			$result=mysql_query($sql);
			$step=3;
			
				$_SESSION['current_step']=3;
		}
		
		if (isset($_GET['step']) && $_GET['step']=="4")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Operations',step_employee=now() where location_id='$location_id'";
			$result=mysql_query($sql);
			
		   $sql_check="select * from location_installation where location_id='$location_id'";
			$result_check=mysql_query($sql_check);
			$row_check=mysql_fetch_array($result_check);
			
			if($row_check['Other']=='Yes')
			{
				header("Location: setup_process.php?step=6");  
			}
			else
			{
				$step=4;
				$_SESSION['current_step']=4;
			}
		}
		
		if (isset($_GET['step']) && $_GET['step']=="5")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Finance',step_operations=now() where location_id='$location_id'";
			$result=mysql_query($sql);
			$step=5;
			
				$_SESSION['current_step']=5;
		}
		
		if (isset($_GET['step']) && $_GET['step']=="6")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Installed',step_fiannce=now() where location_id='$location_id'";
			$result=mysql_query($sql);
			$step=6;
				$_SESSION['current_step']=6;
		}
		
		if (isset($_GET['step']) && $_GET['step']=="7")
		{
				$step=7;
				$_SESSION['current_step']=7;
		}
		
		if (isset($_POST['step']) && $_POST['step']=="7")
		{
			$location_id=$_SESSION["loc"];
			$sql="update location_installation set status='Installed' where location_id='$location_id'";
			$result=mysql_query($sql);
			header("Location: index.php");  
		}
		
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>SoftPoint | BusinessPanel</title>
	<link rel="stylesheet" href="css/style.default.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/modernizr.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
    <script type="text/javascript" src="js/token.js"></script>

<style>
    .line3{
        background-color: #cccccc;
    }
    .input-append .add-on, .input-prepend .add-on {
        height: 22px;
    }
	.modal.fade.in{position: fixed !important;top: 50%;margin-top: -205px !important;left: 50% !important;margin-left: -280px !important;}
</style>
	<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="js/jquery.alerts.js"></script>
	<script type="text/javascript" src="js/elements.js"></script>
	<script type="text/javascript" src="prettify/prettify.js"></script>

	<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
	<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
	<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
	<script type="text/javascript" src="js/charCount.js"></script>
	<script type="text/javascript" src="js/colorpicker.js"></script>
	<script type="text/javascript" src="js/ui.spinner.min.js"></script>
	<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="js/forms.js"></script>
	<script type="text/javascript" src="js/fileuploader.js"></script>
	
<script>
	/*jQuery(document).ready(function(){
		var width = jQuery(window).width();
		var height = jQuery(window).height();		
		jQuery('#initialModal').css({"margin-left":'-'+(width-jQuery('#initialModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#initialModal').height())/1.10+'px'});
		jQuery('#profileModal').css({"margin-left":'-'+(width-jQuery('#profileModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#profileModal').height())/1.10+'px'});
		jQuery('#employeesModal').css({"margin-left":'-'+(width-jQuery('#employeesModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#employeesModal').height())/1.10+'px'});
		jQuery('#operationsModal').css({"margin-left":'-'+(width-jQuery('#operationsModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#operationsModal').height())/1.10+'px'});
		jQuery('#financeModal').css({"margin-left":'-'+(width-jQuery('#financeModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#financeModal').height())/1.10+'px'});
		jQuery('#additionalModal').css({"margin-left":'-'+(width-jQuery('#additionalModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#additionalModal').height())/1.10+'px'});
		jQuery('#completeModal').css({"margin-left":'-'+(width-jQuery('#completeModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#completeModal').height())/1.10+'px'});
	});
	jQuery(window).resize(function(){
		var width = jQuery(window).width();
		var height = jQuery(window).height();		
		jQuery('#initialModal').css({"margin-left":'-'+(width-jQuery('#initialModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#initialModal').height())/1.10+'px'});
		jQuery('#profileModal').css({"margin-left":'-'+(width-jQuery('#profileModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#profileModal').height())/1.10+'px'});
		jQuery('#employeesModal').css({"margin-left":'-'+(width-jQuery('#employeesModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#employeesModal').height())/1.10+'px'});
		jQuery('#operationsModal').css({"margin-left":'-'+(width-jQuery('#operationsModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#operationsModal').height())/1.10+'px'});
		jQuery('#financeModal').css({"margin-left":'-'+(width-jQuery('#financeModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#financeModal').height())/1.10+'px'});
		jQuery('#additionalModal').css({"margin-left":'-'+(width-jQuery('#additionalModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#additionalModal').height())/1.10+'px'});
		jQuery('#completeModal').css({"margin-left":'-'+(width-jQuery('#completeModal').width())/3+'px',"margin-top":'-'+(height-jQuery('#completeModal').height())/1.10+'px'});
	});*/
</script>	
	
</head>

<body>

<div class="mainwrapper">
    
    <div class="header">
        <?php include_once 'includes/header.php';?>
    </div>
    
    <div class="leftpanel">
        
        <div class="leftmenu">        
            <?php include_once 'includes/left_menu.php';?>
        </div><!--leftmenu-->
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
            
        <ul class="breadcrumbs">
                
            
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
			
        </ul>
        
       <div class="pageheader">
            <div style="float:right;margin-top: 10px;">
				<!--<button class="btn btn-primary btn-large" id="goSearch">Go</button>&nbsp;&nbsp;
				<button class="btn btn-success btn-large addcode"  href="#" data-toggle="modal">Add</button>-->
                
            </div>
            <div class="pageicon"><span class="iconfa-table"></span></div>
            <div class="pagetitle">
               
            </div>
        </div><!--pageheader-->
		
		 
        
        <div class="maincontent">
            <div class="maincontentinner">
			 <div style="margin: 20px 0 40px 20px;">
					<!--<input id="step" type="hidden" value="<?php echo $step;?>" />-->
                </div>
            
            </div><!--maincontentinner-->
        </div><!--maincontent-->
    </div><!--rightpanel-->
	
	<button class="btn btn-primary btn-large" id="initial" href="#initialModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="initialModal" class="modal hide fade">
	<div class="modal-header" >
		<h3>Initial Setup Process</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
					Welcome to SoftPoint! The following slides will assist you step-by-step to help you and your business get up and running as quickly as possible.
				</p>
				<br/>
				<p>
					These steps are here to guide you in adding information and how it will be displayed, keep in mind that the information you enter here can be edited at any time.
				</p>
				<br/>
				<p>
					If you have any additional questions about the software that are not answered in the installation process, we invite you to check out <a href="http://www.learntube.co/" target="_blank">LearnTube</a>, our online training platform.
                                        
                                </p>
                                <br/>
                                <p>
					Thank you for choosing SoftPoint! We look forward to working with you.
				</p>
				<br/>
				<br/>
				<p>
					-The SoftPoint Team
				</p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
						<button id="btnContinue" onClick="javascript:jQuery('#profile').click();"  data-dismiss="modal" class="btn btn-primary">Continue</button>
				</p>
		</div>
</div>    


<button class="btn btn-primary btn-large" id="profile" href="#profileModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="profileModal" class="modal hide fade">
	<div class="modal-header" >
		<h3>Step 1 - Business Profile</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px;">
            <tr>
                <td width="100%">
				<p>
					The Profile page is used to enter all of the information about your business, from a detailed description to hours of operations, as well as adding images and map location. </p>
                                <br/>
                                <p>The more information you enter here, the more we can do to best serve your needs. </p>
                                <br/>
                                <p>Remember you can edit your Profile information at any time.
				</p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
						<button id="btnContinue" onClick="javascript:window.location.href='setup_business_profile.php?step=2';Show_left_loding();"  data-dismiss="modal" class="btn btn-primary">Continue</button>
				</p>
		</div>
</div>    

<button class="btn btn-primary btn-large" id="employees" href="#employeesModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="employeesModal" class="modal hide fade">
	<div class="modal-header" >
		<h3>Step 2 - Employees</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
					The Employees option will contain the list of all your current employees once profiles have been created to view and edit information as you choose.
				</p>
                                <br>
                                <p>
                                    The first Employee Profile added is linked to the BusinessPanel email account. We recommend adding the Representative or Contact person associated with that email as the first employee entry.
                                </p>
                                <br>
                                <p>
                                    Employee attendance, access availability and more are made available in 7 distinct tabs.
                                </p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
						<button id="btnContinue" onClick="javascript:window.location.href='setup_employees.php?step=3'"   data-dismiss="modal" class="btn btn-primary">Continue</button>
				</p>
		</div>
</div>    


<button class="btn btn-primary btn-large" id="operations" href="#operationsModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="operationsModal" class="modal hide fade">
	<!--<input type="hidden" value="<?php echo $restaurant;?>" id="restaurant"/>
	<input type="hidden" value="<?php echo $hotel;?>" id="hotel"/>
	<input type="hidden" value="<?php echo $retail;?>" id="retail"/>-->
	<div class="modal-header" >
		<h3>Step 3 - Operations</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
					You have been given access to the following options, shown to the left in the navigation bar based on the services and products linked to your location.
                                </p><br><p>
					Each page has been customized and designed to accommodate your unique business needs. 
				</p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
						<button id="btnContinueCustom"  data-dismiss="modal" class="btn btn-primary">Continue</button>
						<!--<button id="btnSkip"  data-dismiss="modal" class="btn btn-primary">Skip</button>-->
						<button id="btnSkip"  onclick="javascript:window.location.href='setup_process.php?step=5'"  data-dismiss="modal" class="btn btn-primary">Skip</button>
				</p>
		</div>
</div>   


<button class="btn btn-primary btn-large" id="finance" href="#financeModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="financeModal" class="modal hide fade">
	<!--<input type="hidden" value="<?php echo $restaurant;?>" id="restaurant"/>
	<input type="hidden" value="<?php echo $hotel;?>" id="hotel"/>
	<input type="hidden" value="<?php echo $retail;?>" id="retail"/>-->
	<div class="modal-header" >
		<h3>Step 4 - Finances</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
					 The Finance page allows you to keep track of any and all information regarding your finances in real time. You have been given access to the following options: 
					Add and edit this information from the current slide or anytime in the future. 

				</p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
			<p class="stdformbutton">
						<button id="btnContinue" onClick="javascript:window.location.href='setup_backoffice_global_items.php?step=5'"  data-dismiss="modal" class="btn btn-primary">Continue</button>
						<button id="btnSkip"  onclick="javascript:window.location.href='setup_process.php?step=6'"  data-dismiss="modal" class="btn btn-primary">Skip</button>
				</p>
		</div>
</div>   


<button class="btn btn-primary btn-large" id="additional" href="#additionalModal" data-backdrop="static" data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="additionalModal" class="modal hide fade">
	<!--<input type="hidden" value="<?php echo $restaurant;?>" id="restaurant"/>
	<input type="hidden" value="<?php echo $hotel;?>" id="hotel"/>
	<input type="hidden" value="<?php echo $retail;?>" id="retail"/>-->
	<div class="modal-header" >
		<h3>Step 5 - Additional Products</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
				That’s not all SoftPoint has to offer, here is a list of additional products that we offer to help streamline all your business operations. Click on the items you wish to learn more about: 
				
				</p>
                </td>
            </tr>
			<tr>
				<td  width="100%">
				<br/>
				<style>
					.add_products span
					{
						line-height: 14px;
						vertical-align: bottom;
					}
				</style>
				<table class="add_products" width="100%" cellpadding="5" cellspacing="5">
					<tr>
					<td width="25%">
						<table>
						<tr><td><input type="checkbox" value="BarPoint" class="products"/><span>BarPoint</span></td></tr>
						<tr><td><input type="checkbox" value="ConciergePoint" class="products"/><span>ConciergePoint</span></td></tr>
						<tr><td><input type="checkbox" value="CorporatePoint" class="products"/><span>CorporatePoint</span></td></tr>
						<tr><td><input type="checkbox" value="CRMPoint" class="products"/><span>CRMPoint</span></td></tr>
						<tr><td><input type="checkbox" value="CRSPoint" class="products"/><span>CRSPoint</span></td></tr>
						<tr><td><input type="checkbox" value="DeliveryPoint" class="products"/><span>DeliveryPoint</span></td></tr>
						<tr><td><input type="checkbox" value="EventPoint" class="products"/><span>EventPoint</span></td></tr>
						</table>
					</td>
					<td width="25%">
					<table>
						<tr><td><input type="checkbox" value="HotelPoint" class="products"/><span>HotelPoint</span></td></tr>
						<tr><td><input type="checkbox" value="ManagePoint" class="products"/><span>ManagePoint</span></td></tr>
						<tr><td><input type="checkbox" value="MenuPoint" class="products"/><span>MenuPoint</span></td></tr>
						<tr><td><input type="checkbox" value="POSPoint" class="products"/><span>POSPoint</span></td></tr>
						<tr><td><input type="checkbox" value="PrepPoint" class="products"/><span>PrepPoint</span></td></tr>
						<tr><td><input type="checkbox" value="QualityPoint" class="products"/><span>QualityPoint</span></td></tr>
						<tr><td><input type="checkbox" value="RegisterPoint" class="products"/><span>RegisterPoint</span></td></tr>
					</table>
					</td>
					<td width="25%">
					<table>
						<tr><td><input type="checkbox" value="ResvPoint" class="products"/><span>ResvPoint</span></td></tr>
						<tr><td><input type="checkbox" value="TimePoint" class="products"/><span>TimePoint</span></td></tr>
						<tr><td><input type="checkbox" value="BookEatSave" class="products1"/><span>BookEatSave</span></td></tr>
						<tr><td><input type="checkbox" value="Chefedin" class="products1"/><span>Chefedin</span></td></tr>
						<tr><td><input type="checkbox" value="ExpenseTAB" class="products1"/><span>ExpenseTAB</span></td></tr>
						<tr><td><input type="checkbox" value="JessFn" class="products1"/><span>JessFn</span></td></tr>
						<tr><td><input type="checkbox" value="Out2b" class="products1"/><span>Out2b</span></td></tr>
					</table>
					
					</td>
					<td width="25%">
					<table>
					<tr><td><input type="checkbox" value="Edu2b" class="products1"/><span>Edu2b</span></td></tr>
						<tr><td><input type="checkbox" value="Neult" class="products1"/><span>Neult</span></td></tr>
						<tr><td><input type="checkbox" value="StaffPoint" class="products1"/><span>StaffPoint</span></td></tr>
						<tr><td><input type="checkbox" value="SoftFork" class="products1"/><span>SoftFork</span></td></tr>
						<tr><td><input type="checkbox" value="StorePoint" class="products1"/><span>StorePoint</span></td></tr>
						<tr><td><input type="checkbox" value="SultryBar" class="products2"/><span>SultryBar</span></td></tr>
					</table>
					</td>
					
					</tr>
				</table>
				</td>
			</tr>
			<tr>
			<td>
			<br/>
			<p>
			A SoftPoint Representative will contact you with more information about your selected products. 
			</p>
			</td>
			</tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
		 <form class="stdform"  method="post" ENCTYPE="multipart/form-data">
			<input type="hidden" name="step" value="<?php echo $step?>"/>
			<input type="submit" id="btnSubmit" style="display:none;" />
		</form>
			<p class="stdformbutton">
			
					<button  type="submit" id="btnContinue"  onclick="CheckProducts()" class="btn btn-primary">Continue</button>
					<button id="btnSkip"  onclick="javascript:window.location.href='setup_process.php?step=7'"  data-dismiss="modal" class="btn btn-primary">Skip</button>
				</p>
				
		
		</div>
</div>   

<button class="btn btn-primary btn-large" id="complete" href="#completeModal" data-backdrop="static"  data-toggle="modal" style="display:none"></button>&nbsp;&nbsp;
	
	<div id="completeModal" class="modal hide fade">
	<!--<input type="hidden" value="<?php echo $restaurant;?>" id="restaurant"/>
	<input type="hidden" value="<?php echo $hotel;?>" id="hotel"/>
	<input type="hidden" value="<?php echo $retail;?>" id="retail"/>-->
	<div class="modal-header" >
		<h3>Step 6 - Installation Complete</h3>
	</div>
	<div class="modal-body">
            
            <table width="100%" height="250px">
            <tr>
                <td width="100%">
				<p>
					Congratulations! You have completed the installation process and are now ready to begin using the software. If you have any questions or comments contact us at +1 (800) 915-4012.


				</p>
                </td>
            </tr>
        </table>
	</div>
		<div class="modal-footer" style="text-align: center;">
		 <form class="stdform"  method="post" ENCTYPE="multipart/form-data">
			<input type="hidden" name="step" value="<?php echo $step?>"/>
			<input type="submit" id="btnSubmit" style="display:none;" />
		</form>
			<p class="stdformbutton">
			
					<button  type="submit" id="btnContinue"  onclick="jQuery('#btnSubmit').click()" class="btn btn-primary">Continue</button>
						
				</p>
				
		
		</div>
</div>   
<input type="hidden" value="<?php echo $_GET['skip_count'];?>" id="skip_count" />
<input type="hidden" value="<?php echo $_GET['go']; ?>" id="go" />

<input type="hidden" id="location_id" value="<?php echo $_SESSION['loc'];?>" />
    
</div><!--mainwrapper-->
<script>
function CheckProducts()
{
	var products=[];
	jQuery(".products").each(function(){
		if (jQuery(this).is(":checked"))
		{
			products.push(jQuery(this).val());
		}
	});
	
	var location_id=jQuery("#location_id").val();
	//alert(location_id);
	SendEmail(location_id,products);
	
}
function SendEmail(location_id,products)
{
/*var API_URL= "<?php echo API;?>/API/";*/
var API_URL= "api/";
var sent_as ='inst';
	var destination = "info@softpointcloud.com";
    jQuery.ajax({
        url: API_URL +'interestedproducts.php?token='+generatetoken(),
        data: {location_id:location_id,products:products,destination:destination,sent_as:sent_as},
        type: 'POST',
        dataType: 'json',
        success:function(data){
			
			window.location.href='setup_process.php?step=7';
        },
        error: function(a,b,c){
            jQuery('#loginerror').text('An error has occurred, Please try again.');
            jQuery('.login-alert').fadeIn();
        }
    });

	
}
jQuery(document).ready(function(){
		
		
		/*switch(jQuery("#step").val())
		{
			case "2":
				
			break;
			
			
		}*/
		var count=0;
		var arr=[];
		var custom_text="";
		if (jQuery("#restaurant").val()=="yes")
		{
			count++;
			arr.push("restaurant");
			if (custom_text=="")
				custom_text="Restaurant ";
			else
				custom_text+="and Restaurant";
		}
		if (jQuery("#hotel").val()=="yes")
		{
			count++;
			arr.push("hotel");
			if (custom_text=="")
				custom_text="Hotel ";
			else
				custom_text+="and Hotel ";
		}
		if (jQuery("#retail").val()=="yes")
		{
			count++;
			arr.push("retail");
			if (custom_text=="")
				custom_text="Retail ";
			else
				custom_text+="and Retail ";
		}
		
		jQuery("#custom_text").html(custom_text);
		
		if (jQuery("#skip_count").val()=="")
			jQuery("#skip_count").val(count);
		
		
		if (jQuery("#restaurant").val()=="yes" && (jQuery("#go").val()=="" || jQuery("#go").val()=="restaurant"))
		{
			
			
			
			/*jQuery("#skip_count").val(parseInt(jQuery("#skip_count").val())-1);
			
			if (jQuery("#skip_count").val()==0)
			{
				jQuery("#btnSkip").css("display","none");
			}
			
			jQuery("#btnSkip").click(function(){
				
				if (arr.indexOf("hotel")!=-1)
				{
					window.location.href="setup_process.php?step=4&go=hotel&skip_count="+jQuery("#skip_count").val();
				}
				else
					window.location.href="setup_process.php?step=4&go=retail&skip_count="+jQuery("#skip_count").val();
			});*/
			
			jQuery("#btnContinueCustom").click(function(){
				window.location.href="setup_rest_menu_articles_page.php?step=4";
			});
			
			return;
			
		}
		if (jQuery("#hotel").val()=="yes" && (jQuery("#go").val()=="" || jQuery("#go").val()=="hotel"))
		{
			/*if (custom_text=="")
			{
				custom_text="Hotel ";
			}*/
			
			/*jQuery("#skip_count").val(parseInt(jQuery("#skip_count").val())-1);
			if (jQuery("#skip_count").val()==0)
			{
				jQuery("#btnSkip").css("display","none");
			}
			
			jQuery("#btnSkip").click(function(){
				window.location.href="setup_process.php?step=4&go=retail&skip_count="+jQuery("#skip_count").val();
			});*/
			
			jQuery("#btnContinueCustom").click(function(){
				window.location.href="setup_hotel_guarantees_page.php?step=4";
			});
			//jQuery("#custom_text").html(custom_text);
			return;
			//else
				//custom_text+="and Hotel ";
		}
		if (jQuery("#retail").val()=="yes" && (jQuery("#go").val()=="" || jQuery("#go").val()=="retail"))
		{
			/*if (custom_text=="")
			{
				custom_text="Retail ";
			}*/
			/*jQuery("#skip_count").val(parseInt(jQuery("#skip_count").val())-1);
			if (jQuery("#skip_count").val()==0)
			{
				jQuery("#btnSkip").css("display","none");
			}
			jQuery("#btnSkip").click(function(){
				
				window.location.href="setup_process.php?step=4&skip_count="+jQuery("#skip_count").val();
			});*/
			
			jQuery("#btnContinueCustom").click(function(){				
				window.location.href="setup_retail_sales_articles.php?step=4";
			});
			//else
				//custom_text+="and Retail";
				//jQuery("#custom_text").html(custom_text);
		return;
		}
		
		
		
});
</script>
<!--
<script>
	jQuery(document).ready(function(){
		
		jQuery(".leftmenu").find("ul").find("li").each(function(){
			jQuery(this).css("display","none");
			
			
		//Initial
			if (jQuery("#step").val()=="1")
			{
				if (jQuery.trim(jQuery(this).find("a").html())=="Profile")
				{
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					jQuery(this).parent().parent().find("a").click();
					jQuery("#initial").click();
				}
			}
			
			//Profile
			if (jQuery("#step").val()=="2")
			{
				if (jQuery.trim(jQuery(this).find("a").html())=="Profile")
				{
					var href=jQuery(this).find("a").attr("href");
					jQuery(this).find("a").attr("href",href+"?step=2");
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					jQuery(this).parent().parent().find("a").click();
					jQuery("#profile").click();
					
				}
			}
			
			//Employee
			if (jQuery("#step").val()=="3")
			{
				
				if (jQuery.trim(jQuery(this).find("a").html())=="Profile" )
				{
					var href=jQuery(this).find("a").attr("href");
					jQuery(this).find("a").attr("href",href+"?step=2");
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					jQuery(this).parent().parent().find("a").click();
					//jQuery("#employees").click();
				}
				
				if (jQuery.trim(jQuery(this).find("a").html())=="Employees" )
				{
					var href=jQuery(this).find("a").attr("href");
					jQuery(this).find("a").attr("href",href+"?step=3");
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					
					jQuery("#employees").click();
				}
			}
			
			//Operations
			if (jQuery("#step").val()=="4")
			{
				
				if (jQuery.trim(jQuery(this).find("a").html())=="Profile" )
				{
					var href=jQuery(this).find("a").attr("href");
					jQuery(this).find("a").attr("href",href+"?step=2");
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					jQuery(this).parent().parent().find("a").click();
					
				}
				
				if (jQuery.trim(jQuery(this).find("a").html())=="Employees" )
				{
					var href=jQuery(this).find("a").attr("href");
					jQuery(this).find("a").attr("href",href+"?step=3");
					jQuery(this).parent().parent().css("display","block");
					jQuery(this).css("display","block");
					jQuery("#operations").click();
				}
				
				if ((jQuery.trim(jQuery(this).find("a").html())=="Restaurant" || jQuery(this).parent().parent().find("a").html()=="Restaurant") && jQuery.trim(jQuery("#restaurant").val())=="yes")
				{
					if(jQuery.trim(jQuery(this).find("a").html())=="Restaurant" && jQuery.trim(jQuery("#restaurant").val())=="yes")
					{
						jQuery(this).find("ul").css("display","none");
					}
					else
					{
						var href=jQuery(this).find("a").attr("href");
						jQuery(this).find("a").attr("href",href+"?step=4");
						jQuery(this).parent().parent().css("display","block");
						jQuery(this).css("display","block");
					}
				
				}
				
				if ((jQuery.trim(jQuery(this).find("a").html())=="Hotel" || jQuery(this).parent().parent().find("a").html()=="Hotel") && jQuery.trim(jQuery("#hotel").val())=="yes" )
				{
					if(jQuery.trim(jQuery(this).find("a").html())=="Hotel" && jQuery.trim(jQuery("#hotel").val())=="yes")
					{
						jQuery(this).find("ul").css("display","none");
					}
					else
					{
						var href=jQuery(this).find("a").attr("href");
						jQuery(this).find("a").attr("href",href+"?step=4");
						jQuery(this).parent().parent().css("display","block");
						jQuery(this).css("display","block");
					}
					
			
				}
				
				if ((jQuery.trim(jQuery(this).find("a").html())=="Retail" || jQuery(this).parent().parent().find("a").html()=="Retail") && jQuery.trim(jQuery("#retail").val())=="yes" )
				{
					if(jQuery.trim(jQuery(this).find("a").html())=="Retail" && jQuery.trim(jQuery("#retail").val())=="yes")
					{
						jQuery(this).find("ul").css("display","none");
					}
					else
					{
						var href=jQuery(this).find("a").attr("href");
						jQuery(this).find("a").attr("href",href+"?step=3");
						jQuery(this).parent().parent().css("display","block");
						jQuery(this).css("display","block");
					}
					
				
				}
			}
		});
	})
	</script>
-->
<?php 
		

?>
</body>
</html>