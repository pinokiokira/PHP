<?php 
require_once 'require/security.php';
include 'config/accessConfig.php';

$id = $_POST['id'];
$idd = $_POST['sub'];

$sqlloc="SELECT p.subtotal, (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as  				 				tax_total, p.total,st.code,gc.symbol FROM purchases as p INNER JOIN locations lo ON lo.id = p.location_id LEFT JOIN global_currency as gc ON gc.id =lo.currency_id left join states st on st.id = lo.state WHERE p.id=".$idd;
$qrloc = mysql_query($sqlloc) or die(mysql_error());
$lnloc=mysql_fetch_assoc($qrloc);
?>
<style>
.normal{ font-size:12px !important;}
</style>
<div class="popup" id="pop_wrap">
        <div style="clear:both;">
            <div style="min-height:108px; overflow-x:auto;" class="print_content" > 
            	<table id="pulled_ordered_deatails" class="table table-bordered responsive">
                <colgroup>
					<col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:25%;" />
                    <col class="con1" style="width:10%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                   </colgroup>
                   <thead>
                      <tr>
						<th class="head0" >SLOT#</th>
                        <th class="head1" >Item Description</th>
                        <th class="head0" >Qty</th>
						<th class="head1" >Item code</th>
                        <th class="head1" >Pack Size</th>
                        <th class="head1" >Qty in pack</th>
						<th class="head0" >Master Unit Of Measure</th>
                      </tr>
                    </thead>
                    <tbody>
                   
                
                 
                <?php
				$query1 = "SELECT pi.ordered_price,pi.ordered_quantity,pi.ordered_pack_size, pi.inv_item_id , it.item_id,
						   it.description,iiu.unit_type as ordered_pack_unittype,pi.ordered_qty_in_pack,iiu1.unit_type as ordered_qty_in_pack_unittype,
						   pi.ordered_price, pi.ordered_tax_percentage from purchase_items as pi
						   LEFT JOIN vendor_items as vi ON vi.id = pi.inv_item_id
						   LEFT JOIN inventory_items it ON it.id = vi.inv_item_id
						   LEFT JOIN inventory_item_unittype iiu ON iiu.id = pi.ordered_pack_unittype
						   LEFT JOIN inventory_item_unittype iiu1 ON iiu1.id = pi.ordered_qty_in_pack_unittype
						   WHERE pi.id =".$id;
				$res1 = mysql_query($query1);
				while($row1=mysql_fetch_array($res1)){?>
                <tr>
				<td class="normal"></td>
                <td class="normal" ><?php if($row1['description']!=""){echo $row1['description']; }else{echo "Item Not Found";} ?></td>
                <td class="normal" ><?php echo $row1['ordered_quantity']; ?></td>
				<td class="normal" ><?php echo $row1['item_id']; ?></td>
                <td class="normal" ><?php echo $row1['ordered_pack_size']; ?></td>
				<td class="normal" ><?php echo $row1['ordered_qty_in_pack']; ?></td>
                <td class="normal" ><?php echo $row1['ordered_pack_unittype']; ?></td>
                
                </tr>
					
				<?php }
				?> 
            	    </tbody>
                    </table>             
            
				</table>               
				
			
							
            </div>
            
            <div class="print_account" >
            
                
					<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:20px; font-size:13px;">
				                            </table>
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:20px; font-size:13px;">
				 <?php if($lnloc['tax_total']!=""){
				 	$lnloc['tax_total'] = number_format((($lnloc['tax_total']*$lnloc['subtotal'])/100),2,'.',',');
					$lnloc['total'] = number_format(($lnloc['subtotal']+$lnloc['tax_total']),2,'.',','); 
                 } ?>              
                <tbody>
				<!--<tr style="margin-top:20px;">
                <td style="text-align: right; width: 80%" colspan="2">Subtotal:</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['subtotal']; ?></td>                
	            </tr>
                <tr>
                <td style="text-align: right; width: 80%" colspan="2">Tax(%):</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['tax_total']; ?></td>
                </tr>-->
                <!--<tr>
                <td colspan="2">Service "Charge &amp; Adjustments":</td>
                <td style="text-align:right"></td>
                </tr>
                <tr>
                <td colspan="2">Payments:</td>
                <td style="text-align:right;"> </td>	
                </tr>
                <tr> -->
                <!--<td style="text-align: right; width: 80%" colspan="2">Total:</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['total']; ?></td>	-->
                </tr>
                </tbody></table>
                </div>
						
			
        </div>
</div>