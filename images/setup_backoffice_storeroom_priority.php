<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

$setupHead      = "active";
$setupDropDown  = "display: block;";

$financeHead    = "active";
$financeDropDown = "display: block;";
$financeMenu8 = "active";
$set_back_invventoryDropDown  = "display: block;";

function getInvItem1($item_id,$storeroom_id){
	$rp = new db_class();
	$sql = $rp->rp_query("select * from location_inventory_counts where inv_item_id = '".$item_id."' AND storeroom_id = '".$storeroom_id."' order by id desc limit 0,1");
	if($rp->rp_affected_rows($sql) > 0){
		$rs = $rp->rp_fetch_array($sql);
		$qty = $rs['quantity'];
	} else { $qty = '0.00'; }
	return $qty;
}

if($_POST['all_items'] != ''){
 	foreach($_POST['all_items'] as $active){			
		$query = "UPDATE `location_inventory_items` SET `status` = 'Active' WHERE `id` = " . $rp->add_security($active);
		$result = $rp->rp_query($query) or die(mysql_error());
	}
}
if($_POST['active'] != ''){
	foreach($_POST['active'] as $active){			
		$query = "UPDATE `location_inventory_items` SET `status` = 'inactive' WHERE `id` = " . $rp->add_security($active);
		$result = $rp->rp_query($query) or die(mysql_error());
	}
}

if($_POST['itemsPriority'] != ''){	
    $order = explode(',', $_POST['itemsPriority']);
    if($_POST['priority_type'] == 'item'){
        for($i=0;$i<count($order);$i++){
            $priority = $i + 1;
            $id = $rp->add_security($order[$i]);
            $query = "UPDATE location_inventory_storeroom_items SET priority='$priority' WHERE id=" . $id;
            $result = $rp->rp_query($query) or die(mysql_error());
        }
    }else if($_POST['priority_type'] == 'itemgroup'){
        for($i=0;$i<count($order);$i++){
            $priority = $i + 1;
          $id = $rp->add_security($order[$i]);
    	  $query = "UPDATE location_inventory_storeroom_items SET group_item_priority='$priority' WHERE id='".$id."'";
          $result = $rp->rp_query($query) or die(mysql_error());
        }
    }
	
	if($_REQUEST['hidr']!="" && $_REQUEST['hidDesc']!=""){
		header("location:setup_backoffice_storeroom_priority.php?r=".$_REQUEST['hidr']."&desc=".$_REQUEST['hidDesc']."&act=save");
		exit;
	}else if($_REQUEST['hidgroup']!="" && $_REQUEST['hidDesc']!=""){
		header("location:setup_backoffice_storeroom_priority.php?g=".$_REQUEST['hidgroup']."&desc=".$_REQUEST['hidDesc']."&act=save");
		exit;		
	}else{
		header("location:setup_backoffice_storeroom_priority.php?r=".$_REQUEST['r']."&desc=".$_REQUEST['desc']."&act=save");
		exit;
	}
}
if($_GET['desc'] == 'itemgroup' && $_GET['r'] != ''){
    $order = "ORDER BY tbl.group_item_priority ASC";
    $desc = 'itemgroup';

    if($_GET['group'] != ''){
        $group = ' AND ig.id=' . $rp->add_security($_GET['group']);
        $g = $_GET['group'];
    }else{
        $row = $rp->rp_fetch_array($result3);
        //$group = ' AND ig.id=' . $row['id'];
		$group = '';
        $g = $row['id'];
        $rp->rp_data_seek($result3,0);
    }
}else{
    $order = "ORDER BY priority ASC";
    $desc = 'item';
    $group = '';
}
if($_REQUEST['desc']=='itemgroup')
{                
	// left side group selection starts here..
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
	$result1 = $rp->rp_query($query1) or die(mysql_error());

	//group selection starts here

	if(isset($_REQUEST['g'])) { $g=$_REQUEST['g']; }
	else { $g=49; }
		$order = " ORDER BY description DESC ";

	//Select all items for the selected group
	$query2 = "(SELECT lii.type,lii.id,ii.description as description
			FROM location_inventory_items lii
			INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
			WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active' AND lii.type = 'global' AND (ii.inv_group_id=" . $g. " OR lii.local_group_id=" .$g. "))
			UNION
			(SELECT lii.type,lii.id,lii.local_item_desc as description
			FROM location_inventory_items lii
			WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active' AND lii.type <> 'global' AND lii.local_group_id=" .$g. ")" . $order;
	$result2 = $rp->rp_query($query2) or die(mysql_error());
} else {
	$query1 = "SELECT storeroom_id,stroom_id FROM location_inventory_storerooms WHERE location_id = '" . $_SESSION['loc']."' ORDER BY priority asc";
	$result1 = $rp->rp_query($query1) or die(mysql_error());
	$result_first = $rp->rp_query($query1) or die(mysql_error());
	$row_first=$rp->rp_fetch_array($result_first);
	$firstR=$row_first['storeroom_id'];

	if($_GET['r']!=""){ $firstR=$_GET['r']; }

	if ($firstR) {
		$query2 = "SELECT tbl.*,iiu.unit_type FROM(
				(SELECT lii.id as lid,lisi.id,lii.local_item_id as item_id,lii.local_item_desc as item,ig.description as `group`,lii.local_unit_type as unit_type,lii.type,lii.local_unit_type_qty,lii.local_item_image as image,lisi.priority,lisi.group_item_priority,lii.status,lisi.storeroom_id
				FROM location_inventory_storeroom_items lisi
				INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id AND lii.location_id=lisi.location_id
				LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
				WHERE 
				lisi.location_id=" . $_SESSION['loc'] . " 
				AND lisi.storeroom_id=" . $rp->add_security($firstR) . " AND lii.type != 'global' $group)
				UNION ALL
				(SELECT lii.id as lid,lisi.id, ii.item_id, ii.description as item, ig.description as `group`,ii.unit_type,lii.type,lii.local_unit_type_qty,ii.image,lisi.priority,lisi.group_item_priority,lii.status,lisi.storeroom_id
				FROM location_inventory_storeroom_items lisi
				INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id AND lii.location_id=lisi.location_id
				INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
				LEFT JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lisi.storeroom_id=" . $rp->add_security($firstR) . " AND lii.type = 'global' $group)
			)as tbl
			LEFT JOIN inventory_item_unittype iiu ON iiu.id=tbl.unit_type " .$order;
	
		if($_REQUEST['debug']!='') echo '<br>'.$query2;
		$result2 = $rp->rp_query($query2) or die(mysql_error());
	}
}
if($_REQUEST['debug']!='') echo '<br>query2 =>'.$query2.'<br>';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_SESSION['SITE_TITLE']; ?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<style>
	.btn-large { border-radius: 0; font-size: 14px; /*padding: 4px 20px;*/ vertical-align: top; }
	.sorting_asc { background: url('images/sort_asc.png') no-repeat center right !important; background-color: #333333 !important; }
	.sorting_desc {	background: url('images/sort_desc.png') no-repeat center right !important; background-color: #333333 !important; }
	.menu_link_select{ background-color:rgb(128,128,128); background-repeat: repeat; cursor: pointer; }
	.menu_link_select a, .menu_link a{ display: block; }
	div.selector span { text-align: left; }
	.left{ text-align:left; }
	.dataTables_paginate .paginate_active { background: none repeat scroll 0 0 #0866C6; color: #FFFFFF; }
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="js/jquery.uniform.min.js"></script> -->
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js" ></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.tablednd.0.7.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $("input[type='checkbox']").live('click',function(){			
            $('#submit_btn').removeAttr("disabled");
            $('#submit_btn').click(function(){
				$('input[name=toAlert]').val('1');
                $('#change_status_form').submit();
            });
            $("input[type='checkbox']").unbind('click');
        });

        $('select').change(function(){ $(this).closest('form').submit(); });
		
		$('#table-1').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true
            /*"fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				//alert('dd');
				$('.unitselect').msDropDown();
            }*/
        });
		
        jQuery('#table-1_length').html('').css('height','25px');
		
		$('#table-2').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true
            /*"fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				//alert('dd');
				$('.unitselect').msDropDown();
            }*/
        });

        jQuery('#table-2_length').html('').css('height','25px');

        var firstClick = true;
        function setSaveHandler(){
            if(firstClick){
                firstClick = false;
                $('#save_img').show();				
                $('#save_img').click(function(){
					var prio = 'item';
					if($("#priotype").val()!=''){
						prio = $("#priotype").val();
					}
					$("#priority_type").val(prio);
					$('#savePriorityFrm').submit();
                });
            }
        }
		
		$(document).on('click', '.lnkMenu', function(e){
				$("#hidr").val($(this).attr("data-id"));
				$("#hidDesc").val($(this).attr("data-desc"));
				
				if($('#itemsPriority').val()==""){
					window.location.href="setup_backoffice_storeroom_priority.php?r="+$("#hidr").val()+"&desc="+$("#hidDesc").val();
				}else{
					var prio = 'item';
					if($("#priotype").val()!=''){
						prio = $("#priotype").val();
					}
					$("#priority_type").val(prio);
					$('#savePriorityFrm').submit();
				}
        });
		
		var ta = '<?php echo $_REQUEST['toAlert']; ?>';
		if(ta == 1) { 
			jAlert('Storeroom Priority changed successfully!','Show Dialog');
		}
	});
</script>
<style>
	.bcolor{ background-color:rgb(51,51,51) !important;}	
	.menu_link a { color: #000000 !important; }
	.menu_link_select a{ color:#FFFFFF !important;}
	.btn-primary.disabled, .btn-primary[disabled]{ background-color:#d3d3d3 !important; }
</style>
</head>

<body>
	<div class="mainwrapper">
		<?php include_once 'require/top.php';?>
		<div class="leftpanel"><?php include_once 'require/left_nav.php';?></div>
		<!-- leftpanel -->
		<div class="rightpanel">
			<ul class="breadcrumbs">
				<li><a href="messages.php"><i class="iconfa-home"></i> </a> <span
					class="separator"></span></li>
				<li>Setup</li>
				<li><span class="separator"></span></li>
				<li>Inventory</li>
                <li><span class="separator"></span></li>
				<li>Storeroom Priority</li>
				<li class="right"><a href="" data-toggle="dropdown"
					class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
					<form method="get" style="display: inline-block;">
						<input type="hidden" name="r" value="<?php echo $_GET['r'];?>" />
						<select id="priotype" name="desc" style="height:43px;" class="uniformselect">
							<option value=''>- - -Sort Priority By - - -</option>
							<option value="item" <?php if ($_REQUEST['desc'] == 'item') { echo 'selected="selected"'; } ?> onClick="location.href='setup_backoffice_storeroom_priority.php'">Item Priority</option>
							<option value="itemgroup" <?php if ($_REQUEST['desc'] == 'itemgroup') { echo 'selected="selected"'; } ?> onClick="location.href='setup_backoffice_storeroom_priority.php?desc=itemgroup&g=49'">Item Group Priority</option>
						</select>
					</form>
					<button id="save_img" style="display:none;" class="btn btn-primary btn-large">Save Changes</button>
					<button disabled="disabled" id="submit_btn" class="btn btn-primary btn-large">Submit</button>
	            </div>
				<div class="pageicon"><span class="iconfa-cog"></span></div>
				<div class="pagetitle">
					<h5>Select a storeroom to organize the priority for items.</h5>
					<h1>Storeroom Priority</h1>
				</div>
			</div>
			<!--pageheader-->
			
			<div class="maincontent">
				<div class="maincontentinner">
					<div class="row-fluid">
						<?php if($_REQUEST['desc']=='itemgroup')
						{ ?>
						<div class="span2" style="width:15% !important">
							<h4 class="widgettitle">Item Group</h4>
                            <div class="widgetcontent">
							<table class="table table-bordered responsive">
								<thead>
									<tr><th class="head0 bcolor left">Item Group Priority</th></tr>
								</thead>
								<tbody>
									<?php 
										while($row1 = $rp->rp_fetch_array($result1)){
				                        	$class = '';
											if ($_GET['g'] == $row1['id']) {
												$class = "line3";
											}       ?>
				                         	<tr id="<?php echo 'itemgroup_'.$row1['id']; ?>" class="menu_link<?php if($_REQUEST['g'] == $row1['id']) echo '_select'; ?>">
												<td onClick="getitemGroup('itemgroup',<?=$row1['id'];?>)"><?=$row1['description'];?></td>
											</tr>       
				                    <?php } ?>
								</tbody>
							</table>
                            </div>
						</div>
				
				<div class="span10" id="itemgroup_div"  style="width:84% !important; margin-left:1%;">
 					<h4 class="widgettitle">Item Group Priority</h4>
						<form id="change_status_form" name="change_status_form" method="post">
						<input type="hidden" name="toAlert" id='toAlert' value="" />
							<div class="widgetcontent">	
							<table class="table table-bordered responsive" id="table-1">
								<colgroup>
									<col style="width:5%;" class="con0" />
									<col style="width:5%;" class="con1" />
									<col style="width:5%;" class="con0" />
									<col style="width:25%;" class="con1" />
									<col style="width:15%;" class="con0" />
									<col style="width:25%;" class="con1" />
									<col style="width:10%;" class="con0" />
									<col style="width:10%;" class="con1" />
								</colgroup>
								<thead>
									<tr>
										<th class="head0 bcolor left">Type</th>
										<th class="head1 bcolor left">Image</th>
										<th class="head1 bcolor center">S</th>
										<th class="head1 bcolor left">Item</th>
										<th class="head0 bcolor left">Group</th>
										<th class="head1 bcolor left">Description</th>
										<th class="head0 bcolor left">Priority</th>
										<th class="head1 bcolor left">Unit</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										while($row2 = $rp->rp_fetch_array($result2)) {
										$i++; //Add 1 for each item
										if ($row2['type'] == 'global') {
											$query3 = "SELECT lii.id loc_item_id,  lic.id, lic.location_id,
													lic.storeroom_id, lii.type, lii.inv_item_id,
													lic.quantity, ig.description as item_group, lisi.id as lisi_id, lisi.group_item_priority as lisi_group_item_priority, ig.priority as pr, COALESCE(lii.local_item_image,ii.image) as img, ii.description as item,iiu.unit_type as unit,lii.status
													FROM location_inventory_items lii
													INNER JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id
													LEFT JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id = lic.inv_item_id AND lisi.storeroom_id = lic.storeroom_id
													INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
													INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
													LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
													WHERE lii.location_id=" . $_SESSION['loc'] . "
													AND lic.location_id=" . $_SESSION['loc'] . "
													AND lisi.location_id=" . $_SESSION['loc'] . "
													AND lic.inv_item_id=" . $row2['id'] . "
													ORDER BY lic.storeroom_id, lic.id DESC";
										} else {
											$query3 = "SELECT lii.id loc_item_id,  lic.id, lic.location_id,lisi.id as lisi_id, lisi.group_item_priority as lisi_group_item_priority,lii.local_item_image as img,
													lic.storeroom_id,  lii.type, lii.inv_item_id,
													lic.quantity, ig.description as item_group,  ig.priority as pr, lii.local_item_desc as item,iiu.unit_type as unit,lii.status
											FROM location_inventory_items lii
											INNER JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id
											LEFT JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id = lic.inv_item_id AND lisi.storeroom_id = lic.storeroom_id
											
											INNER JOIN inventory_groups ig ON lii.local_group_id=ig.id
											LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
											WHERE lii.location_id=" . $_SESSION['loc'] . "
													AND lic.location_id=" . $_SESSION['loc'] . "
													AND lic.inv_item_id=" . $row2['id'] . "
											ORDER BY lic.storeroom_id, lic.id DESC";
										}
						
										$result3 = $rp->rp_query($query3) or die(mysql_error());
										if($rp->rp_affected_rows($result3)>0){
											$row21 = $rp->rp_fetch_array($result3); ?>
											<tr class="gradeX" id="<?php echo $row21['lisi_id'];?>">
												<td style="text-transform: capitalize;"><?php  
													$type =ucfirst(substr($row21['type'],0,1));
													if ($type = "G") { echo "GLOBAL"; } 
													elseif ($type = "L") { echo "LOCAL"; }
											   		else { echo "PREP"; }
											    ?></td>
												<td style="text-align: center;">
													<?php if($row21['img'] != ''){ ?>
														<a href="<?php echo APIIMAGE;?>images/<?php echo $row21['img'];?>"
														class="img"> <img style="width:50px; height:50px;"
															src="<?php echo APIIMAGE;?>images/<?php echo $row21['img'];?>"
															alt="No Item Image" onerror="this.src='images/defimgpro.png'" />
													</a> <?php }else{?>
													<a href="images/defimgpro.png">
													<img style="width:50px; height:50px;"  src="<?php echo API; ?>panels/businesspanel/images/defimgpro.png">
													</a>
													<?php } ?>
												</td>
												<td style="text-align:center;"><?php if($row21['status'] == 'active'){?><img src="images/Active, Corrected, Delivered.png" title="Active"><?php }?>
													<?php if($row21['status'] == 'inactive'){?><img src="images/Inactive & Missing Punch.png" title="Inactive"><?php }?></td>
												<td><?php echo $row21['item']; ?></td>
												<td><?php echo $row21['item_group']; ?></td>
												<td><?php echo trim($row21['item']); ?></td>
												<td>
												<?php 
												/*$qry33=$rp->rp_query("select * from location_inventory_storeroom_items where location_id=' $_SESSION[loc]' and inv_item_id='$row2[id]'") or die(mysql_error());
												$res33=$rp->rp_fetch_array($qry33);	*/							
													echo intval($row21['lisi_group_item_priority']); 
												?>
												</td>
												<td><?php echo $row21['unit'];?></td>
										</tr>
										<?php 
										} }  ?>
									</tbody>
				                </table>
                                </div>
                                 </form>
                           </div>				           
						</div>
						<?php } else { ?> 
						<div class="span2" style="width:15% !important;">
							<h4 class="widgettitle">Storeroom</h4>
                            <div class="widgetcontent">
							<table class="table table-bordered responsive">
								<thead>
									<tr><th class="head0 bcolor left">Storeroom Priority</th></tr>
								</thead>
								<tbody>
									<?php while($row1 = $rp->rp_fetch_array($result1)){
				                        $class = '';
				                        if($_GET['item'] == $row1['id']){ $class="class='line3'"; $n=$row1['desc']; }
				                        ?>
				                        <tr class="menu_link<?php if($firstR == $row1['storeroom_id']) echo '_select'; ?>" id="<?php echo $desc.'_'.$row1['storeroom_id']; ?>" onClick="getStoreroomPri('<?php echo $desc; ?>',<?php echo $row1['storeroom_id']; ?>)" >
				                        	<td>
				                            	<?php /*?><a href="setup_backoffice_storeroom_priority.php?r=<?php echo $row1['storeroom_id']; ?>&desc=<?php echo $desc; ?>"><?php echo $row1['stroom_id']; ?></a><?php */?>
												<?php echo $row1['stroom_id']; ?>
				                            </td>
				                        </tr>
				                    <?php } ?>
								</tbody>
							</table>
                            </div>
						</div>

						<?php if ($firstR) { ?>
						<div class="span10" id="storeroom_div" style="width:84% !important; margin-left:1%;">
 							<h4 class="widgettitle">Item Priority</h4>
							<form id="change_status_form" name="change_status_form" method="post">
								<input type="hidden" name="toAlert" value="" />
								<div class="widgetcontent">
									<table class="table table-bordered responsive" id="table-2">
										<colgroup>										
											<col style="width:5%;" class="con0" />
											<col style="width:5%;" class="con1" />
											<col style="width:5%;" class="con0" />
											<col style="width:25%;" class="con1" />
											<col style="width:15%;" class="con0" />
											<col style="width:25%;" class="con1" />
											<col style="width:5%;" class="con0" />
											<col style="width:5%;" class="con1" />
											<col style="width:5%;" class="con0" />
											<col style="width:5%;" class="con1" />
										</colgroup>
										<thead>
											<tr>
												<th class="head0 bcolor left">Type</th>
												<th class="head1 bcolor left">Image</th>
												<th class="head1 bcolor center">S</th>
												<th class="head1 bcolor left">Item</th>
												<th class="head0 bcolor left">Group</th>
												<th class="head1 bcolor left">Description</th>
												<th class="head0 bcolor left">QTY</th>
												<th class="head1 bcolor left">Unit</th>
												<th class="head0 bcolor left">Priority</th>
												<th class="head1 bcolor left">INACTIVE</th>
											</tr>
										</thead>
										<tbody>
											<?php while ($row2 = $rp->rp_fetch_array($result2)){ ?>
											<tr class="gradeX" id="<?php echo $row2['id'];?>">
												<!--<td><?php echo ucfirst(substr($row2['type'],0,1));?></td>-->
												<td style="text-transform: capitalize;"><?php echo $row2['type']; ?></td>
												<td style="text-align: center;"><?php if($row2['image'] != ''){ ?>
													<a href="<?php echo APIIMAGE;?>images/<?php echo $row2['image'];?>" class="img"> 
													<img style="width:50px; height:50px;" src="<?php echo APIIMAGE;?>images/<?php echo $row2['image'];?>" alt="No Item Image" onerror="this.src='images/defimgpro.png'" />
												</a> <?php }else{?>
												<a href="images/defimgpro.png">
												<img style="width:50px; height:50px;"   alt="No Item Image" src="images/defimgpro.png" onerror="this.src='images/defimgpro.png'" />
												</a>
												<?php } ?>
												</td>
												
												<td style="text-align:center;">
												<?php
													//$getQty = "SELECT quantity from location_inventory_counts WHERE inv_item_id = '".$row2['lid']."' AND location_id = '".$_SESSION['loc']."' "
													if($row2['status'] == 'active'){
														echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
													} else {
														echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
													}
												?>
												</td>
												
												<td><?php echo $row2['item'];?></td>
												<td><?php echo $row2['group'];?></td>
												<td><?php echo $row2['item'];?></td>
												<td><?php echo $qtyCount = getInvItem1($row2['lid'],$row2['storeroom_id']);?></td> 
												<td><?php echo $row2['unit_type'];?></td>											
												
												<td>
													<?php 
														if($desc == 'item'){ echo intval($row2['priority']); } 
														else { echo intval($row2['group_item_priority']); } 
													?>
												</td>
												<td  style="text-align: center;">
													<input type='hidden' name="all_items[]" value='<?php echo $row2['lid']; ?>' />
													<?php 
														if($qtyCount > 0){
															if($row2['status'] == 'inactive'){
																echo "<input type='checkbox' name='active[]' value='" . $row2['lid'] . "' checked />"; 
															} else {
																echo "<input type='checkbox' name='active[]' value='" . $row2['lid'] . "' checked />"; 
															}
														}else{
															if($row2['status'] == 'active'){
																echo "<input type='checkbox' name='active[]' value='" . $row2['lid'] . "' />";
															}else{ 
																echo "<input type='checkbox' name='active[]' value='" . $row2['lid'] . "' checked />";
															} 
														} 
													?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
				            </form>
							<?php }else{?> 
                        	<div class="span10" id="storeroom_div" style="width:84% !important; margin-left:1%;"></div>
						<?php } } ?>
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
	<!--mainwrapper-->

	<form id="savePriorityFrm" method="post" action="" name="prioform">
		<input type="hidden" id="priority_type" value="<?php echo $_REQUEST['desc']; ?>" name="priority_type" />
		<input type="hidden" value="" name="itemsPriority" id="itemsPriority" />
		<input type="hidden" value="" name="hidr" id="hidr" />
		<input type="hidden" value="" name="hidDesc" id="hidDesc" />
        <input type="hidden" value="" name="hidgroup" id="hidgroup" />
	</form>
</body>
</html>
<script>
function getitemGroup(desc,g){
	jQuery("#hidgroup").val(g);
	jQuery("#hidDesc").val(desc);
	jQuery('.menu_link_select').removeClass('menu_link_select').addClass('menu_link');
	jQuery('#'+desc+'_'+g).addClass('menu_link_select');
	jQuery("#loading-header").show();
	jQuery.ajax({
		url:'ajax_get_storeroom_priority.php',
		type:'GET',
		data:{desc:desc,g:g},
		success:function(data){
			jQuery("#loading-header").hide();
			jQuery('#itemgroup_div').html(data);
		}							
	});
	if(jQuery('#itemsPriority').val()!=""){
		var itemsPriority = jQuery('#itemsPriority').val();
		jQuery.ajax({
		url:'ajax_get_storeroom_priority.php',
		type:'POST',
		data:{priority_type:desc,itemsPriority:itemsPriority},
			success:function(data){
				jQuery("#loading-header").hide();
				jAlert(data,'Alert Dialog');
				jQuery('#itemsPriority').val('');
			}
		});
	}
}
function getStoreroomPri(desc,r){
	jQuery("#hidr").val(r);
	jQuery("#hidDesc").val(desc);
	jQuery('.menu_link_select').removeClass('menu_link_select').addClass('menu_link');
	jQuery('#'+desc+'_'+r).addClass('menu_link_select');
	jQuery("#loading-header").show();
	jQuery.ajax({
	url:'ajax_get_storeroom_priority.php',
		type:'GET',
		data:{desc:desc,r:r},
		success:function(data){
			jQuery("#loading-header").hide();
			jQuery('#storeroom_div').html(data);
		}
		});
	if(jQuery('#itemsPriority').val()!=""){
		var itemsPriority = jQuery('#itemsPriority').val();
		jQuery.ajax({
		url:'ajax_get_storeroom_priority.php',
		type:'POST',
		data:{priority_type:desc,itemsPriority:itemsPriority},
			success:function(data){
				jQuery("#loading-header").hide();
				jAlert(data,'Alert Dialog');
				jQuery('#itemsPriority').val('');
			}
		});
	}
}
</script>