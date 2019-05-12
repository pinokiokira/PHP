<?php //ini_set('display_errors',1);
    //error_reporting(E_ALL|E_STRICT);
ob_start("ob_gzhandler");
include_once 'includes/session.php';
include_once("config/accessConfig.php");

$emploselect = mysql_query("SELECT id from employees WHERE location_id = '".$_SESSION['loc']."'");
$emploselectarr = mysql_fetch_array($emploselect);

//$get_emp = mysql_fetch_assoc(mysql_query("SELECT id FROM employees WHERE email = '{$_SESSION["email"]}'"));
$_SESSION['employee_id'] = $emploselectarr['id'];

$backofficeDropDown = "display:block;";
$backofficeHead 	= "active";
$inventoryHead      = "active";
$inventoryDropDown  = "display:block;";
$inventoryMenu7     = "active";

/* -- Temporary used for the count method -- */
$marktmp 	 = '';
$group_idtmp = '';
$keywwds 	 = '';
$token1tmp 	 = '';
$tokentmp 	 = '';
$sttemp 	 = '';
/* -- End -- */
function unitDropdown($arr){
    $return = '';
    foreach($arr as $a){
        $return .= "<option value='" . $a['id'] . "'>" . $a['unit_type'] . "</option>";
    }
    return $return;
}
function unitDropdown1($arr,$type){
    $return = '';
    foreach($arr as $a){
		if($a['unit_type']==$type){ $selected ="selected='selected'";}else{$selected='';}
        $return .= "<option  $selected value='" . $a['id'] . "'>" . $a['unit_type'] . "</option>";
    }
    return $return;
}
function unitdrop($unit_types)
{
	foreach($unit_types as $ty):
		{
		foreach($ty as $r)	
		$return .="<option value='" . $r['id'] . "'>" . $r['unit_type']. "</option>";
		}
		endforeach;
		return $return;
}

function getTotalCount($inv_item_id)
{
	global $marktmp;
	global $group_idtmp;
	global $keywwds;
	global $token1tmp;
	global $tokentmp;
	global $sttemp;
	$result = 0;
	//$movements = json_decode(file_get_contents(API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$marktmp.'&group_id='.$group_idtmp.'&location=' . $_SESSION['loc'] . '&storeroom=' . $sttemp . '&website&keyword='.$keywwds.'&token1=' . $token1tmp .'&token='.$tokentmp),true);
	//if($movements['status'] == 'success'){	
		//$storerooms = $movements['response']['storerooms'];
		//$st_id = $storerooms['storeroom_id'];
		//foreach ($storerooms as $storeea) {
			//echo $storeea['storeroom_id'];
			$sql1 = "SELECT SUM(lic.quantity) as tot FROM location_inventory_counts lic 
				LEFT JOIN employees ON lic.employee_id=employees.id 
				LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
				WHERE inv_item_id=".$inv_item_id." AND lic.location_id=".$_SESSION['loc']." 
				AND lic.storeroom_id=".$_REQUEST['st']." ORDER BY lic.date_counted DESC, lic.time_counted DESC";

			$quers = mysql_query($sql1);
			$arr = mysql_fetch_array($quers);
			
			$result += (float)$arr['tot'];
		//}
		/*$sql1 = "SELECT SUM(lic.quantity) FROM location_inventory_counts lic LEFT JOIN employees ON lic.employee_id=employees.id LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type WHERE inv_item_id=9848 AND lic.location_id=272867 AND lic.storeroom_id=287 ORDER BY lic.date_counted DESC, lic.time_counted DESC";*/
	//}

	if ($result && !is_null($result)) {
		return $result;	
	}else {
		return '';
	}
}

function jRender_inventory_market_combo1($nameAndID,$locationID,$groupID, $cClass = null, $cStyle = null) {

    $class = "input-xlarge" ;
    $style = 'width:90%;height:23px;';
    if ($cClass!="")
	    $class  = $cClass;
    if ($cStyle!="")
	    $style  = $cStyle;
    $data = '<select name="market" id="' . $nameAndID . '"  class="'.$class.'" style="'.$style.'">'; 
	if ($locationID!="") {
		//INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id WHERE lii.location_id='".$locationID."' //BP BO - Setup Global Items - Sep 16 - SF01.jpg -> no location filter
	$sql1 = "SELECT distinct(market) from ((SELECT distinct(ig.Market) as market
				FROM inventory_groups ig
				INNER JOIN inventory_items ii ON ig.id=ii.inv_group_id
				INNER JOIN location_inventory_items lii ON lii.inv_item_id=ii.id AND lii.type='global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."')
				UNION ALL 
				(SELECT distinct(ig.Market) as market
				FROM inventory_groups ig				
				INNER JOIN location_inventory_items lii ON lii.local_group_id=ig.id AND lii.type<>'global'
				where ig.market !='NULL' AND lii.location_id = '".$locationID."')) as market ORDER BY market";
		//echo $sql;exit;
		$output = mysql_query($sql1) or die(mysql_error());								
		$rows = mysql_num_rows($output);	
			
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -  Select Market - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				
				$market = $result['market'];
				//echo $market;
				if ($result['market'] == $_REQUEST['market']) {
					$sel1 = ' selected="selected"';
				} else {
					$sel1 = '';
				}
				$data .= '<option value="' . $market . '"' . $sel1 . '>' .$market.'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -  No Market Found  - - - </option>';
		}
	} else {
      $data .= '<option value=""> - - -  No Market Found  - - - </option>';
    }
    $data .= '</select>';
return $data;
  
}
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



function storeroomDropdown($arr){
    $return = '';
    foreach($arr as $a){
        if($a['storeroom_id'] != $_GET['st']){
            $return .= "<option value='" . $a['storeroom_id'] . "'>" . $a['stroom_id'] . "</option>";
        }
    }
    return $return;
}

if($_POST['submitted'] == 'submitted'){
    unset($_POST['submitted']);
    $_POST['location_id'] = $_SESSION['loc'];
    $get_the_employ_id = mysql_fetch_array(mysql_query("SELECT id FROM employees WHERE location_id = (SELECT location_id FROM vendor_locations INNER JOIN vendors ON vendor_locations.vendor_id = vendors.id INNER JOIN employees_master emmaster ON emmaster.StorePoint_vendor_Id = vendors.id WHERE empmaster_id = '331' LIMIT 1) LIMIT 1"));


   	$employee_id = $_SESSION['employee_id'];
   	//print_r($employee_id);
   	//exit;
	$location_id = mysql_real_escape_string($_POST['location_id']);
    $date = date('Y-m-d');
    $time = date('H:i:s');
	$curr_stroom = mysql_real_escape_string($_POST['curr_stroom']);
	//print_r($_POST);
    for($i=0;$i<count($_POST['item']);$i++){
		//echo "<br/>".$i.'->'.$_POST['qty'][$i].'->'.$_POST['storeroom'][$i].'->'.$_POST['unit_type'][$i];
        if(!intval($_POST['qty'][$i]) > 0 || !intval($_POST['storeroom'][$i]) > 0 || !intval($_POST['unit_type'][$i]) > 0){
			
			
			
            //unset($_POST['qty'][$i]);
            //unset($_POST['storeroom'][$i]);
            //unset($_POST['item'][$i]);
            //unset($_POST['unit_type'][$i]);
			
        }else{
		
		
		if($_POST['qty'][$i] != '' && $_POST['storeroom'][$i] != ''){
            $qty = mysql_real_escape_string($_POST['qty'][$i]);
            $storeroom = mysql_real_escape_string($_POST['storeroom'][$i]);
            $item = mysql_real_escape_string($_POST['item'][$i]);
            $unit = mysql_real_escape_string($_POST['unit_type'][$i]);
			
            $neg_qty = $qty*(-1);

         	$query = "SELECT id
                      FROM location_inventory_storeroom_items
                      WHERE location_id=" . $location_id . " AND storeroom_id=$storeroom AND inv_item_id=$item";
            $result = mysql_query($query) or die($query);

            if(!mysql_num_rows($result) > 0){
               $query = "INSERT INTO location_inventory_storeroom_items SET
                                location_id=" . $location_id . ",
                                storeroom_id=$storeroom,
                                inv_item_id=$item";
                $result = mysql_query($query) or die($query);
            }

            $query = "INSERT INTO location_inventory_counts SET
                                location_id='" . $location_id . "',
                                storeroom_id='$storeroom',
                                inv_item_id='$item',
                                `Type`='Movement',
                                unit_type='$unit',
                                employee_id='" . $employee_id . "',
                                date_counted='$date',
                                time_counted='$time',
                                quantity='$qty',
								created_on='VendorPanel',
								created_datetime=NOW(),
								created_by='".$_SESSION['employee_id']."',
                                storeroom_id_origin='$curr_stroom'";
            $result = mysql_query($query) or die(" Query 1: ".$query ."<br><br>".mysql_error());

            $query = "INSERT INTO location_inventory_counts SET
                                location_id='" . $location_id . "',
                                storeroom_id='$curr_stroom',
                                inv_item_id='$item',
                                `Type`='Movement',
                                unit_type='$unit',
                                employee_id='" . $employee_id . "',
                                date_counted='$date',
                                time_counted='$time',
								created_on='VendorPanel',
								created_datetime=NOW(),
								created_by='".$_SESSION['employee_id']."',
                                quantity='$neg_qty'";
            $result = mysql_query($query) or die(" Query 2: ".$query);
        }
		
		}
    }
	
	header('Location: backoffice_movements.php?st='.$_POST['curr_stroom'].'&market='.$_POST['curr_market'].'&group_id='.$_POST['curr_group_id'].'&msg=ok');
	
    /*$query = http_build_query($_POST);
   $token1 = md5($query . 'backofficesecure12');
	
   $url = API.'api2/backoffice/insert-movement.php?token1='.$token1.'&token='.$_SESSION['tok'];

   $ch = curl_init( $url );
   curl_setopt( $ch, CURLOPT_POST, true);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
	echo $url.'&'.$query;
	
    $response = json_decode(curl_exec( $ch ),true);
	print_r($response);
	die();
    if($response['status'] == 'success'){
		
        header('Location: backoffice_movements.php?st='.$_POST['curr_stroom'].'&msg=ok');
    }else{
        header('Location: backoffice_movements.php?e');
    }*/
}


if (isset($_REQUEST['get_availability'])) {
	// get_availability
	$item_id = $_REQUEST['item_id'];
	$unit_type = $_REQUEST['unit_type'];
	$l_id = $_REQUEST['location_id'];
	$st_id = $_REQUEST['storeroom_id'];
	$search = array('Search Items','licence_table',5);
	$search1 = array('Search Items','licence_table1',5);//juni -> correct table? if correct :)
    $st = $_REQUEST['storeroom_id'];
	$keyword = $_GET['keyword'];
	$market = $_REQUEST['market'];
	$group_id = $_REQUEST['group_id'];
	$inv_item_id = $_REQUEST['inv_item_id'];
 	//	 $token1 = md5('location=' . $_SESSION['loc'] . 'backofficesecure12');
    $token1 = md5('location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website' . 'backofficesecure12');
	$token = base64_encode(strtotime('-7 hours')."+0");
	$marktmp = $market;
	$group_idtmp = $group_id;
	$keywwds = $keyword;
	$token1tmp = $token1;
	$tokentmp = $token;
	$sttemp = $st;
	$available = 0;
	$qry = mysql_query("SELECT SUM(lic.quantity) AS qty
	FROM location_inventory_counts lic 
	LEFT JOIN employees ON lic.employee_id=employees.id 
	LEFT JOIN inventory_item_unittype ON inventory_item_unittype.id=lic.unit_type 
	WHERE inv_item_id='".$inv_item_id."' AND lic.location_id='".$l_id."' AND lic.storeroom_id='".$st."' AND inventory_item_unittype.id = '".$unit_type."'");
	
	if(mysql_num_rows($qry) > 0){
		$fet_qty = mysql_fetch_array($qry);
		$available = $fet_qty['qty'];
		if($available == null){
			$available = 0;
		}
		echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available));
		exit;
	}
	
	echo json_encode(array('ResponseCode' => 1, 'ResponseMessage' => $available));
	exit;

}



if(!isset($_GET['st'])){
   $search = array('Search Storerooms','stroom_tbl',0);//array with values to append into js for searching
    $token1 = md5('location=' . $_SESSION['loc'] . 'backofficesecure12');
  //  $token = md5($query . 'backofficesecure12');
  	$market = $_REQUEST['market'];
	$group_id = $_REQUEST['group_id'];
	$token = base64_encode(strtotime('-7 hours')."+0");
	if($_REQUEST['debug']!=''){
		/*echo '<br>'.API.'api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&token1=' . $token1.'&token='.$token;*/

		echo 'api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&token1=' . $token1.'&token='.$token;
	}
	
    /*$storerooms = json_decode(file_get_contents(API.'api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&token1=' . $token1.'&token='.$token),true);*/
	
	$storerooms = @json_decode(file_get_contents(API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&token1=' . $token1.'&token='.$token),true);
		
    if($storerooms['status'] == 'success'){
        $storerooms = $storerooms['response']['storerooms'];
    }
   
}else if($_GET['st'] != ''){
   
    $search = array('Search Items','licence_table',5);
	$search1 = array('Search Items','licence_table1',5);//juni -> correct table? if correct :)
    $st = $_GET['st'];
	$keyword = $_GET['keyword'];
	$market = mysql_real_escape_string($_REQUEST['market']);
	$group_id = $_REQUEST['group_id'];
	
 //	 $token1 = md5('location=' . $_SESSION['loc'] . 'backofficesecure12');
   $token1 = md5('location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website' . 'backofficesecure12');
	$token = base64_encode(strtotime('-7 hours')."+0");

	$marktmp = $market;
	$group_idtmp = $group_id;
	$keywwds = $keyword;
	$token1tmp = $token1;
	$tokentmp = $token;
	$sttemp = $st;
	
	if($_REQUEST['debug']!=''){
		/*echo '<br>'.API.'api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token;*/

		echo API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token;
	}
	
	
	
    /*$movements = json_decode(file_get_contents(API.'api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token),true);*/
	//echo API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token;
	$movements = @json_decode(file_get_contents(API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token),true);
	
	    if($movements['status'] == 'success'){	
			$storerooms = $movements['response']['storerooms'];
			$unit_types = $movements['response']['unit_types'];
			$volume = unitDropdown($unit_types['volume']);
			$weight = unitDropdown($unit_types['weight']);
			$package = unitDropdown($unit_types['package']);
			$all= unitdrop($unit_types);
			$items = $movements['response']['items'];
  		  }	
  		  /*echo API.'panels/VendorPanel/api/backoffice/get-movements.php?market='.$market.'&group_id='.$group_id.'&location=' . $_SESSION['loc'] . '&storeroom=' . $st . '&website&keyword='.$keyword.'&token1=' . $token1 .'&token='.$token;
  		  echo "<br><br>";*/
  		  //echo getTotalCount(9848);
  		  //exit;
		  //echo "<pre>"; print_r($items); die;
}

//echo "<pre>"; print_r($items); die;
/* foreach($items as $item){
	foreach($item['types_9767']  as $int_type){
		//echo "<pre>"; print_r($int_type['id']); 
		echo "id : ".$int_type['id']." unittype: ".$int_type['unit_type'];
		echo "<br>";
	}
	//echo $item['types_of_unit'][0];
	//echo "<pre>"; print_r($item); 
}
die; */

//echo "<pre>"; print_r($movements); die;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />

<link rel="stylesheet" href="css/responsive-tables.css">

<!--<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>-->




<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/charCount.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>-->
<script type="text/javascript" src="js/jquery.dataTables_admin.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript">	
	/*//->juni [req REQ_018] - 2014-09-21 - need datatable */
   jQuery(document).ready(function(){
        // dynamic table
		/*if ( jQuery('#licence_table tr').length > 2 ) {
			jQuery('#licence_table').dataTable({
				"sPaginationType": "full_numbers",
				//"aaSortingFixed": [[0,'asc']],
				"bSort" : false,
				"sEmptyTable": "Loading data from server",
				"iDisplayLength" : 10000,//number of records in table
				"bInfo": false,//no footer 
				"bFilter": false,//no filter 
				
				"fnDrawCallback": function(oSettings) {
					// jQuery.uniform.update();
				}
			});
		}*/


		/*setTimeout(function() {
			jQuery('#licence_table').dataTable().fnDestroy();
			jQuery('#licence_table').dataTable({
					"sPaginationType": "full_numbers",
					"aaSorting": [[ 2, "asc" ]],
					"bJQuery": true,
					"fnDrawCallback": function(oSettings) {
						jQuery.uniform.update();
					}
			});
		},400);*/



   });     



/*//<-juni [req REQ_018] - 2014-09-21 -need datatable */
       /* jQuery('#dyntable2').dataTable( {
            "bScrollInfinite": true,
            "bScrollCollapse": true,
            "sScrollY": "300px"
        });
		*/
	/*	if ( jQuery('#licence_table1 tr').length == 0 )
		 {
			 alert();
		 jQuery('#licence_table1').dataTable({
            "sPaginationType": "full_numbers",
            "aaSortingFixed": [[0,'asc']],
			"sEmptyTable": "Loading data from server",
            "fnDrawCallback": function(oSettings) {
                //jQuery.uniform.update();
            }
        });
		 }
        
       /* jQuery('#dyntable2').dataTable( {
            "bScrollInfinite": true,
            "bScrollCollapse": true,
            "sScrollY": "300px"
        });
        
    });*/
	function submit_form(){
		var count = jQuery('#count').val();
		var msg = true; 
		for(i=0;i<=count;i++){
			if(jQuery('#text_'+i).val()=="" && jQuery('#select_'+i).val()!=""){
				jAlert('Please Insert Quantity!','Alert Dialog');
				
				return false;
				jQuery('#text_'+i).focus();
				
			}else if(jQuery('#text_'+i).val()!="" && jQuery('#select_'+i).val()==""){
				jAlert('Please Select Storeroom!','Alert Dialog');
				return false;
				jQuery('#select_'+i).focus();
				
			}
		}
		
	document.getElementById('movement_frm').submit();
	}
</script>
<!--<script type="text/javascript" src="js/forms.js"></script>-->
    
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<style type="text/css">
.maincontentinner  select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
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
input.text:focus, input.email:focus, input.password:focus, textarea.uniform:focus{border-color: rgba(82, 168, 236, 0.8) !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(82, 168, 236, 0.6) !important ;
    outline: 0 none;}
.maincontentinner table th {
font-size:11px;
}
.ui-datepicker-month{
	width:70px
}
.ui-datepicker-year{
		width:70px
}
#stroom_tbl tbody tr{ cursor:pointer; }
a.btn{color:#fff !important;}

.chzn-container-single .chzn-single {
  background-color: #ffffff;
  border: 1px solid #ccc;
  -moz-box-shadow: inset 0 1px 2px #ddd;
  -webkit-box-shadow: inset 0 1px 2px #ddd;
  box-shadow: inset 0 1px 2px #ddd;
  display: block;
  overflow: hidden;
  white-space: nowrap;
  position: relative;
  height: 25px;
  line-height: 24px;
  padding: 0 0 0 8px;
  color: #666;
  text-decoration: none;
}
.chzn-container-single .chzn-single span {
margin-top: 2px;
margin-right: 26px;
display: block;
overflow: hidden;
white-space: nowrap;
-o-text-overflow: ellipsis;
-ms-text-overflow: ellipsis;
text-overflow: ellipsis;
}
#uniform-popup_ok{
		background: none repeat scroll 0 0 #FFFFFF !important;
    	border: 2px solid #0866C6 !important;
    	color: #0866C6 !important;
    	width:30% !important;
}
div #uniform-popup_ok span {	
	background-image: url() !important;
}
/*//->juni [req REQ_018] - 2014-09-21 - do not show filter and paging*/
/*#licence_table_paginate {display:none;}*/
/* #licence_table_length label  {color:#EEEEEE;cursor:default !important;}  */
/*#licence_table_length {display:none;} */
/* #licence_table_length label {display:none;}  */
/*#licence_table_length label {margin-bottom:8px !important;} */ /**to have same spacing after removing select*/
/*//<-juni [req REQ_018] - 2014-09-21 - do not show filter and paging*/

.dataTables_filter input {
    width: 150px !important;
    margin: 0 0 0 10px;
    height: 26px;
}

select[name=licence_table_length] {
	height: 26px !important;
}
</style>
</head>

<body>

<div class="mainwrapper">
    
    <?php include_once 'require/top.php';?>
    
    <div class="leftpanel">
        
        <?php include_once 'require/left_nav.php';?>
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="dashboard.php"><i class="iconfa-home"></i> </a> <span
				class="separator"></span></li>
			<li>inventory</li>
			<li><span class="separator"></span></li>
			<li>Internal Inventory</li>
			<li><span class="separator"></span></li>
			<li>Movements</li>
            <li class="right">
                    <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
                    <ul class="dropdown-menu pull-right skin-color">
                        <li><a href="default">Default</a></li>
                        <li><a href="navyblue">Navy Blue</a></li>
                        <li><a href="palegreen">Pale Green</a></li>
                        <li><a href="red">Red</a></li>
                        <li><a href="green">Green</a></li>
                        <li><a href="brown">Brown</a></li>
                    </ul>
            </li>
			<?php require_once("lang_code.php");?>
        </ul>
        
        <div class="pageheader search_box">
			<div style="margin-top: 16px; float:right">
                <a href="#"><button class="btn btn-large" id="submit_data" type="button" disabled onClick="submit_form()">Submit</button></a>
            </div>
            <form action="" method="GET" class="searchbar" style="float:right; position: static; margin: 16px 5px 0 0;">
			
				<select id="status" name="st" style="width:150px;height:43px;padding: 8px 8px;margin:0px;">
					<option value="">Select Storeroom..</option>
                    
                    
                    <?php 	
					foreach($storerooms as $storeroom){
						$class='';
						if($storeroom['storeroom_id'] == $_GET['st']){$class="selected=selected";}?>
						<option value="<?=$storeroom['storeroom_id'];?>" <?php echo $class; ?>><?=$storeroom['stroom_id'];?></option>
					<?php } 
					
					$storerooms = storeroomDropdown($storerooms); ?>
                    
				</select>
                <?=jRender_inventory_market_combo1('dummy_market',$_SESSION['loc'],$group_id,'dummy-market','width:150px;height:43px;padding: 8px 8px;margin:0px;');?>
				
				<?=jRender_inventory_group_combo('group_id',$_SESSION['loc'],$_REQUEST['group_id'],'dummy','width:150px;height:43px;padding: 8px 8px;margin:0px;','yes');?>
						
				
                <input type="text" value="<?php echo isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '' ?>" name="keyword" placeholder="<?php echo ucfirst($_SESSION['search']);?>" style="font-size: 13px;line-height:30px;padding: 8px 10px;width:120px;height:25px;" />
                <?php if($_REQUEST['st']!=''){ ?>
					<a href="#"><button class="btn btn-large btn-primary" id="go_data" type="submit" >Go</button></a>	
				<?php }else{ ?>
					<a href="#"><button disabled="disabled" class="btn btn-large" id="go_data" type="submit" >Go</button></a>
				<?php } ?>
                
            
            </form>
            <div class="pageicon"><span class="iconfa-book"></span></div>
            <div class="pagetitle">
                <h5>Display All BackOffice Movements Information</h5>
                <h1>Movements</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid" id="movements">
                
                    <div class="span7" >
						<div class="clearfix">
						<h4 class="widgettitle">Inventory items</h4>			
						</div>
                        <form method="post" id="movement_frm" >
                        <input type="hidden" name="submitted" value="submitted"  />
                        <input type="hidden" name="curr_stroom" value="<?=$_GET['st']?>" />
                        <input type="hidden" name="curr_market" value="<?=$_GET['market']?>" />
                        <input type="hidden" name="curr_group_id" value="<?=$_GET['group_id']?>" />
						<table id="licence_table" class="table table-bordered responsive">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
										</colgroup>
										<thead>
											<tr>
												<th class="head1" style="width:10%;">Type</th>
												<th class="head0" style="width:10%;">Item</th>
												<th class="head1" style="width:25%;">Group</th>
												<th class="head0" style="width:25%;">Description</th>
												<th class="head1" style="width:10%;">Master unit of measure</th>
												<th class="head1" style="width:10%;">Available</th>
											</tr>
										</thead>
											<tbody>
                                         <?php if($_GET['st'] != ''){ ?>
										    		
                                            	<?php 
														if(!empty($items)){
														$i=0; foreach($items as $item){
														if($item['status'] == 'inactive' && $item['qty'] == 0){
														
														}else{
														$volume1 = unitDropdown1($unit_types['volume'],$item['unit_type']);
														$weight1 = unitDropdown1($unit_types['weight'],$item['unit_type']);
														$package1 = unitDropdown1($unit_types['package'],$item['unit_type']);
												 ?>
                                            	<tr style="height: 37px;">
												<td><?=ucfirst(substr($item['type'],0,1));?></td>
												<td><?=$item['item_id'];?></td>
                                                <td><?=$item['group_name'];?></td>
                                                <td><?=$item['item_name'];?></td>
                                                <td style="padding:4px !important">
												<?php //echo "<pre>"; print_r($item['types_'.$item['id']]); ?>
													<select style="height:28px !important;" class="unit_type" data-inv_item_id="<?=$item['id'];?>">
                                                       <!--<option value="">Select Unit Type</option>-->
													   	<?php
															foreach($item['types_'.$item['id']]  as $int_type){
																echo "<option value=".$int_type['id'].">".$int_type['unit_type']."</option>";
															}
													   	?>
                                                    </select>
												</td>
                                                <td id="val_<?php echo $i; ?>" data-count="<?=$item['qty']?>" class="<?php echo $i; ?>_item_left_ctrl ">
                                                	<?=''/*$item['qty']*/?>
                                                	<?=sprintf("%.2f",getTotalCount( $item['id']))?>
                                                </td>
											</tr>
												<?php } $i++;} }else{ ?>
												<tr>
                                                    <td colspan="6">No Results Found</td>
                                                </tr>
											<?php } }else{ ?>
                                            	<tr>
                                                    <td colspan="6">No Results Found</td>
                                                </tr>	
                                            <?php }  ?>
											<!--<tr>
												<td class="errorDisplayMessage" valign="top" colspan="5">There is no search data to display.</td>
											</tr>-->
									    </tbody>
										
										 
						</table>
                        
					</div> <!--end span4-->
                   
					<div class="span5" style="float:right;">
						<div class="clearfix">
						<h4 class="widgettitle">Movement</h4>			
						</div>
                        
						<table id="licence_table1" class="table table-bordered responsive" cellpadding="3px">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
										</colgroup>
										<thead>
											<tr>
												<th class="head1" style="width:20%;">Qty</th>
												<th class="head0" style="width:40%;">Master unit of measure</th>
												<th class="head1" style="width:40%;">Storeroom</th>
											</tr>
										</thead>
										<tbody>
                                        <!--<script>
										jQuery(document).ready(function(){
											jQuery(".chzn-select").chosen();
											});
										</script>-->  
                                        	<?php if($_GET['st'] != ''){ $count=0;?>
										    
                                            	<?php 
												if(!empty($items)){
												foreach($items as $item){
												if($item['status'] == 'inactive' && $item['qty'] == 0){
														
												}else{
												$volume1 = unitDropdown1($unit_types['volume'],$item['unit_type']);
        										$weight1 = unitDropdown1($unit_types['weight'],$item['unit_type']);
        										$package1 = unitDropdown1($unit_types['package'],$item['unit_type']);
												
												 ?>
                                              
										    <tr id="inv_movement">
												<td style="padding:4px !important">
													<input class="<?php echo $count; ?>_item_right_ctrl inv_movement_qty" type="text" id="text_<?php echo $count; ?>" rel="<?php echo $count; ?>"  name="qty[]" style="width: 99%;height: 26px;" placeholder=" <?php echo $_SESSION['Count'];?> " onkeyup="validateLeftSideAssignment(this, event)"/>
												</td>
												<td style="padding:4px !important">
													<?php /*echo $item['unit_group'];*/ ?>
													<select name="unit_type[]" style="height:28px !important;"  class="unit_type">
                                                       <option value="">Select Unit Type</option>
                                                      
                                                        <?php
														
	                                                      	switch($item['unit_group']){
	                                                            case 'package':
	                                                                echo $package1;
	                                                                break;
	                                                            case 'volume':
	                                                                echo $volume1;
	                                                                break;
	                                                            case 'weight':
	                                                                echo $weight1;
	                                                                break;
																default:
																	echo $all;
																	break;
	                                                        }
                                                        ?>
                                                    </select>
                                                    
												</td>
												<td style="padding:4px !important">
                                                    <select id="select_<?php echo $count; ?>" name="storeroom[]" style="height:28px !important;" class="storeroom">
                                                        <option value="">Select Storeroom</option>
                                                        <?=$storerooms?>
                                                    </select>												
                                               	</td>
                                                <input type="hidden" value="<?=$item['id'];?>" name="item[]" />
											</tr>
											<?php } $count++;} }else{?>
												<tr>
                                                    <td colspan="3">No Results Found</td>
                                                </tr>
                                            <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" >
                                            
                                            <?php } }else{ ?>
                                            	<tr>
                                                    <td colspan="3">No Results Found</td>
                                                </tr>	
                                            <?php } ?>
									    </tbody>
						</table>
                        </form>
					</div>
                    
                </div><!--row-fluid-->
                  <?php include_once 'require/footer.php';?>
                <!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<script>
jQuery(document).ready(function($){
		
		/*jQuery('#licence_table').DataTable({

		});*/

		$('#licence_table tr').each(function( index ) {
			
		  //$("select").not("#status").chosen();
		  //$("select").not("#status").css('height','50px');
		});
		
	 	$('.inv_movement_qty').on('change', function(){
			if ($(this).val()>0)
			 $('#submit_data').addClass('btn-primary');
			 $('#submit_data').attr('disabled',false);
			 
				
		}); 

		/*setTimeout(function() {
			jQuery('#licence_table').dataTable().fnDestroy();
			jQuery('#licence_table').dataTable({
					"sPaginationType": "full_numbers",
					"aaSorting": [[ 2, "asc" ]],
					"bJQuery": true,
					"aoColumnDefs": [
		      			{ "bSearchable": true, "aTargets": [ 1 ] }
		    		],
					"fnDrawCallback": function(oSettings) {
						jQuery.uniform.update();
					}
			});
		},500);*/
		
}); 
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        
		
		
		window.matchrowsize = function(){ //alert('called');
			$('#licence_table tr').each(function (index){
				var h = $(this).height(); //console.log(index+" : "+h);
				$('#licence_table1 tr').eq(index).css('height', h+'px');
				
			});
		}
		setTimeout(matchrowsize, 3000);
		
		 $(window).resize(matchrowsize);

		/*setTimeout(function() {
			jQuery('#licence_table').dataTable().fnDestroy();
			jQuery('#licence_table').dataTable({
					"sPaginationType": "full_numbers",
					"aaSorting": [[ 2, "asc" ]],
					"bJQuery": true,
					"aoColumnDefs": [
		      			{ "bSearchable": true, "aTargets": [ 1 ] }
		    		],
					"fnDrawCallback": function(oSettings) {
						jQuery.uniform.update();
					}
			});
		},500);*/
		
		
		
		
		
		
		
		
	$("select,input").not('#status, .unit_type, .storeroom,#dummy_market,#group_id').uniform();
		
		$('#status').change(function() { //alert('as');
			if($('#status').val()!=''){
				jQuery("#go_data").addClass('btn-primary').attr('disabled',false);
			}else{
				jQuery("#go_data").removeClass('btn-primary').attr('disabled',true);
			}
		});
		$('#go_data').click(function(){
			if($('#status').val()==""){
				jAlert('Please Select storeroom!','Alert Dialog');
				return false;
			}
		});
		$("#dummy_market").live('change',function(){
			var market = jQuery(this).val();
			jQuery.ajax({
				type: "POST",
				url: "chkmarketgroup.php",
				data: { market: market}
			})
			.done(function(msg) {			
			  jQuery("#group_id").html(msg);
			});
		});
		$('.search_box').on('paste keyup','input',function(){
            if (!this.value) {
                $('#search_x').fadeOut(300);
                //filter('','<?=$search[1]?>','<?=$search[2]?>','clear');
				//filter('','<?=$search1[1]?>','<?=$search1[2]?>','clear');
            }else{
                $('#search_x').delay().fadeIn(300);
                //filter(this.value,'<?=$search[1]?>','<?=$search[2]?>','search');
				//filter(this.value,'<?=$search1[1]?>','<?=$search1[2]?>','search');
            }
        });
        $('#search_x').on('click',function(){
            $('.search_box').find('input').val('');
            filter('','<?=$search[1]?>','<?=$search[2]?>','clear');
			filter('','<?=$search1[1]?>','<?=$search1[2]?>','clear');
            $(this).fadeOut(300);
        });
        if($('.search_box').find('input').val() != ''){
            $('#search_x').show();
        }
        $('input.update').keyup(function(){
            var val = $(this).val();
			var id = $(this).attr('rel');
			var total_avi = $('#val_'+id).attr('data-count');
			if(parseFloat(val)>parseFloat(total_avi)){
				jAlert('Amount Entered Is Greater than the Amount Available!','Alert Dialog');
				$(this).val('');
				return false;
				
			}
			
			
            var prev = $(this).closest('td').prev('td').prev('td');
            if(val != ''){
                var amt = (parseFloat(prev.data('count'))-parseFloat(val)).toFixed(2);
                if(!(amt >= 0)){
                    prev.text(amt).css('background-color','red');
                }else{
                    prev.text(amt).css('background-color','transparent');
                }
            }else{
                prev.text((prev.data('count')).toFixed(2)).css('background-color','transparent');
            }
        });
    });	
	function filter (term, _id,cols,type){
		//console.log(term,_id,cols,type);
        var suche = term.toLowerCase();
        var table = document.getElementById(_id);
        var ele;
        if(type == 'search'){
            for (var r = 1; r < table.rows.length; r++){
                for(var i=0;i<=cols;i++){
                    ele = table.rows[r].cells[i].innerHTML.replace(/<[^>]+>/g,"");
                    if (ele.toLowerCase().indexOf(suche)>=0 ){
                        table.rows[r].style.display = '';
                        break;
                    }else{
                        table.rows[r].style.display = 'none';
                    }
                }
            }
        }else{
            for (r = 1; r < table.rows.length; r++){
                table.rows[r].style.display = '';
            }
        }
    }

jQuery(document).ready(function(){
	<?php if($_REQUEST['msg']=='ok'){ ?>
		jAlert('Movement added successfully!','Alert Dialog')
	<?php } ?>


	//alert("Done loading!");
	/* jQuery('#licence_table').dataTable({
			"sPaginationType": "full_numbers",
			"aaSortingFixed": [[0,'asc']],
			"fnDrawCallback": function(oSettings) {
					jQuery.uniform.update();
			}
	}); */

	/*jQuery('#licence_table').dataTable().fnDestroy();
	jQuery('#licence_table').dataTable({
			"sPaginationType": "full_numbers",
			"aaSorting": [[ 2, "asc" ]],
			"bJQuery": true,
			"aoColumnDefs": [
      			{ "bSearchable": true, "aTargets": [ 1 ] }
    		],
			"fnDrawCallback": function(oSettings) {
				jQuery.uniform.update();
			}
	});*/



	});	
</script>
<script type="text/javascript">
	jQuery('.unit_type').change(function() {
		//alert('Change: '+jQuery(this).val());
		//console.log(jQuery(this).parent().parent());
		//alert(jQuery(this).parent().parent().children().eq(1).text());
		var unit_type_selected = jQuery(this).val();
		var item_id_selected = jQuery(this).parent().parent().children().eq(1).text();
		var elem =jQuery(this).parent().parent().children().eq(1);
		var inv_item_id = jQuery(this).data('inv_item_id');
		/*alert('a: '+unit_type_selected+' b: '+item_id_selected);*/
		/*
		$keyword = $_GET['keyword'];
		$market = $_REQUEST['market'];
		$group_id = $_REQUEST['group_id'];
		*/
		console.log('inv_item_id : '+inv_item_id);
		jQuery.ajax({
			  method: "POST",
			  url: "backoffice_movements.php",
			  data: { get_availability:1,inv_item_id: inv_item_id, unit_type: unit_type_selected, item_id: item_id_selected, location_id: "<?php echo $_SESSION['loc']; ?>", storeroom_id: "<?php echo $_GET['st']; ?>", keyword: "<?php echo $_GET['keyword']; ?>", market: "<?php echo $_REQUEST['market']; ?>", group_id: "<?php echo $_REQUEST['group_id']; ?>" }
			})
			  .done(function( available ) {
			    	//alert( "Data Saved: " + msg );
			    	console.log(available);
			    	var sd = JSON.parse(available);
			    	//alert(sd.ResponseMessage);
			    	//console.log(jQuery(this).parent().parent().children().eq(5));
			    	jQuery(elem).parent().children().eq(5).text(sd.ResponseMessage);
			  });

	});
</script>
<script type="text/javascript">

	function quitLastChar(text) {
		text = text.substring(0, text.length - 1);
		return text;
	}
	function validateLeftSideAssignment(element, evt) {
		//console.log(element);
		if (element && element!=undefined && element != "" && element!=null) {
			// Is element a number else return false
			var value = jQuery(element).val();
			var l_id = jQuery(element).attr('id');
			var r_id = l_id.replace("text", "val");
			if (!isNaN(value)) {
				if (!isNaN(jQuery('#'+r_id).text()) && !isNaN(jQuery('#'+l_id).val())) {
					console.log();
					var nmbr1 = parseFloat(jQuery('#'+r_id).text());
					var nmbr2 = parseFloat(jQuery('#'+l_id).val());
					if (nmbr2>nmbr1) {
						jQuery('#'+l_id).val(quitLastChar(jQuery('#'+l_id).val()));
					}
				}else {
					jQuery('#'+l_id).val(quitLastChar(jQuery('#'+l_id).val()));
				}
			}else {
				jQuery('#'+l_id).val(quitLastChar(jQuery('#'+l_id).val()));
			}
		}else {
			jQuery('#'+l_id).val(quitLastChar(jQuery('#'+l_id).val()));
		}
	}
</script>
</body>
</html>
