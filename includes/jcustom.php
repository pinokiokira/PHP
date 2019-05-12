<?php
/*
 *  @author Ionut Irofte - juniorionut @ elance 9:10 AM 2/1/2014 juni $
 *  Custom php class in which i will include what i needed overtime in several pages
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include_once 'includes/session.php';
include_once 'config/accessConfig.php';
$asterisk = ':<sup><span style="color:red"> *</span></sup>';
//29.05.2014 -> so that i won't have any more problems with "testers" that don't clear their cache
header('Content-type: text/html; charset=UTF-8');
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header("Expires: Fri, 01 Jan 1980 00:00:00 GMT");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**
*	
*	Simple function that returns currency details of location or just the currency   ([req REQ_018] - 2014-09-20 -)
   
* 	@param location_id location
* 	@param onlySymbol return only location currency symbol
*	@return either data otherwise dummy data
*/
function jGetLocCurrency($intLocationID,$onlySymbol = true){
	if ($onlySymbol==true)
		$data = "";
	else
		$data = array();
	if (isset($intLocationID)&&$intLocationID > 0) {
		$query =
		"SELECT  currency_id,currency_symbol,currency 
			FROM  locations 
		WHERE id = ".$intLocationID ;
		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_object($result);
		if ($onlySymbol==true)
			$data = $row->currency_symbol;
		else {
			$data['currency_id']=$row->currency_id;
			$data['currency']=$row->currency;
			$data['currency_symbol']=$row->currency_symbol;
		}

	} else {
		if ($onlySymbol==true)
			$data = 'INVALID';
		else {
			$data['currency_id']=0;
			$data['currency']='DUMMY';
			$data['currency_symbol']='INVALID';
		}
	}
	return $data;
}

/**
* Change of my function's notations
*/
function jFnc ($currencySymbol,$number,$precision) {
	return formatNumberWithCurrency ($currencySymbol,$number,$precision);
}
/**
* Change of my function's notations
*/
function jFormatNumberWithCurrency ($currencySymbol,$number,$precision) {
	return formatNumberWithCurrency ($currencySymbol,$number,$precision);
}


/**
Format the number provided to have the currency as prefix and in case of negative value put it between () 
Ex: corppanel/dashboard.php $revChart 	.= 	 "{v:" .number_format(round($revenue_restaurant,2),2) . ", f:'".formatNumberWithCurrency($revenue_restaurant,$curSymbol,2)."'},";
*/
function formatNumberWithCurrency ($currencySymbol,$number,$precision) {
	if (!is_numeric($number))
		return "";
	if ($currencySymbol == "")
		$currencySymbol = "$";		
	if (!is_numeric($precision))
		$precision = 2;
	if ($number < 0)
		return "(".$currencySymbol."".number_format(round(abs($number),$precision),$precision) .")";
	else
		return $currencySymbol."".number_format(round($number,$precision),$precision);
}

/**
 * 	Simple function which renders a sortable status column (that can be used in dyntable)
    31.01.2014 -> Status column as requested :  Use all icons, use strtoupper and both "short and long versions" of string ("active/a")
 * 	@param status string containing status
 *	@return status image with a span that will give the possiblity to sort the status column in dyntable
 */ 
function renderSortableStatus($status){
	$order_status = 0;
	if (strtoupper($status) == 'ACTIVE'||strtoupper($status) == 'A') {
		$status_img = 'Active-Corrected-Delivered-16.png';
		$status_msg = 'Active';
		$order_status = 1;
	} elseif (strtoupper($status) == 'INACTIVE' || strtoupper($status) == 'I') {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Inactive';
		$order_status = 2;
	} elseif (strtoupper($status) == 'CANCELLED' || strtoupper($status) == 'C') {
		$status_img = 'Cancelled-Terminated-16.png';
		$status_msg = 'Cancelled';
		$order_status = 3;
	} elseif (strtoupper($status) == 'COMPLETE') {
		$status_img = 'icon_enable.png';
		$status_msg = 'Completed';
		$order_status = 4;
	} elseif (strtoupper($status) == 'DISPUTED') {
		$status_img = 'notification-slash.png';
		$status_msg = 'Disputed';
		$order_status = 5;
	} elseif (strtoupper($status) == 'PENDING' || strtoupper($status) == 'P') {
		$status_img = 'Pending-Urgent-Bad Address-16.png';
		$status_msg = 'Pending';
		$order_status = 6;
	} elseif (strtoupper($status) == 'SUSPEND' || strtoupper($status) == 'S' || strtoupper($status) == 'SUSPENDED') {
		$status_img = 'Emergency-Incident-Suspended-16.png';
		$status_msg = 'Suspended';
		$order_status = 7;
	} elseif (strtoupper($status) == 'Not Confirmed' || strtoupper($status) == 'N') {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Not Confirmed';
		$order_status = 8;
	} else {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Unknown';
		$order_status = 9;
	}			
	return '<span style="display:none">'.$order_status.'</span><img src="images/' . $status_img . '" alt="' . $status_msg . '" title="' . $status_msg . '" border="0" />';
}
/**
 * 	Simple function which renders a sortable status column (that can be used in dyntable)
    2014-09-06	-> hotel_reports_rate_discrepancy_report.php
 * 	@param status string containing status
 *	@return status image with a span that will give the possiblity to sort the status column in dyntable
 */ 
function renderSortableHotelStatus($status){
	$order_status = 0;
	if (strtoupper($status) == 'CANCELLED') {
		$status_img = 'Cancelled_white.png';
		$status_msg = 'Shopping';
		$order_status = 1;
	} elseif (strtoupper($status) == 'CHECKOUT') {
		$status_img = 'CheckOut_white.png';
		$status_msg = 'Check Out';
		$order_status = 2;
	} elseif (strtoupper($status) == 'INHOUSE') {
		$status_img = 'InHouse_white.png';
		$status_msg = 'In House';
		$order_status = 3;
	} elseif (strtoupper($status) == 'NOSHOW') {
		$status_img = 'NoShow_white.png';
		$status_msg = 'No Show';
		$order_status = 4;
	} elseif (strtoupper($status) == 'PERMANENT') {
		$status_img = 'Permanent_white.png';
		$status_msg = 'Permanent';
		$order_status = 5;
	} elseif (strtoupper($status) == 'RESERVED') {
		$status_img = 'Reservation_white.png';
		$status_msg = 'Reserved';
		$order_status = 6;
	} elseif (strtoupper($status) == 'SHOPPING') {
		$status_img = 'Shopping_white.png';
		$status_msg = 'Shopping';
		$order_status = 7;
	}  else {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Unknown';
		$order_status = 9;
	}			
	return '<span style="display:none">'.$order_status.'</span><img src="images/' . $status_img . '" alt="' . $status_msg . '" title="' . $status_msg . '" border="0" />';
}
/**
 * 	Simple function which renders a sortable type column (that can be used in dyntable)
    2014-09-06	-> retail_payments.php
 * 	@param is togo 
 * 	@param is delivery 
 *	@return status image with a span that will give the possiblity to sort the status column in dyntable
 */ 
function jRenderRetailType ($isTogo,$isDelivery){
	$order_status = 0;
	if (strtoupper($isTogo) == 'YES') {
		$type_img = 'Cancelled_white.png';
		$type_msg = 'To Go';
		$type_status = 1;
	} else if (strtoupper($isDelivery) == 'YES') {
		$type_img = 'Status Buttons - Orders - Type - Delivery.png';
		$type_msg = 'Delivery';
		$type_status = 2;
	} else  {
		$type_img = 'Status Buttons - Orders - Type - Table.png';
		$type_msg = 'Table';
		$type_status = 3;
	}
			
	return '<span style="display:none">'.$type_status.'</span><img src="images/panel/' . $type_img . '" alt="' . $type_msg . '" title="' . $type_msg . '" border="0" />';
}

/**
 * 	Simple function which renders a location image or a default type one (that can be used in dyntable) @ 10.02.2014
	-> Created to be used initially in sales_survey_results 
	Usage: echo renderLocationImage($row['image'],$row['primary_type_name']);
 * 	@param image_url image url of the location
 *	@param primary_type_name location primary type (from location_types) - can be null
 *	@return status image with a span that will give the possiblity to sort the image column in dyntable
 */ 
function renderLocationImage($image_url,$primary_type_name = null){
	if (!empty($primary_type_name))
		$primary_type_name =(strtoupper(trim($primary_type_name)));
	$location_img = "";
	$location_msg = "";
	$image_order = 0;
	$primary = "images/primary-type/";
	if ($image_url!="") {//if we have a image, use that as a base
		$location_img = API . "images/" . $image_url;
		$image_order = 0;
	} else { //use default icon based on primary type
		if ($primary_type_name == 'BARS') {
			$location_img = $primary.'Default-Primary-Type-Bar.png';
			$location_msg = 'Bar';
			$image_order = 1;
		} elseif ($primary_type_name == 'BEAUTY AND HEALTH') {
			$location_img = $primary.'Default-Primary-Type-Beauty-And-Health.png';
			$location_msg = 'Beauty and Health';
			$image_order = 2;
		} elseif ($primary_type_name == 'CLUB') {
			$location_img = $primary.'Default-Primary-Type-Club.png';
			$location_msg = 'Club';
			$image_order = 3;
		} elseif ($primary_type_name == 'HOME') {
			$location_img = $primary.'Default-Primary-Type-Home.png';
			$location_msg = 'Home';
			$image_order = 4;
		} elseif ($primary_type_name == 'LOUNGES') {
			$location_img = $primary.'Default-Primary-Type-Lounge.png';
			$location_msg = 'Lounges';
			$image_order = 5;
		} elseif ($primary_type_name == 'PRIVATE') {
			$location_img = $primary.'Default-Primary-Type-Private.png';
			$location_msg = 'Private';
			$image_order = 6;
		} elseif ($primary_type_name == 'QUICK SERVICE') {
			$location_img = $primary.'Default-Primary-Type-Quick-Service.png';
			$location_msg = 'Quick Service';
			$image_order = 7;
		} elseif ($primary_type_name == 'RECREATION') {
			$location_img = $primary.'Default-Primary-Type-Recreation.png';
			$location_msg = 'Recreation';
			$image_order = 8;
		} elseif ($primary_type_name == 'RESTAURANTS') {
			$location_img = $primary.'Default-Primary-Type-Restaurant.png';
			$location_msg = 'Restaurants';
			$image_order = 9;
		} elseif ($primary_type_name == 'RETAIL') {
			$location_img = $primary.'Default-Primary-Type-Retail.png';
			$location_msg = 'Retail';
			$image_order = 10;
		} elseif ($primary_type_name == 'TRAVEL') {
			$location_img = $primary.'Default-Primary-Type-Travel.png';
			$location_msg = 'Travel';
			$image_order = 11;
		} else {
			$location_img = $primary.'Default-Primary-Type-Other.png';
			$location_msg = 'Other';
			$image_order = 12;
		}
	}
	return '<span style="display:none">'.$image_order.'</span><img src="' . $location_img . '" alt="' . $location_msg . '" title="' . $location_msg . '" border="0" style="width:80px;height:80px;" />';
}


/**
 * 	Simple function which renders a sortable reservation status column (that can be used in dyntable)
 * 	@param status string containing status
 *	@return status image with a span that will give the possiblity to sort the status column in dyntable
 */ 		
function renderReservationStatus($status){
	$order_status = 0;
	$status = strtoupper($status);
	if ($status == 'A') {
		$status_img = 'Active-Corrected-Delivered-16.png';
		$status_msg = 'Seated';
		$order_status = 1;
	} elseif ($status == 'C') {
		$status_img = 'Cancelled-Terminated-16.png';
		$status_msg = 'Cancelled';
		$order_status = 2;
	} elseif ($status== 'N') {
		$status_img = 'No-Show-16.png';
		$status_msg = 'No Show';
		$order_status = 3;
	} elseif ($status == 'R') {
		$status_img = 'Reserved-Yellow-16.png';
		$status_msg = 'Reserved';
		$order_status = 4;
	}  elseif ($status == 'WA') {
		$status_img = 'Walk-In-16.png';
		$status_msg = 'Walk-In';
		$order_status = 5;
	} elseif ($status == 'W') {
		$status_img = 'Waiting-16.png';
		$status_msg = 'Waiting';
		$order_status = 6;
	} else {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Unknown';
		$order_status = 9;
	}			
	return '<span style="display:none">'.$order_status.'</span><img src="images/' . $status_img . '" alt="' . $status_msg . '" title="' . $status_msg . '" border="0" />';
}
/**
 * 	Simple function which renders a sortable order status column (that can be used in dyntable)
 * 	@param status string containing status
 *	@return status image with a span that will give the possiblity to sort the status column in dyntable
 */ 		
function renderOrderStatus($status){
	$order_status = 0;
	$status = strtoupper($status);		
	if ($status == 'CANCELLED') {
		$status_img = 'Status Buttons - Orders - Status - Cancelled.png';
		$status_msg = 'Cancelled';
		$order_status = 1;
	} elseif ($status == 'CLOSED') {
		$status_img = 'panel/Status Buttons - Orders - Status - Closed.png';
		$status_msg = 'Closed';
		$order_status = 2;
	} elseif ($status== 'COMPLETED') {
		$status_img = 'Status Buttons - Orders - Status - Completed.png';
		$status_msg = 'Completed';
		$order_status = 3;
	} elseif ($status == 'ORDERED') {
		$status_img = 'Status Buttons - Orders - Status - Ordered.png';
		$status_msg = 'Ordered';
		$order_status = 4;
	}  elseif ($status == 'PRINTED') {
		$status_img = 'Status Buttons - Orders - Status - Print.png';
		$status_msg = 'Printed';
		$order_status = 5;
	} elseif ($status == 'STARTED'|| $status == 'OPEN') {
		$status_img = 'Status Buttons - Orders - Status - New.png';
		$status_msg = 'Started';
		$order_status = 6;
	} else {
		$status_img = 'Inactive-Missing-Punch-16.png';
		$status_msg = 'Unknown';
		$order_status = 9;
	}			
	return '<span style="display:none">'.$order_status.'</span><img src="images/' . $status_img . '" alt="' . $status_msg . '" title="' . $status_msg . '" border="0" />';
}

/**
 * 	Get countries and display them in a select box
    01.02.2014 -> get countries with custom class
 * 	@param nameAndID name and id of the new select
 * 	@param selected id of the country to be selected (if is the case)
 * 	@param stateContainer id of the state combobox (there state data will be populated)
 * 	@param newRecord is this a new record
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
function render_countries_combo($nameAndID, $selected, $stateContainer, $newRecord, $cClass = null, $cStyle = null) {
    $query = "SELECT id, code, name, is_default FROM countries WHERE status='A' ORDER BY name ASC";
    $output = mysql_query($query);
    $rows = mysql_num_rows($output);
	$class = "input-xlarge" ;
	$style = 'width:90%;height:23px;';
	if ($cClass!="")
		$class  = $cClass;
	if ($cStyle!="")
		$style  = $cStyle;
    if ($rows > 0 && $rows != '') {
	    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'" onchange="getStates(this.value,\'' . $stateContainer . '\')">';
        $data .= '<option value=""> - - -   Select Country  - - - </option>';
		$sel = "";
		if($selected==3) {
			$sel = ' selected="selected"';
		}
		$data .= '<option value="3" '. $sel .' >United States (US)</option>';
        while ($result = mysql_fetch_assoc($output)) {
            $id = $result['id'];
            $code = $result['code'];
            $name = $result['name'];
            $is_default = $result['is_default'];
            if($id != 3){
                if ($newRecord == 'new_entry') {
                    if ($is_default == 'yes') {
                        $sel = ' selected="selected"';
                    } else {
                        $sel = '';
                    }
                } else {
                    if ($id == $selected) {
                        $sel = ' selected="selected"';
                    } else {
                        $sel = '';
                    }
                }
                if($code != ''){
                    $data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($name) . ' (' . $code . ')</option>';
                }else{
                    $data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($name) . '</option>';
                }
            }
        }
        $data .= '</select>';
    } else {
        //$data = '<input type="text" name="visible_country" id="visible_country" value="No Country found with Active Status!"  style="width:259px;" disabled="disabled">';
        //$data .= ' <a href="countries.php?action=add"> add/modify here!</a>';
        //$data .= '<input type="hidden" name="' . $n . '" id="' . $n . '" value="0"  style="width:259px;">';
    }
    return $data;
}	
/**
 * 	Get payments for a specific location and client and render them in a select box  @ 10:00 AM 2/2/2014
	Designed to be used initially in setup_expensetab_disputes_detail
	Ex: <?php echo render_expensetab_payments_combo('expensetab_payment_id',$row_allworker["client_id"],$row_allworker["location_id"],$row_allworker['expensetab_payment_id'],'input-short','width:28%;height:23px;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param client_id id of client
 * 	@param location_id id of location
 * 	@param paymentID current payment id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
function render_expensetab_payments_combo($nameAndID,$client_id, $location_id, $paymentID, $cClass = null, $cStyle = null) {//	02.03.2014 

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">';
    if ($client_id!='' && $location_id != '') {    	  
	    //$output = mysql_query("SELECT ep.id,CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')','->','c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name,ep.status,l.id as location_id,
	    //Too long?
	    //CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')',' -> ') as name,
	    //CONCAT(c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name_to,
	    $sql = "SELECT ep.id,
	    CONCAT(c.name_first,' ',c.name_last,' -> ') as name,
	    CONCAT(c2.name_first,' ',c2.name_last,' ') as name_to,
	    ep.status,l.id as location_id,
	    ep.to_expensetab_client_account,
	    ep.from_type,
	    ep.amount,ep.datetime,c.name_first,c.name_last,c.id as client_id
		  FROM expensetab_payments ep  
	    LEFT JOIN client_expensetab_accounts cea ON cea.id=ep.expensetab_client_account
	    LEFT JOIN clients c ON c.id=cea.client_id
	    LEFT JOIN location_expensetab_accounts lea ON lea.id=ep.expensetab_location_account
	    LEFT JOIN locations l ON l.id=lea.location_id
	    LEFT JOIN client_expensetab_accounts cea2 ON cea2.id=ep.to_expensetab_client_account
	    LEFT JOIN clients c2 ON c2.id=cea2.client_id
	    WHERE ep.expensetab_client_account='$client_id'  AND  expensetab_location_account='$location_id'
	    ORDER BY ep.id DESC" ;
	    //WHERE cea.client_id=$client_id AND lea.location_id=$location_id
	   // echo $sql;
	    $output = mysql_query($sql);								
	    $rows = mysql_num_rows($output);
	    if ($rows > 0 && $rows != '') {	
		$data .= '<option value=""> - - -   Select Payment  - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
		    //print_r($result);exit;
		    $id = $result['id'];
		    $amount = $result['amount'];
		    $name = $result['name'].$result['name_to'];
		    if ($id == $paymentID) {
			$sel = ' selected="selected"';
		    } else {
			$sel = '';
		    }
		    if($amount != ''){
			//$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(PayId: ".$id.") ".$name) . ' (Amount:' . $amount . ')</option>';
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(PayId: ".$id.") ".$name) . ' (Amount:' . $amount . ')</option>';
		    }else{
			//$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($name) . '</option>';
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(PayId: ".$id.") ".$name) . '</option>';
		    }
		    //}
		}
	    } else {
		 $data .= '<option value=""> - - - No Payment Found - - - </option>';
		//$data = '<input type="text" name="visible_country" id="visible_country" value="No Country found with Active Status!"  style="width:259px;" disabled="disabled">';
		//$data .= ' <a href="countries.php?action=add"> add/modify here!</a>';
		//$data .= '<input type="hidden" name="' . $n . '" id="' . $n . '" value="0"  style="width:259px;">';
	    }
    } else {
      $data .= '<option value=""> - - - No Payment Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}	


/**
 * 	Get orders for a specific location and client and render them in a select box  @ 10:00 AM 2/5/2014
	Designed to be used initially in setup_expensetab_payments_detail
	Ex: <?php echo render_expensetab_orders_combo('client_order_id',$row_allworker["client_id"],$row_allworker["location_id"],$row_allworker['client_order_id'],'input-short','width:28%;height:23px;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param client_id id of client (expense tab client account id)
 * 	@param location_id id of location (expense tab location account id)
 * 	@param paymentID current payment id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
function render_expensetab_orders_combo($nameAndID,$client_id, $location_id, $orderID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">';
    if ($client_id!='' && $location_id != '') {    	  
	    //$output = mysql_query("SELECT ep.id,CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')','->','c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name,ep.status,l.id as location_id,
	    //CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')',' -> ') as name,
	    //CONCAT(c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name_to,
	    $sql = "SELECT co.id,
	    CONCAT(c.name_first,' ',c.name_last,' -> ') as name,
	    co.order_subtotal,co.order_payments,co.order_total
		  FROM client_orders co  	   
	    LEFT JOIN clients c ON c.id=co.client_id
	    LEFT JOIN client_expensetab_accounts cea ON cea.client_id=c.id
	    LEFT JOIN locations l ON l.id=co.location_id
	    LEFT JOIN location_expensetab_accounts lea ON lea.location_id=l.id
	    WHERE cea.id='$client_id' AND lea.id='$location_id'
	    ORDER BY co.id DESC" ;
	   //echo $sql;
	    $output = mysql_query($sql);								
	    $rows = mysql_num_rows($output);
	    if ($rows > 0 && $rows != '') {	
		$data .= '<option value=""> - - -   Select Order  - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
		    $id = $result['id'];
		    $order_payments = $result['order_payments'];
		    $order_subtotal = $result['order_subtotal'];
		    $order_total = $result['order_total'];
		    $name = $result['name'];
		    if ($id == $orderID) {
			$sel = ' selected="selected"';
		    } else {
			$sel = '';
		    }
		    if($order_subtotal != ''){
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Order ID: ".$id.")") . ' Sub:' . $order_subtotal. ', Total:' . $order_total . '</option>';
			//$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Order ID: ".$id.")") . ' Sub:' . $order_subtotal. ', Pay:' . $order_payments. ', Total:' . $order_total . '</option>';
		    }else{
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Order ID: ".$id.") Client:".$name) . ' Sub:' . $order_subtotal. ' Pay:' . $order_payments. ' Total:' . $order_total . '</option>';
		    }
		    //}
		}
	    } else {
		 $data .= '<option value=""> - - - No Order Found - - - </option>';
	    }
    } else {
      $data .= '<option value=""> - - - No Order Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}	

/**
 * 	Get sales for a specific location and client and render them in a select box  @ 10:00 AM 2/5/2014
	Designed to be used initially in setup_expensetab_payments_detail
	Ex: <?php echo render_expensetab_orders_combo('client_order_id',$row_allworker["client_id"],$row_allworker["location_id"],$row_allworker['client_order_id'],'input-short','width:28%;height:23px;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param client_id id of client (expense tab client account id)
 * 	@param location_id id of location (expense tab location account id)
 * 	@param paymentID current payment id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
function render_expensetab_sales_combo($nameAndID,$client_id, $location_id, $orderID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">';
    if ($client_id!='' && $location_id != '') {    	  
	    //$output = mysql_query("SELECT ep.id,CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')','->','c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name,ep.status,l.id as location_id,
	    //CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')',' -> ') as name,
	    //CONCAT(c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name_to,
	    $sql = "SELECT cs.id,
	    CONCAT(c.name_first,' ',c.name_last,' -> ') as name,
	    cs.subtotal,cs.payments_amt,cs.order_total
		  FROM client_sales cs  	   
	    LEFT JOIN clients c ON c.id=cs.client_id
	    LEFT JOIN client_expensetab_accounts cea ON cea.client_id=c.id
	    LEFT JOIN locations l ON l.id=cs.location_id
	    LEFT JOIN location_expensetab_accounts lea ON lea.location_id=l.id
	    WHERE cea.id='$client_id' AND lea.id='$location_id'
	    ORDER BY cs.id DESC" ;
	   //echo $sql;
	    $output = mysql_query($sql);								
	    $rows = mysql_num_rows($output);
	    if ($rows > 0 && $rows != '') {	
		$data .= '<option value=""> - - -   Select Sale  - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
		    $id = $result['id'];
		    $payments_amt = $result['payments_amt'];
		    $subtotal = $result['subtotal'];
		    $order_total = $result['order_total'];
		    $name = $result['name'];
		    if ($id == $orderID) {
			$sel = ' selected="selected"';
		    } else {
			$sel = '';
		    }
		    if($subtotal != ''){
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Sale ID: ".$id.") Client:".$name) . ' Sub:' . $subtotal. ' Pay:' . $payments_amt. '</option>';
			//$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Order ID: ".$id.")") . ' Sub:' . $order_subtotal. ', Pay:' . $order_payments. ', Total:' . $order_total . '</option>';
		    }else{
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(Sale ID: ".$id.") Client:".$name) . ' Sub:' . $subtotal. ' Pay:' . $payments_amt. ' Total:' . $order_total . '</option>';
		    }
		    //}
		}
	    } else {
		 $data .= '<option value=""> - - - No Sale Found - - - </option>';
	    }
    } else {
      $data .= '<option value=""> - - - No Sale Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}	

/**
 * 	Get hotel account for a specific location and render them in a select box  @ 10:00 AM 2/5/2014
	Designed to be used initially in setup_expensetab_payments_detail
	Ex: <?php echo render_expensetab_orders_combo('client_order_id',$row_allworker["client_id"],$row_allworker["location_id"],$row_allworker['client_order_id'],'input-short','width:28%;height:23px;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param client_id id of client (expense tab client account id) - kept here that i keep the function params number
 * 	@param location_id id of location (expense tab location account id)
 * 	@param paymentID current payment id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
function render_expensetab_hotelacct_combo($nameAndID,$client_id, $location_id, $orderID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">';
    if ($location_id != '') { //CANNOT BE JOIN BY CLIENTS AS HOTEL ACCT HAS EMPLOYEE
   // if ($client_id!='' && $location_id != '') {    	    
	    //$output = mysql_query("SELECT ep.id,CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')','->','c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name,ep.status,l.id as location_id,
	    //CONCAT(c.name_first,' ',c.name_last,'(ID: ',c.id,')',' -> ') as name,
	    //CONCAT(c2.name_first,' ',c2.name_last,'(ID: ',c2.id,')') as name_to,
	    /** CANNOT BE JOIN BY CLIENTS AS HOTEL ACCT HAS EMPLOYEE
	        LEFT JOIN clients c ON c.id=lh.employee_id
	    LEFT JOIN client_expensetab_accounts cea ON cea.client_id=c.id	
	    LEFT JOIN employees em ON em.id = lha.employee_id 
	    WHERE cea.id='$client_id' AND
	    */
	    $sql = "SELECT  lh.id,lh.account_id,
		  CONCAT(em.first_name,' ',em.last_name,'(ID: ',em.id,')') as name
		  FROM location_hotelacct lh
	    LEFT JOIN locations l ON l.id=lh.location_id
	    LEFT JOIN location_expensetab_accounts lea ON lea.location_id=l.id
	    LEFT JOIN employees em ON em.id = lh.employee_id 
	    WHERE lea.id='$location_id' 
	    ORDER BY lh.id DESC" ;
	    $output = mysql_query($sql);								
	    $rows = mysql_num_rows($output);
	    if ($rows > 0 && $rows != '') {	
		$data .= '<option value=""> - - -   Select Account  - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
		    $id = $result['id'];
		    $account_id = $result['account_id'];
		   // $name = $result['name'];
		    if ($id == $orderID) {
			$sel = ' selected="selected"';
		    } else {
			$sel = '';
		    }
		    if($account_id != ''){
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(ID: ".$id.") ").$account_id.'</option>';
		    }else{
			$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode("(ID: ".$id.") ").'</option>';
		    }
		    //}
		}
	    } else {
		 $data .= '<option value=""> - - - No Account Found - - - </option>';
	    }
    } else {
      $data .= '<option value=""> - - - No Account Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}	
/**
 * 	Get global currencies  @ 10:13 PM 2/4/2014
	-> Created to be used initially in setup_expensetab_payments_detail.php (check for working example)
 * 	@param nameAndID name and id of the new select
 * 	@param $curencyID current payment id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 

function render_currency_combo($nameAndID,$curencyID, $cClass = null, $cStyle = null) {//04.02.2014 

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
    $sql = "SELECT *  FROM global_currency c ORDER BY description ASC" ;
    $output = mysql_query($sql);								
    $rows = mysql_num_rows($output);
    if ($rows > 0 && $rows != '') {	
		$data .= '<option value=""> - - -   Select Currency - - - </option>';
		while ($result = mysql_fetch_assoc($output)) {
			//print_r($result);exit;
			$id = $result['id'];
			$code = $result['code'];
			$description = $result['description'];
			$symbol = $result['symbol'];
			if ($id == $curencyID) {
				$sel = ' selected="selected"';
			} else {
				$sel = '';
			}
			if($symbol != ''){
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description." (".$symbol.") ").'</option>';
			}else{
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description." (Code: ".$code.") ") . '</option>';
			}
			//}
		}
    } else {
	  $data .= '<option value=""> - - -   No Currency Found  - - - </option>';
    }
    $data .= '</select>';
    return $data;
}
/**
 * 	Get zone_id currencies  @ 8:43 AM 2/8/2014
	-> Created to be used initially in location_table.php (check for working example)
	//	<?php //echo render_location_zone_combo('zone_id',10001,$zone_id,'input-short','width:90%;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param location_id location to get the zones from
 * 	@param zone_id current zone id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 

function render_location_zone_combo($nameAndID,$locationID,$zoneID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		$sql = "SELECT id,code FROM location_tables_zone WHERE location_id='".$locationID."' ORDER BY code ASC" ;
		$output = mysql_query($sql);								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Zone - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				$id = $result['id'];
				$code = $result['code'];
				$description = $result['description'];
				$symbol = $result['symbol'];
				if ($id == $zoneID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				if($code != ''){
					$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description." (Zone ID: ".$id.") " . $code) . '</option>';
				}else{
					$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Zone ID: ".$id.") ").'</option>';
				}
				//}
			}
		} else {
		  $data .= '<option value=""> - - -   No Zone Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Zone Found- - - </option>';
    }
    $data .= '</select>';
    return $data;
}
/**
 * 	Create dropdown from inventory_group table @ 12:58 PM 9/14/2014
	-> Created to be used initially in setup_backoffice_global_items.php (check for working example)
	//	<?php //echo jRender_inventory_group_combo('group_id',10004,$group_id,'input-short','width:90%;');?>
 * 	@param nameAndID name and id of the new select
 * 	@param location_id location to get the zones from
 * 	@param grup_id current group id ( to be selected)
 * 	@param cClass Custom css class
 * 	@param cStyle Custom stle
 *	@return combobox with custom style , selected and data
 */ 
 
 

function jRender_inventory_group_combo($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null,$req_market) {

    $class = "input-xlarge" ;
	$mval ='';
	$sqlval='';
	$limit = 500;
	if (isset($_GET['market'])&& trim($_GET['market'])!='') {
	$mval = $_GET['market'];
	$sqlval =  " where ig.Market='".$mval."'";	
	}else if($req_market=='yes' && trim($_GET['market'])==''){
		$limit = 0;
	}

    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="' . $nameAndID . '" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
		/*"SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id 
				$sqlval	AND lii.location_id = '".$locationID."'	
				ORDER BY ig.description ASC" ;*/
		$sql = 	"SELECT distinct(tbl.id),tbl.description from (
			(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global' $sqlval AND lii.location_id = '".$locationID."' 
		ORDER BY ig.description ASC)
UNION ALL 
		(SELECT distinct(ig.id) as id,ig.description 
				FROM inventory_groups ig			
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND  lii.type<>'global' $sqlval AND lii.location_id = '".$locationID."' 
		ORDER BY ig.description ASC)) as tbl ORDER BY description LIMIT $limit";
		//echo $sql;exit;
		$output = mysql_query($sql) or die(mysql_error());								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Item Group - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				$id = $result['id'];
				$description = $result['description'];
				if ($id == $groupID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Item Group Found - - - </option>';
    }
    $data .= '</select>';
    return $data;
}

/**
 * 	Create Monthly Charges  @ 3:44 PM 2/13/2014
	-> Created to be used initially in sales_monthly_charges.php (check for working example)
	Usage: $result = createMonthlyCharges($_POST['month_year'],true,true,true,'AdminPanel',date("Y-m-d H:i"),$_SESSION['userid']);
		if ($result==true)
			header("Location:".$_SERVER['PHP_SELF']."?ok=3");//juni -> redirect to ok
		else 
			header("Location:".$_SERVER['PHP_SELF']."?ok=4");//juni -> redirect so that i can know that an error occured
 * 	@param month_year current month an year (February 2014)
 * 	@param insertBillingRecords - should data be inserted into location_internal_billing
 * 	@param updateDeleteExisting - should i update/delete records in location_internal_billing_details
 * 	@param insertDetailsRecords - should i insert  records in the location_internal_billing_details
 * 	@param created_on where the record has been created ('AdminPanel')
 * 	@param created_datetime when should be records be set as created (date("Y-m-d H:i"))
 * 	@param created_by user_id
 *	@return true or error
 */ 
function createMonthlyCharges($month_year,$insertBillingRecords,$updateDeleteExisting,$insertDetailsRecords 
	,$created_on = '',$created_datetime = '',$created_by = '') {
	$debug = false;
	if (empty($month_year))
		return "Month and year are mandatory - ex: February 2014";
	if ($insertBillingRecords!=true&&$updateDeleteExisting!=true&&$insertDetailsRecords!=true)
		return "Nothing do do, please check params";
		
	if (empty($created_on))
		$created_on = 'AdminPanel';
	if (empty($created_datetime))
		$created_datetime = date("Y-m-d H:i");
	if (empty($created_by))		
		$created_by = $_SESSION['userid'];	
	//
	$checkBoxes = array('pos','hotel','retail','bar','reservations','time','concierge','corporate','crs','crm','delivery','event','manage','menu','prep','quality','quick');
	//
	$start_date = date('Y-m-01', strtotime($month_year));
	$end_date = date('Y-m-t', strtotime($month_year));
	$month = date('M', strtotime($month_year));
	$year = date('Y', strtotime($month_year));
	$verif = date('F Y', strtotime($month_year));

	if ($verif==$month_year) {//to be 100% sure i get the date correctly
		$productsFrom = " lip.start_date >='$start_date' AND lip.end_date<='$end_date'";
		$billingFrom = " lib.month='$month' AND lib.year='$year'";
		//Check 1: location_internal_billing has records on the selected date (month)
		if ($insertBillingRecords==true) {
			$firstQuery = "
			SELECT DISTINCT(lip.location_id) as location_id	
				FROM location_internal_products lip 
			WHERE $productsFrom 
				AND lip.location_id NOT IN 
				(
					SELECT DISTINCT(location_id) as location_id
					FROM location_internal_billing lib 
					WHERE $billingFrom 
				)
			ORDER BY lip.location_id ASC
			";
			$result = mysql_query($firstQuery) or die(mysql_error());		   
			while($prRow = mysql_fetch_array($result)) {
				$location_id = $prRow['location_id'];
				$sql = "
				INSERT INTO location_internal_billing (
					location_id, month , year , `number of products`, total_amount, created_on, created_by,created_datetime 
				) VALUES (
					'$location_id', '$month', '$year', '0', '0', '$created_on', '$created_by', '$created_datetime'	
				)";//setting a default of 0 on number of products and total_amount
				if ($debug)	echo $sql." </br>\n";
				mysql_query($sql) or die(mysql_error());			
			}
		}
		//Check 2: location_internal_billing has records in the location_internal_billing_details and i only need to update or delete if it's the case
		if ($updateDeleteExisting==true) {
			$secondQuery = "
				SELECT lib.id as lib_id
					,libd.location_internal_billing_id
					,libd.id as location_internal_billing_detai_id
					,libd.product as product_detail
					,lip.*
					FROM location_internal_products lip 
					INNER JOIN location_internal_billing lib ON lip.location_id=lib.location_id
					INNER JOIN location_internal_billing_details libd ON libd.location_internal_billing_id=lib.id
				WHERE $productsFrom  AND $billingFrom
				ORDER BY lip.location_id ASC,libd.id ASC
			";
			$result = mysql_query($secondQuery) or die(mysql_error());		   
			while($prRow = mysql_fetch_array($result)) {
			//echo "<pre>".print_r ($prRow)."</pre><br/>";exit;
				foreach  ($checkBoxes as $key=>$checkBox) {//for each checkbox, if i have yes, i need to insert into location_internal_billing_details			
					$recID = $prRow['location_internal_billing_detai_id'];	
					$bilID = $prRow['lib_id'];	
					$detID = $prRow['location_internal_billing_id'];		
					if ($prRow[$checkBox]=="Yes") {							
						//echo "YES -> recID:".$recID."_".$bilID."-".$detID.":".$prRow[$checkBox]."<br/>";
						$amount = $prRow[$checkBox."_amount"];
						if ($checkBox==$prRow['product_detail']) {
							$sql = "UPDATE location_internal_billing_details SET amount = '$amount' WHERE id = '$recID'";
							if ($debug)	echo $sql." </br>\n";
							mysql_query($sql) or die(mysql_error());			
						}						
					} else {
						if ($checkBox==$prRow['product_detail']) {
							//echo "NO -> recID:".$recID."_".$bilID."-".$detID.":".$prRow[$checkBox]."<br/>";
							$sql = "DELETE FROM location_internal_billing_details WHERE id = '$recID'";
							if ($debug)	echo $sql." </br>\n";
							mysql_query($sql) or die(mysql_error());				
						}
					}
				}
				//echo "<pre>".print_r ($prRow)."</pre>";exit;
			}
		}
		//Check 3: location_internal_billing_details has records on the selected month (year) 
		if ($insertDetailsRecords==true) {
			$sql = "
				SELECT *,lib.id as location_internal_billing_id
					FROM location_internal_products lip 
					INNER JOIN location_internal_billing lib ON lip.location_id=lib.location_id
				WHERE $productsFrom  AND $billingFrom
				ORDER BY lip.location_id ASC
			";
			$result = mysql_query($sql) or die(mysql_error());		   
			while($prRow = mysql_fetch_array($result)) {
				foreach  ($checkBoxes as $key=>$checkBox) {//for each checkbox, if i have yes, i need to insert into location_internal_billing_details
					if ($prRow[$checkBox]=="Yes") {
						$location_internal_billing_id = $prRow['location_internal_billing_id'];
						$location_id = $prRow['location_id'];
						$amount = $prRow[$checkBox."_amount"];
						//echo $checkBox."-".$prRow[$checkBox]."-".$prRow[$checkBox."_amount"]."<br/>";
						$sql = "
						INSERT INTO location_internal_billing_details (
							location_internal_billing_id,status,location_id,product,amount,created_on,created_by,created_datetime 
						) VALUES (
							'$location_internal_billing_id', 'Active', '$location_id', '$checkBox', '$amount', '$created_on', '$created_by', '$created_datetime'	
						)
							ON DUPLICATE KEY UPDATE amount='$amount'
						";
						if ($debug)	echo $sql." </br>\n";
						mysql_query($sql) or die(mysql_error());								
					}
				}
			}
		}
		//Forth queries -> update location_internal_billing number of products and total amount based on what's inserted above
		$sql = "UPDATE location_internal_billing lib SET `number of products`= (SELECT COUNT(*) FROM location_internal_billing_details libd WHERE libd.location_internal_billing_id=lib.id)";
		$result = mysql_query($sql) or die(mysql_error());
		if ($debug)	echo $sql." </br>\n";
		$sql = "UPDATE location_internal_billing lib SET total_amount = (SELECT SUM(libd.amount) AS amount FROM location_internal_billing_details libd WHERE libd.location_internal_billing_id=lib.id)";
		$result = mysql_query($sql) or die(mysql_error());		   		
		if ($debug)	echo $sql." </br>\n";
		//so that i know if everything went smooth
		return true;
	}
}

/**
 * 	Create Location Guest Minibar Entries  @ 9:58 AM 2/16/2014
	-> Created to be used initially in quick_activity.php (check for working example)
	Usage: $result = createMinibarEntries($myID,true,'Minibar',0,0,'');
		if ($result==true)
			header("Location:".$_SERVER['PHP_SELF']."?ok=3");//juni -> redirect to ok
		else 
			header("Location:".$_SERVER['PHP_SELF']."?ok=4");//juni -> redirect so that i can know that an error occured
 * 	@param room_id location_rooms.room_id
 * 	@param insertMinibarRecords - should data be inserted into location_guest_minibar
 * 	@param menu type - such as "Minibar"
 * 	@param last_inventory - date last inventory
 * 	@param last_quantity - last quantity
 * 	@param last_emp_id employee_id
 *	@return true or error
 */ 
 function createMinibarEntries($room_id,$insertMinibarRecords,$menu_type,$last_inventory = '',$last_quantity = '',$last_emp_id = '') {
 
	$debug = false;
	if (empty($room_id))
		return "Room ID is mandatory";
	if (empty($menu_type))
		return "Menu type is mandatory";		
	if (empty($last_inventory))
		$last_inventory = date("Y-m-d H:i");
	if (empty($last_emp_id)||$last_emp_id < 0)		
		$last_emp_id = $_SESSION['employee_id'];	
		
	if ($insertMinibarRecords==true) {
		$firstQuery = "
			SELECT DISTINCT(lmi.location_id) as location_id,lmi.menu_id,lma.id AS menu_item
				FROM location_menu_articles lma 
			INNER JOIN location_menu_items lmi on lma.id=lmi.item_id
			INNER JOIN location_menus lm ON lm.id=lmi.menu_id
			INNER JOIN location_rooms lr ON lmi.location_id=lr.location_id
				WHERE lm.menu='$menu_type'
			AND lr.id = '$room_id'
			AND lma.id NOT IN 
			(
				SELECT  DISTINCT(lgm.menu_item) AS menu_item 
					FROM location_guest_minibar lgm 
				WHERE lgm.room_id = '$room_id'
			)
		ORDER BY lmi.id ASC
		";
		$result = mysql_query($firstQuery) or die(mysql_error());		   
		while($prRow = mysql_fetch_array($result)) {
			$location_id = $prRow['location_id'];
			$menu_id = $prRow['menu_id'];
			$menu_item = $prRow['menu_item'];
			$sql = "
			INSERT INTO location_guest_minibar (
				location_id, room_id , menu_id , menu_item, last_inventory, last_quantity, last_emp_id
			) VALUES (
				'$location_id', '$room_id', '$menu_id', '$menu_item', '$last_inventory', '0', '$last_emp_id'
			)";//setting a default of 0 on number of products and total_amount
			if ($debug)	echo $sql." </br>\n";
			mysql_query($sql) or die(mysql_error());			
		}
	}
}
/**
 * 	Simple function to retrieve data from sql clause  @ 2:23 PM 6/30/2014
	-> Created to be used initially in crm_dashboard.php (check for working example)
	Usage: $revenue_day = getRecord("SELECT SUM(order_subtotal) AS total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND date(co.`order_date`) = date(now())",'total');
 * 	@param sql Sql clause
 * 	@param column_name name of the column to retrieve
 *	@return column value or error
 */
function getRecord($sql,$column_name) {
	if (empty($sql))
		return null;
	$results = 	 mysql_query($sql) or die(mysql_error());
	if ( mysql_affected_rows() > 0) {
		while ($row = mysql_fetch_object($results)) {
			return $row ->$column_name;
		}
	} else
		return null;
}
function encode($CCNumber)
{
	for($i=0;$i<strlen($CCNumber);$i++)
		{
		$var1 = (48)+(3+$CCNumber[$i]);
		$var2 = chr($var1);
		$encodedCC .= $var2;
		}
	return base64_encode($encodedCC);	
}


function decode($CCNumber)
{
	$CCNumber = base64_decode($CCNumber);
	for($i=0;$i<strlen($CCNumber);$i++)
	{
		$var1 = ord($CCNumber[$i]);
		$var2 = $var1 - (48+(3));
		$decodedCC .= $var2;
	}
	return str_repeat("X", 4).'-'.str_repeat("X", 4).'-'.str_repeat("X", 4).'-'.substr($decodedCC,-4);
	
}

function decodeWx($CCNumber)
{	
	$CCNumber = base64_decode($CCNumber);
	for($i=0;$i<strlen($CCNumber);$i++)
	{
		$var1 = ord($CCNumber[$i]);
		$var2 = $var1 - (48+(3));
		$decodedCC .= $var2;
	}
	return substr($decodedCC,-4);
	
}
?>