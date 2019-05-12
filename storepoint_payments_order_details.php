<?php
include_once 'require/security.php';
include_once("config/accessConfig.php");

if ($_REQUEST["orderid"] != "") {
    $purchase_id = $_REQUEST["orderid"];
    $psquery = "SELECT purchases.vendor_id, DATE_FORMAT(order_datetime,'%m/%d/%Y') as order_datetime,
                       purchases.status, DATE_FORMAT(completed_datetime,'%m/%d/%Y') as completed_datetime,
                       vt.code as terms, subtotal, tax_total, total, po, v.name vname, lo.name lname,
                       lo.address laddress,lo.city lcity,s.code lstate,lo.zip lzip,lo.phone lphone,lo.fax lfax,
                       v.address vaddress,v.city vcity,s2.code vstate,v.zip vzip,v.phone vphone,v.contact vrep,purchases.comments,
                       e.first_name,e.last_name
                FROM purchases
                LEFT JOIN vendors v ON purchases.vendor_id = v.id
                LEFT JOIN locations lo ON lo.id=purchases.location_id
                LEFT JOIN employees e ON e.id=purchases.completed_employee_id
                LEFT JOIN states s ON s.id=lo.state
                LEFT JOIN states s2 ON s2.id=v.state
                LEFT JOIN vendors_terms_types vt ON vt.vendors_terms_types=purchases.terms
                WHERE purchases.id='" . $purchase_id . "'";
    $psresult = mysql_query($psquery) or die(mysql_error());
    $psrow = mysql_fetch_object($psresult);
    $ddStatus = $psrow->status;
    $txtOrdDate = $psrow->order_datetime;
    $txtCompDate = $psrow->completed_datetime;
    $ddVendor = $psrow->vendor_id;
    $txtTerms = $psrow->terms;
    $txtSubTotal = $psrow->subtotal;
    $vname = $psrow->vname;
    $Nettotal = $psrow->total;
    $txtPO = $psrow->po;
    $vaddress = $psrow->vaddress;
    $vcity = $psrow->vcity;
    $vstate = $psrow->vstate;
    $vzip = $psrow->vzip;
    $vphone = $psrow->vphone;
    $vrep = $psrow->vrep;
    $lname = $psrow->lname;
    $laddress = $psrow->laddress;
    $lcity = $psrow->lcity;
    $lstate = $psrow->lstate;
    $lzip = $psrow->lzip;
    $lphone = $psrow->lphone;
    $lfax = $psrow->lfax;
    $comments = $psrow->comments;
    $employee = $psrow->first_name . " " . $psrow->last_name;
	
}
?>
<style type="text/css">
#order_frm input[type="text"]{
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-top-left-radius:0;
  border-top-right-radius:0;
  color:#555555;
  display:inline-block;
  font-size:12px;
  margin-bottom:0px;
  padding:0px;
  vertical-align:middle;
  width:100%;
}
.itm_inpt{
text-align:right;}
</style>
<div class="row-fluid">   
	<form action="" method="post" name="order_frm" id="order_frm">
			<div class="span4" style="width:50%;">
				<div class="clearfix"> 
					<!--<h4 class="widgettitle">Milagros - Test Location</h4>-->	
                    <h4 class="widgettitle"><?=$lname;?></h4>			
				</div> 
                <table class="table table-bordered responsive">
                    <!--<tr>
						<td>
							8912 E Pinnacle Peak Rd<br />
							Scottsdale, AZ 85260<br />
							4801234567<br />
							(480) 503-8023
						</td>
					</tr>-->
                    <tr><td>
						<?=$laddress;?>&nbsp;<br />
                        <?=$lcity . ", " . $lstate . " " . $lzip;?>&nbsp;<br />
                    	<?=$lphone;?>&nbsp;<br />
                    	<?=$lfax;?>&nbsp;
                    </td></tr>
				</table>
				<br />
				<div class="clearfix"> 
					<!--<h4 class="widgettitle">Safeway</h4>-->	
                    <h4 class="widgettitle"><?=$vname?></h4>		
				</div>
                <table class="table table-bordered responsive">
                    <!--<tr>
						<td>
							926 W Broadway Rd<br />
							Tempe, AZ 85282<br />
							1234567890<br />
							John
						</td>
					</tr>-->
                    <tr><td>
						<?=$vaddress?>&nbsp;<br />
                    	<?=$vcity . ", " . $vstate . " " . $vzip;?>&nbsp;<br />
                        <?=$vphone;?>&nbsp;<br />
                    	<?=$vrep;?>&nbsp;	
                    </td></tr>
				</table>
				<br />
            </div>
			<div class="span4" style="width:50%;margin:0;padding-left:12px;">
				<div class="clearfix"> 
					<h4 class="widgettitle">Order Details</h4>			
				</div>
                <table class="table table-bordered responsive">
                    <tr>
					  <td>
					    <b>Status:</b>&nbsp;&nbsp;<?=$ddStatus?><br />
						<b>PO:</b>&nbsp;&nbsp;&nbsp;<?=$txtPO;?><br />
						<b>Terms:</b>&nbsp;&nbsp;<?=$txtTerms?><br />
						<b>Order Date:</b>&nbsp;&nbsp;<?=$txtOrdDate?><br />
						<b>Completed Date:</b>&nbsp;&nbsp;<?=$txtCompDate?><br />
						<b>Employee:</b>&nbsp;&nbsp;<?=$employee?><br />
						<b>Comments:</b>&nbsp;&nbsp;<?=$comments?>
					  </td>
					</tr>
			  </table>
            </div>
			<!--<div class="span4" style="width:50%;margin:15px 0 15px 0;">
				<div class="clearfix"> 
					<h4 class="widgettitle">Safeway</h4>			
				</div>
                <table class="table table-bordered responsive">
                    <tr>
						<td>
							926 W Broadway Rd<br />
							Tempe, AZ 85282<br />
							1234567890<br />
							John
						</td>
					</tr>
				</table>
            </div>-->
			<br />
			
			<table id="licence_table" class="table table-bordered responsive">
							<colgroup>
								<col class="con0" />
								<col class="con1" />
								<col class="con0" />
								<col class="con1" />
								<col class="con0" />
							</colgroup>
							<thead>
								<tr>
								<th class="head1" style="width:90px;">Item</th>
								<th class="head1" style="width:90px;">Type</th>
								<th class="head0" style="width:40px;">Qty</th>
								<th class="head1" style="width:40px;">Price</th>
								<th class="head1" style="width:10px;">Amount</th>
								</tr>
							</thead>
							<tbody>
                            <?php
							$pquery = "SELECT p.order_datetime, p.terms,p.total, p.tax_total, p.subtotal,
											pi.id,  pi.received_quantity as quantity, pi.received_price, pi.received_tax_percentage,
											
											pi.shipped_pack_size, pi.shipped_pack_unittype, pi.shipped_qty_in_pack, pi.shipped_qty_in_pack_unittype,
											
											ii.description, ii.id as inv_item_id, ii.item_id,ii.description,
											iiu.unit_type as type
										FROM purchases p
										INNER JOIN purchase_items pi ON p.id=pi.purchase_id
										INNER JOIN vendor_items vi ON pi.inv_item_id=vi.id
										INNER JOIN inventory_items ii ON ii.id=vi.inv_item_id
										LEFT JOIN inventory_item_unittype iiu ON ii.unit_type=iiu.id
										WHERE p.id=" . $purchase_id;


							if (isset($_REQUEST['debug'])) {
								echo "<br><br>";
								print_r('SQL   :    '.$pquery);
								echo "<br><br>";
							}


							$presult = mysql_query($pquery) or die(mysql_error());
							$i = 0;
							$subtotal = 0;
							$tax = 0;
							$total = 0;
							$class = "line1";
							$rows = mysql_num_rows($presult);
							while ($prow = mysql_fetch_array($presult)) {
								$i++;
								if ($i % 2 == 0) {
									$class = "line2";
								}
								
								$subtotal = $prow['subtotal'];
								$tax = $prow['received_tax_percentage'];
								$total = $prow['total'];
								if ($prow['quantity']!=0) {
									$price = $prow['price'] / $prow['quantity'];	
								}else {
									$price = $prow['price'];	
								}
								
								$amt = $prow['received_price'] * $prow['quantity'];
								$itemtax = $prow['tax_percentage'] * $amt * .01;
								?>
								<tr class="<?php echo $class; ?>">
									<td class="purchase_td"><?=$prow["description"]; ?></td>
									<td class="purchase_td">
										<?php echo $prow['shipped_pack_size'].", ".$prow['shipped_pack_unittype'].", ".$prow['shipped_qty_in_pack'].", ".$prow['shipped_qty_in_pack_unittype']; ?>
									</td>
									<td class="purchase_td" style="text-align:right;"><?=$prow["quantity"]; ?></td>
									<td class="purchase_td" style="text-align:right;"><?=number_format($prow['received_price'], 2, '.', ''); ?></td>
									<td class="purchase_td" style="text-align:right;"><?=number_format($amt, 2, '.', ''); ?></td>
								</tr>
								<?php
							}?>
								<!--<tr>
									<td>Pita Bread</td>
									<td>EACH</td>
									<td>Test</td>
									<td>0.00</td>
									<td>0.00</td>
									<td>0.00</td>
								</tr>-->
								<tr>
									<td colspan="4" style="text-align:right;">Sub Total: </td>
									<td>
									<input type="text" disabled="disabled" value="<?=number_format($subtotal, 2, '.', '');?>" class="itm_inpt" />
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align:right;">Tax: </td>
									<td>
									<input type="text" disabled="disabled" value="<?=number_format($tax, 2, '.', '');?>" class="itm_inpt" />
									</td>
								</tr>
								<tr>
									<td colspan="4" style="text-align:right;">Total: </td>
									<td>
									<input type="text" disabled="disabled" value="<?=number_format($total, 2, '.', '');?>" class="itm_inpt" />
									</td>
								</tr>
						   </tbody>
						   </form>
				</table>
	
</div>