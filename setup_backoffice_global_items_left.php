<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
require_once('require/openid-config.php'); 

$group_id  = '';
$marketval ='';
$sqlGroup = '';
$sqlmarket='';
if (isset($_REQUEST['group_id'])&&trim($_REQUEST['group_id'])!='') {
	$group_id = $_REQUEST['group_id'];
	$sqlGroup =  " AND ig.id=".$group_id;
}

if (isset($_REQUEST['market'])&& trim($_REQUEST['market'])!='') {
	$marketval = $_REQUEST['market'];	
	$sqlmarket =  " AND ig.Market='".$marketval."'";	
}

if(isset($_REQUEST['vendor'])&& trim($_REQUEST['vendor'])!='') {
	$vendor_val = trim($_REQUEST['vendor']);
	$vendor_and_where = " AND ii.vendor_default = '". $vendor_val ."' ";
} else {
	$vendor_val = '';
	$vendor_and_where = '';
}

if($vendor_val != '' || $sqlGroup!=''){
 		 $query1 = "SELECT ii.id,ii.description,ii.item_id,ig.description as `group`,iiu.unit_type, lii.default_brand,  lii.default_price,ii.image,ii.model_number,ii.brand,ii.manufacturer 
          FROM inventory_items ii
          LEFT OUTER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
          INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
          LEFT OUTER JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
          WHERE ii.`status`='active'
		  AND ii.id NOT IN (SELECT DISTINCT(inv_item_id) FROM location_inventory_items WHERE location_id=" . $_SESSION['loc'] . " AND inv_item_id > 0)
		  " .$sqlmarket . $vendor_and_where . $sqlGroup . "
          GROUP BY ii.id ORDER BY `group` ASC, description ASC";

	if(isset($_REQUEST['debug']) && $_REQUEST['debug']=='1'){
		echo '<br />........query1:'. $query1;
	}
	$result1 = mysql_query($query1) or die(mysql_error());
	
}
?>


<table class="table table-bordered table-infinite" id="global_tbl" >
								<colgroup>
									<col class="con0" style="width:8%;"/>
									<col class="con1" style="width:45%;"/>
									<col class="con0" style="width:30%;"/>
									<!--<col class="con1" style="width:8%;"/>-->
									<col class="con1" style="width:12%;"/>
									<!--<col class="con1" style="width:9%;"/>-->
                                    <col class="con0" style="width: 5%;"/>
								</colgroup>
								<thead>
									<tr>
										<th class="head0 center">Image</th>
										<th class="head1 center">Item</th>
										<th class="head0 center">Manufacturer</th>
										<!--<th class="head1 center">Def. Pack</th>-->
										<th class="head1 center">Def. Unit</th>
										<!--<th class="head1 center">Def. Price</th>-->
                                        <th class="head0 center">
                                        	<input type="checkbox" id="chk_all_gi" name="chk_all_gi" />
                                        </th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($query1!=''){
										$result1 = mysql_query($query1) or die(mysql_error());
										while($row1 = mysql_fetch_array($result1)){ ?>                                    	
	                                    <tr class="gradeX g_item" id="<?php echo $row1['id']; ?>" style="height:17px;">
	                                        <td><img onerror="this.src='images/noimage.png'" src="<?php echo APIIMAGE.'images/'.$row1['image']; ?>" style="height:30px; width:30px;" ></td>
	                                        <td><?php echo $row1['description']. ' (ID:'.$row1['item_id'].' UID:'.$row1['id'].')';?></td>
	                                        <td><?php echo $row1['manufacturer'].' '.$row1['brand'].' '.$row1['model_number']; ?></td>
	                                        <!--<td><?php echo $row1['default_pack']; ?></td>-->
	                                        <td><?php echo $row1['unit_type']; ?></td>
	                                        <!--<td style="text-align:right;"><?php echo $row1['default_price']; ?></td>-->
                                            <td class="head0 center">
                                                <input type="checkbox" id="chk_gi_<?php echo $row1['id']; ?>" name="chk_gi[]" class="chk_gi" data-row_id="<?php echo $row1['id']; ?>" />
                                            </td>
	                                    </tr>
	                                <?php } 
									}
									?>
								</tbody>
							</table>

<script type="text/javascript">
 jQuery(document).ready(function($){
        // dynamic table
        jQuery('#global_tbl').dataTable({
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
			"bDestroy": true,
            "bJQuery": true,
            oLanguage: {
				sLengthMenu: "Show _MENU_",
                sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
				oPaginate: {
                    sNext: " >",
                    sPrevious: "<",
                    sLast:">>",
                    sFirst:"<<"
                }
            }
        });
		
		var selectedList = new Array();
        var globalSel = new Array();
        var localSel = new Array();
        $("#global_tbl").selectable({			
            filter: ".g_item",
            stop: function() {
				jQuery('#add').removeClass('disabled');
				jQuery('#add').addClass("btn-success");
                globalSel = [];
                $( ".ui-selected", this ).each(function() {
                    globalSel.push( $(this).attr('id') );
                });
            }
        });
});		
</script>