<?php
require_once 'require/security.php';
include 'config/accessConfig.php';


function get_by($empmaster_id) {
	$qry_get_by = "SELECT * FROM employees_master WHERE empmaster_id='". $empmaster_id ."'";
	$res_get_by = mysql_query($qry_get_by) or die($qry_get_by .'-----'. mysql_error());
	$row_get_by = mysql_fetch_assoc($res_get_by);
	
	if( $row_get_by['first_name'] != '') {
		$return_str = trim($row_get_by['first_name'] .' '. $row_get_by['last_name'] .' (ID: '. $row_get_by['empmaster_id'] .')');
	} else {
		$return_str = '';
	}

return $return_str;
}

if(isset($_GET) && $_GET['action'] == 'getEmpName'){
	$emId = $_GET['emid'];
	$sql = "SELECT  id,concat(first_name,' ',last_name,' ') name FROM employees where id={$emId}";
	$qry = mysql_query($sql);
	$fet = mysql_fetch_array($qry);
	echo $fet['name'];
	die;
}

$id = $_GET['id'];
$location_id = $_GET['loc_id'];

$sql = "SELECT  * FROM vendor_locations WHERE vendor_id = '$id' AND location_id = '$location_id' LIMIT 1";

$select = mysql_query($sql);


$sql1 = "SELECT id, payment_types, delivery_types, terms_types FROM vendors WHERE id = '".$_SESSION['StorePointVendorID']."' LIMIT 1";
$select1 = mysql_query($sql1) or die(mysql_error());
$row1 = mysql_fetch_assoc($select1);

/*
 * .. Get Location info.
 */
$locationSql = "SELECT id, status, name, access_backoffice FROM locations where id = '$location_id' ";
$locationResult = mysql_query($locationSql) or die(mysql_error());
$locationRow = mysql_fetch_assoc($locationResult);

?>
<style>
	.ui-datepicker-calendar .ui-datepicker-unselectable span,  .ui-datepicker-calendar .ui-state-disabled span{
		padding: 2px 8px;
	}
	#addLoca_body .control-group,#editLoca_body .control-group
	{
	  margin-bottom: 5px;
	}
	#addLoca_body .default
	{
		height:32px !important;
	}
	#editLoca_body #default_delivery_type_chzn ul.chzn-choices li.search-field input[type="text"] {
	  height: 14px !important;
	}
	#editLoca_body #default_terms_chzn ul.chzn-choices li.search-field input[type="text"] {
	  height: 14px !important;
	}
	#editLoca_body #default_payment_type_chzn ul.chzn-choices li.search-field input[type="text"] {
	  height: 14px !important;
	}
	#editLoca_body .default {
	  height: 14px !important;
	}
</style>
<input type="hidden" name="editLocation" value="1010" />
<input type="hidden" id="client_id" name="client_id" value="<?php echo $id; ?>" />
<?php
if (mysql_num_rows($select) == 0) {
    ?>    

    
    <table width="98%" height="100%">
    
    	
	<?php if($location_id>0){ ?>    
	
        <input type="hidden" id="loc_id" name="location_id" value="<?php echo $location_id; ?>" />
        <input type="hidden" name="editable" value="1" />
        
        
        <?php 
		$employees = mysql_query("SELECT id, emp_id, first_name, last_name, status FROM employees WHERE location_id = '$location_id' AND status = 'A' ");
		if(mysql_num_rows($employees)>0){
		?>
    	<tr>
            <td width="50%">
                <label>Primary Contact Employee ID:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        
                        <select name="primary_contact_employee_id" id="primary_contact_employee_id" style="width:310px; height:30px;" onchange="get_employee(this.value);">
                            <option value="">--Select--</option>
                            <?php while ($emp = mysql_fetch_array($employees)) {  ?>
                            <option value="<?php echo $emp['emp_id'];?>"><?php echo $emp['first_name'].' ',$emp['last_name'].' (ID: '.$emp['emp_id'].')';?></option>
                            <?php } ?>
                        </select>
                       
                    </div>
                </div>
            </td>
        </tr>
        <?php } ?>
        
        
     <?php }else{ ?>
     	 <tr>
            <td width="50%">
                <label>Location: <span style="color:red;">*</span></label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <input type="text"  id="location_id"  style="width:296px; height:20px;" autocomplete="off" />
                        <input type="hidden" id="loc_id_search" name="location_id" value="<?php echo $location_id; ?>" />                        
                    </div>
                </div>
            </td>
        </tr>
        
        
        
    	<tr>
            <td width="50%">
                <label>Primary Contact Employee ID:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        
                        <select name="primary_contact_employee_id" id="primary_contact_employee_id" style="width:310px; height:30px;" onchange="get_employee(this.value);">
                            <option value="">--Select--</option>                            
                        </select>
                       
                    </div>
                </div>
            </td>
        </tr>
     <?php } ?>
        

    
    	
    
    
        <tr>
            <td width="50%">
                <label>Primary Contact: <span style="color:red;">*</span></label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
					
                        <input type="text" name="primary_contact" id="primary_contact"  style="width:296px; height:20px;">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Sales Variance: </label>
            </td>
            <td width="50%">
                <div class="control-group">
                    <div class=" controls">
                        <input type="text" class="input-large" name="sale_variance" id="sale_variance" title="Subject" style="width:296px; height:20px;">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Default Delivery Type: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <!--<input type="text" name="default_delivery_type" id="default_delivery_type" style="width:310px; height:30px;" >-->
                        <select name="default_delivery_type[]" id="default_delivery_type" data-placeholder=" - - - Select Delivery Types - - - " class="select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                            <?php
                            $sqldtype = "SELECT vendors_delivery_types_id, code FROM vendors_delivery_types WHERE vendors_delivery_types_id IN(".$row1['delivery_types'].") ORDER BY code ASC";
                            $resultdtype = mysql_query($sqldtype);
                            while ($rowdtype = mysql_fetch_assoc($resultdtype)) {
                                ?><option value="<?php echo $rowdtype["vendors_delivery_types_id"] ?>" ><?php echo $rowdtype["code"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Default Terms: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <!--<input type="text" name="default_terms" id="default_terms" style="width:310px; height:30px;" >-->
                        <span class="field">
                            <select name="default_terms[]" id="default_terms" data-placeholder="- - - Select Vendor Terms - - -" class="chzn-select select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                                <?php
                                $sqlterms = "SELECT vendors_terms_types id, code FROM vendors_terms_types WHERE vendors_terms_types IN(".$row1['terms_types'].") ORDER BY code ASC";
                                /* $sqlterms = "SELECT id,terms FROM vendor_terms ORDER BY id"; */
                                $resultterms = mysql_query($sqlterms);
                                while ($rowterms = mysql_fetch_assoc($resultterms)) {
                                    ?><option value="<?php echo $rowterms["id"] ?>" ><?php echo $rowterms["code"]; ?></option>
                                <?php }
                                ?>

                            </select>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Default Payment Type: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <!--<input type="text" name="default_payment_type" id="default_payment_type" style="width:310px; height:30px;" >-->
                        <select name="default_payment_type[]" id="default_payment_type" data-placeholder=" - - - Select Payment Types - - - " class="select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                            <?php
                            $sqlptype = "SELECT vendors_payments_id, code FROM vendors_payment_types  WHERE vendors_payments_id IN(".$row1['payment_types'].") ORDER BY code ASC ";
                            $resultptype = mysql_query($sqlptype);
                            while ($rowptype = mysql_fetch_assoc($resultptype)) {
                                ?><option value="<?php echo $rowptype["vendors_payments_id"] ?>" ><?php echo $rowptype["code"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
            </td>
        </tr>

        

        <tr>
            <td width="50%">
                <label>Primary Contact Email: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <input type="text" name="primary_contact_email" id="primary_contact_email" style="width:296px; height:20px;" >
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Primary Contact Phone: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="primary_contact_phone" id="primary_contact_phone" style="width:296px; height:20px;" >
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Note: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <textarea name="note" id="note" style="width:296px; height:auto; resize: none;" ></textarea>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Reminder:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="reminder" id="reminder" style="width:296px; height:20px;" >
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Created By:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="created_by" id="created_by" style="width:296px; height:20px;" readonly="" value="<?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . ' (ID: ' . $_SESSION['client_id'] . ')'; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Created On: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="created_on" id="created_on" style="width:296px; height:20px;" readonly="" value="VendorPanel" >
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Created Date & Time: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="" id="created_datetime" style="width:296px; height:20px;" readonly="" value="<?php echo date('Y-m-d h:i'); ?>" />
                        <input type="hidden" id="hid_created_datetime" name="created_datetime" value="<?php echo date('Y-m-d H:i:s'); ?>" />
                    </div>
                </div>
            </td>
        </tr>

    </table>


    <?php
} else {
    $row = mysql_fetch_assoc($select);
    if($row['reminder'] != '0000-00-00 00:00:00'){
        $row['reminder'] = date('Y-m-d', strtotime($row['reminder']));
    }else{
        $row['reminder'] = '';
    }
//    echo json_encode($row);
    ?>

    
    <!--<input type="hidden" id="client_id" name="client_id" value="<?php echo $row['vendor_id']; ?>" />-->
    <input type="hidden" name="vendor_locations_id" value="<?php echo $row['vendor_locations_id']; ?>" />
    <table width="98%">
    	
    	<?php 
        $employees = mysql_query("SELECT id, emp_id, first_name, last_name, status FROM employees WHERE location_id = '$location_id' AND status = 'A' AND access_bo_purchases='yes'");
		if(mysql_num_rows($employees)>0){
        ?>
    	<tr>
            <td width="50%">
                <label>Primary Contact Employee ID:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        
                        <select name="primary_contact_employee_id" id="primary_contact_employee_id" style="width:310px; height:30px;" onchange="get_employee(this.value);">
                            <option value="">--Select--</option>
                            <?php while ($emp = mysql_fetch_array($employees)) {  ?>
                            <option value="<?php echo $emp['emp_id'];?>" <?php echo ($row['primary_contact_employee_id'] == $emp['emp_id']) ? 'selected':'';?> ><?php echo $emp['first_name'].' ',$emp['last_name'].' (ID: '.$emp['emp_id'].')';?></option>
                            <?php } ?>
                        </select>
                                               
                    </div>
                </div>
            </td>
        </tr>
    	<?php } ?>
		
		
		<tr>
            <td width="50%">
                <label>Location: <span style="color:red;">*</span></label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
						<input type="text" id="loc_id" name="location_id" value="<?php echo $row['location_id']; ?>"  style="width:296px; height:20px;" />
                    </div>
                </div>
            </td>
        </tr>
		
        <tr>
            <td width="50%">
                <label>Primary Contact: <span style="color:red;">*</span></label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <input type="text" name="primary_contact" id="primary_contact"  style="width:296px; height:20px;" value="<?php echo $row['primary_contact']; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Sales Variance: </label>
            </td>
            <td width="50%">
                <div class="control-group">
                    <div class=" controls">
                        <input type="text" class="input-large" name="sale_variance" id="sale_variance" title="Subject" style="width:296px; height:20px;" value="<?php echo $row['sales_variance']; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Default Delivery Type: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <?php $def_deli_type = @explode(',', $row['default_delivery_type']); ?>
                        <select name="default_delivery_type[]" id="default_delivery_type" data-placeholder=" - - - Select Delivery Types - - - " class="select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                            <?php
                            $sqldtype = "SELECT vendors_delivery_types_id, code FROM vendors_delivery_types WHERE vendors_delivery_types_id IN(".$row1['delivery_types'].") ORDER BY code ASC";
                            $resultdtype = mysql_query($sqldtype);
                            while ($rowdtype = mysql_fetch_assoc($resultdtype)) {
                                ?><option value="<?php echo $rowdtype["vendors_delivery_types_id"] ?>" <?php
                                if (in_array($rowdtype["vendors_delivery_types_id"], $def_deli_type)) {
                                    echo 'selected';
                                }
                                ?> ><?php echo $rowdtype["code"]; ?></option>
                                    <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td width="50%">
                <label>Default Terms: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls" >
                        <?php $def_terms = @explode(',', $row['default_terms']); ?>
                        <span class="field">
                            <select name="default_terms[]" id="default_terms" data-placeholder="- - - Select Vendor Terms - - -" class="chzn-select select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                                <?php
                                $sqlterms = "SELECT vendors_terms_types id, code FROM vendors_terms_types WHERE vendors_terms_types IN(".$row1['terms_types'].") ORDER BY code ASC";
                                /* $sqlterms = "SELECT id,terms FROM vendor_terms ORDER BY id"; */
                                $resultterms = mysql_query($sqlterms);
                                while ($rowterms = mysql_fetch_assoc($resultterms)) {
                                    ?><option value="<?php echo $rowterms["id"] ?>" <?php
                                    if (in_array($rowterms["id"], $def_terms)) {
                                        echo 'selected';
                                    }
                                    ?> ><?php echo $rowterms["code"]; ?></option>
                                        <?php }
                                        ?>

                            </select>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Default Payment Type:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">  
                        <?php $def_pay_type = @explode(',', $row['default_payment_type']); ?>
                        <select name="default_payment_type[]" id="default_payment_type" data-placeholder=" - - - Select Payment Types - - - " class="select-xlarge" multiple="multiple" style="width:310px; height:30px;">
                            <?php
                            $sqlptype = "SELECT vendors_payments_id,code FROM vendors_payment_types WHERE vendors_payments_id IN(".$row1['payment_types'].") ORDER BY code ASC";
                            $resultptype = mysql_query($sqlptype);
                            while ($rowptype = mysql_fetch_assoc($resultptype)) {
                                ?><option value="<?php echo $rowptype["vendors_payments_id"] ?>" <?php
                                if (in_array($rowptype["vendors_payments_id"], $def_pay_type)) {
                                    echo 'selected';
                                }
                                ?> ><?php echo $rowptype["code"]; ?></option>
                                    <?php } ?>
                        </select>
                    </div>

                </div>
            </td>
        </tr>
        

        <tr>
            <td width="50%">
                <label>Primary Contact Email: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class="control-group">
                    <div class="controls">
                        <input type="text" name="primary_contact_email" id="primary_contact_email" style="width:296px; height:20px;" value="<?php echo $row['primary_contact_email']; ?>" >
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Primary Contact Phone: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="primary_contact_phone" id="primary_contact_phone" style="width:296px; height:20px;" value="<?php echo $row['primary_contact_phone']; ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Note: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <textarea name="note" id="note" style="width:296px; height:auto; resize: none;" ><?php echo $row['notes']; ?></textarea>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Reminder:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="reminder" id="reminder" style="width:296px; height:20px;" value="<?php echo $row['reminder']; ?>" >
                    </div>
                </div>
            </td>
        </tr>
        
        <tr>
            <td width="50%">
                <label>Created On: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="created_on" id="created_on" style="width:296px; height:20px;" readonly="" value="VendorPanel" >
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Created By:</label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="created_by" id="created_by" style="width:296px; height:20px;" readonly="" value="<?php echo get_by($row['created_by']); ?>">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Created Date & Time: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="" id="created_datetime" style="width:296px; height:20px;" readonly="" value="<?php echo date('Y-m-d h:i', strtotime($row['created_datetime'])); ?>" />
                        <input type="hidden" id="hid_created_datetime" name="created_datetime" value="<?php echo $row['created_datetime']; ?>" />
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table width="98%" id="last_tbl">
    	<tr>
            <td width="50%">
                <label>Last On: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="last_on" id="last_on" style="width:296px; height:20px;" readonly="" value="<?php echo $row['last_on']?>" >
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Last By: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="last_by" id="last_by" style="width:296px; height:20px;" readonly="" value="<?php echo get_by($row['last_by']); ?>" >
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td width="50%">
                <label>Last Date & Time: </label>
            </td>
            <td width="50%" style="vertical-align: top;">
                <div class=" control-group">
                    <div class="controls">
                        <input type="text" name="last_datetime" id="last_datetime" style="width:296px; height:20px;" readonly="" value="<?php
                               if ($row['last_datetime'] != '') {
                                   echo date('Y-m-d h:i', strtotime($row['last_datetime']));
                               }
                               ?>" >
                    </div>
                </div>
                
            </td>
        </tr>        

    </table>

<?php } ?>

<script type="text/javascript">
    jQuery(document).ready(function (event) {		

        jQuery("#default_terms").chosen();
        jQuery("#default_delivery_type").chosen();
        jQuery("#default_payment_type").chosen();
		var xar = '';
		jQuery('#location_id').typeahead({
		source: function (query, process) {
			if(xar!=''){
				xar.abort();
				xar = '';
			}
			return xar = jQuery.ajax({
			//url: 'ajax_autocomplete.php', //juni -> switch file as someone keeps modifying it
			 url: 'messages/ajax_get_location_with_id.php',
				type: 'post',
				data: { query: query,  autoCompleteClassName:'autocomplete',
				employeeid:'<?php echo $_SESSION['client_id']?>',
				selectedClassName:'sel',
				attrCallBack:'rel',
				identifier:'estadoAll'},
				dataType: 'json',
				success: function (result) {

					var resultList = result.map(function (item) {
						var label=(item.label).replace("[[semicolon]]",":");
						var aItem = { id: item.id, name: label};
						return JSON.stringify(aItem);
					});
	
					return process(resultList);
	
				}
			});
		},
	
		matcher: function (obj) {
				var item = JSON.parse(obj);
				return ~item.name.toLowerCase().indexOf(this.query.toLowerCase())
			},
		
			sorter: function (items) {          
			   var beginswith = [], caseSensitive = [], caseInsensitive = [], item;
				while (aItem = items.shift()) {
					var item = JSON.parse(aItem);
					if (!item.name.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(JSON.stringify(item));
					else if (~item.name.indexOf(this.query)) caseSensitive.push(JSON.stringify(item));
					else caseInsensitive.push(JSON.stringify(item));
				}
		
				return beginswith.concat(caseSensitive, caseInsensitive)
		
			},
		
		
			highlighter: function (obj) {
				var item = JSON.parse(obj);
				var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
				return item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
					return '<strong>' + match + '</strong>'
				})
			},
		
			updater: function (obj) {
				var client_id = jQuery('#client_id').val();

				var item = JSON.parse(obj);
				var item_id = item.id;
				var item_name = item.name;

				//jQuery('#loc_id_search').attr('value', item.id);
				//loadlocemp(item.id);
				//return item.name;
				// check for vendor
				
				jQuery.ajax({
					url: 'storepoint_clients.php'
					,data: {
						'chk_ven_loc': 'y'
						,'chk_ven_loc_id': item_id
						,'chk_ven_id': client_id
					}
					,type: 'POST'
					,success: function(res){
						if( res == 'y' ){
							jAlert('The location already is linked to your account.', 'Alert');
							jQuery('#loc_id_search').attr('value', '');
						} else {
							//alert();
							jQuery('#loc_id_search').attr('value', item_id);
							loadlocemp(item_id);
							jQuery('#location_id').val(item_name);
							//return item_name;
						}
					}
				});
				
				
			}
		});
		
		jQuery('#primary_contact_employee_id').change(function(){
			var v = jQuery(this).val();
			console.log(v);
			jQuery.ajax({
			url: 'storepoint_get_vender_location_info.php?action=getEmpName&emid='+ v,
				type: 'GET',
				success: function (result) {
					console.log('==> '+result);
					jQuery("#primary_contact").val("");
					jQuery("#primary_contact").val(result);
				}
			});    
		});
		
		
    });
	
	function loadlocemp(id){
		jQuery.ajax({
			url: 'messages/ajax_autocomplete_loc_employee.php?loc='+ id,
				type: 'post',
				success: function (result) {
					console.log(result);
					jQuery("#primary_contact_employee_id").html("");
					// jQuery('#primary_contact_employee_id').trigger("liszt:updated");
					jQuery("#primary_contact_employee_id").html(result);
					// jQuery('#primary_contact_employee_id').trigger("liszt:updated");
				}
		})     
	}

    jQuery(function () {
        jQuery("#reminder").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            minDate: 0
        });
    });
</script>

