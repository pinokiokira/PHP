<?php 
include_once 'includes/session.php';
include_once 'config/accessConfig.php';

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

$pathMain = $_SERVER['DOCUMENT_ROOT'];

$supportDropDown = "display: block;";
$supportHead = "active";
$supportSchedule = "active";

function getCreatedBy($by,$on){
	$name ='';
	if($by>0){
		if(strtolower($on)=='adminpanel' || strtolower($on)=='admin panel'){
			$get = "SELECT name from users where id = '".$by."'";	
		}else if(strtolower($on)=='business panel'){
			$get = "SELECT CONCAT(first_name,' ',last_name) as name from employees where id = '".$by."'";	
		}
		$res = mysql_query($get);
		if($res && mysql_num_rows($res)>0){
			$row = mysql_fetch_assoc($res);
			$name = $row['name'].' (ID:'.$by.')'; 	
		}	
	}
	return $name;	
}

if(isset($_POST) && $_POST['action_type']=='getlocCheckDetails'){
	$locbID = $_REQUEST['locbID'];
	$location_id = $_REQUEST['location_id'];
	$returnArray = array();
		
	$get_detaisl = "SELECT gbc.description,COALESCE(lbc.details,'') as details ,lbc.created_on,lbc.created_by,lbc.created_datetime,COALESCE(lbc.last_on,'') as last_on,COALESCE(lbc.last_by,'') as last_by,COALESCE(lbc.last_datetime,'') as last_datetime,lbc.location_id,lbc.perform_status,lbc.ov_acct_email,cd.3rd_party as thirdParty,now() as serverdatetime,COALESCE(lbc.document,'') as document,gbc.details_required,lbc.conference_number,lbc.conference_url,COALESCE(lbc.technician,'') as technician ,COALESCE(lbc.technician_3rd_party,'') as technician_3rd_party,lbc.ov_line,lbc.merchant_line,lbc.windows_user,lbc.windows_password,lbc.pos_manager_user,lbc.pos_manager_password,lbc.cmc_account,cd.manufacturer,gbc.global_boarding_checklist_id,
	SUBSTRING_INDEX(GROUP_CONCAT(CONCAT(le.password,'|',le.emp_id) ORDER BY le.emp_id=001 DESC),',', 1) AS emp_password,lbc.traking_number,lbc.hardware_type,lbc.hardware_req,COALESCE(lbc.hardware_purchaser,'') as hardware_purchaser,lbc.hardware_transaction_no,lbc.installation_type
	FROM location_boarding_checklist as lbc	
	JOIN global_boarding_checklist as gbc ON gbc.global_boarding_checklist_id = lbc.global_checklist_id
	LEFT JOIN clover_devices as cd ON cd.location_id = lbc.location_id
	LEFT JOIN employees AS le on le.location_id=lbc.location_id 
	WHERE lbc.location_boarding_checklist_id = '".$locbID."'";
	if($locbID=="" && $location_id>0){
		$get_detaisl = "SELECT cd.3rd_party as thirdParty,now() as serverdatetime,cd.manufacturer,
		SUBSTRING_INDEX(GROUP_CONCAT(CONCAT(le.password,'|',le.emp_id) ORDER BY le.emp_id=001 DESC),',', 1) AS emp_password
		FROM locations as l
		LEFT JOIN clover_devices as cd ON cd.location_id = l.id
		LEFT JOIN employees AS le on le.location_id=l.id 
		WHERE l.id = '".$location_id."'";
	}
	$res = mysql_query($get_detaisl) or die(mysql_error());
	if($res && mysql_num_rows($res)>0){
		$row = mysql_fetch_assoc($res);	
		$empp = explode('|',$row['emp_password']);		
		$row['emp_id'] = $empp[1];
		$row['emp_password'] = $empp[0];
		if(is_numeric($row['created_by'])){
			$row['created_by'] = getCreatedBy($row['created_by'],$row['created_on']);
		}
		if(is_numeric($row['last_by'])){
			$row['last_by'] = getCreatedBy($row['last_by'],$row['last_on']);
		}
		if($row['description']=='Receive Signed EULA'){
			$getEulaid= "SELECT location_internal_eula_id FROM location_internal_eula WHERE location_id = '".$row['location_id']."' AND eula_order_step='5'  ORDER BY location_internal_eula_id DESC LIMIT 1";
			$resEulaid = mysql_query($getEulaid);
			if($resEulaid && mysql_num_rows($resEulaid)>0){
				$rowEulaid = mysql_fetch_assoc($resEulaid); 
				$row['eula_id'] =$rowEulaid['location_internal_eula_id'];
			}
		}
		if($row['description']=='Perform Integration' || $row['description']=='Perform Installation & Training'){
			//$row['technician'] = getCreatedBy($row['technician'],'AdminPanel');
			$row['technicianName'] = getCreatedBy($row['technician'],'AdminPanel');
			$row['technician_3rd_partyID'] = $row['technician_3rd_party'];
			$row['technician_3rd_party'] = $row['technician_3rd_party'];;// getCreatedBy($row['technician_3rd_party'],'AdminPanel');
			$getHistory= "SELECT *,COALESCE(last_on,'') as last_on,COALESCE(technician_3rd_party,'') as technician_3rd_party,COALESCE(last_by,'') as last_by,COALESCE(last_datetime,'') as last_datetime FROM location_boarding_schedule WHERE location_id = '".$row['location_id']."' AND global_checklist_id = '".$row['global_boarding_checklist_id']."'";
			$res_his = mysql_query($getHistory);
			$historyArray = array();
			while($row_his = mysql_fetch_assoc($res_his)){
				$row_his['created_by'] = getCreatedBy($row_his['created_by'],$row_his['created_on']);
				if($row_his['last_by']>0){
					$row_his['last_by'] = getCreatedBy($row_his['last_by'],$row_his['last_on']);
				}
				$row_his['technicianName'] = getCreatedBy($row_his['technician'],'AdminPanel');
				$row_his['technician_3rd_partyID'] = $row_his['technician_3rd_party'];
				$row_his['technician_3rd_party'] = $row_his['technician_3rd_party'];//getCreatedBy($row_his['technician_3rd_party'],'AdminPanel');
				$historyArray[] = $row_his;				
			}
			$row['historyarray'] = $historyArray;

		}else if($row['description']=='Verify Compatibility'){
			 $get_loc = "SELECT COALESCE(current_cc_processer,'') as current_cc_processer,
							   COALESCE(integrated_cc_processing,'') as integrated_cc_processing, 
							   COALESCE(pos_pms_system,'') as pos_pms_system,
							   COALESCE(current_pos_pms_system,'') as current_pos_pms_system,
							   COALESCE(pos_version_number,'') as pos_version_number,
							   COALESCE(operating_system,'') as operating_system,
							   COALESCE(integration_license,'') as integration_license
						FROM locations where id = '".$row['location_id']."'";
			$res_loc = mysql_query($get_loc);
			$row_loc = mysql_fetch_assoc($res_loc);
			$row['current_cc_processer'] = $row_loc['current_cc_processer'];
			$row['integrated_cc_processing'] = $row_loc['integrated_cc_processing'];
			$row['pos_pms_system'] = $row_loc['pos_pms_system'];
			$row['current_pos_pms_system'] = $row_loc['current_pos_pms_system'];
			$row['pos_version_number'] = $row_loc['pos_version_number'];
			$row['operating_system'] = $row_loc['operating_system'];
			$row['integration_license'] = $row_loc['integration_license'];
		}
		if($row['description']=='Perform Precheck'){
			$get_loc = "SELECT COALESCE(remote_access_type,'') as remote_access_type,
							   COALESCE(remote_access_id,'') as remote_access_id, 
							   COALESCE(remote_access_pin,'') as remote_access_pin
						FROM locations where id = '".$row['location_id']."'";
			$res_loc = mysql_query($get_loc);
			$row_loc = mysql_fetch_assoc($res_loc);	
			$row['remote_access_type'] = $row_loc['remote_access_type'];		
			$row['remote_access_id'] = $row_loc['remote_access_id'];		
			$row['remote_access_pin'] = $row_loc['remote_access_pin'];		
		}
		if($row['description']=='Update Products Billing Cycle'){
			$getLocProduct = "SELECT status,start_date,end_date,COALESCE(last_on,'') as last_on,COALESCE(last_by,'') as last_by,COALESCE(last_datetime,'') as last_datetime from location_internal_products WHERE location_id = '".$row['location_id']."'";
			$res_loc = mysql_query($getLocProduct);
			$row_loc = mysql_fetch_assoc($res_loc);	
			$row['status'] = $row_loc['status'];		
			$row['start_date'] = $row_loc['start_date'];		
			$row['end_date'] = $row_loc['end_date'];
			//$row['last_on'] = $row_loc['last_on'];
			if($row_loc['last_by']!=''){
				//$row['last_by'] = getCreatedBy($row_loc['last_by'],$row_loc['last_on']);
			}
			//$row['last_datetime'] = $row_loc['last_datetime'];		
		}
		
		$returnArray = $row;
	}
	echo json_encode($returnArray);
	exit();
}
if(isset($_POST) && $_POST['request_type']=='add_new_event'){

	$location_id = mysql_real_escape_string($_REQUEST['location_id']);	
	$date = mysql_real_escape_string($_REQUEST['date']);
	$stime = mysql_real_escape_string($_REQUEST['stime']);
	$etime = mysql_real_escape_string($_REQUEST['etime']);
	$hiddenGid = mysql_real_escape_string($_REQUEST['hiddenGid']);
	$hidEventID = mysql_real_escape_string($_REQUEST['hidEventID']);
	$evnt_conference_number = mysql_real_escape_string($_REQUEST['evnt_conference_number']);
	$evnt_conference_url = mysql_real_escape_string($_REQUEST['evnt_conference_url']);
	$evnt_add_installation_record = mysql_real_escape_string($_REQUEST['evnt_add_installation_record']);
	$evnt_locationChecklistDetails = mysql_real_escape_string($_REQUEST['evnt_locationChecklistDetails']);
	$evnt_ov_line = mysql_real_escape_string($_REQUEST['evnt_ov_line']);
	$evnt_merchant_line = mysql_real_escape_string($_REQUEST['evnt_merchant_line']);	
	$GloablBordingType = mysql_real_escape_string($_REQUEST['GloablBordingType']);
	$type = mysql_real_escape_string($_REQUEST['type']);
	$details = $date.' '.$stime.' - '.$etime;
	if($location_id>0){
		echo $hidEventID;
		if($hidEventID>0){
			$insert = "UPDATE location_boarding_checklist SET 
                        event_type = '".$GloablBordingType."',
						details = '".$details."',
						conference_number = '".$evnt_conference_number."',
						conference_url = '".$evnt_conference_url."',
						ov_line = '".$evnt_ov_line."',
						merchant_line = '".$evnt_merchant_line."',
						type = '".$type."',			
						last_by 		= '".$_SESSION['userid']."',
						last_on 		= 'AdminPanel',
						last_datetime= NOW() 
						WHERE location_boarding_checklist_id='".$hidEventID."'";
						$ins = mysql_query($insert) or die(mysql_error()); 
		}else{
			$insert = "INSERT INTO  location_boarding_checklist SET			
			location_id 	= '".$location_id."',
			description 	= 'Event',
			event_type = '".$GloablBordingType."',
			details = '".$details."',
			conference_number = '".$evnt_conference_number."',
			conference_url = '".$evnt_conference_url."',
			ov_line = '".$evnt_ov_line."',
			merchant_line = '".$evnt_merchant_line."',
			type = '".$type."',			
			created_by 		= '".$_SESSION['userid']."',
			created_on 		= 'AdminPanel',
			created_datetime= NOW()";
			$ins = mysql_query($insert) or die(mysql_error());
		}

        $checkInst = mysql_query("SELECT id from location_installation WHERE location_id = '".$location_id."'");
        $getLoc = mysql_query("SELECT l.id,if(cd.3rd_party='Aireus',cd.aireus_storeid,cd.omnivore_location_id) as omnivore_location_id from locations as l LEFT JOIN clover_devices as cd on cd.location_id = l.id where l.id = '".$location_id."'");
        $rowloc = mysql_fetch_assoc($getLoc);
        $omnivore_location_id = $rowloc['omnivore_location_id'];
        if(mysql_num_rows($checkInst)>0){
            $insert = "UPDATE location_installation SET 					
					status = 'Installed',
					temporary_password = '',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW() WHERE location_id = '".$location_id."'";
            echo $insert;
            $rs = mysql_query($insert);
        }else{

            $insert = "INSERT INTO location_installation SET 
					location_id = '".$location_id."',				
					status = 'Installed',
					temporary_password = '',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW()";
            echo $insert;
            $rs = mysql_query($insert);

            $sql = mysql_query("UPDATE locations SET sales_status='Installed',password='', omnivore_status='Active', omnivore_location_id='". mysql_escape_string($omnivore_location_id) ."', access_datapoint='Yes' where id='".$location_id."'");
        }

	}

	exit();
}
if(isset($_POST) && $_POST['req_type']=='getEventDetails'){
	$checklistId = $_POST['checklistId'];
	$return_data = array();
	if($checklistId>0){		
        $check = mysql_query("SELECT details,location_boarding_checklist_id as id,COALESCE(global_checklist_id,'') as global_checklist_id,conference_number,conference_url,ov_line,merchant_line,created_by,created_on,type,created_datetime,last_by,last_on,last_datetime,location_id,COALESCE(event_type,'') as event_type FROM location_boarding_checklist WHERE location_boarding_checklist_id='".$checklistId."'");        
		if(mysql_num_rows($check)>0){
			$row_event = mysql_fetch_assoc($check);			
			$ddate = $row_event['details'];
			$ddate1 = explode(' - ',$ddate);
			$ddate2 = explode(' ',$ddate1[0]);								
			$return_data['date'] = $ddate2[0];
			$return_data['sTime'] = $ddate2[1].' '.$ddate2[2];
			$ddate3 = explode(' ',$ddate1[1]);								
			$Etime = $ddate3[0].' '.$ddate3[1];			
			$return_data['eTime'] = date('h:i A',strtotime($Etime));
			$return_data['id'] = $row_event['id'];
			$return_data['details'] = $row_event['details'];
			$return_data['global_checklist_id'] = $row_event['global_checklist_id'];
			$return_data['event_type'] = $row_event['event_type'];
			$return_data['conference_number'] = $row_event['conference_number'];
			$return_data['conference_url'] = $row_event['conference_url'];
			$return_data['ov_line'] = $row_event['ov_line'];
			$return_data['merchant_line'] = $row_event['merchant_line'];
			$return_data['created_by'] = getCreatedBy($row_event['created_by'],$row_event['created_on']);
			$return_data['created_on'] = $row_event['created_on'];
			$return_data['created_datetime'] = $row_event['created_datetime'];
			$return_data['last_by'] = getCreatedBy($row_event['last_by'],$row_event['last_on']);
			$return_data['last_on'] = $row_event['last_on'];
			$return_data['last_datetime'] = $row_event['last_datetime'];
			$return_data['location_id'] = $row_event['location_id'];
			$return_data['type'] = $row_event['type'];
		}
	}
	echo json_encode($return_data);
	exit();
}

if(isset($_POST) && $_POST['action_type']=='SavelocCheckDetailsSI'){
    saveInstallationRecord();
	echo $res;
	exit();
}

function saveInstallationRecord() {
    $locbID = $_REQUEST['locbID'];
    $location_id = $_REQUEST['location_id'];
    $details = mysql_real_escape_string($_REQUEST['details']);
    $conference_number = mysql_real_escape_string($_REQUEST['SIconference_number']);
    $conference_url = mysql_real_escape_string($_REQUEST['SIconference_url']);
    $add_installation_record = mysql_real_escape_string($_REQUEST['SIadd_installation_record']);
    $technician_3rd_party = mysql_real_escape_string($_REQUEST['SItechnician_softpoint']);
    $installation_type = mysql_real_escape_string($_REQUEST['SIpinstallationType']);
    $common_first_time = $_REQUEST['common_first_time'];
    $txtPassword = $_REQUEST['txtPassword'];
    $extField = "";
    $res = 0;
    if($locbID>0){
        $lastFiels = ",last_on='AdminPanel',last_by = '".$_SESSION['userid']."',
		last_datetime = NOW()";
        if(strtolower($common_first_time)=="yes"){
            $lastFiels = "";
        }
        $update = "UPDATE location_boarding_checklist SET conference_number = '".$conference_number."',conference_url = '".$conference_url."',details = '".$details."',technician_3rd_party='".$technician_3rd_party."',installation_type='".$installation_type."' $lastFiels  WHERE location_boarding_checklist_id = '".$locbID."'";
        $res = mysql_query($update);

        if($add_installation_record=="Yes"){
            $checkInst = mysql_query("SELECT id from location_installation WHERE location_id = '".$location_id."'");
            $getLoc = mysql_query("SELECT l.id,if(cd.3rd_party='Aireus',cd.aireus_storeid,cd.omnivore_location_id) as omnivore_location_id from locations as l LEFT JOIN clover_devices as cd on cd.location_id = l.id where l.id = '".$location_id."'");
            $rowloc = mysql_fetch_assoc($getLoc);
            $omnivore_location_id = $rowloc['omnivore_location_id'];
            if(mysql_num_rows($checkInst)>0){
                $insert = "UPDATE location_installation SET 					
					status = 'Installed',
					temporary_password = '".$txtPassword."',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW() WHERE location_id = '".$location_id."'";
                $rs = mysql_query($insert);
            }else{

                $insert = "INSERT INTO location_installation SET 
					location_id = '".$location_id."',				
					status = 'Installed',
					temporary_password = '".$txtPassword."',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW()";
                $rs = mysql_query($insert);

                $sql = mysql_query("UPDATE locations SET sales_status='Installed',password='".$txtPassword."', omnivore_status='Active', omnivore_location_id='". mysql_escape_string($omnivore_location_id) ."', access_datapoint='Yes' where id='".$location_id."'");
            }
        }
    }
}
if(isset($_POST) && $_POST['action_type']=='SavelocCheckDetails'){
	$locbID = $_REQUEST['locbID'];
	$location_id = $_REQUEST['location_id'];
	$details = mysql_real_escape_string($_REQUEST['details']);
	$perform_status = mysql_real_escape_string($_REQUEST['perform_status']);
	
	$conference_number = mysql_real_escape_string($_REQUEST['conference_number']);
	$conference_url = mysql_real_escape_string($_REQUEST['conference_url']);

	$ov_line = mysql_real_escape_string($_REQUEST['ov_line']);
	$merchant_line = mysql_real_escape_string($_REQUEST['merchant_line']);
	$traking_number = mysql_real_escape_string($_REQUEST['traking_number']);
	$hardware_type = mysql_real_escape_string($_REQUEST['hardware_type']);
	$hardware_req = mysql_real_escape_string($_REQUEST['hardware_req']);
	$hardware_purchaser = mysql_real_escape_string($_REQUEST['hardware_purchaser']);
	if($hardware_purchaser=="Add New"){
		$hardware_purchaser = mysql_real_escape_string($_REQUEST['new_hardware_purchaser']);
	}
	$hardware_transaction_no = mysql_real_escape_string($_REQUEST['hardware_transaction_no']);
	$add_installation_record = mysql_real_escape_string($_REQUEST['add_installation_record']);
	$txtPassword = mysql_real_escape_string($_REQUEST['txtPassword']);
	$ov_acct_email = mysql_real_escape_string($_REQUEST['ov_acct_email']);
	$p_technician = mysql_real_escape_string($_REQUEST['p_technician']);	
	
	$digital_image_name = $_REQUEST['digital_image_name'];
	$digital_image_delete = $_REQUEST['digital_image_delete'];
	$db_first_time = $_REQUEST['db_first_time'];
	$checklsit = $_REQUEST['checklsit'];	
	$common_first_time = $_REQUEST['common_first_time'];	
	$extField = "";
	if($digital_image_delete=='Y'){
		$extField = " document = ''";
	}else if($digital_image_name!=''){
		$ftphost = FTPDOMAIN;
		$ftpusr = FTPUSER;
		$ftppwd = FTPPASSWORD;

		
		$local_file = $target_path;

		$target_path = "temp_img/";
		$file_with_path = $digital_image_name;
		$target_path = $target_path . $file_with_path;

		$target_ftp_path = "location_ads/";
		$ftp_path = $target_ftp_path . $file_with_path;

		$conn_id = ftp_connect($ftphost,FTPPORT) or die("Couldn't connect to $ftphost");
		$login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
		ftp_pasv ($conn_id, FTPPASIVE);
		$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);
		ftp_close($conn_id);
		$image = mysql_real_escape_string($ftp_path);
		$extField = ", document = '".$image."'";		
	}
	
	$res = 0;
	if($locbID>0){
		$lastFiels = ",last_on='AdminPanel',last_by = '".$_SESSION['userid']."',
		last_datetime = NOW()";
		if($checklsit=="dropbox" && $db_first_time=='yes'){
			$lastFiels = "";
		}else if(strtolower($common_first_time)=="yes"){
			$lastFiels = "";
		}
		$update = "UPDATE location_boarding_checklist SET conference_number = '".$conference_number."',conference_url = '".$conference_url."',details = '".$details."',perform_status='".$perform_status."',ov_acct_email='".$ov_acct_email."',technician='".$p_technician."',ov_line='".$ov_line."',merchant_line='".$merchant_line."',traking_number='".$traking_number."',hardware_req='".$hardware_req."',hardware_type='".$hardware_type."',hardware_purchaser='".$hardware_purchaser."',hardware_transaction_no='".$hardware_transaction_no."' $extField $lastFiels  WHERE location_boarding_checklist_id = '".$locbID."'";
		$res = mysql_query($update);	

		if($add_installation_record=="Yes"){
			$checkInst = mysql_query("SELECT id from location_installation WHERE location_id = '".$location_id."'");
			$getLoc = mysql_query("SELECT l.id,if(cd.3rd_party='Aireus',cd.aireus_storeid,cd.omnivore_location_id) as omnivore_location_id from locations as l LEFT JOIN clover_devices as cd on cd.location_id = l.id where l.id = '".$location_id."'");
			$rowloc = mysql_fetch_assoc($getLoc);
			$omnivore_location_id = $rowloc['omnivore_location_id'];
			if(mysql_num_rows($checkInst)>0){
				$insert = "UPDATE location_installation SET 					
					status = 'Installed',
					temporary_password = '".$txtPassword."',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW() WHERE location_id = '".$location_id."'";
				$rs = mysql_query($insert);
			}else{
				
				$insert = "INSERT INTO location_installation SET 
					location_id = '".$location_id."',				
					status = 'Installed',
					temporary_password = '".$txtPassword."',				
					Restaurant = 'No',
					Retail = 'No',
					Hotel = 'No',
					Other = 'No',
					send_registration_email = 'No',
					step_profile = NOW(),
					step_employee =NOW(),
					step_operations =NOW(),
					step_fiannce = NOW(),
					created_on='AdminPanel',
					created_by='".$_SESSION['userid']."',
					created_datetime=NOW()";
				$rs = mysql_query($insert);

				$sql = mysql_query("UPDATE locations SET sales_status='Installed',password='".$txtPassword."', omnivore_status='Active', omnivore_location_id='". mysql_escape_string($omnivore_location_id) ."', access_datapoint='Yes' where id='".$location_id."'");
			}
		}
		
	}
	echo $res;
	exit();
}
if(isset($_REQUEST) && $_REQUEST['action_type']=='get_calendar'){ ?>
    	<script>
    var doubleClick = '';
    var clickTimer = '';
    jQuery(document).ready(function(){
		jQuery("#locaiton_event_calender").fullCalendar({
			slotEventOverlap : false, 
			contentHeight: jQuery(window).height() + 60,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText: {
				prev: '&laquo;',
				next: '&raquo;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'today',
				week: 'W',
				month: 'M',
				day: 'D'
			},
			defaultView: 'agendaWeek',
			dayClick:function( date, allDay, jsEvent, view ) {
				var singleClick = date.toUTCString();

				if(doubleClick==singleClick){
					AddnewEventCal(date, allDay);
					doubleClick = null;
				}else{
					doubleClick=date.toUTCString();
					clearInterval(clickTimer);
					clickTimer = setInterval(function(){
						doubleClick = null;
						clearInterval(clickTimer);
					}, 500);
				}
			},
			eventClick: function(calEvent, jsEvent, view) {	
				var date = new Date();
				singleClick = date.toUTCString();
				if (doubleClick == singleClick){			
					jQuery("#checkListEventsPopup").modal('hide');
					if(calEvent.Etype=="Event"){
						let location_name = calEvent.title.substring(10,calEvent.title.length-1);
						UpdateEventPopup(calEvent.id,calEvent.location_id,location_name);
					}else{
						UpdateCheckListDetails(calEvent.id,calEvent.location_id,'checkList_'+calEvent.checklist_id);
					}
				}else{
					doubleClick = date.toUTCString();
					clearInterval(clickTimer);
						clickTimer = setInterval(function(){
						doubleClick = null;
						clearInterval(clickTimer);
					}, 500);
				}	
			},														
			//start: '10:00',
			firstHour: 7,
			timeFormat: 'hh:mm TT',
			events: [
				<?php 
					$location_id = $_REQUEST['location_id'];
					$get_details = "SELECT DATE(lbc.details) AS ddate,DATE_FORMAT(TIME(lbc.details),'%H:%i:%s') AS dtime,lbc.location_id,l.name  as location_name,lbc.description,lbc.details as actdetails,location_boarding_checklist_id as id,global_checklist_id as checklist_id,lbc.event_type
									FROM location_boarding_checklist lbc 
									LEFT JOIN locations l ON l.id = lbc.location_id
									where lbc.description IN('Setup Installation & Training','Schedule Integration','Event')";
									//YEAR(lbc.details)>'2017' AND
					$res = mysql_query($get_details);
					if($res && mysql_num_rows($res)>0){
						while($row_cal = mysql_fetch_assoc($res)){
							//$row_cal['description'] = $row_cal['description']=="Event"?$row_cal['description']="":$row_cal['description'];
							
							$color = "#FF0000";

							if($row_cal['checklist_id']=="42"){
								$color = "#228b22";
							}else if($row_cal['checklist_id']=="38"){
								$color = "#FFA500";
							}else if($row_cal['event_type']=="1"){
								$color = "#FFFF00";
							}else if($row_cal['event_type']=="4"){
								$color = "#FF0000";
							}
                            if($row_cal['event_type']=="42") {
                                $color = "#228b22";
                            }
							$SdateTime = date("D M d Y", strtotime($row_cal['ddate']))." ".date("H:i:s",strtotime($row_cal['dtime']));
							$Etime =  date('H:i:s',(strtotime($row_cal['dtime']) + 60*60));
							$EdateTime = date("D M d Y", strtotime($row_cal['ddate']))." ".date("H:i:s",strtotime($Etime));
							//echo '<br>'. $row_cal['description'];
							if($row_cal['description']=='Schedule Integration' || $row_cal['description']=='Setup Installation & Training' || $row_cal['description']=='Event'){
								$ddate = $row_cal['actdetails'];
								$ddate1 = explode(' - ',$ddate);
								$ddate2 = explode(' ',$ddate1[0]);								
								$time = $ddate2[1].' '.$ddate2[2];								
								$SdateTime = date("D M d Y", strtotime($ddate2[0]))." ".date('H:i:s',strtotime($time));

								$ddate3 = explode(' ',$ddate1[1]);								
								$Etime = $ddate3[0].' '.$ddate3[1];								
								$EdateTime = date("D M d Y", strtotime($ddate2[0]))." ".date('H:i:s',strtotime($Etime));
								$Etime = date('h:i A',strtotime($Etime));
							}

							

							
							$type = "CheckList";
							if($row_cal['description']=='Event'){
								$type = "Event";
							}
							$desc = $row_cal['description'];
							if($desc=="Event"){
								$desc = "";
							}
							$loc_name = $row_cal['location_name']." (ID:".$row_cal['location_id'].") ".$desc;
							
							?>
							{
								title: "<?='- '.$Etime.' '.str_replace('"',"'",$loc_name);?>",
								start: '<?=$SdateTime?>',
								end: '<?=$EdateTime?>',
								color  :'<?=$color;?>',
								textColor: '#333333',
								allDay: '<?= $Etime == '11:59 PM' && $time == '12:00 AM'?>',
								id:'<?php echo $row_cal['id']; ?>',
								location_id:'<?php echo $row_cal['location_id']; ?>',
								checklist_id:'<?php echo $row_cal['checklist_id']; ?>',
								Etype:'<?=$type;?>',
                                editable: false,
							},
										
						<?php }
					}				
				?>
				
			],
		});	
	});
    		
    
    </script>	
    
    <?php
    exit();
     }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_SESSION["SITE_TITLE"];?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" type="text/css" href="tooltipster/dist/css/tooltipster.bundle.min.css" />

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>

<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
<script type="text/javascript" src="js/jquery.tooltipster.js"></script>
<!--
	This is not Correct Way to change button name you can change it using inline js. 
	
	<script type="text/javascript" src="js/jquery.alerts_jonnathan.js"></script>-->


<script type="text/javascript" src="/internalaccess/url.js"></script>
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script>
var GLOBAL_url = API;
</script>
<script>
    var doubleClick = '';
    var clickTimer = '';
    jQuery(document).ready(function(){
		jQuery("#locaiton_event_calender").fullCalendar({
			slotEventOverlap : false, 
			contentHeight: jQuery(window).height() + 60,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText: {
				prev: '&laquo;',
				next: '&raquo;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'today',
				week: 'W',
				month: 'M',
				day: 'D'
			},
			defaultView: 'agendaWeek',
			dayClick:function( date, allDay, jsEvent, view ) {
				var singleClick = date.toUTCString();

				if(doubleClick==singleClick){
					AddnewEventCal(date,allDay);
					doubleClick = null;
				}else{
					doubleClick=date.toUTCString();
					clearInterval(clickTimer);
					clickTimer = setInterval(function(){
						doubleClick = null;
						clearInterval(clickTimer);
					}, 500);
				}
			},
			eventClick: function(calEvent, jsEvent, view) {	
				var date = new Date();
				singleClick = date.toUTCString();
				if (doubleClick == singleClick){			
					jQuery("#checkListEventsPopup").modal('hide');
					if(calEvent.Etype=="Event"){
						let location_name = calEvent.title.substring(10,calEvent.title.length-1);
						UpdateEventPopup(calEvent.id,calEvent.location_id,location_name);
					}else{
						UpdateCheckListDetails(calEvent.id,calEvent.location_id,'checkList_'+calEvent.checklist_id);
					}
				}else{
					doubleClick = date.toUTCString();
					clearInterval(clickTimer);
						clickTimer = setInterval(function(){
						doubleClick = null;
						clearInterval(clickTimer);
					}, 500);
				}	
			},														
			//start: '10:00',
			firstHour: 7,			
			timeFormat: 'hh:mm TT',
			events: [
				<?php 
					$location_id = $_REQUEST['location_id'];
					$get_details = "SELECT DATE(lbc.details) AS ddate,DATE_FORMAT(TIME(lbc.details),'%H:%i:%s') AS dtime,lbc.location_id,l.name  as location_name,lbc.description,lbc.details as actdetails,location_boarding_checklist_id as id,global_checklist_id as checklist_id,lbc.event_type
									FROM location_boarding_checklist lbc 
									LEFT JOIN locations l ON l.id = lbc.location_id
									where lbc.description IN('Schedule Omnivore','Setup Installation & Training','Schedule Integration','Event')";
									//YEAR(lbc.details)>'2017' AND
					$res = mysql_query($get_details);
					if($res && mysql_num_rows($res)>0){
						while($row_cal = mysql_fetch_assoc($res)){
							//$row_cal['description'] = $row_cal['description']=="Event"?$row_cal['description']="":$row_cal['description'];
							
							
							$SdateTime = date("D M d Y", strtotime($row_cal['ddate']))." ".date("H:i:s",strtotime($row_cal['dtime']));
							$Etime =  date('H:i:s',(strtotime($row_cal['dtime']) + 60*60));
							$EdateTime = date("D M d Y", strtotime($row_cal['ddate']))." ".date("H:i:s",strtotime($Etime));
							//echo '<br>'. $row_cal['description'];

							$color = "#FF0000";

							if($row_cal['checklist_id']=="42"){
								$color = "#228b22";
							}else if($row_cal['checklist_id']=="38"){
								$color = "#FFA500";
							}else if($row_cal['event_type']=="1"){
								$color = "#FFFF00";
							}else if($row_cal['event_type']=="4"){
								$color = "#FF0000";
							}
							if($row_cal['event_type']=="42") {
                                $color = "#228b22";
                            }
							if($row_cal['description']=='Schedule Integration' || $row_cal['description']=='Setup Installation & Training' || $row_cal['description']=='Event'){
								$ddate = $row_cal['actdetails'];
								$ddate1 = explode(' - ',$ddate);
								$ddate2 = explode(' ',$ddate1[0]);								
								$time = $ddate2[1].' '.$ddate2[2];

                                $SdateTime = date("D M d Y", strtotime($ddate2[0]))." ".date('H:i:s',strtotime($time));

								$ddate3 = explode(' ',$ddate1[1]);								
								$Etime = $ddate3[0].' '.$ddate3[1];								
								$EdateTime = date("D M d Y", strtotime($ddate2[0]))." ".date('H:i:s',strtotime($Etime));
								$Etime = date('h:i A',strtotime($Etime));
							}
							
							$type = "CheckList";
							if($row_cal['description']=='Event'){
								$type = "Event";
							}
							$desc = $row_cal['description'];
							if($desc=="Event"){
								$desc = "";
							}
							$loc_name = $row_cal['location_name']." (ID:".$row_cal['location_id'].") ".$desc;
							
							?>
							{
								title: "<?='- '.$Etime.' '.str_replace('"',"'",$loc_name);?>",
								start: '<?=$SdateTime?>',
								end: '<?=$EdateTime?>',
								color  :'<?=$color;?>',
								textColor: '#333333',
								allDay: '<?= $Etime == '11:59 PM' && $time == '12:00 AM'?>',
								id:'<?php echo $row_cal['id']; ?>',
								location_id:'<?php echo $row_cal['location_id']; ?>',
								checklist_id:'<?php echo $row_cal['checklist_id']; ?>',
								Etype:'<?=$type;?>',
                                //editable : '<?//= (strtotime('now') < strtotime($ddate2[0].' '.$time)) ? 'true' : 'false'?>//' == 'true',
                                editable: false,
							},
										
						<?php }
					}				
				?>

			],
		});	
	});
    		
    
    </script>
<script>
	jQuery(document).ready(function(){
		//ShowLocationCalender();
	});

	 jQuery("#myModal").on('hide.bs.modal', function(){
	    jQuery("#myModal").load(location.href + " #myModal");
	    jQuery('#eventeTime').val(' ');
	    jQuery('#eventSTime').val(' ');
	  });


    function ShowLocationCalender(){
        jQuery("#loading-header").show();
        jQuery("#locaiton_event_calender").fullCalendar('destroy');		
        jQuery("#calendarDiv").html('');
        var scrolled=300;
        jQuery.ajax({
            url: "support_schedule.php",
            dataType:"HTML",
            data:{action_type:'get_calendar'},
            success: function(html){
                jQuery("#loading-header").hide();				
                jQuery("#calendarDiv").html(html);
                jQuery("#checkListEventsPopup .fc-button-month").live("click",function(){
                    var $container = jQuery("#checkListEventsPopup .widgetcontentfrm");
                    var $scrollTo = jQuery('.fc-today');
                    $container.animate({scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop(), scrollLeft: 0},300);	
                });
                setTimeout(function(){
                    jQuery(".fc-button-today").trigger('click');					                    
                },1000);
            }
        });
	}
	function TimeToAMPM(time) {  		
		time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
		  
		if (time.length > 1) { // If time format correct			
			time = time.slice(1);  // Remove full string match value
			
			time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
			time[0] = +time[0] % 12 || 12; // Adjust hours			
			time.splice(3,1);			
		}
  		return time.join(''); // return adjusted time or original string
	}
	function FormatDate(dd){		
		return dd<10 && dd.toString().length<2?'0'+dd:dd;
	}
	function AddnewEventCal(date, allDay){
		var d = new Date(date);
		var cd = new Date();
		if(cd>d){
			return false;
		}
		ed = new Date(date.getTime() + 1800000);
		
		jQuery('#event_location_search').attr('readonly', false);
		jQuery("#addEventForm")[0].reset();
        jQuery("#eventDate").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,minDate:0});

        jQuery('#eventSTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});
        jQuery('#eventeTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});	
        
        jQuery("#eventSTime").on("focus", function() {
            return jQuery(this).timepicker("showWidget");
        });
        jQuery("#eventeTime").on("focus", function() {
            return jQuery(this).timepicker("showWidget");
            return jQuery(this).timepicker("showWidget");
		});
		
		
		var dd =  d.getFullYear()+'-'+FormatDate((d.getMonth()+1))+'-'+FormatDate(d.getDate()); 
		var tt,ett;
		if(allDay){
			tt = FormatDate('00:00:00');
			ett = FormatDate('23:59:59');
		}else{
			tt = FormatDate(d.getHours())+':'+FormatDate(d.getMinutes())+':'+FormatDate(d.getSeconds());
			ett = FormatDate(ed.getHours())+':'+FormatDate(ed.getMinutes())+':'+FormatDate(ed.getSeconds());
		}
		
		tt = TimeToAMPM(tt);
		ett = TimeToAMPM(ett);
        jQuery("#eventDate").val(dd);
        jQuery('#eventSTime').val(tt);
        jQuery('#eventeTime').val(ett);
		jQuery("#CalenderNewEventPopup").modal('show');
		jQuery("#eventLocDiv").show();
        jQuery('#CalenderNewEventPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#CalenderNewEventPopup').height()/2)+'px 0 0 -'+(jQuery('#CalenderNewEventPopup').width() / 2)+'px'});	
	}
    function AddnewEvent(){    
    	jQuery('#event_location_search').attr('readonly', false);       
    	jQuery('#hidEventID').attr('value','');
        jQuery("#addEventForm")[0].reset();
        jQuery("#eventDate").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,minDate:0});
        jQuery('#eventSTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});
        jQuery('#eventeTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});
        jQuery("#eventSTime").on("focus", function() {
            return jQuery(this).timepicker("showWidget");
        });
        jQuery("#eventeTime").on("focus", function() {
            return jQuery(this).timepicker("showWidget");
            return jQuery(this).timepicker("showWidget");
        });
        jQuery("#eventDate").val(" ");
        jQuery('#eventSTime').val(" ");
        jQuery('#eventeTime').val(" ");
		jQuery("#CalenderNewEventPopup").modal('show');
		jQuery("#eventLocDiv").show();
        jQuery('#CalenderNewEventPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#CalenderNewEventPopup').height()/2)+'px 0 0 -'+(jQuery('#CalenderNewEventPopup').width() / 2)+'px'});		
    }
    function DisplayEventFields(){
		var value = jQuery("#GloablBordingType").val();
		jQuery(".eventClass").hide();
		if(value=="38"){
			jQuery(".siClass").show();
		}else if(value=="42"){
			jQuery(".sitClass").show();
		}else{
			jQuery(".siClass").show();
		}

		if (value == 1 || value == 4) {
            jQuery(".type-select").hide();
        }
		
		jQuery('#CalenderNewEventPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#CalenderNewEventPopup').height()/2)+'px 0 0 -'+(jQuery('#CalenderNewEventPopup').width() / 2)+'px'});
	}
    function UpdateDateTimeDetailsEvent(){
        var date = jQuery("#eventDate").val();
        var time = jQuery("#eventSTime").val();
        var endtime = jQuery("#eventeTime").val();		
        if(date!='' && time!=''){
            jQuery("#evnt_locationChecklistDetails").val(date+' '+time+' - '+endtime);			
        }
    }
    function blockSpecialChar(e) {
        var k = e.which;
        
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 58 || k == 45 || k == 32 || k == 46 || k == 0 || k == 47  || (k >= 48 && k <= 57));
    }
    function UpdateEventPopup(checklistId,location_id, location_name){
        jQuery("#CalenderNewEventPopup").modal("show");
        jQuery("#addEventForm")[0].reset();
        var data = {
            checklistId:checklistId,
            req_type:'getEventDetails'
        };
        jQuery.ajax({
            url:'support_schedule.php',
            type:'POST',
            data: data,
            dataType:'json',
            success:function(data){
            	console.log(data);
                if(data!=''){
                    jQuery("#eventDate").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,minDate:0});
                    jQuery('#eventSTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});
                    jQuery('#eventeTime').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true});
                    jQuery("#eventSTime").on("focus", function() {
                        return jQuery(this).timepicker("showWidget");
                    });
                    jQuery("#eventeTime").on("focus", function() {
                        return jQuery(this).timepicker("showWidget");
                    });
                    jQuery("#eventDate").val(data.date);
                    jQuery("#eventSTime").val(data.sTime);
                    jQuery("#eventeTime").val(data.eTime);
					jQuery("#hidEventID").val(data.id);
					jQuery("#event_location_id").val(data.location_id);
					if(data.global_checklist_id=="" && data.event_type>0){
						data.global_checklist_id = data.event_type;
					}
					
                    jQuery("#GloablBordingType").val(data.global_checklist_id);
                    jQuery("#evnt_conference_number").val(data.conference_number);
                    jQuery("#evnt_conference_url").val(data.conference_url);
                    jQuery("#evnt_locationChecklistDetails").val(data.details);
                    jQuery("#evnt_ov_line").val(data.ov_line);
                    jQuery("#evnt_merchant_line").val(data.merchant_line);
                    jQuery("#evnt_CreatedBy").val(data.created_by);
                    jQuery("#evnt_CreatedOn").val(data.created_on);
                    jQuery("#evnt_CreatedDateTime").val(data.created_datetime);
                    jQuery("#evnt_LastBy").val(data.last_by);
                    jQuery("#evnt_LastOn").val(data.last_on);
					jQuery("#evnt_LastDateTime").val(data.last_datetime);
					jQuery("#eventLocDiv").show();
					jQuery('#event_location_search').val(location_name);
					jQuery('#event_location_id').val(data.location_id);
					jQuery('#event_location_search').attr('readonly', true);

					if(data.global_checklist_id==42 || data.global_checklist_id==38){
						console.log(data.type);
						jQuery('#installationIntegration_type').val(data.type);
					}
                    DisplayEventFields();
                }
            }
        });
    }
    function UpdateCheckListDetails(id,location_id, element,first_time='no'){
        jQuery("#common_first_time").val(first_time);                
        jQuery("#locGID").val(element);
        jQuery("#plocGID").val(element);       

        var d = new Date();
        var dateandtime = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();

        jQuery('#hidd_edited_checkbox').val(element);
        jQuery.ajax({
            url:'support_schedule.php',
            type:'POST',
            data:{action_type:'getlocCheckDetails',locbID:id,location_id:location_id},
            dataType:"json",
            success:function(data){
                jQuery(".perform_int").hide();
                jQuery("#perform_status").val("");
                jQuery(".omnivore_sync").hide();
                jQuery(".aireus_sync").hide();
                
                

                
                jQuery(".datetimeTr1").hide();                
                jQuery(".datetimeTr").hide();
                jQuery(".datetimeTrInst").hide();
                jQuery(".ord_hrdwrField").hide();
                jQuery("#btnCalednder").hide();    
                
                
                jQuery("#Common_popup_title").html(data.description);                
                jQuery("#checklist_details_required").val("No");
                if(first_time=='yes'){
                    jQuery("#checklist_details_required").val(data.details_required);
                }
                
                if(data.description=='Schedule Integration'){
                    jQuery("#locationChecklistDetails").val(data.details);
                    jQuery("#detCreatedOn").val(data.created_on.replace(/\s/g,''));
                    jQuery("#detCreatedBy").val(data.created_by);
                    jQuery("#detCreatedDateTime").val(data.created_datetime);
                    jQuery("#detLastOn").val(data.last_on);
                    jQuery("#detLastBy").val(data.last_by);
                    jQuery("#detLastDateTime").val(data.last_datetime);
                    jQuery("#hiddenLocationId").val(location_id);
                    jQuery("#locbID").val(id);
                    jQuery(".conf_field").show();
                    jQuery("#conference_number").val(data.conference_number);
                    jQuery("#conference_url").val(data.conference_url);
                    jQuery("#ov_line").val(data.ov_line);
                    jQuery("#merchant_line").val(data.merchant_line);
                    jQuery(".datetimeTr").show();
                    jQuery(".datetimeTr1").show();
                    jQuery("#calenderDatein").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,minDate:0});
                    jQuery('#calenderTimein').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                    jQuery('#calenderTimeout').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                    jQuery("#calenderDatein").val('');
                    jQuery("#calenderTimein").val('');
                    jQuery("#calenderTimeout").val('');
                    var details = data.details;
                    if(details!=''){
                        var datas = details.split(' ');
                        var date = datas[0];
                        var stime = datas[1]+' '+datas[2];	
                        
                        var etime = '';
                        if(datas[4]!=''){
                            etime = datas[4]+' '+datas[5];						
                        };	
                        //re = /^\d{4}\-\d{1,2}\-\d{1,2}$/;
                        re = /^(\d{4})-(\d{1,2})-(\d{1,2})/ 
                        if(date.match(re)){
                            jQuery("#calenderDatein").val(date);
                        }
                        //re = /^\d{1,2}:\d{2}([ap]m)?$/;
                        re = /^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/;
                        if(stime.match(re)){
                            jQuery("#calenderTimein").val(stime);
                        }
                        if(etime.match(re)){
                            jQuery("#calenderTimeout").val(etime);
                        }
                    }
                    
                    jQuery("#checkListDetailsPopup").modal('show');
                    GeneratePAss();
                    jQuery('#checkListDetailsPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#checkListDetailsPopup').height() / 2)+'px 0 0 -'+(jQuery('#checkListDetailsPopup').width() / 2)+'px'});
                    //jQuery("#btnCalednder").show();    
                }else if(data.description=='Setup Installation & Training'){
                    jQuery("#SIcalenderDate").datepicker({changeMonth: true,dateFormat:"yy-mm-dd",changeYear: true,minDate:0 });
                    jQuery("#SIcalenderDatein").datepicker({changeMonth: true,dateFormat:"yy-mm-dd",changeYear: true,minDate:0 });
                    jQuery("#SIcalenderDateout").datepicker({changeMonth: true,dateFormat:"yy-mm-dd",changeYear: true,minDate:0 });
                    jQuery('#SIcalenderTimein').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                    jQuery('#SIcalenderTimeout').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                    GeneratePAss();
                    
                    var detailsArr = new Array();
                    if(data.details!=""){
                        detailsArr = data.details.split('||');				
                        jQuery("#SIpinstallationType").val(data.installation_type);
                        if(data.installation_type=="Remote"){
                            jQuery("#SIcalenderDate").val(detailsArr[0]);
                            jQuery("#SIcalenderTimein").val(detailsArr[1]);
                            jQuery("#SIcalenderTimeout").val(detailsArr[2]);
                        }else{
                            jQuery("#SIcalenderDatein").val(detailsArr[0]);
                            jQuery("#SIcalenderTimein").val(detailsArr[1]);
                            jQuery("#SIcalenderDateout").val(detailsArr[2]);
                            jQuery("#SIcalenderTimeout").val(detailsArr[3]);
                        }
                    }
                    
                    jQuery("#hiddenLocationId").val(location_id);
                    jQuery("#locbID").val(id);
                    jQuery("#locGID").val(data.global_boarding_checklist_id);
                    
                    jQuery("#SIconference_number").val(data.conference_number);
                    jQuery("#SIconference_url").val(data.conference_url);
                    jQuery("#SItechnician_softpoint").val(data.technician_3rd_party);				
                    jQuery("#SIdetCreatedBy").val(data.created_by);
                    jQuery("#SIdetCreatedOn").val(data.created_on.replace(/\s/g,''));
                    jQuery("#SIdetCreatedDateTime").val(data.created_datetime);
                    jQuery("#SIdetLastBy").val(data.last_by);
                    jQuery("#SIdetLastOn").val(data.last_on);
                    jQuery("#SIdetLastDateTime").val(data.last_datetime);			

                    GetInstallationFields(data.installation_type);
                    jQuery("#setupInstAllationPopup").modal('show');
                    jQuery('#setupInstAllationPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#setupInstAllationPopup').height() / 2)+'px 0 0 -'+(jQuery('#setupInstAllationPopup').width() / 2)+'px'});
                }else{	
                    
                    jQuery("#locationChecklistDetails").val(data.details);
                    jQuery("#detCreatedOn").val(data.created_on.replace(/\s/g,''));
                    jQuery("#detCreatedBy").val(data.created_by);
                    jQuery("#detCreatedDateTime").val(data.created_datetime);
                    jQuery("#detLastOn").val(data.last_on);
                    jQuery("#detLastBy").val(data.last_by);
                    jQuery("#detLastDateTime").val(data.last_datetime);
                    jQuery("#hiddenLocationId").val(location_id);
                    jQuery("#locbID").val(id);
                    GeneratePAss();
                    jQuery("#checkListDetailsPopup").modal('show');	
                    if(data.description=="Schedule Omnivore" || data.description=='Setup Installation & Training'){
                            if(data.description=='Setup Installation & Training'){
                                jQuery(".datetimeTrInst").show();
                            }
                            jQuery("#conference_number").val(data.conference_number);
                            jQuery("#conference_url").val(data.conference_url);
                            jQuery("#ov_line").val(data.ov_line);
                            jQuery("#merchant_line").val(data.merchant_line);
                            //jQuery("#btnCalednder").show();
                            jQuery(".datetimeTr").show();
                            if(data.description=='Setup Installation & Training'){
                                jQuery(".datetimeTr1").hide();
                                jQuery(".conf_field").show();
                            }						
                            jQuery("#calenderDatein").datepicker({ dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true,minDate:0});
                            jQuery('#calenderTimein').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                            jQuery('#calenderTimeout').timepicker({timeFormat:'hh:mm:ss',defaultTime:false, showMeridian : true,interval:15});
                            jQuery("#calenderDatein").val('');
                            jQuery("#calenderTimein").val('');
                            jQuery("#calenderTimeout").val('');
                            var details = data.details;
                            if(details!=''){
                                var datas = details.split(' ');
                                var date = datas[0];
                                var stime = datas[1]+' '+datas[2];	
                        
                                var etime = '';
                                if(datas[4]!=''){
                                    etime = datas[4]+' '+datas[5];						
                                };	
                                //re = /^\d{4}\-\d{1,2}\-\d{1,2}$/;
                                re = /^(\d{4})-(\d{1,2})-(\d{1,2})/ 
                                if(date.match(re)){
                                    jQuery("#calenderDatein").val(date);
                                }
                                //re = /^\d{1,2}:\d{2}([ap]m)?$/;
                                re = /^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/;
                                if(stime.match(re)){
                                    jQuery("#calenderTimein").val(stime);
                                }
                                if(etime.match(re)){
                                    jQuery("#calenderTimeout").val(etime);
                                }
                            }
                            
                        }                        
                    jQuery('#checkListDetailsPopup').css({top:'50%',left:'50%',margin:'-'+(jQuery('#checkListDetailsPopup').height() / 2)+'px 0 0 -'+(jQuery('#checkListDetailsPopup').width() / 2)+'px'});
                    
                }
            }
        });
        }
    jQuery(document).ready(function(){
        jQuery("#eventDate").on('change',function(){
			UpdateDateTimeDetailsEvent();
		});
		jQuery("#eventSTime").on('change',function(){
			UpdateDateTimeDetailsEvent();
		});
		jQuery("#eventeTime").on('change',function(){
			UpdateDateTimeDetailsEvent();
		});

        jQuery("#SubmitNewEvent").on("click",function(){
        	var e_loc_search = jQuery('#event_location_search').val();
			var date = jQuery("#eventDate").val();
			var stime = jQuery('#eventSTime').val();
			var etime = jQuery('#eventeTime').val();
			var hidEventID = jQuery('#hidEventID').val();
			var hiddenGid = jQuery('#hiddenGid').val();			
			var location_id = jQuery('#event_location_id').val(); 
			var GloablBordingType = jQuery("#GloablBordingType").val();
			var evnt_conference_number = jQuery("#evnt_conference_number").val();
			var evnt_conference_url = jQuery("#evnt_conference_url").val();
			var evnt_add_installation_record = jQuery("#evnt_add_installation_record").val();
			var evnt_locationChecklistDetails = jQuery("#evnt_locationChecklistDetails").val();
			var evnt_ov_line = jQuery("#evnt_ov_line").val();
			var evnt_merchant_line = jQuery("#evnt_merchant_line").val();
			var evnt_locationChecklistDetails = jQuery("#evnt_locationChecklistDetails").val();		

			let installationIntegration_type = jQuery('#installationIntegration_type').val();
			if(e_loc_search=="")	{
				jAlert("Please Enter Location","Alert Dialog");
				return false;
			}
			if(GloablBordingType=="")	{
				jAlert("Please Select an Event","Alert Dialog");
				return false;
			}
			
			if(date==""){
				jAlert("Please select Date","Alert Dialog");
				return false;
			}
			if(stime==""){
				jAlert("Please select Start Time","Alert Dialog");
				return false;
			}
			if(etime==""){
				jAlert("Please select End Time","Alert Dialog");
				return false;
			}
            let starttime = stime.split(' ')[0].split(':');
            starttime[0] = starttime[0] === '12' ? 0 : starttime[0];
            starttime[0] = stime.split(' ')[1] === 'PM' ? Number(starttime[0]) + 12 : 0;
			let startDate = new Date();
			startDate.setHours(starttime[0]);
			startDate.setMinutes(starttime[1]);

            let endtime = etime.split(' ')[0].split(':');
            endtime[0] = endtime[0] === '12' ? 0 : endtime[0];
            endtime[0] = etime.split(' ')[1] === 'PM' ? Number(endtime[0]) + 12 : 0;
            let endDate = new Date();
            endDate.setHours(endtime[0]);
            endDate.setMinutes(endtime[1]);
			if(startDate > endDate){
                jAlert("Start date should be before End date","Alert Dialog");
                return false;
            }
			if(jQuery("#evnt_conference_number").val()==""){
				jAlert("Please Enter Conference Number & PIN","Alert Dialog");
				return false;
			}
			if(jQuery("#evnt_conference_url").val()==""){
				jAlert("Please Enter Conference URL","Alert Dialog");
				return false;
			}
			if(GloablBordingType==42 && jQuery("#evnt_add_installation_record").val()==""){
				jAlert("Please select an Instalation Record","Alert Dialog");
				return false;
			}
			if((GloablBordingType==42 ||GloablBordingType==38)&& jQuery("#installationIntegration_type").val()==""){
				jAlert("Please select a Type","Alert Dialog");
				return false;
			}

			if(jQuery("#evnt_locationChecklistDetails").val()==""){
				jAlert("Please Enter Description","Alert Dialog");
				return false;
			}			
			jQuery.ajax({
				url: "support_schedule.php",  
				type:'POST',			
				data:{
					request_type:'add_new_event',
					location_id:location_id,
					date:date,
					stime:stime,
					etime:etime,
					hiddenGid:hiddenGid,
					hidEventID:hidEventID,
					evnt_conference_number:evnt_conference_number,
					evnt_conference_url:evnt_conference_url,
					evnt_add_installation_record:evnt_add_installation_record,
					evnt_locationChecklistDetails:evnt_locationChecklistDetails,
					evnt_ov_line:evnt_ov_line,
					evnt_merchant_line:evnt_merchant_line,
					evnt_locationChecklistDetails:evnt_locationChecklistDetails,
					GloablBordingType:GloablBordingType,
					type: installationIntegration_type
				},
				success:function (d) {
					if(d){
						jAlert("Event has been added successfully","Alert Dialog");
						ShowLocationCalender(location_id);
                        jQuery("#CalenderNewEventPopup").modal('hide');
					}
				}
			});
		});
  installationIntegration_type      
        
        
        jQuery('#goSearch').click(function(){
            jQuery("#loading-header").show("fast");
            var total_rec = jQuery('#total_rows').val();
            jQuery('#pageSize').val(jQuery('#selectSize').val());
            if(total_rec > 499){
                var search_inpt = jQuery('.go_search').val();     
                if (search_inpt!=null) {
                    search_inpt = search_inpt.replace(/ /gi,'') ; //replace all spaces (just to be sure)
                    if(search_inpt.length > 0){
                        if(search_inpt.length < 3) {
                            jQuery("#loading-header").hide();
                            jAlert('Please enter 3 or more characters');
                            return false;                            
                        }else{
                            jQuery("#loading-header").hide();
                            jQuery("#searchform").submit();
                        }
                    }else{
                        jQuery("#loading-header").hide();
                        jAlert(' Enter value to search');
                        return false;
                    }  
                    return false;
                }else{
                    jQuery("#loading-header").hide();
                    jAlert(' Enter value to search');
                    return false;                
                }
            }else{
                jQuery("#searchform").submit();
                jQuery("#loading-header").hide();
            }
        });
        jQuery('#dyntable1').dataTable( {
            "sPaginationType": "full_numbers",
            "bFilter": true,
            "bScrollInfinite": false,
			"bAutoWidth":false,
			"aaSorting": [[2, "asc"]],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            },
            "oLanguage": {
                "sZeroRecords":  "No data available in table"
            }
		});
		
		var l_xhr = null;
		jQuery('#event_location_search').typeahead({
			source: function (query, process) {
				if(query.length < 3) {
					return false;
				}
				jQuery('#event_location_search').addClass('loadinggif');
				if(l_xhr != null) {
					l_xhr.abort();
					l_xhr = null;
				}
				return l_xhr = jQuery.ajax({				
				url: 'ajax_get_location_with_id.php',
					type: 'post',
					data: { query: query,  autoCompleteClassName:'autocomplete',
					selectedClassName:'sel',
					attrCallBack:'rel',
					limit: 20,
					identifier:'estado'},
					dataType: 'json',
					success: function (result) {
						var v = jQuery('#event_location_search').val();
						if(v.length > 5 && result.length < 1){
							jAlert("Location does not exist or may be Inactive!");
							jQuery('#event_location_search').removeClass('loadinggif');
							jQuery('#event_location_id').val('');
							return false;
						}
						jQuery('#event_location_search').removeClass('loadinggif');						
						var lcid = result[0].id;
						
						if( (isNaN(query) == false) && (result.length == 1) ) {
						
							var resultList = result.map(function (item) {
								jQuery('#event_location_id').attr('value', item.id);
								jQuery('#event_location_search').attr('value', item.label);
							});	
							
						} else {
							var resultList = result.map(function (item) {
								var aItem = { id: item.id, name: item.label };
								return JSON.stringify(aItem);
							});
			
							return process(resultList);
						} 
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
				var item = JSON.parse(obj);
				jQuery('#event_location_id').attr('value', item.id);				
				var lcid = item.id;
				return item.name;
			}
		});
    });
    function CancelChecklsitDetails(){
        var check_box = jQuery('#hidd_edited_checkbox').val();	
        if (jQuery('#checklist_details_required').val()=="Yes") {
            jQuery('#'+check_box).trigger('click');
        }
        jQuery("#checkListDetailsPopup").modal('hide');
        jQuery("#setupInstAllationPopup").modal('hide');
    }
    function SubmitSetupTraining(){		  
			var SIpinstallationType = jQuery("#SIpinstallationType").val();
			var SIcalenderDate = jQuery("#SIcalenderDate").val();
			var SIcalenderDatein = jQuery("#SIcalenderDatein").val();
			var SIcalenderTimein = jQuery("#SIcalenderTimein").val();
			var SIcalenderDateout = jQuery("#SIcalenderDateout").val();
			var SIcalenderTimeout = jQuery("#SIcalenderTimeout").val();
			var SIconference_number = jQuery("#SIconference_number").val();
			var SIconference_url = jQuery("#SIconference_url").val();
			var SItechnician_softpoint = jQuery("#SItechnician_softpoint").val();
			var SIadd_installation_record = jQuery("#SIadd_installation_record").val();

			var locbID = jQuery("#locbID").val();
			var plocGID = jQuery("#locGID").val();
			var p_recType =  jQuery("#p_recType").val();
			var loc_id = jQuery("#hiddenLocationId").val();			
			var location_id = jQuery('#hiddenLocationId').val();
			 
			


			if(SIpinstallationType==""){
				jAlert("Please Select Installation Type!","Alert Dialog");
				return false;
			}			
			if(SIcalenderDate=="" && SIpinstallationType=="Remote"){
				jAlert("Please Enter Date!","Alert Dialog");
				return false;
			}
			if(SIcalenderDatein=="" && SIpinstallationType=="Onsite"){
				jAlert("Please Enter Start Date!","Alert Dialog");
				return false;
			}
			if(SIcalenderTimein==""){
				jAlert("Please Enter Start Time!","Alert Dialog");
				return false;
			}
			if(SIcalenderDateout=="" && SIpinstallationType=="Onsite"){
				jAlert("Please Enter End Date!","Alert Dialog");
				return false;
			}
			if(SIcalenderTimeout==""){
				jAlert("Please Enter End Time!","Alert Dialog");
				return false;
			}
			if(SIcalenderTimeout==""){
				jAlert("Please Enter End Time!","Alert Dialog");
				return false;
			}
			if(SIconference_number=="" && SIpinstallationType=="Remote"){
				jAlert("Please Enter Conference Number & PIN!","Alert Dialog");
				return false;
			}
			if(SIconference_url=="" && SIpinstallationType=="Remote"){
				jAlert("Please Enter Conference URL!","Alert Dialog");
				return false;
			}
			if(SItechnician_softpoint=="" && SIpinstallationType=="Onsite"){
				jAlert("Please Select Softpoint Technician!","Alert Dialog");
				return false;
			}
			if(SIadd_installation_record==""){
				jAlert("Please Select Installation Record!","Alert Dialog");
				return false;
			}
			if(SIpinstallationType=="Remote"){
				var details = SIcalenderDate+'||'+SIcalenderTimein+'||'+SIcalenderTimeout;
			}else{
				var details = SIcalenderDatein+'||'+SIcalenderTimein+'||'+SIcalenderDateout+'||'+SIcalenderTimeout;
			}
			
			
			
			jQuery("#checklist_details_required").val("No");
			
			jQuery.ajax({
				url:'support_schedule.php',
				type:'POST',
				data:{action_type:'SavelocCheckDetailsSI',locbID:locbID,details:details,SIconference_number:SIconference_number,SIconference_url:SIconference_url,SItechnician_softpoint:SItechnician_softpoint,plocGID:plocGID,p_recType:p_recType,location_id:location_id,SIadd_installation_record:SIadd_installation_record,SIpinstallationType:SIpinstallationType,txtPassword:jQuery("#txtPassword").val()},
				success:function(data){
					ShowLocationCalender(location_id);		
				}
			});
	}
    function SubmitChecklsitDetails(){
        var locbID = jQuery("#locbID").val();
        var details =  jQuery("#locationChecklistDetails").val();
        var loc_id = jQuery("#hiddenLocationId").val();
        var perform_status = jQuery("#perform_status").val();
        var digital_image_name = jQuery("#digital_image_name").val();
        var digital_image_delete = jQuery("#digital_image_delete").val();
        var locGID = jQuery("#locGID").val();
        var common_first_time = jQuery("#common_first_time").val();
        var conference_number = jQuery("#conference_number").val();
        var conference_url = jQuery("#conference_url").val();
        var ov_line = jQuery("#ov_line").val();
        var merchant_line = jQuery("#merchant_line").val();
        var traking_number = jQuery("#traking_number").val();
        var hardware_type = jQuery("#hardware_type").val();
        var hardware_req = jQuery("#hardware_req").val();
        var hardware_purchaser = jQuery("#hardware_purchaser").val();
        var new_hardware_purchaser = jQuery("#new_hardware_purchaser").val();
        var hardware_transaction_no = jQuery("#hardware_transaction_no").val();
        var add_installation_record = jQuery("#add_installation_record").val();
        var txtPassword = jQuery("#txtPassword").val();
        
        
        if(jQuery("#calenderDatein").is(":visible") && jQuery("#calenderDatein").val()==""){
            jAlert("Please select Date!","Alert Dialog");
            return false;
        }
        if(jQuery("#calenderTimein").is(":visible") && jQuery("#calenderTimein").val()==""){
            jAlert("Please select Start Time!","Alert Dialog");
            return false;
        }
        if(jQuery("#calenderTimeout").is(":visible") && jQuery("#calenderTimeout").val()==""){
            jAlert("Please select End Time!","Alert Dialog");
            return false;
        }
        if(jQuery("#perform_status").is(":visible") && perform_status==""){
            jAlert("Please select Status!","Alert Dialog");
            return false;
        }
        if(jQuery("#perform_Users").is(":visible") && jQuery("#perform_Users").val()==""){
            jAlert("Please select User!","Alert Dialog");
            return false;
        }
        if(jQuery("#conference_number").is(":visible") && jQuery(".conf_field").is(":visible") && jQuery("#conference_number").val()==""){
            jAlert("Please enter Conference Number & Pin!","Alert Dialog");
            return false;
        }
        if(jQuery("#conference_url").is(":visible") && jQuery(".conf_field").is(":visible") && jQuery("#conference_url").val()==""){
            jAlert("Please enter Conference URL!","Alert Dialog");
            return false;
        }
        if(jQuery("#add_installation_record").is(":visible") && jQuery("#add_installation_record").val()==""){
            jAlert("Please select Installation Record!","Alert Dialog");
            return false;
        }
        if(jQuery("#hardware_req").is(":visible")){
            if(jQuery("#hardware_req").val()=="Yes"){
                if(details==''){
                    jAlert("Please Insert Description!","Alert Dialog");
                    return false;
                }else if(details.trim()==''){
                    jAlert("Please Insert Description!","Alert Dialog");
                    return false;
                }
            }
        }else{
            if(jQuery("#"+locGID).attr("data-details-required")=="Yes"){                
                if(details==''){
                    jQuery("#locationChecklistDetails").val("None");                    
                }else if(details.trim()==''){
                    jQuery("#locationChecklistDetails").val("None");                    
                }
            }	
        }	
        
        if(jQuery("#hardware_type").is(":visible") && jQuery("#hardware_type").val()=="" && jQuery("#hardware_req").val()=="Yes"){
            jAlert("Please select Hardware Type!","Alert Dialog");
            return false;
        }
        if(jQuery("#hardware_purchaser").is(":visible") && jQuery("#hardware_purchaser").val()==""  && jQuery("#hardware_req").val()=="Yes"){
            jAlert("Please select Hardware Purchaser!","Alert Dialog");
            return false;
        }
        if(jQuery("#new_hardware_purchaser").is(":visible") && jQuery("#new_hardware_purchaser").val()==""){
            jAlert("Please Enter Hardware Purchaser Name!","Alert Dialog");
            return false;
        }
        jQuery("#checklist_details_required").val("No");
        jQuery.ajax({
            url:'support_schedule.php',
            type:'POST',
            data:{action_type:'SavelocCheckDetails',locbID:locbID,details:details,perform_status:perform_status,digital_image_name:digital_image_name,digital_image_delete:digital_image_delete,common_first_time:common_first_time,conference_number:conference_number,conference_url:conference_url,ov_line:ov_line,merchant_line:merchant_line,traking_number:traking_number,hardware_type:hardware_type,hardware_req:hardware_req,hardware_purchaser:hardware_purchaser,new_hardware_purchaser:new_hardware_purchaser,hardware_transaction_no:hardware_transaction_no,add_installation_record:add_installation_record,location_id:loc_id,txtPassword:txtPassword},
            success:function(data){
                ShowLocationCalender(loc_id);	
            }
        });
	}
	function GeneratePAss(){
		var length=8;
		var chars='#A';
		var mask = '';
		if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
		if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if (chars.indexOf('#') > -1) mask += '0123456789';
		if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
		var result = '';
		for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
		jQuery("#txtPassword").val(result);
	}
	function GetInstallationFields(val){		
		jQuery(".RemoteTr").hide();
		jQuery(".OnsiteTr").hide();
		if(val=="Remote"){
			jQuery(".RemoteTr").show();
		}else if(val=="Onsite"){
			jQuery(".OnsiteTr").show();	
		}
	}
</script>
<Style>
    .error{
        color:red;
    }
    .line3,.line6{
        background-color: gray;
    } 
	
	#frmterminals select{
	height:20px !important;
	 border-radius: 0 !important;
}
.widgetcontentfrm input[type="text"], .widgetcontentfrm select, .widgetcontentfrm textarea {
	width:90% ;
	-ms-box-sizing:content-box;
	-moz-box-sizing:content-box;
	box-sizing:content-box;
	-webkit-box-sizing:content-box;
	margin-right:0;
}
.bootstrap-timepicker-widget table td input{
	width: 25px !important;
}
.dropdown-menu > li > a > strong {
	position:relative;
	top:-1px;
}
.icon-pencil-new {
	background-image: url("images/new_edit.png")!important;
	height:16px;
	width:16px;
	display:inline-block;
	background-position:0;
}
.icon-trash-new {
	background-image: url("images/new_del.png")!important;
	height:16px;
	width:13px;
	display:inline-block;
	background-position:0;
}
.shadow_select {
	background-color: #ffffff;
	border: 1px solid #cccccc;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
	-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
	-ms-transition: border linear 0.2s, box-shadow linear 0.2s;
	-o-transition: border linear 0.2s, box-shadow linear 0.2s;
	transition: border linear 0.2s, box-shadow linear 0.2s;
}
.shadow_select:focus {
	border-color: rgba(82, 168, 236, 0.8);
	outline: 0;
	outline: thin dotted \9;
	/* IE6-9 */

  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
	-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
}
.webkit_specific {
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
}
.widgetcontentfrm .dropdown-menu {
	width:21%!important;
}
.widgetcontentfrm .typeahead{
	width: auto !important;
}
.widgetcontentfrm #cashier_bank {
 // height:22px!important;
} 
.paginate_disabled_next,.paginate_disabled_previous {
    border: 1px solid #ccc;
    padding: 5px;
	color: #ccc;
	background: #eee;
}
#dyntablerightterminal{
	/*display: table-caption;*/
	overflow: auto;
	max-width: 100%;
}
.btn-group1 {
    position: relative;
    display: inline-block;
    font-size: 0;
    vertical-align: middle;
    white-space: nowrap;
	float:right;
}
.btn-group1 .btn{padding: 11px 20px !important;}
.table td,.table th {vertical-align:middle !important;}
.bootstrap-timepicker-hour, .bootstrap-timepicker-minute{ width:25px !important; }
.ui-state-disabled span{text-align:center; vertical-align:middle; padding:2px 8px;}

.tooltipster-base {
	opacity: 0.85 !important;
	z-index: 0 !important;
} 
.tooltipster-base .tooltipster-box {
	background: rgba(51, 51, 51, 1) !important;;
	color:#FFFFF;
	border: 1px solid #DDDDDD;
	border-radius: 0px;
	box-shadow:none;
}
#tooltip_lng_title {
    font-size: 25px;
    text-align: center;
    padding: 19px;
}
#tooltip_lng_details {
    font-size: 16px;
    padding: 20px;
}
.codedatamessage img{ min-width:16px; }

.maxHeight{
	display: block;
  	width: 90%;	
	height: 5.5em;
	overflow: hidden;
	position:relative;
}
.trimwords {
  display: block;
  width: 90%;
  /*height: 12em;*/
   height: 5.5em;
  overflow: hidden;
  position:relative;
}


.trimwords > div {
background:#F7F7F7; none repeat scroll 0 0;
/*bottom: 3%;*/
bottom: -1%;
position: absolute;
margin-right:1em;
right:0;
}
.widgetcontent p {
 margin: 0 !important;
 }
 #dyntablerightchecklist1 img {
    width: 20px;
}
.line3,.line6{
    background-color: gray;
}
</style>
    
</head>

<body>

<div class="mainwrapper">
    
    <div class="header">
        <?php include_once 'includes/header.php';?>
    </div>
    
    <div class="leftpanel">
        
        <div class="leftmenu">        
            <?php include_once 'includes/left_menu.php';?>
        </div><!--leftmenu-->
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">        
        <ul class="breadcrumbs">
            <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Support <span class="separator"></span></li>
            <li>Schedule</li>
            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle" style="float:right;"><i class="icon-tint"></i> Color Skins</a>
                <ul class="dropdown-menu pull-right skin-color">
                    <li><a href="default">Default</a></li>
                    <li><a href="navyblue">Navy Blue</a></li>
                    <li><a href="palegreen">Pale Green</a></li>
                    <li><a href="red">Red</a></li>
                    <li><a href="green">Green</a></li>
                    <li><a href="brown">Brown</a></li>
                </ul>
            </li>
        </ul>        		
        <div class="pageheader">
            <div style="float:right;margin-top: 10px;margin-left:10px;wdith:100%;">                                    
                <a href="javascript:void(0)" onClick="AddnewEvent()"> <input type="button" id="add_button1" class="btn btn-success btn-large hide" value="Add Event"></a>

                <button id="show_legend" class="btn btn-large" rel='tooltip' 
                data-original-title='<table width="180px">
                						<tr>
            								<td width="25%"><img  width="25%" src="img/yellowtype.JPG" /></td>
            								<td style="text-align:left">Precheck</td>
            							</tr>

            							<tr>
            								<td width="25%"><img  width="25%" src="img/orangetype.JPG" /></td>
            								<td style="text-align:left">Integration</td>
            							</tr>

                						<tr>
            								<td width="25%"><img  width="25%" src="img/greentype.JPG" /></td>
            								<td style="text-align:left">Installation & Training</td>
            							</tr>

            							<tr>
            								<td width="25%"><img width="25%" src="img/redtype.JPG" /></td>
            								<td style="text-align:left">Support</td>
            							</tr>
            						</table>'>Legend</button>
                
            </div>
            <div class="pageicon"><span class="iconfa-headphones"></span></div>
            <div class="pagetitle">                    
                <h5>Support Schedule</h5>                    
                <h1>Schedule</h1>
            </div>            
        </div>
        <div class="maincontent">
            <div class="maincontentinner" style="min-height:400px;">
             	<div class="row-fluid">                    
                    <div class="span12">
                        <div class="widgetbox">
                            <div class="headtitle">
                                <h4 class="widgettitle">Schedule</h4>    
                            </div>
                            <div class="widgetcontent">
                                <input type="hidden" name="hiddenLocationId" id="hiddenLocationId" value="">  
								<div id="calendarDiv"></div>                              
                                <div id="locaiton_event_calender"></div> 
                            </div>
                        </div>        
                    </div>
                 </div>
            </div>
        </div>
    </div>
</body>
<div id="CalenderNewEventPopup" class="modal hide fade" style="max-width:600px;">   
	<div class="modal-header" >
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Add Event</h3>		
	</div>
	<input type="hidden" name="hidEventID" id="hidEventID" value="">
	<div class="modal-body widgetcontentfrm" style="max-height:610px !important; overflow:unset;overflow-x: auto;">
		<form id="addEventForm" action="">
			<table width="100%">
			
				<tr id="eventLocDiv">
					<td width="30%">Location:<span style="color:#F00;">*</span></td>
					<td width="70%">
					<input type="text"  name="event_location_search" value="" id="event_location_search" placeholder="Location" title="location" style="width:321px;height:24px;" autocomplete="off" >
					<input type="hidden" name="event_location_id" value="" id="event_location_id">
					</td>
				</tr>											
				<tr>
					<td width="30%">Event:<span style="color:#F00;">*</span></td>
					<td width="70%">
						<input type="hidden" value="" id="hiddenGid">					
						<select id="GloablBordingType" onChange="DisplayEventFields()" name="GloablBordingType" style="height:24px;width:320px;border-radius:0px;">
							<option value=""> - - - Select Event - - -</option>
							<option value="1">Precheck</option>
							<option value="38">Integration</option>
							<option value="42">Installation & Training</option>
							<option value="4">Support</option>
						</select>
					</td>
				</tr>
                <tr class="siClass sitClass type-select eventClass" style="display:none;">
                    <td width="30%">Type:<span class="conf_field" style="color:#F00;">*</span></td>
                    <td width="70%">
                        <select id="installationIntegration_type" name="installationIntegration_type" style="height:24px; border-radius:0px">
                            <option value=""> - - - Select Type - - -</option>
                            <?php
                            $typeq  = "SHOW COLUMNS FROM location_boarding_checklist WHERE Field = 'type'";
                            $res = mysql_query($typeq);
                            $trow = mysql_fetch_assoc($res);
                            $type = $trow['Type'];
                            preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
                            $enum = explode("','", $matches[1]);

                            foreach($enum as $val){
                                echo '<option value="'.$val.'">'.$val.'</option>';
                            }
                            ?>
                    </td>
                </tr>
				<tr>
					<td width="30%">Date:<span style="color:#F00;">*</span></td>
					<td width="70%">
						<input type="hidden" value="" id="hiddenGid">
						<input type="text" autocomplete="off" style="margin: 10px 0px;width: 321px;" name="eventDate" id="eventDate" value="" >
					</td>
				</tr>
				<tr>
					<td width="30%">Start Time:<span style="color:#F00;">*</span></td>
					<td width="70%">
					<div class="input-append bootstrap-timepicker" >
						<input id="eventSTime" autocomplete="off" name="eventSTime" value=" " style="width:295px;" type="text" />
						<span class="add-on"><i class="iconfa-time"></i></span>
					</div>                  
				</tr>
				<tr>
					<td width="30%">End Time:<span style="color:#F00;">*</span></td>
					<td width="70%">
					<div class="input-append bootstrap-timepicker" >
						<input id="eventeTime" autocomplete="off" name="eventeTime" value=" " style="width:295px;" type="text" />
						<span class="add-on"><i class="iconfa-time"></i></span>
					</div>                  
				</tr>

				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Conference Number & PIN:<span class="conf_field" style="color:#F00;">*</span></td>
					<td width="70%"> 
							<input id="evnt_conference_number" onkeypress="return blockSpecialChar(event)"  name="evnt_conference_number" value=""  type="text" /> 
					</td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Conference URL:<span class="conf_field" style="color:#F00;">*</span></td>
					<td width="70%"> 
							<input id="evnt_conference_url" onkeypress="return blockSpecialChar(event)" name="evnt_conference_url" value="" type="text" /> 
					</td>
				</tr>
				<tr class="sitClass eventClass" style="display:none;">
					<td width="30%">Installation Record:<span style="color:#F00;">*</span></td>
					<td width="70%"> 
						<select id="evnt_add_installation_record" name="evnt_add_installation_record" style="height:24px; border-radius:0px;">
							<option value=""> - - - Add Installation Record - - -</option>
							<option value="No">No</option>
							<option value="Yes">Yes</option>
						</select>
						<input type="hidden" name="txtPassword" id="txtPassword" value="">
					</td>
				</tr>
				<tr class="siClass eventClass" style="display:none;">
					<td width="30%">3rd Party Line & Pin:</td>
					<td width="70%"> 
						<input id="evnt_ov_line" name="evnt_ov_line" value="" type="text" /> 
					</td>
				</tr>
				<tr class="siClass eventClass" style="display:none;">
					<td width="30%">Merchant Contact Number:</td>
					<td width="70%"> 
						<input id="evnt_merchant_line" name="evnt_merchant_line" value="" type="text" /> 
					</td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td colspan="100%";>
						<textarea rows="5" id="evnt_locationChecklistDetails" class="tinymce1" style="resize:none;width: 93% !important;"></textarea>
					</td>            
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Created By:</td>
					<td width="70%"><input id="evnt_CreatedBy" type="text" readonly value="" ></td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Created On:</td>
					<td width="70%"><input id="evnt_CreatedOn" type="text" readonly value="" ></td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Created Date & Time:</td>
					<td width="70%"><input id="evnt_CreatedDateTime" type="text" readonly value="" ></td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Last By:</td>
					<td width="70%"><input id="evnt_LastBy" type="text" readonly value="" ></td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Last On:</td>
					<td width="70%"><input id="evnt_LastOn" type="text" readonly value="" ></td>
				</tr>
				<tr class="siClass sitClass eventClass" style="display:none;">
					<td width="30%">Last Date & Time:</td>
					<td width="70%"><input id="evnt_LastDateTime" type="text" readonly value="" ></td>
				</tr>
			</table>
		</form>	
	</div>
	<div class="modal-footer" style="text-align: center;">
		<p class="stdformbutton">
          <button id="btnCancel" data-dismiss="modal" class="btn btn-primary">Cancel</button>          
          <button id="SubmitNewEvent" class="btn btn-primary">Submit</button>
        </p>
	</div>
</div>
<div id="setupInstAllationPopup" class="modal hide fade">   
	<div class="modal-header" >
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3 id="Common_popup_title3">Setup Installation & Training</h3>
	</div>
	<div class="modal-body widgetcontentfrm" style="max-height:610px !important;">
		<table width="100%">	
			<tr>
				<td width="30%">Installation Type:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<select id="SIpinstallationType" onChange="GetInstallationFields(this.value)" name="SIpinstallationType" style="height:24px; border-radius:0px;">
						<option value=""> - - -Select Installation Type - - -</option>
						<option value="Remote">Remote</option>
						<option value="Onsite">Onsite</option>
					</select>					
				</td>
			</tr>
			<tr class="RemoteTr" style="display:none;">
				<td width="30%">Date:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<input type="text" name="SIcalenderDate" id="SIcalenderDate" value="" >
				</td>
			</tr>
			<tr class="OnsiteTr" style="display:none;">
				<td width="30%">Start Date:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<input type="text" name="SIcalenderDatein" id="SIcalenderDatein" value="" >
				</td>
			</tr>		 
			<tr class="RemoteTr OnsiteTr" style="display:none;">
				<td width="30%">Start Time:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<div class="input-append bootstrap-timepicker" >
						<input id="SIcalenderTimein" name="SIcalenderTimein" value="09:00 AM" style="width:305px;" type="text" />
						<span class="add-on"><i class="iconfa-time"></i></span>
					</div> 
				</td>
			</tr>
			<tr class="OnsiteTr" style="display:none;">
				<td width="30%">End Date:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<input type="text" name="SIcalenderDateout" id="SIcalenderDateout" value="" >
				</td>
			</tr>
			<tr class="RemoteTr OnsiteTr" style="display:none;">
				<td width="30%">End Time:<span style="color:#F00;">*</span></td>
				<td width="70%">
					<div class="input-append bootstrap-timepicker" >
						<input id="SIcalenderTimeout" name="SIcalenderTimeout" value="00:00" style="width:305px;" type="text" />
						<span class="add-on"><i class="iconfa-time"></i></span>
					</div> 
				</td>
			</tr>
			<tr class="RemoteTr" style="display:none;">
				<td width="30%">Conference Number & PIN:<span class="conf_field" style="color:#F00;">*</span></td>
				<td width="70%"> 
						<input id="SIconference_number" onkeypress="return blockSpecialChar(event)"  name="SIconference_number" value=""  type="text" /> 
				</td>
			</tr>
			<tr class="RemoteTr" style="display:none;">
				<td width="30%">Conference URL:<span class="conf_field" style="color:#F00;">*</span></td>
				<td width="70%"> 
						<input id="SIconference_url" onkeypress="return blockSpecialChar(event)" name="SIconference_url" value="" type="text" /> 
				</td>
			</tr>
			<tr class="OnsiteTr" style="display:none;">
				<td width="30%">Softpoint Technician:<span style="color:#F00;">*</span></td>
				<td width="70%">				
					<select id="SItechnician_softpoint" name="SItechnician_softpoint" style="height:24px; border-radius:0px;">
						<option value=""> - - - Select Softpoint Technician - - -</option>
						<?php $getUSer = "SELECT id,name FROM users where status = 'Active'";
							$resUsr = mysql_query($getUSer);
							if($resUsr && mysql_num_rows($resUsr)>0){
								while($rowUsr= mysql_fetch_assoc($resUsr)){
									echo '<option value="'.$rowUsr['id'].'">'.$rowUsr['name'].'</option>';
								}
							}
						?>
					</select>					
				</td>
			</tr>
			<tr class="RemoteTr OnsiteTr" style="display:none;">
				<td width="30%">Installation Record:<span style="color:#F00;">*</span></td>
				<td width="70%"> 
					<select id="SIadd_installation_record" name="SIadd_installation_record" style="height:24px; border-radius:0px;">
						<option value=""> - - - Add Installation Record - - -</option>
						<option value="No">No</option>
						<option value="Yes">Yes</option>
					</select>
					<input type="hidden" name="txtPassword" id="txtPassword" value="">
				</td>
			</tr>
			<tr>
				<td width="30%">Created By:</td>
				<td width="70%"><input id="SIdetCreatedBy" type="text" readonly value="" ></td>
			</tr>
			<tr>
				<td width="30%">Created On:</td>
				<td width="70%"><input id="SIdetCreatedOn" type="text" readonly value="" ></td>
			</tr>
			<tr>
				<td width="30%">Created Date & Time:</td>
				<td width="70%"><input id="SIdetCreatedDateTime" type="text" readonly value="" ></td>
			</tr>
			<tr>
				<td width="30%">Last By:</td>
				<td width="70%"><input id="SIdetLastBy" type="text" readonly value="" ></td>
			</tr>
			<tr>
				<td width="30%">Last On:</td>
				<td width="70%"><input id="SIdetLastOn" type="text" readonly value="" ></td>
			</tr>
			<tr>
				<td width="30%">Last Date & Time:</td>
				<td width="70%"><input id="SIdetLastDateTime" type="text" readonly value="" ></td>
			</tr>			
		</table>
	</div>
	<div class="modal-footer" style="text-align: center;">
		<p class="stdformbutton">          
          <button id="btnCancel" onClick="CancelChecklsitDetails()" class="btn btn-primary">Cancel</button>
		  <button type="button" onClick="SubmitSetupTraining()" class="btn btn-primary">Submit</button>		  
        </p>
	</div>      
</div>
<div id="checkListDetailsPopup" class="modal hide fade">   
	<div class="modal-header" >
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3 id="Common_popup_title">Add/Edit Boarding Details</h3>
	</div>
	<div class="modal-body widgetcontentfrm" style="max-height:610px !important;">
    <input type="hidden" id="locbID" name="locbID" value="">
	<input type="hidden" id="locGID" name="locGID" value="">    
    <table width="100%">
		<tr class="omnivore_sync" style="display:none;">
			<td width="30%">Omnivore Sync General:</td>
			<td width="70%">
				<form id="frm_sync_general" name="frm_sync_general" action="<?=basename($_SERVER['PHP_SELF'])?>" method="post" style="display: inline;" onSubmit="return false;">                  
                  <input type="hidden" id="h_sync_general" name="h_sync_general" value="Yes" />                  
                  <button type="submit" id="sync_general" name="sync_general" class="btn btn-primary">Sync</button>
                </form>
			</td>
		</tr>
		<tr class="omnivore_sync" style="display:none;">
			<td width="30%">Omnivore Sync Menu:</td>
			<td width="70%">
				<form id="frm_sync_menu" name="frm_sync_menu" action="<?=basename($_SERVER['PHP_SELF'])?>" method="post" style="display: inline;" onSubmit="return false;">                  
                  <input type="hidden" id="h_sync_menu" name="h_sync_menu" value="Yes" />                  
                  <button type="submit" id="sync_menu" name="sync_menu" class="btn btn-primary">Sync</button>
                </form>
			</td>
		</tr>
		<tr class="aireus_sync" style="display:none;">
			<td width="30%">Aireus Sync:</td>
			<td width="70%">
				<form id="frm_sync_general_aireus" name="frm_sync_general_aireus" action="<?=basename($_SERVER['PHP_SELF'])?>" method="post" style="display: inline;" onSubmit="return false;">                  
                  <input type="hidden" id="h_sync_general_aireus" name="h_sync_general_aireus" value="Yes" />                  
                  <button type="submit" id="sync_general_aireus" name="sync_general_aireus" class="btn btn-primary">Sync</button>
                </form>
			</td>
		</tr>		
		<tr class="perform_int" style="display:none;">
			<td width="30%">Status:<span style="color:#F00;">*</span></td>
			<td width="70%">
				<select id="perform_status" onChange="changePerform_status(this.value)" name="perform_status" style="height:24px; border-radius:0px;">
					<option value=""> - - - Select Status - - -</option>
					<option value="Failed">Failed</option>
					<option value="Successful">Successful</option>
				</select>
			</td>
		</tr>
		<tr class="perform_int" style="display:none;">			
			<td width="30%">Users:<span style="color:#F00;">*</span></td>
			<td width="70%">
				<input type="hidden" id="perform_history" value="">
				<select id="perform_Users" onChange="changePerform_status()" name="perform_status" style="height:24px; border-radius:0px;">
					<option value=""> - - - Select User - - -</option>
					<?php $getUsers = mysql_query("SELECT id,name from users where status='Active' ORDER BY name");
					if($getUsers && mysql_num_rows($getUsers)>0){
						$selected = "";
						while($rowusers = mysql_fetch_assoc($getUsers)){
							$selected = ($_SESSION['userid'] == $rowusers['id']) ? "selected='selected'" : "";
							echo '<option '.$selected.' value="'.$rowusers['name'].'">'.$rowusers['name'].'</option>';		
						}
					} ?>

				</select>
			</td>
		</tr>
		<tr class="datetimeTr" style="display:none;">
			<td width="30%">Date:<span style="color:#F00;">*</span></td>
			<td width="70%">
			<input type="text" name="calenderDatein" id="calenderDatein" value="" >
			</td>
		</tr>		 
		<tr class="datetimeTr" style="display:none;">
			<td width="30%">Start Time:<span style="color:#F00;">*</span></td>
			<td width="70%">
				<div class="input-append bootstrap-timepicker" >
					<input id="calenderTimein" name="calenderTimein" style="width:305px;" value="09:00 AM" type="text" />
					<span class="add-on"><i class="iconfa-time"></i></span>
                </div> 
			</td>
		</tr>
		<tr class="datetimeTr" style="display:none;">
			<td width="30%">End Time:<span style="color:#F00;">*</span></td>
			<td width="70%">
				<div class="input-append bootstrap-timepicker" >
					<input id="calenderTimeout" name="calenderTimeout" style="width:305px;" value="00:00" type="text" />
					<span class="add-on"><i class="iconfa-time"></i></span>
                </div> 
			</td>
		</tr>	
		
		<tr class="datetimeTr" style="display:none;">
			<td width="30%">Conference Number & PIN:<span class="conf_field" style="color:#F00;">*</span></td>
			<td width="70%"> 
					<input id="conference_number" onkeypress="return blockSpecialChar(event)"  name="conference_number" value=""  type="text" /> 
			</td>
		</tr>
		
		
		
		<tr class="datetimeTr" style="display:none;">
			<td width="30%">Conference URL:<span class="conf_field" style="color:#F00;">*</span></td>
			<td width="70%"> 
					<input id="conference_url" onkeypress="return blockSpecialChar(event)" name="conference_url" value="" type="text" /> 
			</td>
		</tr>
		<tr class="datetimeTrInst" style="display:none;">
			<td width="30%">Softpoint Technician:<span style="color:#F00;">*</span></td>
			<td width="70%">				
				<select id="technician_softpoint" name="technician_softpoint" style="height:24px; border-radius:0px;">
					<option value=""> - - - Select Softpoint Technician - - -</option>
					<?php $getUSer = "SELECT id,name FROM users where status = 'Active'";
						$resUsr = mysql_query($getUSer);
						if($resUsr && mysql_num_rows($resUsr)>0){
							while($rowUsr= mysql_fetch_assoc($resUsr)){
								echo '<option value="'.$rowUsr['id'].'">'.$rowUsr['name'].'</option>';
							}
						}
					?>
				</select>					
			</td>
		</tr>
		<tr class="datetimeTrInst" style="display:none;">
			<td width="30%">Installation Record:<span style="color:#F00;">*</span></td>
			<td width="70%"> 
				<select id="add_installation_record" name="add_installation_record" style="height:24px; border-radius:0px;">
					<option value=""> - - - Add Installation Record - - -</option>
					<option value="No">No</option>
					<option value="Yes">Yes</option>
				</select>
				<input type="hidden" name="txtPassword" id="txtPassword" value="">
			</td>
		</tr>	
		<tr class="datetimeTr1" style="display:none;">
			<td width="30%">3rd Party Line & Pin:</td>
			<td width="70%"> 
				<input id="ov_line" name="ov_line" value="" type="text" /> 
			</td>
		</tr>
		<tr class="datetimeTr1" style="display:none;">
			<td width="30%">Merchant Contact Number:</td>
			<td width="70%"> 
				<input id="merchant_line" name="merchant_line" value="" type="text" /> 
			</td>
		</tr>	
    	<tr>
        	<td colspan="100%";>
            	<textarea rows="5" id="locationChecklistDetails" class="tinymce1" style="resize:none;width: 93% !important;"></textarea>
            </td>            
        </tr>
		<tr id="eula_docTr" style="disaply:none;">
			<td width="30%">Contract:</td>		
			<td width="70%">
				<span id="imagebox_eula" style="width:100%; display:block"></span>
			</td>
		</tr>
		<tr id="document_upload_div" style="disaply:none;">
        	<td width="30%">Document:</td>
            <td width="70%">
			<input type="button" onClick="OpenUploadPopu()" style="float:left" class="btn btn-primary" id="uploadDocument" value="Upload" >
			<span id="imagebox" style="width:100%; display:block"></span>
			<input type="hidden" name="digital_image_name" id="digital_image_name" value="">
          	<input type="hidden" name="digital_image_delete" id="digital_image_delete" value="N">
			</td>
        </tr>
		<tr class="ord_hrdwrField" style="display:none;">
        	<td width="30%">Needs Hardware:<span style="color:#F00;">*</span></td>
            <td width="70%">
			<select id="hardware_req" name="hardware_req" onChange="javascript:if(jQuery(this).val()=='Yes'){jQuery('.hardwareReq').show();}else{jQuery('.hardwareReq').hide();}" style="height:24px; border-radius:0px;">
				<option value=""> - - - Needs Hardware - - -</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</td>
        </tr>
		<tr class="ord_hrdwrField" style="display:none;">
        	<td width="30%">Hardware Type:<span class="hardwareReq" style="color:#F00;;display:none;">*</span></td>
            <td width="70%">
			<select id="hardware_type" name="hardware_type" style="height:24px; border-radius:0px">
				<option value=""> - - - Select Hardware Type - - -</option>
				<?php
					$typeq  = "SHOW COLUMNS FROM clover_devices WHERE Field = 'manufacturer'"; 
					$res = mysql_query($typeq);
					$trow = mysql_fetch_assoc($res);					
					$type = $trow['Type'];
					preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
					$enum = explode("','", $matches[1]);
					
					foreach($enum as $val){
						echo '<option value="'.$val.'">'.$val.'</option>';
					}
				?>
			</td>
        </tr>
		<tr class="ord_hrdwrField" style="display:none;">
        	<td width="30%">Hardware Purchaser:<span class="hardwareReq" style="color:#F00;display:none;">*</span></td>
            <td width="70%">			
				<select id="hardware_purchaser" name="hardware_purchaser" onChange="purhcaserChange(this.value)" style="height:24px; border-radius:0px;">
					<option value=""> - - - Select Hardware Purchaser - - -</option>
					<option value="SoftPoint">SoftPoint</option>					
					<?php
						$getPuch = mysql_fetch_assoc(mysql_query("SELECT hardware_purchaser FROM `location_boarding_checklist` WHERE location_id ='".$_REQUEST['location_id']."' AND global_checklist_id = 56"));
						$selectedPuch = $getPuch['hardware_purchaser'];
						$purchaserFound = false; 						
						if(count($all_reseller)>0){
							foreach($all_reseller as $val){									
								if($selectedPuch==$val['name']){
									$purchaserFound = true;
								}
								echo '<option value="'.$val['name'].'">'.$val['name'].'</option>';	
							}	
						}
						if(!$purchaserFound && $selectedPuch!=''){
							echo '<option value="'.$selectedPuch.'">'.$selectedPuch.'</option>';	
						}
					?>
					<option value="Add New">Add New</option>
				</select>
			</td>
        </tr>
		<tr id="newHardwarePurchaser" style="display:none;">
			<td width="30%">New Purchaser<span style="color:#F00;">*</span></td>
			<td width="70%"> 
				<input id="new_hardware_purchaser" name="new_hardware_purchaser" value="" type="text" /> 
			</td>
		</tr>
		<tr class="ord_hrdwrField" style="display:none;">
			<td width="30%">Transaction #:<span class="transactionReq" style="color:#F00;">*</span></td>
			<td width="70%"> 
				<input id="hardware_transaction_no" name="hardware_transaction_no" value="" type="text" /> 
			</td>
		</tr>
		
		<tr class="ord_hrdwrField" style="display:none;">
        	<td width="30%">Traking Number:</td>
            <td width="70%"><input id="traking_number" name="traking_number" type="text" value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Created By:</td>
            <td width="70%"><input id="detCreatedBy" type="text" readonly value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Created On:</td>
            <td width="70%"><input id="detCreatedOn" type="text" readonly value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Created Date & Time:</td>
            <td width="70%"><input id="detCreatedDateTime" type="text" readonly value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Last By:</td>
            <td width="70%"><input id="detLastBy" type="text" readonly value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Last On:</td>
            <td width="70%"><input id="detLastOn" type="text" readonly value="" ></td>
        </tr>
        <tr>
        	<td width="30%">Last Date & Time:</td>
            <td width="70%"><input id="detLastDateTime" type="text" readonly value="" ></td>
        </tr>
    </table>
            
	</div>
	<div class="modal-footer" style="text-align: center;">
		<p class="stdformbutton">          
          <button id="btnCancel" onClick="CancelChecklsitDetails()" class="btn btn-primary">Cancel</button>
          <button type="button" onClick="SubmitChecklsitDetails()" class="btn btn-primary">Submit</button>
        </p>
	</div>
      
</div>
</html>    
<script type="text/javascript">
	jQuery(function(){
		jQuery("[rel=tooltip]").tooltip({
	    	html:true,
	    	placement:'left'
	    });
	});
</script>

                           