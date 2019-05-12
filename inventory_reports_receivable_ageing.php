<?php
/*
*  @created Ionut Irofte - juniorionut @ elance
*  @version $Id: backoffice_reports_receivable_ageing.php , v 1.0 7:54 PM 9/3/2014 juni $
*  -> [REQ_014  - 03.09.2014]
		->  BP - Receivables Settlements
			-> Do not use absolute path to redirect to report (server might change adress)!!!
*/
include_once 'includes/session.php';
include_once("config/accessConfig.php");
include_once 'includes/jcustom.php';
//include_once("../../internalaccess/connectdb.php");
//ob_start("ob_gzhandler");

//->juni [REQ_014]  ->  03.09.2014 -> get proper url path (without the last part) (using absolute path is not indicated)
$cPath = $_SERVER['REQUEST_URI'];
$end = end((explode('/', $_SERVER['REQUEST_URI'])));
$path = str_replace($end,"",$cPath);
//<-juni [REQ_014]

$orderField = $_GET['orderfield'];
$orderType = $_GET['type'];
$orderBy = ' ORDER BY ';
if(!empty($orderField)){
	$orderBy .= $orderField;
}else{
	$orderBy .= " company_name";
}
if(!empty($orderType)){
	$orderBy .=	' '.$orderType;
}else{
	$orderBy .= ' ASC';
}
//
$detail_level = 'report_summary';//default 
if(isset($_GET['detail_level'])&& $_GET['detail_level'] != '' )
	$detail_level = $_GET['detail_level'];



function GetLocationTimeFromServer($intLocationID, $servertime){
	/*$jsonurl = API ."API2/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);*/

	$jsonurl = "api/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);
	$json = @file_get_contents($jsonurl,0,null,null);  
	$datetimenow= json_decode($json);
	$datetimenowk1 = $datetimenow->servertolocation_datetime;    
	$ldatetitme = date('Y-m-d H:i:s',strtotime($datetimenowk1));
	return $ldatetitme;
}
function getLocationName($locid) 
{
    $sql = "SELECT name FROM locations where id=" . $locid;
    $rs = mysql_query($sql);
    $d = mysql_fetch_array($rs);
    $nameloc = $d["name"];
    return $nameloc;
}


$backofficeHead       = "active";
$backofficeDropDown   = "display:block;";
$backofficeMenu12      = "active";
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
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript">
function savePDF() {
	show_page = jQuery("#report_select1").val();  
	hiddenURL = jQuery("#hiddenURL").val();
	pathURL = hiddenURL+'/inventory_reports_pdf.php';
	order_field = jQuery("#orderfield").val(); 
	order_type = jQuery("#ordertype").val(); 
	detail_level = jQuery("#detail_level").val(); 
	console.log(show_page);
	document.location = 'http://'+pathURL+'?orderfield='+order_field+'&type='+order_type+"&detail_level="+detail_level+'&download='+show_page;
}

var delayTimer;
function sendMail(){
	jQuery("#content-modal").html('');
	clearTimeout(delayTimer);
	delayTimer = setTimeout(function() {
		var _show_page = jQuery("#report_select1").val();       
		var _hiddenURL = jQuery("#hiddenURL").val();
		var _pathURL = _hiddenURL+'/inventory_reports_pdf_mailer.php';
		var _order_field = jQuery("#orderfield").val(); 
		var _order_type = jQuery("#ordertype").val(); 
		var _detail_level = jQuery("#detail_level").val(); 
		var _mailAddress = jQuery("#mailaddress").val();
		var _destName = jQuery("#destname").val();

		_mailAddress = jQuery.trim(_mailAddress);
		var mailValid = mailValidate(_mailAddress);
		if(mailValid === true)	{
			var url  = location.protocol +'//'+_pathURL+'?orderfield='+_order_field+'&type='+_order_type+"&detail_level="+_detail_level+'&download='+_show_page;
			//console.log(url);
			jQuery.ajax({
			type: "GET",
				url: url,
				dataType:"JSON",
				data: ({
					orderfield 	: _order_field,
					detail_level 	: _detail_level,
					type : _order_type,
					download : _show_page,
					mailaddress : _mailAddress,
					destname : _destName
				}),
				cache: false,
				success: function(data)	{
					jQuery("#loading-header").hide();
					jAlert(data,'Alert');
					jQuery('#mailaddress').val('');
					jQuery('#mailmodal div.modal-footer button.btn-default').trigger('click');
					
					// jQuery("#content-modal").html(data);
				}
			});
		} else	{
			// jQuery("#content-modal").html();
			jAlert("Invalid Email Address",'Alert');
		}
	}, 1000); 
}

jQuery(document).ready(function() {
	var emptyval = ' ';
	jQuery('#mailmodal').on('show.bs.modal', function() {
		jQuery('#content-modal').html('');
		jQuery("#mailaddress").val(emptyval);
	});
	jQuery('#mailmodal').on('hide.bs.modal', function() {
		jQuery('#content-modal').html('');
		jQuery("#mailaddress").val(emptyval);
	});
	jQuery("#report_select1").change(function(){ 
		urlname = jQuery('#hiddenURL').val();
		show_page = jQuery("#report_select1").val(); 
		document.location = '//'+urlname+'/inventory_reports_'+show_page+'.php';
	}); 
});
</script>
<style>
	#report_select1{
		margin-bottom: 1px !important;
	}
</style>
</head>

<body>
<?php
		$detail_level=$_GET['detail_level'];
		
		$arr_details_level=array(
			"report_summary"=>"Summary",
			"report_summary_balance"=>"Summary only with balance",
			"report_detail"=>"Details",
			"report_all_detail"=>"Details with settlements"
		);
		
		if($detail_level!="")
		{
			$str_detail_level=" - ".$arr_details_level[$detail_level];
		}
		else
		{
			$str_detail_level="- Summary";
		}
		
		
		$location = $_SESSION['loc_name'];
		$date = GetLocationTimeFromServer($_SESSION['loc'],'');
		$filename = $location .' - Receivable Ageing '.$str_detail_level.' Report - '. date('Y-m-d',strtotime($date));
	?>
<div class="modal fade" id="mailmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Send Report</h4>
			</div>
			<div class="modal-body">
				<div id="content-modal"></div>
				<form name="fsendmail" method="POST" action="">
					<p>Email address</p>
					<input type="text" name="mailaddress" id="mailaddress" style="width:98%;" value="">
					<p>Name</p>
					<input type="text" name="destname" id="destname" style="width:98%;" value="<?=$filename;?>">
				</form>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" onClick="sendMail();" class="btn btn-primary">Send Mail</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<div class="mainwrapper">
	<?php include_once 'includes/header.php';?>
	<div class="leftpanel">
		<?php include_once 'includes/left_menu.php';?>
	</div><!-- leftpanel -->
	<div class="rightpanel">
		<ul class="breadcrumbs">
			<li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Inventory</li>
            <li><span class="separator"></span></li>
            <li>Reports</li>
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
				<a href="javascript:void(0);" onClick="savePDF();"><button class="btn btn-success btn-mini">Save</button> </a> &nbsp;
				<a data-toggle="modal" href="#mailmodal"><button class="btn btn-success btn-mini">Email</button> </a> &nbsp;
				<a href="#" onClick="printDiv()"><button class="btn btn-success btn-mini">Print</button></a>&nbsp;
				<select id="report_select1" class="buttonReport">
				  <option value="" selected>Select a report</option>                  
<!--                    <option value="item">Items</option>
                    <option value="item_group">Items by Group</option>
                    <option value="item_stroom">Items by Storeroom</option>
                    <option value="inv_date">Inventory by Date</option>
                    <option value="inv_item">Inventory by Item</option>
                    <option value="inv_stroom">Inventory by Storeroom</option>
					 <option value="inv_mul_stroom">Inventory by Multiple Storeroom</option>
                    <option value="inv_emp">Inventory by Employee</option>
                    <option value="inv_vendor">Inventory by Vendor</option>
                    <option value="order_group">Order by Group</option>-->
                    <option value="line">Line Check</option>
                    <option value="receivable_ageing" selected="selected">Receivable Ageing</option>
					<!--<option value="low_invertory">Low Invertory</option>-->
				</select>
				<input type="hidden" id="hiddenURL" value="<?=$_SERVER['SERVER_NAME'].$path;/*//REQ_014 =$_SERVER['SERVER_NAME'].'/panels/businesspanel';*/?>">
			</form>
			<div class="pageicon"><span class="iconfa-book"></span></div>
			<div class="pagetitle">
				<h5>Display All Inventory Reports Information</h5>
				<h1>Inventory Reports</h1>
			</div>
		</div><!--pageheader-->
		<div class="maincontent">
			<div class="maincontentinner">   
				<div class="report-header">
					<span class="tabname"> <?=$_SESSION['user_full_name'];?> - <?=$_SESSION["user"];?></span> <div class="tabtitle">Receivable Ageing</div><br/>
					<span> <?=$_SESSION["loc_name"]?> (ID#: <?=$_SESSION["loc"];?>)</span>
					<span class="tabdate"> 
						<?php 
							$date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i:s"));
							$arrDate['date'] = date("Y-m-d", strtotime($date));
							$arrDate['time'] = date("H:i:s", strtotime($date));
							echo $arrDate['date'] .' '. $arrDate['time'];
						?>
					</span>
				</div>         
				<!--<div class="report-header-el" id="report-header-print">              		
					<span class="tablocation">  <?=$_SESSION["loc_name"]?>  (ID#: <?=$_SESSION["loc"];?>)</span> <div class="tabtitle">Receivable Ageing</div><br/>
					<span class="tabname"> <?=$_SESSION['user_full_name'];?> - <?=$_SESSION["user"];?></span><span class="tabdate"> <?=date("m/d/Y");?> - <?=date("H:i");?></span>
				</div>-->               	   	       	
				<div class="widget">
					<div class="widget-sort">
						<select id="detail_level" name="detail_level"><!-- juni [req REQ_014] - 04.09.2014 - add new filter -->
							<option value="report_summary" <? if($_GET['detail_level']=='report_summary') echo "Selected"; else echo ""; ?>>Summary</option>
							<option value="report_summary_balance" <? if($_GET['detail_level']=='report_summary_balance') echo "Selected"; else echo ""; ?>>Summary only with balance</option>
							<option value="report_detail" <? if($_GET['detail_level']=='report_detail') echo "Selected"; else echo ""; ?>>Details</option>
							<option value="report_all_detail" <? if($_GET['detail_level']=='report_all_detail') echo "Selected"; else echo ""; ?>>Details with settlements</option>
						</select>   					
						<select id="orderfield" name="orderfield">
							<option value="">Sort</option>
							<option value="company_name" <? if($_GET['orderfield']=='company_name') echo "Selected"; else echo ""; ?>>Company</option>
						</select>        
						<select id="ordertype" name="ordertype">
							<option value="ASC" <? if($_GET['type']=='ASC') echo "Selected"; else echo ""; ?>>ASC</option>
							<option value="DESC" <? if($_GET['type']=='DESC') echo "Selected"; else echo ""; ?>>DESC</option>           	                    	
						</select>     
					</div>                               	
					<h4 class="widgettitle">&nbsp;</h4>
				</div>
				<div id='backoffice_report'>
					<div class="widgetcontent">
						<div id="report-header-print" class="report-header-print" style="display: none;">
							<span class="tablocation"><?=$_SESSION['user_full_name'];?> - <?=$_SESSION["user"];?></span>
							<div class="tabtitle">Receivable Ageing</div>
							<br/>
							<span class="tabname"><?php echo getLocationName($_SESSION["loc"]); ?> (ID#: <?=$_SESSION["loc"];?>)</span> 
							<span class="tabdate"> 
								<?php 
									$date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i:s"));
									$arrDate['date'] = date("Y-m-d", strtotime($date));
									$arrDate['time'] = date("H:i:s", strtotime($date));
									echo $arrDate['date'] .' '. $arrDate['time'];
								?>
							</span>
						</div><!--report-header-print-->
						<?php 
						//report_summary - this will  show each company and there aged balances.
						//report_summary_balance - this will  not show accounts with zero  balance
						//report_detail - this will show all of the  active records that make up the  balance
						//report_all_detail - this will print for  each company all active and settled  records.
						
						if ($detail_level=='report_summary' || $detail_level=='report_summary_balance' ) 
						{
							include 'inventory_reports_receivable_ageing_summary.php';
						}
						if ($detail_level=='report_detail' || $detail_level=='report_all_detail' ) 
						{
							include 'inventory_reports_receivable_ageing_detail.php';						
						}
						?>
						
					</div><!--widgetcontent-->
				</div>
				<?php include_once 'includes/footer.php';?>
				<!--footer-->
			</div><!--maincontentinner-->
		</div><!--maincontent-->
	</div><!--rightpanel-->
</div><!--mainwrapper-->
<script>
function printDiv() {

	var page_title = '<?php echo $filename; ?>';
	jQuery("#backoffice_report").printElement({
		pageTitle: page_title,
		overrideElementCSS: [
			'css/custom.report.css',
			{ href:'css/print_inventory_reports.css', media:'print' }
		]
	});

}
</script>
</body>
</html>   