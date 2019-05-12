<?php
	require_once 'require/security.php';
	include 'config/accessConfig.php';

	$vendor_purchases_items=trim($_REQUEST['vendor_purchases_items']);

	if($vendor_purchases_items!==''&&$_REQUEST['vendor_purchases_items']!=NULL)
	{
		$sqlloc="SELECT p.subtotal, (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as tax_total, p.total,st.code,gc.symbol FROM vendor_purchases as p INNER JOIN vendors lo ON p.vendor_id = lo.id LEFT JOIN global_currency as gc ON gc.id =lo.currency_id left join states st on st.id = lo.state WHERE p.vendor_purchases_id=".mysql_real_escape_string($vendor_purchases_items);
		$qrloc = mysql_query($sqlloc) or die(mysql_error());
		$lnloc=mysql_fetch_assoc($qrloc);
?>
		<style>
			.normal{ font-size:12px !important;}
		</style>
		<div class="popup" id="pop_wrap">
			<div style="clear:both;">
				<div style="min-height:108px; overflow-x:auto;" class="print_content" > 
					<table id="licence_table" class="table table-bordered responsive">
                <colgroup>
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:25%;" />
                    <col class="con1" style="width:12%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:18%;"/>
                    <col class="con0" style="width:5%;"/>
					<col class="con1" style="width:10%;"/>
                   </colgroup>
                   <thead>
                      <tr>

                        <th class="head1" >S</th>
                        <th class="head1" >NAME</th>
                        <th class="head1" >PACK UNIT TYPE</th>
                        <th class="head1" >Qty in pack</th>
                        <th class="head1" >QTY IN PACK UNIT TYPE</th>
                        <th class="head1" >QTY IN Pack Size</th>						
                        <th class="head0" >Qty</th>
						<th class="head1" >Price</th>
                      </tr>
                    </thead>
                    <tbody>
                   
                
                 
                <?php
			$query1 = 'SELECT ii.status AS status, vpi.ordered_qty_in_pack_unittype as ordered_qty_in_pack_unittype, ig.description as groups, ii.description as description, vpi.ordered_quantity, vpi.ordered_pack_size, vpi.ordered_qty_in_pack, vpi.ordered_pack_unittype, vpi.ordered_tax_percentage, vpi.ordered_price,ordered_quantity FROM vendor_purchases_items as vpi LEFT JOIN inventory_items ii ON ii.id = vpi.inv_item_id 
LEFT JOIN inventory_groups ig ON ii.inv_group_id = ig.id 
WHERE vendor_purchases_id = "'.mysql_real_escape_string($vendor_purchases_items).'"';

				$res1 = mysql_query($query1);

				while($row1=mysql_fetch_array($res1))
				{
					if($row1['status'] == 'active')
					{
						$status =  "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
					}
					else
					{
						$status = "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
					}

					$qtyunittype = "SELECT unit_type from inventory_item_unittype WHERE id = '".$row1['ordered_qty_in_pack_unittype']."' ORDER BY conversion_group, unit_type";
					$qtyunittype = mysql_query($qtyunittype);
					$qtyunittype = mysql_fetch_array($qtyunittype);

					$unittype = "SELECT unit_type from inventory_item_unittype WHERE id = '".$row1['ordered_pack_unittype']."' ORDER BY conversion_group, unit_type";
					$unittype = mysql_query($unittype);
					$unittype = mysql_fetch_array($unittype);
?>
                <tr>
                <td  class="row_style center" style="line-height:15px;word-break: break-all !important;vertical-align:middle;"><?php echo $status;?></td>

<td class="row_style" style="line-height:15px;word-break: break-all !important;vertical-align:middle; color:black;"><b><?php echo $row1['description'];?></b><br>Group: <?php echo $row1['groups'];?></td>


               <!-- <td class="normal" ><?php if($row1['description']!=""){echo $row1['description']; }else{echo "Item Not Found";} ?></td>-->
	<td><?php echo $unittype['unit_type'];?></td>
                <td class="normal right" ><?php echo $row1['ordered_qty_in_pack']; ?></td>
	<td><?php echo $qtyunittype['unit_type'];?></td>
                <td class="normal right" ><?php echo $row1['ordered_pack_size']; ?></td>
                <!--<td class="right normal"><?php echo $row1['ordered_tax_percentage']; ?>%</td>-->
                <td class="normal right" ><?php echo $row1['ordered_quantity']; ?></td>
				<td class="right normal"><?php echo number_format(($row1['ordered_price']*$row1['ordered_quantity']),2,'.',','); ?></td>
               </tr>
					
				<?php }
				?> 
            	    </tbody>
                    </table>             
            
				</table>               
				
			
							
            </div>
  




            <div class="print_account" >
            
                
					<table width="100%" cellspacing="0" cellpadding="0" border="0" style="/*margin-top:20px;*/ font-size:13px;">
				                            </table>
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="/*margin-top:20px;*/ font-size:13px;">
				 <?php if($lnloc['tax_total']!=""){
				 	$lnloc['tax_total'] = number_format((($lnloc['tax_total']*$lnloc['subtotal'])/100),2,'.',',');
					$lnloc['total'] = number_format(($lnloc['subtotal']+$lnloc['tax_total']),2,'.',','); 
                 } ?>              
                <tbody><tr style="margin-top:20px;">
                <td style="text-align: right; width: 80%" colspan="2">Subtotal:</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['subtotal']; ?></td>                
	            </tr>
                <tr>
                <td style="text-align: right; width: 80%" colspan="2">Tax(%):</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['tax_total']; ?></td>
                </tr>
                <!--<tr>
                <td colspan="2">Service "Charge &amp; Adjustments":</td>
                <td style="text-align:right"></td>
                </tr>
                <tr>
                <td colspan="2">Payments:</td>
                <td style="text-align:right;"> </td>	
                </tr>
                <tr> -->
                <td style="text-align: right; width: 80%" colspan="2">Total:</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['total']; ?></td>	
                </tr>
                </tbody></table>
                </div>










			
			
        </div>
</div>
<?php
	}
?>