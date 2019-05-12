<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

$setupHead      = "active";
$setupDropDown  = "display: block;";
$set_back_chefDropDown = "display: block;";

$financeHead    = "active";
$setupDropDown3 = "display: block;";

$setupRetailMenu10 = "active";


function wrap($str){
    if($str != '' && (strlen($str) > 50)){
    	
        return substr($str, 0, 47) . ' <a class="bs-tooltip" title="Details - ' . $str . '" href="#" data-toggle="tooltip" data-placement="top">...</a>';
    }else{
        return $str;
    }
}
function displayImg($img){
    return APIIMAGE.'images/' . str_replace('http://www.softpoint.us/images/','',$img);
}
if(isset($_POST['delete_recipe_id']) && $_POST['delete_recipe_id']>0){
	$delete_detail = mysql_query("DELETE FROM location_inventory_recipe_details WHERE recipe_id = '".$_POST['delete_recipe_id']."'") or die(mysql_error());
	$delete_recipe = mysql_query("DELETE FROM location_inventory_recipe WHERE id = '".$_POST['delete_recipe_id']."'") or die(mysql_error());
	header('location:setup_retail_item_link.php?msg=del');
}

$total_count = mysql_num_rows(mysql_query("SELECT id from location_menu_articles where retail='Yes' AND location_id = '".$_SESSION['loc']."'"));
if($total_count>500 && $_REQUEST['search_txt']=="" && $_REQUEST['dd_group']==""){
	$limit = 0;
}else{
	$limit = 500;
}
$filter_where = "";
if($_REQUEST['search_txt']!=""){
	$search = $_REQUEST['search_txt'];
	$filter_where .= " AND (lma.item like '".$search."%' OR lma.priority like '".$search."%' OR lma.description like '".$search."%' OR lma.article_type like '".$search."%' OR lma.plu like '".$search."%') ";
	$limit = 500;
}
if($_REQUEST['dd_group']>0){
	$filter_where .= " AND (ii.inv_group_id = '".$_REQUEST['dd_group']."' OR lma.defaut_menu_group_id = '".$_REQUEST['dd_group']."' OR lii.local_group_id = '".$_REQUEST['dd_group']."') ";	
	$limit = 500;
}

$query1 = "SELECT lma.id art_id,lma.image,lma.item,lma.defaut_menu_group_id,lmg.menu_group, lir.id as recipe_id,lii.id,lii.manufacturer_barcode,COALESCE(ii.inv_group_id,lii.local_group_id) as group_id,COALESCE(ii.description,lii.local_item_desc) as description,lii.default_vendor,COALESCE(ii.unit_type,lii.local_unit_type) as unit_type,ve.name as vendor_name
           FROM location_menu_articles lma
		   LEFT JOIN location_inventory_recipe as lir ON lir.menu_article_id = lma.id AND lir.type = 'item'
		   LEFT JOIN location_inventory_recipe_details as lird ON lird.recipe_id = lir.id
           LEFT JOIN location_inventory_items lii ON lii.id=lird.inv_item_id
		   LEFT JOIN location_menu_group lmg ON lmg.id=lma.defaut_menu_group_id
		   LEFT JOIN inventory_items as ii ON ii.id = lii.inv_item_id AND lii.type = 'global'
		   LEFT JOIN vendors as ve ON ve.id = lii.default_vendor
           WHERE lma.retail='Yes' AND lma.location_id = " . $_SESSION['loc'] . " $filter_where GROUP BY lma.id limit $limit";
$result1 = mysql_query($query1) or die(mysql_error());

$i=0;
$yield = '';
$shelf_life = '';
$date_rev = '';
$total_oz = '';
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
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}

.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right
		!important;
	background-color: #333333 !important;
}
.action{
	font-size: 18px;
	text-decoration: none;
}
.action:hover{
	text-decoration: none;
}
.action.edit{
	color: #4d8adf;
}
.action.remove{
	color: #d26169;
}
.action.add{
	color: #41aa3f;
}
.tooltip-inner{
	max-width: 360px;
}
.left{
	text-align:left;
}
.dataTables_filter {
    position: absolute;
    right: 10px;
    top: 10px;
}
.dataTables_paginate .paginate_active {
    background: none repeat scroll 0 0 #FFFFFF;
    color: rgb(102,102,102);
}
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($){
		
			
        // dynamic table
        var sortColumns = [];
		var seachColumns = [];
        jQuery('#dyntable > thead > tr > th').each(function(){
        	if ( $(this).hasClass( 'nosort' )) {
        		sortColumns.push( { "bSortable": false } );
            } else {
            	sortColumns.push( null );
            }
			if ( $(this).hasClass( 'nosearch' )) {
        		seachColumns.push( { "bSearchable": false } );
            } else {
            	seachColumns.push( null );
            }
        });
        jQuery('#dyntable').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [[1, "asc" ]],
            "bJQuery": true,
            "aoColumns": sortColumns,
			"aoColumns": seachColumns,
			
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
		jQuery(document).tooltip({selector: '.bs-tooltip'});
		
		
	
		jQuery("#goSearch1").click(function(){
                    
        var total_rec = jQuery('#total_rows').val();
        if(total_rec > 499){
        var search_inpt = jQuery('.go_search1').val();
        if (search_inpt!=null) {
            search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
            if (search_inpt.length > 0) {
                if(search_inpt.length < 3) {
                
                  showCDialog('Alert','Please enter 3 or more characters');
                  jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
                }
                else{
                  jQuery("#go_search_form").submit();
                }
            } else
              showADialog('Enter value to search');
              jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
        } else{
             showADialog(' Enter value to search');
             jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
        }
        }else{
            jQuery("#go_search_form").submit();
        }
             
    });
jQuery('.go_search1').bind("keypress", function(e) {
      if (e.keyCode == 13) {//just enter
        e.preventDefault();
        var total_rec = jQuery('#total_rows').val();
        if(total_rec > 499){
        var search_inpt = jQuery('.go_search1').val();
        if (search_inpt!=null) {
            search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
            if (search_inpt.length > 0) {
                if(search_inpt.length < 3){
                  showADialog(' Please enter 3 or more characters');
                  jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
                }
                else{
                  jQuery("#go_search_form").submit();
                }
            } else
              showADialog(' Enter value to search');
              jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
        } else
              showADialog(' Enter value to search');
              jQuery('#popup_container').css({margin:'-'+(jQuery('.maincontentinner').height() / 16)+'px 0 0 0'});
      }else{
            jQuery("#go_search_form").submit();
        }
      }
    });
        
	

        
    });
</script>
<script type="text/ecmascript" language="javascript">
        function delrec(data){
			 //var data = jQuery(this).data();
		
			jConfirm('Are you sure you want to delete this Item Link?', 'Confirm Delete', function(r) {
                if(r){
                jQuery('#delete_recipe_id').val(data);
                jQuery('#delete_recipe').submit();
            	}
        	});
        }
		</script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
</head>

<body>

	<div class="mainwrapper">

		<?php include_once 'includes/header.php';?>

		<div class="leftpanel">

			<?php include_once 'includes/left_menu.php';?>

		</div>
		<!-- leftpanel -->

		<div class="rightpanel">

			<ul class="breadcrumbs">
				<li><a href="messages.php"><i class="iconfa-home"></i> </a> <span
					class="separator"></span></li>
				<li>Setup</li>
				<li><span class="separator"></span></li>
				<li>Retail</li>
				<li><span class="separator"></span></li>
				<li>Inventory Link</li>
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
            	<div style="position: absolute;margin-top: 11px;right: 22px;">
                <form name="go_search_form" id="go_search_form" action="<?=basename($_SERVER['PHP_SELF'])?>" method="get" style="display: inline;">
                    <select style="height: 44px !important;margin-top: 10px !important;" name="dd_group" id="dd_group" >
                    	 <option value="">- - - Select Group - - -</option>
						 <?php
									//$group_query = mysql_query("SELECT id,description from inventory_groups where Market = 'Retail'");		
									/*$group_query = mysql_query("SELECT ig.id,ig.description 
													from inventory_groups ig
													join location_inventory_items lii on ig.id = lii.local_group_id
													where ig.Market = 'Retail' and lii.location_id = '".$_SESSION["loc"]."'");*/	
									$group_query = mysql_query("SELECT id,menu_group from location_menu_group where location_id = '".$_SESSION["loc"]."' AND market = 'Retail' order by menu_group asc");								 
									 while($grp_row=mysql_fetch_array($group_query)){
									 	$grp_selected ="";
									 	if($_REQUEST['dd_group']==$grp_row['id']){
											$grp_selected = 'selected';
										}
										echo '<option '.$grp_selected.' value="'.$grp_row['id'].'">'.$grp_row["menu_group"].'</option>';
									} ?>
                    </select> 
                    <input type="hidden" name="total_rows" id="total_rows" value="<?php echo $total_count; ?>" >
                    <span><input class="go_search" name="search_txt" type="text" value="<?php if (isset($_GET['search_txt'])) echo $_GET['search_txt']; else echo "";?>" placeholder="<?php echo $_SESSION['Search'];?>" style="height: 32px !important;margin-top: 10px !important;"/></span>
                </form> 
                <button class="btn btn-primary btn-large search_location" id="goSearch1">Go</button>                
            </div>
				<div class="pageicon">
					<span class="iconfa-cog"></span>
				</div>
				<div class="pagetitle">
					<h5>Link your menu articles to your inventory.</h5>
					<h1>Inventory Link</h1>

				</div>
			</div>
			<!--pageheader-->
			<div class="maincontent">
				<div class="maincontentinner">
					<div class="row-fluid">
						<div class="span5" style="width:100%; border: 2px solid #0866C6;">
							<h4 class="widgettitle">Menu Articles</h4>
							<div class="table-holder" style="overflow:auto;">
							<table id="dyntable" class="table table-bordered responsive">
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
                                	<th class="head0 left nosearch">Image</th>
									<th class="head0 left">Menu Article</th>
									<th class="head1 left nosearch">Barcode</th>
									<th class="head0 left nosearch">Group</th>
									<th class="head1 left nosearch">Inventory Items</th>
									<th class="head0 left nosearch">Default Vendor</th>
									<th class="head1 left nosearch">Unit Type</th>									
									<th class="head1 center nosort nosearch" style="width: 4%">Actions</th>
								</tr>
							</thead>
						<tbody>
						<?php $i=0; while($row1 = mysql_fetch_array($result1)){
							$i++; ?>
							<tr class="gradeX">
                            	<?php if($row1['id'] != ''){ 
										$link_url = "setup_retail_item_breakdown.php?action=edit&id=".$row1['id']."&t=".$row1['type'];
										}else{
										$link_url = "setup_retail_item_breakdown.php?action=add&a=".$row1['art_id'];										
									} ?>
                            	<input type="hidden" id="lma_id_<?php echo $i; ?>" value="<?php echo $row1['art_id']; ?>" > 
                            	<!--<td style="text-align: center;"><?php if($row1['image'] != ''){ ?><a class="action" href="<?php echo displayImg($row1['image']);?>"><span class="iconfa-camera-retro"></span></a><?php } ?></td>-->
                                <td style="text-align: center;"><img style="height:50px; width:50px;" onerror="this.src='images/nImage.png'"  src="<?php if($row1['image'] != ''){ echo APIIMAGE.'images/'.$row1['image']; }else{ echo 'images/nImage.png'; } ?>" ></td>
								<td>
									<?php echo $row1['item'];?><br />
									<?php if($row1['defaut_menu_group_id'] != ""){
									echo $row1['menu_group']." (ID:".$row1['defaut_menu_group_id'].")";
									} ?>
								</td>								
								<td>
                                <span class="field input-append" style="width:60%;">                                 
                                <input id="barcode_<?php echo $i; ?>" type="text" onBlur="getbarcode(<?php echo $i; ?>)" onKeyPress="javascript:if(event.keyCode==13){getbarcode(<?php echo $i; ?>);  return false;}" style="width:100%" value="<?php echo $row1['manufacturer_barcode']; ?>">
                                <span class="add-on" style="height:20px;" > 
                                <a href="javascript:void(0);" rel="client" onClick="getbarcode(<?php echo $i; ?>)" class="icon-search" ></a>
                                </span>
                                </span>
                                
                                </td>
								<td><select id="group_<?php echo $i; ?>" rel="<?php echo $i; ?>" style="width:90%">
                                	<option value="">--- Select Group ---</option>
                                    <?php
									//$qry = "SELECT * FROM location_menu_group where location_id = '" . $_SESSION['loc'] . "' AND (market = 'Retail' OR id in(SELECT DISTINCT (menu_group) FROM location_menu_items WHERE location_id='".$_SESSION['loc']."' AND menu_id IN(select id FROM location_menus WHERE location_id='".$_SESSION['loc']."' AND (type='Retail' OR menu='Retail')))) ORDER BY id DESC";
									$qry = "SELECT * FROM location_menu_group where location_id = '" . $_SESSION['loc'] . "' AND market = 'Retail'";
									$group_query = mysql_query($qry);
									 while($grp_row=mysql_fetch_array($group_query)){
									 	$grp_selected ="";
									 	if($row1['group_id']==$grp_row['id']){
											$grp_selected = 'selected';
										}
										echo '<option '.$grp_selected.' value="'.$grp_row['id'].'">'.$grp_row["menu_group"].'</option>';
									} ?>
                                </select></td>
								<td>
                                <select id="inventory_<?php echo $i; ?>" rel="<?php echo $i; ?>" style="width:90%">
                                	<option value="">--- Select Item ---</option>
                                <?php
									if($row1['group_id']>0){
										$inv_query = "(SELECT lii.id,lii.local_item_desc description from location_inventory_items lii 
														JOIN inventory_groups ig ON ig.id= lii.local_group_id AND Market ='Retail'
														WHERE lii.type <>'global' and ig.id='".$row1['group_id']."' AND lii.location_id = '".$_SESSION['loc']."')
														UNION 
														(SELECT lii.id,ii.description from location_inventory_items lii
														JOIN inventory_items ii ON ii.id = lii.inv_item_id 
														JOIN inventory_groups ig ON ig.id= ii.inv_group_id AND Market ='Retail'
														WHERE lii.type ='global' and ig.id='".$row1['group_id']."'  AND lii.location_id = '".$_SESSION['loc']."')";
										$inv_res = mysql_query($inv_query);
										while($inv_row=mysql_fetch_array($inv_res)){
											$inv_selected ="";
											if($row1['id']==$inv_row['id']){
												$inv_selected = 'selected';
											}
											echo '<option '.$inv_selected.' value="'.$inv_row['id'].'">'.$inv_row["description"].'</option>';
										} 
									}
								 ?>
                                 </select>
                                
                                </td>
								<td><input disabled id="vendor_<?php echo $i; ?>" rel="<?php echo $i; ?>" type="text" style="width:90%" value="<?php echo $row1['vendor_name']; ?>"></td>
								<td>
                                <select disabled id="unittype_<?php echo $i; ?>" rel="<?php echo $i; ?>" name="unittype" style="width:90%">
                                <option value="">--- Select Unit Type ---</option>
                                <?php 
                                $unit_query = mysql_query("SELECT id,description from inventory_item_unittype");
									 while($unit_row=mysql_fetch_array($unit_query)){
									 	$unit_selected ="";
										if($row1['unit_type']==$unit_row['id']){
											$unit_selected = 'selected';
										}
										echo '<option '.$unit_selected.' value="'.$unit_row['id'].'">'.$unit_row["description"].'</option>';
									} ?>
                                </select>
                                </td>
								<td style="text-align: center;">
									
									<a style="display:none;" id="save_btn_<?php echo $i; ?>" href="#" rel="<?php echo $i; ?>" class="action edit"><img src="images/Save_receipe.png" ></span></a>
									
									<?php if($row1['id'] == ''): ?>
										<a id="add_btn_<?php echo $i; ?>" href="setup_finance_manage_items_add.php?action=add&menu_art=<?php echo $row1['art_id'];?>"  class="action add"><img  src="images/dodaj.png" ></a>
									<?php endif; ?>
									<?php if($row1['id'] != ''): ?>
										<a onClick="delrec('<?php echo $row1['recipe_id'];?>')" href="#" id="delete" class="action remove" data-id="<?php echo $row1['id'];?>"><span class="icon-trash"></span>
                                        </a>
									<?php endif; ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					</div>
					</div>
					
					<!--footer-->

				</div>
                <?php include_once 'includes/footer.php';?>
				<!--maincontentinner-->
			</div>
			<!--maincontent-->
			
		</div>
		<!--rightpanel-->

	</div>
	<!--mainwrapper-->
	<form method="post" id="delete_recipe">
	    <input type="hidden" id="delete_recipe_id" name="delete_recipe_id" value="" />
	</form>
</body>
</html>
<script>
jQuery("[id*='group_']").on('change',function(){
	var rel_id = jQuery(this).attr('rel');
	var val = jQuery(this).val();
	var item_id = jQuery('#lma_id_'+rel_id).val();
	console.log(item_id);
	jQuery.ajax({
		url:'GetretailItems.php',
		type:'POST',
		data:{grp_id:val,item_id:item_id},
		success:function(data){
			jQuery('#inventory_'+rel_id).html(data);
		}
	});	
	
});
jQuery("[id*='inventory_']").on('change',function(){
	var rel_id = jQuery(this).attr('rel');
	var val = jQuery(this).val();
	var man_id = jQuery('#lma_id_'+rel_id).val();
	jQuery.ajax({
		url:'check_receip_menu.php',
		type:'POST',
		data:{menu_id:man_id,item_id:val},
		dataType: 'json',
		success:function(result){
			var resultList = result.map(function (item) {
				var rec_found = item.rec_found;
				var vendor = item.vendor;
				var unit_type = item.unit_type;
				jQuery('#vendor_'+rel_id).val(vendor);
				jQuery('#unittype_'+rel_id).val(unit_type);
				if(rec_found=='Yes'){
					jQuery('#save_btn_'+rel_id).hide();
					jQuery('#add_btn_'+rel_id).show();
				}else{
					jQuery('#save_btn_'+rel_id).show();
					jQuery('#add_btn_'+rel_id).hide();
				}
				
			});
			
			
			
		}	
	});
});
jQuery("[id*='save_btn_']").on('click',function(){
	var values = "";
	jQuery("[id*='save_btn_']:visible").each(function(){
		var rel_id  = jQuery(this).attr("rel");
		if(values==""){
			values = jQuery('#inventory_'+rel_id).val()+'|'+ jQuery('#lma_id_'+rel_id).val();
		}else{
			values = values +','+ jQuery('#inventory_'+rel_id).val()+'|'+ jQuery('#lma_id_'+rel_id).val();
		}
	});	
	
	/*var rel_id = jQuery(this).attr('rel');
	var val = jQuery('#inventory_'+rel_id).val();
	var man_id = jQuery('#lma_id_'+rel_id).val();*/
	jQuery.ajax({
		url:'save_receip_menu_article.php',
		type:'POST',
		data:{values:values},		//menu_id:man_id,item_id:val
		success:function(data){
			if(data>0){
				window.location.href='setup_retail_item_link.php?msg=add';
			}else{
				jAlert('Error Occured. Please Try Agian!','Alert Dialog');
			}
		}
	});	
});
function getbarcode(rel_id){
	 var barcode = jQuery('#barcode_'+rel_id).val();
	 if(barcode!=""){
	jQuery.ajax({
		url:'barcode_search_from_retallink.php',
		type:'POST',
		data:{barcode:barcode},
		dataType: 'json',
		success:function(result){
			var resultList = result.map(function (item) {
				if(item.found=='Yes'){
					jQuery('#group_'+rel_id).val(item.group_id);
					jQuery('#vendor_'+rel_id).val(item.vendor);
					jQuery('#unittype_'+rel_id).val(item.unit_type);										
					getItemsbygrp(item.group_id,rel_id,item.item_id);
					jQuery('#save_btn_'+rel_id).show();
					jQuery('#add_btn_'+rel_id).hide();
				}else{
					jQuery('#save_btn_'+rel_id).hide();
					jQuery('#add_btn_'+rel_id).show();
					jAlert('Barcode Not Found!','Alert Dialog');
				}
			});
		}
	});
	}
};

function getItemsbygrp(grp_id,rel_id,sel_item){
	//console.log(grp_id); console.log(rel_id); console.log(sel_item);
	jQuery.ajax({
		url:'GetretailItems.php',
		type:'POST',
		data:{grp_id:grp_id},
		success:function(data){
			jQuery('#inventory_'+rel_id).html(data);
			if(sel_item!=""){
				jQuery('#inventory_'+rel_id).val(sel_item);
			}
		}
	});	
}
jQuery(document).ready(function($){
		var msg='<?php echo $_REQUEST['msg']; ?>';
		if(!(msg=="")){
			if(msg=="add"){
				
				jAlert('Item Link Added Succussfully.','Recipe Added', function(){					
				});
			}
			if(msg=="del"){
				
				jAlert('Inventory Link has been deleted succussfully.','Recipe Deleted', function(){					
				});
			}		
		}
});
</script>            
