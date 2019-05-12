<?php 
include_once 'require/security.php';
include_once("config/accessConfig.php");
?>

<div class="row-fluid">   
<form action="" method="post" name="distribution_frm" id="distribution_frm" onSubmit="return distribution();">
	<table class="table table-bordered responsive" style="border-color:#FFFFFF;table-layout: fixed;">
		<tr>
			<td style="width:30%;border:none;padding-top:12px;" valign="middle">Bays: <span style="color:#F00;">*</span></td>
			<td style="padding-left: 10px;border:none;">
			<?php 
				//for list of bays
				$sql1 = "SELECT * FROM vendor_bays";
				$result1Jobs = mysql_query($sql1) or die(mysql_error());
				$result1Rows = mysql_num_rows($result1Jobs);
			?>
				<select name="bays_id" id="bays_id">
					<?php
						if($result1Rows > 0){
							$r = 1;$g = 1;
							while($row1 = mysql_fetch_array($result1Jobs)){
					?>
								<option value="<?php echo $row1['vendor_bays_id'];?>"><?php echo $row1['bay_code'];?></option>
					<?php
							}
						}else{?>
							<option>Data not available</option>
						<?php }?>
				</select>
			</td>
		</tr>
		<!--<tr>
			<td style="width:30%;border:none;padding-top:12px;" valign="middle">Bank Account: <span style="color:#F00;">*</span></td>
			<td style="padding-left: 10px;border:none;">
				<select name="bank_id" id="bank_id" style="width:217px;margin-bottom:0;">                            
					<option value="">- - - Select Bank Account - - -</option>
					<?php 
						$bquery = mysql_query("SELECT loc_expensetab_banks_id as id,bank_name from location_expensetab_banks where location_id ='".$_REQUEST['client_id']."' AND used_for_payments = 'Yes' AND bank_name <>''");
						while($brow = mysql_fetch_array($bquery)){
					?>
					<option value="<?php echo $brow['id']; ?>"><?php echo $brow['bank_name']; ?></option>
				   <?php } ?>
				   
				   
				</select>
			</td>
		</tr>-->
		<tr>
			<td style="width:30%;border:none;padding-top:13px;">Amount: <span style="color:#F00;">*</span></td>
			<td style="padding-left: 10px;border:none;">
				<input type="text" style="width:217px;height:27px; padding:0 6px;" onblur="dec_point(this.value);" onkeypress="return validateFloatKeyPress(this,event);" id="amount" name="amount">
			</td>
		</tr>
		<tr>
			<td style="width:30%;border:none;padding-top:13px;">Reference/Check#: <span style="color:#F00;">*</span></td>
			<td style="padding-left: 10px;border:none;">
				<input type="text" style="width:217px;height:27px; padding:0 6px;" id="reference" name="reference">
			</td>
		</tr>
		<tr>
			<td style="width:30%;border:none;">Description:</td>
			<td style="padding-left: 10px;border:none;">
				<textarea style="width:217px;" cols="23" rows="6" id="description" name="description"></textarea>
				
			</td>
		 </tr>
		 <tr>
			 <td style="width:30%;border:none;">Created By:</td>
			 <td style="padding-left: 10px;border:none;">
			 <input readonly="readonly" type="text" style="width:217px;height:27px;" value="<?php echo get_empmaster($_SESSION['client_id']); ?>" id="gccreatedby" name="gccreatedby">
			 <input type="hidden" name="created_by" value="<?php echo $_SESSION['client_id']; ?>" id="created_by" >
			 </td>
		 </tr>
		 <tr>
			 <td style="width:30%;border:none;">Created On:</td>
			 <td style="padding-left: 10px;border:none;">
			 <input readonly="readonly" type="text" style="width:217px;height:27px;" value="VendorPanel" id="created_on" name="created_on">                         
			 </td>
		 </tr>
		 <tr>
			 <td style="width:30%;border:none;">Created Date & Time:</td>
			 <td style="padding-left: 10px;border:none;">
			 <input readonly="readonly" type="text" style="width:217px;height:27px;" value="<?php echo date('Y-m-d H:i'); ?>" id="created_datetime" name="created_datetime">                         
			 </td>
		 </tr> 	
							
							
		</table>
	</form>
</div>
