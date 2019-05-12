<?php 
require_once 'require/security.php';
include 'config/accessConfig.php';

$id = $_POST['id'];

// $sqlloc="SELECT p.subtotal, (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as tax_total, p.total,st.code,gc.symbol FROM vendor_purchases as p INNER JOIN vendors lo ON p.vendor_id = lo.id LEFT JOIN global_currency as gc ON gc.id =lo.currency_id left join states st on st.id = lo.state WHERE p.vendor_purchases_id=".$vendor_purchases_id;

$sqlloc = "SELECT 
    COALESCE(SUM(vpi.ordered_price*ordered_quantity), 0) AS subtotal,
    COALESCE(SUM(vpi.ordered_tax_percentage), 0) AS tax_total,
    gc.symbol
FROM
    vendor_purchases_items AS vpi
        INNER JOIN
    vendors v ON vpi.vendor_id = v.id
        LEFT JOIN
    global_currency AS gc ON gc.id = v.currency_id
WHERE
    vpi.vendor_id =".$id;

$qrloc = mysql_query($sqlloc) or die(mysql_error());
$lnloc=mysql_fetch_assoc($qrloc);
?>
<style>
.normal{ font-size:12px !important;}
</style>

       
            <div style="min-height:108px; overflow-x:auto;" class="print_content" > 
            	<table id="licence_table" class="table table-bordered responsive">
                <colgroup>
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:25%;" />
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                   
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
                                          
                      </tr>
                    </thead>
                    <tbody>
                   
                
                 
                <?php
				
                $query1 = "SELECT 
								ii.status,
                                ii.description,
                                vpi.ordered_quantity,
                                vpi.ordered_pack_size,
                                vpi.ordered_qty_in_pack,
                                vpi.ordered_quantity,
                                vpi.ordered_pack_unittype,
                                vpi.ordered_qty_in_pack_unittype,
                                vpi.ordered_tax_percentage,
                                vpi.ordered_price,
                                ig.description AS groups
                            FROM
                                vendor_purchases_items AS vpi
                                    LEFT JOIN
                                inventory_items AS ii ON vpi.inv_item_id = ii.id
                                    LEFT JOIN
                                vendor_purchases AS vp ON vpi.vendor_purchases_id = vp.vendor_purchases_id
                                    LEFT JOIN
                                inventory_groups AS ig ON ii.inv_group_id = ig.id
                            WHERE
                                vpi.vendor_id = ".$id;
               // echo $query1;
				$res1 = mysql_query($query1);
				while($row1=mysql_fetch_array($res1)){?>
                <tr>
                <td>
				<?php
					if($row1['status'] == 'active'){
						echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
					} else {
						echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
					}
				?>
				</td>
                <td class="normal" ><?php echo '<b>'.(($row1['description']) ? $row1['description'] : '').'</b><br>Group: '.(($row1['groups']) ? $row1['groups'] : '') ?></td>
                <td class="normal" >

                    <?php
                            $pack_unittype = ($row1['ordered_pack_unittype']) ? $row1['ordered_pack_unittype'] : '';
                        $unittype = "SELECT * from inventory_item_unittype WHERE id = '".$pack_unittype."' ORDER BY conversion_group, unit_type";
                        $pack_unittype_qry = mysql_query($unittype);
                        $pack_unittype_fetch = mysql_fetch_array($pack_unittype_qry);


                     ?>


                    <?php echo $pack_unittype_fetch['unit_type']; ?></td>
                <td class="normal right" ><?php echo $row1['ordered_qty_in_pack']; ?></td>
                <td class="normal " >


                    <?php
                        $ordered_qty_in_pack_unittype = ($row1['ordered_qty_in_pack_unittype']) ? $row1['ordered_qty_in_pack_unittype'] : '';
                        $unittype = "SELECT * from inventory_item_unittype WHERE id = '".$ordered_qty_in_pack_unittype."' ORDER BY conversion_group, unit_type";
                        $qty_in_pack_unittype_qry = mysql_query($unittype);
                        $qty_in_pack_unittype_fetch = mysql_fetch_array($qty_in_pack_unittype_qry);
                    ?>




                    <?php echo $qty_in_pack_unittype_fetch['unit_type']; ?></td>

                
				
                <td class="right normal"><?php echo $row1['ordered_pack_size']; ?></td>
                <td class="right normal"><?php echo $row1['ordered_tax_percentage']; ?>%</td>
                 <td class="right normal"><?php echo number_format(($row1['ordered_price']*$row1['ordered_quantity']),2,'.',','); ?></td>
                 <td class="normal right" ><?php echo $row1['ordered_quantity']; ?></td>
                 <td>&nbsp;</td>
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
                
                <td style="text-align: right; width: 80%" colspan="2">Total:</td>
                <td style="text-align:right;"><?php echo $lnloc['symbol'].''.$lnloc['total']; ?></td>	
                </tr>
                </tbody></table>
                </div>
						
			
