<?php 
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: backoffice_reports.php , v 1.0 7:54 PM 9/3/2014 juni $
*  -> [REQ_014  - 03.09.2014]
		->  BP - Receivables Settlements
			-> Do not use absolute path to redirect to report (server might change adress)!!!
*/
require_once 'require/security.php';
include 'config/accessConfig.php';

//->juni [REQ_014]  ->  03.09.2014 -> get proper url path (without the last part) (using absolute path is not indicated)
$cPath = $_SERVER['REQUEST_URI'];
$end = end((explode('/', $_SERVER['REQUEST_URI'])));
$path = str_replace($end,"",$cPath);
//<-juni [REQ_014]
$backofficeDropDown      = "display:block;";
$backofficeHead 	     = "active";
$inventoryHead           = "active";
$inventoryDropDown       = "display:block;";
$backoffice_inv_reports  = "active";

//$r = $_GET['r'];

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/custom.report.css" type="text/css" />

<link rel="stylesheet" href="css/responsive-tables.css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.printElement.min.js"></script>
<script type="text/javascript" src="js/custom.report.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript">
    jQuery(document).ready(function(){
		    jQuery("#report_select1").change(function(){ 
			 	urlname = jQuery('#hiddenURL').val();
				show_page = jQuery("#report_select1").val(); 
				document.location = '//'+urlname+'/inventory_reports_'+show_page+'.php';
		});     	
 		}); 
 		
     function selectPrint() {
        alert("Please select a report to display first.");
        document.getElementById("report_select").focus();
    }
</script>
</head>

<body>

<div class="mainwrapper">
    
    <?php require_once('require/top.php');?>
    <?php require_once('require/left_nav.php');?>
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li></span> Inventory<span class="separator"></span> Reports</li>
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
            <form action="" method="post" class="searchbar" onSubmit="return false;">
  				<select id="report_select1" class="buttonReport">
                    <option value="">Select a report</option>                  
                    <option value="item">Items</option>
                    <option value="item_group">Items by Group</option>
                    <option value="item_stroom">Items by Storeroom</option>
                    <option value="date">Inventory by Date</option>
                    <option value="by_item">Inventory by Item</option>
                    <option value="stroom">Inventory by Storeroom</option>
					<option value="mul_stroom">Inventory by Multiple Storeroom</option>
                    <option value="emp">Inventory by Employee</option>
                    <option value="vendor">Inventory by Vendor</option>
                   
					<option value="low_invertory">Low Inventory</option>
                </select>
            <input type="hidden" id="hiddenURL" value="<?=$_SERVER['SERVER_NAME'].$path;/* //REQ_014 =$_SERVER['SERVER_NAME'].'/panels/businesspanel';*/?>">
            </form>
            <div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Display All Inventory Reports Information</h5>
                <h1>Reports</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
            	<div class="widget">
					<h4 class="widgettitle">&nbsp;</h4>
                    <div class="widgetcontent">
						<div style="font-size:20px;font-weight:bold; text-align:center;height:25px;overflow:auto;">Please select a report.</div>
                    </div>
                    <!--widgetcontent-->
                </div>
            	
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
