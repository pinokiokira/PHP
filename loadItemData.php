<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");

if($_GET['i'] != '' && $_GET['i'] != 'add_new_item'){
    $item = mysql_real_escape_string($_GET['i']);
    $query = "SELECT ii.taxable,ii.notes,ii.image,iiu.unit_type,iiu.id,ii.manufacturer_barcode
              FROM inventory_items ii
              LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
              WHERE ii.id=$item";
    $result = mysql_query($query) or die(mysql_error());
    $row=mysql_fetch_array($result);
}
if($_REQUEST['type']=='image'){
	if($_GET['i'] != 'add_new_item' && $row['image']!=""){
	echo API.'images/'.$row['image']; 
	}else{
	echo 'images/defimgpro.png';
	}
	exit;
}
?>

			<div class="loaded">
				<input value="1" type="hidden"/>
                <?php
					if($_GET['i'] == 'add_new_item'){
				?>
                	<p>
                        <label>New Item:</label>
                        <span class="field">
                            <input name="global_description" id="global_description" type="text" class="input-large" value="<?php echo $row['global_description']; ?>">
                        </span>
                    </p>
				<?php
					}
                ?>
                <p>
					<label>Barcode:</label>
					<span class="field">
						<input name="barcode_global" readonly="readonly"  id="barcode_global" type="text" class="input-large" value="<?php echo $row['manufacturer_barcode']; ?>">
					</span>
				</p>
				<p>
					<label>Priority:</label>
					<span class="field">
						<input name="priority"  id="priority_1" type="text" class="input-large" value="">
					</span>
				</p>
				<p>
					<label>Unit Type:</label>
					<span class="field">
						<input type="hidden" name="unit_type" value="<?php echo $row['id']; ?>">
						<input disabled="disabled" name="unittype" type="text" class="input-large" value="<?php echo $row['unit_type']; ?>">
					</span>
				</p>
				<p>
					<label>Notes:</label>
					<span class="field">
						<textarea rows="5" cols="22" disabled="disabled" style="width:210px;" class=""><?php echo $row['notes'];?></textarea>
					</span>
				</p>
				<p>
					<label>Taxable:</label>
					<span class="field">
						<select name="global_taxable" id="global_taxable" class="uniformselect">
	                        <!--<option>---Select Taxable---</option>-->
	                        <option value='no' <?php if($row['taxable'] == "no"){echo "selected";}?>>No</option>
	                        <option value='yes'<?php if($row['taxable'] == "yes"){echo "selected";}?>>Yes</option>
	                    </select>
					</span>
				</p>
                
				<p>
					<label>Manufacturer Barcode:</label>
					<span class="field">
						<input type="text" name="manufacturer_barcode" class="input-large" id="manufacturer_barcode" value="" />
					</span>
				</p>
                <p>
					<label>Default Vendor:</label>
					<span class="field">
						<?php $vendor = mysql_query("select name,id from vendors where status = 'active' and name != '' order by name asc");?>
                          <select name="vendor_default" id="vendor_default" style="width: 220px; " class="vendor_default">
                                    <option value=""> - - - Select Vendor - - - </option>
                                    <?php while($resultVendor = mysql_fetch_assoc($vendor)){?>
                                    <option value="<?php echo $resultVendor['id'];?>"><?php echo $resultVendor['name'];?>&nbsp;(ID:<?php echo $resultVendor['id'];?>)</option>
                                    <?php }?>                              
                             </select>
					</span>
				</p>
		        <div class="clearfix"></div>
			</div>