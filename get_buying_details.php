<?php
    require_once 'require/security.php';
	include 'config/accessConfig.php';

	$empmaster_id=$_SESSION['client_id'];
	$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
	$login_vendor_id = $vender['StorePoint_vendor_Id'];
	
	$f = mysql_fetch_array(mysql_query("SELECT location_link FROM vendors WHERE id = '".$login_vendor_id."'"));

	$vendor_id = $_REQUEST['vendor_id'];
	

	$query_act = "SELECT p.vendor_purchases_id,p.status,p.buying_vendor_id,p.vendor_id,
	   CASE p.status
	   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
	   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
	   when 'Shopping'   then DATE_FORMAT(p.shopping_datetime,'%Y-%m-%d %H:%i')
	   END as order_datetime,
	 vt.code as terms,p.subtotal,
	 (select COALESCE(sum(ordered_tax_percentage),0) FROM vendor_purchases_items WHERE vendor_purchases_id =p.vendor_purchases_id) AS tax_total,
	 p.total,p.comments,CONCAT(e.first_name,' ',e.last_name) as emp_name,p.vendor_invoice_num
	 FROM vendor_purchases as p
	 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id				 
	 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
	 WHERE p.status IN('Shopping','Ordered','Shipped')  AND p.vendor_id='".$vendor_id."' order by p.order_datetime desc";
	$res_act = mysql_query($query_act) or die(mysql_error());

	function getstatus($status){
		switch($status){
		case 'Cancelled':
			$image = '<img src="images/Closed, Cancelled & Terminated - 16.png" title="Cancelled">';
			break;
		case 'Completed':
			$image = '<img src="images/Active, Corrected, Delivered - 16.png" title="Completed">';
			break;
		case 'Ordered':
			$image = '<img src="images/Ordered - 16.png" title="Ordered">';
			break;
		case 'Shipped':
			$image = '<img src="images/Shipped - 16.png" title="Shipped">';
			break;
		case 'Shopping':
			$image = '<img src="images/Shopping - 16.png" title="Shopping">';
			break;
		default:
			$image ='';
		}
		return $image;
	}



	/* if(isset($_GET['action']) && $_GET['action'] == 'addVendorItem'){
		$vendor_id = $_GET['vendor_id'];
		$login_vendor_id = $_GET['login_vendor_id'];

		vendor_id = 29 AND buying_vendor_id = 513 AND STATUS=Shopping AND buying_vendor_purchase_order=1 AND subtotal=403 AND tax_total=0 AND total=403 applied_amount= shoppingdate='today'
created_on = 'Vendorpanel' AND created_by = '513' AND created_datetime ='today'

		$vender_purchase_qry = "INSERT INTO vendor_purchases(vendor_id,buying_vendor_id,status,buying_vendor_purchase_order,subtotal,tax_total,total,shoppingdate,created_on,created_by,created_datetime)
		VALUES('".."','".."','".."','".."','".."','','')";

		die;
	} */
?>
<style>
.row_style{
	padding: 2px !important;
}
</style>
<script type="text/javascript" src="js/elements.js"></script>
<div class="tabbedwidget tab-primary display_details">
	<ul>
		<li><a href="#e-1" class="items_data">Items</a></li>
		<li><a href="#e-2" class="purchase_orders">Purchase Orders</a></li>
	</ul>
	<div id="e-1">
		<form method="post">
		<input type="hidden" name="submit_purchase_order" value="1">
		<input type="hidden" name="login_vendor_id" value="<?= $login_vendor_id ?>">
		<input type="hidden" name="vendor_id" value="<?= $vendor_id ?>">
		<table id="detail_table_item" class="table table-bordered responsive" style="height: auto !important; width: 100%">
			<colgroup>
				<col class="con0" style="width:5%;"/>
				<col class="con1" style="width:21%;"/>
				<col class="con0" style="width:8%;"/>
				<col class="con1" style="width:7%;"/>
				<col class="con0" style="width:10%;"/>
				<col class="con1" style="width:10%;"/>
				<col class="con0" style="width:7%;"/>
				<col class="con1" style="width:7%;"/>
				<col class="con0" style="width:7%;"/>
				<col class="con1" style="width:11%;"/>
				<col class="con1" style="width:4%;"/>
			</colgroup>
			<thead>
				<tr>
					<th class="head0" >S</th>
					<th class="head1" >Name</th>
					<th class="head0" >Pack Unit Type</th>
					<th class="head1" >Qty in Pack</th>
					<th class="head0" >Qty In Pack Unit Type</th>
					<th class="head1" >Qty In Pack Size</th>
					<th class="head0" >Tax</th>
					<th class="head1" >Price</th>
					<th class="head0" >Qty</th>
					<th class="head1 right" >Spot Price</th>
					<th class="head1" >A</th>
				</tr>
			</thead>
			<tbody id="">
				<?php
				if (!empty($_REQUEST['vendor_id'])) {

					$sql_vendor_items = "SELECT *, inventory_items.description AS name,inventory_items.id AS inv_id,inventory_groups.description as groups,vendor_items.status AS sta FROM vendor_items 
					LEFT JOIN inventory_items ON vendor_items.inv_item_id=inventory_items.id 
					LEFT JOIN inventory_groups ON inventory_groups.id=inventory_items.inv_group_id
					WHERE vendor_items.vendor_id = ".$_REQUEST['vendor_id']."";
					$sql_vendor_items_res = mysql_query($sql_vendor_items);
					$total = 0;
					$i=1;
					while ($row = mysql_fetch_assoc($sql_vendor_items_res)) {
						
						$pack_unittype = ($row['pack_unittype']) ? $row['pack_unittype'] : '';
						$unittype = "SELECT * from inventory_item_unittype WHERE id = '".$pack_unittype."' ORDER BY conversion_group, unit_type";
						$pack_unittype_qry = mysql_query($unittype);
						$pack_unittype_fetch = mysql_fetch_array($pack_unittype_qry);
						
						$qty_in_pack_unittype = ($row['qty_in_pack_unittype']) ? $row['qty_in_pack_unittype'] : '';
						$unittype = "SELECT * from inventory_item_unittype WHERE id = '".$qty_in_pack_unittype."' ORDER BY conversion_group, unit_type";
						$qty_in_pack_unittype_qry = mysql_query($unittype);
						$qty_in_pack_unittype_fetch = mysql_fetch_array($qty_in_pack_unittype_qry);
						
						if($row['sta'] == 'active'){
							$status =  "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
							$dis = '';
						} else {
							$status = "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
							$dis = 'readonly';
						}
						echo '<tr id="'.$row['inv_id'].'">';
						echo '<td class="row_style center status_image_'.$row['inv_id'].'" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.$status.'</td>';
						echo '&nbsp&nbsp&nbsp<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle; color:black;"><b>'.(($row['name']) ? trim($row['name']) : '').' </b>(ID: '.$row['inv_id'].')<br>Group: '.(($row['groups']) ? $row['groups'] : '').'</td>';
						echo '<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.$pack_unittype_fetch['unit_type'].'</td>';
						echo '<td class="row_style right" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.(($row['qty_in_pack']) ? $row['qty_in_pack'] : '').'</td>';
						echo '<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.$qty_in_pack_unittype_fetch['unit_type'].'</td>';
						echo '<td class="row_style right" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.(($row['qty_in_pack_size']) ? $row['qty_in_pack_size'] : '').'</td>';
						
						echo '<td class="row_style right" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.(($row['tax_percentage']) ? $row['tax_percentage'] : '').'</td>';
						echo '<td class="row_style right" style="line-height:15px;word-break: break-all !important;vertical-align:middle;">'.(($row['price']) ? $row['price'] : '').'</td><input type="hidden" name="id[]" value="'.$row['id'].'">';
						echo '<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle;"><input '.$dis.' class="quantity removeronly_'.$row['inv_id'].'" onKeyPress="return isNumberKey(event)" data-tax="'.$row['tax_percentage'].'" data-price="'.$row['price'].'" data-id="'.$row['inv_id'].'" style="width:94%;height:23px;padding:0;margin-top:5px;margin-bottom:5px;text-align:right;" type="text" name="qty[]" id="qty"></td>';
						echo '<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle;"><input readonly value="" style="width:98%;height:23px;text-align:right;padding:0;margin-top:5px;margin-bottom:5px;" class="cal_price_'.$row['inv_id'].' myprice spotprice" type="text" name="price[]" id="price"></td>';
						echo '<td class="row_style center" style="line-height:15px;word-break: break-all !important;vertical-align:middle;"><img class="change_status" data-id="'.$row['inv_id'].'" src="images/Edit.png" style="height:10px;width:10px;"></td>';
						echo '</tr>';
						$i++;
					}
				}
				?>
			</tbody>
	  </table>
		<div style="margin-top:20px;margin-left:275px;">
			<h5 style="float:right;margin-top:5px;/*margin-right:10px;*/">Total : <b class="total">0.00</b></h5>
			<input type="submit" value="Submit Purchase Orders" style="display:none" class="btn btn-primary active_submit_button" id="submit_purchase_order" >
			<button type="button" class="btn btn-default deactive_submit_button">Submit Purchase Orders</button>
		</div>
		</form>
	</div>
	<div id="e-2">
	<div>
		<?php 
			if (mysql_num_rows($res_act) > 0) {
		?>
		  <table id="detail_table_purchase" class="table table-bordered responsive" style="height: auto !important; width: 100%">
			<colgroup>
				<col class="con1" style="width:5%;" />
				<col class="con0" style="width:10%;"/>
				<col class="con1" style="width:10%;"/>
				<col class="con0" style="width:10%;"/>
				<col class="con1" style="width:10%;"/>
				<col class="con0" style="width:10%;"/>
				<col class="con1" style="width:5%;"/>
			</colgroup>
			<thead>
			  <tr>
				<th class="head1 center" >S</th>
				<th class="head0" >Date & Time</th>
				<th class="head1" >INVOICE#</th>
				<th class="head0" >Order By</th>
				<th class="head1" >Terms</th>
				<th class="head0" >Total</th>
				<th class="head1 center" >A</th>
			  </tr>
			</thead>
			<tbody id="">
				<?php 
				
					while($row_act = mysql_fetch_array($res_act)){
						
						$vender  = mysql_fetch_array(mysql_query("select first_name,last_name from employees_master where StorePoint_vendor_Id=".$row_act['buying_vendor_id']));
						
						if($row_act['tax_total']!=""){
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					?>
						<tr onClick="get_detail(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
							<td class="center" style="vertical-align:middle;"><a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['vendor_purchases_id']; ?>&loc_id=<?php echo $f['location_link']; ?>" ><?php echo getstatus($row_act['status']); ?></a></td>
							<td>&nbsp<?php echo $row_act['order_datetime']; ?></td>
							<td><?php echo $row_act['vendor_id'].'-'.$row_act['vendor_invoice_num']; ?></td>
							<td><?php if($vender['first_name'] != "") echo $vender['first_name'].' '.$vender['last_name']; else echo "-";?></td>
							<td><?php echo $row_act['terms']; ?></td>
							<td class="right"><?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
							<td class="center" style="vertical-align:middle;">
							<a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['vendor_purchases_id']; ?>&loc_id=<?php echo $f['location_link']; ?>" >
								<img src="images/icons/search.png" >
							</a></td>
						</tr>
					<?php 
					} 
				
				?>
			</tbody>
		  </table>
		<?php
			}else{
				echo "<h5>No purchase order to display</h5>";
			}
		?>
	  </div>
	</div>
</div>

<script>

jQuery('#submit_purchase_order').attr('data-login_vendor_id', '<?php echo $login_vendor_id; ?>');
jQuery('#submit_purchase_order').attr('data-vendor_id', '<?php echo $_GET['vendor_id']; ?>');

var total = 0;
/* jQuery("#qty").live('keyup', function(){
	console.log(jQuery(this).val().length);
	var rowid = jQuery(this).closest('tr').prop('id');
	if(jQuery(this).val().length > 0){

		console.log(jQuery(this).data('price'));
		console.log(jQuery(this).data('id'));
		console.log(jQuery(this).data('tax'));

		var calPrice = jQuery(this).val() * jQuery(this).data('price');

		jQuery('.cal_price_'+rowid).val(parseFloat(calPrice).toFixed(2));
		//var calPrice = jQuery('.cal_price_'+rowid).val();
		console.log('calPrice : '+parseFloat(calPrice).toFixed(2));
		total += parseFloat(calPrice);

		jQuery('.total').html(total.toFixed(2));

		jQuery('.active_submit_button').show();
		jQuery('.deactive_submit_button').hide();
	}else{
		jQuery('.cal_price_'+rowid).val('');
		jQuery('.active_submit_button').hide();
		jQuery('.deactive_submit_button').show();
	}
	console.log('Total : '+total);

}); */

/*jQuery(".quantity").keyup(function () {
    var calPrice = jQuery(this).val() * jQuery(this).data('price');
    updateTotal();
    jQuery('.total').html(total.toFixed(2));
    if (total > 0) {
        jQuery('.active_submit_button').show();
        jQuery('.deactive_submit_button').hide();
    } else {
        jQuery('.active_submit_button').hide();
        jQuery('.deactive_submit_button').show();
    }
    sum = 0;
    let rowid = jQuery(this).data('id');
    if (jQuery(this).val().length > 0 && jQuery('.cal_price_' + rowid).val().length == 0) {
        jQuery('.cal_price_' + rowid).val(parseFloat(calPrice).toFixed(2));
    }
    jQuery('.myprice').each(function () {
        sum += Number(jQuery('.myprice').val());
    });
});

jQuery(".myprice").keyup(function () {
    var calPrice = jQuery(this).val() * jQuery(this).data('price');
    updateTotal();
    if (total > 0) {
        jQuery('.active_submit_button').show();
        jQuery('.deactive_submit_button').hide();
    } else {
        jQuery('.active_submit_button').hide();
        jQuery('.deactive_submit_button').show();
    }

});

function updateTotal() {
    let t = 0;
    jQuery('.quantity').each(function (i, obj) {
        let dom = jQuery(obj);
        t += (dom.val() * jQuery(`.cal_price_${dom.data('id')}`).val());
    });
    total = parseFloat(t);
    console.log(total)
    jQuery('.total').html(total.toFixed(2));

}*/

jQuery('.change_status').click(function(){
	var id = jQuery(this).data('id');
	jConfirm('Are you sure you want to change status?', 'Confirm!', function(r) {
		if(r){
			jQuery.ajax({
				url:'buying.php',
				type:'GET',
				data: { action: "change_inv_status", id: id },
				success:function(data){
					console.log(data);
					window.location="buying.php?&vendor_id=<?php echo $vendor_id ?>";
					/* if(data){
						jQuery('.status_image_'+id).html("<img src='images/Active, Corrected, Delivered.png' title='Active'>");
						jQuery('.removeronly_'+id).removeAttr("readonly");
					}
					if(data===0){
						jQuery('.status_image_'+id).html("<img src='images/Inactive & Missing Punch.png' title='Inactive'>");
						jQuery('.removeronly_'+id).attr("readonly","readonly");
					} */
				}
			});
		}
	});
	
});

jQuery(".quantity").keyup(function () {
    console.log(jQuery(this).val());
	
	//jQuery.session.set("compareLeftContent", "MYNEWVALUE");

    var rowid = jQuery(this).closest('tr').prop('id');
    let t = 0;
    jQuery('.quantity').each(function (i, obj) {
        let dom = jQuery(obj);
        t += (dom.val() * dom.data('price'));
    });
    total = parseFloat(t);
    // var calPrice = jQuery(this).val() * jQuery(this).data('price');
    var calPrice = jQuery(this).data('price');
    if (jQuery(this).val().length > 0) {
        jQuery('.cal_price_' + rowid).val(parseFloat(calPrice).toFixed(2));
    } else {
        jQuery('.cal_price_' + rowid).val('');
    }
    console.log('calPrice : ' + parseFloat(calPrice).toFixed(2));
    jQuery('.total').html(total.toFixed(2));
    console.log('total: '+total);
    if (total > 0) {
        jQuery('.active_submit_button').show();
        jQuery('.deactive_submit_button').hide();
    } else {
        jQuery('.active_submit_button').hide();
        jQuery('.deactive_submit_button').show();
    }
    //console.log('Total : '+total);
    sum = 0;
    jQuery('.myprice').each(function () {
        sum += Number(jQuery('.myprice').val());
    });
    console.log('sum : ' + sum);
});


jQuery(".purchase_orders").click(function () {
	console.log('purchase_orders');
	jQuery('#add_item').hide();
});

jQuery(".items_data").click(function () {
	console.log('items_data');
	jQuery('#add_item').show();
});

jQuery(".purchase_orders").click(function () {
	console.log('purchase_orders');
	jQuery('#add_item').hide();
});

jQuery(".items_data").click(function () {
	console.log('items_data');
	jQuery('#add_item').show();
});

function isNumberKey(e){

	if(e.which == 46){
        if(jQuery(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
}
</script>
