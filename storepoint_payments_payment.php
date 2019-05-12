<?php 
include_once 'require/security.php';
include_once("config/accessConfig.php");
function get_empmaster($emp_id){
	$query =mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees_master where empmaster_id =".$emp_id));
	return $query['name'];
}
?>

<script>
function vPayment(){
    var type = document.getElementById('type');
    var amt = document.getElementById('amount');
    var appl_amt = document.getElementById('applied_amt');
    if(type.value == ''){
	jAlert('Please select a type!','Alert Dialog');
	type.focus();
	return false;
    }else if(amt.value == ''){
	jAlert('Please enter an amount!','Alert Dialog');
	amt.focus();
	return false;
	}else if(jQuery('#reference').val()==""){
		jAlert('Please enter Reference!','Alert Dialog');
		jQuery('#reference').focus();
		return false;	
    }else{
	return true;
    }
}
function dec_point(val_n){
if(val_n!=""){
	var decimals =2;
	var val = Math.round(val_n * Math.pow(10, decimals)) / Math.pow(10, decimals);
	var n = val.toFixed(2);
	jQuery('#amount').val(n);
	
}
}

</script>
<style type="text/css">
#payments_frm input[type="text"]{
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
/*#amount:focus{
	text-align:right;
}
#amount:blur{
	text-align:left;
}*/
</style>
<div class="row-fluid">   
<form action="" method="post" name="payments_frm" id="payments_frm" onSubmit="return vPayment();">
<!--<input type="hidden" name="vendor_id" value="<?=$_REQUEST['client_id'];?>" />-->
<input type="hidden" name="location_id" value="<?=$_REQUEST['client_id'];?>" />
                <input type="hidden" name="payment_frm" value="submitted" />
			<table class="table table-bordered responsive" style="border-color:#FFFFFF;table-layout: fixed;">
					<!--<tr style="background-color:#FFFFFF; border:none;">
					<th style="width:2%"></th>
					<th style="width:30%"></th>
					</tr>-->
                    <tr>
                        <td style="width:30%;border:none;padding-top:12px;" valign="middle">Type: <span style="color:#F00;">*</span></td>
                        <td style="padding-left: 10px;border:none;">
                        <?php 
							$check_loc = mysql_query("SELECT default_payment_type FROM vendor_locations WHERE vendor_id = '".$_SESSION['StorePointVendorID']."' AND location_id = '".$_REQUEST['client_id']."'");
							$where = '';
							if(mysql_num_rows($check_loc)>0){
								$res = mysql_fetch_array($check_loc);
								if($res['default_payment_type']!=''){
									$where = " AND vendors_payments_id IN(".$res['default_payment_type'].") ";
								}
								
							}
							$payments = "SELECT id, payment_types FROM vendors WHERE id = '".$_SESSION['StorePointVendorID']."' LIMIT 1";
							$res = mysql_query($payments);
							$row1 = mysql_fetch_array($res);
						?>
							<select name="type" id="type" style="width:217px;margin-bottom:0;">
                                <option value="">Select Type</option>
                                <!--<option selected="selected" value="check">Check</option>
                                <option value="transfer">Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="other">Other</option>-->
                             
                            <?php
                            $sqlptype = "SELECT vendors_payments_id, code FROM vendors_payment_types  WHERE vendors_payments_id IN(".$row1['payment_types'].") $where ORDER BY code ASC ";
							//$sqlptype = "SELECT vendors_payments_id, code FROM vendors_payment_types WHERE vendors_payments_id IN(1,2) ORDER BY CODE ASC";
                            $resultptype = mysql_query($sqlptype);
                            while ($rowptype = mysql_fetch_assoc($resultptype)) {
                                ?><option value="<?php echo $rowptype["code"] ?>" ><?php echo $rowptype["code"]; ?></option>
                            <?php } ?>
                        
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

<script type="text/javascript">
    

    function validateFloatKeyPress(el, evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot (thanks ddlab)
        if(number.length>1 && charCode == 46){
             return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
            return false;
        }
        return true;
    }

    //thanks: http://javascript.nwbox.com/cursor_position/
    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }

</script>