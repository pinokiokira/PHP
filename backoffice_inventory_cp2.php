<?php 
include_once 'includes/session.php';
include_once("config/accessConfig.php");
include_once 'includes/jcustom.php';
//var_dump($_SESSION['user_full_name']);
$backofficeDropDown = "display:block;";
$backofficeHead 		 = "active";
$inventoryHead       = "active";
$inventoryDropDown   = "display:block;";
$inventoryMenu2      = "active";
$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];


print_r($empmaster_id);
//print_r($emp_id);
//exit;

$group_id  = '';
if (isset($_GET['group_id'])&&trim($_GET['group_id'])!='') {
	$group_id = $_GET['group_id'];	
}
if ($_POST['count_submit'] == 'submitted') {
    $item = mysql_real_escape_string($_POST['item']);
    $date = date('Y-m-d');
    $time = date('H:i:s');
    for ($i = 0; $i < count($_POST['count']); $i++) {
        if ($_POST['count'][$i] != '' && $_POST['storeroom'][$i]>0) {
            $count = mysql_real_escape_string($_POST['count'][$i]);
            $unit = mysql_real_escape_string($_POST['unit'][$i]);
            $storeroom = mysql_real_escape_string($_POST['storeroom'][$i]);
            $query = "SELECT id
                      FROM location_inventory_storeroom_items
                      WHERE location_id=" . $_SESSION['loc'] . " AND storeroom_id='".$storeroom."' AND inv_item_id=$item";
            $result = mysql_query($query) or die($query.'<br>'.mysql_error());
            if (!mysql_num_rows($result) > 0) {
                $query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $_SESSION['loc'] . ",
                                storeroom_id='".$storeroom."',
                                inv_item_id='".$item."'";
                $result = mysql_query($query) or die(mysql_error());
            }
			
			if($unit>0){
				$get_item = "SELECT inv_item_id,type,local_unit_type from location_inventory_items WHERE id = '".$item."'";
				$res_item = mysql_query($get_item) or die($get_item.'<br>'.mysql_error());
				if($res_item && mysql_num_rows($res_item)>0){
					$row_item = mysql_fetch_assoc($res_item);
					if(strtotime($row_item['type'])=='global' && $row_item['inv_item_id']>0){
						$update = "update inventory_items SET unit_type = '".$unit."' WHERE id = '".$row_item['inv_item_id']."' AND (unit_type='' OR unit_type IS NULL OR unit_type=0)";
					}else{
						$update = "update location_inventory_items SET local_unit_type = '".$unit."' WHERE id = '".$item."' AND (local_unit_type='' OR local_unit_type IS NULL OR local_unit_type=0)";
					}
					
					$res_up= mysql_query($update) or die($update.'<br>'.mysql_error());				
				}
			}
			
            $query = "INSERT INTO location_inventory_counts SET
                            location_id=" . $_SESSION['loc'] . ",
                            storeroom_id='".$storeroom."',
                            inv_item_id='".$item."',
                            Type='Count',
                            date_counted='".$date."',
                            time_counted='".$time."',
                            quantity='".$count."',
                            unit_type='".$unit."',
							created_on='BusinessPanel',
							created_datetime=NOW(),
							created_by='".$empmaster_id."',
                            employee_id=" . $empmaster_id;
                            print_r($query);
            //$result = mysql_query($query) or die('Insert 1'.mysql_error());
                            exit;
        }
    }
	$group_id = $_REQUEST['group_id'];
	$item_id = $_REQUEST['item'];
	$page = $_REQUEST['page'];
	header('location:backoffice_inventory_cp2.php?market='.$_POST['market'].'&group_id='.$_POST['group_id'].'&vendor='.$_POST['vendor'].'&item_id='.$item_id.'&page='.$page.'&t');
    //file_get_contents(API.'api/backoffice_inventory.php?items=' . $item);
}
$search = array('Search Groups', 'grp_tbl', 0); //array with values to append into js for searching
//Set variables to append into query when search is submitted
//Select groups for location
$query1 = "(SELECT DISTINCT ig.id,ig.description
          FROM location_inventory_items lii
          INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
          INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
          WHERE lii.type='global' AND lii.status='active' AND lii.location_id=" . $_SESSION['loc'] . ")
      UNION
          (SELECT DISTINCT ig.id,ig.description
           FROM location_inventory_items lii
           INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
           WHERE lii.type!='global' AND lii.status='active' AND lii.location_id=" . $_SESSION['loc'] . ")
           ORDER BY description ASC";
$result1 = mysql_query($query1) or die(mysql_error());
$search = array('Search Items', 'itm_tbl', 2);
function jRender_inventory_group_combo_ones($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
	$mval ='';
	$sqlval='';
	$limit = 500;
	if (isset($_GET['market'])&& trim($_GET['market'])!='') {
		$mval = $_GET['market'];
		$sqlval =  " where ig.Market='".$mval."'";	
	}else{
		$limit = 0;
	}	
	
	$vendor_where1 = '';
	$vendor_where2 = '';	
	if($_GET['vendor']>0){
		$vendor_where1 =" AND ii.vendor_default = '". $_REQUEST['vendor'] ."'";
		$vendor_where2 =" AND lii.default_vendor = '". $_REQUEST['vendor'] ."'";	
	}

    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
		/*"SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
				$sqlval	AND lii.location_id = '".$locationID."'	
				ORDER BY ig.description ASC" ;*/
		$sql = 	"SELECT distinct(tbl.id),tbl.description from (
			(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global' $sqlval AND lii.location_id = '".$locationID."' $vendor_where1
		ORDER BY ig.description ASC)
UNION ALL 
		(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND  lii.type<>'global' $sqlval AND lii.location_id = '".$locationID."' $vendor_where2 
		ORDER BY ig.description ASC)) as tbl ORDER BY description LIMIT $limit";
		//echo $sql;exit;
		$output = mysql_query($sql) or die(mysql_error());								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Item Group - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				$id = $result['id'];
				$description = $result['description'];
				if ($id == $groupID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Item Group Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}
function jRender_inventory_market_combo1($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null,$vendor_id) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="market" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
	
	$vendor_whrii = '';
	$vendor_whrLii = '';	
	if($vendor_id>0){
		$vendor_whrii = " AND ii.vendor_default='". $vendor_id ."'";
		$vendor_whrLii = " AND lii.default_vendor ='". $vendor_id ."'";
	}	
		
	$sql1 = "SELECT distinct(market) from ((SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_whrii)
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."' $vendor_whrLii)) as market ORDER BY market";
		//echo $sql1;exit;
		$output = mysql_query($sql1) or die(mysql_error());								
		$rows = mysql_num_rows($output);	
			
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -  Select Market - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				
				$market = $result['market'];
				//echo $market;
				if ($result['market'] == $_REQUEST['market']) {
					$sel1 = ' selected="selected"';
				} else {
					$sel1 = '';
				}
				$data .= '<option value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -  No Market Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Market Found  - - - </option>';
    }
    $data .= '</select>';
return $data;
  
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_SESSION['SITE_TITLE']; ?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />

<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="css/dd.css" type="text/css">
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
<script type="text/javascript" src="js/jquery.alerts.js" ></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.dd.js"></script>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript">

var intRoomHeight = jQuery('.widgetcontent').height();
    function onclk(item) {
        window.location.href = 'backoffice_inventory_cp2.php?g=<?=$_GET['g'];?>&item=' + item
    }
	
    jQuery(document).ready(function ($) {
        // dynamic table
        jQuery('#grp_tbl').dataTable({
			bFilter: false,
            "sPaginationType": "two_button",
            //"aaSortingFixed": [[0,'asc']],
			oLanguage: {
				sLengthMenu: "Show _MENU_",
                sInfo: "_START_ to _END_ of _TOTAL_",
				oPaginate: {
                    sNext: " Next",
                    sPrevious: "Prev "
                }
            },
            "fnDrawCallback": function(oSettings) {
                //jQuery.uniform.update();
				//$('.dataTables_paginate').hide();
				$('#grp_tbl_previous').addClass("first paginate_button");
				$('#grp_tbl_next').addClass("last paginate_button");
            }
        });
       
		
		$('#grp_tbl_paginate').css('right', '2px');
        
    
         
		 /*$('#grp_tbl').dataTable({
			bFilter: false,
			bSort: true,
			sSortAsc: "sorting_asc",
			sPaginationType:"two_button",
			oLanguage: {
				sLengthMenu: "Show _MENU_",
                sInfo: "_START_ to _END_ of _TOTAL_",
				oPaginate: {
                    sNext: " Next",
                    sPrevious: "Prev "
                }
            }
			"fnDrawCallback": function(oSettings) {
                //jQuery.uniform.update();
				//$('.dataTables_paginate').hide();
				$('#grp_tbl_previous').addClass("first paginate_button");
				$('#grp_tbl_next').addClass("last paginate_button");
            }
        });
		
		$('.dataTables_filter').css('top','10px');
		
		//$("select,input").not('#search').uniform();
		
		//$('#grp_tbl_previous').addClass("first paginate_button");
		$('#grp_tbl_next').addClass("last paginate_button");
		$('#grp_tbl_paginate').css('right', '2px');*/

		
		jQuery(document).ready(function(){
			jQuery('.unitselect').msDropDown();
			jQuery('.unitselect1').msDropDown();
		});

    });
</script>

<style>
.line3 {background-color: #808080;}

/* ms drop down */
.selectouter12 {
   	background: none repeat scroll 0 0 #ffffff;
    border: 1px solid #c9c9c9;
    float: left;
	height: 32px;
    line-height: 5px;
    margin: 0 0 7px;    
    position: relative;
    width: 200px;
}
.dd .ddArrow {
	background-position: 8px 2px;
}
._msddli_ {
	width: 100% !important;
}
.dd .ddChild li .ddlabel {
	margin: 0px;
	width: 50%;
}
.dd .ddChild li .description {
	margin: 0px;
	width: 50%;
}
.dd .ddChild li.disabled .ddlabel {
	width: 65% !important;
}	
#inventoryTable .span7{
		float:right;
	}
/* ms drop down */
@media (max-width:1024px){
	#inventoryTable .span5, #inventoryTable .span7{
		width:100% !important;
		margin-left:0px !important;
	}
}
</style>

</head>

<body>

<div class="mainwrapper">
    
    <?php include_once 'require/top.php';?>
    
    <div class="leftpanel">
        
        <?php include_once 'require/left_nav.php';?>
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Inventory <span class="separator"></span> Internal Inventory <span class="separator"></span> Inception</li>
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
			<div style="float:right;margin-top: 11px;">
            <input id="g_val" class="hidden" value="">
            <input id="i_val" class="hidden" value="">
            <input id="f_val" class="hidden" value="">
            	<a href="#" class="btn btn-primary btn-large" onClick="group_formSubmit()">Go</a>
                <button id="toggle_btn" group="<?php echo $_REQUEST['g_id']; ?>" item="<?php echo $_REQUEST['item_id']; ?>" class="btn btn-success btn-large">Filter</button>
                <button id="submit_btn" disabled="disabled" class="btn btn-primary btn-large" style=" display:none; opacity: 1 !important; background-color:#0866C6 !important;">Submit</button>
                 
            </div>
                <form action="<?=basename($_SERVER['PHP_SELF'])."?".$_SERVER['QUERY_STRING'];?>" name="group_form" id="group_form" method="get">
                <input type="text" placeholder="Search Items" style="float:right; width:140px; height:24px;padding: 8px 8px;margin:12px 10px 0 0;" name="serach_item" id="serach_item" value="<?=$_GET['serach_item'];?>">
				<?=jRender_inventory_group_combo_ones('group_id',$_SESSION['loc'],$group_id,'dummy','float:right; width:150px; height:43px;padding: 8px 8px;margin:12px 10px 0 0;','yes');?>
						
				<?=jRender_inventory_market_combo1('dummy_market',$_SESSION['loc'],$group_id,'dummy-market','float:right; width:140px; height:43px;padding: 8px 8px;margin:12px 10px 0 0;',$_REQUEST['vendor']);?>
                <?php 
                $qry_vendors = "SELECT distinct(id),name FROM vendors WHERE 
										id IN (SELECT DISTINCT(default_vendor)	FROM location_inventory_items WHERE 
											(default_vendor!='' AND default_vendor IS NOT NULL) AND location_id = '".$_SESSION['loc']."' AND type<>'global')
										OR 
										id IN (SELECT DISTINCT(vendor_default)	FROM inventory_items ii JOIN location_inventory_items lii ON lii.inv_item_id=ii.id  WHERE 
											(ii.vendor_default!='' AND ii.vendor_default IS NOT NULL) AND lii.location_id = '".$_SESSION['loc']."' AND lii.type = 'global')	
											ORDER BY vendors.name ASC";
                        $rs_vendors = mysql_query($qry_vendors) or die($qry_vendors .'-----'. mysql_error());
						
                    ?>
                    <select id="vendor" name="vendor" class="dummy-market" style="float:right; width:150px; height:43px;padding: 8px 8px;margin:12px 10px 0 0;" onChange="change_vendor(); " >
                    	<option value="">- - - Select Vendor - - -</option>
                        <?php
							while($row_vendors = mysql_fetch_assoc($rs_vendors)){
                                $selected = ($_REQUEST['vendor'] == $row_vendors['id']) ? 'selected' : '';
                                echo '<option value="'. $row_vendors['id'] .'" data-id='. $row_vendors['id'] .' '. $selected .' >'. $row_vendors['name'] .' (ID:'. $row_vendors['id'] .')</option>';
                            }
                        ?>
                    </select>
                <input type="hidden" name="t" value="">
          </form>
               
            <!-- 
            <form style="position: static; margin: 14px 10px 0 0; float: right;" action="results.html" method="post" class="searchbar">
                <input type="text" name="keyword" placeholder="To search type and hit enter..." />
            </form>
             -->
            <div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Display All Inception Information</h5>
                <h1>Inception</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid" id="inventoryTable">
                
                	<!--<div class="span3" style="width:20%;">
                        <div class="widgetbox">
                            <h4 class="widgettitle">Inventory Groups </h4>
                            <div class="widgetcontent">
                                List Groups-->
                                <!--<div id="group_table">
                                    <table id="grp_tbl" class="table table-bordered table-infinite">
                                        <colgroup>
                                            <col class="con0" style=""/>
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="head0 center">Group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (mysql_num_rows($result1) > 0) {
                                                while ($row1 = mysql_fetch_array($result1)) {
                                                    $class = '';
                                                    if ($_GET['g'] == $row1['id']) {
                                                        $class = "line3";
                                                    }?>
                                                    <tr class="group_row <?=$class?>" id="g_<?=$row1['id'];?>" style="cursor:pointer;" >
                                                        <td><?=$row1['description']?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else { ?>
                                                <tr>
                                                    <td>No Results Found!</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>-->
                                <!--End List Groups
                            </div>
                        </div>
                      </div>-->
                      
                      <div class="span5" >
                        <div class="widgetbox">
                            <h4 class="widgettitle">Inventory Items </h4>
                            <div class="widgetcontent">
                            	<div class="load">
                                <!--List items-->
									
                                    <div id="inv_table">
                                        <form id="count_form" name="count_form" action="" method="post">
                                            <input type="hidden" name="gotopage" id="gotopage" value="">
                                            <table id="itm_tbl" class="table table-bordered table-infinite">
                                                <colgroup>
                                                    <col class="con0" style=""/>
                                                    <col class="con1" style=""/>
                                                    <col class="con0" style=""/>
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th class="head0 center">Item</th>
                                                        <th class="head1 center">Qty</th>
                                                        <th class="head1 center">Unit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                        <tr style="text-align: center;">
                                                            <td colspan="5">Nothing To Display!</td>
                                                        </tr>
                                                   
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    
                                    <!--End List Items-->
                                 </div>
                            </div>
                        </div>
                      </div>
                      
                      <div class="span7">
                        <div class="widgetbox"> 
                            <h4 class="widgettitle itemnameD">Details </h4>
                            <div class="widgetcontent">
                                <div class="detaildiv">
								
                                            <form id="count_frm" method="post">
                                                <input type="hidden" name="count_submit" value="submitted"/>
                                                <input type="hidden" name="item" value="<?=$_GET['item'];?>"/>
                                                <table class="table table-bordered table-infinite" id="itm_detail">
                                                    <colgroup>
                                                        <col class="con0" style=""/>
                                                        <col class="con1" style=""/>
                                                        <col class="con0" style=""/>
                                                        <col class="con1" style=""/>
                                                        <col class="con0" style=""/>
                                                    </colgroup>
                                                    <thead>
                                                        <tr>
                                                            <th class="head0 center">Type</th>
                                                            <th class="head1 center">Date</th>
                                                            <th class="head0 center">Employee</th>
                                                            <th class="head1 center">Unit Type</th>
                                                            <th class="head0 center">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                       <tr style="text-align: center;">
                                                            <td colspan="5">Nothing To Display!</td>
                                                        </tr>
                                                    <tbody>
                                                </table>
                                            </form>
                                               
                                  </div>
                            </div>
                        </div>
                      </div>
                     
                </div>
					
                  
                  <?php include_once 'require/footer.php';?>
                <!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<script type="text/javascript">
    jQuery(document).ready(function ($) {
		var gid = '<?php echo $_REQUEST['group_id']; ?>';
		var market = '<?php echo $_REQUEST['market']; ?>';
		var vendor = '<?php echo $_REQUEST['vendor']; ?>';
		var serach_item = '<?php echo $_REQUEST['serach_item']; ?>';
		if(gid>0 || vendor>0 ||serach_item!=''){
		$("#g_val").val(gid);
		$(".detaildiv").html('');
		$.ajax({
				url: "ajax/ajax_inventory.php",
				type: "GET",
				data: "g="+gid+"&vendor="+vendor+"&serach_item="+serach_item+"&market="+market,
				success: function(html){
					console.log(html);
					$(".load").html(html);
					
					$('#itm_tbl').dataTable({
						aaSorting: [[ 1, "asc" ]],
						sPaginationType:"full_numbers",
						oLanguage: {
							sLengthMenu: "Show _MENU_",
							sInfo: "_START_ to _END_ of _TOTAL_",
							sSearch: "Search: ",
							sInfoEmpty: "0 to 0 of 0",
							oPaginate: {
							    sFirst: "First",
							    sLast: "Last",
							    sNext: "Next",
							    sPrevious: "Prev"
							}
						}
					});
					
					//$("select,input").not('#search').uniform();
					$("#itm_tbl_filter input").css('width','80px');
					var itm_id = '<?php echo $_REQUEST['item_id']; ?>';
					var grp_id = '<?php echo $_REQUEST['group_id']; ?>';
					if(itm_id!=""){
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page']!=""){?>	
					var page = '<?php echo $_REQUEST['page']; ?>';
					for(i=1;i<page;i++){
					jQuery('#itm_tbl_next').click();
					}
					<?php } ?>
					$("#item_"+grp_id+"_"+itm_id).trigger('click');
					jQuery('#toggle_btn').attr("group",grp_id);
					jQuery('#toggle_btn').attr('item',itm_id);
					}else{
					jQuery('#toggle_btn').attr("group",grp_id);
					jQuery('#toggle_btn').attr('item',itm_id);
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page']!=""){?>	
					var page = '<?php echo $_REQUEST['page']; ?>';
					for(i=1;i<page;i++){
					jQuery('#itm_tbl_next').click();
					}
					<?php } ?>
					$("#itm_tbl>tbody>tr:first").trigger('click');	
					}
					
					$('.unitselect').msDropDown();
					$('.unitselect1').msDropDown();
		
				},
				error:function(){
					//console.log('failed');
					//$("#result").html('There is error while submit');
				},
				beforeSend: function(){
                			//console.log('loading');
				},
				complete: function(){
					//console.log('complete');
				}
			});
		} 
		/*$('.group_row').live('click', function () { //alert('sad');
			var gid = $(this).attr('id').split("_")[1];
			$("#g_val").val(gid);
			$("#i_val").val("")
			
			//alert(gid); return false;
			$(".load").html('loading...');
			$(".detaildiv").html('');
			$('.group_row').removeClass('line3');
			$(this).addClass('line3');
			$.ajax({
				url: "ajax/ajax_inventory.php",
				type: "GET",
				data: "g="+gid,
				success: function(html){
					//console.log('success');
					$(".load").html(html);
					
					$('#itm_tbl').dataTable({
						sPaginationType:"full_numbers",
						oLanguage: {
							sLengthMenu: "Show _MENU_",
							sInfo: "_START_ to _END_ of _TOTAL_",
							sSearch: "Search: ",
							sInfoEmpty: "0 to 0 of 0",
							oPaginate: {
							    sFirst: "First",
							    sLast: "Last",
							    sNext: "Next",
							    sPrevious: "Prev"
							}
						}
					});
					
					//$("select,input").not('#search').uniform();
					$("input").css('width','80px');
					var itm_id = '<?php echo $_REQUEST['item_id']; ?>';
					var grp_id = '<?php echo $_REQUEST['g_id']; ?>';
					if(itm_id!=""){
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page']!=""){?>	
					var page = '<?php echo $_REQUEST['page']; ?>';
					for(i=1;i<page;i++){
					jQuery('#itm_tbl_next').click();
					}
					<?php } ?>
					$("#item_"+grp_id+"_"+itm_id).trigger('click');
					jQuery('#toggle_btn').attr("group",grp_id);
					jQuery('#toggle_btn').attr('item',itm_id);
					}else{
					jQuery('#toggle_btn').attr("group",grp_id);
					jQuery('#toggle_btn').attr('item',itm_id);
					<?php if(isset($_REQUEST['page']) && $_REQUEST['page']!=""){?>	
					var page = '<?php echo $_REQUEST['page']; ?>';
					for(i=1;i<page;i++){
					jQuery('#itm_tbl_next').click();
					}
					<?php } ?>
					$("#itm_tbl>tbody>tr:first").trigger('click');	
					}
					
		
				},
				error:function(){
					//console.log('failed');
					//$("#result").html('There is error while submit');
				},
				beforeSend: function(){
                			//console.log('loading');
				},
				complete: function(){
					//console.log('complete');
				}
			});
        	});*/
		
		$('.item_row').live('click', function () { //alert('sad');
			var pagin = jQuery('#itm_tbl_paginate .paginate_active').html();
			//var gid = $(this).attr('id').split("_")[1];
			var gid = $(this).attr('rel_g');
			var market = '<?php echo $_REQUEST['market']; ?>';
			//var id = $(this).attr('id').split("_")[2];
			var id = $(this).attr('id').split(/[_ ]+/).pop();
			$("#g_val").val(gid);
			$("#i_val").val(id);
			//alert(gid + " " +id); return false;
			$(".detaildiv").html('loading...');
			$('.item_row').removeClass('line3');
			$(this).addClass('line3');
			var vendor = '<?php echo $_REQUEST['vendor']; ?>';
			var serach_item = '<?php echo $_REQUEST['serach_item']; ?>';
			<?php if(isset($_GET['t'])){ ?>
			var data = "g="+gid+"&item="+id+"&page="+pagin+"&market="+market+"&vendor="+vendor+"&serach_item="+serach_item+"&t"
			<?php }else{ ?>
			var data = "g="+gid+"&item="+id+"&market="+market+"&vendor="+vendor+"&serach_item="+serach_item+"&page="+pagin;
			<?php } ?>
			
			jQuery('#toggle_btn').attr("group",gid);
			jQuery('#toggle_btn').attr('item',id);
			$(".itemnameD").html("Details");
			$.ajax({
				url: "ajax/ajax_inventory.php",
				type: "GET",
				data: data,
				success: function(html){
					//console.log('success');
					$(".detaildiv").html(html);
					if($("#ditemName").val()!=''){
						$(".itemnameD").html("Details - "+$("#ditemName").val());
					}
					
					$('.unitselect').msDropDown();
					$('.unitselect1').msDropDown();
				},
				error:function(){
					//console.log('failed');
					//$("#result").html('There is error while submit');
				},
				beforeSend: function(){
                			//console.log('loading');
				},
				complete: function(){
					//console.log('complete');
				}
			});
        });
		
		
        // $('#grp_tbl').dataTable({
            // "sPaginationType": "full_numbers",
            // "aaSorting": [[ 2, "asc" ]],
            // "bJQuery": true,
        // });
        // $('#itm_tbl').dataTable({
            // "sPaginationType": "full_numbers",
            // "aaSorting": [[ 2, "asc" ]],
            // "bJQuery": true,
        // });
		
		
        $('.enable').live('keyup', function () {
			
            $('#submit_btn').removeAttr("disabled");
			$('#submit_btn').show();
            $('#submit_btn').live('click', function () {
			$('.unitselect').parent().parent().removeClass("se");
			$('.unitselect1').parent().parent().removeClass("se");
			$('#storeroom1').removeClass("se");	
			var submt = true;
			$('.incnt').each(function(){
				if($(this).val()!=""){
					var rel = jQuery(this).attr('rel');
					if($('.incntunit'+rel).val() == ''){ 
						/*alert('Please Select a Unit Type First');*/
						$('.incntunit'+rel).parent().parent().addClass("se");
						submt = false;
						return false;	
					}
					
				}
			});
			/*if($('.incnt').val()!=""){
				var rel = jQuery(this).attr('rel');
				alert(rel);
				if($('.incntunit'+rel).val() == ''){ 
					/*alert('Please Select a Unit Type First');
					$('.incntunit'+rel).parent().parent().addClass("se");
					return false;	
				}
			}*/
			
			
			if(typeof($('#storeroom1').val())=='undefined' && jQuery("#itm_detail tr.widgettitle").length==0){
				jAlert('Please Config Storeroom First','Alert Dialog');
				return false;
			}
			if($('#count1').val()!=""){
				if($('#storeroom1').val() == ''){ 
					$('#storeroom1').addClass("se");
					return false;	
				}
			}
			if($('#storeroom1').val()!="" || $('#count1').val()!=""){
				//alert($('.unitselect1').val()+'=>'+$('.unitselect').val());
				//return false;				
				if($('.unitselect1').val() == ''){ 
					/*alert('Please Select a Unit Type First');*/
					$('.unitselect1').parent().parent().addClass("se");
					return false;	
				}
				
			 }
			 
			 if(submt){
             	$('#count_frm').submit();
			 }
            });
            $('.enable').unbind('click');
        });
		
        $('#toggle_btn').click(function () {
			var group_id = jQuery('#toggle_btn').attr('group');
			var item_id = jQuery('#toggle_btn').attr('item');
			var market = '<?php echo $_REQUEST['market']; ?>'; 
        	<?php if (isset($_GET['t'])) { ?>
            	window.location = "backoffice_inventory_cp2.php?group_id="+group_id+"&item_id="+item_id+"&market="+market;
            <?php } else { ?>
            	window.location = "backoffice_inventory_cp2.php?group_id="+group_id+"&item_id="+item_id+"&market="+market+"&t";
            <?php } ?>
			
			var datastring;
			var gid = $("#g_val").val();
			var id = $("#i_val").val();
			var f = $("#f_val").val();
			var vendor = '<?php echo $_REQUEST['vendor']; ?>';
			var serach_item = '<?php echo $_REQUEST['serach_item']; ?>';
			if(f!=""){
				datastring = "g="+gid+"&item="+id+"&market="+market+"&vendor="+vendor+"&serach_item="+serach_item+"&t";
				$("#f_val").val("")
			}else{
				datastring = "g="+gid+"&item="+id+"&vendor="+vendor+"&serach_item="+serach_item+"&market="+market;
				$("#f_val").val("t")
			}
			
			$(".itemnameD").html("Details");
			$.ajax({
				url: "ajax/ajax_inventory.php",
				type: "GET",
				data: datastring,
				success: function(html){
					console.log('success');
					if(id!=""){
						$(".detaildiv").html(html);
						if($("#ditemName").val()!=''){
							$(".itemnameD").html("Details - "+$("#ditemName").val());
						}
						
						$('.unitselect').msDropDown();
						$('.unitselect1').msDropDown();
					}
				},
				error:function(){
					console.log('failed');
					//$("#result").html('There is error while submit');
				},
				beforeSend: function(){
                	console.log('loading');
				},
				complete: function(){
					console.log('complete');
				}
			});
			
			
        });
		
        $('.search_box').on('paste keyup', 'input', function () {
            if (!this.value) {
                $('#search_x').fadeOut(300);
                filter('', '<?=$search[1]?>',<?=$search[2]?>, 'clear');
            } else {
                $('#search_x').delay().fadeIn(300);
                filter(this.value, '<?=$search[1]?>',<?=$search[2]?>, 'search');
            }
        });
        $('#search_x').on('click', function () {
            $('.search_box').find('input').val('');
            filter('', '<?=$search[1]?>',<?=$search[2]?>, 'clear');
            $(this).fadeOut(300);
        });
        if ($('.search_box').find('input').val() != '') {
            $('#search_x').show();
        }
		
		//alert('ready');
		var grp_id = '<?php echo $_REQUEST['g_id']; ?>';
		if(grp_id!=""){
		$("#g_"+grp_id).trigger('click');			
		}else{
		$("#grp_tbl>tbody>tr:first").trigger('click');
		}
		$('.dataTables_filter').addClass('search-query');
		$('.dataTables_filter input').attr("background", "red");
		
    });
	

	
    function filter(term, _id, cols, type) {
        var suche = term.toLowerCase();
        var table = document.getElementById(_id);
        var ele;
        if (type == 'search') {
            for (var r = 1; r < table.rows.length; r++) {
                for (var i = 0; i <= cols; i++) {
                    ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g, "");
                    if (ele.toLowerCase().indexOf(suche) >= 0) {
                        table.rows[r].style.display = '';
                        break;
                    } else {
                        table.rows[r].style.display = 'none';
                    }
                }
            }
        } else {
            for (r = 1; r < table.rows.length; r++) {
                table.rows[r].style.display = '';
            }
        }
    }
	jQuery(document).on('change','#dummy_market',function(){
	
	  var market = jQuery(this).val();
	   var vendor = jQuery("#vendor").val();
	 // alert(market);
		jQuery.ajax({
		type: "POST",
		url: "chkmarketgroup.php",
		data: { market: market,vendor:vendor}
		})
		.done(function(msg) {
		// alert(msg);
		  jQuery("#group_id").html(msg);
		});
	
	});
	function group_formSubmit(){
		var market = jQuery("#dummy_market").val();
		var group_id = jQuery("#group_id").val();
		if(jQuery("#vendor").val()!='' || jQuery("#serach_item").val()!=''){
			document.group_form.submit();	
		}else if(market === '' && group_id ===''){
			jAlert('Please select a Market and an Inventory Group.','Alert Dialog');
			return false;
		}else if(market === ''){
			jAlert('Please select a Market.','Alert Dialog');
			return false;
		}else if(group_id ===''){
			jAlert('Please enter Item Group.','Alert Dialog');
			return false;
		}
 		document.group_form.submit();
	}
	
	function change_vendor(){
		var vendor_val = jQuery('#vendor').val();	
		return xhr = jQuery.ajax({
			url: 'get_market_from_vendor.php'
			,type: 'GET'
			,data: { 
				'vendor_default': vendor_val,'type':'local'
				
			}
			,dataType: 'JSON' 
			,success: function (res) {
				if(res.ResponseCode == '1'){
					var options_market = res.Response.data.options_market;
					jQuery('#dummy_market').html(options_market);
					jQuery('#dummy_market').val('<?php echo $_REQUEST['market']; ?>');
				}
			}
		});
}
</script>
</body>
</html>
<style>
.se
{
	border-color:#FF0000;
}
</style>