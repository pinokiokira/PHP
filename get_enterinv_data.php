<?php 
include_once 'includes/session.php';
include_once("config/accessConfig.php");


if($_REQUEST['debugTime']!=''){
	echo '<br> 7 =>'.date('H:i:s');
}

if($_GET['getGrops']=='yes' && $_GET['baseMArket']!=''){
	$st = $_REQUEST['st'];
	$groups = array();
	$res_group = mysql_query("SELECT id,description FROM inventory_groups WHERE Market = '".$_REQUEST['baseMArket']."' order by description ");
	$res_group = mysql_query("SELECT DISTINCT(tab.id) as id,tab.description FROM (
							(SELECT ig.id, ig.description
							FROM location_inventory_storeroom_items lisi 
							INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
							INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id 
							WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type != 'global' AND lisi.storeroom_id='".$st."' AND ig.Market = '".$_REQUEST['baseMArket']."') 
							UNION ALL 
							(SELECT ig.id, ig.description
							FROM location_inventory_storeroom_items lisi 
							INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
							INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id 
							INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id  
							WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type = 'global' AND lisi.storeroom_id='".$st."'
							AND ig.Market = '".$_REQUEST['baseMArket']."')) as tab ORDER BY tab.description");
	if($res_group && mysql_num_rows($res_group)>0){
		while($row_group = mysql_fetch_assoc($res_group)){
			$groups[] = $row_group;
		}			
	}
	echo json_encode($groups);
	exit();
}
if($_REQUEST['debugTime']!=''){
	echo '<br> 36 =>'.date('H:i:s');
}

if($_GET['st'] != ''){
    $inv = array();
    $st = mysql_real_escape_string($_GET['st']);
	$strwhere = '';
	if($_GET['ddmarket']!=''){
		$strwhere .=  " AND ig.Market = '".$_GET['ddmarket']."'";
	}
	if($_GET['ddgroup']!=''){
		$strwhere .=  " AND ig.id = '".$_GET['ddgroup']."'";
	}

   $query2 = "(SELECT lii.id,lii.local_item_desc description,lii.default_brand as default_brand,lii.default_vendor,lii.default_price,iiu.conversion_group,
   				iiu.id as default_unit,TRUNCATE(lisi.priority,0) as priority,gc.symbol,v.name as vendor_name,ig.description as group_name,'' as global_vendor_name,lii.type as item_type,lii.status               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
               INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
			   left JOIN inventory_items ii ON ii.id=lii.inv_item_id
			   LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
			   LEFT JOIN vendors as v ON v.id = lii.default_vendor			   
               WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.type != 'global' AND lisi.storeroom_id='".$st."'".$strwhere.")
               UNION ALL
               (SELECT lii.id,ii.description,COALESCE(lii.default_brand,ii.brand) as default_brand,lii.default_vendor,lii.default_price,iiu2.conversion_group,
			   iiu2.id as default_unit,TRUNCATE(lisi.priority,0) as priority,gc.symbol,v.name as vendor_name,ig.description as group_name,gv.name as global_vendor_name,lii.type as item_type,lii.status 
               FROM location_inventory_storeroom_items lisi
               INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id
               INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
			   INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
               LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id=ii.unit_type
			   LEFT JOIN locations as l on l.id = lisi.location_id
			   LEFT JOIN global_currency as gc ON gc.id = l.currency_id 
			   LEFT JOIN vendors as v ON v.id = lii.default_vendor
			   LEFT JOIN vendors as gv ON gv.id = ii.vendor_default
               WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.type = 'global' AND lisi.storeroom_id='".$st."'".$strwhere.")
               ORDER BY priority ASC,group_name ASC,description ASC";//description 
			   

	if($_REQUEST['debug']!=''){
		echo '<br>'.$query2.'<br><br>';
	}
    $result2 = mysql_query($query2) or die(mysql_error());
    while($row2 = mysql_fetch_row($result2)){
         	$query3 = "select inv_item_id,unit_type, sum(quantity) as quantity
                   from location_inventory_counts lic
                   WHERE inv_item_id=$row2[0] AND storeroom_id=$st AND id >=(
                       COALESCE((SELECT max(id)
                           FROM location_inventory_counts lic
                           WHERE inv_item_id=$row2[0] AND lic.Type='Count' AND storeroom_id=$st),0)
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
		if($row2[9]=='' && $row2[11]!=''){
			$row3['vendor_name'] = $row2[11];
		}
		$row3['group_name'] = $row2[10];
        if($row3['inv_item_id'] == ''){//set inv_item_id if no records exist in counts table for item
            $row3['inv_item_id'] = $row2[0];
        }
		if(strtolower($row2[12])=='local'){
			$row3['item_type'] = 'L';
		}else if(strtolower($row2[12])=='prep'){
			$row3['item_type'] = 'P';
		}else{
			$row3['item_type'] = 'G';
		}
		$row3['status'] = $row2[13];	
        $inv[] = $row3;
    }
}
if($_REQUEST['debugTime']!=''){
	echo '<br> 121 =>'.date('H:i:s');
	echo "<br>";
	echo "<pre>";print_r($inv);
}


/*echo "<pre>";
print_r($inv);
die;
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
);*/

if($_REQUEST['debugTime']!=''){
	echo '<br> 154 =>'.date('H:i:s');
}


$unit_types5 = array();
	 /*$query10 = "SELECT *
		   FROM inventory_item_unittype
		   ORDER BY conversion_group,unit_type";*/
	
	$query10 = "SELECT * from inventory_item_unittype ORDER BY conversion_group, unit_type";
		   
$result10 = mysql_query($query10) or die(mysql_error());
while ($row10 = mysql_fetch_assoc($result10)) {
	$unit_types5[] = $row10;
}

function returnUnitTypes($unit_types5,$selected,$default){
    $response = '';
	
    if($selected >0){
        $select = $selected;
		
    }else{
        $select = $default;
    }

   
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
			
			/*$response .= '<option '.$selected.' value="'.$unit['id'].'" data-description="'. $unit['description'] .'" >'.$unit['unit_type']."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$unit['description'].'</option>';*/

			$response .= '<option '.$selected.' value='.$unit['id'].' data-description='. $unit['description'] .' >'.$unit['unit_type'].'</option>';

		}
    return $response;
}



if($_REQUEST['debugTime']!=''){
	echo '<br> 203 =>'.date('H:i:s');
}

if (isset($_REQUEST['get_availability'])) {
	
	
	$unit_type = $_REQUEST['unit_type'];
	$l_id = $_REQUEST['location_id'];
    $st = $_REQUEST['storeroom_id'];
	$inv_item_id = $_REQUEST['inv_item_id'];
 	//	 $token1 = md5('location=' . $_SESSION['loc'] . 'backofficesecure12');
    $token1 = md5('location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website' . 'backofficesecure12');
	$token = base64_encode(strtotime('-7 hours')."+0");
	$marktmp = $market;
	$group_idtmp = $group_id;
	$keywwds = $keyword;
	$token1tmp = $token1;
	$tokentmp = $token;
	$sttemp = $st;
	$available = 0;
	
	$qry = mysql_query("SELECT SUM(lic.quantity) AS qty
	FROM location_inventory_counts lic 
	LEFT JOIN employees ON lic.employee_id=employees.id 
	LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
	WHERE inv_item_id='".$inv_item_id."' AND lic.location_id='".$l_id."' AND lic.storeroom_id IN (".$st.") AND inventory_item_unittype.id = '".$unit_type."'");
	
	if(mysql_num_rows($qry) > 0){
		$fet_qty = mysql_fetch_array($qry);
		$available = $fet_qty['qty'];
		if($available == null){
			$available = 0;
		}
		echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available));
		exit;
	}
	
	echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available));
	exit;

}

?>
<style>
	#storeroom_items_tbl td {
    vertical-align: middle;
}
	#storeroom_items_tbl_filter{ 
		/*right:60px !important;*/
		top: 11px;
	}
	.ddtabledrop1 {
		right: 48px !important;
		margin-top: 9px;
		margin-right: 15px;
	}
	#msdrpdd20_child{
		position: sticky !important;
	}
</style>
<script type="text/javascript">
	jQuery('.unit_type').change(function() {
		
		var unit_type_selected = jQuery(this).val();
		var location_id = '<?php echo $_SESSION['loc']; ?>';
		var storeroom_id = '<?php echo $_GET['st']; ?>';
		var inv_item_id = jQuery(this).data('inv_item_id');
		
		
		console.log('unit_type_selected : '+unit_type_selected);
		console.log('location_id : '+location_id);
		console.log('storeroom_id : '+storeroom_id);
		console.log('inv_item_id : '+inv_item_id);
		
		jQuery.ajax({
			method: "POST",
			url: "get_enterinv_data.php",
			data: { get_availability:1,inv_item_id: inv_item_id, unit_type: unit_type_selected, location_id: "<?php echo $_SESSION['loc']; ?>", storeroom_id: "<?php echo $_GET['st']; ?>" }
		}).done(function( available ) {
			var sd = JSON.parse(available);
			console.log('sd.ResponseMessage : '+sd.ResponseMessage);
			jQuery('.qty_total_'+inv_item_id).html(sd.ResponseMessage); 
		});
	});
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#storeroom_items_tbl').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				$('.unitselect').msDropDown();
            }
        });
		$('.cnt_input').keyup(function(e){
           if($(this).val()!=''){
				if(!isNumber($(this).val())){
					jAlert('This field must be a number!');
					$(this).val('');
				}else{
					enable();
				}
			}
			if (e.which == 40) {				
				var ths = jQuery(this).parent().parent().next();				
				jQuery(ths).find('.cnt_input').focus();
			}else if (e.which == 38) {
				var ths = jQuery(this).parent().parent().prev();				
				jQuery(ths).find('.cnt_input').focus();
			}
			
        });
		
		$('#count_frm').change(function(){
             enable();            
        });
		
		
		//var offset = jQuery("#storeroom_items_tbl_length select").offset();
		//var left = eval(offset.left) + eval(jQuery("#storeroom_items_tbl_length select").width())+ 100;
		//jQuery(".ddtabledrop").css('left',left+'px');
		
		
		setfilterdd();
		
		
		
		jQuery("#storeroom_items_tbl_wrapper").scroll(function(x,y){
			
			setfilterdd();
		});
		
    });
	jQuery(window).resize(function(){
		setfilterdd();
	});
	
	function setfilterdd(){
		var offset = jQuery("#storeroom_items_tbl_filter").offset();
		var wnwidth = jQuery(window).width();
		var spanw = jQuery(".span9").width();
		var wnwidth = eval(wnwidth) - eval(spanw);
		console.log(offset.left);
		var left = eval(offset.left) - eval(jQuery("#storeroom_items_tbl_length select").width())-  eval(wnwidth) +60;
		jQuery(".ddtabledrop").css('left',left+'px');
		var tableWidth = jQuery("#storeroom_items_tbl").width();
		tableWidth=tableWidth-22;
		jQuery(".dataTables_length, .dataTables_info").width(tableWidth);
	}
	function isNumber(n) {
	    return !isNaN(parseFloat(n)) && isFinite(n);
	}
	var first=true;
	
	function getItems(){
		jQuery("#loading-header").show();
		var st = '<?=$_GET['st'];?>';
		var ddmarket = jQuery("#ddmarket").val();
		var ddgroup = jQuery("#ddgroup").val();
		jQuery.ajax({
			url:'get_enterinv_data.php?st='+st+'&ddmarket='+ddmarket+'&ddgroup='+ddgroup,
			type:"POST",
			success:function(data){
				jQuery("#loading-header").hide();
				jQuery('#ent_inv_data').html(data);	
				jQuery('.unitselect').msDropDown();
				
			}			
		});
	}
	
	function getSearchitemPop(){
		var st = '<?=$_GET['st'];?>';
		jQuery.ajax({
			url:'get_location_item_notinstoreroom.php',
			type:'POST',
			data:{st:st},
			success:function(data){
				jQuery("#search_st_item_modal_body").html(data);
				jQuery("#search_st_item_modal").modal('show');
			}
		});
	}
	
	function getGrouponMarket(market){
		var html = '<option value="">- - - Select Group - - -</option>';
		if(market!=''){			
			jQuery.ajax({
					url:'get_enterinv_data.php?getGrops=yes&baseMArket='+market+'&st=<?=$_GET['st'];?>',
					type:"GET",
					dataType:'json',
					success:function(data){						
						if(data!='' && data!=null && data.length>0){
							for(i=0;i<data.length;i++){
								html = html +'<option value="'+data[i].id+'">'+data[i].description+'</option>';
							}
						}
						jQuery("#ddgroup").html(html);
					}			
				});
		}else{
			jQuery("#ddgroup").html(html);
		}
		
	}
	
		       
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
	
	                    <?php if($_GET['st'] != ''){ 
						if($_REQUEST['debugTime']!=''){
							echo '<br> 362 =>'.date('H:i:s');
						}
						?>
                        
                        	<span style="position: absolute; z-index: 9; margin-top: 11px; width:25%;margin-left:142px !important;"  class="ddtabledrop">
                                	<select name="ddmarket" onchange="getGrouponMarket(this.value)" id="ddmarket" style="width:40%; max-width:180px;">
                                    	<option value="">- - - Select Market - - -</option>
                                        <?php 
											$res_market = mysql_query("SELECT DISTINCT(tab.Market) FROM (
															(SELECT ig.Market 
															FROM location_inventory_storeroom_items lisi 
															INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
															INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id 
															WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type != 'global' AND lisi.storeroom_id='".$st."') 
															UNION ALL 
															(SELECT ig.Market
															FROM location_inventory_storeroom_items lisi 
															INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
															INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id 
															INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id  
															WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type = 'global' AND lisi.storeroom_id='".$st."')) as tab ORDER BY tab.Market");
											if($res_market && mysql_num_rows($res_market)>0){
												while($row_market = mysql_fetch_assoc($res_market)){
													$selmarket = '';
													if($_REQUEST['ddmarket']==$row_market['Market']){
														$selmarket = 'selected="selected"';
													}
													echo '<option '.$selmarket.' value="'.$row_market['Market'].'">'.$row_market['Market'].'</option>';
												}
											}
											
											
										?>
                                    </select>
                                    <?php 
									if($_REQUEST['debugTime']!=''){
										echo '<br> 399 =>'.date('H:i:s');
									}
									?>
                                    <select name="ddgroup" id="ddgroup" style="width:40%; max-width:180px;">
                                    	<option value="">- - - Select Group - - -</option>
                                        <?php 
										
										
											if($_REQUEST['ddmarket']!=''){
												//$res_group = mysql_query("SELECT id,description FROM inventory_groups WHERE Market = '".$_REQUEST['ddmarket']."' order by description");
												$res_group = mysql_query("SELECT DISTINCT(tab.id) as id,tab.description FROM (
																		(SELECT ig.id, ig.description
																		FROM location_inventory_storeroom_items lisi 
																		INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
																		INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id 
																		WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type != 'global' AND lisi.storeroom_id='".$st."' AND ig.Market = '".$_REQUEST['ddmarket']."') 
																		UNION ALL 
																		(SELECT ig.id, ig.description
																		FROM location_inventory_storeroom_items lisi 
																		INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id 
																		INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id 
																		INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id  
																		WHERE lisi.location_id='".$_SESSION['loc']."' AND lii.type = 'global' AND lisi.storeroom_id='".$st."'
																		AND ig.Market = '".$_REQUEST['ddmarket']."')) as tab ORDER BY tab.description");
												if($res_group && mysql_num_rows($res_group)>0){
												while($row_group = mysql_fetch_assoc($res_group)){
													$selgroup = '';
													if($_REQUEST['ddgroup']==$row_group['id']){
														$selgroup = 'selected="selected"';
													}
													echo '<option '.$selgroup.' value="'.$row_group['id'].'">'.$row_group['description'].'</option>';
												}
											}
											}
										?>
                                    </select>
                                    <?php 
									if($_REQUEST['debugTime']!=''){
										echo '<br> 435 =>'.date('H:i:s');
									}
									?>
                                    <input type="button" class="btn btn-primary" onclick="getItems();" value="Go" style="margin-top:-6px;height:30px;" />
                                    
                                </span>
                                <!--<span style="position: absolute; z-index: 9; width:2%;"  class="ddtabledrop1">
                                    	 <input type="button" class="btn btn-primary" onclick="getSearchitemPop();" value="Add"  />
                                </span>-->
                                
                                
	                        <form id="count_frm" name="count_frm" method="post">
	                            <input type="hidden" value="submitted" name="count_submit" />
	                            <input type="hidden" value="<?=$st?>" name="storeroom" />	                            
								<table class="table table-bordered table-infinite" id="storeroom_items_tbl" >
                                	
									<!-- <colgroup>
                                    
                                    	<col class="con0" style=""/>
										<col class="con1" style=""/>
										<col class="con0"/>
										<col class="con1" style=""/>
										<col class="con0" style=""/>
										<col class="con1" style=""/>
										<col class="con0" style=""/>
										<col class="con1" style=""/>
										<col class="con0" style=""/>
                                        <col class="con1" style=""/>
                                        <col class="con0" style=""/>
                                        <col class="con1" style=""/>
									</colgroup> -->

									<colgroup>
                                    
                                    	<col class="con0" style="width: 2%;">
										<col class="con1" style="width: 2%;">
										<col class="con0" style="width: 26%;">
										<col class="con1" style="width: 8%;">
										<col class="con0" style="width: 4%;">
										<col class="con1" style="width: 15%;">
										<col class="con0" style="width: 10%;">
										<col class="con1" style="width: 10%;">
										<col class="con0" style="width: 10%;">
                                        <col class="con1" style="width: 5%;">
                                        <col class="con0" style="width: 5%;">
                                        <col class="con1" style="width: 5%;">
									</colgroup>

									<thead>
										<tr>
                                        	<th class="head0" style="">P</th>
											<th class="head1">T</th>
                                            <th class="head0">Item Description</th>
                                            <th class="head0">Pack Unit</th>
											<th class="head1 center">Qty</th>
											<th class="head0">Master Unit of Measure</th>
											<th class="head1">Count</th>
											<th class="head0">Brand</th>
											<th class="head1">Vendor</th>
											<th class="head0">Pack</th>
											<th class="head1">Cost</th>
                                            <th class="head0">Value</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										if($_REQUEST['debugTime']!=''){
											echo '<br> 435 =>'.date('H:i:s');
										}
										foreach($inv as $val){
											$qty = $val['quantity']!=''?$val['quantity']:0;
											if($val['status'] == 'inactive' && $qty == 0){
											
											}else{
											
											
											$qry = 	mysql_query("SELECT SUM(lic.quantity) AS qty
													FROM location_inventory_counts lic 
													LEFT JOIN employees ON lic.employee_id=employees.id 
													LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
													WHERE inv_item_id='".$val['inv_item_id']."' AND lic.location_id='".$_SESSION['loc']."' AND lic.storeroom_id='".$_GET['st']."' AND inventory_item_unittype.id = '".$val['unit_type']."'");
											$fet_qty = mysql_fetch_array($qry);
											 ?>
		                                    <tr class="gradeX g_item" id="<?php echo $row1['id']; ?>">
		                                    	
		                                        <input type="hidden" value="<?=$val['inv_item_id']?>" name="item[]" />
                                                <td style=""><?=$val['priority']?></td>	
                                                <td style=""><?=$val['item_type']?></td>	
		                                        <td><?=$val['description'].'<span style="float:right;"> - '.$val['group_name'].'</span>';?>
												</td>
		                                        <td>
		                                        	<?php if($val['unit_type']!=""){																
																	 $xx = $val['default_unit_type'];
																 }else{ 
																	$xx = $val['default_unit_type'];
                                                                        
                                                                } 

                                                                $x = mysql_query("SELECT * FROM inventory_item_unittype WHERE id='".$xx."'");
                                                                $x1=mysql_fetch_array($x);

                                                                echo $x1['description'];
                                                                ?>
		                                        </td>
		                                        <td class="right qty_total_<?php echo $val['inv_item_id'] ?>"><?=$fet_qty['qty']?></td>
		                                        <td>
												
													<div class="selectouter12 select_w3">
													<select name="unit[]" class="unitselect" style="margin: 0px;">
														<option value="" >Unit Type</option>
														  <?php
															$unittype = "SELECT * from inventory_item_unittype ORDER BY conversion_group, unit_type";
															$res_unit_type = mysql_query($unittype);
															
															$opt_lbl = '';
															while($row_unit_type= mysql_fetch_array($res_unit_type)){ 
																if($opt_lbl != $row_unit_type['conversion_group']){
																	echo '<option value="" style="text-align: center; font-weight: bold; width: 100%;" disabled >'. ucfirst($row_unit_type['conversion_group']) .'</option>';
																}
																$opt_lbl = $row_unit_type['conversion_group'];
															?>
															
														  <option data-description="<?php echo $row_unit_type['description']; ?>" value="<?php echo $row_unit_type['id']; ?>"><?php echo $row_unit_type['unit_type']; ?></option>
														  <?php } ?>
													</select>
													</div>
													
													<?php /*<select name="unit[]" class="" style="margin: 0px;">
		                                                <option value="">Unit Type</option>
                                                                
                                                                <?php if($val['unit_type']!=""){																
																	echo returnUnitTypes($unit_types5,$val['unit_type'],$val['default_unit_type']);
																 }else{ 
																	
																	echo returnUnitTypes($unit_types5,$val['unit_type'],$val['default_unit_type']);
                                                                        
                                                                } ?>
													</select>*/?>
		                                        </td>
		                                        <td>
		                                            <input type="text" class="cnt_input" style="width:80% !important;margin: 0px;" name="count[]" />
		                                        </td>
		                                        <td><?=$val['default_brand']?></td>
		                                        <td><?=$val['vendor_name']?></td>
		                                        <td><?=$val['default_pack']?></td>
		                                        <td class="right"><?=$val['symbol'].''.number_format($val['default_price'],2,'.','')?></td>
                                                <?php 
												$qty = $fet_qty['qty']!=''?$fet_qty['qty']:0;
												$price = $val['default_price']!=''?$val['default_price']:0;
												$tval = $qty * $price;
												?>
                                                <td class="right"><?=$val['symbol'].''.number_format($tval,2,'.','')?></td>
			                                </tr>
		                                <?php } }?>
									</tbody>
								</table>
                                <?php 
									if($_REQUEST['debugTime']!=''){
										echo '<br> <br> 532 =>'.date('H:i:s').'<br> <br>';
									}
								?>
									
	                        </form>
	                    <?php } ?>

						