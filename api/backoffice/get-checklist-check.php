<?php
ob_start("ob_gzhandler"); 
$token=md5('loc=' . $_GET['loc'] . '&list=' . $_GET['list'] . '&d=' . $_GET['d'] . 'backofficesecure12');
$panel = $_REQUEST['panel'];
//$curr_time = date('Y-m-d H:i:s', strtotime($_REQUEST['cur_time'])); //02-03-2017
//$today_date = date('Y-m-d', strtotime($_REQUEST['cur_time'])); //02-03-2017
$curr_time = date('Y-m-d H:i:s', $_REQUEST['cur_time']);
$today_date = date('Y-m-d', $_REQUEST['cur_time']);
$strCurrentDateTime_Split=explode(" ",$curr_time);
$day_field = 'dow_'.strtolower(date('D',strtotime($strCurrentDateTime_Split[0])));
if($day_field=="dow_thu"){
	$day_field = "dow_thr";
} 

if($token == $_GET['token2'] && $_GET['loc'] != ''){
    $return_database = 0;//stop db from echoing db out
    require_once("../../includes/connectdb.php");

    $lists = array();
    $dates = array();
    $details = array();
    $new_lists = array();
	$times = array(); 
	
	$debug = array();

    $location_id = mysql_real_escape_string($_GET['loc']);
	
	//$dept = $_GET['dept'];
	$dept = base64_decode( $_GET['dept'] );//htmlspecialchars($_GET['dept']);
	
	if($dept!= ""){
	
	$query1 = "SELECT location_checklist.status,location_checklist.checklist_id,location_checklist.checklist_name,location_checklist.starttime,location_checklist.endtime,employees.image as emp_image,CONCAT(employees.first_name,' ',employees.last_name) as emp_name,location_checklist.employee_id,
				CASE WHEN dow_fri='Yes' THEN 'Yes' ELSE 'NO' END as dietodo 
           		FROM location_checklist
				LEFT JOIN employees ON employees.id = location_checklist.employee_id
               WHERE location_checklist.status='Active' AND location_checklist.location_id='".$location_id."' AND location_checklist.department = '" . $dept . "'";
	}
	else{
	
	$query1 = "SELECT location_checklist.status,location_checklist.checklist_id,location_checklist.checklist_name,location_checklist.starttime,location_checklist.endtime,employees.image as emp_image,CONCAT(employees.first_name,' ',employees.last_name) as emp_name,location_checklist.employee_id,
           		CASE WHEN dow_fri='Yes' THEN 'Yes' ELSE 'NO' END as dietodo
				FROM location_checklist
				LEFT JOIN employees ON employees.id = location_checklist.employee_id
          		 WHERE location_checklist.status='Active' AND location_checklist.location_id='" . $location_id . "'";
	
	}
		   // AND checklist_id
             //   IN (SELECT distinct checklist_id FROM location_checklist_details WHERE status='Active')";
    $result1 = mysql_query($query1) or die(mysql_error());
    while($row1 = mysql_fetch_assoc($result1)){
		$list = $row1['checklist_id'];
				$time_query = "SELECT location_checklist.starttime,location_checklist.endtime from location_checklist 
				LEFT JOIN location_checklist_details as lcd ON lcd.checklist_id = location_checklist.checklist_id 
				 where $day_field='Yes' AND location_checklist.checklist_id ='$list' GROUP BY location_checklist.checklist_id"; //AND location_checklist.starttime < '".$cur_time."' AND location_checklist.endtime > '".$cur_time."'
				 $debug[]['time_query']= $time_query;
				 
				 if($_REQUEST['debug']=="1"){
				 	echo $time_query;
					
					
					echo "<br>CT:".date('Y-m-d H:i:s', ($_REQUEST['cur_time']));
					echo "<br>CD:".date('Y-m-d', ($_REQUEST['cur_time']));
				 }
				 
			$time_res = mysql_query($time_query);
			if(mysql_num_rows($time_res)>0){
				$row_time = mysql_fetch_assoc($time_res);
				$start_date = $today_date.' '.$row_time['starttime'];
				$end_dt = $today_date.' '.$row_time['endtime'];
				$curr_dt = $curr_time;
				if($row_time['starttime']>$row_time['endtime']){
					$curr_sdate = date('Y-m-d').' 23:59:59';
					$curr_edate = date('Y-m-d').' 00:00:00';
					if(($start_date < $curr_dt && $curr_dt < $curr_sdate) || ($end_dt > $curr_dt && $curr_dt > $curr_edate)  ){//
						$res = '1';
					}else{
						$res ='0' ;
					}
				}else{
					if($_REQUEST['debug']=="1"){
						echo '<br/>...start_date:'. $start_date .' curr_dt:'. $curr_dt .' end_dt:'.  $end_dt;
					}
					if($start_date < $curr_dt && $end_dt > $curr_dt ){
						$res = '1';
					}else{
						$res ='0' ;
					}
					
				}
				
				
				if($_REQUEST['debug']=="1"){
					echo "<br> ========= <br>";
					echo "Cur Dt: ".$curr_dt."==>".$start_date."==>".$end_dt;
				}
				
				
			}else{
			$res = '0';
			}
			
			$row1['times'] =$res; 
				
			//$row1['status']=$res;
        $lists[] = $row1;
    }

    	if ($_GET['list'] != '') {
		
        $list = mysql_real_escape_string($_GET['list']);
			$time_query = "SELECT location_checklist.starttime,location_checklist.endtime from location_checklist LEFT JOIN location_checklist_details as lcd ON lcd.checklist_id = location_checklist.checklist_id  where $day_field='Yes' AND location_checklist.checklist_id ='$list' GROUP BY location_checklist.checklist_id"; //AND location_checklist.starttime < '".$cur_time."' AND location_checklist.endtime > '".$cur_time."'
			$time_res = mysql_query($time_query);
			if(mysql_num_rows($time_res)>0){
				$row_time = mysql_fetch_assoc($time_res);
				$start_date = $today_date.' '.$row_time['starttime'];
				$end_dt = $today_date.' '.$row_time['endtime'];
				$curr_dt = $curr_time;
				if($row_time['starttime']>$row_time['endtime']){
					$curr_sdate = date('Y-m-d').' 23:59:59';
					$curr_edate = date('Y-m-d').' 00:00:00';
					if(($start_date < $curr_dt && $curr_dt < $curr_sdate) || ($end_dt > $curr_dt && $curr_dt > $curr_edate)  ){//
						$res = '1';
					}else{
						$res ='0' ;
					}
				}else{
					if($start_date < $curr_dt && $end_dt > $curr_dt ){
						$res = '1';
					}else{
						$res ='0' ;
					}
					
				}
				
				
				//$res = $start_date. '=>'. $curr_dt .'==>'.  $end_dt.'=>'.$curr_edate.'=>>>'.$res; 
				
				//$curr_time = date('Y-m-d').' '.$cur_time;
				
				
				
			}else{
			$res = '0';
			}
			
			$times[] =$res; 
		/*if($panel == "Corp"){
			$loc_condition = "AND lcc.location_id=".$location_id;
		}
		else { $loc_condition = ""; }*/
		$loc_condition = "AND lcc.location_id=".$location_id;
        $query2 = "SELECT DATE_FORMAT(lcc.datetime,'%Y-%m-%d') as date,DATE_FORMAT(lcc.datetime,'%Y-%m-%d %H:%i') as datetime,DATE_FORMAT(lcc.datetime,'%Y-%m-%d %H:%i:%s') as datetime_full,CONCAT(e.first_name,' ',e.last_name) as created_by, lcc.created_by as created_by_id
               FROM location_checklist_check as lcc
			   LEFT JOIN employees as e ON e.id = lcc.created_by
               WHERE lcc.checklist_id='$list' ".$loc_condition."
               GROUP BY datetime
               ORDER BY date DESC";
        $result2 = mysql_query($query2) or die(mysql_error());
        while($row2 = mysql_fetch_assoc($result2)){
            $dates[] = $row2;
        }

        $query4 = "SELECT *
               FROM location_checklist_details
               WHERE checklist_id='$list' AND status='Active' AND location_id=" . $location_id . "
               ORDER BY priority ASC";
        $result4 = mysql_query($query4) or die(mysql_error());
        while($row4 = mysql_fetch_assoc($result4)){
            $new_lists[] = $row4;
        }

        if($_REQUEST['d'] != ''){
		   $fd = date('Y-m-d H:i:s',$_REQUEST['fd']);
           //$date = "2014-10-14 00:34:10";//$_REQUEST['d'];			
              /*$query3 = "SELECT lcc.created_by,lcd.priority,lcc.created_datetime,lcd.description,lcd.instructions,lcd.required,lcc.answer, lcd.type
                   FROM location_checklist_check lcc
                   INNER JOIN location_checklist_details lcd ON lcc.checklistdetails_id=lcd.checklistdetails_id
                   WHERE lcc.datetime ='$fd' AND lcd.checklist_id='$list' AND lcd.status='Active'
                   ORDER BY lcd.priority asc, lcc.created_datetime DESC";		*/
				   	
			 $query3 = "SELECT tbl.checklistchecks_id,tbl.status,tbl.created_by,tbl.priority,tbl.created_datetime,tbl.description,tbl.instructions,tbl.required,tbl.answer, tbl.type FROM(
						  SELECT lcc.created_by,lcd.priority,lcc.created_datetime,lcd.description,lcd.instructions,lcd.required,lcc.answer,lcc.status,lcc.checklistchecks_id ,lcd.type
									   FROM location_checklist_check lcc
									   INNER JOIN location_checklist_details lcd ON lcc.checklistdetails_id=lcd.checklistdetails_id
									   WHERE lcc.datetime ='$fd' AND lcd.checklist_id='$list' AND lcd.status='Active' AND lcd.type<>'Category'
									   
						UNION ALL
						SELECT    lcc.created_by,lcd.priority,lcc.created_datetime,lcd.description,lcd.instructions,lcd.required,lcc.answer,lcc.status,lcc.checklistchecks_id, lcd.type                   
						   FROM location_checklist_details lcd
								   LEFT JOIN location_checklist_check lcc  ON lcc.checklistdetails_id=lcd.checklistdetails_id
								   WHERE lcd.checklist_id='$list' AND lcd.status='Active' AND lcd.type='Category' GROUP BY  lcd.checklistdetails_id
				) as tbl ORDER BY tbl.priority asc, tbl.created_datetime DESC";	   
            $result3 = mysql_query($query3) or die(mysql_error());
			
            while($row3 = mysql_fetch_assoc($result3)){
                $details[] =$row3;
            }
        }
    }
	$post = array();
    $response = array(
        'status' => 'success',
        'response' => array(
            'lists' => $lists,
            'dates' => $dates,
            'details' => $details,
            'new_lists' => $new_lists,
			'times' => $times,
			'post'=> $_REQUEST
        )
    );
	if($_REQUEST['debug'] == '1') {
		$response['debug'] = $debug;
	}

    echo json_encode($response);
}else{
    $response = array(
        'status' => 'fail',
        'response' => array(
            'lists' => '',
            'dates' => '',
            'details' => '',
            'new_lists' => '',
			'times' => ''
        )
    );

    echo json_encode($response);
}