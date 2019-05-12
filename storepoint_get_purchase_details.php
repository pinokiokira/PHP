<?php 
require_once 'require/security.php';
include 'config/accessConfig.php';

$id = $_POST['id'];

$sqlloc="SELECT p.subtotal, (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as tax_total, p.total,st.code,gc.symbol FROM purchases as p INNER JOIN locations lo ON lo.id = p.location_id LEFT JOIN global_currency as gc ON gc.id =lo.currency_id left join states st on st.id = lo.state WHERE p.id=".$id;
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
                    <col class="con0" style="width:25%;" />
                    <col class="con1" style="width:10%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                   
                   </colgroup>
                   <thead>
                      <tr>
                        <th class="head1" >Item Description</th>
                        <th class="head0" >Qty</th>
                          <th class="head1" >Pack Unit Type</th>
                          <th class="head0" >Qty in Pack</th>
                          <th class="head1" >Qty in Pack Unit Type</th>
                          <th class="head0" >Qty in Pack Size</th>
                        <!--<th class="head0" >Tax</th>-->
                        <th class="head1" >Price</th>
                                          
                      </tr>
                    </thead>
                    <tbody>
                   
                
                 
                <?php
//				Old query using purchase_items
//				$query1 = "SELECT pi.ordered_price,pi.ordered_quantity,pi.ordered_pack_size, pi.inv_item_id ,
//						   it.description,iiu.unit_type as ordered_pack_unittype,pi.ordered_qty_in_pack,iiu1.unit_type as ordered_qty_in_pack_unittype,
//						   pi.ordered_price, pi.ordered_tax_percentage from purchase_items as pi
//						   LEFT JOIN vendor_items as vi ON vi.id = pi.inv_item_id
//						   LEFT JOIN inventory_items it ON it.id = vi.inv_item_id
//						   LEFT JOIN inventory_item_unittype iiu ON iiu.id = pi.ordered_pack_unittype
//						   LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id = pi.ordered_qty_in_pack_unittype
//						   WHERE purchase_id =".$id;
                $vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$_SESSION['client_id']));
                $vendor_id = $vender['StorePoint_vendor_Id'];
				$query1 = "SELECT 
                    it.description,
                    viic.quantity AS ordered_quantity,
                    iiu.unit_type AS ordered_pack_unittype,
                    vi.qty_in_pack AS ordered_qty_in_pack,
                    iiu1.unit_type AS ordered_qty_in_pack_unittype,
                    vi.qty_in_pack_size AS ordered_qty_in_pack_size,
                    vi.price AS ordered_price
                        FROM
                    vendor_items vi
                        LEFT JOIN
                    inventory_items it ON it.id = vi.inv_item_id
                        LEFT JOIN
                    inventory_item_unittype iiu ON iiu.id = vi.pack_unittype
                        LEFT JOIN
                    inventory_item_unittype iiu1 ON iiu1.id = vi.qty_in_pack_unittype
                        LEFT JOIN
                    vendor_items_inventory_counts viic ON it.id = viic.inv_item_id
                    WHERE vi.vendor_id = ". $vendor_id;

				if(isset($_REQUEST['history']) && $_REQUEST['history'] == 'shipped'){
					$query1 = "SELECT pi.ordered_price,pi.shipped_quantity as ordered_quantity,pi.shipped_pack_size as ordered_pack_size, pi.inv_item_id , 
						   it.description,iiu.unit_type as ordered_pack_unittype,pi.shipped_qty_in_pack as ordered_qty_in_pack,iiu1.unit_type as ordered_qty_in_pack_unittype,
						   pi.shipped_price as ordered_price, pi.shipped_tax_percentage as ordered_tax_percentage from purchase_items as pi
						   LEFT JOIN vendor_items as vi ON vi.id = pi.inv_item_id
						   LEFT JOIN inventory_items it ON it.id = vi.inv_item_id
						   LEFT JOIN inventory_item_unittype iiu ON iiu.id = pi.shipped_pack_unittype
						   LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id = pi.shipped_qty_in_pack_unittype
						   WHERE purchase_id =".$id;
				}
				if(isset($_REQUEST['history']) && $_REQUEST['history'] == 'Yes'){
					$query1 = "SELECT pi.ordered_price,pi.received_quantity as ordered_quantity,pi.received_pack_size as ordered_pack_size, pi.inv_item_id , 
						   it.description,iiu.unit_type as ordered_pack_unittype,pi.received_qty_in_pack as ordered_qty_in_pack,iiu1.unit_type as ordered_qty_in_pack_unittype,
						   pi.received_price as ordered_price, pi.received_tax_percentage as ordered_tax_percentage from purchase_items as pi
						   LEFT JOIN vendor_items as vi ON vi.id = pi.inv_item_id
						   LEFT JOIN inventory_items it ON it.id = vi.inv_item_id
						   LEFT JOIN inventory_item_unittype iiu ON iiu.id = pi.received_pack_unittype
						   LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id = pi.received_qty_in_pack_unittype
						   WHERE purchase_id =".$id;
				}
				$res1 = mysql_query($query1);
				while($row1=mysql_fetch_array($res1)){?>
                <tr>
                    <td class="normal" ><?php if($row1['description']!=""){echo $row1['description']; }else{echo "Item Not Found";} ?></td>
                    <td class="normal" ><?php echo $row1['ordered_quantity']; ?></td>
                    <td class="normal" ><?php echo $row1['ordered_pack_unittype']; ?></td>
                    <td class="normal" ><?php echo $row1['ordered_qty_in_pack']; ?></td>
                    <td class="normal" ><?php echo $row1['ordered_qty_in_pack_unittype']; ?></td>
                    <td class="normal" ><?php echo $row1['ordered_qty_in_pack_size']; ?></td>
                <!--<td class="right normal"><?php echo $row1['ordered_tax_percentage']; ?>%</td>-->
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