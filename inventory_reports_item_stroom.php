<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: backoffice_reports_item_stroom.php , v 1.0 7:54 PM 9/3/2014 juni $
*  -> [REQ_014  - 03.09.2014]
		->  BP - Receivables Settlements
			-> Do not use absolute path to redirect to report (server might change adress)!!!
*/
include_once 'includes/session.php';
include_once("config/accessConfig.php");

//->juni [REQ_014]  ->  03.09.2014 -> get proper url path (without the last part) (using absolute path is not indicated)
$cPath = $_SERVER['REQUEST_URI'];
$end = end((explode('/', $_SERVER['REQUEST_URI'])));
$path = str_replace($end,"",$cPath);
//<-juni [REQ_014]

$orderField = $_GET['orderfield'];
//$orderType = $_GET['type'];
$stroom = $_GET['stroom'];
$orderBy = '';

if(!empty($orderField)){
	$orderBy = $orderField. ' ASC' ;
}else{
	$orderBy = " p1 ASC";
}

//if(!empty($orderType)){
//	$orderBy .=	' '.$orderType;
//}else{
//	$orderBy .= ' ASC';
//}

if(!empty($stroom)){
    $s = mysql_real_escape_string($_GET['stroom']);
    $stroom = ' AND lis.storeroom_id=' . $s;
}else{
    $stroom = '';
}

$query1 = "(SELECT lii.id, ii.description as description, ig.description as `itemgroup`,stroom_id, lii.priority,lii.type,iiu.unit_type,lis.priority as p1
            FROM location_inventory_storeroom_items lisi
            INNER JOIN location_inventory_items lii ON lisi.inv_item_id=lii.id
            INNER JOIN location_inventory_storerooms lis ON lisi.storeroom_id=lis.storeroom_id
            INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
            INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
            WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $stroom . ")
            UNION
            (SELECT lii.id,lii.local_item_desc as description, ig.description as `group`,stroom_id, lii.priority,lii.type,iiu.unit_type,lis.priority as p1
            FROM location_inventory_storeroom_items lisi
            INNER JOIN location_inventory_items lii ON lisi.inv_item_id=lii.id
            INNER JOIN location_inventory_storerooms lis ON lisi.storeroom_id=lis.storeroom_id
            INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
            WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $stroom . ")
            ORDER BY ".$orderBy;
			
$result1 = mysql_query($query1) or die(mysql_error());

function getLocationName($locid) 
{
    $sql = "SELECT name FROM locations where id=" . $locid;
    $rs = mysql_query($sql);
    $d = mysql_fetch_array($rs);
    $nameloc = $d["name"];
    return $nameloc;
}
function GetLocationTimeFromServer($intLocationID, $servertime,$path){
	/*$jsonurl = API ."API2/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);*/

	$jsonurl = API.$path."/api/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);
	$json = @file_get_contents($jsonurl,0,null,null);  
	$datetimenow= json_decode($json);
	$datetimenowk1 = $datetimenow->servertolocation_datetime;    
	$ldatetitme = date('Y-m-d H:i:s',strtotime($datetimenowk1));
	return $ldatetitme;
}

$backofficeDropDown = "display:block;";
$backofficeHead 		 = "active";
$inventoryHead       = "active";
$inventoryDropDown   = "display:block;";
$backoffice_inv_reports      = "active";
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
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.printElement.min.js"></script>
<script type="text/javascript" src="js/custom.report.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript">
function savePDF()
{
	show_page = jQuery("#report_select1").val();       
 	hiddenURL = jQuery("#hiddenURL").val();
 	pathURL = hiddenURL+'/inventory_reports_pdf.php';
 	stroom_field = jQuery("#stroom").val(); 
 	document.location = 'http://'+pathURL+'?stroom='+stroom_field+'&download='+show_page;
}

var delayTimer;

function sendMail()
{
	jQuery("#content-modal").html('');
 	clearTimeout(delayTimer);
 	delayTimer = setTimeout(function() 
	{
		var _show_page = jQuery("#report_select1").val();       
	 	var _hiddenURL = jQuery("#hiddenURL").val();
	 	var _pathURL = _hiddenURL+'/inventory_reports_pdf_mailer.php';
		var _stroom_field = jQuery("#stroom").val(); 
	 	var _mailAddress = jQuery("#mailaddress").val();
   		var _destName = jQuery("#destname").val();
   
   		_mailAddress = jQuery.trim(_mailAddress);
       	var mailValid = mailValidate(_mailAddress);
    
    	if(mailValid === true)
		{
    		var url  = location.protocol +'//'+_pathURL+'?stroom='+_stroom_field+'&download='+_show_page;
			console.log(url);
			jQuery.ajax(
			{
				type: "GET",
				url: url,
				dataType:"JSON",
				data: ({
					stroom 	: _stroom_field,
					download : _show_page,
					mailaddress : _mailAddress,
					destname : _destName
				 }),
				cache: false,
				success: function(data)
				{
					jQuery("#loading-header").hide();
					jAlert(data,'Alert');
					jQuery('#mailaddress').val('');
					
					jQuery('#mailmodal div.modal-footer button.btn-default').trigger('click');
				}
			});
		}
		else
		{
			jQuery("#content-modal").html("Invalid Email Address");
		}
    }, 1000); 
}

jQuery(document).ready(function()
{
	var emptyval = ' ';
	jQuery('#mailmodal').on('show.bs.modal', function() 
	{
 		jQuery('#content-modal').html('');
 		jQuery("#mailaddress").val(emptyval);
	});
	jQuery('#mailmodal').on('hide.bs.modal', function() 
	{
 		jQuery('#content-modal').html('');
 		jQuery("#mailaddress").val(emptyval);
	});
	jQuery("#report_select1").change(function(){ 
			 	urlname = jQuery('#hiddenURL').val();
			 	
			        show_page = jQuery("#report_select1").val(); 
							document.location = '//'+urlname+'/inventory_reports_'+show_page+'.php';
			}); 
	var loc = window.location;
    var pathurl = loc.protocol + '//' + loc.host + loc.pathname;
	jQuery("#stroom").change(function()
	{
		stroom_field = jQuery("#stroom").val();
		order_field = jQuery("#orderfield").val();
		if(stroom_field != '' || order_field != '')
		{
			document.location = pathurl+'?stroom='+stroom_field+'&orderfield='+order_field;
		}
	}); 
	jQuery("#orderfield").change(function()
	{
		stroom_field = jQuery("#stroom").val();
		order_field = jQuery("#orderfield").val();
		if(stroom_field != '' || order_field != '')
		{
			document.location = pathurl+'?stroom='+stroom_field+'&orderfield='+order_field;
		}
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
		$location = $_SESSION['loc_name'];
		$date = GetLocationTimeFromServer($_SESSION['loc'],'',$path);
		$filename = ' - Items by Storeroom Report - '. date('Y-m-d H:i:s',strtotime($date));
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
          	<!--<textarea name="addresslist" rows="2" style="width:100%;-moz-resize: none; -webkit-resize: none; resize: none;">	</textarea>-->
          	<p>Email address</p>
          	<input type="text" name="mailaddress" id="mailaddress" style="width:75%;" value="">
          	<p>Name</p>
          	<input type="text" name="destname" id="destname" style="width:75%;" value="<?=getLocationName($_SESSION['loc']).' '.$filename;?>">
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
    
    <?php include_once 'require/top.php';?>
    
    <div class="leftpanel">
        <?php include_once 'require/left_nav.php';?>
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
		        	<a href="javascript:void(0);" onClick="printDiv()"><button class="btn btn-success btn-mini">Print</button> </a> &nbsp;            	
                <select id="report_select1" class="buttonReport">
                    <option value="item">Items</option>
                    <option value="item_group">Items by Group</option>
                    <option value="item_stroom" selected>Items by Storeroom</option>
                    <option value="date">Inventory by Date</option>
                    <option value="by_item">Inventory by Item</option>
                    <option value="stroom">Inventory by Storeroom</option>
					<option value="mul_stroom">Inventory by Multiple Storeroom</option>
                    <option value="emp">Inventory by Employee</option>
                    <option value="vendor">Inventory by Vendor</option>
					<option value="low_invertory">Low Invertory</option>
                </select>
                  <input type="hidden" id="hiddenURL" value="<?=$_SERVER['SERVER_NAME'].$path;/*//REQ_014 =$_SERVER['SERVER_NAME'].'/panels/businesspanel';*/?>">
            </form>
            <div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Display All Inventory Reports Information</h5>
                <h1>Reports</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">   
              	<div class="report-header">
              		<!--<span class="tabname"> <?=getLocationName($_SESSION["loc"]);?> - <?=$_SESSION["user"];?></span><span class="tabdate"> <?=date("m/d/Y");?> - <?=date("H:i");?></span><div class="tabtitle">Items by Storeroom</div><br/>-->
              		<span> <?php echo getLocationName($_SESSION["loc"]); ?> (ID#: <?=$_SESSION["loc"];?>)</span><span class="tabdate"> 
                    <?php
						 $date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i:s"),$path);
						 $arrDate['date'] = date("Y-m-d", strtotime($date));
						 $arrDate['time'] = date("H:i:s", strtotime($date));
						 echo $arrDate['date'] .' '. $arrDate['time'];
					?>
                    </span>
              	</div>         
              	<div class="report-header-el" id="report-header-print">              		
              		<div class="tabtitle">Items by Storeroom </div><br/>
              		<span class="tabname"><?php echo getLocationName($_SESSION["loc"]); ?> (ID#: <?=$_SESSION["loc"];?>)</span> 
					<span class="tabdate"> 
						<?php 
							$date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i"),$path);
							$arrDate['date'] = date("Y-m-d", strtotime($date));
							$arrDate['time'] = date("H:i:s", strtotime($date));
							echo $arrDate['date'] .' '. $arrDate['time'];
						?>
					</span>
              	</div>                	   	       	
                <div class="widget">
					<div class="widget-sort">
                    	<select id="orderfield" name="orderfield">
	                    	<option value=""> - - - Sort - - - </option>
	                    	<option value="stroom_id" <? if($_GET['orderfield']=='stroom_id') echo "Selected"; else echo ""; ?>>Storroom</option>
	                    	<option value="itemgroup" <? if($_GET['orderfield']=='itemgroup') echo "Selected"; else echo ""; ?>>Group</option>
	                    	<option value="description" <? if($_GET['orderfield']=='description') echo "Selected"; else echo ""; ?>>Item</option>
							<option value="type" <? if($_GET['orderfield']=='type') echo "Selected"; else echo " "; ?>>Type</option>
	                    	<option value="priority" <? if($_GET['orderfield']=='priority') echo "Selected"; else echo ""; ?>>Priority</option>
	                    </select>
                        <?php
							$query2 = "SELECT storeroom_id as id, stroom_id as name FROM location_inventory_storerooms WHERE location_id = " . $_SESSION['loc'];
							$result2 = mysql_query($query2) or die(mysql_error());
						?>
                        <select name="stroom" id="stroom">
                        	<option value='0' <?php if($_GET['stroom'] == '0') echo "Selected"; else echo ""; ?>>Select Storeroom</option>
                        	<?php
                        		while($row2 = mysql_fetch_array($result2)){ ?>
                            <option value='<?=$row2['id']?>' <?php if($row2['id'] == $_GET['stroom']){ echo 'Selected'; }?>><?=$row2['name']?></option>
                            <?php } ?>
                    	</select>
	                </div>                               	
                    <h4 class="widgettitle">&nbsp;</h4>
					</div>
					<div id='backoffice_report'>
						<div class="widgetcontent">
								 <div id="report-header-print" class="report-header-print" style="display: none;">
									<span class="tablocation"><?=getLocationName($_SESSION["loc"]);?> - <?=$_SESSION["user"];?></span>
									<div class="tabtitle">Items by Storeroom</div>
									<br/>
									<span class="tabname"><?php echo getLocationName($_SESSION["loc"]); ?> (ID#: <?=$_SESSION["loc"];?>)</span> 
									<span class="tabdate"> 
										<?php 
											$date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i"),$path);
											$arrDate['date'] = date("Y-m-d", strtotime($date));
											$arrDate['time'] = date("H:i:s", strtotime($date));
											echo $arrDate['date'] .' '. $arrDate['time'];
										?>
									</span>
								</div><!--report-header-print-->
											<table id="dyntabletable" class="table table-bordered responsive dataTable" cellpadding="0" cellspacing="0">
											<colgroup>
												<col class="con0">
												<col class="con1">
												<col class="con0">
												<col class="con1">
												<col class="con0">
												<col class="con1">
												<col class="con0">
												<col class="con1">
												<col class="con0">												
											</colgroup>
											<thead>
												<tr role="row">
                                               		<th class="head0">Storeroom</th>
													<th class="head1">Group</th>
                                                    <th class="head0">Item</th>
                                                    <th class="head1">Type</th>
                                                    <th class="head0" style="text-align: center;" >Priority</th>											
												</tr>
											</thead>		
												<tbody role="alert" aria-live="polite" aria-relevant="all">
													<?php
														$i=0;
														while ($row1 = mysql_fetch_assoc($result1)) 
														{ 
															if($i%2 == 0){$class = " odd";}
															else{$class = " even";}
                                							$i++;
													?>
													<tr class="gradeX <?=$class;?>">
                                                   		<td><?php if($curr_stroom != $row1['stroom_id']){$curr_stroom = $row1['stroom_id'];echo $row1['stroom_id'];}?></td>
                                                        <td><?=$row1['itemgroup'];?></td>
                                                        <td><?=$row1['description'];?></td>
                                                        <td><?=ucfirst($row1['type']);?></td>
                                                        <td style="text-align: center;" ><?=$row1['priority'];?></td>
													</tr>																		
													<? 	}?>															
												</tbody>
											</table>

                    </div><!--widgetcontent-->
                </div>
				
			      <?php include_once 'require/footer.php';?>
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