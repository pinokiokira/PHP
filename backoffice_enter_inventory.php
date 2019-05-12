<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

$backofficeDropDown  = "display:block;";
$backofficeHead 	 = "active";
$inventoryHead       = "active";
$inventoryDropDown   = "display:block;";
$inventoryMenu3      = "active";

/*
print_r("SELECT employees.emp_id FROM employees INNER JOIN employeee_master ON employeee_master.email = employees.email WHERE employeee_master.empmaster_id = '".$_SESSION['empmaster_id']."' LIMIT 1");
exit;*/
/* $emp_id = mysql_fetch_array(mysql_query("SELECT employees.emp_id FROM employees INNER JOIN employees_master ON employees_master.email = employees.email WHERE employees_master.empmaster_id = '".$_SESSION['empmaster_id']."' LIMIT 1"));
$emp_id['emp_id'] = '13109'; */
$emp_id = mysql_fetch_array(mysql_query("SELECT id as emp_id from employees WHERE location_id = '".$_SESSION['loc']."'"));
if($_POST['count_submit'] == 'submitted'){
    $updated_items = array();

    $storeroom = mysql_real_escape_string($_POST['storeroom']);
    $date = date('Y-m-d');
    $time = date('H:i:s');
	
	//echo "<pre>"; print_r($_POST);
    for($i=0;$i<count($_POST['count']);$i++){
        if($_POST['unit'][$i] != ''){
            $count = mysql_real_escape_string($_POST['count'][$i]);
            $unit  = mysql_real_escape_string($_POST['unit'][$i]);
            $item  = mysql_real_escape_string($_POST['item'][$i]);

            $query = "INSERT INTO location_inventory_counts SET
                            location_id=" . $_SESSION['loc'] . ",
                            storeroom_id='$storeroom',
                            inv_item_id='$item',
                            Type='Count',
                            date_counted='$date',
                            time_counted='$time',
                            quantity='$count',
                            unit_type='$unit',
							created_by = '" . $emp_id['emp_id'] . "',
							created_on ='BusinessPanel',
							created_datetime='".date('Y-m-d H:i:s')."',
                            employee_id=" . $emp_id['emp_id'];
            $result = mysql_query($query) or die(mysql_error());
            $updated_items[] = $item;
        }
    }
    file_get_contents(API.'/Panels/VendorPanel/inventory.php?items=' . implode(',',$updated_items));
	header('location:backoffice_enter_inventory.php?st='.$storeroom.'&msg=ok');
}

$query1 = "SELECT storeroom_id,stroom_id FROM location_inventory_storerooms lis
           WHERE location_id='" . $_SESSION['loc'] . "' ORDER BY priority,stroom_id ASC";
$result1 = mysql_query($query1) or die(mysql_error());

$getCount = "select sum(tb.cnt) as totalcount from (
(SELECT count(lisi.id) as cnt
               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
               LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
               WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type != 'global' AND lisi.storeroom_id in (SELECT storeroom_id
           FROM location_inventory_storerooms lis
           WHERE location_id='".$_SESSION['loc']."'
           ORDER BY priority,stroom_id ASC))
               UNION ALL
(SELECT count(lisi.id) as cnt
               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
               INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
               LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id=ii.unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
               WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type = 'global' AND lisi.storeroom_id in (SELECT storeroom_id
           FROM location_inventory_storerooms lis
           WHERE location_id='".$_SESSION['loc']."'
           ORDER BY priority,stroom_id ASC))
) as tb";
$res_cnt = mysql_query($getCount);
$invCount = 0;
if($res_cnt && mysql_num_rows($res_cnt)>0){
	$row_cnt = mysql_fetch_assoc($res_cnt);
	$invCount = $row_cnt['totalcount'];
}

if($_GET['st'] != ''){
    $inv = array();
    $st = mysql_real_escape_string($_GET['st']);

   $query2 = "(SELECT lii.id,lii.local_item_desc description,lii.default_brand,lii.default_vendor,lii.default_price,iiu.conversion_group,iiu.id as default_unit,TRUNCATE(lisi.priority,0) as priority,gc.symbol,v.name as vendor_name,ig.description as group_name
               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
			   LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
               LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
			   LEFT JOIN vendors as v ON v.id = lii.default_vendor
               WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.type != 'global' AND lisi.storeroom_id=$st)
               UNION ALL
               (SELECT lii.id,ii.description,lii.default_brand,lii.default_vendor,lii.default_price,iiu2.conversion_group,iiu2.id as default_unit,TRUNCATE(lisi.priority,0) as priority,gc.symbol,v.name as vendor_name,ig.description as group_name
               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
               INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
			   LEFT JOIN inventory_groups ig ON ig.id=ii.inv_group_id                    
               LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id=ii.unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
			   LEFT JOIN vendors as v ON v.id = lii.default_vendor
               WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.type = 'global' AND lisi.storeroom_id=$st)
               ORDER BY priority ASC, group_name ASC,description ASC ";//description 
    $result2 = mysql_query($query2) or die(mysql_error());
    while($row2 = mysql_fetch_row($result2)){
         	$query3 = "select inv_item_id,unit_type, sum(quantity) as quantity
                   from location_inventory_counts lic
                   WHERE inv_item_id=$row2[0] AND storeroom_id=$st AND id >=(
                       COALESCE((SELECT max(id)
                           FROM location_inventory_counts lic
                           WHERE inv_item_id=$row2[0] AND Type='Count' AND storeroom_id=$st),0)
                   )
                   ORDER BY id DESC";
	$result3 = mysql_query($query3);		
	$row3 = mysql_fetch_assoc($result3);
	$row3['description'] = $row2[1];
	$row3['default_brand'] = $row2[2];
	$row3['default_vendor'] = $row2[3];
	$row3['default_price'] = $row2[4];
	$row3['conversion_group'] = $row2[5];
	$row3['default_unit_type'] = $row2[6];
	$row3['priority'] = $row2[7];
	$row3['symbol'] = $row2[8];
	$row3['vendor_name'] = $row2[9];
	$row3['group_name'] = $row2[10];
	
	
	if($row3['inv_item_id'] == ''){ $row3['inv_item_id'] = $row2[0]; }
	$inv[] = $row3;
    }
}
//arrays to hold unit types for each group
$volume = array();
$weight = array();
$package = array();

$query4 = "SELECT *
           FROM inventory_item_unittype
           ORDER BY conversion_group,unit_type ASC";
$result4 = mysql_query($query4) or die(mysql_error());
while($row4 = mysql_fetch_assoc($result4)){
    switch($row4['conversion_group']){
        case 'volume':
            $volume[] = $row4;
            break;
        case 'weight':
            $weight[] = $row4;
            break;
        case 'package':
            $package[] = $row4;
            break;
        default:
            $package[] = $row4;
    }
}

$unit_types = array(
    'volume' => $volume,
    'weight' => $weight,
    'package' => $package
);

function returnUnitTypes($selected,$default,$group,$unitArr){
    $response = '';
	
    if($selected >0){
        $select = $selected;
    }else{
        $select = $default;
    }
	$grp_whr='';
	if($group!=""){
	$grp_whr= " AND conversion_group = '".$group."'";
	}
$unit_types5 = array();
	 $query10 = "SELECT *
		   FROM inventory_item_unittype where 1 $grp_whr
		   ORDER BY conversion_group desc,unit_type";
		
$result10 = mysql_query($query10) or die(mysql_error());
while ($row10 = mysql_fetch_assoc($result10)) {
	$unit_types5[] = $row10;
}
   // foreach($unitArr[$group] as $unit){
   
	foreach($unit_types5 as $unit){
        if($select == $unit['id']){
			$selected = "selected='selected'";
		}else{
			$selected ='';
		}
		if($opt_lbl != $unit['conversion_group']){
			$response .= '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($unit['conversion_group']) .'</option>';
		}
		$opt_lbl = $unit['conversion_group'];
		
		$response .= '<option '.$selected.' value="'.$unit['id'].'" data-description="'. $unit['description'] .'" >'.$unit['unit_type'].'</option>';
    }
    return $response;
}

$unit_types2 = array();
$query9 = "SELECT * FROM inventory_item_unittype
		   ORDER BY conversion_group,unit_type";
$result9 = mysql_query($query9) or die(mysql_error());
while ($row9 = mysql_fetch_assoc($result9)) {
	$unit_types2[] = $row9;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/dd.css" type="text/css">

<link rel="stylesheet" href="css/responsive-tables.css">
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<!-- <script type="text/javascript" src="js/jquery.uniform.min.js"></script> -->
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.dd.js"></script>
<!-- [if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif] -->
<script type="text/javascript">
    var intRoomHeight = jQuery('.widgetcontent').height();
	jQuery(document).ready(function($) {
		
		get_enterinv_data('<?php echo $_REQUEST['st'] ?>');
		<?php
		if(isset($_REQUEST['msg']) && $_REQUEST['msg']=='ok'){?>
			jAlert('Storeroom Item Updated Successfully!','Alert Dialog');
		<?php } ?>
        $('#storeroom_items_tbl').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				//alert('dd');
				$('.unitselect').msDropDown();
            }
			 
        });
		jQuery("#storeroom_items_tbl_length").live('change',function(){
			$('.unitselect').msDropDown();
		});
		jQuery("#storeroom_items_tbl_filter input").live('keyup',function(){
			$('.unitselect').msDropDown();
		});		
		$('.unitselect').msDropDown();

        $('.cnt_input').keyup(function(e){
			if($(this).val()!=''){
				if(!isNumber($(this).val())){
					jAlert('This field must be a number!');
					$(this).val('');
				}else{
					enable();
				}
			}
			console.log(e.which);
			if (e.which == 40) {
				console.log('down pressed');
				var ths = jQuery(this).parent().parent().next();
				jQuery(ths).css('color','green');
				jQuery(ths).find('.cnt_input').focus();
			}else if (e.which == 38) {
				console.log('up pressed');
				var ths = jQuery(this).parent().parent().prev();
				jQuery(ths).css('color','#ff0000');
				jQuery(ths).find('.cnt_input').focus();
			}
        });
		
		$('#count_frm').change(function(){
             enable();            
        });
    });
	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}
	var first=true;
		       
	function enable(){
	    if(first){	
			jQuery('#submit_btn').addClass("btn-primary");
	        jQuery('#submit_btn').removeAttr("disabled").click(function(){
				var submits = true;
				jQuery('.cnt_input').each(function(){
					jQuery(this).closest('tr').find('.selectouter12').css({'border':'1px solid #c9c9c9'});
					console.log(jQuery(this).val());
					if(jQuery(this).val()!=''){
						if(jQuery(this).closest('tr').find('select').val()==''){
							jQuery(this).closest('tr').find('.selectouter12').css({'border':'1px solid #FF0000'});
							submits = false;
							return false;
						};
					}
				});	
				if(submits){
	            	jQuery('#count_frm').submit();
				}
	        });
	        first = false;
	    }
	}
</script>

<style>
  .ddtabledrop1{
    margin-right: 0 !important;
  }
.line3 {background-color: #808080;}
.btn-primary.disabled, .btn-primary[disabled]{ background: none repeat scroll 0 0 #0866c6; border-color: #0a6bce; color: #fff; }
.btn.disabled, .btn[disabled]{ opacity: 0.90; }
.selectouter12 { 
	background: none repeat scroll 0 0 #ffffff; border: 1px solid #c9c9c9; float: left; height: 32px; 
	line-height: 5px; margin: 0 0 7px; position: relative;width: 200px;
}
.dd .ddArrow { background-position: 8px 2px; }
._msddli_ {	width: 100% !important; }
.dd .ddChild li .ddlabel { margin: 0px;	width: 50%; }
.dd .ddChild li .description { margin: 0px; width: 50%; }
.dd .ddChild li.disabled .ddlabel {	width: 65% !important; }
</style>
</head>

<body>
<?php 
$result1 = mysql_query($query1) or die(mysql_error());
$i=0; while($row1 = mysql_fetch_assoc($result1)){
$class = "";
/*
if(!isset($_GET['st']) && $i==0){ ?> <script>

window.location = 'backoffice_enter_inventory.php?st=<?=$row1['storeroom_id'];?>&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)';</script> <?php }*/
if($row1['storeroom_id'] == $_GET['st']){$class="class='line3'";}?>

<?php $i++; } ?>
<div class="mainwrapper">
    
    <?php include_once 'require/top.php';?>
    
    <div class="leftpanel">
        
        <?php include_once 'require/left_nav.php';?>
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Inventory <span class="separator"></span> Internal Inventory <span class="separator"></span> Enter Inventory</li>
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
                <button id="submit_btn" disabled="disabled" class="btn btn-large">Submit</button>
				<span class="ddtabledrop1">
					<input type="button" class="btn btn-large btn-primary" onclick="getSearchitemPop();" value="Add"  />
				</span>
            </div>
            <div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Display Enter Inventory Information</h5>
                <h1>Enter Inventory</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
					<div class="span3" style="width:24%;">
						<div class="clearfix"><h4 class="widgettitle">Storerooms</h4></div>
                        <div class="widgetcontent">
						<table class="table table-bordered table-infinite" id="tblstore">
							<colgroup>
								<col class="con0" style=""/>
								<col class="con1" style=""/>
							</colgroup>
							<thead>
								<tr><th class="head0">Name</th></tr>
							</thead>
							<tbody>
	                            <?php
								 	$result1 = mysql_query($query1) or die(mysql_error());
									if(mysql_num_rows($result1)==0){ ?>
										<script>
											jAlert('There is no Storerooms Configured, Please go to Setup!<?=$invCount;?>','Alert Dialog');
                                        </script>
                                        
									<?php } 
								 	$i=0; while($row1 = mysql_fetch_assoc($result1)){
	                                $class = "";
									/*if(!isset($_GET['st']) && $i==0){ ?> <script>
									
									window.location = 'backoffice_enter_inventory.php?st=<?=$row1['storeroom_id'];?>&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)';</script> <?php }*/
	                                if($row1['storeroom_id'] == $_GET['st']){$class="class='line3'";}?>
	                                <tr <?=$class;?> id="<?=$row1['storeroom_id'];?>" style="cursor:pointer;">
	                                    <td onClick="get_enterinv_data2(<?= $row1['storeroom_id'] ?>)"><?=$row1['stroom_id'];?></td>
	                                </tr>
									
	                            <?php $i++; } ?>
							</tbody>
						</table>
					</div>
                  </div>
                
					<div class="span9" style="float:right;margin-left: 10px;">
						<div class="clearfix">
                            <h4 class="widgettitle">Storeroom Items</h4>
						</div>
						<div class="widgetcontent" id="ent_inv_data">	
	                    <?php /*if($_GET['st'] != ''){ ?>
	                        <form id="count_frm" name="count_frm" method="post">
	                            <input type="hidden" value="submitted" name="count_submit" />
	                            <input type="hidden" value="<?=$st?>" name="storeroom" />
	                            
								<table class="table table-bordered table-infinite" id="storeroom_items_tbl" >
									<colgroup>
                                    	<col class="con0" style=""/>
										<col class="con0" style=""/>
										<col class="con1"/>
										<col class="con0" style=""/>
										<col class="con1" style=""/>
										<col class="con0" style=""/>
										<col class="con1" style=""/>
										<col class="con0" style=""/>
										<col class="con1" style=""/>
                                        <col class="con0" style=""/>
									</colgroup>
									<thead>
										<tr>
                                        	<th class="head0" style="">P</th>
											<th class="head0">Item</th>
											<th class="head1 center">Qty</th>
											<th class="head0">Unit</th>
											<th class="head1">Count</th>
											<th class="head0">Brand</th>
											<th class="head1">Vendor</th>
											<th class="head0">Pack</th>
											<th class="head1">Price</th>
                                            <th class="head1">Value</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											if($invCount==0){ ?>
												<script>
													jAlert('There are no Inventory Items Configured, Please go to Setup!','Alert Dialog');
												</script>
											<?php }
										foreach($inv as $val){
											 ?>
		                                    <tr class="gradeX g_item" id="<?php echo $row1['id']; ?>">
		                                    	
		                                        <input type="hidden" value="<?=$val['inv_item_id']?>" name="item[]" />
                                                <td style=""><?=$val['priority']?></td>	
		                                        <td><?=$val['description'].'<br>'.$val['group_name']?></td>
		                                        <td class="right"><?=$val['quantity']?></td>
		                                        <td>
		                                            <div class="selectouter12 select_w3">
                                                    <select name="unit[]" class="unitselect" style="margin: 0px;">
		                                                <option value="">Unit Type</option>
                                                                
                                                                <?php if($val['unit_type']!=""){ ?>
                                                                
		                                                		<?=returnUnitTypes($val['unit_type'],$val['default_unit_type'],$val['conversion_group'],$unit_types);?>
                                                                <?php }else{ 
																	
																	echo returnUnitTypes($val['unit_type'],$val['default_unit_type'],$val['conversion_group'],$unit_types);
                                                                        /*foreach ($unit_types2 as $type) { ?>
                                                                        	<option value="<?php echo $type['id']; ?>"><?php echo $type['unit_type']; ?></option>
                                                                //<?php   }
                                                                } ?>
		                                            </select>
                                                    </div>
		                                        </td>
		                                        <td>
		                                            <input type="text" class="cnt_input" style="width:90% !important;margin: 0px;" name="count[]" />
		                                        </td>
		                                        <td><?=$val['default_brand']?></td>
		                                        <td><?=$val['vendor_name']?></td>
		                                        <td><?=$val['default_pack']?></td>
		                                        <td class="right"><?=$val['symbol'].''.$val['default_price']?></td>
                                                <?php 
												$qty = $val['quantity']!=''?$val['quantity']:0;
												$price = $val['default_price']!=''?$val['default_price']:0;
												$tval = $qty * $price;
												?>
                                                
                                                <td class="right"><?=$val['symbol'].''.number_format($tval,2,'.','')?></td>
			                                </tr>
		                                <?php } ?>
									</tbody>
								</table>
									
	                        </form>
	                    <?php }  */
						 ?>
                         <span>Select StoreRoom</span>

						</div>
					</div>
                  </div>
                
                  <?php include_once 'require/footer.php';?>
                <!--footer-->
               </div><!--row-fluid--> 
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->

</body>
</html>
<script>
	function getSearchitemPop(){
		var st = '<?=$_REQUEST['st'];?>';
		if(st > 0){
			jQuery.ajax({
				url:'get_location_item_notinstoreroom.php',
				type:'POST',
				data:{st:st},
				success:function(data){
					jQuery("#search_st_item_modal_body").html(data);
					jQuery("#search_st_item_modal").modal('show');
				}
			});
		} else {
			jAlert('Please select any of the Storerooms','Alert Dialog');
		}
		
	}
	function get_enterinv_data2(st){
		window.location.href = 'backoffice_enter_inventory.php?st=' + st;
	}
	function get_enterinv_data(st){
		if(st.length > 0){
			jQuery("#loading-header").show();
			jQuery('.line3').removeClass('line3');
			jQuery('#'+st).addClass('line3');
			jQuery.ajax({
				url:'get_enterinv_data.php?st='+st,
				type:"POST",
				success:function(data){
					jQuery('#ent_inv_data').html(data);	
					jQuery('.unitselect').msDropDown();
					jQuery("#loading-header").hide();
				}			
			});
		}
	}
	
	function Submit_items_tostRoom(){
		jQuery.ajax({
			url:'get_location_item_notinstoreroom.php?r_type=sub_st_item',
			type:'POST',
			data: jQuery('#submit_inv_store').serialize(),
			success:function(data){
				if(data){
					jQuery("#search_st_item_modal").modal('hide');
					jAlert('Item added successfully','Alert Dialog',function(r){
						if(r){
							getItems();
						}
					});
					
				}
			}
		});	
	}
	
</script>

<div id="search_st_item_modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add Item to selected Storeroom</h3>
	</div>
	<div class="modal-body" id="search_st_item_modal_body" style="height:500px; min-height:500px;">
	</div>
    <div class="modal-footer" style="text-align:center;">
        <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
        <button class="btn btn-primary" onClick="Submit_items_tostRoom()" >Submit</button>
    </div>
</div> 
