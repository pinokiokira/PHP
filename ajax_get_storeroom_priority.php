<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");


function getInvItem($item_id,$storeroom_id){
	$sql = mysql_query("select * from location_inventory_counts where inv_item_id = '".$item_id."' AND storeroom_id = '".$storeroom_id."'  order by id desc limit 0,1");
	if($sql && mysql_num_rows($sql) > 0){
		$rs = mysql_fetch_assoc($sql);
		$qty = $rs['quantity'];
	}else{
		$qty = '0.00';
	}
	return $qty;
}



if($_POST['itemsPriority'] != ''){
    $order = explode(',', $_POST['itemsPriority']);
    if($_POST['priority_type'] == 'item'){
        for($i=0;$i<count($order);$i++){
            $priority = $i + 1;
            $id = mysql_real_escape_string($order[$i]);
            $query = "UPDATE location_inventory_storeroom_items SET priority='$priority' WHERE id=" . $id;
            $result = mysql_query($query) or die(mysql_error());
        }
    }else if($_POST['priority_type'] == 'itemgroup'){
        for($i=0;$i<count($order);$i++){
            $priority = $i + 1;
          $id = mysql_real_escape_string($order[$i]);
    	  $query = "UPDATE location_inventory_storeroom_items SET group_item_priority='$priority' WHERE id='".$id."'";
            $result = mysql_query($query) or die(mysql_error());
        }
		
    }
	echo "Change Saved successfully";
	die();
	/*if($_REQUEST['hidr']!="" && $_REQUEST['hidDesc']!=""){

		header("location:setup_backoffice_storeroom_priority.php?r=".$_REQUEST['hidr']."&desc=".$_REQUEST['hidDesc']."&act=save");
		
		exit;
	}else{
		header("location:setup_backoffice_storeroom_priority.php?r=".$_REQUEST['r']."&desc=".$_REQUEST['desc']."&act=save");
		exit;
	}*/
}


if($_GET['desc'] == 'itemgroup' && $_GET['r'] != ''){
    $order = "ORDER BY tbl.group_item_priority ASC";
    $desc = 'itemgroup';
	if($_GET['group'] != ''){
        $group = ' AND ig.id=' . mysql_real_escape_string($_GET['group']);
        $g = $_GET['group'];
    }else{
        $row = mysql_fetch_array($result3);
        //$group = ' AND ig.id=' . $row['id'];
		$group = '';
        $g = $row['id'];
        mysql_data_seek($result3,0);
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
if($_REQUEST['debug']!=''){
	echo '<br>'.$query1.'<br>';
}
$result1 = mysql_query($query1) or die($query1.'<br>'.mysql_error());




//group selection starts here

if(isset($_REQUEST['g']))
{
$g=$_REQUEST['g'];
}
else
{

$g=49;
}
	$order = " ORDER BY description DESC ";

//Select all items for the selected group
$query2 = "(SELECT lii.status,lii.type,lii.id,ii.description as description
		   FROM location_inventory_items lii
		   INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
		   WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active' AND lii.type = 'global' AND (ii.inv_group_id=" . $g. " OR lii.local_group_id=" .$g. "))
		   UNION
		   (SELECT lii.status,lii.type,lii.id,lii.local_item_desc as description
		   FROM location_inventory_items lii
		   WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active' AND lii.type <> 'global' AND lii.local_group_id=" .$g. ")" . $order;		   
$result2 = mysql_query($query2) or die($query2.'<br>'.mysql_error());
}
else
{
$query1 = "SELECT storeroom_id,stroom_id FROM location_inventory_storerooms WHERE location_id = '" . $_SESSION['loc']."' ORDER BY priority asc";
$result1 = mysql_query($query1) or die(mysql_error());
$result_first = mysql_query($query1) or die(mysql_error());
$row_first=mysql_fetch_object($result_first);
$firstR=$row_first->storeroom_id;

if($_GET['r']!=""){
	$firstR=$_GET['r'];
}

if ($firstR) {
   		 $query2 = "SELECT tbl.*,iiu.unit_type FROM(
                    (SELECT lii.id as lid,lisi.id,lii.local_item_id as item_id,lii.local_item_desc as item,ig.description as `group`,lii.local_unit_type as unit_type,lii.type,lii.local_unit_type_qty,lii.local_item_image as image,lisi.priority,lisi.group_item_priority,lii.status,lisi.storeroom_id
                    FROM location_inventory_storeroom_items lisi
                    INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id AND lii.location_id=lisi.location_id
                    LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
                    WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lisi.storeroom_id=" . mysql_real_escape_string($firstR) . " AND lii.type != 'global' $group)
                    UNION ALL
                    (SELECT lii.id as lid,lisi.id, ii.item_id, ii.description as item, ig.description as `group`,ii.unit_type,lii.type,lii.local_unit_type_qty,COALESCE(lii.local_item_image,ii.image) as image,lisi.priority,lisi.group_item_priority,lii.status,lisi.storeroom_id
                    FROM location_inventory_storeroom_items lisi
                    INNER JOIN location_inventory_items lii ON lii.id=lisi.inv_item_id AND lii.location_id=lisi.location_id
                    INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
                    LEFT JOIN inventory_groups ig ON ig.id=ii.inv_group_id
                    WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lisi.storeroom_id=" . mysql_real_escape_string($firstR) . " AND lii.type = 'global' $group)
               )as tbl
               LEFT JOIN inventory_item_unittype iiu ON iiu.id=tbl.unit_type " .$order;
  
    $result2 = mysql_query($query2) or die($query2.'<br>'.mysql_error());
}
}
if($_REQUEST['debug']!=''){
	echo '<br>query2 =>'.$query2.'<br>';
}
?>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.tablednd.0.7.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($){
	
		
	var act='<?php echo $_REQUEST['act']; ?>';
		if(act=="save"){
			jAlert('Change Saved successfully','Alert Diloag');		
		}
        $('select').change(function(){
            $(this).closest('form').submit();			
        });
		/* $("#table-1").dataTable({
			//"bPaginate": false,			
			//"bSerchable": false,
			'bAutoWidth': false,
			"sPaginationType": "full_numbers",
			"lengthMenu": [[-1], ["All"]],
			"aaSorting": [[ 6, "asc" ]],
			
			 "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [0] },
			 				  { 'bSortable': false, 'aTargets': [1] },
			 				  { 'bSortable': false, 'aTargets': [2] },
							  { 'bSortable': false, 'aTargets': [3] },
							  { 'bSortable': false, 'aTargets': [4] },
							  { 'bSortable': false, 'aTargets': [5] },
							  { 'bSortable': false, 'aTargets': [6] }							  
							],
			"drawCallback": function( settings ) {
			 	 $("#table-1").tableDnD({
					onDragClass: "line2",
					onDrop: function(table, row) {
						setSaveHandler();
						var tblRows = table.tBodies[0].rows;
						var priorityOrder = new Array();
						for (var i=0; i<tblRows.length; i++) {
							priorityOrder.push(tblRows[i].id);
						}
						$('#itemsPriority').val( priorityOrder.join() );
					}
				});
			 }					
		}); */
		
        $('#table-1').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				//alert('dd');
				$('.unitselect').msDropDown();
            }
        });
		
        jQuery('#table-1_length').html('').css('height','25px');
		
		$('#table-2').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [],
            "bJQuery": true,
            "fnDrawCallback": function(oSettings) {
                // jQuery.uniform.update();
				//alert('dd');
				$('.unitselect').msDropDown();
            }
        });
		
		/* $("#table-2").dataTable({
			//"bPaginate": false,			
			//"bSerchable": false,
			'bAutoWidth': false,
			"sPaginationType": "full_numbers",			
			"lengthMenu": [[-1], ["All"]],
			"aaSorting": [[ 8, "asc" ]],
			 "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [0] },
			 				  { 'bSortable': false, 'aTargets': [1] },
			 				  { 'bSortable': false, 'aTargets': [2] },
							  { 'bSortable': false, 'aTargets': [3] },
							  { 'bSortable': false, 'aTargets': [4] },
							  { 'bSortable': false, 'aTargets': [5] },
							  { 'bSortable': false, 'aTargets': [6] }							  
							],
			 "drawCallback": function( settings ) {
			 	 $("#table-2").tableDnD({
					onDragClass: "line2",
					onDrop: function(table, row) {
						setSaveHandler();
						var tblRows = table.tBodies[0].rows;
						var priorityOrder = new Array();
						for (var i=0; i<tblRows.length; i++) {
							priorityOrder.push(tblRows[i].id);
						}
						$('#itemsPriority').val( priorityOrder.join() );
					}
				});
			 }				
		}); */
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
		
    });
</script>

		<?php if($_REQUEST['desc']=='itemgroup')
						{ ?>
                        
 <h4 class="widgettitle">Item Group Priority</h4>
							<form id="change_status_form" name="change_status_form" method="post">
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
										
						while($row2 = mysql_fetch_assoc($result2))
						{						
                        $i++; //Add 1 for each item
                        if ($row2['type'] == 'global') {
                            $query3 = "SELECT lii.id loc_item_id,  lic.id, lic.location_id,
                                      lic.storeroom_id, lii.type, lii.inv_item_id,
                                      lic.quantity, ig.description as item_group, lisi.id as lisi_id, lisi.group_item_priority as lisi_group_item_priority, 
									  ig.priority as pr, ii.image as img, ii.description as item,iiu.unit_type as unit,lii.status
                               FROM location_inventory_items lii
                               INNER JOIN location_inventory_counts lic ON lii.id=lic.inv_item_id
							   LEFT JOIN location_inventory_storeroom_items lisi ON lisi.inv_item_id = lic.inv_item_id AND lisi.storeroom_id = lic.storeroom_id
                               INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
                               INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
                               LEFT JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
                               WHERE lii.location_id=" . $_SESSION['loc'] . "
                                    AND lic.location_id=" . $_SESSION['loc'] . "
                                    AND lic.inv_item_id=" . $row2['id'] . "
                               ORDER BY lic.storeroom_id, lic.id DESC";
                        } 
						else {
                            $query3 = "SELECT lii.id loc_item_id,  lic.id, lic.location_id,lisi.id as lisi_id, lisi.group_item_priority as lisi_group_item_priority,
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
						if($_REQUEST['debug']!=''){
							echo '<br><br>'.$query3.'<br><br>';
						}
     
						$result3 = mysql_query($query3) or die(mysql_error());
						if(mysql_num_rows($result3)>0){

										
										
										$row21 = mysql_fetch_array($result3); ?>
										<tr class="gradeX" id="<?php echo $row21['lisi_id'];?>">
											<td><?php echo ucfirst(substr($row21['type'],0,1));?></td>
                                            <td style="text-align: center;"><?php if($row21['img'] != ''){ ?>
                                            <a href="<?php echo APIIMAGE;?>images/<?php echo $row21['img'];?>" class="img">                                            
                                            <img width="80" onerror="this.src='images/defimgpro.png'" src="<?php echo APIIMAGE;?>images/<?php echo $row21['img'];?>" style="width:50px; height:50px;"  alt="No Item Image" />
                                            </a>
											<?php }else{?>
                                            <a href="images/defimgpro.png"><img style="width:50px; height:50px;"   alt="No Item Image" src="images/defimgpro.png"></a>
                                            <?php } ?>										</td>
											
											<td style="text-align:center;"><?php if($row21['status'] == 'active'){?><img src="images/Active, Corrected, Delivered.png" title="Active"><?php }?>
				<?php if($row21['status'] == 'inactive'){?><img src="images/Inactive & Missing Punch.png" title="Inactive"><?php }?></td>
											
											<td><?php echo $row21['item'];?></td>
											<td><?php echo $row21['item_group'];?></td>
											<td><?php echo trim($row21['item']); ?></td>
											<td>
											<?php 
											/*$qry33=mysql_query("select * from location_inventory_storeroom_items where location_id=' $_SESSION[loc]' and inv_item_id='$row2[id]'") or die(mysql_error());
				                      $res33=mysql_fetch_array($qry33);	*/							
									  echo intval($row21['lisi_group_item_priority']); ?>											</td>
											<td><?php echo $row21['unit'];?></td>
											<?php /*?><td>
											<?php 
		$qry44=mysql_query("SELECT * FROM `location_inventory_storeroom_items` where location_id='$_SESSION[loc]' AND inv_item_id='$row2[id]'") or die(mysql_error());
		$res44=mysql_fetch_array($qry44);
	//echo $res44['inv_item_id'].'hello';
	$qry55=mysql_query("SELECT * FROM `location_inventory_counts`  where location_id='$_SESSION[loc]' AND inv_item_id='$res44[inv_item_id]'") or die(mysql_error());
		$cc=0;
		while($res55=mysql_fetch_array($qry55))
		{
		$cc++;
		}
				echo $cc;							
											
											?></td><?php */?>
											
									  </tr>
										<?php 
										}  } ?>
									</tbody>
				                </table>
                              </div>
				            </form>
						
                        <?php } 
						else
						      { ?>
                              
                              <?php if ($firstR) { ?>
 <h4 class="widgettitle">Item Priority</h4>
							<form id="change_status_form" name="change_status_form" method="post">
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
										<?php while ($row2 = mysql_fetch_array($result2)){ ?>
										<tr class="gradeX" id="<?php echo $row2['id'];?>">
											<td><?php echo ucfirst(substr($row2['type'],0,1));?></td>
                                            <td style="text-align: center;"><?php if($row2['image'] != ''){ ?>
												<a href="<?php echo APIIMAGE;?>images/<?php echo $row2['image'];?>"
												class="img"> <img width="80"
													src="<?php echo APIIMAGE;?>images/<?php echo $row2['image'];?>"
													style="width:50px; height:50px;" onerror="this.src='images/defimgpro.png'"  alt="No Item Image" />
											</a> <?php }else{?>
                                            <a href="images/defimgpro.png">
                                            <img style="width:50px; height:50px;"    alt="No Item Image" src="images/defimgpro.png">
                                            </a>
                                            <?php } ?>
											</td>
											<td style="text-align:center;"><?php if($row2['status'] == 'active'){?><img src="images/Active, Corrected, Delivered.png" title="Active"><?php }?>
				<?php if($row2['status'] == 'inactive'){?><img src="images/Inactive & Missing Punch.png" title="Inactive"><?php }?></td>
											<td><?php echo $row2['item'];?></td>
											<td><?php echo $row2['group'];?></td>
											<td><?php echo $row2['item'];?></td>
                                            <td><?php echo $qtyCount = getInvItem($row2['lid'],$row2['storeroom_id']);?></td>                                            
											<td><?php echo $row2['unit_type'];?></td>
                                            <td>
											
											<?php if($desc == 'item'){
												echo intval($row2['priority']);
											} else {
                                            	echo intval($row2['group_item_priority']);
                                        	} ?>
											</td>
                                            <td>
											 <input type='hidden' name="all_items[]" value='<?php echo $row2['lid']; ?>' />
											<?php /*?><td><?php echo $row2['local_unit_type_qty'];?></td><?php */?>
                                            <?php 
											
								if($qtyCount > 0){
									if($row2['status'] == 'inactive'){?>
                                    	<input type='checkbox' name="active[]" value='<?php echo $row2['lid']; ?>' checked />	 
                                    <?php }  
								}else{	
									if($row2['status'] == 'active'){?>                                	
									 <input type='checkbox' name="active[]" value='<?php echo $row2['lid']; ?>' />
								<?php }else{ ?>
									  <input type='checkbox' name="active[]" value='<?php echo $row2['lid']; ?>' checked />	 
								<?php } }	?>		
											
					<?php /*$Check_count = "SELECT quantity FROM location_inventory_counts WHERE location_id = '".$_SESSION['loc']."' AND inv_item_id = '".$row2['lid']."' AND storeroom_id='".$row2['storeroom_id']."' order by id desc limit 1";
					$res_check_count = mysql_query($Check_count);
					$row_check_count = mysql_fetch_assoc($res_check_count);
				if(mysql_num_rows($res_check_count)>0 && $row_check_count['quantity']>0){ ?>
					<td style="text-align: center;">&nbsp;</td>
				<?php  }else{
				if($row2['status'] == 'inactive'){
                            			$inactive = $inactive . $row2['id'] . ","; ?>
                <td  style="text-align: center;"><input type='checkbox' name="inactive[]" value='<?php echo $row2['lid']; ?>' checked /></td>
                <?php } else { ?>
                <td style="text-align: center;"><input type='checkbox' name="active[]" value='<?php echo $row2['lid']; ?>' /></td>
                <?php } 
				}*/
				?>
										</td>	
										</tr>
										<?php } ?>
									</tbody>
				                </table>
                                </div>
				            </form>
						
                              <?php } 
						}
						 ?>

